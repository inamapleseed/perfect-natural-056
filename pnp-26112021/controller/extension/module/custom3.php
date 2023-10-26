<?php 
class ControllerExtensionModuleCustom3 extends controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Custom 3',
            'modulename' => 'custom3',
            'fields' => array(
                array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                array('type' => 'image', 'label' => 'Logo', 'name' => 'logo'),
                array('type' => 'textarea', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'textarea', 'label' => 'Text Content', 'name' => 'text', 'ckeditor' =>true),
            ),
        );
        $this->modulehelper->init($array);
	}
}