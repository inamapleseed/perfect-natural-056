<?php
class ModelExtensionModuleSpinWin extends Model {
	public function addCoupon($data,$data1) {

		$ip = $_SERVER['REMOTE_ADDR']; 
		$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
		$ip_country=$dataArray->geoplugin_countryName;
		if($ip_country==""){
			$ip_country='India';
		}

		
		$this->load->model('setting/setting');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}

		
		$sett=$this->model_setting_setting->getSetting('spinwin',$storeId);
		$date_start=date('Y-m-d');
		$date_end=date('Y-m-d',date(strtotime("+".$sett['spinwin_expiration']." day", strtotime("$date_start"))));

		//Getting the offer from database
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'spin_win_offer';
            $slices = $this->db->query($sql)->rows;

            $coupon_data = array();
            $prob_arr = array();
            foreach ($slices as $slice) {
                for ($i = 0; $i < $slice['gravity']; $i++) {
                    $prob_arr[] = $slice['id'];
                }
            }
        $key = array_rand($prob_arr);
        $slice_no=$prob_arr[$key];
       	$arr_offer =  $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_win_offer where id= '$slice_no'");
     	$offer_arr=$arr_offer->row;
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            if($this->config->get('config_language')==$language['code']){
                $lang_id=$language['language_id'];
            }
        }  
     	$arr_offer_name =  $this->db->query("SELECT label FROM " . DB_PREFIX . "spin_language_label where slice_id= '$slice_no' and store_id='$storeId' and language_id='".$lang_id."'");
     	$offer_arr_name=$arr_offer_name->row;
     	$offer_name= $offer_arr_name['label'];
		if($offer_arr['value']!=0){
			if($offer_arr['coupon_type']=='Free shiping'){
				$shiping=1;
			}else{
				$shiping=0;
			}
		//Inserting Coupon data
                        
                if($offer_arr['coupon_type']=='Fixed'){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($offer_name) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$offer_arr['value'] . "', type = '" . $this->db->escape($offer_arr['coupon_type']) . "', total = '" . (float)$offer_arr['value'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$shiping . "', date_start = '" . $this->db->escape($date_start) . "', date_end = '" . $this->db->escape($date_end) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($offer_name) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$offer_arr['value'] . "', type = '" . $this->db->escape($offer_arr['coupon_type']) . "', total = '" . (float)0.001 . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$shiping . "', date_start = '" . $this->db->escape($date_start) . "', date_end = '" . $this->db->escape($date_end) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
                }
                        
		
		// Browsing Device  
		require_once(DIR_SYSTEM.'library/Mobile-Detect-2.8.33/Mobile_Detect.php');
		$mobile_detect = new Mobile_Detect();
		$this->load->language('extension/module/spin_win');
		if($mobile_detect->isMobile() && !$mobile_detect->isTablet()){
			$browse_device= $this->language->get('mobile');
		}else if($mobile_detect->isMobile() && $mobile_detect->isTablet()){
			$browse_device= $this->language->get('tablet');
		}else{
			$browse_device=$this->language->get('desktop');
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "spin_win SET coupon = '" . $this->db->escape($data['code']) . "', email = '" . $this->db->escape($data1['email']) . "', country = '" . $ip_country . "', device = '" . $browse_device."',storeId = '" . $storeId."'");
		}
		$this->load->language('extension/module/spin_win');

		//Getting Coupon value		
		if($offer_arr['coupon_type']=='Fixed'){
			$discount_value=$this->currency->format($offer_arr['value'], $this->session->data['currency']);
		}else if($offer_arr['coupon_type']=='Percentage'){
			$discount_value=$offer_arr['value'].' %';
		}else{
			$discount_value=$offer_arr['coupon_type'];
		}
		// Sending email 
		if($data1['email_pop']=='3' && $offer_arr['value']!=0){
			$url=$this->config->get('config_url');		
			$msg=str_replace('{{coupon}}',$data['code'],$sett['spinwin_emailer_content']);
			$msg=str_replace('{{amount}}',$discount_value,$msg);
			$msg=str_replace('{{storeurl}}',$url,$msg);
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

			$mail->setTo($data1['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($sett['spinwin_email_subject'], ENT_QUOTES, 'UTF-8'));
			$mail->setHtml(html_entity_decode($msg, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}
		

		$success = $this->language->get('success');
		$use_coupon = $this->language->get('use_coupon');
		$coupon_sent = $this->language->get('coupon_sent');
		$better_luck = $this->language->get('better_luck');
		if($offer_arr['value']==0 && $offer_arr['coupon_type']!='Free shiping'){
			$output=json_encode(array('suc_msg'=>$better_luck,'code'=>"",'suc_desc'=>'','slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Loose','value'=>0,'gift_product'=>0,'code'=>""));
		}else if($offer_arr['coupon_type']!='Free shiping'){
			$output=json_encode(array('suc_msg'=>$success.$discount_value,'code'=>$data['code'],'suc_desc'=>$coupon_sent,'slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Win','value'=>$offer_arr['value'],'gift_product'=>0,'code'=>$data['code']));
		}else if($offer_arr['coupon_type']=='Free shiping'){
			$output=json_encode(array('suc_msg'=>$success.$discount_value,'code'=>$data['code'],'suc_desc'=>$coupon_sent,'slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Win','value'=>$offer_arr['value'],'gift_product'=>0,'code'=>$data['code']));
		}
		die($output);
	}

	public function checkEmail($data){	
		extract($data);	
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_win WHERE email='$email' ORDER BY id DESC");		
		$arr=$query->num_rows;		
		if($arr<=0){
			$output=json_encode(array('type'=>'success'));
			die($output);
		}else{
			$output=json_encode(array('type'=>'error'));
			die($output);
		}
	}

	public function checkCoupon ($data){
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon WHERE code='$data'");		
		$arr=$query->num_rows;		
		return $arr;
	}

	public function sendEmail($data, $arr,$data1){
		extract($_POST);
		$this->load->language('extension/module/spin_win');
		$success = $this->language->get('success');
		$use_coupon = $this->language->get('use_coupon');
		$better_luck = $this->language->get('better_luck');
		$coupon_sent = $this->language->get('coupon_sent');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
		//Getting the coupon expiration dates
		$sett=$this->model_setting_setting->getSetting('spinwin',$storeId);		
		$date_start=date('Y-m-d');
		$date_end=date('Y-m-d',date(strtotime("+".$sett['spinwin_expiration']." day", strtotime("$date_start"))));

		//Getting the offer from database
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'spin_win_offer';
            $slices = $this->db->query($sql)->rows;

            $coupon_data = array();
            $prob_arr = array();
            foreach ($slices as $slice) {
                for ($i = 0; $i < $slice['gravity']; $i++) {
                    $prob_arr[] = $slice['id'];
                }
            }
        $key = array_rand($prob_arr);
        $slice_no=$prob_arr[$key];
       	$arr_offer =  $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_win_offer where id= '$slice_no'");
     	$offer_arr=$arr_offer->row;
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            if($this->config->get('config_language')==$language['code']){
                $lang_id=$language['language_id'];
            }
        }  
     	$arr_offer_name =  $this->db->query("SELECT label FROM " . DB_PREFIX . "spin_language_label where slice_id= '$slice_no' and store_id='$storeId' and language_id='$lang_id'");
     	$offer_arr_name=$arr_offer_name->row;
     	$offer_name= $offer_arr_name['label'];

		if($offer_arr['coupon_type']=='Fixed'){
			$discount_value=$this->currency->format($offer_arr['value'], $this->session->data['currency']);
		}else if($offer_arr['coupon_type']=='Percentage'){
			$discount_value=$offer_arr['value'].' %';
		}else{
			$discount_value=$offer_arr['coupon_type'];
		}
		if($offer_arr['value']!=0){
		$url=$this->config->get('config_url');		
		$msg=str_replace('{{coupon}}',$data['code'],$arr['spinwin_emailer_content']);
		$msg=str_replace('{{amount}}',$discount_value,$msg);
		$msg=str_replace('{{storeurl}}',$url,$msg);
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
		$mail->setSubject(html_entity_decode($arr['spinwin_email_subject'], ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($msg, ENT_QUOTES, 'UTF-8'));
		$mail->send();
		}
		$ip = $_SERVER['REMOTE_ADDR']; 
		$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
		$ip_country=$dataArray->geoplugin_countryName;
		if($ip_country==""){
			$ip_country='India';
		}
		$this->load->model('setting/setting');
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}
		if($offer_arr['value']!=0){
			if($offer_arr['coupon_type']=='Free shiping'){
				$shiping=1;
			}else{
				$shiping=0;
			}
		if($offer_arr['coupon_type']=='Fixed'){
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($offer_name) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$offer_arr['value'] . "', type = '" . $this->db->escape($offer_arr['coupon_type']) . "', total = '" . (float)$offer_arr['value'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$shiping . "', date_start = '" . $this->db->escape($date_start) . "', date_end = '" . $this->db->escape($date_end) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
                } else {
                    $this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($offer_name) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$offer_arr['value'] . "', type = '" . $this->db->escape($offer_arr['coupon_type']) . "', total = '" . (float)0.001 . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$shiping . "', date_start = '" . $this->db->escape($date_start) . "', date_end = '" . $this->db->escape($date_end) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
                }
		//Browsing Device
		require_once(DIR_SYSTEM.'library/Mobile-Detect-2.8.33/Mobile_Detect.php');
		$mobile_detect = new Mobile_Detect();
		$this->load->language('extension/module/spin_win');
		if($mobile_detect->isMobile() && !$mobile_detect->isTablet()){
			$browse_device= $this->language->get('mobile');
		}else if($mobile_detect->isMobile() && $mobile_detect->isTablet()){
			$browse_device= $this->language->get('tablet');
		}else{
			$browse_device=$this->language->get('desktop');
		}
		if($this->config->get('config_store_id')!=""){
			$storeId=$this->config->get('config_store_id');
		}else{
			$storeId=0;
		}

		$this->db->query("INSERT INTO " . DB_PREFIX . "spin_win SET coupon = '" . $this->db->escape($data['code']) . "', email = '" . $this->db->escape($data1['email']) . "', country = '" . $ip_country . "', device = '" . $browse_device."',storeId = '" . $storeId."'");
		}

		if($offer_arr['value']==0 && $offer_arr['coupon_type']!='Free shiping'){
			$output=json_encode(array('suc_msg'=>$better_luck,'code'=>"",'suc_desc'=>'','slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Loose','value'=>0,'gift_product'=>0,'code'=>""));
		}else if($offer_arr['coupon_type']!='Free shiping'){
			$output=json_encode(array('suc_msg'=>$success.$discount_value,'code'=>$data['code'],'suc_desc'=>$coupon_sent,'slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Win','value'=>$offer_arr['value'],'gift_product'=>0,'code'=>$data['code']));
		}else if($offer_arr['coupon_type']=='Free shiping'){
			$output=json_encode(array('suc_msg'=>$success.$discount_value,'code'=>$data['code'],'suc_desc'=>$coupon_sent,'slice_no'=>$offer_arr['id'],'coupon_type'=>$offer_arr['coupon_type'],'type'=>'Win','value'=>$offer_arr['value'],'gift_product'=>0,'code'=>$data['code']));
		}
		die($output);
	}

	public function getOffer(){
            $this->load->model('localisation/language');
            $languages = $this->model_localisation_language->getLanguages();
            foreach ($languages as $language) {
                if($this->config->get('config_language')==$language['code']){
                    $lang_id=$language['language_id'];
                }
            }  
            $offer = $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_win_offer as spin inner join " . DB_PREFIX . "spin_language_label as lang  on spin.id=lang.slice_id and lang.language_id='".$lang_id."' ORDER BY spin.id ASC"); 
            return $offer->rows; 
	}
	 public function getLayoutRoutes($layout_id) {
		$query = $this->db->query("SELECT route FROM " . DB_PREFIX . "layout_route WHERE layout_id = '" . (int)$layout_id . "'");
		foreach($query->row as $key=>$value){
                    return $value;
                }
    }
}

