<?php
class ControllerExtensionPaymentHoolah extends Controller {
    /*
        Hoolah Integration API
        Empowering consumers to buy now, pay later, while spending responsibly. 0% interest 3 mos installment
        Reference:
        https://howto.onboarding.hoolah.co/bespoke-integration/#1563509311163-8bb29d58-427a
        https://api.hoolah.co/#tag/Getting-startedhttps://api.hoolah.co/#tag/Getting-started
        https://tryout.hoolah.co/
        Developed by: Pauline Janine Laude
    */
	public function index() {
		$this->load->language('extension/payment/hoolah');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('extension/payment/hoolah/checkout', '', true);

		return $this->load->view('extension/payment/hoolah', $data);
	}
	
	public function checkout() {
	    if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}
	    
	    // Gather data
		$this->load->model('extension/payment/hoolah');
		$this->load->model('tool/image');
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        // Sub Total
		$sub_total = $this->cart->getTotal();
		$sub_total = $this->currency->format($sub_total, $this->session->data['currency'], '', false);
		
		// Totals
		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		$this->load->model('extension/extension');

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('extension/total/' . $result['code']);
                // We have to put the totals in an array so that they pass by reference.
			    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
			}
		}

		$sort_order = array();

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);
	    
	    // shipping and payment details
		if ($this->cart->hasShipping()) {
			$data_shipping = array(
				'line1'         => html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8'),
				'line2'         => html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8'),
				'suburb'        => html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8'),
				'postcode'      => html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8'),
				'countryCode'   => $order_info['shipping_iso_code_2']
			);
			
			$data_payment = array(
				'line1'         => html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8'),
				'line2'         => html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'),
				'suburb'        => html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'),
				'postcode'      => html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8'),
				'countryCode'   => $order_info['payment_iso_code_2']
			);
			
		} else {
			$data_shipping = array();
			$data_payment = array();
		}
      
        // country code for phone number extension
        $country_code = $this->model_extension_payment_hoolah->getCountryCode($order_info['payment_country']);
        if(!isset($country_code)) {
            // if country code doesn't have value, then return the default - SG 65
            $country_code = '65';
        }
        
        // gst
        $gst = 0;
        foreach($totals AS $values) { 
            if($values['code'] == 'gst') {
                $gst = number_format($values['value'], 2);
            }
            if (strpos($values['title'], 'GST') !== false) {
                $gst = number_format($values['value'], 2);
            }
        }
        
        // shipping method
        $shippingfee = 0;
        if(isset($this->session->data['shipping_method']['value'])) {
            $shippingfee = $this->session->data['shipping_method']['value'];
        }
        
        // voucher
        $voucher = '';
        if(isset($this->session->data['vouchers'])) {
            $voucher = $this->session->data['vouchers'];
        }
        
        // products
        $product_data = array();
		foreach ($this->cart->getProducts() as $product) {
		    // get product details
		    $result = $this->model_extension_payment_hoolah->getProductDetails($product['product_id']);
		    
		    $originalPrice = $this->tax->calculate($result['price'], $product['tax_class_id'], $this->config->get('config_tax'));
		    $price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		     
		    $dt = array();
		    $dt['name']           = $result['name'];
		    $dt['description']    = $product['name'];
		    $dt['sku']            = $product['sku'];
		    $dt['ean']            = $result['ean'];
		    $dt['quantity']       = $product['quantity'];
		    $dt['originalPrice']  = $originalPrice;
		    $dt['price']          = $price;
		    $dt['images']         = array(
	            array(
    	            'imageLocation' => $result['image'],
    	        ));
		    $dt['taxAmount']      = '';
		    $dt['detailDescription']   = $product['name'];
		    
		    $product_data[] = $dt;
		}
		
		// data
        $cart['consumerEmail'] = $order_info['email'];
	    $cart['consumerFirstName'] = $order_info['firstname'];
	    $cart['consumerMiddleName'] = '';
	    $cart['consumerLastName'] = $order_info['lastname'];
	    $cart['consumerTitle'] = '';
	    $cart['consumerPhoneNumber'] = '+' . $country_code . $order_info['telephone'];
	    $cart['consumerPhoneNumberExtension'] = $country_code;
	    $cart['cartId'] = 'Order-' . $order_info['order_id'];
	    $cart['totalAmount'] = $order_info['total'];
	    $cart['originalAmount'] = $sub_total;
	    $cart['orderType'] = 'ONLINE';
	    $cart['currency'] = $this->config->get('hoolah_currency');
	    $cart['taxAmount'] = $gst;
	    $cart['shippingMethod'] = 'EXPRESS';
	    $cart['shippingAmount'] = $shippingfee;
	    $cart['shippingAddress'] = $data_shipping;
	    $cart['billingAddress'] = $data_payment;
	    $cart['items'] = $product_data;
	    $cart['voucherCode'] = $voucher;
        $cart['closeUrl'] = $this->url->link('quickcheckout/checkout', '', true);
        $cart['returnToShopUrl'] = $this->url->link('extension/payment/hoolah/success', '', true);
        $cart['callbackUrl'] = $this->url->link('extension/payment/hoolah/callback');
     
        // connect to hoolah
	    $connect_result = $this->model_extension_payment_hoolah->connect();
	    
	    // initiate order
		$order_token = $this->model_extension_payment_hoolah->initiate($connect_result, $cart);
	    
	    // open the hoolah page, order token is from initiating order
	    if ($this->config->get('hoolah_test')) {
        	header('Location: https://demo-js.demo-hoolah.co/?ORDER_CONTEXT_TOKEN=' . $order_token->orderContextToken);
        } else {
        	header('Location: https://js.secure-hoolah.co/?ORDER_CONTEXT_TOKEN=' . $order_token->orderContextToken);
        }
	}
	
	public function callback() {
	    $data = file_get_contents("php://input");
	    
	    $decode = json_decode($data, true);
	    
	    $this->load->model('extension/payment/hoolah');
	    
        if(empty($decode['webhook_url'])) {
            $decode['webhook_url'] = '';
        }
        
        if(empty($decode['order_uuid'])) {
            $decode['order_uuid'] = '';
        }
        
        if(empty($decode['cart_id'])) {
            $decode['cart_id'] = '';
        }
        
        if(empty($decode['failure_code'])) {
            $decode['failure_code'] = '';
        }
        
        $this->model_extension_payment_hoolah->saveCallback($decode);
	    
        // $this->log->write($data);
        
        echo 'OK';
	}

	public function success() {
	    $this->load->model('extension/payment/hoolah');
	    $this->load->model('checkout/order');
	    
	    $order_id = 'Order-' . $this->session->data['order_id'];
        $callback_result = $this->model_extension_payment_hoolah->getCallback($order_id);
        
        // save transaction
        $this->model_extension_payment_hoolah->order($callback_result['order_uuid']);
        
        // order history 
        if ($callback_result['order_status'] == 'SUCCESS') {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('hoolah_order_status_id'));
			$this->response->redirect($this->url->link('checkout/success'));
        } 
        else if ($callback_result['order_status'] == 'ERROR') {
			$this->response->redirect($this->url->link('quickcheckout/checkout'));
        }
	}
}