<div class="featured-module featured_<?= $uqid; ?>">
  <div class="my-heading">
    <h2 class="">
      <?= nl2br($heading_title); ?>
    </h2>
    <p class="p-tured-p"><?= $mydescription; ?></p>
    <a href="<?php echo $myurl; ?>" class="btn btn-primary"><?php echo $myurl_title; ?></a>

  </div>

  <div class="featured section relative" style="opacity: 0;">
    <div id="featured_slider_<?= $uqid; ?>" >
      <?php foreach ($products as $product) { ?>
        <?= html($product); ?>
      <?php } ?>
    </div>

    <script>
      $(".indict").removeClass('pi-con');
    </script>
    <script type="text/javascript">

      $(window).load(function(){
        setTimeout(function () {
          featured_product_slick<?= $uqid; ?>();
          AOS.init();
        }, 250);
      });

      function featured_product_slick<?= $uqid; ?>(){
        $("#featured_slider_<?= $uqid; ?>").on('init', function (event, slick) {
          $('#featured_slider_<?= $uqid; ?>').parent().removeAttr('style');
        });

        $("#featured_slider_<?= $uqid; ?>").slick({
          dots: false,
          infinite: true,
          speed: 300,
          slidesToShow: 2,
          centerMode: true,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 1201,
              settings: {
                slidesToShow: 2,
              }
            },
            {
              breakpoint: 993,
              settings: {
                slidesToShow: 1,
              }
            },
            {
              breakpoint: 769,
              settings: {
                slidesToShow: 1,
              }
            },
            {
              breakpoint: 767,
              settings: {
                slidesToShow: 1,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 5000
              }
            }
          ],
          prevArrow: "<div class='pointer slick-nav left prev absolute'><div class='absolute position-center-center'><img src='image/catalog/slicing/general/prev.png' alt='<'/></div></div>",
          nextArrow: "<div class='pointer slick-nav right next absolute'><div class='absolute position-center-center'><img src='image/catalog/slicing/general/next.png' alt='<'/></i></div></div>",
        });

        
      }
    </script>
  </div>
</div>
