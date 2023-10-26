<?php if($active){ ?>
	
	<div id="side-length">
		<div class="list-group-item item-header"><?= $heading_title; ?></div>
		<div class="list-group-item">
			<div class="length-container">
			
				<div class="input-group">
					<input type="text" 
					name="length_min" 
					min="<?= $lowest_length; ?>" 
					max="<?= $highest_length; ?>" 
					class="form-control input-number" 
					value="<?= $length_min; ?>" 
					onkeyup="updateSlider2();"
					id="length_min"
					/>
					<?php if($right_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $right_symbol; ?></label>
					<?php } ?>
				</div>
				<div class="input-group">
					<input type="text" 
					name="length_max" 
					min="<?= $lowest_length; ?>" 
					max="<?= $highest_length; ?>" 
					class="form-control input-number" 
					value="<?= $length_max; ?>"
					onkeyup="updateSlider2();"
					id="length_max"
					/>
					<?php if($right_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $right_symbol; ?></label>
					<?php } ?>
				</div>
			</div>
		
			<div id="slider-length"></div>
		</div>
		
		<script type='text/javascript' >
			$("#slider-length").slider({
				min: <?= $lowest_length; ?>,
				max: <?= $highest_length; ?>,
				step: 1.00,
				range: true,
				values: [<?= $length_min; ?>, <?= $length_max; ?>],
				create: function (event, ui) {
					$(".ui-slider-handle").attr("onclick", "");
				},
				slide: function () {
					val = $(this).slider("values");

					length_min = val[0].toFixed(2);
					length_max = val[1].toFixed(2);

					$("input[name='length_min']").val(length_min);
					$("input[name='length_max']").val(length_max);
				},
				stop: function (event, ui) {
					val = $(this).slider("values");
					length_min = val[0].toFixed(2);
					length_max = val[1].toFixed(2);

					$("input[name='length_min']").val(length_min);
					$("input[name='length_max']").val(length_max);

					applyFilter();
				}
			});

			function updateSlider2(){

				let length_min = $("input[name='length_min']").val();
				let length_max = $("input[name='length_max']").val();

				$("#slider-length").slider( "values", [length_min, length_max]);
				
				if(length_min > -1 && length_max > -1){
					applyFilter();
				}
			}
		</script>
		
	</div>
	
<?php } ?>