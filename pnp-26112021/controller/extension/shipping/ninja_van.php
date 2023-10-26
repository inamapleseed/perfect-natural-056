<?php
class ControllerExtensionShippingNinjaVan extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/ninja_van');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('ninja_van', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_client_key'] = $this->language->get('entry_client_key');
		$data['entry_sandbox'] = $this->language->get('entry_sandbox');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/ninja_van', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/ninja_van', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		if (isset($this->request->post['ninja_van_total'])) {
			$data['ninja_van_total'] = $this->request->post['ninja_van_total'];
		} else {
			$data['ninja_van_total'] = $this->config->get('ninja_van_total');
		}

		if (isset($this->request->post['ninja_van_client_id'])) {
			$data['ninja_van_client_id'] = $this->request->post['ninja_van_client_id'];
		} else {
			$data['ninja_van_client_id'] = $this->config->get('ninja_van_client_id');
		}

		if (isset($this->request->post['ninja_van_client_key'])) {
			$data['ninja_van_client_key'] = $this->request->post['ninja_van_client_key'];
		} else {
			$data['ninja_van_client_key'] = $this->config->get('ninja_van_client_key');
		}

		if (isset($this->request->post['ninja_van_geo_zone_id'])) {
			$data['ninja_van_geo_zone_id'] = $this->request->post['ninja_van_geo_zone_id'];
		} else {
			$data['ninja_van_geo_zone_id'] = $this->config->get('ninja_van_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['ninja_van_status'])) {
			$data['ninja_van_status'] = $this->request->post['ninja_van_status'];
		} else {
			$data['ninja_van_status'] = $this->config->get('ninja_van_status');
		}

		if (isset($this->request->post['ninja_van_sandbox'])) {
			$data['ninja_van_sandbox'] = $this->request->post['ninja_van_sandbox'];
		} else {
			$data['ninja_van_sandbox'] = $this->config->get('ninja_van_sandbox');
		}

		if (isset($this->request->post['ninja_van_sort_order'])) {
			$data['ninja_van_sort_order'] = $this->request->post['ninja_van_sort_order'];
		} else {
			$data['ninja_van_sort_order'] = $this->config->get('ninja_van_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');



		$this->response->setOutput($this->load->view('extension/shipping/ninja_van', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/ninja_van')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}