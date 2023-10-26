<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
          <div class="pull-right">
            <a data-toggle="tooltip" title="Read help guide to learn more about the extension" class="btn btn-warning" target="_blank" href="http://blog.cartbinder.com/documentation/create-awesome-offer-pages-for-opencart-store-with-multiple-features/"><i class="fa fa-question-circle" aria-hidden="true"></i> Read Documentation</a>
            <a data-toggle="tooltip" title="Need Support. Click Here" class="btn btn-primary" target="_blank" href="http://support.cartbinder.com/open.php"><i class="fa fa-life-ring"></i> Support</a>
         </div>
    </div>
  </div>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-salescombopge" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i style="color:red;" class="fa fa-heart" aria-hidden="true"></i> <?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-salescombopge" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
                <?php if($version < 2200) { ?>
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
                <?php } else { ?>
                <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                <?php } ?>
                  <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="salescombopge_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_description; ?>"><?php echo $entry_description; ?></span></label>
                    <div class="col-sm-10">
                      <textarea name="salescombopge_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="<?php if ($ckeditor_enabled == 1) { ?>form-control<?php } else { ?>form-control summernote<?php } ?>"><?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['description'] : ''; ?></textarea>
                      <?php if (isset($error_description[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                   <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-rules<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_rules; ?>"><?php echo $entry_rules; ?></label>
                    <div class="col-sm-10">
                      <textarea name="salescombopge_description[<?php echo $language['language_id']; ?>][rules]" placeholder="<?php echo $entry_rules; ?>" id="input-rules<?php echo $language['language_id']; ?>" class="<?php if ($ckeditor_enabled == 1) { ?>form-control<?php } else { ?>form-control summernote<?php } ?>"><?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['rules'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-message<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_message; ?>"><?php echo $entry_message; ?></label>
                    <div class="col-sm-10">
                      <textarea name="salescombopge_description[<?php echo $language['language_id']; ?>][message]" placeholder="<?php echo $entry_message; ?>" id="input-message<?php echo $language['language_id']; ?>" class="<?php if ($ckeditor_enabled == 1) { ?>form-control<?php } else { ?>form-control summernote<?php } ?>"><?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['message'] : ''; ?></textarea>
                      <label><?php echo $help_tags; ?></label>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="salescombopge_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="salescombopge_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="salescombopge_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($salescombopge_description[$language['language_id']]) ? $salescombopge_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $text_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $salescombopge_store)) { ?>
                        <input type="checkbox" name="salescombopge_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="salescombopge_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $salescombopge_store)) { ?>
                        <input type="checkbox" name="salescombopge_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="salescombopge_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group customergroup">
                <label class="col-sm-2 control-label"><?php echo $text_customergroup; ?></label>
                <div class="col-sm-5">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($customergroups as $customergroup) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($customergroup['customer_group_id'], $customergroupcst)) { ?>
                        <input type="checkbox" name="customergroupcst[]" value="<?php echo $customergroup['customer_group_id']; ?>" checked="checked" />
                        <?php echo $customergroup['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="customergroupcst[]" value="<?php echo $customergroup['customer_group_id']; ?>" />
                        <?php echo $customergroup['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
               <div class="form-group customers">
                  <label class="col-sm-2 control-label" for="input-customer"><span data-toggle="tooltip" title="<?php echo $help_customer; ?>"><?php echo $text_customer; ?></span></label>
                  <div class="col-sm-5">
                    <input type="text" name="customername" value="" placeholder="<?php echo $text_customer; ?>" id="input-customer" class="form-control" />
                    <div id="customerlist" class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach ($customers as $customer) { ?>
                      <div id="customer<?php echo $customer['customer_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $customer['name']; ?>
                        <input type="hidden" name="customers[]" value="<?php echo $customer['customer_id']; ?>" />
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
                 <div class="form-group">
                <label class="col-sm-2 control-label" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($top) { ?>
                      <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                      <?php } else { ?>
                      <input type="checkbox" name="top" value="1" id="input-top" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_keyword; ?></label>
                <div class="col-sm-10">
                <input type="text" id="keyword" name="keyword" value="<?php echo $keyword; ?>" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
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
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
            </div>
             <div class="tab-pane" id="tab-links">
               <div class="form-group customizetheme">
                <label class="col-sm-10 control-label"><?php echo $text_messagedisplay; ?></label>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                  <div id="product-category" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($product_categories as $product_category) { ?>
                    <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
                      <input type="hidden" name="salescombopge_category[]" value="<?php echo $product_category['category_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-related"><span data-toggle="tooltip" title="<?php echo $help_products; ?>"><?php echo $entry_products; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="related" value="" placeholder="<?php echo $entry_products; ?>" id="input-related" class="form-control" />
                  <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($salescombopge_product as $product_related) { ?>
                    <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_related['name']; ?>
                      <input type="hidden" name="salescombopge_product[]" value="<?php echo $product_related['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
               <div class="form-group">
                <label class="col-sm-2 control-label" for="input-bottom"><span data-toggle="tooltip" title="<?php echo $help_bottom; ?>"><?php echo $entry_bottom; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($bottom) { ?>
                      <input type="checkbox" name="bottom" value="1" checked="checked" id="input-bottom" />
                      <?php } else { ?>
                      <input type="checkbox" name="bottom" value="1" id="input-bottom" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-autopopup"><span data-toggle="tooltip" title="<?php echo $help_autopopup; ?>"><?php echo $entry_autopopup; ?></span></label>
                <div class="col-sm-10">
                  <div class="checkbox">
                    <label>
                      <?php if ($autopopup) { ?>
                      <input type="checkbox" name="autopopup" value="1" checked="checked" id="input-autopopup" />
                      <?php } else { ?>
                      <input type="checkbox" name="autopopup" value="1" id="input-autopopup" />
                      <?php } ?>
                      &nbsp; </label>
                  </div>
                </div>
              </div>
              <div class="form-group customizetheme">
                <label class="col-sm-2 control-label colors"><?php echo $text_customize_theme; ?></label>
                <div class="col-sm-2">
                   <label class="control-label"><?php echo $text_backgroundcolor; ?></label>
                <input type="color" id="date" name="backgroundcolor" value="<?php echo $backgroundcolor; ?>" class="form-control" />
                </div>
                <div class="col-sm-2">
                   <label class="control-label"><?php echo $text_fontcolor; ?></label>
                <input type="color" id="date" name="fontcolor" value="<?php echo $fontcolor; ?>" class="form-control" />
                </div>
              </div>
             </div>
            <div class="tab-pane" id="tab-design">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_store; ?></td>
                      <td class="text-left"><?php echo $entry_layout; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="text-left"><?php echo $text_default; ?></td>
                      <td class="text-left"><select name="salescombopge_layout[0]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($salescombopge_layout[0]) && $salescombopge_layout[0] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                          <?php } ?>
                          <?php } ?>
                        </select></td>
                    </tr>
                    <?php foreach ($stores as $store) { ?>
                    <tr>
                      <td class="text-left"><?php echo $store['name']; ?></td>
                      <td class="text-left"><select name="salescombopge_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                          <option value=""></option>
                          <?php foreach ($layouts as $layout) { ?>
                          <?php if (isset($salescombopge_layout[$store['store_id']]) && $salescombopge_layout[$store['store_id']] == $layout['layout_id']) { ?>
                          <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                          <?php } else { ?>
                          <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
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
  <?php if($version < 2200) { ?>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
// $('#input-description<?php echo $language['language_id']; ?>').summernote({
// 	height: 300
// });
// $('#input-rules<?php echo $language['language_id']; ?>').summernote({
//   height: 300
// });
// $('#input-message<?php echo $language['language_id']; ?>').summernote({
//   height: 300
// });
<?php } ?>
<?php } ?>

</script>
<?php if($version >= 2300) { ?>
  <!-- <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> -->
<?php } ?>


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
        CKEDITOR.replace("input-rules<?= $language['language_id']; ?>", { 
          baseHref: "<?= $base_url; ?>", 
          language: "<?= $language['code']; ?>", 
          filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          skin : "<?= $ckeditor_skin; ?>", 
        codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
        CKEDITOR.replace("input-message<?= $language['language_id']; ?>", { 
          baseHref: "<?= $base_url; ?>", 
          language: "<?= $language['code']; ?>", 
          filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?= $token; ?>', 
          skin : "<?= $ckeditor_skin; ?>", 
        codemirror: { theme: "<?= $codemirror_skin; ?>", }, height: 350 });
      <?php } ?> 
    </script>
  <?php } ?>
<?php } ?>
<!-- Enhanced CKEditor -->  
<script type="text/javascript">
  
// Category
$('input[name=\'category\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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

    $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="salescombopge_category[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-category').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});


// Related
$('input[name=\'related\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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

    $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="salescombopge_product[]" value="' + item['value'] + '" /></div>');
  }
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

$('input[name=\'customername\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
     url: 'index.php?route=<?php echo $customerlink; ?>autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['customer_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'customername\']').val('');
   
    $('#customerlist' + item['value']).remove();
    
    $('#customerlist').append('<div id="customer' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="customers[]" value="' + item['value'] + '" /></div>'); 
  }
});

$('#customerlist').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});

</script>
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>
<?php echo $footer; ?>