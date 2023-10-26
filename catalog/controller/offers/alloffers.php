<?php
class ControllerOffersAllOffers extends Controller {
	public function index() {
		$this->load->language('offers/salescombopge');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_alloffers'),
			'href' => $this->url->link('offers/alloffers')
		);

		$this->document->addScript('catalog/view/javascript/jquery/flip.js');
		$this->document->setTitle($this->language->get('text_alloffers'));
		$this->load->model('offers/salescombopge');
		$this->load->model('tool/image');

		$data['salescombopge_info'] = $this->model_offers_salescombopge->getAllOffers();

		foreach ($data['salescombopge_info'] as $key => $value) {
			if ($value['image']) {
				$data['salescombopge_info'][$key]['thumb'] =  $this->model_tool_image->resize($value['image'], $this->language->get('alloffer_image_width'), $this->language->get('alloffer_image_height'));
			} else {
				$data['salescombopge_info'][$key]['thumb'] =  $this->model_tool_image->resize('placeholder.png', $this->language->get('alloffer_image_width'), $this->language->get('alloffer_image_height'));
			}
		}

		$data['salescombopgetotal'] = count($data['salescombopge_info']);
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_viewmore'] = $this->language->get('button_viewmore');
		$data['button_viewdetails'] = $this->language->get('button_viewdetails');

		if($data['salescombopgetotal'] == 1) {
			$data['totaloffers'] = sprintf($this->language->get('totaloffer'),$data['salescombopgetotal']);
		} else if($data['salescombopgetotal']) {
			$data['totaloffers'] = sprintf($this->language->get('totaloffers'),$data['salescombopgetotal']);
		} else {
			$data['totaloffers'] = "";
		}

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$version = str_replace(".","",VERSION);
		if($version < 2200) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/offers/alloffers.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/offers/alloffers.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/offers/alloffers.tpl', $data));
			}
		} else {
			$this->response->setOutput($this->load->view('offers/alloffers', $data));
		}		
	}
}