<?php
class ModelExtensionShippingLalamove extends Model {
	function getQuote($address) {
            
            $this->load->language('extension/shipping/lalamove');

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('lalamove_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

            if (!$this->config->get('lalamove_geo_zone_id')) {
                    $status = true;
            } elseif ($query->num_rows) {
                    $status = true;
            } else {
                    $status = false;
            }

            if ($this->cart->getSubTotal() < $this->config->get('lalamove_total')) {
                    $status = false;
            }
            
            $method_data = array();

            if($this->config->get('lalamove_display')){
                
                $info = $this->load->controller('extension/lalamove_api/createQuatation',$address);
                $price = 0;
    
                if($info['status'] && isset($info['amount'])){
                    $this->db->query("INSERT INTO ".DB_PREFIX."lalamove SET lalamove_currency = '".$info['currency']."', lalamove_amount = '".$info['amount']."', lalamove_content = '".json_encode($info['content'])."', date_added = NOW()");
                    $lalamove_id = $this->db->getLastId();
                    $this->session->data['lalamove_id'] = $lalamove_id;
    
                    $price = $info['amount'];
                    $status = true;
                }else{
                    $status = false;
                }
        
            
                if ($status) {
                        $quote_data = array();
    
                        $quote_data['lalamove'] = array(
                                'code'         => 'lalamove.lalamove',
                                'title'        => $this->language->get('text_description'),
                                'cost'         => $price,
                                'tax_class_id' => 0,
                                'text'         => $this->currency->format($price, $this->session->data['currency'])
                        );
    
                        $method_data = array(
                                'code'       => 'lalamove',
                                'title'      => $this->language->get('text_title'),
                                'quote'      => $quote_data,
                                'sort_order' => $this->config->get('lalamove_sort_order'),
                                'error'      => false
                        );
                }
            }
            return $method_data;
	}
}