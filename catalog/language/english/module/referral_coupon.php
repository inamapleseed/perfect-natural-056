<?php
// text enclosed in asterisks {} will be replaced by actual value.  For example, {friend_name} will be replaced by John, or {referral_coupon_discount} will be replaced by 10, and so on.

$_['heading_title'] = 'Referral Coupon Reward Program';
$_['heading_referral_form'] = 'Sending Referral Form';
$_['heading_referral_table'] = 'Your Referrals';

$_['text_account'] = 'Account';
$_['text_referral'] = 'Referral Program';
$_['html_info'] = 'When you refer a friend to the store, you will receive {sending_reward} {reward_type} and your friend will receive a coupon for {coupon_discount}.  When your friend redeems the coupon, you will receive an additional {coupon_redeemed_reward} {reward_type}.  Coupon conditions: <ul><li>Order total: {order_total}</li><li>Customer login: {customer_login}</li><li>Expire in: {expire} days</li><li>Uses per coupon: {uses_total}</li><li>Uses per customer: {uses_customer}</li></ul>'; //You can use html tags here
$_['text_sent_success'] = 'Referral sent!';
$_['html_login'] = 'Please <a href="index.php?route=account/login">login</a> to start sending referrals and earning reward.';
$_['text_reward_point'] = 'Reward Point';
$_['text_store_credit'] = 'Store Credit';
$_['text_sending_limit'] = 'You can send {referrals} referrals in {hours} hours.  You have {remain} referrals remain.';

$_['button_send'] = 'Send Referral';
$_['button_add'] = 'Add Friend';
$_['button_remove'] = 'Remove';

$_['column_date'] = 'Referral Date';
$_['column_name'] = 'Friend Name';
$_['column_email'] = 'Friend E-Mail';

$_['entry_name'] = 'Friend Name:';
$_['entry_email'] = 'Friend E-Mail:';
$_['entry_message'] = 'Your Message:';
$_['text_sample_email'] = 'Sample Email';

$_['error_name'] = 'Name must be between 1 and 64 characters!';
$_['error_email_format'] = 'E-Mail does not appear to be valid!';
$_['error_email_existed'] = 'E-Mail already existed!';
$_['error_sending_limit_reached'] = 'You have reached the sending limit.  Please try again around {time}';

$_['coupon_name'] = 'Referral coupon for {referee_name}';
$_['text_sending_referral_reward_desc'] = 'Reward for referring {referee_name}';
$_['text_coupon_used_reward_desc'] = 'Reward for referral coupon redeemed - order id: {order_id}, referee: {referee_name}, referee email: {referee_email}';

$_['email_subject'] = 'Store coupon - Recommended by {referrer_name}';
$_['email_body'] = '
<p><a href="{store_link}" target="_blank"><img src="{store_logo}" alt="{store_name}" /></a></p>
<p>Hello {referee_name},</p>
<p>{referrer_message}</p>
<p>Your friend {referrer_name} has referred you to our store.  Please check it out and enjoy a discount of {coupon_discount} on your order.  Your coupon code is <b>{coupon_code}</b>.  Coupon conditions:</p>
<ul>
  <li>Order total: {order_total}</li>
  <li>Customer login: {customer_login}</li>
  <li>Expire on: {expire_date}</li>
  <li>Uses per coupon: {uses_total}</li>
  <li>Uses per customer: {uses_customer}</li>
</ul>
<p>Thank you.</p>
<p>{store_name}</p>
';

$_['email_subject_reward_notification'] = 'You receive {reward} {reward_type} from {store_name}';
$_['email_body_reward_notification'] = '
<p>Dear {customer},</p>
<p>You receive {reward} {reward_type}.</p>
<p>Your total reward is {reward_total}.</p>
<p>Thanks,</p>
<p>{store_name}</p>
';
?>
