<?php		
class ControllerReportCustomerEngagement extends Controller {	

	public function index() {
		$this->load->language('report/customer_engagement');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}
				

		if (isset($this->request->get['filter_customer_group'])) {
			$filter_customer_group = $this->request->get['filter_customer_group'];
		} else {
			$filter_customer_group = null;
		}
		
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
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . $url, true),
			'text' => $this->language->get('heading_title')
		);

		$this->load->model('report/customer');

		$data['activities'] = array();	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_neverlogin'] = $this->language->get('text_neverlogin');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_customeremail'] = $this->language->get('column_customeremail');
		$data['column_successlogincount'] = $this->language->get('column_successlogincount');
		$data['column_failedlogincount'] = $this->language->get('column_failedlogincount');
		$data['column_last_login_date'] = $this->language->get('column_last_login_date');
		$data['column_total_amount_spent'] = $this->language->get('column_total_amount_spent');
		$data['column_total_orders'] = $this->language->get('column_total_orders');
		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_cart_total'] = $this->language->get('column_cart_total');
		$data['column_wishlist_value'] = $this->language->get('column_wishlist_value');
		
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		$data['button_export'] = $this->language->get('button_export');
		$data['export'] = $this->url->link('report/customer_engagement/export', 'token=' . $this->session->data['token'] . $url, true);
	
	
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
			$sort_param = $this->request->get['sort'];
		} else {
			$sort = 'customer_name';
			$sort_param = "customer_name";
		}
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
			
			if($this->request->get['order'] =="ASC")
			{
				$order_param = 'SORT_ASC';
			}
			elseif($this->request->get['order'] =="DESC")
			{
				$order_param = 'SORT_DESC';
			}			
			
		} else {
			$order = 'ASC';
			$order_param = 'SORT_ASC';
		}
		
		
		$filter_data = array(
			'filter_name'   => $filter_customer,
			'filter_customer_group_id'	=> $filter_customer_group,
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * 20,
			'limit'             => 20
		);
		$this->load->model('report/customer_engagement');
				
		$activity_total = $this->model_report_customer_engagement->getTotalCustomers($filter_data);

		$results = $this->model_report_customer_engagement->getCustomers($filter_data);

		$list = array();
		
		foreach ($results as $result) {
			$total_spents=0;
			$cart_value=0;
			$this->load->model('report/customer_engagement');
			$total_spents = $this->model_report_customer_engagement->getCustomerTotalSpent($result['customer_id'],$filter_data);	
			$cart_value = $this->model_report_customer_engagement->getCustomerCartValue($result['customer_id']);
			$wishlist_value= $this->model_report_customer_engagement->getCustomerWishlistValue($result['customer_id']);
				
			@$list[] = array(
				'customer_name'    			=> $result['firstname']." ".$result['lastname'],
				'customer_email'			=> $result['email'],
				'total_success_login'       => $result['total_success_login'],
				'total_failed_login'        => $result['total_failed_login'],
				'last_login'         		=> $result['noofdays'],
				'ip'         				=> $result['ip'],
				'total_amount_spent'        => $total_spents['total'],
				'cart_value'         		=> $cart_value['price'],
				'number_of_orders'         	=> $total_spents['count'],
				'wishlist_value'			=> $wishlist_value['price']

			);
				
		}
		
		if(@$this->request->get['order'] =="DESC")
		{			
			$data['activities'] = array_sort($list, $sort_param, SORT_DESC);
		}
		else
		{
			$data['activities'] = array_sort(@$list, $sort_param, SORT_ASC);
		}
		
		foreach($data['activities'] as $key=>$value)
		{
			
			$data['activities'][$key]["total_amount_spent"]	=	$this->currency->format($data['activities'][$key]["total_amount_spent"], $this->config->get('config_currency'));			
			$data['activities'][$key]["cart_value"]	=	$this->currency->format($data['activities'][$key]["cart_value"], $this->config->get('config_currency'));			
			$data['activities'][$key]["wishlist_value"]	=	$this->currency->format($data['activities'][$key]["wishlist_value"], $this->config->get('config_currency'));					
				
		
		}
		
		
		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		
		
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_customername'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=customer_name' . $url, true);
		$data['sort_customeremail'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=customer_email' . $url, true);
		$data['sort_totalsuccesslogin'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=total_success_login' . $url, true);
		$data['sort_totalfailedlogin'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=total_failed_login' . $url, true);
		$data['sort_lastlogin'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=last_login' . $url, true);
		$data['sort_ip'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=ip' . $url, true);
		$data['sort_totalamount'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=total_amount_spent' . $url, true);
		$data['sort_cartvalue'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=cart_value' . $url, true);
		$data['sort_nooforders'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=number_of_orders' . $url, true);
		$data['sort_wishlistvalue'] = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . '&sort=wishlist_value' . $url, true);

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_clear'] = $this->language->get('button_clear');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_customer_group'])) {
			$url .= '&filter_customer_group=' . $this->request->get['filter_customer_group'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}


		$pagination = new Pagination();
		$pagination->total = $activity_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/customer_engagement', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($activity_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($activity_total - $this->config->get('config_limit_admin'))) ? $activity_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $activity_total, ceil($activity_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['filter_customer'] = $filter_customer;
		$data['filter_customer_group'] = $filter_customer_group;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/customer_engagement', $data));
	}

	public function export() {

		$page = 1;
		$limit = 9999;

			if (isset($this->request->get['filter_customer'])) {
				$filter_customer = $this->request->get['filter_customer'];
			} else {
				$filter_customer = null;
			}
					

			if (isset($this->request->get['filter_customer_group'])) {
				$filter_customer_group = $this->request->get['filter_customer_group'];
			} else {
				$filter_customer_group = null;
			}
			
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
			
	
			if (isset($this->request->get['sort'])) {
				$sort = $this->request->get['sort'];
				$sort_param = $this->request->get['sort'];
			} else {
				$sort = 'customer_name';
				$sort_param = "customer_name";
			}
			if (isset($this->request->get['order'])) {
				$order = $this->request->get['order'];
				
				if($this->request->get['order'] =="ASC")
				{
					$order_param = 'SORT_ASC';
				}
				elseif($this->request->get['order'] =="DESC")
				{
					$order_param = 'SORT_DESC';
				}			
				
			} else {
				$order = 'ASC';
				$order_param = 'SORT_ASC';
			}
			
			$filter_data = array(
				'filter_name'   => $filter_customer,
				'filter_customer_group_id'	=> $filter_customer_group,
				'filter_date_start'	=> $filter_date_start,
				'filter_date_end'	=> $filter_date_end,
				'sort'              => $sort,
				'order'             => $order,
				'start'             => 0,
				'limit'             => $limit
			);
			$this->load->model('report/customer_engagement');		
			$activity_total = $this->model_report_customer_engagement->getTotalCustomers($filter_data);
			
			$results = $this->model_report_customer_engagement->getCustomers($filter_data);
			foreach ($results as $result) {
				$total_spents=0;
				$cart_value=0;
				$this->load->model('report/customer_engagement');
				$total_spents = $this->model_report_customer_engagement->getCustomerTotalSpent($result['customer_id'],$filter_data);	
				$cart_value = $this->model_report_customer_engagement->getCustomerCartValue($result['customer_id']);
				$wishlist_value= $this->model_report_customer_engagement->getCustomerWishlistValue($result['customer_id']);
					
				@$list[] = array(
					'customer_name'    			=> $result['firstname']." ".$result['lastname'],
					'customer_email'			=> $result['email'],
					'total_success_login'       => $result['total_success_login'],
					'total_failed_login'        => $result['total_failed_login'],
					'last_login'         		=> $result['noofdays'],
					'ip'         				=> $result['ip'],
					'total_amount_spent'        => $total_spents['total'],
					'cart_value'         		=> $cart_value['price'],
					'wishlist_value'			=> $wishlist_value['price'],
					'number_of_orders'         	=> $total_spents['count'],

				);
					
			}
			
			if(@$this->request->get['order'] =="DESC") {			
				$activities = array_sort($list, $sort_param, SORT_DESC);
			} else {
				$activities = array_sort(@$list, $sort_param, SORT_ASC);
			}
			
			foreach($activities as $key=>$value) {
				$activities[$key]["total_amount_spent"]	=	$this->currency->format($activities[$key]["total_amount_spent"], $this->config->get('config_currency'));			
				$activities[$key]["cart_value"]	=	$this->currency->format($activities[$key]["cart_value"], $this->config->get('config_currency'));			
				$activities[$key]["wishlist_value"]	=	$this->currency->format($activities[$key]["wishlist_value"], $this->config->get('config_currency'));					
			}

		$customer_engagements = array();
		
		$customer_engagements_column = array(
			'Customer', 
			'Customer Email', 
			'Total Successful Logins', 
			'Failed Logins',
			'Days Since Last Login',
			'IP',
			'Total Amount Spent',
			'Cart Value',
			'Wishlist Value',
			'No. of Orders',
		);
			
		$customer_engagements[0] = $customer_engagements_column;   
		
		foreach($activities as $activity) {
			$customer_engagements[] = $activity;            
		}     

		$excel_data['Engagement'] = $customer_engagements;
		
		$filepath = $this->excel->generate($excel_data, true, 'customer_engagements_report');

		$this->excel->download($filepath);

	}

}

function array_sort($array, $on, $order=SORT_ASC)
{

	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}

		switch ($order) {
			case SORT_ASC:
				asort($sortable_array);
				break;
			case SORT_DESC:
				arsort($sortable_array);
				break;
		}

		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}

	return $new_array;
}

