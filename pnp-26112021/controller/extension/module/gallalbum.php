<?php
class ControllerExtensionModuleGallalbum extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/gallalbum');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('gallalbum', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			$this->model_setting_setting->editSetting('gallalbum', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');	
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_limit'] = $this->language->get('entry_limit');
        $data['entry_gallimage'] = $this->language->get('entry_gallimage');
        $data['help_gallimage'] = $this->language->get('help_gallimage');
        
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_showimg'] = $this->language->get('entry_showimg');
		$data['entry_headtitle'] = $this->language->get('entry_headtitle');
		$data['entry_descstat'] = $this->language->get('entry_descstat');
		$data['entry_chardesc'] = $this->language->get('entry_chardesc');
		$data['entry_popupstyle'] = $this->language->get('entry_popupstyle');
        $data['entry_thumblist'] = $this->language->get('entry_thumblist');
        $data['entry_titlepos'] = $this->language->get('entry_titlepos');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}
		
		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['code' . $language['language_id']])) {
				$data['error_code' . $language['language_id']] = $this->error['code' . $language['language_id']];
			} else {
				$data['error_code' . $language['language_id']] = '';
			}
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/gallalbum', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/gallalbum', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);			
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/gallalbum', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/gallalbum', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
        
        $data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
						
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}
        
        $this->load->model('catalog/gallimage');

		$data['gallimages'] = array();

		if (isset($this->request->post['gallimage'])) {
			$gallimages = $this->request->post['gallimage'];
		} elseif (!empty($module_info)) {
			$gallimages = $module_info['gallimage'];
		} else {
			$gallimages = array();
		}
        
        if ($gallimages) {
		foreach ($gallimages as $gallimage_id) {
			$gallimage_info = $this->model_catalog_gallimage->getGallimage($gallimage_id);

			if ($gallimage_info) {
				$data['gallimages'][] = array(
					'gallimage_id' => $gallimage_info['gallimage_id'],
					'name'       => $gallimage_info['name']
				);
			}
		}
        }
		
		if (isset($this->request->post['showimg'])) {
			$data['showimg'] = $this->request->post['showimg'];
		} elseif (!empty($module_info)) {
			$data['showimg'] = $module_info['showimg'];
		} else {
			$data['showimg'] = '';
		}	

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}	
			
		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}
		
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		foreach ($languages as $language) {
		if (isset($this->request->post['headtitle_' . $language['language_id']])) {
			$data['headtitle_' . $language['language_id']] = $this->request->post['headtitle_' . $language['language_id']];
		} elseif (!empty($module_info)) {
			$data['headtitle_' . $language['language_id']] = $module_info['headtitle_' . $language['language_id']];
		} else {
			$data['headtitle_' . $language['language_id']] = '';
		}	
		}		
		
		if (isset($this->request->post['descstat'])) {
			$data['descstat'] = $this->request->post['descstat'];
		} elseif (!empty($module_info)) {
			$data['descstat'] = $module_info['descstat'];
		} else {
			$data['descstat'] = '';
		}	
		
		if (isset($this->request->post['chardesc'])) {
			$data['chardesc'] = $this->request->post['chardesc'];
		} elseif (!empty($module_info)) {
			$data['chardesc'] = $module_info['chardesc'];
		} else {
			$data['chardesc'] = '200';
		}
        
        if (isset($this->request->post['thumblist'])) {
			$data['thumblist'] = $this->request->post['thumblist'];
		} elseif (!empty($module_info)) {
			$data['thumblist'] = $module_info['thumblist'];
		} else {
			$data['thumblist'] = '';
		}
        
        if (isset($this->request->post['titlepos'])) {
			$data['titlepos'] = $this->request->post['titlepos'];
		} elseif (!empty($module_info)) {
			$data['titlepos'] = $module_info['titlepos'];
		} else {
			$data['titlepos'] = '';
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/gallalbum', $data));
	}
	
	public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallimage` (`gallimage_id` int(11) NOT NULL AUTO_INCREMENT, `image` varchar(255) DEFAULT NULL, `sort_order` int(3) NOT NULL DEFAULT '0', `status` tinyint(1) NOT NULL, `gwidth` int(11) DEFAULT NULL, `gheight` int(11) DEFAULT NULL, `pwidth` int(11) DEFAULT NULL, `pheight` int(11) DEFAULT NULL, `awidth` int(11) DEFAULT NULL, `aheight` int(11) DEFAULT NULL, `position` varchar(11) NOT NULL, `resize` tinyint(1) NOT NULL, `imgperrow` int(11) NOT NULL, `popstyle` varchar(11) NOT NULL, `gallpage` int(11) DEFAULT NULL, `date_added` datetime NOT NULL, `date_modified` datetime NOT NULL, PRIMARY KEY (`gallimage_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");	
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallimage_description` (`gallimage_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `name` varchar(255) NOT NULL, `description` text NOT NULL, `meta_title` varchar(255) NOT NULL, `meta_description` varchar(255) NOT NULL, `meta_keyword` varchar(255) NOT NULL, PRIMARY KEY (`gallimage_id`,`language_id`), KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallimage_to_store` (`gallimage_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY (`gallimage_id`,`store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallimage_image` (`gallimage_image_id` int(11) NOT NULL AUTO_INCREMENT, `gallimage_id` int(11) NOT NULL, `link` varchar(255) NOT NULL, `image` varchar(255) NOT NULL, `sort_order` int(3) NOT NULL DEFAULT '0', PRIMARY KEY (`gallimage_image_id`) ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "gallimage_image_description` (`gallimage_image_id` int(11) NOT NULL, `language_id` int(11) NOT NULL, `gallimage_id` int(11) NOT NULL, `title` varchar(128) NOT NULL, PRIMARY KEY (`gallimage_image_id`,`language_id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8");
    }
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gallimage");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gallimage_description");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gallimage_to_store");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gallimage_image");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "gallimage_image_description");
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/gallalbum')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}