<?php
class ModelExtensionPaymentDbsPaynowQr extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/dbs_paynow_qr');

		$method_data = array();

        $language_id = $this->config->get('config_language_id');

        if ($this->config->get('dbs_paynow_qr_payment_title' . $language_id) != "")
            $payment_title = $this->config->get('dbs_paynow_qr_payment_title' . $language_id);
        else
            $payment_title = $this->language->get('text_title');

		$method_data = array(
			'code'       => 'dbs_paynow_qr',
			'title'      => $payment_title,
			'terms'      => '',
			'sort_order' => $this->config->get('dbs_paynow_qr_sort_order')
		);

		return $method_data;
	}
}