<?php

/**
 * Mega Filter
 * 
 * Editing this file may result in loss of license which will be permanently blocked.
 * 
 * @license Commercial
 * @author info@ocdemo.eu
 * 
 * All code within this file is copyright OC Mega Extensions.
 * You may not copy or reuse code within this file without written permission.
 */

class MegaFilterModule {
	
	private $_ctrl;
	
	private $_cache = array();
	
	private $_data = array();
	
	private static $_warningRendered = false;
	
	public function setData( $data ) {
		$this->_data = $data;
		
		return $this;
	}
	
	private function _keysByAttribs( $attributes ) {
		$keys = array();
		
		foreach( $attributes as $key => $attribute ) {
			if( $attribute['type'] != 'attribute_group' ) {
				$keys[$attribute['seo_name']] = $key;
			}
		}
		
		return $keys;
	}
	
	private function _renderWarning( $warning, $links = false ) {
		if( self::$_warningRendered ) {
			return;
		}
		
		echo '<div style="padding: 10px; text-align: center">';
		echo $warning;
		
		if( $links ) {
			echo '<br /><br />';
			echo 'Please <a href="https://github.com/vqmod/vqmod/releases/tag/v2.6.1-opencart" target="_blank">download VQMod</a> and read ';
			echo '<a href="https://github.com/vqmod/vqmod/wiki/Installing-vQmod-on-OpenCart" target="_blank">How to installl VQMod</a>';
		}
		
		echo '</div>';
		
		self::$_warningRendered = true;
	}
	
	private function parseUrl( $url ) {
		$scheme		= isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		$host		= isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
		$parse		= parse_url( $url );
		
		return $scheme . '://' . $host . $parse['path'] . ( empty( $parse['query'] ) ? '' : '?' . str_replace( '&amp;', '&', $parse['query'] ) );
	}
	
	public static function newInstance( & $ctrl ) {
		return new self( $ctrl );
	}
	
	private function __construct( & $ctrl ) {
		$this->_ctrl = $ctrl;
	}
	
	public function __get( $name ) {
		return $this->_ctrl->{$name};
	}
	
	public function render( $setting ) {		
		if( ! file_exists( DIR_SYSTEM . 'mega_filter.ocmod.xml' ) ) {
			if( ! class_exists( 'VQMod' ) ) {
				$this->_renderWarning( 'Mega Filter PRO to work properly requires an installed VQMod.', true );

				return;
			}

			if( version_compare( VQMod::$_vqversion, '2.5.1', '<' ) ) {
				$this->_renderWarning( 'Mega Filter PRO to work properly requires VQMod in version 2.5.1 or later.<br />Your version of VQMod is too old. Please upgrade it to the latest version.', true );

				return;
			}

			if( version_compare( VERSION, '2.2.0.0', '>=' ) && version_compare( VQMod::$_vqversion, '2.6.1', '<' ) && empty( VQMOD::$_virtualMFP ) ) {
				$this->_renderWarning( 'Your OpenCart requires VQMod in version 2.6.1 or later.<br />Your version of VQMod is too old. Please upgrade it to the latest version.', true );

				return;
			}
		}
		
		if( ! isset( $setting['_idx'] ) ) {
			$this->_renderWarning( 'There is a conflict Mega Filter PRO with your template or other extension - <a href="http://forum.ocdemo.eu/" target="_blank" style="text-decoration:underline">please find a solution on our forum</a>.' );
			
			return;
		}
		
		if( NULL != ( $config = $this->db->query( "SELECT * FROM `" . DB_PREFIX . "mfilter_settings` WHERE `idx` = " . (int) $setting['_idx'] )->row ) ) {
			$config = json_decode( $config['settings'], true );
		} else {
			return;
		}
		
		if( empty( $config['status'] ) ) {
			return;
		}
		
		if( ! method_exists( $this->_ctrl, 'getajaxmodule' ) ) {
			return;
		}
		
		if( class_exists( 'VQMod' ) ) {
			require_once VQMod::modCheck( modification( DIR_SYSTEM . 'library/mfilter_mobile.php' ) );
		} else {
			require_once modification( DIR_SYSTEM . 'library/mfilter_mobile.php' );
		}
		
		$is_mobile = Mobile_Detect_MFP::create()->isMobile();
		
		if( $config['status'] == 'pc' && $is_mobile ) {
			return;
		}
		
		if( $config['status'] == 'mobile' && ! $is_mobile ) {
			return;
		}
		
		if( ! in_array( $this->config->get( 'config_store_id' ), $config['store_id'] ) ) {
			return;
		}
		
		if( empty( $setting['base_attribs'] ) ) {
			$setting['base_attribs'] = empty( $config['base_attribs'] ) ? array() : $config['base_attribs'];
		}
		
		if( empty( $setting['attribs'] ) ) {
			$setting['attribs'] = empty( $config['attribs'] ) ? array() : $config['attribs'];
		}
		
		if( empty( $setting['options'] ) ) {
			$setting['options'] = empty( $config['options'] ) ? array() : $config['options'];
		}
		
		if( empty( $setting['filters'] ) ) {
			$setting['filters'] = empty( $config['filters'] ) ? array() : $config['filters'];
		}
		
		if( empty( $setting['categories'] ) ) {
			$setting['categories'] = empty( $config['categories'] ) ? array() : $config['categories'];
		}
		
		$hasVehicles = $this->config->get( 'mfilter_vehicle_version' );
		
		if( $hasVehicles && empty( $setting['vehicles'] ) ) {
			$setting['vehicles'] = empty( $config['vehicles'] ) ? array() : $config['vehicles'];
		}
		
		$hasLevels = $this->config->get( 'mfilter_level_version' );
		
		if( $hasLevels && empty( $setting['levels'] ) ) {
			$setting['levels'] = empty( $config['levels'] ) ? array() : $config['levels'];
		}
		
		$settings	= $this->config->get('mega_filter_settings');
		
		$in_stock_default_selected = empty( $settings['in_stock_default_selected'] ) ? false : true;
		
		if( ! empty( $config['configuration'] ) ) {
			foreach( $config['configuration'] as $k => $v ) {
				$settings[$k] = $v;
			}
		}
		
		if( isset( $config['layout_id'] ) && is_array( $config['layout_id'] ) ) {
			if( in_array( $settings['layout_c'], $config['layout_id'] ) && isset( $this->request->get['path'] ) ) {
				if( ! empty( $config['category_id'] ) ) {
					$categories		= explode( '_', $this->request->get['path'] );
					
					if( ! empty( $config['category_id_with_childs'] ) ) {
						$is = false;
						
						foreach( $categories as $category_id ) {
							if( in_array( $category_id, $config['category_id'] ) ) {
								$is = true; break;
							}
						}
						
						if( ! $is )
							return;
					} else {
						$category_id	= end( $categories );
						
						if( ! in_array( $category_id, $config['category_id'] ) )
							return false;
					}
				}
				
				if( ! empty( $config['hide_category_id'] ) ) {
					$categories		= explode( '_', $this->request->get['path'] );
					
					if( ! empty( $config['hide_category_id_with_childs'] ) ) {						
						foreach( $categories as $category_id ) {
							if( in_array( $category_id, $config['hide_category_id'] ) ) {
								return;
							}
						}
					} else {
						$category_id	= array_pop( $categories );

						if( in_array( $category_id, $config['hide_category_id'] ) ) {
							return;
						}
					}
				}
			}
		}
		
		if( isset( $config['store_id'] ) && is_array( $config['store_id'] ) && ! in_array( $this->config->get('config_store_id'), $config['store_id'] ) ) {
			return;
		}
		
		if( ! empty( $config['customer_groups'] ) ) {
			$customer_group_id = $this->customer->isLogged() ? $this->customer->getGroupId() : $this->config->get( 'config_customer_group_id' );
			
			if( ! in_array( $customer_group_id, $config['customer_groups'] ) ) {
				return;
			}
		}
		
		$data = $this->language->load('module/mega_filter');
		
		if( isset( $config['title'][$this->config->get('config_language_id')] ) ) {
			$data['heading_title'] = $config['title'][$this->config->get('config_language_id')];
		}
		
		$this->load->model('module/mega_filter');
		
		$this->model_module_mega_filter->setSettings( $settings );
		
		$mfp_data = array(
			'mfp' => null,
		);
		
		foreach( $mfp_data as $k => $v ) {
			if( isset( $this->request->get[$k] ) ) {
				$mfp_data[$k] = $this->request->get[$k];
				$this->request->get[$k.'_temp'] = $mfp_data[$k];
				unset( $this->request->get[$k] );
			}
		}
		
		$core = MegaFilterCore::newInstance( $this, NULL, array(), $settings );
		$cache = null;
		
		if( ! empty( $settings['cache_enabled'] ) ) {
			$cache = 'idx.' . $setting['_idx'] . '.' . $core->cacheName();
		}
		
		if( ! $cache || NULL == ( $attributes = $core->_getCache( $cache ) ) ) {
			$attributes	= $this->model_module_mega_filter->getAttributes( 
				$core,
				$setting['_idx'],
				$setting['base_attribs'], 
				$setting['attribs'], 
				$setting['options'], 
				$setting['filters'],
				empty( $setting['categories'] ) ? array() : $setting['categories'],
				empty( $setting['vehicles'] ) ? array() : $setting['vehicles'],
				empty( $setting['levels'] ) ? array() : $setting['levels']
			);
			
			if( ! empty( $settings['cache_enabled'] ) ) {
				$core->_setCache( $cache, $attributes );
			}
		}
		
		$keys		= $this->_keysByAttribs( $attributes );
		
		$route		= isset( $this->request->get['route'] ) ? $this->request->get['route'] : NULL;
		
		if( in_array( $route, array( 'product/manufacturer', 'product/manufacturer/info' ) ) && isset( $keys['manufacturers'] ) ) {
			unset( $attributes[$keys['manufacturers']] );
		}
		
		if( empty( $settings['allow_search_for_empty_keyword'] ) && in_array( $route, array( 'product/search' ) ) && empty( $this->request->get['search'] ) && empty( $this->request->get['tag'] ) ) {
			$attributes = array();
		}
		
		$hasPrice = false;
		
		foreach( $attributes as $k => $v ) {
			if( $v['base_type'] == 'price' ) {
				$hasPrice = true; break;
			}
		}
		
		if( $hasPrice ) {
			$data['price'] = $core->getMinMaxPrice();

			if( $data['price']['min'] == 0 && $data['price']['max'] == 0 && ! empty( $data['price']['empty'] ) ) {
				$attributesCopy = array();

				foreach( $attributes as $k => $v ) {
					if( $v['base_type'] != 'price' ) {
						$attributesCopy[] = $v;
					}
				}

				$attributes = $attributesCopy;
			}
		} else {
			$data['price'] = array( 'min' => 0, 'max' => 0, 'empty' => true );
		}
		
		if( ! $attributes ) {
			return;
		}
		
		foreach( $mfp_data as $k => $v ) {
			if( $v !== null ) {
				$this->request->get[$k] = $v;
			}
			
			if( isset( $this->request->get[$k.'_temp'] ) ) {
				unset( $this->request->get[$k.'_temp'] );
			}
		}
		
		$core->parseParams();
		
		$mijo_shop	= class_exists( 'MijoShop' ) ? true : false;
		$joo_cart	= defined( 'JOOCART_SITE_URL' ) ? array(
			'site_url' => $this->parseUrl( JOOCART_SITE_URL ),
			'main_url' => $this->parseUrl( $this->url->link( '', '', 'SSL' ) )
		) : false;
		$jcart		= defined( 'JCART_SITE_URL' ) ? array(
			'site_url' => $this->parseUrl( JCART_SITE_URL ),
			'main_url' => $this->parseUrl( $this->url->link( '', '', 'SSL' ) )
		) : false;
		
		if( $setting['position'] == 'content_top' && ! empty( $settings['change_top_to_column_on_mobile'] ) && $is_mobile ) {
			$setting['position'] = 'column_left';
			$data['hide_container'] = true;
		}
		
		$data['direction']			= $this->language->get('direction');
		$data['ajaxGetInfoUrl']		= $this->parseUrl( $this->url->link( 'module/mega_filter/getajaxinfo', '', 'SSL' ) );
		$data['ajaxResultsUrl']		= $this->parseUrl( $this->url->link( 'module/mega_filter/results', '', 'SSL' ) );
		$data['ajaxGetCategoryUrl']	= $this->parseUrl( $this->url->link( 'module/mega_filter/getcategories', '', 'SSL' ) );
		
		if( ! empty( $settings['javascript'] ) ) {
			$settings['javascript'] = preg_replace( 
				'/MegaFilter\.prototype\.(beforeRequest|beforeRender|afterRender)\s*=\s*function/', 
				'MegaFilterOverrideFn['.(int)$setting['_idx'].']["$1"] = function', 
				$settings['javascript'] 
			);
		}
		
		$data['is_mobile']		= $is_mobile;
		$data['mijo_shop']		= $mijo_shop;
		$data['joo_cart']		= $joo_cart;
		$data['jcart']			= $jcart;
		$data['filters']		= $attributes;
		$data['settings']		= $settings;
		$data['params']			= $core->getParseParams();
		$data['_data']			= $core->getData();
		$data['_idx']			= (int) $setting['_idx'];
		$data['_route']				= base64_encode( $core->route() );
		$data['_routeProduct']		= base64_encode( 'product/product' );
		$data['_routeCategory']		= base64_encode( 'product/category' );
		$data['_routeHome']			= base64_encode( 'common/home' );
		$data['_routeInformation']	= base64_encode( 'information/information' );
		$data['_position']		= $setting['position'];
		$data['getSymbolLeft']	= $this->currency->getSymbolLeft( isset( $this->session->data['currency'] ) ? $this->session->data['currency'] : '' );
		$data['getSymbolRight']	= $this->currency->getSymbolRight( isset( $this->session->data['currency'] ) ? $this->session->data['currency'] : '' );
		$data['requestGet']		= $this->request->get;
		$data['_horizontalInline']	= $setting['position'] == 'content_top' && ! empty( $config['inline_horizontal'] ) ? true : false;
		$data['smp']				= array(
			'isInstalled'			=> $this->config->get( 'smp_is_install' ),
			'disableConvertUrls'	=> $this->config->get( 'smp_disable_convert_urls' )
		);
		$data['seo_alias']		= empty( $this->request->request['mfp_seo_alias'] ) ? '' : $this->request->request['mfp_seo_alias'];
		$data['_v'] = $this->config->get('mfilter_version') ? $this->config->get('mfilter_version') : '1';
		$data['displayAlwaysAsWidget'] = empty( $config['display_always_as_widget'] ) ? false : true;
		$data['displaySelectedFilters'] = empty( $config['display_selected_filters'] ) ? false : $config['display_selected_filters'];
		$data['widgetWithSwipe'] = ! isset( $config['widget_with_swipe'] ) || ! empty( $config['widget_with_swipe'] ) ? true : false;
		$data['widgetPosition'] = isset( $config['widget_position'] ) ? $config['widget_position'] : '';
		$data['usePostAjaxRequests'] = isset( $settings['use_post_ajax_requests'] ) ? true : false;
		$data['inStockDefaultSelectedGlobal'] = $in_stock_default_selected;
		$data['theme'] = isset( $config['theme'] ) ? trim( $config['theme'], ' .' ) : '';
		
		$data['seo'] = $this->config->get( 'mega_filter_seo' );
		$data['current_url'] = '';
		$data['aliases'] = empty( $data['seo']['enabled'] ) && empty( $data['seo']['aliases_enabled'] ) ? array() : $core->getCurrentPathAliases();
		
		$tmp = $this->request->get;
		$tmp_to_remove = array( 'route', '_route_', $this->_ctrl->config->get('mfilter_url_param')?$this->_ctrl->config->get('mfilter_url_param'):'mfp' );
		$tmp_params = array();

		foreach( $tmp as $k => $v ) {
			if( ! in_array( $k, $tmp_to_remove ) ) {
				$tmp_params[$k] = $v;
			}
		}

		$data['current_url'] = $this->url->link( isset( $this->request->get['route'] ) ? $this->request->get['route'] : 'common/home', http_build_query( $tmp_params ) );
		
		if( ! empty( $data['seo']['separator'] ) ) {
			$data['seo']['separator'] = isset( $data['seo']['separator'][$this->config->get('config_language_id')] ) ? $data['seo']['separator'][$this->config->get('config_language_id')] : 'mfp';
		} else {
			$data['seo']['separator'] = 'mfp';
		}
		
		if( isset( $data['requestGet']['mfp_path'] ) ) {
			$data['requestGet']['mfp_path_aliases'] = implode( '_', MegaFilterCore::pathToAliases( $this, $data['requestGet']['mfp_path'] ) );
		}
		if( isset( $data['requestGet']['mfp_org_path'] ) ) {
			$data['requestGet']['mfp_org_path_aliases'] = implode( '_', MegaFilterCore::pathToAliases( $this, $data['requestGet']['mfp_org_path'] ) );
		}
		if( isset( $data['requestGet']['path'] ) ) {
			$data['requestGet']['path_aliases'] = implode( '_', MegaFilterCore::pathToAliases( $this, $data['requestGet']['path'] ) );
		}
		
		if( $data['requestGet'] ) {
			foreach( $data['requestGet'] as $k => $v ) {
				if( is_array( $v ) || ! in_array( $k, array( $this->_ctrl->config->get('mfilter_url_param')?$this->_ctrl->config->get('mfilter_url_param'):'mfp', 'mfp_path_aliases', 'mfp_org_path_aliases', 'path_aliases', 'mfp_org_path', 'mfp_path', 'path', 'category_id', 'manufacturer_id', 'filter', 'search', 'sub_category', 'description', 'filter_tag' ) ) ) {
					unset( $data['requestGet'][$k] );
				}
			}
		}
		
		if( ! empty( $data['displayAlwaysAsWidget'] ) ) {
			$data['hide_container'] = true;
		}
		
		$filesJs = array(
			'jquery-ui.min.js?v'.$data['_v'],
			'jquery-plugins.js?v'.$data['_v'],
			'hammer.js?v'.$data['_v'],
			'iscroll.js?v'.$data['_v'],
			'livefilter.js?v'.$data['_v'],
			'selectpicker.js?v'.$data['_v'],
			'mega_filter.js?v'.$data['_v']
		);
		
		$filesCss = array(
			'catalog/view/theme/default/stylesheet/mf/jquery-ui.min.css?v'.$data['_v'],
			file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/mf/style.css') ?
				'catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/mf/style.css?v'.$data['_v'] :
				'catalog/view/theme/default/stylesheet/mf/style.css?v'.$data['_v'],
			'catalog/view/theme/default/stylesheet/mf/style-2.css?v'.$data['_v']
		);
		
		if( $mijo_shop ) {
			MijoShop::getClass('base')->addHeader($this->parseUrl( $this->url->link( 'module/mega_filter/js_params', '', 'SSL' ) ), false);
			
			foreach( $filesJs as $file ) {
				MijoShop::getClass('base')->addHeader(JPATH_MIJOSHOP_OC . '/catalog/view/javascript/mf/'.str_replace( '.js?v'.$data['_v'], '.js', $file ), false);
			}

			if( file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/stylesheet/mf/style.css') ) {
				MijoShop::get()->addHeader(JPATH_MIJOSHOP_OC.'/catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/mf/style.css');
			} else {
				MijoShop::get()->addHeader(JPATH_MIJOSHOP_OC.'/catalog/view/theme/default/stylesheet/mf/style.css');
			}
			
			MijoShop::get()->addHeader(JPATH_MIJOSHOP_OC.'/catalog/view/theme/default/stylesheet/mf/style-2.css');
		} else {
			$direction_file = DIR_SYSTEM . '../catalog/view/javascript/mf/direction_' . $this->config->get( 'config_language_id' ) . '.js';
			
			if( file_exists( $direction_file ) ) {
				array_unshift( $filesJs, 'direction_' . $this->config->get( 'config_language_id' ) . '.js?v' . $data['_v'] );
			} else {
				if( is_writable( DIR_SYSTEM . '../catalog/view/javascript/mf' ) ) {
					file_put_contents( $direction_file, 'var MFP_RTL = ' . ( $this->language->get('direction') == 'rtl' ? 'true' : 'false' ) . ';' );
					array_unshift( $filesJs, 'direction_' . $this->config->get( 'config_language_id' ) . '.js?v' . $data['_v'] );
				} else {
					$this->document->addScript($this->parseUrl( $this->url->link( 'module/mega_filter/js_direction', '', 'SSL' ) ));
				}
			}
			
			if( ! empty( $settings['combine_js_css_files'] ) ) {
				if( ! file_exists( DIR_SYSTEM . '../catalog/view/javascript/mf/combined.js' ) ) {
					/* @var $js string */
					$js = '';

					foreach( $filesJs as $file ) {
						$file = str_replace( '.js?v'.$data['_v'], '.js', $file );

						$js .= $js ? "\n\n" : '';
						$js .= file_get_contents( DIR_SYSTEM . '../catalog/view/javascript/mf/' . $file );
					}

					file_put_contents( DIR_SYSTEM . '../catalog/view/javascript/mf/combined.js', $js );
				}
				
				$filesJs = array( 'combined.js?v' . $data['_v'] );
				
				if( ! file_exists(  DIR_SYSTEM . '../catalog/view/theme/default/stylesheet/mf/combined.css' ) ) {
					$css = '';

					foreach( $filesCss as $file ) {
						$file = str_replace( '.css?v'.$data['_v'], '.css', $file );

						$css .= $css ? "\n\n" : '';
						$css .= file_get_contents( DIR_SYSTEM . '../' . $file );
					}

					file_put_contents( DIR_SYSTEM . '../catalog/view/theme/default/stylesheet/mf/combined.css', $css );
				}
				
				$filesCss = array( 'catalog/view/theme/default/stylesheet/mf/combined.css?v' . $data['_v'] );
			}
			
			foreach( $filesJs as $file ) {
				if( ! empty( $settings['minify_support'] ) ) {
					$file = str_replace( '.js?v'.$data['_v'], '.js', $file );
				}
				
				$this->document->addScript('catalog/view/javascript/mf/'.$file);
			}
			
			foreach( $filesCss as $file ) {
				if( ! empty( $settings['minify_support'] ) ) {
					$file = str_replace( '.css?v'.$data['_v'], '.css', $file );
				}
				
				$this->document->addStyle( $file );
			}
		}
		
		$data = array_replace( $data, $this->_data );
		
		if( file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mega_filter.tpl') ) {
			return $this->load->view((version_compare( VERSION, '2.2.0.0', '>=' ) ? '' : $this->config->get('config_template') . '/template/') . 'module/mega_filter.tpl', $data);
		} else {
			return $this->load->view((version_compare( VERSION, '2.2.0.0', '>=' ) ? '' : 'default/template/') . 'module/mega_filter.tpl', $data);
		}
	}
	
	private static function nonLatinChars() {
		return array(
			'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ǟ', 'ǟ', 'Ǻ', 'ǻ', 'Α', 'α', 'ъ',
			'Ḃ', 'ḃ', 'Б', 'б',
			'Ć', 'ć', 'Ç', 'ç', 'Č', 'č', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Ч', 'ч', 'Χ', 'χ',
			'Ḑ', 'ḑ', 'Ď', 'ď', 'Ḋ', 'ḋ', 'Đ', 'đ', 'Ð', 'ð', 'Д', 'д', 'Δ', 'δ',
			'Ǳ',  'ǲ', 'ǳ', 'Ǆ', 'ǅ', 'ǆ', 
			'È', 'è', 'É', 'é', 'Ě', 'ě', 'Ê', 'ê', 'Ë', 'ë', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ę', 'ę', 'Ė', 'ė', 'Ʒ', 'ʒ', 'Ǯ', 'ǯ', 'Е', 'е', 'Э', 'э', 'Ε', 'ε',
			'Ḟ', 'ḟ', 'ƒ', 'Ф', 'ф', 'Φ', 'φ',
			'ﬁ', 'ﬂ', 
			'Ǵ', 'ǵ', 'Ģ', 'ģ', 'Ǧ', 'ǧ', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ǥ', 'ǥ', 'Г', 'г', 'Γ', 'γ',
			'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ж', 'ж', 'Х', 'х',
			'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ĩ', 'ĩ', 'Ï', 'ï', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'И', 'и', 'Η', 'η', 'Ι', 'ι',
			'Ĳ', 'ĳ', 
			'Ĵ', 'ĵ',
			'Ḱ', 'ḱ', 'Ķ', 'ķ', 'Ǩ', 'ǩ', 'К', 'к', 'Κ', 'κ',
			'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Л', 'л', 'Λ', 'λ',
			'Ǉ', 'ǈ', 'ǉ', 
			'Ṁ', 'ṁ', 'М', 'м', 'Μ', 'μ',
			'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'Ñ', 'ñ', 'ŉ', 'Ŋ', 'ŋ', 'Н', 'н', 'Ν', 'ν',
			'Ǌ', 'ǋ', 'ǌ', 
			'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ø', 'ø', 'Ő', 'ő', 'Ǿ', 'ǿ', 'О', 'о', 'Ο', 'ο', 'Ω', 'ω',
			'Œ', 'œ', 
			'Ṗ', 'ṗ', 'П', 'п', 'Π', 'π',
			'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Р', 'р', 'Ρ', 'ρ', 'Ψ', 'ψ',
			'Ś', 'ś', 'Ş', 'ş', 'Š', 'š', 'Ŝ', 'ŝ', 'Ṡ', 'ṡ', 'ſ', 'ß', 'С', 'с', 'Ш', 'ш', 'Щ', 'щ', 'Σ', 'σ', 'ς',
			'Ţ', 'ţ', 'Ť', 'ť', 'Ṫ', 'ṫ', 'Ŧ', 'ŧ', 'Þ', 'þ', 'Т', 'т', 'Ц', 'ц', 'Θ', 'θ', 'Τ', 'τ',
			'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ũ', 'ũ', 'Ü', 'ü', 'Ů', 'ů', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ų', 'ų', 'Ű', 'ű', 'У', 'у',
			'В', 'в', 'Β', 'β',
			'Ẁ', 'ẁ', 'Ẃ', 'ẃ', 'Ŵ', 'ŵ', 'Ẅ', 'ẅ',
			'Ξ', 'ξ',
			'Ỳ', 'ỳ', 'Ý', 'ý', 'Ŷ', 'ŷ', 'Ÿ', 'ÿ', 'Й', 'й', 'Ы', 'ы', 'Ю', 'ю', 'Я', 'я', 'Υ', 'υ',
			'Ź', 'ź', 'Ž', 'ž', 'Ż', 'ż', 'З', 'з', 'Ζ', 'ζ',
			'Æ', 'æ', 'Ǽ', 'ǽ', 'а', 'А',
			'ь', 'ъ', 'Ъ', 'Ь',
		);
	}
	
	private static function latinChars() {
		return array(
			'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'A',
			'B', 'b', 'B', 'b',
			'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'CH', 'ch', 'CH', 'ch',
			'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd', 'D', 'd',
			'DZ', 'Dz', 'dz', 'DZ', 'Dz', 'dz',
			'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e',
			'F', 'f', 'f', 'F', 'f', 'F', 'f',
			'fi', 'fl',
			'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
			'H', 'h', 'H', 'h', 'ZH', 'zh', 'H', 'h',
			'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
			'IJ', 'ij',
			'J', 'j',
			'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k', 'K', 'k',
			'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l',
			'LJ', 'Lj', 'lj',
			'M', 'm', 'M', 'm', 'M', 'm',
			'N', 'n', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'N', 'n', 'N', 'n', 'N', 'n',
			'NJ', 'Nj', 'nj',
			'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o',
			'OE', 'oe',
			'P', 'p', 'P', 'p', 'P', 'p', 'PS', 'ps',
			'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r', 'R', 'r',
			'S', 's', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 's', 'ss', 'S', 's', 'SH', 'sh', 'SHCH', 'shch', 'S', 's', 's',
			'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'T', 't', 'TS', 'ts', 'TH', 'th', 'T', 't',
			'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
			'V', 'v', 'V', 'v',
			'W', 'w', 'W', 'w', 'W', 'w', 'W', 'w',
			'X', 'x',
			'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'Y', 'y', 'YU', 'yu', 'YA', 'ya', 'Y', 'y',
			'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z', 'Z', 'z',
			'AE', 'ae', 'AE', 'ae', 'a', 'A',
			'', '', '', '',
		);
	}
	
	public static function convertNonLatinToLatin( $str ) {		
		return str_replace( self::nonLatinChars(), self::latinChars(), $str );
	}
	
	public static function removeSpecialCharacters( $str ) {
		return str_replace(array(
			' ', '`', '~', '!', '@', '#', '$', '%', '^', '*', '(', ')', '+', '=', '[', '{', ']', '}', '\\', '|', ';', ':', "'", '"', ',', '<', '.', '>', '/', '?'
		), '-', str_replace(array(
			'&'
		), array(
			'and'
		), htmlspecialchars_decode( $str )) );
	}
	
	public static function convertValueToSeo( & $ctrl, $value ) {
		$settings = $ctrl->config->get('mega_filter_seo');
		
		if( empty( $settings['enabled'] ) ) {
			return $value;
		}
		
		if( ! empty( $settings['convert_non_to_latin'] ) ) {
			$value = $this->convertNonLatinToLatin( $value );
		}
		
		if( ! empty( $settings['remove_special_characters'] ) ) {
			$value = $this->removeSpecialCharacters( $value );
		}
		
		if( ! empty( $settings['convert_to_lowercase'] ) ) {
			$value = mb_strtolower( $value, 'utf-8' );
		}
		
		return $value;
	}
}
