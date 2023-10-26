<div class="isl-instagramphotos islip-modal-dialog th-isl-<?php echo $theme; ?>">
  <div class="row">

    <div class="col-md-7 uk-text-center">
      <a href="https://www.instagram.com/p/<?php echo $photo['shortcode']; ?>/" target="_blank">
        <img src="<?php echo $photo['media_url']; ?>" class="img-responsive">
      </a>
    </div>

    <div class="col-md-5">

      <div class="islip-modal-info">
        <?php if ($photo['username']) { ?>
          <h3 class="photo-ig-username">
            <i class="fa fa-instagram"></i>
            <a href="https://www.instagram.com/<?php echo $photo['username']; ?>" target="_blank"><?php echo $photo['username']; ?></a>
          </h3>
        <?php } ?>
        <?php if ($photo['date']) { ?>
          <p class="photo-ig-info">
            <span style="margin-right:15px"><?php echo $photo['date']; ?></span>
          </p>
        <?php } ?>

        <div class="photo-ig-caption caption-truncate">
          <p><?php echo $photo['caption']; ?></p>
          <div class="photo-ig-caption-overlay">
            <span class="photo-ig-caption-more label label-primary"><?php echo $text_more; ?></span>
            <span class="photo-ig-caption-less label label-success"><?php echo $text_less; ?></span>
          </div>
        </div>

        <?php if ($products) { ?>
          <hr>
          <div class="swiper-viewport islip-related-product">
            <div id="islip-swiper-<?php echo $photo['shortcode']; ?>" class="islip-gutter-5px swiper-container">

              <div class="<?php echo $carousel ? 'swiper-wrapper' : ''; ?>">
                <?php foreach ($products as $product) { ?>
                  <div class="<?php echo $carousel ? 'swiper-slide' : 'col-xs-4'; ?>">
                    <div class="product-thumb">
                      <div class="image">
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
                      </div>
                      <div class="text-center">
                        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>

                        <?php if ($product['price']) { ?>
                          <div class="price">
                            <?php if (!$product['special']) { ?>
                              <?php echo $product['price']; ?>
                            <?php } else { ?>
                              <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                            <?php } ?>
                          </div>
                        <?php } ?>

                        <button type="button" class="btn btn-default <?php echo $theme == 'journal2' ? 'button' : 'btn-isg'; ?>" data-isg-add-cart="<?php echo $product['product_id']; ?>"><?php echo $button_cart; ?></button>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>

            </div>

            <?php if ($carousel) { ?>
              <div class="swiper-pagination islip-swiper-<?php echo $photo['shortcode']; ?>"></div>
              <div class="swiper-pager" hidden>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>

    </div>
  </div>

<script>
  var captionHeight = $('.photo-ig-caption p').height();

  if (captionHeight < 100) {
    $('.photo-ig-caption').removeClass('caption-truncate');
  }
  $('.photo-ig-caption-overlay .label').on('click', function() {
    $('.photo-ig-caption').toggleClass('caption-expand');
  });

<?php if ($carousel) { ?>
  $('#islip-swiper-<?php echo $photo['shortcode']; ?>').swiper({
    loop: true,
    autoplay: 2500,
    mode: 'horizontal',
    slidesPerView: 3,
    centeredSlides: true,
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    pagination: '.islip-swiper-<?php echo $photo['shortcode']; ?>',
    paginationClickable: true,
  });
<?php } ?>
</script>
</div>
