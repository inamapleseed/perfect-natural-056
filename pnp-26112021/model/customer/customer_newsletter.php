<?php
class ModelCustomerCustomerNewsletter extends Model {
	public function getCustomerNewsletter($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer_newsletter_list cn LEFT JOIN " . DB_PREFIX . "customer c ON (cn.customer_id = c.customer_id) WHERE cn.customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerNewsletters($data = array()) {
		$sql = "SELECT cn.id, CONCAT(c.firstname, ' ', c.lastname) AS name, cn.email, cn.date_added, cn.customer_id FROM " . DB_PREFIX . "customer_newsletter_list cn LEFT JOIN " . DB_PREFIX . "customer c ON (cn.customer_id = c.customer_id) WHERE 1=1";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "cn.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(cn.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'cn.email',
			'cn.date_added',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalCustomerNewsletters() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_newsletter_list");

		return $query->row['total'];
	}
}
