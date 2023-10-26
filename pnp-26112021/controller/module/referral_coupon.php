<?php
class ControllerModuleReferralCoupon extends Controller {
  private $version = '2.9.3';
  private $error = array();

  public function index() {
    if (!$this->config->get('referral_coupon_installed')) $this->install();

    $this->load->language('extension/module');
    $data['lng'] = $this->load->language('marketing/coupon');
    $data['lng'] = $this->load->language('module/referral_coupon');

    $data['text_layout'] = sprintf($this->language->get('text_layout'), $this->url->link('design/layout', 'token=' . $this->session->data['token'], 'SSL'));

    $token = $this->session->data['token'];
    $data['token'] = $token;

    $this->load->model('setting/setting');

    $this->document->setTitle($this->language->get('heading_title'));

    $data['setting_action'] = $this->url->link('module/referral_coupon', 'token=' . $token . '&tab=setting', 'SSL');
    $data['data_action'] = $this->url->link('module/referral_coupon', 'token=' . $token . '&tab=data', 'SSL');
    $data['cancel'] = $this->url->link('extension/module', 'token=' . $token, 'SSL');

    if (isset($this->request->get['tab'])) {
      $data['tab'] = $this->request->get['tab'];
    } else {
      $data['tab'] = 'setting';
    }

    $url = $data['setting_action'];

    if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
      $this->model_setting_setting->editSetting('referral_coupon', $this->request->post);
      $this->session->data['success'] = $this->language->get('text_success');
      $url = $data['setting_action'];

      $this->response->redirect($url);
    }

    $data['heading_title'] = $this->language->get('heading_title') . ' - ' . $this->version;

    $data['token'] = $this->session->data['token'];

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home', 'token=' . $token, 'SSL'),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_module'),
      'href' => $this->url->link('extension/module', 'token=' . $token, 'SSL'),
      'separator' => " :: "
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $url,
      'separator' => ' :: '
    );

    $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

    $data['success'] = isset($this->session->data['success']) ? $this->session->data['success'] : '';
    unset($this->session->data['success']);

    $data['referral_coupon_status'] = $this->config->get('referral_coupon_status');

    $data['referral_coupon_limit'] = $this->config->get('referral_coupon_limit');
    $data['referral_coupon_period'] = $this->config->get('referral_coupon_period');
    $data['referral_coupon_from_email'] = $this->config->get('referral_coupon_from_email');
    $data['referral_coupon_from_custom_email'] = $this->config->get('referral_coupon_from_custom_email');
    $data['referral_coupon_from_custom_name'] = $this->config->get('referral_coupon_from_custom_name');
    $data['config_email'] = $this->config->get('config_email');

    $data['referral_coupon_reward_type'] = $this->config->get('referral_coupon_reward_type');
    $data['referral_coupon_referrer_sending_reward'] = $this->config->get('referral_coupon_referrer_sending_reward');
    $data['referral_coupon_referrer_reward_for_coupon_used_type'] = $this->config->get('referral_coupon_referrer_reward_for_coupon_used_type');
    $data['referral_coupon_referrer_reward_for_coupon_used'] = $this->config->get('referral_coupon_referrer_reward_for_coupon_used');
    $data['referral_coupon_notify'] = $this->config->get('referral_coupon_notify');

    $data['referral_coupon_type'] = $this->config->get('referral_coupon_type');
    $data['referral_coupon_discount'] = $this->config->get('referral_coupon_discount');
    $data['referral_coupon_total'] = $this->config->get('referral_coupon_total');
    $data['referral_coupon_logged'] = $this->config->get('referral_coupon_logged');
    $data['referral_coupon_shipping'] = $this->config->get('referral_coupon_shipping');
    $data['referral_coupon_expire'] = $this->config->get('referral_coupon_expire');
    $data['referral_coupon_uses_total'] = $this->config->get('referral_coupon_uses_total');
    $data['referral_coupon_uses_customer'] = $this->config->get('referral_coupon_uses_customer');
    $data['referral_coupon_installed'] = $this->config->get('referral_coupon_installed');

    $this->load->model('design/layout');
    $data['layouts'] = $this->model_design_layout->getLayouts();

    $complete_order_status = $this->db->query("SELECT GROUP_CONCAT(name SEPARATOR ', ') AS order_statuses FROM " . DB_PREFIX . "order_status WHERE language_id='" . (int)$this->config->get('config_language_id') . "' AND order_status_id IN (" . implode(',', $this->config->get('config_complete_status')) . ")")->row['order_statuses'];
    $data['checkout_note'] = str_replace('{complete_order_status}', $complete_order_status, $this->language->get('text_checkout_note'));

    $url = '';

    if (isset($this->request->get['filter_referrer'])) {
      $data['filter_referrer'] = $this->request->get['filter_referrer'];
      $url .= '&filter_referrer=' . $this->request->get['filter_referrer'];
    } else {
      $data['filter_referrer'] = '';
    }

    if (isset($this->request->get['filter_referee'])) {
      $data['filter_referee'] = $this->request->get['filter_referee'];
      $url .= '&filter_referee=' . $this->request->get['filter_referee'];
    } else {
      $data['filter_referee'] = '';
    }

    if (isset($this->request->get['filter_coupon_code'])) {
      $data['filter_coupon_code'] = $this->request->get['filter_coupon_code'];
      $url .= '&filter_coupon_code=' . $this->request->get['filter_coupon_code'];
    } else {
      $data['filter_coupon_code'] = '';
    }

    if (isset($this->request->get['filter_referred_date'])) {
      $data['filter_referred_date'] = $this->request->get['filter_referred_date'];
      $url .= '&filter_referred_date=' . $this->request->get['filter_referred_date'];
    } else {
      $data['filter_referred_date'] = '';
    }

    $data['order'] = isset($this->request->get['order']) ? $this->request->get['order'] : 'desc';
    $url .= '&order=' . ($data['order'] == 'desc' ? 'asc' : 'desc');

    $data['sort_url'] = $data['data_action'] . $url;

    if (isset($this->request->get['sort'])) {
      $data['sort'] = $this->request->get['sort'];
      $url .= '&sort=' . $this->request->get['sort'];
    } else {
      $data['sort'] = 'crp.date_added';
    }

    $data['page'] = empty($this->request->get['page']) ? 1 : $this->request->get['page'];

    $data['limit'] = $this->config->get('config_limit_admin');
    $data['start'] = ($data['page'] - 1) * $data['limit'];

    $data['referrals'] = $this->getReferrals($data);

    $data['total_referrals'] = $this->getTotalReferrals($data);

    $pagination = new Pagination();
    $pagination->total = $data['total_referrals'];
    $pagination->page = $data['page'];
    $pagination->limit = $data['limit'];
    $pagination->url = $data['data_action'] . $url . '&page={page}';

    $data['pagination'] = $pagination->render();

    $data['pagination_text'] = sprintf($this->language->get('text_pagination'), ($data['total_referrals']) ? (($data['page'] - 1) * $data['limit']) + 1 : 0, ((($data['page'] - 1) * $data['limit']) > ($data['total_referrals'] - $data['limit'])) ? $data['total_referrals'] : ((($data['page'] - 1) * $data['limit']) + $data['limit']), $data['total_referrals'], ceil($data['total_referrals'] / $data['limit']));

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('module/referral_coupon.tpl', $data));
  } //index end

  public function getReferrals($data=array()) {
    $this->load->language('module/referral_coupon');

    $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS referrer, crp.date_added AS referred_date, (SELECT customer_id FROM " . DB_PREFIX . "customer WHERE email = crp.email) AS referee_id, crp.name AS referee, cp.code AS coupon_code FROM " . DB_PREFIX . "customer_referral_coupon crp JOIN " . DB_PREFIX . "coupon cp ON cp.coupon_id = crp.coupon_id LEFT JOIN " . DB_PREFIX . "customer c ON crp.referrer_id = c.customer_id";

    $conditions = array();

    if (!empty($data['filter_referrer'])) {
      $conditions[] = "(LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(strtolower($data['filter_referrer'])) . "%' AND c.customer_id = crp.referrer_id)";
    }

    if (!empty($data['filter_referee'])) {
      $conditions[] = "(LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_referee'])) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $conditions[] = "crp.date_added LIKE '%" . $this->db->escape($data['filter_date_added']) . "%'";
    }

    if (!empty($data['filter_coupon_code'])) {
      $conditions[] = "cp.code LIKE '%" . $this->db->escape($data['filter_coupon_code']) . "%'";
    }

    if ($conditions) $sql .= " WHERE " . implode(" AND ", $conditions);

    $sql .= " ORDER BY " . $this->db->escape($data['sort']) . " " . $this->db->escape(strtoupper($data['order'])) . " LIMIT " . (int)$data['start'] . ", " . (int)$data['limit'];

    $query = $this->db->query($sql);

    return $query->rows;
  } //getReferrals end

  public function getTotalReferrals($data=array()) {
    $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_referral_coupon crp JOIN " . DB_PREFIX . "coupon cp ON cp.coupon_id = crp.coupon_id LEFT JOIN " . DB_PREFIX . "customer c ON crp.referrer_id = c.customer_id";

    $conditions = array();

    if (!empty($data['filter_referrer'])) {
      $conditions[] = "(LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(strtolower($data['filter_referrer'])) . "%' AND c.customer_id = crp.referrer_id)";
    }

    if (!empty($data['filter_referee'])) {
      $conditions[] = "(LCASE(name) LIKE '%" . $this->db->escape(strtolower($data['filter_referee'])) . "%'";
    }

    if (!empty($data['filter_date_added'])) {
      $conditions[] = "crp.date_added LIKE '%" . $this->db->escape($data['filter_date_added']) . "%'";
    }

    if ($conditions) $sql .= " WHERE " . implode(" AND ", $conditions);

    return $this->db->query($sql)->row['total'];
  } //getTotalReferrals end

  protected function validate() {
    if (!$this->user->hasPermission('modify', 'module/referral_coupon')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->error) {
      return true;
    } else {
      return false;
    }
  } //validate end

  public function install() {
    if (!$this->validate()) {
      $this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
      $this->log->write(print_r($this->error, true));
      return;
    }

    $this->load->model('extension/event');
    $this->model_extension_event->deleteEvent('referral_coupon');

    if (version_compare(VERSION, '2.2') >= 0) {
      $this->model_extension_event->addEvent('referral_coupon', 'catalog/model/checkout/order/editOrder/after', 'module/referral_coupon/updateOrder');
      $this->model_extension_event->addEvent('referral_coupon', 'catalog/model/checkout/order/addOrderHistory/after', 'module/referral_coupon/updateOrder');
    } else {
      $this->model_extension_event->addEvent('referral_coupon', 'post.order.edit', 'module/referral_coupon/couponUsedReward');
      $this->model_extension_event->addEvent('referral_coupon', 'post.order.history.add', 'module/referral_coupon/couponUsedReward');
    }

    $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "customer_referral_coupon (
      referral_coupon_id int(11) NOT NULL AUTO_INCREMENT,
      referrer_id int(11) NOT NULL,
      coupon_id int(11) NOT NULL,
      date_added datetime,
      name varchar(64),
      email varchar(96),
      PRIMARY KEY (referral_coupon_id)
    ) ENGINE=MyISAM COLLATE=utf8_general_ci;");

    if (!$this->db->query("DESC " . DB_PREFIX . "customer_reward referral_reward")->num_rows) $this->db->query("ALTER TABLE " . DB_PREFIX . "customer_reward ADD referral_reward TINYINT(1) NOT NULL");

    if (!$this->db->query("DESC " . DB_PREFIX . "customer_transaction referral_reward")->num_rows) $this->db->query("ALTER TABLE " . DB_PREFIX . "customer_transaction ADD referral_reward TINYINT(1) NOT NULL");

    $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET `code` = 'referral_coupon', `key` = 'referral_coupon_installed', `value` = '1'");

    if (isset($this->request->get['redirect'])) $this->response->redirect($this->url->link('module/referral_coupon', 'token=' . $this->session->data['token'], 'SSL'));
  } //install end

  public function uninstall() {
    $this->load->model('extension/event');
    $this->model_extension_event->deleteEvent('referral_coupon');
  } //uninstall end

} //class end
?>
