<?php
namespace Cart;
class Cart {
	// << Related Options / Связанные опции 
		private $relatedoptions_model = false;
		private $ro_global_registry = false;
	// >> Related Options / Связанные опции 
	private $data = array();

	public function __construct($registry) {
		// << Related Options
		$this->ro_global_registry = $registry;
		// >> Related Options
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		/* completecombo */
		if (!isset($this->session->data['slscmdprdts']) || !is_array($this->session->data['slscmdprdts'])) {$this->session->data['slscmdprdts'] = array();}
     	if (!isset($this->session->data['cartbindercombooffers']) || !is_array($this->session->data['cartbindercombooffers'])) { $this->session->data['cartbindercombooffers'] = array(); }
		if (!isset($this->session->data['removedproduct'])) {$this->session->data['removedproduct'] = array();}
        if (!isset($this->session->data['autoaddedproduct'])) {$this->session->data['autoaddedproduct'] = array();}
		/* completecombo */

		// Remove all the expired carts with no customer ID
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE (api_id > '0' OR customer_id = '0') AND date_added < DATE_SUB(NOW(), INTERVAL 4 HOUR)");

		if ($this->customer->getId()) {
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");

				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				$this->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
			}
		}
	}
	// << Related Options / Связанные опции 
			
	private function ro_calc_price($product_price, $ro_combs) {

		$ro_model = $this->ro_get_model();
		$ro_price_data = $ro_model->calc_price_with_ro($product_price, $ro_combs);

		return $ro_price_data;
		//return $ro_price_data['price'];
	}

	private function ro_get_model() {
		global $loader, $registry;
		
		if ( !$this->relatedoptions_model ) {
		
			$current_loader = $loader;
			if ( $this->ro_global_registry ) {
				$current_loader = $this->ro_global_registry->get('load');
				$current_registry = $this->ro_global_registry;
			} else {
				if (!$loader || !is_object($loader) || !method_exists($loader, 'model')) {
					$current_loader = new Loader($registry);
					$current_registry = $registry;
				}
			}
			
			if ( !$current_registry->get('model_module_related_options') ) {
				$current_loader->model('module/related_options');
			}
			$this->relatedoptions_model = $current_registry->get('model_module_related_options');
		}
		return $this->relatedoptions_model;
		
	}

	private function ro_get_products_data(&$ro_quantities) {
		
		$ro_model = $this->ro_get_model();
		
		$ro_for_products = array();
		$ro_quantities = array(); // total quantities by related options
		
		if (	$ro_model->installed() ) {
			if (!$this->data) {
			
				if ( VERSION >= '2.1.0.0' ) {
					$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

					foreach ($cart_query->rows as $cart) {
						$key = $cart['cart_id'];
						$product_id = $cart['product_id'];
						$quantity = $cart['quantity'];
						
						if ($quantity > 0) {
							$options = (array)json_decode($cart['option']);
							
							
							$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options, true, true);
							//$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options);
							
							if ($ro_for_products[$key]) {
								foreach ($ro_for_products[$key] as $ro_comb) {
									if (!isset($ro_quantities[$ro_comb['relatedoptions_id']])) {
										$ro_quantities[$ro_comb['relatedoptions_id']] = 0;
									}
									$ro_quantities[$ro_comb['relatedoptions_id']]+= $quantity;
								}
							}
						}
					}
				} else {
			
					foreach ($this->session->data['cart'] as $key => $quantity) {
						$product = unserialize(base64_decode($key));
			
						$product_id = $product['product_id'];
			
						// Options
						if (!empty($product['option'])) {
							$options = $product['option'];
						} else {
							$options = array();
						}
						
						$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options, true, true);
						//$ro_for_products[$key] = $ro_model->get_related_options_sets_by_poids($product_id, $options);
						
						if ($ro_for_products[$key]) {
							foreach ($ro_for_products[$key] as $ro_comb) {
								if (!isset($ro_quantities[$ro_comb['relatedoptions_id']])) {
									$ro_quantities[$ro_comb['relatedoptions_id']] = 0;
								}
								$ro_quantities[$ro_comb['relatedoptions_id']]+= $quantity;
							}
						}
					}
				}
			}
		}
		
		return $ro_for_products;
		
	}
			
	// >> Related Options / Связанные опции

	public function getProducts($cart_id = 0) {
		// << Related Options / Связанные опции 
				
		$ro_quantities = array();
		$ro_for_products = $this->ro_get_products_data($ro_quantities);
		if ($ro_for_products) {
			$ro_settings = $this->config->get('related_options');
		} else {
			$ro_settings = false;
		}
		
		// >> Related Options / Связанные опции
		$product_data = array();

		// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)
		$config_dependent_option = $this->config->get('config_dependent_option');
		$cart_opts = array();
		// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)

		if($cart_id == 0){
    		$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
        }else{
            $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND cart_id = '" . $cart_id . "'");
        }
		foreach ($cart_query->rows as $cart) {
			$stock = true;

			$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

			if ($product_query->num_rows && ($cart['quantity'] > 0)) {
				// << Related Options / Связанные опции 

					$ro_price = false;
					if ( VERSION >= '2.1.0.0' ) {
						$key = $cart['cart_id'];
						$ro_cart_quantity = $cart['quantity'];
					} else {
						$ro_cart_quantity = $quantity;
					}
					
					$ro_for_product = false;
					if ($ro_for_products && isset($ro_for_products[$key]) ) {
						$ro_for_product = $ro_for_products[$key];
					} elseif ( !$key && !empty($cart) ) {
						$ro_temp_options = json_decode($cart['option']);
						$ro_for_product = $this->relatedoptions_model->get_related_options_sets_by_poids($cart['product_id'], $ro_temp_options, true);
						
					}
					if ( $ro_for_product ) {
						$ro_model = '';
						$ro_weight = false;
						
						if ( isset($ro_settings['spec_price']) && $ro_settings['spec_price'] ) {
							$ro_price_data = $this->ro_calc_price($product_query->row['price'], $ro_for_product);
							//$ro_price_data = $this->ro_calc_price($product_query->row['price'], $ro_for_products[$key]);
						}
						
						$last_model_is_from_product = false;
						foreach ($ro_for_product as $ro_comb) {
						//foreach ($ro_for_products[$key] as $ro_comb) {
							if ($ro_comb['quantity'] < $ro_cart_quantity && ( empty($ro_settings['allow_zero_select']) || !$ro_settings['allow_zero_select']) ) {
								$stock = false;
							}
							
							if ( isset($ro_settings['spec_model']) && $ro_settings['spec_model'] ) {
								if ($ro_settings['spec_model'] == 1) {
									$ro_model = $ro_comb['model'];
								} elseif ($ro_settings['spec_model'] == 2) {
									if ( $ro_model && isset($ro_settings['spec_model_delimiter_ro']) ) {
										$ro_model.= $ro_settings['spec_model_delimiter_ro'];
									}
									$ro_model.= $ro_comb['model'];
								} elseif ($ro_settings['spec_model'] == 3) {
									if ($ro_model == '') {
										$ro_model = $product_query->row['model'];
										$last_model_is_from_product = true;
									}
									if ( $last_model_is_from_product && isset($ro_settings['spec_model_delimiter_product']) ) {
										$ro_model.= $ro_settings['spec_model_delimiter_product'];
									} elseif ( !$last_model_is_from_product && isset($ro_settings['spec_model_delimiter_ro']) ) {
										$ro_model.= $ro_settings['spec_model_delimiter_ro'];
									}
									$ro_model.= $ro_comb['model'];
									$last_model_is_from_product = false;
								}
							}
							
							// Related Options weight
							if (isset($ro_settings['spec_weight']) && $ro_settings['spec_weight'] ) {
								
								if ( $ro_comb['weight'] != 0 ) {
									if ($ro_comb['weight_prefix'] == '+') {
										if ($ro_weight === false) $ro_weight = $product_query->row['weight'];
										$ro_weight+= $ro_comb['weight'];
									} elseif ($ro_comb['weight_prefix'] == '-') {
										if ($ro_weight === false) $ro_weight = -$product_query->row['weight'];
										$ro_weight-= $ro_comb['weight'];
									} else { // =
										$ro_weight = $ro_comb['weight'];
									}
								}
							}
							
						}
						
						if ($ro_model) {
							$product_query->row['model'] = $ro_model;
						}
						
						if (isset($ro_settings['spec_weight']) && $ro_settings['spec_weight'] && $ro_weight !== false ) {
							$product_query->row['weight'] = $ro_weight;
						}
						
					}
					

				// >> Related Options / Связанные опции

				$sku = $product_query->row['sku'];

				$option_price = 0;
				$option_points = 0;
				$option_weight = 0;

				$option_data = array();

				$option_image = '';

				foreach (json_decode($cart['option']) as $product_option_id => $value) {
					$option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

					if ($option_query->num_rows) {
						
						$option_image = $this->getProductOptionImageByProductID((int)$cart['product_id'], $value);  
					
						if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
							$option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, pov.sku FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

							if ($option_value_query->num_rows) {

								if( trim($option_value_query->row['sku']) != '' ){
									$sku = trim($option_value_query->row['sku']);
								}

								if ($option_value_query->row['price_prefix'] == '+') {
									$option_price += $option_value_query->row['price'];
								} elseif ($option_value_query->row['price_prefix'] == '-') {
									$option_price -= $option_value_query->row['price'];
								}

								if ($option_value_query->row['points_prefix'] == '+') {
									$option_points += $option_value_query->row['points'];
								} elseif ($option_value_query->row['points_prefix'] == '-') {
									$option_points -= $option_value_query->row['points'];
								}

								if ($option_value_query->row['weight_prefix'] == '+') {
									$option_weight += $option_value_query->row['weight'];
								} elseif ($option_value_query->row['weight_prefix'] == '-') {
									$option_weight -= $option_value_query->row['weight'];
								}

								if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
									$stock = false;
								}

								// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)
								if ($config_dependent_option) {
									$opt_key = $value.'-'.$option_value_query->row['name'];
									
									if($option_value_query->row['subtract']) {
										if(isset($cart_opts[$opt_key])) {
											$cart_opts[$opt_key] += $cart['quantity'];
										}
										else {
											$cart_opts[$opt_key] = $cart['quantity'];
										}
									}
									
									if(isset($cart_opts[$opt_key]) && $cart_opts[$opt_key] > $option_value_query->row['quantity']) {
										$stock = false;
									}
								}
								// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)

								$option_data[] = array(
									'product_option_id'       => $product_option_id,
									'product_option_value_id' => $value,
									'option_id'               => $option_query->row['option_id'],
									'option_value_id'         => $option_value_query->row['option_value_id'],
									'sku'          		      => $option_value_query->row['sku'],
									'name'                    => $option_query->row['name'],
									'value'                   => $option_value_query->row['name'],
									'type'                    => $option_query->row['type'],
									'quantity'                => $option_value_query->row['quantity'],
									'subtract'                => $option_value_query->row['subtract'],
									'price'                   => $option_value_query->row['price'],
									'price_prefix'            => $option_value_query->row['price_prefix'],
									'points'                  => $option_value_query->row['points'],
									'points_prefix'           => $option_value_query->row['points_prefix'],
									'weight'                  => $option_value_query->row['weight'],
									'weight_prefix'           => $option_value_query->row['weight_prefix']
								);
							}
						} elseif (($option_query->row['type'] == 'quantity')) {
							
							foreach ($value as $product_option_value_id => $quantity) {			
								$option_value_query = $this->db->query("SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name, pov.sku FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

								if ($option_value_query->num_rows) {

									if( trim($option_value_query->row['sku']) != '' ){
										$sku = trim($option_value_query->row['sku']);
									}

									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += ($option_value_query->row['price'] * $quantity[0]);
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= ($option_value_query->row['price'] * $quantity[0]);
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}
									
									// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)
									if ($config_dependent_option) {
										$opt_key = $product_option_value_id.'-'.$option_value_query->row['name'];
										
										if($option_value_query->row['subtract']) {
											if(isset($cart_opts[$opt_key])) {
												$cart_opts[$opt_key] += $cart['quantity'];
											}
											else {
												$cart_opts[$opt_key] = $cart['quantity'];
											}
										}
										
										if(isset($cart_opts[$opt_key]) && $cart_opts[$opt_key] > $option_value_query->row['quantity']) {
											$stock = false;
										}
									}
									// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_value_query->row['name'],
										'value'                   => $quantity[0],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => ($option_value_query->row['price'] * $quantity[0]),
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
								}
							}
						} elseif (($option_query->row['type'] == 'checkbox') && is_array($value)) {
							foreach ($value as $product_option_value_id) {								
								$option_value_query = $this->db->query("SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name, pov.sku FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
								if ($option_value_query->num_rows) {

									if( trim($option_value_query->row['sku']) != '' ){
										$sku = trim($option_value_query->row['sku']);
									}

									if ($option_value_query->row['price_prefix'] == '+') {
										$option_price += $option_value_query->row['price'];
									} elseif ($option_value_query->row['price_prefix'] == '-') {
										$option_price -= $option_value_query->row['price'];
									}

									if ($option_value_query->row['points_prefix'] == '+') {
										$option_points += $option_value_query->row['points'];
									} elseif ($option_value_query->row['points_prefix'] == '-') {
										$option_points -= $option_value_query->row['points'];
									}

									if ($option_value_query->row['weight_prefix'] == '+') {
										$option_weight += $option_value_query->row['weight'];
									} elseif ($option_value_query->row['weight_prefix'] == '-') {
										$option_weight -= $option_value_query->row['weight'];
									}

									if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
										$stock = false;
									}
									
									// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)
									if ($config_dependent_option) {
										$opt_key = $product_option_value_id.'-'.$option_value_query->row['name'];
										
										if($option_value_query->row['subtract']) {
											if(isset($cart_opts[$opt_key])) {
												$cart_opts[$opt_key] += $cart['quantity'];
											}
											else {
												$cart_opts[$opt_key] = $cart['quantity'];
											}
										}
										
										if(isset($cart_opts[$opt_key]) && $cart_opts[$opt_key] > $option_value_query->row['quantity']) {
											$stock = false;
										}
									}
									// in order to check whole cart option qty (for e.g. product with same 1st option and diff 2nd option)

									$option_data[] = array(
										'product_option_id'       => $product_option_id,
										'product_option_value_id' => $product_option_value_id,
										'option_id'               => $option_query->row['option_id'],
										'option_value_id'         => $option_value_query->row['option_value_id'],
										'name'                    => $option_query->row['name'],
										'value'                   => $option_value_query->row['name'],
										'type'                    => $option_query->row['type'],
										'quantity'                => $option_value_query->row['quantity'],
										'subtract'                => $option_value_query->row['subtract'],
										'price'                   => $option_value_query->row['price'],
										'price_prefix'            => $option_value_query->row['price_prefix'],
										'points'                  => $option_value_query->row['points'],
										'points_prefix'           => $option_value_query->row['points_prefix'],
										'weight'                  => $option_value_query->row['weight'],
										'weight_prefix'           => $option_value_query->row['weight_prefix']
									);
			
								}
							}
						} elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
							$option_data[] = array(
								'product_option_id'       => $product_option_id,
								'product_option_value_id' => '',
								'option_id'               => $option_query->row['option_id'],
								'option_value_id'         => '',
								'name'                    => $option_query->row['name'],
								'value'                   => $value,
								'type'                    => $option_query->row['type'],
								'quantity'                => '',
								'subtract'                => '',
								'price'                   => '',
								'price_prefix'            => '',
								'points'                  => '',
								'points_prefix'           => '',
								'weight'                  => '',
								'weight_prefix'           => ''
							);
						}
					}
				}

				$price = $product_query->row['price'];

				// << Related Options / Связанные опции 
					
					if ($ro_for_product && isset($ro_settings['spec_price']) && $ro_settings['spec_price'] && !empty($ro_price_data) ) {
						$price = $ro_price_data['price'];
					}
				
				// >> Related Options / Связанные опции

				// Product Discounts
				if ($this->config->get('discounts_status')) {
					$override = $this->config->get('discounts_override_discount_price');
					if ($override == 'override') {
						$discount_status = true;
					}
				}

				$discount_quantity = 0;

				foreach ($cart_query->rows as $cart_2) {
					if ($cart_2['product_id'] == $cart['product_id']) {
						$discount_quantity += $cart_2['quantity'];
					}
				}

				$product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start <= CURDATE()) AND (date_end = '0000-00-00' OR date_end >= CURDATE())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

				// << Related Options / Связанные опции 
					// Related Options discounts
					if ($ro_for_product
					//if ($ro_for_products && $ro_for_products[$key]
					&& isset($ro_settings['spec_price']) && $ro_settings['spec_price']
					&& isset($ro_settings['spec_price_discount']) && $ro_settings['spec_price_discount'] ) {
					
						// get first option combination with discount
						foreach ($ro_for_products[$key] as $ro_comb) {
						//foreach ($ro_for_product as $ro_comb) {
							
							if ($ro_comb['discounts']) {
								$ro_discount_quantity = $ro_quantities[$ro_comb['relatedoptions_id']];
								$product_ro_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "relatedoptions_discount
																																WHERE relatedoptions_id = '" . (int)$ro_comb['relatedoptions_id'] . "'
																																AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
																																AND quantity <= '" . (int)$ro_discount_quantity . "'
																																ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");
								if ($product_ro_discount_query->num_rows) {
									$product_discount_query = $product_ro_discount_query;
									break;
								}
							}
						}
					}
				// >> Related Options / Связанные опции

				if ($product_discount_query->num_rows) {
					if (empty($discount_status)) {
						$price = $product_discount_query->row['price'];
						// << Related Options / Связанные опции 
						
							if ( !empty($ro_price_data['price_modificator']) ) {
								$price = $price + $ro_price_data['price_modificator'];
							}
						
						// >> Related Options / Связанные опции
					}
				}

				// Product Specials
				if ($this->config->get('discounts_status')) {
					$override = $this->config->get('discounts_override_special_price');
					if ($override == 'override') {
						$special_status = true;
					}
				}

				$product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start <= CURDATE()) AND (date_end = '0000-00-00' OR date_end >= CURDATE())) ORDER BY priority ASC, price ASC LIMIT 1");

				// << Related Options / Связанные опции 
					// related options specials
					
					if ($ro_for_product
					//if ($ro_for_products && $ro_for_products[$key]
					&& isset($ro_settings['spec_price']) && $ro_settings['spec_price']
					&& isset($ro_settings['spec_price_special']) && $ro_settings['spec_price_special'] ) {
					
						// get first option combination with special
						foreach ($ro_for_product as $ro_comb) {
						//foreach ($ro_for_products[$key] as $ro_comb) {
						
							if ($ro_comb['specials']) {
								$product_ro_special_query = $this->db->query("SELECT price FROM ".DB_PREFIX."relatedoptions_special 
																															WHERE relatedoptions_id = '" . (int)$ro_comb['relatedoptions_id'] . "'
																																AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
																															ORDER BY priority ASC, price ASC LIMIT 1");
								if ($product_ro_special_query->num_rows) {
									$product_special_query = $product_ro_special_query;
									break;
								}
							}
						}
					}
				// >> Related Options / Связанные опции

				if ($product_special_query->num_rows) {
					if (empty($special_status)) {
						$price = $product_special_query->row['price'];
						// << Related Options / Связанные опции 
			
							if ( !empty($ro_price_data['price_modificator']) ) {
								$price = $price + $ro_price_data['price_modificator'];
							}
						
						// >> Related Options / Связанные опции
					}
				}

				// Reward Points
				$product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($product_reward_query->num_rows) {
					$reward = $product_reward_query->row['points'];
				} else {
					$reward = 0;
				}

				/* completecombo */
				$version = str_replace(".","",VERSION);
				if($version > 2100) {
					$key = $cart['cart_id'];$netquantity2 = $salecombinationquantity = $cart['quantity'];
				} else {
					$netquantity2 = $salecombinationquantity = $quantity;
				}
				$salecombination2price = 0;
				if(isset($this->session->data['cartbindercombooffers'][$key])) {
					$ogquantity = $netquantity2;$netquantity2 = $netquantity2 - intval($this->session->data['cartbindercombooffers'][$key]['quantity']);
					$salecombinationquantity = intval($this->session->data['cartbindercombooffers'][$key]['quantity']);
					$netnewprice = $price + $option_price;
					if($this->session->data['cartbindercombooffers'][$key]['type']){
						$salecombination2price = ($netnewprice - $this->session->data['cartbindercombooffers'][$key]['discount'])/$netnewprice;
					} else {
						$salecombination2price = (100 - $this->session->data['cartbindercombooffers'][$key]['discount'])/100;
					}
					$salecombinationquantity =  $netquantity2 + ($salecombinationquantity * $salecombination2price);
					$discountdone = ($netnewprice * $ogquantity) - ($netnewprice * $salecombinationquantity);
					$this->session->data['cartbindercombooffers'][$key]['discountdone'] = $discountdone;
					$product_query->row['name'] = $product_query->row['name'].$this->getOfferTag($product_query->row['product_id']);
				}
				/* completecombo */


				// Downloads
				$download_data = array();

				$download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

				foreach ($download_query->rows as $download) {
					$download_data[] = array(
						'download_id' => $download['download_id'],
						'name'        => $download['name'],
						'filename'    => $download['filename'],
						'mask'        => $download['mask']
					);
				}

				// Stock
				if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
					$stock = false;
				}

				$recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

				if ($recurring_query->num_rows) {
					$recurring = array(
						'recurring_id'    => $cart['recurring_id'],
						'name'            => $recurring_query->row['name'],
						'frequency'       => $recurring_query->row['frequency'],
						'price'           => $recurring_query->row['price'],
						'cycle'           => $recurring_query->row['cycle'],
						'duration'        => $recurring_query->row['duration'],
						'trial'           => $recurring_query->row['trial_status'],
						'trial_frequency' => $recurring_query->row['trial_frequency'],
						'trial_price'     => $recurring_query->row['trial_price'],
						'trial_cycle'     => $recurring_query->row['trial_cycle'],
						'trial_duration'  => $recurring_query->row['trial_duration']
					);
				} else {
					$recurring = false;
				}

				$product_data[] = array(
					'cart_id'         => $cart['cart_id'],
					'product_id'      => $product_query->row['product_id'],
					'name'            => $product_query->row['name'],
					'model'           => $product_query->row['model'],
					'sku'			  => $sku,
					'shipping'        => $product_query->row['shipping'],
					'image'           => $option_image ?  $option_image : $product_query->row['image'],
					'option'          => $option_data,
					'download'        => $download_data,
					'quantity'        => $cart['quantity'],
					'minimum'         => $product_query->row['minimum'],
					'subtract'        => $product_query->row['subtract'],
					'stock'           => $stock,
					'price'           => ($price + $option_price),
					'total'           => ($price + $option_price) * $cart['quantity'],
					'reward'          => $reward * $cart['quantity'],
					'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
					'tax_class_id'    => $product_query->row['tax_class_id'],
					'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
					'weight_class_id' => $product_query->row['weight_class_id'],
					'length'          => $product_query->row['length'],
					'width'           => $product_query->row['width'],
					'height'          => $product_query->row['height'],
					'length_class_id' => $product_query->row['length_class_id'],
					'recurring'       => $recurring,
					'manufacturer_id'      => $product_query->row['manufacturer_id'],
					/* completecombo */
					'salecombinationquantity' => $salecombinationquantity,
					/* completecombo */
				);
			} else {
				$this->remove($cart['cart_id']);
			}
		}

		return $product_data;
	}


	public function add($product_id, $quantity = 1, $option = array(), $recurring_id = 0) {
		/* completecombo */
		if(!$this->countProducts()) {
          	$this->reinitializeAutoAdd();
        }
		/* completecombo */

		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");

		if (!$query->row['total']) {
			$this->db->query("INSERT " . DB_PREFIX . "cart SET api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "', customer_id = '" . (int)$this->customer->getId() . "', session_id = '" . $this->db->escape($this->session->getId()) . "', product_id = '" . (int)$product_id . "', recurring_id = '" . (int)$recurring_id . "', `option` = '" . $this->db->escape(json_encode($option)) . "', quantity = '" . (int)$quantity . "', date_added = NOW()");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = (quantity + " . (int)$quantity . ") WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '" . (int)$product_id . "' AND recurring_id = '" . (int)$recurring_id . "' AND `option` = '" . $this->db->escape(json_encode($option)) . "'");
		}
		/* completecombo */
      	if($this->config->get("offerpage_installed")) {
      		$this->sortcombination();
      		$this->salesonecombo1a();
      		$this->salesonecombo1b();
      		$this->salesonecombo1c();
      		$this->salesonecombo1();
      		$this->salesonecombo2a();
      		$this->salesonecombo2();
		}
		/* completecombo */
	}

	public function update($cart_id, $quantity) {

		$query = $this->db->query('SELECT count(*) as total FROM `' . DB_PREFIX . 'product` p LEFT JOIN `' . DB_PREFIX . 'cart` c ON (p.product_id = c.product_id) WHERE c.cart_id="' . (int)$cart_id . '" AND p.minimum <= "' . (int)$quantity . '"');

		if($query->row['total']){
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET quantity = '" . (int)$quantity . "' WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
			/* completecombo */
			if($this->config->get("offerpage_installed")) {
		   	 	$this->sortcombination();
	      		$this->salesonecombo1a();
	      		$this->salesonecombo1b();
	      		$this->salesonecombo1c();
	      		$this->salesonecombo1();
	      		$this->salesonecombo2a();
	      		$this->salesonecombo2();
		    }
		    /* completecombo*/
		}
		
	}

	public function remove($cart_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart_id . "' AND api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");

		if($this->config->get("offerpage_installed")) {
	  		$this->sortcombination();
	  		$this->salesonecombo1a();
	  		$this->salesonecombo1b();
	  		$this->salesonecombo1c();
	  		$this->salesonecombo1();
	  		$this->salesonecombo2a();
	  		$this->salesonecombo2();
      	}
	}

	public function getProductOptionImageByProductID($product_id,$product_option_value_id) {
		$query = $this->db->query("SELECT * FROM (SELECT DISTINCT(product_option_value_id), product_id, image FROM " . DB_PREFIX . "product_option_image WHERE product_id = '" . (int)$product_id . "' AND sort_order <= 1 ORDER BY sort_order ASC) as oimg  WHERE product_option_value_id ='".(int)$product_option_value_id."' GROUP BY (oimg.product_option_value_id) ");

		$image = '';

		if($query->num_rows){
			$image = $query->row['image'];
		}
        
		return $image;
	}
	/* completecombo */
	// public function remove($cart_id, $autoadded=1) {
 //      	$cartquery = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE cart_id = '".(int)$cart_id."'");
	// 	if($cartquery->num_rows) {
	// 		$this->removeProduct($cartquery->row['product_id'],$autoadded);
	// 	}
	// }
	/* completecombo */

	public function clear() {
		$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
	}

	public function getRecurringProducts() {
		$product_data = array();

		foreach ($this->getProducts() as $value) {
			if ($value['recurring']) {
				$product_data[] = $value;
			}
		}

		return $product_data;
	}

	public function getWeight() {
		$weight = 0;

		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('config_weight_class_id'));
			}
		}

		return $weight;
	}

	public function getSubTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			$total += $product['total'];
		}

		return $total;
	}

	public function getTaxes() {
		$tax_data = array();

		foreach ($this->getProducts() as $product) {
			if ($product['tax_class_id']) {
				/* completecombo */
				if(isset($product['salecombinationquantity'])) {
			        $product['quantity'] = $product['salecombinationquantity'];
		      	}
				/* completecombo */
		      	
				$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($tax_data[$tax_rate['tax_rate_id']])) {
						$tax_data[$tax_rate['tax_rate_id']] = ($tax_rate['amount'] * $product['quantity']);
					} else {
						$tax_data[$tax_rate['tax_rate_id']] += ($tax_rate['amount'] * $product['quantity']);
					}
				}
			}
		}

		return $tax_data;
	}

	public function getTotal() {
		$total = 0;

		foreach ($this->getProducts() as $product) {
			if(isset($product['salecombinationquantity'])) {
		        $product['quantity'] = $product['salecombinationquantity'];
	      	}
			$total += $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
		}

		return $total;
	}

	public function countProducts() {
		$product_total = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}

	// count product id
	public function countProductsById() {
		$product_count = 0;

		$products = $this->getProducts();

		foreach ($products as $product) {
			$product_count += $product['quantity'];
		}

		return $product_count;
	}

	public function hasProducts() {
		return count($this->getProducts());
	}

	public function hasRecurringProducts() {
		return count($this->getRecurringProducts());
	}

	public function hasStock() {
		foreach ($this->getProducts() as $product) {
			if (!$product['stock']) {
				return false;
			}
		}

		return true;
	}

	public function hasShipping() {
		foreach ($this->getProducts() as $product) {
			if ($product['shipping']) {
				return true;
			}
		}

		return false;
	}

	public function hasDownload() {
		foreach ($this->getProducts() as $product) {
			if ($product['download']) {
				return true;
			}
		}

		return false;
	}
	/* completecombo */
	public function getOfferTag($product_id) {
		$offertag_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "salescombopge_offertag WHERE product_id = '" . (int)$product_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if($offertag_query->num_rows) {
			if($offertag_query->row['offertag']) {
				return "&nbsp;<span class='offertag'>".$offertag_query->row['offertag']."</span>";
			}
		}
		return "";
	}

	public function removeProduct($product_id,$autoadded = 1) {
		if($autoadded) {
			$this->session->data['removedproduct'][] = $product_id;
		} else if (!empty($this->session->data['autoaddedproduct'])) {
			$pkey = array_search($product_id,$this->session->data['autoaddedproduct']);
			if($pkey!==false){
				unset($this->session->data['autoaddedproduct'][$pkey]);
			}
		}        
	}

	public function canbeAutoAdded($product_id,$checkinsession=1) {
		$autoadd = 1;
		if(!empty($this->session->data['removedproduct']) && in_array($product_id, $this->session->data['removedproduct'])) {
			$autoadd = 0;
		}
		if($autoadd && !empty($this->session->data['autoaddedproduct']) && in_array($product_id, $this->session->data['autoaddedproduct'])) {
			$autoadd = 0;
		}
		if($autoadd) {
			$autoadd = $this->checkProductStatus($product_id);
		}
		if($autoadd && $checkinsession && $this->checkProductInSession($product_id)) {
			$autoadd = 0;
		}
		return $autoadd;
	}

	public function checkProductStatus($product_id) {
		$query = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = '".(int)$product_id."' AND status = 1");
		if($query->num_rows) {
			return 1;
		} else {
			return 0;
		}
	}

	public function reinitializeAutoAdd() {
		$this->session->data['removedproduct'] = array();
		$this->session->data['autoaddedproduct'] = array();
	}

	public function checkProductInSession($product_id) {
		$cartsession_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id = '".(int)$product_id."'");
		if($cartsession_query->num_rows) {
			return 1;
		} else {
			return 0;
		}
	}
	public function sortcombination(){
      //$this->log->write("insortcombination");
		unset($this->session->data['cartbindercombooffers']);
		$this->session->data['cartbindercombooffers_pages'] = array();
		$this->session->data['cartbindercombooffers_offerapplied'] = array();
		$this->session->data['slscmdprdts'] = array();
		$this->session->data['slscmdctgs'] = array();
		$this->session->data['slscmdctgscids'] = array();
		$version = str_replace(".","",VERSION);
		if($version > 2100) {
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
			foreach ($cart_query->rows as $cart) {
				$product_id =  $cart['product_id'];
				$this->getcategories($product_id,$cart['quantity']);
				if (!isset($this->session->data['slscmdprdts'][$product_id])) {$this->session->data['slscmdprdts'][$product_id] = (int)$cart['quantity'];} else {$this->session->data['slscmdprdts'][$product_id] += (int)$cart['quantity'];}
			} 
		} else {
			$productsincart = $this->session->data['cart'];
			foreach ($productsincart as $key => $quantity) {
				$product = unserialize(base64_decode($key));
				$product_id =  $product['product_id'];
				if (!isset($this->session->data['slscmdprdts'][$product_id])) {
					$this->session->data['slscmdprdts'][$product_id] = (int)$quantity;
				} else {
					$this->session->data['slscmdprdts'][$product_id] += (int)$quantity;
				}
			} 
		}
	}

	public function getcategories($product_id,$quantity) {
		$queries = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");

		foreach ($queries->rows as $result) {
			$this->session->data['slscmdctgs'][$product_id][] = $result['category_id'];
			if(isset($this->session->data['slscmdctgscids'][$result['category_id']])) {
				$this->session->data['slscmdctgscids'][$result['category_id']] += $quantity;
			} else {
				$this->session->data['slscmdctgscids'][$result['category_id']] = $quantity;
			}
		}
	}
	public function checkcondition($product_id,$secondaryarray,$secondarycarray) {
		if(in_array($product_id,$secondaryarray))  {
			return 1;
		}
		if(isset($this->session->data['slscmdctgs'][$product_id])) {
			foreach($this->session->data['slscmdctgs'][$product_id] as $key => $value) {
				if(in_array($value,$secondarycarray)) {
					return 1;
				}
			}
		}
		return 0;
	}
	public function checkcondition2($product_id,$secondarycarray) {
		if(isset($this->session->data['slscmdctgs'][$product_id])) {
			foreach($this->session->data['slscmdctgs'][$product_id] as $key => $value) {
				if(in_array($value,$secondarycarray)) {
					return 1;
				}
			}
		}
		return 0;
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
	public function xnfrmla($tn,$pq,$sq) {
		$sm = $pq + $sq;
		$answer = floor( $tn / $sm );
		$bq = ($answer * $sm) + $pq;
		$uq = ($answer * $sm) + $sm;
		$min = min($tn,$uq);
		$dyn = $min - $bq;
		if($dyn < 0){$dyn = 0;}
		$fq = $answer * $sq;
		return $fq + $dyn;
	}
	public function salesonecombo1a(){
		if(isset($this->session->data['slscmdprdts']) && !empty($this->session->data['slscmdprdts'])){
          	$query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1a_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");foreach($query->rows as $step => $destination) {$primaryarray = explode(",",$destination['primarypids']);
            if($this->checkCg($destination['cids'])) {
            	continue;
            }
            foreach($primaryarray as $key => $value){
            	if(!array_key_exists($value,$this->session->data['slscmdprdts'])) {
               continue 2;
            }}
            $totalprimaryquantity = array();
            foreach($primaryarray as $pids) {
            	$totalprimaryquantity[] = $this->session->data['slscmdprdts'][$pids];
            }
            $commonseocndaryquantity = min($totalprimaryquantity);
            if($destination['multidiscount']) {
              $multidiscountarray = array();
              $extrasarray =  explode(";", $destination['multidiscount']);
              if(isset($extrasarray[0]) && !empty($extrasarray[0])) {
                foreach($extrasarray as $key => $value ) {
                  $valuearray = explode(":",$value);
                  if(isset($valuearray[0]) && isset($valuearray[1])) {
                    $multidiscountarray[$valuearray[0]] = $valuearray[1];
                  }
                }
              }
              krsort($multidiscountarray);
              $offerfound = 0;
              foreach($multidiscountarray as $quantity => $discount) {
                if($commonseocndaryquantity >= $quantity) {
                  $netquantity = $commonseocndaryquantity;
                  if(strpos($discount, "p") !== false) {
                    $destination['type'] = 0;
                    $discount = str_replace("p", "", $discount);
                  }
                  $destination['discount'] = $discount;
                  $offerfound = 1;
                  break;
                }
              }
              if($offerfound) {
                goto direct1aoffer;
              }
            }
            $netquantity = $this->xnfrmla($commonseocndaryquantity,$destination['primaryquant'],$destination['secondaryquant']);
            direct1aoffer:
            $goforautoadd = 0;
            if($commonseocndaryquantity == $destination['primaryquant']) {
              $goforautoadd = 1;
            }

           	$i = 0;
           	if($netquantity >= 0) {
           		$i = 1;
           	}
            $version = str_replace(".","",VERSION);
            $spprtsimproopt = array();
            $temp  = $netquantity;
            $quantityapply = $temp;

            if($version > 2100) {
             $cart_query = $this->db->query("SELECT c.cart_id,c.quantity,c.product_id,c.option FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY p.price ASC");
              	foreach ($cart_query->rows as $cart) {

                	if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']]) && in_array($cart['product_id'], $primaryarray)) {

                  		if(!$netquantity && $destination['autoadd'] && $goforautoadd) {
                      		if($this->canbeAutoAdded($cart['product_id'],0)) {
                        		$this->session->data['autoaddedproduct'][] = $cart['product_id'];
                        		$this->add($cart['product_id'],$destination['secondaryquant'],json_decode($cart['option']));
                        		break;
                      		}
                  		}

                  		if($quantityapply > 0) {
                    		$temp = $quantityapply;
                     		$quantityapply = $quantityapply - $cart['quantity'];

                     		$i = 0; 
                     		$this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                      		$this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;
                      		if($quantityapply >= 0) {
                        		$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];
                      		} else {
                      			$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;
                  			} 

                  			if($quantityapply <= 0) {
                  				break;
                  			}
                  		}
                	}
              	}
            } else {
              	foreach ($this->session->data['cart'] as $key => $quantity) {
              		$product = unserialize(base64_decode($key));
              		$product_id = $product['product_id'];
	                if(!isset($this->session->data['cartbindercombooffers'][$key]) && in_array($product_id,$primaryarray) && ($quantityapply > 0)) {
	                    $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;
	                    $i = 0;
	                     $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
	                    //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
	                    $this->session->data['cartbindercombooffers'][$key] =  $destination;
	                    if($quantityapply >= 0) {
	                      $this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;
	                      } else {
	                    $this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;
	                    } if($quantityapply <= 0) {break;}
	                    //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
	                 }
	             }
         	}
         	if($i){
                 $this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];
         	}
         	}
     	}
 	}
 	public function salesonecombo1b(){if(isset($this->session->data['slscmdprdts']) && !empty($this->session->data['slscmdprdts'])){
        //$this->log->write("wholesaleconditionexists");$this->log->write(print_r($this->session->data['slscmdprdts'],true));
          $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1b_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");foreach($query->rows as $step => $destination) {$primaryarray = explode(",",$destination['primarypids']);$secondaryarray = explode(",",$destination['secondarycids']);
            //$this->log->write("primaryarray");$this->log->write(print_r($primaryarray,true));$this->log->write("secondaryarray");$this->log->write(print_r($secondaryarray,true));
            $totalprimaryquantity  = array();
            if($this->checkCg($destination['cids'])) {continue;}
            foreach($primaryarray as $key => $value){
            if(!array_key_exists($value,$this->session->data['slscmdprdts']) && !$destination['anyorall']) {
               //$this->log->write("break from primary in 1b");
               continue 2;
              } else if(array_key_exists($value,$this->session->data['slscmdprdts'])) {$totalprimaryquantity[] = $this->session->data['slscmdprdts'][$value];}
            }
            if($destination['anyorall']) {$totalprimaryquantity = array_sum($totalprimaryquantity);} else {$totalprimaryquantity = min($totalprimaryquantity);}$commonseocndaryquantity = $totalprimaryquantity;
            //$this->log->write("common quantity".$commonseocndaryquantity);

            $netquantity = floor(($commonseocndaryquantity * $destination['secondaryquant'])/$destination['primaryquant']);
            //$netquantity = $commonseocndaryquantity;
            //$this->log->write("net quantity".$netquantity);
            $i = 0;if($netquantity > 0) {$i = 1;}
            $version = str_replace(".","",VERSION);$spprtsimproopt = array();$temp  = $netquantity;$quantityapply = $temp;
            if($version > 2100) {$cart_query = $this->db->query("SELECT c.cart_id,c.quantity,c.product_id FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY p.price ASC");
              foreach ($cart_query->rows as $cart) {
                if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']])) {
                    $product_id =   $cart['product_id'];
                    $checkcondition = $this->checkcondition2($product_id,$secondaryarray);
                    if($checkcondition && ($quantityapply > 0)) {
                    $i = 0;$this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                    $temp = $quantityapply;
                    $quantityapply = $quantityapply - $cart['quantity'];
                    //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                    $this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;
                    if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];} else {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;}
                    //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'], true));
                    if($quantityapply <= 0) {break;}
                  }
                }
              }
            } else {
              foreach ($this->session->data['cart'] as $key => $quantity) {
              if(!isset($this->session->data['cartbindercombooffers'][$key])) {
               $product = unserialize(base64_decode($key));$product_id = $product['product_id'];$checkcondition = $this->checkcondition2($product_id,$secondaryarray);if($checkcondition && ($quantityapply > 0) ) {
                $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;$i = 0;
                $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
               // $this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                $this->session->data['cartbindercombooffers'][$key] =  $destination;if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;} else {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;}
                //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                if($quantityapply <= 0) {break;}
              }  
    }}}if($i){$this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];}}}}
    public function salesonecombo1c(){if(isset($this->session->data['slscmdprdts']) && !empty($this->session->data['slscmdprdts'])){
        //$this->log->write("wholesaleconditionexists");$this->log->write(print_r($this->session->data['slscmdprdts'],true));
          $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1c_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");foreach($query->rows as $step => $destination) {
          $autoaddproduct_id = array();
          $autoaddoption = array();
          $secondarycartids = array();$primaryarray = $destination['primarypids'];
          if($this->checkCg($destination['cids'])) {continue;}
          if(!array_key_exists($primaryarray,$this->session->data['slscmdprdts'])) {
            continue;
          }

           $cart_query = $this->db->query("SELECT `option`,`cart_id`,`quantity`,`product_id` FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "' AND product_id  = '".$primaryarray."'");
           $commonseocndaryquantity = $netquantity = 0;
           foreach($cart_query->rows as $key => $value) {
              $bit = 0;
              $customerselectedoptions = json_decode($value['option'],true);
              $optionset = json_decode($destination['optionidarray'],true);
               if(is_array($customerselectedoptions) && empty($customerselectedoptions)) {
                continue;
              }
              if(is_array($optionset) && empty($optionset)) {
                continue;
              }
              //$this->log->write(print_r($optionset,true));$this->log->write(print_r($customerselectedoptions,true));
              foreach($optionset as $optionkey => $optionvalue) {
                if($destination['anyorall']) {
                  //check if key exist
                  if(array_key_exists($optionkey,$customerselectedoptions)) {

                    //check if value exist
                    if(is_array($optionvalue)) {
                      foreach($optionvalue as $optionkey1 => $optionvalue1) {
                        if(in_array($optionvalue1,$customerselectedoptions[$optionkey])) {
                          $bit = 1;
                        }
                      }
                    } else {
                      if($customerselectedoptions[$optionkey] == $optionvalue) {
                        $bit = 1;
                      }
                    }
                  }
                } else {
                  $bit = 1;
                  //check if key exist
                  if(!array_key_exists($optionkey,$customerselectedoptions)) {
                    $bit = 0;
                     break;
                  }


                  //check if value exist
                  if(is_array($optionvalue)) {
                    foreach($optionvalue as $optionkey1 => $optionvalue1) {
                      if(!in_array($optionvalue1,$customerselectedoptions[$optionkey])) {
                        $bit = 0;
                        break;
                      }
                    }
                  } else {
                    if($customerselectedoptions[$optionkey] != $optionvalue) {
                      $bit = 0;
                      break;
                    }
                  }
                } 
              }
              //$this->log->write("bit value".$bit);
              if($bit) {
                 $commonseocndaryquantity += $value['quantity'];
                 $secondarycartids[] = $value['cart_id'];
                 $autoaddproduct_id[] = $value['product_id'];
                 $autoaddoption[] = $customerselectedoptions;
              }
            }
            if($commonseocndaryquantity == $destination['primaryquant']) {
               if($destination['autoadd']) {
                    if($this->canbeAutoAdded($autoaddproduct_id[0],0)) {
                      $this->session->data['autoaddedproduct'][] = $autoaddproduct_id[0];
                      $this->add($autoaddproduct_id[0],$destination['secondaryquant'],$autoaddoption[0]);
                      break;
                    }
                 }
            } else if($commonseocndaryquantity > $destination['primaryquant']) {
              //$netquantity = floor($commonseocndaryquantity/($destination['primaryquant']+$destination['secondaryquant']));
              $netquantity = $this->xnfrmla($commonseocndaryquantity,$destination['primaryquant'],$destination['secondaryquant']);
            } else {
              break;
            }
            if(empty($secondarycartids) || $netquantity == 0) {
              break;
            }
            //$this->log->write("netquantity value".$netquantity);
            $i = 0;if($netquantity > 0) {$i = 1;}
            $version = str_replace(".","",VERSION);$spprtsimproopt = array();$temp  = $netquantity;$quantityapply = $temp;
            if($version > 2100) {
              $cart_query = $this->db->query("SELECT  c.cart_id,c.quantity,c.product_id FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' AND c.product_id  = '".$primaryarray."' ORDER BY p.price ASC");
              foreach ($cart_query->rows as $cart) {
               if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']])) {
                $product_id = $cart['product_id'];
                if(in_array($cart['cart_id'],$secondarycartids) && ($quantityapply > 0)) {
                 $temp = $quantityapply;$quantityapply = $quantityapply - $cart['quantity'];
                 $i = 0; $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                 // $this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                  $this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];} else {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;} if($quantityapply <= 0) {break;}
                  //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                  }
                }
              }
            } else {
              foreach ($this->session->data['cart'] as $key => $quantity) {
                if(!isset($this->session->data['cartbindercombooffers'][$key])) {
                  $product = unserialize(base64_decode($key));$product_id = $product['product_id'];
                  if(in_array($product_id,$secondaryarray) && ($quantityapply > 0)) {
                    $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;
                    $i = 0;
                     $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                    //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                    $this->session->data['cartbindercombooffers'][$key] =  $destination;
                    if($quantityapply >= 0) {
                      $this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;
                      } else {
                    $this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;
                    } if($quantityapply <= 0) {break;}
                    //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                 }}}}if($i){
                   $this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];
                 }}}}
                 public function salesonecombo1(){if(isset($this->session->data['slscmdprdts']) && !empty($this->session->data['slscmdprdts'])){
        //$this->log->write("wholesaleconditionexists");$this->log->write(print_r($this->session->data['slscmdprdts'],true));
          $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo1_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");foreach($query->rows as $step => $destination) {$primaryarray = explode(",",$destination['primarypids']);$secondaryarray = explode(",",$destination['secondarypids']);
           //$this->log->write("primaryarray");$this->log->write(print_r($primaryarray,true));$this->log->write("secondaryarray");$this->log->write(print_r($secondaryarray,true));
            if($this->checkCg($destination['cids'])) {continue;}
            foreach($primaryarray as $key => $value){if(!array_key_exists($value,$this->session->data['slscmdprdts'])) {
               //$this->log->write("break from primary");
               continue 2;
            }}
            //$this->log->write("offer should come");
            $totalprimaryquantity = array();foreach($primaryarray as $pids) {$totalprimaryquantity[] = $this->session->data['slscmdprdts'][$pids];}$commonseocndaryquantity = min($totalprimaryquantity);
            //$this->log->write("common quantity".$commonseocndaryquantity);
            $netquantity = floor(($commonseocndaryquantity * $destination['secondaryquant'])/$destination['primaryquant']);
            //$netquantity = $commonseocndaryquantity;
            //$this->log->write("net quantity".$netquantity);
            $i = 0;if($netquantity > 0) {$i = 1;}
            $version = str_replace(".","",VERSION);$spprtsimproopt = array();$temp  = $netquantity;$quantityapply = $temp;
            if($version > 2100) {
              $cart_query = $this->db->query("SELECT c.cart_id,c.product_id,c.quantity FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY p.price ASC");
              foreach ($cart_query->rows as $cart) {
               if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']])) {
                $product_id = $cart['product_id'];
                if(in_array($product_id,$secondaryarray) && ($quantityapply > 0)) {
                 $temp = $quantityapply;$quantityapply = $quantityapply - $cart['quantity'];
                 $i = 0; $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                 // $this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                  $this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];} else {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;} if($quantityapply <= 0) {break;}
                  //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                  }
                }
              }
            } else {
              foreach ($this->session->data['cart'] as $key => $quantity) {
                if(!isset($this->session->data['cartbindercombooffers'][$key])) {
                  $product = unserialize(base64_decode($key));$product_id = $product['product_id'];
                  if(in_array($product_id,$secondaryarray) && ($quantityapply > 0)) {
                    $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;
                    $i = 0;
                     $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                    //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                    $this->session->data['cartbindercombooffers'][$key] =  $destination;
                    if($quantityapply >= 0) {
                      $this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;
                      } else {
                    $this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;
                    } if($quantityapply <= 0) {break;}
                    //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                 }}}}if($i){
                 if($destination['autoadd']) {

                   if(count($secondaryarray) == 1) {
                     if($this->canbeAutoAdded($secondaryarray[0])) {
                      $this->session->data['autoaddedproduct'][] = $secondaryarray[0];
                      $this->add($secondaryarray[0],$quantityapply);
                      break;
                     } 
                   }
                 } else {
                   $this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];
                 }
                 }}}}


         public function salesonecombo2a(){
      if(isset($this->session->data['slscmdctgs']) && !empty($this->session->data['slscmdctgs']) && isset($this->session->data['slscmdctgscids']) && !empty($this->session->data['slscmdctgscids'])){
          //$this->log->write("categoryconditionexist"); $this->log->write(print_r($this->session->data['slscmdctgs'],true));$this->log->write(print_r($this->session->data['slscmdprdts'],true));
          $version = str_replace(".","",VERSION);
          $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo2a_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");
          foreach($query->rows as $step => $destination) {
          $primarycarray = array();$primarycrtarray = array();
          $primaryarray = explode(",",$destination['primarycids']);
          //$this->log->write("primaryarray");$this->log->write(print_r($primaryarray,true));
          //$this->log->write("primaryarray");$this->log->write(print_r($primaryarray,true));
          //$this->log->write("category with quantity");$this->log->write(print_r($this->session->data['slscmdctgscids'],true));
          if($this->checkCg($destination['cids'])) {continue;}
            if(!$destination['anyorall']) {
            foreach($primaryarray as $key => $value){if(!array_key_exists($value,$this->session->data['slscmdctgscids'])) {
               //$this->log->write("break from primary");
               continue 2;
            }}}
            if($version > 2100) {
              $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
                foreach ($cart_query->rows as $cart) {
                  if(isset($this->session->data['slscmdctgs'][$cart['product_id']])) {
                    $excludeparray = explode(",",$destination['excludeproducts']);
                    if(empty($excludeparray) || !in_array($cart['product_id'],$excludeparray)){
                      foreach($this->session->data['slscmdctgs'][$cart['product_id']] as $key => $value) {
                        if(in_array($value,$primaryarray)  && isset($this->session->data['slscmdctgscids'][$value])) {
                          $primarycarray[$value] = $this->session->data['slscmdctgscids'][$value];
                          $primarycrtarray[$cart['cart_id']] = $cart['quantity'];
                        }
                      }
                    }  
                  }
                }
              } else {
                foreach ($this->session->data['cart'] as $key => $quantity) {
                  $product = unserialize(base64_decode($key));
                  $product_id = $product['product_id'];
                  if(isset($this->session->data['slscmdctgs'][$product_id])) {
                     $excludeparray = explode(",",$destination['excludeproducts']);
                    if(empty($excludeparray) || !in_array($product_id,$excludeparray)){
                      foreach($this->session->data['slscmdctgs'][$product_id] as $key1 => $value) {
                        if(in_array($value,$primaryarray)  && isset($this->session->data['slscmdctgscids'][$value])) {
                         $primarycarray[$value] = $this->session->data['slscmdctgscids'][$value];
                          $primarycrtarray[] = $key;
                        }
                      }
                    }  
                  }
                }
              }
            if(!count($primarycrtarray)) { continue;}
            if($destination['anyorall']) {
              $totalprimaryquantity = array_sum($primarycrtarray);
            } else {
              $totalprimaryquantity = min($primarycrtarray);
            }
            if($destination['multidiscount']) {
              $multidiscountarray = array();
              $extrasarray =  explode(";", $destination['multidiscount']);
              if(isset($extrasarray[0]) && !empty($extrasarray[0])) {
                foreach($extrasarray as $key => $value ) {
                  $valuearray = explode(":",$value);
                  if(isset($valuearray[0]) && isset($valuearray[1])) {
                    $multidiscountarray[$valuearray[0]] = $valuearray[1];
                  }
                }
              }
              krsort($multidiscountarray);
              $offerfound = 0;
              foreach($multidiscountarray as $quantity => $discount) {
                if($totalprimaryquantity >= $quantity) {
                  $netquantity2 = $totalprimaryquantity;
                  if(strpos($discount, "p") !== false) {
                    $destination['type'] = 0;
                    $discount = str_replace("p", "", $discount);
                  }
                  $destination['discount'] = $discount;
                  $offerfound = 1;
                  break;
                }
              }
              if($offerfound) {
                goto direct2aoffer;
              }
            }
            if($destination['secondaryquant'] == 0) {
              if($totalprimaryquantity >= $destination['primaryquant']) { $netquantity2 = $totalprimaryquantity; goto direct2aoffer;}
            }
            if($totalprimaryquantity < $destination['primaryquant']) { continue; }
            $commonseocndaryquantity = $totalprimaryquantity;
            //$netquantity2 = floor($commonseocndaryquantity/($destination['primaryquant']+$destination['secondaryquant']));
            $netquantity2 = $this->xnfrmla($commonseocndaryquantity,$destination['primaryquant'],$destination['secondaryquant']);
            direct2aoffer:
            $i = 0;if($netquantity2 >= 0) {$i = 1;}
            //$this->log->write("net quantity".$netquantity2);
            $spprtsimproopt = array();$temp  = $netquantity2;$quantityapply = $temp;
            if($version > 2100) {$cart_query = $this->db->query("SELECT c.cart_id,c.quantity,c.product_id FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY p.price ASC");
              foreach ($cart_query->rows as $cart) {
                if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']]) && array_key_exists($cart['cart_id'], $primarycrtarray) && ($quantityapply > 0)) {
                    $temp = $quantityapply;$i =0;$this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                    $quantityapply = $quantityapply - $cart['quantity'];
                    //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp."cart : ".$cart['quantity']);
                    $this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;
                    if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];} else {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;}
                    if($quantityapply < 0) {break;}
                }
              }
            } else {
              $this->session->data['tempcart'] = array();
              $products = $this->getProducts();
              foreach ($products as $product) {
                $this->session->data['tempcart'][$product['key']] = $product['price'];
              }
              asort( $this->session->data['tempcart']);
              foreach ($this->session->data['tempcart'] as $key => $price) {
              $quantity = $this->session->data['cart'][$key];
              if(!isset($this->session->data['cartbindercombooffers'][$key]) && in_array($key, $primarycrtarray) && ($quantityapply > 0)) {
                $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;$i =0;
                $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
               // $this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                $this->session->data['cartbindercombooffers'][$key] =  $destination;if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;} else {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;}
                if($quantityapply < 0) {break;}
                }}}if($i){$this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];}}}}

                public function salesonecombo2(){if(isset($this->session->data['slscmdctgs']) && !empty($this->session->data['slscmdctgs']) && isset($this->session->data['slscmdctgscids']) && !empty($this->session->data['slscmdctgscids'])){
          //$this->log->write("categoryconditionexist");$this->log->write(print_r($this->session->data['slscmdctgs'],true));$this->log->write(print_r($this->session->data['slscmdprdts'],true));
          $version = str_replace(".","",VERSION);
          $query = $this->db->query("SELECT  * FROM " . DB_PREFIX . "cartbindercombo2_setting WHERE status = '1' AND ((datestart = '0000-00-00' OR datestart < NOW()) AND (dateend = '0000-00-00' OR dateend > NOW()))");foreach($query->rows as $step => $destination) {$primarycarray = array();$primaryarray = explode(",",$destination['primarycids']);$secondaryarray = explode(",",$destination['secondarypids']);$secondarycarray = explode(",",$destination['secondarycids']);
           //$this->log->write("primaryarray");$this->log->write(print_r($primaryarray,true));$this->log->write("secondaryarray");$this->log->write(print_r($secondaryarray,true));$this->log->write("secondarycarray");$this->log->write(print_r($secondarycarray,true));
           if($this->checkCg($destination['cids'])) {continue;}
           if(!$destination['anyorall']) {
            foreach($primaryarray as $key => $value){if(!array_key_exists($value,$this->session->data['slscmdctgscids'])) {
               //$this->log->write("break from primary");
               continue 2;
            }}}
            $cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");
            foreach ($cart_query->rows as $cart) {
              if(isset($this->session->data['slscmdctgs'][$cart['product_id']])) {
                foreach($this->session->data['slscmdctgs'][$cart['product_id']] as $key => $value) {
                 if(in_array($value,$primaryarray) && isset($this->session->data['slscmdctgscids'][$value])) {
                    $primarycarray[$value] = $this->session->data['slscmdctgscids'][$value];
                  }
                }
              }
            }
            if(!count($primarycarray)) { continue;}
            if($destination['anyorall']) {
              $totalprimaryquantity = array_sum($primarycarray);
            } else {
              $totalprimaryquantity = min($primarycarray);
            }
            if($totalprimaryquantity < $destination['primaryquant']) { continue; }
            $commonseocndaryquantity = $totalprimaryquantity;
            //$netquantity2 = floor($commonseocndaryquantity/($destination['primaryquant']+$destination['secondaryquant']));$netquantity2 = $this->xnfrmla($commonseocndaryquantity,$destination['primaryquant'],$destination['secondaryquant']);
            $netquantity2 = floor(($commonseocndaryquantity * $destination['secondaryquant'])/$destination['primaryquant']);
            if($netquantity2 <= 0){ continue;}
            $i = 0;if($netquantity2 > 0) {$i = 1;}
            $goforautoadd = 0;
            if($commonseocndaryquantity == $destination['primaryquant']) {
              $goforautoadd = 1;
            }
           //$this->log->write("net quantity".$netquantity2);
            $spprtsimproopt = array();$temp  = $netquantity2;$quantityapply = $temp;
            if($version > 2100) {$cart_query = $this->db->query("SELECT c.cart_id,c.quantity,c.product_id FROM " . DB_PREFIX . "cart c LEFT JOIN " . DB_PREFIX . "product p ON (p.product_id = c.product_id) WHERE c.customer_id = '" . (int)$this->customer->getId() . "' AND c.session_id = '" . $this->db->escape($this->session->getId()) . "' ORDER BY p.price ASC");
              foreach ($cart_query->rows as $cart) {
                if(!isset($this->session->data['cartbindercombooffers'][$cart['cart_id']])) {
                    if($destination['autoadd'] && $goforautoadd) {
                       if(count($secondaryarray) == 1) {
                        if($this->canbeAutoAdded($secondaryarray[0])) {
                          $this->session->data['autoaddedproduct'][] = $secondaryarray[0];
                          $this->add($secondaryarray[0],$netquantity2);
                          break;
                        }
                      }
                    }
                    if($quantityapply > 0) {
                      $product_id =   $cart['product_id'];
                      $checkcondition = $this->checkcondition($product_id,$secondaryarray,$secondarycarray);
                      if($checkcondition) {
                      $i = 0;$this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
                      $temp = $quantityapply;
                      $quantityapply = $quantityapply - $cart['quantity'];
                      //$this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                      $this->session->data['cartbindercombooffers'][$cart['cart_id']] = $destination;
                      if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $cart['quantity'];} else {$this->session->data['cartbindercombooffers'][$cart['cart_id']]['quantity'] = $temp;}
                     //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'], true));
                      if($quantityapply <= 0) {break;}
                    }  
                  }
                }
              }
            } else {
              foreach ($this->session->data['cart'] as $key => $quantity) {
              if(!isset($this->session->data['cartbindercombooffers'][$key])) {
               $product = unserialize(base64_decode($key));$product_id = $product['product_id'];$checkcondition = $this->checkcondition($product_id,$secondaryarray,$secondarycarray);if($checkcondition && ($quantityapply > 0) ) {
                $temp = $quantityapply;$quantityapply = $quantityapply - $quantity;$i = 0;
                $this->session->data['cartbindercombooffers_offerapplied'][] = $destination['sales_offer_id'];
               // $this->log->write("Quantity apply : ".$quantityapply."Temp : ".$temp);
                $this->session->data['cartbindercombooffers'][$key] =  $destination;if($quantityapply >= 0) {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $quantity;} else {$this->session->data['cartbindercombooffers'][$key]['quantity'] = $temp;}
                //$this->log->write("offer on product id ".$product_id);$this->log->write(print_r($this->session->data['cartbindercombooffers'],true));
                if($quantityapply <= 0) {break;}
              }  
    }}}if($i){$this->session->data['cartbindercombooffers_pages'][] = $destination['sales_offer_id'];}}}}
	/* completecombo */
}
