<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-gallimage" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-gallimage" class="form-horizontal">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li class="hidden"><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
            <li><a href="#tab-gallery" data-toggle="tab"><?php echo $tab_gallery; ?></a></li>
          </ul>
        <div class="tab-content">  
         <div class="tab-pane active" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>          
              <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="gallimage_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($gallimage_description[$language['language_id']]) ? $gallimage_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="gallimage_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($gallimage_description[$language['language_id']]) ? $gallimage_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="gallimage_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($gallimage_description[$language['language_id']]) ? $gallimage_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_meta_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="gallimage_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($gallimage_description[$language['language_id']]) ? $gallimage_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                    <div class="col-sm-10">
                      <textarea name="gallimage_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($gallimage_description[$language['language_id']]) ? $gallimage_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
                </div>
                </div>
          <div class="tab-pane fade" id="tab-data">      
          <div class="form-group">
             <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
             <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
             <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
             </div>
          </div>
          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_size; ?></label>
          <div class="col-sm-5">
          <input type="text" name="awidth" value="<?php echo $awidth; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
          </div>
          <div class="col-sm-5">
          <input type="text" name="aheight" value="<?php echo $aheight; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
          </div>
          </div>
          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-position"><?php echo $entry_position; ?></label>
          <div class="col-sm-10">
          <select name="position" class="form-control">
                <?php if (isset($position)) { $selected = "selected"; ?>
                <option value="left" <?php if($position=='left'){echo $selected;} ?>>Left</option>
                <option value="right" <?php if($position=='right'){echo $selected;} ?>>Right</option>
                <option value="center" <?php if($position=='center'){echo $selected;} ?>>Center</option>
                <?php } else { ?>
                <option value="left" selected="selected">Left</option>
                <option value="right">Right</option>
                <option value="center">Center</option>
                <?php } ?>
              </select>
          </div>
          </div>      
          <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $gallimage_store)) { ?>
                        <input type="checkbox" name="gallimage_store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="gallimage_store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $gallimage_store)) { ?>
                        <input type="checkbox" name="gallimage_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="gallimage_store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-keyword"><span data-toggle="tooltip" title="<?php echo $help_keyword; ?>"><?php echo $entry_keyword; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
                </div>
              </div>
          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_gsize; ?></label>
          <div class="col-sm-5">
          <input type="text" name="gwidth" value="<?php echo $gwidth; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
          </div>
          <div class="col-sm-5">
          <input type="text" name="gheight" value="<?php echo $gheight; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
          </div>
          </div>
          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-popstyle"><?php echo $entry_thumbstyle; ?></label>
          <div class="col-sm-10">
          <select name="thumbstyle" class="form-control">
              <?php if ($thumbstyle == 'style1') { ?>
              <option value="style1" selected="selected">Style 1</option>
              <?php } else { ?>
              <option value="style1">Style 1</option>
              <?php } ?>
              <?php if ($thumbstyle == 'style2') { ?>
              <option value="style2" selected="selected">Style 2</option>
              <?php } else { ?>
              <option value="style2">Style 2</option>
              <?php } ?>
              <?php if ($thumbstyle == 'style3') { ?>
              <option value="style3" selected="selected">Style 3</option>
              <?php } else { ?>
              <option value="style3">Style 3</option>
              <?php } ?>
          </select>      
          </div>
          </div>
          <div class="form-group">
          <label class="col-sm-2 control-label" for="input-popstyle"><?php echo $entry_popstyle; ?></label>
          <div class="col-sm-10">
          <select name="popstyle" class="form-control">
                <?php if (isset($popstyle)) { $selected = "selected"; ?>
                <?php /* ?>
                <option value="default" <?php if($popstyle=='default'){echo $selected;} ?>>MagnificPopup (Default)</option>
                <option value="blueimp" <?php if($popstyle=='blueimp'){echo $selected;} ?>>Bootstrap - blueimp</option>
                <?php */ ?>
                <option value="lightgall" <?php if($popstyle=='lightgall'){echo $selected;} ?>>LighBox</option>
                <?php } else { ?>
                <?php /* ?>
                <option value="default" selected="selected">Default</option>
                <option value="blueimp">Bootstrap - blueimp</option>
                <?php */ ?>
                <option value="lightgall">LighBox</option>
                <?php } ?>
                </select>
          </div>
          </div> 
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-resize"><?php echo $entry_resize; ?></label>
            <div class="col-sm-10">
              <select name="resize" id="input-resize" class="form-control">
                <?php if ($resize) { ?>
                <option value="1" selected="selected">Resize</option>
                <option value="0">Do Not Resize</option>
                <?php } else { ?>
                <option value="1">Resize</option>
                <option value="0" selected="selected">Do Not Resize</option>
                <?php } ?>
              </select>  
            </div>
            </div>         
          <div id="presize" class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_psize; ?></label>
          <div class="col-sm-5">
          <input type="text" name="pwidth" value="<?php echo $pwidth; ?>" placeholder="<?php echo $entry_width; ?>" class="form-control" />
          </div>
          <div class="col-sm-5">
          <input type="text" name="pheight" value="<?php echo $pheight; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
          </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-imgperrow"><?php echo $entry_imgperrow; ?></label>
            <div class="col-sm-10">
              <select name="imgperrow" class="form-control">
                <?php if (isset($imgperrow)) { $selected = "selected"; ?>
                <option value="6" <?php if($imgperrow=='6'){echo $selected;} ?>>2</option>
                <option value="4" <?php if($imgperrow=='4'){echo $selected;} ?>>3</option>
				<option value="3" <?php if($imgperrow=='3'){echo $selected;} ?>>4</option>
                <option value="5" <?php if($imgperrow=='5'){echo $selected;} ?>>5</option>
                <option value="2" <?php if($imgperrow=='2'){echo $selected;} ?>>6</option>
                <?php } else { ?>
                <option selected="selected"><?php echo $text_pleaseselect; ?></option>
                <option value="6" selected="selected">2</option>
                <option value="4">3</option>
				<option value="3">4</option>
                <option value="5">5</option>
                <option value="2">6</option>
                <?php } ?>
              </select>
          </div>
          </div> 
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-gallpage"><?php echo $entry_gallpage; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="gallpage" value="<?php echo $gallpage; ?>" placeholder="<?php echo $entry_gallpage; ?>" id="input-gallpage" class="form-control" />
                </div>
              </div>     
          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
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
          </div>
          <div class="tab-pane" id="tab-gallery">     
          <table id="images" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_title; ?></td>
                <td class="text-left"><?php echo $entry_link; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $image_row = 0; ?>
              <?php foreach ($gallimage_images as $gallimage_image) { ?>
              <tr id="image-row<?php echo $image_row; ?>">
                <td class="text-left" style="min-width:120px;"><?php foreach ($languages as $language) { ?>
                  <div class="input-group pull-left"><span class="input-group-addon">
                      <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> 
                      </span>
                    <input type="text" name="gallimage_image[<?php echo $image_row; ?>][gallimage_image_description][<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($gallimage_image['gallimage_image_description'][$language['language_id']]) ? $gallimage_image['gallimage_image_description'][$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_gallimage_image[$image_row][$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_gallimage_image[$image_row][$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?></td>
                <td class="text-left" style="width: 30%;min-width:80px;"><input type="text" name="gallimage_image[<?php echo $image_row; ?>][link]" value="<?php echo $gallimage_image['link']; ?>" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>
                <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $gallimage_image['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="gallimage_image[<?php echo $image_row; ?>][image]" value="<?php echo $gallimage_image['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                <td class="text-right" style="width: 10%;min-width:60px;"><input type="text" name="gallimage_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $gallimage_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $image_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4"></td>
                <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_gallimage_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>
//--></script>
  <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
	html  = '<tr id="image-row' + image_row + '">';
    html += '  <td class="text-left">';
	<?php foreach ($languages as $language) { ?>
	html += '    <div class="input-group">';                                         
	html += '      <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="gallimage_image[' + image_row + '][gallimage_image_description][<?php echo $language['language_id']; ?>][title]" value="" placeholder="<?php echo $entry_title; ?>" class="form-control" />';    
    html += '    </div>';
	<?php } ?>
	html += '  </td>';	
	html += '  <td class="text-left" style="width: 30%;min-width:80px;"><input type="text" name="gallimage_image[' + image_row + '][link]" value="" placeholder="<?php echo $entry_link; ?>" class="form-control" /></td>';	
	html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="gallimage_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
	html += '  <td class="text-right" style="width: 10%;min-width:60px;"><input type="text" name="gallimage_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#images tbody').append(html);
	
	image_row++;
}
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
<script type="text/javascript"><!--
$('select[name=\'resize\']').on('change', function() {
	if (this.value == '1') {
		$('#presize').show();
	} else  {
        $('#presize').hide();
	}
});
$('select[name=\'resize\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$(function() {
    $('body').on('change', '#input-all-products', function() {
        if ($(this).is(":checked")) {
            $("#input-product").prop("disabled", true);
            $("#gallimage-products input").each(function(index){
                $(this).prop("disabled", true);
            });
        } else {
            $("#input-product").prop("disabled", false);
            $("#gallimage-products input").each(function(index){
                $(this).prop("disabled", false);
            });
        }
    }).on('click', '#gallimage-products .fa-minus-circle', function() {
        $(this).parent().remove();
    });

    $('input[name=\'product\']').autocomplete({
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
            $('input[name=\'product\']').val('');
            $('#gallimage-product' + item['value']).remove();
            $('#gallimage-products').append('<div id="gallimage-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="linked_products[]" value="' + item['value'] + '" /></div>');
        }
    });
})
//--></script>
<?php echo $footer; ?>