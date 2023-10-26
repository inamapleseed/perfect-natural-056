<?php
class ModelCatalogsalescombopge extends Model {
	public function addsalescombopge($data) {


		$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge SET sort_order = '" . (int)$data['sort_order'] . "', top = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "',bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "',autopopup = '" . (isset($data['autopopup']) ? (int)$data['autopopup'] : 0) . "',image = '" . $this->db->escape($data['image']) . "',backgroundcolor = '" . $this->db->escape($data['backgroundcolor']) . "',fontcolor = '" . $this->db->escape($data['fontcolor']) . "', status = '" . (int)$data['status'] . "'");

		$salescombopge_id = $this->db->getLastId();

		foreach ($data['salescombopge_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_description SET salescombopge_id = '" . (int)$salescombopge_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', message = '" . $this->db->escape($value['message']) . "', description = '" . $this->db->escape($value['description']) . "', rules =  '" . $this->db->escape($value['rules']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		if (isset($data['salescombopge_store'])) {
			foreach ($data['salescombopge_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_store SET salescombopge_id = '" . (int)$salescombopge_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['salescombopge_layout'])) {
			foreach ($data['salescombopge_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_layout SET salescombopge_id = '" . (int)$salescombopge_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		if (isset($data['salescombopge_category'])) {
			foreach ($data['salescombopge_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_category SET salescombopge_id = '" . (int)$salescombopge_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['salescombopge_product'])) {
			foreach ($data['salescombopge_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_product SET salescombopge_id = '" . (int)$salescombopge_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		if (isset($data['customergroupcst'])) {
			foreach ($data['customergroupcst'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_customer_group SET salescombopge_id = '" . (int)$salescombopge_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}

		if (isset($data['customers'])) {
			foreach ($data['customers'] as $customer_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_customer SET salescombopge_id = '" . (int)$salescombopge_id . "', customer_id = '" . (int)$customer_id . "'");
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'salescombopge_id=" . (int)$salescombopge_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('salescombopge');

		return $salescombopge_id;
	}

	public function editsalescombopge($salescombopge_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "salescombopge SET sort_order = '" . (int)$data['sort_order'] . "', top = '" . (isset($data['top']) ? (int)$data['top'] : 0) . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "',autopopup = '" . (isset($data['autopopup']) ? (int)$data['autopopup'] : 0) . "',image = '" . $this->db->escape($data['image']) . "',backgroundcolor = '" . $this->db->escape($data['backgroundcolor']) . "',fontcolor = '" . $this->db->escape($data['fontcolor']) . "', status = '" . (int)$data['status'] . "' WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_description WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($data['salescombopge_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_description SET salescombopge_id = '" . (int)$salescombopge_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', message = '" . $this->db->escape($value['message']) . "', description = '" . $this->db->escape($value['description']) . "', rules =  '" . $this->db->escape($value['rules']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_to_store WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		if (isset($data['salescombopge_store'])) {
			foreach ($data['salescombopge_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_store SET salescombopge_id = '" . (int)$salescombopge_id . "', store_id = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_to_layout WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		if (isset($data['salescombopge_layout'])) {
			foreach ($data['salescombopge_layout'] as $store_id => $layout_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_layout SET salescombopge_id = '" . (int)$salescombopge_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_to_category WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		if (isset($data['salescombopge_category'])) {
			foreach ($data['salescombopge_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_to_category SET salescombopge_id = '" . (int)$salescombopge_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_product WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		if (isset($data['salescombopge_product'])) {
			foreach ($data['salescombopge_product'] as $product_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_product SET salescombopge_id = '" . (int)$salescombopge_id . "', product_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		if (isset($data['customergroupcst'])) {
			foreach ($data['customergroupcst'] as $customer_group_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_customer_group SET salescombopge_id = '" . (int)$salescombopge_id . "', customer_group_id = '" . (int)$customer_group_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		if (isset($data['customers'])) {
			foreach ($data['customers'] as $customer_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "salescombopge_customer SET salescombopge_id = '" . (int)$salescombopge_id . "', customer_id = '" . (int)$customer_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE  query = 'salescombopge_id=" . (int)$salescombopge_id . "'");
		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'salescombopge_id=" . (int)$salescombopge_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('salescombopge');

	}

	public function deletesalescombopge($salescombopge_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_description WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_to_store WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "salescombopge_to_layout WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'salescombopge_id=" . (int)$salescombopge_id . "'");

		$this->cache->delete('salescombopge');

	}

	public function getsalescombopge($salescombopge_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'salescombopge_id=" . (int)$salescombopge_id . "') AS keyword  FROM " . DB_PREFIX . "salescombopge WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		return $query->row;
	}

	public function getsalescombopges($data = array()) {
		if ($data) {
			$sql = "SELECT * FROM " . DB_PREFIX . "salescombopge i LEFT JOIN " . DB_PREFIX . "salescombopge_description id ON (i.salescombopge_id = id.salescombopge_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sort_data = array(
				'id.title',
				'i.sort_order'
			);

			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY id.title";
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
		} else {
			$salescombopge_data = $this->cache->get('salescombopge.' . (int)$this->config->get('config_language_id'));

			if (!$salescombopge_data) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge i LEFT JOIN " . DB_PREFIX . "salescombopge_description id ON (i.salescombopge_id = id.salescombopge_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY id.title");

				$salescombopge_data = $query->rows;

				$this->cache->set('salescombopge.' . (int)$this->config->get('config_language_id'), $salescombopge_data);
			}

			return $salescombopge_data;
		}
	}

	public function getsalescombopgeDescriptions($salescombopge_id) {
		$salescombopge_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_description WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombopge_description_data[$result['language_id']] = array(
				'title'            => $result['title'],
				'description'      => $result['description'],
				'message'      => $result['message'],
				'rules'      => $result['rules'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword']
			);
		}

		return $salescombopge_description_data;
	}

	public function getsalescombopgeCategories($salescombopge_id) {
		$salescombo_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_to_category WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombo_category_data[] = $result['category_id'];
		}

		return $salescombo_category_data;
	}

	public function getsalescombopgeProducts($salescombopge_id) {
		$salescombo_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_product WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombo_related_data[] = $result['product_id'];
		}

		return $salescombo_related_data;
	}


	public function getsalescombopgeStores($salescombopge_id) {
		$salescombopge_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_to_store WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombopge_store_data[] = $result['store_id'];
		}

		return $salescombopge_store_data;
	}

	public function getProductOffeTagFields($product_id, $language_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_offertag WHERE product_id = '".(int)$product_id."' AND language_id = '".(int)$language_id."'");
		return $query->row;
	}

	public function getsalescombopgeLayouts($salescombopge_id) {
		$salescombopge_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_to_layout WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombopge_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $salescombopge_layout_data;
	}

	public function getTotalsalescombopges() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "salescombopge");

		return $query->row['total'];
	}

	public function getTotalsalescombopgesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "salescombopge_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}

	public function getCustomerGroups($salescombopge_id) {
		$customer_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		foreach ($query->rows as $result) {
			$customer_data[] = $result['customer_group_id'];
		}
		return $customer_data;
	}

	public function getCustomerGroupsNames($salescombopge_id) {
		$customer_data = array();
		$query =  $this->db->query("SELECT  cgd.name AS customer_group FROM " . DB_PREFIX . "salescombopge_customer_group c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE c.salescombopge_id = '" . (int)$salescombopge_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		foreach ($query->rows as $result) {
			$customer_data[] = $result['customer_group'];
		}
		return $customer_data;
	}
	public function getCustomers($salescombopge_id) {
		$customer_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		foreach ($query->rows as $result) {
			$customer_data[] = $result['customer_id'];
		}
		return $customer_data;
	}

	public function getCustomerNames($salescombopge_id) {
		$customer_data = array();
		$query =  $this->db->query("SELECT  CONCAT(c.firstname, ' ', c.lastname) as name, cc.customer_id FROM " . DB_PREFIX . "salescombopge_customer cc LEFT JOIN " . DB_PREFIX . "customer c ON (cc.customer_id = c.customer_id) WHERE cc.salescombopge_id = '" . (int)$salescombopge_id . "'");
		
		foreach ($query->rows as $key => $result) {
			$customer_data[$key]['name'] = $result['name'];
			$customer_data[$key]['customer_id'] = $result['customer_id'];
		}
		return $customer_data;
	}

	public function createTable() {
		$this->load->model('tool/cartbindercombo2');
    	$this->model_tool_cartbindercombo2->createTable();
		$this->load->model('tool/cartbindercombo1');
		$this->model_tool_cartbindercombo1->createTable();
		
       if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge'")->num_rows == 0) {
			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge` (
			  `salescombopge_id` int(11) NOT NULL AUTO_INCREMENT,
			  `bottom` int(1) NOT NULL DEFAULT '0',
			   `backgroundcolor` varchar(64) NOT NULL,
			    `fontcolor` varchar(64) NOT NULL,
			    `image` varchar(256) NOT NULL,
			  `top` int(1) NOT NULL DEFAULT '0',
			  `autopopup` int(1) NOT NULL DEFAULT '0',
			  `sort_order` int(3) NOT NULL DEFAULT '0',
			  `status` tinyint(1) NOT NULL DEFAULT '1',
			  PRIMARY KEY (`salescombopge_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);
			$this->load->model('extension/extension');
			$extensions = $this->model_extension_extension->getInstalled('total');

			if(!in_array("salescombo", $extensions)) {
				if (file_exists(DIR_APPLICATION . 'controller/extension/total/salescombo.php')) {
					$this->model_extension_extension->install('total', 'salescombo');
					$temp['salescombo_status'] = 1;
					$temp['salescombo_sort_order'] = 4;
					$this->load->model('setting/setting');
					$this->model_setting_setting->editSetting('salescombo', $temp);
				}
			}
		} 

		if(!$this->config->get("offerpage_installed")) {
			$this->load->model('setting/setting');
			$post['offerpage_installed'] = 1;
			$this->model_setting_setting->editSetting('offerpage',$post);
		}
		//$this->db->query("DROP TABLE `". DB_PREFIX ."salescombopge_description`");
		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_description'")->num_rows == 0) {
			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_description` (
			  `salescombopge_id` int(11) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  `title` varchar(64) NOT NULL,
			  `description` text NOT NULL,
			  `rules` text NOT NULL,
			  `message` text NOT NULL,
			  `meta_title` varchar(255) NOT NULL,
			  `meta_description` varchar(255) NOT NULL,
			  `meta_keyword` varchar(255) NOT NULL,
			  PRIMARY KEY (`salescombopge_id`,`language_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);
		}


        $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "salescombopge_description` LIKE  'rules'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."salescombopge_description` ADD  `rules` text NOT NULL");
	    }

	    $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "salescombopge_description` LIKE  'message'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."salescombopge_description` ADD  `message` text NOT NULL");
	    }

	    $sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "salescombopge` LIKE  'autopopup'";
	    $result = $this->db->query($sql)->num_rows;
	       if(!$result) {
	      	$this->db->query("ALTER TABLE  `". DB_PREFIX ."salescombopge` ADD  `autopopup` int(1) NOT NULL DEFAULT '0'");
	      	
	    }


		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_to_category'")->num_rows == 0) {

			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_to_category` (
			`salescombopge_id` int(11) NOT NULL,
			`category_id` int(11) NOT NULL,
			PRIMARY KEY (`salescombopge_id`,`category_id`),
			KEY `category_id` (`category_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);
		}

		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_product'")->num_rows == 0) {

			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_product` (
			`salescombopge_id` int(11) NOT NULL,
			`product_id` int(11) NOT NULL,
			PRIMARY KEY (`salescombopge_id`,`product_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);
		}

		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_to_layout'")->num_rows == 0) {

			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_to_layout` (
			  `salescombopge_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  `layout_id` int(11) NOT NULL,
			  PRIMARY KEY (`salescombopge_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);

		}

		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_offertag'")->num_rows == 0) {
			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_offertag` (
			  `salescombopge_offertag_id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `offertag` varchar(255) NOT NULL,
			  `language_id` int(11) NOT NULL,
			  PRIMARY KEY (`salescombopge_offertag_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

			$this->db->query($sql);

		}

		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_to_store'")->num_rows == 0) {

			 $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_to_store` (
			  `salescombopge_id` int(11) NOT NULL,
			  `store_id` int(11) NOT NULL,
			  PRIMARY KEY (`salescombopge_id`,`store_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

		$this->db->query($sql);

		}

		if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_customer_group'")->num_rows == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_customer_group` (
				  `salescombopge_id` int(11) NOT NULL,
				  `customer_group_id` int(11) NOT NULL,
				    PRIMARY KEY (`salescombopge_id`,`customer_group_id`)
				) ENGINE=MyISAM COLLATE=utf8_general_ci";
            $this->db->query($sql);          
	      }

	      if ($this->db->query("SHOW TABLES LIKE '". DB_PREFIX ."salescombopge_customer'")->num_rows == 0) {
	            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "salescombopge_customer` (
					  `salescombopge_id` int(11) NOT NULL,
					  `customer_id` int(11) NOT NULL,
					   PRIMARY KEY (`salescombopge_id`,`customer_id`)
					) ENGINE=MyISAM COLLATE=utf8_general_ci";
	            $this->db->query($sql);
	      }

	}
}