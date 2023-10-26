<?= $header; ?><?= $column_left; ?>
<div id="content">
	<!-- << OPTIONS IMAGE -->
    <style>
        .product-options-images-container{
            display: inline-block;
            vertical-align: top;
            padding-right: 20px;
			padding-bottom: 10px;
			width: calc(100% - 4px);
        }
		@media (min-width: 768px) {
			.product-options-images-container{
				width: calc(50% - 4px);
			}
		}
		@media (min-width: 1025px) {
			.product-options-images-container{
				width: calc(33.3333% - 4px);
			}
		}
		@media (min-width: 1300px) {
			.product-options-images-container{
				width: calc(25% - 4px);
			}
		}
        .product-options-images-container .checkbox {
            width: auto;
            opacity: 1;
            position: relative;
            min-height: 20px;
            padding-top: 1px;
        }
        .product-options-images-container b{
            margin-bottom: 5px;
            display: inline-block;
        }
		.highlight-select {
			border: 2px solid #086b9e;
		}
		.highlight-text {
			color: #086b9e;
		}
    </style>
    <!-- >> OPTIONS IMAGE -->
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary btn-submit" onclick="$('#form-product').submit();"><i class="fa fa-save"></i></button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
				

			</div>
			<h1><?= $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_form; ?></h3>
				<?php /* mod for next/previous page */ ?> 
				<?php if ($prev_product || $next_product) { ?>
					<div class="pull-right next-prev-buttons">
						<?php if ($prev_product) { ?>
								<a href="<?= $prev_product; ?>" data-toggle="tooltip" title="<?= $button_prev; ?>" class="btn btn-xs btn-primary"><i class="fa fa-arrow-left"></i> <?=$text_prev?></a>
						<?php } ?>
						<?php if ($next_product) { ?>
								<a href="<?= $next_product; ?>" data-toggle="tooltip" title="<?= $button_next; ?>" class="btn btn-xs btn-info"><?=$text_next?> <i class="fa fa-arrow-right"></i></a>
						<?php } ?>
					</div>
				<?php } ?>
				<?php /* mod for next/previous page */ ?> 
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab"><?= $tab_product_1; ?></a></li>
						<li><a href="#tab-data" data-toggle="tab"><?= $tab_product_2; ?></a></li>						
						<li><a href="#tab-links" data-toggle="tab"><?= $tab_product_3; ?></a></li>
						<li><a href="#tab-attribute" data-toggle="tab"><?= $tab_attribute; ?></a></li>
						<li><a href="#tab-option" data-toggle="tab"><?= $tab_option; ?></a></li>
						<li class='hidden'><a href="#tab-recurring" data-toggle="tab"><?= $tab_recurring; ?></a></li>
						<li><a href="#tab-discount" data-toggle="tab"><?= $tab_discount; ?></a></li>
						<li><a href="#tab-special" data-toggle="tab"><?= $tab_special; ?></a></li>
						<li><a href="#tab-image" data-toggle="tab"><?= $tab_image; ?></a></li>
						<li class='hidden'><a href="#tab-reward" data-toggle="tab"><?= $tab_reward; ?></a></li>
						<li><a href="#tab-design" data-toggle="tab" class='<?=$is_dev?>'><?= $tab_design; ?></a></li>
				        <!-- << Related Options / Связанные опции  -->
								<?php if ($ro_installed) { ?>
								<li><a href="#tab-related_options" data-toggle="tab"><?php echo $related_options_title; ?></a></li>
								<?php } ?>
				        <!-- >> Related Options / Связанные опции  -->

					</ul>
					<div class="tab-content">
						
						<div class="tab-pane active" id="tab-general">
							<ul class="nav nav-tabs" id="language">
								<?php foreach ($languages as $language) { ?>
									<li><a href="#language<?= $language['language_id']; ?>" data-toggle="tab"><img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" /> <?= $language['name']; ?></a></li>
								<?php } ?>
							</ul>
							<div class="tab-content">
								<?php foreach ($languages as $language) { ?>
									<div class="tab-pane" id="language<?= $language['language_id']; ?>">
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-name<?= $language['language_id']; ?>"><?= $entry_name; ?></label>
											<div class="col-sm-10">
												<input type="text" name="product_description[<?= $language['language_id']; ?>][name]" value="<?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?= $entry_name; ?>" id="input-name<?= $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_name[$language['language_id']])) { ?>
													<div class="text-danger"><?= $error_name[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<?php /* completecombo */ ?>
										<div class="form-group hide">
											<label class="col-sm-2 control-label" for="input-offertag<?php echo $language['language_id']; ?>">Offer Tag</label>
								        	<div class="col-sm-10">
								          		<input type="text" name="product_description[<?php echo $language['language_id']; ?>][offertag]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['offertag'] : ''; ?>" placeholder="Enter Offer Tag" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
								       	 	</div>
								      	</div>
										<?php /* completecombo */ ?>
								      	<div class="form-group">
											<label class="col-sm-2 control-label" for="input-description<?= $language['language_id']; ?>"><?= $entry_description; ?></label>
											<div class="col-sm-10">
												<textarea name="product_description[<?= $language['language_id']; ?>][description]" placeholder="<?= $entry_description; ?>" id="input-description<?= $language['language_id']; ?>" class="<?php if ($ckeditor_enabled == 1) { ?>form-control<?php } else { ?>form-control summernote<?php } ?>"><?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group required">
											<label class="col-sm-2 control-label" for="input-meta-title<?= $language['language_id']; ?>"><?= $entry_meta_title; ?></label>
											<div class="col-sm-10">
												<input type="text" name="product_description[<?= $language['language_id']; ?>][meta_title]" value="<?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?= $entry_meta_title; ?>" id="input-meta-title<?= $language['language_id']; ?>" class="form-control" />
												<?php if (isset($error_meta_title[$language['language_id']])) { ?>
													<div class="text-danger"><?= $error_meta_title[$language['language_id']]; ?></div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-description<?= $language['language_id']; ?>"><?= $entry_meta_description; ?></label>
											<div class="col-sm-10">
												<textarea name="product_description[<?= $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?= $entry_meta_description; ?>" id="input-meta-description<?= $language['language_id']; ?>" class="form-control"><?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-meta-keyword<?= $language['language_id']; ?>"><?= $entry_meta_keyword; ?></label>
											<div class="col-sm-10">
												<textarea name="product_description[<?= $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?= $entry_meta_keyword; ?>" id="input-meta-keyword<?= $language['language_id']; ?>" class="form-control"><?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label" for="input-tag<?= $language['language_id']; ?>"><span data-toggle="tooltip" title="<?= $help_tag; ?>"><?= $entry_tag; ?></span></label>
											<div class="col-sm-10">
												<input type="text" name="product_description[<?= $language['language_id']; ?>][tag]" value="<?= isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['tag'] : ''; ?>" placeholder="<?= $entry_tag; ?>" id="input-tag<?= $language['language_id']; ?>" class="form-control" />
											</div>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="tab-pane" id="tab-data">
							<div class="form-group required">
								<label class="col-sm-2 control-label" for="input-model"><?= $entry_model; ?></label>
								<div class="col-sm-10">
									<input type="text" name="model" value="<?= $model; ?>" placeholder="<?= $entry_model; ?>" id="input-model" class="form-control" />
									<?php if ($error_model) { ?>
										<div class="text-danger"><?= $error_model; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?= $help_sku; ?>"><?= $entry_sku; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="sku" value="<?= $sku; ?>" placeholder="<?= $entry_sku; ?>" id="input-sku" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-upc"><span data-toggle="tooltip" title="<?= $help_upc; ?>"><?= $entry_upc; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="upc" value="<?= $upc; ?>" placeholder="<?= $entry_upc; ?>" id="input-upc" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-ean"><span data-toggle="tooltip" title="<?= $help_ean; ?>"><?= $entry_ean; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="ean" value="<?= $ean; ?>" placeholder="<?= $entry_ean; ?>" id="input-ean" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-jan"><span data-toggle="tooltip" title="<?= $help_jan; ?>"><?= $entry_jan; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="jan" value="<?= $jan; ?>" placeholder="<?= $entry_jan; ?>" id="input-jan" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-isbn"><span data-toggle="tooltip" title="<?= $help_isbn; ?>"><?= $entry_isbn; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="isbn" value="<?= $isbn; ?>" placeholder="<?= $entry_isbn; ?>" id="input-isbn" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-mpn"><span data-toggle="tooltip" title="<?= $help_mpn; ?>"><?= $entry_mpn; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="mpn" value="<?= $mpn; ?>" placeholder="<?= $entry_mpn; ?>" id="input-mpn" class="form-control" />
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-location"><?= $entry_location; ?></label>
								<div class="col-sm-10">
									<input type="text" name="location" value="<?= $location; ?>" placeholder="<?= $entry_location; ?>" id="input-location" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-price">
									<span data-toggle="tooltip" title="Enter 0 to make product an enquiry item" ><?= $entry_price; ?></span>
								</label>
								<div class="col-sm-10">
									<input type="text" name="price" value="<?= $price; ?>" placeholder="<?= $entry_price; ?>" id="input-price" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-volume">Volume</label>
								<div class="col-sm-10">
									<input type="text" name="volume" value="<?= $volume; ?>" placeholder="Volume" id="input-volume" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-tax-class"><?= $entry_tax_class; ?></label>
								<div class="col-sm-10">
									<select name="tax_class_id" id="input-tax-class" class="form-control">
										<option value="0"><?= $text_none; ?></option>
										<?php foreach ($tax_classes as $tax_class) { ?>
											<?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
												<option value="<?= $tax_class['tax_class_id']; ?>" selected="selected"><?= $tax_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?= $tax_class['tax_class_id']; ?>"><?= $tax_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-quantity"><?= $entry_quantity; ?></label>
								<div class="col-sm-10">
									<input type="text" name="quantity" value="<?= $quantity; ?>" placeholder="<?= $entry_quantity; ?>" id="input-quantity" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-minimum"><span data-toggle="tooltip" title="<?= $help_minimum; ?>"><?= $entry_minimum; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="minimum" value="<?= $minimum; ?>" placeholder="<?= $entry_minimum; ?>" id="input-minimum" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-subtract"><?= $entry_subtract; ?></label>
								<div class="col-sm-10">
									<select name="subtract" id="input-subtract" class="form-control">
										<?php if ($subtract) { ?>
											<option value="1" selected="selected"><?= $text_yes; ?></option>
											<option value="0"><?= $text_no; ?></option>
											<?php } else { ?>
											<option value="1"><?= $text_yes; ?></option>
											<option value="0" selected="selected"><?= $text_no; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?= $help_stock_status; ?>"><?= $entry_stock_status; ?></span></label>
								<div class="col-sm-10">
									<select name="stock_status_id" id="input-stock-status" class="form-control">
										<?php foreach ($stock_statuses as $stock_status) { ?>
											<?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
												<option value="<?= $stock_status['stock_status_id']; ?>" selected="selected"><?= $stock_status['name']; ?></option>
												<?php } else { ?>
												<option value="<?= $stock_status['stock_status_id']; ?>"><?= $stock_status['name']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><?= $entry_shipping; ?></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($shipping) { ?>
											<input type="radio" name="shipping" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="shipping" value="1" />
											<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$shipping) { ?>
											<input type="radio" name="shipping" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="shipping" value="0" />
											<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?= $help_keyword; ?>"><?= $entry_keyword; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="keyword" value="<?= $keyword; ?>" placeholder="<?= $entry_keyword; ?>" id="input-keyword" class="form-control" />
									<?php if ($error_keyword) { ?>
										<div class="text-danger"><?= $error_keyword; ?></div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-available"><?= $entry_date_available; ?></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<input type="text" name="date_available" value="<?= $date_available; ?>" placeholder="<?= $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
										<span class="input-group-btn">
											<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
										</span></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-length-class"><?= $entry_length_class; ?></label>
								<div class="col-sm-10">
									<select name="length_class_id" id="input-length-class" class="form-control">
										<?php foreach ($length_classes as $length_class) { ?>
											<?php if ($length_class['length_class_id'] == $length_class_id) { ?>
												<option value="<?= $length_class['length_class_id']; ?>" selected="selected"><?= $length_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?= $length_class['length_class_id']; ?>"><?= $length_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-length"><?= $entry_dimension; ?></label>
								<div class="col-sm-10">
									<div class="row">
										<div class="col-sm-4">
											<input type="text" name="length" value="<?= $length; ?>" placeholder="<?= $entry_length; ?>" id="input-length" class="form-control" />
										</div>
										<div class="col-sm-4">
											<input type="text" name="width" value="<?= $width; ?>" placeholder="<?= $entry_width; ?>" id="input-width" class="form-control" />
										</div>
										<div class="col-sm-4">
											<input type="text" name="height" value="<?= $height; ?>" placeholder="<?= $entry_height; ?>" id="input-height" class="form-control" />
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-weight-class"><?= $entry_weight_class; ?></label>
								<div class="col-sm-10">
									<select name="weight_class_id" id="input-weight-class" class="form-control">
										<?php foreach ($weight_classes as $weight_class) { ?>
											<?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
												<option value="<?= $weight_class['weight_class_id']; ?>" selected="selected"><?= $weight_class['title']; ?></option>
												<?php } else { ?>
												<option value="<?= $weight_class['weight_class_id']; ?>"><?= $weight_class['title']; ?></option>
											<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-weight"><?= $entry_weight; ?></label>
								<div class="col-sm-10">
									<input type="text" name="weight" value="<?= $weight; ?>" placeholder="<?= $entry_weight; ?>" id="input-weight" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status"><?= $entry_status; ?></label>
								<div class="col-sm-10">
									<select name="status" id="input-status" class="form-control">
										<?php if ($status) { ?>
											<option value="1" selected="selected"><?= $text_enabled; ?></option>
											<option value="0"><?= $text_disabled; ?></option>
											<?php } else { ?>
											<option value="1"><?= $text_enabled; ?></option>
											<option value="0" selected="selected"><?= $text_disabled; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-sort-order"><?= $entry_sort_order; ?></label>
								<div class="col-sm-10">
									<input type="text" name="sort_order" value="<?= $sort_order; ?>" placeholder="<?= $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-links">
							
								<div class="form-group">
									<label class="col-sm-2 control-label" for="input-filename"><?= $entry_product_catalog; ?></label>
									<div class="col-sm-10">
										<div class="input-group">
											<input type="text" name="mask" value="<?php echo $mask; ?>" placeholder="" id="input-filename" class="form-control" />
											<span class="input-group-btn">
												<button type="button" id="button-upload" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><i class="fa fa-upload"></i> <?= $button_upload; ?></button>
											</span> 
											<input type="hidden" name="filename" value="<?= $filename; ?>" />
											<input type="hidden" name="product_pdf_id" value="<?= $product_pdf_id; ?>" />
										</div>
									</div>
								</div>

							
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?= $help_manufacturer; ?>"><?= $entry_manufacturer; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="manufacturer" value="<?= $manufacturer; ?>" placeholder="<?= $entry_manufacturer; ?>" id="input-manufacturer" class="form-control" />
									<input type="hidden" name="manufacturer_id" value="<?= $manufacturer_id; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?= $help_category; ?>"><?= $entry_category; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="category" value="" placeholder="<?= $entry_category; ?>" id="input-category" class="form-control" />
									<div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_categories as $product_category) { ?>
											<div id="product-category<?= $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?= $product_category['name']; ?>
												<input type="hidden" name="product_category[]" value="<?= $product_category['category_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-filter"><span data-toggle="tooltip" title="<?= $help_filter; ?>"><?= $entry_filter; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="filter" value="" placeholder="<?= $entry_filter; ?>" id="input-filter" class="form-control" />
									<div id="product-filter" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_filters as $product_filter) { ?>
											<div id="product-filter<?= $product_filter['filter_id']; ?>"><i class="fa fa-minus-circle"></i> <?= $product_filter['name']; ?>
												<input type="hidden" name="product_filter[]" value="<?= $product_filter['filter_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label"><?= $entry_store; ?></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<div class="checkbox">
											<label>
												<?php if (in_array(0, $product_store)) { ?>
													<input type="checkbox" name="product_store[]" value="0" checked="checked" />
													<?= $text_default; ?>
													<?php } else { ?>
													<input type="checkbox" name="product_store[]" value="0" />
													<?= $text_default; ?>
												<?php } ?>
											</label>
										</div>
										<?php foreach ($stores as $store) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($store['store_id'], $product_store)) { ?>
														<input type="checkbox" name="product_store[]" value="<?= $store['store_id']; ?>" checked="checked" />
														<?= $store['name']; ?>
														<?php } else { ?>
														<input type="checkbox" name="product_store[]" value="<?= $store['store_id']; ?>" />
														<?= $store['name']; ?>
													<?php } ?>
												</label>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group hidden">
								<label class="col-sm-2 control-label" for="input-download"><span data-toggle="tooltip" title="<?= $help_download; ?>"><?= $entry_download; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="download" value="" placeholder="<?= $entry_download; ?>" id="input-download" class="form-control" />
									<div id="product-download" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_downloads as $product_download) { ?>
											<div id="product-download<?= $product_download['download_id']; ?>"><i class="fa fa-minus-circle"></i> <?= $product_download['name']; ?>
												<input type="hidden" name="product_download[]" value="<?= $product_download['download_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?= $help_related; ?>"><?= $entry_related; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="related" value="" placeholder="<?= $entry_related; ?>" id="input-related" class="form-control" />
									<div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($product_relateds as $product_related) { ?>
											<div id="product-related<?= $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?= $product_related['name']; ?>
												<input type="hidden" name="product_related[]" value="<?= $product_related['product_id']; ?>" />
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-attribute">
							<div class="table-responsive">
									<table id="attribute" class="table table-bordered">
											<thead>
												<tr>
													<td class="text-left"><?php echo $entry_attribute; ?></td>
													<td width="1px" ></td>
												</tr>
											</thead>
											<tbody>
												<?php $attribute_row = 0; ?>
												<?php foreach ($product_attributes as $product_attribute) { ?>
													<tr id="attribute-row<?php echo $attribute_row; ?>">
														<td class="text-left">
															<p><?php echo $entry_attribute; ?></p>
															<input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control"/>
															<input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>"/>
															<p>Text</p>
															<div class="input-group col-xs-12">
																<ul class="nav nav-tabs" id="language<?php echo $attribute_row; ?>">
																	<?php foreach ($languages as $language) { ?>
																		<li class="<?php echo ($language['language_id']==1)?'active':''; ?>">
																			<a href="#language-text-<?php echo $attribute_row; ?><?php echo $language['language_id']; ?>" data-toggle="tab">
																				<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>"/> 
																				<?php echo $language['name']; ?>
																			</a>
																		</li>
																	<?php } ?>
																</ul>
																<div class="tab-content">
																	<?php foreach ($languages as $language) { ?>
																		<div class="tab-pane <?php echo ($language['language_id']==1)?'in active':''; ?>" id="language-text-<?php echo $attribute_row; ?><?php echo $language['language_id']; ?>">
																			<textarea id="input-attribute<?php echo $attribute_row.$language['language_id']; ?>" name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
																		</div>
																	<?php } ?>
																</div>
															</td>
															<td class="text-left">
																<button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger">
																	<i class="fa fa-minus-circle"></i>
																</button>
															</td>
														</tr>
														<?php $attribute_row++; ?>
													<?php } ?>
												</tbody>
												<tfoot>
													<tr>
														<td colspan="1"></td>
														<td class="text-left">
															<button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary">
																<i class="fa fa-plus-circle"></i>
															</button>
														</td>
													</tr>
												</tfoot>
											</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-option">
							<div class="row">
								<div class="col-sm-2">
									<ul class="nav nav-pills nav-stacked" id="option">
										<?php $option_row = 0; ?>
										<?php foreach ($product_options as $product_option) { ?>
											<li><a href="#tab-option<?= $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?= $option_row; ?>\']').parent().remove(); $('#tab-option<?= $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?= $product_option['name']; ?></a></li>
											<?php $option_row++; ?>
										<?php } ?>
										<li>
											<input type="text" name="option" value="" placeholder="<?= $entry_option; ?>" id="input-option" class="form-control" />
										</li>
									</ul>
								</div>
								<div class="col-sm-10">
									<div class="tab-content">
										<?php $option_row = 0; ?>
										<?php $option_value_row = 0; ?>
										<?php foreach ($product_options as $product_option) { ?>
											<div class="tab-pane" id="tab-option<?= $option_row; ?>">
												<input type="hidden" name="product_option[<?= $option_row; ?>][product_option_id]" value="<?= $product_option['product_option_id']; ?>" />
												<input type="hidden" name="product_option[<?= $option_row; ?>][name]" value="<?= $product_option['name']; ?>" />
												<input type="hidden" name="product_option[<?= $option_row; ?>][option_id]" value="<?= $product_option['option_id']; ?>" />
												<input type="hidden" name="product_option[<?= $option_row; ?>][type]" value="<?= $product_option['type']; ?>" />
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-required<?= $option_row; ?>"><?= $entry_required; ?></label>
													<div class="col-sm-10">
														<select name="product_option[<?= $option_row; ?>][required]" id="input-required<?= $option_row; ?>" class="form-control">
															<?php if ($product_option['required']) { ?>
																<option value="1" selected="selected"><?= $text_yes; ?></option>
																<option value="0"><?= $text_no; ?></option>
																<?php } else { ?>
																<option value="1"><?= $text_yes; ?></option>
																<option value="0" selected="selected"><?= $text_no; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<?php if ($product_option['type'] == 'text') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_option[<?= $option_row; ?>][value]" value="<?= $product_option['value']; ?>" placeholder="<?= $entry_option_value; ?>" id="input-value<?= $option_row; ?>" class="form-control" />
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'textarea') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-10">
															<textarea name="product_option[<?= $option_row; ?>][value]" rows="5" placeholder="<?= $entry_option_value; ?>" id="input-value<?= $option_row; ?>" class="form-control"><?= $product_option['value']; ?></textarea>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'file') { ?>
													<div class="form-group" style="display: none;">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-10">
															<input type="text" name="product_option[<?= $option_row; ?>][value]" value="<?= $product_option['value']; ?>" placeholder="<?= $entry_option_value; ?>" id="input-value<?= $option_row; ?>" class="form-control" />
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'date') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-3">
															<div class="input-group date">
																<input type="text" name="product_option[<?= $option_row; ?>][value]" value="<?= $product_option['value']; ?>" placeholder="<?= $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value<?= $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'time') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-10">
															<div class="input-group time">
																<input type="text" name="product_option[<?= $option_row; ?>][value]" value="<?= $product_option['value']; ?>" placeholder="<?= $entry_option_value; ?>" data-date-format="HH:mm" id="input-value<?= $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'datetime') { ?>
													<div class="form-group">
														<label class="col-sm-2 control-label" for="input-value<?= $option_row; ?>"><?= $entry_option_value; ?></label>
														<div class="col-sm-10">
															<div class="input-group datetime">
																<input type="text" name="product_option[<?= $option_row; ?>][value]" value="<?= $product_option['value']; ?>" placeholder="<?= $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value<?= $option_row; ?>" class="form-control" />
																<span class="input-group-btn">
																	<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
																</span></div>
														</div>
													</div>
												<?php } ?>
												<?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
													<div class="table-responsive">
														<table id="option-value<?= $option_row; ?>" class="table table-striped table-bordered table-hover">
															<thead>
																<tr>
																	<td class="text-left"><?= $entry_option_value; ?></td>
																	<td class="text-right"><?= $entry_quantity; ?></td>
																	<td class="text-left"><?= $entry_subtract; ?></td>
																	<td class="text-left"><?= $entry_sku; ?></td>
																	<td class="text-right"><?= $entry_price; ?></td>
																	<td class="text-right"><?= $entry_option_points; ?></td>
																	<td class="text-right"><?= $entry_weight; ?></td>
																	<td></td>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
																	<tr id="option-value-row<?= $option_value_row; ?>">
																		<td class="text-left"><select name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][option_value_id]" class="form-control">
																			<?php if (isset($option_values[$product_option['option_id']])) { ?>
																				<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																					<?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
																						<option value="<?= $option_value['option_value_id']; ?>" selected="selected"><?= $option_value['name']; ?></option>
																						<?php } else { ?>
																						<option value="<?= $option_value['option_value_id']; ?>"><?= $option_value['name']; ?></option>
																					<?php } ?>
																				<?php } ?>
																			<?php } ?>
																		</select>
																		<input type="hidden" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][product_option_value_id]" value="<?= $product_option_value['product_option_value_id']; ?>" /></td>
																		<td class="text-right"><input type="text" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][quantity]" value="<?= $product_option_value['quantity']; ?>" placeholder="<?= $entry_quantity; ?>" class="form-control" /></td>
																		<td class="text-left"><select name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][subtract]" class="form-control">
																			<?php if ($product_option_value['subtract']) { ?>
																				<option value="1" selected="selected"><?= $text_yes; ?></option>
																				<option value="0"><?= $text_no; ?></option>
																				<?php } else { ?>
																				<option value="1"><?= $text_yes; ?></option>
																				<option value="0" selected="selected"><?= $text_no; ?></option>
																			<?php } ?>
																		</select></td>
																		<td class="text-left">
																			
																			<input value="<?= $product_option_value['sku']; ?>" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][sku]" class="form-control">
																			
																		</td>
																		<td class="text-right"><select name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][price_prefix]" class="form-control">
																			<?php if ($product_option_value['price_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['price_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][price]" value="<?= $product_option_value['price']; ?>" placeholder="<?= $entry_price; ?>" class="form-control" /></td>
																		<td class="text-right"><select name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][points_prefix]" class="form-control">
																			<?php if ($product_option_value['points_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['points_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][points]" value="<?= $product_option_value['points']; ?>" placeholder="<?= $entry_points; ?>" class="form-control" /></td>
																		<td class="text-right"><select name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][weight_prefix]" class="form-control">
																			<?php if ($product_option_value['weight_prefix'] == '+') { ?>
																				<option value="+" selected="selected">+</option>
																				<?php } else { ?>
																				<option value="+">+</option>
																			<?php } ?>
																			<?php if ($product_option_value['weight_prefix'] == '-') { ?>
																				<option value="-" selected="selected">-</option>
																				<?php } else { ?>
																				<option value="-">-</option>
																			<?php } ?>
																		</select>
																		<input type="text" name="product_option[<?= $option_row; ?>][product_option_value][<?= $option_value_row; ?>][weight]" value="<?= $product_option_value['weight']; ?>" placeholder="<?= $entry_weight; ?>" class="form-control" /></td>
																		<td class="text-left"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?= $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
																	</tr>
																	<?php $option_value_row++; ?>
																<?php } ?>
															</tbody>
															<tfoot>
																<tr>
																	<td colspan="7"></td>
																	<td class="text-left"><button type="button" onclick="addOptionValue('<?= $option_row; ?>');" data-toggle="tooltip" title="<?= $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
																</tr>
															</tfoot>
														</table>
													</div>
													<select id="option-values<?= $option_row; ?>" style="display: none;">
														<?php if (isset($option_values[$product_option['option_id']])) { ?>
															<?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
																<option value="<?= $option_value['option_value_id']; ?>"><?= $option_value['name']; ?></option>
															<?php } ?>
														<?php } ?>
													</select>
												<?php } ?>
											</div>
											<?php $option_row++; ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-recurring">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_recurring; ?></td>
											<td class="text-left"><?= $entry_customer_group; ?></td>
											<td class="text-left"></td>
										</tr>
									</thead>
									<tbody>
										<?php $recurring_row = 0; ?>
										<?php foreach ($product_recurrings as $product_recurring) { ?>
											
											<tr id="recurring-row<?= $recurring_row; ?>">
												<td class="text-left"><select name="product_recurring[<?= $recurring_row; ?>][recurring_id]" class="form-control">
													<?php foreach ($recurrings as $recurring) { ?>
														<?php if ($recurring['recurring_id'] == $product_recurring['recurring_id']) { ?>
															<option value="<?= $recurring['recurring_id']; ?>" selected="selected"><?= $recurring['name']; ?></option>
															<?php } else { ?>
															<option value="<?= $recurring['recurring_id']; ?>"><?= $recurring['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-left"><select name="product_recurring[<?= $recurring_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_recurring['customer_group_id']) { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>" selected="selected"><?= $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>"><?= $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-left"><button type="button" onclick="$('#recurring-row<?= $recurring_row; ?>').remove()" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $recurring_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<td class="text-left"><button type="button" onclick="addRecurring()" data-toggle="tooltip" title="<?= $button_recurring_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<!-- << Related Options / Связанные опции  -->
						<?php if ($ro_installed) { ?>
							<div class="tab-pane" id="tab-related_options">
								
								<ul class="nav nav-tabs" id="ro_nav_tabs">
									<li>
										<button type="button" id='ro_add_tab_button' onclick="ro_add_tab();" data-toggle="tooltip" class="btn"><i class="fa fa-plus-circle"></i></button>
									</li>
								</ul>
					
								<div class="tab-content" id="ro_content">
								
									<input type="hidden" name="ro_data_included" value="1">
									
								</div>
								
								<span class="help-block" style="margin-top: 30px;">
									<?php echo $entry_ro_version.": ".$ro_version; ?> | <?php echo $text_ro_support; ?> | <?php echo $text_extension_page; ?>
								</span>
						
							</div>
					
						<?php } ?>

		        		<!-- >> Related Options / Связанные опции  -->
		        		
						<div class="tab-pane" id="tab-discount">
							<div class="table-responsive">
								<table id="discount" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_customer_group; ?></td>
											<td class="text-right"><?= $entry_quantity; ?></td>
											<td class="text-right"><?= $entry_priority; ?></td>
											<td class="text-right"><?= $entry_price; ?></td>
											<td class="text-left"><?= $entry_date_start; ?></td>
											<td class="text-left"><?= $entry_date_end; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $discount_row = 0; ?>
										<?php foreach ($product_discounts as $product_discount) { ?>
											<tr id="discount-row<?= $discount_row; ?>">
												<td class="text-left"><select name="product_discount[<?= $discount_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>" selected="selected"><?= $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>"><?= $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-right"><input type="text" name="product_discount[<?= $discount_row; ?>][quantity]" value="<?= $product_discount['quantity']; ?>" placeholder="<?= $entry_quantity; ?>" class="form-control" /></td>
												<td class="text-right"><input type="text" name="product_discount[<?= $discount_row; ?>][priority]" value="<?= $product_discount['priority']; ?>" placeholder="<?= $entry_priority; ?>" class="form-control" /></td>
												<td class="text-right"><input type="text" name="product_discount[<?= $discount_row; ?>][price]" value="<?= $product_discount['price']; ?>" placeholder="<?= $entry_price; ?>" class="form-control" /></td>
												<td class="text-left" style="width: 20%;"><div class="input-group date">
													<input type="text" name="product_discount[<?= $discount_row; ?>][date_start]" value="<?= $product_discount['date_start']; ?>" placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
													<span class="input-group-btn">
														<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
													</span></div></td>
													<td class="text-left" style="width: 20%;"><div class="input-group date">
														<input type="text" name="product_discount[<?= $discount_row; ?>][date_end]" value="<?= $product_discount['date_end']; ?>" placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
														</span></div></td>
														<td class="text-left"><button type="button" onclick="$('#discount-row<?= $discount_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $discount_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6"></td>
											<td class="text-left"><button type="button" onclick="addDiscount();" data-toggle="tooltip" title="<?= $button_discount_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-special">
							<div class="table-responsive">
								<table id="special" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_customer_group; ?></td>
											<td class="text-right"><?= $entry_priority; ?></td>
											<td class="text-right"><?= $entry_percentage; ?></td>
											<td class="text-right"><?= $entry_price; ?></td>
											<td class="text-left"><?= $entry_date_start; ?></td>
											<td class="text-left"><?= $entry_date_end; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $special_row = 0; ?>
										<?php foreach ($product_specials as $product_special) { ?>
											<tr id="special-row<?= $special_row; ?>">
												<td class="text-left"><select name="product_special[<?= $special_row; ?>][customer_group_id]" class="form-control">
													<?php foreach ($customer_groups as $customer_group) { ?>
														<?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>" selected="selected"><?= $customer_group['name']; ?></option>
															<?php } else { ?>
															<option value="<?= $customer_group['customer_group_id']; ?>"><?= $customer_group['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
												<td class="text-right"><input type="text" name="product_special[<?= $special_row; ?>][priority]" value="<?= $product_special['priority']; ?>" placeholder="<?= $entry_priority; ?>" class="form-control" /></td>
												<td class="text-right">
													<div class="input-group">
														<input type="text" value="" placeholder="<?= $entry_percentage; ?>" class="form-control percent-input percent-input<?= $special_row; ?>" data-calc="product_special[<?= $special_row; ?>][price]" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button">%</button>
														</span>
													</div>
												</td>
												<td class="text-right"><input type="text" name="product_special[<?= $special_row; ?>][price]" value="<?= $product_special['price']; ?>" placeholder="<?= $entry_price; ?>" class="form-control price-special" data-calc="percent-input<?= $special_row; ?>" /></td>
												<td class="text-left" style="width: 20%;"><div class="input-group date">
													<input type="text" name="product_special[<?= $special_row; ?>][date_start]" value="<?= $product_special['date_start']; ?>" placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
													<span class="input-group-btn">
														<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
													</span></div></td>
													<td class="text-left" style="width: 20%;"><div class="input-group date">
														<input type="text" name="product_special[<?= $special_row; ?>][date_end]" value="<?= $product_special['date_end']; ?>" placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
														<span class="input-group-btn">
															<button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
														</span></div></td>
														<td class="text-left"><button type="button" onclick="$('#special-row<?= $special_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $special_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="6"></td>
											<td class="text-left"><button type="button" onclick="addSpecial();" data-toggle="tooltip" title="<?= $button_special_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-image">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_image; ?></td>
										</tr>
									</thead>
									
									<tbody>
										<tr>
											<td class="text-left"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?= $thumb; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a><input type="hidden" name="image" value="<?= $image; ?>" id="input-image" /></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="table-responsive">
								<table id="images" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_additional_image; ?></td>
											<!-- << OPTIONS IMAGE -->
											<td class="text-left"><span data-toggle="tooltip" title="<?= $help_option; ?>"><?= $tab_option; ?></span></td>
                                            <!-- >> OPTIONS IMAGE -->
											<td class="text-right"><?= $entry_sort_order; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $image_row = 0; ?>
										<?php foreach ($product_images as $product_image) { ?>
											<tr id="image-row<?= $image_row; ?>">
												<td class="text-left"><a href="" id="thumb-image<?= $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?= $product_image['thumb']; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a><input type="hidden" name="product_image[<?= $image_row; ?>][image]" value="<?= $product_image['image']; ?>" id="input-image<?= $image_row; ?>" /></td>
												<!-- << OPTIONS IMAGE -->
                                                <td>
                                                	<?php if($product_options) { ?>
                                                		<?php 
                                                		$po_counter = 1;
                                                		foreach($product_options as $po) {?>
                                                			<div class="text-left product-options-images-container">
                                                				<b><?= $po['name'] ?></b>
                                                				<?php if(!empty($po['product_option_value'])) {?>
                                                					
																	<?php if($product_option_image_mode == 1) { ?>
																		<select name="product_image[<?= $image_row; ?>][options][<?= $po['product_option_id'] ?>][]" class="form-control">
																			<option value="">--- Select <?= $po['name'] ?> ---</option>
																			<?php foreach($po['product_option_value'] as $pov) { ?>
																			<option value="<?= $pov['product_option_value_id'] ?>" <?= isset($product_image['option_image']) && array_search($pov['product_option_value_id'],array_column($product_image['option_image'], 'product_option_value_id')) > -1 ? 'selected="selected"' :'' ?>><?= $pov['name'] ?></option>
																			<?php } ?>
																		</select>
																	<?php }else{ ?>
																		<?php foreach($po['product_option_value'] as $pov) { ?>
																		<div class="checkbox">
																			<label>
																				<input <?= isset($product_image['option_image']) && array_search($pov['product_option_value_id'],array_column($product_image['option_image'], 'product_option_value_id')) > -1 ? 'checked' :'' ?> type="checkbox" name="product_image[<?= $image_row; ?>][options][<?= $po['product_option_id'] ?>][]" value="<?= $pov['product_option_value_id'] ?>" class="checkboxes-<?= $image_row; ?>">&nbsp;<?= $pov['name'] ?>
																			</label>
																		</div>    
																		<?php }?>
																	<?php } ?>

                                                				<?php }?>
					                                 		</div>
					                                	<?php $po_counter++;}?>

														<?php if($product_option_image_mode == 0) { ?>
                                    					<div>
											              	<a style="cursor:pointer;" onclick="$('.checkboxes-<?= $image_row; ?>').prop('checked', true);">Select All</a> | 
											              	<a style="cursor:pointer;" onclick="$('.checkboxes-<?= $image_row; ?>').prop('checked', false);">Unselect All</a>
											          	</div>
														<?php } ?>

					                                <?php } else {?>
					                                    No options available.
					                                <?php } ?>
				                                </td>
                               					<!-- >> OPTIONS IMAGE -->
												<td class="text-right"><input type="text" name="product_image[<?= $image_row; ?>][sort_order]" value="<?= $product_image['sort_order']; ?>" placeholder="<?= $entry_sort_order; ?>" class="form-control" /></td>
												<td class="text-left"><button type="button" onclick="$('#image-row<?= $image_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $image_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<!-- << OPTIONS IMAGE -->
											<td colspan="3"></td>
                                            <!-- >> OPTIONS IMAGE -->
											<td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?= $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-reward">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-points"><span data-toggle="tooltip" title="<?= $help_points; ?>"><?= $entry_points; ?></span></label>
								<div class="col-sm-10">
									<input type="text" name="points" value="<?= $points; ?>" placeholder="<?= $entry_points; ?>" id="input-points" class="form-control" />
								</div>
							</div>
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_customer_group; ?></td>
											<td class="text-right"><?= $entry_reward; ?></td>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($customer_groups as $customer_group) { ?>
											<tr>
												<td class="text-left"><?= $customer_group['name']; ?></td>
												<td class="text-right"><input type="text" name="product_reward[<?= $customer_group['customer_group_id']; ?>][points]" value="<?= isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" /></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab-design">
							<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_store; ?></td>
											<td class="text-left"><?= $entry_layout; ?></td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left"><?= $text_default; ?></td>
											<td class="text-left"><select name="product_layout[0]" class="form-control">
												<option value=""></option>
												<?php foreach ($layouts as $layout) { ?>
													<?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
														<option value="<?= $layout['layout_id']; ?>" selected="selected"><?= $layout['name']; ?></option>
														<?php } else { ?>
														<option value="<?= $layout['layout_id']; ?>"><?= $layout['name']; ?></option>
													<?php } ?>
												<?php } ?>
											</select></td>
										</tr>
										<?php foreach ($stores as $store) { ?>
											<tr>
												<td class="text-left"><?= $store['name']; ?></td>
												<td class="text-left"><select name="product_layout[<?= $store['store_id']; ?>]" class="form-control">
													<option value=""></option>
													<?php foreach ($layouts as $layout) { ?>
														<?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
															<option value="<?= $layout['layout_id']; ?>" selected="selected"><?= $layout['name']; ?></option>
															<?php } else { ?>
															<option value="<?= $layout['layout_id']; ?>"><?= $layout['name']; ?></option>
														<?php } ?>
													<?php } ?>
												</select></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Enhanced CKEditor -->
	<?php if ($fm_installed == 0) { ?>
		<?php if ($ckeditor_enabled == 1) { ?>
			<script type="text/javascript">
				<?php foreach ($languages as $language) { ?>
					CKEDITOR.replace("input-description<?= $language['language_id']; ?>", { 
						baseHref: "<?= $base_url; ?>", 
						language: "<?= $language['code']; ?>", 
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
						skin : "<?= $ckeditor_skin; ?>", 
					codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
					
						<?php for($i = 0; $i < $attribute_row; $i++){ ?>
						CKEDITOR.replace("input-attribute<?= $i.$language['language_id']; ?>", { baseHref: "<?= $base_url; ?>",  language: "<?= $language['code']; ?>", filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', skin : "<?= $ckeditor_skin; ?>", codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
						<?php } ?>
					
				<?php } ?> 
			</script>
		<?php } ?>
	<?php } ?>
	<!-- Enhanced CKEditor -->	
	<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
	<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
	<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
	<script type="text/javascript"><!--
		$(document).on('focus', '.product-options-images-container select', function() {
			$(this).addClass('highlight-select').find('option').css('color', '#000');
		})
		
		$(document).on('blur', '.product-options-images-container select', function() {
			$(this).removeClass('highlight-select');
			$(this).prev('b').removeClass('highlight-text');
			if (this.value.length) $(this).addClass('highlight-select');
			if (this.value.length) $(this).prev('b').addClass('highlight-text');
		})
		
		$(document).on('change', '.product-options-images-container select', function() {
			if (this.value.length) $(this).addClass('highlight-select');
			else $(this).removeClass('highlight-select')

			if (this.value.length) $(this).prev('b').addClass('highlight-text');
			else $(this).prev('b').removeClass('highlight-text')
		});

		$('.product-options-images-container select').trigger('change');

		// Manufacturer
		$('input[name=\'manufacturer\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						json.unshift({
							manufacturer_id: 0,
							name: '<?= $text_none; ?>'
						});
						
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['manufacturer_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'manufacturer\']').val(item['label']);
				$('input[name=\'manufacturer_id\']').val(item['value']);
			}
		});
		
		// Category
		$('input[name=\'category\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/category/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['category_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'category\']').val('');
				
				$('#product-category' + item['value']).remove();
				
				$('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
			}
		});
		
		$('#product-category').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
		
		// Filter
		$('input[name=\'filter\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/filter/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['filter_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'filter\']').val('');
				
				$('#product-filter' + item['value']).remove();
				
				$('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
			}
		});
		
		$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
		
		// Downloads
		$('input[name=\'download\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/download/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['download_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'download\']').val('');
				
				$('#product-download' + item['value']).remove();
				
				$('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
			}
		});
		
		$('#product-download').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
		
		// Related
		$('input[name=\'related\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/product/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item['name'],
								value: item['product_id']
							}
						}));
					}
				});
			},
			'select': function(item) {
				$('input[name=\'related\']').val('');
				
				$('#product-related' + item['value']).remove();
				
				$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
			}
		});
		
		$('#product-related').delegate('.fa-minus-circle', 'click', function() {
			$(this).parent().remove();
		});
	//--></script>
	<script type="text/javascript">
			<!--
			var attribute_row = <?php echo $attribute_row; ?>;
			
			function addAttribute() {
				
				html  = '<tr id="attribute-row' + attribute_row + '">';
				html += '  <td class="text-left"> <?php echo $entry_attribute; ?><p><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></p><p><?php echo $entry_text; ?></p>';
				//html += '  </td><td class="text-left">';
				html += '<div class="input-group col-xs-12" ><ul class="nav nav-tabs" id="language'+attribute_row+'">';
				<?php foreach ($languages as $language) { ?>
					html += '<li class="<?php if($language['language_id'] == 1) echo 'active'; ?>" >'+
					'<a href="#language-text-'+attribute_row+'<?php echo $language['language_id']; ?>" data-toggle="tab">'+
					'<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>"/><?php echo $language['name']; ?>'+
					'</a>'+
					'</li>';
				<?php } ?>
				html += '</ul><div class="tab-content">';
				<?php foreach ($languages as $language) { ?>
					html += '<div class="tab-pane <?php if($language['language_id'] == 1) echo 'in active'; ?>" id="language-text-'+attribute_row+'<?php echo $language['language_id']; ?>" >';
					html += '<textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" id="input-attribute' + attribute_row + '<?php echo $language['language_id']; ?>" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
				<?php } ?>
				html += '</div></div>';
				html += '  </td>';
				html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
				html += '</tr>';
				
				
				
				$('#attribute tbody').append(html);
				<?php foreach ($languages as $language) { ?>
					CKEDITOR.replace("input-attribute"+attribute_row+"<?php echo $language['language_id']; ?>", { baseHref: "<?= $base_url; ?>", language: "<?php echo $language['code']; ?>", filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', skin : "<?php echo $ckeditor_skin; ?>", codemirror: { theme: "<?php echo $codemirror_skin; ?>", }, height: 350 });
				<?php } ?>
				attributeautocomplete(attribute_row);
				attribute_row++;
				
				
			}
			
			function attributeautocomplete(attribute_row) {
				$('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
					'source': function (request, response) {
						$.ajax({
							url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
							dataType: 'json',
							success: function (json) {
								response($.map(json, function (item) {
									return {category: item.attribute_group, label: item.name, value: item.attribute_id}
								}));
							}
						});
					},
					'select': function (item) {
						$('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
						$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
					}
				});
			}
			
			$('#attribute tbody tr').each(function (index, element) {
				attributeautocomplete(index);
			});
			
	//--></script>
	<script type="text/javascript"><!--
		var option_row = <?= $option_row; ?>;
		
		$('input[name=\'option\']').autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=catalog/option/autocomplete&token=<?= $token; ?>&filter_name=' +  encodeURIComponent(request),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								category: item['category'],
								label: item['name'],
								value: item['option_id'],
								type: item['type'],
								option_value: item['option_value']
							}
						}));
					}
				});
			},
			'select': function(item) {
				html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
				html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
				html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
				html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
				html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';
				
				html += '	<div class="form-group">';
				html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?= $entry_required; ?></label>';
				html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
				html += '	      <option value="0"><?= $text_no; ?></option>';
				html += '	      <option value="1"><?= $text_yes; ?></option>';
				html += '	  </select></div>';
				html += '	</div>';
				
				if (item['type'] == 'text') {
					html += '	<div class="form-group">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?= $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'textarea') {
					html += '	<div class="form-group">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?= $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'file') {
					html += '	<div class="form-group" style="display: none;">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?= $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'date') {
					html += '	<div class="form-group">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?= $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'time') {
					html += '	<div class="form-group">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?= $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'datetime') {
					html += '	<div class="form-group">';
					html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?= $entry_option_value; ?></label>';
					html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?= $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
					html += '	</div>';
				}
				
				if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
					html += '<div class="table-responsive">';
					html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
					html += '  	 <thead>';
					html += '      <tr>';
					html += '        <td class="text-left"><?= $entry_option_value; ?></td>';
					html += '        <td class="text-right"><?= $entry_quantity; ?></td>';
					html += '        <td class="text-left"><?= $entry_subtract; ?></td>';
					html += '        <td class="text-left"><?= $entry_sku; ?></td>';
					html += '        <td class="text-right"><?= $entry_price; ?></td>';
					html += '        <td class="text-right"><?= $entry_option_points; ?></td>';
					html += '        <td class="text-right"><?= $entry_weight; ?></td>';
					html += '        <td></td>';
					html += '      </tr>';
					html += '  	 </thead>';
					html += '  	 <tbody>';
					html += '    </tbody>';
					html += '    <tfoot>';
					html += '      <tr>';
					html += '        <td colspan="7"></td>';
					html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?= $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
					html += '      </tr>';
					html += '    </tfoot>';
					html += '  </table>';
					html += '</div>';
					
					html += '  <select id="option-values' + option_row + '" style="display: none;">';
					
					for (i = 0; i < item['option_value'].length; i++) {
						html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
					}
					
					html += '  </select>';
					html += '</div>';
				}
				
				$('#tab-option .tab-content').append(html);
				
				$('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick=" $(\'#option a:first\').tab(\'show\');$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove();"></i>' + item['label'] + '</li>');
				
				$('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
				
				$('[data-toggle=\'tooltip\']').tooltip({
					container: 'body',
					html: true
				});
				
				$('.date').datetimepicker({
					pickTime: false
				});
				
				$('.time').datetimepicker({
					pickDate: false
				});
				
				$('.datetime').datetimepicker({
					pickDate: true,
					pickTime: true
				});
				
				option_row++;
			}
		});
	//--></script>
	<script type="text/javascript"><!--
		var option_value_row = <?= $option_value_row; ?>;
		
		function addOptionValue(option_row) {
			html  = '<tr id="option-value-row' + option_value_row + '">';
			html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
			html += $('#option-values' + option_row).html();
			html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
			html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?= $entry_quantity; ?>" class="form-control" /></td>';
			html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
			html += '    <option value="0"><?= $text_no; ?></option>';
			html += '    <option value="1"><?= $text_yes; ?></option>';
			html += '  </select></td>';
			
			html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][sku]" value="" placeholder="SKU" class="form-control" /></td>';
			
			html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
			html += '    <option value="+">+</option>';
			html += '    <option value="-">-</option>';
			html += '  </select>';
			
			html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?= $entry_price; ?>" class="form-control" /></td                                                                  >';
			html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
			html += '    <option value="+">+</option>';
			html += '    <option value="-">-</option>';
			html += '  </select>';
			html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?= $entry_points; ?>" class="form-control" /></td>';
			html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
			html += '    <option value="+">+</option>';
			html += '    <option value="-">-</option>';
			html += '  </select>';
			html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?= $entry_weight; ?>" class="form-control" /></td>';
			html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';
			
			$('#option-value' + option_row + ' tbody').append(html);
			$('[rel=tooltip]').tooltip();
			
			option_value_row++;
		}
	//--></script>
	<script type="text/javascript"><!--
		var discount_row = <?= $discount_row; ?>;
		
		function addDiscount() {
			html  = '<tr id="discount-row' + discount_row + '">';
			html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
			<?php foreach ($customer_groups as $customer_group) { ?>
				html += '    <option value="<?= $customer_group['customer_group_id']; ?>"><?= addslashes($customer_group['name']); ?></option>';
			<?php } ?>
			html += '  </select></td>';
			html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?= $entry_quantity; ?>" class="form-control" /></td>';
			html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?= $entry_priority; ?>" class="form-control" /></td>';
			html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?= $entry_price; ?>" class="form-control" /></td>';
			html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
			html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
			html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';
			
			$('#discount tbody').append(html);
			
			$('.date').datetimepicker({
				pickTime: false
			});
			
			discount_row++;
		}
	//--></script>
	<script type="text/javascript"><!--
		var special_row = <?= $special_row; ?>;
		
		function addSpecial() {
			html  = '<tr id="special-row' + special_row + '">';
			html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
			<?php foreach ($customer_groups as $customer_group) { ?>
				html += '      <option value="<?= $customer_group['customer_group_id']; ?>"><?= addslashes($customer_group['name']); ?></option>';
			<?php } ?>
			html += '  </select></td>';
			html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?= $entry_priority; ?>" class="form-control" /></td>';
			html += '  <td class="text-right">';
			html += '  	<div class="input-group">';
			html += '		<input type="text" value="" placeholder="<?= $entry_percentage; ?>" class="form-control percent-input percent-input' + special_row + '" data-calc="product_special[' + special_row + '][price]" />';
			html += '		<span class="input-group-btn">';
			html += '			<button class="btn btn-default" type="button">%</button>';
			html += '		</span>';
			html += '	</div>';
			html += '  </td>';
			html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?= $entry_price; ?>" class="form-control price-special" data-calc="percent-input' + special_row + '" /></td>';
			html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
			html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
			html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';
			
			$('#special tbody').append(html);
			
			$('.date').datetimepicker({
				pickTime: false
			});
			
			special_row++;
		}
	//--></script>
	<script type="text/javascript"><!--
		function selectAllOption(row_no) {

		}

		var image_row = <?= $image_row; ?>;
		
		function addImage() {
			html  = '<tr id="image-row' + image_row + '">';
			html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?= $placeholder; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';

			//<!-- >> OPTIONS IMAGE -->
            html += '  <td>';
            <?php if($product_options) {?>
                <?php 
                $po_counter = 1;
                foreach($product_options as $po) {?>
                 html += '<div class="text-left product-options-images-container">';
                 html += '<b><?= $po['name'] ?></b>';
                    <?php if(!empty($po['product_option_value'])) {?>

                        <?php if($product_option_image_mode == 1) { ?>
							html += '<select name="product_image['+ image_row + '][options][<?= $po['product_option_id'] ?>][]" class="form-control">';
								html += '<option value="">--- Select <?= $po['name'] ?> ---</option>';
								<?php foreach($po['product_option_value'] as $pov) { ?>
								html += '<option value="<?= $pov['product_option_value_id'] ?>"><?= $pov['name'] ?></option>';
								<?php } ?>
							html += '</select>';
						<?php }else{ ?>
							<?php foreach($po['product_option_value'] as $pov) { ?>
								html += '<div class="checkbox">';
									html += '<label><input type="checkbox" name="product_image['+image_row+'][options][<?= $po['product_option_id'] ?>][]" value="<?= $pov['product_option_value_id'] ?>" class="checkboxes-'+image_row+'">&nbsp;<?= $pov['name'] ?></label>';
								html += '</div>';  
							<?php }?>
						<?php } ?>

                    <?php }?>
                html += '</div>';

                <?php $po_counter++;}?>

				<?php if($product_option_image_mode == 0) { ?>
                html += '<div>';
              		html += '<a style="cursor:pointer;" onclick="$(\'.checkboxes-'+image_row+'\').prop(\'checked\', true);">Select All</a> | ';
              		html += '<a style="cursor:pointer;" onclick="$(\'.checkboxes-'+image_row+'\').prop(\'checked\', false);">Unselect All</a>';
              	html += '</div>';
				<?php } ?>

            <?php } else {?>
                html += 'No options available.'
            <?php } ?>
			html += '  </td>';
            //<!-- << OPTIONS IMAGE -->

			html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?= $entry_sort_order; ?>" class="form-control" /></td>';
			html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';
			
			$('#images tbody').append(html);
			
			image_row++;
		}
	//--></script>
	<script type="text/javascript"><!--
		var recurring_row = <?= $recurring_row; ?>;
		
		function addRecurring() {
			html  = '<tr id="recurring-row' + recurring_row + '">';
			html += '  <td class="left">';
			html += '    <select name="product_recurring[' + recurring_row + '][recurring_id]" class="form-control">>';
			<?php foreach ($recurrings as $recurring) { ?>
				html += '      <option value="<?= $recurring['recurring_id']; ?>"><?= $recurring['name']; ?></option>';
			<?php } ?>
			html += '    </select>';
			html += '  </td>';
			html += '  <td class="left">';
			html += '    <select name="product_recurring[' + recurring_row + '][customer_group_id]" class="form-control">>';
			<?php foreach ($customer_groups as $customer_group) { ?>
				html += '      <option value="<?= $customer_group['customer_group_id']; ?>"><?= $customer_group['name']; ?></option>';
			<?php } ?>
			html += '    <select>';
			html += '  </td>';
			html += '  <td class="left">';
			html += '    <a onclick="$(\'#recurring-row' + recurring_row + '\').remove()" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>';
			html += '  </td>';
			html += '</tr>';
			
			$('#tab-recurring table tbody').append(html);
			
			recurring_row++;
		}
	//--></script>
	<script type="text/javascript"><!--
		$('.date').datetimepicker({
			pickTime: false
		});
		
		$('.time').datetimepicker({
			pickDate: false
		});
		
		$('.datetime').datetimepicker({
			pickDate: true,
			pickTime: true
		});
	//--></script>
	<script type="text/javascript"><!--
		$('#language a:first').tab('show');
		$('#option a:first').tab('show');
	//--></script>
	<script type="text/javascript"><!--
	$('#button-upload').on('click', function() {
		$('#form-upload').remove();
		
		$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
		
		$('#form-upload input[name=\'file\']').trigger('click');
		
		if (typeof timer != 'undefined') {
			clearInterval(timer);
		}
		
		timer = setInterval(function() {
			if ($('#form-upload input[name=\'file\']').val() != '') {
				clearInterval(timer);		
				
				$.ajax({
					url: 'index.php?route=catalog/download/upload&token=<?php echo $token; ?>',
					type: 'post',		
					dataType: 'json',
					data: new FormData($('#form-upload')[0]),
					cache: false,
					contentType: false,
					processData: false,		
					beforeSend: function() {
						$('#button-upload').button('loading');
					},
					complete: function() {
						$('#button-upload').button('reset');
					},	
					success: function(json) {
						if (json['error']) {
							alert(json['error']);
						}
						
						if (json['success']) {
							alert(json['success']);
							
							$('input[name=\'filename\']').val(json['filename']);
							$('input[name=\'mask\']').val(json['mask']);
						}
					},			
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		}, 500);
	});

	function getPrice(price, discount) {         
		var numVal1 = Number(price);
		var numVal2 = Number(discount);
		return (numVal1 * ((100-numVal2) / 100 )).toFixed(2);
	}

	function getPercentage(old_price, new_price) {
		if(old_price && new_price) {
			var decreaseValue = Number(old_price) - Number(new_price);
			return (Math.abs(decreaseValue / old_price) * 100).toFixed(2);
		}
	}

	$(document).on('keyup', '.percent-input', function(){
		var new_price = getPrice($('#input-price').val(), $(this).val());
		$('input[name="' + $(this).data('calc') + '"]').val(new_price);
	});

	function calcPercentage(price_field, old_price, new_price) {
		var percent = getPercentage(old_price, new_price);
		//console.log(percent);
		$('.' + price_field.data('calc')).val(percent);
	}

	$(document).on('keyup', '.price-special', function(){
		calcPercentage($(this), $('#input-price').val(), $(this).val());
	});

	// Calculate percentage if price and discount price both have value
	$('.price-special').each(function(){
		calcPercentage($(this), $('#input-price').val(), $(this).val());
	});
//--></script></div>


<!-- << Related Options / Связанные опции  -->

<script type="text/javascript"><!--

var ro_counter = 0;
var ro_discount_counter = 0;
var ro_special_counter = 0;
var ro_variants = [];
//var ro_variants_options_order = [];
var ro_all_options = <?php echo json_encode($ro_all_options); ?>;
var ro_settings = <?php echo json_encode($ro_settings); ?>;
var ro_variants = <?php echo json_encode($variants_options['vopts']); ?>;
var ro_variants_sorted = <?php echo json_encode($variants_options['sorted']); ?>;
var ro_data = <?php echo json_encode($ro_data); ?>;
//ro_variants_options_order[0] = [];

if ( ro_variants.length == 0 ) {
	$('#tab-related_options').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_ro_set_options_variants; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
}

var ro_tabs_cnt = 0;

// ROPRO
function ro_tab_name_change(ro_tabs_num) {
	
	if ( $('#ro-use-'+ro_tabs_num+'').is(':checked') ) {
		var new_tab_name = $('#rov-'+ro_tabs_num+' option[value="'+$('#rov-'+ro_tabs_num).val()+'"]').html();
	} else {
		var new_tab_name = '<?php echo addslashes($related_options_title); ?>';
	}
	
	$('#ro_nav_tabs a[data-ro-cnt="'+ro_tabs_num+'"]').html(new_tab_name);
	
}

function ro_add_tab(tab_data_param) {

	var tab_data = tab_data_param ? tab_data_param : false;
	
	html = '<li><a href="#tab-ro-'+ro_tabs_cnt+'" data-toggle="tab" data-ro-cnt="'+ro_tabs_cnt+'">ro '+ro_tabs_cnt+'</a></li>';
	$('#ro_add_tab_button').closest('li').before(html);
	
	html = '<div class="tab-pane" id="tab-ro-'+ro_tabs_cnt+'" data-ro-cnt="'+ro_tabs_cnt+'">'+ro_tabs_cnt+'</div>';
	$('#ro_content').append(html);
	
	$('#ro_nav_tabs [data-ro-cnt='+ro_tabs_cnt+']').click();
	
	html = '';
	html+= '<input type="hidden" name="ro_data['+ro_tabs_cnt+'][rovp_id]" value="'+(tab_data['rovp_id'] ? tab_data['rovp_id'] : '0')+'">';
	html+= '<div class="form-group">';
	
	html+= '<label class="col-sm-2 control-label"><?php echo addslashes($entry_ro_use); ?></label>';
	
	html+= '<div class="col-sm-10">';
	html+= '<label class="radio-inline">';
		html+= '<input type="radio" name="ro_data['+ro_tabs_cnt+'][use]" id="ro-use-'+ro_tabs_cnt+'" value="1" '+((tab_data['use'])?('checked'):(''))+' onchange="ro_use_check('+ro_tabs_cnt+')" />';
		html+= ' <?php echo $text_yes; ?>';
	html+= '</label>';
	html+= '<label class="radio-inline">';
		html+= '<input type="radio" name="ro_data['+ro_tabs_cnt+'][use]" value="" '+((tab_data['use'])?(''):('checked'))+' onchange="ro_use_check('+ro_tabs_cnt+')" />';
		html+= ' <?php echo $text_no; ?>';
	html+= '</label>';
	html+= '</div>';
	
	html+= '</div>';
	
	html+= '<div id="ro-use-data-'+ro_tabs_cnt+'">';
	html+= '	<div class="form-group">';
	html+= '		<label class="col-sm-2 control-label" for="rov-'+ro_tabs_cnt+'" ><?php echo $entry_ro_variant; ?></label>';
	html+= '		<div class="col-sm-3" >';
	html+= '			<select name="ro_data['+ro_tabs_cnt+'][rov_id]" id="rov-'+ro_tabs_cnt+'" class="form-control" onChange="ro_tab_name_change('+ro_tabs_cnt+');">';
	
	if (ro_settings['ro_use_variants']) {
		for (var i in ro_variants_sorted) {
			var ro_variant = ro_variants_sorted[i];
			if (ro_variant['rov_id'] == 0) {
				html+= '				<option value="0"><?php echo addslashes($text_ro_all_options); ?></option>';
			} else {
				html+= '			<option value="'+ro_variant['rov_id']+'" '+(tab_data['rov_id'] && tab_data['rov_id'] == ro_variant['rov_id'] ? 'selected':'')+' >'+ro_variant['name']+'</option>';
			}
		}	
	} else {
		html+= '				<option value="0"><?php echo addslashes($text_ro_all_options); ?></option>';
	}
	
	html+= '			</select>';
	html+= '		</div>';
	html+= '		<button type="button" onclick="ro_fill_all_combinations('+ro_tabs_cnt+');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo addslashes($entry_add_all_variants); ?>"><?php echo addslashes($entry_add_all_variants); ?></button>';
	html+= '		<button type="button" onclick="ro_fill_all_combinations('+ro_tabs_cnt+',1);" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo addslashes($entry_add_product_variants); ?>"><?php echo addslashes($entry_add_product_variants); ?></button>';
	html+= '		<button type="button" onclick="ro_delete_all_combinations('+ro_tabs_cnt+');" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="<?php echo addslashes($entry_delete_all_combs); ?>"><?php echo addslashes($entry_delete_all_combs); ?></button>';
	html+= '	</div>';
	
	
	
	html+= '	<div class="table-responsive">';
	html+= '		<table class="table table-striped table-bordered table-hover">';
	html+= '			<thead>';
	html+= '				<tr>';
	html+= '					<td class="text-left"><?php echo addslashes($entry_options_values); ?></td>';
	html+= '					<td class="text-left" width="90"><?php echo addslashes($entry_related_options_quantity); ?>:</td>';
			
	var ro_fields = {spec_model: "<?php echo addslashes($entry_model); ?>"
									,spec_sku: "<?php echo addslashes($entry_sku); ?>"
									,spec_upc: "<?php echo addslashes($entry_upc); ?>"
									,spec_ean: "<?php echo addslashes($entry_ean); ?>"
									,spec_location: "<?php echo addslashes($entry_location); ?>"
									,spec_ofs: "<?php echo addslashes($entry_stock_status); ?>"
									,spec_weight: "<?php echo addslashes($entry_weight); ?>"
									};
								
	for (var i in ro_fields) {
		if (ro_settings[i] && ro_settings[i] != 0) {
			html+= '<td class="text-left" width="90">'+ro_fields[i]+'</td>';
		}
	}
			
	if (ro_settings['spec_price'] ) {
		html+= '				<td class="text-left" width="90" ><?php echo addslashes($entry_price); ?></td>';
		if (ro_settings['spec_price_discount'] ) {
			html+= '					<td class="text-left" style="90"><?php echo addslashes($tab_discount); ?>: <font style="font-weight:normal;font-size:80%;">(<?php echo addslashes(str_replace(":","",$entry_customer_group." | ".$entry_quantity." | ".$entry_price)); ?>)</font></td>';
		}
		if (ro_settings['spec_price_special'] ) {
			html+= '					<td class="text-left" style="90"><?php echo addslashes($tab_special); ?>: <font style="font-weight:normal;font-size:80%;">(<?php echo addslashes(str_replace(":","",$entry_customer_group." | ".$entry_price)); ?>)</font></td>';
		}
	}
				
	if (ro_settings['select_first'] && ro_settings['select_first'] == 1 ) {
		html+= '				<td class="text-left" width="90" style="white-space:nowrap"><?php echo addslashes($entry_select_first_short); ?>:</td>';
	}
	
					
	html+= '					<td class="text-left" width="90"></td>';
	
	html+= '				<tr>';			
	html+= '		</thead>';
	html+= '		<tbody id="tbody-ro-'+ro_tabs_cnt+'"></tbody>';
	html+= '	</table>';
	
	html+= '	<div class="col-sm-2" >';
	html+= '		<button type="button" onclick="ro_add_combination('+ro_tabs_cnt+', false);" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo addslashes($entry_add_related_options); ?>"><?php echo addslashes($entry_add_related_options); ?></button>';
	html+= '	</div>';
			
	html+= '</div>';
	
	html+= '';
	html+= '';
	html+= '</div>';
	
	$('#tab-ro-'+ro_tabs_cnt+'').html(html);
	$('#ro-use-'+ro_tabs_cnt).prop('checked', true);
	ro_use_check(ro_tabs_cnt);
	
	if (tab_data['ro']) {
		for (var i in tab_data['ro']) {
			ro_add_combination(ro_tabs_cnt, tab_data['ro'][i]);
		}
	}
	
	// select added tab ROPRO
	$('#ro_nav_tabs a[data-ro-cnt="'+ro_tabs_cnt+'"]').click();
	
	ro_tabs_cnt++;
	
	return ro_tabs_cnt-1;
	
}

function ro_use_check(ro_tabs_num) {
	
	$('#ro-use-data-'+ro_tabs_num).toggle( $('input[type=radio][name="ro_data['+ro_tabs_num+'][use]"][value="1"]').is(':checked') );
	ro_tab_name_change(ro_tabs_num);
	
}

function ro_add_combination(ro_tabs_num, params) {

	var rov_id = $('#rov-'+ro_tabs_num).val();
	var ro_variant = ro_variants[ rov_id ];

	var entry_add_discount = "<?php echo addslashes($entry_add_discount); ?>";
	var entry_del_discount_title = "<?php echo addslashes($entry_del_discount_title); ?>";
	
	var entry_add_special = "<?php echo addslashes($entry_add_special); ?>";
	var entry_del_special_title = "<?php echo addslashes($entry_del_special_title); ?>";
	
	
	str_add = '';
	str_add += "<tr id=\"related-option"+ro_counter+"\"><td>";
	
	var div_id = "ro_status"+ro_counter;
	str_add +="<div id='"+div_id+"' style='color: red'></div>";
	
	for (var i in ro_variant['options']) {
		
		var ro_option = ro_variant['options'][i];
		var option_id = ro_option['option_id'];
	
		str_add += "<div style='float:left;'><label class='col-sm-1 control-label' for='ro_o_"+ro_counter+"_"+option_id+"'> ";
		str_add += "<nobr>"+ro_option['name']+":</nobr>";
		str_add += "</label>";
		str_add += "<select class='form-control' id='ro_o_"+ro_counter+"_"+option_id+"' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][options]["+option_id+"]' onChange=\"ro_refresh_status("+ro_tabs_num+","+ro_counter+")\">";
		str_add += "<option value=0></option>";
					
			for (var j in ro_all_options[option_id]['values']) {
				if((ro_all_options[option_id]['values'][j] instanceof Function) ) { continue; }
				
				var option_value_id = ro_all_options[option_id]['values'][j]['option_value_id'];
				
				str_add += "<option value='"+option_value_id+"'";
				if (params['options'] && params['options'][option_id] && params['options'][option_id] == option_value_id) str_add +=" selected ";
				str_add += ">"+ro_all_options[option_id]['values'][j]['name']+"</option>";
			}

		str_add += "</select>";
		str_add += "</div>";
	}
	
  
  str_add += "</td>";
  str_add += "<td>&nbsp;";
	str_add += "<input type='text' class='form-control' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][quantity]' size='2' value='"+(params['quantity']||999)+"'>";
  str_add += "<input type='hidden' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][relatedoptions_id]' value='"+(params['relatedoptions_id']||"")+"'>";
  str_add += "</td>";
	
	str_add += ro_add_text_field(ro_tabs_num, ro_counter, 'spec_model', params, 'model');
	str_add += ro_add_text_field(ro_tabs_num, ro_counter, 'spec_sku', params, 'sku');
	str_add += ro_add_text_field(ro_tabs_num, ro_counter, 'spec_upc', params, 'upc');
	str_add += ro_add_text_field(ro_tabs_num, ro_counter, 'spec_ean', params, 'ean');
	str_add += ro_add_text_field(ro_tabs_num, ro_counter, 'spec_location', params, 'location');
	
	if (ro_settings['spec_ofs']) {
		
		str_add += '<td>';
		str_add += '&nbsp;<select name="ro_data['+ro_tabs_num+'][ro]['+ro_counter+'][stock_status_id]" class="form-control">';
		str_add += '<option value="0">-</option>';
		<?php foreach ($stock_statuses as $stock_status) { ?>
			str_add += '<option value="<?php echo $stock_status['stock_status_id']; ?>"';
			if ("<?php echo $stock_status['stock_status_id'] ?>" == params['stock_status_id']) {
				str_add += ' selected ';
			}
			str_add += '><?php echo addslashes($stock_status['name']); ?></option>';
		<?php } ?>
		str_add += '</select>';
		
		str_add += '</td>';
	
	}
	
	if (ro_settings['spec_weight'])	{
		str_add += "<td>&nbsp;";
		str_add += "<select class='form-control' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][weight_prefix]'>";
		str_add += "<option value='=' "+( (params['weight_prefix'] && params['weight_prefix']=='=')? ("selected") : (""))+">=</option>";
		str_add += "<option value='+' "+( (params['weight_prefix'] && params['weight_prefix']=='+')? ("selected") : (""))+">+</option>";
		str_add += "<option value='-' "+( (params['weight_prefix'] && params['weight_prefix']=='-')? ("selected") : (""))+">-</option>";
		str_add += "</select>";
		str_add += "<input type='text' class='form-control' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][weight]' value=\""+(params['weight']||'0.000')+"\" size='5'>";
		str_add += "</td>";
	}
	
	if (ro_settings['spec_price'])	{
		str_add += "<td>&nbsp;";
		if (ro_settings['spec_price_prefix']) {
			str_add += "<select name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][price_prefix]' class='form-control'>";
			var price_prefixes = ['=', '+', '-'];
			for (var i in price_prefixes) {
				if((price_prefixes[i] instanceof Function) ) { continue; }
				var price_prefix = price_prefixes[i];
				str_add += "<option value='"+price_prefix+"' "+(price_prefix==params['price_prefix']?"selected":"")+">"+price_prefix+"</option>";
			}
			str_add += "</select>";
		}
		str_add += "<input type='text' class='form-control' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][price]' value='"+(params['price']||'')+"' size='10'>";
		str_add += "</td>";
	}
	
	
	if (ro_settings['spec_price'] && ro_settings['spec_price_discount'])	{
		str_add += "<td>";
	
		str_add += "<button type='button' onclick=\"ro_add_discount("+ro_tabs_num+", "+ro_counter+", '');\" data-toggle='tooltip' title='"+entry_add_discount+"' class='btn btn-primary'><i class='fa fa-plus-circle'></i></button>";
		str_add += "<div id='ro_price_discount"+ro_counter+"' >";
		str_add += "</div>";
		str_add += "</td>";	
	}
	
	if (ro_settings['spec_price'] && ro_settings['spec_price_special'])	{
		str_add += "<td>";
		str_add += "<button type='button' onclick=\"ro_add_special("+ro_tabs_num+", "+ro_counter+", '');\" data-toggle='tooltip' title='"+entry_add_special+"' class='btn btn-primary'><i class='fa fa-plus-circle'></i></button>";
		str_add += "<div id='ro_price_special"+ro_counter+"'>";
		str_add += "</div>";
		str_add += "</td>";	
	}
	
	if (ro_settings['select_first'] && ro_settings['select_first']==1) {
		str_add += "<td>&nbsp;";
		
		str_add += "<input id='defaultselect_"+ro_counter+"' type='checkbox' onchange='ro_check_defaultselectpriority("+ro_tabs_num+");' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][defaultselect]' "+((params && params['defaultselect']==1)?("checked"):(""))+" value='1'>";
		str_add += "<input id='defaultselectpriority_"+ro_counter+"' type='text' class='form-control' title='<?php echo $entry_select_first_priority; ?>' name='ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][defaultselectpriority]'  value=\""+((params && params['defaultselectpriority'])?(params['defaultselectpriority']):(""))+"\" >";
		str_add += "</td>";	
	}

	str_add += "<td><br>";
	str_add += "<button type=\"button\" class='btn btn-danger' onclick=\"$('#related-option" + ro_counter + "').remove();ro_refresh_status("+ro_tabs_num+");\" data-toggle=\"tooltip\" title=\"<?php echo $button_remove; ?>\" class='btn btn-primary' data-original-title=\"<?php echo $button_remove; ?>\" ><i class=\"fa fa-minus-circle\"></i></button>";
	
  str_add += "</td></tr>";
  
  $('#tbody-ro-'+ro_tabs_num).append(str_add);
	
	if (ro_settings['spec_price'] && ro_settings['spec_price_discount'])	{
		if (params && params['discounts'] ) {
			for (var i_dscnt in params['discounts']) {
				if ( ! params['discounts'].hasOwnProperty(i_dscnt) ) continue;
				ro_add_discount(ro_tabs_num, ro_counter, params['discounts'][i_dscnt]);
			}
		}
	}
	
	if (ro_settings['spec_price'] && ro_settings['spec_price_special'])	{
		if (params && params['specials'] ) {
			for (var i_dscnt in params['specials']) {
				if ( ! params['specials'].hasOwnProperty(i_dscnt) ) continue;
				ro_add_special(ro_tabs_num, ro_counter, params['specials'][i_dscnt]);
			}
		}
	}
	
	ro_update_combination(ro_tabs_num,ro_counter);
	
	if (params==false) {
		ro_refresh_status(ro_tabs_num);
		ro_check_defaultselectpriority(ro_tabs_num);
	}
	
  ro_counter++;
  
}

function ro_refresh_status(ro_tabs_num, ro_num) {
  
	if (ro_num || ro_num==0) {
		ro_update_combination(ro_tabs_num, ro_num);
	}
	
	var rov_id = $('#rov-'+ro_tabs_num).val();
	var ro_variant = ro_variants[ rov_id ];
	
	$('#tab-ro-'+ro_tabs_num+' div[id^=ro_status]').html('');
	
	var opts_combs = [];
	var checked_opts_combs = [];
	$('#tab-ro-'+ro_tabs_num+' tr[id^=related-option]').each( function () {
		var opts_comb = $(this).attr('ro_opts_comb');
		
		if ( $.inArray(opts_comb, opts_combs) != -1 && $.inArray(opts_comb, checked_opts_combs)==-1 ) {
			$('#tab-ro-'+ro_tabs_num+' tr[ro_opts_comb='+opts_comb+']').each( function () {
				$(this).find('div[id^=ro_status]').html('<?php echo $warning_equal_options; ?>');
			});
			checked_opts_combs.push(opts_comb);
		} else {
			opts_combs.push(opts_comb);
		}
	})
	
	return;
	
}

function ro_update_combination(ro_tabs_num, ro_num) {
	
	var rov_id = $('#rov-'+ro_tabs_num).val();
	var ro_variant = ro_variants[ rov_id ];
	var str_opts = "";
	
	for (var i in ro_variant['options']) {
		
		if((ro_variant['options'][i] instanceof Function) ) { continue; }
		
		var option_id = ro_variant['options'][i]['option_id'];
	
		str_opts += "_o"+option_id;
		str_opts += "_"+$('#ro_o_'+ro_num+'_'+option_id).val();
	}
	$('#related-option'+ro_num).attr('ro_opts_comb', str_opts);
	
}

function ro_add_text_field(ro_tabs_num, ro_num, setting_name, params, field_name) {
	str_add = "";
	if (ro_settings[setting_name] && ro_settings[setting_name]!='0')	{
		str_add += "<td>&nbsp;";
		str_add += "<input type='text' class='form-control' name='ro_data["+ro_tabs_num+"][ro]["+ro_num+"]["+field_name+"]' value=\""+(params[field_name]||'')+"\">";
		str_add += "</td>";
	}
	return str_add;
}

function ro_add_discount(ro_tabs_num, ro_counter, discount) {
	
	var first_name = "ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][discounts]["+ro_discount_counter+"]";
	var customer_group_id = (discount=="")?(0):(discount['customer_group_id']);
	
	str_add = "";
	str_add += "<table id='related-option-discount"+ro_discount_counter+"' style='width:300px;'><tr><td>";
	
	str_add += "<select name='"+first_name+"[customer_group_id]' class='form-control' title=\"<?php echo htmlspecialchars($entry_customer_group); ?>\" style='float:left;width:80px;'>";
	<?php foreach ($customer_groups as $customer_group) { ?>
	str_add += "<option value='<?php echo $customer_group['customer_group_id']; ?>' "+((customer_group_id==<?php echo $customer_group['customer_group_id']; ?>)?("selected"):(""))+"><?php echo $customer_group['name']; ?></option>";
	<?php } ?>
	str_add += "</select>";
	
	str_add += "<input type='text' class='form-control' style='float:left;width:100px;' size='2' name='"+first_name+"[quantity]' value='"+((discount=="")?(""):(discount['quantity']))+"' title=\"<?php echo htmlspecialchars($entry_quantity); ?>\">";
	str_add += "";
	
	// hidden
	str_add += "<input type='hidden' name='"+first_name+"[priority]' value='"+((discount=="")?(""):(discount['priority']))+"' title=\"<?php echo htmlspecialchars($entry_priority); ?>\">";
	
	str_add += "<input type='text' class='form-control' style='float:left;width:80px;' size='10' name='"+first_name+"[price]' value='"+((discount=="")?(""):(discount['price']))+"' title=\"<?php echo htmlspecialchars($entry_price); ?>\">";
	
	str_add += "<button type=\"button\" onclick=\"$('#related-option-discount" + ro_discount_counter + "').remove();\" data-toggle=\"tooltip\" title=\"<?php echo $button_remove; ?>\" class=\"btn btn-danger\" style='float:left;' data-original-title=\"\"><i class=\"fa fa-minus-circle\"></i></button>";

	str_add += "</td></tr></table>";
	
	$('#ro_price_discount'+ro_counter).append(str_add);
	
	ro_discount_counter++;
	
}

function ro_add_special(ro_tabs_num, ro_counter, special) {
	
	var first_name = "ro_data["+ro_tabs_num+"][ro]["+ro_counter+"][specials]["+ro_special_counter+"]";
	var customer_group_id = (special=="")?(0):(special['customer_group_id']);
	
	str_add = "";
	str_add += "<table id='related-option-special"+ro_special_counter+"' style='width:200px;'><tr><td>";
	
	str_add += "<select name='"+first_name+"[customer_group_id]' class='form-control' style='float:left;width:80px;' title=\"<?php echo htmlspecialchars($entry_customer_group); ?>\">";
	<?php foreach ($customer_groups as $customer_group) { ?>
	str_add += "<option value='<?php echo $customer_group['customer_group_id']; ?>' "+((customer_group_id==<?php echo $customer_group['customer_group_id']; ?>)?("selected"):(""))+"><?php echo $customer_group['name']; ?></option>";
	<?php } ?>
	str_add += "</select>";
	
	// hidden
	str_add += "<input type='hidden' size='2' name='"+first_name+"[priority]' value='"+((special=="")?(""):(special['priority']))+"' title=\"<?php echo htmlspecialchars($entry_priority); ?>\">";
	str_add += "<input type='text'  class='form-control' style='float:left;width:80px;' size='10' name='"+first_name+"[price]' value='"+((special=="")?(""):(special['price']))+"' title=\"<?php echo htmlspecialchars($entry_price); ?>\">";
	str_add += "<button type=\"button\" onclick=\"$('#related-option-special" + ro_special_counter + "').remove();\" data-toggle=\"tooltip\" title=\"<?php echo $button_remove; ?>\" class=\"btn btn-danger\" style='float:left;' data-original-title=\"<?php echo $button_remove; ?>\"><i class=\"fa fa-minus-circle\"></i></button>";
	str_add += "</td></tr></table>";
	
	$('#ro_price_special'+ro_counter).append(str_add);
	
	ro_special_counter++;
	
}

function ro_delete_all_combinations(ro_tabs_num) {

	if ( confirm('<?php echo $text_delete_all_combs; ?>') ) {
		// fastest
		$('#tbody-ro-'+ro_tabs_num+' tr').detach().remove();
		//$('#tbody-ro-'+ro_tabs_num).empty();
		//$('#tbody-ro-'+ro_tabs_num+' tr').remove();
		//$('#tbody-ro-'+ro_tabs_num).html('');
		ro_refresh_status(ro_tabs_num);
	}
}

function numberOfPossibleCombinations(ro_variant) {
	var numberOfCombs = 1;
	for (var i in ro_variant['options']) {
		var option_id = ro_variant['options'][i]['option_id'];
		var numberOfValues = ro_all_options[option_id]['values'].length || 1;
		numberOfCombs = numberOfCombs * numberOfValues;
	}
	return numberOfCombs;
}

function confirmNumberOfCombinations(numberOfCombs) {
	var maxNumberOfCombinations = <?php echo $maxNumberOfCombinations; ?>;
	var confirmNumberOfCombinations = <?php echo $confirmNumberOfCombinations; ?>;
	if ( numberOfCombs > maxNumberOfCombinations ) {
		alert('<?php echo $text_combs_number; ?>'+numberOfCombs.toString()+'<?php echo $text_combs_number_out_of_limit; ?>');
		return false;
	} else if ( numberOfCombs > confirmNumberOfCombinations ) {
		if ( !confirm('<?php echo $text_combs_number; ?>'+numberOfCombs.toString()+'<?php echo $text_combs_number_is_big; ?>') ) {
			return false;
		}
	} else {
		if ( !confirm(numberOfCombs.toString()+'<?php echo $text_combs_will_be_added; ?>') ) {
			return false;
		}
	}
	return true;
}

function ro_fill_all_combinations(ro_tabs_num, product_options_only) {

	var rov_id = $('#rov-'+ro_tabs_num).val();
	var ro_variant = ro_variants[ rov_id ];
	var all_vars = [];
	
	if (product_options_only) {
		var this_product_options = [];
		$('select[name^=product_option][name*=option_value_id]').each(function() {
			if ( $(this).val() ) {
				this_product_options.push($(this).val());
			}
		});
	}
	
	if (!product_options_only) {
		// if all options used, there may be millinons of combinations, it may freeze script before determination of combinations list
		var numberOfCombs = numberOfPossibleCombinations(ro_variant);
		if (!confirmNumberOfCombinations(numberOfCombs)) {
			return;
		}
	}
		
	var reversed_options = [];	
	for (var i in ro_variant['options']) {
		if((ro_variant['options'][i] instanceof Function) ) { continue; }
		reversed_options.unshift(i);
	}
		
	for (var i_index in reversed_options) {
	
		var i = reversed_options[i_index];
		
		var option_id = ro_variant['options'][i]['option_id'];
		
		var temp_arr = [];
		for (var j in ro_all_options[option_id]['values']) {
			if((ro_all_options[option_id]['values'][j] instanceof Function) ) { continue; }
			
			var option_value_id = ro_all_options[option_id]['values'][j]['option_value_id']
			
			if (product_options_only && $.inArray(option_value_id, this_product_options) == -1 ) { //
				continue;
			}
			if (all_vars.length) {
				for (var k in all_vars) {
					if((all_vars[k] instanceof Function) ) { continue; }
					
					var comb_arr = all_vars[k].slice(0);
					comb_arr[option_id] = option_value_id;
					temp_arr.push( comb_arr );
				}
			} else {
				var comb_arr = [];
				comb_arr[option_id] = option_value_id;
				temp_arr.push(comb_arr);
			}
			
		}
		if (temp_arr && temp_arr.length) {
			all_vars = temp_arr.slice(0);
		}
	}
	
	if (all_vars.length) {
		
		if (product_options_only) {
			var numberOfCombs = all_vars.length;
			if (!confirmNumberOfCombinations(numberOfCombs)) {
				return;
			}
		}
	
		for (var i in all_vars) {
			if((all_vars[i] instanceof Function) ) { continue; }
			
			rop = {};
			for (var j in all_vars[i]) {
				if((all_vars[i][j] instanceof Function) ) { continue; }
				rop[j] = all_vars[i][j];
			}
			
			ro_add_combination(ro_tabs_num, {options: rop});

		}
		
		ro_use_check(ro_tabs_num);
		ro_refresh_status(ro_tabs_num);
		ro_check_defaultselectpriority(ro_tabs_num);
		
	}
	
}

// check priority fields (is it available or not) for default options combination
function ro_check_defaultselectpriority(ro_tabs_num) {
	
	var dsc = $('#tab-ro-'+ro_tabs_num+' input[type=checkbox][id^=defaultselect_]');
	var dsp;
	for (var i=0;i<dsc.length;i++) {
		dsp = $('#defaultselectpriority_'+dsc[i].id.substr(14));
		if (dsp && dsp.length) {
			if (dsc[i].checked) {
				dsp[0].style.display = '';
				if (isNaN(parseInt(dsp[0].value))) {
					dsp[0].value = 0;
				}
				if (parseInt(dsp[0].value)==0) {
					dsp[0].value = "1";
				}
			} else {
				dsp[0].style.display = 'none';
			}
		}
	}
}

function check_max_input_vars() {
	var max_input_vars = <?php echo $max_input_vars; ?>;
	if (max_input_vars && !$('#warning_max_input_vars').length) {
		var input_vars = $('select, input, textarea').length;
		if ( input_vars/max_input_vars*100 > 80 ) {
			var html = '<div class="alert alert-danger" id="warning_max_input_vars"><i class="fa fa-exclamation-circle"></i> <?php echo addslashes($warning_max_input_vars); ?></div>';
			$('div.panel:first').before(html);
		}
	}
}
setInterval(function(){
	check_max_input_vars();
}, 1000);

if (ro_data && ro_settings) {
	for (var i in ro_data) {
		var ro_tabs_num = ro_add_tab(ro_data[i]);
		
		ro_use_check(ro_tabs_num);
		ro_refresh_status(ro_tabs_num);
		ro_check_defaultselectpriority(ro_tabs_num);
		
	}
	
}

//--></script>
<!-- >> Related Options / Связанные опции  -->


	<?= $footer; ?>
		