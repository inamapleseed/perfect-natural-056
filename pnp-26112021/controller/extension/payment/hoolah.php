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
    private $error = array();
    
    public function index() {

		$this->checkIfInstalledDB();

		$this->load->language('extension/payment/hoolah');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hoolah', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title']              = $this->language->get('heading_title');

		$data['text_edit']                  = $this->language->get('text_edit');
		$data['text_enabled']               = $this->language->get('text_enabled');
		$data['text_disabled']              = $this->language->get('text_disabled');
		$data['text_yes']                   = $this->language->get('text_yes');
		$data['text_no']                    = $this->language->get('text_no');

        $data['tab_api']                    = $this->language->get('tab_api');
        $data['tab_instruction']            = $this->language->get('tab_instruction');
        
		$data['entry_merchant_id']          = $this->language->get('entry_merchant_id');
		$data['entry_merchant_key_live']    = $this->language->get('entry_merchant_key_live');
		$data['entry_merchant_key_test']    = $this->language->get('entry_merchant_key_test');
		$data['entry_cdn_id']               = $this->language->get('entry_cdn_id');
		$data['entry_currency']             = $this->language->get('entry_currency');
		$data['entry_status']               = $this->language->get('entry_status');
		$data['entry_sort_order']           = $this->language->get('entry_sort_order');
		$data['entry_order_status']         = $this->language->get('entry_order_status');
		$data['entry_refund_status']        = $this->language->get('entry_refund_status');
		$data['entry_test']                 = $this->language->get('entry_test');
	
	    $data['help_currency']              = $this->language->get('help_currency');
	    $data['help_order_status']          = $this->language->get('help_order_status');
	    $data['help_test']                  = $this->language->get('help_test');
	    
		$data['button_save']                = $this->language->get('button_save');
		$data['button_cancel']              = $this->language->get('button_cancel');
		$data['button_search']              = $this->language->get('button_search');

		$data['token']                      = $this->session->data['token'];

        if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['hoolah_merchant_id'])) {
			$data['error_hoolah_merchant'] = $this->error['hoolah_merchant_id'];
		} else {
			$data['error_hoolah_merchant'] = '';
		}
		
		if (isset($this->error['hoolah_merchant_key_live'])) {
			$data['error_merchant_key_live'] = $this->error['hoolah_merchant_key_live'];
		} else {
			$data['error_merchant_key_live'] = '';
		}
		
		if (isset($this->error['hoolah_merchant_key_test'])) {
			$data['error_merchant_key_test'] = $this->error['hoolah_merchant_key_test'];
		} else {
			$data['error_merchant_key_test'] = '';
		}
		
		if (isset($this->error['hoolah_cdn_id'])) {
			$data['error_hoolah_cdn_id'] = $this->error['hoolah_cdn_id'];
		} else {
			$data['error_hoolah_cdn_id'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/hoolah', 'token=' . $this->session->data['token'], true),
		);
        
        if (isset($this->request->post['hoolah_test'])) {
			$data['hoolah_test'] = $this->request->post['hoolah_test'];
		} else {
			$data['hoolah_test'] = $this->config->get('hoolah_test');
		}
		
		if (isset($this->request->post['hoolah_merchant_id'])) {
			$data['hoolah_merchant_id'] = $this->request->post['hoolah_merchant_id'];
		} else {
			$data['hoolah_merchant_id'] = $this->config->get('hoolah_merchant_id');
		}

		if (isset($this->request->post['hoolah_merchant_key_live'])) {
			$data['hoolah_merchant_key_live'] = $this->request->post['hoolah_merchant_key_live'];
		} else {
			$data['hoolah_merchant_key_live'] = $this->config->get('hoolah_merchant_key_live');
		}
		
		if (isset($this->request->post['hoolah_merchant_key_test'])) {
			$data['hoolah_merchant_key_test'] = $this->request->post['hoolah_merchant_key_test'];
		} else {
			$data['hoolah_merchant_key_test'] = $this->config->get('hoolah_merchant_key_test');
		}
		
		if (isset($this->request->post['hoolah_cdn_id'])) {
			$data['hoolah_cdn_id'] = $this->request->post['hoolah_cdn_id'];
		} else {
			$data['hoolah_cdn_id'] = $this->config->get('hoolah_cdn_id');
		}
		
        if (isset($this->request->post['hoolah_currency'])) {
			$data['hoolah_currency'] = $this->request->post['hoolah_currency'];
		} else {
			$data['hoolah_currency'] = $this->config->get('hoolah_currency');
		}
		
		if (isset($this->request->post['hoolah_order_status_id'])) {
			$data['hoolah_order_status_id'] = $this->request->post['hoolah_order_status_id'];
		} else {
			$data['hoolah_order_status_id'] = $this->config->get('hoolah_order_status_id');
		}
		
		if (isset($this->request->post['hoolah_refund_status_id'])) {
			$data['hoolah_refund_status_id'] = $this->request->post['hoolah_refund_status_id'];
		} else {
			$data['hoolah_refund_status_id'] = $this->config->get('hoolah_refund_status_id');
		}
		
		if (isset($this->request->post['hoolah_sort_order'])) {
			$data['hoolah_sort_order'] = $this->request->post['hoolah_sort_order'];
		} else {
			$data['hoolah_sort_order'] = $this->config->get('hoolah_sort_order');
		}
		
		if (isset($this->request->post['hoolah_status'])) {
			$data['hoolah_status'] = $this->request->post['hoolah_status'];
		} else {
			$data['hoolah_status'] = $this->config->get('hoolah_status');
		}
		
		$data['action'] = $this->url->link('extension/payment/hoolah', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		
        $this->load->model('extension/payment/hoolah');
        
        $data['currencies'] = $this->model_extension_payment_hoolah->getCurrencies();
        
        $this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/hoolah', $data));
	}

    protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/hoolah')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['hoolah_merchant_id']) {
			$this->error['hoolah_merchant_id'] = $this->language->get('error_hoolah_merchant');
		}

        if (!$this->request->post['hoolah_merchant_key_live']) {
			$this->error['hoolah_merchant_key_live'] = $this->language->get('error_merchant_key_live');
		}
		
		if (!$this->request->post['hoolah_merchant_key_test']) {
			$this->error['hoolah_merchant_key_test'] = $this->language->get('error_merchant_key_test');
		}

		if (!$this->request->post['hoolah_cdn_id']) {
			$this->error['hoolah_cdn_id'] = $this->language->get('error_hoolah_cdn_id');
		}

		return !$this->error;
	}
	
	public function order() {
	    // sales order info - order history tab
	    /* get an update about the order details from hoolah api */
	    $this->load->language('extension/payment/hoolah');
	    $this->load->model('extension/payment/hoolah');
	    $this->load->model('sale/order');
	    
	    if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
	
		// get the callback info
		$order_mid = 'Order-' . $order_id;
		$callback_result = $this->model_extension_payment_hoolah->getCallback($order_mid);
	
        // call hoolah api
        $hoolah_info = $this->model_extension_payment_hoolah->order($callback_result['order_uuid']);
      
        $infos = array();
        $infos['order_id']      = $hoolah_info->merchantRef;
        $infos['uuid']          = $hoolah_info->uuid;
        $infos['status']        = $hoolah_info->status;
        $infos['orderType']     = $hoolah_info->orderType;
        $infos['total']         = $hoolah_info->totalAmount;
        $infos['tax']           = $hoolah_info->taxAmount;
        $infos['shipping_fee']  = $hoolah_info->shippingAmount;
        $infos['currency']      = $hoolah_info->currency;
        $infos['createdAt']     = date('Y-m-d H:i:s', strtotime($hoolah_info->createdAt));
        $infos['date_added']    = date('Y-m-d H:i:s');
        
        // if the order is refunded, check the status of refund in api
        $refund_status = $this->model_extension_payment_hoolah->getHoolahRefundStatus($callback_result['order_uuid'], 'ACCEPTED');
        
        // provide other details to save in transaction
        if(isset($refund_status)) {
            $infos['total']     = $refund_status['total'];
            $infos['requestId'] = $refund_status['requestId'];
            $infos['code']      = $refund_status['code'];
            $infos['message']   = $refund_status['message'];
            $infos['details']   = $refund_status['details'];
        }
       
        // check if not existing
        $order_transaction = $this->model_extension_payment_hoolah->getHoolahOrderTransactionStatus($hoolah_info->merchantRef, $hoolah_info->status);
        if(count($order_transaction) == 0) {
            // if not existing, insert
            $this->model_extension_payment_hoolah->addHoolahOrderTransaction($infos);
        } else {
            // if existing, update
            $this->model_extension_payment_hoolah->editHoolahOrderTransaction($order_transaction['id'], $infos);
        }
        
        if(isset($order_transaction)) {
            $data['text_extension']         = $this->language->get('text_extension');
			$data['text_transaction']       = $this->language->get('text_transaction');
			$data['text_capture_status']    = $this->language->get('text_capture_status');
			$data['text_amount_authorised'] = $this->language->get('text_amount_authorised');
			$data['text_total_amount_captured']   = $this->language->get('text_total_amount_captured');
			$data['text_amount_captured']   = $this->language->get('text_amount_captured');
			$data['text_amount_refunded']   = $this->language->get('text_amount_refunded');
			$data['text_confirm_void']      = $this->language->get('text_confirm_void');
			$data['text_full_refund']       = $this->language->get('text_full_refund');
			$data['text_partial_refund']    = $this->language->get('text_partial_refund');
			$data['text_loading']           = $this->language->get('text_loading');

			$data['entry_capture_amount']   = $this->language->get('entry_capture_amount');
			$data['entry_capture_complete'] = $this->language->get('entry_capture_complete');
			$data['entry_full_refund']      = $this->language->get('entry_full_refund');
			$data['entry_note']             = $this->language->get('entry_note');
			$data['entry_amount']           = $this->language->get('entry_amount');

			$data['button_capture']         = $this->language->get('button_capture');
			$data['button_refund']          = $this->language->get('button_refund');
			$data['button_void']            = $this->language->get('button_void');

			$data['tab_capture']            = $this->language->get('tab_capture');
			$data['tab_refund']             = $this->language->get('tab_refund');

			$data['token']                  = $this->session->data['token'];

			$data['order_id']               = $this->request->get['order_id'];
            
          	$data['status'] = $order_transaction['status'];

			$data['total'] = $order_transaction['total'];
			
			// total order
			$captured = number_format($this->model_extension_payment_hoolah->getCapturedTotal($order_transaction['order_id']), 2);
			
			$orders = $this->model_sale_order->getOrder($this->request->get['order_id']);
			
            if(number_format($orders['total'], 2) != $captured) {
                $captured = number_format($orders['total'], 2);
            }
			$data['captured'] = $captured;
            
            // refund order
// 			$refunded = number_format($this->model_extension_payment_hoolah->getRefundedTotal($order_transaction['uuid']), 2);
// 			$data['refunded'] = $refunded;
			
			$hoolah_info = $this->model_extension_payment_hoolah->getHoolahOrderTransaction($this->request->get['order_id']);
            $refund = array();
    		if (isset($hoolah_info)) {
    			foreach ($hoolah_info as $key => $result) {
        		    // refund
        		    if($result['status'] == 'ACCEPTED') {
        		        $refund[] = $result['total'];
        		    }
    			}
    		} 
    		if(!empty($refund)) {
			    $data['refunded']   = number_format(array_sum($refund), 2);
			} 
			$data['total_amount'] = number_format($orders['total'], 2);
			
			return $this->load->view('extension/payment/hoolah_order', $data);
        }
	}
	
	public function transaction() {
	    // sales order info - order history tab
		$this->load->language('extension/payment/hoolah');
		$this->load->model('extension/payment/hoolah');
		$this->load->model('sale/order');

		$data['text_no_results']        = $this->language->get('text_no_results');

		$data['column_transaction']     = $this->language->get('column_transaction');
		$data['column_amount']          = $this->language->get('column_amount');
		$data['column_type']            = $this->language->get('column_type');
		$data['column_status']          = $this->language->get('column_status');
		$data['column_pending_reason']  = $this->language->get('column_pending_reason');
		$data['column_date_added']      = $this->language->get('column_date_added');
		$data['column_action']          = $this->language->get('column_action');

		$data['button_view']            = $this->language->get('button_view');
		$data['button_refund']          = $this->language->get('button_refund');
		$data['button_resend']          = $this->language->get('button_resend');

		if (isset($this->request->get['order_id'])) {
			$order_id = 'Order-' . $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		$orders = $this->model_sale_order->getOrder($this->request->get['order_id']);
		
		$data['transactions'] = array();
		
		$hoolah_info = $this->model_extension_payment_hoolah->getHoolahOrderTransaction($order_id);
    
		if (isset($hoolah_info)) {
		    $refund = array();
			foreach ($hoolah_info as $key => $result) {
    		    // refund
    		    if($result['status'] == 'ACCEPTED') {
    		        $refund[] = $result['total'];
    		    }
			    $transac = array();
				$transac[$key] = $result;
				$transac[$key]['view'] = $this->url->link('extension/payment/hoolah/info', 'token=' . $this->session->data['token'] . '&tid=' . $result['id'] . '&order_id=' . $this->request->get['order_id'], true);
				$transac[$key]['refund'] = $this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'] . '&tid=' . $result['id']. '&order_id=' . $this->request->get['order_id'], true);
				$data['transactions'] = $transac;
			}
		    
		    $data['total_refund']   = number_format(array_sum($refund), 2);
		    
		    $data['total_amount']   = number_format($orders['total'], 2);
		    
		} 
		$this->response->setOutput($this->load->view('extension/payment/hoolah_transaction', $data));
	}
	
	public function info() {
	    // transaction info
		$this->load->language('extension/payment/hoolah_view');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title']          = $this->language->get('heading_title');

		$data['text_product_lines']     = $this->language->get('text_product_lines');
		$data['text_order_id']          = $this->language->get('text_order_id');
		$data['text_uuid']              = $this->language->get('text_uuid');
		$data['text_buyer_email']       = $this->language->get('text_buyer_email');
		$data['text_payment_method']    = $this->language->get('text_payment_method');
		$data['text_payment_status']    = $this->language->get('text_payment_status');
		$data['text_order_type']        = $this->language->get('text_order_type');
		$data['text_order_time']        = $this->language->get('text_order_time');
		$data['text_firstname']         = $this->language->get('text_firstname');
		$data['text_lastname']          = $this->language->get('text_lastname');
	    $data['text_shipping_address']  = $this->language->get('text_shipping_address');
		$data['text_shipping_unit_no']  = $this->language->get('text_shipping_unit_no');
		$data['text_shipping_postcode'] = $this->language->get('text_shipping_postcode');
		$data['text_shipping_country']  = $this->language->get('text_shipping_country');
		$data['text_amount']            = $this->language->get('text_amount');
		$data['text_currency_code']     = $this->language->get('text_currency_code');
		$data['text_tax']               = $this->language->get('text_tax');
		$data['text_shipping']          = $this->language->get('text_shipping');
	    $data['text_refund_lines']     = $this->language->get('text_refund_lines');
	    
		$data['button_cancel']          = $this->language->get('button_cancel');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_hoolah'),
			'href' => $this->url->link('extension/payment/hoolah', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/hoolah/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], true),
		);

		$this->load->model('extension/payment/hoolah');
		$this->load->model('sale/order');
        
        $order_id = $this->request->get['order_id'];
        
        $data['orders'] = $this->model_sale_order->getOrder($order_id);
        
	    // if there's a refund value
	    if (isset($this->request->get['order_id'])) {
			$orderid = 'Order-' . $this->request->get['order_id'];
		} else {
			$orderid = 0;
		}
		
	    $hoolah_info = $this->model_extension_payment_hoolah->getHoolahOrderTransaction($orderid);
	    $totalval = 0;
	    $refund = array();
	    foreach ($hoolah_info as $key => $result) {
	        // success order
	        if($result['status'] == 'APPROVED') {
		        $data['success_status'] = $result['status'];
		    }
		    // refund
		    if($result['status'] == 'ACCEPTED') {
		        $refund[] = $result['total'];
		    }
	    }
	    $data['total_refund'] = 0;
	    if(!empty($refund)) {
	        $data['total_refund']   = number_format(array_sum($refund), 2);
	    }
	    
	    if(number_format($data['orders']['total'], 2) == $data['total_refund']) {
	        $data['refund_status'] = 'FULL REFUNDED';
	    } else {
	        $data['refund_status'] = 'PARTIALLY REFUNDED';
	    }
		// end if there's a refund value
	    
	    $data['transaction'] = $this->model_extension_payment_hoolah->getHoolahOrderTransactionId($this->request->get['tid']);
		
		$data['order_products'] = $this->model_extension_payment_hoolah->getOrderProducts($order_id);
		
		$data['view_link'] = $this->url->link('extension/payment/hoolah/info', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/payment/hoolah/search', 'token=' . $this->session->data['token'], true);
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/hoolah_view', $data));
	}
    
    public function refund() {
        // sales order refund page
		$this->load->language('extension/payment/hoolah_refund');
		$this->load->model('extension/payment/hoolah');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title']          = $this->language->get('heading_title');
		$data['button_cancel']          = $this->language->get('button_cancel');
		$data['entry_transaction_id']   = $this->language->get('entry_transaction_id');
		$data['entry_full_refund']      = $this->language->get('entry_full_refund');
		$data['entry_amount']           = $this->language->get('entry_amount');
		$data['entry_message']          = $this->language->get('entry_message');
		$data['button_refund']          = $this->language->get('button_refund');
		$data['text_refund']            = $this->language->get('text_refund');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_hoolah'),
			'href' => $this->url->link('extension/payment/hoolah', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'], true),
		);
        
		//button actions
		$data['action'] = $this->url->link('extension/payment/hoolah/doRefund', 'token=' . $this->session->data['token'] . '&tid=' . $this->request->get['tid'] . '&order_id=' . $this->request->get['order_id'], true);
		$data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], true);

		$transaction_id = $this->request->get['tid'];
        
        $transaction = $this->model_extension_payment_hoolah->getHoolahOrderTransactionId($transaction_id);
        
        $data['order_id'] = $this->request->get['order_id'];
        $data['transaction_id'] = $transaction['uuid'];
		$data['total'] = $transaction['total'];
		$data['currency_code'] = $transaction['currency'];

		$refunded = number_format($this->model_extension_payment_hoolah->getRefundedTotal($transaction['uuid']), 2);
		
		$this->load->model('sale/order');
        $order = $this->model_sale_order->getOrder($this->request->get['order_id']);

		if ($refunded != 0.00) {
			$data['refund_available'] = number_format($order['total'] - $refunded, 2);
			$data['attention'] = $this->language->get('text_current_refunds') . ': ' . $data['refund_available'];
		} else {
			$data['refund_available'] = '';
			$data['attention'] = '';
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/hoolah_refund', $data));
	}
	
	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		
		$this->load->language('extension/payment/hoolah_refund');
		if (!empty($this->request->post['transaction_id']) && isset($this->request->post['refund_full']) && !empty($this->request->post['description'])) {
			$this->load->model('extension/payment/hoolah');

			if ($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_partial_amt');
				$this->response->redirect($this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'] . '&tid=' . $this->request->get['tid'] . '&order_id=' . $this->request->get['order_id'], true));
			} else {
				$order_transaction = $this->model_extension_payment_hoolah->getHoolahOrderTransactionStatus($this->request->get['order_id'], 'APPROVED');
			  
			    // get sku order
		        $orders = $this->model_extension_payment_hoolah->getOrderProducts($this->request->get['order_id']);
		
				if (isset($order_transaction)) {
				    $call_data['transaction_id']    = $order_transaction['id'];
				    $call_data['uuid']              = $order_transaction['uuid'];
				    $call_data['description']       = $this->request->post['description'];
				    $call_data['order_id']          = $this->request->post['order_id'];
				    
					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE']    = 'Full';
						$call_data['AMT']           = $order_transaction['total'];
					
						// submit to hoolah api
					    $result = $this->model_extension_payment_hoolah->initiate_fullrefund($call_data);
					} else {
						$call_data['REFUNDTYPE']    = 'Partial';
						$call_data['AMT']           = $this->request->post['amount'];
						$call_data['sku']           = $orders[0]['sku'];
						$call_data['CURRENCYCODE']  = $this->request->post['currency_code'];
						
						// submit to hoolah api
					    $result = $this->model_extension_payment_hoolah->initiate_partialrefund($call_data);
					}
					$refund_details = array();
                    foreach($result->details AS $rows) {
                        $refund_details[] = $rows;
                    }
                    
                    $refund = array();
                    $refund['order_id']     = $order_transaction['order_id'];
                    $refund['uuid']         = $order_transaction['uuid'];
                    $refund['status']       = $result->status;
                    $refund['orderType']    = $order_transaction['orderType'];
                    $refund['total']        = $call_data['AMT'];
                    $refund['tax']          = $order_transaction['tax'];
                    $refund['shipping_fee'] = $order_transaction['shipping_fee'];
                    $refund['currency']     = $order_transaction['currency'];
                    $refund['requestId']    = $result->requestId;
                    $refund['code']         = $result->code;
                    $refund['message']      = $result->message;
                    $refund['details']      = json_encode($refund_details);
                    $refund['createdAt']    = date('Y-m-d H:i:s');
                    $refund['date_added']   = date('Y-m-d H:i:s');
                    
                    // save it to transaction tbl
                    $this->model_extension_payment_hoolah->addHoolahOrderTransactionRefund($refund);
                            
                    if($result->code == 'accepted' || $result->code == 'in_process' || $result->code == 'in_process') {
                        //redirect back to the order
						$this->response->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], true));
                        
                    } else {
                        $this->session->data['error'] = $result->message;
					    $this->response->redirect($this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'] . '&tid=' . $this->request->get['tid'] . '&order_id=' . $this->request->get['order_id'], true));
                    }
				} else {
					$this->session->data['error'] = $this->language->get('error_data');
					$this->response->redirect($this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'] . '&tid=' . $this->request->get['tid'] . '&order_id=' . $this->request->get['order_id'], true));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->response->redirect($this->url->link('extension/payment/hoolah/refund', 'token=' . $this->session->data['token'] . '&tid=' . $this->request->get['tid'] . '&order_id=' . $this->request->get['order_id'], true));
		}
	}
	
	public function initiateRefundWebhook() { 
	    // do not delete
	}
	
	public function search() {
		$this->load->language('extension/payment/hoolah_search');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title']          = $this->language->get('heading_title');

		$data['text_name']              = $this->language->get('text_name');
		$data['text_searching']         = $this->language->get('text_searching');
		$data['text_view']              = $this->language->get('text_view');
		$data['text_format']            = $this->language->get('text_format');
		$data['text_date_search']       = $this->language->get('text_date_search');
		$data['text_no_results']        = $this->language->get('text_no_results');

		$data['entry_date']             = $this->language->get('entry_date');
		$data['entry_date_start']       = $this->language->get('entry_date_start');
		$data['entry_date_end']         = $this->language->get('entry_date_end');
		$data['entry_date_to']          = $this->language->get('entry_date_to');
		$data['entry_transaction']      = $this->language->get('entry_transaction');
		$data['entry_transaction_type'] = $this->language->get('entry_transaction_type');
		$data['entry_transaction_status'] = $this->language->get('entry_transaction_status');
		$data['entry_transaction_id']   = $this->language->get('entry_transaction_id');
		$data['entry_order_no']         = $this->language->get('entry_order_no');
		$data['entry_amount']           = $this->language->get('entry_amount');

		$data['column_date']            = $this->language->get('column_date');
		$data['column_type']            = $this->language->get('column_type');
		$data['column_email']           = $this->language->get('column_email');
		$data['column_name']            = $this->language->get('column_name');
		$data['column_transid']         = $this->language->get('column_transid');
		$data['column_status']          = $this->language->get('column_status');
		$data['column_currency']        = $this->language->get('column_currency');
		$data['column_amount']          = $this->language->get('column_amount');
		$data['column_fee']             = $this->language->get('column_fee');
		$data['column_action']          = $this->language->get('column_action');

		$data['button_search']          = $this->language->get('button_search');
		$data['button_edit']            = $this->language->get('button_edit');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_hoolah'),
			'href' => $this->url->link('extension/payment/hoolah', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/hoolah/search', 'token=' . $this->session->data['token'], true),
		);

		$this->load->model('extension/payment/hoolah');

		$data['currency_codes'] = $this->model_extension_payment_hoolah->getCurrencies();

		$data['default_currency'] = $this->config->get('hoolah_currency');

		$data['date_start'] = date("Y-m-d", strtotime('-30 days'));
		$data['date_end'] = date("Y-m-d");
		$data['view_link'] = $this->url->link('extension/payment/hoolah/info', 'token=' . $this->session->data['token'], true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/hoolah_search', $data));
	}
	
	public function doSearch() {
		/**
		 * used to search for transactions from a user account
		 */
		if (isset($this->request->post['date_start'])) {
			$this->load->model('extension/payment/hoolah');
			$result = $this->model_extension_payment_hoolah->searchHoolahTransaction($this->request->post);
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($result));
		} else {
			$response['error'] = true;
			$response['error_msg'] = 'Enter a start date';
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($response));
		}
	}


	private function checkIfInstalledDB() {

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."hoolah_callback` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `order_status` varchar(10) NOT NULL,
			  `cart_id` varchar(50) NOT NULL,
			  `webhook_url` text NOT NULL,
			  `order_uuid` varchar(250) NOT NULL,
			  `order_context_token` varchar(250) NOT NULL,
			  `failure_code` varchar(20) NOT NULL,
			  `date_added` datetime NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."hoolah_order_transaction` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `order_id` varchar(10) NOT NULL,
			  `uuid` varchar(250) NOT NULL,
			  `status` varchar(10) NOT NULL,
			  `orderType` varchar(10) NOT NULL,
			  `total` decimal(10,2) NOT NULL,
			  `tax` decimal(10,2) NOT NULL,
			  `shipping_fee` decimal(10,2) NOT NULL,
			  `currency` varchar(10) NOT NULL,
			  `requestId` varchar(250) NOT NULL,
			  `code` varchar(150) NOT NULL,
			  `message` varchar(250) NOT NULL,
			  `details` text NOT NULL,
			  `createdAt` datetime NOT NULL,
			  `date_added` datetime NOT NULL
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");


		$check_country_code = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."country` LIKE 'country_code'");
		if (!$check_country_code->num_rows) {
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."country`;");
			$this->db->query("
				CREATE TABLE `".DB_PREFIX."country` (
				  `country_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				  `name` varchar(128) NOT NULL,
				  `iso_code_2` varchar(2) NOT NULL,
				  `iso_code_3` varchar(3) NOT NULL,
				  `address_format` text NOT NULL,
				  `postcode_required` tinyint(1) NOT NULL,
				  `country_code` varchar(20) NOT NULL,
				  `status` tinyint(1) NOT NULL DEFAULT '1'
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			");
			$this->db->query("
				INSERT INTO `".DB_PREFIX."country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`, `postcode_required`, `country_code`, `status`) VALUES
				(1, 'Afghanistan', 'AF', 'AFG', '', 0, '93', 1),
				(2, 'Albania', 'AL', 'ALB', '', 0, '355', 1),
				(3, 'Algeria', 'DZ', 'DZA', '', 0, '213', 1),
				(4, 'American Samoa', 'AS', 'ASM', '', 0, '1-684', 1),
				(5, 'Andorra', 'AD', 'AND', '', 0, '376', 1),
				(6, 'Angola', 'AO', 'AGO', '', 0, '244', 1),
				(7, 'Anguilla', 'AI', 'AIA', '', 0, '1-264', 1),
				(8, 'Antarctica', 'AQ', 'ATA', '', 0, '672', 1),
				(9, 'Antigua and Barbuda', 'AG', 'ATG', '', 0, '1-268', 1),
				(10, 'Argentina', 'AR', 'ARG', '', 0, '54', 1),
				(11, 'Armenia', 'AM', 'ARM', '', 0, '374', 1),
				(12, 'Aruba', 'AW', 'ABW', '', 0, '297', 1),
				(13, 'Australia', 'AU', 'AUS', '', 0, '61', 1),
				(14, 'Austria', 'AT', 'AUT', '', 0, '43', 1),
				(15, 'Azerbaijan', 'AZ', 'AZE', '', 0, '994', 1),
				(16, 'Bahamas', 'BS', 'BHS', '', 0, '1-242', 1),
				(17, 'Bahrain', 'BH', 'BHR', '', 0, '973', 1),
				(18, 'Bangladesh', 'BD', 'BGD', '', 0, '880', 1),
				(19, 'Barbados', 'BB', 'BRB', '', 0, '1-246', 1),
				(20, 'Belarus', 'BY', 'BLR', '', 0, '375', 1),
				(21, 'Belgium', 'BE', 'BEL', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 0, '32', 1),
				(22, 'Belize', 'BZ', 'BLZ', '', 0, '501', 1),
				(23, 'Benin', 'BJ', 'BEN', '', 0, '229', 1),
				(24, 'Bermuda', 'BM', 'BMU', '', 0, '1-441', 1),
				(25, 'Bhutan', 'BT', 'BTN', '', 0, '975', 1),
				(26, 'Bolivia', 'BO', 'BOL', '', 0, '591', 1),
				(27, 'Bosnia and Herzegovina', 'BA', 'BIH', '', 0, '387', 1),
				(28, 'Botswana', 'BW', 'BWA', '', 0, '267', 1),
				(29, 'Bouvet Island', 'BV', 'BVT', '', 0, '55', 1),
				(30, 'Brazil', 'BR', 'BRA', '', 0, '246', 1),
				(31, 'British Indian Ocean Territory', 'IO', 'IOT', '', 0, '1-284', 1),
				(32, 'Brunei Darussalam', 'BN', 'BRN', '', 0, '673', 1),
				(33, 'Bulgaria', 'BG', 'BGR', '', 0, '359', 1),
				(34, 'Burkina Faso', 'BF', 'BFA', '', 0, '226', 1),
				(35, 'Burundi', 'BI', 'BDI', '', 0, '257', 1),
				(36, 'Cambodia', 'KH', 'KHM', '', 0, '855', 1),
				(37, 'Cameroon', 'CM', 'CMR', '', 0, '237', 1),
				(38, 'Canada', 'CA', 'CAN', '', 0, '1', 1),
				(39, 'Cape Verde', 'CV', 'CPV', '', 0, '238', 1),
				(40, 'Cayman Islands', 'KY', 'CYM', '', 0, '1-345', 1),
				(41, 'Central African Republic', 'CF', 'CAF', '', 0, '236', 1),
				(42, 'Chad', 'TD', 'TCD', '', 0, '235', 1),
				(43, 'Chile', 'CL', 'CHL', '', 0, '56', 1),
				(44, 'China', 'CN', 'CHN', '', 0, '86', 1),
				(45, 'Christmas Island', 'CX', 'CXR', '', 0, '61', 1),
				(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', '', 0, '61', 1),
				(47, 'Colombia', 'CO', 'COL', '', 0, '57', 1),
				(48, 'Comoros', 'KM', 'COM', '', 0, '269', 1),
				(49, 'Congo', 'CG', 'COG', '', 0, '', 1),
				(50, 'Cook Islands', 'CK', 'COK', '', 0, '682', 1),
				(51, 'Costa Rica', 'CR', 'CRI', '', 0, '506', 1),
				(52, 'Cote D\'Ivoire', 'CI', 'CIV', '', 0, '', 1),
				(53, 'Croatia', 'HR', 'HRV', '', 0, '385', 1),
				(54, 'Cuba', 'CU', 'CUB', '', 0, '53', 1),
				(55, 'Cyprus', 'CY', 'CYP', '', 0, '357', 1),
				(56, 'Czech Republic', 'CZ', 'CZE', '', 0, '420', 1),
				(57, 'Denmark', 'DK', 'DNK', '', 0, '45', 1),
				(58, 'Djibouti', 'DJ', 'DJI', '', 0, '253', 1),
				(59, 'Dominica', 'DM', 'DMA', '', 0, '1-767', 1),
				(60, 'Dominican Republic', 'DO', 'DOM', '', 0, '1-809, 1-829, 1-849', 1),
				(61, 'East Timor', 'TL', 'TLS', '', 0, '670', 1),
				(62, 'Ecuador', 'EC', 'ECU', '', 0, '593', 1),
				(63, 'Egypt', 'EG', 'EGY', '', 0, '20', 1),
				(64, 'El Salvador', 'SV', 'SLV', '', 0, '503', 1),
				(65, 'Equatorial Guinea', 'GQ', 'GNQ', '', 0, '240', 1),
				(66, 'Eritrea', 'ER', 'ERI', '', 0, '291', 1),
				(67, 'Estonia', 'EE', 'EST', '', 0, '372', 1),
				(68, 'Ethiopia', 'ET', 'ETH', '', 0, '251', 1),
				(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '', 0, '500', 1),
				(70, 'Faroe Islands', 'FO', 'FRO', '', 0, '298', 1),
				(71, 'Fiji', 'FJ', 'FJI', '', 0, '679', 1),
				(72, 'Finland', 'FI', 'FIN', '', 0, '358', 1),
				(74, 'France, Metropolitan', 'FR', 'FRA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '33', 1),
				(75, 'French Guiana', 'GF', 'GUF', '', 0, '', 1),
				(76, 'French Polynesia', 'PF', 'PYF', '', 0, '689', 1),
				(77, 'French Southern Territories', 'TF', 'ATF', '', 0, '', 1),
				(78, 'Gabon', 'GA', 'GAB', '', 0, '241', 1),
				(79, 'Gambia', 'GM', 'GMB', '', 0, '220', 1),
				(80, 'Georgia', 'GE', 'GEO', '', 0, '995', 1),
				(81, 'Germany', 'DE', 'DEU', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '49', 1),
				(82, 'Ghana', 'GH', 'GHA', '', 0, '233', 1),
				(83, 'Gibraltar', 'GI', 'GIB', '', 0, '350', 1),
				(84, 'Greece', 'GR', 'GRC', '', 0, '30', 1),
				(85, 'Greenland', 'GL', 'GRL', '', 0, '299', 1),
				(86, 'Grenada', 'GD', 'GRD', '', 0, '1-473', 1),
				(87, 'Guadeloupe', 'GP', 'GLP', '', 0, '', 1),
				(88, 'Guam', 'GU', 'GUM', '', 0, '1-671', 1),
				(89, 'Guatemala', 'GT', 'GTM', '', 0, '502', 1),
				(90, 'Guinea', 'GN', 'GIN', '', 0, '224', 1),
				(91, 'Guinea-Bissau', 'GW', 'GNB', '', 0, '245', 1),
				(92, 'Guyana', 'GY', 'GUY', '', 0, '592', 1),
				(93, 'Haiti', 'HT', 'HTI', '', 0, '509', 1),
				(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '', 0, '', 1),
				(95, 'Honduras', 'HN', 'HND', '', 0, '504', 1),
				(96, 'Hong Kong', 'HK', 'HKG', '', 0, '852', 1),
				(97, 'Hungary', 'HU', 'HUN', '', 0, '36', 1),
				(98, 'Iceland', 'IS', 'ISL', '', 0, '354', 1),
				(99, 'India', 'IN', 'IND', '', 0, '91', 1),
				(100, 'Indonesia', 'ID', 'IDN', '', 0, '62', 1),
				(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', '', 0, '98', 1),
				(102, 'Iraq', 'IQ', 'IRQ', '', 0, '964', 1),
				(103, 'Ireland', 'IE', 'IRL', '', 0, '353', 1),
				(104, 'Israel', 'IL', 'ISR', '', 0, '972', 1),
				(105, 'Italy', 'IT', 'ITA', '', 0, '39', 1),
				(106, 'Jamaica', 'JM', 'JAM', '', 0, '1-876', 1),
				(107, 'Japan', 'JP', 'JPN', '', 0, '81', 1),
				(108, 'Jordan', 'JO', 'JOR', '', 0, '962', 1),
				(109, 'Kazakhstan', 'KZ', 'KAZ', '', 0, '7', 1),
				(110, 'Kenya', 'KE', 'KEN', '', 0, '254', 1),
				(111, 'Kiribati', 'KI', 'KIR', '', 0, '686', 1),
				(112, 'North Korea', 'KP', 'PRK', '', 0, '850', 1),
				(113, 'South Korea', 'KR', 'KOR', '', 0, '82', 1),
				(114, 'Kuwait', 'KW', 'KWT', '', 0, '965', 1),
				(115, 'Kyrgyzstan', 'KG', 'KGZ', '', 0, '996', 1),
				(116, 'Lao People\'s Democratic Republic', 'LA', 'LAO', '', 0, '856', 1),
				(117, 'Latvia', 'LV', 'LVA', '', 0, '371', 1),
				(118, 'Lebanon', 'LB', 'LBN', '', 0, '961', 1),
				(119, 'Lesotho', 'LS', 'LSO', '', 0, '266', 1),
				(120, 'Liberia', 'LR', 'LBR', '', 0, '231', 1),
				(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', '', 0, '218', 1),
				(122, 'Liechtenstein', 'LI', 'LIE', '', 0, '423', 1),
				(123, 'Lithuania', 'LT', 'LTU', '', 0, '370', 1),
				(124, 'Luxembourg', 'LU', 'LUX', '', 0, '352', 1),
				(125, 'Macau', 'MO', 'MAC', '', 0, '853', 1),
				(126, 'FYROM', 'MK', 'MKD', '', 0, '', 1),
				(127, 'Madagascar', 'MG', 'MDG', '', 0, '261', 1),
				(128, 'Malawi', 'MW', 'MWI', '', 0, '265', 1),
				(129, 'Malaysia', 'MY', 'MYS', '', 0, '60', 1),
				(130, 'Maldives', 'MV', 'MDV', '', 0, '960', 1),
				(131, 'Mali', 'ML', 'MLI', '', 0, '223', 1),
				(132, 'Malta', 'MT', 'MLT', '', 0, '356', 1),
				(133, 'Marshall Islands', 'MH', 'MHL', '', 0, '692', 1),
				(134, 'Martinique', 'MQ', 'MTQ', '', 0, '', 1),
				(135, 'Mauritania', 'MR', 'MRT', '', 0, '222', 1),
				(136, 'Mauritius', 'MU', 'MUS', '', 0, '230', 1),
				(137, 'Mayotte', 'YT', 'MYT', '', 0, '262', 1),
				(138, 'Mexico', 'MX', 'MEX', '', 0, '52', 1),
				(139, 'Micronesia, Federated States of', 'FM', 'FSM', '', 0, '691', 1),
				(140, 'Moldova, Republic of', 'MD', 'MDA', '', 0, '373', 1),
				(141, 'Monaco', 'MC', 'MCO', '', 0, '377', 1),
				(142, 'Mongolia', 'MN', 'MNG', '', 0, '976', 1),
				(143, 'Montserrat', 'MS', 'MSR', '', 0, '382', 1),
				(144, 'Morocco', 'MA', 'MAR', '', 0, '212', 1),
				(145, 'Mozambique', 'MZ', 'MOZ', '', 0, '258', 1),
				(146, 'Myanmar', 'MM', 'MMR', '', 0, '95', 1),
				(147, 'Namibia', 'NA', 'NAM', '', 0, '264', 1),
				(148, 'Nauru', 'NR', 'NRU', '', 0, '674', 1),
				(149, 'Nepal', 'NP', 'NPL', '', 0, '977', 1),
				(150, 'Netherlands', 'NL', 'NLD', '', 0, '31', 1),
				(151, 'Netherlands Antilles', 'AN', 'ANT', '', 0, '599', 1),
				(152, 'New Caledonia', 'NC', 'NCL', '', 0, '687', 1),
				(153, 'New Zealand', 'NZ', 'NZL', '', 0, '64', 1),
				(154, 'Nicaragua', 'NI', 'NIC', '', 0, '505', 1),
				(155, 'Niger', 'NE', 'NER', '', 0, '227', 1),
				(156, 'Nigeria', 'NG', 'NGA', '', 0, '234', 1),
				(157, 'Niue', 'NU', 'NIU', '', 0, '683', 1),
				(158, 'Norfolk Island', 'NF', 'NFK', '', 0, '', 1),
				(159, 'Northern Mariana Islands', 'MP', 'MNP', '', 0, '1-670', 1),
				(160, 'Norway', 'NO', 'NOR', '', 0, '47', 1),
				(161, 'Oman', 'OM', 'OMN', '', 0, '968', 1),
				(162, 'Pakistan', 'PK', 'PAK', '', 0, '92', 1),
				(163, 'Palau', 'PW', 'PLW', '', 0, '680', 1),
				(164, 'Panama', 'PA', 'PAN', '', 0, '507', 1),
				(165, 'Papua New Guinea', 'PG', 'PNG', '', 0, '675', 1),
				(166, 'Paraguay', 'PY', 'PRY', '', 0, '595', 1),
				(167, 'Peru', 'PE', 'PER', '', 0, '51', 1),
				(168, 'Philippines', 'PH', 'PHL', '', 0, '63', 1),
				(169, 'Pitcairn', 'PN', 'PCN', '', 0, '64', 1),
				(170, 'Poland', 'PL', 'POL', '', 0, '48', 1),
				(171, 'Portugal', 'PT', 'PRT', '', 0, '351', 1),
				(172, 'Puerto Rico', 'PR', 'PRI', '', 0, '1-787,1-939', 1),
				(173, 'Qatar', 'QA', 'QAT', '', 0, '974', 1),
				(174, 'Reunion', 'RE', 'REU', '', 0, '262', 1),
				(175, 'Romania', 'RO', 'ROM', '', 0, '40', 1),
				(176, 'Russian Federation', 'RU', 'RUS', '', 0, '7', 1),
				(177, 'Rwanda', 'RW', 'RWA', '', 0, '250', 1),
				(178, 'Saint Kitts and Nevis', 'KN', 'KNA', '', 0, '1-869', 1),
				(179, 'Saint Lucia', 'LC', 'LCA', '', 0, '1-758', 1),
				(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '', 0, '1-784', 1),
				(181, 'Samoa', 'WS', 'WSM', '', 0, '685', 1),
				(182, 'San Marino', 'SM', 'SMR', '', 0, '378', 1),
				(183, 'Sao Tome and Principe', 'ST', 'STP', '', 0, '239', 1),
				(184, 'Saudi Arabia', 'SA', 'SAU', '', 0, '966', 1),
				(185, 'Senegal', 'SN', 'SEN', '', 0, '221', 1),
				(186, 'Seychelles', 'SC', 'SYC', '', 0, '248', 1),
				(187, 'Sierra Leone', 'SL', 'SLE', '', 0, '232', 1),
				(188, 'Singapore', 'SG', 'SGP', '', 0, '65', 1),
				(189, 'Slovak Republic', 'SK', 'SVK', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city} {postcode}\r\n{zone}\r\n{country}', 0, '', 1),
				(190, 'Slovenia', 'SI', 'SVN', '', 0, '', 1),
				(191, 'Solomon Islands', 'SB', 'SLB', '', 0, '', 1),
				(192, 'Somalia', 'SO', 'SOM', '', 0, '', 1),
				(193, 'South Africa', 'ZA', 'ZAF', '', 0, '', 1),
				(194, 'South Georgia &amp; South Sandwich Islands', 'GS', 'SGS', '', 0, '', 1),
				(195, 'Spain', 'ES', 'ESP', '', 0, '', 1),
				(196, 'Sri Lanka', 'LK', 'LKA', '', 0, '', 1),
				(197, 'St. Helena', 'SH', 'SHN', '', 0, '', 1),
				(198, 'St. Pierre and Miquelon', 'PM', 'SPM', '', 0, '', 1),
				(199, 'Sudan', 'SD', 'SDN', '', 0, '', 1),
				(200, 'Suriname', 'SR', 'SUR', '', 0, '', 1),
				(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '', 0, '', 1),
				(202, 'Swaziland', 'SZ', 'SWZ', '', 0, '', 1),
				(203, 'Sweden', 'SE', 'SWE', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', 1, '', 1),
				(204, 'Switzerland', 'CH', 'CHE', '', 0, '', 1),
				(205, 'Syrian Arab Republic', 'SY', 'SYR', '', 0, '', 1),
				(206, 'Taiwan', 'TW', 'TWN', '', 0, '886', 1),
				(207, 'Tajikistan', 'TJ', 'TJK', '', 0, '', 1),
				(208, 'Tanzania, United Republic of', 'TZ', 'TZA', '', 0, '', 1),
				(209, 'Thailand', 'TH', 'THA', '', 0, '66', 1),
				(210, 'Togo', 'TG', 'TGO', '', 0, '', 1),
				(211, 'Tokelau', 'TK', 'TKL', '', 0, '', 1),
				(212, 'Tonga', 'TO', 'TON', '', 0, '', 1),
				(213, 'Trinidad and Tobago', 'TT', 'TTO', '', 0, '', 1),
				(214, 'Tunisia', 'TN', 'TUN', '', 0, '', 1),
				(215, 'Turkey', 'TR', 'TUR', '', 0, '', 1),
				(216, 'Turkmenistan', 'TM', 'TKM', '', 0, '', 1),
				(217, 'Turks and Caicos Islands', 'TC', 'TCA', '', 0, '', 1),
				(218, 'Tuvalu', 'TV', 'TUV', '', 0, '', 1),
				(219, 'Uganda', 'UG', 'UGA', '', 0, '', 1),
				(220, 'Ukraine', 'UA', 'UKR', '', 0, '', 1),
				(221, 'United Arab Emirates', 'AE', 'ARE', '', 0, '', 1),
				(222, 'United Kingdom', 'GB', 'GBR', '', 1, '', 1),
				(223, 'United States', 'US', 'USA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}', 0, '1', 1),
				(224, 'United States Minor Outlying Islands', 'UM', 'UMI', '', 0, '', 1),
				(225, 'Uruguay', 'UY', 'URY', '', 0, '', 1),
				(226, 'Uzbekistan', 'UZ', 'UZB', '', 0, '', 1),
				(227, 'Vanuatu', 'VU', 'VUT', '', 0, '', 1),
				(228, 'Vatican City State (Holy See)', 'VA', 'VAT', '', 0, '', 1),
				(229, 'Venezuela', 'VE', 'VEN', '', 0, '', 1),
				(230, 'Viet Nam', 'VN', 'VNM', '', 0, '84', 1),
				(231, 'Virgin Islands (British)', 'VG', 'VGB', '', 0, '', 1),
				(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', '', 0, '', 1),
				(233, 'Wallis and Futuna Islands', 'WF', 'WLF', '', 0, '', 1),
				(234, 'Western Sahara', 'EH', 'ESH', '', 0, '', 1),
				(235, 'Yemen', 'YE', 'YEM', '', 0, '', 1),
				(237, 'Democratic Republic of Congo', 'CD', 'COD', '', 0, '', 1),
				(238, 'Zambia', 'ZM', 'ZMB', '', 0, '', 1),
				(239, 'Zimbabwe', 'ZW', 'ZWE', '', 0, '', 1),
				(242, 'Montenegro', 'ME', 'MNE', '', 0, '', 1),
				(243, 'Serbia', 'RS', 'SRB', '', 0, '', 1),
				(244, 'Aaland Islands', 'AX', 'ALA', '', 0, '', 1),
				(245, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'BES', '', 0, '', 1),
				(246, 'Curacao', 'CW', 'CUW', '', 0, '', 1),
				(247, 'Palestinian Territory, Occupied', 'PS', 'PSE', '', 0, '', 1),
				(248, 'South Sudan', 'SS', 'SSD', '', 0, '', 1),
				(249, 'St. Barthelemy', 'BL', 'BLM', '', 0, '', 1),
				(250, 'St. Martin (French part)', 'MF', 'MAF', '', 0, '', 1),
				(251, 'Canary Islands', 'IC', 'ICA', '', 0, '', 1),
				(252, 'Ascension Island (British)', 'AC', 'ASC', '', 0, '', 1),
				(253, 'Kosovo, Republic of', 'XK', 'UNK', '', 0, '', 1),
				(254, 'Isle of Man', 'IM', 'IMN', '', 0, '', 1),
				(255, 'Tristan da Cunha', 'TA', 'SHN', '', 0, '', 1),
				(256, 'Guernsey', 'GG', 'GGY', '', 0, '', 1),
				(257, 'Jersey', 'JE', 'JEY', '', 0, '', 1);

			");
		}

	}
	
}