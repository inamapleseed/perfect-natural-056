<?php foreach ($medias as $media) { ?>
  <div class="col-xs-3 col-md-2 js-media-item js-media-item-<?php echo $media['shortcode']; ?>">
    <div data-toggle="tooltip" title="<?php echo $text_media_manage; ?>">
      <img src="<?php echo $media['media_image']; ?>" class="img-responsive" style="cursor:pointer;min-height:60px;" data-toggle="modal" data-target="#js-media-modal" data-isl-media='<?php echo $media['data']; ?>'>
      <?php if ($media['resave']) { ?>
        <span class="label label-warning cursor-pointer label-resave-<?php echo $media['shortcode']; ?>" style="position:absolute;top:10px;left:8px;"><?php echo $text_resave; ?></span>
      <?php } ?>
      <?php if ($media['legacy']) { ?>
        <span class="label label-warning cursor-pointer label-legacy-<?php echo $media['shortcode']; ?>" style="position:absolute;top:10px;left:8px;"><?php echo $text_legacy; ?></span>
      <?php } ?>
    </div>
    <div class="text-center" style="margin-top:5px">
      <span class="label label-default js-media-approve-<?php echo $media['shortcode']; ?>" data-toggle="tooltip" title="<?php echo $text_approval_status; ?>"><?php echo $media['approve'] ? $text_yes : $text_no; ?></span>
      <span class="label label-primary js-media-relproduct-<?php echo $media['shortcode']; ?>" data-toggle="tooltip" title="<?php echo $text_related_product; ?>" style="padding-left:8px;padding-right:8px;"><?php echo $media['product_count']; ?></span>
      <span class="label label-danger js-media-remove-<?php echo $media['shortcode']; ?> cursor-pointer" data-isg-remove="<?php echo $media['shortcode']; ?>" data-toggle="tooltip" title="<?php echo $text_remove_db; ?>" style="padding-left:8px;padding-right:8px;<?php echo !$media['remove'] ? 'display:none' : ''; ?>">x</span>
    </div>
  </div>
<?php } ?>
