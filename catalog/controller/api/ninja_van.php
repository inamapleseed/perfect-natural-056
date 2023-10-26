<?php	
//Ninja Van Send Order - Trnc
class ControllerApiNinjaVan extends Controller {
	public function index($order_info = array()){

		if(!is_array($order_info) ){
			return;
		}
		$postfields = array();
		$from_address = array(); 
		$to_address = array(); 
		$parcel_job = array(); 
		$date = date('Y-m-d');
		$dimensions = array(
			'weight' => 0,
			'length' => 0,
			'width' => 0,
			'height' => 0,
		);


		$this->load->model('localisation/country');
		$this->load->model('checkout/order');

		$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

		$order_products = $this->model_checkout_order->getOrderProducts($order_info['order_id']);

		if($order_products) {
			$weight=0;
			$length=0;
			$width=0;
			$height=0;
			foreach($order_products as $products) {
				$weight += $products['weight'];
				$length += $products['length'];
				$width += $products['width'];
				$height += $products['height'];
				$dimensions = array(
					'weight' => $weight,
					'length' => $length,
					'width' => $width,
					'height' => $height,
				);
			}
		}

		if($this->config->get('config_postcode')) {
			$postcode = $this->config->get('config_postcode');
		}else {
			$postcode = '408694';
		}

		$from_address = array(
			'name' => $this->config->get('config_name'),
			'phone_number' => $this->config->get('config_telephone'),
			'email' => $this->config->get('config_email'),
			'address' => array(
				'address1' => $this->config->get('config_address'),
				'address2' => $order_info['shipping_address_2'],
				'country' => $country['iso_code_2'],
				'postcode' => $postcode,
			),
		);

		$to_address = array(
			'name' => $order_info['shipping_firstname'],
			'phone_number' => $order_info['telephone'],
			'email' => $order_info['email'],
			'address' => array(
				'address1' => $order_info['shipping_address_1'],
				'address2' => $order_info['shipping_address_2'],
				'country' => $order_info['shipping_iso_code_2'],
				'postcode' => $order_info['shipping_postcode'],
			),
		);

		$parcel_job = array(
			'is_pickup_required' => false,
			'delivery_start_date' => $date,
			'delivery_timeslot' => array(
				'start_time' => '09:00',
				'end_time' => '22:00',
				'timezone' => 'Asia/Singapore',
			),
			'delivery_instructions' => 'Order ID:'.$order_info['order_id'].'-'.$order_info['comment'],
			'dimensions'=> array(
				'size' => 'L',
				'width' => $dimensions['width'],
				'length' => $dimensions['length'],
				'height' => $dimensions['height'],
				'weight' => $dimensions['weight']
			),
		);

		$postfields = array(
			'service_type' => 'Parcel',
			'service_level' => 'Standard',
			'requested_tracking_number' => '',
			'reference' => array('merchant_order_number' => $order_info['order_id']),
			'from' => $from_address,
			'to' => $to_address,
			'parcel_job' => $parcel_job
		);
		//Check if it is sandbox
 		$sandbox = $this->config->get('ninja_van_sandbox');
  
		$this->load->model('setting/setting');

		$store_id = 0;
		$setting_token_array = array(
			'njvat_access_token' => '',
			'njvat_expires' => '',
			'njvat_token_type' => 'bearer',
			'njvat_expires_in' => ''
		);
		$timestamp = time(); 

		$saved_token = $this->model_setting_setting->getSetting('njvat', $store_id);

		if($saved_token) {
		  $fiveminutesbefore = strtotime('-5 minutes', $saved_token['njvat_expires']);
			if($timestamp > $fiveminutesbefore) {
				$cached_token = $this->getTokens($sandbox);

				$token_array = json_decode($cached_token, true);

				if(!array_key_exists('Error', $token_array)) {
					$setting_token_array = array(
						'njvat_access_token' => $token_array['access_token'],
						'njvat_expires' => $token_array['expires'],
						'njvat_token_type' => $token_array['token_type'],
						'njvat_expires_in' => $token_array['expires_in']
					);
				}
				$this->model_setting_setting->editSetting('njvat', $setting_token_array, $store_id);	
			}
		}
		else {
	    	$cached_token = $this->getTokens($sandbox);
            
			$token_array = json_decode($cached_token, true);
            
			if(!array_key_exists('Error', $token_array) && !array_key_exists('message', $token_array)) {
				$setting_token_array = array(
					'njvat_access_token' => $token_array['access_token'],
					'njvat_expires' => $token_array['expires'],
					'njvat_token_type' => $token_array['token_type'],
					'njvat_expires_in' => $token_array['expires_in']
				);
				
				$this->model_setting_setting->editSetting('njvat', $setting_token_array, $store_id);
			}

			
		}
		
		if(isset($saved_token) && $saved_token) {
			if(isset($order_info['order_id']) && !empty($order_info['order_id'])) {
				$this->send_order($sandbox, $saved_token['njvat_access_token'], $postfields);
			}
		}
		else {
			$this->log->write('Ninja Van Order not Sent');
		}
	}

	public function getTokens($sandbox) {
		if ($sandbox) {
			$endpoint = 'https://api-sandbox.ninjavan.co/SG/2.0/oauth/access_token';
			$client_id = $this->config->get('ninja_van_client_id');
			$client_key = $this->config->get('ninja_van_client_key');
		} else {
			$endpoint = 'https://api.ninjavan.co/SG/2.0/oauth/access_token';
			$client_id = $this->config->get('ninja_van_client_id');
			$client_key = $this->config->get('ninja_van_client_key');
		}

		$request = array(
			'client_id' => $client_id,
			'client_secret' => $client_key,
			'grant_type' => 'client_credentials',	
		);
		$curl = $this->curl($endpoint, $request);

		return $curl;
	}

	private function curl($endpoint,$request, $resend=0) {
		$trigger_resend = 0;
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		
		curl_setopt($ch, CURLOPT_POST, TRUE);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
		
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/json",
		  "Accept: application/json"
		));
		
		/* if have error - log any error */
		if (curl_errno($ch)) {
			$status_number = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$this->log->write(curl_error($ch) . " ORDERID: " . json_encode($postfields['reference']['merchant_order_number']));
		}

		$response = curl_exec($ch);
		curl_close($ch);

		return $response;
	}


	public function send_order($sandbox,$access_token,$postfields,$resend=0) {
		if ($sandbox) {
			$endpoint = 'https://api-sandbox.ninjavan.co/SG/4.1/orders';
		} else {
			$endpoint = 'https://api.ninjavan.co/SG/4.1/orders';
		}

		$trigger_resend = 0;

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));

			curl_setopt($ch, CURLOPT_FAILONERROR, true); 

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"Accept: application/json",
			"Authorization: Bearer ". $access_token
		));

		$response = curl_exec($ch);

		/* if error 500 or 400 */
			if (curl_errno($ch)) {
				$status_number = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			    $this->log->write(curl_error($ch) . " ORDERID: " . json_encode($postfields['reference']['merchant_order_number']));
			    if ((int)substr($status_number,0,1) == 5) { 
			    	/* if error 5XX */
			    	if ($resend==0) {
			    		/* only resend once to avoid forever loop */
			    		$trigger_resend = 1;
			    	}
			    }
			    if ((int)substr($status_number,0,1) == 4) {
			    	/* if error 4XX */
		    		$trigger_resend = 0;
			    	/* log the posted data */
			    	/* Regenerate token if error 401 */
	    	        if((int)$status_number == 401) {
	    	    	
    			    	$cached_token = $this->getTokens($sandbox);
    
            			$token_array = json_decode($cached_token, true);
            	        $this->log->write($token_array);
            			if(!array_key_exists('Error', $token_array)) {
            				$setting_token_array = array(
            					'njvat_access_token' => $token_array['access_token'],
            					'njvat_expires' => $token_array['expires'],
            					'njvat_token_type' => $token_array['token_type'],
            					'njvat_expires_in' => $token_array['expires_in']
            				);
            			}
            			
            			$store_id = 0;
            
            			$this->model_setting_setting->editSetting('njvat', $setting_token_array, 0);	
			    	}

		    		/* if 400 we will not resend again */
			    	$this->log->write("Response: " . curl_error($ch) . " ORDER request payload : " . json_encode($postfields));
			    }
			} else {
				$this->log->write('Ninja Van Order Successful');
			}

		curl_close($ch);

		if ($trigger_resend) {
			$this->log->write("resent - ORDERID: " . json_encode($postfields['reference']['merchant_order_number']));
    		$this->send_order($sandbox,$access_token,$postfields,1);
		}
		// debug($response);
		 // $this->log->write(json_decode($response));
	}
}
//Ninja Van Send Order - Trnc