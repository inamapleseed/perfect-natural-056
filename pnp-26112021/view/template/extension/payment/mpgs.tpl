<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-mpgs" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-mpgs" class="form-horizontal">
          <div class="well">
            Notes: <br>
            Get the test account from your vendor. IP whitelisting may be required for test environment. <br>
            Please make sure the site is SSL enabled for both test and live environment.
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_mode; ?></label>
            <div class="col-sm-10">
              <select name="mpgs_mode" id="input-mode" class="form-control">
                <?php if ($mpgs_mode == 'test') { ?>
                <option value="test" selected="selected"><?php echo $text_test; ?></option>
                <option value="live"><?php echo $text_live; ?></option>
                <?php } else { ?>
                <option value="test"><?php echo $text_test; ?></option>
                <option value="live" selected="selected"><?php echo $text_live; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant_name"><?php echo $entry_merchant_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mpgs_merchant_name" class="form-control" value="<?=$mpgs_merchant_name?>" placeholder="<?=$entry_merchant_name?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mpgs_merchant_id"><?php echo $entry_merchant_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mpgs_merchant_id" class="form-control" value="<?=$mpgs_merchant_id?>" placeholder="<?=$entry_merchant_id?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mpgs_integration_password"><?php echo $entry_integration_passowrd; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mpgs_integration_password" class="form-control" value="<?=$mpgs_integration_password?>" placeholder="<?=$entry_integration_passowrd?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mpgs_webhook_secret"><?php echo $entry_webhook_secret; ?></label>
            <div class="col-sm-10">
              <input type="text" name="mpgs_webhook_secret" class="form-control" value="<?=$mpgs_webhook_secret?>" placeholder="<?=$entry_webhook_secret?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_display_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input name="mpgs_payment_title<?php echo $language['language_id']; ?>" placeholder="<?php echo $entry_display_name; ?>" id="input-payment-title<?php echo $language['language_id']; ?>" class="form-control" value="<?php echo isset(${'mpgs_payment_title' . $language['language_id']}) ? ${'mpgs_payment_title' . $language['language_id']} : ''; ?>">
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <div class="col-sm-10">
              <select name="mpgs_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mpgs_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-failed-order-status"><?php echo $entry_failed_order_status; ?></label>
            <div class="col-sm-10">
              <select name="mpgs_failed_order_status_id" id="input-failed-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $mpgs_failed_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="mpgs_status" id="input-status" class="form-control">
                <?php if ($mpgs_status) { ?>
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
              <input type="text" name="mpgs_sort_order" value="<?php echo $mpgs_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>