<?php
class ControllerExtensionPaymentMpgs extends Controller {
	private $error = array();
	private function checkIfInstalledDB() {

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "mpgs_order` (
			  `mpgs_order_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `successIndicator` varchar(50) NOT NULL,
			  `resultIndicator` varchar(50) NOT NULL,
			  `amount` decimal(15,4) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
		");

	}
	public function index() {

		$this->checkIfInstalledDB();

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('mpgs', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data = $this->load->language('extension/payment/mpgs');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_notice'] = $this->language->get('text_notice');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['heading_title']	= $this->language->get('heading_title');

		$data['text_extension']	= $this->language->get('text_extension');
		$data['text_success']	= $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');

		$data['entry_total']	= $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_failed_order_status'] = $this->language->get('entry_failed_order_status');
		$data['entry_status']	= $this->language->get('entry_status');
		$data['entry_sort_order']	= $this->language->get('entry_sort_order');
		$data['entry_payment_title']	= $this->language->get('entry_payment_title');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_merchant_name'] = $this->language->get('entry_merchant_name');
		$data['entry_org_id'] = $this->language->get('entry_org_id');
		$data['help_timeout'] = $this->language->get('help_timeout');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_integration_passowrd'] = $this->language->get('entry_integration_passowrd');
		$data['entry_webhook_secret'] = $this->language->get('entry_webhook_secret');
		$data['entry_display_name'] = $this->language->get('entry_display_name');
		$data['entry_mode'] = $this->language->get('entry_mode');


		$data['error_permission'] = $this->language->get('error_permission');
		$data['error_instruction'] = $this->language->get('error_instruction');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_mpgs_payment_title' . $language['language_id']] = $this->error['mpgs_payment_title' . $language['language_id']];
			} else {
				$data['error_mpgs_payment_title' . $language['language_id']] = '';
			}
		}
                
        if(isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        }else{
            $data['error_image'] = '';
        }

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/mpgs', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/mpgs', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['mpgs_mode'])) {
			$data['mpgs_mode'] = $this->request->post['mpgs_mode'];
		} else {
			$data['mpgs_mode'] = $this->config->get('mpgs_mode');
		}

		if (isset($this->request->post['mpgs_merchant_name'])) {
			$data['mpgs_merchant_name'] = $this->request->post['mpgs_merchant_name'];
		} else {
			$data['mpgs_merchant_name'] = $this->config->get('mpgs_merchant_name');
		}

		if (isset($this->request->post['mpgs_merchant_id'])) {
			$data['mpgs_merchant_id'] = $this->request->post['mpgs_merchant_id'];
		} else {
			$data['mpgs_merchant_id'] = $this->config->get('mpgs_merchant_id');
		}

		if (isset($this->request->post['mpgs_integration_password'])) {
			$data['mpgs_integration_password'] = $this->request->post['mpgs_integration_password'];
		} else {
			$data['mpgs_integration_password'] = $this->config->get('mpgs_integration_password');
		}
		
		if (isset($this->request->post['mpgs_webhook_secret'])) {
			$data['mpgs_webhook_secret'] = $this->request->post['mpgs_webhook_secret'];
		} else {
			$data['mpgs_webhook_secret'] = $this->config->get('mpgs_webhook_secret');
		}

		$this->load->model('localisation/language');

		foreach ($languages as $language) {

	      	if (isset($this->request->post['mpgs_payment_title' . $language['language_id']])) {
	        	$data['mpgs_payment_title' . $language['language_id']] = $this->request->post['mpgs_payment_title' . $language['language_id']];
	      	} else {
	        	$data['mpgs_payment_title' . $language['language_id']] = $this->config->get('mpgs_payment_title' . $language['language_id']);
	      	}
		}

		$data['languages'] = $languages;

		if (isset($this->request->post['mpgs_order_status_id'])) {
			$data['mpgs_order_status_id'] = $this->request->post['mpgs_order_status_id'];
		} else {
			$data['mpgs_order_status_id'] = $this->config->get('mpgs_order_status_id');
		}

		if (isset($this->request->post['mpgs_failed_order_status_id'])) {
			$data['mpgs_failed_order_status_id'] = $this->request->post['mpgs_failed_order_status_id'];
		} else {
			$data['mpgs_failed_order_status_id'] = $this->config->get('mpgs_failed_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mpgs_status'])) {
			$data['mpgs_status'] = $this->request->post['mpgs_status'];
		} else {
			$data['mpgs_status'] = $this->config->get('mpgs_status');
		}

		if (isset($this->request->post['mpgs_sort_order'])) {
			$data['mpgs_sort_order'] = $this->request->post['mpgs_sort_order'];
		} else {
			$data['mpgs_sort_order'] = $this->config->get('mpgs_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/mpgs', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/mpgs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['mpgs_payment_title' . $language['language_id']])) {
				$this->error['mpgs_payment_title' .  $language['language_id']] = $this->language->get('error_instruction');
			}
		}
//                if (empty($this->request->post['paynow_image'])) {
//                        $this->error['image'] = $this->language->get('error_image');
//                }
                
		return !$this->error;
	}
}