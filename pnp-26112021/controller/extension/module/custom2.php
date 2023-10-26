<?php 
class ControllerExtensionModuleCustom2 extends controller {
	public function index() {
		$array = array(
            'oc' => $this,
            'heading_title' => 'Homepage UI',
            'modulename' => 'custom2',
            'fields' => array(
                array('type' => 'textarea', 'label' => 'Title', 'name' => 'title'),

                array('type' => 'repeater', 'label' => 'Content', 'name' => 'repeater', 'fields' => array(
                    array('type' => 'image', 'label' => 'Image', 'name' => 'image1'),
                    array('type' => 'text', 'label' => 'Text ', 'name' => 'text1'),
                    )
                ),
                array('type' => 'textarea', 'label' => 'Text Content', 'name' => 'text', 'ckeditor' =>true),

                array('type' => 'repeater', 'label' => 'Content 2', 'name' => 'repeater2', 'fields' => array(
                    array('type' => 'image', 'label' => 'Image', 'name' => 'image2'),
                    array('type' => 'textarea', 'label' => 'Text', 'name' => 'text2'),
                    )
                ),
                array('type' => 'image', 'label' => 'Image Design', 'name' => 'design2'),
                array('type' => 'image', 'label' => 'Image Design', 'name' => 'design2b'),

            ),
        );
        $this->modulehelper->init($array);
	}
}