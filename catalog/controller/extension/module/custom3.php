<?php
class ControllerExtensionModuleCustom3 extends Controller {
	public function index() {
		// Handle custom3 fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'custom3';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title' );
		$data['image'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image' );
		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );
		$data['logo'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'logo' );

		return $this->load->view('extension/module/custom3', $data);
	}
	public function migrate() {
		// Handle custom3 fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'custom3';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title' );
		$data['image'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'image' );
		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );
		$data['logo'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'logo' );

		return $data;
	}
}