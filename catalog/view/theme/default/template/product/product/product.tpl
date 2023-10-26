<?= $header; ?>
<div class="container">

	<?= $content_top; ?>

	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
	<div class="row"><?= $column_left; ?>

	<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-4'; ?>
	<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
	<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
	<?php } ?>

	<div id="content" class="<?= $class; ?>">
		<div class="row">
			<?php if ($column_left || $column_right) { ?>
				<?php $class = 'col-sm-4'; ?>
			<?php } else { ?>
				<?php $class = 'col-sm-4'; ?>
			<?php } ?>
			<div class="<?= $class; ?> col-lg-4 pd-lg-r60">
				<?php if ($vertical_slider) { ?>
					<?php include_once('product_image_vertical.tpl'); ?>
				<?php } else { ?>
					<?php include_once('product_image.tpl'); ?>
				<?php } ?>
			</div>

			<?php if ($column_left || $column_right) { ?>
				<?php $class = 'col-sm-4'; ?>
			<?php } else { ?>
				<?php $class = 'col-sm-4'; ?>
			<?php } ?>

			<div class="<?= $class; ?> col-lg-5">
				<?php include_once('product_description.tpl'); ?>
			</div>
			
			<div class="<?= $class; ?> col-lg-3">
                <?php include_once('product_options.tpl'); ?>
			</div>
		</div>


		<?php //if ($products) include_once('product_related.tpl'); ?>

      <!--
      <?php if ($tags) { ?>
      <p><?= $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?= $tags[$i]['href']; ?>"><?= $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?= $tags[$i]['href']; ?>"><?= $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
  -->
</div>
<?= $column_right; ?></div>
<?= $content_bottom; ?>

</div>
<?php include_once('product_attributes_reviews.tpl'); ?>

<?= $related_products_slider; ?>

<!-- FACEBOOK EVENT - CALLING SCRIPTS -->
<?php if (isset($facebook_events) ) { ?>
	<?= $facebook_events?>
<?php } ?>
<!-- FACEBOOK EVENT - CALLING SCRIPTS END -->

<script type="text/javascript"><!--

  // product option change image gallery
  	function destroySlick() {
	  	$('.product-image-main').on('destroy', function(event, slick){
	        //console.log('destroy');
	        $('.product-image-main').html('');
	    });
	  	$('.product-image-main').slick('unslick');

	  	$('.product-image-additional').on('destroy', function(event, slick){
	        //console.log('destroy');
	        $('.product-image-additional').html('');
	    });
	  	$('.product-image-additional').slick('unslick');
  	}
  
  	function initSlick() {
  	    
        let width2 = $(window).width();

        if(width2 > 767){
    	  	$('.product-image-main').on('init', function(event, slick){
    			//console.log('init');
    			<?= $product_zoom ? 'initEzPlus();' :'' ?>
    	    });
        }
	  	$('.product-image-main').on('afterChange', function(event, slick, currentSlide){
	        //console.log('change');
	        <?= $product_zoom ? 'initEzPlus();' :'' ?>
	    });

	  	$('.product-image-main').slick({
	  		slidesToShow: 1,
	  		slidesToScroll: 1,
	  		arrows: false,
	  		fade: true,
	  		infinite: false,
	  		asNavFor: '.product-image-additional',
	  		prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='fa fa-chevron-left'></div></div>",
	  		nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='fa fa-chevron-right'></div></div>",
	  	});

	  	function initEzPlus() {
            $('.slick-slide.slick-current.slick-active .m_image').ezPlus({
              responsive : true,
			  zoomType: 'inner',
			  cursor: 'crosshair',
			  scrollZoom: true,
              respond: [
                {
                  range: '0-767',
                  zoomType: 'inner'
                }
              ],
            });
        }   

      	setTimeout(function(){ 
		  	<?php if ($vertical_slider) { ?>
		  		$('.product-image-additional').slick({
		  			slidesToShow: 4,
		  			slidesToScroll: 1,
		  			asNavFor: '.product-image-main',
		  			dots: false,
		  			centerMode: false,
		  			focusOnSelect: true,
		  			infinite: false,
		  			vertical: true,
		  			prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><img src='image/catalog/slicing/general/up-arrow.png' alt='<'/></div></div>",
		  			nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><img src='image/catalog/slicing/general/down-arrow.png' alt='<'/></div></div>",
		  		});
		  	<?php } else { ?>

		  		$('.product-image-additional').slick({
		  			slidesToShow: 4,
		  			slidesToScroll: 1,
		  			asNavFor: '.product-image-main',
		  			dots: false,
		  			centerMode: false,
		  			focusOnSelect: true,
		  			infinite: false,
		  			prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='fa fa-chevron-left'></div></div>",
		  			nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='fa fa-chevron-right'></div></div>",
		  		});

		  	<?php } ?>

      		$('.product-image-additional').css('opacity',1);

			initMagnificPopup();  
     	}, 50);
  	}


  	initSlick();
    
	function initMagnificPopup() {
		//cl('mag popup');
		$('.main_images').magnificPopup({
	   		type: 'image',
	   		gallery: {
	   			enabled: true
	   		}
	   	});
	}

   // product option change image gallery 
   
   	//$(document).ready(function () {
	//   	initMagnificPopup();
   	//});



   	$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
	   	$.ajax({
	   		url: 'index.php?route=product/product/getRecurringDescription',
	   		type: 'post',
	   		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
	   		dataType: 'json',
	   		beforeSend: function() {
	   			$('#recurring-description').html('');
	   		},
	   		success: function(json) {
	   			$('.alert, .text-danger').remove();

	   			if (json['success']) {
	   				$('#recurring-description').html(json['success']);
	   			}
	   		}
	   	});
   	});
   //--></script>
   <?php if(!$enquiry){ ?>
   	<script type="text/javascript"><!--
   		$('#button-cart').on('click', function() {
   			if($('#input-quantity').val() > 0) {
   				$.ajax({
   					url: 'index.php?route=checkout/cart/add',
   					type: 'post',
   					data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
   					dataType: 'json',
   					beforeSend: function () {
   						$('#button-cart').button('loading');
   					},
   					complete: function () {
   						$('#button-cart').button('reset');
   					},
   					success: function (json) {
   						$('.alert.alert-danger, .text-danger').remove();
   						$('.form-group').removeClass('has-error');

   						if (json['error']) {
   							if (json['error']['option']) {
   								for (i in json['error']['option']) {
   									var element = $('#input-option' + i.replace('_', '-'));

   									if (element.parent().hasClass('input-group')) {
   										element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
   									} else {
   										element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
   									}
   								}
   							}

   							if (json['error']['recurring']) {
   								$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
   							}

					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');
				}

				if (json['success']) {

					//FACEBOOK EVENT - ADDTOCART
					if(json['facebookevent_status']){
						if (typeof fbq == 'function') {
							fbq('track', 'AddToCart', {
								content_name: json['content_name'], 
								content_ids: json['content_ids'],
								content_type: json['content_type'],
								value: json['price'],
								currency: json['currency']
							});
						}else{
							console.log('Pixel not found');
						}
					}
					//FACEBOOK EVENT - ADDTOCART END

					//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					swal({
						title: json['success_title'],
						html: json['success'],
						type: "success"
					});

					setTimeout(function () {
						$('#cart-quantity-total').text(json['total_quantity']);
						$('#cart-total').text(json['total']);
					}, 100);

					$('#cart > ul').load('index.php?route=common/cart/info ul > *');
				}

				if(json['error_stock_add']){
					swal({
						title: json['error_stock_add_title'],
						html: json['error_stock_add'],
						type: "error"
					});
				}

				if(json['error_outofstock']){
					swal({
						title: json['error_outofstock_title'],
						html: json['error_outofstock'],
						type: "error"
					});
				}
				
				if(json['error_minstock']){
					swal({
						title: json['error_minstock_title'],
						html: json['error_minstock'],
						type: "error"
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
   			}
   		});
   		//--></script>
   	<?php }else{ ?>
   		<script type="text/javascript"><!--
   			$('#button-enquiry').on('click', function () {
   				if ($('#input-quantity').val()  > 0) {
   					$.ajax({
   						url: 'index.php?route=enquiry/cart/add',
   						type: 'post',
   						data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
   						dataType: 'json',
   						beforeSend: function () {
   							$('#button-enquiry').button('loading');
   						},
   						complete: function () {
   							$('#button-enquiry').button('reset');
   						},
   						success: function (json) {
   							$('.alert, .text-danger').remove();
   							$('.form-group').removeClass('has-error');

   							if (json['error']) {
   								if (json['error']['option']) {
   									for (i in json['error']['option']) {
   										var element = $('#input-option' + i.replace('_', '-'));

   										if (element.parent().hasClass('input-group')) {
   											element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
   										} else {
   											element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
   										}
   									}
   								}

   								if (json['error']['recurring']) {
   									$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
   								}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						swal({
							title: json['success_title'],
							html: json['success'],
							type: "success"
						});

						setTimeout(function () {
							$('#enquiry-quantity-total').text(json['total_quantity']);
							$('#enquiry-total').text(json['total']);
						}, 100);

						$('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
   				}
   			});
   			//--></script>
   		<?php } ?>

   		<script type="text/javascript"><!--
   			$('#review').delegate('.pagination a', 'click', function(e) {
   				e.preventDefault();

   				$('#review').fadeOut('slow');

   				$('#review').load(this.href);

   				$('#review').fadeIn('slow');
   			});

   			$('#review').load('index.php?route=product/product/review&product_id=<?= $product_id; ?>');

   			$('#button-review').on('click', function() {
   				$.ajax({
   					url: 'index.php?route=product/product/write&product_id=<?= $product_id; ?>',
   					type: 'post',
   					dataType: 'json',
   					data: $("#form-review").serialize(),
   					beforeSend: function() {
   						$('#button-review').button('loading');
   					},
   					complete: function() {
   						$('#button-review').button('reset');
   					},
   					success: function(json) {
   						$('.alert-success, .alert-danger').remove();

   						if (json['error']) {
   							$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
   						}

   						if (json['success']) {
   							$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

   							$('input[name=\'name\']').val('');
   							$('textarea[name=\'text\']').val('');
   							$('input[name=\'rating\']:checked').prop('checked', false);
   						}

			// to reset recaptcha after form ajax submit
			if(typeof grecaptcha !== 'undefined' && grecaptcha && grecaptcha.reset) { 
				grecaptcha.reset(); 
			} 
		}
	});
   			});

   			/*  product review tab not active by default when it has only one tab */
   			var tab_active = false;
   			$('#product-tabs').find('li').each(function(){
   				if($(this).hasClass('active')){
   					tab_active = true;
   				}
   			});
   			if(!tab_active){
   				$('#product-tabs li:first-child a').trigger('click');
   			}
   			/*  product review tab not active by default when it has only one tab */

   			//--></script>
   			<input type='hidden' id='fbProductID' value='<?= $product_id ?>' />
   			<!-- << Related Options / Связанные опции  -->

   			<?php if ( !empty($ro_installed) ) { ?>

   				<?php if ( !empty($ro_data) || !empty($ro_settings['show_clear_options']) ) { // the common part and the part for option reset ?> 
   					<style>
   						.ro_option_disabled { color: #e1e1e1!important; }
   					</style>

   					<?php if ( empty($ro_custom_selectbox_script_included) ) { ?>
   						<?php if ( $ro_theme_name == 'theme625' || $ro_theme_name == 'theme628' || $ro_theme_name == 'theme638' || $ro_theme_name == 'theme649' || $ro_theme_name == 'theme707' || $ro_theme_name == 'theme725' || $ro_theme_name == 'theme759' || $ro_theme_name == 'themeXXX' ) { ?>
   							<script src="catalog/view/theme/<?php echo $ro_theme_name; ?>/js/jquery.selectbox-0.2.min.js" type="text/javascript"></script>
   							<style>
   								<?php if ( $ro_theme_name == 'theme725' ) { ?>
   									.sbDisabled { padding-left:10px; padding-top:8px; padding-bottom:8px; opacity:0.4; line-height:32px; }
   								<?php } else { ?>
   									.sbDisabled { padding-left:10px; padding-top:8px; padding-bottom:8px; opacity:0.4; line-height:37px; }
   								<?php } ?>
   							</style>
   							<?php
   							$ro_custom_selectbox_script_included = true;
   							?>
   						<?php } ?>
   					<?php } ?>	

   					<?php
   					$ro_tpl_common_js = 'catalog/view/extension/related_options/tpl/product_page_common.tpl';
   					if (class_exists('VQMod')) {
   						include( VQMod::modCheck( modification($ro_tpl_common_js) ) );
   					} else {
   						include( modification($ro_tpl_common_js) );
   					}	
   					?>

   				<?php } // the common part and the part for option reset ?>


   				<?php if ( !empty($ro_data) ) { // the part when the product has related options ?>

   					<?php
   					$ro_tpl_ro_js = 'catalog/view/extension/related_options/tpl/product_page_related_options.tpl';
   					if (class_exists('VQMod')) {
   						include( VQMod::modCheck( modification($ro_tpl_ro_js) ) );
   					} else {
   						include( modification($ro_tpl_ro_js) );
   					}	
   					?>

   				<?php } // the part for related options ?>	

   				<?php if ( !empty($ro_data) || !empty($ro_settings['show_clear_options']) ) {	?>
   					<script type="text/javascript"><!--

   						(function(){	
   							var ro_params = {};
   							ro_params['ro_settings'] = <?php echo json_encode($ro_settings); ?>;
   							ro_params['ro_data'] = <?php echo json_encode($ro_data); ?>;
   							ro_params['ro_theme_name'] = '<?php echo $ro_theme_name; ?>';
   							<?php if ( isset($ros_to_select) && $ros_to_select ) { ?>
   								ro_params['ros_to_select'] = <?php echo json_encode($ros_to_select); ?>;
   							<?php } elseif (isset($_GET['filter_name'])) { ?>
   								ro_params['filter_name'] = '<?php echo $_GET['filter_name']; ?>';
   							<?php } elseif (isset($_GET['search'])) { ?>
   								ro_params['filter_name'] = '<?php echo $_GET['search']; ?>';
   							<?php } ?>
   							<?php if ( isset($poip_ov) ) { ?>
   								ro_params['poip_ov'] = '<?php echo $poip_ov; ?>';
   							<?php } ?>

   							var $container_of_options = $('body');
   							<?php if ( $ro_theme_name == 'themeXXX' || $ro_theme_name == 'theme725' ) { ?>
   								if ( $('.ajax-quickview').length ) {
   									var $container_of_options = $('.ajax-quickview');
   								}
   							<?php } elseif ( $ro_theme_name == 'revolution') { ?>
							if ( $('#purchase-form').length ) { // quickorder
								var $container_of_options = $('#purchase-form');
							} else if ( $('#popup-view-wrapper').length ) { // quickview	
								var $container_of_options = $('#popup-view-wrapper');
							}	else if ( $('.product-info').length ) { // product page
								var $container_of_options = $('.product-info');
							}
						<?php } elseif ( substr($ro_theme_name, 0, 6) == 'brezza') { ?>
              if ( typeof(ro_is_quickview) ) { // quickview
              	var $container_of_options = $('#modal-quickview');
              } else {
              	var $container_of_options = $('#content');
              }
          <?php } ?>

          var ro_instance = $container_of_options.liveopencart_RelatedOptions(ro_params);

          ro_instance.common_fn = ro_getCommonFunctions(ro_instance);
          ro_instance.common_fn.initBasic();

          <?php if ( !empty($ro_data) ) { // the part when the product has related options ?>

          	var spec_fn = ro_getSpecificFunctions(ro_instance);

							// to custom
							ro_instance.use_block_options = ($('a[id^=block-option][option-value]').length || $('a[id^=block-image-option][option-value]').length || $('a[id^=color-][optval]').length);
							
							ro_instance.bind('setOptionValue_after.ro', spec_fn.event_setOptionValue_after);
							ro_instance.bind('init_after.ro', spec_fn.event_init_after);
							ro_instance.bind('setAccessibleOptionValues_select_after.ro', spec_fn.event_setAccessibleOptionValues_select_after);
							ro_instance.bind('setAccessibleOptionValues_radioUncheck_after.ro', spec_fn.event_setAccessibleOptionValues_radioUncheck_after);
							ro_instance.bind('setAccessibleOptionValues_radioToggle_after.ro', spec_fn.event_setAccessibleOptionValues_radioToggle_after);
							ro_instance.bind('setAccessibleOptionValues_radioEnableDisable_after.ro', spec_fn.event_setAccessibleOptionValues_radioEnableDisable_after);
							ro_instance.bind('setSelectedCombination_withAccessControl_after.ro', spec_fn.event_setSelectedCombination_withAccessControl_after);
							ro_instance.bind('controlAccessToValuesOfAllOptions_after.ro', spec_fn.event_controlAccessToValuesOfAllOptions_after);
							
							ro_instance.custom_getQuantityInput = spec_fn.custom_getQuantityInput;
							ro_instance.custom_radioToggle = spec_fn.custom_radioToggle;
							ro_instance.custom_radioChangeClass = spec_fn.custom_radioChangeClass;
							ro_instance.custom_radioEnableDisable = spec_fn.custom_radioEnableDisable;
							
							ro_instance.sstore_setOptionsStyles = spec_fn.sstore_setOptionsStyles;
							
							ro_instance.spec_fn = spec_fn;
							
						<?php } ?>
						
						ro_instance.initRO();

					})();
					
					//--></script>
				<?php } ?>
			<?php } ?>

			<!-- >> Related Options / Связанные опции  -->
      <!-- completecombo -->
      <?php if (isset($salescombopgeoffers)) { echo $offerpopup ; } ?>
      <!-- completecombo -->
			<?= $footer; ?>
<script>
	$(".newsletter-module button").empty();
	$(".newsletter-module button").append('SIGN UP');
</script>