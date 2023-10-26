<div class="product-gutter" id="product-<?=$product_id?>"> <?php /* product option in product component :: add product id to div  */ ?>
	<div class="product-block <?= $out_of_stock; ?>">
		<div class="product-image-block relative">
			<?php if($sticker && $sticker['name']){ ?>
			<span
			title="<?= $name; ?>" 
			class="sticker absolute <?= $sticker['image'] ? 'sticker-image':''; ?>" 
			style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
				<?php if($sticker['image']){ ?>
				    <img src="<?= $sticker['image'] ?>" />
				<?php } else { 
				    echo $sticker['name']; 
				} ?>
			</span>
			<?php } ?>
			<?php if($show_special_sticker && !$out_of_stock){ ?>
			<span 
			title="<?= $name; ?>" 
			class="sticker absolute" 
			style="top:<?= $sticker ? '30px' : '10px' ?>; color: #fff; background-color: #D41700;">
				<?= $text_sale; ?>
			</span>
			<?php } ?>
			<a 
				<?//php if(!$add_imgs): ?>
					href="<?= $href; ?>" 
				<?//php endif ?>
				title="<?= $name; ?>" 
				class="product-image image-container relative" >
				<div class="pi<?=$product_id; ?> pi-con indict">

					<div class="og-img" style="position: relative">
						<!-- <a href="" style="display: block"></a> -->

						<img href="<?= $href; ?>" 
							src="<?= $thumb; ?>" 
							alt="<?= $name; ?>" 
							title="<?= $name; ?>"
							class="img-responsive img1"/>
						
					</div>

					<?php if($add_imgs): ?>
						<?php foreach ($add_imgs as $imgs):?>
							<div style="position: relative" class="adds">
								<a href="<?= $href; ?>" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0"></a>
								<img src="<?php echo $imgs['thumb']; ?>" alt="additional imgs">
							</div>
						<?php endforeach ?>
					<?php endif ?>						
				</div>
				<?php if($add_imgs): ?>
					<div style="display: none">
						<script type="text/javascript">
							function initSlick<?=$product_id;?>() {
								$('.pi-con.pi<?=$product_id; ?>').slick({
								dots: false,
								infinite: true,
								speed: 500,
								arrows: true,
								pauseOnHover: false,
								autoplay: false,
								slidesToShow: 1,
									prevArrow: "<div class='pointer slick-nav left prev absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-left'></i></div></div>",
									nextArrow: "<div class='pointer slick-nav right next absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-right'></i></div></div>",

								});
							}
							initSlick<?=$product_id; ?>();
						</script>
					</div>
				<?php endif ?>	
					
				<?php if($thumb2 && $hover_image_change) { ?>
					<img 
						src="<?= $thumb2; ?>" 
						alt="<?= $name; ?>" 
						title="<?= $name; ?>"
						class="img-responsive img2" style="display: none"/>
				<?php } ?>

				<?php /*if($more_options){ ?>
				<div class="more-options-text absolute position-bottom-center">
					<?= $more_options; ?>
				</div>
				<?php }*/ ?>

			</a>
		
			<button type="button" onclick="wishlist.add('<?= $product_id; ?>');" class="btn wishlist-btn btn- product_wishlist_<?= $product_id; ?>">
				<i class="fa <?= in_array($product_id, $wishlist) ?'fa-heart':'fa-heart-o';?>"></i>
			</button>
			<div class="btn-group product-button pcat pcat<?= $product_id; ?> hidden">
				<div class="left"><i class="fa fa-angle-left"></i></div>
				<div class="right"><i class="fa fa-angle-right"></i></div>
				<!-- <?php if ($options) { ?>
					<button type="button"
						<?php if($enquiry){ ?>
							class="btn btn-default btn-enquiry btn-enquiry-<?= $product_id; ?>" data-product-id="<?= $product_id; ?>"
						<?php }else{ ?>
							class="btn btn-default btn-cart btn-cart-<?= $product_id; ?>" data-product-id="<?= $product_id; ?>"
						<?php } ?>
						>
						<i class="fa fa-shopping-cart"></i>
					</button>
				<?php } else { ?>
					<button type="button"
						<?php if($enquiry){ ?>
							onclick="enquiry.add('<?= $product_id; ?>', '<?= $minimum; ?>');"
						<?php }else{ ?>
							onclick="cart.add('<?= $product_id; ?>', '<?= $minimum; ?>');"
						<?php } ?>
						class="btn btn-default">
						<i class="fa fa-shopping-cart"></i>
					</button>
				<?php } ?>
				<button type="button" onclick="compare.add('<?= $product_id; ?>');" class="btn btn-default hide">
					<i class="fa fa-exchange"></i>
				</button> -->
			</div>
			<?php if($add_imgs): ?>
				<script>
					$(".pcat<?= $product_id; ?>").removeClass('hidden');
					$(".pcat<?= $product_id; ?> .left").click(function(){
						$(".pi<?=$product_id; ?> .slick-nav.left").click();
					})
					$(".pcat<?= $product_id; ?> .right").click(function(){
						$(".pi<?=$product_id; ?> .slick-nav.right").click();
					})
				</script>
			<?php endif?>
		</div>
		<div class="product-name">
			<h4 class="brand"><?php echo $brand; ?></h4>
			<a href="<?= $href; ?>"><?= $name; ?></a>
		</div>

		<div class="product-details product-price-<?=$product_id?>">
			<?php if ($price && !$enquiry) { ?>
				<div class="price">
					<?php if (!$special) { ?>
						<span class="price-new price-og"><?= $price; ?></span>
					<?php } else { ?>
						<span class="price-new"><?= $special; ?></span>
						<span class="price-old"><?= $price; ?></span>
					<?php } ?>
					<?php if ($tax) { ?>
						<span class="price-tax"><?= $text_tax; ?> <?= $tax; ?></span>
					<?php } ?>
				</div>
			<?php } ?>
			<?php if($review_status) { ?>
			<div class="rating">
				<?php for ($i = 1; $i <= 5; $i++) { ?>
					<?php if ($rating < $i) { ?>
					<span class="fa fa-stack"><i class="fa fa-star fa-gr fa-stack-2x"></i></span>
					<?php } else { ?>
					<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
					<?php } ?>
				<?php } ?>
				<span class="review-count">&nbsp;&nbsp;(<?php echo $review_count; ?> reviews)</span>
			</div>
		<?php } ?>
		</div>
		<?php /* product option in product component */ ?>
			<div class="product-inputs">
		    <?php if ($options && count($options) == 1) { ?>
				<div class="product-option">
				    <?php foreach ($options as $option) { ?>
					    <?php if ($option['type'] == 'select') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <select name="option[<?= $option['product_option_id']; ?>]" id="input-option<?= $option['product_option_id']; ?>" class="form-control" data-product-id="<?= $product_id; ?>">
					        <option value=""><?= $text_select; ?></option>
					        <?php foreach ($option['product_option_value'] as $option_value) { ?>
					        <option value="<?= $option_value['product_option_value_id']; ?>"><?= $option_value['name']; ?>
					        <?php if ($option_value['price']) { ?>
					        (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
					        <?php } ?>
					        </option>
					        <?php } ?>
					      </select>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'radio') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label"><?= $option['name']; ?></label>
					      <div id="input-option<?= $option['product_option_id']; ?>">
					        <?php foreach ($option['product_option_value'] as $option_value) { ?>
					        <div class="radio">
					          <label>
					            <input type="radio" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option_value['product_option_value_id']; ?>" data-product-id="<?= $product_id; ?>" />
					            <?php if ($option_value['image']) { ?>
					            <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
					            <?php } ?>                    
					            <?= $option_value['name']; ?>
					            <?php if ($option_value['price']) { ?>
					            (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
					            <?php } ?>
					          </label>
					        </div>
					        <?php } ?>
					      </div>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'checkbox') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label"><?= $option['name']; ?></label>
					      <div id="input-option<?= $option['product_option_id']; ?>">
					        <?php foreach ($option['product_option_value'] as $option_value) { ?>
					        <div class="checkbox">
					          <label>
					            <input type="checkbox" name="option[<?= $option['product_option_id']; ?>][]" value="<?= $option_value['product_option_value_id']; ?>" data-product-id="<?= $product_id; ?>" />
					            <?php if ($option_value['image']) { ?>
					            <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
					            <?php } ?>
					            <?= $option_value['name']; ?>
					            <?php if ($option_value['price']) { ?>
					            (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
					            <?php } ?>
					          </label>
					        </div>
					        <?php } ?>
					      </div>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'text') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'textarea') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <textarea name="option[<?= $option['product_option_id']; ?>]" rows="5" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control"><?= $option['value']; ?></textarea>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'file') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label"><?= $option['name']; ?></label>
					      <button type="button" id="button-upload<?= $option['product_option_id']; ?>" data-loading-text="<?= $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?= $button_upload; ?></button>
					      <input type="hidden" name="option[<?= $option['product_option_id']; ?>]" value="" id="input-option<?= $option['product_option_id']; ?>" />
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'date') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <div class="input-group date">
					        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
					        <span class="input-group-btn">
					        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
					        </span></div>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'datetime') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <div class="input-group datetime">
					        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
					        <span class="input-group-btn">
					        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					        </span></div>
					    </div>
					    <?php } ?>
					    <?php if ($option['type'] == 'time') { ?>
					    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
					      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
					      <div class="input-group time">
					        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
					        <span class="input-group-btn">
					        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					        </span></div>
					    </div>
					    <?php } ?>
				    <?php } ?>
				</div>
		    <?php } ?>
		  </div>

		<div class="qty-cart-btn">
	    	<div class="form-group hidden">
	          	<label class="control-label hide"><?= $entry_qty; ?></label>
		        <div class="input-group">
		          <span class="input-group-btn"> 
		            <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="qty-<?= $product_id; ?>" data-product-id="<?= $product_id; ?>" onclick="descrement($(this).parent().parent())")>
		              	<span class="glyphicon glyphicon-minus"></span> 
		            </button>
		          </span>
		          <input type="text" name="quantity" class="form-control input-number integer text-center" id="input-quantity-<?= $product_id; ?>" value="<?= $minimum; ?>" data-product-id="<?= $product_id; ?>" >
		          <span class="input-group-btn">
		            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="qty-<?= $product_id; ?>" data-product-id="<?= $product_id; ?>" onclick="increment($(this).parent().parent())">
		              	<span class="glyphicon glyphicon-plus"></span>
		            </button>
		          </span>
		        </div>
	        </div>

			<div class="cart-buttons">
			<input type="hidden" name="product_id" value="<?=$product_id?>">
			<?php if(!$enquiry){ ?>
				<?php if(!$not_avail) { ?>
					<button type="button" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary btn-cart btn-cart-<?= $product_id; ?>" data-product-id="<?= $product_id; ?>">ADD TO CART</button>
				<?php }else{ ?>
					<button style="cursor: not-allowed;"class="btn btn-primary btn-cart "><?= $button_cart; ?></button>
				<?php } ?>
			<?php }else{ ?>
				<button style="cursor: not-allowed;"class="btn btn-primary btn-cart "><?= $button_cart; ?></button>
			<?php } ?>
			</div>
		</div>  
		<?php /* product option in product component */ ?>
	</div>
</div>




