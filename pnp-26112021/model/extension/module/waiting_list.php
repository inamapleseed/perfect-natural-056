<?php
    class ModelExtensionModuleWaitingList extends Model {
        public function getProductWaiting($page = 1, $limit){
            $page = ($page - 1) * $limit;

            $query = $this->db->query('SELECT pd.name, pd.product_id, pwl.selected_pov_ids, pwl.waiting_id, COUNT(pwl.email) as request 
            FROM `' . DB_PREFIX . 'product_waiting_list` pwl LEFT JOIN `' . DB_PREFIX . 'product_description` pd ON (pwl.product_id = pd.product_id) 
            WHERE pd.language_id=1 AND notified = 0
            GROUP BY pwl.product_id, pwl.selected_pov_ids ORDER BY request DESC LIMIT '.$page.','.$limit);

            return $query->rows;
        }

        public function getTotalProductWaiting(){
            $query = $this->db->query('SELECT pd.name, COUNT(pwl.email) as request 
            FROM `' . DB_PREFIX . 'product_waiting_list` pwl LEFT JOIN `' . DB_PREFIX . 'product_description` pd ON (pwl.product_id = pd.product_id) 
            WHERE pd.language_id=1 AND notified = 0
            GROUP BY pwl.product_id, pwl.selected_pov_ids ORDER BY request DESC');

            return $query->num_rows;
        }

        // << CHECK OPTION STOCK	
        public function getWaitingListEmail($product_id, $selected_pov_ids){	
            $query = $this->db->query("SELECT email, waiting_id FROM `" . DB_PREFIX . "product_waiting_list` WHERE product_id = '".$product_id."' AND selected_pov_ids = '".$selected_pov_ids."' AND notified = 0");	
            return $query->rows;	
        }	
        	
        public function getOptionName($product_option_value_id){	
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_option_value` pov, `" . DB_PREFIX . "option_value_description` ovd WHERE 	
                pov.product_option_value_id = '".$product_option_value_id."' AND 	
                    pov.option_value_id = ovd.option_value_id ");	
            return $query->row;	
        }	
        	
        public function deleteWaitingRecord($waiting_id){	
            $this->db->query("DELETE FROM " . DB_PREFIX . "product_waiting_list WHERE waiting_id = '" . (int)$waiting_id . "'");	
        }	
        // >> CHECK OPTION STOCK
    }