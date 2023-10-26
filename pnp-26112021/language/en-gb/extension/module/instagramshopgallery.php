<?php
$_['heading_title']              = 'Instagram Shop Gallery';

$_['text_main_setting']          = 'Main Settings';
$_['text_module']                = 'Module';
$_['text_page']                  = 'Page';
$_['text_support']               = 'Support';

$_['text_modules']               = 'Modules';
$_['text_enabled']               = 'Enabled';
$_['text_disabled']              = 'Disabled';
$_['button_cancel']              = 'Cancel';
$_['text_default']               = 'Default';
$_['text_close']                 = 'Close';
$_['text_save']                  = 'Save';
$_['text_no']                    = 'No';
$_['text_yes']                   = 'Yes';
$_['text_likes']                 = 'Likes';
$_['text_comments']              = 'Comments';
$_['text_na']                    = 'n/a';

$_['entry_title']                = 'Title';
$_['entry_status']               = 'Status';
$_['entry_global_status']        = 'Global Status';
$_['entry_validate_media']       = 'Validate Media';
$_['entry_media_source']         = 'Media Source';
$_['entry_hashtag']              = 'Hashtag Search';
$_['entry_basic_app_id']         = 'Instagram App ID';
$_['entry_basic_app_secret']     = 'Instagram App Secret';
$_['entry_oauth_redirect_uri']   = 'OAuth Redirect URIs';
$_['entry_graph_app_id']         = 'Facebook App ID';
$_['entry_graph_app_secret']     = 'Facebook App Secret';
$_['entry_access_token']         = 'Access Token';
$_['text_authorize_access']      = 'Get Token';

$_['entry_visibility']           = 'Media Visibility';
$_['entry_media_limit']          = 'Media Limit';
$_['entry_extra_image']          = 'Extra Image';
$_['entry_extra_link']           = 'Extra Image Link';
$_['entry_banner_image']         = 'Banner Image';
$_['entry_banner_link']          = 'Banner Image Link';
$_['entry_navbar']               = 'Main Navigation';
$_['entry_custom_css']           = 'Custom CSS';
$_['entry_seo_options']          = 'SEO Options';
$_['entry_meta_title']           = 'Meta Title';
$_['entry_meta_desc']            = 'Meta Description';
$_['entry_meta_keywords']        = 'Meta Keywords';
$_['entry_url_alias']            = 'URL Alias';
$_['entry_ig_user_id']           = 'IG User Id';
$_['entry_username']             = 'Username';
$_['entry_post_time']            = 'Post Time';
$_['entry_approve']              = 'Approve';
$_['entry_store']                = 'Stores';
$_['entry_products']             = 'Products';

$_['entry_extra_image_help']     = 'Extra image added to the end of media list. For best result, size at least 480px x 480px';
$_['entry_banner_image_help']    = 'Banner image placed at top of the page. For best result, width size at least 1920px';
$_['entry_extra_link_help']      = 'Full URL address, including \'http(s)://\' protocol.';
$_['entry_navbar_help']          = 'Display in the top main navigation bar.';
$_['entry_oauth_redirect_info']  = 'Deauthorize Callback and Data Deletion Request URL: ';

$_['text_moderation']            = 'Moderation';
$_['text_saved_media']           = 'Saved Media';
$_['text_information']           = 'Information';
$_['text_migration']             = 'Migration';
$_['text_media_source_basic']    = 'Media Source: Account';
$_['text_media_source_graph']    = 'Media Source: Hashtag Search';
$_['text_fetch']                 = 'Fetch';
$_['text_fetch_info']            = 'Click <code>Fetch</code> to get media list.';
$_['text_resave']                = 'resave';
$_['text_legacy']                = 'legacy';
$_['text_load_more']             = 'Load more';
$_['text_load_more']             = 'Load more';
$_['text_processing']            = 'Processing..';
$_['text_loading']               = 'Loading..';
$_['text_only_approved']         = 'Only approved';
$_['text_have_related']          = 'Have related product';
$_['text_approve_have_related']  = 'Approved and related product';

$_['text_aliasing']              = 'Aliasing';
$_['text_media_manage']          = 'Click to open media modal';
$_['text_approval_status']       = 'Approval status';
$_['text_related_product']       = 'Total related product';
$_['text_remove_db']             = 'Remove from database';

$_['text_info_setting']          = array(
  'Global Status cover module and page tab.',
  'Validate Media check each media availability before show them. First check might be slower before the result is cached.',
  'Each Media Source require different Facebook App setup.'
);
$_['text_info_setting_source_basic']          = array(
  'Fetch media from user account (Personal, Business, or Creator).',
  'FB APP product: <b>Instagram</b>.<br>Follow <a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">step 1-3</a> from Facebook guide.',
  'Instagram App ID and APP Secret at <code>FB App > Products > Instagram > Basic Display</code>',
  'Button "Get Access Token" will appear once you save App ID and App Secret in the module input.'
);
$_['text_info_setting_source_graph']          = array(
  'Explore media by hashtag.',
  'Set up your Facebook App:
    <ul class="isl-list">
      <li>Login to your Facebook Developer account and create a Facebook App if you don\'t have one.<br>*Check <a href="https://goldplugins.com/documentation/wp-social-pro-documentation/how-to-get-an-app-id-and-secret-key-from-facebook/" target="_blank">this guide</a>\'s step 1 and 2 if you\'re having difficulties.
      <li>Add Facebook Login to your App by clicking on the plus icon next to <b>PRODUCTS</b>. After that, choose <b>Web</b> as platform, finish only step 1 by entering your website\'s URL, and leave the other 4 steps (2,3,4,5).<br>*Check <a href="https://developers.facebook.com/docs/instagram-api/getting-started" target="_blank">this guide</a>\'s step 1 if you\'re having difficulties.</li>
      <li>Login to your Buisness/Creator Instagram account. <br>*If you don\'t have one, follow the steps in <a href="https://business.instagram.com/getting-started?fbclid=IwAR1szXxAxSLYo7jVavc7JvHaqMj8n2iM5smMx72Nly9WyNiMHVgdzmqlWPY" target="_blank">this guide</a>, on how to create one.</li>
      <li>Link your Instagram Account to your Facebook account in your Instagram from <code>Settings > Account > Linked Accounts > Facebook</code>.</li>
    </ul>',
  'App ID and APP Secret at <code>FB App > Settings > Basic</code>.<br>*Check step 3 from <a href="https://goldplugins.com/documentation/wp-social-pro-documentation/how-to-get-an-app-id-and-secret-key-from-facebook/" target="_blank">this guide</a> if you can\'t find the data.<br>*Button "Continue with Facebook" will appear once you save App ID and App Secret in the module input.',
  'Website is required to have <b>HTTPS</b> URL for the app to run properly. <br>*If your site in not running via SSL, check out <a href="https://isenselabs.com/posts/for-dummies-how-to-set-up-an-ssl-certificate-in-opencart" target="_blank">this article</a> which show how to up SSL in your site.',
  'API Limitation from Facebook:
        <ul class="isl-list">
          <li>30 Unique hashtags within 7 day period. Using the same hashtag within a single range of time will not be counted additionally, meaning that your limitation is only for unique hashtags.</li>
          <li>Personally identifiable information (ex. username) will not be included in responses.</li>
          <li>Hashtags on Stories are not supported.</li>
          <li>Does not work on hashtags that have deemed sensitive or offensive.</li>
        </ul>',
  // 'Must undergo App review and approval for "Instagram Public Content Access" feature.'

);
$_['text_info_setting_moderation_migrate'] = array(
    'This panel appears when saved images prior to the Instagram API update is found.',
    'In the <b><i>Instagram fetch</i></b>, media with <code>resave</code> badge mean the same media is found in database. Open the media modal and resave.',
    'In the <b><i>Saved Media fetch</i></b>, media with <code>legacy</code> badge mean it is saved media prior to Instagram API update.',
);
$_['text_info_setting_moderation'] = array(
    'Click image to show media modal to update approval, stores and related products.',
    'Media with a "no-image" thumbnail or broken image is media that <b><i>inaccessible</i></b> any more. We recommend you to remove it.',
    'Change fetching source from the <code>dropdown</code>',
    '<code>Instagram Fetch</code> gathers recent media.',
    '<code>Saved Media Fetch</code> will load saved media information.',
    '<code>Load more</code> button will appear if next media list available.',
);
$_['text_info_module']          = array(
    'Assign module position through <code>Design > Layouts</code> to display on the page.',
    'Visibility setting:
        <ul class="isl-list">
          <li><code>Only approved</code> displays media that have been approved. Regardless of whether they have related product or not.</li>
          <li><code>Have related product</code> shows only posts that have related products. Does not matter whether the post is approved or not.</li>
          <li><code>Approved and related product</code> shows only posts which are approved and have related products added to them.</li>
        </ul>',
);
$_['text_info_page']          = array(
    'Visibility setting:
        <ul class="isl-list">
          <li><code>Only approved</code> displays media that have been approved. Regardless of whether they have related product or not.</li>
          <li><code>Have related product</code> shows only posts that have related products. Does not matter whether the post is approved or not.</li>
          <li><code>Approved and related product</code> shows only posts which are approved and have related products added to them.</li>
        </ul>',
);

// Tab Support
$_['text_your_license']          = 'Your license';
$_['text_please_enter_the_code'] = "Please enter your product purchase license code";
$_['text_activate_license']      = "Activate License";
$_['text_not_having_a_license']  = "Don't have a code? Get it from here.";
$_['text_license_holder']        = "License Holder";
$_['text_registered_domains']    = "Registered domains";
$_['text_expires_on']            = "License Expires on";
$_['text_valid_license']         = "VALID LICENSE";
$_['text_get_support']           = 'Get Support';
$_['text_community']             = "Community";
$_['text_ask_our_community']     = "Ask the community about your issue on the iSenseLabs forum.";
$_['text_tickets']               = 'Tickets';
$_['text_open_a_ticket']         = 'Want to communicate one-to-one with our tech people? Then open a support ticket.';
$_['text_pre_sale']              = 'Pre-sale';
$_['text_pre_sale_desc']         = 'Have a brilliant idea for your webstore? Our team of top-notch developers can make it real.';
$_['text_browse_forums']         = 'Browse forums';
$_['text_open_ticket_for_real']  = 'Open a ticket';
$_['text_bump_the_sales']        = 'Bump the sales';

// // Notification
$_['text_success']               = 'Success: You have modified InstagramShopGallery!';
$_['text_success_save']          = 'Successfully saved!';
$_['text_success_refresh_token'] = 'Success: access token successfully extended!';

$_['error_permission']           = 'Warning: You do not have permission to modify InstagramShopGallery!';
$_['error_general']              = 'Error occured, please try again later!';
$_['error_access_token']         = 'Valid access token required!';
$_['error_ig_not_respond']       = 'Instagram API is not responding, please try again later!';
$_['error_empty_database']       = 'No media found in database!';
$_['error_url_fopen']            = 'Warning! The <b>allow_url_fopen</b> needs to be enabled!';
