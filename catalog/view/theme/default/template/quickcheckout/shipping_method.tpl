<?php if ($error_warning) { ?>
<div class="alert alert-danger"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($shipping_methods) { ?>
	<p><?php echo $text_shipping_method; ?></p>
<?php if ($shipping) { ?>

<table class="table table-hover">
	<?php foreach ($shipping_methods as $key => $shipping_method) { if ($key=='formula_based') { $key='complex'; } ?>

	<!--
  <tr>
    <td colspan="3" width="160px">
	  <?php if (!empty($shipping_logo[$key])) { ?>
	  <img src="<?php echo $shipping_logo[$key]; ?>" alt="<?php echo $shipping_method['title']; ?>" title="<?php echo $shipping_method['title']; ?>" class="img-responsive" />
	  <?php } else { ?>
	  <b><?php echo $shipping_method['title']; ?></b>
	  <?php } ?>
	</td>
	</tr>
	-->

  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
		<tr>
			<td><?php if ($quote['code'] == $code) { ?>
				<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" class="shipping-radios ship-method-<?=$key?>" checked="checked" />
				<?php } else { ?>
				<input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" class="shipping-radios ship-method-<?=$key?>" />
				<?php } ?></td>
			<td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
			<td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
		</tr>
			<?php 
			/* pick up location mod */
			if (isset($shipping_method['pickup_location']) && $shipping_method['pickup_location']) { $gotLocation = 1; ?>
				<tr class="location-holder <?= $quote['code'] == $code?'':'hide'?>">
					<td colspan="3">
						<label><?=$text_pick_up?></label>
						<select name="shipping_location" class="form-control">
							<?php foreach ( $shipping_method['pickup_location'] as $pikachu ) {  ?>
								<?php if ($pikachu) { ?>
									<option value="<?=$pikachu?>" <?=$shipping_location == $pikachu?'selected="selected"':''?>><?=$pikachu?></option>
								<?php } ?>
							<?php } ?>
						</select>
						<input type="hidden" name="is_location" value="<?= $quote['code'] == $code?'1':'0'?>" class="is_location">
					</td>
				</tr>
			<?php }
			/* pick up location mod */
			?>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
		</tr>
		<?php } ?>
		
		<?php } ?>
	</table>
	<?php } else { ?>
		<select class="form-control" name="shipping_method">
		<?php foreach ($shipping_methods as $shipping_method) { ?>
			<?php if (!$shipping_method['error']) { ?>
			<?php foreach ($shipping_method['quote'] as $quote) { ?>
				<?php if ($quote['code'] == $code) { ?>
					<?php $code = $quote['code']; ?>
				<?php $exists = true; ?>
				<option value="<?php echo $quote['code']; ?>" selected="selected">
				<?php } else { ?>
				<option value="<?php echo $quote['code']; ?>">
				<?php } ?>
				<?php echo $quote['title']; ?>&nbsp;&nbsp;(<?php echo $quote['text']; ?>) </option>
			<?php } ?>
		<?php } ?>
		<?php } ?>
		</select><br />
	<?php } ?>

<?php } ?>


<?php if ($delivery && $delivery_delivery_date) { ?>
	
<div<?php echo $delivery_required ? ' class="required"' : ''; ?> style="padding:8px;">
  <label class="control-label"><strong><?php echo $text_delivery; ?></strong></label>
	
	<div class="input-group date">
		<?php if ($delivery_delivery_time == '1') { ?>
		<input type="text" name="delivery_date" value="<?php echo $delivery_date; ?>" class="form-control readonly-white" readonly="true" />
		<?php } else { ?>
		<input type="text" name="delivery_date" value="<?php echo $delivery_date; ?>" class="form-control readonly-white" readonly="true" />
		<?php } ?>
		<label class="input-group-addon">
			<span class="glyphicon glyphicon-calendar"></span>
		</label>
	</div>


  <?php if ($delivery_delivery_time == '3') { ?><br />
    <select name="delivery_time" class="form-control"><?php foreach ($delivery_times as $quickcheckout_delivery_time) { ?>
    <?php if (!empty($quickcheckout_delivery_time[$language_id])) { ?>
      <?php if ($delivery_time == $quickcheckout_delivery_time[$language_id]) { ?>
	  <option value="<?php echo $quickcheckout_delivery_time[$language_id]; ?>" selected="selected"><?php echo $quickcheckout_delivery_time[$language_id]; ?></option>
	  <?php } else { ?>
	  <option value="<?php echo $quickcheckout_delivery_time[$language_id]; ?>"><?php echo $quickcheckout_delivery_time[$language_id]; ?></option>
      <?php } ?>
	<?php } ?>
    <?php } ?></select>
  <?php } ?>
</div>
<?php } elseif ($delivery_delivery_time && $delivery_delivery_time == '2') { ?>
	
  <input type="text" name="delivery_date" value="" class="hide" />
  <select name="delivery_time" class="hide"><option value=""></option></select>
  <strong><?php echo $text_estimated_delivery; ?></strong><br />
  <?php echo $estimated_delivery; ?><br />
  <?php echo $estimated_delivery_time; ?>
<?php } else { ?>
	
  <input type="text" name="delivery_date" value="" class="hide" />
  <select name="delivery_time" class="hide"><option value=""></option></select>
<?php } ?>

<?php 
	/* pick up location mod */
	if ($gotLocation) { ?>
	<script type="text/javascript">
		$('.shipping-radios').on('click',function() {
			if ($(this).hasClass('ship-method-pickup')) {
			   	if($(this).is(':checked')) { 
			   		$('.location-holder').removeClass('hide'); 
			   		$('.is_location').val('1');
			   	} else {
			   		$('.location-holder').addClass('hide'); 
			   		$('.is_location').val('0');
			   	}
		   	} else {
	   			$('.location-holder').addClass('hide'); 
		   		$('.is_location').val('0');
		  	}
		});
	</script>
<?php } 
	/* pick up location mod */
?>
<script type="text/javascript"><!--
$('#shipping-method input[name=\'shipping_method\'], #shipping-method select[name=\'shipping_method\'], #shipping-method select[name=\'shipping_location\']').on('change', function() {
	<?php if (!$logged) { ?>
		if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		} else {
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		}

		$.ajax({
			url: 'index.php?route=quickcheckout/shipping_method/set',
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart) { ?>
				loadCart();
				<?php } ?>
				<?php if ($shipping_reload) { ?>
				reloadPaymentMethod();
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } else { ?>
		if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
			var url = 'index.php?route=quickcheckout/shipping_method/set';
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		} else {
			var url = 'index.php?route=quickcheckout/shipping_method/set&address_id=' + $('#shipping-address select[name=\'address_id\']').val();
			var post_data = $('#shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		}
		
		$.ajax({
			url: url,
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			success: function(html) {
				<?php if ($cart) { ?>
				loadCart();
				<?php } ?>
				<?php if ($shipping_reload) { ?>
				if ($('#payment-address input[name=\'payment_address\']').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	<?php } ?>
});

$('#shipping-method input[name=\'shipping_method\'], #shipping-method select[name=\'shipping_method\']').on('click',function(){
	//set buffer time for quickcheckout/shipping_method/set to be finish 1st
	setTimeout(function(){
		<?php if (!$logged) { ?>
			if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
				reloadShippingMethod('payment');
			} else {
				reloadShippingMethod('shipping');
			}
		<?php } else { ?>
			if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
				reloadShippingMethod('shipping');
			}else{
				reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
			}
		<?php }  ?>
	},500);
});

$(document).ready(function() {
		$('#shipping-method input[name=\'shipping_method\']:checked, #shipping-method select[name=\'shipping_method\']').trigger('change');	
});

<?php if ($delivery && $delivery_delivery_time == '1') { ?>
$(document).ready(function() {
	//$('input[name=\'delivery_date\']').datetimepicker({
	$('.date').datetimepicker({
		format: 'YYYY-MM-DD HH:mm',
		minDate: '<?php echo $delivery_min; ?>',
		maxDate: '<?php echo $delivery_max; ?>',
		disabledDates: [<?php echo $delivery_unavailable; ?>],
		enabledHours: [<?php echo $hours; ?>],
		ignoreReadonly: true,
		keepInvalid : true,
		useCurrent: false,
		allowInputToggle: true,
		<?php if ($delivery_days_of_week != '') { ?>
		daysOfWeekDisabled: [<?php echo $delivery_days_of_week; ?>]
		<?php } ?>
	});
});
<?php } elseif ($delivery && ($delivery_delivery_time == '3' || $delivery_delivery_time == '2') || $delivery_delivery_date) { ?>
	//$('input[name=\'delivery_date\']').datetimepicker({
		$('.date').datetimepicker({
			format: 'YYYY-MM-DD',
			minDate: '<?php echo $delivery_min; ?>',
			maxDate: '<?php echo $delivery_max; ?>',
			disabledDates: [<?php echo $delivery_unavailable; ?>],
			ignoreReadonly: true,
			keepInvalid : true,
			useCurrent: false,
			allowInputToggle: true,
			<?php if ($delivery_days_of_week != '') { ?>
			daysOfWeekDisabled: [<?php echo $delivery_days_of_week; ?>]
			<?php } ?>
		});

		$('#shipping-method .date').on('dp.change', function(e){ 
			<?php if (!$logged) { ?>
				    if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadShippingMethod('payment');
					} else {
						reloadShippingMethod('shipping');
					}
			<?php } else { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
				    reloadShippingMethod('shipping');
				}else{
				    reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
			<?php }  ?>
			$('select[name=\'delivery_time\']').val('');
		});

		$('#shipping-method select[name=delivery_time]').on('change', function(e){ 
			<?php if (!$logged) { ?>
				    if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadShippingMethod('payment');
					} else {
						reloadShippingMethod('shipping');
					}
			<?php } else { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
				    reloadShippingMethod('shipping');
				}else{
				    reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
			<?php }  ?>
		});

		<?php if($clear_datetimepicker_value){ ?>
			$('.date').data("DateTimePicker").clear();
		<?php } ?>
<?php } ?>
//--></script>

<?php if($general_description){ ?>
	<div class="quickcheckout-alert alert alert-info">
		<?= $general_description; ?>
	</div>
<?php } ?>