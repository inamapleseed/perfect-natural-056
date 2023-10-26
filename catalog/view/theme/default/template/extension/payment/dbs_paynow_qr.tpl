<style type="text/css">
	#qrcode {
		margin-top:15px;
	}
	#qrcode canvas{
		max-height: 200px;
	}
</style>

<div class="custom-heading" style="margin: 4rem 0 2rem;"><h2 ><?php echo $text_instruction; ?></h2></div>
<div style="width: 100%;float: left;">
	<?php if ($error) { ?>
	  <div class="alert alert-danger" style="margin: 3rem 0;width: 100%;float: left;">
	    <i class="fa fa-exclamation-circle"></i>
	    <?= $error; ?>
	    <button type="button" class="close" data-dismiss="alert">&times;</button>
	  </div>
	  <?php } ?>
	<div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12 instructions well well-sm">
		<?php if ($qrImgBase64) { ?>
			<div class="col-md-6 col-sm-5 col-xs-12 left">
				<p><?php echo $instruction; ?></p>
			</div>
			<div class="col-md-6 col-sm-7 col-xs-12 right text-right">
				<div class="qr_code_holder">
					<img src="data:image/png;base64, <?=$qrImgBase64?>">
				</div>
				<div id="countdown_timer_holder"><?=$text_time_out?> <span id="countdown_timer"></span></div>
			</div>
		<?php } else { ?>

			<div class="col-xs-12 left">
				<?=$text_paynow_unavailable?>
			</div>
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
	setInterval(function(){
		$.ajax({
			url: 'index.php?route=extension/payment/dbs_paynow_qr/checkStatus',
			success: function(json) {
				if (json['redirect']) {
          			location = json['redirect'];
				}
			},
		});
	}, 5000);

	var timeout_timing = <?php echo $timeout_timing; ?>;
	if (timeout_timing > 0) {
		var cancel_url = "<?php echo $cancel_url ?>";
		var minutes2 = Math.floor(timeout_timing / 60000);
		var seconds2 = ((timeout_timing % 60000) / 1000).toFixed(0);
		var timer2 = minutes2 + ":" + (seconds2 < 10 ? "0" : "") + seconds2;
		var interval = setInterval(function() {
			var timer = timer2.split(":");
			var minutes = parseInt(timer[0], 10);
			var seconds = parseInt(timer[1], 10);
			--seconds;
			minutes = (seconds < 0) ? --minutes : minutes;
			if (minutes < 0) clearInterval(interval);
			seconds = (seconds < 0) ? 59 : seconds;
			seconds = (seconds < 10) ? "0" + seconds : seconds;
			$("#countdown_timer").html(minutes + ":" + seconds);
			timer2 = minutes + ":" + seconds;
		}, 1000);

		setTimeout(function(){
			location = cancel_url;
		}, timeout_timing);
	}

	// jQuery('#qrcode').qrcode({
	// 	text:$('#qr_string').val()
	// });	

</script>