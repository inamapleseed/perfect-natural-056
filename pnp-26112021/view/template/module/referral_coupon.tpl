<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $lng['button_cancel']; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $lng['text_edit']; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="<?php if ($tab == 'setting') echo 'active'; ?>"><a href="#tab-setting" data-toggle="tab"><?php echo $lng['tab_setting']; ?></a></li>
          <li class="<?php if ($tab == 'data') echo 'active'; ?>"><a href="#tab-data" data-toggle="tab"><?php echo $lng['tab_data']; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane <?php if ($tab == 'setting') echo 'active'; ?>" id="tab-setting">
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <form action="<?php echo $setting_action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
              <div class="pull-right">
                <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $lng['button_save']; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
              </div>
              <input type="hidden" name="referral_coupon_installed" value="<?php echo $referral_coupon_installed; ?>" />
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_status']; ?></label>
                <div class="col-sm-2">
                  <select name="referral_coupon_status" class="form-control">
                    <option value="0"><?php echo $lng['text_disabled']; ?></option>
                    <option <?php if ($referral_coupon_status) echo 'selected'; ?> value="1"><?php echo $lng['text_enabled']; ?></option>
                  </select>
                </div>
              </div>
              <h2><?php echo $lng['heading_email_setting']; ?></h2>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="limit"><span data-toggle="tooltip" title="<?php echo $lng['help_referral_limit']; ?>"><?php echo $lng['entry_referral_limit']; ?></span></label>
                <div class="col-sm-1">
                  <label for="limit"><?php echo $lng['text_limit']; ?></label>
                  <input type="number" min="0" name="referral_coupon_limit" id="limit" class="form-control" value="<?php echo $referral_coupon_limit; ?>" />
                </div>
                <div class="col-sm-1">
                  <label for="period"><?php echo $lng['text_period']; ?></label>
                  <input type="number" min="0" name="referral_coupon_period" id="period" class="form-control" value="<?php echo $referral_coupon_period; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_from_email']; ?>"><?php echo $lng['entry_from_email']; ?></span></label>
                <div class="col-sm-10">
                  <label for="from_email_config" class="radio-inline control-label">
                    <input type="radio" name="referral_coupon_from_email" id="from_email_config" value="config" checked />
                    <?php echo $config_email; ?>
                  </label>
                  <label for="from_email_referrer" class="radio-inline control-label">
                    <input type="radio" name="referral_coupon_from_email" id="from_email_referrer" value="referrer" <?php if ($referral_coupon_from_email == 'referrer') echo "checked"; ?> />
                    <?php echo $lng['text_referrer_email']; ?>
                  </label>
                  <label for="from_email_custom" class="radio-inline control-label">
                    <input type="radio" name="referral_coupon_from_email" id="from_email_custom" value="custom" <?php if ($referral_coupon_from_email == 'custom') echo "checked"; ?> />
                    <?php echo $lng['text_custom_email']; ?>
                    <input type="text" name="referral_coupon_from_custom_email" value="<?php echo $referral_coupon_from_custom_email; ?>" placeholder="<?php echo $lng['text_email']; ?>" />
                    <input type="text" name="referral_coupon_from_custom_name" value="<?php echo $referral_coupon_from_custom_name; ?>" placeholder="<?php echo $lng['text_name']; ?>" />
                  </label>
                </div>
              </div>
              <h2><?php echo $lng['heading_referrer_reward_setting']; ?></h2>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_reward_type']; ?></label>
                <div class="col-sm-2">
                  <select name="referral_coupon_reward_type" class="form-control">
                    <option value="point"><?php echo $lng['text_point']; ?></option>
                    <option <?php if ($referral_coupon_reward_type == 'credit') echo 'selected'; ?> value="credit"><?php echo $lng['text_credit']; ?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_referrer_sending_reward']; ?></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_referrer_sending_reward" value="<?php echo $referral_coupon_referrer_sending_reward; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label for="referrer_coupon_reward" class="col-sm-2 control-label"><?php echo $lng['entry_referrer_reward_for_coupon_used']; ?></label>
                <div class="col-sm-3">
                  <select name="referral_coupon_referrer_reward_for_coupon_used_type" class="form-control">
                    <option value="P"><?php echo $lng['text_percent']; ?></option>
                    <option value="F" <?php echo ($referral_coupon_referrer_reward_for_coupon_used_type == 'F' ? 'selected' : ''); ?> ><?php echo $lng['text_amount']; ?></option>
                  </select>
                </div>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_referrer_reward_for_coupon_used" value="<?php echo $referral_coupon_referrer_reward_for_coupon_used; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_notify']; ?></label>
                <div class="col-sm-2">
                  <div class="checkbox"><label><input type="checkbox" name="referral_coupon_notify" value="1" <?php if ($referral_coupon_notify) echo 'checked'; ?> /></label></div>
                </div>
              </div>
              <h2><?php echo $lng['heading_referee_coupon_setting']; ?></h2>
              <div><?php echo $checkout_note; ?></div>
              <div class="form-group">
                <label for="referrer_coupon_reward" class="col-sm-2 control-label"><?php echo $lng['entry_name']; ?></label>
                <div class="col-sm-10">
                  <span class="form-control"><?php echo $lng['text_name']; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label for="checkout_referee" class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_code']; ?>"><?php echo $lng['entry_code']; ?></span></label>
                <div class="col-sm-10">
                  <span class="form-control"><?php echo $lng['text_code']; ?></span>
                </div>
              </div>
              <div class="form-group">
                <label for="checkout_uses" class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_type']; ?>"><?php echo $lng['entry_type']; ?></span></label>
                <div class="col-sm-3">
                  <select name="referral_coupon_type" class="form-control">
                    <option value="P"><?php echo $lng['text_percent']?></option>
                    <option <?php if ($referral_coupon_type == "F") echo 'selected'; ?> value="F"><?php echo $lng['text_amount']?></option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_discount']; ?></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_discount" value="<?php echo $referral_coupon_discount; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_total']; ?>"><?php echo $lng['entry_total']; ?></span></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_total" value="<?php echo $referral_coupon_total; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_logged']; ?>"><?php echo $lng['entry_logged']; ?></span></label>
                <div class="col-sm-10">
                  <label for="referral_coupon_logged_yes" class="radio-inline">
                    <input type="radio" name="referral_coupon_logged" id="referral_coupon_logged_yes" value="1" <?php if ($referral_coupon_logged) echo 'checked'; ?> /> <?php echo $lng['text_yes']; ?>
                  </label>
                  <label for="referral_coupon_logged_no" class="radio-inline">
                    <input type="radio" name="referral_coupon_logged" id="referral_coupon_logged_no" value="0" <?php if (!$referral_coupon_logged) echo 'checked'; ?> /> <?php echo $lng['text_no']; ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_shipping']; ?></label>
                <div class="col-sm-10">
                  <label for="referral_coupon_shipping_yes" class="radio-inline">
                    <input type="radio" name="referral_coupon_shipping" id="referral_coupon_shipping_yes" value="1" <?php if ($referral_coupon_shipping) echo 'checked'; ?> /> <?php echo $lng['text_yes']; ?>
                  </label>
                  <label for="referral_coupon_shipping_no" class="radio-inline">
                    <input type="radio" name="referral_coupon_shipping" id="referral_coupon_shipping_no" value="0" <?php if (!$referral_coupon_shipping) echo 'checked'; ?> /> <?php echo $lng['text_no']; ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $lng['entry_expire']; ?></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_expire" value="<?php echo $referral_coupon_expire; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_uses_total']; ?>"><?php echo $lng['entry_uses_total']; ?></span></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_uses_total" value="<?php echo $referral_coupon_uses_total; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $lng['help_uses_customer']; ?>"><?php echo $lng['entry_uses_customer']; ?></span></label>
                <div class="col-sm-1">
                  <input type="number" min="0" name="referral_coupon_uses_customer" value="<?php echo $referral_coupon_uses_customer; ?>" class="form-control" />
                </div>
              </div>
            </form>
          </div>
          <div class="tab-pane <?php if ($tab == 'data') echo 'active'; ?>" id="tab-data">
            <div class="well">
              <div class="row">
                <form id="form-filter" class="form-horizontal">
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label class="control-label"><?php echo $lng['column_referrer']; ?></label>
                      <input type="text" name="filter_referrer" value="<?php echo $filter_referrer; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label class="control-label"><?php echo $lng['column_referee']; ?></label>
                      <input type="text" name="filter_referee" value="<?php echo $filter_referee; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label class="control-label"><?php echo $lng['column_coupon_code']; ?></label>
                      <input type="text" name="filter_coupon_code" value="<?php echo $filter_coupon_code; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label class="control-label"><?php echo $lng['column_date_added']; ?></label>
                      <div class="input-group date">
                        <input type="text" name="filter_referred_date" value="<?php echo $filter_referred_date; ?>" class="form-control" />
                        <span class="input-group-btn"><button class="btn btn-default"><i class="fa fa-calendar"></i></button></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-3" style="clear: both;">
                    <button type="button" onclick="filter();" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $lng['button_filter']; ?></button>
                    <button type="button" onclick="filter(1);" class="btn btn-primary"><i class="fa fa-eraser"></i> <?php echo $lng['button_clear_filter']; ?></button>
                  </div>
                </form>
              </div>
            </div>
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">
                    <a href="<?php echo $sort_url; ?>&sort=referrer" class="<?php if ($sort == 'referrer') echo $order; ?>"><?php echo $lng['column_referrer']; ?></a>
                  </td>
                  <td class="text-left">
                    <a href="<?php echo $sort_url; ?>&sort=crp.name" class="<?php if ($sort == 'crp.name') echo $order; ?>"><?php echo $lng['column_referee']; ?></a>
                  </td>
                  <td class="text-left">
                    <a href="<?php echo $sort_url; ?>&sort=cp.code" class="<?php if ($sort == 'cp.code') echo $order; ?>"><?php echo $lng['column_coupon_code']; ?></a>
                  </td>
                  <td class="text-left">
                    <a href="<?php echo $sort_url; ?>&sort=crr.referred_date" class="<?php if ($sort == 'crr.referred_date') echo $order; ?>"><?php echo $lng['column_date_added']; ?></a>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($referrals as $r) { ?>
                <tr>
                  <td class="text-left">
                    <?php echo $r['referrer']; ?>
                    [ <a href="index.php?route=<?php echo (version_compare(VERSION, '2.2', 'ge') ? 'customer' : 'sale'); ?>/customer/edit&token=<?php echo $token; ?>&customer_id=<?php echo $r['referrer_id']; ?>" target="_blank"><?php echo $lng['button_edit']; ?></a> ]
                  </td>
                  <td class="text-left">
                    <?php echo $r['referee']; ?>
                    <?php if ($r['referee_id']) { ?>
                    [ <a href="index.php?route=<?php echo (version_compare(VERSION, '2.2', 'ge') ? 'customer' : 'sale'); ?>/customer/edit&token=<?php echo $token; ?>&customer_id=<?php echo $r['referee_id']; ?>" target="_blank"><?php echo $lng['button_edit']; ?></a> ]
                    <?php } ?>
                  </td>
                  <td class="text-left"><a href="index.php?route=marketing/coupon/edit&token=<?php echo $token; ?>&coupon_id=<?php echo $r['coupon_id']; ?>" target="_blank"><?php echo $r['coupon_code']; ?></a></td>
                  <td class="text-left"><?php echo $r['referred_date']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $pagination_text; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="support"><a href="mailto:trile7@gmail.com?Subject=<?php echo urlencode($heading_title); ?>">Support Email</a></div>
    <div class="row" id="upgrade">Upgrade:
      <ol>
        <li><a href="http://tlecoding.gurleegirl.com/referral_coupon" target="_blank">Check for new upgrade</a></li>
        <li>Download, unzip, and upload file to opencart installation root</li>
        <li><a href="index.php?route=module/referral_coupon/install&token=<?php echo $token; ?>&redirect=1">Complete upgrade process</a></li>
      </ol>
      <a href="http://tlecoding.gurleegirl.com" target="_blank">My other extensions</a>
    </div>
  </div>
</div><!--id content end-->
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript" >
function validateModule() {
  error = 0;

  if (!$('#input-name').val()) {
    $('#input-name').parents('.form-group').addClass('has-error');
    $('#input-name').after('<div class="text-danger" id="error-name"><?php echo $lng['error_name']; ?></div>');
    error = 1;
  }

  if (error) return false;
} //validateModule end

function filter(clear_filter) {
  url = '<?php echo html_entity_decode($data_action, ENT_QUOTES, 'UTF-8'); ?>';
  if (!clear_filter) {
    url += '&' + $('#form-filter').serialize();
  }
  location = url;
} //filter end

$(function() {
  $('.date').datetimepicker({
    pickTime: false
  });

  $('input[name=filter_referee]').autocomplete({
    'source': function(request, response) {
  		$.ajax({
  			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
  			dataType: 'json',
  			success: function(json) {
  				response($.map(json, function(item) {
  					return {
  						label: item['name'],
  						value: item['customer_id']
  					}
  				}));
  			}
  		});
  	},
  	'select': function(item) {
  		$('input[name=filter_referee]').val(item['label']);
  	}
  });

  $('input[name=filter_referrer]').autocomplete({
    'source': function(request, response) {
  		$.ajax({
  			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
  			dataType: 'json',
  			success: function(json) {
  				response($.map(json, function(item) {
  					return {
  						label: item['name'],
  						value: item['customer_id']
  					}
  				}));
  			}
  		});
  	},
  	'select': function(item) {
  		$('input[name=filter_referrer]').val(item['label']);
  	}
  });
});
</script>
<?php echo $footer; ?>