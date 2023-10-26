<?php
class ControllerExtensionModuleCustom extends Controller {
	public function index() {
		// Handle custom fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'custom';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['btntitle'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'btntitle' );
		$data['image'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image' );
		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );
		$data['url'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'url' );

		return $this->load->view('extension/module/custom', $data);
	}
}