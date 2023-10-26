<?php
class ControllerExtensionModuleRelated extends Controller {
	private static $module_id = 1;

	public function index($setting) {
		// $this->load->language('extension/module/related');

		$data["uqid"] = 'related_products_' . $this->module_id;

		$data['heading_title'] = $this->language->get('heading_title');

		if( isset($setting['title'][(int)$this->config->get('config_language_id')]) ){
			$data['heading_title'] = $setting['title'][(int)$this->config->get('config_language_id')];
		}

		$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
		$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');

		//$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.css');
		//$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

		$this->load->model('catalog/product');

		$data['products'] = array();

		if (!empty($setting['product'])) { 
			$products = $setting['product'];

			foreach ($products as $product_id) {
				$data['products'][] = $this->load->controller('component/product_info2', $product_id);
			}
		}

		if(!$data['products']) return '';

		$this->module_id++;

		return $this->load->view('extension/module/related_slick', $data);
	}
}