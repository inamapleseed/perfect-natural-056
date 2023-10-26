<?php
class ControllerExtensionModuleSettingFacebookevents extends Controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Setting - Facebook Events',
            'alerts' =>  array(
                    array( 
                            'type'          =>  'info', 
                            'dismissible'   =>  true, 
                            'msg'           =>  '<i class="fa fa-exclamation-circle"></i>&nbsp; This extension is to enable Facebook Events.'
                        )
            ), //if you no need can just make this array empty
            'modulename' => 'setting_facebookevents',
            'auto_increment' => true, // for auto increment number

            'fields' => array(
                // array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                // array('type' => 'textarea', 'label' => 'Main Description 1', 'name' => 'main_description1','ckeditor'=>true),
                // array('type' => 'textarea', 'label' => 'Main Description 2', 'name' => 'main_description2','ckeditor'=>true),
                // array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                // array('type' => 'repeater', 'label' => 'Items', 'name' => 'items',
                //     'fields' => array(
                //         array('type' => 'text', 'label' => 'ID', 'name' => 'id', 'readonly' => true), // for auto increment number
                //         array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                //         array('type' => 'text', 'label' => 'Promotion Text', 'name' => 'promotion_text'),
                //         array('type' => 'text', 'label' => 'Text', 'name' => 'text'),
                //         array('type' => 'text', 'label' => 'Button Label', 'name' => 'label'),
                //         array('type' => 'text', 'label' => 'Button Redirect Link', 'name' => 'link'),
                //         array('type' => 'image', 'label' => 'Banner Image', 'name' => 'image'),
                //         array('type' => 'textarea', 'label' => 'Description', 'name' => 'description','ckeditor'=>true),
                //         array('type' => 'upload', 'label' => 'Upload', 'name' => 'upload'),
                //         array('type' => 'date', 'label' => 'Date', 'name' => 'date'),
                //         array('type' => 'dropdown', 'label' => 'Dropdown', 'name' => 'dropdown', 'choices' => $choices),
                //     )
                // ),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
