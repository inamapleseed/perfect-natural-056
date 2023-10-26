<?php
class ControllerInsightsSales extends Controller {
    public function index() {

        $this->load->language('common/dashboard');

        $this->document->setTitle('Sales Analytics');

        $data['heading_title'] = 'Sales Analytics';

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        // Check install directory exists
        if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
            $data['error_install'] = $this->language->get('error_install');
        } else {
            $data['error_install'] = '';
        }
        // Check CRON job for low stock notification
        if ($this->config->get('config_low_stock_notify')) {
            $data['warning_cron_stock'] = nl2br($this->language->get('warning_cron_stock'));
        } else {
            $data['warning_cron_stock'] = '';
        }

        $data = $this->load->controller('common/common', $data);

        // Run currency update
        if ($this->config->get('config_currency_auto')) {
            $this->load->model('localisation/currency');

            $this->model_localisation_currency->refresh();
        }

        $this->response->setOutput($this->load->view('analytics/sales', $data));
    }
}
?>