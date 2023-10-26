<?php
class ModelExtensionPaymentHoolah extends Model {
    /*
        Hoolah Integration API
        Empowering consumers to buy now, pay later, while spending responsibly. 0% interest 3 mos installment
        Reference:
        https://howto.onboarding.hoolah.co/bespoke-integration/#1563509311163-8bb29d58-427a
        https://api.hoolah.co/#tag/Getting-startedhttps://api.hoolah.co/#tag/Getting-started
        https://tryout.hoolah.co/
        Developed by: Pauline Janine Laude
    */
    public function getMethod($address, $total) {
		$this->load->language('extension/payment/hoolah');

		$method_data = array(
			'code'       => 'hoolah',
			'title'      => $this->language->get('text_title'),
			'terms'      => '',
			'sort_order' => $this->config->get('hoolah_sort_order')
		);

		return $method_data;
	}

    public function connect() {
        $connect_result = array();
        
        if ($this->config->get('hoolah_test')) {
        	$api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/auth/login';
        	$merchant_id = $this->config->get('hoolah_merchant_id');
        	$merchant_key = $this->config->get('hoolah_merchant_key_test');
        } else {
        	$api_url = 'https://prod-merchant-service.hoolah.co/merchant/auth/login';
        	$merchant_id = $this->config->get('hoolah_merchant_id');
        	$merchant_key = $this->config->get('hoolah_merchant_key_live');
        }
        
        $settings = array(
        	'username'         => $merchant_id,
        	'password'         => $merchant_key,
        );
        
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($settings));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $connect_result = json_decode($result);
        }
        
        curl_close($ch);
	    
	    return $connect_result;
	}
	
	public function initiate($connect_result, $cart) {
	    $order_token = array();
	    
        if ($this->config->get('hoolah_test')) {
        	$api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/initiate';
        } else {
        	$api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/initiate';
        }
        
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $connect_result->token;
        $headers[] = 'Content-Type: application/json';
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cart));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $order_token = json_decode($result);
        }
        
        curl_close($ch);
	    
	    return $order_token;
	}
	
	public function order($uuid) {
	    
	    $data = array();
	    $connect = $this->connect();
	    
	    if ($this->config->get('hoolah_test')) {
        	$api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/' . $uuid;
        } else {
        	$api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/' . $uuid;
        }

        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $connect->token;
        $headers[] = 'Content-Type: application/json';

        $ch = curl_init($api_url);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $data = json_decode($result);
        }
        
        curl_close($ch);
	    
	    $return = array();
        $return['order_id'] = $data->merchantRef;
        $return['uuid'] = $data->uuid;
        $return['status'] = $data->status;
        $return['orderType'] = $data->orderType;
        $return['total'] = $data->totalAmount;
        $return['tax'] = $data->taxAmount;
        $return['shipping_fee'] = $data->shippingAmount;
        $return['currency'] = $data->currency;
        $return['createdAt'] = date('Y-m-d H:i:s', strtotime($data->createdAt));
        $return['date_added'] = date('Y-m-d H:i:s');
        
        $this->addHoolahOrderTransaction($return);
        
	    return true;
	}
	
    public function getCountryCode($country) {
        $result = $this->db->query("SELECT country_code FROM `" . DB_PREFIX . "country` WHERE name LIKE '%" . $this->db->escape($country) . "%'");
        return $result->rows[0]['country_code'];
    }
    
    public function getProductDetails($id) {
        $result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` `p` LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON `pd`.`product_id` = `p`.`product_id` WHERE p.product_id = '" . (int)($id) . "'");
        return $result->rows[0];
    }
    
    public function getOrderId($order_id) {
        return $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . $order_id. "'");
    }
    
    public function saveCallback($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "hoolah_callback` SET order_status = '" . $this->db->escape($data['order_status']) . "', cart_id = '" . $this->db->escape($data['cart_id']) . "', webhook_url = '" . $this->db->escape($data['webhook_url']) . "', order_uuid = '" . $this->db->escape($data['order_uuid']) . "', order_context_token = '" . $this->db->escape($data['order_context_token']) . "', failure_code = '" . $this->db->escape($data['failure_code']) . "', date_added = '" . date('Y-m-d H:s') . "'");
        return true;
	}
	
	public function addHoolahOrderTransaction($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "hoolah_order_transaction` SET order_id = '" . $data['order_id'] . "', uuid = '" . $this->db->escape($data['uuid']) . "', status = '" . $data['status'] . "', orderType = '" . $data['orderType'] . "', total = '" . $data['total'] . "', tax = '" . $data['tax'] . "', shipping_fee = '" . $data['shipping_fee'] . "', currency = '" . $this->db->escape($data['currency']) . "', createdAt = '" . $data['createdAt'] . "', date_added = '" . $data['date_added'] . "'");
        return true;
	}
	
	public function getCallback($order_id) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_callback` WHERE cart_id LIKE '%" . $order_id . "%'");
		return $result->rows[0];
	}
	
}