<?php
class ModelCommonAnalytics extends Model {
    public function trackVisitor($route, $customer_id = 0) {
        $visitor_id = $this->getVisitorId($customer_id);
        return $this->insertVisitorLog($visitor_id, $route);
    }

    private function getVisitorId($customer_id = 0) {
        $session_id = session_id();
        $ip_address = $this->db->escape($_SERVER['REMOTE_ADDR']);
        $user_agent = $this->db->escape($_SERVER['HTTP_USER_AGENT']);
        $http_headers_json = $this->db->escape(json_encode($_SERVER));

        $logged_visitor = $this->db->query("
            SELECT * FROM `" . DB_PREFIX . "visitor` WHERE `session_id` = '{$session_id}'
        ");

        if ($logged_visitor->num_rows) {
            /* A visitor exists for this session_id */
            $visitor_id = $logged_visitor->row['visitor_id'];

            /* Update this visitor with the relevant customer_id if customer logs in. */
            if ($customer_id > 0 && $logged_visitor->row['customer_id'] == 0) {
                $this->db->query("UPDATE `" . DB_PREFIX . "visitor` SET `customer_id`='{$customer_id}' WHERE `visitor_id`='{$visitor_id}'");
            }

            return $visitor_id;
        }

        /* New visitor */
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "visitor` (`session_id`, `ipv4_addr`, `user_agent`, `http_headers`) 
            VALUES('{$session_id}', '{$ip_address}', '{$user_agent}', '{$http_headers_json}')
        ");

        $visitor_id = $this->db->getLastId();
        return $visitor_id;
    }

    private function insertVisitorLog($visitor_id, $route) {
        $this->db->query(
            "INSERT INTO `" . DB_PREFIX . "visitor_log` (`visitor_id`, `route`) 
            VALUES('{$visitor_id}', '{$route}')
        ");
        return $this->db->getLastId();
    }
}