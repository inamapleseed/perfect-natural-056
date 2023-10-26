<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-lalamove" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-lalamove" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant_id"><?php echo $entry_merchant_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_merchant_id" value="<?php echo $lalamove_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-merchant_id" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant_password"><?php echo $entry_merchant_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_merchant_password" value="<?php echo $lalamove_merchant_password; ?>" placeholder="<?php echo $entry_merchant_password; ?>" id="input-merchant_password" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-merchant_url"><?php echo $entry_merchant_url; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_merchant_url" id="input-display" class="form-control">
                <option value="https://sandbox-rest.lalamove.com"   <?php if($lalamove_merchant_url == "https://sandbox-rest.lalamove.com"){ echo "selected"; } ?>><?php echo "Sandbox URL"; ?></option>
                <option value="https://rest.lalamove.com"           <?php if($lalamove_merchant_url == "https://rest.lalamove.com"){ echo "selected"; } ?>><?php echo "Live URL"; ?></option>
              </select>
              <!--<input type="text" name="lalamove_merchant_url" value="<?php echo $lalamove_merchant_url; ?>" placeholder="<?php echo $entry_merchant_url; ?>" id="input-merchant_url" class="form-control" />-->
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-owner_name"><?php echo "Owner Name"; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_owner_name" value="<?php echo $lalamove_owner_name; ?>" placeholder="<?php echo "Owner Name"; ?>" id="input-owner_name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-owner_contact"><?php echo "Owner Contact Number"; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_owner_contact" value="<?php echo $lalamove_owner_contact; ?>" placeholder="<?php echo "Owner Contact"; ?>" id="input-owner_contact" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-owner_postcode"><?php echo "Owner Postal Code"; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_owner_postcode" value="<?php echo $lalamove_owner_postcode; ?>" placeholder="<?php echo "Owner Postal Code"; ?>" id="input-owner_postcode" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-owner_address"><?php echo "Owner Address"; ?></label>
            <div class="col-sm-10">
              <textarea name="lalamove_owner_address" placeholder="<?php echo "Owner Address"; ?>" rows="5" id="input-owner_address" class="form-control"><?php echo $lalamove_owner_address; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_total" value="<?php echo $lalamove_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
<!--          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $lalamove_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-display"><?php echo $entry_display; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_display" id="input-display" class="form-control">
                <?php if ($lalamove_display) { ?>
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
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_status" id="input-status" class="form-control">
                <?php if ($lalamove_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-test"><?php echo "Sandbox (testing) mode"; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_test" id="input-test" class="form-control">
                    <option value="0" <?php if($lalamove_test == "0"){echo "SELECTED";} ?>>No</option>
                    <option value="1" <?php if($lalamove_test == "1"){echo "SELECTED";} ?>>Yes</option>
              </select>
            </div>
          </div>-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-service_type"><?php echo $entry_service_type; ?></label>
            <div class="col-sm-10">
              <select name="lalamove_service_type" id="input-service_type" class="form-control">
                    <option value="AUTO" <?php if($lalamove_service_type == "AUTO"){echo "SELECTED";} ?>>Auto</option>
                    <option value="MOTORCYCLE" <?php if($lalamove_service_type == "MOTORCYCLE"){echo "SELECTED";} ?>>Bike</option>
                    <option value="CAR" <?php if($lalamove_service_type == "CAR"){echo "SELECTED";} ?>>Car</option>
                    <option value="MINIVAN" <?php if($lalamove_service_type == "MINIVAN"){echo "SELECTED";} ?>>1.7m Van</option>
                    <option value="VAN" <?php if($lalamove_service_type == "VAN"){echo "SELECTED";} ?>>2.4m Van</option>
                    <option value="TRUCK330" <?php if($lalamove_service_type == "TRUCK330"){echo "SELECTED";} ?>>10ft Lorry</option>
                    <option value="TRUCK550" <?php if($lalamove_service_type == "TRUCK550"){echo "SELECTED";} ?>>14ft Lorry</option>
              </select>
            </div>
          </div>
          
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-lalamove-order-status"><span data-toggle="tooltip" title="<?= "Order Status that auto send to Lalamove"; ?>"><?= "Send to Lalamove Shipping"; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <div class="checkbox">
                    <label>
                      <?php if (in_array($order_status['order_status_id'], $lalamove_order_status)) { ?>
                      <input type="checkbox" name="lalamove_order_status[]" value="<?= $order_status['order_status_id']; ?>" checked="checked" />
                      <?= $order_status['name']; ?>
                      <?php } else { ?>
                      <input type="checkbox" name="lalamove_order_status[]" value="<?= $order_status['order_status_id']; ?>" />
                      <?= $order_status['name']; ?>
                      <?php } ?>
                    </label>
                  </div>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-lalamove_completed_status"><span data-toggle="tooltip" title="<?= "Order Status that after Lalamove shipping completed"; ?>"><?= "After Lalamove Shipping"; ?></span></label>
              <div class="col-sm-10">
                  <select name="lalamove_completed_status" id="input-lalamove_completed_status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $lalamove_completed_status) { ?>
                      <option value="<?= $order_status['order_status_id']; ?>" selected="selected"><?= $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?= $order_status['order_status_id']; ?>"><?= $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-lalamove_cancelled_status"><span data-toggle="tooltip" title="<?= "Order Status that after Lalamove Cancelled and Expired shipping"; ?>"><?= "Lalamove Cancel or Expired"; ?></span></label>
              <div class="col-sm-10">
                  <select name="lalamove_cancelled_status" id="input-lalamove_cancelled_status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $lalamove_cancelled_status) { ?>
                      <option value="<?= $order_status['order_status_id']; ?>" selected="selected"><?= $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?= $order_status['order_status_id']; ?>"><?= $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
              </div>
            </div>
          
          <div class="table-responsive">
				<table id="delivery-time" class="table table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left" colspan="1"><?php echo "Delivery Times"; ?></td>
							<td class="text-left" colspan="1"><?php echo "Pikcup Time Format for Lalamove (eg.13:00:00)"; ?></td>
							<!--<td class="text-left" colspan="2"><?php echo "Lalamove Cut Off Time (eg.13:00:00) (For sameday)"; ?></td>-->
						</tr>
					</thead>
					<tbody>
						<?php $delivery_time_row = 0; ?>
						<?php foreach ($lalamove_delivery_times as $delivery_time) { ?>
							<tr id="delivery-time-<?php echo $delivery_time_row; ?>">
								<td class="text-left">
    								<?php foreach ($languages as $language) { ?>
    									<div class="input-group">
    										<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
    										<input type="text" name="lalamove_delivery_times[<?php echo $delivery_time_row; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($delivery_time[$language['language_id']]) ? $delivery_time[$language['language_id']] : ''; ?>" class="form-control" />
    									</div>
    								<?php } ?></td>
								<td class="text-left">
								    <?php foreach ($languages as $language) { ?>
    									<div class="input-group">
    										<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
    										<input type="text" name="lalamove_delivery_times_format[<?php echo $delivery_time_row; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($lalamove_delivery_times_format[$delivery_time_row][$language['language_id']]) ? $lalamove_delivery_times_format[$delivery_time_row][$language['language_id']] : ''; ?>" class="form-control" />
    									</div>
    								<?php } ?>
    							</td>
								<!--<td class="text-left"><?php foreach ($languages as $language) {
									?>
									<div class="input-group">
										<span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
										<input type="text" name="lalamove_delivery_times_cut_off[<?php echo $delivery_time_row; ?>][<?php echo $language['language_id']; ?>]" value="<?php echo !empty($lalamove_delivery_times_cut_off[$delivery_time_row][$language['language_id']]) ? $lalamove_delivery_times_cut_off[$delivery_time_row][$language['language_id']] : ''; ?>" class="form-control" />
									</div>
								<?php } ?></td>-->
								<td class="text-right"><a class="btn btn-danger" onClick="$('#delivery-time-<?php echo $delivery_time_row; ?>').remove();"><?php echo $button_remove; ?></a></td>
								<?php $delivery_time_row++; ?>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="3"><a class="btn btn-success" onClick="addTime();"><?php echo $button_add; ?></a></td>
						</tr>
					</tfoot>
				</table>
			</div>
          
          
          <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-generate_quotation_link"><?php echo $entry_generate_quotation_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_generate_quotation_link" value="<?php echo $lalamove_generate_quotation_link; ?>" placeholder="<?php echo $entry_generate_quotation_link; ?>" id="input-generate_quotation_link" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-post_order_link"><?php echo $entry_post_order_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_post_order_link" value="<?php echo $lalamove_post_order_link; ?>" placeholder="<?php echo $entry_post_order_link; ?>" id="input-post_order_link" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order_status_link"><?php echo $entry_order_status_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_order_status_link" value="<?php echo $lalamove_order_status_link; ?>" placeholder="<?php echo $entry_order_status_link; ?>" id="input-order_status_link" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-driver_info_link"><?php echo $entry_driver_info_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_driver_info_link" value="<?php echo $lalamove_driver_info_link; ?>" placeholder="<?php echo $entry_driver_info_link; ?>" id="input-driver_info_link" class="form-control" />
            </div>
          </div>
          <div class="form-group hide">
            <label class="col-sm-2 control-label" for="input-driver_location_link"><?php echo $entry_driver_location_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_driver_location_link" value="<?php echo $lalamove_driver_location_link; ?>" placeholder="<?php echo $entry_driver_location_link; ?>" id="input-driver_location_link" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cancel_order_link"><?php echo $entry_cancel_order_link; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lalamove_cancel_order_link" value="<?php echo $lalamove_cancel_order_link; ?>" placeholder="<?php echo $entry_cancel_order_link; ?>" id="input-cancel_order_link" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-remark"><?php echo $entry_remark; ?></label>
            <div class="col-sm-10">
              <textarea name="lalamove_remark" placeholder="<?php echo $entry_remark; ?>" id="input-remark" class="form-control"><?php echo $lalamove_remark; ?></textarea>
            </div>
          </div>-->
          <p style="font-weight:bold">Remember to set cronjob
            <br>
            <span style="color:red">wget -qO- --user=fcs --password=qwerty54321 <span style="color:orange">https://domainname.com.sg</span>/index.php?route=extension/lalamove_api/getOrderStatus &> /dev/null</span>
            <br>or<br>
            <span style="color:red">wget -qO- <span style="color:orange">https://domainname.com.sg</span>/index.php?route=extension/lalamove_api/getOrderStatus &> /dev/null</span></p>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
    var delivery_time_row = <?php echo $delivery_time_row; ?>;
	
	function addTime() {
		html  = '<tr id="delivery-time-' + delivery_time_row + '">';
		html += '  <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<div class="input-group"><span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>';
			html += '<input type="text" name="lalamove_delivery_times[' + delivery_time_row + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
			html += '</div>';
		<?php } ?>
		html += '  </td>';
		html += '  <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<div class="input-group"><span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>';
			html += '<input type="text" name="lalamove_delivery_times_format[' + delivery_time_row + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
			html += '</div>';
		<?php } ?>
		html += '  </td>';
		/*html += '  <td class="left">';
		<?php foreach ($languages as $language) { ?>
			html += '<div class="input-group"><span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>';
			html += '<input type="text" name="lalamove_delivery_times_cut_off[' + delivery_time_row + '][<?php echo $language['language_id']; ?>]" value="" class="form-control" />';
			html += '</div>';
		<?php } ?>
		html += '  </td>';*/
		html += '  <td class="text-right"><a class="btn btn-danger" onClick="$(\'#delivery-time-' + delivery_time_row + '\').remove();"><?php echo $button_remove; ?></a></td>';
		html += '</tr>';
		
		$('#delivery-time tbody').append(html);
		
		delivery_time_row++;
	}
</script>
<?php echo $footer; ?> 