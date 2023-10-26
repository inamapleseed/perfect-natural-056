<?php
class ControllerExtensionModuleCustom2 extends Controller {
	public function index() {
		// Handle custom2 fields
		$oc = $this;
		$language_id = $this->config->get('config_language_id');
		$modulename  = 'custom2';
	    $this->load->library('modulehelper');
	    $Modulehelper = Modulehelper::get_instance($this->registry);

		$data['title'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'title' );
		$data['repeater'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'repeater' );
		$data['repeater2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'repeater2' );
		$data['text'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'text' );
		$data['design2'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'design2' );
		$data['design2b'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'design2b' );

		return $this->load->view('extension/module/custom2', $data);
	}
}