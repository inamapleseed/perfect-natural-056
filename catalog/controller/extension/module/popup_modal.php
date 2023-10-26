<?php
class ControllerExtensionModulePopupModal extends Controller {
	public function index() {
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'popup_modal';

		$extension_module = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'extension_module' );
		
		$data['module'] = '';
		
		if(isset($extension_module)) {
			$part = explode('.', $extension_module);
			
			if (isset($part[0]) && $this->config->get($part[0] . '_status')) {
				if($part[0] == 'mailchimp_integration') {
					$ext_path = 'module/';
				}
				else {
					$ext_path = 'extension/module/';
				}
				$module_data = $this->load->controller($ext_path . $part[0]);	
				
				if ($module_data) {
					$data['module'] = $module_data;
				}
			}
			
			if (isset($part[1])) {
				$setting_info = $this->model_extension_module->getModule($part[1]);
				
				if ($setting_info && $setting_info['status']) {
					$setting_info['module_id'] = $part[1];
					
					$output = $this->load->controller('extension/module/' . $part[0], $setting_info);
					
					if ($output) {
						$data['module'] = $output;
					}
				}
			}
		}
		//debug($data['module']);
		
		$data['background_img'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'background_img' ) ? 'image/'.$this->modulehelper->get_field ( $this, $modulename, $language_id, 'background_img' ) : '';
		$data['on_off_popup'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'on_off_popup' );
		$data['delay_time'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'delay_time' ) ? $this->modulehelper->get_field ( $this, $modulename, $language_id, 'delay_time' ) : 1000;
		$data['show_mode'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'show_mode' );
		$data['show_page'] = $this->modulehelper->get_field ( $this, $modulename, $language_id, 'show_page' );
		$data['current_route'] = isset($this->request->get['route']) ? $this->request->get['route'] : '';
		$data['popup_state_name'] = 'popupState-'.generateSlug($this->config->get('config_name')).'-'.DB_PREFIX.'site';
		//debug($data['popup_state_name']);

        return $this->load->view('extension/module/popup_modal', $data);
	}
}
