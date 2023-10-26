<?php 
class ControllerExtensionLalamoveApi extends Controller {
    public function createQuatation($address){
        $request_data = array();
        
        $location = $this->getLocation($this->config->get("lalamove_owner_postcode"));
        if($location['found'] > 0){
            $owner_lat = $location['results'][0]['LATITUDE'];
            $owner_lng = $location['results'][0]['LONGTITUDE'];
        }else{
            $owner_lat = 0;
            $owner_lng = 0;
        }
        
        if($address['postcode']){
            $location = $this->getLocation($address['postcode']);
            if($location['found'] > 0){
                $partner_lat = $location['results'][0]['LATITUDE'];
                $partner_lng = $location['results'][0]['LONGTITUDE'];
            }else{
                $partner_lat = 0;
                $partner_lng = 0;
            }
        }else{
            $partner_lat = 0;
            $partner_lng = 0;
        }
        
        $request_data['stops'] = array(
            array(
                "location"  => array("lat" => $owner_lat, "lng" => $owner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $this->config->get("lalamove_owner_address"),
                        "country"       => "SG"
                    ),
                ),
	        ),
	        array(
                "location"  => array("lat" => $partner_lat, "lng" => $partner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $address['address_1']." ".$address['address_2']." ".$address['postcode'],
                        "country"       => "SG"
                    ),
                ),
	        ),
        );
        
        $request_data['deliveries'][0] = array(
            "toStop" => (int)1,
            "toContact" => array(
                "name" => $address['firstname']." ".$address['lastname'],
                "phone" => "87558755",
                //"phone" => $address['telephone'],
            ),
            "remarks" => $this->getProduct(),
        );
        
        $request_data['toContact'] = array(
            "name" => $address['firstname']." ".$address['lastname'],
            "phone" => "87558755",
        );
        $request_data['toStop']     = (int)1;
        $request_data['remarks'] = $this->getProduct();
        
        
        
	$post = array();
        $url = "https://sandbox-rest.lalamove.com";
        if($this->config->get('lalamove_merchant_url')){
	        $url = $this->config->get('lalamove_merchant_url');
        }
	    /*$url = "https://sandbox-web.lalamove.com";
        if($this->config->get('lalamove_test')){
            $url = "https://sandbox-rest.lalamove.com";
        }*/
        
        if(isset($this->session->data['delivery_time_format']) && $this->session->data['delivery_time_format'] != ""){
            $time = $this->session->data['delivery_time_format'];
        }else{
            $time = 'H:i:s';
        }
        
	    if(isset($this->session->data['delivery_date']) && $this->session->data['delivery_date'] != ""){
    	    //$post['scheduleAt']         = gmdate($this->session->data['delivery_date'].'\T'.$time.'\Z');
    	    $post['scheduleAt']         = gmdate('Y-m-d\TH:i:s\Z', strtotime($this->session->data['delivery_date']." ".$time));
	    }else{
	        $today = date('Y-m-d');
    	    $post['scheduleAt']         = gmdate('Y-m-d\TH:i:s\Z', strtotime(date('Y-m-d'." ".$time, strtotime(date('Y-m-d'." ".$time)." +60 minutes"))));
    	   // $post['scheduleAt']         = gmdate('Y-m-d\T'.$time.'\Z', time() + 60 * 30);
    	    //$post['scheduleAt']         = gmdate('Y-m-d\TH:i:s\Z', time() + 60 * 30);
	    }
	    
	    if($this->config->get('lalamove_service_type') != "AUTO"){
	        $post['serviceType']        = $this->config->get('lalamove_service_type');
	    }else{
	        $total_weight = 0;
	        foreach($this->cart->getProducts() as $cart){
	             $total_weight = $total_weight + $cart['weight'];
	        }
	        
	        if($total_weight >= 0 && $total_weight < 8){
	            $post['serviceType']        = "MOTORCYCLE";
	        }else if($total_weight >= 8 && $total_weight < 20){
	            $post['serviceType']        = "CAR";
	        }else{
	            $post['serviceType']        = "MINIVAN";
	        }
	    }
	    
	    $post['stops']              = $request_data['stops'];
	    $post['deliveries']         = $request_data['deliveries'];
	        
    	$phone = str_replace(" ","",$this->config->get('lalamove_owner_contact'));
    	$contact = array("name" => $this->config->get('lalamove_owner_name'), "phone"=>$phone);
	    
        $post['requesterContact']   = $contact;
	    
        /*if($this->config->get('lalamove_remark')){
            $post['specialRequests']    = $this->config->get('lalamove_remark');
        }else{
            $post['specialRequests']    = array();
        }*/
        
        include_once (DIR_SYSTEM.'lalamove/vendor/autoload.php');
        $request = new \Lalamove\Api\LalamoveApi($url,$this->config->get('lalamove_merchant_id'),$this->config->get('lalamove_merchant_password'), 'SG');
        $result = $request->quotation($post);
        
        $data = json_decode($result,true);
        
        //$data['totalFee'] = 8;
        //$data['totalFeeCurrency'] = "SGD";
        if(isset($data['totalFee']) && $data['totalFee'] > 0){
            $data['status']     = 1;
            $data['currency']   = $data['totalFeeCurrency'];
            $data['amount']     = $data['totalFee'];
            $data['content']    = $post;
        }else{
            $data['status'] = 0;
        }
        
        return $data;
    }
    
    public function getLocation($postal_code){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://developers.onemap.sg/commonapi/search?returnGeom=Y&getAddrDetails=Y&pageNum=1&searchVal=".$postal_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
            
        curl_close($curl);
        $info = json_decode($response, true);
        
        return $info;
    }
    
    public function getProduct(){
        $string = "Order ";
        $l = 1;
        foreach($this->cart->getProducts() as $cart){
            $string .= "Item ".$l.": ".$cart['name']." X ".$cart['quantity'].", ";
        }
        return substr($string, 0, -2);
    }
    
    public function getOrderProduct($order_id){
        $order_product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE order_id = '" . (int)$order_id . "'");
        $string = "Order No:".$order_id." ";
        $l = 1;
        foreach($order_product->rows as $product){
            $string .= "Item ".$l.": ".$product['name']." X ".$product['quantity'].", ";
        }
        return substr($string, 0, -2);
    }
    
    public function getOrderProductWeight($order_id){
        $order_product = $this->db->query("SELECT p.weight FROM `" . DB_PREFIX . "order_product` op INNER JOIN `" . DB_PREFIX . "product` p ON p.product_id = op.product_id WHERE op.order_id = '" . (int)$order_id . "'");
        $total_weight = 0;
        foreach($order_product->rows as $product){
            $total_weight = $total_weight + $product['weight'];
        }
        return $total_weight;
    }
    
    public function postOrder($data){
                
        $location = $this->getLocation($this->config->get("lalamove_owner_postcode"));
        if($location['found'] > 0){
            $owner_lat = $location['results'][0]['LATITUDE'];
            $owner_lng = $location['results'][0]['LONGTITUDE'];
        }else{
            $owner_lat = 0;
            $owner_lng = 0;
        }
        
        if($data['postcode']){
            $location = $this->getLocation($data['postcode']);
            if($location['found'] > 0){
                $partner_lat = $location['results'][0]['LATITUDE'];
                $partner_lng = $location['results'][0]['LONGTITUDE'];
            }else{
                $partner_lat = 0;
                $partner_lng = 0;
            }
        }else{
            $partner_lat = 0;
            $partner_lng = 0;
        }
        
        $request_data['stops'] = array(
            array(
                "location"  => array("lat" => $owner_lat, "lng" => $owner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $this->config->get("lalamove_owner_address"),
                        "country"       => "SG"
                    ),
                ),
	        ),
	        array(
                "location"  => array("lat" => $partner_lat, "lng" => $partner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $data['address_1']." ".$data['address_2']." ".$data['postcode'],
                        "country"       => "SG"
                    ),
                ),
	        ),
        );
        
        $request_data['deliveries'][0] = array(
            "toStop" => (int)1,
            "toContact" => array(
                "name" => $data['firstname']." ".$data['lastname'],
                "phone" => $data['telephone'],
            ),
            "remarks" => $this->getOrderProduct($data['order_id']),
        );
        
        $post = array();
            $url = "https://sandbox-rest.lalamove.com";
            if($this->config->get('lalamove_merchant_url')){
    	        $url = $this->config->get('lalamove_merchant_url');
            }
            
        	$post['scheduleAt'] = $data['scheduleAt'];
        	
        	
        	if($this->config->get('lalamove_service_type') != "AUTO"){
    	        $post['serviceType']        = $this->config->get('lalamove_service_type');
    	    }else{
    	        $total_weight = $this->getOrderProductWeight($data['order_id']);
    	        
    	        if($total_weight >= 0 && $total_weight < 8){
    	            $post['serviceType']        = "MOTORCYCLE";
    	        }else if($total_weight >= 8 && $total_weight < 20){
    	            $post['serviceType']        = "CAR";
    	        }else{
    	            $post['serviceType']        = "MINIVAN";
    	        }
    	    }
	        $post['stops']              = $request_data['stops'];
	        $post['deliveries']         = $request_data['deliveries'];
	        
    	    $phone = str_replace(" ","",$this->config->get('lalamove_owner_contact'));
    	    $contact = array("name" => $this->config->get('lalamove_owner_name'), "phone"=>$phone);
	        $post['requesterContact']   = $contact;
	        $post['quotedTotalFee']     = $data['quotedTotalFee'];
                
        include_once (DIR_SYSTEM.'lalamove/vendor/autoload.php');
        $request = new \Lalamove\Api\LalamoveApi($url,$this->config->get('lalamove_merchant_id'),$this->config->get('lalamove_merchant_password'), 'SG');
        $result = $request->postOrder($post);
        $data = json_decode($result,true);
        
        if(isset($data['customerOrderId']) && $data['customerOrderId'] != ""){
            $data['status']     = 1;
            $data['customerOrderId'] = $data['customerOrderId'];
            $data['orderRef']   = $data['orderRef'];
            $data['content']    = $post;
        }else{
            $data['status'] = 0;
        }
        
        return $data;
    }
    
    public function getOrderStatus(){
	    $url = "https://sandbox-rest.lalamove.com";
        if($this->config->get('lalamove_merchant_url')){
	        $url = $this->config->get('lalamove_merchant_url');
        }
	    $order_product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "lalamove` WHERE lalamove_order_id > 0 AND lalamove_status IN ('','ASSIGNING_DRIVER','ON_GOING','PICKED_UP','EXPIRED','CANCELLED')");

        include_once (DIR_SYSTEM.'lalamove/vendor/autoload.php');
        $request = new \Lalamove\Api\LalamoveApi($url,$this->config->get('lalamove_merchant_id'),$this->config->get('lalamove_merchant_password'), 'SG');
	    foreach($order_product->rows as $order){
            $result = $request->getOrderStatus($order['lalamove_order_ref']);
            
            $data = json_decode($result,true);
            if(isset($data['status'])){
                $sql = "UPDATE ".DB_PREFIX."lalamove SET lalamove_status = '".$data['status']."', lalamove_driver_id = '".$data['driverId']."', lalamove_price = '".json_encode($data['price'])."' WHERE lalamove_id = '".$order['lalamove_id']."'";
                $this->db->query($sql);
                if($data['driverId'] > 0){
                    $result2 = $request->getDriverInfo($order['lalamove_order_ref'],$data['driverId']);
                    
                    $data2 = json_decode($result2,true);
                    
                    if(isset($data2['name'])){
                        $sql = "UPDATE ".DB_PREFIX."lalamove SET lalamove_driver_name = '".$data2['name']."', lalamove_driver_phone = '".$data2['phone']."', lalamove_driver_plate = '".json_encode($data2['plateNumber'])."', lalamove_driver_photo = '".$data2['photo']."' WHERE lalamove_id = '".$order['lalamove_id']."'";
                        $this->db->query($sql);
                    }
                }
                
                if($data['status'] == "CANCELED" || $data['status'] == "EXPIRED"){
                    $sql = "UPDATE ".DB_PREFIX."lalamove SET lalamove_cust_order_id = '' WHERE lalamove_id = '".$order['lalamove_id']."'";
                    $this->db->query($sql);
                }
            }
            
	    }
	    
    }
    public function getDriverInfo($order_id, $driverId){
        
        $sql = "SELECT * FROM ".DB_PREFIX."lalamove WHERE lalamove_order_id = '".$order_id."'";
        $info = $this->db->query($sql);
        
        $shipping_refer_id = $info->row['lalamove_order_ref'];
        
        $post = array();
        $post['merchant_id']                = $this->config->get('lalamove_merchant_id');
	    $post['merchant_password']          = $this->config->get('lalamove_merchant_password');
	    $post['lalamove_order_id']          = "85758547-0597-5009-2800-284224616021";
	    $post['lalamove_driver_id']         = $driverId;
	    $post['action']                     = "getDriverInfo";
	    
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config->get('lalamove_driver_info_link'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($post),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        
        $data = array();
        if ($err) {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['error_detail'] = $err;
        } else {
            $info = json_decode($response, true);
            if(isset($info['msg'])){
                $data['status'] = 1;
                $data['error'] = 0;
                $data['error_detail'] = $info['msg'];
            }else{
                $data['status'] = 1;
                $data['error'] = 0;
                $data['error_detail'] = "";
                $data['name']           = $info['name'];
                $data['phone']          = $info['phone'];
                $data['plateNumber']    = $info['plateNumber'];
                $data['photo']          = $info['photo'];
            }
        }
        return $data;
    }
    public function getDriverLocation($shipping_refer_id,$driverId){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config->get('lalamove_driver_location_link')."/".$shipping_refer_id."/driver/".$driverId."/location",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
            
        curl_close($curl);
        
        $data = array();
        if ($err) {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['error_detail'] = $err;
        } else {
            $info = json_decode($response, true);
            if(isset($info['msg'])){
                $data['status'] = 1;
                $data['error'] = 0;
                $data['error_detail'] = $info['msg'];
            }else{
                $data['status'] = 1;
                $data['error'] = 0;
                $data['error_detail'] = "";
                $data['location']   = $info['location'];
                $data['updatedAt']  = $info['updatedAt'];
            }
        }
        return $data;
    }
    public function cancelOrder(){
        /*$data = array("status"=>1);
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));*/
		
        $order_id = $this->request->post['order_id'];
        $sql = "SELECT * FROM ".DB_PREFIX."lalamove WHERE lalamove_order_id = '".$order_id."'";
        $info = $this->db->query($sql);
        
        $shipping_refer_id = $info->row['lalamove_cust_order_id'];
        $post = array();
        $post['merchant_id']                = $this->config->get('lalamove_merchant_id');
	    $post['merchant_password']          = $this->config->get('lalamove_merchant_password');
	    $post['order_id']                   = $order_id;
	    $post['action']                     = "cancelOrder";
	    
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->config->get('lalamove_cancel_order_link'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($post),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
            
        curl_close($curl);
        
        $data = array();
        if ($err) {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['error_detail'] = $err;
        } else {
            $info = json_decode($response, true);
            if($info['status'] == 1){
                $this->db->query("UPDATE ".DB_PREFIX."lalamove SET lalamove_cancel = '1' WHERE lalamove_order_id = '".$order_id."'");
                $this->load->model("checkout/order");
                $this->model_checkout_order->addOrderHistory($order_id,$this->config->get('lalamove_cancelled_status'),"",true);
                $data['status'] = 1;
                $data['error'] = 0;
                $data['error_detail'] = "";
            }else{
                $data['status'] = 0;
                $data['error'] = 0;
                $data['error_detail'] = "";
            }
        }
        $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
    }
    
    public function returnURL(){
        if($this->config->get('lalamove_merchant_id') == $this->request->post['merchant_id'] && $this->config->get('lalamove_merchant_password') == $this->request->post['merchant_password']){
            $order_id       = $this->request->post['order_id'];
            $order_status   = $this->request->post['status'];
            
            if(isset($this->request->post['driverInfo'])){
                $driver_info = json_decode($_POST['driverInfo'],true);
                $order_driver_name  = $driver_info['name'];
                $order_driver_phone = $driver_info['phone'];
                $order_driver_plate = $driver_info['plateNumber'];
                $order_driver_photo = $driver_info['photo'];
            }
            
            $this->db->query("UPDATE ".DB_PREFIX."lalamove SET lalamove_status = '".$order_status."',lalamove_driver_name = '".$order_driver_name."', lalamove_driver_phone = '".$order_driver_phone."', lalamove_driver_plate = '".$order_driver_plate."', lalamove_driver_photo = '".$order_driver_photo."' WHERE lalamove_order_id = '".$order_id."'");
            if($order_status == "COMPLETED"){
                $this->load->model("checkout/order");
                $this->model_checkout_order->addOrderHistory($order_id,$this->config->get('lalamove_completed_status'),"",true);
            }
            
            /*if($order_driver != ""){
                $info = $this->getDriverInfo($order_id, $order_driver);
                if($info['status']){
                    $this->db->query("UPDATE ".DB_PREFIX."lalamove SET lalamove_driver_name = '".$info['name']."', lalamove_driver_phone = '".$info['phone']."', lalamove_driver_plate = '".$info['plateNumber']."', lalamove_driver_photo = '".$info['photo']."' WHERE lalamove_order_id = '".$order_id."'");
                }
            }*/
            return "200";
        }else{
            return "401";
        }
    }
    
    private function makeSign($payload, $shared_key){
        ksort($payload);
   
        $has_nonce = false;
        $buff = [];
        foreach ($payload as $key => $value) {
        if ($key == 'nonce') {
            $has_nonce = true;
        }
        if ($key == 'signature') {
            continue;
        }
        $buff[] = ($key . '=' . $value);
        }
       
        if (!$has_nonce) {
            // Refuse to sign because empty nonce may introduce security issues.
            throw new Exception('Cannot sign payload without nonce.');
        }
        $buff[] = ('shared_key=' . $shared_key);
        $params = implode("&", $buff);
       
        return hash("sha256", $params);
    }
    
    public function createBackendQuatation(){
        
        $request_data = array();
        
        $location = $this->getLocation($this->config->get("lalamove_owner_postcode"));
        if($location['found'] > 0){
            $owner_lat = $location['results'][0]['LATITUDE'];
            $owner_lng = $location['results'][0]['LONGTITUDE'];
        }else{
            $owner_lat = 0;
            $owner_lng = 0;
        }
        
        if(isset($this->request->post['order_shipping_postal_code'])){
            $location = $this->getLocation($this->request->post['order_shipping_postal_code']);
            if($location['found'] > 0){
                $partner_lat = $location['results'][0]['LATITUDE'];
                $partner_lng = $location['results'][0]['LONGTITUDE'];
            }else{
                $partner_lat = 0;
                $partner_lng = 0;
            }
        }else{
            $partner_lat = 0;
            $partner_lng = 0;
        }
        
        $request_data['stops'] = array(
            array(
                "location"  => array("lat" => $owner_lat, "lng" => $owner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $this->config->get("lalamove_owner_address"),
                        "country"       => "SG"
                    ),
                ),
	        ),
	        array(
                "location"  => array("lat" => $partner_lat, "lng" => $partner_lng),
                "addresses" => array(
                    "en_SG" => array(
                        "displayString" => $this->request->post['order_shipping_to'],
                        "country"       => "SG"
                    ),
                ),
	        ),
        );
        
        $request_data['deliveries'][0] = array(
            "toStop" => (int)1,
            "toContact" => array(
                "name" => $this->request->post['order_shipping_contact'],
                "phone" => $this->request->post['order_shipping_mobile'],
            ),
            "remarks" => $this->getOrderProduct($this->request->post['order_id']),
        );
        
        $request_data['toContact'] = array(
            "name" => $this->request->post['order_shipping_contact'],
            "phone" => $this->request->post['order_shipping_mobile'],
        );
        
        $url = "https://sandbox-rest.lalamove.com";
        if($this->config->get('lalamove_merchant_url')){
	        $url = $this->config->get('lalamove_merchant_url');
        }
    	
    	$post = array();
        $post['scheduleAt']         = gmdate('Y-m-d\TH:i:s\Z', strtotime($this->request->post['order_shipping_date']." ".$this->request->post['order_shipping_time']));
	    $post['serviceType']        = $this->request->post['shipping_type'];
	    $post['stops']              = $request_data['stops'];
	    $post['deliveries']         = $request_data['deliveries'];
	        
        if(isset($this->request->post['submit'])){
	        $post['quotedTotalFee']['amount'] = $this->request->post['order_shippingamount'];
	        $post['quotedTotalFee']['currency'] = "SGD";
        }
	        
    	$phone = str_replace(" ","",$this->config->get('lalamove_owner_contact'));
    	$contact = array("name" => $this->request->post['order_shipping_contact'], "phone" => $this->request->post['order_shipping_mobile']);
	    $post['requesterContact']   = $contact;
	    
        include_once (DIR_SYSTEM.'lalamove/vendor/autoload.php');
        $request = new \Lalamove\Api\LalamoveApi($url,$this->config->get('lalamove_merchant_id'),$this->config->get('lalamove_merchant_password'), 'SG');
        if(isset($this->request->post['submit'])){
            $result = $request->postOrder($post);
        }else{
            $result = $request->quotation($post);
        }
        $data = json_decode($result,true);
        
        if(isset($this->request->post['submit'])){
            if(isset($data['customerOrderId']) && $data['orderRef'] > 0){
                $sql = "UPDATE ".DB_PREFIX."lalamove SET lalamove_cust_order_id = '".$data['customerOrderId']."', lalamove_order_ref = '".$data['orderRef']."', lalamove_order_content = '".json_encode($post)."' WHERE lalamove_order_id = '".$this->request->post['order_id']."'";
                $this->db->query($sql);
                $data['status'] = 1;
                $data['customerOrderId'] = $data['customerOrderId'];
                $data['orderRef'] = $data['orderRef'];
            }else{
                $data['status'] = 0;
            }
        }else{
            if(isset($data['totalFee']) && $data['totalFee'] > 0){
                $sql = "SELECT * FROM ".DB_PREFIX."lalamove WHERE lalamove_order_id = '".$this->request->post['order_id']."'";
                $info = $this->db->query($sql);
                if($info->num_rows){
                    $this->db->query("UPDATE ".DB_PREFIX."lalamove SET lalamove_currency = '".$data['totalFeeCurrency']."', lalamove_amount = '".$data['totalFee']."', lalamove_content = '".json_encode($post)."', lalamove_order_id = '".$this->request->post['order_id']."', date_added = NOW() WHERE lalamove_id = '".$info->row['lalamove_id']."'");
                }else{
                    $this->db->query("INSERT INTO ".DB_PREFIX."lalamove SET lalamove_currency = '".$data['totalFeeCurrency']."', lalamove_amount = '".$data['totalFee']."', lalamove_content = '".json_encode($post)."', lalamove_order_id = '".$this->request->post['order_id']."', date_added = NOW()");
                }
                $data['status']     = 1;
                $data['currency']   = $data['totalFeeCurrency'];
                $data['amount']     = $data['totalFee'];
            }else{
                $data['status'] = 0;
            }
        }
        
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
    }
    
}
?>