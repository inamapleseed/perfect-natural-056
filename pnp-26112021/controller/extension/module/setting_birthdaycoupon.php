<?php
class ControllerExtensionModuleSettingBirthdaycoupon extends Controller {
	public function index() {
		// Do note that below are the sample for using module helper, you may use it in other modules
        
        $coupontype = array();
        $coupontype[0]['value'] = "P";
        $coupontype[0]['label'] = "Percentage";
        $coupontype[1]['value'] = "F";
        $coupontype[1]['label'] = "Fixed Amount";

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
            'heading_title' => 'Event Day Promotions',
            'modulename' => 'setting_birthdaycoupon',
                'fields' => array(
                    array('type' => 'dropdown', 'label' => 'Coupon Type *', 'name' => 'coupon_type', 'choices' => $coupontype),
                    array('type' => 'text', 'label' => 'Coupon Value *<br>(depends on Coupon Type)', 'name' => 'coupon_value'),
                    array('type' => 'text', 'label' => 'Minimum Spend *', 'name' => 'coupon_min_use'),
                    array('type' => 'text', 'label' => 'Coupon Code Prefix *<br>(eg. if value set is PREFIX, then Coupon Code will be PREFIX_000001, 000001 is using last Coupon ID)', 'name' => 'coupon_prefix'),
                    array('type' => 'dropdown', 'label' => 'Age Validation (18 Years old and above)', 'name' => 'age_validate', 'choices' => $choices),
                ),
        );

        $this->modulehelper->init($array);
	}
}