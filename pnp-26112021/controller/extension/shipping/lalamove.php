<?php
class ControllerExtensionShippingLalamove extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/lalamove');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('lalamove', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_display'] = $this->language->get('entry_display');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_service_type']             = $this->language->get('entry_service_type');
		$data['entry_generate_quotation_link']  = $this->language->get('entry_generate_quotation_link');
		$data['entry_post_order_link']          = $this->language->get('entry_post_order_link');
		$data['entry_driver_info_link']         = $this->language->get('entry_driver_info_link');
		$data['entry_driver_location_link']     = $this->language->get('entry_driver_location_link');
		$data['entry_order_status_link']        = $this->language->get('entry_order_status_link');
		$data['entry_cancel_order_link']        = $this->language->get('entry_cancel_order_link');
		
		$data['entry_sms']                      = $this->language->get('entry_sms');
		$data['entry_merchant_id']              = $this->language->get('entry_merchant_id');
		$data['entry_merchant_password']        = $this->language->get('entry_merchant_password');
		$data['entry_merchant_url']             = $this->language->get('entry_merchant_url');
		$data['entry_remark']                   = $this->language->get('entry_remark');                                                                       
		
		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/lalamove', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/lalamove', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		if (isset($this->request->post['lalamove_total'])) {
			$data['lalamove_total'] = $this->request->post['lalamove_total'];
		} else {
			$data['lalamove_total'] = $this->config->get('lalamove_total');
		}

		if (isset($this->request->post['lalamove_geo_zone_id'])) {
			$data['lalamove_geo_zone_id'] = $this->request->post['lalamove_geo_zone_id'];
		} else {
			$data['lalamove_geo_zone_id'] = $this->config->get('lalamove_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['lalamove_display'])) {
			$data['lalamove_display'] = $this->request->post['lalamove_display'];
		} else {
			$data['lalamove_display'] = $this->config->get('lalamove_display');
		}
		
		if (isset($this->request->post['lalamove_status'])) {
			$data['lalamove_status'] = $this->request->post['lalamove_status'];
		} else {
			$data['lalamove_status'] = $this->config->get('lalamove_status');
		}

		if (isset($this->request->post['lalamove_sort_order'])) {
			$data['lalamove_sort_order'] = $this->request->post['lalamove_sort_order'];
		} else {
			$data['lalamove_sort_order'] = $this->config->get('lalamove_sort_order');
		}

        if (isset($this->request->post['lalamove_service_type'])) {
			$data['lalamove_service_type'] = $this->request->post['lalamove_service_type'];
		} else {
			$data['lalamove_service_type'] = $this->config->get('lalamove_service_type');
		}

        if (isset($this->request->post['lalamove_generate_quotation_link'])) {
			$data['lalamove_generate_quotation_link'] = $this->request->post['lalamove_generate_quotation_link'];
		} else {
			$data['lalamove_generate_quotation_link'] = $this->config->get('lalamove_generate_quotation_link');
		}
		
        if (isset($this->request->post['lalamove_post_order_link'])) {
			$data['lalamove_post_order_link'] = $this->request->post['lalamove_post_order_link'];
		} else {
			$data['lalamove_post_order_link'] = $this->config->get('lalamove_post_order_link');
		}
		
		if (isset($this->request->post['lalamove_order_status_link'])) {
			$data['lalamove_order_status_link'] = $this->request->post['lalamove_order_status_link'];
		} else {
			$data['lalamove_order_status_link'] = $this->config->get('lalamove_order_status_link');
		}
		
		if (isset($this->request->post['lalamove_driver_info_link'])) {
			$data['lalamove_driver_info_link'] = $this->request->post['lalamove_driver_info_link'];
		} else {
			$data['lalamove_driver_info_link'] = $this->config->get('lalamove_driver_info_link');
		}
		
		
		if (isset($this->request->post['lalamove_driver_location_link'])) {
			$data['lalamove_driver_location_link'] = $this->request->post['lalamove_driver_location_link'];
		} else {
			$data['lalamove_driver_location_link'] = $this->config->get('lalamove_driver_location_link');
		}
		
		if (isset($this->request->post['lalamove_cancel_order_link'])) {
			$data['lalamove_cancel_order_link'] = $this->request->post['lalamove_cancel_order_link'];
		} else {
			$data['lalamove_cancel_order_link'] = $this->config->get('lalamove_cancel_order_link');
		}
		
		if (isset($this->request->post['lalamove_merchant_id'])) {
			$data['lalamove_merchant_id'] = $this->request->post['lalamove_merchant_id'];
		} else {
			$data['lalamove_merchant_id'] = $this->config->get('lalamove_merchant_id');
		}
		
		if (isset($this->request->post['lalamove_merchant_password'])) {
			$data['lalamove_merchant_password'] = $this->request->post['lalamove_merchant_password'];
		} else {
			$data['lalamove_merchant_password'] = $this->config->get('lalamove_merchant_password');
		}
		
		if (isset($this->request->post['lalamove_merchant_url'])) {
			$data['lalamove_merchant_url'] = $this->request->post['lalamove_merchant_url'];
		} else {
			$data['lalamove_merchant_url'] = $this->config->get('lalamove_merchant_url');
		}
		
		if (isset($this->request->post['lalamove_owner_name'])) {
			$data['lalamove_owner_name'] = $this->request->post['lalamove_owner_name'];
		} else {
			$data['lalamove_owner_name'] = $this->config->get('lalamove_owner_name');
		}
		
		if (isset($this->request->post['lalamove_owner_contact'])) {
			$data['lalamove_owner_contact'] = $this->request->post['lalamove_owner_contact'];
		} else {
			$data['lalamove_owner_contact'] = $this->config->get('lalamove_owner_contact');
		}
		
		if (isset($this->request->post['lalamove_sms'])) {
			$data['lalamove_sms'] = $this->request->post['lalamove_sms'];
		} else {
			$data['lalamove_sms'] = $this->config->get('lalamove_sms');
		}
		
		if (isset($this->request->post['lalamove_remark'])) {
			$data['lalamove_remark'] = $this->request->post['lalamove_remark'];
		} else {
			$data['lalamove_remark'] = $this->config->get('lalamove_remark');
		}
		
		if (isset($this->request->post['lalamove_owner_postcode'])) {
			$data['lalamove_owner_postcode'] = $this->request->post['lalamove_owner_postcode'];
		} else {
			$data['lalamove_owner_postcode'] = $this->config->get('lalamove_owner_postcode');
		}
		
		if (isset($this->request->post['lalamove_owner_address'])) {
			$data['lalamove_owner_address'] = $this->request->post['lalamove_owner_address'];
		} else {
			$data['lalamove_owner_address'] = $this->config->get('lalamove_owner_address');
		}
		
		if (isset($this->request->post['lalamove_test'])) {
			$data['lalamove_test'] = $this->request->post['lalamove_test'];
		} else {
			$data['lalamove_test'] = $this->config->get('lalamove_test');
		}

		if (isset($this->request->post['lalamove_delivery_times'])) {
			$data['lalamove_delivery_times'] = $this->request->post['lalamove_delivery_times'];
		} elseif ($this->config->get('lalamove_delivery_times') != "") {
			$data['lalamove_delivery_times'] = $this->config->get('lalamove_delivery_times');
		} else {
			$data['lalamove_delivery_times'] = array();
		}
		
		if (isset($this->request->post['lalamove_delivery_times_format'])) {
			$data['lalamove_delivery_times_format'] = $this->request->post['lalamove_delivery_times_format'];
		} elseif ($this->config->get('lalamove_delivery_times_format') != "") {
			$data['lalamove_delivery_times_format'] = $this->config->get('lalamove_delivery_times_format');
		} else {
			$data['lalamove_delivery_times_format'] = array();
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['lalamove_order_status'])) {
			$data['lalamove_order_status'] = $this->request->post['lalamove_order_status'];
		} elseif ($this->config->get('lalamove_order_status')) {
			$data['lalamove_order_status'] = $this->config->get('lalamove_order_status');
		} else {
			$data['lalamove_order_status'] = array();
		}
		
		if (isset($this->request->post['lalamove_completed_status'])) {
			$data['lalamove_completed_status'] = $this->request->post['lalamove_completed_status'];
		} elseif ($this->config->get('lalamove_completed_status')) {
			$data['lalamove_completed_status'] = $this->config->get('lalamove_completed_status');
		} else {
			$data['lalamove_completed_status'] = array();
		}
		if (isset($this->request->post['lalamove_cancelled_status'])) {
			$data['lalamove_cancelled_status'] = $this->request->post['lalamove_cancelled_status'];
		} elseif ($this->config->get('lalamove_cancelled_status')) {
			$data['lalamove_cancelled_status'] = $this->config->get('lalamove_cancelled_status');
		} else {
			$data['lalamove_cancelled_status'] = array();
		}
		
		// Languages
		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/lalamove', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/lalamove')) {
		    
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}