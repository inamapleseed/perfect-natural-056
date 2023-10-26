<?php
class ModelExtensionTotalGst extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/gst');

		$rate = $this->config->get('gst_rate');
		$rate_calculation = 100 + $this->config->get('gst_rate');
		if(!isset($rate)){
			$rate = 7;
			$rate_calculation = 107;
		}

		$total['totals'][] = array(
			'code'       => 'gst',
			'title'      => sprintf($this->language->get('text_total'),$rate),
			'value'      => ($total['total'] / $rate_calculation) * $rate,
			'sort_order' => $this->config->get('gst_sort_order')
		);
		
	}
}