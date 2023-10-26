<?php
    class ControllerExtensionModuleWaitingList extends Controller{
        public function index(){

            if(!$this->config->get('waiting_list_status') || !isset($this->request->get['product_id'])) return '';

            $data = $this->load->language('extension/module/waiting_list');

            $this->load->model('catalog/product');

            $data['product_id'] = (int)$this->request->get['product_id'];

            $product_info = $this->model_catalog_product->getProduct($data['product_id']);

            // if(!$product_info || $product_info['quantity'] > 0) return '';
            if(!$product_info) return '';

            $data['description'] = '';

            if($this->config->get('waiting_list_description') && text($this->config->get('waiting_list_description')) != ''){
                $data['description'] = html($this->config->get('waiting_list_description'));
            }

            $data['email']  = $this->customer->getEmail();

            return $this->load->view('extension/module/waiting_list', $data);
        }

        public function add(){
            $this->load->language('extension/module/waiting_list');

            $json = array();

            if(isset($this->request->post['email']) 
            && isset($this->request->post['product_id'])
            && !is_array($this->request->post['email'])
            && !is_array($this->request->post['product_id'])
            && (int)$this->request->post['product_id']
            && filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)
            ){
                $this->load->model('extension/module/waiting_list');
                $email = $this->db->escape(text($this->request->post['email']));
                $product_id = (int)$this->request->post['product_id'];
                $p_no_stock = $this->request->post['product_no_stock'] == 'true' ? '1' : '0';	
                $selected_pov_ids = $this->request->post['pov_ids'];	
                $no_stock_pov_ids = $this->request->post['no_stock_pov_ids'];

                $response = $this->model_extension_module_waiting_list->add($email, $product_id, $p_no_stock, $selected_pov_ids, $no_stock_pov_ids);
                $json['test']= true;
                if($response['code'] == 1){
                    $json['error_title']      =   $this->language->get('error_title');
                    $json['error_general']    =   str_replace('[EMAIL]', $email, $this->config->get('waiting_list_error'));
                }
                else{
                    $json['success_title']      =   $this->language->get('success_title');
                    $json['success_general']    =   str_replace('[EMAIL]', $email, $this->config->get('waiting_list_success'));
                }
            }

            if(!$json){
                $json['error_title']      =   $this->language->get('error_title');
                $json['error_general']    =   $this->language->get('error_general');
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function notify(){

            if(text($this->config->get('waiting_msg_title'))=='' || text($this->config->get('waiting_msg_description'))=='') return;

            $title = $this->config->get('waiting_msg_title');

            $this->load->language('extension/module/waiting_list');

            $this->load->model('extension/module/waiting_list');

            $notifications = $this->model_extension_module_waiting_list->getToNotified();

            if($notifications){
                $notified_ids = array();

                $mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html($this->config->get('config_mail_smtp_password'));
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html($this->config->get('config_name')));
                $mail->setSubject(html($title));
                
                $font_link = '<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">';
                $style_body = "font-family: 'Montserrat'; font-size: 12.5px; font-weight: 400;";
                $style_link = 'text-decoration: none; color: #2083b8;';

                $company_logo = $this->config->get('config_logo');
                if(is_file(DIR_IMAGE . $company_logo)){
                    $company_logo = '<br/><img src="' .HTTPS_SERVER . 'image/' . $company_logo . '" title="' . $this->config->get('config_name') . ' style="width: 80px;" />';
                }
                else{
                    $company_logo = false;
                }

                $notified_ids = array();
                foreach($notifications as $notify){
                    $tmp_pov_group = array();	
                    
                    if(isset($notify['email']) && text($notify['email']) != '' ){

                        //list option	
                        //check each option has stock	
                        $option_array = array();	
                        $option_stock = true;	
                        $option_no_stock_array = array();	
                        if($notify['selected_pov_ids'] && $notify['selected_pov_ids'] != ''){	
                            $tmp_pov_group = explode('@', $notify['selected_pov_ids']);	
                            foreach($tmp_pov_group as $option_index => $tmp_pov_ids){	
                                $option_str = '';	
                                    $tmp_option_array = explode(',',$tmp_pov_ids);	
                                    foreach($tmp_option_array as $tmp_option){	
                                        $option_info = $this->model_extension_module_waiting_list->getOptionName($tmp_option);	
                                        if($option_info){	
                                            $option_str .= '&emsp;<small>'.$option_info['name'].'</small><br>';	
                                            //if option substract stock is yes and quantity is 0 add loop index to no stock array	
                                            if($option_info['quantity'] == 0 && $option_info['subtract']){	
                                                $option_stock = false;	
                                                $option_no_stock_array[] = $option_index;	
                                            }	
                                        } else {	
                                            $option_str .= '';	
                                        }	
                                    }	
                                    $option_array[] = $option_str; 	
                            }	
                        }

                        $products = ''; //'<ul><li>';
                        $product_names = explode(',', $notify['products']);
                        foreach(explode(',', $notify['product_ids']) as $index => $product_id){
                            if(isset($product_names[$index])){
                                //check if product index is on no stock index then skip	
                                if(!in_array($index, $option_no_stock_array)){	
                                    $url = $this->url->link('product/product&product_id='.$product_id);	
                                    $name = $product_names[$index];	
                                    $products .= '<li><a href="' . $url . '" alt="' . $name . '" style="' . $style_link . '">'.$name.'</a>';	
                                    if(isset($option_array[$index])){	
                                        $products .= '<br>'.$option_array[$index];	
                                    }	
                                    $products .= '</li>';	
                                }
                            }
                        }
                        if(!$products) continue;

                        $products = '<ul>' . $products . '</ul>';

                        $description = str_replace('[PRODUCTS]', $products, text($this->config->get('waiting_msg_description')));
                        $description = nl2br($description);

                        $description = $font_link . '<div style="' . $style_body . '">' . $description . '</div>';

                        if($company_logo){
                            $description .= $company_logo;
                        }

                        $mail->setHtml($description);
                        $mail->setTo($notify['email']);
                        
                        $mail->send();

                        $notify_ids_array = array();

                        if($notify['waiting_ids']){	
                            $notify_ids_array = explode(',', $notify['waiting_ids']);	
                            if($notify_ids_array){	
                                foreach($notify_ids_array as $notify_index => $notify_id){	
                                    //if got stock add to notify array	
                                    if(!in_array($notify_index, $option_no_stock_array)){	
                                        $notified_ids[] = $notify_id;	
                                    }	
                                }	
                            }	
                        }
                    }
                }

                if($notified_ids){
                    $notified_ids = implode(',', $notified_ids);
                    $this->model_extension_module_waiting_list->updateNotifiedList($notified_ids);
                }
            }
        }
    }