<?php
    class ControllerExtensionModuleWaitingList extends Controller {
        private $error = array();
        private $limit = 50;

        public function index(){
            $data = $this->load->language('extension/module/waiting_list');

            $this->load->model('extension/module/waiting_list');

            $this->load->model('setting/setting');

            // Save
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_setting_setting->editSetting('waiting_list', $this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
            }
            // ---
         
            // Errors

            $data['warning'] =  '';

            if(isset($this->error['warning'])){
                $data['warning'] =  $this->error['warning'];
            }

            // ---

            // Variables
            $cron_url = HTTPS_CATALOG . 'index.php?route=extension/module/waiting_list/notify';
            $data['base_url']           =   HTTPS_CATALOG;
            $data['cron_job_settings']  =   sprintf($data['cron_job_settings'], $cron_url, $cron_url);

            $token = $data['token'] = $this->session->data['token'];

            $page = 1;

            if(isset($this->request->get['page']))
                $page = (int)$this->request->get['page'];
            
            $data['action'] = $this->url->link('extension/module/waiting_list', 'token=' . $token, true);

            $this->limit = 20;

            $data['waiting_products'] = $this->model_extension_module_waiting_list->getProductWaiting($page, $this->limit);
            
            if($data['waiting_products']){	
                foreach($data['waiting_products'] as $key => $w_product){	
                    //populate email list	
                    $email_str = '';	
                    $id_str = '';	
                    $email_list = $this->model_extension_module_waiting_list->getWaitingListEmail($data['waiting_products'][$key]['product_id'],$data['waiting_products'][$key]['selected_pov_ids']);	
                    if($email_list){	
                        foreach($email_list as $email){	
                            $email_str .= '<a href="mailto:'.$email['email'].'">'. $email['email'].'</a>&emsp;<a class="deleteWaitingRecord" onclick="deleteRecord(\''.$email['waiting_id'].'\')">&#10006;</a><br>';	
                            $id_str .= $email['waiting_id'].',';	
                            	
                        }	
                        $data['waiting_products'][$key]['email_list'] = $email_str;	
                        $data['waiting_products'][$key]['waiting_id_list'] = rtrim($id_str,',');	
                    }	
                    //populate option list	
                    $option_str = '';	
                    $tmp_option_array = array();		
                    if($w_product['selected_pov_ids']){	
                        $tmp_option_array = explode(',',$w_product['selected_pov_ids']);	
                        foreach($tmp_option_array as $tmp_option){	
                            $option_info = $this->model_extension_module_waiting_list->getOptionName($tmp_option);	
                            if($option_info){	
                                $option_str .= '&emsp;<small>'.$option_info['name'].'</small><br>';	
                            } else {	
                                $option_str .= '&emsp;<small>Missing Data</small><br>';	
                            }	
                        }	
                    }	
                    $data['waiting_products'][$key]['option_list'] = $option_str;	
                    $data['waiting_products'][$key]['option_list_id'] = $w_product['selected_pov_ids'];	
                    $data['waiting_products'][$key]['product_link'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'].'&product_id='.$w_product['product_id']);	
                }	
            }
            $total_waiting = $this->model_extension_module_waiting_list->getTotalProductWaiting();

            // Waiting List

            $data['error_waiting_list_success'] = '';
            if(isset($this->error['waiting_list_success'])){
                $data['error_waiting_list_success'] = $this->error['waiting_list_success'];
            }

            $data['error_waiting_list_error'] = '';
            if(isset($this->error['waiting_list_error'])){
                $data['error_waiting_list_error'] = $this->error['waiting_list_error'];
            }

            $data['waiting_list_status'] = $this->config->get('waiting_list_status');

            if(isset($this->request->post['waiting_list_status'])){
                $data['waiting_list_status'] = $this->request->post['waiting_list_status'];
            }

            $data['waiting_list_success'] = $this->config->get('waiting_list_success');

            if(isset($this->request->post['waiting_list_success'])){
                $data['waiting_list_success'] = $this->request->post['waiting_list_success'];
            }

            $data['waiting_list_error'] = $this->config->get('waiting_list_error');

            if(isset($this->request->post['waiting_list_error'])){
                $data['waiting_list_error'] = $this->request->post['waiting_list_error'];
            }

            $data['waiting_list_description'] = $this->config->get('waiting_list_description');

            if(isset($this->request->post['waiting_list_description'])){
                $data['waiting_list_description'] = $this->request->post['waiting_list_description'];
            }
            // ---

            // Waiting Msg
            $data['waiting_msg_title'] = $this->config->get('waiting_msg_title');

            if(isset($this->request->post['waiting_msg_title'])){
                $data['waiting_msg_title'] = $this->request->post['waiting_msg_title'];
            }

            $data['waiting_msg_description'] = $this->config->get('waiting_msg_description');

            if(isset($this->request->post['waiting_msg_description'])){
                $data['waiting_msg_description'] = $this->request->post['waiting_msg_description'];
            }
            // ---
            
            $data['pg'] = array(
                $total_waiting,
                $page,
                $this->limit,
                $this->url->link('extension/module/waiting_list', 'token=' . $token . '&page={page}', true)
            );

            // ---

            // Don't use in other projects ...
            $this->craft->pagination($data);
            $this->craft->page($this, $data); 
        }

        protected function validate() {
            if (!$this->user->hasPermission('modify', 'extension/module/waiting_list')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }

            if(utf8_strlen(text($this->request->post['waiting_list_success'])) < 1){
                $this->error['waiting_list_success'] = $this->language->get('field_required');
            }

            if(utf8_strlen(text($this->request->post['waiting_list_error'])) < 1){
                $this->error['waiting_list_error'] = $this->language->get('field_required');
            }
    
            return !$this->error;
        }

        public function saveMsg(){
            $json = '';
            $this->load->language('extension/module/waiting_list');

            if(
                isset($this->request->post['waiting_msg_title'])
                && isset($this->request->post['waiting_msg_description'])
                && trim($this->request->post['waiting_msg_title']) != ''
                && trim($this->request->post['waiting_msg_description']) != ''
            ){  
                $this->load->model('setting/setting');

                $this->model_setting_setting->editSetting('waiting_msg', $this->request->post);

                $json = $this->language->get('text_success_saved');
            }   

            if(!$json){
                $json = $this->language->get('error_inputs');
            }

            $this->response->addHeader('Content-type: application/json');
            $this->response->setOutput(json_encode($json));
        }

        public function deleteWaitingRecord(){	
            $json['success'] = 0;	
            $this->load->model('extension/module/waiting_list');	
            if(isset($this->request->post['waiting_ids'])){	
                $waiting_ids = explode(',',$this->request->post['waiting_ids']);	
                foreach ($waiting_ids as $waiting_id) {	
                    $this->model_extension_module_waiting_list->deleteWaitingRecord($waiting_id);	
                }	
                $json['success'] = 1;	
            }	
            $this->response->addHeader('Content-type: application/json');	
            $this->response->setOutput(json_encode($json));	
        }
    }