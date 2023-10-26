<?php foreach ($photos['items'] as $photo) { ?>
  <?php if (!$photo['is_extra']) { ?>
    <div class="islip-photo-item col-xs-6 col-sm-3 col-md-2">
      <a href="index.php?route=<?php echo $module['path']; ?>/modal&<?php echo http_build_query($photo, '', '&'); ?>" class="isl-photo-gallery">
        <img src="<?php echo $photo['media_image']; ?>" class="img-responsive">
      </a>
    </div>
  <?php } else { ?>
    <div class="islip-photo-item col-xs-6 col-sm-3 col-md-2">
      <a href="<?php echo $photo['url']; ?>" target="_blank">
        <img src="<?php echo  $photo['image_thumb']; ?>" class="img-responsive">
      </a>
    </div>
  <?php } ?>
<?php } ?>
