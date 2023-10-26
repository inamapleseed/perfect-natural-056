<?php
class ControllerExtensionShippinghitdhlexpress extends Controller {
	private $error = array();
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "hittech_dhl_details` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `order_id` int(11) NOT NULL,
		  `tracking_num` int(11) NOT NULL,
		  `shipping_label` text COLLATE utf8_bin NOT NULL,
		  `invoice` text COLLATE utf8_bin NOT NULL,
		  `return_label` text COLLATE utf8_bin  NULL,
		  `return_invoice` text COLLATE utf8_bin  NULL,
		  `one` text COLLATE utf8_bin  NULL,
		  `two` text COLLATE utf8_bin  NULL,
		  `three` text COLLATE utf8_bin  NULL,
		  PRIMARY KEY (`id`)
		)");
	}
	public function index() {
		$this->install();
		$this->load->language('extension/shipping/hitdhlexpress');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('hitdhlexpress', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		if (isset($this->error['dimension'])) {
			$data['error_dimension'] = $this->error['dimension'];
		} else {
			$data['error_dimension'] = '';
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_success'] = $this->language->get('text_success');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_shiiping_address'] = $this->language->get('text_shiiping_address');
		$data['text_rates'] = $this->language->get('text_rates');
		$data['text_packing'] = $this->language->get('text_packing');
		$data['text_dhl_1'] = $this->language->get('text_dhl_1');
		$data['text_dhl_2'] = $this->language->get('text_dhl_2');
		$data['text_dhl_3'] = $this->language->get('text_dhl_3');
		$data['text_dhl_4'] = $this->language->get('text_dhl_4');
		$data['text_dhl_5'] = $this->language->get('text_dhl_5');
		$data['text_dhl_6'] = $this->language->get('text_dhl_6');
		$data['text_dhl_7'] = $this->language->get('text_dhl_7');
		$data['text_dhl_8'] = $this->language->get('text_dhl_8');
		$data['text_dhl_9'] = $this->language->get('text_dhl_9');
		$data['text_dhl_B'] = $this->language->get('text_dhl_B');
		$data['text_dhl_C'] = $this->language->get('text_dhl_C');
		$data['text_dhl_D'] = $this->language->get('text_dhl_D');
		$data['text_dhl_E'] = $this->language->get('text_dhl_E');
		$data['text_dhl_F'] = $this->language->get('text_dhl_F');
		$data['text_dhl_G'] = $this->language->get('text_dhl_G');
		$data['text_dhl_H'] = $this->language->get('text_dhl_H');
		$data['text_dhl_I'] = $this->language->get('text_dhl_I');
		$data['text_dhl_J'] = $this->language->get('text_dhl_J');
		$data['text_dhl_K'] = $this->language->get('text_dhl_K');
		$data['text_dhl_L'] = $this->language->get('text_dhl_L');
		$data['text_dhl_M'] = $this->language->get('text_dhl_M');
		$data['text_dhl_N'] = $this->language->get('text_dhl_N');
		$data['text_dhl_O'] = $this->language->get('text_dhl_O');
		$data['text_dhl_P'] = $this->language->get('text_dhl_P');
		$data['text_dhl_Q'] = $this->language->get('text_dhl_Q');
		$data['text_dhl_R'] = $this->language->get('text_dhl_R');
		$data['text_dhl_S'] = $this->language->get('text_dhl_S');
		$data['text_dhl_T'] = $this->language->get('text_dhl_T');
		$data['text_dhl_U'] = $this->language->get('text_dhl_U');
		$data['text_dhl_V'] = $this->language->get('text_dhl_V');
		$data['text_dhl_W'] = $this->language->get('text_dhl_W');
		$data['text_dhl_X'] = $this->language->get('text_dhl_X');
		$data['text_dhl_Y'] = $this->language->get('text_dhl_Y');
		$data['text_regular_pickup'] = $this->language->get('text_regular_pickup');
		$data['text_request_courier'] = $this->language->get('text_request_courier');
		$data['text_your_packaging'] = $this->language->get('text_your_packaging');
		$data['text_list_rate'] = $this->language->get('text_list_rate');
		$data['text_account_rate'] = $this->language->get('text_account_rate');
		$data['entry_shipper_name'] = $this->language->get('entry_shipper_name');
		$data['entry_company_name'] = $this->language->get('entry_company_name');
		$data['entry_phone_num'] = $this->language->get('entry_phone_num');
		$data['entry_email_addr'] = $this->language->get('entry_email_addr');
		$data['entry_address1'] = $this->language->get('entry_address1');
		$data['entry_address2'] = $this->language->get('entry_address2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_state'] = $this->language->get('entry_state');
		$data['entry_country_code'] = $this->language->get('entry_country_code');
		$data['entry_realtime_rates'] = $this->language->get('entry_realtime_rates');
		$data['entry_insurance'] = $this->language->get('entry_insurance');
		$data['_entry_weight'] = $this->language->get('_entry_weight');
		$data['_entry_kgcm'] = $this->language->get('_entry_kgcm');
		$data['_entry_lbin'] = $this->language->get('_entry_lbin');
		$data['_entry_packing_type'] = $this->language->get('_entry_packing_type');
		$data['text_per_item'] = $this->language->get('text_per_item');
		$data['text_dhl_box'] = $this->language->get('text_dhl_box');
		$data['text_dhl_weight_based'] = $this->language->get('text_dhl_weight_based');
		$data['text_peritem_head'] = $this->language->get('text_peritem_head');
		$data['text_box_head'] = $this->language->get('text_box_head');
		$data['text_weight_head'] = $this->language->get('text_weight_head');
		$data['text_box'] = $this->language->get('text_box');
		$data['text_fly'] = $this->language->get('text_fly');
		$data['text_dhl_yp'] = $this->language->get('text_dhl_yp');
		$data['text_enable'] = $this->language->get('text_enable');
		$data['text_disable'] = $this->language->get('text_disable');
		$data['text_head1'] = $this->language->get('text_head1');
		$data['text_head2'] = $this->language->get('text_head2');
		$data['text_head3'] = $this->language->get('text_head3');
		$data['text_head4'] = $this->language->get('text_head4');
		$data['text_head5'] = $this->language->get('text_head5');
		$data['text_head6'] = $this->language->get('text_head6');
		$data['text_head7'] = $this->language->get('text_head7');
		$data['text_head8'] = $this->language->get('text_head8');
		$data['text_head9'] = $this->language->get('text_head9');
		$data['text_head10'] = $this->language->get('text_head10');
		$data['text_head11'] = $this->language->get('text_head11');
		$data['text_head12'] = $this->language->get('text_head12');
		$data['text_head13'] = $this->language->get('text_head13');
		$data['text_head14'] = $this->language->get('text_head14');
		$data['text_head15'] = $this->language->get('text_head15');
		$data['text_head16'] = $this->language->get('text_head16');
		$data['text_head17'] = $this->language->get('text_head17');
		$data['text_head18'] = $this->language->get('text_head18');
		$data['text_head19'] = $this->language->get('text_head19');
		$data['text_head20'] = $this->language->get('text_head20');
		$data['text_head21'] = $this->language->get('text_head21');
		$data['text_head22'] = $this->language->get('text_head22');
		$data['text_head23'] = $this->language->get('text_head23');
		$data['text_head24'] = $this->language->get('text_head24');
		$data['text_head25'] = $this->language->get('text_head25');
		$data['text_head26'] = $this->language->get('text_head26');
		$data['text_head27'] = $this->language->get('text_head27');
		$data['text_head28'] = $this->language->get('text_head28');
		$data['text_head29'] = $this->language->get('text_head29');
		$data['text_head30'] = $this->language->get('text_head30');
		$data['text_head31'] = $this->language->get('text_head31');
		$data['text_head32'] = $this->language->get('text_head32');
		$data['text_head33'] = $this->language->get('text_head33');
		$data['text_head34'] = $this->language->get('text_head34');
		$data['text_head35'] = $this->language->get('text_head35');
		$data['text_head36'] = $this->language->get('text_head36');
		$data['text_head37'] = $this->language->get('text_head37');
		$data['text_head38'] = $this->language->get('text_head38');
		$data['text_head39'] = $this->language->get('text_head39');
		$data['text_head40'] = $this->language->get('text_head40');
		$data['text_head41'] = $this->language->get('text_head41');
		$data['text_head42'] = $this->language->get('text_head42');
		$data['text_head43'] = $this->language->get('text_head43');
		$data['text_label'] = $this->language->get('text_label');
		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_account'] = $this->language->get('entry_account');
		$data['entry_meter'] = $this->language->get('entry_meter');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_service'] = $this->language->get('entry_service');
		$data['entry_dimension'] = $this->language->get('entry_dimension');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_length'] = $this->language->get('entry_length');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_dropoff_type'] = $this->language->get('entry_dropoff_type');
		$data['entry_packaging_type'] = $this->language->get('entry_packaging_type');
		$data['entry_rate_type'] = $this->language->get('entry_rate_type');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['help_length_class'] = $this->language->get('help_length_class');
		$data['help_display_time'] = $this->language->get('help_display_time');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_weight_class'] = $this->language->get('help_weight_class');
		$data['error_permission'] = '';
		$data['error_key'] = '';
		$data['error_password'] = '';
		$data['error_account'] = '';
		$data['error_meter'] = '';
		$data['error_postcode'] = '';
		$data['error_dimension'] = '';
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_select_all'] = 'Select All';
		$data['text_unselect_all'] = 'Deselect All';
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/hitdhlexpress', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/hitdhlexpress', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		
		if (isset($this->request->post['hitdhlexpress_test'])) {
			$data['hitdhlexpress_test'] = $this->request->post['hitdhlexpress_test'];
		} else {
			$data['hitdhlexpress_test'] = $this->config->get('hitdhlexpress_test');
		}
		
		if (isset($this->request->post['hitdhlexpress_key'])) {
			$data['hitdhlexpress_key'] = $this->request->post['hitdhlexpress_key'];
		} else {
			$data['hitdhlexpress_key'] = $this->config->get('hitdhlexpress_key');
		}

		if (isset($this->request->post['hitdhlexpress_password'])) {
			$data['hitdhlexpress_password'] = $this->request->post['hitdhlexpress_password'];
		} else {
			$data['hitdhlexpress_password'] = $this->config->get('hitdhlexpress_password');
		}

		if (isset($this->request->post['hitdhlexpress_account'])) {
			$data['hitdhlexpress_account'] = $this->request->post['hitdhlexpress_account'];
		} else {
			$data['hitdhlexpress_account'] = $this->config->get('hitdhlexpress_account');
		}
		
		if (isset($this->request->post['hitdhlexpress_status'])) {
			$data['hitdhlexpress_status'] = $this->request->post['hitdhlexpress_status'];
		} else {
			$data['hitdhlexpress_status'] = $this->config->get('hitdhlexpress_status');
		}
		
		if (isset($this->request->post['hitdhlexpress_sort_order'])) {
			$data['hitdhlexpress_sort_order'] = $this->request->post['hitdhlexpress_sort_order'];
		} else {
			$data['hitdhlexpress_sort_order'] = $this->config->get('hitdhlexpress_sort_order');
		}

		if (isset($this->request->post['hitdhlexpress_shipper_name'])) {
			$data['hitdhlexpress_shipper_name'] = $this->request->post['hitdhlexpress_shipper_name'];
		} else {
			$data['hitdhlexpress_shipper_name'] = $this->config->get('hitdhlexpress_shipper_name');
		}
		
		if (isset($this->request->post['hitdhlexpress_company_name'])) {
			$data['hitdhlexpress_company_name'] = $this->request->post['hitdhlexpress_company_name'];
		} else {
			$data['hitdhlexpress_company_name'] = $this->config->get('hitdhlexpress_company_name');
		}
		
		if (isset($this->request->post['hitdhlexpress_phone_num'])) {
			$data['hitdhlexpress_phone_num'] = $this->request->post['hitdhlexpress_phone_num'];
		} else {
			$data['hitdhlexpress_phone_num'] = $this->config->get('hitdhlexpress_phone_num');
		}
		
		if (isset($this->request->post['hitdhlexpress_email_addr'])) {
			$data['hitdhlexpress_email_addr'] = $this->request->post['hitdhlexpress_email_addr'];
		} else {
			$data['hitdhlexpress_email_addr'] = $this->config->get('hitdhlexpress_email_addr');
		}
		
		if (isset($this->request->post['hitdhlexpress_address1'])) {
			$data['hitdhlexpress_address1'] = $this->request->post['hitdhlexpress_address1'];
		} else {
			$data['hitdhlexpress_address1'] = $this->config->get('hitdhlexpress_address1');
		}
		
		if (isset($this->request->post['hitdhlexpress_address2'])) {
			$data['hitdhlexpress_address2'] = $this->request->post['hitdhlexpress_address2'];
		} else {
			$data['hitdhlexpress_address2'] = $this->config->get('hitdhlexpress_address2');
		}
		
		
		if (isset($this->request->post['hitdhlexpress_city'])) {
			$data['hitdhlexpress_city'] = $this->request->post['hitdhlexpress_city'];
		} else {
			$data['hitdhlexpress_city'] = $this->config->get('hitdhlexpress_city');
		}
		
		
		if (isset($this->request->post['hitdhlexpress_state'])) {
			$data['hitdhlexpress_state'] = $this->request->post['hitdhlexpress_state'];
		} else {
			$data['hitdhlexpress_state'] = $this->config->get('hitdhlexpress_state');
		}
		
		
		if (isset($this->request->post['hitdhlexpress_country_code'])) {
			$data['hitdhlexpress_country_code'] = $this->request->post['hitdhlexpress_country_code'];
		} else {
			$data['hitdhlexpress_country_code'] = $this->config->get('hitdhlexpress_country_code');
		}
		$data['countrylist'] = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BQ' => 'Bonaire, Saint Eustatius and Saba',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CW' => 'Curacao',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'CD' => 'Democratic Republic of the Congo',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'TL' => 'East Timor',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'CI' => 'Ivory Coast',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'XK' => 'Kosovo',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Laos',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'KP' => 'North Korea',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'CG' => 'Republic of the Congo',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russia',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SX' => 'Sint Maarten',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'KR' => 'South Korea',
			'SS' => 'South Sudan',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syria',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'VI' => 'U.S. Virgin Islands',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VA' => 'Vatican',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);

		
		if (isset($this->request->post['hitdhlexpress_postcode'])) {
			$data['hitdhlexpress_postcode'] = $this->request->post['hitdhlexpress_postcode'];
		} else {
			$data['hitdhlexpress_postcode'] = $this->config->get('hitdhlexpress_postcode');
		}
		
		if (isset($this->request->post['hitdhlexpress_realtime_rates'])) {
			$data['hitdhlexpress_realtime_rates'] = $this->request->post['hitdhlexpress_realtime_rates'];
		} else {
			$data['hitdhlexpress_realtime_rates'] = $this->config->get('hitdhlexpress_realtime_rates');
		}
		if (isset($this->request->post['hitdhlexpress_insurance'])) {
			$data['hitdhlexpress_insurance'] = $this->request->post['hitdhlexpress_insurance'];
		} else {
			$data['hitdhlexpress_insurance'] = $this->config->get('hitdhlexpress_insurance');
		}
		
		if (isset($this->request->post['hitdhlexpress_display_time'])) {
			$data['hitdhlexpress_display_time'] = $this->request->post['hitdhlexpress_display_time'];
		} else {
			$data['hitdhlexpress_display_time'] = $this->config->get('hitdhlexpress_display_time');
		}

			
		if (isset($this->request->post['hitdhlexpress_rate_type'])) {
			$data['hitdhlexpress_rate_type'] = $this->request->post['hitdhlexpress_rate_type'];
		} else {
			$data['hitdhlexpress_rate_type'] = $this->config->get('hitdhlexpress_rate_type');
		}
		
		
		if (isset($this->request->post['hitdhlexpress_service'])) {
			$data['hitdhlexpress_service'] = $this->request->post['hitdhlexpress_service'];
		} elseif ($this->config->has('hitdhlexpress_service')) {
			$data['hitdhlexpress_service'] = $this->config->get('hitdhlexpress_service');
		} else {
			$data['hitdhlexpress_service'] = array();
		}

		$data['services'] = array();

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_1'),
			'value' => '1'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_2'),
			'value' => '2'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_3'),
			'value' => '3'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_4'),
			'value' => '4'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_5'),
			'value' => '5'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_7'),
			'value' => '7'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_8'),
			'value' => '8'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_9'),
			'value' => '9'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_B'),
			'value' => 'B'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_C'),
			'value' => 'C'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_D'),
			'value' => 'D'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_E'),
			'value' => 'E'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_F'),
			'value' => 'F'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_G'),
			'value' => 'G'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_H'),
			'value' => 'H'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_I'),
			'value' => 'I'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_J'),
			'value' => 'J'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_K'),
			'value' => 'K'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_L'),
			'value' => 'L'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_M'),
			'value' => 'M'
		);

		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_N'),
			'value' => 'N'
		);
		
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_O'),
			'value' => 'O'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_P'),
			'value' => 'P'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_Q'),
			'value' => 'Q'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_R'),
			'value' => 'R'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_S'),
			'value' => 'S'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_T'),
			'value' => 'T'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_U'),
			'value' => 'U'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_V'),
			'value' => 'V'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_W'),
			'value' => 'W'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_X'),
			'value' => 'X'
		);
		$data['services'][] = array(
			'text'  => $this->language->get('text_dhl_Y'),
			'value' => 'y'
		);
		
		if (isset($this->request->post['hitdhlexpress_weight'])) {
			$data['hitdhlexpress_weight'] = $this->request->post['hitdhlexpress_weight'];
		} else {
			$data['hitdhlexpress_weight'] = $this->config->get('hitdhlexpress_weight');
		}
		
		if (isset($this->request->post['hitdhlexpress_packing_type'])) {
			$data['hitdhlexpress_packing_type'] = $this->request->post['hitdhlexpress_packing_type'];
		} else {
			$data['hitdhlexpress_packing_type'] = $this->config->get('hitdhlexpress_packing_type');
		}
		
		if (isset($this->request->post['hitdhlexpress_per_item'])) {
			$data['hitdhlexpress_per_item'] = $this->request->post['hitdhlexpress_per_item'];
		} else {
			$data['hitdhlexpress_per_item'] = $this->config->get('hitdhlexpress_per_item');
		}
		
		
			
		if (isset($this->request->post['hitdhlexpress_wight_b'])) {
			$data['hitdhlexpress_wight_b'] = $this->request->post['hitdhlexpress_wight_b'];
		} else {
			$data['hitdhlexpress_wight_b'] = $this->config->get('hitdhlexpress_wight_b');
		}
		
				
		if (isset($this->request->post['hitdhlexpress_weight_c'])) {
			$data['hitdhlexpress_weight_c'] = $this->request->post['hitdhlexpress_weight_c'];
		} else {
			$data['hitdhlexpress_weight_c'] = $this->config->get('hitdhlexpress_weight_c');
		}
		
				
		if (isset($this->request->post['hitdhlexpress_plt'])) {
			$data['hitdhlexpress_plt'] = $this->request->post['hitdhlexpress_plt'];
		} else {
			$data['hitdhlexpress_plt'] = $this->config->get('hitdhlexpress_plt');
		}
		
				
		if (isset($this->request->post['hitdhlexpress_sat'])) {
			$data['hitdhlexpress_sat'] = $this->request->post['hitdhlexpress_sat'];
		} else {
			$data['hitdhlexpress_sat'] = $this->config->get('hitdhlexpress_sat');
		}
		
				
		if (isset($this->request->post['hitdhlexpress_email_trach'])) {
			$data['hitdhlexpress_email_trach'] = $this->request->post['hitdhlexpress_email_trach'];
		} else {
			$data['hitdhlexpress_email_trach'] = $this->config->get('hitdhlexpress_email_trach');
		}
				
		if (isset($this->request->post['hitdhlexpress_airway'])) {
			$data['hitdhlexpress_airway'] = $this->request->post['hitdhlexpress_airway'];
		} else {
			$data['hitdhlexpress_airway'] = $this->config->get('hitdhlexpress_airway');
		}
				
		if (isset($this->request->post['hitdhlexpress_dropoff_type'])) {
			$data['hitdhlexpress_dropoff_type'] = $this->request->post['hitdhlexpress_dropoff_type'];
		} else {
			$data['hitdhlexpress_dropoff_type'] = $this->config->get('hitdhlexpress_dropoff_type');
		}
				
		if (isset($this->request->post['hitdhlexpress_duty_type'])) {
			$data['hitdhlexpress_duty_type'] = $this->request->post['hitdhlexpress_duty_type'];
		} else {
			$data['hitdhlexpress_duty_type'] = $this->config->get('hitdhlexpress_duty_type');
		}
		if (isset($this->request->post['hitdhlexpress_output_type'])) {
			$data['hitdhlexpress_output_type'] = $this->request->post['hitdhlexpress_output_type'];
		} else {
			$data['hitdhlexpress_output_type'] = $this->config->get('hitdhlexpress_output_type');
		}
		
		if (isset($this->request->post['hitdhlexpress_shipment_content'])) {
			$data['hitdhlexpress_shipment_content'] = $this->request->post['hitdhlexpress_shipment_content'];
		} else {
			$data['hitdhlexpress_shipment_content'] = $this->config->get('hitdhlexpress_shipment_content');
		}
		if (isset($this->request->post['hitdhlexpress_logo'])) {
			$data['hitdhlexpress_logo'] = $this->request->post['hitdhlexpress_logo'];
		} else {
			$data['hitdhlexpress_logo'] = $this->config->get('hitdhlexpress_logo');
		}
		
			if (isset($this->request->post['hitdhlexpress_picper'])) {
			$data['hitdhlexpress_picper'] = $this->request->post['hitdhlexpress_picper'];
		} else {
			$data['hitdhlexpress_picper'] = $this->config->get('hitdhlexpress_picper');
		}
			if (isset($this->request->post['hitdhlexpress_piccon'])) {
			$data['hitdhlexpress_piccon'] = $this->request->post['hitdhlexpress_piccon'];
		} else {
			$data['hitdhlexpress_piccon'] = $this->config->get('hitdhlexpress_piccon');
		}
			if (isset($this->request->post['hitdhlexpress_pickup_time'])) {
			$data['hitdhlexpress_pickup_time'] = $this->request->post['hitdhlexpress_pickup_time'];
		} else {
			$data['hitdhlexpress_pickup_time'] = $this->config->get('hitdhlexpress_pickup_time');
		}
			if (isset($this->request->post['hitdhlexpress_close_time'])) {
			$data['hitdhlexpress_close_time'] = $this->request->post['hitdhlexpress_close_time'];
		} else {
			$data['hitdhlexpress_close_time'] = $this->config->get('hitdhlexpress_close_time');
		}
		//thilak
		
		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/shipping/hitdhlexpress', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/hitdhlexpress')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['hitdhlexpress_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['hitdhlexpress_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['hitdhlexpress_account']) {
			$this->error['account'] = $this->language->get('error_account');
		}

		if (!$this->request->post['hitdhlexpress_postcode']) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		return !$this->error;
	}
}
