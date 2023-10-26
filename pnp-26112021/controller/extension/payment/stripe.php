<?php
class ControllerExtensionPaymentStripe extends Controller { 
	private $type = 'payment';
	private $name = 'stripe';
	
	public function index() {
		$data = array(
			'type'			=> $this->type,
			'name'			=> $this->name,
			'autobackup'	=> false,
			'save_type'		=> 'keepediting',
			'permission'	=> $this->hasPermission('modify'),
		);
		
		$this->loadSettings($data);
		
		// extension-specific
		if (empty($data['saved'])) {
			$data['save_type'] = 'reload';
		}
		
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "stripe_customer` (
				`customer_id` int(11) NOT NULL,
				`stripe_customer_id` varchar(18) NOT NULL,
				`transaction_mode` varchar(4) NOT NULL DEFAULT 'live',
				PRIMARY KEY (`customer_id`, `stripe_customer_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
		");
		
		$transaction_mode_column = false;
		$database_table_query = $this->db->query("DESCRIBE " . DB_PREFIX . "stripe_customer");
		foreach ($database_table_query->rows as $column) {
			if ($column['Field'] == 'transaction_mode') {
				$transaction_mode_column = true;
			}
		}
		if (!$transaction_mode_column) {
			$this->db->query("ALTER TABLE " . DB_PREFIX . "stripe_customer ADD transaction_mode varchar(4) NOT NULL DEFAULT 'live'");
		}
		
		//------------------------------------------------------------------------------
		// Check for Stripe Checkout data (Pro-specific)
		//------------------------------------------------------------------------------
		if (!empty($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
			
			if (!empty($this->request->get['order_status_id'])) {
				$order_status_id = $this->request->get['order_status_id'];
			} else {
				$order_status_id = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$order_id)->row['order_status_id'];
			}
			
			$comment = 'Stripe payment of ' . $this->request->get['checkout'] . ' completed via "Create a Charge" tab';
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = " . (int)$order_id . ", order_status_id = " . (int)$order_status_id . ", notify = 0, comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = " . (int)$order_status_id . " WHERE order_id = " . (int)$order_id);
		}
		
		if (isset($this->request->get['checkout'])) {
			$this->session->data['success'] = $this->request->get['checkout'];
			$token = (version_compare(VERSION, '3.0', '<')) ? 'token=' . $data['token'] : 'user_token=' . $data['token'];
			$this->response->redirect(str_replace('&amp;', '&', $this->url->link('extension/' . $this->type . '/' . $this->name, $token, 'SSL')));
		}
		
		if (isset($this->session->data['success'])) {
			echo '<div class="alert alert-success" style="font-size: 16px; font-weight: bold; margin-bottom: 0; text-align: center">Your payment of ' . $this->session->data['success'] . ' completed successfully!</div>';
			unset($this->session->data['success']);
		}
		
		//------------------------------------------------------------------------------
		// Data Arrays
		//------------------------------------------------------------------------------
		$data['language_array'] = array($this->config->get('config_language') => '');
		$data['language_flags'] = array();
		$this->load->model('localisation/language');
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$data['language_array'][$language['code']] = $language['name'];
			$data['language_flags'][$language['code']] = (version_compare(VERSION, '2.2', '<')) ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}
		
		$data['order_status_array'] = array(0 => $data['text_ignore']);
		$this->load->model('localisation/order_status');
		foreach ($this->model_localisation_order_status->getOrderStatuses() as $order_status) {
			$data['order_status_array'][$order_status['order_status_id']] = $order_status['name'];
		}
		
		$data['customer_group_array'] = array(0 => $data['text_guests']);
		$this->load->model((version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '/customer_group');
		foreach ($this->{'model_' . (version_compare(VERSION, '2.1', '<') ? 'sale' : 'customer') . '_customer_group'}->getCustomerGroups() as $customer_group) {
			$data['customer_group_array'][$customer_group['customer_group_id']] = $customer_group['name'];
		}
		
		$data['geo_zone_array'] = array(0 => $data['text_everywhere_else']);
		$this->load->model('localisation/geo_zone');
		foreach ($this->model_localisation_geo_zone->getGeoZones() as $geo_zone) {
			$data['geo_zone_array'][$geo_zone['geo_zone_id']] = $geo_zone['name'];
		}
		
		$data['store_array'] = array(0 => $this->config->get('config_name'));
		$store_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store ORDER BY name");
		foreach ($store_query->rows as $store) {
			$data['store_array'][$store['store_id']] = $store['name'];
		}
		
		$data['currency_array'] = array($this->config->get('config_currency') => '');
		$this->load->model('localisation/currency');
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$data['currency_array'][$currency['code']] = $currency['code'];
		}
		
		// Get subscription products
		$data['subscription_products'] = array();
		
		if (!empty($data['saved']['subscriptions']) &&
			!empty($data['saved']['transaction_mode']) &&
			!empty($data['saved'][$data['saved']['transaction_mode'].'_secret_key'])
		) {
			$plan_response = $this->curlRequest('GET', 'plans', array('count' => 100));
			
			if (!empty($plan_response['error'])) {
				$this->log->write('STRIPE ERROR: ' . $plan_response['error']['message']);
			} else {
				$plans = $plan_response['data'];
				
				while (!empty($plan_response['has_more'])) {
					$plan_response = $this->curlRequest('GET', 'plans', array('count' => 100, 'starting_after' => $plans[count($plans) - 1]['id']));
					if (empty($plan_response['error'])) {
						$plans = array_merge($plans, $plan_response['data']);
					}
				}
				
				foreach ($plans as $plan) {
					$decimal_factor = (in_array(strtoupper($plan['currency']), array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF'))) ? 1 : 100;
					$product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id AND pd.language_id = " . (int)$this->config->get('config_language_id') . ") WHERE p.location = '" . $this->db->escape($plan['id']) . "'");
					
					foreach ($product_query->rows as $product) {
						$data['subscription_products'][] = array(
							'product_id'	=> $product['product_id'],
							'name'			=> $product['name'],
							'price'			=> $this->currency->format($product['price'], $this->config->get('config_currency')),
							'location'		=> $product['location'],
							'plan'			=> $plan['nickname'],
							'interval'		=> $plan['interval_count'] . ' ' . $plan['interval'] . ($plan['interval_count'] > 1 ? 's' : ''),
							'charge'		=> $this->currency->format($plan['amount'] / $decimal_factor, strtoupper($plan['currency']), 1, strtoupper($plan['currency'])),
						);
					}
				}
			}
		}
		
		// Create webhook if necessary
		if (!empty($data['saved']['transaction_mode']) && !empty($data['saved'][$data['saved']['transaction_mode'].'_secret_key'])) {
			$urls = array();
			$webhook_url = str_replace('http:', 'https:', HTTP_CATALOG) . 'index.php?route=extension/' . $this->type . '/' . $this->name . '/webhook&key=' . md5($this->config->get('config_encryption'));
			
			$webhooks = $this->curlRequest('GET', 'webhook_endpoints');
			
			if (!empty($webhooks['data'])) {
				foreach ($webhooks['data'] as $webhook) {
					$urls[] = $webhook['url'];
				}
			}
			
			if (!in_array($webhook_url, $urls)) {
				$webhook_data = array(
					'enabled_events'	=> array('*'),
					'url'				=> $webhook_url,
					'api_version'		=> '2019-03-14',
				);
				
				$create_webhook = $this->curlRequest('POST', 'webhook_endpoints', $webhook_data);
				
				if (!empty($create_webhook['error'])) {
					$this->log->write('STRIPE ERROR: ' . $create_webhook['error']['message']);
				}
			}
		}
		
		//------------------------------------------------------------------------------
		// Extensions Settings
		//------------------------------------------------------------------------------
		$data['settings'] = array();
		
		$data['settings'][] = array(
			'type'		=> 'tabs',
			// 'tabs'		=> array('extension_settings', 'order_statuses', 'restrictions', 'stripe_settings', 'stripe_checkout', 'other_payment_methods', 'subscription_products', 'create_a_charge'), 
			'tabs'		=> array('extension_settings', 'order_statuses', 'restrictions', 'stripe_settings'), 
		);
		$data['settings'][] = array(
			'key'		=> 'extension_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'status',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
			'default'	=> 1,
		);
		$data['settings'][] = array(
			'key'		=> 'sort_order',
			'type'		=> 'text',
			'default'	=> 1,
			'class'		=> 'short',
		);
		$data['settings'][] = array(
			'key'		=> 'title',
			'type'		=> 'multilingual_text',
			'default'	=> 'Credit / Debit Card',
		);
		$data['settings'][] = array(
			'key'		=> 'card_input_format',
			'type'		=> 'select',
			// 'options'	=> array('combined' => $data['text_combined'], 'individual' => $data['text_individual']),
			'options'	=> array('individual' => $data['text_individual']),
			'default'	=> 'individual',
			'hide' 		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'button_text',
			'type'		=> 'multilingual_text',
			'default'	=> 'Confirm Order',
		);
		$data['settings'][] = array(
			'key'		=> 'button_class',
			'type'		=> 'text',
			'default'	=> 'btn btn-primary',
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'button_styling',
			'type'		=> 'text',
			'hide'		=> true
		);
		
		// Payment Page Text
		$data['settings'][] = array(
			'key'		=> 'payment_page_text',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'text_use_a_new_card',
			'type'		=> 'multilingual_text',
			'default'	=> 'Credit / Debit Card',
		);
		$data['settings'][] = array(
			'key'		=> 'new_card_image',
			'type'		=> 'text',
			'default'	=> str_replace('"', '&quot;', $data['new_card_image']),
		);
		$data['settings'][] = array(
			'key'		=> 'text_card_number',
			'type'		=> 'multilingual_text',
			'default'	=> 'Card Number:',
		);
		$data['settings'][] = array(
			'key'		=> 'text_card_expiry',
			'type'		=> 'multilingual_text',
			'default'	=> 'Expiration Date:',
		);
		$data['settings'][] = array(
			'key'		=> 'text_card_cvc',
			'type'		=> 'multilingual_text',
			'default'	=> 'Security Code:',
		);
		$data['settings'][] = array(
			'key'		=> 'text_store_card',
			'type'		=> 'multilingual_text',
			'default'	=> 'Store Card for Future Use:',
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'text_use_a_stored_card',
			'type'		=> 'multilingual_text',
			'default'	=> 'Use a Stored Card',
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'stored_card_image',
			'type'		=> 'text',
			'default'	=> str_replace('"', '&quot;', $data['stored_card_image']),
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'text_ending_in',
			'type'		=> 'multilingual_text',
			'default'	=> 'ending in',
		);
		$data['settings'][] = array(
			'key'		=> 'text_set_card_as_default',
			'type'		=> 'multilingual_text',
			'default'	=> 'Set Card as Default:',
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'text_customer_required',
			'type'		=> 'multilingual_text',
			'default'	=> 'Error: You must create a customer account to purchase a subscription product.',
		);
		$data['settings'][] = array(
			'key'		=> 'text_to_be_charged',
			'type'		=> 'multilingual_text',
			'default'	=> 'To Be Charged Later',
			'hide'		=> true
		);
		
		// Please Wait Messages
		$data['settings'][] = array(
			'key'		=> 'please_wait_messages',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'text_please_wait',
			'type'		=> 'multilingual_text',
			'default'	=> 'Please wait...',
		);
		$data['settings'][] = array(
			'key'		=> 'text_validating_payment_info',
			'type'		=> 'multilingual_text',
			'default'	=> 'Validating payment info...',
		);
		$data['settings'][] = array(
			'key'		=> 'text_redirecting_to_payment',
			'type'		=> 'multilingual_text',
			'default'	=> 'Redirecting to payment page...',
		);
		$data['settings'][] = array(
			'key'		=> 'text_processing_payment',
			'type'		=> 'multilingual_text',
			'default'	=> 'Processing payment...',
		);
		$data['settings'][] = array(
			'key'		=> 'text_finalizing_order',
			'type'		=> 'multilingual_text',
			'default'	=> 'Finalizing order...',
		);
		
		// Stripe Error Codes
		$data['settings'][] = array(
			'key'		=> 'stripe_error_codes',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info alert alert-info stick text-center pad-bottom-sm">' . $data['help_stripe_error_codes'] . '</div>',
		);
		$stripe_errors = array(
			'card_declined',
			'expired_card',
			'incorrect_cvc',
			'incorrect_number',
			'incorrect_zip',
			'invalid_cvc',
			'invalid_expiry_month',
			'invalid_expiry_year',
			'invalid_number',
			'missing',
			'processing_error',
		);
		foreach ($stripe_errors as $stripe_error) {
			$data['settings'][] = array(
				'key'		=> 'error_' . $stripe_error,
				'type'		=> 'multilingual_text',
				'class'		=> 'long',
			);
		}
		
		// Cards Page Text (Pro-specific)
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_text',
		// 	'type'		=> 'heading',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_heading',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Your Stored Cards',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_none',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'You have no stored cards.',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_default_card',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Default Card',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_make_default',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Make Default',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_delete',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Delete',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_confirm',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Are you sure you want to delete this card?',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_add_card',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Add New Card',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_card_name',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Name on Card:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_card_details',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Card Details:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_card_address',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Card Address:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'cards_page_success',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Success!',
		// );
		
		// Subscriptions Page Text (Pro-specific)
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_text',
		// 	'type'		=> 'heading',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_heading',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Your Subscriptions',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_message',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> '<h4>Subscriptions will be charged using your default card. The shipping address on the subscription will be your default address, which you can change <a href="index.php?route=account/address">on this page</a></h4>',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_none',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'You have no subscriptions.',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_trial',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Trial End:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_last',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Last Charge:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_next',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Next Charge:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_charge',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Additional Charge:',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_cancel',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Cancel',
		// );
		// $data['settings'][] = array(
		// 	'key'		=> 'subscriptions_page_confirm',
		// 	'type'		=> 'multilingual_text',
		// 	'default'	=> 'Please type CANCEL to confirm that you want to cancel this subscription.',
		// );
		
		//------------------------------------------------------------------------------
		// Order Statuses
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'order_statuses',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info alert alert-info stick text-center pad-bottom-sm">' . $data['help_order_statuses'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'order_statuses',
			'type'		=> 'heading',
		);
		
		$processing_status_id = $this->config->get('config_processing_status');
		$processing_status_id = $processing_status_id[0];
		
		foreach (array('success', 'authorize', 'mismatch', 'error', 'elevated', 'highest', 'street', 'zip', 'cvc', 'refund', 'partial') as $order_status) { // Pro-specific
			if ($order_status == 'success' || $order_status == 'authorize') {
				$default_status = $processing_status_id;
			} elseif ($order_status == 'error' || $order_status == 'mismatch') {
				$default_status = 1;
			} else {
				$default_status = 0;
			}
			
			$data['settings'][] = array(
				'key'		=> $order_status . '_status_id',
				'type'		=> 'select',
				'options'	=> $data['order_status_array'],
				'default'	=> $default_status,
			);
		}
		
		//------------------------------------------------------------------------------
		// Restrictions
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info alert alert-info stick text-center pad-bottom-sm">' . $data['help_restrictions'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'restrictions',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'min_total',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 80px !important'),
			'default'	=> '0.50',
		);
		$data['settings'][] = array(
			'key'		=> 'max_total',
			'type'		=> 'text',
			'attributes'=> array('style' => 'width: 80px !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'stores',
			'type'		=> 'checkboxes',
			'options'	=> $data['store_array'],
			'default'	=> array_keys($data['store_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'geo_zones',
			'type'		=> 'checkboxes',
			'options'	=> $data['geo_zone_array'],
			'default'	=> array_keys($data['geo_zone_array']),
		);
		$data['settings'][] = array(
			'key'		=> 'customer_groups',
			'type'		=> 'checkboxes',
			'options'	=> $data['customer_group_array'],
			'default'	=> array_keys($data['customer_group_array']),
		);
		
		// Currency Settings
		$data['settings'][] = array(
			'key'		=> 'currency_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info alert alert-info stick text-center pad-bottom-sm">' . $data['help_currency_settings'] . '</div>',
		);
		foreach ($data['currency_array'] as $code => $title) {
			$data['settings'][] = array(
				'key'		=> 'currencies_' . $code,
				'title'		=> str_replace('[currency]', $code, $data['entry_currencies']),
				'type'		=> 'select',
				'options'	=> array_merge(array(0 => $data['text_currency_disabled']), $data['currency_array']),
				'default'	=> $this->config->get('config_currency'),
			);
		}
		
		//------------------------------------------------------------------------------
		// Stripe Settings
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'stripe_settings',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info alert alert-info stick text-center pad-bottom-sm">' . $data['help_stripe_settings'] . '</div>',
		);
		
		// API Keys
		$data['settings'][] = array(
			'key'		=> 'api_keys',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'test_publishable_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 100% !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'test_secret_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 100% !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'live_publishable_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 100% !important'),
		);
		$data['settings'][] = array(
			'key'		=> 'live_secret_key',
			'type'		=> 'text',
			'attributes'=> array('onchange' => '$(this).val($(this).val().trim())', 'style' => 'width: 100% !important'),
		);
		
		// Stripe Settings
		$data['settings'][] = array(
			'key'		=> 'stripe_settings',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'three_d_secure',
			'type'		=> 'html',
			'content'	=> '<div class="text-info">' . $data['text_three_d_secure'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'transaction_mode',
			'type'		=> 'select',
			'options'	=> array('test' => $data['text_test'], 'live' => $data['text_live']),
		);
		$data['settings'][] = array(
			'key'		=> 'charge_mode',
			'type'		=> 'select',
			'options'	=> array('authorize' => $data['text_authorize'], 'capture' => $data['text_capture'], 'fraud' => $data['text_fraud_authorize']),
			'default'	=> 'capture',
		);
		$data['settings'][] = array(
			'key'		=> 'transaction_description',
			'type'		=> 'text',
			'default'	=> '[store]: Order #[order_id] ([email])',
		);
		$data['settings'][] = array(
			'key'		=> 'send_customer_data',
			'type'		=> 'select',
			'options'	=> array('never' => $data['text_never'], 'choice' => $data['text_customers_choice'], 'always' => $data['text_always']),
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'allow_stored_cards',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
			'hide'		=> true
		);
		$data['settings'][] = array(
			'key'		=> 'always_send_receipts',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		
		//------------------------------------------------------------------------------
		// Stripe Checkout
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'stripe_checkout',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center pad-bottom-sm">' . $data['help_stripe_checkout'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'stripe_checkout',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-danger well" style="font-size: 15px">' . $data['text_stripe_checkout_placeholder'] . '</div>',
		);
		
		//------------------------------------------------------------------------------
		// Other Payment Methods
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'other_payment_methods',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center pad-bottom-sm">' . $data['help_other_payment_methods'] . '</div>',
		);
		
		// Error Page
		$data['settings'][] = array(
			'key'		=> 'error_page',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'error_page',
			'type'		=> 'multilingual_textarea',
			'attributes'=> array('style' => 'font-family: monospace; height: 180px; width: 600px !important'),
			'default'	=> '
[header]
<div class="container" style="font-size: 18px; min-height: 600px; text-align: center;">
	<div style="color: red; margin: 20px">
		<b>Error:</b> [error]
	</div>
	<a href="' . HTTPS_CATALOG . 'index.php?route=checkout/checkout">
		Return to checkout
	</a>
</div>
[footer]
			',
		);
		
		// Apple Pay, Google Pay, and Microsoft Pay
		foreach (array('applepay', 'googlepay', 'microsoftpay') as $payment_type) {
			$data['settings'][] = array(
				'key'		=> $payment_type,
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'key'		=> $payment_type,
				'type'		=> 'select',
				'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
				'default'	=> 0,
			);
			$data['settings'][] = array(
				'key'		=> $payment_type . '_heading',
				'type'		=> 'multilingual_text',
				'default'	=> $data['heading_' . $payment_type],
			);
			$data['settings'][] = array(
				'key'		=> $payment_type . '_image',
				'type'		=> 'text',
				'default'	=> str_replace('"', '&quot;', $data[$payment_type . '_image']),
			);
		}
		
		// Other payment methods
		foreach (array('alipay', 'bancontact', 'eps', 'giropay', 'ideal', 'masterpass', 'p24', 'visacheckout', 'wechat') as $payment_type) {
			$data['settings'][] = array(
				'key'		=> $payment_type,
				'type'		=> 'heading',
			);
			$data['settings'][] = array(
				'key'		=> $payment_type,
				'type'		=> 'select',
				'options'	=> array(1 => $data['text_enabled'], 0 => $data['text_disabled']),
				'default'	=> 0,
			);
			$data['settings'][] = array(
				'key'		=> $payment_type . '_heading',
				'type'		=> 'multilingual_text',
				'default'	=> $data['heading_' . $payment_type],
			);
			$data['settings'][] = array(
				'key'		=> $payment_type . '_image',
				'type'		=> 'text',
				'default'	=> str_replace('"', '&quot;', $data[$payment_type . '_image']),
			);
			
			if ($payment_type == 'masterpass') {
				$data['settings'][] = array(
					'key'		=> $payment_type . '_checkout_id',
					'type'		=> 'text',
				);
			} elseif ($payment_type == 'visacheckout') {
				$data['settings'][] = array(
					'key'		=> $payment_type . '_production_key',
					'type'		=> 'text',
				);
				$data['settings'][] = array(
					'key'		=> $payment_type . '_sandbox_key',
					'type'		=> 'text',
				);
			} else {
				$data['settings'][] = array(
					'key'		=> $payment_type . '_instructions',
					'type'		=> 'multilingual_textarea',
				);
			}
		}
		
		//------------------------------------------------------------------------------
		// Subscription Products
		//------------------------------------------------------------------------------
		$data['settings'][] = array(
			'key'		=> 'subscription_products',
			'type'		=> 'tab',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info pad-left pad-bottom-sm">' . $data['help_subscription_products'] . '</div>',
		);
		$data['settings'][] = array(
			'key'		=> 'subscription_products',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'key'		=> 'subscriptions',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'prevent_guests',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'order_address',
			'type'		=> 'select',
			'options'	=> array('stripe' => $data['text_stripe_address'], 'opencart' => $data['text_opencart_address'], 'both' => $data['text_stripe_and_opencart']),
			'default'	=> 'both',
		);
		
		// Pro-specific
		$data['settings'][] = array(
			'key'		=> 'include_shipping',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 0,
		);
		$data['settings'][] = array(
			'key'		=> 'allow_customers_to_cancel',
			'type'		=> 'select',
			'options'	=> array(1 => $data['text_yes'], 0 => $data['text_no']),
			'default'	=> 1,
		);
		
		// Current Subscription Products
		$data['settings'][] = array(
			'key'		=> 'current_subscriptions',
			'type'		=> 'heading',
		);
		$subscription_products_table = '
			<div class="form-group">
				<label class="control-label col-sm-3">' . str_replace('[transaction_mode]', ucwords(isset($data['saved']['transaction_mode']) ? $data['saved']['transaction_mode'] : 'test'), $data['entry_current_subscriptions']) . '</label>
				<div class="col-sm-9">
					<br />
					<table class="table table-stripe table-bordered">
						<thead>
							<tr>
								<td colspan="3" style="text-align: center">' . $data['text_thead_opencart'] . '</td>
								<td colspan="3" style="text-align: center">' . $data['text_thead_stripe'] . '</td>
							</tr>
							<tr>
								<td class="left">' . $data['text_product_name'] . '</td>
								<td class="left">' . $data['text_product_price'] . '</td>
								<td class="left">' . $data['text_location_plan_id'] . '</td>
								<td class="left">' . $data['text_plan_name'] . '</td>
								<td class="left">' . $data['text_plan_interval'] . '</td>
								<td class="left">' . $data['text_plan_charge'] . '</td>
							</tr>
						</thead>
		';
		if (empty($data['subscription_products'])) {
			$subscription_products_table .= '
				<tr><td class="center" colspan="6">' . $data['text_no_subscription_products'] . '</td></tr>
				<tr><td class="center" colspan="6">' . $data['text_create_one_by_entering'] . '</td></tr>
			';
		}
		foreach ($data['subscription_products'] as $product) {
			$highlight = ($product['price'] == $product['charge']) ? '' : 'style="background: #FDD"';
			$subscription_products_table .= '
				<tr>
					<td class="left"><a target="_blank" href="index.php?route=catalog/product/edit&amp;product_id=' . $product['product_id'] . '&amp;token=' . $data['token'] . '">' . $product['name'] . '</a></td>
					<td class="left" ' . $highlight . '>' . $product['price'] . '</td>
					<td class="left">' . $product['location'] . '</td>
					<td class="left">' . $product['plan'] . '</td>
					<td class="left">' . $product['interval'] . '</td>
					<td class="left" ' . $highlight . '>' . $product['charge'] . '</td>
				</tr>
			';
		}
		$subscription_products_table .= '</table></div></div><br />';
		
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> $subscription_products_table,
		);
		
		// Map Options to Subscriptions (Pro-specific)
		$data['settings'][] = array(
			'key'		=> 'map_options',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="margin-bottom: 30px">' . $data['help_map_options'] . '</div>',
		);
		
		$table = 'subscription_options';
		$sortby = 'option_name';
		$data['settings'][] = array(
			'key'		=> $table,
			'type'		=> 'table_start',
			'columns'	=> array('action', 'option_name', 'option_value', 'plan_id', 'start_date', 'cycles'),
		);
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'option_name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'option_value',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'plan_id',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'start_date',
				'type'		=> 'text',
				'attributes'=> array('placeholder' => 'YYYY-MM-DD', 'style' => 'width: 100px !important', 'maxlength' => '10'),
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'cycles',
				'type'		=> 'text',
				'attributes'=> array('style' => 'width: 80px !important'),
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_mapping',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<br />',
		);
		
		// Map Recurring Profiles to Subscriptions (Pro-specific)
		$data['settings'][] = array(
			'key'		=> 'map_recurring_profiles',
			'type'		=> 'heading',
		);
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> '<div class="text-info text-center" style="margin-bottom: 30px">' . $data['help_map_recurring_profiles'] . '</div>',
		);
		
		$table = 'subscription_profiles';
		$sortby = 'profile_name';
		$data['settings'][] = array(
			'key'		=> $table,
			'type'		=> 'table_start',
			'columns'	=> array('action', 'profile_name', 'plan_id', 'start_date', 'cycles'),
		);
		foreach ($this->getTableRowNumbers($data, $table, $sortby) as $num => $rules) {
			$prefix = $table . '_' . $num . '_';
			$data['settings'][] = array(
				'type'		=> 'row_start',
			);
			$data['settings'][] = array(
				'key'		=> 'delete',
				'type'		=> 'button',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'profile_name',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'plan_id',
				'type'		=> 'text',
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'start_date',
				'type'		=> 'text',
				'attributes'=> array('placeholder' => 'YYYY-MM-DD', 'style' => 'width: 100px !important', 'maxlength' => '10'),
			);
			$data['settings'][] = array(
				'type'		=> 'column',
			);
			$data['settings'][] = array(
				'key'		=> $prefix . 'cycles',
				'type'		=> 'text',
				'attributes'=> array('style' => 'width: 80px !important'),
			);
			$data['settings'][] = array(
				'type'		=> 'row_end',
			);
		}
		$data['settings'][] = array(
			'type'		=> 'table_end',
			'buttons'	=> 'add_row',
			'text'		=> 'button_add_mapping',
		);
		
		//------------------------------------------------------------------------------
		// Create a Charge
		//------------------------------------------------------------------------------
		// Pro-specific
		$data['settings'][] = array(
			'key'		=> 'create_a_charge',
			'type'		=> 'tab',
		);
		
		$settings = $data['saved'];
		$language = $this->config->get('config_language');
		
		ob_start();
		$filepath = DIR_APPLICATION . 'view/template/extension/payment/' . $this->name . '_card_form.twig';
		include_once(class_exists('VQMod') ? VQMod::modCheck(modification($filepath)) : modification($filepath));
		$tpl_contents = ob_get_contents();
		ob_end_clean();
		
		$data['settings'][] = array(
			'type'		=> 'html',
			'content'	=> $tpl_contents,
		);
		
		//------------------------------------------------------------------------------
		// end settings
		//------------------------------------------------------------------------------
		
		$this->document->setTitle($data['heading_title']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$template_file = DIR_TEMPLATE . 'extension/' . $this->type . '/' . $this->name . '.twig';
		
		if (is_file($template_file)) {
			extract($data);
			
			ob_start();
			require(class_exists('VQMod') ? VQMod::modCheck(modification($template_file)) : modification($template_file));
			$output = ob_get_clean();
			
			if (version_compare(VERSION, '3.0', '>=')) {
				$output = str_replace('&token=', '&user_token=', $output);
			}
			
			echo $output;
		} else {
			echo 'Error loading template file';
		}
	}
	
	
	// Helper functions
	
	private function hasPermission($permission) {
		return ($this->user->hasPermission($permission, $this->type . '/' . $this->name) || $this->user->hasPermission($permission, 'extension/' . $this->type . '/' . $this->name));
	}
	
	private function loadLanguage($path) {
		$_ = array();
		$language = array();
		$admin_language = (version_compare(VERSION, '2.2', '<')) ? $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE `code` = '" . $this->db->escape($this->config->get('config_admin_language')) . "'")->row['directory'] : $this->config->get('config_admin_language');
		foreach (array('english', 'en-gb', $admin_language) as $directory) {
			$file = DIR_LANGUAGE . $directory . '/' . $directory . '.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/default.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/' . $path . '.php';
			if (file_exists($file)) require($file);
			$file = DIR_LANGUAGE . $directory . '/extension/' . $path . '.php';
			if (file_exists($file)) require($file);
			$language = array_merge($language, $_);
		}
		return $language;
	}
	
	private function getTableRowNumbers(&$data, $table, $sorting) {
		$groups = array();
		$rules = array();
		
		foreach ($data['saved'] as $key => $setting) {
			if (preg_match('/' . $table . '_(\d+)_' . $sorting . '/', $key, $matches)) {
				$groups[$setting][] = $matches[1];
			}
			if (preg_match('/' . $table . '_(\d+)_rule_(\d+)_type/', $key, $matches)) {
				$rules[$matches[1]][] = $matches[2];
			}
		}
		
		if (empty($groups)) $groups = array('' => array('1'));
		ksort($groups, defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		
		foreach ($rules as $key => $rule) {
			ksort($rules[$key], defined('SORT_NATURAL') ? SORT_NATURAL : SORT_REGULAR);
		}
		
		$data['used_rows'][$table] = array();
		$rows = array();
		foreach ($groups as $group) {
			foreach ($group as $num) {
				$data['used_rows'][preg_replace('/module_(\d+)_/', '', $table)][] = $num;
				$rows[$num] = (empty($rules[$num])) ? array() : $rules[$num];
			}
		}
		sort($data['used_rows'][$table]);
		
		return $rows;
	}
	
	
	// Setting functions
	
	private $encryption_key = '';
	
	public function loadSettings(&$data) {
		$backup_type = (empty($data)) ? 'manual' : 'auto';
		if ($backup_type == 'manual' && !$this->hasPermission('modify')) {
			return;
		}
		
		$this->cache->delete($this->name);
		unset($this->session->data[$this->name]);
		$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		
		// Set exit URL
		$data['token'] = $this->session->data[version_compare(VERSION, '3.0', '<') ? 'token' : 'user_token'];
		$data['exit'] = $this->url->link((version_compare(VERSION, '3.0', '<') ? 'extension' : 'marketplace') . '/' . (version_compare(VERSION, '2.3', '<') ? '' : 'extension&type=') . $this->type . '&token=' . $data['token'], '', 'SSL');
		
		// Load saved settings
		$data['saved'] = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$key = str_replace($code . '_', '', $setting['key']);
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			
			$data['saved'][$key] = $value;
			
			if (is_array($value)) {
				foreach ($value as $num => $value_array) {
					foreach ($value_array as $k => $v) {
						$data['saved'][$key . '_' . $num . '_' . $k] = $v;
					}
				}
			}
		}
		
		// Load language and run standard checks
		$data = array_merge($data, $this->loadLanguage($this->type . '/' . $this->name));
		
		if (ini_get('max_input_vars') && ((ini_get('max_input_vars') - count($data['saved'])) < 50)) {
			$data['warning'] = $data['standard_max_input_vars'];
		}
		
		// Modify files according to OpenCart version
		if ($this->type == 'total' && version_compare(VERSION, '2.2', '<')) {
			file_put_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php', str_replace('public function getTotal($total) {', 'public function getTotal(&$total_data, &$order_total, &$taxes) {' . "\n\t\t" . '$total = array("totals" => &$total_data, "total" => &$order_total, "taxes" => &$taxes);', file_get_contents(DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php')));
		}
		
		if (version_compare(VERSION, '2.3', '>=')) {
			$filepaths = array(
				DIR_APPLICATION . 'controller/' . $this->type . '/' . $this->name . '.php',
				DIR_CATALOG . 'controller/' . $this->type . '/' . $this->name . '.php',
				DIR_CATALOG . 'model/' . $this->type . '/' . $this->name . '.php',
			);
			foreach ($filepaths as $filepath) {
				if (file_exists($filepath)) {
					rename($filepath, str_replace('.php', '.php-OLD', $filepath));
				}
			}
		}
		
		// Set save type and skip auto-backup if not needed
		if (!empty($data['saved']['autosave'])) {
			$data['save_type'] = 'auto';
		}
		
		if ($backup_type == 'auto' && empty($data['autobackup'])) {
			return;
		}
		
		// Create settings auto-backup file
		$manual_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.backup';
		$auto_filepath = DIR_LOGS . $this->name . $this->encryption_key . '.autobackup';
		$filepath = ($backup_type == 'auto') ? $auto_filepath : $manual_filepath;
		if (file_exists($filepath)) unlink($filepath);
		
		file_put_contents($filepath, 'SETTING	NUMBER	SUB-SETTING	SUB-NUMBER	SUB-SUB-SETTING	VALUE' . "\n", FILE_APPEND|LOCK_EX);
		
		foreach ($data['saved'] as $key => $value) {
			if (is_array($value)) continue;
			
			$parts = explode('|', preg_replace(array('/_(\d+)_/', '/_(\d+)/'), array('|$1|', '|$1'), $key));
			
			$line = '';
			for ($i = 0; $i < 5; $i++) {
				$line .= (isset($parts[$i]) ? $parts[$i] : '') . "\t";
			}
			$line .= str_replace(array("\t", "\n"), array('    ', '\n'), $value) . "\n";
			
			file_put_contents($filepath, $line, FILE_APPEND|LOCK_EX);
		}
		
		$data['autobackup_time'] = date('Y-M-d @ g:i a');
		$data['backup_time'] = (file_exists($manual_filepath)) ? date('Y-M-d @ g:i a', filemtime($manual_filepath)) : '';
		
		if ($backup_type == 'manual') {
			echo $data['autobackup_time'];
		}
	}
	
	public function saveSettings() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$this->cache->delete($this->name);
		unset($this->session->data[$this->name]);
		$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		
		if ($this->request->get['saving'] == 'manual') {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' AND `key` != '" . $this->db->escape($this->name . '_module') . "'");
		}
		
		$module_id = 0;
		$modules = array();
		$module_instance = false;
		
		foreach ($this->request->post as $key => $value) {
			if (strpos($key, 'module_') === 0) {
				$parts = explode('_', $key, 3);
				$module_id = $parts[1];
				$modules[$parts[1]][$parts[2]] = $value;
				if ($parts[2] == 'module_id') $module_instance = true;
			} else {
				$key = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name . '_' . $key;
				
				if ($this->request->get['saving'] == 'auto') {
					$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' AND `key` = '" . $this->db->escape($key) . "'");
				}
				
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "setting SET
					`store_id` = 0,
					`code` = '" . $this->db->escape($code) . "',
					`key` = '" . $this->db->escape($key) . "',
					`value` = '" . $this->db->escape(stripslashes(is_array($value) ? implode(';', $value) : $value)) . "',
					`serialized` = 0
				");
			}
		}
		
		foreach ($modules as $module_id => $module) {
			if (!$module_id) {
				$this->db->query("
					INSERT INTO " . DB_PREFIX . "module SET
					`name` = '" . $this->db->escape($module['name']) . "',
					`code` = '" . $this->db->escape($this->name) . "',
					`setting` = ''
				");
				$module_id = $this->db->getLastId();
				$module['module_id'] = $module_id;
			}
			$module_settings = (version_compare(VERSION, '2.1', '<')) ? serialize($module) : json_encode($module);
			$this->db->query("
				UPDATE " . DB_PREFIX . "module SET
				`name` = '" . $this->db->escape($module['name']) . "',
				`code` = '" . $this->db->escape($this->name) . "',
				`setting` = '" . $this->db->escape($module_settings) . "'
				WHERE module_id = " . (int)$module_id . "
			");
		}
	}
	
	public function deleteSetting() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		$prefix = (version_compare(VERSION, '3.0', '<')) ? '' : $this->type . '_';
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($prefix . $this->name) . "' AND `key` = '" . $this->db->escape($prefix . $this->name . '_' . str_replace('[]', '', $this->request->get['setting'])) . "'");
	}
	
	
	// capture()
	
	public function capture() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$capture_response = $this->curlRequest('POST', 'payment_intents/' . $this->request->get['payment_intent_id'] . '/capture', array('amount_to_capture' => $this->request->get['amount'] * 100));
		
		if (!empty($capture_response['error'])) {
			$this->log->write('STRIPE ERROR: ' . $capture_response['error']['message']);
			echo 'Error: ' . $capture_response['error']['message'];
		}
		
		if (empty($capture_response['error']) || strpos($capture_response['error']['message'], 'has already been captured')) {
			$this->db->query("UPDATE " . DB_PREFIX . "order_history SET `comment` = REPLACE(`comment`, '<span>No &nbsp;</span> <a', 'Yes (" . number_format($this->request->get['amount'], 2, '.', '') . " captured) <a style=\"display: none\"') WHERE `comment` LIKE '%capture($(this), %, \'" . $this->db->escape($this->request->get['payment_intent_id']) . "\')%'");
		}
	}
	
	
	// refund()
	
	public function refund() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$refund_response = $this->curlRequest('POST', 'charges/' . $this->request->get['charge_id'] . '/refunds', array('amount' => $this->request->get['amount'] * 100));
		
		if (!empty($refund_response['error'])) {
			$this->log->write('STRIPE ERROR: ' . $refund_response['error']['message']);
			echo 'Error: ' . $refund_response['error']['message'];
		}
	}
	
	
	// stripeCheckout()
	
	public function stripeCheckout() {
		if (!$this->hasPermission('modify')) {
			echo 'PermissionError';
			return;
		}
		
		$settings = array('autobackup' => false);
		$this->loadSettings($settings);
		$settings = $settings['saved'];
		
		// Get currency settings
		$currency = $this->request->post['currency'];
		$main_currency = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND store_id = 0")->row['value'];
		$decimal_factor = (in_array($settings['currencies_' . $currency], array('BIF','CLP','DJF','GNF','JPY','KMF','KRW','MGA','PYG','RWF','VND','VUV','XAF','XOF','XPF'))) ? 1 : 100;
		
		// Set other checkout data
		$order_id = (int)$this->request->post['order_id'];
		$order_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$order_id)->row;
		
		$customer_id = (!empty($order_info['customer_id'])) ? $order_info['customer_id'] : 0;
		$stripe_customer_id = '';
		
		if ($customer_id) {
			$stripe_customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stripe_customer WHERE customer_id = " . (int)$customer_id);
			if ($stripe_customer_query->num_rows) {
				$stripe_customer_id = $stripe_customer_query->row['stripe_customer_id'];
			}
		}
		
		$current_url = 'http' . (!empty($server['HTTPS']) && $server['HTTPS'] != 'off' ? 's' : '') . '://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
		$current_url = str_replace('/stripeCheckout', '', html_entity_decode($current_url, ENT_QUOTES, 'UTF-8'));
		
		$success_url = $current_url . '&checkout=' . number_format($this->request->post['amount'], 2) . ' ' . $currency;
		if (!empty($order_id)) {
			$success_url .= '&order_id=' . (int)$order_id;
		}
		if (!empty($this->request->post['order_status'])) {
			$success_url .= '&order_status_id=' . (int)$this->request->post['order_status'];
		}
		
		// Create Stripe Checkout session
		$checkout_data = array(
			'payment_method_types'	=> array('card'),
			'line_items'			=> array(array(
				'name'		=> 'Total',
				'amount'	=> round($decimal_factor * $this->request->post['amount']),
				'currency'	=> strtolower($currency),
				'quantity'	=> 1,
			)),
			'success_url'			=> $success_url,
			'cancel_url'			=> $current_url,
			'client_reference_id'	=> $order_id,
			'payment_intent_data'	=> array(
				'description'	=> $this->request->post['description'],
				'metadata'		=> array(
					'Store'			=> substr($this->config->get('config_name'), 0, 200),
					'Order ID'		=> $order_id,
				),
			),
		);
		
		if ($stripe_customer_id) {
			$checkout_data['customer'] = $stripe_customer_id;
		}
		
		$checkout_session = $this->curlRequest('POST', 'checkout/sessions', $checkout_data);
		
		if (!empty($checkout_session['error'])) {
			echo $checkout_session['error']['message'];
		} else {
			echo 'success:' . $checkout_session['id'];
		}
	}
	
	
	// curlRequest()
	
	private function curlRequest($request, $api, $data = array()) {
		$model_file = DIR_CATALOG . 'model/extension/' . $this->type . '/' . $this->name . '.php';
		require_once(class_exists('VQMod') ? VQMod::modCheck($model_file) : $model_file);
		
		$stripe = new ModelExtensionPaymentStripe($this->registry);
		return $stripe->curlRequest($request, $api, $data);
	}
}
?>