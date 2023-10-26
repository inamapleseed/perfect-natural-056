<?php
    class ModelExtensionModuleWaitingList extends Model{
        public function add($email = '', $product_id = '', $p_no_stock = 0, $selected_pov_ids = '', $no_stock_pov_ids = ''){
            $response = array();

            $response = array(
                'code'      => 0,
                'message'   => 'Missing Parameter / Insufficient Value. Function expected 2 variables, $email and $product_id. Neither can be 0.'
            );

            if($email && $product_id){
                $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'product_waiting_list` WHERE 
                notified="0" AND 
                product_id="'.(int)$product_id.'" AND 
                selected_pov_ids = "'.$this->db->escape($selected_pov_ids).'" AND 
                email="'.$this->db->escape($email).'"');

                if($query->num_rows){
                    $response = array(
                        'code'  =>  1,
                        'message' => 'Email already added and are not notified'
                    );
                }
                else{
                    $response = array(
                        'code'  =>  2,
                        'message' => 'Email not found in the waiting list for the product_id('.(int)$product_id.') and will be added in'
                    );

                    $this->db->query('
                        INSERT INTO `'.DB_PREFIX.'product_waiting_list` SET 
                        `product_id`="'.(int)$product_id.'", 
                        `email`="'.$this->db->escape($email).'", 
                        `product_no_stock` = "'.(int)$p_no_stock.'",	
                        `selected_pov_ids` = "'.$this->db->escape($selected_pov_ids).'",	
                        `no_stock_pov_ids` = "'.$this->db->escape($no_stock_pov_ids).'",
                        date_added=NOW()
                    ');
                }
            }

            return $response;
        }

        public function getToNotified(){
            $sql = "
            SELECT 
                pwl.email,
                GROUP_CONCAT(pwl.selected_pov_ids SEPARATOR '@') as selected_pov_ids,
                GROUP_CONCAT(pwl.waiting_id SEPARATOR ',') as waiting_ids,
                GROUP_CONCAT(pd.product_id SEPARATOR ',') as product_ids,
                GROUP_CONCAT(pd.name SEPARATOR ',') as products
            FROM
                `".DB_PREFIX."product_waiting_list` pwl
            LEFT JOIN
                `".DB_PREFIX."product_description` pd
            ON
                (pd.product_id = pwl.product_id)
            WHERE
                pwl.notified = 0
                AND pd.language_id = '".(int)$this->config->get('config_language_id')."' 
                AND
                    pwl.product_id IN (
                        SELECT 
                            DISTINCT product_id FROM `".DB_PREFIX."product` 
                        WHERE 
                            quantity > 0
                            AND status = 1
                            AND date_available <= NOW()
                    ) 
            GROUP BY pwl.email
            ";

            $query = $this->db->query($sql);

            return $query->rows;
        }

        public function updateNotifiedList($notified_ids = ''){
            if($notified_ids){
                $this->db->query('UPDATE `' . DB_PREFIX . 'product_waiting_list` SET notified = 1, date_notified = NOW() WHERE waiting_id IN ('.$this->db->escape($notified_ids).')');
            }
        }

        public function getOptionName($product_option_value_id){	
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` pov, `" . DB_PREFIX . "option_value_description` ovd WHERE 	
                pov.product_option_value_id = '".$product_option_value_id."' AND 	
                    pov.option_value_id = ovd.option_value_id ");	
            return $query->row;	
        }

    }