<?php
class ModelExtensionPaymentMpgs extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/mpgs');

		$method_data = array();

        $language_id = $this->config->get('config_language_id');

        if ($this->config->get('mpgs_payment_title' . $language_id) != "")
            $payment_title = $this->config->get('mpgs_payment_title' . $language_id);
        else
            $payment_title = $this->language->get('text_title');

		$method_data = array(
			'code'       => 'mpgs',
			'title'      => $payment_title,
			'terms'      => '',
			'sort_order' => $this->config->get('mpgs_sort_order')
		);

		return $method_data;
	}

	public function saveOrder($order_info,$total, $successIndicator,$resultIndicator = false) {

		$sql = "INSERT INTO " . DB_PREFIX . "mpgs_order SET 
				order_id = '".(int)$order_info['order_id']."',
				successIndicator = '".$this->db->escape($successIndicator)."',
				amount = '".$this->db->escape($total)."',
				date_added = NOW();
		";

		$this->db->query($sql);
	}

	public function updateOrder($order_id,$resultIndicator = false) {
		
		$sql = "UPDATE " . DB_PREFIX . "mpgs_order SET 
			resultIndicator = '".$this->db->escape($resultIndicator)."',
			date_modified = NOW()
			WHERE 
			order_id = '".(int)$order_id."'
		";

		$this->db->query($sql);
	}
}