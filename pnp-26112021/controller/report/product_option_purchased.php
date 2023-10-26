<?php
class ControllerReportProductOptionPurchased extends Controller {
	public function index() {
		$this->load->language('report/product_option_purchased');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/product_option_purchased', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('sale/order');
		$this->load->model('catalog/product');

		$data['products'] = array();
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		if($filter_order_status_id != ''){
			$filter_data['filter_order_status'] = $filter_order_status_id;
		}
		
		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			$products = $this->model_sale_order->getOrderProducts($result['order_id']);
				
				foreach ($products as $product) {
					
					$options = $this->model_sale_order->getOrderOptions($result['order_id'], $product['order_product_id']);

					if(!empty($options)) {
						foreach ($options as $option) {
							$option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'],$option['product_option_value_id']);
							if($option_value_info){
								$to_pack_prods[] = array(
									'id' 			=> $product['product_id'].'-'.$option['product_option_value_id'],
									'product_name' 	=> $product['name'].' - '.$option['value'],
									'quantity' 		=> $product['quantity'],
									'price' 		=> $option['price'],
									'sku' 			=> $option_value_info['sku'],
								);

							}
	
						}
					}

					
				}
		}

		$to_pack_prods_arr = array();
		$option_sales = array();

		if(isset($to_pack_prods) && !empty($to_pack_prods)){
			array_multisort(array_column($to_pack_prods, 'product_name'), SORT_ASC, $to_pack_prods);
			// debug($to_pack_prods);
	
			foreach($to_pack_prods as $each) {
	
				if(!isset($to_pack_prods_arr[$each['id']][$each['product_name']])) {
					$to_pack_prods_arr[$each['id']][$each['product_name']] = $each['quantity'];
					$option_sales[$each['id']]['name'] = $each['product_name'];
					$option_sales[$each['id']]['quantity'] = $each['quantity'];
					$option_sales[$each['id']]['sku'] = $each['sku'];
					$option_sales[$each['id']]['price'] = $each['price'];
				}
				else {
					// debug($each);
					$to_pack_prods_arr[$each['id']][$each['product_name']] = $to_pack_prods_arr[$each['id']][$each['product_name']] + $each['quantity'];
					$option_sales[$each['id']]['quantity'] = $option_sales[$each['id']]['quantity'] + $each['quantity'];
					$option_sales[$each['id']]['sku'] = $each['sku'];
					$option_sales[$each['id']]['price'] = $option_sales[$each['id']]['price'] + $each['price'];
				}
			}
		}

		foreach($option_sales as $option_key => $option_value) {
			$option_sales[$option_key]['total'] = $this->currency->format($option_sales[$option_key]['price'], $this->config->get('config_currency'));
		}
		
		//  debug($to_pack_prods_arr);
		// debug($option_sales);

		$total = count($option_sales);
		$limit = $this->config->get('config_limit_admin'); //per page 
		$offset = ($page - 1) * $limit;
		$option_sales = array_slice( $option_sales, $offset, $limit );

		
		
		$data['to_pack_prods'] = $to_pack_prods_arr;
		$data['option_sales'] = $option_sales;

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_all_status'] = $this->language->get('text_all_status');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_sku'] = $this->language->get('column_sku');
		$data['column_total'] = $this->language->get('column_total');

		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_export'] = $this->language->get('button_export');
		$data['export'] = $this->url->link('report/product_option_purchased/export', 'token=' . $this->session->data['token'] . $url, true);

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_clear'] = $this->language->get('button_clear');

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/product_option_purchased', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total - $this->config->get('config_limit_admin'))) ? $total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total, ceil($total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/product_option_purchased', $data));
	}

	public function export() {

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		$page = 1;
		$limit = 9999;

		$this->load->model('sale/order');
		$this->load->model('catalog/product');

		$data['products'] = array();
		
		$filter_data = array(
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     => $filter_date_end
		);

		if($filter_order_status_id != ''){
			$filter_data['filter_order_status'] = $filter_order_status_id;
		}
		
		$results = $this->model_sale_order->getOrders($filter_data);
		
		foreach ($results as $result) {
			$products = $this->model_sale_order->getOrderProducts($result['order_id']);
				
				foreach ($products as $product) {
					
					$options = $this->model_sale_order->getOrderOptions($result['order_id'], $product['order_product_id']);

					if(!empty($options)) {
						foreach ($options as $option) {
							$option_value_info = $this->model_catalog_product->getProductOptionValue($product['product_id'],$option['product_option_value_id']);
							if($option_value_info){
								$to_pack_prods[] = array(
									'id' 			=> $product['product_id'].'-'.$option['product_option_value_id'],
									'product_name' 	=> $product['name'].' - '.$option['value'],
									'quantity' 		=> $product['quantity'],
									'price' 		=> $option['price'],
									'sku' 			=> $option_value_info['sku'],
								);

							}
	
						}
					}

					
				}
		}

		$to_pack_prods_arr = array();
		$option_sales = array();

		if(isset($to_pack_prods) && !empty($to_pack_prods)){
			array_multisort(array_column($to_pack_prods, 'product_name'), SORT_ASC, $to_pack_prods);
			// debug($to_pack_prods);
	
			foreach($to_pack_prods as $each) {
	
				if(!isset($to_pack_prods_arr[$each['id']][$each['product_name']])) {
					$to_pack_prods_arr[$each['id']][$each['product_name']] = $each['quantity'];
					$option_sales[$each['id']]['name'] = $each['product_name'];
					$option_sales[$each['id']]['quantity'] = $each['quantity'];
					$option_sales[$each['id']]['sku'] = $each['sku'];
					$option_sales[$each['id']]['price'] = $each['price'];
				}
				else {
					// debug($each);
					$to_pack_prods_arr[$each['id']][$each['product_name']] = $to_pack_prods_arr[$each['id']][$each['product_name']] + $each['quantity'];
					$option_sales[$each['id']]['quantity'] = $option_sales[$each['id']]['quantity'] + $each['quantity'];
					$option_sales[$each['id']]['sku'] = $each['sku'];
					$option_sales[$each['id']]['price'] = $option_sales[$each['id']]['price'] + $each['price'];
				}
			}
		}
		$new_option_sales = array();

		foreach($option_sales as $option_key => $option_value) {
			$new_option_sales[$option_key]['name'] = $option_sales[$option_key]['name'];
			$new_option_sales[$option_key]['sku'] = $option_sales[$option_key]['sku'];
			$new_option_sales[$option_key]['quantity'] = $option_sales[$option_key]['quantity'];
			$new_option_sales[$option_key]['total'] = $this->currency->format($option_sales[$option_key]['price'], $this->config->get('config_currency'));
		}
		
		$options = array();
		
		$option_column = array('Product Option Name', 'Product Option SKU', 'Quantity', 'Total');
			
		$options[0]=   $option_column;   
		
		foreach($new_option_sales as $new_option_sale) {
			$options[]=   $new_option_sale;            
		}     

		$excel_data['Product Option Purchased'] = $options;
		
		$filepath = $this->excel->generate($excel_data, true, 'products_option_purchased_report');

		$this->excel->download($filepath);

	}
}