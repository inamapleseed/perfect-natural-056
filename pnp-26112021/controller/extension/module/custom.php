<?php 
class ControllerExtensionModuleCustom extends controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Homepage About',
            'modulename' => 'custom',
            'fields' => array(
                array('type' => 'image', 'label' => 'Image', 'name' => 'image'),
                array('type' => 'textarea', 'label' => 'Text Content', 'name' => 'text', 'ckeditor' =>true),
                array('type' => 'text', 'label' => 'Button URL', 'name' => 'url'),
                array('type' => 'text', 'label' => 'Button Title', 'name' => 'btntitle'),
            ),
        );
        $this->modulehelper->init($array);
	}
}