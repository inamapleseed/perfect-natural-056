<?php
class ControllerExtensionModuleGstInclusive extends Controller {

    public function install() {
        $this->load->model('setting/setting');

        $this->request->post['gst_inclusive_status'] = 0;
        
        $this->model_setting_setting->editSetting('gst_inclusive', $this->request->post);

        // install gst  
        $this->model_extension_extension->install('total', 'gst');

        // install sub_total if not yet installed  
        $this->model_extension_extension->install('total', 'sub_total');
    }

    public function uninstall() {

        $this->request->post['gst_inclusive_status'] = 0;
        
        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting('gst_inclusive', $this->request->post);

        // uninstall gst  
        $this->model_extension_extension->uninstall('total', 'gst');
    }

	public function index() {
        $this->load->language('extension/module/gst_inclusive');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {

            $this->model_setting_setting->editSetting('gst_inclusive', $this->request->post);

            $this->load->model('extension/extension');

            if ($this->request->post['gst_inclusive_status']) {

                $this->model_extension_extension->install('total', 'gst');

                $this->load->model('user/user_group');

                $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/total/gst');
                
                $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/total/gst');

                /* if yes - enable gst inclusive */
                $gst_data = array(
                    'gst_sort_order' => 98,
                    'gst_rate' => 7,
                    'gst_status' => 1,
                );

                $this->model_setting_setting->editSetting('gst', $gst_data);

                $subtotal_data = array(
                    'sub_total_sort_order' => 97,
                    'sub_total_status' => 1,
                );

                $this->model_setting_setting->editSetting('sub_total', $subtotal_data);


            } else {
                /* if no - disable gst inclusive */
                $gst_data = array(
                    'gst_sort_order' => 98,
                    'gst_rate' => 7,
                    'gst_status' => 0,
                );

                $this->model_setting_setting->editSetting('gst', $gst_data);

                $this->model_extension_extension->uninstall('total', 'gst');

                $subtotal_data = array(
                    'sub_total_sort_order' => 1,
                    'sub_total_status' => 1,
                );
                
                $this->model_setting_setting->editSetting('sub_total', $subtotal_data);
            }


            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_status'] = $this->language->get('entry_status');

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
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/gst_inclusive', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/module/gst_inclusive', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

        if (isset($this->request->post['gst_inclusive_status'])) {
            $data['gst_inclusive_status'] = $this->request->post['gst_inclusive_status'];
        } else {
            $data['gst_inclusive_status'] = $this->config->get('gst_inclusive_status');
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/gst_inclusive', $data));


	}

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/gst_inclusive')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }
}