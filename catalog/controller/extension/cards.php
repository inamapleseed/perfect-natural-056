<?php
//==============================================================================
// Stripe Payment Gateway Pro v303.4
// 
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
// 
// All code within this file is copyright Clear Thinking, LLC.
// You may not copy or reuse code within this file without written permission.
//==============================================================================

class ControllerExtensionCards extends Controller {
	private $type = 'extension';
	private $name = 'cards';
	
	public function index() {
		$data['type'] = $this->type;
		$data['name'] = $this->name;
		
		$settings = $this->getSettings();
		$data['settings'] = $settings;
		$data['language'] = $this->session->data['language'];
		$data = array_merge($data, $this->load->language('account/address'));
		
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link($this->type . '/' . $this->name, '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		// Create countries array
		$data['countries'] = array();
		
		$store_country = (int)$this->config->get('config_country_id');
		$country_query = $this->db->query("(SELECT * FROM " . DB_PREFIX . "country WHERE country_id = " . $store_country . ") UNION (SELECT * FROM " . DB_PREFIX . "country WHERE country_id != " . $store_country . ")");
		
		foreach ($country_query->rows as $country) {
			$data['countries'][$country['iso_code_2']] = $country['name'];
		}
		
		// Get customer info
		$data['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		$data['customer_email'] = $this->customer->getEmail();
		
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = " . (int)$this->customer->getAddressId());
		$data['address'] = ($address_query->num_rows) ? $address_query->row : array();
		
		$payment_methods = array();
		$subscriptions = array();
		$this->session->data['stripe_customer_id'] = '';
		
		$customer_id_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stripe_customer WHERE customer_id = " . (int)$this->customer->getId() . " AND transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
		
		if ($customer_id_query->num_rows) {
			$stripe_customer_id = $customer_id_query->row['stripe_customer_id'];
			$this->session->data['stripe_customer_id'] = $stripe_customer_id;
			
			$payment_methods = $this->curlRequest('GET', 'payment_methods', array('customer' => $stripe_customer_id, 'type' => 'card'));
			
			if (!empty($payment_methods['error'])) {
				$data['error_warning'] = $payment_methods['error']['message'];
				$payment_methods = array();
				$subscriptions = array();
			} else {
				$customer_response = $this->curlRequest('GET', 'customers/' . $stripe_customer_id);
				$default_payment_method = '';
				
				if (!empty($customer_response['deleted'])) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "stripe_customer WHERE stripe_customer_id = '" . $this->db->escape($stripe_customer_id) . "'");
				} elseif (!empty($customer_response['error'])) {
					$data['error_warning'] = $customer_response['error']['message'];
				} else {
					$payment_methods = $payment_methods['data'];
					$default_payment_method = (!empty($customer_response['invoice_settings']['default_payment_method'])) ? $customer_response['invoice_settings']['default_payment_method'] : '';
					$subscriptions = $customer_response['subscriptions']['data'];
				}
			}
		}
		
		// Create cards array
		if ($settings['allow_stored_cards']) {
			$data['cards'] = array();
			
			foreach ($payment_methods as $payment_method) {
				if ($payment_method['type'] != 'card') continue;
				
				$card = array(
					'default'	=> ($payment_method['id'] == $default_payment_method),
					'id'		=> $payment_method['id'],
					'text'		=> ucwords($payment_method['card']['brand']) . ' ' . $settings['text_ending_in_' . $data['language']] . ' ' . $payment_method['card']['last4'] . ' (' . str_pad($payment_method['card']['exp_month'], 2, '0', STR_PAD_LEFT) . '/' . substr($payment_method['card']['exp_year'], 2) . ')',
				);
				
				if ($card['default']) {
					array_unshift($data['cards'], $card);
				} else {
					$data['cards'][] = $card;
				}
			}
		}
		
		// Create subscriptions array
		if ($settings['subscriptions']) {
			$data['subscriptions'] = array();
			
			foreach ($subscriptions as $subscription) {
				if (!empty($subscription['ended_at'])) continue;
				
				$upcoming_invoice_response = $this->curlRequest('GET', 'invoices/upcoming', array('customer' => $stripe_customer_id, 'subscription' => $subscription['id']));
				$upcoming_invoice_items = (empty($upcoming_invoice_response['error'])) ? $upcoming_invoice_response['lines']['data'] : array();
				
				$invoiceitems = array();
				foreach ($upcoming_invoice_items as $invoice_item) {
					if ($invoice_item['type'] == 'subscription') continue;
					$invoiceitems[] = $invoice_item['description'] . ' (' . $this->currency->format($invoice_item['amount'] / 100, strtoupper($invoice_item['currency'])) . ')';
				}
				
				$plan = $subscription['plan'];
				$plan_name = (!empty($plan['nickname'])) ? $plan['nickname'] : $plan['id'];
				
				$data['subscriptions'][] = array(
					'id'			=> $subscription['id'],
					'last'			=> $subscription['current_period_start'],
					'next'			=> $subscription['current_period_end'],
					'invoiceitems'	=> $invoiceitems,
					'plan'			=> $plan_name . ' (' . $this->currency->format($plan['amount'] / 100, strtoupper($plan['currency'])) . ' / ' . ($plan['interval_count'] == 1 ? '' : $plan['interval_count'] . ' ') . $plan['interval'] . ')',
					'trial'			=> $subscription['trial_end'],
				);
			}
		}
		
		// Breadcrumbs
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'		=> $data['text_home'],
			'href'		=> $this->url->link('common/home'),
		);
		$data['breadcrumbs'][] = array(
			'text'		=> $data['text_account'],
			'href'		=> $this->url->link('account/account', '', 'SSL'),
		);
		$data['breadcrumbs'][] = array(
			'text'		=> $settings['cards_page_heading_' . $data['language']],
			'href'		=> $this->url->link($this->type . '/' . $this->name, '', 'SSL'),
		);
		
		// Render
		$this->document->setTitle($settings['cards_page_heading_' . $data['language']]);
		$data['heading_title'] = $settings['cards_page_heading_' . $data['language']];
		$data['back'] = $this->url->link('account/account', '', 'SSL');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$theme = (version_compare(VERSION, '2.2', '<')) ? $this->config->get('config_template') : str_replace('theme_', '', $this->config->get('config_theme'));
		$template = (file_exists(DIR_TEMPLATE . $theme . '/template/' . $this->type . '/' . $this->name . '.twig')) ? $theme : 'default';
		$template_file = DIR_TEMPLATE . $template . '/template/' . $this->type . '/' . $this->name . '.twig';
		
		if (is_file($template_file)) {
			extract($data);
			
			ob_start();
			require(class_exists('VQMod') ? VQMod::modCheck(modification($template_file)) : modification($template_file));
			$output = ob_get_clean();
			
			echo $output;
		} else {
			echo 'Error loading template file';
		}
	}
	
	//==============================================================================
	// getSettings()
	//==============================================================================
	private function getSettings() {
		//$code = (version_compare(VERSION, '3.0', '<') ? '' : $this->type . '_') . $this->name;
		$code = (version_compare(VERSION, '3.0', '<') ? '' : 'payment_') . 'stripe';
		
		$settings = array();
		$settings_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE `code` = '" . $this->db->escape($code) . "' ORDER BY `key` ASC");
		
		foreach ($settings_query->rows as $setting) {
			$value = $setting['value'];
			if ($setting['serialized']) {
				$value = (version_compare(VERSION, '2.1', '<')) ? unserialize($setting['value']) : json_decode($setting['value'], true);
			}
			$split_key = preg_split('/_(\d+)_?/', str_replace($code . '_', '', $setting['key']), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			
				if (count($split_key) == 1)	$settings[$split_key[0]] = $value;
			elseif (count($split_key) == 2)	$settings[$split_key[0]][$split_key[1]] = $value;
			elseif (count($split_key) == 3)	$settings[$split_key[0]][$split_key[1]][$split_key[2]] = $value;
			elseif (count($split_key) == 4)	$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]] = $value;
			else 							$settings[$split_key[0]][$split_key[1]][$split_key[2]][$split_key[3]][$split_key[4]] = $value;
		}
		
		return $settings;
	}
	
	//==============================================================================
	// curlRequest()
	//==============================================================================
	private function curlRequest($request, $api, $data = array()) {
		$this->load->model('extension/payment/stripe');
		return $this->model_extension_payment_stripe->curlRequest($request, $api, $data);
	}
	
	//==============================================================================
	// Ajax functions
	//==============================================================================
	public function modifyObject() {
		$settings = $this->getSettings();

		if ($this->request->get['request'] == 'make_default') {
			
			$response = $this->curlRequest('POST', 'customers/' . $this->session->data['stripe_customer_id'], array('invoice_settings' => array('default_payment_method' => $this->request->get['id'])));
			
		} elseif ($this->request->get['request'] == 'delete_card') {
			
			$response = $this->curlRequest('POST', 'payment_methods/' . $this->request->get['id'] . '/detach');
			
		} elseif ($this->request->get['request'] == 'add_card') {
			
			$customer_id_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stripe_customer WHERE stripe_customer_id = '" . $this->db->escape($this->session->data['stripe_customer_id']) . "' AND transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
			
			if (!$customer_id_query->num_rows) {
				$customer_data['description'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName() . ' (' . 'customer_id: ' . $this->customer->getId() . ')';
				$customer_data['email'] = $this->customer->getEmail();
				
				$response = $this->curlRequest('POST', 'customers', $customer_data);
				
				if (empty($response['error'])) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "stripe_customer SET customer_id = " . (int)$this->customer->getId() . ", stripe_customer_id = '" . $this->db->escape($response['id']) . "', transaction_mode = '" . $this->db->escape($settings['transaction_mode']) . "'");
				}
			}
			
			$response = $this->curlRequest('POST', 'payment_methods/' . $this->request->get['id'] . '/attach', array('customer' => $this->session->data['stripe_customer_id']));
			
		} elseif ($this->request->get['request'] == 'cancel_subscription') {
			
			$response = $this->curlRequest('DELETE', 'customers/' . $this->session->data['stripe_customer_id'] . '/subscriptions/' . $this->request->get['id']);
			$invoice_items = $this->curlRequest('GET', 'invoiceitems', array('customer' => $this->session->data['stripe_customer_id'], 'limit' => 100));
			
			foreach ($invoice_items['data'] as $invoice_item) {
				if ($invoice_item['subscription'] == $this->request->get['id'] || (empty($invoice_item['subscription']) && strpos($invoice_item['description'], 'Shipping for') === 0)) {
					$this->curlRequest('DELETE', 'invoiceitems/' . $invoice_item['id']);
				}
			}
			
		}
		
		if (!empty($response['error'])) {
			$this->log->write('STRIPE CARD/SUBSCRIPTION PAGE ERROR: ' . $response['error']['message']);
			echo $response['error']['message'];
		}
	}
}
?>