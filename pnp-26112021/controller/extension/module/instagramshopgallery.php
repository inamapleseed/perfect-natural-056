<?php
class ControllerExtensionModuleInstagramShopGallery extends Controller
{
    private $data   = array();
    private $module = array();
    private $error  = array();
    private $igBasicApi;
    private $igGraphApi;

    public function __construct($registry)
    {
        parent::__construct($registry);
        $this->config->load('isenselabs/instagramshopgallery');
        $this->module = $this->config->get('instagramshopgallery');
        
        $this->load->model('setting/setting');
        $this->load->model($this->module['path']);
        
        // === ===

        $this->module['token'] = $this->session->data['token'];
        $this->module['model'] = $this->{$this->module['model']};

        if (isset($this->request->post['store_id'])) {
            $this->module['store_id'] = (int)$this->request->post['store_id'];
        } elseif (isset($this->request->get['store_id'])) {
            $this->module['store_id'] = (int)$this->request->get['store_id'];
        }

        // Module setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->module['store_id']);
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );

        // Template variables
        $this->data['store_id']    = $this->module['store_id'];
        $this->data['setting']     = $this->module['setting'];
        $this->data['token']       = $this->module['token'];
        $this->data['link_ext']    = $this->url->link($this->module['ext_link'], 'token=' . $this->module['token'] . $this->module['ext_type'], 'SSL');
        $this->data['link_module'] = $this->url->link($this->module['path'], 'store_id=' . $this->module['store_id'] . '&token=' . $this->module['token'], 'SSL');
        $this->data['module']      = array(
            'name'  => $this->module['name'],
            'path'  => $this->module['path']
        );

        $storeUrl = HTTPS_CATALOG;
        if ($this->module['store_id'] != 0) {
            $this->load->model('setting/store');
            $store =  $this->model_setting_store->getStore($this->module['store_id']);
            if (isset($store['url'])) {
                $storeUrl = $store['url'];
            }
        }

        $this->igBasicApi = new \vendor\isenselabs\instagramshopgallery\basicapi(array(
            'appId'       => $this->module['setting']['basic_app_id'],
            'appSecret'   => $this->module['setting']['basic_app_secret'],
            'redirectUri' => $storeUrl . 'catalog/controller/extension/module/isgcallback.php',
            'accessToken' => $this->module['setting']['basic_access_token']
        ));

        $this->igGraphApi = new \vendor\isenselabs\instagramshopgallery\graphapi(array(
            'appId'       => $this->module['setting']['graph_app_id'],
            'appSecret'   => $this->module['setting']['graph_app_secret'],
            'accessToken' => $this->module['setting']['graph_access_token']
        ));

        $this->session->data['isg_basic_api'] = array(
            'oauth_uri'    => $storeUrl . 'catalog/controller/extension/module/isgcallback.php',
            'redirect_url' => $this->url->link($this->module['path'], 'store_id=' . $this->module['store_id'], 'SSL'),
        );

        $this->data = array_replace_recursive(
            $this->data,
            $this->load->language($this->module['path'], $this->module['name'])
        );
    }

    public function index()
    {
        $this->load->model('setting/store');
        $this->load->model('tool/image');
        $this->load->model('localisation/language');

        $this->module['model']->update();

        $this->document->setTitle($this->module['title']);

        $data      = $this->data;
        $languages = $this->model_localisation_language->getLanguages();

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            if (!empty($_POST['OaXRyb1BhY2sgLSBDb21'])) {
                $this->request->post[$this->module['code'] . '_setting']['LicensedOn'] = $_POST['OaXRyb1BhY2sgLSBDb21'];
            }
            if (!empty($_POST['cHRpbWl6YXRpb24ef4fe'])) {
                $this->request->post[$this->module['code'] . '_setting']['License'] = json_decode(base64_decode($_POST['cHRpbWl6YXRpb24ef4fe']), true);
            }

            $post = array_replace_recursive(
                $this->request->post,
                array($this->module['code'] . '_status' => $this->request->post[$this->module['code'] . '_setting']['status'])
            );

            $this->model_setting_setting->editSetting($this->module['code'], $post, $this->module['store_id']);

            $this->session->data['success'] = $data['text_success'];
            $this->response->redirect($data['link_module']);
        }

        if (!ini_get('allow_url_fopen')) {
            $this->error['warning'] = $this->data['error_url_fopen'];
        }

        // === ===

        $data['heading_title']  = $this->module['title'] . ' ' . $this->module['version'];

        $data['breadcrumbs']    = array();
        $data['breadcrumbs'][]  = array(
            'text'      => $data['text_home'],
            'href'      => $this->url->link('common/dashboard', 'token=' . $this->module['token'], 'SSL')
        );
        $data['breadcrumbs'][]  = array(
            'text'      => $data['text_modules'],
            'href'      => $data['link_ext']
        );
        $data['breadcrumbs'][]  = array(
            'text'      => $this->module['title'],
            'href'      => $data['link_module']
        );

        $data['error_warning'] = '';
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        }
        $data['success'] = '';
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        // === ===

        $data['action']         = $data['link_module'];
        $data['cancel']         = $data['link_ext'];

        $data['site_url']       = HTTPS_CATALOG;
        $data['module_setting'] = $this->module['code'] . '_setting';

        $store_default = array(
            'store_id' => '0',
            'name'     => $this->config->get('config_name') . ' <b>' . $data['text_default'] . '</b>',
            'url'      => HTTPS_CATALOG
        );
        $data['store']          = $this->module['store_id'] != 0 ? $this->model_setting_store->getStore($this->module['store_id']) : $store_default;
        $data['stores']         = array_merge(
            array(0 => $store_default),
            $this->model_setting_store->getStores()
        );

        // Tab Settings
        $data['basic_redirect_uri'] = $this->igBasicApi->redirectUri;
        $data['basic_access_token_button'] = '';
        if (!$data['setting']['basic_access_token'] && $data['setting']['basic_app_id'] && $data['setting']['basic_app_secret']) {
            $data['basic_access_token_button'] = $this->igBasicApi->getUserAccessUrl($this->module['token']);
        }
        $data['graph_access_token_button'] = false;
        if (!$data['setting']['graph_access_token'] && $data['setting']['graph_app_id'] && $data['setting']['graph_app_secret']) {
            $data['graph_access_token_button'] = true;
        }
        $data['extend_token'] = $this->url->link($this->module['path'] . '/refreshToken', 'store_id=' . $this->module['store_id'] . '&igAccessToken=' . $data['setting']['basic_access_token'] . '&token=' . $this->module['token'], 'SSL');
        $data['is_migrate'] = $this->module['model']->haveMigrateData();

        // Tab Module
        $data['languages']  = $languages;
        foreach ($data['languages'] as $key => $value) {
            $data['languages'][$key]['flag_url'] = 'language/'.$data['languages'][$key]['code'].'/'.$data['languages'][$key]['code'].'.png';
        }

        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        $data['setting']['module']['extra_thumb'] = $data['no_image'];
        if ($data['setting']['module']['extra_image'] && is_file(DIR_IMAGE . $data['setting']['module']['extra_image'])) {
            $data['setting']['module']['extra_thumb'] = $this->model_tool_image->resize($data['setting']['module']['extra_image'], 100, 100);
        }

        // Tab Page
        $data['setting']['page']['banner_thumb'] = $data['no_image'];
        if ($data['setting']['page']['banner'] && is_file(DIR_IMAGE . $data['setting']['page']['banner'])) {
            $data['setting']['page']['banner_thumb'] = $this->model_tool_image->resize($data['setting']['page']['banner'], 100, 100);
        }

        // Tab Support
        $data['unlincensedHtml']    = empty($this->module['setting']['LicensedOn']) ? base64_decode('ICAgIDxkaXYgY2xhc3M9ImFsZXJ0IGFsZXJ0LWRhbmdlciBmYWRlIGluIj4NCiAgICAgICAgPGJ1dHRvbiB0eXBlPSJidXR0b24iIGNsYXNzPSJjbG9zZSIgZGF0YS1kaXNtaXNzPSJhbGVydCIgYXJpYS1oaWRkZW49InRydWUiPsOXPC9idXR0b24+DQogICAgICAgIDxoND5XYXJuaW5nISBVbmxpY2Vuc2VkIHZlcnNpb24gb2YgdGhlIG1vZHVsZSE8L2g0Pg0KICAgICAgICA8cD5Zb3UgYXJlIHJ1bm5pbmcgYW4gdW5saWNlbnNlZCB2ZXJzaW9uIG9mIHRoaXMgbW9kdWxlISBZb3UgbmVlZCB0byBlbnRlciB5b3VyIGxpY2Vuc2UgY29kZSB0byBlbnN1cmUgcHJvcGVyIGZ1bmN0aW9uaW5nLCBhY2Nlc3MgdG8gc3VwcG9ydCBhbmQgdXBkYXRlcy48L3A+PGRpdiBzdHlsZT0iaGVpZ2h0OjVweDsiPjwvZGl2Pg0KICAgICAgICA8YSBjbGFzcz0iYnRuIGJ0bi1kYW5nZXIiIGhyZWY9ImphdmFzY3JpcHQ6dm9pZCgwKSIgb25jbGljaz0iJCgnYVtocmVmPSNpc2Vuc2Vfc3VwcG9ydF0nKS50cmlnZ2VyKCdjbGljaycpIj5FbnRlciB5b3VyIGxpY2Vuc2UgY29kZTwvYT4NCiAgICA8L2Rpdj4=') : '';
        $data['licenseDataBase64']  = !empty($this->module['setting']['License']) ? base64_encode(json_encode($this->module['setting']['License'])) : '';
        $data['supportTicketLink']  = 'http://isenselabs.com/tickets/open/' . base64_encode('Support Request').'/'.base64_encode('475').'/'. base64_encode($_SERVER['SERVER_NAME']);

        //===

        $data['tab_main_setting']   = $this->load->view($this->module['path'] .'/tab_setting', $data);
        $data['tab_module_setting'] = $this->load->view($this->module['path'] .'/tab_module', $data);
        $data['tab_page_setting']   = $this->load->view($this->module['path'] .'/tab_page', $data);
        $data['tab_support']        = $this->load->view($this->module['path'] .'/tab_support', $data);

        $data['header']             = $this->load->controller('common/header');
        $data['column_left']        = $this->load->controller('common/column_left');
        $data['footer']             = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view($this->module['path'], $data));
    }

    /**
     * Fetch media from source: instagram or database
     *
     * @return string   JSON format
     */
    public function fetch()
    {
        $limit = 18;
        $data  = $this->data;
        $param = array_replace_recursive(array(
            'store_id' => $this->module['store_id'],
            'api'      => 'basic',
            'source'   => 'instagram',
            'hashtag'  => '',
            'page'     => '1'
        ), $this->request->post);
        $response = array(
            'error'     => false,
            'output'    => '',
            'page'      => array(
                'next' => '',
                'show' => false,
            ),
            'results'   => array(),
        );

        // Reset media list result on base Fetch
        if ($param['page'] == '1') {
            $this->session->data['isg_basic_media'] = array();
            $this->session->data['isg_graph_media'] = array();
        }

        $results = array(
            'data'      => array(),
            'errorMsg'  => ''
        );

        if ($param['source'] == 'instagram') {
            $param['page'] = $param['page'] != 1 ? $param['page'] : '';

            if ($param['api'] == 'basic' && $this->module['setting']['basic_access_token']) {
                if (!empty($this->session->data['isg_basic_media'][$param['page']]['data'])) {
                    $results = $this->session->data['isg_basic_media'][$param['page']];
                } else {
                    $results = $this->session->data['isg_basic_media'][$param['page']] = $this->igBasicApi->getUserMedia($limit, $param['page']);
                }

                $moreHalfResult = false;
                if (!empty($results['data']) && (count($results['data']) > $limit/2)) {
                    $moreHalfResult = true;
                }

                if ($moreHalfResult && isset($results['paging']['next'])) {
                    $response['page'] = array(
                        'next' => $results['paging']['cursors']['after'],
                        'show' => true,
                    );
                }
            } elseif ($param['api'] == 'graph' && $this->module['setting']['graph_access_token']) {
                $ig_user_id = $this->module['setting']['graph_meta']['ig_user_id'];

                if (!empty($this->session->data['isg_graph_hashtag'][$param['hashtag']])) {
                    $nodeId = $this->session->data['isg_graph_hashtag'][$param['hashtag']];
                } else {
                    $nodeId = $this->session->data['isg_graph_hashtag'][$param['hashtag']] = $this->igGraphApi->getHashtagId($param['hashtag'], $ig_user_id);
                }

                if (!empty($this->session->data['isg_graph_media'][$param['page']]['data'])) {
                    $results = $this->session->data['isg_graph_media'][$param['page']];
                } else {
                    $results = $this->session->data['isg_graph_media'][$param['page']] = $this->igGraphApi->hashtagSearch($nodeId, $ig_user_id, $limit, $param['page']);
                }

                $moreHalfResult = false;
                if (!empty($results['data']) && (count($results['data']) > $limit/2)) {
                    $moreHalfResult = true;
                }

                if ($moreHalfResult && isset($results['paging']['next'])) {
                    $response['page'] = array(
                        'next' => $results['paging']['cursors']['after'],
                        'show' => true,
                    );
                }
            } else {
                $results['errorMsg'] = $data['error_access_token'];
            }
        }

        if ($param['source'] == 'database') {
            $this->module['model']->deleteMedia(''); // Cleanup

            $mediaItems = $this->module['model']->getMedias($param['page'], $param['store_id'], $limit);

            if (!empty($mediaItems)) {
                $results['data'] = $mediaItems;

                $totalMedia = $this->module['model']->getMediasTotal($param['store_id']);
                $mediaCount = count($mediaItems);

                $response['page'] = array(
                    'next' => $param['page'] + 1,
                    'show' => ($limit * $param['page']) < $totalMedia ? true : false,
                );
            } else {
                $results['errorMsg'] = $data['error_empty_database'];
            }
        }

        // Media preparation
        if (!empty($results['data']) && empty($results['errorMsg'])) {
            $this->load->model('tool/image');

            $no_image = $this->model_tool_image->resize('no_image.png', 320, 320);
            $allMediaMeta = $this->module['model']->getMediaSummary($param['store_id']);

            $data['medias'] = array();
            foreach ($results['data'] as $media) {
                if ($param['source'] == 'instagram') {
                    if (!isset($media['media_url']) || strtolower($media['media_type']) == 'video') {
                        continue;
                    }
                }

                if (!empty($media['permalink']) && empty($media['shortcode'])) {
                    $permalink  = trim($media['permalink'], '/');
                    $shortcodes = explode('/', $permalink);
                    $media['shortcode'] = end($shortcodes);
                }

                $media['media_image'] = $no_image;
                if ($this->module['setting']['validate_media'] && $this->isValidMedia($media['shortcode'] . '.cdn', $media['media_url'])) {
                    $media['media_image'] = $media['media_url'];
                } elseif (!$this->module['setting']['validate_media']) {
                    $media['media_image'] = $media['media_url'];
                }

                $media['caption']       = isset($media['caption']) ? htmlspecialchars($media['caption'], ENT_QUOTES, 'UTF-8') : '';
                $media['approve']       = !empty($allMediaMeta[$media['shortcode']]['approve']) ? 1 : 0;
                $media['product_count'] = !empty($allMediaMeta[$media['shortcode']]['product_count']) ? $allMediaMeta[$media['shortcode']]['product_count'] : 0;
                $media['remove']        = !empty($allMediaMeta[$media['shortcode']]['shortcode']) ? true : false;
                $media['resave']        = $media['remove'] && !empty($media['id']) && empty($allMediaMeta[$media['shortcode']]['media_id']) ? true : false;
                $media['legacy']        = $media['remove'] && empty($media['id']) && empty($allMediaMeta[$media['shortcode']]['media_id']) ? true : false;

                $data['medias'][$media['shortcode']] = $media;
                $data['medias'][$media['shortcode']]['data'] = json_encode($media);
            }

            $response['error']   = false;
            $response['results'] = $results;
            $response['output']  = $this->load->view($this->module['path'] .'/tab_photos_items', $data);
        }

        if (!empty($results['errorMsg'])) {
            $response['error']  = true;
            $response['output'] = $results['errorMsg'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    /**
     * Show media meta info, approval option and related products
     *
     * @return string   Template output used inside modal
     */
    public function modalForm()
    {
        $post   = $this->request->post;
        $data   = $this->data;
        $output = '<div style="padding:100px;text-align:center">' . $this->data['error_general'] . '</div>';

        if (isset($post['shortcode'])) {
            $post['media_id'] = $post['id'];

            $data['token'] = $this->module['token'];
            $data['media'] = $this->module['model']->getMedia($post['shortcode'], $post);

            $data['media']['date'] = !empty($post['timestamp']) ? $this->timeElapsedString($post['timestamp']) : '';
            $data['media']['media_image'] = $data['media']['media_url'];

            // ===
            $data['mediaData'] = htmlspecialchars(json_encode($data['media']), ENT_QUOTES, 'UTF-8');

            $this->load->model('setting/store');
            $store_default = array(
                'store_id' => '0',
                'name'     => $this->config->get('config_name'),
                'url'      => HTTPS_CATALOG
            );
            $data['stores'] = array_merge(
                array(0 => $store_default),
                $this->model_setting_store->getStores()
            );

            $output = $this->load->view($this->module['path'] .'/tab_photos_modal', $data);
        }

        $this->response->setOutput($output);
    }

    // Saving media detail
    public function modalSave()
    {
        $param = $this->request->post;
        $param['data'] = json_decode(htmlspecialchars_decode($param['data']), true);

        $response = array(
            'shortcode' => $param['data']['shortcode'],
            'approve'   => $param['approve'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
            'related_products' => isset($param['related_products']) ? count($param['related_products']) : 0
        );

        $this->module['model']->updateMedia($param);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    // Delete media from database
    public function remove()
    {
        $param = $this->request->post;
        $response = array(
            'shortcode' => $param['shortcode'],
            'removed'   => $param['source'] == 'database' ? true : false
        );

        $this->module['model']->deleteMedia($param['shortcode']);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    /**
     * Instagram Basic API extend token expire
     */
    public function refreshToken()
    {
        $data  = $this->data;
        $param = $this->request->get;

        if ($param['igAccessToken'] && $this->module['setting']['basic_access_token']) {
            $result = $this->igBasicApi->refreshToken($param['igAccessToken']);

            if (isset($result['access_token'])) {
                $setting = $this->module['setting'];
                $setting['basic_access_token'] = $result['access_token'];
                $setting['basic_token_expire'] = date('Y-m-d H:i:s', time() + (int)$result['expires_in']);

                $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$this->data['store_id'] . "' AND `code` = '" . $this->db->escape($this->module['code']) . "'");
                $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$this->data['store_id'] . "', `code` = '" . $this->db->escape($this->module['code']) . "', `key` = '" . $this->db->escape($this->module['code'] . '_setting') . "', `value` = '" . $this->db->escape(json_encode($setting, true)) . "', serialized = '1'");

                $this->session->data['success'] = $data['text_success_refresh_token'];
            } elseif (isset($result['error_message'])) {
                $this->log->write('InstagramShopGallery ' . $result['error_type'] . ': ' . $result['error_message']);
            }
        }

        $this->response->redirect($data['link_module']);
    }

    /**
     * Instagram Graph API access token
     */
    public function graphApiCallback()
    {
        $param = $this->request->post;
        $response = array(
            'reload' => false,
        );

        if (isset($param['access_token'])) {
            $this->igGraphApi->setConfig('accessToken', $param['access_token']);

            $setting = $this->module['setting'];
            $setting['graph_access_token'] = $param['access_token'];
            $setting['graph_token_expire'] = isset($param['expires_in']) ? date('Y-m-d H:i:s', time() + (int)$param['expires_in']) : '';
            $setting['graph_meta'] = $this->igGraphApi->getProfile();

            $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$this->data['store_id'] . "' AND `code` = '" . $this->db->escape($this->module['code']) . "'");
            $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$this->data['store_id'] . "', `code` = '" . $this->db->escape($this->module['code']) . "', `key` = '" . $this->db->escape($this->module['code'] . '_setting') . "', `value` = '" . $this->db->escape(json_encode($setting, true)) . "', serialized = '1'");

            $response['reload'] = true;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($response));
    }

    /**
     * Check if valid instagram media
     *
     * @param  string  $shortcode As cache key
     * @param  string  $media     Media url
     * @param  string  $status    200 for direct image, 302 for media found
     *
     * @return boolean
     */
    private function isValidMedia($shortcode, $media, $status = '200')
    {
        if (!$shortcode || filter_var($media, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        if (!empty($this->session->data['isg_valid_media'][$shortcode . $media])) {
            $headers = $this->session->data['isg_valid_media'][$shortcode . $media];
        } else {
            $headers = $this->cache->get('isg_media.' . $shortcode);
        }

        if (!$headers) {
            $headers = @get_headers($media);
            $this->cache->set('isg_media.' . $shortcode, $headers);
            $this->session->data['isg_valid_media'][$shortcode . $media] = $headers;
        }

        if (!empty($headers) && strpos($headers[0], $status) !== false) {
            return true;
        }

        return false;
    }

    private function validateForm()
    {
        if (!$this->user->hasPermission('modify', $this->module['path'])) {
            $this->error['warning'] = $this->data['error_permission'];
        }
        return !$this->error;
    }

    /**
     * Convert datetime to elapsed time lang
     * @link https://stackoverflow.com/a/18602474
     */
    private function timeElapsedString($datetime, $level = 2)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        $string = array_slice($string, 0, $level);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function install()
    {
        $this->module['model']->install();
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        $this->model_setting_setting->deleteSetting($this->module['name'], 0);

        $stores = $this->model_setting_store->getStores();
        foreach ($stores as $store) {
            $this->model_setting_setting->deleteSetting($this->module['name'], $store['store_id']);
        }

        $this->module['model']->uninstall();
    }
}
