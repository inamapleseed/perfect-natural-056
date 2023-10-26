<?php
class ModelExtensionModuleGoogleLogin extends Model {
	public function getCustomer($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	
		return $query->row;
	}
	
	public function addCustomer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET store_id = '" . (int)$data['store_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', password = '" . $this->db->escape($data['password']) . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', newsletter = '" . (int)$data['newsletter'] . "', ip = '" . $this->db->escape($data['ip']) . "', status = '1', approved = '" . (int)$data['approved'] . "', date_added = NOW()");
	
		$customer_id = $this->db->getLastId();
	
		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) ."', country_id = '" . (int)$this->config->get('config_country_id') . "'");
	
		$address_id = $this->db->getLastId();
	
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
		return $address_id;
	}
}