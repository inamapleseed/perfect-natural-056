<?php
    class ControllerComponentSticker extends Controller{
        public function index($product_id){
            $stickers = $this->config->get('sticker');

            if(!is_array($stickers) || !$product_id ) return;

            $sticker_info = array();

            if(!$product_id) return $sticker_info;

            //debug($stickers);
            $lang_id = $this->config->get('config_language_id');
            
            $this->load->model('tool/image');
            
            foreach($stickers as $sticker){
                
                if( isset($sticker['products']) 
                &&  is_array($sticker['products']) 
                &&  $sticker['products']
                &&  in_array($product_id, $sticker['products'])) {
                    
                    $sticker_image = '';
                    if($sticker['image']){
                        $sticker_image = $this->model_tool_image->resize($sticker['image'], 50, 50);
                    }
                    $sticker_info = array(
                        'name'              =>  isset($sticker['name'][$lang_id])?trim($sticker['name'][$lang_id]):'',
                        'color'             =>  $sticker['label_color'],
                        'image'             =>  $sticker_image,
                        'background-color'  =>  $sticker['sticker_color'],                        
                    );
                }
            }
            return $sticker_info;
        }
    }