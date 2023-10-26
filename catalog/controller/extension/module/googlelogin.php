<?php
require_once(DIR_SYSTEM . 'library/equotix/googlelogin/equotix.php');
class ControllerExtensionModuleGoogleLogin extends Equotix {
	protected $code = 'googlelogin';
	protected $extension_id = '93';
	
	public function index() {
		$heading_title = $this->config->get('googlelogin_heading');
		
    	$data['heading_title'] = !empty($heading_title[$this->config->get('config_language_id')]) ? $heading_title[$this->config->get('config_language_id')] : '';

		$text = $this->config->get('googlelogin_text');
    	$data['text'] = !empty($text[$this->config->get('config_language_id')]) ? $text[$this->config->get('config_language_id')] : '';
		
		$data['align'] = $this->config->get('googlelogin_align');
		$data['box'] = $this->config->get('googlelogin_box');
        $data['target_location'] = html_entity_decode($this->config->get('googlelogin_target_location'), ENT_QUOTES);
        $data['target_action'] = $this->config->get('googlelogin_target_action');
        $data['additional_javascript'] = html_entity_decode($this->config->get('googlelogin_additional_javascript'), ENT_QUOTES);
        $data['googlelogin_button_width'] = $this->config->get('googlelogin_button_width');
        $data['googlelogin_button_height'] = $this->config->get('googlelogin_button_height');

        $client_id = $this->config->get('googlelogin_client_id');

		$data['googlelogin_client_id'] = $client_id[$this->config->get('config_store_id')];

		if (isset($this->session->data['google_error'])) {
			$data['google_error'] = $this->session->data['google_error'];
            
            unset($this->session->data['google_error']);
		} else {
			$data['google_error'] = false;
		}
		
		if ((isset($this->request->get['route']) && $this->request->get['route'] == 'account/logout') || ($this->customer->isLogged()) || empty($data['googlelogin_client_id']) || !$this->validated()) {
			return;
		}

		return $this->load->view('extension/module/googlelogin', $data);
	}
	
	public function redirect() {
        $json = array();
        
		$this->load->model('extension/module/googlelogin');
        
		$this->load->language('extension/module/googlelogin');
        
        $google_token = isset($this->request->post['google_token']) ? utf8_decode($this->request->post['google_token']) : '';

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart Google Login Equotix');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $google_token);
		
        $response = curl_exec($curl);

		curl_close($curl);

		$response = json_decode($response, true);

		$client_id = $this->config->get('googlelogin_client_id');
		$client_id = $client_id[$this->config->get('config_store_id')];
		
		if (empty($response['aud']) || strpos($response['aud'], $client_id) === false) {
			$this->session->data['google_error'] = $this->language->get('error_email');
			
			$json['error'] = true;
		} else {
            $this->load->model('account/customer_group');
            
            $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('googlelogin_customer_group_id'));
                    
            if ($customer_group_info) {
                $approved = !$customer_group_info['approval'];
            } else {
                $approved = 1;
            }
            
            $data = array(
                'store_id'			=> $this->config->get('config_store_id'),
                'firstname'			=> $response['given_name'],
                'lastname'			=> $response['family_name'],
                'email'				=> $response['email'],
                'password'			=> md5(uniqid(rand(), true)),
                'customer_group_id'	=> $this->config->get('googlelogin_customer_group_id'),
                'newsletter'		=> 1,
                'ip'				=> $this->request->server['REMOTE_ADDR'],
                'approved'			=> $approved
            );

            $customer = $this->model_extension_module_googlelogin->getCustomer($response['email']);

            if (!$customer) {
                $address_id = $this->model_extension_module_googlelogin->addCustomer($data);
                
                $customer = $this->model_extension_module_googlelogin->getCustomer($response['email']);
            }
            
            if ($customer['approved']) {
                $this->customer->login($response['email'], '', true);
                
                if (isset($address_id)) {
                    $json['success'] = html_entity_decode($this->url->link('account/address/edit', 'address_id=' . $address_id, true), ENT_QUOTES);
                } else {
                    $json['success'] = html_entity_decode($this->url->link('account/account', '', false), ENT_QUOTES);
                }
            } else {
                $json['error'] = true;
                
                $this->session->data['google_error'] = $this->language->get('error_approved');
            }
        }
        
		$this->response->setOutput(json_encode($json));
	}
}