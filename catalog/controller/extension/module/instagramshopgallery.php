<?php
class ControllerExtensionModuleInstagramShopGallery extends Controller
{
    private $data = array();
    private $module = array();

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->config->load('isenselabs/instagramshopgallery');
        $this->module = $this->config->get('instagramshopgallery');

        $this->load->model('setting/setting');
        $this->load->model($this->module['path']);
        $this->module['model'] = $this->{$this->module['model']};

        // Module setting
        $setting = $this->model_setting_setting->getSetting($this->module['code'], $this->config->get('config_store_id'));
        $this->module['setting'] = array_replace_recursive(
            $this->module['setting'],
            !empty($setting[$this->module['code'] . '_setting']) ? $setting[$this->module['code'] . '_setting'] : array()
        );

        // Template variables
        $this->data['store_id'] = $this->config->get('config_store_id');
        $this->data['lang_id']  = $this->config->get('config_language_id');
        $this->data['setting']  = $this->module['setting'];
        $this->data['module']   = array(
            'name'  => $this->module['name'],
            'path'  => $this->module['path']
        );

        $this->data = array_replace_recursive(
            $this->data,
            $this->load->language($this->module['path'], $this->module['name'])
        );
    }

    // Module
    public function index()
    {
        if (!$this->module['setting']['status'] || !$this->module['setting']['module']['status']) {
            return;
        }

        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addStyle('catalog/view/javascript/' . $this->module['name'] . '/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/' . $this->module['name'] . '/swiper/css/opencart.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '.css?v=' . $this->module['version']);

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addScript('catalog/view/javascript/' . $this->module['name'] . '/swiper/js/swiper.jquery.js');

        $data = $this->data;

        $data['theme']      = $this->config->get('config_theme');
        $data['custom_css'] = trim(htmlspecialchars_decode($this->module['setting']['module']['custom_css']));

        if ($this->config->get('theme_default_directory') == 'journal2') {
            $data['theme'] = 'journal2';
            $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '_bootstrap.css?v=' . $this->module['version']);
        }

        return $this->load->view($this->module['path'] . '/module', $data);
    }

    public function page()
    {
        if (!$this->module['setting']['status'] || !$this->module['setting']['page']['status']) {
            $this->response->redirect($this->url->link('common/home', '', true));
        }

        $data       = $this->data;
        $lang_id    = $this->config->get('config_language_id');
        $meta_title = $this->module['setting']['page']['meta_title'][$lang_id] ? $this->module['setting']['page']['meta_title'][$lang_id] : $this->module['setting']['page']['title'][$lang_id];

        $this->document->setTitle($meta_title);
        $this->document->setDescription($this->module['setting']['page']['meta_desc'][$lang_id]);
        $this->document->setKeywords($this->module['setting']['page']['meta_keyword'][$lang_id]);

        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        $this->document->addStyle('catalog/view/javascript/' . $this->module['name'] . '/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/' . $this->module['name'] . '/swiper/css/opencart.css');
        $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '.css?v=' . $this->module['version']);

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addScript('catalog/view/javascript/' . $this->module['name'] . '/swiper/js/swiper.jquery.js');

        $page_url = HTTPS_SERVER . 'index.php?route=' . $this->module['path'] . '/page';
        if ($this->config->get('config_seo_url') && !empty($this->module['setting']['page']['seo_url'][$lang_id])) {
            $page_url = HTTPS_SERVER . $this->module['setting']['page']['seo_url'][$lang_id];
        }

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', '', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->module['setting']['page']['title'][$lang_id],
            'href' => $page_url
        );

        // === ===

        $data['theme']          = $this->config->get('config_theme');
        $data['banner']         = $this->module['setting']['page']['banner'] ? HTTPS_SERVER . 'image/' . $this->module['setting']['page']['banner'] : '';
        $data['custom_css']     = trim(htmlspecialchars_decode($this->module['setting']['page']['custom_css']));

        if ($this->config->get('theme_default_directory') == 'journal2') {
            $data['theme'] = 'journal2';
            $this->document->addStyle('catalog/view/theme/default/stylesheet/' . $this->module['name'] . '_bootstrap.css?v=' . $this->module['version']);
        }

        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view($this->module['path'] . '/page', $data));
    }


    public function fetch()
    {
        $param = $this->request->post;
        $data  = array(
            'photos'  => array(
                'items' => array(),
                'page'  => array()
            ),
            'param'   => $param,
            'setting' => $this->module['setting'],
            'module'  => array(
                'name'  => $this->module['name'],
                'path'  => $this->module['path']
            )
        );

        if (!$this->module['setting']['status'] || !$this->module['setting'][$param['type']]['status']) {
            return;
        }

        // Media list
        $where  = '';
        $clause = '';
        $select = 'isg.media_id, isg.shortcode, isg.permalink, isg.media_url';
        $limit  = $this->module['setting'][$param['type']]['limit'];
        $page   = !empty($param['page']) ? $param['page'] : 1;

        switch ($this->module['setting'][$param['type']]['visibility']) {
            case 'product':
                $clause = 'HAVING related_product > 0';
                break;
            case 'both':
                $where  = ' AND isg.approve = 1';
                $clause = 'HAVING related_product > 0';
                break;
            case 'approve':
            default:
                $where  = ' AND isg.approve = 1';
                break;
        }

        $photos      = array();
        $totalPhotos = $this->module['model']->getMediasTotal();
        $results     = $this->module['model']->getMedias($page, $limit, $select, $where, $clause);

        $i = 0;
        foreach ($results as $key => $media) {
            if ($i == $limit) { break; }

            $media['media_image'] = '';
            if ($this->module['setting']['validate_media'] && $this->isValidMedia($media['shortcode'] . '.cdn', $media['media_url'])) {
                $media['media_image'] = $media['media_url'];
            } elseif (!$this->module['setting']['validate_media']) {
                $media['media_image'] = $media['media_url'];
            }

            if ($media['media_image']) {
                $photos[$key] = $media;
                $photos[$key]['is_extra'] = false;
                $i++;
            }
        }

        $data['photos'] = array(
            'items' => $photos,
            'page'  => array(
                'next' => $page + 1,
                'show' => ($limit * $page) < $totalPhotos ? true : false,
            )
        );

        // Append extra image
        if ($param['type'] == 'module' && $this->module['setting']['module']['extra_image'] && is_file(DIR_IMAGE . $this->module['setting']['module']['extra_image'])) {
            $this->load->model('tool/image');

            if (count($data['photos']['items']) >= $this->module['setting']['module']['limit']) {
                array_pop($data['photos']['items']);
            }

            $data['photos']['items'][1000000] = array(
                'image_thumb' => $this->model_tool_image->resize($this->module['setting']['module']['extra_image'], 480, 480),
                'url'         => $this->module['setting']['module']['extra_link'],
                'is_extra'    => true
            );
        }

        if ($param['type'] == 'module') {
            $this->response->setOutput($this->load->view($this->module['path'] . '/items', $data));
        } else {
            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode(array(
                'output' => $this->load->view($this->module['path'] . '/items', $data),
                'page'   => $data['photos']['page'],
            )));
        }
    }

    public function modal()
    {
        $param = $this->request->get;
        $data  = $this->data;
        $data['products'] = array();
        $data['carousel'] = false;

        if (!$this->module['setting']['status'] || !$param) {
            return;
        }

        if (isset($param['shortcode'])) {
            $photoFeed     = array();
            $data['photo'] = $this->module['model']->getMedia($param['shortcode'], $param);

            $caption = str_replace(array('“', '”', "`", "'"), '', $data['photo']['caption']);
            $caption = preg_replace('~#[\w-]+~u', '', $caption); // Remove hashtags in caption
            $data['photo']['caption'] = nl2br(htmlspecialchars_decode($caption));
            $data['photo']['date']    = !empty($data['photo']['timestamp']) ? $this->timeElapsedString($data['photo']['timestamp']) : '';

            // Related products
            $image_size = 150;
            $products   = $this->module['model']->getRelatedProduct($param['shortcode']);

            if ($products) {
                $this->load->model('tool/image');

                foreach ($products as $product) {
                    if ($product['image']) {
                        $image = $this->model_tool_image->resize($product['image'], $image_size, $image_size);
                    } else {
                        $image = $this->model_tool_image->resize('placeholder.png', $image_size, $image_size);
                    }

                    $price = false;
                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    }

                    $special = false;
                    if ((float)$product['special']) {
                        $special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    }

                    $tax = false;
                    if ($this->config->get('config_tax')) {
                        $tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
                    }

                    $rating = false;
                    if ($this->config->get('config_review_status')) {
                        $rating = (int)$product['rating'];
                    }

                    $data['products'][] = array(
                        'product_id'  => $product['product_id'],
                        'thumb'       => $image,
                        'name'        => $product['name'],
                        'price'       => $price,
                        'special'     => $special,
                        'tax'         => $tax,
                        'minimum'     => $product['minimum'] > 0 ? $product['minimum'] : 1,
                        'rating'      => $rating,
                        'href'        => $this->url->link('product/product', 'product_id=' . $product['product_id'])
                    );
                }
            }

            $data['carousel'] = count($products) > 3 ? true : false;

            $data['theme'] = $this->config->get('config_theme');
            if ($this->config->get('theme_default_directory') == 'journal2') {
                $data['theme'] = 'journal2';
            }

            $output = $this->load->view($this->module['path'] . '/modal', $data);

        } else {
            $output = '<div class="islip-p100">' . $data['error_general'] . '</div>';
        }

        $this->response->setOutput($output);
    }

    /**
     * Check if valid instagram media
     *
     * @param  string  $key    Cache key
     * @param  string  $media  Media url
     * @param  string  $status 200 for direct image, 302 for media found
     *
     * @return boolean
     */
    private function isValidMedia($key, $media, $status = '200')
    {
        if (!empty($this->session->data['isg_valid_media'][$key . $media])) {
            $headers = $this->session->data['isg_valid_media'][$key . $media];
        } else {
            $headers = $this->cache->get('isg_media.' . $key);
        }

        if (!$key || filter_var($media, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        if (!$headers) {
            $headers = @get_headers($media);
            $this->cache->set('isg_media.' . $key, $headers);
            $this->session->data['isg_valid_media'][$key . $media] = $headers;
        }

        if (!empty($headers) && strpos($headers[0], $status) !== false) {
            return true;
        }

        return false;
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

    /**
     * Instagram Basic API OAuth callback
     */
    public function callback()
    {
        if (!$this->module['setting']['basic_app_id'] || !$this->module['setting']['basic_app_secret'] || empty($this->session->data['isg_basic_api']['redirect_url'])) {
            return;
        }

        if (isset($this->request->get['code'])) {
            $code = $this->request->get['code'];
            $adminToken = $this->request->get['state'];

            $igBasicApi = new \vendor\isenselabs\instagramshopgallery\basicapi(array(
                'appId'       => $this->module['setting']['basic_app_id'],
                'appSecret'   => $this->module['setting']['basic_app_secret'],
                'redirectUri' => $this->session->data['isg_basic_api']['oauth_uri']
            ));

            $token = '';

            // Short lived access token (valid for 1 hour)
            $result = $igBasicApi->authCodeToken($code);

            if (isset($result['access_token'])) {
                $token = $result['access_token'];
            } elseif (isset($result['error_message'])) {
                $this->log->write('InstagramShopGallery ' . $result['error_type'] . ': ' . $result['error_message']);
            }

            if ($token) {
                // Exchange short-live token for a long-lived token (valid for 60 days)
                $result = $igBasicApi->exchangeToken($token);

                if (isset($result['access_token'])) {
                    $igBasicApi->setConfig('accessToken', $result['access_token']);

                    $setting = $this->module['setting'];
                    $setting['basic_access_token'] = $result['access_token'];
                    $setting['basic_token_expire'] = date('Y-m-d H:i:s', time() + (int)$result['expires_in']);
                    $setting['basic_meta'] = $igBasicApi->getProfile();

                    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$this->data['store_id'] . "' AND `code` = '" . $this->db->escape($this->module['code']) . "'");
                    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$this->data['store_id'] . "', `code` = '" . $this->db->escape($this->module['code']) . "', `key` = '" . $this->db->escape($this->module['code'] . '_setting') . "', `value` = '" . $this->db->escape(json_encode($setting, true)) . "', serialized = '1'");
                } elseif (isset($result['error_message'])) {
                    $this->log->write('InstagramShopGallery ' . $result['error_type'] . ': ' . $result['error_message']);
                }

                $this->response->redirect($this->session->data['isg_basic_api']['redirect_url'] . '&token=' . $adminToken);
            }
        }
    }
}
