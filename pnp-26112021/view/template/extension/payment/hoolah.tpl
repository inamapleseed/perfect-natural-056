<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-hoolah" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
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
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-hoolah" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-api" data-toggle="tab"><?php echo $tab_api; ?></a></li>
                        <li><a href="#tab-instruction" data-toggle="tab"><?php echo $tab_instruction; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active in" id="tab-api">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-test"><span data-toggle="tooltip" title="<?php echo $help_test; ?>"><?php echo $entry_test; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="hoolah_test" id="input-test" class="form-control">
                                        <?php if ($hoolah_test) { ?>
                                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                            <option value="0"><?php echo $text_no; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_yes; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-merchant-id"><?php echo $entry_merchant_id; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="hoolah_merchant_id" value="<?php echo $hoolah_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="entry-merchant-id" class="form-control" />
                                  <?php if ($error_hoolah_merchant) { ?>
                                  <div class="text-danger"><?php echo $error_hoolah_merchant; ?></div>
                                  <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-merchant-key-live"><?php echo $entry_merchant_key_live; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="hoolah_merchant_key_live" value="<?php echo $hoolah_merchant_key_live; ?>" placeholder="<?php echo $entry_merchant_key_live; ?>" id="entry-merchant-key-live" class="form-control" />
                                  <?php if ($error_merchant_key_live) { ?>
                                  <div class="text-danger"><?php echo $error_merchant_key_live; ?></div>
                                  <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-merchant-key-test"><?php echo $entry_merchant_key_test; ?></label>
                                <div class="col-sm-10">
                                  <input type="text" name="hoolah_merchant_key_test" value="<?php echo $hoolah_merchant_key_test; ?>" placeholder="<?php echo $entry_merchant_key_test; ?>" id="entry-merchant-key-test" class="form-control" />
                                  <?php if ($error_merchant_key_test) { ?>
                                  <div class="text-danger"><?php echo $error_merchant_key_test; ?></div>
                                  <?php } ?>
                                </div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-2 control-label" for="entry-cdn-id"><?php echo $entry_cdn_id; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="hoolah_cdn_id" value="<?php echo $hoolah_cdn_id; ?>" placeholder="<?php echo $entry_cdn_id; ?>" id="entry-cdn-id" class="form-control" />
                                    <?php if ($error_hoolah_cdn_id) { ?>
                                        <div class="text-danger"><?php echo $error_hoolah_cdn_id; ?></div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="hoolah_order_status_id" id="input-order-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $hoolah_order_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-refund-status"><?php echo $entry_refund_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="hoolah_refund_status_id" id="input-refund-status" class="form-control">
                                        <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $hoolah_refund_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="hoolah_currency" id="input-currency" class="form-control">
                                        <?php foreach ($currencies as $currency) { ?>
                                        <?php if ($currency == $hoolah_currency) { ?>
                                        <option value="<?php echo $currency; ?>" selected="selected"><?php echo $currency; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="hoolah_sort_order" value="<?php echo $hoolah_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <select name="hoolah_status" id="input-status" class="form-control">
                                        <?php if ($hoolah_status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                        <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-instruction">
                            <h2>Step 1: Get an testing/live account from Hoolah</h2>
                            <br>
                            <h2>Step 2: Set the setting and use following to test the demo checkout</h2>
                            <strong>Phone-number / NRIC / Creditcard details for testing (Malaysia only)</strong>
                            <p>
                                You can use following details to complete the checkout demo:
                            </p>
                            <p>
                                Phone: +60 12345678 (we send the registration OTP to your email address)<br>
                                NRIC: S8009676C<br>
                                Credit Card: 4000 0000 0000 3055<br>
                                Validity: any future month / year<br>
                                CCV / CCV: any numeric value<br>
                            </p>
                            <hr>
                            <p>
                                <strong>Phone-number / NRIC / Creditcard details for testing (Singapore only)</strong>
                            </p>
                            <p>
                                You can use following details to complete the checkout demo:<br>
                                Phone: +65 1234 1234 (we send the registration OTP to your email address)<br>
                                NRIC: S8009676C<br>
                                Credit Card: 4242 4242 4242 4242<br>
                                Validity: any future month / year<br>
                                CCV / CCV: any numeric value<br>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script> 
<?php echo $footer; ?> 