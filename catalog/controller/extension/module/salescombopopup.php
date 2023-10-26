<?php
class ControllerExtensionModuleSalescombopopup extends Controller {
	public function index($autopopup  = array()) {
		$data['autopopup'] = $autopopup;
		$this->load->language('offers/salescombopge');
        $data['offerclose'] = $this->language->get('offerclose');
        return $this->load->view('extension/module/salescombopopup', $data);
	}
}