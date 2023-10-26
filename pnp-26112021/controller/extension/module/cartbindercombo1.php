<?php
class ControllerExtensionModuleCartbindercombo1 extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('extension/module/cartbindercombo1');
 
		$this->document->setTitle($this->language->get('title'));
		$this->document->addLink("view/stylesheet/imdev.css","stylesheet");
 		
		$this->load->model('tool/cartbindercombo1');
		$this->model_tool_cartbindercombo1->createTable();
		$this->getList();
	}

	public function insert() {
		$this->load->language('extension/module/cartbindercombo1');

		$this->document->setTitle($this->language->get('title'));
		
		$this->load->model('tool/cartbindercombo1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_tool_cartbindercombo1->addcartbindercombo1($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('extension/module/cartbindercombo1');

		$this->document->setTitle($this->language->get('title'));		
		
		$this->load->model('tool/cartbindercombo1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			$this->model_tool_cartbindercombo1->editcartbindercombo1($this->request->get['id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getForm();
	}
		
	public function delete() { 
		$this->load->language('extension/module/cartbindercombo1');

		$this->document->setTitle($this->language->get('title'));		
		
		$this->load->model('tool/cartbindercombo1');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
      		foreach ($this->request->post['selected'] as $id) {
				$this->model_tool_cartbindercombo1->delete($id);	
			}
						
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'].$url, 'SSL'));
		}

		$this->getList();
	}

	private function getList() {
		
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

  		$data['breadcrumbs'] = array();

  		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

  		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		
		$data['insert']  = $this->url->link('extension/module/cartbindercombo1/insert', 'token=' . $this->session->data['token'], 'SSL');
		$data['delete']  = $this->url->link('extension/module/cartbindercombo1/delete', 'token=' . $this->session->data['token'], 'SSL');
		$data['addoffers']  = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.id';
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
	
		$data['cartbindercombo1s'] = array();

		$filter_data = array(
			'filter_name'	  => $filter_name, 
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$cartbindercombo1_total = $this->model_tool_cartbindercombo1->getTotalcartbindercombo1($filter_data);
		$results = $this->model_tool_cartbindercombo1->getcartbindercombo1s($filter_data,($page - 1) * $this->config->get('config_limit_admin'),$this->config->get('config_limit_admin'));
		
		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('button_edit'),
				'href' => $this->url->link('extension/module/cartbindercombo1/update', 'token=' . $this->session->data['token'] . '&id=' . $result['id'], 'SSL')
			);		
			$data['cartbindercombo1s'][] = array(
				'id' 		 	 => $result['id'],
				'name' 	 	     => $result['name'],
				'type' 	 	     => ($result['type'])?"Fixed":"Percentage",
				'discount' 	 	 => $result['discount'],
				'primaryproducts'  => $this->model_tool_cartbindercombo1->getNames($result['primarypids']),
				'secondaryproducts' => $this->model_tool_cartbindercombo1->getNames($result['secondarypids']),
				'total' 	 	 => $this->model_tool_cartbindercombo1->getTotalForOffer($result['id']),
				'offersapplied'  => $this->model_tool_cartbindercombo1->getTotalOfferApplied($result['id']),
				'status' 	 	 => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'action'     	 => $action
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['headerinfo'] = $this->language->get('headerinfo');
		
		$data['headerinfo2'] = $this->language->get('headerinfo2');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_helpguide'] = $this->language->get('text_helpguide');

		$data['text_name'] = $this->language->get('text_name');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_total'] = $this->language->get('text_total');
		$data['text_offersapplied'] = $this->language->get('text_offersapplied');
		$data['text_productstoadd'] = $this->language->get('text_productstoadd');
		$data['text_discountapply'] = $this->language->get('text_discountapply');	
		$data['text_addoffers'] = $this->language->get('text_addoffers');
		$data['text_type'] = $this->language->get('text_type');
		$data['text_discountlist'] = $this->language->get('text_discountlist');
		$data['column_action'] = $this->language->get('column_action');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];
			
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}
					
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
 
		$data['sort_name'] = $this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'].'&type=module', 'SSL');
 		
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
		
		$url = "";
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}
		
		$pagination = new Pagination();
		$pagination->total = $cartbindercombo1_total;
		$pagination->page  = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url   = $this->url->link('extension/module/cartbindercombo1/', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cartbindercombo1_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($cartbindercombo1_total - $this->config->get('config_limit_admin'))) ? $cartbindercombo1_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $cartbindercombo1_total, ceil($cartbindercombo1_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;
		
		$data['sort'] = $sort;
		$data['order'] = $order;


		$data['sort_name'] = $this->url->link('extension/module/cartbindercombo1/', 'token=' . $this->session->data['token']. '&sort=c.name' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/module/cartbindercombo1/', 'token=' . $this->session->data['token']. '&sort=c.status' . $url, 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/cartbindercombo1_list.tpl', $data));
 	}

	private function getForm() {
		$url = '';
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['date_added'] = $this->language->get('date_added');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['headerinfo1'] = $this->language->get('headerinfo1');
		$data['headerinfo3'] = $this->language->get('headerinfo3');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_helpguide'] = $this->language->get('text_helpguide');

		$data['text_name'] = $this->language->get('text_name');
		$data['text_nameform'] = $this->language->get('text_nameform');
		$data['text_status'] = $this->language->get('text_statusform');
		
		$data['token'] = $this->session->data['token'];
		$data['text_default'] = $this->language->get('text_default');
		$data['text_primaryproducts'] = $this->language->get('text_primaryproducts');
		$data['help_primaryproducts'] = $this->language->get('help_primaryproducts');
		$data['text_primaryquant'] = $this->language->get('text_primaryquant');
		$data['help_primaryquant'] = $this->language->get('help_primaryquant');
		$data['text_percentage'] = $this->language->get('text_percentage');
		$data['text_fixed'] = $this->language->get('text_fixed');
		$data['text_discount'] = $this->language->get('text_discount');
		$data['text_offername'] = $this->language->get('text_offername');
		$data['text_below'] = $this->language->get('text_below');
		$data['text_addoffers'] = $this->language->get('text_addoffers');
		$data['text_products'] = $this->language->get('text_products');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_quantitybelow'] = $this->language->get('text_quantitybelow');
		$data['text_conditions'] = $this->language->get('text_conditions');
		$data['text_customergroup'] = $this->language->get('text_customergroup');
		$data['text_autoadd'] = $this->language->get('text_autoadd');	
		$data['text_datestart'] = $this->language->get('text_datestart');
		$data['text_dateend'] = $this->language->get('text_dateend');
		$data['text_daterange'] = $this->language->get('text_daterange');
		$data['text_at'] = $this->language->get('text_at');
		$data['text_salesoffer'] = $this->language->get('text_salesoffer');
		$data['text_showoffer'] = $this->language->get('text_showoffer');
		$data['text_bundle'] = $this->language->get('text_bundle');
		$data['text_displaylocation'] = $this->language->get('text_displaylocation');
		$data['text_displaylocation1'] = $this->language->get('text_displaylocation1');
		$data['text_displaylocation2'] = $this->language->get('text_displaylocation2');

		$data['text_secondaryproducts'] = $this->language->get('text_secondaryproducts');
		$data['help_secondaryproducts'] = $this->language->get('help_secondaryproducts');
		$data['text_secondaryquant'] = $this->language->get('text_secondaryquant');
		$data['help_secondaryquant'] = $this->language->get('help_secondaryquant');

		$data['text_sales'] = $this->language->get('text_sales');
		$data['text_new'] = $this->language->get('text_new');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_free'] = $this->language->get('text_free');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_enabled'] = $this->language->get('text_enabled');		
		$data['text_disabled'] = $this->language->get('text_disabled');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['error_name'])) {
			$data['error_name'] = $this->error['error_name'];
		} else {
			$data['error_name'] = '';
		}
		
 		if (isset($this->error['error_cartbindercombo1_exist'])) {
			$data['error_cartbindercombo1_exist'] = $this->error['error_cartbindercombo1_exist'];
		} else {
			$data['error_cartbindercombo1_exist'] = '';
		}

		if (isset($this->error['error_cartbindercombo1_empty'])) {
			$data['error_cartbindercombo1_empty'] = $this->error['error_cartbindercombo1_empty'];
		} else {
			$data['error_cartbindercombo1_empty'] = '';
		}
		
  		$data['breadcrumbs'] = array();

  		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

  		$data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
			
		if (!isset($this->request->get['id'])) {
			$data['action'] = $this->url->link('extension/module/cartbindercombo1/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/module/cartbindercombo1/update', 'token=' . $this->session->data['token'] . '&id=' . $this->request->get['id'], 'SSL');
		}
		
		$data['token'] = $this->session->data['token'];
		  
    	$data['cancel'] = $this->url->link('extension/module/cartbindercombo1', 'token=' . $this->session->data['token'], 'SSL');
    	$data['addoffers']  = $this->url->link('catalog/salescombopge', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cartbindercombo1_info = $this->model_tool_cartbindercombo1->getcartbindercombo1($this->request->get['id']);
		}
		
		if (isset($this->request->get['id'])) {
			$data['id'] = $this->request->get['id'];
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['name'] = $cartbindercombo1_info['name'];
		} else {
			$data['name'] = '';
		}

		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['status'] = $cartbindercombo1_info['status'];
		} else {
			$data['status'] = '1';
		}
		$this->load->model('catalog/product');
		if (isset($this->request->post['primaryproducts'])) {
			$primaryproducts = $this->request->post['primaryproducts'];
		} else if(!empty($cartbindercombo1_info['primarypids'])) {
			$primaryproducts = explode(",",  $cartbindercombo1_info['primarypids']);
		} else {
			$primaryproducts = array();
		}
		
		$data['primaryproducts'] = array();
		foreach ($primaryproducts as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['primaryproducts'][] = array(
					'product_id' => $product_info['product_id'],
					'name'        => $product_info['name']
				);
			}
		}	

		if (isset($this->request->post['type'])) {
			$data['type'] = $this->request->post['type'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['type'] = $cartbindercombo1_info['type'];
		} else {
			$data['type'] = 1;
		}

		if (isset($this->request->post['discount'])) {
			$data['discount'] = $this->request->post['discount'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['discount'] = $cartbindercombo1_info['discount'];
		} else {
			$data['discount'] = 10;
		}


		if (isset($this->request->post['primaryquant'])) {
			$data['primaryquant'] = $this->request->post['primaryquant'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['primaryquant'] = $cartbindercombo1_info['primaryquant'];
		} else {
			$data['primaryquant'] = 1;
		}

		if (isset($this->request->post['secondaryproducts'])) {
			$secondaryproducts = $this->request->post['secondaryproducts'];
		} else if(!empty($cartbindercombo1_info['secondarypids'])) {
			$secondaryproducts = explode(",",  $cartbindercombo1_info['secondarypids']);
		} else {
			$secondaryproducts = array();
		}
		
		$data['secondaryproducts'] = array();
		foreach ($secondaryproducts as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['secondaryproducts'][] = array(
					'product_id' => $product_info['product_id'],
					'name'        => $product_info['name']
				);
			}
		}	

		$this->load->model('catalog/salescombopge');
		$data['offerpages'] = $this->model_catalog_salescombopge->getsalescombopges();
		
		if (isset($this->request->post['sales_offer_id'])) {
			$data['sales_offer_id'] = $this->request->post['sales_offer_id'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['sales_offer_id'] = $cartbindercombo1_info['sales_offer_id'];
		} else {
			$data['sales_offer_id'] = 0;
		}

		if (isset($this->request->post['secondaryquant'])) {
			$data['secondaryquant'] = $this->request->post['secondaryquant'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['secondaryquant'] = $cartbindercombo1_info['secondaryquant'];
		} else {
			$data['secondaryquant'] = 1;
		}

		$this->load->model('customer/customer_group');
		$data['customergroups'] = $this->model_customer_customer_group->getCustomerGroups();
		

		if (isset($this->request->post['cids'])) {
			$data['cids'] = $this->request->post['cids'];
		} else if(isset($cartbindercombo1_info)) {
			$data['cids'] = json_decode($cartbindercombo1_info['cids'],true);
		} else {
			$data['cids'] = array();
		}

		if (isset($this->request->post['showoffer'])) {
			$data['showoffer'] = $this->request->post['showoffer'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['showoffer'] = $cartbindercombo1_info['showoffer'];
		} else {
			$data['showoffer'] = 0;
		}

		if (isset($this->request->post['datestart'])) {
			$data['datestart'] = $this->request->post['datestart'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['datestart'] = ($cartbindercombo1_info['datestart'] != '0000-00-00') ? $cartbindercombo1_info['datestart'] : '';
		} else {
			$data['datestart'] = "";
		}

		if (isset($this->request->post['dateend'])) {
			$data['dateend'] = $this->request->post['dateend'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['dateend'] = ($cartbindercombo1_info['dateend'] != '0000-00-00') ? $cartbindercombo1_info['dateend'] : '';
		} else {
			$data['dateend'] = "";
		}

		if (isset($this->request->post['bundle'])) {
			$data['bundle'] = $this->request->post['bundle'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['bundle'] = $cartbindercombo1_info['bundle'];
		} else {
			$data['bundle'] = 0;
		}

		if (isset($this->request->post['autoadd'])) {
			$data['autoadd'] = $this->request->post['autoadd'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['autoadd'] = $cartbindercombo1_info['autoadd'];
		} else {
			$data['autoadd'] = 0;
		}

		if (isset($this->request->post['displaylocation'])) {
			$data['displaylocation'] = $this->request->post['displaylocation'];
		} elseif (isset($cartbindercombo1_info)) {
			$data['displaylocation'] = $cartbindercombo1_info['displaylocation'];
		} else {
			$data['displaylocation'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/cartbindercombo1_form.tpl', $data));
	}

	private function validateForm() {
		
		$this->load->model('tool/cartbindercombo1');
		
		if (!$this->user->hasPermission('modify', 'extension/module/cartbindercombo1')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['warning'] = $this->language->get('error_name');
		}

		if (empty($this->request->post['primaryproducts'])) {
			$this->error['warning'] = $this->language->get('error_primaryproductsempty');
		}

		if (empty($this->request->post['secondaryproducts'])) {
			$this->error['warning'] = $this->language->get('error_secondaryproductsempty');
		}

		if(!isset($this->error['warning'])) {
			foreach ($this->request->post['primaryproducts'] as $key => $value) {
				$primaryproductarray[] = $value;
			}
			foreach ($this->request->post['secondaryproducts'] as $key => $value) {
				if(in_array($value, $primaryproductarray)) {
					$this->error['warning'] = $this->language->get('error_sameproduct');
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/cartbindercombo1')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}


	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_condition'])) {
			$this->load->model('tool/cartbindercombo1');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = null;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'filter_condition' => $filter_condition,
				'start'        => 0,
				'limit'        => $limit
			);

			$json = $this->model_tool_cartbindercombo1->getcartbindercombo1s($data);

		}

		$this->response->setOutput(json_encode($json));
	}
}
?>