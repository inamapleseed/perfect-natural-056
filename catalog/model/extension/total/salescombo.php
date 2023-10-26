<?php
class ModelExtensionTotalSalescombo extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/salescombo');
	    $totaldiscount = array();
	    $this->session->data['salescombo_netdiscount']  = 0;
		foreach ($this->cart->getProducts() as $product) {
		    $key = $product['cart_id'];
			if(isset($this->session->data['cartbindercombooffers'][$key])) {
				$discount = 0;
				if(isset($totaldiscount[$this->session->data['cartbindercombooffers'][$key]['variation']][$this->session->data['cartbindercombooffers'][$key]['id']])) {
					$discount = $totaldiscount[$this->session->data['cartbindercombooffers'][$key]['variation']][$this->session->data['cartbindercombooffers'][$key]['id']]['discountdone'];
				}
	        	$totaldiscount[$this->session->data['cartbindercombooffers'][$key]['variation']][$this->session->data['cartbindercombooffers'][$key]['id']] = array(
				'discountdone'	=> $discount + $this->session->data['cartbindercombooffers'][$key]['discountdone'],
	    		'name' => $this->session->data['cartbindercombooffers'][$key]['name']
	    		);
				$this->session->data['salescombo_netdiscount'] += $this->session->data['cartbindercombooffers'][$key]['discountdone'];
		    }
		}
		
		foreach ($totaldiscount as $variation => $disounts) {
			foreach ($disounts as $key => $value) {
				$charge = $this->currency->format($value['discountdone'], $this->session->data['currency']);
				$total['totals'][] = array(
				'code'       => 'salescombo',
				'title'      => sprintf($this->language->get('text_salescombo'),$value['name'],$charge),
				'value'      => -$value['discountdone'],
				'sort_order' => $this->config->get('salescombo_sort_order')
				);
				$total['total'] -= $value['discountdone'];
			}
		}
	}

	public function combo1a($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1a_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 0");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}

	public function combo1atab($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1a_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 1");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}
	public function combo1b($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['foroptions'] = $this->language->get('foroptions');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1b_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 0");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}

	public function combo1btab($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['foroptions'] = $this->language->get('foroptions');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1b_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 1");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}
	public function combo1c($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['foroptions'] = $this->language->get('foroptions');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1c_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 0");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}

		return $this->load->view('extension/module/salescombo', $data);
	}

	public function combo1ctab($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['foroptions'] = $this->language->get('foroptions');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1c_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 AND displaylocation = 1");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}

		return $this->load->view('extension/module/salescombo', $data);
	}
	public function combo1($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND (FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 OR FIND_IN_SET('".(int)$product_id."',`secondarypids`)>0) AND displaylocation = 0");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}

	public function combo1tab($product_id) {
		$this->load->language('extension/total/salescombo');
		$data['addqty'] = $this->language->get('addqty');
		$data['getqty'] = $this->language->get('getqty');
		$data['offertag'] = $this->language->get('offertag');
		$data['button_bundle'] = $this->language->get('button_bundle');
		$data['offers'] = array();
		$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1_setting WHERE status = '1' AND showoffer = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW())) AND (FIND_IN_SET('".(int)$product_id."',`primarypids`)>0 OR FIND_IN_SET('".(int)$product_id."',`secondarypids`)>0) AND displaylocation = 1");
		foreach ($query->rows as $key => $value) {
			if($this->checkCg($value['cids'])) {continue;}
			$data['offers'][] = $this->generateOffer($value);
		}
		return $this->load->view('extension/module/salescombo', $data);
	}
	public function getAvailableOffers($product_id) {
		$availableoffers['notab'] = "";
		$availableoffers['tab'] = "";
		$availableoffers['notab'] .= $this->combo1a($product_id);$availableoffers['tab'] .= $this->combo1atab($product_id);
		$availableoffers['notab'] .= $this->combo1b($product_id);$availableoffers['tab'] .= $this->combo1btab($product_id);
		$availableoffers['notab'] .= $this->combo1c($product_id);$availableoffers['tab'] .= $this->combo1ctab($product_id);
		$availableoffers['notab'] .= $this->combo1($product_id);$availableoffers['tab'] .= $this->combo1tab($product_id);
		return $availableoffers;
	}

	public function checkCg($cids = array()) {
  		$cggrup = json_decode($cids,true);
        if(!empty($cggrup)) {
        	$cgid = $this->customer->getGroupId();
         	if(!in_array($cgid, $cggrup)) {
            	return 1;
         	}
        }
        return 0;
    }
	
	public function optionnames($optionids) {
		$optionarray = explode(",", $optionids);
		
		$name = $out = "";
		foreach ($optionarray as $key => $value) {
			$optionname =  $this->getOptionName($value);
			$name .= $out.$optionname;
			$out = "<br>";
		}
		return $name;
		
	}

	public function getOptionName($product_option_value_id) {
		if($product_option_value_id) {
			$product_option_value_query = $this->db->query("SELECT ovd.name as name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id)  WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND ovd.language_id = '".$this->config->get('config_language_id')."' ");
			if($product_option_value_query->num_rows) {
				return $product_option_value_query->row['name'];
			}
		} 
		return "";
	}

	public function generateOffer($value = array()) {
		  $primaryarray = explode(",",$value['primarypids']);
	      $this->load->model("catalog/product");
	      $this->load->model("catalog/category");
	      $this->load->model("offers/salescombopge");
	      $this->load->model('tool/image');
	      $data['offers'][$value['id']]['id'] = $value['variation']."_".$value['id'];
	      $data['offers'][$value['id']]['name'] = $value['name'];
	      $data['offers'][$value['id']]['bundle'] = isset($value['bundle'])?$value['bundle']:0;
	      if($value['type']) {
	        $discount = $this->currency->format($value['discount'], $this->session->data['currency']);
	        $data['offers'][$value['id']]['offervalue'] = sprintf($this->language->get("fixedoff"),$discount);
	      } else {
	        $data['offers'][$value['id']]['offervalue'] = sprintf($this->language->get("percentageoff"),$value['discount']);
	      }
	      $data['offers'][$value['id']]['offerpage'] = "";
	      if($value['sales_offer_id']) {
	        $salescombopge_info = $this->model_offers_salescombopge->getPage($value['sales_offer_id']);
	        if ($salescombopge_info) {
	          $url = $this->url->link('offers/salescombopge', 'page_id=' .  $value['sales_offer_id'], "SSL");
	          $data['offers'][$value['id']]['offerpage'] = sprintf($this->language->get('readmore_offerpage'), $url, $salescombopge_info['title']);
	        }
	      }

	      $data['offers'][$value['id']]['addproducts'] = array();

	      $data['offers'][$value['id']]['totalvalue'] = 0;
	      $data['offers'][$value['id']]['availableprice'] = 0;
	      $data['offers'][$value['id']]['totalsavings'] = 0;
	      $data['offers'][$value['id']]['totaldiscount'] = 0;

	      foreach ($primaryarray as $key => $product_id) {
	        $product_info = $this->model_catalog_product->getProduct($product_id);
	        if(empty($product_info)) {
	        	return array();
	        }
	        $product_name = $product_info['name'];
	        if ($product_info['image']) {
	          $thumb = $this->model_tool_image->resize($product_info['image'], 75, 75);
	        } else {
	          $thumb = $this->model_tool_image->resize('placeholder.png', 75, 75);
	        }
	        if($value['variation'] == 6) {
	        	$optionnames = $this->optionnames($value['optionids']);
	        } else {
	        	$optionnames = "";
	        }

        	if (($this->customer->isLogged() || !$this->config->get('config_customer_price'))) {

        		if ((float)$product_info['special']) {
		        	$product_info['price'] = $product_info['special'];
		        }

		        $data['offers'][$value['id']]['totalvalue'] += $product_info['price'] * $value['primaryquant'];

		        $data['offers'][$value['id']]['availableprice'] += $product_info['price'] * $value['primaryquant'];

		        if($value['variation'] == 3 || $value['variation'] == 6) {
			    	 $data['offers'][$value['id']]['totalvalue'] += $product_info['price'] * $value['secondaryquant'];
			    }

				$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if($value['type']) {
			        $discount = $product_info['price'] - $value['discount'];
			    } else {
			        $discount = $product_info['price'] - (($value['discount']/100.00) * $product_info['price']);
			        
			    }

			    if($value['variation'] == 3 || $value['variation'] == 6) {
			    	 $data['offers'][$value['id']]['availableprice'] += $discount * $value['secondaryquant'];
			    }

			    $discount = $this->currency->format($discount, $this->session->data['currency']);
				
			} else {
				$price = false;
				$discount = false;
			}

	        $data['offers'][$value['id']]['addproducts'][] = array(
	          'name' => $product_name,
	          'product_id' => $product_id,
	          'href' => $this->url->link('product/product', 'product_id=' . $product_id, TRUE),
	          'thumb' => $thumb,
	          'price' => $price,
	          'discount' => $discount,
	          'priqty' => $value['primaryquant'],
	          'secqty' => $value['secondaryquant'],
	          'optionnames' => $optionnames,
	        );
	      }

	      if($value['variation'] == 3 || $value['variation'] == 6) {
	      	$data['offers'][$value['id']]['getproducts'] = $data['offers'][$value['id']]['addproducts'];
	      } else if ($value['variation'] == 5) {
	      	$secondaryarray = explode(",",$value['secondarycids']);
		    $data['offers'][$value['id']]['getcategories'] = array();
		    foreach ($secondaryarray as $key => $category_id) {
		        $category_info = $this->model_catalog_category->getCategory($category_id);
		        if(empty($category_info)) {
		        	return array();
		        }
		        $category_name = $category_info['name'];
		        if ($category_info['image']) {
		          $thumb = $this->model_tool_image->resize($category_info['image'], 75, 75);
		        } else {
		          $thumb = $this->model_tool_image->resize('placeholder.png', 75, 75);
		        }
		        $data['offers'][$value['id']]['getcategories'][] = array(
		          'name' => $category_name,
		          'href' => $this->url->link('product/category', 'path=' . $category_id, TRUE),
		          'thumb' => $thumb,
		          'price' => "",
	          	  'discount' => "",
		          'secqty' => $value['secondaryquant'],
		        );
		    }
	      } else if($value['variation'] == 1) {
	      	$secondaryarray = explode(",",$value['secondarypids']);
	        $data['offers'][$value['id']]['getproducts'] = array();
	        foreach ($secondaryarray as $key => $product_id) {
		        $product_info = $this->model_catalog_product->getProduct($product_id);
		        if(empty($product_info)) {
		        	return array();
		        }
		        $product_name = $product_info['name'];
		        if ($product_info['image']) {
		          $thumb = $this->model_tool_image->resize($product_info['image'], 75, 75);
		        } else {
		          $thumb = $this->model_tool_image->resize('placeholder.png', 75, 75);
		        }

		        if (($this->customer->isLogged() || !$this->config->get('config_customer_price'))) {

		        	if ((float)$product_info['special']) {
		        		$product_info['price'] = $product_info['special'];
		        	}

		        	$data['offers'][$value['id']]['totalvalue'] += $product_info['price'] * $value['secondaryquant'];

					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					if($value['type']) {
				        $discount = $product_info['price'] - $value['discount'];
				    } else {
				        $discount = $product_info['price'] - (($value['discount']/100.00) * $product_info['price']);
				    }

				    $data['offers'][$value['id']]['availableprice'] += $discount * $value['secondaryquant'];

				    $discount = $this->currency->format($discount, $this->session->data['currency']);
					
				} else {
					$price = false;
					$discount = false;
				}

		        $data['offers'][$value['id']]['getproducts'][] = array(
		          'name' => $product_name,
		          'product_id' => $product_id,
		          'href' => $this->url->link('product/product', 'product_id=' . $product_id, TRUE),
		          'thumb' => $thumb,
		          'price' => $price,
	          	  'discount' => $discount,
		          'secqty' => $value['secondaryquant'],
		        );
	        }
	      }


	      $totalsavings = $data['offers'][$value['id']]['totalvalue'] - $data['offers'][$value['id']]['availableprice'];

	      if($totalsavings) {

	      	$data['offers'][$value['id']]['totalvalue'] = $this->currency->format($data['offers'][$value['id']]['totalvalue'], $this->session->data['currency']);

	      	$data['offers'][$value['id']]['availableprice'] = $this->currency->format($data['offers'][$value['id']]['availableprice'], $this->session->data['currency']);

	      	$data['offers'][$value['id']]['totalsavings'] = $this->currency->format($totalsavings, $this->session->data['currency']);
	      }	

	      return $data['offers'];
	}

	public function addOffersDetails($order_id) {
		$this->deleteOldOffers($order_id);
		foreach ($this->cart->getProducts() as $product) {
			$key = $product['cart_id'];
			if(isset($this->session->data['cartbindercombooffers'][$key])) {

				$offerdetails = $this->session->data['cartbindercombooffers'][$key];
				$type = ($this->session->data['cartbindercombooffers'][$key]['type'])?"Fixed":"Percentage";
			      if($this->session->data['cartbindercombooffers'][$key]['variation'] == 1) {
			        $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo1 SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarypids = '".$offerdetails['primarypids']."' ,secondarypids = '".$offerdetails['secondarypids']."',discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			      } else if($this->session->data['cartbindercombooffers'][$key]['variation'] == 2) {
			        $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo2 SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarycids = '".$offerdetails['primarycids']."' ,secondarypids = '".$offerdetails['secondarypids']."',secondarycids = '".$offerdetails['secondarycids']."' ,discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			      } else if($this->session->data['cartbindercombooffers'][$key]['variation'] == 3) {
			         $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo1a SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarypids = '".$offerdetails['primarypids']."' ,discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			     } else if($this->session->data['cartbindercombooffers'][$key]['variation'] == 4) {
			         $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo2a SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarycids = '".$offerdetails['primarycids']."' ,discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			      }  else if($this->session->data['cartbindercombooffers'][$key]['variation'] == 5) {
			         $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo1b SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarypids = '".$offerdetails['primarypids']."' , secondarycids = '".$offerdetails['secondarycids']."' , discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			      } else if($this->session->data['cartbindercombooffers'][$key]['variation'] == 6) {
			         $this->db->query("INSERT INTO " . DB_PREFIX . "cartbindercombo1c SET order_id = '" . (int)$order_id . "',offer_id = '" . (int)$offerdetails['id'] . "', customer_id = '" . (int)$this->customer->getId() . "', type = '" . $type . "',name = '" . $offerdetails['name'] . "', primarypids = '".$offerdetails['primarypids']."' , optionids = '".$offerdetails['optionids']."' , discount = '".$offerdetails['discount']."',total = '" . (float)$offerdetails['discountdone'] . "'");
			      }
	      	}
	  	}
	}

	public function deleteOldOffers($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo1 WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo1a WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo1b WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo1c WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo2 WHERE order_id = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "cartbindercombo2a WHERE order_id = '" . (int)$order_id . "'");
	}
}