<?php
class ControllerExtensionPaymentDbsPaynowQr extends Controller {
	public function index() {
		$this->load->language('extension/payment/dbs_paynow_qr');

		$data['error'] = false;

		$this->load->model('checkout/order');

		$order_id    = $this->session->data['order_id'];
		$order_info  = $this->model_checkout_order->getOrder($order_id);


		// $order_total = $this->currency->format($order_info['total'], $order_info['currency_code'], '', false);
		$order_total = round($order_info['total'],2);
		$order_total = number_format($order_total, 2, '.', '');  
		// $order_total = "20.05";
		// debuginfo($order_total);exit;


		$data['qrImgBase64'] = false;
		$today = date('Y-m-d H:i:s');
		$datetime = date('YmdHis',strtotime($today . ' + 1 hour')); /* 20200101123456 */
		$orderReferenceNo = sprintf("%07d", $order_id);

		$merchant_id = $this->load->controller('extension/payment/dbs_paynow_qr/getMerchantId');
		$merchantId = sprintf("%05d", $merchant_id);
		$tag62 =  $merchantId . 'MD' . $orderReferenceNo;

		/* create dbs reference no. */
		$sql = "INSERT INTO " . DB_PREFIX . "dbs_order SET order_id = '".$order_id."', customer_reference = '".$this->db->escape($tag62)."' ";
		$this->db->query($sql);

		// save into FDS
		$toFDS = array(
			'order_id' => (int)$order_id,
			'tag62' => $this->db->escape($tag62),
		);
		// save into FDS

		if ($merchant_id && $order_info) {	
	   		
			$postcode = '';
			if(isset($order_info['payment_postcode']) && !empty($order_info['payment_postcode'])) {
				$postcode = $order_info['payment_postcode'];
			}

			$toFDS['dbs_paynow_qr_proxy_value'] = $this->config->get('dbs_paynow_qr_proxy_value'); 
			$toFDS['dbs_paynow_qr_merchant_name'] = $this->config->get('dbs_paynow_qr_merchant_name'); 
			$toFDS['datetime'] = $datetime; 
			$toFDS['order_total'] = $order_total; 
			$toFDS['postcode'] = $postcode; 
			$toFDS['tag62'] = $tag62; 

	   		$info = $this->load->controller('extension/payment/dbs_paynow_qr/saveDBSOrder', $toFDS);

	   		if (isset($info['qr'])) {
	   			$data['qrImgBase64'] = $info['qr'];
	   		}

		} 

		$data['timeout_timing'] = 0;
		if ($this->config->get('dbs_paynow_qr_timeout')) {
			$data['timeout_timing'] = $this->config->get('dbs_paynow_qr_timeout');
		}
		$data['cancel_url'] = $this->url->link('checkout/cart');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_paynow_unavailable'] = $this->language->get('text_paynow_unavailable');
		$data['text_time_out'] = $this->language->get('text_time_out');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['instruction'] = nl2br($this->config->get('dbs_paynow_qr_instruction' . $this->config->get('config_language_id')));
		$data['image'] = $this->config->get('paynow_image');

		$data['continue'] = $this->url->link('checkout/success');

		return $this->load->view('extension/payment/dbs_paynow_qr', $data);
	}

	/**
	 * @return string
	 */
	public function checkStatus() {
		// debug($this->session->data['order_id']);exit;
		$json = array();

		if (isset($this->session->data['order_id']) && !empty($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$this->load->language('extension/payment/dbs_paynow_qr');
			
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info) {
				if ( $order_info['order_status_id'] == $this->config->get('dbs_paynow_qr_order_status_id') ) {
					$json['redirect'] = $this->url->link('checkout/success', '', true);
				}
				if ( in_array($order_info['order_status_id'], $this->config->get('config_cancel_status') ) ){
					$json['redirect'] = $this->url->link('checkout/failure', '', true);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));

	}
	public function addOrderHistory() {
		if (isset($this->request->post) && isset($this->request->post['order_id']) && $this->request->post['order_id']) {
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($this->request->post['order_id'],$this->config->get('dbs_paynow_qr_order_status_id'));
		}
	}


	/* FCSPAY */
	public function getMerchantId() {
		$fds_id = $this->config->get('dbs_paynow_qr_fds_id');

		$data['fds_id'] = $fds_id;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->config->get('dbs_paynow_qr_fds_link')."api.php?action=get_merchant_id",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => http_build_query($data),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);

		if ($this->isJson($response)) {
			$data = json_decode($response);
			return $data->m_id;
		}
		return false;

	}
	private function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
	public function saveDBSOrder($toFDS = array()) {
		if (!$toFDS) {
			return;
		}
		$this->load->model("account/order");
	
		$fds_id = $this->config->get('dbs_paynow_qr_fds_id');
		$fds_password = $this->config->get('dbs_paynow_qr_fds_password');
		$data = $toFDS;
		$data['fds_id'] = $fds_id;
		$signature = $this->makeSign($data, $fds_password);
		$data['signature'] = $signature;
		$data['system'] = "onlineStore";
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->config->get('dbs_paynow_qr_fds_link') . "api.php?action=dbs_order",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => http_build_query($data),
		));

		$response = curl_exec($curl);
		
		// $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// if (curl_errno($curl)) {
		// 	$error = curl_error($curl);
		// 				debug($error);
		// }
		// 				debug($code);
		// 				debuginfo($response);
		// 				debug(curl_errno($curl));
		// 				exit;

        $err = curl_error($curl);
        curl_close($curl);

		$data = array();
		
		if ($err) {
			$data['status'] = 0;
			$data['error'] = 1;
			$data['error_detail'] = $err;
		} else {
			$info = json_decode($response, true);
			if($info['status'] == "200" && $info['qr']){
				$data['status'] = 1;
				$data['qr'] = $info['qr'];
				$data['error'] = 0;
				$data['error_detail'] = "";
			}else{
				$data['status'] = 1;
				$data['error'] = 0;
				$data['error_detail'] = "Fail";
			}
		}
		return $data;
	}
	private function makeSign($data, $shared_key){
        //ksort($data);
   
        $has_nonce = false;
        $buff = [];
        
        foreach ($data as $key => $value) {
            if($key == "order_id" || $key == "firstname" || $key == "lastname" || $key == "telephone" || $key == "email"){
                $buff[] = ($key . '=' . $value);
            }
        }
        
        $buff[] = ('shared_key=' . $shared_key);
        $params = implode("&", $buff);
       
        return hash("sha256", $params);
    }
}