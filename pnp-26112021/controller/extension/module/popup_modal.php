<?php
class ControllerExtensionModulePopupModal extends Controller {
	public function index() {
		$this->load->model('extension/extension');
		$this->load->model('extension/module');

		$extensions_list = array();
		
		$module_to_exclude = array('popup_modal', 'quickcheckout', 'update_price', 'category', 'discounts');

		// Get a list of installed modules
		$extensions = $this->model_extension_extension->getInstalled('module');

		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if(!in_array($code, $module_to_exclude)) {
				$this->load->language('extension/module/' . $code);

				$modules = $this->model_extension_module->getModulesByCode($code);

				if(!empty($modules)) {
					foreach ($modules as $module) {
						$extensions_list[] = array(
							'label'   => strip_tags($this->language->get('heading_title')).' - '.strip_tags($module['name']),
							'value'   => $code . '.' .  $module['module_id']
						);
					}
				}
				else if($this->config->has($code . '_status')) {
					$extensions_list[] = array(
						'label'   => strip_tags($this->language->get('heading_title')),
						'value'   => $code,
					);
				}
			}
		}
		
		$yes_no = array();
		$yes_no[] = array(
			'label' => 'Yes',
			'value' => 1,
		);
		$yes_no[] = array(
			'label' => 'No',
			'value' => 0,
		);
		
		$show = array();
		$show[] = array(
			'label' => 'Always show',
			'value' => 1,
		);
		$show[] = array(
			'label' => 'Show once (based on browser)',
			'value' => 2,
		);
		
        // Do note that below are the sample for using module helper, you may use it in other modules
		$array = array(
            'oc' => $this,
            'heading_title' => 'Pop Up Modal',
            'modulename' => 'popup_modal',
            'fields' => array(
                array('type' => 'dropdown', 'label' => 'Extension Module', 'name' => 'extension_module', 'choices' => $extensions_list),
				array('type' => 'image', 'label' => 'Background Image', 'name' => 'background_img'),
				array('type' => 'dropdown', 'label' => 'On/Off Pop Up', 'name' => 'on_off_popup', 'choices' => $yes_no),
				array('type' => 'text', 'label' => 'Delay Time (milliseconds)', 'name' => 'delay_time'),
				array('type' => 'dropdown', 'label' => 'Show Mode', 'name' => 'show_mode', 'choices' => $show),
				array('type' => 'dropdown', 'label' => 'Show only in Home page', 'name' => 'show_page', 'choices' => $yes_no),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
