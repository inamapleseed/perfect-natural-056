<?php
class ControllerExtensionPaymentMpgs extends Controller {
	public function callback() {
		$data = $this->request->get;

		$resultIndicator = $data['resultIndicator'];
		$sessionVersion = $data['sessionVersion'];
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/mpgs');

		$this->model_extension_payment_mpgs->updateOrder($this->session->data['order_id'],$resultIndicator);

		if ($resultIndicator == $this->session->data['successIndicator']) {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'],$this->config->get('mpgs_order_status_id'));
			$this->response->redirect($this->url->link('checkout/success', '', true));
		} else {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'],$this->config->get('mpgs_failed_order_status_id'));
			$this->response->redirect($this->url->link('checkout/failure', '', true));
		}

	}
	public function index() {

		if (isset($this->session->data['order_id'])) {


			$this->load->model('checkout/order');
			$this->load->model('extension/payment/mpgs');

			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if ($order_info) {

				$url = 'https://test-gateway.mastercard.com/api/nvp/version/56';

				if ($this->config->get('mpgs_mode') == 'live') {
					$url = 'https://ap-gateway.mastercard.com/api/nvp/version/56';
				}

				$apiPassword = $this->config->get('mpgs_integration_password');
				$merchant_id = $this->config->get('mpgs_merchant_id');
				$order_total = number_format((float)(round($order_info['total'],2)), 2, '.', '');
				$order_id = $this->session->data['order_id'];
				$currency = $order_info['currency_code'];

				$ch = curl_init();
				$order_id = date('YmdHis');

				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, "apiOperation=CREATE_CHECKOUT_SESSION&apiPassword=$apiPassword&apiUsername=merchant.$merchant_id&merchant=$merchant_id&interaction.operation=PURCHASE&order.id=$order_id&order.amount=$order_total&order.currency=$currency");

				$headers = array();
				$headers[] = 'Content-Type: application/x-www-form-urlencoded';
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$result = curl_exec($ch);
				if (curl_errno($ch)) {
				    echo 'Error:' . curl_error($ch);
				}
				curl_close($ch);
				parse_str($result, $arrayOutput);

				$data = $arrayOutput;

				if (isset($data['successIndicator'])) {
					$this->session->data['successIndicator'] = $data['successIndicator'];
				}

				$this->load->language('extension/payment/mpgs');

				$data['cancel_url'] = $this->url->link('checkout/cart');

				$data['text_mpgs_unavailable'] = $this->language->get('text_mpgs_unavailable');
				$data['text_time_out'] = $this->language->get('text_time_out');
				$data['button_confirm'] = $this->language->get('button_confirm');
				
				$data['apiPassword'] = $this->config->get('mpgs_integration_password');
				$data['merchant_id'] = $this->config->get('mpgs_merchant_id');
				$data['order_total'] = $order_total;
				$data['order_id'] = $order_id;
				$data['currency'] = $currency;			
				$data['merchant_name'] = $this->config->get('mpgs_merchant_name');		

				$this->model_extension_payment_mpgs->saveOrder($order_info,$order_total,$data['successIndicator']);
				
				$data['continue'] = $this->url->link('checkout/success');

				return $this->load->view('extension/payment/mpgs', $data);
			}
		}

	}
}