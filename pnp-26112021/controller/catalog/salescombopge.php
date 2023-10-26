<?php
class ControllerCatalogsalescombopge extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/salescombopge');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/salescombopge');
		$this->model_catalog_salescombopge->createTable();

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/salescombopge');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/salescombopge');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_salescombopge->addsalescombopge($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/salescombopge');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/salescombopge');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_salescombopge->editsalescombopge($this->request->get['salescombopge_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/salescombopge');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/salescombopge');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $salescombopge_id) {
				$this->model_catalog_salescombopge->deletesalescombopge($salescombopge_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'id.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('catalog/salescombopge/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/salescombopge/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['salescombopges'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$salescombopge_total = $this->model_catalog_salescombopge->getTotalsalescombopges();

		$results = $this->model_catalog_salescombopge->getsalescombopges($filter_data);

		foreach ($results as $result) {
			$data['salescombopges'][] = array(
				'salescombopge_id' => $result['salescombopge_id'],
				'title'          => $result['title'],
				'sort_order'     => $result['sort_order'],
				'edit'           => $this->url->link('catalog/salescombopge/edit', 'token=' . $this->session->data['token'] . '&salescombopge_id=' . $result['salescombopge_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_addoffer'] = $this->language->get('button_addoffer');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . '&sort=id.title' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . '&sort=i.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $salescombopge_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($salescombopge_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($salescombopge_total - $this->config->get('config_limit_admin'))) ? $salescombopge_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $salescombopge_total, ceil($salescombopge_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/salescombopge_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		$data['version'] = str_replace(".","",VERSION);
		$data['text_form'] = !isset($this->request->get['salescombopge_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['text_image'] = $this->language->get('text_image');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_message'] = $this->language->get('entry_message');
		$data['entry_rules'] = $this->language->get('entry_rules');
		$data['entry_meta_title'] = $this->language->get('entry_meta_title');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_bottom'] = $this->language->get('entry_bottom');
		$data['entry_top'] = $this->language->get('entry_top');
		$data['text_customize_theme'] = $this->language->get('text_customize_theme');
		$data['text_backgroundcolor'] = $this->language->get('text_backgroundcolor');
		$data['text_fontcolor'] = $this->language->get('text_fontcolor');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_category'] = $this->language->get('entry_category');
		$data['entry_products'] = $this->language->get('entry_products');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['text_customergroup'] = $this->language->get('text_customergroup');
		$data['text_messagedisplay'] = $this->language->get('text_messagedisplay');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['help_customer'] = $this->language->get('help_customer');
		$data['entry_autopopup'] = $this->language->get('entry_autopopup');
		$data['help_autopopup'] = $this->language->get('help_autopopup');

		$data['help_description'] = $this->language->get('help_description');
		$data['help_rules'] = $this->language->get('help_rules');
		$data['help_message'] = $this->language->get('help_message');
		$data['help_tags'] = $this->language->get('help_tags');
		$data['help_keyword'] = $this->language->get('help_keyword');
		$data['help_bottom'] = $this->language->get('help_bottom');
		$data['help_top'] = $this->language->get('help_top');
		$data['help_category'] = $this->language->get('help_category');
		$data['help_products'] = $this->language->get('help_products');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_design'] = $this->language->get('tab_design');
		$data['tab_links'] = $this->language->get('tab_links');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['salescombopge_id'])) {
			$data['action'] = $this->url->link('catalog/salescombopge/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/salescombopge/edit', 'token=' . $this->session->data['token'] . '&salescombopge_id=' . $this->request->get['salescombopge_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['salescombopge_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$salescombopge_info = $this->model_catalog_salescombopge->getsalescombopge($this->request->get['salescombopge_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['salescombopge_description'])) {
			$data['salescombopge_description'] = $this->request->post['salescombopge_description'];
		} elseif (isset($this->request->get['salescombopge_id'])) {
			$data['salescombopge_description'] = $this->model_catalog_salescombopge->getsalescombopgeDescriptions($this->request->get['salescombopge_id']);
		} else {
			$data['salescombopge_description'] = array();
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['salescombopge_store'])) {
			$data['salescombopge_store'] = $this->request->post['salescombopge_store'];
		} elseif (isset($this->request->get['salescombopge_id'])) {
			$data['salescombopge_store'] = $this->model_catalog_salescombopge->getsalescombopgeStores($this->request->get['salescombopge_id']);
		} else {
			$data['salescombopge_store'] = array(0);
		}

		if (isset($this->request->post['bottom'])) {
			$data['bottom'] = $this->request->post['bottom'];
		} elseif (!empty($salescombopge_info)) {
			$data['bottom'] = $salescombopge_info['bottom'];
		} else {
			$data['bottom'] = 0;
		}

		if (isset($this->request->post['autopopup'])) {
			$data['autopopup'] = $this->request->post['autopopup'];
		} elseif (!empty($salescombopge_info)) {
			$data['autopopup'] = $salescombopge_info['autopopup'];
		} else {
			$data['autopopup'] = 0;
		}

		if (isset($this->request->post['top'])) {
			$data['top'] = $this->request->post['top'];
		} elseif (!empty($salescombopge_info)) {
			$data['top'] = $salescombopge_info['top'];
		} else {
			$data['top'] = 0;
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($salescombopge_info)) {
			$data['image'] = $salescombopge_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($salescombopge_info) && is_file(DIR_IMAGE . $salescombopge_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($salescombopge_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['backgroundcolor'])) {
			$data['backgroundcolor'] = $this->request->post['backgroundcolor'];
		} elseif (isset($salescombopge_info)) {
			$data['backgroundcolor'] = $salescombopge_info['backgroundcolor'];
		} else {
			$data['backgroundcolor'] = '';
		}

		if (isset($this->request->post['fontcolor'])) {
			$data['fontcolor'] = $this->request->post['fontcolor'];
		} elseif (isset($salescombopge_info)) {
			$data['fontcolor'] = $salescombopge_info['fontcolor'];
		} else {
			$data['fontcolor'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($salescombopge_info)) {
			$data['status'] = $salescombopge_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($salescombopge_info)) {
			$data['keyword'] = $salescombopge_info['keyword'];
		} else {
			$data['keyword'] = true;
		}

		if($data['version'] > 2100) {
			$this->load->model('customer/customer_group');
			$data['customergroups'] = $this->model_customer_customer_group->getCustomerGroups();
			$data['customerlink'] = "customer/customer/";
		} else {
			$this->load->model('sale/customer_group');
			$data['customerlink'] = "sale/customer/";
			$data['customergroups'] = $this->model_sale_customer_group->getCustomerGroups();
		}

		if (isset($this->request->post['customergroupcst'])) {
			$data['customergroupcst'] = $this->request->post['customergroupcst'];
		} elseif (isset($salescombopge_info)) {
			$data['customergroupcst'] = $this->model_catalog_salescombopge->getCustomerGroups($this->request->get['salescombopge_id']);
		} else {
			$data['customergroupcst'] = array();
		}

		if (isset($this->request->post['customers'])) {
			$data['customers'] = $this->request->post['customers'];
		} elseif (isset($salescombopge_info)) {
			$data['customers'] = $this->model_catalog_salescombopge->getCustomerNames($this->request->get['salescombopge_id']);
		} else {
			$data['customers'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($salescombopge_info)) {
			$data['sort_order'] = $salescombopge_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->post['salescombopge_layout'])) {
			$data['salescombopge_layout'] = $this->request->post['salescombopge_layout'];
		} elseif (isset($this->request->get['salescombopge_id'])) {
			$data['salescombopge_layout'] = $this->model_catalog_salescombopge->getsalescombopgeLayouts($this->request->get['salescombopge_id']);
		} else {
			$data['salescombopge_layout'] = array();
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['salescombopge_category'])) {
			$categories = $this->request->post['salescombopge_category'];
		} elseif (isset($this->request->get['salescombopge_id'])) {
			$categories = $this->model_catalog_salescombopge->getsalescombopgeCategories($this->request->get['salescombopge_id']);
		} else {
			$categories = array();
		}

		$data['product_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['product_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
				);
			}
		}

		//Products
		$this->load->model('catalog/product');
		if (isset($this->request->post['salescombopge_product'])) {
			$products = $this->request->post['salescombopge_product'];
		} elseif (isset($this->request->get['salescombopge_id'])) {
			$products = $this->model_catalog_salescombopge->getsalescombopgeProducts($this->request->get['salescombopge_id']);
		} else {
			$products = array();
		}

		$data['salescombopge_product'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_catalog_product->getProduct($product_id);

			if ($related_info) {
				$data['salescombopge_product'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name']
				);
			}
		}


		$data["base_url"] = HTTPS_CATALOG;
		// Enhanced CKEditor
		if (!file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') || file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml_')) {				
			$data['fm_installed'] = 0;
		}
		if (file_exists(DIR_CATALOG.'../vqmod/xml/enhanced_file_manager.xml') && $this->config->get('fm_installed') == 1) {				
			$data['fm_installed'] = 1;
		}
					
		if ($this->config->get('ea_cke_enable_ckeditor') == 1) {
			$data['ckeditor_enabled'] = 1;
		} else {
			$data['ckeditor_enabled'] = 0;
		}
		
		if ($this->config->get('ea_cke_ckeditor_skin')) {
		  	$data['ckeditor_skin'] = $this->config->get('ea_cke_ckeditor_skin');
		} else {
		  	$data['ckeditor_skin'] = 'moono-lisa';
		}
		
		if ($this->config->get('ea_cke_codemirror_skin')) {
		  	$data['codemirror_skin'] = $this->config->get('ea_cke_codemirror_skin');
		} else {
		  	$data['codemirror_skin'] = 'eclipse';
		}
		// Enhanced CKEditor	

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/salescombopge_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/salescombopge')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['salescombopge_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 64)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}

		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/salescombopge')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}