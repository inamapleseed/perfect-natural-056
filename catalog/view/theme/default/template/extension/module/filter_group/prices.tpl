<?php if($active){ ?>
	<div id="side-price">
		<div class="list-group-item item-header"><?= $heading_title ?><?php if($left_symbol){ ?><label class="">, <?= $left_symbol; ?></label><?php } ?>
		</div>
		<div class="list-group-item">
			<div class="price-container">
			
				<div class="input-group ">
					<!-- <?php if($left_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $left_symbol; ?></label>
					<?php } ?> -->
					<input type="text" 
					name="price_min" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number" 
					value="<?= $price_min; ?>" 
					onkeyup="updateSlider();"
					id="price_min"
					placeholder="Min"
					/>
				</div>
				<span>&nbsp;-&nbsp;</span>
                <div class="hide text_price_left"><?= "$".$lowest_price; ?></div>
				<div class="input-group ">
					<!-- <?php if($left_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $left_symbol; ?></label>
					<?php } ?> -->
					<input type="text" 
					name="price_max" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number" 
					value="<?= $price_max; ?>"
					onkeyup="updateSlider();"
					id="price_max"
					placeholder="Max"
					/>
				</div>
                <!--<div class="text_price_right"><?= "$".$highest_price; ?></div>-->
			</div>
		
			<span id="min"></span>
			<span id="max"></span>
			<div id="slider-price"></div>


		</div>
		<!-- <script src="https://cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script> -->
		<!-- <script src="https://cdn.bootcss.com/ion-rangeslider/2.1.4/js/ion.rangeSlider.min.js"></script> -->

		<script type='text/javascript' >
            $(document).ready(function(){
				// $("#demo_1").ionRangeSlider({
				// 	type: "double",
				// 	grid: true,
				// 	min: <?= $lowest_price; ?>,
				// 	max: <?= $highest_price; ?>,
				// 	from: <?= $lowest_price; ?>,
				// 	to: <?= $highest_price; ?>,
				// 	// prefix: ""
				// });
							
				$("#slider-price").slider({
					min: <?= $lowest_price; ?>,
					max: <?= $highest_price; ?>,
					values: [<?= $price_min; ?>, <?= $price_max; ?>],
					range: true,
					create: function (event, ui) {
						$(".ui-slider-handle").attr("onclick", "");
						//$('#min').appendTo($('#slider a').get(0));
						//$('#max').appendTo($('#slider a').get(1));
					},
					slide: function (event, ui) {
						val = $(this).slider("values");

						price_min = val[0].toFixed(2);
						price_max = val[1].toFixed(2);

						$("input[name='price_min']").val(price_min);
						$("input[name='price_max']").val(price_max);
										
						$(".text_price_left").text("$"+price_min);
						$(".text_price_right").text("$"+price_max);

						var delay = function() {
							var handleIndex = $(ui.handle).data('uiSliderHandleIndex');
							var label = handleIndex == 0 ? '#min' : '#max';
							$(label).html('$' + ui.value).position({
								my: 'center top',
								at: 'center bottom',
								of: ui.handle,
								offset: "0, 10"
							});
						};

						// wait for the ui.handle to set its position
						setTimeout(delay, 5);


					},
					stop: function (event, ui) {
						val = $(this).slider("values");

						price_min = val[0].toFixed(2);
						price_max = val[1].toFixed(2);

						$("input[name='price_min']").val(price_min);
						$("input[name='price_max']").val(price_max);
											
						$(".text_price_left").text("$"+price_min);
						$(".text_price_right").text("$"+price_max);

						applyFilter(false);
					}
				});
							
				$('#min').html('$' + $("#slider-price").slider('values', 0)).position({
					my: 'center top',
					at: 'center bottom',
					of: $('#slider-price span:eq(0)'),
					offset: "0, 10"
				});

				$('#max').html('$' + $("#slider-price").slider('values', 1)).position({
					my: 'center top',
					at: 'center bottom',
					of: $('#slider-price span:eq(1)'),
					offset: "0, 10"
				});

			});
			function updateSlider(){

				let price_min = $("input[name='price_min']").val();
				let price_max = $("input[name='price_max']").val();

				$("#slider-price").slider( "values", [price_min, price_max]);
				
				if(price_min > -1 && price_max > -1){
					applyFilter(false);
				}
			}
		</script>
		
	</div>
	
<?php } ?>