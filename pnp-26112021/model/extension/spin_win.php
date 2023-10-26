<?php
class ModelExtensionSpinWin extends Model {
    public function install() {
            $this->db->query("
            CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "spin_win(
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `storeId` varchar(255) NOT NULL,
                        `coupon` varchar(255) NOT NULL,
                        `email` varchar(255) NOT NULL,
                        `country` varchar(255) NOT NULL,
                        `device` varchar(255) NOT NULL,
                        `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                       ) ENGINE=InnoDB DEFAULT CHARSET=latin1");

            $this->db->query("
            CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "spin_win_offer(
                 `id` int(11) NOT NULL AUTO_INCREMENT,
                 `store_id` int(11) NOT NULL,
                 `coupon_type` varchar(255) NOT NULL,
                 `value` varchar(255) NOT NULL,
                 `gravity` varchar(255) NOT NULL,
                 `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                 PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");

            $this->db->query("INSERT INTO " . DB_PREFIX . "spin_win_offer (store_id,coupon_type,value,gravity) VALUES ('0','Fixed','5','35'),
                ('0','Fixed','0','0'),
                ('0','Fixed','15','10'),
                ('0','Fixed','0','5'),
                ('0','Fixed','20','15'),
                ('0','Fixed','0','0'),
                ('0','Fixed','5','5'),
                ('0','Fixed','5','5'),
                ('0','Fixed','5','5'),
                ('0','Fixed','5','5'),
                ('0','Fixed','5','5'),
                ('0','Fixed','5','5')");

            $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "spin_language_label(
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `slice_id` int(11) NOT NULL,
                         `store_id` int(11) NOT NULL,
                         `language_id` int(11) NOT NULL,
                         `label` varchar(255) NOT NULL,
                         `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1");
        $this->load->model('localisation/language');    
        $languages = $this->model_localisation_language->getLanguages();
        foreach($languages as $language) {	
	    $lang_id=$language['language_id'];	
            $this->db->query("INSERT INTO " . DB_PREFIX . "spin_language_label (slice_id,store_id,language_id,label) VALUES ('1','0','".$lang_id."','10% OFF'),
                ('2','0','".$lang_id."','Not Lucky Today'),
                ('3','0','".$lang_id."','15% OFF'),
                ('4','0','".$lang_id."','Oops!Sorry'),
                ('5','0','".$lang_id."','20% OFF'),
                ('6','0','".$lang_id."','Not Lucky Today'),
                ('7','0','".$lang_id."','25% OFF'),
                ('8','0','".$lang_id."','10% OFF'),
                ('9','0','".$lang_id."','10% OFF'),
                ('10','0','".$lang_id."','10% OFF'),
                ('11','0','".$lang_id."','10% OFF'),
                ('12','0','".$lang_id."','10% OFF')");
	     }		
        }
    public function uninstall() {
        
        $this->db->query("DROP TABLE " . DB_PREFIX . "spin_win");
        $this->db->query("DROP TABLE " . DB_PREFIX . "spin_language_label");
        $this->db->query("DROP TABLE " . DB_PREFIX . "spin_win_offer");
    }         
    public function getCoupons($data,$id){                       

        $sql = "SELECT * FROM " . DB_PREFIX . "spin_win WHERE storeId='$id' ORDER BY id DESC";
        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $coupons = $this->db->query($sql);
        return $coupons->rows;            
    }

    public function getTotalCoupon($id){                       

        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "spin_win WHERE storeId='$id'");

        return $query->row['total'];
            
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

    public function getSelected($data,$lang){   
    extract($data);
        foreach ($lang as $val) {
          foreach ($val as  $value) {
            $lang_id=$value['language_id'];
            $offer = $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_win_offer as spin inner join " . DB_PREFIX . "spin_language_label as lang  on spin.id=lang.slice_id WHERE spin.id='$id' and lang.language_id='$lang_id'");        
            $arr["$lang_id"]= $offer->row;
          }
        }     
        return $arr;            
    }

    public function updateOffer($data){                       
        extract($data);
        $is_num=is_numeric($offer_value);
        $is_num1=is_numeric($offer_gravity);
        if($offer_value==""){
            $output=json_encode(array('type'=>'coupon_error'));
            die($output);
        }
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        foreach ($languages as $language) {
            if($this->config->get('config_language')==$language['code']){
                $lang_id=$language['language_id'];
            }
        } 
        if($offer_label[$lang_id]==""){
            $output=json_encode(array('type'=>'label_error'));
            die($output);
        }

        if($discount_type=='Percentage' && $offer_value>100 ){
            $output=json_encode(array('type'=>'coupon_error_per'));
            die($output);
        }

        if($is_num!=1 || $offer_value<0){
            $output=json_encode(array('type'=>'coupon_error_num'));
            die($output);
        }
        if($is_num1!=1 || $offer_gravity<0){
            $output=json_encode(array('type'=>'gravity_error_num'));
            die($output);
        }

        if($offer_gravity!="" && $offer_gravity>=0){
            $offer = $this->db->query("SELECT SUM(gravity) as gravity FROM " . DB_PREFIX . "spin_win_offer WHERE id!='$offer_id'");
            $arr=$offer->row;
            $gravity=$arr['gravity']+$offer_gravity;
            if($gravity<0 || $gravity>100){
                $output=json_encode(array('type'=>'gravity_value_error','sum'=>$gravity));
                die($output);
            }
        }else{
            $output=json_encode(array('type'=>'gravity_error'));
            die($output);
        }
       
        $this->db->query("UPDATE " . DB_PREFIX . "spin_win_offer SET coupon_type='" . $this->db->escape($discount_type) . "',value='" . $this->db->escape($offer_value) . "',gravity='" . $this->db->escape($offer_gravity) . "' WHERE id='$offer_id'");
        foreach ($offer_label as $key => $value) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "spin_language_label WHERE slice_id='$offer_id' and language_id='$key' and store_id='$store_id'");
            $num=$query->num_rows;
            if($num>0){
                if($value!=""){
                $this->db->query("UPDATE " . DB_PREFIX . "spin_language_label SET language_id='$key',label='" . $this->db->escape($value) . "' WHERE slice_id='$offer_id' and language_id='$key' and store_id='$store_id'");                    
                }
            }else if($num==0){
                if($value!=""){
                $this->db->query("INSERT INTO " . DB_PREFIX . "spin_language_label (slice_id,store_id,language_id,label) VALUES ('$offer_id','$store_id','$key','" . $this->db->escape($value) . "')");   
                }
            }
        }
        $output=json_encode(array('type'=>'success'));
        die($output);     
            
    }

    public function getUsedCouponsByMonth() {
       
        $order_data = array();

        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $order_data[date('j', strtotime($date))] = array(
                'day'   => date('d', strtotime($date)),
                'total' => 0
            );
        }

        $query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "coupon_history` WHERE DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");

        foreach ($query->rows as $result) {
            $order_data[date('j', strtotime($result['date_added']))] = array(
                'day'   => date('d', strtotime($result['date_added'])),
                'total' => $result['total']
            );
        }

        return $order_data;
    }

    public function getUnusedCouponsByMonth() {        

        $order_data = array();

        for ($i = 1; $i <= date('t'); $i++) {
            $date = date('Y') . '-' . date('m') . '-' . $i;

            $order_data[date('j', strtotime($date))] = array(
                'day'   => date('d', strtotime($date)),
                'total' => 0
            );
        }

        $query1 = $this->db->query("SELECT coupon_id FROM `" . DB_PREFIX . "coupon_history` ");
        $used_coupon=$query1->rows;
        $used_values='';
        $i=0;
        foreach ($used_coupon as $value) {
            if(count($used_coupon)-1==$i){
                $used_values.="'".$value['coupon_id']."'";
            }else{
                $used_values.="'".$value['coupon_id']."',";
            }
            
        $i++;
        } 
       if($used_values!=""){
             $used_values='('.$used_values.')'; 
            $sql="SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "coupon` WHERE coupon_id NOT IN $used_values AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "'  GROUP BY DATE(date_added)";
        $query = $this->db->query($sql)->rows;
        }else{
            $sql="SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "coupon` WHERE coupon_id NOT IN ('') AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "'  GROUP BY DATE(date_added)";
        $query = $this->db->query($sql)->rows;
        }

        foreach ($query as $result) {
            $order_data[date('j', strtotime($result['date_added']))] = array(
                'day'   => date('d', strtotime($result['date_added'])),
                'total' => $result['total']
            );
        }

        return $order_data;
    }
}
