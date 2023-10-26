<?php echo $header; ?>
<div class="container isl-instagramphotos islip-page th-isl-<?php echo $theme; ?> <?php echo $theme == 'journal2' ? 'j-container' : ''; ?>" <?php echo $theme == 'journal2' ? 'id="container"' : ''; ?>>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <div class="row">
    <?php echo $column_left; ?>

    <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'?>
    <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'?>
    <?php } else { ?>
      <?php $class = 'col-sm-12'?>
    <?php }?>

    <div id="content" class="<?php echo $class; ?>">
      <?php echo $content_top; ?>

      <h1 class="<?php echo $theme == 'journal2' ? 'heading-title' : ''; ?>"><?php echo $setting['page']['title'][$lang_id]; ?></h1>

      <div class="clearfix">
        <?php if ($banner) { ?>
          <?php if ($setting['page']['banner_link']) { ?>
            <a href="<?php echo $setting['page']['banner_link']; ?>" target="_blank">
              <img src="<?php echo $banner; ?>" alt="<?php echo $setting['page']['title'][$lang_id]; ?>" class="img-responsive">
            </a>
          <?php } else { ?>
            <img src="<?php echo $banner; ?>" alt="<?php echo $setting['page']['title'][$lang_id]; ?>" class="img-responsive">
          <?php } ?>
        <?php } ?>

        <div class="islip-gutter-5px clearfix js-islip-page-container"></div>

        <div class="js-photos-notification"></div>
        <div class="text-center">
          <a class="btn btn-default btn-info btn-sm js-fetch-more" data-isl-fetch>Load more</a>
        </div>
      </div>

<?php if (!empty($custom_css)) { ?>
<style>
  <?php echo $custom_css; ?>
</style>
<?php } ?>
<script>
$(document).ready(function()
{
  // Get photos
  fetchPhotos();
  $('[data-isl-fetch]').on('click', function() {
    fetchPhotos();
  });

  // On addCart
  $('body').on('click', '[data-isg-add-cart]', function() {
    var product_id = $(this).data('isg-add-cart');

    if (product_id) {
      <?php if ($theme == 'journal2') { ?>
        addToCart(product_id);
      <?php } else { ?>
        cart.add(product_id);
      <?php } ?>

      setTimeout(function() {
        $.magnificPopup.close();
      }, 1000);
    }
  });
});

function fetchPhotos() {
  var page = $('[data-isl-fetch]').data('isl-fetch');

  $.ajax({
    url: 'index.php?route=<?php echo $module['path']; ?>/fetch&_='+ new Date().getTime(),
    type: 'POST',
    dataType: 'json',
    cache: false,
    data: {
      type :"page",
      page : page
    },
    beforeSend: function() {
      $('.js-photos-notification').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Processing..</div>');
    },
    success: function(data) {
      $('.js-photos-notification').html('');
      $('.islip-page .js-islip-page-container').append(data.output);

      if (data.page.show) {
        $('.js-fetch-more').fadeIn();
        $('.js-fetch-more').data('isl-fetch', data.page.next);
      } else {
        $('.js-fetch-more').fadeOut();
      }

      photoGallery();
    }
  });
}

function photoGallery() {
  $('.js-islip-page-container').magnificPopup({
    delegate: '.isl-photo-gallery',
    type: 'ajax',
    gallery: {
      enabled: true,
      tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
    },
    showCloseBtn: true,
    callbacks: {
      open: function() {
        $('html').addClass('mfpip-open');
      },
      close: function() {
        $('html').removeClass('mfpip-open');
      }
    }
  });
}
</script>

      <?php echo $content_bottom; ?>
    </div>

    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
