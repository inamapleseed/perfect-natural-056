<?php
class ModelToolcartbindercombo1 extends Model {
	public function addcartbindercombo1($data) {
		asort($data['primaryproducts']);
		asort($data['secondaryproducts']);
		$primarypids = implode(",", $data['primaryproducts']);
		$secondarypids = implode(",", $data['secondaryproducts']);
		if(!isset($data['cids'])){$data['cids']=array();}
		$this->db->query("INSERT INTO  " . DB_PREFIX . "cartbindercombo1_setting SET type = '" . (int)$data['type'] . "',discount = '" . (float)$data['discount'] . "',primaryquant = '" . (float)$data['primaryquant'] . "',secondaryquant = '" . (float)$data['secondaryquant'] . "', primarypids = '".$this->db->escape($primarypids)."',secondarypids = '".$this->db->escape($secondarypids)."',name = '".$this->db->escape($data['name'])."', sales_offer_id = '".(int)$data['sales_offer_id']."', cids = '".json_encode($data['cids'])."', showoffer = '".(int)$data['showoffer']."', displaylocation = '".(int)$data['displaylocation']."', bundle = '".(int)$data['bundle']."', autoadd = '".(int)$data['autoadd']."',  datestart = '" . $this->db->escape($data['datestart']) . "', dateend = '" . $this->db->escape($data['dateend']) . "', status = '".(int)$data['status']."'");
	}

	public function editcartbindercombo1($id, $data) {
		asort($data['primaryproducts']);
		asort($data['secondaryproducts']);
		$primarypids = implode(",", $data['primaryproducts']);
		if(!isset($data['cids'])){$data['cids']=array();}
		$secondarypids = implode(",", $data['secondaryproducts']);
		$this->db->query("UPDATE " . DB_PREFIX . "cartbindercombo1_setting SET type = '" . (int)$data['type'] . "',discount = '" . (float)$data['discount'] . "',primaryquant = '" . (float)$data['primaryquant'] . "',secondaryquant = '" . (float)$data['secondaryquant'] . "', primarypids = '".$this->db->escape($primarypids)."',secondarypids = '".$this->db->escape($secondarypids)."',name = '".$this->db->escape($data['name'])."', sales_offer_id = '".(int)$data['sales_offer_id']."', cids = '".json_encode($data['cids'])."', showoffer = '".(int)$data['showoffer']."', displaylocation = '".(int)$data['displaylocation']."', bundle = '".(int)$data['bundle']."', autoadd = '".(int)$data['autoadd']."',  datestart = '" . $this->db->escape($data['datestart']) . "', dateend = '" . $this->db->escape($data['dateend']) . "', status = '".(int)$data['status']."' WHERE id = '" . (int)$id . "'");
	}
	
	public function delete($id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo1_setting WHERE id = '" . (int)$id . "'");
	}

	public function getcartbindercombo1($id) {
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1_setting WHERE id = '" . (int)$id . "'");

		return $query->row;
	
	}

	public function getTotalForOffer($offer_id) {
		$query = $this->db->query("SELECT  SUM(total) as total FROM " . DB_PREFIX . "cartbindercombo1 WHERE offer_id = '" . (int)$offer_id . "'");
		if ($query->num_rows) {
			return $this->currency->format($query->row['total'], $this->config->get('config_currency'));	
		} else {
			return 0;
		}
	}

	public function getTotalOfferApplied($offer_id) {
		$query = $this->db->query("SELECT  DISTINCT(order_id) FROM " . DB_PREFIX . "cartbindercombo1 WHERE offer_id = '" . (int)$offer_id . "'");
		return $query->num_rows;
	}
	
	public function getNames($pids) {
		$products = explode(",", $pids);
		$name = $out = "";
		foreach ($products as $key => $value) {
			$productname =  $this->getProduct($value);
			$name .= $out.$productname;
			$out = "<br>";
		}
		return $name;
		
	}

	public function getProduct($product_id) {
		$query = $this->db->query("SELECT pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		return $query->row['name'];
	}

	

	public function getcartbindercombo1s($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "cartbindercombo1_setting c  WHERE  1 ";

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
	
	public function getTotalcartbindercombo1($data) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "cartbindercombo1_setting c WHERE 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND c.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}	
		
		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}

	public function createTable() {
	    //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1_setting`");
	    if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1_setting'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `status` tinyint(1) NOT NULL DEFAULT '1',
				  `name`  varchar(255) NOT NULL,
				  `primarypids` text NOT NULL,
				  `cids` text  NOT NULL,
				  `datestart` date NOT NULL DEFAULT '0000-00-00',
				  `dateend` date NOT NULL DEFAULT '0000-00-00',
				  `type` int(11) NOT NULL,
				  `autoadd` tinyint(1) NOT NULL,
				  `sort_order` int(11) NOT NULL,
				  `discount` float(11) NOT NULL,
				  `primaryquant` float(11) NOT NULL,
				  `variation`  int(11) NOT NULL DEFAULT '1',
				  `secondarypids` text NOT NULL,`secondaryquant` float(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM COLLATE=utf8_general_ci"; 
       			  $this->db->query($sql);
        }

        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'variation'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."cartbindercombo1_setting` ADD  `variation`  int(11) NOT NULL DEFAULT '1'");
	    }

	    $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'sales_offer_id'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."cartbindercombo1_setting` ADD  `sales_offer_id`  int(11) NOT NULL");
	    }

        //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1` (
				  `order_offer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `offer_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `customer_id` int(11) NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				   `primarypids` text NOT NULL,
				  `secondarypids` text NOT NULL,
				  `discount`  float(11) NOT NULL,
				  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
				   PRIMARY KEY (`order_offer_id`)
				) ENGINE=MyISAM COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

         //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1a_setting`");
	    if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1a_setting'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1a_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `status` tinyint(1) NOT NULL DEFAULT '1',
				  `name`  varchar(255) NOT NULL,
				  `primarypids` text NOT NULL,
				  `type` int(11) NOT NULL,
				  `autoadd` tinyint(1) NOT NULL,
				  `datestart` date NOT NULL DEFAULT '0000-00-00',
				  `dateend` date NOT NULL DEFAULT '0000-00-00',
				  `discount` float(11) NOT NULL,
				  `cids` text  NOT NULL,
				  `primaryquant` float(11) NOT NULL,
				  `sales_offer_id`  int(11) NOT NULL,
				  `variation`  int(11) NOT NULL DEFAULT '3',
				  `secondaryquant` float(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM COLLATE=utf8_general_ci";
       			  $this->db->query($sql);
        }

        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1a_setting` LIKE  'multidiscount'";
	    $result = $this->db->query($sql)->num_rows;
	    if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."cartbindercombo1a_setting` ADD  `multidiscount`  varchar(512) NOT NULL");
	    }

        //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1a`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1a'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1a` (
				  `order_offer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `offer_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `customer_id` int(11) NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				   `primarypids` text NOT NULL,
				  `secondarypids` text NOT NULL,
				  `discount`  float(11) NOT NULL,
				  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
				   PRIMARY KEY (`order_offer_id`)
				) ENGINE=MyISAM COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

        //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1b_setting`");
	    if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1b_setting'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1b_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `status` tinyint(1) NOT NULL DEFAULT '1',
				  `name`  varchar(255) NOT NULL,
				  `primarypids` text NOT NULL,
				  `type` int(11) NOT NULL,
				  `datestart` date NOT NULL DEFAULT '0000-00-00',
				  `dateend` date NOT NULL DEFAULT '0000-00-00',
				  `cids` text  NOT NULL,
				  `discount` float(11) NOT NULL,
				  `anyorall` int(11) NOT NULL,
				  `sales_offer_id`  int(11) NOT NULL,
				  `primaryquant` float(11) NOT NULL,
				  `variation`  int(11) NOT NULL DEFAULT '5',
				  `secondarycids` text NOT NULL,`secondaryquant` float(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM COLLATE=utf8_general_ci";
       			  $this->db->query($sql);
        }

        //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1b`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1b'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1b` (
				  `order_offer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `offer_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `customer_id` int(11) NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				   `primarypids` text NOT NULL,
				  `secondarycids` text NOT NULL,
				  `discount`  float(11) NOT NULL,
				  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
				   PRIMARY KEY (`order_offer_id`)
				) ENGINE=MyISAM COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }


        //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1c_setting`");
	    if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1c_setting'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1c_setting` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `status` tinyint(1) NOT NULL DEFAULT '1',
				  `name`  varchar(255) NOT NULL,
				  `primarypids` text NOT NULL,
				  `type` int(11) NOT NULL,
				  `autoadd` tinyint(1) NOT NULL,
				  `discount` float(11) NOT NULL,
				  `anyorall` int(11) NOT NULL,
				  `cids` text  NOT NULL,
				  `datestart` date NOT NULL DEFAULT '0000-00-00',
				  `dateend` date NOT NULL DEFAULT '0000-00-00',
				  `sales_offer_id`  int(11) NOT NULL,
				  `primaryquant` float(11) NOT NULL,
				  `variation`  int(11) NOT NULL DEFAULT '6',
				  `optionidarray` text NOT NULL,
				  `optionids` text NOT NULL,`secondaryquant` float(11) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM COLLATE=utf8_general_ci";
       			  $this->db->query($sql);
        }

       //$this->db->query("DROP TABLE `". DB_PREFIX ."cartbindercombo1c`");
        if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."cartbindercombo1c'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cartbindercombo1c` (
				  `order_offer_id` int(11) NOT NULL AUTO_INCREMENT,
				  `offer_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `customer_id` int(11) NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `name` varchar(255) NOT NULL,
				   `primarypids` text NOT NULL,
				  `optionids` text NOT NULL,
				  `discount`  float(11) NOT NULL,
				  `total` decimal(15,4) NOT NULL DEFAULT '0.0000',
				   PRIMARY KEY (`order_offer_id`)
				) ENGINE=MyISAM COLLATE=utf8_general_ci";
            $this->db->query($sql);
        }

        if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'cids'")->num_rows) {
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting`  ADD  `cids` text  NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1a_setting`  ADD  `cids` text  NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1b_setting`  ADD  `cids` text  NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1c_setting`  ADD  `cids` text  NOT NULL");
	  	}

	  	if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'showoffer'")->num_rows) {
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting` ADD `showoffer` tinyint(1) NOT NULL, ADD `displaylocation` tinyint(1) NOT NULL, ADD `bundle` tinyint(1) NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1a_setting` ADD `showoffer` tinyint(1) NOT NULL, ADD `displaylocation` tinyint(1) NOT NULL, ADD `bundle` tinyint(1) NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1b_setting` ADD `showoffer` tinyint(1) NOT NULL, ADD `displaylocation` tinyint(1) NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1c_setting` ADD  `showoffer` tinyint(1) NOT NULL, ADD `displaylocation` tinyint(1) NOT NULL");
	  	}

	  	if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'sort_order'")->num_rows) {
	  		
	  		$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting` DROP `datestart`, DROP `dateend` ");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1a_setting` DROP `datestart`, DROP `dateend` ");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1b_setting` DROP `datestart`, DROP `dateend` ");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1c_setting` DROP  `datestart`, DROP `dateend` ");

	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting` ADD `datestart` date NOT NULL DEFAULT '0000-00-00', ADD `dateend` date NOT NULL DEFAULT '0000-00-00'");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1a_setting` ADD `datestart` date NOT NULL DEFAULT '0000-00-00', ADD `dateend` date NOT NULL DEFAULT '0000-00-00'");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1b_setting` ADD `datestart` date NOT NULL DEFAULT '0000-00-00', ADD `dateend` date NOT NULL DEFAULT '0000-00-00'");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1c_setting` ADD  `datestart` date NOT NULL DEFAULT '0000-00-00', ADD `dateend` date NOT NULL DEFAULT '0000-00-00'");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting` ADD `sort_order` int(11) NOT NULL");
	  	}

	  	if(!$this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "cartbindercombo1_setting` LIKE  'autoadd'")->num_rows) {
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1_setting` ADD `autoadd` tinyint(1) NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1a_setting` ADD `autoadd` tinyint(1) NOT NULL");
	    	$this->db->query("ALTER TABLE `" . DB_PREFIX . "cartbindercombo1c_setting` ADD  `autoadd` tinyint(1) NOT NULL");
	  	}
	}
}
?>