<?php
class ControllerExtensionModuleSpinWin extends Controller {
	public function index() {		

		$this->load->model('setting/setting');
		$this->load->model('extension/module/spin_win');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
        $data['setting']=$this->model_setting_setting->getSetting('spinwin',$storeId);
		if(empty($data['setting'])) {
			return;
		}
        $data['select_pages']=array();
        if(isset($data['setting']['spinwin_selectpages'])) {
            foreach ($data['setting']['spinwin_selectpages'] as $key => $value) {
                $data['select_pages'][] = $this->model_extension_module_spin_win->getLayoutRoutes($value);
            }
        }    
        $data['spin_data']=$this->model_extension_module_spin_win->getOffer('spinwin',$storeId);
        $this->load->language('extension/module/spin_win');
		$data['do_not'] = $this->language->get('do_not');
		$data['email_address'] = $this->language->get('email_address');
		$data['get_coupon'] = $this->language->get('get_coupon');
		$data['no_thanks'] = $this->language->get('no_thanks');
		$data['empty_field'] = $this->language->get('empty_field');
		$data['empty_email'] = $this->language->get('empty_email');
		$data['validate_email'] = $this->language->get('validate_email');
		$data['max_email'] = $this->language->get('max_email');
		$data['offer'] = $this->language->get('offer');
		$data['hurry'] = $this->language->get('hurry');
		$data['continue'] = $this->language->get('continue');
		$data['code_copied'] = $this->language->get('code_copied');
		$data['email_check'] = $this->language->get('email_check');
		$data['fat_discount'] = $this->language->get('fat_discount');
		$data['email_place'] = $this->language->get('email_place');
		$data['bonus_unlocked'] = $this->language->get('bonus_unlocked');
		$data['wheel_term'] = $this->language->get('wheel_term');
		$data['dnt_feel'] = $this->language->get('dnt_feel');
		$data['try_luck'] = $this->language->get('try_luck');
		$data['continue'] = $this->language->get('continue');
		$data['use_coupon'] = $this->language->get('use_coupon');
		$data['better_luck'] = $this->language->get('better_luck');
		$data['coupon_sent'] = $this->language->get('coupon_sent');
		$data['route'] = isset($this->request->get['route']) ? $this->request->get['route'] : 'common/home';			

		$this->config->get('config_store_id');
		return $this->load->view('common/spin_win', $data);
	}
	//Generate and save Coupon to the database 
	public function generate_coupon(){
		$this->load->model('setting/setting');
		$this->load->model('extension/module/spin_win');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
		$sett=$this->model_setting_setting->getSetting('spinwin',$storeId);	
                
                if($sett['spinwin_enable_chimp']==1){
		 	$list_id=$sett['spinwin_chimp_list'];
		 	$mca_api_key=$sett['spinwin_chimp_api'];
		 	$mailchimp = $this->getMailchimp($mca_api_key);
		 	$sett1 = array('email_address' => $this->request->post['email'],'status'=>'subscribed');
		 	$chimp=addSubscriber($mailchimp, ($sett1), $list_id); 
		 	
		 }
		 if($sett['spinwin_enable_kalav']==1){
		 	$ch = curl_init( 'https://api.constantcontact.com/v2/contacts?action_by=ACTION_BY_OWNER&api_key='.$sett['spinwin_kalav_api'].'&access_token='.$sett['spinwin_kalav_token'].'' );
		     $payload = json_encode(array ('lists' => array (0 => array ('id' => $sett['spinwin_kalav_list'])),'email_addresses' => array (0 => array ('status' => 'ACTIVE','email_address' => $this->request->post['email']))));
		     curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		     curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		     curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		     $result = curl_exec($ch);
		     curl_close($ch);
		     $kalav=json_decode($result,true);
		 }
                
                
		
		$code=$this->generate_code();
		$arr=array( 'name' => '', 'code' => $code, 'type' =>'', 'discount' => '', 'total' => '', 'logged' => '0', 'shipping' => '0', 'product' =>'', 'category' =>'', 'date_start' => '', 'date_end' => '', 'uses_total' => '1', 'uses_customer' => '1', 'status' => '1' );
		
		$data=$this->model_extension_module_spin_win->addCoupon($arr,$this->request->post);
		
	}
	//To check the coupon is generated for the customer or not 
	public function email_check(){		
		$this->load->model('extension/module/spin_win');
		$this->load->model('setting/setting');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
		$data=$this->model_extension_module_spin_win->checkEmail($this->request->post);
	}
	//To get the mail chimp list
	public function getMailchimp($mca_api_key) {
		//echo DIR_SYSTEM . 'library/mailchimp-api/index.php'; die;
        include(DIR_SYSTEM . 'library/mailchimp-api/index.php');
        $mailchimp = initilizeMailchimp($mca_api_key);
        return $mailchimp;
	}
	// Coupon generation code
	public function generate_code(){
		$coupon_code=bin2hex(openssl_random_pseudo_bytes(3));
		$this->load->model('extension/module/spin_win');
		$data=$this->model_extension_module_spin_win->checkCoupon($coupon_code);
		if($data>0){
			$this->generate_code();
		}else{
			return $coupon_code;
		}
	}
	// Send  mail to the customer
	public function send_email(){		
		$this->load->model('setting/setting');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
		$this->load->model('extension/module/spin_win');	
		$code=$this->generate_code();
		$arr=array( 'name' => '', 'code' => $code, 'type' =>'', 'discount' => '', 'total' => '', 'logged' => '0', 'shipping' => '0', 'product' =>'', 'category' =>'', 'date_start' => '', 'date_end' => '', 'uses_total' => '1', 'uses_customer' => '1', 'status' => '1' );
		$data=$this->model_setting_setting->getSetting('spinwin',$storeId);
		$data=$this->model_extension_module_spin_win->sendEmail($arr,$data,$this->request->post);
	}

}