<?php
abstract class Controller {

				protected function rgetMFP( $name ){
					if( isset( $this->request->post[$name] ) ) {
						return $this->request->post[$name];
					}

					if( isset( $this->request->get[$name] ) ) {
						return $this->request->get[$name];
					}

					return null;
				}
			
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;

				if( ! defined( 'HTTP_CATALOG' ) && $this->registry->get('config') && $this->rgetMFP( $this->registry->get('config')->get('mfilter_url_param')?$this->registry->get('config')->get('mfilter_url_param'):'mfp' ) && ! $this->registry->get('config')->get('mfp_path_was_verificed') && isset( $this->registry->get('request')->get['route'] ) ) {
					preg_match( '/path\[([^]]*)\]/', $this->rgetMFP( $this->registry->get('config')->get('mfilter_url_param')?$this->registry->get('config')->get('mfilter_url_param'):'mfp' ), $mf_matches );
								
					if( class_exists( 'VQMod' ) ) {
						require_once VQMod::modCheck( modification( DIR_SYSTEM . '../catalog/model/module/mega_filter.php' ) );
					} else {
						require_once modification( DIR_SYSTEM . '../catalog/model/module/mega_filter.php' );
					}
				
					if( empty( $mf_matches[1] ) ) {
						preg_match( '#path,([^/]+)#', $this->rgetMFP( $this->registry->get('config')->get('mfilter_url_param')?$this->registry->get('config')->get('mfilter_url_param'):'mfp' ), $mf_matches );
				
						if( ! empty( $mf_matches[1] ) ) {				
							if( class_exists( 'MegaFilterCore' ) ) {
								$mf_matches[1] = MegaFilterCore::__parsePath( $this, $mf_matches[1] );
							}
						}
					} else if( class_exists( 'MegaFilterCore' ) ) {
						$mf_matches[1] = MegaFilterCore::__parsePath( $this, $mf_matches[1] );
					}

					if( ! empty( $mf_matches[1] ) ) {
						if( ! $this->rgetMFP('mfilterAjax') && $this->rgetMFP('path') && $this->rgetMFP('path') != $mf_matches[1] ) {
							$this->registry->get('request')->get['mfp_org_path'] = $this->rgetMFP('path');
				
							if( 0 === ( $mf_strpos = strpos( $this->rgetMFP('mfp_org_path'), $mf_matches[1] . '_' ) ) ) {
								$this->registry->get('request')->get['mfp_org_path'] = substr( $this->rgetMFP('mfp_org_path'), $mf_strpos+strlen($mf_matches[1])+1 );
							}
						} else {
							$this->registry->get('request')->get['mfp_org_path'] = '';
						}
				
						//$this->registry->get('request')->get['path'] = $mf_matches[1];
						$this->registry->get('request')->get['mfp_path'] = $mf_matches[1];

						if( isset( $this->registry->get('request')->get['category_id'] ) || ( isset( $this->registry->get('request')->get['route'] ) && in_array( $this->registry->get('request')->get['route'], array( 'product/search', 'product/special', 'product/manufacturer/info' ) ) ) ) {
							$mf_matches = explode( '_', $mf_matches[1] );
							$this->registry->get('request')->get['category_id'] = end( $mf_matches );
						}
					}
				
					unset( $mf_matches );
				
					if( method_exists( $this->registry->get('config'), 'set' ) ) {
						$this->registry->get('config')->set('mfp_path_was_verificed', true);
					}
				}
			
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
}