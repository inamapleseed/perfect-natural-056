<?php
class ControllerExtensionModuleUpsellProductAddon extends Controller {
	public function index() {

		$array = array(
            'oc' => $this,
            'heading_title' => 'Upsell Product Addons',
            'modulename' => 'upsell_product_addon',
            'auto_increment' => false, // for auto increment number
            'fields' => array(
                array('type' => 'text', 'label' => 'Title', 'name' => 'title'),
                array('type' => 'text', 'label' => 'Autoplay Speed (ms)<br><i>(1s = 1000, leave blank for disabled autoplay)</i>', 'name' => 'autoplay'),
            ),
        );

        $this->modulehelper->init($array);    
	}
}
