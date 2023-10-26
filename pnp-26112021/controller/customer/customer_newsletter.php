<?php
class ControllerCustomerCustomerNewsletter extends Controller {
	public function index() {
		$this->load->language('customer/customer_newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer_newsletter');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href' => $this->url->link('customer/customer_newsletter', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['customer_newsletters'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_date_added'        => $filter_date_added,

			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin'),
		);

		$customer_newsletter_total = $this->model_customer_customer_newsletter->getTotalCustomerNewsletters();

		$results = $this->model_customer_customer_newsletter->getCustomerNewsletters($filter_data);

		foreach ($results as $result) {
			$link = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'].'&customer_id='.$result['customer_id'], true);

			$data['customer_newsletters'][] = array(
				'id' => $result['id'],
				'name' => $result['name'] ? '<a href="'.$link.'">'.$result['name'].'</a>' : '-',
				'email' => $result['email'],
				'date_added' => $result['date_added'],
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_date_added'] = $this->language->get('entry_date_added');

		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$data['export_url'] = html($this->url->link('customer/customer_newsletter/export', 'token=' . $this->session->data['token'], true));

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customer/customer_newsletter', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_email'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=cn.email' . $url, true);
		$data['sort_date_added'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=cn.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/customer_newsletter', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_newsletter_total - $this->config->get('config_limit_admin'))) ? $customer_newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_newsletter_total, ceil($customer_newsletter_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_newsletter_list', $data));
	}

	public function export() {
		$error = false;
		
		if (!$this->user->hasPermission('modify', 'customer/customer_newsletter')) {
			$error = true;
		}
		
		if(!$error){
			$this->load->model('customer/customer_newsletter');
			
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_newsletter_list` ORDER BY id ASC");
			$thedata = array();

			foreach($query->rows as $d){
				$thedata[] = array(
					'email' => $d["email"],
					'date_added' => date('Y-m-d', strtotime($d["date_added"])),
				);
			}
			
			$general = array( 0 => array( 'Email', 'Date Added' ) );

			foreach($thedata as $id){
				$general[] = array(
					$id['email'],
					$id['date_added'],
				);
			}	
			
			$excel = array(
				"Newsletter" => $general,
			);
			
			$filepath = $this->excel->generate($excel, true, 'customer_newsletter_list_');
			
			$this->excel->download($filepath);
		}
		// End error
	}
}