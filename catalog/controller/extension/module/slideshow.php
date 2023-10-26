<?php
class ControllerExtensionModuleSlideshow extends Controller {
	public function index($setting) {
		static $module = 0;		

		$this->load->model('design/banner');
		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.css');
		$this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

		//$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
		//$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');

		$data['banners'] = array();

		$results = $this->model_design_banner->getBanner($setting['banner_id']);

		// debug($setting);

		$data['dots'] = $setting['dots'];
		$data['arrows'] = $setting['arrows'];
		$data['autoplayspeed'] = $setting['autoplayspeed'];

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['image'])) {

				// Windows server might need to explode: \r or \r\n or \n
				// Linux server only need to explode \n or PHP_EOL
				// Note: PHP_EOL do not necessary equals to \r\n
				if(trim($result['description']) != ''){
					$spliter = "\n";
					$result['description'] = explode($spliter, $result['description']);
					
					foreach($result['description'] as $index => &$each){
						$each = '<span class="slideshow-text-' . $index . '">' . $each . '</span>';
					}

					$result['description'] = implode($spliter, $result['description']);
				}
				
				if(!is_file(DIR_IMAGE . $result['mobile_image'])){
					$result['mobile_image'] = $result['image'];
				}

				$data['banners'][] = array(
					'title'			=> $result['title'],
					'description'	=> $result['description'],
					'position'	=> $result['position'],
					'desc_font'	=> $result['desc_font'],
					'title_font'	=> $result['title_font'],
					'design_image'	=> $result['design_image'],
					'desc_color'	=> $result['desc_color'],
					'text_adj'	=> $result['text_adj'],
					'text_adj2'	=> $result['text_adj2'],
					'sub_textpos'	=> $result['sub_textpos'],
					'link_text' 	=> $result['link_text'],
					'link' 			=> $result['link'],
					'theme' 		=> $result['color_theme'],
					'mobile_theme' 	=> 'mobile_' . $result['mobile_color_theme'],
					'image' 		=> $this->model_tool_image->resize(
										$result['image'], 
										$setting['width'], 
										$setting['height'], 'a'),
					'mobile_image' 	=> $this->model_tool_image->resize(
										$result['mobile_image'], 
										$setting['mobile_width'], 
										$setting['mobile_height'], 'h')
				);
			}
		}

		$data['module'] = $module++;

		if(isset($setting['return_json']) && $setting['return_json'] === true){
			return $data;
		}

		return $this->load->view('extension/module/slideshow', $data);
	}
}
