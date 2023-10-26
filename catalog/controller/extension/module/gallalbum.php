<?php
class ControllerExtensionModuleGallalbum extends Controller {
	public function index($setting) {
		static $module = 0;
		$this->load->language('extension/module/gallalbum');

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('catalog/gallimage');
		$this->load->model('tool/image');
		
		$data['headtitle'] 	= $setting['headtitle_' . $this->config->get('config_language_id')];
		$data['descstat'] 	= $setting['descstat'];
		$data['chardesc']	= $setting['chardesc'];
		// $data['popupstyle']	= $setting['popupstyle'];
		$data['showimg'] 	= $setting['showimg'];
		$data['imgheight']  = $setting['height'];
        $data['thumblist'] = $setting['thumblist'];
		
		$this->document->addStyle('catalog/view/javascript/jquery/gallery-album/gallery.css');

		$this->document->addScript('catalog/view/javascript/jquery/gallery-album/lightbox/js/lightbox.min.js');    
		$this->document->addStyle('catalog/view/javascript/jquery/gallery-album/lightbox/css/lightbox.css');   

		// slick
		$this->document->addStyle('catalog/view/javascript/slick/slick.min.css');
		$this->document->addScript('catalog/view/javascript/slick/slick-custom.min.js');
           
        if ($setting['thumblist'] == 'style1') {
            $data['boxstyle'] = 'boxstyle1';
        } else if ($setting['thumblist'] == 'style2') {
            $data['boxstyle'] = 'boxstyle2';
        } else if ($setting['thumblist'] == 'style3') {
            $data['boxstyle'] = 'boxstyle3';
        }
        
        if ($setting['titlepos'] == 'left') {
            $data['titlepos'] = 'gall-text-left';
        } else if ($setting['titlepos'] == 'right') {
            $data['titlepos'] = 'gall-text-right';
        } else if ($setting['titlepos'] == 'center') {
            $data['titlepos'] = 'gall-text-center';
        }

		$data['gallalbums'] = array();
		
		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}
        
        if (!empty($setting['gallimage'])) {
			$gallalbums = array_slice($setting['gallimage'], 0, (int)$setting['limit']);
		}
		else {
			$gallalbums = $this->model_catalog_gallimage->getGallalbums();
		}
		//debug($gallalbums);

		foreach ($gallalbums as $gallalbum) {
			$gallalbum_info = $this->model_catalog_gallimage->getGallalbum($gallalbum['gallimage_id']);
			
			if ($gallalbum_info) {
				$filter_data = array(
					'start' => 0,
					'limit' => 999999,
					'gallimage_id' => $gallalbum['gallimage_id'],
				);

				$results = $this->model_catalog_gallimage->getGallimage($filter_data);
				$gallimages = array();
				if ($results) {
					foreach ($results as $result) {
						if ($result['image']) {                  
							$thumb = $this->model_tool_image->resize($result['image'], 400, 400);
							//$popupimage = 'image/' . $result['image'];
							$popupimage = $this->model_tool_image->resize($result['image'], 700, 700);
							$popupimage2 = $this->model_tool_image->resize($result['image'], 200, 200);
						} else {
							$thumb = $this->model_tool_image->resize('placeholder.png', 400, 400);
							$popupimage = 'image/placeholder.png';
							$popupimage2 = 'image/placeholder.png';
						}

						$gallimages[] = array(
							'gallimage_id' => $result['gallimage_id'],
							'title' => $result['title'],
							'link'  => html_entity_decode($result['link'], ENT_QUOTES, 'UTF-8'),
							'thumb' => $thumb,
							'image' => $popupimage,
							'image2' => $popupimage2,
						);
					}
				}

				if ($gallalbum_info['image']) {
					$image = $this->model_tool_image->resize($gallalbum_info['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				$data['gallalbums'][] = array(
					'gallimage_id' => $gallalbum_info['gallimage_id'],
					'name'        => $gallalbum_info['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($gallalbum_info['description'], ENT_QUOTES, 'UTF-8')), 0, $data['chardesc']) . '..',
					'thumb'   	 => $image,
					'href'        => $this->url->link('gallery/gallery', 'gallimage_id=' . $gallalbum_info['gallimage_id']),
					'gallalbum' => $gallimages,
				);
			}
		}
		//debuginfo($data['gallalbums']);
		
		$data['module'] = $module++;
        
        return $this->load->view('extension/module/gallalbum', $data);    
	}
}