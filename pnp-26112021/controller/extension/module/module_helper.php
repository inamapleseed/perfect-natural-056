<?php
class ControllerExtensionModuleModuleHelper extends Controller {
	public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        // autocomplete
        $ac_data = array();
        // product array
        $this->load->model('catalog/product');
        $filter_data = array(
            'start'        => 0,
            'limit'        => 9999999999999999,
        );
        $products = $this->model_catalog_product->getProducts($filter_data);
        foreach($products as $prod) {
            $ac_data[$prod['product_id']] = $prod['name'];
        }
        // product array

        // category array
        // $this->load->model('catalog/category');
        // $filter_data = array(
        //     'start'        => 0,
        //     'limit'        => 9999999999999999,
        // );
        // $ctgrs = $this->model_catalog_category->getCategories($filter_data);
				
        // foreach($ctgrs as $c) {
        //     $ac_data[$c['category_id']] = $c['name'];
        // }
        // category array
        // autocomplete

        $choices = array(
            array(
                'label' => 'Yes',
                'value' => 1,
            ),
            array(
                'label' => 'No',
                'value' => 0,
            ),
        );

		$array = array(
            'oc' => $this,
            'heading_title' => 'Sample Module Helper',
            'alerts' =>  array(
                    array( 
                            'type'          =>  'info', 
                            'dismissible'   =>  true, 
                            'msg'           =>  '<i class="fa fa-exclamation-circle"></i>&nbsp; Sample Info Message'
                        ),
                    array( 
                            'type'          =>  'warning', 
                            'dismissible'   =>  false,
                            'msg'           =>  '<i class="fa fa-exclamation-circle"></i>&nbsp; Sample Warning message'
                        ),
                    array( 
                            'type'          =>  'danger', 
                            'dismissible'   =>  true,
                            'msg'           =>  '<i class="fa fa-exclamation-circle"></i>&nbsp; Sample Danger message'
                        ),
                    array( 
                            'type'          =>  'success', 
                            'dismissible'   =>  false,
                            'msg'           =>  '<i class="fa fa-exclamation-circle"></i>&nbsp; Sample Success message'
                        )
            ), //if you no need can just make this array empty
            'modulename' => 'module_helper',
            'auto_increment' => true, // for auto increment number

            // autocomplete
            'data_array' => $ac_data,
            'autocomplete_url' => 'catalog/product/autocomplete',
            //'autocomplete_url' => 'catalog/category/autocomplete_filter',
            // autocomplete

            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'textarea', 'label' => 'Main Description 1', 'name' => 'main_description1','ckeditor'=>true),
                array('type' => 'textarea', 'label' => 'Main Description 2', 'name' => 'main_description2','ckeditor'=>true),
                array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                    'fields' => array(
                        array('type' => 'text', 'label' => 'ID', 'name' => 'id', 'readonly' => true), // for auto increment number
                        array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                        array('type' => 'text', 'label' => 'Promotion Text', 'name' => 'promotion_text'),
                        array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
                        array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
                        array('type' => 'text', 'label' => 'Button Redirect Link', 'name' => 'link'),
                        array('type' => 'image', 'label' => 'Banner Image', 'name' => 'image'),
                        array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true),
                        array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                        array('type' => 'date', 'label' => 'Date', 'name' => 'date'),
                        array('type' => 'dropdown', 'label' => 'Dropdown', 'name' => 'dropdown', 'choices' => $choices),
                    )
                ),

                array('type' => 'repeater', 'label' => 'Items 2', 'name' => 'items2',
                    'fields' => array(
                        // autocomplete
                        array('type' => 'autocomplete', 'label' => 'Products', 'name' => 'products'),
                        // autocomplete
                    )
                ),
            ),
        );

        // Without Repeater
        // $array = array(
        //     'oc' => $this,
        //     'heading_title' => 'Module Helper',
        //     'modulename' => 'module_helper',
        //     'fields' => array(
        //         array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
        //         array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
        //         array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
        //         array('type' => 'text', 'label' => 'Button Redirect Link', 'name' => 'link'),
        //         array('type' => 'image', 'label' => 'Background Image', 'name' => 'background_image'),
        //     ),
        // );

        $this->modulehelper->init($array);    
	}
}
