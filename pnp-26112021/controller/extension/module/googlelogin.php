<?php
require_once(DIR_SYSTEM . 'library/equotix/googlelogin/equotix.php');
class ControllerExtensionModuleGoogleLogin extends Equotix {
	protected $version = '1.0.0';
	protected $code = 'googlelogin';
	protected $extension = 'Google Login';
	protected $extension_id = '93';
	protected $purchase_url = 'google-login';
	protected $purchase_id = '35231';
	protected $errro = array();

	public function index() {
		$this->language->load('extension/module/googlelogin');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('googlelogin', $this->request->post);		
			
			$this->session->data['success'] = $this->language->get('text_success');
		
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
	
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_box'] = $this->language->get('entry_box');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_heading'] = $this->language->get('entry_heading');
		$data['entry_align'] = $this->language->get('entry_align');
		$data['entry_button_width'] = $this->language->get('entry_button_width');
		$data['entry_button_height'] = $this->language->get('entry_button_height');
		$data['entry_target_location'] = $this->language->get('entry_target_location');
		$data['entry_target_action'] = $this->language->get('entry_target_action');
		$data['entry_additional_javascript'] = $this->language->get('entry_additional_javascript');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled']	= $this->language->get('text_enabled');
		$data['text_disabled']	= $this->language->get('text_disabled');
		
		$data['help_client_id']	= $this->language->get('help_client_id');
		$data['help_target_location']	= $this->language->get('help_target_location');
		
		$data['tab_general'] = $this->language->get('tab_general');
        $data['tab_design'] = $this->language->get('tab_design');
		$data['tab_advanced'] = $this->language->get('tab_advanced');
		        
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
  		$data['breadcrumbs'] = array();

  		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
   		);

   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
   		);
		
   		$data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/googlelogin', 'token=' . $this->session->data['token'], true)
   		);
		
		$data['action'] = $this->url->link('extension/module/googlelogin', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		if (isset($this->request->post['googlelogin_status'])) { 
			$data['googlelogin_status'] = $this->request->post['googlelogin_status']; 
		} else { 
			$data['googlelogin_status'] = $this->config->get('googlelogin_status');
		} 

		if (isset($this->request->post['googlelogin_client_id'])) {
			$data['googlelogin_client_id'] = $this->request->post['googlelogin_client_id'];
		} elseif ($this->config->get('googlelogin_client_id')) { 
			$data['googlelogin_client_id'] = $this->config->get('googlelogin_client_id');
		} else {
			$data['googlelogin_client_id'] = '';
		}
        
		if (isset($this->request->post['googlelogin_customer_group_id'])) {
			$data['googlelogin_customer_group_id'] = $this->request->post['googlelogin_customer_group_id'];
		} elseif ($this->config->get('googlelogin_customer_group_id')) { 
			$data['googlelogin_customer_group_id'] = $this->config->get('googlelogin_customer_group_id');
		} else {
			$data['googlelogin_customer_group_id'] = '';
		}
		
		if (isset($this->request->post['googlelogin_box'])) {
			$data['googlelogin_box'] = $this->request->post['googlelogin_box'];
		} elseif ($this->config->get('googlelogin_box')) { 
			$data['googlelogin_box'] = $this->config->get('googlelogin_box');
		} else {
			$data['googlelogin_box'] = '';
		}
		
		if (isset($this->request->post['googlelogin_text'])) {
			$data['googlelogin_text'] = $this->request->post['googlelogin_text'];
		} elseif ($this->config->get('googlelogin_text')) { 
			$data['googlelogin_text'] = $this->config->get('googlelogin_text');
		} else {
			$data['googlelogin_text'] = '';
		}
		
		if (isset($this->request->post['googlelogin_heading'])) {
			$data['googlelogin_heading'] = $this->request->post['googlelogin_heading'];
		} elseif ($this->config->get('googlelogin_heading')) { 
			$data['googlelogin_heading'] = $this->config->get('googlelogin_heading');
		} else {
			$data['googlelogin_heading'] = '';
		}
		
		if (isset($this->request->post['googlelogin_button_text'])) {
			$data['googlelogin_button_text'] = $this->request->post['googlelogin_button_text'];
		} elseif ($this->config->get('googlelogin_button_text')) { 
			$data['googlelogin_button_text'] = $this->config->get('googlelogin_button_text');
		} else {
			$data['googlelogin_button_text'] = '';
		}
		
		if (isset($this->request->post['googlelogin_align'])) {
			$data['googlelogin_align'] = $this->request->post['googlelogin_align'];
		} elseif ($this->config->get('googlelogin_align')) { 
			$data['googlelogin_align'] = $this->config->get('googlelogin_align');
		} else {
			$data['googlelogin_align'] = 'CENTER';
		}

		if (isset($this->request->post['googlelogin_button_width'])) {
			$data['googlelogin_button_width'] = $this->request->post['googlelogin_button_width'];
		} elseif ($this->config->get('googlelogin_button_width')) {
			$data['googlelogin_button_width'] = $this->config->get('googlelogin_button_width');
		} else {
			$data['googlelogin_button_width'] = '240';
		}

		if (isset($this->request->post['googlelogin_button_height'])) {
			$data['googlelogin_button_height'] = $this->request->post['googlelogin_button_height'];
		} elseif ($this->config->get('googlelogin_button_height')) {
			$data['googlelogin_button_height'] = $this->config->get('googlelogin_button_height');
		} else {
			$data['googlelogin_button_height'] = '40';
		}
        
        if (isset($this->request->post['googlelogin_target_location'])) {
			$data['googlelogin_target_location'] = $this->request->post['googlelogin_target_location'];
		} elseif ($this->config->get('googlelogin_target_location')) { 
			$data['googlelogin_target_location'] = $this->config->get('googlelogin_target_location');
		} else {
			$data['googlelogin_target_location'] = '';
		}
        
        if (isset($this->request->post['googlelogin_target_action'])) {
			$data['googlelogin_target_action'] = $this->request->post['googlelogin_target_action'];
		} elseif ($this->config->get('googlelogin_target_action')) { 
			$data['googlelogin_target_action'] = $this->config->get('googlelogin_target_action');
		} else {
			$data['googlelogin_target_action'] = 'APPEND';
		}
        
        if (isset($this->request->post['googlelogin_additional_javascript'])) {
			$data['googlelogin_additional_javascript'] = $this->request->post['googlelogin_additional_javascript'];
		} elseif ($this->config->get('googlelogin_additional_javascript')) { 
			$data['googlelogin_additional_javascript'] = $this->config->get('googlelogin_additional_javascript');
		} else {
			$data['googlelogin_additional_javascript'] = '';
		}
		
		$this->load->model('customer/customer_group');
		
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/language');
		
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('setting/store');
		
		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id'		=> 0,
			'name'			=> 'Default'
		);
		
		$stores = $this->model_setting_store->getStores();
		
		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id'		=> $store['store_id'],
				'name'			=> $store['name']
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->generateOutput('extension/module/googlelogin', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/googlelogin') || !$this->validated()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}