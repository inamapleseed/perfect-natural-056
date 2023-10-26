<?php
	class ModelAccountCustomer extends Model {
		
		public function addCustomer($data) {

			if(!isset($data['newsletter'])) $data['newsletter'] = 0;

			if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				$customer_group_id = $data['customer_group_id'];
			}
			else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
			
			$this->load->model('account/customer_group');
			
			$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
			
			if(!isset($data['birthday'])){
				$data['birthday'] = '0000-00-00';
			}

			$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', birthday = '" . $this->db->escape($data['birthday']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW()");
			
			$customer_id = $this->db->getLastId();
			
			$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', unit_no = '" . $this->db->escape($data['unit_no']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? json_encode($data['custom_field']['address']) : '') . "'");
			
			$address_id = $this->db->getLastId();

			// Clear Thinking: mailchimp_integration.xml
			// if (!empty($data['newsletter'])) {
			// 	if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
			// 	$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
			// 	$mailchimp_integration->send(array_merge($data, array('customer_id' => $customer_id, 'customer_newsletter' => 1)));
			// }
			// end: mailchimp_integration.xml

			// newsletter module
			$this->handleNewsletter($data);
			// newsletter module
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			
			$this->load->language('mail/customer');
			
			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			
			$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
			
			if (!$customer_group_info['approval']) {
				$message .= $this->language->get('text_login') . "\n";
			}
			else {
				$message .= $this->language->get('text_approval') . "\n";
			}
			
			$message .= $this->url->link('account/login', '', true) . "\n\n";
			$message .= $this->language->get('text_services') . "\n\n";
			$message .= $this->language->get('text_thanks') . "\n";
			$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
			
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setTo($data['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject($subject);
			$mail->setText($message);
			// $mail->send();

			// Pro email Template Mod
			if($this->config->get('pro_email_template_status')){
				$this->load->model('tool/pro_email');

				$email_params = array(
					'type' => 'customer.register',
					'mail' => $mail,
					'data' => $data,
					'customer_id' => isset($customer_id) ? $customer_id : false,
					'conditions' => array('approval' => $customer_group_info['approval']),
				);
				
				$this->model_tool_pro_email->generate($email_params);
			}
			else{
				$mail->send();
			}
			// End Pro email Template Mod
			
			// Send to main admin email if new account email is enabled
			if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
				$message  = $this->language->get('text_signup') . "\n\n";
				$message .= $this->language->get('text_website') . ' ' . html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "\n";
				$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
				$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
				$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
				$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
				$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";
				
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
				$mail->smtp_username = $this->config->get('config_mail_smtp_username');
				$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
				$mail->smtp_port = $this->config->get('config_mail_smtp_port');
				$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
				$mail->setText($message);
				//$mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'admin.customer.register',
						'mail' => $mail,
						'reply_to' => $data['email'],
						'customer_id' => isset($customer_id) ? $customer_id : false,
						'data' => array(),
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}
				
				// Send to additional alert emails if new account email is enabled
				$emails = explode(',', $this->config->get('config_alert_email'));
				
				foreach ($emails as $email) {
					$email = preg_replace('/\s+/', '', $email);
					if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail->setTo($email);
						//$mail->send();

						// Pro email Template Mod
						if($this->config->get('pro_email_template_status')){
							$this->load->model('tool/pro_email');

							$email_params = array(
								'type' => 'admin.customer.register',
								'mail' => $mail,
								'reply_to' => $data['email'],
								'customer_id' => isset($customer_id) ? $customer_id : false,
								'data' => array(),
							);
							
							$this->model_tool_pro_email->generate($email_params);
						}
						else{
							$mail->send();
						}
					}
				}
			}
			

			/* $this->sms->send(); */

			return $customer_id;
		}
		
		public function editCustomer($data) {

			if(!isset($data['newsletter'])) $data['newsletter'] = 0;
		
			// Clear Thinking: mailchimp_integration.xml
			// if ($this->customer->getNewsletter()) {
			// 	if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
			// 	$mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
			// 	$mailchimp_integration->send(array_merge($data, array('newsletter' => 1, 'customer_id' => $this->customer->getId())));
			// }
			// end: mailchimp_integration.xml

			// newsletter module
			$this->handleNewsletter($data);
			// newsletter module

			$customer_id = $this->customer->getId();
			
			if(!isset($data['birthday'])){
				$data['birthday'] = '0000-00-00';
			}

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET birthday = '" . $this->db->escape($data['birthday']) . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
			// $this->editNewsletter($data['newsletter']);
		}
		
		public function editPassword($email, $password) {
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}
		
		public function editCode($email, $code) {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}
		
		public function editNewsletter($newsletter) {
			// Clear Thinking: mailchimp_integration.xml
			// if (version_compare(VERSION, '2.1', '<')) $this->load->library('mailchimp_integration');
			// $mailchimp_integration = new MailChimp_Integration($this->config, $this->db, $this->log, $this->session, $this->url);
			// $mailchimp_integration->send(array('newsletter' => $newsletter, 'customer_id' => $this->customer->getId()));
			// end: mailchimp_integration.xml

			// newsletter module
			$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->customer->getId() . "'");
			if($query->num_rows){ 		
				$data['email'] = $query->row['email'];	
				$data['newsletter'] = (int)$newsletter;	
				$this->handleNewsletter($data);
			}
			// newsletter module
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		}
		
		public function getCustomer($customer_id) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row;
		}
		
		public function getCustomerByEmail($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row;
		}
		
		public function getCustomerByCode($code) {
			$query = $this->db->query("SELECT customer_id, firstname, lastname, email FROM `" . DB_PREFIX . "customer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");
			
			return $query->row;
		}
		
		public function getCustomerByToken($token) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");
			
			return $query->row;
		}
		
		public function getTotalCustomersByEmail($email) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row['total'];
		}
		
		public function getRewardTotal($customer_id) {
			$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->row['total'];
		}
		
		public function getIps($customer_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");
			
			return $query->rows;
		}
		
		public function addLoginAttempt($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
			
			if (!$query->num_rows) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
			}
			else {
				$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
			}
		}
		
		public function getLoginAttempts($email) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			return $query->row;
		}
		
		public function deleteLoginAttempts($email) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		}

		public function getCustomersByCustomerGroup($customer_group_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE customer_group_id = '" . (int)$customer_group_id . "'");

			return $query->rows;
		}
		
		public function getRewardTotalByCustomerId($customer_id, $start_date, $end_date) {
			$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "' AND (date_added BETWEEN '".$this->db->escape($start_date . ' 00:00:00'). "' AND '".$this->db->escape($end_date . ' 23:59:59'). "' )");

			return $query->row['total'];
		}

		public function clearReward($customer_id, $points, $description) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '0', points = '" . (int)$points*-1 . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");
		}

		// Update customer success login		
		public function getCustomerSuccessLogin($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			if($query->num_rows > 0) {

				$total_success_login = $query->row['total_success_login'] + 1;
				
				$update_query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET total_success_login = ".$total_success_login.", last_login = NOW() WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			}
			return true;
		}
			
		// Update customer failed login		
		public function getCustomerFailedLogin($email) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			
			if($query->num_rows > 0) {
				$total_failed_login = $query->row['total_failed_login'] + 1;
				
				$update_query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET total_failed_login = ".$total_failed_login." WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			}
			
			return true;
		}

		public function getCustomerMembershipRecords($customer_id) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_membership_records` WHERE `customer_id` = '".$customer_id."'");
			return $query->rows;
		}

		public function getBirthdayCouponLastID() {
			$query = $this->db->query("SELECT coupon_id FROM " . DB_PREFIX . "coupon_customer ORDER BY coupon_id DESC LIMIT 1");

			$last_coupon_id = $query->row;

			$last_coupon_id['coupon_id']++;
	
			return $last_coupon_id['coupon_id'];
		}
	
		public function getCurrentBirthdayCustomer() {
			$query = $this->db->query("SELECT customer_id FROM " . DB_PREFIX . "customer WHERE MONTH(birthday) = MONTH(NOW()) AND (birthdaycoupon != YEAR(NOW()) || birthdaycoupon IS NULL)");
	
			return $query->rows;
		}

		public function addCoupon($data) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "coupon SET name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', discount = '" . (float)$data['discount'] . "', type = '" . $this->db->escape($data['type']) . "', total = '" . (float)$data['total'] . "', logged = '" . (int)$data['logged'] . "', shipping = '" . (int)$data['shipping'] . "', date_start = '" . $this->db->escape($data['date_start']) . "', date_end = '" . $this->db->escape($data['date_end']) . "', uses_total = '" . (int)$data['uses_total'] . "', uses_customer = '" . (int)$data['uses_customer'] . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	
			$coupon_id = $this->db->getLastId();
	
			if (isset($data['coupon_customer'])) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_customer SET coupon_id = '" . (int)$coupon_id . "', customer_id = '" . (int)$data['coupon_customer'] . "'");
			}

			$this->db->query("UPDATE " . DB_PREFIX . "customer SET birthdaycoupon = YEAR(NOW()) WHERE customer_id =". (int)$data['coupon_customer']);
		}

		public function findCustomerEmailByID($customer_id){
			$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer WHERE customer_id = ". (int)$customer_id );

			$customer_email = $query->row;

			return $customer_email;
		}

		public function getTotalCustomerRewardsByOrderId($order_id) {
				$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_reward WHERE order_id = '" . (int)$order_id . "' AND points > 0");

				return $query->row['total'];
		}
		
		public function addReward($customer_id, $description = '', $points = '', $order_id = 0) {
				$customer_info = $this->getCustomer($customer_id);

				if ($customer_info) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "customer_reward SET customer_id = '" . (int)$customer_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

						$this->load->language('mail/customer');

						$this->load->model('setting/store');

						$store_info = $this->model_setting_store->getStore($customer_info['store_id']);

						if ($store_info) {
								$store_name = $store_info['name'];
						} else {
								$store_name = $this->config->get('config_name');
						}

						$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
						$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($customer_id));

						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

						$mail->setTo($customer_info['email']);
						$mail->setFrom($this->config->get('config_email'));
						$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
						$mail->setSubject(sprintf($this->language->get('text_reward_subject'), html_entity_decode($store_name, ENT_QUOTES, 'UTF-8')));
						$mail->setText($message);
						//$mail->send();

						// Pro email Template Mod
						if($this->config->get('pro_email_template_status')){
								$this->load->model('tool/pro_email');

								$email_params = array(
										'type' => 'customer.reward',
										'mail' => $mail,
										'store_id' => isset($store_info['store_id']) ? $store_info['store_id'] : 0,
										'data' => array(
										'firstname' => $customer_info['firstname'],
										'amount' => $points,
										'total' => $this->getRewardTotal($customer_id),
										),
								);

								$this->model_tool_pro_email->generate($email_params);
						}
						else{
								$mail->send();
						}
				}
		}

		// mailchimp (newlsetter module)
		private function handleNewsletter($data) {
			if ($this->config->get('newsletter_module_status') && isset($data['newsletter']) && ($this->config->get('newsletter_module_mailchimp_api_key_test_1') || $this->config->get('newsletter_module_mailchimp_api_key_live_1'))) {
				$the_mailchimp = new Newsletter_Module($this->config, $this->db, $this->log, $this->session, $this->url, $this->modulehelper);
				$mailchimp = $the_mailchimp->initMailchimp();

				$subscribe = 0;

				// subscribe
				if($data['newsletter'] == 1) {
					$query = $this->db->query("SELECT email FROM " . DB_PREFIX . "customer_newsletter_list WHERE email = '" . $this->db->escape($data['email']) . "'");

					if($query->num_rows == 0){ 
						// save record to database
						$this->db->query("INSERT INTO " . DB_PREFIX . "customer_newsletter_list SET customer_id = '".(int)$this->customer->getId()."', email = '" . $this->db->escape($data['email']) . "', status = '1', date_added = NOW()");

						$mailchimp_param = array('email_address' => $data['email'], 'status' => 'subscribed');
						$chimp = $the_mailchimp->subscribeTheSubscriber($mailchimp, $mailchimp_param); 
					}
					else {
						// resubscribe
						$mailchimp_param = array('email_address' => $data['email']);
						$chimp = $the_mailchimp->resubscribeTheSubscriber($mailchimp, $mailchimp_param); 

						$this->db->query("UPDATE " . DB_PREFIX . "customer_newsletter_list SET status = '1' WHERE email = '" . $this->db->escape($data['email']) . "'");
					}

					$subscribe = 1;
				}
				// unsubscribe
				else if($data['newsletter'] == 0) {
					$mailchimp_param = array('email_address' => $data['email']);
					$chimp = $the_mailchimp->unsubcribeTheSubscriber($mailchimp, $mailchimp_param); 

					$this->db->query("UPDATE " . DB_PREFIX . "customer_newsletter_list SET status = '0' WHERE email = '" . $this->db->escape($data['email']) . "'");
				} 

				// update newsletter in customer
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '".$subscribe."' WHERE customer_id = '".(int)$this->customer->getId()."'");
			}
		}
		// mailchimp (newlsetter module)	
			
	}
