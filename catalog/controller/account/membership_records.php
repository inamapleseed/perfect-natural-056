<?php
class ControllerAccountMembershipRecords extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/membership_records', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/membership_records');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_membership_records'),
			'href' => $this->url->link('account/membership_records', '', true)
		);

		$this->load->model('account/customer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_from'] = $this->language->get('column_from');
		$data['column_to'] = $this->language->get('column_to');

		$data['text_total'] = $this->language->get('text_total');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
        }
        
        $this->load->model('account/customer_group');

		$data['membership_records'] = array();

        $results = $this->model_account_customer->getCustomerMembershipRecords($this->customer->getId());
        
        $result_total = count($results);

		foreach ($results as $result) {
            $old = $this->model_account_customer_group->getCustomerGroup($result['old_customer_group_id']);
            $new = $this->model_account_customer_group->getCustomerGroup($result['new_customer_group_id']);

			$data['membership_records'][] = array(
				'date_added' => date('Y-m-d', strtotime($result['date_added'])),
				'from' => $old['name'],
				'to' => $new['name'],
			);
		}

		$pagination = new Pagination();
		$pagination->total = $result_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/membership_records', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($result_total - 10)) ? $result_total : ((($page - 1) * 10) + 10), $result_total, ceil($result_total / 10));

		$data['total'] = $this->currency->format($this->customer->getBalance(), $this->session->data['currency']);

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$this->response->setOutput($this->load->view('account/membership_records', $data));
	}
}