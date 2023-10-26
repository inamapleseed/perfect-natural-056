<?php
class ControllerExtensionModuleRecentlyViewed extends Controller {
	public function index($setting) {
		static $module = 0;

		$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
		$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');
		
		$this->load->language('extension/module/recently_viewed');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		
		$current_product_id = false;
		if(isset($this->request->get['product_id'])) {
			$current_product_id = (int)$this->request->get['product_id'];
		}

		// default limit is 4
		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if ($setting['name']) {
			$data['heading_title'] = $setting['name'];
		}

		$data['autoplay'] = "";
		$data['mobile_autoplay'] = "";
		if ($setting['autoplay']) {
			$data['autoplay'] = $setting['autoplay'];
			$data['mobile_autoplay'] = $setting['autoplay'];
		} else {
			$data['mobile_autoplay'] = "5000";
		}

		$results  = array();
		$setting['products'] = array();
		if ($this->customer->isLogged()) {
			$this->load->model('extension/module/recently_viewed');
			
			/* if user is logged in then save all recently_viewed products to database if available in cookie and then clear the cookie */
			if(isset($this->request->cookie['recently_viewed']) && !empty($this->request->cookie['recently_viewed'])) {
				$recently_viewed = json_decode(base64_decode($this->request->cookie['recently_viewed']), true);
				// sort by in recent viewed order
				uasort($recently_viewed, function($a, $b){ return strtotime($a) < strtotime($b); });
				foreach($recently_viewed as $k=>$v){
					$this->model_extension_module_recently_viewed->setRecentlyViewedProducts($this->customer->getId(), $k, $v);
				}
				unset($this->request->cookie['recently_viewed']);
				setcookie('recently_viewed', '', 0, '/', $this->request->server['HTTP_HOST']);
			}
			
			if($product_ids = $this->model_extension_module_recently_viewed->getRecentlyViewedProducts($this->customer->getId(), $setting['limit'], $current_product_id)){
				
				foreach($product_ids as $p){
					$results[] = $p['product_id'];
				}
			}
		} else if(isset($this->request->cookie['recently_viewed']) && !empty($this->request->cookie['recently_viewed'])) {
			$recently_viewed = json_decode(base64_decode($this->request->cookie['recently_viewed']), true);
			// sort by in recent viewed order
			uasort($recently_viewed, function($a, $b){ return strtotime($a) < strtotime($b); });

			// if user is on product detail page then do not show current product in recently_viewed list
			if($current_product_id) {
				unset($recently_viewed[$current_product_id]);
			}
			
			$setting['products'] = array_keys($recently_viewed);
			
			if (!empty($setting['products'])) {
				$results = array_slice($setting['products'], 0, (int)$setting['limit']);
			}
		}
		
		
		$data['products'] = array();

		if ($results) {
			foreach ($results as $result) {
				$data['products'][] = $this->load->controller('component/product_info', $result);
			}

			if(version_compare(VERSION, '2.2.0.0', '>=')) {

				return $this->load->view('extension/module/recently_viewed', $data);

			} else {

				if(file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/recently_viewed.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/module/recently_viewed.tpl', $data);
				} else {
					return $this->load->view('default/template/module/recently_viewed.tpl', $data);
				}

			}
		}
	}
}