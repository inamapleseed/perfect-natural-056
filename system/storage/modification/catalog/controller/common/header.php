<?php
	class ControllerCommonHeader extends Controller {
		public function index() {

				if( ! isset( $this->request->request['mfp_seo_alias'] ) ) {
					$mfilterSeoConfig = $this->config->get('mega_filter_seo');

					if( ! empty( $mfilterSeoConfig['meta_robots'] ) && ! empty( $this->request->get[$this->config->get('mfilter_url_param')?$this->config->get('mfilter_url_param'):'mfp'] ) ) {
						$data['mfp_robots_value'] = $mfilterSeoConfig['meta_robots_value'];
					}
				}
			
		
			$data['actual_link'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

			$google_map = $this->cache->get('google_map');
			
			// Load Facebook
			$this->facebookPixel($data);
			$data['social_icons'] = $this->load->controller('component/social_icons');

			//Visitor Logging
            $this->load->model('common/analytics');
            $customer_id = $this->customer->isLogged() ? $this->customer->getId() : 0;
            $this->model_common_analytics->trackVisitor($data['actual_link'], $customer_id);

			// Analytics
			$this->load->model('extension/extension');
			
			$data['analytics'] = array();
			
			$analytics = $this->model_extension_extension->getExtensions('analytics');
			
			foreach ($analytics as $analytic) {
				if ($this->config->get($analytic['code'] . '_status')) {
					$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get($analytic['code'] . '_status'));
				}
			}
			
			$server = $this->config->get('config_url');
			
			if ($this->request->server['HTTPS']) {
				$server = $this->config->get('config_ssl');
			}
	
			if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
				$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
			}
			
			$data['meta_store'] = $this->config->get('config_store');
			$data['schema_json_code'] = $this->document->getSchema();

			if(!$data['schema_json_code']){
				$data['schema_json_code'] = $this->config->get('config_schema');
			}

			$data['title'] = $this->document->getTitle();
			
			$data['is_mpgs'] = false;
	        if ($this->config->get('mpgs_status')) {
	        	$data['is_mpgs'] = true;
	        	$data['mpgs_mode'] = $this->config->get('mpgs_mode');
	        	$data['mpgs_callback'] = $this->url->link('extension/payment/mpgs/callback');
	        }

			$data['actual_link'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			
			$data['base'] = $this->url->fix_url($server);
			/* completecombo */
			//$this->document->addStyle('catalog/view/theme/default/stylesheet/slsoffr.css');
			/* completecombo */

			$data['description'] = $this->document->getDescription();
			$data['keywords'] = $this->document->getKeywords();
			$data['links'] = $this->document->getLinks();
			$data['styles'] = $this->document->getStyles();
			// << Related Options
			if ( !$this->model_module_related_options ) {
				$this->load->model('module/related_options');
			}
			
			if ( $this->model_module_related_options->installed() ) {
		      	foreach ( $this->model_module_related_options->getProductPageScripts() as $script ) {
					$this->document->addScript( $script );
     			}
			}
			// >> Related Options
			$data['scripts'] = $this->document->getScripts();
			$data['lang'] = $this->language->get('code');
			$data['direction'] = $this->language->get('direction');
			
			$data['name'] = $this->config->get('config_name');

			$data['seo_enabled'] = $this->config->get('config_seo_url');
			
			$data['logo'] = '';

			if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
				$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
			}
			
			$this->load->language('common/header');
			
			$data['text_home'] = $this->language->get('text_home');
			
			// Wishlist
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
			
			if ($this->customer->isLogged()) {
				$this->load->model('account/wishlist');
				
				$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
			}
			
			$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
			$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', true), $this->customer->getFirstName(), $this->url->link('account/logout', '', true));
			
			$data['text_login_register'] = $this->language->get('text_login_register');
			$data['text_account'] = $this->language->get('text_account');
			$data['text_register'] = $this->language->get('text_register');
			$data['text_login'] = $this->language->get('text_login');
			$data['text_order'] = $this->language->get('text_order');
			$data['text_transaction'] = $this->language->get('text_transaction');
			$data['text_download'] = $this->language->get('text_download');
			$data['text_logout'] = $this->language->get('text_logout');
			$data['text_checkout'] = $this->language->get('text_checkout');
			$data['text_category'] = $this->language->get('text_category');
			$data['text_all'] = $this->language->get('text_all');
			
			$data['home'] = $this->url->link('common/home');
			$data['wishlist'] = $this->url->link('account/wishlist', '', true);
			$data['logged'] = $this->customer->isLogged();
			$data['account'] = $this->url->link('account/account', '', true);
			$data['register'] = $this->url->link('account/register', '', true);
			$data['login'] = $this->url->link('account/login', '', true);
			$data['order'] = $this->url->link('account/order', '', true);
			$data['transaction'] = $this->url->link('account/transaction', '', true);
			$data['download'] = $this->url->link('account/download', '', true);
			$data['logout'] = $this->url->link('account/logout', '', true);
			$data['shopping_cart'] = $this->url->link('checkout/cart');
			$data['checkout'] = $this->url->link('checkout/checkout', '', true);
			$data['contact'] = $this->url->link('information/contact');
			$data['telephone'] = $this->config->get('config_telephone');
			
			// For page specific css
			
			$data['class'] = 'common-home';
			

			/* completecombo */
		 	if($this->config->get("offerpage_installed")) {
	          $this->load->language('offers/salescombopge');
	          $data['text_salescombopge_heading'] = $this->language->get('text_salescombopge_heading');
	          $data['salescombopge_info'] = array();
	          $this->load->model('offers/salescombopge');
	          $salescombopge_info = $this->model_offers_salescombopge->getPages();
	          foreach ($salescombopge_info as $key => $value) {
	           if($value['top']) {
	              $data['salescombopge_info'][] = array(
	                'name'=> $value['title'],
	                'href' => $this->url->link('offers/salescombopge', 'page_id=' .  $value['salescombopge_id']),
	                'id' => "",
	                'children_level2' => array()
	              );
	            } 
	          }
	          if(!empty($data['salescombopge_info'])) {
	            $data['categories'][] = array(
	              'name'     => $data['text_salescombopge_heading'],
	              'children' => $data['salescombopge_info'],
	              'column'   => 1,
	              'href'     => $this->url->link("offers/alloffers")
	            );
	          }
	        }
			/* completecombo */

			if (isset($this->request->get['route'])) {
				$class = '';
				
				if (isset($this->request->get['product_id'])) {
					$class = ' pid-' . $this->request->get['product_id'];
				}
				elseif (isset($this->request->get['path'])) {
					$class = ' cid-' . $this->request->get['path'];
				}
				elseif (isset($this->request->get['manufacturer_id'])) {
					$class = ' mid-' . $this->request->get['manufacturer_id'];
				}
				elseif (isset($this->request->get['information_id'])) {
					$class = ' iid-' . $this->request->get['information_id'];
				}
				elseif (isset($this->request->get['ncat'])) {
					$class = ' ncat-' . $this->request->get['ncat'];
				}
				elseif (isset($this->request->get['news_id'])) {
					$class = ' nid-' . $this->request->get['news_id'];
				}
				
				$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;


				if (strpos($this->request->get['route'], '/checkout') !== false || 
					strpos($this->request->get['route'], '/cart') !== false) {
					$data['class'] .= ' cart-edit-off'; 
				}
				if (!$this->config->get('config_edit_cart') ) {
					$data['class'] .= ' cart-edit-off'; 
				}


			}

			// Social Media Sharing

			$this->loadSocialTags($data, $server);

			$theme = $this->config->get('config_theme');
			$menu_id = $this->config->get($theme . "_header");
			
			$menu = $this->load->controller('common/menu', $menu_id);

			$this->fill_sub_categories($menu);

			$data['menu'] = $this->craftHtml($menu);

			$data['mobile_menu'] = $this->craftMobileHtml($menu);

			// mobile menu background color
			$theme = $this->config->get('config_theme');
			$data['mobile_menu_background_color1'] = $this->config->get($theme . "_mobile_menu_background_color1");
			$data['mobile_menu_background_color2'] = $this->config->get($theme . "_mobile_menu_background_color2");
			// mobile menu background color

			// parent menu item clickable or non-clickable setting
			if ($this->config->get('theme_default_parent_menu_non_clickable') ) {
				$data['class'] .= ' parent-menu-non-clickable'; 
			}
			// parent menu item clickable or non-clickable setting

			//Live chat script
			$data['live_chat_script'] = html($this->config->get('config_livechat'));
			
			$pixelMicrodataSetup = $this->pixelMicrodataSetup();
			$data = array_merge($data, $pixelMicrodataSetup);

			// Load Controller
			$controllers = array(
				'language'			=>	'common/language',
				'currency'			=>	'common/currency',
				'search'			=>	'common/search',
				'cart'				=>	'common/cart',
				
				'enquiry'			=>	'common/enquiry',
			);
			foreach($controllers as $var => $controller) 
				$data[$var]	=$this->load->controller($controller);

			// Load Parts
			$parts = array(
				'fb_pixel'			=>	'common/header/fb_pixel',
				'fb_messanger'		=>	'', //'common/header/fb_messanger',
				'head_tags'			=>	'common/header/_head_meta',
				'login_part'		=>	'common/header/login',
				'wishlist'			=>	'common/header/wishlist',
				'pop_up_search'		=>	'common/header/search_pop_up',	//	Note: echo $search for non popup search box
			); 

			foreach($parts as $var => $part) 
				$data[$var]	=$this->load->view($part, $data);

			// Load Page Banner
			$data['page_banner'] = $this->load->controller('component/page_banner');

			$data['isMobile'] = $this->mobile_detect->isMobile()?'mobile':'desktop';

			$announcement_bar = $this->loadAnnouncementBar();
			$data = array_merge($data, $announcement_bar);

            // Moz in mac handler
			$os = getenv("HTTP_USER_AGENT"); //debug($os);
			$os = strtolower($os);

			if(strpos('_' . $os, 'macintosh') || strpos('_' . $os, 'mac')){
				$data['isMobile'] .= ' mac-browser';
			}
			
			//Spin & Win
			$data['spinwin'] = "";
			$spinwheel_status = $this->config->get('spinwin_enable');
			if(isset($spinwheel_status) && $spinwheel_status == 1){
				$data['spinwin'] = $this->load->controller('extension/module/spin_win');
			}
			
			return $this->load->view('common/header/header', $data);
		}

		private function loadAnnouncementBar() {
            $this->load->library('modulehelper');
            $Modulehelper = Modulehelper::get_instance($this->registry);
            $language_id = $this->config->get('config_language_id');
            $modulename  = 'announcement_bar';
            $data[] = array();
            $data['announcement_status'] = $this->config->get('announcement_bar_status');
            $data['header_top_icon'] = $this->modulehelper->get_field($this, $modulename, $language_id, "icon");
            $data['header_top_title'] = $this->modulehelper->get_field($this, $modulename, $language_id, "title");
            $data['header_top_background'] = $this->modulehelper->get_field($this, $modulename, $language_id, "background_color");
            $data['header_top_text_color'] = $this->modulehelper->get_field($this, $modulename, $language_id, "text_color");
            $data['header_top_position'] = $this->modulehelper->get_field($this, $modulename, $language_id, "position");
            $data['header_top_padding'] = $this->modulehelper->get_field($this, $modulename, $language_id, "padding");
            $data['header_top_running'] = $this->modulehelper->get_field($this, $modulename, $language_id, "running");
            return $data;
		}
		
		private function pixelMicrodataSetup() {
			$data[] = array();
			/*Pixel microdata setup*/
			$data['is_product'] = false;
			if(isset($this->request->get['route']) && $this->request->get['route'] == 'product/product'){
				$data['is_product'] = true;
				if (isset($this->request->get['product_id'])) {
					$product_id = (int)$this->request->get['product_id'];
				} 
				$this->load->model('catalog/product');
		
				$product_info = $this->model_catalog_product->getProduct($product_id);
				
				$this->load->model('tool/image');

				$theme = $this->config->get('config_theme');
				
				$image_popup_width = $this->config->get( $theme . '_image_popup_width');
				$image_popup_height = $this->config->get( $theme . '_image_popup_height');
				
				if($product_info) {
					$data['og_image'] =  $this->model_tool_image->resize($product_info['image'], $image_popup_width, $image_popup_height);
					$data['og_price'] =	$product_info['price'];
					$data['og_retailer_id'] =	$product_id;
					$data['og_currency']  = $this->session->data['currency'];
					$data['og_url'] =$this-> url->link('product/product','product_id='.$product_id);
					if($product_info['manufacturer'] == ""){
						$data['og_brand'] = $this->config->get('config_name');
					} else {
						$data['og_brand'] = $product_info['manufacturer'];
					}
					
					$data['og_availability'] = 'in stock';     
					if ( $product_info['quantity']<=0 ) {
						$data['og_availability'] = 'out of stock';
					}  
				}
			}
			/*Pixel microdata setup*/
			return $data;
		}

		private function craftMobileHtml($menu = array(), $level = 0, $menu_part = 0 ){
			 
			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;

			foreach($menu as $link){

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
				if($link['child']){
					$sub_menu = $this->craftMobileHtml($link['child'], $level+1);
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}

				$carat = '';

				$id = uniqid(). '-' . generateSlug($name) . '-' . $menu_part . '-' . $level . '-' . $index;

				if($sub_menu){
					$menus .= '<li class="has-children '.($link['active']?'active':'').'">';
					$menus .= '	<input type="checkbox" name ="sub-group-'.$id.'" id="sub-group-'.$id.'" class="hidden">';

					$menus .= '	<a href="' . $href . '" alt="' . $name . '" >'.$inner_text.'</a>';
					$menus .= '	<label for="sub-group-'.$id.'"><i class="fa fa-caret-down" aria-hidden="true"></i></label>';
					$menus .= $sub_menu;
				} 
				else{ 
					$menus .= '<li class="'.($link['active']?'active':'').'">';
					$menus .= '	<a href="' . $href . '" alt="' . $name . '" >'.$inner_text.'</a>';
				}

				$menus .= '</li>';

				$index++;
			}

			if( trim($menus) != '' ){
				if($level){
					$menus = '<ul>' . $menus . '</ul>';
				}
				else{
					$menus = 
					'<ul class="cd-accordion-menu animated">'.
						$menus.
					'</ul>';
				}
			}
			

			return $menus;
		}

		private function craftHtml($menu = array(), $level = 0, $menu_part = 0 ){
			
			if(!is_array($menu)){
				return '';
			}

			$menus = '';

			$index = 1;

			foreach($menu as $link){

				$tab_option = $link['new_tab']?'target="_blank"':'';

				$href = $link['href'];
				$name = $link['name'];

				$sub_menu = '';
				if($link['child']){
					$sub_menu = $this->craftHtml($link['child'], $level+1);
				}

				$inner_text = $name;
				if(!$level){
					$inner_text = '<span>' . $name . '</span>';
				}
	
				// $carat = '<div class="caret"></div>';
				$carat = '';

				if($link['query'] == 'product/category'){
					if($sub_menu) {
						$menus .= '<li class="all-cats '.($link['active']?'active':'').'">';
						if($level){
							$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $name . ' ' . $carat . '</a>' . $sub_menu;
						}else{
							$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>' . $sub_menu;
						}
					} 
					else{ 
						$menus .= '<li class="'.($link['active']?'active':'').'">';
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>';
					}
				} else {

					if($sub_menu) {
						$menus .= '<li class="'.($link['active']?'active':'').'">';
						if($level){
							$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $name . ' ' . $carat . '</a>' . $sub_menu;
						}else{
							$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>' . $sub_menu;
						}
					} 
					else{ 
						$menus .= '<li class="'.($link['active']?'active':'').'">';
						$menus .= '	<a href="'.$href.'" '.$tab_option.' alt="' . $name . '" >' . $inner_text . '</a>';
					}
	
				}

				$menus .= '</li>';

				$index++;
			}

			if( trim($menus) != '' ){
				if($level){
					$menus = '<ul>' . $menus . '</ul>';
				}
				else{
					$menus = 
					'<ul id="main-menu" class="sm sm-blue">'.
						$menus.
					'</ul>';
				}
			}
			

			return $menus;
		}

		private function loadSocialTags(&$data, $server){

			$general_image = false;
			if ($this->config->get('config_image')) {
				$general_image = $this->config->get('config_image');
			}
			
			$data["content_type"] = "website";
			
			$data["store_name"] = $this->config->get("config_name");
			
			$sharing_image	= "";
			
			$data["fb_img"] = "";
			$data["tw_img"] = "";
			$data["gp_img"] = "";
			
			$data['current_page'] = $server;
			
			if (isset($this->request->get['route'])) {
			
				if (isset($this->request->get['product_id'])) {
				
					$data["content_type"] = "article";
					
					$this->load->model("catalog/product");
					
					$product_info = $this->model_catalog_product->getProduct((int)$this->request->get['product_id']);
					
					if($product_info){
						$sharing_image = $product_info["image"];
						
						$data['current_page'] = $this->url->link("product/product", "product_id=" . $product_info["product_id"], true);
					}
				}
				
				// for news article og meta data
				if (isset($this->request->get['news_id'])) {
				
					$data["content_type"] = "article";
					
					$this->load->model('catalog/news');
					
					$news_info = $this->model_catalog_news->getNewsStory((int)$this->request->get['news_id']);
					
					if($news_info){
						if($news_info["image"]) {
							$sharing_image = $news_info["image"];
						}

						if($news_info["image2"]) {
							$sharing_image = $news_info["image2"];
						}
						
						$data['current_page'] = $this->url->link("news/article", "news_id=" . $news_info["news_id"], true);
					}
				}
				// for news article og meta data
				
			}

			$fb_width = 600;
			$fb_height = 315;

			$tw_width = 512;
			$tw_height = 299;

			$gp_width = 612;
			$gp_height = 299;
			
			$this->load->model("tool/image");

			if($sharing_image){ 	
				$data["fb_img"] = $this->model_tool_image->resize($sharing_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($sharing_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($sharing_image, $gp_width, $gp_height, 'w');
			}
			elseif($general_image){
				$data["fb_img"] = $this->model_tool_image->resize($general_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($general_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($general_image, $gp_width, $gp_height, 'w');
			}
			elseif($this->config->get('config_logo')){
				$sharing_image = $this->config->get('config_logo');

				$data["fb_img"] = $this->model_tool_image->resize($sharing_image, $fb_width, $fb_height, 'w');
				$data["tw_img"] = $this->model_tool_image->resize($sharing_image, $tw_width, $tw_height, 'w');
				$data["gp_img"] = $this->model_tool_image->resize($sharing_image, $gp_width, $gp_height, 'w');
			}
		}

		private function facebookPixel(&$data){
				
			$this->facebookcommonutils = new FacebookCommonUtils();
			$data['facebook_pixel_id_FAE'] =
			$this->config->get('facebook_pixel_id');
			$source = 'exopencart';
			$opencart_version = VERSION;
			$plugin_version = $this->facebookcommonutils->getPluginVersion();
			$agent_string = sprintf(
			'%s-%s-%s',
			$source,
			$opencart_version,
			$plugin_version);
			$facebook_pixel_pii_FAE = array();
			if ($this->config->get('facebook_pixel_use_pii') === 'true'
			&& $this->customer->isLogged()) {
			$facebook_pixel_pii_FAE['em'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getEmail());
			$facebook_pixel_pii_FAE['fn'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getFirstName());
			$facebook_pixel_pii_FAE['ln'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getLastName());
			$facebook_pixel_pii_FAE['ph'] =
				$this->facebookcommonutils->getEscapedString(
				$this->customer->getTelephone());
			}
			$data['facebook_pixel_pii_FAE'] = json_encode(
			$facebook_pixel_pii_FAE,
			JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
			$facebook_pixel_params_FAE = array('agent' => $agent_string);
			$data['facebook_pixel_params_FAE'] = json_encode(
			$facebook_pixel_params_FAE,
			JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
			$data['facebook_pixel_event_params_FAE'] =
			(isset($this->request->post['facebook_pixel_event_params_FAE'])
				&& $this->request->post['facebook_pixel_event_params_FAE'])
			? $this->request->post['facebook_pixel_event_params_FAE']
			: '';
			// flushing away the facebook_pixel_event_params_FAE
			// in the controller to ensure that subsequent fires
			// for the same param is not performed

			$this->request->post['facebook_pixel_event_params_FAE'] = '';
		}

		private function fill_sub_categories(&$menus){
			$this->load->model('catalog/category');

			$current_active_paths = array();
			if(isset($this->request->get['path'])){
				$current_active_paths = explode('_', $this->request->get['path']);
			}

			foreach($menus as &$menu){
				
				$menu['columns'] = 4;

				// Skip those that have child or not category page
				if( $menu['child'] || !strpos($menu['query'], '/category') ) continue;
				
				$path = 0;

				$query_break = explode('&path=', $menu['query']);
				
				if(count($query_break) > 1 && isset($query_break[1]) ){
					$path	=	$query_break[1];
					$paths 	= 	explode('_',$path);
					$path 	= 	end($paths);
				}

				$menu['columns'] = 5;

				$subs = array();
			
				$categories = $this->model_catalog_category->getCategories($path);
				
				foreach($categories as $category){
					$subs_childs = array();
					$active = '';
					
					$sub_categories = $this->model_catalog_category->getCategories($category['category_id']);
					
					foreach($sub_categories as $sub_category){
						$sub_active = '';
						/** level 3 **/	
						$subs_childs_1 = array();
						$sub_categories_1 = $this->model_catalog_category->getCategories($sub_category['category_id']);
					
						foreach($sub_categories_1 as $sub_category_1){
							$sub_active_1 = '';
							/** level 4 **/	
							$subs_childs_2 = array();
							$sub_categories_2 = $this->model_catalog_category->getCategories($sub_category_1['category_id']);
						
							foreach($sub_categories_2 as $sub_category_2){
								$sub_active_2 = '';
								/** level 5 (can add new level using this similar block of code) **/	
								$subs_childs_3 = array();
								$sub_categories_3 = $this->model_catalog_category->getCategories($sub_category_2['category_id']);
							
								foreach($sub_categories_3 as $sub_category_3){
									$sub_active_3 = '';
									
									if( in_array($sub_category_3['category_id'], $current_active_paths) ){
											$sub_active_3 = 'active';
									}
									
									$subs_childs_3[] = array(
										'level'		=>	5,
										'label'		=>	$sub_category_3['name'],
										'name'	=>	$sub_category_3['name'],
										'query'	=>	'',
										'new_tab'	=>	0,
										'child'		=>	array(),
										'active'	=>	$sub_active_3,
										'href'		=>	$this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $sub_category['category_id'] . '_' .$sub_category_1['category_id'] . '_' . $sub_category_2['category_id'] . '_' .$sub_category_3['category_id'])
									);
								}
								/** level 5 (can add new level using this similar block of code) **/
								
								if( in_array($sub_category_2['category_id'], $current_active_paths) ){
										$sub_active_2 = 'active';
								}
								
								$subs_childs_2[] = array(
									'level'		=>	4,
									'label'		=>	$sub_category_2['name'],
									'name'	=>	$sub_category_2['name'],
									'query'	=>	'',
									'new_tab'	=>	0,
									'child'		=>	$subs_childs_3,
									'active'	=>	$sub_active_2,
									'href'		=>	$this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $sub_category['category_id'] . '_' .$sub_category_1['category_id'] . '_' . $sub_category_2['category_id'])
								);
							}
							/** level 4 **/	
							
							if( in_array($sub_category_1['category_id'], $current_active_paths) ){
									$sub_active_1 = 'active';
							}
							
							$subs_childs_1[] = array(
								'level'		=>	3,
								'label'		=>	$sub_category_1['name'],
								'name'	=>	$sub_category_1['name'],
								'query'	=>	'',
								'new_tab'	=>	0,
								'child'		=>	$subs_childs_2,
								'active'	=>	$sub_active_1,
								'href'		=>	$this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $sub_category['category_id'] . '_' .$sub_category_1['category_id'])
							);
						}
						/** level 3 **/	
						
						if( in_array($sub_category['category_id'], $current_active_paths) ){
								$sub_active = 'active';
						}
						
						$subs_childs[] = array(
							'level'		=>	2,
							'label'		=>	$sub_category['name'],
							'name'	=>	$sub_category['name'],
							'query'	=>	'',
							'new_tab'	=>	0,
							'child'		=>	$subs_childs_1,
							'active'	=>	$sub_active,
							'href'		=>	$this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $sub_category['category_id'])
						);
					}
					
					if( in_array($category['category_id'], $current_active_paths) ){
							$active = 'active';
					}
					
					$subs[] = array(
						'level'		=>	1,
						'label'		=>	$category['name'],
						'name'	=>	$category['name'],
						'query'	=>	'',
						'new_tab'	=>	0,
						'child'		=>	$subs_childs,
						'active'	=>	$active,
						'href'		=>	$this->url->link('product/category', 'path=' . $category['category_id'])
					);
				}
				
				$menu['child'] = $subs;
			}
		}
	} 