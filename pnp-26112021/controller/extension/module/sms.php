<?php
class ControllerExtensionModuleSms extends Controller {

    public function install() {
        $this->load->model('setting/setting');

        $this->request->post['sms_status'] = 0;
        
        $this->model_setting_setting->editSetting('sms', $this->request->post);
    }

    public function uninstall() {

        $this->request->post['sms_status'] = 0;
        
        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting('sms', $this->request->post);
    }

	public function index() {
        $this->load->language('extension/module/sms');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

            $this->model_setting_setting->editSetting('sms', $this->request->post);

            $this->load->model('extension/extension');

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password']  = $this->language->get('entry_password');
        $data['entry_from'] = $this->language->get('entry_from');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

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
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/sms', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/module/sms', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->post['sms_status'])) {
            $data['sms_status'] = $this->request->post['sms_status'];
        } else {
            $data['sms_status'] = $this->config->get('sms_status');
        }

        if (isset($this->request->post['sms_username'])) {
            $data['sms_username'] = $this->request->post['sms_username'];
        } else {
            $data['sms_username'] = $this->config->get('sms_username');
        }
        
        if (isset($this->request->post['sms_password'])) {
            $data['sms_password'] = $this->request->post['sms_password'];
        } else {
            $data['sms_password'] = $this->config->get('sms_password');
        }
        
        if (isset($this->request->post['sms_from'])) {
            $data['sms_from'] = $this->request->post['sms_from'];
        } else {
            $data['sms_from'] = $this->config->get('sms_from');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/sms', $data));

	}

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/sms')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['sms_password']) < 1) {
            $this->error['password'] = $this->language->get('error_password');
        }
        if (utf8_strlen($this->request->post['sms_username']) < 1) {
            $this->error['username'] = $this->language->get('error_username');
        }


        return !$this->error;
    }
}
