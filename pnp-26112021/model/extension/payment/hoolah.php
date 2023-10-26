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

    public function getCurrencies() {
		return array(
			'AUD',
			'BRL',
			'CAD',
			'CZK',
			'DKK',
			'EUR',
			'HKD',
			'HUF',
			'ILS',
			'JPY',
			'MYR',
			'MXN',
			'NOK',
			'NZD',
			'PHP',
			'PLN',
			'GBP',
			'SGD',
			'SEK',
			'CHF',
			'TWD',
			'THB',
			'TRY',
			'USD',
		);
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
	    
	    return $data;
	}

	public function getCallback($order_id) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_callback` WHERE cart_id LIKE '%" . $order_id . "%'");
		return $result->rows[0];
	}
	
	public function getHoolahOrderTransaction($order_id) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE order_id LIKE '%" . $order_id . "%'");
		return $result->rows;
	}
	
	public function getHoolahOrderTransactionId($transaction_id) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE id = '" . $transaction_id . "'");
		return $result->rows[0];
	}
	
	public function getHoolahOrderTransactionUId($transaction_id) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE uuid LIKE '%" . $transaction_id . "%'");
		return $result->rows[0];
	}
	
	public function getHoolahOrderTransactionStatus($order_id, $status) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE order_id LIKE '%" . $order_id . "%' AND status LIKE '%" . $status . "%'");
		return $result->rows[0];
	}
	
	public function addHoolahOrderTransaction($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "hoolah_order_transaction` SET order_id = '" . $this->db->escape($data['order_id']) . "', uuid = '" . $this->db->escape($data['uuid']) . "', status = '" . $this->db->escape($data['status']) . "', orderType = '" . $this->db->escape($data['orderType']) . "', total = '" . $data['total'] . "', tax = '" . $data['tax'] . "', shipping_fee = '" . $data['shipping_fee'] . "', currency = '" . $this->db->escape($data['currency']) . "', createdAt = '" . $data['createdAt'] . "', date_added = '" . $data['date_added'] . "'");
        return true;
	}
	
	public function editHoolahOrderTransaction($id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "hoolah_order_transaction` SET total = '" . $data['total'] . "', tax = '" . $data['tax'] . "', shipping_fee = '" . $data['shipping_fee'] . "', currency = '" . $this->db->escape($data['currency']) . "', requestId = '" . $data['requestId'] . "', code = '" . $data['code'] . "', message = '" . $data['message'] . "', details = '" . $data['details'] . "' WHERE `id` = '" . $id . "'");
		return true;
	}
	
	public function addHoolahOrderTransactionRefund($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "hoolah_order_transaction` SET order_id = '" . $this->db->escape($data['order_id']) . "', uuid = '" . $this->db->escape($data['uuid']) . "', status = '" . $this->db->escape($data['status']) . "', orderType = '" . $this->db->escape($data['orderType']) . "', total = '" . $data['total'] . "', tax = '" . $data['tax'] . "', shipping_fee = '" . $data['shipping_fee'] . "', currency = '" . $this->db->escape($data['currency']) . "', requestId = '" . $data['requestId'] . "', code = '" . $data['code'] . "', message = '" . $data['message'] . "', details = '" . $data['details'] . "', createdAt = '" . $data['createdAt'] . "', date_added = '" . $data['date_added'] . "'");
        return true;
	}
    
	public function getCapturedTotal($order_id) {
		$query = $this->db->query("SELECT SUM(`total`) AS `total` FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE `order_id` LIKE '%" . $order_id . "%' AND (`status` LIKE '%Approved%' OR `status` LIKE '%Complete%' OR `status` LIKE '%Pending%' OR `status` LIKE '%Initiate%')");
        return $query->row['total'];
	}
	
	public function getRefundedTotal($uuid) {
		$query = $this->db->query("SELECT SUM(`total`) AS `total` FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE `uuid` LIKE '%" . $uuid . "%' AND (`status` LIKE '%REJECT%' OR `status` LIKE '%ACCEPT%' OR `status` LIKE '%IN_PROCESS%' OR `status` LIKE '%RETRY_NEEDED%' OR `status` LIKE '%DONE%' OR `status` LIKE '%FAILED%') AND `code` = 'accepted'");
 
		return $query->row['total'];
	}
	
	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT *, pd.name as product_name FROM " . DB_PREFIX . "order_product op LEFT JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = op.product_id WHERE op.order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}
	
	public function getHoolahRefundStatus($uuid, $status) {
		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE `uuid` LIKE '%" . $uuid . "%' AND `status` LIKE '%" . $status . "%'");
		return $result->rows[0];
	}
	
	public function searchHoolahTransaction($data) {
	    
	    $return_data = array();
	    
	    $sql = "SELECT * FROM `" . DB_PREFIX . "hoolah_order_transaction` WHERE "; 
	    
	    if($data['transaction_class'] != 'ALL') {
	        $sql .= "`uuid` LIKE '%" . $data['transaction_id'] . "%' AND `status` LIKE '%" . $data['transaction_class'] . "%' AND `order_id` LIKE '%" . $data['order_id'] . "%'";
	    } else {
	        $sql .= "`uuid` LIKE '%" . $data['transaction_id'] . "%' AND `order_id` LIKE '%" . $data['order_id'] . "%'";
	    }
	    
	    $sql .= " AND `total` LIKE '%" . $data['amount'] . "%'";
	    
	    $sql .= " AND `currency` LIKE '%" . $data['currency_code'] . "%'";
	    
	    $date_start = date('Y-m-d H:i:s', strtotime($data['date_start']));
        $date_end = date('Y-m-d H:i:s', strtotime($data['date_end'] . '23:59:59'));
	    $sql .= " AND (`createdAt` BETWEEN '" . $date_start . "' AND '" . $date_end . "')";
	
	    $hoolah_transactions = $this->db->query($sql);
	    
		foreach($hoolah_transactions->rows AS $key => $values) {
		    
		    foreach($values AS $id => $rows) {
		        $return_data[$key][$id] = $rows;
		        $orderid = explode('-', $values['order_id']);
		        
    		    $order_details = $this->getOrder($orderid[1]);
    		    if(isset($order_details)) {
    		        $return_data[$key]['order_no'] =  $order_details['order_id'];
    		        $return_data[$key]['email'] =  $order_details['email'];
    		        $return_data[$key]['name'] =  ucwords($order_details['firstname']) . ' ' . ucwords($order_details['lastname']);
    		    }
		    }
		    
		}
		
		return $return_data;
	}
	
	
	public function initiate_fullrefund($data) {
	    $uuid = $data['uuid']; 
	    $connect = $this->connect();
	    
	    if ($this->config->get('hoolah_test')) {
	        $api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/' . $uuid . '/full-refund';
        } else {
            $api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/' . $uuid . '/full-refund';
        }

        $pass_data = array(
            'description' => $data['description'],
            'webhookUrl' => $this->url->link('extension/payment/hoolah/initiateRefundWebhook')
        );
        $return = array();
        
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $connect->token;
        $headers[] = 'Content-Type: application/json';
        
        $ch = curl_init($api_url);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pass_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $return = json_decode($result);
        }
        
        curl_close($ch);
	    
	    return $return;
	}
	
	public function initiate_partialrefund($data) {
	    $uuid = $data['uuid']; 
	    $connect = $this->connect();
	    
	    if ($this->config->get('hoolah_test')) {
	        $api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/' . $uuid . '/partial-refund';
        } else {
            $api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/' . $uuid . '/partial-refund';
        }
        $sku = array(
                'sku' => $data['sku']
            );
        $pass_data = array(
            'description' => $data['description'],
            'webhookUrl' => $this->url->link('extension/payment/hoolah/initiateRefundWebhook'),
            'amount' => $data['AMT'],
            'items' => [$sku]
            
        );

        $return = array();
        
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $connect->token;
        $headers[] = 'Content-Type: application/json';
        
        $ch = curl_init($api_url);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pass_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $return = json_decode($result);
        }
        
        curl_close($ch);
	    
	    return $return;
	}
	
	public function checkRefund($uuid) {
	    
	    $connect = $this->connect();
	    
	    if ($this->config->get('hoolah_test')) {
	        if($data['REFUNDTYPE'] == 'Full'){
	            $api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/full-refund/' . $uuid;
	        } else {
	            $api_url = 'https://demo-merchant-service.demo-hoolah.co/merchant/order/partial-refund/' . $uuid;
	        }
        	
        } else {
            if($data['REFUNDTYPE'] == 'Full'){
                $api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/' . $uuid . '/full-refund';
            } else {
                $api_url = 'https://prod-merchant-service.hoolah.co/merchant/order/' . $uuid . '/partial-refund';
            }
        	
        }

        $pass_data = array(
            'description' => $data['description'],
            'webhookUrl' => $this->url->link('extension/payment/hoolah/initiateRefundWebhook')
        );
        $return = array();
        
        $headers = array();
        $headers[] = 'Authorization: Bearer ' . $connect->token;
        $headers[] = 'Content-Type: application/json';
        
        $ch = curl_init($api_url);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($pass_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        $result = curl_exec($ch);
        
        if ($result) {
            $return = json_decode($result);
        }
        
        curl_close($ch);
	    
	    return $return;
	}
	
	public function getOrder($order_id) {
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");

			if ($country_query->num_rows) {
				$payment_iso_code_2 = $country_query->row['iso_code_2'];
				$payment_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$payment_iso_code_2 = '';
				$payment_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$payment_zone_code = $zone_query->row['code'];
			} else {
				$payment_zone_code = '';
			}

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");

			if ($country_query->num_rows) {
				$shipping_iso_code_2 = $country_query->row['iso_code_2'];
				$shipping_iso_code_3 = $country_query->row['iso_code_3'];
			} else {
				$shipping_iso_code_2 = '';
				$shipping_iso_code_3 = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");

			if ($zone_query->num_rows) {
				$shipping_zone_code = $zone_query->row['code'];
			} else {
				$shipping_zone_code = '';
			}

			$reward = 0;

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

			foreach ($order_product_query->rows as $product) {
				$reward += $product['reward'];
			}
			
			if ($order_query->row['affiliate_id']) {
				$affiliate_id = $order_query->row['affiliate_id'];
			} else {
				$affiliate_id = 0;
			}

			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$affiliate_firstname = $affiliate_info['firstname'];
				$affiliate_lastname = $affiliate_info['lastname'];
			} else {
				$affiliate_firstname = '';
				$affiliate_lastname = '';
			}

			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			if ($language_info) {
				$language_code = $language_info['code'];
			} else {
				$language_code = $this->config->get('config_language');
			}

			return array(
				'order_id'                => $order_query->row['order_id'],
				'invoice_no'              => $order_query->row['invoice_no'],
				'invoice_prefix'          => $order_query->row['invoice_prefix'],
				'store_id'                => $order_query->row['store_id'],
				'store_name'              => $order_query->row['store_name'],
				'store_url'               => $order_query->row['store_url'],
				'customer_id'             => $order_query->row['customer_id'],
				'customer'                => $order_query->row['customer'],
				'customer_group_id'       => $order_query->row['customer_group_id'],
				'firstname'               => $order_query->row['firstname'],
				'lastname'                => $order_query->row['lastname'],
				'email'                   => $order_query->row['email'],
				'telephone'               => $order_query->row['telephone'],
				'fax'                     => $order_query->row['fax'],
				'custom_field'            => json_decode($order_query->row['custom_field'], true),
				'payment_firstname'       => $order_query->row['payment_firstname'],
				'payment_lastname'        => $order_query->row['payment_lastname'],
				'payment_company'         => $order_query->row['payment_company'],
				'payment_address_1'       => $order_query->row['payment_address_1'],
				'payment_address_2'       => $order_query->row['payment_address_2'],
				'payment_unit_no'         => $order_query->row['payment_unit_no'],
				'payment_postcode'        => $order_query->row['payment_postcode'],
				'payment_city'            => $order_query->row['payment_city'],
				'payment_zone_id'         => $order_query->row['payment_zone_id'],
				'payment_zone'            => $order_query->row['payment_zone'],
				'payment_zone_code'       => $payment_zone_code,
				'payment_country_id'      => $order_query->row['payment_country_id'],
				'payment_country'         => $order_query->row['payment_country'],
				'payment_iso_code_2'      => $payment_iso_code_2,
				'payment_iso_code_3'      => $payment_iso_code_3,
				'payment_address_format'  => $order_query->row['payment_address_format'],
				'payment_custom_field'    => json_decode($order_query->row['payment_custom_field'], true),
				'payment_method'          => $order_query->row['payment_method'],
				'payment_code'            => $order_query->row['payment_code'],
				'shipping_firstname'      => $order_query->row['shipping_firstname'],
				'shipping_lastname'       => $order_query->row['shipping_lastname'],
				'shipping_company'        => $order_query->row['shipping_company'],
				'shipping_address_1'      => $order_query->row['shipping_address_1'],
				'shipping_address_2'      => $order_query->row['shipping_address_2'],
				'shipping_unit_no'        => $order_query->row['shipping_unit_no'],
				'shipping_postcode'       => $order_query->row['shipping_postcode'],
				'shipping_city'           => $order_query->row['shipping_city'],
				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],
				'shipping_zone'           => $order_query->row['shipping_zone'],
				'shipping_zone_code'      => $shipping_zone_code,
				'shipping_country_id'     => $order_query->row['shipping_country_id'],
				'shipping_country'        => $order_query->row['shipping_country'],
				'shipping_iso_code_2'     => $shipping_iso_code_2,
				'shipping_iso_code_3'     => $shipping_iso_code_3,
				'shipping_address_format' => $order_query->row['shipping_address_format'],
				'shipping_custom_field'   => json_decode($order_query->row['shipping_custom_field'], true),
				'shipping_method'         => $order_query->row['shipping_method'],
				'shipping_code'           => $order_query->row['shipping_code'],
				'comment'                 => $order_query->row['comment'],
				'total'                   => $order_query->row['total'],
				'reward'                  => $reward,
				'order_status_id'         => $order_query->row['order_status_id'],
				'order_status'            => $order_query->row['order_status'],
				'affiliate_id'            => $order_query->row['affiliate_id'],
				'affiliate_firstname'     => $affiliate_firstname,
				'affiliate_lastname'      => $affiliate_lastname,
				'commission'              => $order_query->row['commission'],
				'language_id'             => $order_query->row['language_id'],
				'language_code'           => $language_code,
				'currency_id'             => $order_query->row['currency_id'],
				'currency_code'           => $order_query->row['currency_code'],
				'currency_value'          => $order_query->row['currency_value'],
				'ip'                      => $order_query->row['ip'],
				'forwarded_ip'            => $order_query->row['forwarded_ip'],
				'user_agent'              => $order_query->row['user_agent'],
				'accept_language'         => $order_query->row['accept_language'],
				'date_added'              => $order_query->row['date_added'],
				'date_modified'           => $order_query->row['date_modified'],
				'reward_earn'             => $order_query->row['reward_earn'],
			);
		} else {
			return;
		}
	}
}