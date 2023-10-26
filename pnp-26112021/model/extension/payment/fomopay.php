<?php
class ModelExtensionPaymentFomopay extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fomopay_request` (
			  `fomopay_id` int(11) NOT NULL AUTO_INCREMENT,
			  `callback_url` TEXT NOT NULL,
			  `currency_code` VARCHAR(20) NOT NULL,
			  `description` TEXT NOT NULL,
			  `ori_description` TEXT NOT NULL,
			  `merchant` VARCHAR(100) NOT NULL,
			  `shared_key` VARCHAR(100) NOT NULL,
			  `price` DECIMAL( 15, 4 ) NOT NULL,
			  `transaction` int(11) NOT NULL,
			  `order_id` int(11) NOT NULL,
			  `return_url` TEXT NOT NULL,
			  `timeout` int(11) NOT NULL,
			  `type` VARCHAR(100) NOT NULL,
			  `nonce` VARCHAR(100) NOT NULL,
			  `redirect_url` TEXT NOT NULL,
			  `signature` TEXT NOT NULL,
			  PRIMARY KEY (`fomopay_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fomopay_response` (
			  `fomopay_response_id` int(11) NOT NULL AUTO_INCREMENT,
			  `transaction` TEXT NOT NULL,
			  `payment_id` TEXT NOT NULL,
			  `result` TEXT NOT NULL,
			  `nonce` TEXT NOT NULL,
			  `upstream` TEXT NOT NULL,
			  `signature` TEXT NOT NULL,
			  `mysign` TEXT NOT NULL,
			  `status` int(11) NOT NULL,
			  PRIMARY KEY (`fomopay_response_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");
	}

	public function uninstall() {
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fomopay_response`");
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fomopay_request`");
	}

}