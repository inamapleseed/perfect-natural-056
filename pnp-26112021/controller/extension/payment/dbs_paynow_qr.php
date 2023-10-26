<?php
class ControllerExtensionPaymentDbsPaynowQr extends Controller {
	private $error = array();
	private function checkIfInstalledDB() {

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "dbs_order` (
			  `dbs_order_id` int(11) NOT NULL PRIMARY KEY,
			  `order_id` int(11) NOT NULL,
			  `customer_reference` varchar(20) NOT NULL,
			  `msg_id` varchar(50) NOT NULL,
			  `org_id` varchar(50) NOT NULL,
			  `time_stamp` varchar(50) NOT NULL,
			  `txn_type` varchar(20) NOT NULL,
			  `txn_ref_id` varchar(50) NOT NULL,
			  `txn_date` date NOT NULL,
			  `txn_ccy` varchar(3) NOT NULL,
			  `txn_amt` decimal(15,4) NOT NULL
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
		");

	}
	public function index() {

		$this->checkIfInstalledDB();
		$this->load->language('extension/payment/dbs_paynow_qr');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dbs_paynow_qr', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_notice'] = $this->language->get('text_notice');

		$data['entry_instruction'] = $this->language->get('entry_instruction');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_failed_order_status'] = $this->language->get('entry_failed_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_timeout'] = $this->language->get('entry_timeout');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_payment_title'] = $this->language->get('entry_payment_title');
		$data['entry_merchant_name'] = $this->language->get('entry_merchant_name');
		$data['entry_proxy_value'] = $this->language->get('entry_proxy_value');
		$data['entry_org_id'] = $this->language->get('entry_org_id');
		$data['help_timeout'] = $this->language->get('help_timeout');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank' . $language['language_id']])) {
				$data['error_instruction' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_instruction' . $language['language_id']] = '';
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
			'href' => $this->url->link('extension/payment/dbs_paynow_qr', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/dbs_paynow_qr', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		$this->load->model('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['dbs_paynow_qr_instruction' . $language['language_id']])) {
				$data['dbs_paynow_qr_instruction' . $language['language_id']] = $this->request->post['dbs_paynow_qr_instruction' . $language['language_id']];
			} else {
				$data['dbs_paynow_qr_instruction' . $language['language_id']] = $this->config->get('dbs_paynow_qr_instruction' . $language['language_id']);
			}

	      	if (isset($this->request->post['dbs_paynow_qr_payment_title' . $language['language_id']])) {
	        	$data['dbs_paynow_qr_payment_title' . $language['language_id']] = $this->request->post['dbs_paynow_qr_payment_title' . $language['language_id']];
	      	} else {
	        	$data['dbs_paynow_qr_payment_title' . $language['language_id']] = $this->config->get('dbs_paynow_qr_payment_title' . $language['language_id']);
	      	}
		}

		$data['languages'] = $languages;


		if (isset($this->request->post['dbs_paynow_qr_order_status_id'])) {
			$data['dbs_paynow_qr_order_status_id'] = $this->request->post['dbs_paynow_qr_order_status_id'];
		} else {
			$data['dbs_paynow_qr_order_status_id'] = $this->config->get('dbs_paynow_qr_order_status_id');
		}


		if (isset($this->request->post['dbs_paynow_qr_failed_order_status_id'])) {
			$data['dbs_paynow_qr_failed_order_status_id'] = $this->request->post['dbs_paynow_qr_failed_order_status_id'];
		} else {
			$data['dbs_paynow_qr_failed_order_status_id'] = $this->config->get('dbs_paynow_qr_failed_order_status_id');
		}

		if (isset($this->request->post['dbs_paynow_qr_merchant_name'])) {
			$data['dbs_paynow_qr_merchant_name'] = $this->request->post['dbs_paynow_qr_merchant_name'];
		} else {
			$data['dbs_paynow_qr_merchant_name'] = $this->config->get('dbs_paynow_qr_merchant_name');
		}

	    if (isset($this->request->post['dbs_paynow_qr_proxy_value'])) {
	      	$data['dbs_paynow_qr_proxy_value'] = $this->request->post['dbs_paynow_qr_proxy_value'];
	    } else {
	      	$data['dbs_paynow_qr_proxy_value'] = $this->config->get('dbs_paynow_qr_proxy_value');
	    }
		
		if (isset($this->request->post['dbs_paynow_qr_fds_id'])) {
			$data['dbs_paynow_qr_fds_id'] = $this->request->post['dbs_paynow_qr_fds_id'];
		} else {
			$data['dbs_paynow_qr_fds_id'] = $this->config->get('dbs_paynow_qr_fds_id');
		}
		
		if (isset($this->request->post['dbs_paynow_qr_fds_password'])) {
			$data['dbs_paynow_qr_fds_password'] = $this->request->post['dbs_paynow_qr_fds_password'];
		} else {
			$data['dbs_paynow_qr_fds_password'] = $this->config->get('dbs_paynow_qr_fds_password');
		}
		
		if (isset($this->request->post['dbs_paynow_qr_fds_link'])) {
			$data['dbs_paynow_qr_fds_link'] = $this->request->post['dbs_paynow_qr_fds_link'];
		} else {
			$data['dbs_paynow_qr_fds_link'] = $this->config->get('dbs_paynow_qr_fds_link');
		}

	    if (isset($this->request->post['dbs_paynow_qr_org_id'])) {
	      	$data['dbs_paynow_qr_org_id'] = $this->request->post['dbs_paynow_qr_org_id'];
	    } else {
	      	$data['dbs_paynow_qr_org_id'] = $this->config->get('dbs_paynow_qr_org_id');
	    }

		if (isset($this->request->post['dbs_paynow_qr_timeout'])) {
			$data['dbs_paynow_qr_timeout'] = $this->request->post['dbs_paynow_qr_timeout'];
		} else {
			$data['dbs_paynow_qr_timeout'] = $this->config->get('dbs_paynow_qr_timeout');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['timeouts'] = array(
      		'0' => 'Do not redirect',
      		'180000' => '3 minutes',
      		'300000' => '5 minutes',
      		'600000' => '10 minutes'
     	);

		if (isset($this->request->post['dbs_paynow_qr_geo_zone_id'])) {
			$data['dbs_paynow_qr_geo_zone_id'] = $this->request->post['dbs_paynow_qr_geo_zone_id'];
		} else {
			$data['dbs_paynow_qr_geo_zone_id'] = $this->config->get('dbs_paynow_qr_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['dbs_paynow_qr_status'])) {
			$data['dbs_paynow_qr_status'] = $this->request->post['dbs_paynow_qr_status'];
		} else {
			$data['dbs_paynow_qr_status'] = $this->config->get('dbs_paynow_qr_status');
		}

		if (isset($this->request->post['dbs_paynow_qr_sort_order'])) {
			$data['dbs_paynow_qr_sort_order'] = $this->request->post['dbs_paynow_qr_sort_order'];
		} else {
			$data['dbs_paynow_qr_sort_order'] = $this->config->get('dbs_paynow_qr_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/dbs_paynow_qr', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/dbs_paynow_qr')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['dbs_paynow_qr_instruction' . $language['language_id']])) {
				$this->error['bank' .  $language['language_id']] = $this->language->get('error_instruction');
			}
		}
//                if (empty($this->request->post['paynow_image'])) {
//                        $this->error['image'] = $this->language->get('error_image');
//                }
                
		return !$this->error;
	}
}