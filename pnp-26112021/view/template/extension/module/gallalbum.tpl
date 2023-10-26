<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-gallalbum" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-gallalbum" class="form-horizontal"> 
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_gallimage; ?>"><?php echo $entry_gallimage; ?></span></label>
            <div class="col-sm-10">                  
              <input type="text" name="gallimage" value="" placeholder="<?php echo $entry_gallimage; ?>" id="input-gallimage" class="form-control" />
              <div id="selected-album" class="well well-sm" style="height: 150px; overflow: auto; margin-bottom:4px;">
                <?php foreach ($gallimages as $gallimage) { ?>
                <div id="selected-album<?php echo $gallimage['gallimage_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $gallimage['name']; ?>
                  <input type="hidden" name="gallimage[]" value="<?php echo $gallimage['gallimage_id']; ?>" />
                </div>
                <?php } ?>
              </div>
              <span class="help" style="color:green;">Click and drag the album item to reorder</span>    
            </div>
          </div>   
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_showimg; ?></label>
            <div class="col-sm-10">
              <select name="showimg" class="form-control">
                <?php if ($showimg) { ?>
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
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php foreach ($languages as $language) { ?>  
          <div class="form-group">
            <?php if (version_compare(VERSION, '2.2.0.0', '>=')) { ?>   
            <label class="col-sm-2 control-label" for="input-headtitle"><?php echo $entry_headtitle; ?>&nbsp;<img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></label>
            <?php } else { ?>
            <label class="col-sm-2 control-label" for="input-headtitle"><?php echo $entry_headtitle; ?>&nbsp;<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></label>  
            <?php } ?>               
            <div class="col-sm-10">
              <input type="text" name="headtitle_<?php echo $language['language_id']; ?>" value="<?php echo isset(${'headtitle_' . $language['language_id']}) ? ${'headtitle_' . $language['language_id']} : ''; ?>" placeholder="<?php echo $entry_headtitle; ?>" id="input-headtitle" class="form-control" />
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-descstat"><?php echo $entry_descstat; ?></label>
            <div class="col-sm-10">
              <select name="descstat" class="form-control">
                <?php if ($descstat) { ?>
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
            <label class="col-sm-2 control-label" for="input-chardesc"><?php echo $entry_chardesc; ?></label>
            <div class="col-sm-10">
            <?php if(empty($chardesc)) { $chardesc = "200"; } ?>
              <input type="text" name="chardesc" value="<?php echo $chardesc; ?>" placeholder="<?php echo $entry_chardesc; ?>" id="input-chardesc" class="form-control" />
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-thumblist"><?php echo $entry_thumblist; ?></label>
              <div class="col-sm-10">
              <select name="thumblist" id="input-thumblist" class="form-control">
                  <?php if ($thumblist == 'style1') { ?>
                  <option value="style1" selected="selected">Style 1</option>
                  <?php } else { ?>
                  <option value="style1">Style 1</option>
                  <?php } ?>
                  <?php if ($thumblist == 'style2') { ?>
                  <option value="style2" selected="selected">Style 2</option>
                  <?php } else { ?>
                  <option value="style2">Style 2</option>
                  <?php } ?>
                  <?php if ($thumblist == 'style3') { ?>
                  <option value="style3" selected="selected">Style 3</option>
                  <?php } else { ?>
                  <option value="style3">Style 3</option>
                  <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" for="input-titlepos"><?php echo $entry_titlepos; ?></label>
              <div class="col-sm-10">
              <select name="titlepos" class="form-control gall">
                <?php if ($titlepos == 'left') { ?>
                <option value="left" selected="selected">Left</option>
                <?php } else { ?>
                <option value="left">Left</option>
                <?php } ?>
                <?php if ($titlepos == 'right') { ?>
                <option value="right" selected="selected">Right</option>
                <?php } else { ?>
                <option value="right">Right</option>
                <?php } ?>
                <?php if ($titlepos == 'center') { ?>
                <option value="center" selected="selected">Center</option>
                <?php } else { ?>
                <option value="center">Center</option>
                <?php } ?>
              </select>
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
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--    
$('input[name=\'gallimage\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/gallimage/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['gallimage_id']
          }
        }));
      }
    });
  },
  select: function(item) {
    $('input[name=\'gallimage\']').val('');
    
    $('#selected-album' + item['value']).remove();
    
    $('#selected-album').append('<div id="selected-album' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="gallimage[]" value="' + item['value'] + '" /></div>');  
  }
});
  
$('#selected-album').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
}); 
//--></script>
<style type="text/css">
.ui-sortable-handle {cursor: pointer;}
</style>
<link rel="stylesheet" href="view/javascript/jquery/gallery/jquery-ui.css">
<script src="view/javascript/jquery/gallery/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function() {
  $('#selected-album').sortable();
});
</script>
<?php echo $footer; ?>