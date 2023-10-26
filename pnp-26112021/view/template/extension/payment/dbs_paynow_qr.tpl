<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-dbs-paynow-qr" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-dbs-paynow-qr" class="form-horizontal">
          <div class="well">
            Notes: <br>
            UEN + Suffix to be provided by merchant after onboarding <br>
            Please make sure the site is SSL enabled for both test and live environment.
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant_name"><?php echo $entry_merchant_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="dbs_paynow_qr_merchant_name" class="form-control" value="<?=$dbs_paynow_qr_merchant_name?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-proxy_value"><?php echo $entry_proxy_value; ?></label>
            <div class="col-sm-10">
              <input type="text" name="dbs_paynow_qr_proxy_value" class="form-control" value="<?=$dbs_paynow_qr_proxy_value?>">
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fds_id">FCS ID</label>
            <div class="col-sm-10">
              <input type="text" name="dbs_paynow_qr_fds_id" value="<?= $dbs_paynow_qr_fds_id; ?>" placeholder="FCS ID" id="input-fds_id" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fds_password">FCS Secret</label>
            <div class="col-sm-10">
              <input type="password" name="dbs_paynow_qr_fds_password" value="<?= $dbs_paynow_qr_fds_password; ?>" placeholder="FCS Secret" id="input-fds_password" class="form-control" />
            </div>
          </div>
          <div class="form-group hide">
            <label class="col-sm-2 control-label" for="input-fds_link">FCS Link</label>
            <div class="col-sm-10">
              <input type="text" name="dbs_paynow_qr_fds_link" value="https://fcspayment.online/dbsqr_v1/" placeholder="FCS Link" id="input-fds_link" class="form-control" />
            </div>
          </div>
          <?php foreach ($languages as $language) { ?>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-payment-title<?php echo $language['language_id']; ?>"><?php echo $entry_payment_title; ?></label>
              <div class="col-sm-10">
                <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                  <input name="dbs_paynow_qr_payment_title<?php echo $language['language_id']; ?>" placeholder="<?php echo $entry_payment_title; ?>" id="input-payment-title<?php echo $language['language_id']; ?>" class="form-control" value="<?php echo isset(${'dbs_paynow_qr_payment_title' . $language['language_id']}) ? ${'dbs_paynow_qr_payment_title' . $language['language_id']} : ''; ?>">
                </div>
              </div>
            </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-bank<?php echo $language['language_id']; ?>"><?php echo $entry_instruction; ?></label>
            <div class="col-sm-10">
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="dbs_paynow_qr_instruction<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_instruction; ?>" id="input-bank<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset(${'dbs_paynow_qr_instruction' . $language['language_id']}) ? ${'dbs_paynow_qr_instruction' . $language['language_id']} : "Please scan the QR code with your PayNow app to make payment\nFor mobile user, kindly save the QR Code and upload to your PayNow app"; ?></textarea>
              </div>
              <?php if (${'error_instruction' . $language['language_id']}) { ?>
              <div class="text-danger"><?php echo ${'error_instruction' . $language['language_id']}; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="dbs_paynow_qr_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $dbs_paynow_qr_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group hide">
            <label class="col-sm-2 control-label" for="input-failed-order-status"><?php echo $entry_failed_order_status; ?></label>
            <div class="col-sm-10">
              <select name="dbs_paynow_qr_failed_order_status_id" id="input-failed-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $dbs_paynow_qr_failed_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-timeout"><span data-toggle="tooltip" title="<?= $help_timeout; ?>"><?php echo $entry_timeout; ?></span></label>
            <div class="col-sm-10">
              <select name="dbs_paynow_qr_timeout" id="input-timeout" class="form-control">
                <?php foreach ($timeouts as $mili => $timeout) { ?>
                <?php if ($mili == $dbs_paynow_qr_timeout) { ?>
                <option value="<?php echo $mili; ?>" selected="selected"><?php echo $timeout; ?></option>
                <?php } else { ?>
                <option value="<?php echo $mili; ?>"><?php echo $timeout; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="dbs_paynow_qr_status" id="input-status" class="form-control">
                <?php if ($dbs_paynow_qr_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="dbs_paynow_qr_sort_order" value="<?php echo $dbs_paynow_qr_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>