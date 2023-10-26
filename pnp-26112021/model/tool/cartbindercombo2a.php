<?php
class ModelToolcartbindercombo2a extends Model {
	public function addcartbindercombo2a($data) {
		asort($data['primarycategories']);
		$primarycids = implode(",", $data['primarycategories']);
		if(isset($data['excludeproducts'])){
			$excludeproducts = implode(",", $data['excludeproducts']);
		} else{
			$excludeproducts = '';
		}
		if(!isset($data['cids'])){$data['cids']=array();}
		$this->db->query("INSERT INTO  " . DB_PREFIX . "cartbindercombo2a_setting SET type = '" . (int)$data['type'] . "',discount = '" . (float)$data['discount'] . "',anyorall = '" . (int)$data['anyorall'] . "',primaryquant = '" . (float)$data['primaryquant'] . "',sales_offer_id = '".(int)$data['sales_offer_id']."',secondaryquant = '" . (float)$data['secondaryquant'] . "', primarycids = '".$this->db->escape($primarycids)."',excludeproducts = '".$this->db->escape($excludeproducts)."',name = '".$this->db->escape($data['name'])."', cids = '".json_encode($data['cids'])."', multidiscount = '" . $this->db->escape($data['multidiscount']) . "', datestart = '" . $this->db->escape($data['datestart']) . "', dateend = '" . $this->db->escape($data['dateend']) . "', status = '".(int)$data['status']."'");
	}

	public function editcartbindercombo2a($id, $data) {
		asort($data['primarycategories']);
		$primarycids = implode(",", $data['primarycategories']);
		
		if(isset($data['excludeproducts'])){
			$excludeproducts = implode(",", $data['excludeproducts']);
		} else{
			$excludeproducts = '';
		}
		if(!isset($data['cids'])){$data['cids']=array();}
		$this->db->query("UPDATE " . DB_PREFIX . "cartbindercombo2a_setting SET type = '" . (int)$data['type'] . "',discount = '" . (float)$data['discount'] . "',anyorall = '" . (int)$data['anyorall'] . "',primaryquant = '" . (float)$data['primaryquant'] . "',sales_offer_id = '".(int)$data['sales_offer_id']."',secondaryquant = '" . (float)$data['secondaryquant'] . "', primarycids = '".$this->db->escape($primarycids)."',excludeproducts = '".$this->db->escape($excludeproducts)."',name = '".$this->db->escape($data['name'])."', cids = '".json_encode($data['cids'])."', multidiscount = '" . $this->db->escape($data['multidiscount']) . "', datestart = '" . $this->db->escape($data['datestart']) . "', dateend = '" . $this->db->escape($data['dateend']) . "', status = '".(int)$data['status']."' WHERE id = '" . (int)$id . "'");
	}
	
	public function delete($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo2a_setting WHERE id = '" . (int)$id . "'");
	}

	public function getcartbindercombo2a($id) {
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo2a_setting WHERE id = '" . (int)$id . "'");
		return $query->row;
	}

	public function getTotalForOffer($offer_id) {
		$query = $this->db->query("SELECT  SUM(total) as total FROM " . DB_PREFIX . "cartbindercombo2a WHERE offer_id = '" . (int)$offer_id . "'");
		if ($query->num_rows) {
			return $this->currency->format($query->row['total'], $this->config->get('config_currency'));	
		} else {
			return 0;
		}
	}

	public function getTotalOfferApplied($offer_id) {
		$query = $this->db->query("SELECT  DISTINCT(order_id) FROM " . DB_PREFIX . "cartbindercombo2a WHERE offer_id = '" . (int)$offer_id . "'");
		return $query->num_rows;
	}

	public function getCNames($cids) {
		$categories = explode(",", $cids);
		$name = $out = "";
		foreach ($categories as $key => $value) {
			$categoryname =  $this->getCategory($value);
			$name .= $out.$categoryname;
			$out = "<br>";
		}
		return $name;
		
	}

	public function getCategory($category_id) {
		if($category_id) {
			$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "category c  LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
			return $query->row['name'];
		} else {
			return "";
		}
	}

	public function getcartbindercombo2as($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cartbindercombo2a_setting c  WHERE  1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}	


		$sort_data = array(
			'c.name',
			'c.id',
			'c.status'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function getTotalcartbindercombo2a($data) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "cartbindercombo2a_setting c WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}	
		
		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}
}
?>