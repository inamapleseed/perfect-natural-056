<?php
class ControllerCronCron extends Controller {
		
	// for membership tier module - start
	public function checkMembership() {
		$incement = 1; // for year
		$unit_txt = 'year';
		//  $incement = 1; // for year
		//  $unit_txt = 'months';
			
		$today = date('Y-m-d');
		//$today = '2020-04-10';
		
		$query = $this->db->query("SELECT `date_added` AS membership_date, `customer_id` FROM `" . DB_PREFIX . "customer_membership_records` GROUP BY `customer_id` ORDER BY `date_added` DESC");
		// Select all confirmed orders and group by customer
		//$query = $this->db->query("SELECT SUM(`total`) AS total_spent, `customer_id`, `date_added` AS first_order_date FROM `" . DB_PREFIX . "order` WHERE (`order_status_id` = 2 OR `order_status_id` = 5) AND `customer_id` > 0 AND `total` > 0 GROUP BY `customer_id` ORDER BY `total_spent` DESC");
		if($query->num_rows) {
			foreach($query->rows as $d) {
				//debug($d['customer_id'].' - '. $d['membership_date']);
				//debug($d['customer_id'].' - '.$d['total_spent']. ' - '.date('Y-m-d', strtotime($d['first_order_date'])));
				
				// $yr_count = 0;
				
				// $query3 = $this->db->query("SELECT year_count FROM `" . DB_PREFIX . "customer_membership_year_count` WHERE customer_id = '".$d['customer_id']."'");
				// if($query3->num_rows) {
				// 	$yr_count = $query3->row['year_count'];
				// }
				//debug($yr_count);
				$membership_date =  date('Y-m-d', strtotime($d['membership_date']));
				$membership_date_after_a_period = date('Y-m-d', strtotime($d['membership_date'] . ' + '.$incement.$unit_txt));
				if($today == $membership_date_after_a_period) {
				//if($today == date('Y-m-d', strtotime($d['first_order_date'] . ' + '.($yr_count + $incement).$unit_txt))) {
					// Select total spent by each customer between a period range (e.g. between 1 year)
					// Prev
					$cur_customer_group_id = 1;
					$query4 = $this->db->query("SELECT `customer_group_id` FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '".$d['customer_id']."'");
					if($query4->num_rows) {
						$cur_customer_group_id = $query4->row['customer_group_id'];
					}
					
					// $prev_total = 0;
					// if($yr_count < $incement) {
					// 	$yr_count_prev = $yr_count;
					// }
					// else {
					// 	$yr_count_prev = $yr_count - $incement;
					// }
					// $sql_part = "(`date_added` >= '". date('Y-m-d', strtotime($d['first_order_date'] . ' + '.($yr_count_prev).$unit_txt)) ."' AND `date_added` < '". date('Y-m-d', strtotime($d['first_order_date'] . ' + '.$yr_count.$unit_txt)) ."')";
					// $sql = "SELECT SUM(`total`) AS total_spent, `customer_id` FROM `" . DB_PREFIX . "order` WHERE (`order_status_id` = 2 OR `order_status_id` = 5) AND `customer_id` = '".$d['customer_id']."' AND ".$sql_part." GROUP BY `customer_id` ORDER BY `total_spent` DESC";
					// $query2 = $this->db->query($sql);
					// //debug($sql);
					// if($query2->num_rows) {
					// 	$prev_total = $query2->row['total_spent'];
					// 	//debug($query2->row['customer_id'].' - '.$query2->row['total_spent']);
					// }
					// $prev_membership = $this->getMembership($prev_total);
					
					// New
					$cur_total = 0;
					$sql_part = "(`date_added` >= '". $membership_date ."' AND `date_added` <= '". $today.' 23:59:59' ."')";
				//	$sql_part = "(`date_added` >= '". date('Y-m-d', strtotime($d['first_order_date'] . ' + '.$yr_count.$unit_txt)) ."' AND `date_added` <= '". date('Y-m-d', strtotime($d['first_order_date'] . ' + '.($yr_count + $incement).$unit_txt)) ."')";
					$sql = "SELECT SUM(`total`) AS total_spent, `customer_id` FROM `" . DB_PREFIX . "order` WHERE (`order_status_id` = 2 OR `order_status_id` = 5) AND `customer_id` = '".$d['customer_id']."' AND ".$sql_part." GROUP BY `customer_id` ORDER BY `total_spent` DESC";
					$query2 = $this->db->query($sql);
					//debug($sql);
					if($query2->num_rows) {
						$cur_total = $query2->row['total_spent'];
						//debug($query2->row['customer_id'].' - '.$query2->row['total_spent']);
					}
					//$cur_total -= $prev_membership['min_spend_amount'];
					$new_membership = $this->getMembership($cur_total);
					// debug('Cust ID: '.$d['customer_id'] .'<br>Membership Date: '. $membership_date . '<br>Membership Date After '.$incement.' '.$unit_txt.': '. $membership_date_after_a_period . '<br>' .
					// 'Prev: '.$cur_customer_group_id.
					// '<br>New: '.$cur_total. ' - '.$new_membership['customer_group_id'] 
					// );
					// If new membership is different than previous then only update membership
					if($cur_customer_group_id != $new_membership['customer_group_id']) {
						//$this->updateMembership($d['customer_id'], $cur_customer_group_id, $new_membership['customer_group_id']);
						$this->updateMembership(array('customer_id' => $d['customer_id'], 'old_customer_group_id' => $cur_customer_group_id, 'new_customer_group_id' => $new_membership['customer_group_id']));
						// send membership update email
						//$this->sendMembershipEmail($d['customer_id'], $cur_customer_group_id, $new_membership['customer_group_id']);
						$this->sendMembershipEmail(array('customer_id' => $d['customer_id'], 'old_customer_group_id' => $cur_customer_group_id, 'new_customer_group_id' => $new_membership['customer_group_id']));
					}
				}
			}
		}
	
	}

	public function notifyMembership() {
		$today = date('Y-m-d');
		
		$query = $this->db->query("SELECT `date_added` AS membership_date, `customer_id` FROM `" . DB_PREFIX . "customer_membership_records` GROUP BY `customer_id` ORDER BY `date_added` DESC");
		if($query->num_rows) {
			foreach($query->rows as $d) {
				$membership_date =  date('Y-m-d', strtotime($d['membership_date']));
				// 3 mths before 1 year review membership
				$membership_date_after_a_period = date('Y-m-d', strtotime($d['membership_date'] . ' + 9months'));
				if($today == $membership_date_after_a_period) {
					$cur_customer_group_id = 1;
					$query4 = $this->db->query("SELECT `customer_group_id` FROM `" . DB_PREFIX . "customer` WHERE `customer_id` = '".$d['customer_id']."'");
					if($query4->num_rows) {
						$cur_customer_group_id = $query4->row['customer_group_id'];
					}
					$cur_total = 0;
					$sql_part = "(`date_added` >= '". $membership_date ."' AND `date_added` <= '". $today.' 23:59:59' ."')";
				
					$sql = "SELECT SUM(`total`) AS total_spent, `customer_id` FROM `" . DB_PREFIX . "order` WHERE (`order_status_id` = 2 OR `order_status_id` = 5) AND `customer_id` = '".$d['customer_id']."' AND ".$sql_part." GROUP BY `customer_id` ORDER BY `total_spent` DESC";
					$query2 = $this->db->query($sql);
					//debug($sql);
					if($query2->num_rows) {
						$cur_total = $query2->row['total_spent'];
					}
					$minAmountForMembership = $this->getMembershipAmountByCustomerGroupId($cur_customer_group_id);
					//debug($d['customer_id']. ' - ' .$cur_total. ' - ' .$minAmountForMembership);
					// if not enough to maintain current membership
					if($cur_total < $minAmountForMembership) {
						$amt_diff = '$'.number_format(($minAmountForMembership - $cur_total), 2);
						$this->sendReviewMembershipEmail($d['customer_id'], $cur_customer_group_id, $amt_diff);
					}
				}
			}
		}
	}

   	public function getMembership($total_spent) {
		$membership = array();
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group` ORDER BY min_spend_amount DESC");
		if($query->num_rows) {
			foreach($query->rows as $d) {
				if($total_spent >= $d['min_spend_amount']) {
					$membership = $d;
					break;
				}
			}
		}
		return $membership;
	}

	//public function updateMembership($customer_id, $old_customer_group_id, $new_customer_group_id) {
	public function updateMembership($d) {
		$customer_id = $d['customer_id']; 
		$old_customer_group_id = $d['old_customer_group_id']; 
		$new_customer_group_id = $d['new_customer_group_id']; 
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `customer_group_id` = '".$new_customer_group_id."' WHERE `customer_id` = '".$customer_id."'");
		// add record for membership
		$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_membership_records` SET `customer_id` = '".$customer_id."', `old_customer_group_id` = '".$old_customer_group_id."', `new_customer_group_id` = '".$new_customer_group_id."', `date_added` = NOW()");
	}

	//public function sendMembershipEmail($customer_id, $old_customer_group_id, $new_customer_group_id) {
	public function sendMembershipEmail($d) {
		$customer_id = $d['customer_id']; 
		$old_customer_group_id = $d['old_customer_group_id']; 
		$new_customer_group_id = $d['new_customer_group_id']; 
		$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomer($customer_id);
		$name = $customer_info['firstname'].' '.$customer_info['lastname'];
		$email = $customer_info['email'];
		//$email = 'williamyew@firstcom.com.sg';
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$subject = 'Membership Update - '.$this->config->get('config_name');
		$text = '';
		$this->load->model('account/customer_group');	
		$old_customer_group_info = $this->model_account_customer_group->getCustomerGroup($old_customer_group_id);
		$new_customer_group_info = $this->model_account_customer_group->getCustomerGroup($new_customer_group_id);
		$message = '<b>Hi '.$name.',</b><br><br>';
		// upgrade
		if($new_customer_group_id > $old_customer_group_id) {
			$message .= 'Congratulations! Your membership have now been upgraded to the '.$new_customer_group_info['name'].' tier!';
		}
		// downgrade
		else if($new_customer_group_id < $old_customer_group_id) {
			$message .= 'Unfortunately '.$name.', your membership have now been downgraded to the '.$new_customer_group_info['name'].' tier.';
		}
	//	$mail->setTo($this->config->get('config_email'));
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
	//	$mail->setHtml($this->load->view('mail/order', $data));
		$mail->setText($text);
		// $mail->send();
		// Pro email Template Mod
		if($this->config->get('pro_email_template_status')){
			$this->load->model('tool/pro_email');
			$email_params = array(
			'type' => 'customer.account.membership',
			'mail' => &$mail,
			'reply_to' => $this->config->get('config_email'),
			'data' => array(
				'the_message' => $message,
			),
			);
			
			$this->model_tool_pro_email->generate($email_params);
		}
		else{
			$mail->send();
		}
	}

	private function getMembershipAmountByCustomerGroupId($customer_group_id) {
		$query = $this->db->query("SELECT `min_spend_amount` FROM `" . DB_PREFIX . "customer_group` WHERE `customer_group_id` = '".$customer_group_id."'");
		return $query->row['min_spend_amount'];
	}
	
	private function sendReviewMembershipEmail($customer_id, $old_customer_group_id, $amt_diff) {
	   $this->load->model('account/customer');
	   $customer_info = $this->model_account_customer->getCustomer($customer_id);
	   $name = $customer_info['firstname'].' '.$customer_info['lastname'];
	   $email = $customer_info['email'];
	   //$email = 'williamyew@firstcom.com.sg';
	   $mail = new Mail();
	   $mail->protocol = $this->config->get('config_mail_protocol');
	   $mail->parameter = $this->config->get('config_mail_parameter');
	   $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
	   $mail->smtp_username = $this->config->get('config_mail_smtp_username');
	   $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
	   $mail->smtp_port = $this->config->get('config_mail_smtp_port');
	   $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	   $subject = 'Membership Reminder - '.$this->config->get('config_name');
	   $text = '';
	   $this->load->model('account/customer_group');	
	   $old_customer_group_info = $this->model_account_customer_group->getCustomerGroup($old_customer_group_id);
	   $message = '<b>Hi '.$name.',</b><br><br>';
	   $message .= 'Your current membership will be reviewed after 3 months. You need to spend '.($amt_diff).' more in order to maintain the '.$old_customer_group_info['name'].' tier membership.';	
   //	$mail->setTo($this->config->get('config_email'));
	   $mail->setTo($email);
	   $mail->setFrom($this->config->get('config_email'));
	   $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
	   $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
   //	$mail->setHtml($this->load->view('mail/order', $data));
	   $mail->setText($text);
	   // $mail->send();
	   // Pro email Template Mod
	   if($this->config->get('pro_email_template_status')){
		   $this->load->model('tool/pro_email');
		   $email_params = array(
		   'type' => 'customer.account.membership',
		   'mail' => &$mail,
		   'reply_to' => $this->config->get('config_email'),
		   'data' => array(
			   'the_message' => $message,
		   ),
		   );
		   
		   $this->model_tool_pro_email->generate($email_params);
	   }
	   else{
		   $mail->send();
	   }
   }
   // for membership tier module - end

   // for Event day promotions - start
   public function generateBirthdayCoupon() {
		$checkStatus = $this->config->get('setting_birthdaycoupon_status');

		if(isset($checkStatus) && $checkStatus)
		{
			$this->load->library('modulehelper');
			$Modulehelper = Modulehelper::get_instance($this->registry);

			$oc = $this;
			$language_id = $this->config->get('config_language_id');
			
			$modulename  = 'setting_birthdaycoupon';
			$module_data['coupon_type'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'coupon_type');
			$module_data['coupon_value'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'coupon_value');
			$module_data['coupon_min_use'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'coupon_min_use');
			$module_data['coupon_prefix'] = $Modulehelper->get_field ( $oc, $modulename, $language_id, 'coupon_prefix');
			
			// GET DAYS IN CURRENT MONTH RANGE
			$first_day_this_month = date('Y-m-01');
			$last_day_this_month  = date('Y-m-t');

			$currentDate = date('Y-m-d');
			$endDate = date('Y-m-d', (strtotime('+30 days', strtotime(date('Y-m-d')))));
			
			$currentMonth = date('M');
			
			$this->load->model('account/customer');

			// GET CUSTOMERS BIRTHDAY THIS MONTH
			$birthday_customer_list = $this->model_account_customer->getCurrentBirthdayCustomer();

			if(isset($birthday_customer_list) && $birthday_customer_list)
			{
				for($x = 0; $x < count($birthday_customer_list); $x++)
				{
					// RUNNING NUMBER
					$coupon_last = $this->model_account_customer->getBirthdayCouponLastID();
		
					$total_running_number = 6;
					
					$couponcode_running = str_pad($coupon_last, $total_running_number, "0", STR_PAD_LEFT);
		
					$data['name'] = "Birthday".$currentMonth."_".$coupon_last;
					$data['code'] = $module_data['coupon_prefix']."_".$couponcode_running;
					$data['discount'] = $module_data['coupon_value'];
					$data['type'] = $module_data['coupon_type'];
					$data['total'] = $module_data['coupon_min_use'];
					$data['logged'] = 1;
					$data['shipping'] = 0;
					$data['date_start'] = $currentDate;
					$data['date_end'] = $endDate;
					$data['uses_total'] = 1;
					$data['uses_customer'] = 1;
					$data['status'] = 1;
		
					$data['coupon_customer'] = $birthday_customer_list[$x]['customer_id'];
					
					$this->model_account_customer->addCoupon($data);

					$customeremail = $this->model_account_customer->findCustomerEmailByID($data['coupon_customer']);

					$this->load->language('mail/birthday');
					//SEND EMAIL OUT
					$subject = $this->language->get('text_subject');
					
					$message = $this->language->get('text_welcome') . "\n\n";
					$message .= $this->language->get('text_message') . "\n\n";
					$message .= $data['code'];
			
					$mail = new Mail();
					$mail->protocol = $this->config->get('config_mail_protocol');
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
					
					$mail->setTo($customeremail);
					$mail->setFrom($this->config->get('config_email'));
					$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
					$mail->setSubject($subject);
					$mail->setText($message);
					// $mail->send();
			
					// Pro email Template Mod
					if($this->config->get('pro_email_template_status')){
						$this->load->model('tool/pro_email');

						if($data['type'] == "P")
						{
							$dollarsign = "";
							$coupon_type = "%";
						}else if($data['type'] == "F"){
							$dollarsign = "$";
							$coupon_type = "Flat Rate";
						}

						if($data['total'] == 0)
						{
							$coupon_minimum = "no minimum spend!";
						}else{
							$coupon_minimum = "a minimum spend of: $".$data['total'].".";
						}

						$email_params = array(
							'type' => 'customer.information.birthday',
							'mail' => $mail,
							'data' => array(
								'birthday_coupon' => $data['code'],
								'coupon_value' => $dollarsign.$data['discount'],
								'coupon_type'	=> $coupon_type,
								'coupon_minimum' => $coupon_minimum,
							),
						);
						
						$this->model_tool_pro_email->generate($email_params);
					}
					else{
						$mail->send();
					}
				}
			}
		}
	}
	// for Event day promotions - end

	public function clearCustomerReward() {

		$this->load->model('account/customer_group');

		$this->load->model('account/customer');

		$this->load->model('account/customer_group');

		$this->load->language('account/account');

		$customer_groups = $this->model_account_customer_group->getCustomerGroups();

		foreach ($customer_groups as $customer_group) {

			$today = date('Y-m-d');
			$customer_group_id = $customer_group['customer_group_id'];

			// Retrieves customer group reward point dates (start date, end date, clear date) by customer group
			$reward_dates = $this->model_account_customer_group->getCustomerGroupRewardDates($customer_group_id);

			foreach($reward_dates as $reward_date) {
				$start_date = $reward_date['start_date'];
				$end_date = $reward_date['end_date'];
				$clear_date = $reward_date['clear_date'];

				// $start_date = $customer_group['start_date'];
				// $end_date = $customer_group['end_date'];
				// $clear_date = $customer_group['clear_date'];

				if ($today == $clear_date) {
					/* clear reward from date1 to date2 for customer who is within the customer group */

					$customers = $this->model_account_customer->getCustomersByCustomerGroup($customer_group_id);

					foreach ($customers as $customer) {
						
						$customer_id = $customer['customer_id'];
						
						$points = $this->model_account_customer->getRewardTotalByCustomerId($customer_id, $start_date, $end_date);

						if ($points > 0) {
							$this->model_account_customer->clearReward($customer_id, $points, $this->language->get('text_clear_reward'));
						}

					} /* foreach customer */

				} /* if today = clear date*/
			
			} /* foreach reward dates */

		} /* foreach customer */
	}

	public function notifyLowStock() {

		$notify = $this->config->get('config_low_stock_notify');

		if ($notify) {

			$low_stock_quantity = $this->config->get('config_low_stock_quantity');

			$this->load->model('catalog/product');

			$this->load->language('mail/product');

			$low_stock_products = $this->model_catalog_product->getLowStockProducts($low_stock_quantity);

			if($low_stock_products) {

				$product_table = '<table class="invoice">';
				$product_table .= '<thead>';
				$product_table .= '<tr>';
				$product_table .= '<td>'. $this->language->get('column_product_id') .'</td>';
				$product_table .= '<td class="text-left">'. $this->language->get('column_name') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_sku') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_model') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_price') .'</td>';
				$product_table .= '<td>'. $this->language->get('column_quantity') .'</td>';
				$product_table .= '</tr>';
				$product_table .= '</thead>';

				$product_table .= '<tbody>';
				foreach ($low_stock_products as $product) {
					$product_table .= '<tr>';
			          $product_table .= '<td>'.$product['product_id'].'</td>';
			          $product_table .= '<td class="text-left">'.$product['name'].'</td>';
			          $product_table .= '<td>'.$product['sku'].'</td>';
			          $product_table .= '<td>'.$product['model'].'</td>';
			          $product_table .= '<td>'.$product['price'].'</td>';
			          $product_table .= '<td>'.$product['quantity'].'</td>';
					$product_table .= '</tr>';
				}
				$product_table .= '</tbody>';
				$product_table .= '</table>';
					
				$subject = $this->language->get('text_subject') . date('j F Y, l, H:i');
				
				$message = $this->language->get('text_welcome') . "\n\n";
				$message .= $this->language->get('text_message') . "\n\n";

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
				$mail->setSubject($subject);
				$mail->setText($message);
				// $mail->send();

				// Pro email Template Mod
				if($this->config->get('pro_email_template_status')){
					$this->load->model('tool/pro_email');

					$email_params = array(
						'type' => 'admin.stock',
						'mail' => $mail,
						'product_table' => $product_table
					);
					
					$this->model_tool_pro_email->generate($email_params);
				}
				else{
					$mail->send();
				}

			} /* if have low stock products */
		}
	}

}
