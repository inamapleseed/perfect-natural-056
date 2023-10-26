<?php
class ControllerExtensionModuleLoyaltyProgram extends Controller {
	public function index() {

		$array = array(
            'oc' => $this,
            'heading_title' => 'Loyalty Program',
            'modulename' => 'loyalty_program',
            'auto_increment' => false, // for auto increment number
            'fields' => array(
            ),
        );

        $this->modulehelper->init($array);    
	}
}
