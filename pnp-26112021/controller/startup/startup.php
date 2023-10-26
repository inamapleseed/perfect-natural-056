<?php
class ControllerStartupStartup extends Controller {
	public function index() {
		
		// Settings
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");
		
		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$this->config->set($setting['key'], $setting['value']);
			} else {
				$this->config->set($setting['key'], json_decode($setting['value'], true));
			}
		}
		
		// Timezone	
		if($this->config->get('config_timezone')){
			date_default_timezone_set($this->config->get('config_timezone'));
		}
		
		// Language
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($this->config->get('config_admin_language')) . "'");
		
		if ($query->num_rows) {
			$this->config->set('config_language_id', $query->row['language_id']);
		}
		
		// Language
		$language = new Language($this->config->get('config_admin_language'));
		$language->load($this->config->get('config_admin_language'));
		$this->registry->set('language', $language);
		
		// Customer
		$this->registry->set('customer', new Cart\Customer($this->registry));
		
		// Affiliate
		$this->registry->set('affiliate', new Cart\Affiliate($this->registry));

		// Currency
		$this->registry->set('currency', new Cart\Currency($this->registry));
	
		// Tax
		$this->registry->set('tax', new Cart\Tax($this->registry));
		
		if ($this->config->get('config_tax_default') == 'shipping') {
			$this->tax->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		if ($this->config->get('config_tax_default') == 'payment') {
			$this->tax->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}

		$this->tax->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));

		// Weight
		$this->registry->set('weight', new Cart\Weight($this->registry));
		
		// Length
		$this->registry->set('length', new Cart\Length($this->registry));
		
		// Cart
		$this->registry->set('cart', new Cart\Cart($this->registry));
		
		// Enquiry
		$this->registry->set('enquiry', new Cart\Enquiry($this->registry));
		
		// Encryption
		$this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));
		
		// OpenBay Pro
		$this->registry->set('openbay', new Openbay($this->registry));				
		
		// Excel
    	if (isset($this->request->get['route']) && strpos($this->request->get['route'], 'extension/module/excelport') === false) {
    		/* if it is not excel port module */
		    require_once(DIR_SYSTEM . 'phpexcel/Classes/PHPExcel.php');	
			$this->registry->set('excel', new Excel($this->registry));
	    }

		// Module Helper
		$this->registry->set('modulehelper', new Modulehelper($this->registry));

		// Craft - Alpha version - Don't use
		$this->registry->set('craft', new Craft());
		
		// Enquiry
		// $this->registry->set('omise', new Omise($this->registry));
	}
}