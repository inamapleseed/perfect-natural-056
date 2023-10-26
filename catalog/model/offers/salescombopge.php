<?php
class Modelofferssalescombopge extends Model {
	public function getPage($salescombopge_id) {

		$query = $this->db->query("SELECT DISTINCT c.top,c.bottom,c.salescombopge_id,cd.description,cd.meta_description,cd.meta_keyword,cd.title,cd.meta_title,c.image  FROM " . DB_PREFIX . "salescombopge c LEFT JOIN " . DB_PREFIX . "salescombopge_description cd ON (c.salescombopge_id = cd.salescombopge_id)   LEFT JOIN " . DB_PREFIX . "salescombopge_to_store cs ON (c.salescombopge_id = cs.salescombopge_id)  LEFT JOIN " . DB_PREFIX . "salescombopge_customer_group ccg ON (c.salescombopge_id = ccg.salescombopge_id)  LEFT JOIN " . DB_PREFIX . "salescombopge_customer cc ON (c.salescombopge_id = cc.salescombopge_id) WHERE c.salescombopge_id = '" . (int)$salescombopge_id . "' AND cd.language_id = '".(int)$this->config->get("config_language_id")."' AND cs.store_id = '".$this->config->get("config_store_id")."' AND c.status = 1 "); 
		
		if($query->num_rows){
			if($this->validatePage($salescombopge_id)) {
				return $query->row;
			}
		} else {
			return array();
		}
	}

	public function validatePage($salescombopge_id) {
		$cgs = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		$cid = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		if(!$cgs->num_rows && !$cid->num_rows) {
			return 1;
		} 

		if($cgs->num_rows) {
			$cgid = $this->customer->getGroupId();
			foreach ($cgs->rows as $key1 => $value1) {
				if($cgid == $value1['customer_group_id']) {
					return 1;
				}
			}
		}

		if($cid->num_rows) {
			$cids = $this->customer->getId();
			foreach ($cid->rows as $key2 => $value2) {
				if($cids == $value2['customer_id']) {
					return 1;
				}
			}
		}

		return 0;

	}

	public function getPages() {
		$returnarray = array();
		$query = $this->db->query("SELECT DISTINCT c.salescombopge_id,cd.title,c.top  FROM " . DB_PREFIX . "salescombopge c LEFT JOIN " . DB_PREFIX . "salescombopge_description cd ON (c.salescombopge_id = cd.salescombopge_id)   LEFT JOIN " . DB_PREFIX . "salescombopge_to_store cs ON (c.salescombopge_id = cs.salescombopge_id)  WHERE cd.language_id = '".(int)$this->config->get("config_language_id")."' AND cs.store_id = '".$this->config->get("config_store_id")."' AND c.status = 1"); 
		
		foreach ($query->rows as $key => $value) {
			$cgs = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$value['salescombopge_id'] . "'");
			$cid = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$value['salescombopge_id'] . "'");

			if(!$cgs->num_rows && !$cid->num_rows) {
				$returnarray[$key]['top'] = $value['top'];
				$returnarray[$key]['title'] = $value['title'];
				$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
				continue;
			} 

			if($cgs->num_rows) {
				$cgid = $this->customer->getGroupId();
				foreach ($cgs->rows as $key1 => $value1) {
					if($cgid == $value1['customer_group_id']) {
						$returnarray[$key]['top'] = $value['top'];
						$returnarray[$key]['title'] = $value['title'];
						$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
						continue 2;
					}
				}
			}

			if($cid->num_rows) {
				$cids = $this->customer->getId();
				foreach ($cid->rows as $key2 => $value2) {
					if($cids == $value2['customer_id']) {
						$returnarray[$key]['top'] = $value['top'];
						$returnarray[$key]['title'] = $value['title'];
						$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
						continue 2;
					}
				}
			}

		}
		
		return $returnarray;
	}

	public function getsalescombopgeDescriptions($salescombopge_id) {
		$salescombopge_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_description WHERE salescombopge_id = '" . (int)$salescombopge_id . "' AND language_id = '".$this->config->get("config_language_id")."'");

		foreach ($query->rows as $result) {
			$salescombopge_description_data[] = array(
				'title'            => $result['title'],
				'description'      => $result['description']
			);
		}

		return $salescombopge_description_data;
	}

	public function getStores($salescombopge_id) {
		$salescombopge_store_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_to_store WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");

		foreach ($query->rows as $result) {
			$salescombopge_store_data[] = $result['store_id'];
		}

		return $salescombopge_store_data;
	}

	public function getCustomerGroups($salescombopge_id) {
		$customer_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		foreach ($query->rows as $result) {
			$customer_data[] = $result['customer_group_id'];
		}
		return $customer_data;
	}

	public function getPageLayoutId($salescombopge_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_to_layout WHERE salescombopge_id = '" . (int)$salescombopge_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return 0;
		}
	}

	public function getCustomers($salescombopge_id) {
		$customer_data = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$salescombopge_id . "'");
		foreach ($query->rows as $result) {
			$customer_data[] = $result['customer_id'];
		}
		return $customer_data;
	}
	public function getOfferByCategoryId($category_id) {

		$salescombo_category_data = array();
		$salescombo_category_data['autopopup'] = array();

		$query = $this->db->query("SELECT sd.meta_title,sd.message,sd.title,s.bottom,s.autopopup,s.salescombopge_id,s.backgroundcolor,s.fontcolor FROM " . DB_PREFIX . "salescombopge_to_category sc LEFT JOIN " . DB_PREFIX . "salescombopge s ON (s.salescombopge_id = sc.salescombopge_id) LEFT JOIN " . DB_PREFIX . "salescombopge_description sd ON (sd.salescombopge_id = sc.salescombopge_id) LEFT JOIN " . DB_PREFIX . "salescombopge_to_store cts ON (cts.salescombopge_id = sc.salescombopge_id)  WHERE category_id = '" . (int)$category_id . "' AND sd.language_id = '".$this->config->get("config_language_id")."' AND cts.store_id = '".$this->config->get("config_store_id")."'");

		foreach ($query->rows as $key => $result) {

			if($this->validatePage($result['salescombopge_id'])) {
				$salescombo_category_data[$key]['id'] = $result['salescombopge_id'];

				$href = $this->url->link('offers/salescombopge', 'page_id=' .  $result['salescombopge_id']);

				if($result['bottom']) { 
	              $link = '<a onclick="openOfferPopup('.$result['salescombopge_id'].')" class="offerpopup" id="offerpopup'.$result['salescombopge_id'].'"  title="'. $result['meta_title'] .'" >'.$result['title'].'</a>';
	            } else { 
	              $link = '<a href="'.$href.'" title='.  $result['meta_title'] .'" target="_blank">'.$result['title'].'</a>';
	            }

	            if($result['autopopup']) {
		            if(isset($this->session->data['offerdisplayedpopup'])) {
						if(!empty($this->session->data['offerdisplayedpopup']) && !in_array($result['salescombopge_id'], $this->session->data['offerdisplayedpopup'])) {
							$salescombo_category_data['autopopup'][] = $result['salescombopge_id'];
						}
					} else {
							$salescombo_category_data['autopopup'][] = $result['salescombopge_id'];
					}
				}

				$tags = array("{customer_name}" => $this->customer->getFirstName(),"{offer_link}" => $link);
				$desc = "";
				foreach ($tags as $key1 => $value) {
					$desc = str_replace($key1, $value, $result['message']);
				}

        		$html = '<div class="messagestrip alert'. $result['salescombopge_id'].'" style="background:'.$result['backgroundcolor'].';color:'.$result['fontcolor'].';" >'.$desc.'</div>';
        		$salescombo_category_data[$key]['html'] = $html;
			}
		}

		return $salescombo_category_data;
		
	}
	public function getOfferByProductId($product_id) {

		$salescombo_category_data = array();
		$salescombo_category_data['autopopup'] = array();

		$query = $this->db->query("SELECT sd.title,sd.message,sd.meta_title,s.bottom,s.autopopup,s.salescombopge_id,s.backgroundcolor,s.fontcolor FROM " . DB_PREFIX . "salescombopge_product sp LEFT JOIN " . DB_PREFIX . "salescombopge s ON (s.salescombopge_id = sp.salescombopge_id) LEFT JOIN " . DB_PREFIX . "salescombopge_description sd ON (sd.salescombopge_id = sp.salescombopge_id) LEFT JOIN " . DB_PREFIX . "salescombopge_to_store cts ON (cts.salescombopge_id = sp.salescombopge_id)  WHERE product_id = '" . (int)$product_id . "' AND sd.language_id = '".$this->config->get("config_language_id")."' AND cts.store_id = '".$this->config->get("config_store_id")."'");
		foreach ($query->rows as $key => $result) {
			if($this->validatePage($result['salescombopge_id'])) {
				$salescombo_category_data[$key]['id'] = $result['salescombopge_id'];

				$href = $this->url->link('offers/salescombopge', 'page_id=' .  $result['salescombopge_id']);

				if($result['bottom']) { 
	              $link = '<a onclick="openOfferPopup('.$result['salescombopge_id'].')" class="offerpopup" id="offerpopup'.$result['salescombopge_id'].'"  title="'. $result['meta_title'] .'" >'.$result['title'].'</a>';
	            } else { 
	              $link = '<a href="'.$href.'" title='.  $result['meta_title'] .'" target="_blank">'.$result['title'].'</a>';
	            }

	            if($result['autopopup']) {
		            if(isset($this->session->data['offerdisplayedpopup'])) {
						if(!empty($this->session->data['offerdisplayedpopup']) && !in_array($result['salescombopge_id'], $this->session->data['offerdisplayedpopup'])) {
							$salescombo_category_data['autopopup'][] = $result['salescombopge_id'];
						}
					} else {
							$salescombo_category_data['autopopup'][] = $result['salescombopge_id'];
					}
				}

				$tags = array("{customer_name}" => $this->customer->getFirstName(),"{offer_link}" => $link);
				$desc = "";
				foreach ($tags as $key1 => $value) {
					$desc = str_replace($key1, $value, $result['message']);
				}

        		$html = '<div class="messagestrip alert'. $result['salescombopge_id'].'" style="background:'.$result['backgroundcolor'].';color:'.$result['fontcolor'].';" >'.$desc.'</div>';
        		$salescombo_category_data[$key]['html'] = $html;
			}	
		}
		
		return $salescombo_category_data;
		
	}
	
	public function getAllOffers() {
		$returnarray = array();
		$query = $this->db->query("SELECT DISTINCT c.salescombopge_id,cd.rules,cd.title,cd.meta_title,c.image  FROM " . DB_PREFIX . "salescombopge c LEFT JOIN " . DB_PREFIX . "salescombopge_description cd ON (c.salescombopge_id = cd.salescombopge_id)   LEFT JOIN " . DB_PREFIX . "salescombopge_to_store cs ON (c.salescombopge_id = cs.salescombopge_id)  WHERE cd.language_id = '".(int)$this->config->get("config_language_id")."' AND cs.store_id = '".$this->config->get("config_store_id")."' AND c.status = 1 ORDER BY c.sort_order ASC"); 
		
		foreach ($query->rows as $key => $value) {
			$cgs = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer_group WHERE salescombopge_id = '" . (int)$value['salescombopge_id'] . "'");
			$cid = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_customer WHERE salescombopge_id = '" . (int)$value['salescombopge_id'] . "'");
			
			if(!$cgs->num_rows && !$cid->num_rows) {
				$returnarray[$key]['title'] = $value['title'];
				$returnarray[$key]['image'] = $value['image'];
				$returnarray[$key]['meta_title'] = $value['meta_title'];
				$returnarray[$key]['rules'] = $value['rules'];
				$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
				$returnarray[$key]['href'] = $this->url->link('offers/salescombopge', 'page_id=' .  $value['salescombopge_id']);
				continue;
			} 

			if($cgs->num_rows) {
				$cgid = $this->customer->getGroupId();
				foreach ($cgs->rows as $key1 => $value1) {
					if($cgid == $value1['customer_group_id']) {
						$returnarray[$key]['title'] = $value['title'];
						$returnarray[$key]['image'] = $value['image'];
						$returnarray[$key]['rules'] = $value['rules'];
						$returnarray[$key]['meta_title'] = $value['meta_title'];
						$returnarray[$key]['href'] = $this->url->link('offers/salescombopge', 'page_id=' .  $value['salescombopge_id']);
						$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
						continue 2;
					}
				}
			}

			if($cid->num_rows) {
				$cids = $this->customer->getId();
				foreach ($cid->rows as $key2 => $value2) {
					if($cids == $value2['customer_id']) {
						$returnarray[$key]['title'] = $value['title'];
						$returnarray[$key]['rules'] = $value['rules'];
						$returnarray[$key]['image'] = $value['image'];
						$returnarray[$key]['meta_title'] = $value['meta_title'];
						$returnarray[$key]['href'] = $this->url->link('offers/salescombopge', 'page_id=' .  $value['salescombopge_id']);
						$returnarray[$key]['salescombopge_id'] = $value['salescombopge_id'];
						continue 2;
					}
				}
			}

		}
		
		return $returnarray;
	}
}