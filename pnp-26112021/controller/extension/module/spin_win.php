<?php
class ControllerExtensionModuleSpinWin extends Controller {
	private $error = array();

	public function index() {
		if(isset($_GET['store_id'])){
			$storeId=$_GET['store_id'];
		}else{
			$storeId=0;
		}
		$this->load->language('extension/module/spin_win');
		$this->document->setTitle($this->language->get('heading_title'));		
		//$this->document->addScript('view/javascript/admin/newspinwinup_admin.js');
		$this->load->model('setting/setting');
                //handling post values from admin end and saving it to the database
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
              
			$this->model_setting_setting->editSetting('spinwin', $this->request->post,$storeId);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/spin_win', 'token=' . $this->session->data['token'] . '&type=module&store_id='.$storeId.'', true));
		}

		
        //Fetching text from language file and saving 
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['general'] = $this->language->get('general');
		$data['display'] = $this->language->get('display');
		$data['css'] = $this->language->get('css');
		$data['show_pullout'] = $this->language->get('show_pullout');
		$data['show_pullout_descp'] = $this->language->get('show_pullout_descp');
		$data['firework'] = $this->language->get('firework');
		$data['firework_descp'] = $this->language->get('firework_descp');
		$data['discount'] = $this->language->get('discount');
		$data['marketing'] = $this->language->get('marketing');
		$data['email_sett'] = $this->language->get('email_sett');
		$data['statistic'] = $this->language->get('statistic');
		$data['text_form'] = $this->language->get('text_form');
		$data['entry_enable'] = $this->language->get('entry_enable');
		$data['entry_enable_descp'] = $this->language->get('entry_enable_descp');
		$data['recheck'] = $this->language->get('recheck');
		$data['recheck_descp'] = $this->language->get('recheck_descp');
		$data['interval'] = $this->language->get('interval');
		$data['interval_descp'] = $this->language->get('interval_descp');
		$data['check_interval'] = $this->language->get('check_interval');
		$data['expiration'] = $this->language->get('expiration');
		$data['check_expiration'] = $this->language->get('check_expiration');
		$data['custom_css'] = $this->language->get('custom_css');
		$data['custom_css_descp'] = $this->language->get('custom_css_descp');
		$data['custom_js'] = $this->language->get('custom_js');
		$data['custom_js_descp'] = $this->language->get('custom_js_descp');
		$data['warn_css'] = $this->language->get('warn_css');
		$data['warn_js'] = $this->language->get('warn_js');
		$data['days'] = $this->language->get('days');
		$data['freq'] = $this->language->get('freq');
		$data['freq_descp'] = $this->language->get('freq_descp');
		$data['everyv'] = $this->language->get('everyv');
		$data['hourv'] = $this->language->get('hourv');
		$data['dayv'] = $this->language->get('dayv');
		$data['weekv'] = $this->language->get('weekv');
		$data['monthv'] = $this->language->get('monthv');
		$data['hide'] = $this->language->get('hide');
		$data['hide_descp'] = $this->language->get('hide_descp');
		$data['always'] = $this->language->get('always');
		$data['sec10'] = $this->language->get('sec10');
		$data['sec20'] = $this->language->get('sec20');
		$data['sec30'] = $this->language->get('sec30');
		$data['sec40'] = $this->language->get('sec60');
		$data['fixtime'] = $this->language->get('fixtime');
		$data['fixtime_descp'] = $this->language->get('fixtime_descp');
		$data['fix_active'] = $this->language->get('fix_active');
		$data['fix_active_descp'] = $this->language->get('fix_active_descp');
		$data['fix_expire'] = $this->language->get('fix_expire');
		$data['fix_expire_descp'] = $this->language->get('fix_expire_descp');
		$data['smartphone']  = $this->language->get('smartphone');
		$data['tabletp']  = $this->language->get('tabletp');
		$data['tabletl']  = $this->language->get('tabletl');
		$data['laptop']  = $this->language->get('laptop');
		$data['desktop']  = $this->language->get('desktop');
		$data['only']  = $this->language->get('only');
		$data['screen']  = $this->language->get('screen');
		$data['screen_descp']  = $this->language->get('screen_descp');
		$data['where']  = $this->language->get('where');
		$data['where_descp']  = $this->language->get('where_descp');
		$data['all_page']  = $this->language->get('all_page');
		$data['selected_page']  = $this->language->get('selected_page');
		$data['no_page']  = $this->language->get('no_page');
		$data['sel_pages']  = $this->language->get('sel_pages');
		$data['who_to']  = $this->language->get('who_to');
		$data['all_visit']  = $this->language->get('all_visit');
		$data['new_visit']  = $this->language->get('new_visit');
		$data['return_visit']  = $this->language->get('return_visit');
		$data['display_image']  = $this->language->get('display_image');
		$data['display_image_descp']  = $this->language->get('display_image_descp');
		$data['image_logo']  = $this->language->get('image_logo');
		$data['image_logo_descp']  = $this->language->get('image_logo_descp');
		$data['wheel_backround']  = $this->language->get('wheel_backround');
		$data['font_color']  = $this->language->get('font_color');
		$data['font_color_descp']  = $this->language->get('font_color_descp');
		$data['font_family']  = $this->language->get('font_family');
		$data['font_family_descp']  = $this->language->get('font_family_descp');
		$data['button_backg']  = $this->language->get('button_backg');
		$data['no_lucky']  = $this->language->get('no_lucky');
		$data['discount_type']  = $this->language->get('discount_type');
		$data['discount_type_descp']  = $this->language->get('discount_type_descp');
		$data['fix_type']  = $this->language->get('fix_type');
		$data['range_type']  = $this->language->get('range_type');
		$data['per_type']  = $this->language->get('per_type');
		$data['range_type']  = $this->language->get('range_type');
		$data['range_from']  = $this->language->get('range_from');
		$data['range_to']  = $this->language->get('range_to');
		$data['enable_chimp']  = $this->language->get('enable_chimp');
		$data['enable_kalav']  = $this->language->get('enable_kalav');
		$data['enable_chimp_descp']  = $this->language->get('enable_chimp_descp');
		$data['enable_kalav_descp']  = $this->language->get('enable_kalav_descp');
		$data['chimp_api']  = $this->language->get('chimp_api');
		$data['chimp_list']  = $this->language->get('chimp_list');
		$data['kalav_api']  = $this->language->get('kalav_api');
		$data['kalav_list']  = $this->language->get('kalav_list');
		$data['display_opt']  = $this->language->get('display_opt');
		$data['subject']  = $this->language->get('subject');
		$data['content']  = $this->language->get('content');
		$data['content_descp']  = $this->language->get('content_descp');
		$data['test_email']  = $this->language->get('test_email');
		$data['test_email_descp']  = $this->language->get('test_email_descp');
		$data['popup_only']  = $this->language->get('popup_only');
		$data['email_only']  = $this->language->get('email_only');
		$data['pop_email']  = $this->language->get('pop_email');
		$data['sel_country']  = $this->language->get('sel_country');
		$data['geo_loc']  = $this->language->get('geo_loc');
		$data['geo_loc_descp']  = $this->language->get('geo_loc_descp');
		$data['world']  = $this->language->get('world');
		$data['selected_area']  = $this->language->get('selected_area');
		$data['noselected_area']  = $this->language->get('noselected_area');
		$data['coupon']  = $this->language->get('coupon');
		$data['coupon_id']  = $this->language->get('coupon_id');
		$data['c_email']  = $this->language->get('c_email');
		$data['c_country']  = $this->language->get('c_country');
		$data['c_device']  = $this->language->get('c_device');
		$data['c_date']  = $this->language->get('c_date');
		$data['no_result']  = $this->language->get('no_result');
		$data['kalav_token']  = $this->language->get('kalav_token');
		$data['var_email']  = $this->language->get('var_email');
		$data['rule_text']  = $this->language->get('rule_text');
		$data['title_text']  = $this->language->get('title_text');
		$data['subtitle_text']  = $this->language->get('subtitle_text');
		$data['rules_text']  = $this->language->get('rules_text');
		$data['title_text_descp']  = $this->language->get('title_text_descp');
		$data['subtitle_text_descp']  = $this->language->get('subtitle_text_descp');
		$data['rules_text_descp']  = $this->language->get('rules_text_descp');
		$data['theme']  = $this->language->get('theme');
		$data['theme_descp']  = $this->language->get('theme_descp');
		$data['theme0']  = $this->language->get('theme0');
		$data['theme1']  = $this->language->get('theme1');
		$data['theme2']  = $this->language->get('theme2');
		$data['wheel_design']  = $this->language->get('wheel_design');
		$data['wheel_design_descp']  = $this->language->get('wheel_design_descp');
		$data['wheel_design1']  = $this->language->get('wheel_design1');
		$data['wheel_design2']  = $this->language->get('wheel_design2');
		$data['wheel_prev']  = $this->language->get('wheel_prev');
		$data['wheel_prev_descp']  = $this->language->get('wheel_prev_descp');
		$data['used']  = $this->language->get('used');
		$data['unused']  = $this->language->get('unused');
		$data['pre_image']  = $this->language->get('pre_image');
		$data['get_list']  = $this->language->get('get_list');
		$data['popexpire']  = $this->language->get('popexpire');
		$data['gravity_error_value']  = $this->language->get('gravity_error_value');
		$data['gravity_error']  = $this->language->get('gravity_error');
		$data['coupon_error_per']  = $this->language->get('coupon_error_per');
		$data['coupon_error_num']  = $this->language->get('coupon_error_num');
		$data['when']  = $this->language->get('when');
		$data['when_desc']  = $this->language->get('when_desc');
		$data['when_scroll']  = $this->language->get('when_scroll');
		$data['when_time']  = $this->language->get('when_time');
		$data['when_immediately']  = $this->language->get('when_immediately');
		$data['when_exit']  = $this->language->get('when_exit');
		$data['when_1_time']  = $this->language->get('when-1-time');
		$data['when_1_time_desc']  = $this->language->get('when-1-time-desc');
		$data['when_2_scroll']  = $this->language->get('when-2-scroll');
		$data['when_2_scroll_desc']  = $this->language->get('when-2-scroll-desc');
		$data['when_2_scroll_msg']  = $this->language->get('when-2-scroll-msg');

		$data['sno']  = $this->language->get('sno');
		$data['c_type']  = $this->language->get('c_type');
		$data['label']  = $this->language->get('label');
		$data['label_descp']  = $this->language->get('label_descp');
		$data['value1']  = $this->language->get('value');
		$data['gravity']  = $this->language->get('gravity');
		$data['gravity_descp']  = $this->language->get('gravity_descp');
		$data['edit']  = $this->language->get('edit');
		$data['offer_upd']  = $this->language->get('offer_upd');
		 //Validation
        $data['required']  = $this->language->get('required');
        $data['remote']  = $this->language->get('remote');
        $data['email']  = $this->language->get('email');
        $data['url']  = $this->language->get('url');
        $data['date']  = $this->language->get('date');
        $data['dateISO']  = $this->language->get('dateISO');
        $data['number']  = $this->language->get('number');
        $data['digits']  = $this->language->get('digits');
        $data['creditcard']  = $this->language->get('creditcard');
        $data['equalTo']  = $this->language->get('equalTo');
        $data['maxlength']  = $this->language->get('maxlength');
        $data['minlength']  = $this->language->get('minlength');
        $data['rangelength']  = $this->language->get('rangelength');
        $data['range']  = $this->language->get('range');
        $data['max']  = $this->language->get('max');
        $data['min']  = $this->language->get('min');
        $data['mandatory']  = $this->language->get('mandatory');
        $data['mand_success']  = $this->language->get('mand_success');
         $data['send_email']  = $this->language->get('send_email');

		$data['mandatory']  = $this->language->get('mandatory');
		$data['price']  = $this->language->get('price');
		$data['decimalNotRequired']  = $this->language->get('decimalNotRequired');
		$data['email']  = $this->language->get('email');
		$data['passwd']  = $this->language->get('passwd');
		$data['notRequiredPasswd']  = $this->language->get('notRequiredPasswd');
		$data['mobile']  = $this->language->get('mobile');
		$data['addressLine1']  = $this->language->get('addressLine1');
		$data['addressLine2']  = $this->language->get('addressLine2');
		$data['digit']  = $this->language->get('digit');
		$data['notRequiredDigit']  = $this->language->get('notRequiredDigit');
		$data['firstname']  = $this->language->get('firstname');
		$data['lastname']  = $this->language->get('lastname');
		$data['middlename']  = $this->language->get('middlename');
		$data['requiredMin2Max60NoSpecial']  = $this->language->get('requiredMin2Max60NoSpecial');
		$data['requiredip']  = $this->language->get('requiredip');
		$data['optionalip']  = $this->language->get('optionalip');
		$data['requiredimage']  = $this->language->get('requiredimage');
		$data['optionalimage']  = $this->language->get('optionalimage');
		$data['requiredcharonly']  = $this->language->get('requiredcharonly');
		$data['optionalcharonly']  = $this->language->get('optionalcharonly');
		$data['barcode']  = $this->language->get('barcode');
		$data['ean']  = $this->language->get('ean');
		$data['upc']  = $this->language->get('upc');
		$data['size']  = $this->language->get('size');
		$data['requiredurl']  = $this->language->get('requiredurl');
		$data['optionalurl']  = $this->language->get('optionalurl');
		$data['carrier']  = $this->language->get('carrier');
		$data['brand']  = $this->language->get('brand');
		$data['optionalcompany']  = $this->language->get('optionalcompany');
		$data['requiredcompany']  = $this->language->get('requiredcompany');
		$data['sku']  = $this->language->get('sku');
		$data['requiredmmddyy']  = $this->language->get('requiredmmddyy');
		$data['optionalmmddyy']  = $this->language->get('optionalmmddyy');
		$data['requiredddmmyy']  = $this->language->get('requiredddmmyy');
		$data['optionalddmmyy']  = $this->language->get('optionalddmmyy');
		$data['optionalpercentage']  = $this->language->get('optionalpercentage');
		$data['requiredpercentage']  = $this->language->get('requiredpercentage');
		$data['checktags']  = $this->language->get('checktags');
		$data['checkhtmltags']  = $this->language->get('checkhtmltags');
		$data['requireddocs']  = $this->language->get('requireddocs');
		$data['optionaldocs']  = $this->language->get('optionaldocs');
		$data['requiredcolor']  = $this->language->get('requiredcolor');
		$data['optionalcolor']  = $this->language->get('optionalcolor');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

        //Warning error display
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

        //Getting All the layouts of website
		$this->load->model('design/layout');
		$data['layouts'] = array();

		$filter_data = array(
			'sort'  =>'name',
			'order' => 'ASC',
			'start' => (1 - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);		

		$results = $this->model_design_layout->getLayouts($filter_data);

		foreach ($results as $result) {
			$data['layouts'][] = array(
				'layout_id' => $result['layout_id'],
				'name'      => $result['name'],
				'edit'      => ''
			);
		}
                
		$data['latest_theme'] = array(
                    'chocolate_day' => 'Chocolate Day Theme',
                    'hug_day'  => 'Hug Day Theme',
                    'kiss_day'  => 'Kiss Day Theme',
                    'promise_day'  => 'Promise Day Theme',
                    'propose_day' => 'Propose Day Theme',
                    'rose_day' => 'Rose Day Theme',
                    'teddy_day' => 'Teddy Day Theme',
                    'valentine_day' => 'Valentine Day Theme',
                    4 => 'Halloween Theme 1',
                    5 => 'Halloween Theme 2',
                    6 => 'Halloween Theme 3',
                    7 => 'Halloween Theme 4',
                    8 => 'Black Friday Theme 1',
                    9 => 'Black Friday Theme 2',
                    10 => 'Black Friday Theme 3',
                    11 => 'Black Friday Theme 4',
                    12 => 'Thanks Giving Theme 1',
                    13 => 'Thanks Giving Theme 2',
                    14 => 'Thanks Giving Theme 3',
                    15 => 'Thanks Giving Theme 4',
                    16 => 'Easter Theme 1',
                    17 => 'Easter Theme 2',
                    18 => 'Easter Theme 3',
                    19 => 'Diwali Theme 1',
                    20 => 'Diwali Theme 2',
                    21 => 'Diwali Theme 3',
                    22 => 'Diwali Theme 4',
                    23 => 'Diwali Theme 5',
//                    24 => 'Football Generic Theme1',
//                    25 => 'Football Generic Theme2'
                );
                
		//Getting countries list
		$this->load->model('localisation/country');
		$data['countries'] = array();
		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => (1 - 1) * $this->config->get('config_limit_admin'),
			'limit' => '500'
		);

		$results = $this->model_localisation_country->getCountries($filter_data);

		foreach ($results as $result) {
			$data['countries'][] = array(
				'country_id' => $result['country_id'],
				'name'       => $result['name'] . (($result['country_id'] == $this->config->get('config_country_id')) ? $this->language->get('text_default') : null)
				
			);
		}

		//Getting the store list

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name') . $this->language->get('text_default'),
			'url'      => HTTP_CATALOG,
			'edit'     => $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true)
		);
		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name'],
				'url'      => $result['url'],
				'edit'     => $this->url->link('setting/store/edit', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], true)
			);
		}

		//Getting the language list from oc_language
		$this->load->model('localisation/language');
		$data['languages'] = array();

		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => (1 - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_localisation_language->getLanguages($filter_data);
		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				'sort_order'  => $result['sort_order']
			);
		}
		$this->load->model('extension/spin_win');

		//Getting the coupon data with pagination	

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';		
		$page_limit= $this->config->get('config_limit_admin');
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['coupon_data'] = array();

		$filter_data = array(
			'start' => ($page - 1) * $page_limit,
			'limit' => $page_limit 
		);
		//print_r($filter_data); die;

		$coupon_total = $this->model_extension_spin_win->getTotalCoupon($storeId);

		$results = $this->model_extension_spin_win->getCoupons($filter_data,$storeId);

		foreach ($results as $result) {
			$data['coupon_data'][] = array(
				'coupon' => $result['coupon'],
				'email'        => $result['email'],
				'country'  => $result['country'],
				'device'  => $result['device'],
				'added_date'  => $result['added_date']
			);
		}

		$pagination = new Pagination();
		$pagination->total = $coupon_total;
		$pagination->page = $page;
		$pagination->limit = $page_limit;
		$pagination->url = $this->url->link('extension/module/spin_win', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $page_limit) + 1 : 0, ((($page - 1) * $page_limit) > ($coupon_total - $page_limit)) ? $coupon_total : ((($page - 1) * $page_limit) + $page_limit), $coupon_total, ceil($coupon_total / $page_limit));

		
		$data['offer_data']=$this->model_extension_spin_win->getOffer();
		$getSetting=$this->model_setting_setting->getSetting('spinwin',$storeId);
		// echo "<pre>";
		// print_r($getSetting); die;
         //Form Action for module
		$data['action'] = $this->url->link('extension/module/spin_win', 'token=' . $this->session->data['token'].'&store_id='.$storeId.'', true);
                //Cancel Action for module at the admin end
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module&store_id='.$storeId.'', true);
                //Fetching saved module settting from database
		if (isset($this->request->post['spinwin_enable'])) {
			$data['spinwin_enable'] = $this->request->post['spinwin_enable'];
		} else if(isset($getSetting['spinwin_enable'])){
			$data['spinwin_enable'] = $getSetting['spinwin_enable'];
		}else{
			$data['spinwin_enable'] = '0';
		}
		if (isset($this->request->post['spinwin_recheck'])) {
			$data['spinwin_recheck'] = $this->request->post['spinwin_recheck'];
		} elseif(isset($getSetting['spinwin_recheck'])) {
			$data['spinwin_recheck'] = $getSetting['spinwin_recheck'];
		}else{
			$data['spinwin_recheck'] = '0';
		}

		if (isset($this->request->post['spinwin_show_pullout'])) {
			$data['spinwin_show_pullout'] = $this->request->post['spinwin_show_pullout'];
		} elseif(isset($getSetting['spinwin_show_pullout'])) {
			$data['spinwin_show_pullout'] = $getSetting['spinwin_show_pullout'];
		}else{
			$data['spinwin_show_pullout'] = '0';
		}

		if (isset($this->request->post['spinwin_show_firework'])) {
			$data['spinwin_show_firework'] = $this->request->post['spinwin_show_firework'];
		} elseif(isset($getSetting['spinwin_show_firework'])) {
			$data['spinwin_show_firework'] = $getSetting['spinwin_show_firework'];
		}else{
			$data['spinwin_show_firework'] = '0';
		}

		if (isset($this->request->post['spinwin_css'])) {
			$data['spinwin_css'] = $this->request->post['spinwin_css'];
		} elseif(isset($getSetting['spinwin_css'])) {
			$data['spinwin_css'] = $getSetting['spinwin_css'];
		}else{
			$data['spinwin_css'] = '';
		}
		if (isset($this->request->post['spinwin_js'])) {
			$data['spinwin_js'] = $this->request->post['spinwin_js'];
		} elseif(isset($getSetting['spinwin_js'])) {
			$data['spinwin_js'] = $getSetting['spinwin_js'];
		}else{
			$data['spinwin_js'] = '';
		}

		if (isset($this->request->post['spinwin_interval'])) {
			$data['spinwin_interval'] = $this->request->post['spinwin_interval'];
		} elseif(isset($getSetting['spinwin_interval'])) {
			$data['spinwin_interval'] = $getSetting['spinwin_interval'];
		}else{
			$data['spinwin_interval'] = '1';
		}

		if (isset($this->request->post['spinwin_expiration'])) {
			$data['spinwin_expiration'] = $this->request->post['spinwin_expiration'];
		} elseif(isset($getSetting['spinwin_expiration'])) {
			$data['spinwin_expiration'] = $getSetting['spinwin_expiration'];
		}else{
			$data['spinwin_expiration'] = '1';
		}

		if (isset($this->request->post['spinwin_freq'])) {
			$data['spinwin_freq'] = $this->request->post['spinwin_freq'];
		} elseif($this->config->get('spinwin_freq')) {
			$data['spinwin_freq'] = $this->config->get('spinwin_freq');
		}else {
			$data['spinwin_freq'] = '0';
		}

		if (isset($this->request->post['spinwin_hide'])) {
			$data['spinwin_hide'] = $this->request->post['spinwin_hide'];
		} elseif(isset($getSetting['spinwin_hide'])) {
			$data['spinwin_hide'] = $getSetting['spinwin_hide'];
		}else {
			$data['spinwin_hide'] = '0';
		}

		if (isset($this->request->post['spinwin_fixtime'])) {
			$data['spinwin_fixtime'] = $this->request->post['spinwin_fixtime'];
		} elseif(isset($getSetting['spinwin_fixtime'])) {
			$data['spinwin_fixtime'] = $getSetting['spinwin_fixtime'];
		}else {
			$data['spinwin_fixtime'] = '0';
		}
		if (isset($this->request->post['spinwin_fixtimeactive'])) {
			$data['spinwin_fixtimeactive'] = $this->request->post['spinwin_fixtimeactive'];
		} elseif(isset($getSetting['spinwin_fixtimeactive'])) {
			$data['spinwin_fixtimeactive'] = $getSetting['spinwin_fixtimeactive'];
		}else {
			$data['spinwin_fixtimeactive'] = '';
		}

		if (isset($this->request->post['spinwin_fixtimeexpire'])) {
			$data['spinwin_fixtimeexpire'] = $this->request->post['spinwin_fixtimeexpire'];
		} elseif(isset($getSetting['spinwin_fixtimeexpire'])) {
			$data['spinwin_fixtimeexpire'] = $getSetting['spinwin_fixtimeexpire'];
		}else {
			$data['spinwin_fixtimeexpire'] = '';
		}
		
		if (isset($this->request->post['spinwin_imagedisplay'])) {
			$data['spinwin_imagedisplay'] = $this->request->post['spinwin_imagedisplay'];
		} elseif(isset($getSetting['spinwin_imagedisplay'])) {
			$data['spinwin_imagedisplay'] = $getSetting['spinwin_imagedisplay'];
		}else {
			$data['spinwin_imagedisplay'] = '0';
		}
		
		if (isset($this->request->post['spinwin_logo'])) {
			$data['spinwin_logo'] = $this->request->post['spinwin_logo'];
		} elseif(isset($getSetting['spinwin_logo'])) {
			$data['spinwin_logo'] = $getSetting['spinwin_logo'];
		}else {
			$data['spinwin_logo'] = '';
		}
		
		if (isset($this->request->post['spinwin_wheel_background'])) {
			$data['spinwin_wheel_background'] = $this->request->post['spinwin_wheel_background'];
		} elseif(isset($getSetting['spinwin_wheel_background'])) {
			$data['spinwin_wheel_background'] = $getSetting['spinwin_wheel_background'];
		}else {
			$data['spinwin_wheel_background'] = '0';
		}

		if (isset($this->request->post['spinwin_font_color'])) {
			$data['spinwin_font_color'] = $this->request->post['spinwin_font_color'];
		} elseif(isset($getSetting['spinwin_font_color'])) {
			$data['spinwin_font_color'] = $getSetting['spinwin_font_color'];
		}else {
			$data['spinwin_font_color'] = '0';
		}
		
                if (isset($this->request->post['spinwin_font_family'])) {
			$data['spinwin_font_family'] = $this->request->post['spinwin_font_family'];
		} elseif(isset($getSetting['spinwin_font_family'])) {
			$data['spinwin_font_family'] = $getSetting['spinwin_font_family'];
		}else {
			$data['spinwin_font_family'] = 'inherit';
		}

		if (isset($this->request->post['spinwin_button_background'])) {
			$data['spinwin_popup_title'] = $this->request->post['spinwin_button_background'];
		} elseif(isset($getSetting['spinwin_button_background'])) {
			$data['spinwin_button_background'] = $getSetting['spinwin_button_background'];
		}else {
			$data['spinwin_button_background'] = '0';
		}

		if (isset($this->request->post['spinwin_no_lucky'])) {
			$data['spinwin_popup_title'] = $this->request->post['spinwin_no_lucky'];
		} elseif(isset($getSetting['spinwin_no_lucky'])) {
			$data['spinwin_no_lucky'] = $getSetting['spinwin_no_lucky'];
		}else {
			$data['spinwin_no_lucky'] = '0';
		}

		if (isset($this->request->post['spinwin_title_text'])) {
			$data['spinwin_title_text'] = $this->request->post['spinwin_title_text'];
		} elseif(isset($getSetting['spinwin_title_text'])) {
			$data['spinwin_title_text'] = $getSetting['spinwin_title_text'];
		}else {
			$data['spinwin_title_text'] = '';
		}

		if (isset($this->request->post['spinwin_subtitle_text'])) {
			$data['spinwin_subtitle_text'] = $this->request->post['spinwin_subtitle_text'];
		} elseif(isset($getSetting['spinwin_subtitle_text'])) {
			$data['spinwin_subtitle_text'] = $getSetting['spinwin_subtitle_text'];
		}else {
			$data['spinwin_subtitle_text'] = '';
		}
                
                if (isset($this->request->post['spinwin_when_display'])) {
			$data['spinwin_when_display'] = $this->request->post['spinwin_when_display'];
		} elseif(isset($getSetting['spinwin_when_display'])) {
			$data['spinwin_when_display'] = $getSetting['spinwin_when_display'];
		}else {
			$data['spinwin_when_display'] = '';
		}
                
                if (isset($this->request->post['spinwin_when_time'])) {
			$data['spinwin_when_time'] = $this->request->post['spinwin_when_time'];
		} elseif(isset($getSetting['spinwin_when_time'])) {
			$data['spinwin_when_time'] = $getSetting['spinwin_when_time'];
		}else{
			$data['spinwin_when_time'] = '10';
		}
                
                if (isset($this->request->post['spinwin_when_scroll'])) {
			$data['spinwin_when_scroll'] = $this->request->post['spinwin_when_scroll'];
		} elseif(isset($getSetting['spinwin_when_scroll'])) {
			$data['spinwin_when_scroll'] = $getSetting['spinwin_when_scroll'];
		}else{
			$data['spinwin_when_scroll'] = '10';
		}
                
		if (isset($this->request->post['spinwin_rules_text'])) {
			$data['spinwin_rules_text'] = $this->request->post['spinwin_rules_text'];
		} elseif(isset($getSetting['spinwin_rules_text'])) {
			$data['spinwin_rules_text'] = $getSetting['spinwin_rules_text'];
		}else {
			$data['spinwin_rules_text'] = '';
		}


		if (isset($this->request->post['spinwin_discount_type'])) {
			$data['spinwin_discount_type'] = $this->request->post['spinwin_discount_type'];
		} elseif(isset($getSetting['spinwin_discount_type'])) {
			$data['spinwin_discount_type'] = $getSetting['spinwin_discount_type'];
		}else {
			$data['spinwin_discount_type'] = '0';
		}

		if (isset($this->request->post['spinwin_discount_pertype'])) {
			$data['spinwin_discount_pertype'] = $this->request->post['spinwin_discount_pertype'];
		} elseif(isset($getSetting['spinwin_discount_pertype'])) {
			$data['spinwin_discount_pertype'] = $getSetting['spinwin_discount_pertype'];
		}else {
			$data['spinwin_discount_pertype'] = '0';
		}

		if (isset($this->request->post['spinwin_discount_rangefrom'])) {
			$data['spinwin_discount_rangefrom'] = $this->request->post['spinwin_discount_rangefrom'];
		} elseif(isset($getSetting['spinwin_discount_rangefrom'])) {
			$data['spinwin_discount_rangefrom'] = $getSetting['spinwin_discount_rangefrom'];
		}else {
			$data['spinwin_discount_rangefrom'] = '0';
		}

		if (isset($this->request->post['spinwin_discount_rangeto'])) {
			$data['spinwin_discount_rangeto'] = $this->request->post['spinwin_discount_rangeto'];
		} elseif(isset($getSetting['spinwin_discount_rangeto'])) {
			$data['spinwin_discount_rangeto'] = $getSetting['spinwin_discount_rangeto'];
		}else {
			$data['spinwin_discount_rangeto'] = '0';
		}

		if (isset($this->request->post['spinwin_discount_fixtype'])) {
			$data['spinwin_discount_fixtype'] = $this->request->post['spinwin_discount_fixtype'];
		} elseif(isset($getSetting['spinwin_discount_fixtype'])) {
			$data['spinwin_discount_fixtype'] = $getSetting['spinwin_discount_fixtype'];
		}else {
			$data['spinwin_discount_fixtype'] = '0';
		}

		if (isset($this->request->post['spinwin_enable_chimp'])) {
			$data['spinwin_enable_chimp'] = $this->request->post['spinwin_enable_chimp'];
		} elseif(isset($getSetting['spinwin_enable_chimp'])) {
			$data['spinwin_enable_chimp'] = $getSetting['spinwin_enable_chimp'];
		}else {
			$data['spinwin_enable_chimp'] = '0';
		}

		if (isset($this->request->post['spinwin_chimp_api'])) {
			$data['spinwin_chimp_api'] = $this->request->post['spinwin_chimp_api'];
			$mca_api_key=$this->request->post['spinwin_chimp_api'];			
			if($mca_api_key!=''){
				$mailchimp = $this->getMailchimp($mca_api_key);
				$data['chimp_list1']=getLists($mailchimp);
			}
		} elseif(isset($getSetting['spinwin_chimp_api'])) {
			$data['spinwin_chimp_api'] = $getSetting['spinwin_chimp_api'];
			$mca_api_key=$getSetting['spinwin_chimp_api'];
			if($mca_api_key!=''){
				$mailchimp = $this->getMailchimp($mca_api_key);
				$data['chimp_list1']=getLists($mailchimp);
			}
		}else {
			$data['spinwin_chimp_api'] = '';
		}

		if (isset($this->request->post['spinwin_chimp_list'])) {
			$data['spinwin_chimp_list'] = $this->request->post['spinwin_chimp_list'];
		} elseif(isset($getSetting['spinwin_chimp_list'])) {
			$data['spinwin_chimp_list'] = $getSetting['spinwin_chimp_list'];
		}else {
			$data['spinwin_chimp_list'] = '';
		}

		if (isset($this->request->post['spinwin_enable_kalav'])) {
			$data['spinwin_enable_kalav'] = $this->request->post['spinwin_enable_kalav'];
		} elseif(isset($getSetting['spinwin_enable_kalav'])) {
			$data['spinwin_enable_kalav'] = $getSetting['spinwin_enable_kalav'];
		}else {
			$data['spinwin_enable_kalav'] = '0';
		}

		if (isset($this->request->post['spinwin_kalav_api'])) {
			$data['spinwin_kalav_api'] = $this->request->post['spinwin_kalav_api'];
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_URL,'https://api.constantcontact.com/v2/lists?api_key='.$this->request->post['spinwin_kalav_api'].'&access_token='.$this->request->post['spinwin_kalav_token'].'');
		    $result=curl_exec($ch);
		    curl_close($ch);
		    $data['kalaviyo_api']=json_decode($result, true);
		} elseif(isset($getSetting['spinwin_kalav_api'])) {
			$data['spinwin_kalav_api'] = $getSetting['spinwin_kalav_api'];
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($ch, CURLOPT_URL,'https://api.constantcontact.com/v2/lists?api_key='.$getSetting['spinwin_kalav_api'].'&access_token='.$getSetting['spinwin_kalav_token'].'');
		    $result=curl_exec($ch);
		    curl_close($ch);
		    $data['kalaviyo_api']=json_decode($result, true);
		}else {
			$data['spinwin_kalav_api'] = '';
		}

		if (isset($this->request->post['spinwin_kalav_list'])) {
			$data['spinwin_kalav_list'] = $this->request->post['spinwin_kalav_list'];
		} elseif(isset($getSetting['spinwin_kalav_list'])) {
			$data['spinwin_kalav_list'] = $getSetting['spinwin_kalav_list'];
		}else {
			$data['spinwin_kalav_list'] = '';
		}

		if (isset($this->request->post['spinwin_email_setting'])) {
			$data['spinwin_email_setting'] = $this->request->post['spinwin_email_setting'];
		} elseif(isset($getSetting['spinwin_email_setting'])) {
			$data['spinwin_email_setting'] = $getSetting['spinwin_email_setting'];
		}else {
			$data['spinwin_email_setting'] = '';
		}

		if (isset($this->request->post['spinwin_email_subject'])) {
			$data['spinwin_email_subject'] = $this->request->post['spinwin_email_subject'];
		} elseif(isset($getSetting['spinwin_email_subject'])) {
			$data['spinwin_email_subject'] = $getSetting['spinwin_email_subject'];
		}else {
			$data['spinwin_email_subject'] = '';
		}

		if (isset($this->request->post['spinwin_emailer_content'])) {
			$data['spinwin_emailer_content'] = $this->request->post['spinwin_emailer_content'];
		} elseif(isset($getSetting['spinwin_emailer_content'])) {
			$data['spinwin_emailer_content'] = $getSetting['spinwin_emailer_content'];
		}else {
			$data['spinwin_emailer_content'] = '<p></p>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ECF0F1" background="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/minimal6.png" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<tbody>
<tr>
<td class="mlTemplateContainer" align="center">
<table align="center" border="0" class="mlEmailContainer" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
<tbody>
<tr>
<td align="center"><!--<![endif]--> <!-- Content starts here -->
<table width="640" class="mlContentTable" cellspacing="0" cellpadding="0" border="0" align="center">
<tbody>
<tr>
<td height="30"></td>
</tr>
</tbody>
</table>
<table align="center" width="640" class="mlContentTable" cellpadding="0" cellspacing="0" border="0" style="min-width: 640px; width: 640px;">
<tbody>
<tr>
<td class="mlContentTable"></td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#8b67bb" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #8b67bb; min-width: 640px; width: 640px;" width="640" id="ml-block-55149655">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#8b67bb" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #8b67bb; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 0px 50px 0px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;">
<p style="margin: 0px 0px 10px 0px; line-height: 23px; text-align: center;"></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#8b67bb" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #8b67bb; min-width: 640px; width: 640px;" width="640" id="ml-block-55149967">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#8b67bb" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #8b67bb; width: 640px;">
<tbody>
<tr>
<td align="center" class="mlContentContainer mlContentImage mlContentHeight" style="padding: 0px 50px 10px 50px;"><img border="0" src="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/ICON.png" width="99" height="99" class="mlContentImage" style="display: block; max-width: 99px;"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#8b67bb" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #8b67bb; min-width: 640px; width: 640px;" width="640" id="ml-block-55150241">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#8b67bb" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #8b67bb; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 15px; color: #ffffff; line-height: 25px;">
<p style="margin: 0px 0px 10px 0px; line-height: 25px; text-align: center;"><strong>You just earned a discount of {amount} on your next purchase!</strong></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55150427">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;">
<h2 style="line-height: 26px; text-decoration: none; font-weight: bold; margin: 0px 0px 10px 0px; font-family: Helvetica; font-size: 20px; color: #000000; text-align: left;">Hi,</h2>
<p style="margin: 0px 0px 10px 0px; line-height: 23px;">Congratulations and Thank You for registering with us. To help you celebrate, heres a coupon you can put towards your next purchase with us.</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55150501">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td class="mlContentContainer" style="padding: 15px 50px 0px 50px;">
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid #d8d8d8;">
<tbody>
<tr>
<td width="100%" height="15px"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55148241">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 0px 50px 0px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;">
<p style="margin: 0px 0px 10px 0px; line-height: 23px; text-align: center;"><strong>Coupon Code:</strong></p>
<p style="margin: 0px 0px 20px 0px; line-height: 23px; text-align: center; font-size: 35px; color: #000;">{coupon_code}</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55148245">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td class="mlContentContainer" style="padding: 0px 50px 0px 50px;">
<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: 1px solid #d8d8d8;">
<tbody>
<tr>
<td width="100%" height="11px"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55150703">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;">
<p style="margin: 0px 0px 10px 0px; line-height: 23px;">Please feel free to email us with any questions. If you prefer to call with any questions or requests we can be reached at: +xx-xxx xxx xxxx</p>
<p style="margin: 0px 0px 10px 0px; line-height: 23px;">Best regards <br> <br> Name <br> Designation <br> www.yoursite.com<br> Ph: +xx-xxx xxx xxxx<br> Email: support mail</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#8b67bb" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #8b67bb; min-width: 640px; width: 640px;" width="640" id="ml-block-55148251">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#8b67bb" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #8b67bb; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;">
<p style="margin: 0px 0px 10px 0px; line-height: 23px; text-align: center;"><span style="font-size: 20px;"><a href="#" style="color: #ffffff; text-decoration: none;">Happy Shopping!</a></span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55148239">
<tbody>
<tr>
<td></td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55153137">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td>
<div style="text-align: center;" class="html-content">
<ul style="display: inline-block; text-align: center; list-style-type: none;">
<li style="display: inline-block;"><a href="#"> <img src="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/FB.png"> </a></li>
<li style="display: inline-block;"><a href="#"> <img src="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/TUMBLER.png"> </a></li>
<li style="display: inline-block;"><a href="#"> <img src="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/PINTEREST.png"> </a></li>
<li style="display: inline-block;"><a href="#"> <img src="https://ps.knowband.com/demo6/16/modules/spinwheel/views/img/admin/email/TWITTER.png"> </a></li>
</ul>
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table align="center" border="0" bgcolor="#FFFFFF" class="mlContentTable" cellspacing="0" cellpadding="0" style="background: #FFFFFF; min-width: 640px; width: 640px;" width="640" id="ml-block-55152047">
<tbody>
<tr>
<td>
<table width="640" class="mlContentTable" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #FFFFFF; width: 640px;">
<tbody>
<tr>
<td align="left" class="mlContentContainer" style="padding: 15px 50px 5px 50px; font-family: Helvetica; font-size: 14px; color: #7f8c8d; line-height: 23px;"></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table width="640" class="mlContentTable" cellspacing="0" cellpadding="0" border="0" align="center" style="min-width: 640px; width: 640px;">
<tbody>
<tr>
<td height="30"></td>
</tr>
</tbody>
</table>
<!-- Content ends here --> <!-- [if !mso]><!-- --></td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<!--<![endif]-->';
		}

		if (isset($this->request->post['spinwin_geo_location'])) {
			$data['spinwin_geo_location'] = $this->request->post['spinwin_geo_location'];
		} elseif(isset($getSetting['spinwin_geo_location'])) {
			$data['spinwin_geo_location'] = $getSetting['spinwin_geo_location'];
		}else {
			$data['spinwin_geo_location'] = '';
		}

		if (isset($this->request->post['spinwin_selectcountry'])) {
			$data['spinwin_selectcountry'] = $this->request->post['spinwin_selectcountry'];
		} elseif(isset($getSetting['spinwin_selectcountry'])) {
			$data['spinwin_selectcountry'] = $getSetting['spinwin_selectcountry'];
		}else {
			$data['spinwin_selectcountry'] = array(0=>"india");
		}

		if (isset($this->request->post['spinwin_screen'])) {
			$data['spinwin_screen'] = $this->request->post['spinwin_screen'];
		} elseif(isset($getSetting['spinwin_screen'])) {
			$data['spinwin_screen'] = $getSetting['spinwin_screen'];
		}else {
			$data['spinwin_screen'] = '';
		}

		if (isset($this->request->post['spinwin_where_display'])) {
			$data['spinwin_where_display'] = $this->request->post['spinwin_where_display'];
		} elseif(isset($getSetting['spinwin_where_display'])) {
			$data['spinwin_where_display'] = $getSetting['spinwin_where_display'];
		}else {
			$data['spinwin_where_display'] = '';
		}

		if (isset($this->request->post['spinwin_visit'])) {
			$data['spinwin_visit'] = $this->request->post['spinwin_visit'];
		} elseif(isset($getSetting['spinwin_visit'])) {
			$data['spinwin_visit'] = $getSetting['spinwin_visit'];
		}else {
			$data['spinwin_visit'] = '';
		}

		if (isset($this->request->post['spinwin_selectpages'])) {
			$data['spinwin_selectpages'] = $this->request->post['spinwin_selectpages'];
		} elseif(isset($getSetting['spinwin_selectpages'])) {
			$data['spinwin_selectpages'] = $getSetting['spinwin_selectpages'];
		}else {
			$data['spinwin_selectpages'] = array(0=>'Home');
		}

		if (isset($this->request->post['spinwin_test_email'])) {
			$data['spinwin_test_email'] = $this->request->post['spinwin_test_email'];
		} elseif(isset($getSetting['spinwin_test_email'])) {
			$data['spinwin_test_email'] = $getSetting['spinwin_test_email'];
		}else {
			$data['spinwin_test_email'] = '';
		}

		if (isset($this->request->post['spinwin_chimp_list'])) {
			$data['spinwin_chimp_list'] = $this->request->post['spinwin_chimp_list'];
			
		} elseif(isset($getSetting['spinwin_chimp_list'])) {
			$data['spinwin_chimp_list'] = $getSetting['spinwin_chimp_list'];
		}else {
			$data['spinwin_chimp_list'] = '';
		}
		if (isset($this->request->post['spinwin_kalav_token'])) {
			$data['spinwin_kalav_token'] = $this->request->post['spinwin_kalav_token'];
			
		} elseif(isset($getSetting['spinwin_kalav_token'])) {
			$data['spinwin_kalav_token'] = $getSetting['spinwin_kalav_token'];
		}else {
			$data['spinwin_kalav_token'] = '';
		}	

		if (isset($this->request->post['spinwin_theme'])) {
			$data['spinwin_theme'] = $this->request->post['spinwin_theme'];
			
		} elseif(isset($getSetting['spinwin_theme'])) {
			$data['spinwin_theme'] = $getSetting['spinwin_theme'];
		}else {
			$data['spinwin_theme'] = '';
		}	

		if (isset($this->request->post['spinwin_wheel_design'])) {
			$data['spinwin_wheel_design'] = $this->request->post['spinwin_wheel_design'];
			
		} elseif(isset($getSetting['spinwin_wheel_design'])) {
			$data['spinwin_wheel_design'] = $getSetting['spinwin_wheel_design'];
		}else {
			$data['spinwin_wheel_design'] = '';
		}	

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/spin_win', $data));
	}

	public function install() {
		$this->load->model('extension/spin_win');
		$this->model_extension_spin_win->install();
	}

	public function uninstall(){
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('spin_win');
		$this->load->model('extension/spin_win');
		$this->model_extension_spin_win->uninstall();
	}

	public function getMailchimp($mca_api_key) {
		//echo DIR_SYSTEM . 'library/mailchimp-api/index.php'; die;
        include(DIR_SYSTEM . 'library/mailchimp-api/index.php');
        $mailchimp = initilizeMailchimp($mca_api_key);
        return $mailchimp;
	}

	public function test_mail(){
		extract($this->request->post);
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($this->request->post['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($this->request->post['content'], ENT_QUOTES, 'UTF-8'));
		$mail->send();
		$output=json_encode(array('type'=>'Mail sent successfully'));
		die($output);
	}

	protected function validate() {

		if (!$this->user->hasPermission('modify', 'extension/module/spin_win')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if(isset($this->request->post['spinwin_imagedisplay']) && $this->request->post['spinwin_imagedisplay']==1){
            if (empty($this->request->post['spinwin_logo'])) {                    
				$this->error['warning'] = sprintf($this->language->get('error_image'));
			}
			}

		if (isset($this->request->post['spinwin_fixtimeactive']) && isset($this->request->post['spinwin_fixtimeexpire'])) { 
			 if (strtotime($this->request->post['spinwin_fixtimeactive']) > strtotime($this->request->post['spinwin_fixtimeexpire'])){
				$this->error['warning'] = sprintf($this->language->get('active_expire'));
				$this->error['popexpire'] = sprintf($this->language->get('popexpire'));
		}
				
		}
               
		return !$this->error;
	}

	public function chart() {
		$this->load->language('extension/module/spin_win');
		$json = array();

		$this->load->model('extension/spin_win');

		$json['used'] = array();
		$json['unused'] = array();
		$json['xaxis'] = array();
		
		$json['used']['label'] = $this->language->get('unused');
		$json['unused']['label'] = $this->language->get('used');
		$json['used']['data'] = array();
		$json['unused']['data'] = array();
		
		$results = $this->model_extension_spin_win->getUsedCouponsByMonth();

		foreach ($results as $key => $value) {
			$json['unused']['data'][] = array($key, $value['total']);
		}

		$results = $this->model_extension_spin_win->getUnusedCouponsByMonth();

		foreach ($results as $key => $value) {
			$json['used']['data'][] = array($key, $value['total']);
		}

		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;

			$json['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
		}
	

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	public function get_offer(){
		$this->load->model('extension/spin_win');
		//Getting the language list from oc_language
		$this->load->model('localisation/language');
		$data['languages'] = array();

		$filter_data = array(
			'sort'  => 'name',
			'order' => 'ASC',
			'start' => (1 - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$results = $this->model_localisation_language->getLanguages($filter_data);
		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name'] . (($result['code'] == $this->config->get('config_language')) ? $this->language->get('text_default') : null),
				'code'        => $result['code'],
				'sort_order'  => $result['sort_order']
			);
		}
		$data=$this->model_extension_spin_win->getSelected($this->request->post,$data);
		$output=json_encode($data);
		die($output);
	}

	public function update_offer(){
		$this->load->model('extension/spin_win');
		$data=$this->model_extension_spin_win->updateOffer($this->request->post);
		}

	public function getChimp(){		
		$mailchimp = $this->getMailchimp($this->request->post['chimp_api']);
		$chimp_list1=getLists($mailchimp);
		if(isset($chimp_list1['total_items']) && $chimp_list1['total_items']>1){
			for($i=0;$i<$chimp_list1['total_items'];$i++){           
            ?>
              <option value="<?php echo $chimp_list1['lists'][$i]['id']; ?>"><?php echo $chimp_list1['lists'][$i]['name'];?></option>
            <?php
          }
      }else{?>
      	<option value="">No List Available</option>
      	<?php
      }		
	}

	public function getContant(){		
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_URL,'https://api.constantcontact.com/v2/lists?api_key='.$this->request->post['kalav_api'].'&access_token='.$this->request->post['kalav_token'].'');
	    $result=curl_exec($ch);
	    curl_close($ch);
	    $kalaviyo_api=json_decode($result, true);
         foreach($kalaviyo_api as $val){        
            ?>
              <option value="<?php echo isset($val['id'])?$val['id']:''; ?>"><?php echo isset($val['name'])?$val['name']:'No List Available';?>  </option>
            <?php
          }
	}

}
