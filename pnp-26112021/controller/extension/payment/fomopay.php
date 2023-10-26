<?php
class ControllerExtensionPaymentFomopay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/fomopay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fomopay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_signup'] = $this->language->get('text_signup');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_ipn'] = $this->language->get('entry_ipn');
		$data['entry_redirect'] = $this->language->get('entry_redirect');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_ipn'] = $this->language->get('help_ipn');
		$data['help_redirect'] = $this->language->get('help_redirect');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_search'] = $this->language->get('button_search');

		$data['tab_api'] = $this->language->get('tab_api');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
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
			'href' => $this->url->link('extension/payment/fomopay', 'token=' . $this->session->data['token'], true),
		);

		$data['action'] = $this->url->link('extension/payment/fomopay', 'token=' . $this->session->data['token'], true);
		
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		
		$data['search'] = $this->url->link('extension/payment/fomopay/search', 'token=' . $this->session->data['token'], true);

		$this->load->model('localisation/country');

		if (isset($this->request->post['fomopay_username'])) {
			$data['fomopay_username'] = $this->request->post['fomopay_username'];
		} else {
			$data['fomopay_username'] = $this->config->get('fomopay_username');
		}

		if (isset($this->request->post['fomopay_password'])) {
			$data['fomopay_password'] = $this->request->post['fomopay_password'];
		} else {
			$data['fomopay_password'] = $this->config->get('fomopay_password');
		}

		$data['ipn_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/fomopay/checkoutReturn';

        if (isset($this->request->post['fomopay_redirect'])) {
			$data['fomopay_redirect'] = $this->request->post['fomopay_redirect'];
		} else {
			$data['fomopay_redirect'] = $this->config->get('fomopay_redirect');
		}

		if (isset($this->request->post['fomopay_test'])) {
			$data['fomopay_test'] = $this->request->post['fomopay_test'];
		} else {
			$data['fomopay_test'] = $this->config->get('fomopay_test');
		}

		if (isset($this->request->post['fomopay_total'])) {
			$data['fomopay_total'] = $this->request->post['fomopay_total'];
		} else {
			$data['fomopay_total'] = $this->config->get('fomopay_total');
		}

		if (isset($this->request->post['fomopay_status'])) {
			$data['fomopay_status'] = $this->request->post['fomopay_status'];
		} else {
			$data['fomopay_status'] = $this->config->get('fomopay_status');
		}

		if (isset($this->request->post['fomopay_sort_order'])) {
			$data['fomopay_sort_order'] = $this->request->post['fomopay_sort_order'];
		} else {
			$data['fomopay_sort_order'] = $this->config->get('fomopay_sort_order');
		}

		if (isset($this->request->post['fomopay_pending_status_id'])) {
			$data['fomopay_pending_status_id'] = $this->request->post['fomopay_pending_status_id'];
		} else {
			$data['fomopay_pending_status_id'] = $this->config->get('fomopay_pending_status_id');
		}

		if (isset($this->request->post['fomopay_completed_status_id'])) {
			$data['fomopay_completed_status_id'] = $this->request->post['fomopay_completed_status_id'];
		} else {
			$data['fomopay_completed_status_id'] = $this->config->get('fomopay_completed_status_id');
		}

		if (isset($this->request->post['fomopay_failed_status_id'])) {
			$data['fomopay_failed_status_id'] = $this->request->post['fomopay_failed_status_id'];
		} else {
			$data['fomopay_failed_status_id'] = $this->config->get('fomopay_failed_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/fomopay', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/fomopay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['fomopay_test']) {

		} else {
			if (!$this->request->post['fomopay_username']) {
				$this->error['username'] = $this->language->get('error_username');
			}

			if (!$this->request->post['fomopay_password']) {
				$this->error['password'] = $this->language->get('error_password');
			}

		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/payment/fomopay');

		$this->model_extension_payment_fomopay->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/fomopay');

		$this->model_extension_payment_fomopay->uninstall();
	}
}
