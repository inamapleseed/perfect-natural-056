<?php
class ControllerExtensionModuleAnnouncementBar extends Controller {
    public function index() {
        // Do note that below are the sample for using module helper, you may use it in other modules

        $position = array();
        $position[0]['value'] = "left";
        $position[0]['label'] = "Left";
        $position[1]['value'] = 'center';
        $position[1]['label'] = "Center";
        $position[2]['value'] = 'right';
        $position[2]['label'] = "Right";

        $running = array();
        $running[0]['value'] = 0;
        $running[0]['label'] = "Disable";
        $running[1]['value'] = 1;
        $running[1]['label'] = "Enable";
        $running[2]['value'] = 2;
        $running[2]['label'] = "Mobile Only";

        $array = array(
            'oc' => $this,
            'heading_title' => 'Announcement Bar',
            'modulename' => 'announcement_bar',
            'fields' => array(
                array('type' => 'text', 'label' => 'Background Color', 'name' => 'background_color'),
                array('type' => 'image', 'label' => 'Icon', 'name' => 'icon'),
                array('type' => 'textarea', 'label' => 'Title', 'name' => 'title', 'ckeditor' => true),
                array('type' => 'text', 'label' => 'Title color', 'name' => 'text_color'),
                array('type' => 'dropdown', 'label' => 'Title position', 'name' => 'position', 'choices' => $position),
                array('type' => 'text', 'label' => 'Padding', 'name' => 'padding'),
                array('type' => 'dropdown', 'label' => 'Sliding Text', 'name' => 'running', 'choices' => $running),
            ),
        );

        $this->modulehelper->init($array);
    }
}