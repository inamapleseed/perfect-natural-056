<?php
class Controllerofferssalescombopge extends Controller {
	public function index() {

		
		$this->load->language('offers/offers');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$this->load->language('offers/salescombopge');
		

		if (isset($this->request->get['page_id'])) {
			$page_id = (int)$this->request->get['page_id'];
		} else {
			$page_id = 0;
		}

		$this->load->model('offers/salescombopge');

		$salescombopge_info = $this->model_offers_salescombopge->getPage($page_id);
		
		if ($salescombopge_info) {

			$this->document->setTitle($salescombopge_info['meta_title']);
			$this->document->setDescription($salescombopge_info['meta_description']);
			$this->document->setKeywords($salescombopge_info['meta_keyword']);
			$this->document->addLink($this->url->link('offers/salescombopge', 'page_id=' .  $page_id), 'canonical');
			
			$data['breadcrumbs'][] = array(
				'text' => $salescombopge_info['title'],
				'href' => $this->url->link('offers/salescombopge', 'page_id=' .  $page_id)
			);

			$data['heading_title'] = $salescombopge_info['title'];

			$data['button_continue'] = $this->language->get('button_continue');

			$data['description'] = html_entity_decode($salescombopge_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$version = str_replace(".","",VERSION);

			if($version < 2200) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/offers/salescombopge.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/offers/salescombopge.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/offers/salescombopge.tpl', $data));
				}
			} else {
				$this->response->setOutput($this->load->view('offers/salescombopge', $data));
			}

		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('offers/salescombopge', 'page_id=' . $page_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$version = str_replace(".","",VERSION);

			if($version < 2200) {
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
				}
			} else {
				$this->response->setOutput($this->load->view('error/not_found', $data));
			}
		}
	}
	public function popp() {
		if (isset($this->request->post['page_id'])) {
			$page_id = (int)$this->request->post['page_id'];
		} else {
			$page_id = 0;
		}
		$json = array();
		$this->load->model('offers/salescombopge');
		$salescombopge_info = $this->model_offers_salescombopge->getPage($page_id);

		if ($salescombopge_info) {
			$json['html']['title']  = $salescombopge_info['title'];
			$json['html']['description'] = html_entity_decode($salescombopge_info['description'], ENT_QUOTES, 'UTF-8');
		} 

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addOfferSession() {
		$this->session->data['offerdisplayedpopup'][] = $this->request->post['id'];
	}
}