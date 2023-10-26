<?php
/*
    Hoolah Integration API
    Empowering consumers to buy now, pay later, while spending responsibly. 0% interest 3 mos installment
    Reference:
    https://howto.onboarding.hoolah.co/bespoke-integration/#1563509311163-8bb29d58-427a
    https://api.hoolah.co/#tag/Getting-startedhttps://api.hoolah.co/#tag/Getting-started
    https://tryout.hoolah.co/
    Developed by: Pauline Janine Laude
*/
    
// Heading
$_['heading_title']					 = 'Hoolah Installment';

// Text
$_['text_extension']				 = 'Extensions';
$_['text_success']				 	 = 'Success: You have modified Hoolah Payment account details!';
$_['text_edit']                      = 'Edit Hoolah Payment';
$_['text_hoolah']				     = '<a target="_BLANK" href="https://www.hoolah.co/"><img src="view/image/payment/hoolah.png" alt="Hoolah Payment" title="Hoolah Payment" style="width: 70px; border: 1px solid #EEEEEE;" /></a>';

// Tab
$_['tab_api']				         = 'Settings';
$_['tab_instruction']		         = 'Instructions';

// Help
$_['help_currency']					 = 'Used for transaction searches';
$_['help_order_status']              = 'The order status after the customer has placed the order.';
$_['help_test']                      = 'Use the live or testing (demo) gateway server to process transactions?';

// Entry
$_['entry_test']					 = 'Test Mode';
$_['entry_merchant_id']				 = 'Merchant ID';
$_['entry_merchant_key_live']		 = 'Merchant Key (Live)';
$_['entry_merchant_key_test']		 = 'Merchant Key (Test)';
$_['entry_cdn_id']	            	 = 'CDN ID';
$_['entry_currency']				 = 'Currency';
$_['entry_order_status']             = 'Order Status';
$_['entry_refund_status']            = 'Refund Status';
$_['entry_status']					 = 'Status';
$_['entry_sort_order']				 = 'Sort Order';

// Error
$_['error_permission']				 = 'Warning: You do not have permission to modify payment PayPal Express Checkout!';
$_['error_hoolah_merchant']			 = 'Merchant ID is required!';
$_['error_merchant_key_live']		 = 'Merchant Key is required!';
$_['error_merchant_key_test']		 = 'Merchant Key is required!';
$_['error_hoolah_cdn_id']	 	     = 'CDN ID is required!';



// order
// Text
$_['text_extension']		 = 'Payment Information';
$_['text_capture_status']	 = 'Capture status';
$_['text_amount_authorised'] = 'Amount authorised';
$_['text_total_amount_captured']	 = 'Total amount order';
$_['text_amount_captured']	 = 'Amount captured';
$_['text_amount_refunded']	 = 'Amount refunded';
$_['text_transaction']		 = 'Transactions';
$_['text_complete']			 = 'Complete';
$_['text_confirm_void']		 = 'If you void you cannot capture any further funds';
$_['text_view']				 = 'View';
$_['text_refund']			 = 'Refund';
$_['text_resend']			 = 'Resend';
$_['text_success']           = 'Transaction was successfully sent';
$_['text_full_refund']		 = 'Full refund';
$_['text_partial_refund']	 = 'Partial refund';

$_['text_current_refunds']   = 'Refunds have already been done for this transaction. The max refund is';

// Column
$_['column_transaction']	 = 'Transaction ID';
$_['column_amount']			 = 'Amount';
$_['column_type']			 = 'Payment Type';
$_['column_status']			 = 'Status';
$_['column_pending_reason']	 = 'Pending Reason';
$_['column_date_added']		 = 'Date Added';
$_['column_action']			 = 'Action';

// Entry
$_['entry_capture_amount']	 = 'Capture amount';
$_['entry_capture_complete'] = 'Complete capture';
$_['entry_full_refund']		 = 'Full refund';
$_['entry_amount']			 = 'Amount';
$_['entry_note']             = 'Note';

// Help
$_['help_capture_complete']  = 'If this is a the last capture.';

// Tab
$_['tab_capture']		     = 'Capture';
$_['tab_refund']             = 'Refund';

// Button
$_['button_void']			 = 'Void';
$_['button_capture']		 = 'Capture';
$_['button_refund']		     = 'Issue refund';

// Error
$_['error_capture']		     = 'Enter an amount to capture';
$_['error_transaction']	     = 'Transaction could not be carried out!';
$_['error_not_found']	     = 'Transaction could not be found!';
$_['error_partial_amt']		 = 'You must enter a partial refund amount';