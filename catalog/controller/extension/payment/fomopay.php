<?php
class ControllerExtensionPaymentFomopay extends Controller {
    
	public function index() {
		$this->load->language('extension/payment/fomopay');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('extension/payment/fomopay/checkout', '', true);

		unset($this->session->data['paypal']);

		return $this->load->view('extension/payment/fomopay', $data);
	}

	public function checkout() {
	    if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		
		$this->load->model('extension/payment/fomopay');
		$this->load->model('tool/image');
		$this->load->model('checkout/order');


		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$item_price = 0;
		$order_desc = "Order NO.: ".$order_info['order_id']." ";
		$ori_desc = "Order NO.: ".$order_info['order_id']." ";
		foreach ($this->cart->getProducts() as $item) {
		    $order_desc .= "Product Detail : " . $item['name']. " X ".$item['quantity']." ";
		    $ori_desc .= "Product Detail : " . $item['name']. " X ".$item['quantity']." ";
		    if($item['option']){
		        $order_desc .= "Option Detail : ";
		        $ori_desc .= "Option Detail : ";
		    }
		    foreach ($item['option'] as $option) {
		        if ($option['type'] != 'file') {
					$ori_desc .= " - ".$option['value']." ";
				} else {
					$filename = $this->encryption->decrypt($option['value']);
					$ori_desc .= " - ".utf8_substr($filename, 0, utf8_strrpos($filename, '.'))." ";
				}
		    }
		    $item_price = $this->currency->format($item['price'], $this->session->data['currency'], false, false);
		    $item_total = number_format($item_price * $item['quantity'], 2, '.', '');
		    
		    $order_desc .= "Unit Price : ".$item_price." ";
		    $order_desc .= "Product Total : ".$item_total." ";
		    
		    $ori_desc .= "Unit Price : ".$item_price." ";
		    $ori_desc .= "Product Total : ".$item_total." ";
		}
		
		// Totals
		$this->load->model('extension/extension');
		
		$total_data = array();					
		$total = 0;
		$taxes = $this->cart->getTaxes();
		
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);
		
		// Display prices
		$data['totals'] = array();
		$final_total = 0;
		
		if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			$sort_order = array(); 
			
			$results = $this->model_extension_extension->getExtensions('total');
			
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}
			
			array_multisort($sort_order, SORT_ASC, $results);
			
			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
		
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}
			
			$total_data = $totals;
				
			$sort_order = array(); 
		  
			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $total_data);
			
			foreach ($total_data as $total) {
				//$text = $this->currency->format($total['value'], $this->session->data['currency']);
				if($total['value'] < 0) {
					$text = '-'.$this->currency->format(abs($total['value']), $this->session->data['currency']);
				}
				else {
					$text = $this->currency->format($total['value'], $this->session->data['currency']);
				}	

				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $text
				);
				if($total['code'] == "total"){
				    $final_total = $total['value']; 
				}
			}
		}
		
	    $order_desc .= "Total Detail : ";
	    $ori_desc .= "Total Detail : ";
		foreach($data['totals'] as $total){
		    $order_desc .= " - ".$total['title']." : ".$total['text']." ";
		    $ori_desc .= " - ".$total['title']." : ".$total['text']." ";
		}
		
		if($this->config->get("fomopay_redirect") == 0){
		    $redirect_url = $this->url->link('checkout/success', '', true);
		}else{
		    $redirect_url = $this->url->link('checkout/pending', '', true);
		}
        $callback_url   = $this->url->link('extension/payment/fomopay/checkoutReturn', '', true);
        $currency_code  = strtolower($this->session->data['currency']);
        //$currency_code  = "usd";
        $description    = $order_desc;
        //$description    = "Description";
        $merchant       = $this->config->get('fomopay_username');
        $nonce          = $this->getNonce(20);
        $price          = $final_total;
        $transaction    = $order_info['order_id'];
        $order_id       = $order_info['order_id'];
        $return_url     = $redirect_url;
        $timeout        = '1800';
        $type           = 'sale';
        $shared_key     = $this->config->get('fomopay_password');
        $redirUrl       = "https://gateway.fomopay.com/pgw/v1";
        // $redirUrl       = "https://gateway.fomopay.com/pgw/v1/wxpay";

        
        $isSandbox = $this->config->get('fomopay_test');   //set to false to test the live environment
        
        if ($isSandbox) {
            $merchant   = 'test';
            $shared_key = '00000000';
            $redirUrl   = ("https://gateway.fomopay.com/sandbox/pgw/v1");
            // $redirUrl   = ("https://gateway.fomopay.com/sandbox/pgw/v1/wxpay");
            // $redirUrl   = ("https://gateway.fomopay.com/pgw/v1/alipay");
            // $redirUrl   = ("$callback_url"."&currency_code="."$currency_code"."&description="."$description"."&merchant="."$merchant"."&nonce="."$nonce"."&price="."$price"."&timeout="."$timeout"."&transaction="."$transaction"."&type="."$type");
            // $redirUrl   = ("callback_url="."$callback_url"."&currency_code="."$currency_code"."&description="."$description"."&merchant="."$merchant"."&nonce="."$nonce"."&price="."$price"."&timeout="."$timeout"."&transaction="."$transaction"."&type="."$type"."&shared_key="."$shared_key");
            // $redirUrl   = ("https://gateway.fomopay.com/pgw/v1/wxpay/"."&merchant="."$merchant"."&price="."$price"."&description="."$description"."&transaction="."$transaction"."callback_url="."$callback_url"."&currency_code="."$currency_code"."&type="."$type"."&timeout="."$timeout"."&nonce="."$nonce"."&signature="."$signature");
            // b787be44b659fb778ad5bb474087943bb3cd9e4414f6715a27f97d339a0a937b --> hex
        }

        $signResource = [];
        $signResource['callback_url']   = $callback_url;
        $signResource['currency_code']  = "sgd";
        $signResource['description']    = $description;
        $signResource['merchant']       = $merchant;
        $signResource['nonce']          = $nonce;
        $signResource['price']          = $price;
        $signResource['return_url']     = $return_url;
        $signResource['timeout']        = $timeout;
        $signResource['transaction']    = $transaction;
        $signResource['type']           = $type;
        
        $sign = $this->makeSign($signResource, $shared_key);
        
        $postData = $signResource;
        $postData['signature'] = $sign;
        
        $sql = "INSERT into " .DB_PREFIX. "fomopay_request (`callback_url`, `currency_code`, `description`, `ori_description`, `merchant`, `shared_key`, `price`, `transaction`, `return_url`, `timeout`, `type`, `nonce`, `redirect_url`, `signature`, `order_id`)";
        $sql .= "VALUES ('" . $this->db->escape($callback_url) . "', '" . $this->db->escape($currency_code) . "', '" . $this->db->escape($description) . "', '" . $this->db->escape($ori_desc) ."', '" . $this->db->escape($merchant) ."', '" . $this->db->escape($shared_key) . "', '" . $this->db->escape($price) . "', '" . $this->db->escape($transaction) . "', '" . $this->db->escape($return_url) . "', '" . $this->db->escape($timeout) . "', '" . $this->db->escape($type) . "', '" . $this->db->escape($nonce) . "', '" . $this->db->escape($redirUrl)."', '" . $this->db->escape($sign) ."', '".$this->db->escape($order_id)."')";
        $this->db->query($sql);
        
        $this->redirectPost($redirUrl, $postData);
    }

    public function checkoutReturn(){
        $this->log->write($this->request->post);
        $validate_sign = false;
        
        $transaction    = $this->request->post['transaction'];
        $payment_id     = $this->request->post['payment_id'];
        $result         = $this->request->post['result'];
        $nonce          = $this->request->post['nonce'];
        $upstream       = $this->request->post['upstream'];
        $signature      = $this->request->post['signature'];
        
        $sql = "INSERT INTO " .DB_PREFIX. "fomopay_response (`transaction`, `payment_id`, `result`, `nonce`, `upstream`, `signature`, `status`)";
        $sql .= "VALUES ('" . $this->db->escape($transaction) . "', '" . $this->db->escape($payment_id) . "', '" . $this->db->escape($result) . "', '" . $this->db->escape($nonce) . "', '" . $this->db->escape($upstream) . "', '" . $this->db->escape($signature) . "', '0')";
        
        $this->db->query($sql);
        $fomopay_response_id = $this->db->getLastId();
        
        $sql = "SELECT * FROM " . DB_PREFIX . "fomopay_request WHERE transaction = '" . $transaction . "'";
        $fomo_info = $this->db->query($sql);
        if($fomo_info->num_rows > 0){
            $order_id = $fomo_info->row['order_id'];
        $this->log->write($order_id);
            $signResource = [];
            $signResource['transaction']    = $transaction;
            $signResource['payment_id']     = $payment_id;
            $signResource['result']         = $result;
            $signResource['nonce']          = $nonce;
            $signResource['upstream']       = $upstream;
            
            $shared_key = $this->config->get('fomopay_password');
            $isSandbox = $this->config->get('fomopay_test');   //set to false to test the live environment
        
            if ($isSandbox) {
                $shared_key = '00000000';
            }
            
            $sign = $this->makeSign($signResource, $shared_key);
            //if($sign == $signature){
                $updateFomo = "UPDATE " . DB_PREFIX . "fomopay_response SET status = 1 WHERE fomopay_response_id = '" . $fomopay_response_id . "'";
                $this->db->query($updateFomo);
                
                $this->load->model('checkout/order');
                if($result == 0){
                    $order_status_id = $this->config->get('fomopay_completed_status_id');
                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
                }else{
                    $order_status_id = $this->config->get('fomopay_failed_status_id');
                    $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
                }
                echo "OK";die;
            //}else{
            //    echo "Error 2";die;
            //}
        }else{
            echo "Error 1";die;
        }
    }
    
    private function redirectPost($urls, $post) {
    ?>
      <html xmlns="http://www.w3.org/1999/xhtml">
          <body onLoad="document.fomopay.submit();">
              <form name="fomopay" action="<?= $urls ?>" method="post" enctype="application/x-www-form-urlencoded">
                  <input type="hidden" name="merchant" value="<?= htmlspecialchars($post['merchant']) ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($post['price']) ?>">
                  <input type="hidden" name="description" value="<?= htmlspecialchars($post['description']) ?>">
                    <input type="hidden" name="transaction" value="<?= htmlspecialchars($post['transaction']) ?>">
                  <input type="hidden" name="return_url" value="<?= htmlspecialchars($post['return_url']) ?>">
                  <input type="hidden" name="callback_url" value="<?= htmlspecialchars($post['callback_url']) ?>">
                  <input type="hidden" name="currency_code" value="<?= htmlspecialchars($post['currency_code']) ?>">
                  <input type="hidden" name="type" value="<?= htmlspecialchars($post['type']) ?>">
                  <input type="hidden" name="timeout" value="<?= htmlspecialchars($post['timeout']) ?>">
                  <input type="hidden" name="nonce" value="<?= htmlspecialchars($post['nonce']) ?>">
                  <input type="hidden" name="signature" value="<?= htmlspecialchars($post['signature']) ?>">
              </form>
          </body>
      </html>
    <?php
    
    }

    private function getNonce($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    private function makeSign($payload, $shared_key) {
        ksort($payload);
        $has_nonce = false;
        $buff = [];
        foreach ($payload as $key => $value) {
            if ($key == 'nonce') {
                $has_nonce = true;
            }
       
            if ($key == 'signature') {
                continue;
            }
            $buff[] = ($key . '=' . $value);
        }
       
        if (!$has_nonce) {
            // Refuse to sign because empty nonce may introduce security issues.
            throw new Exception('Cannot sign payload without nonce.');
        }
        $buff[] = ('shared_key=' . $shared_key);
        $params = implode("&", $buff);
        return hash("sha256", $params);
    }
}
?>