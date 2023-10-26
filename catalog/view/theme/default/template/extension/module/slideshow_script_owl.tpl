<script type="text/javascript">
    // Note.. it supports Animate.css
    // Manual Slider don't support animate css
    $(window).load(function(){
        $('#slideshow<?= $module; ?>').owlCarousel({
            items: 1,
            <?php if (count($banners) > 1) { ?>
                loop: true,
            <?php } ?>

            <?php if($autoplayspeed != 0){ ?>
                autoplay: false,
                autoplayTimeout: <?= $autoplayspeed; ?>,
            <?php }else{ ?>
                autoplay: false,
            <?php } ?>
            
            smartSpeed: 450,
            
            nav: <?= $arrows; ?>,
            navText: ['<div class="pointer absolute position-top-left h100 slider-nav slider-nav-left hover-show"><div class="absolute position-center-center"><i class="fa fa-chevron-left fa-2em"></i></div></div>', '<div class="pointer absolute position-top-right h100 slider-nav slider-nav-right hover-show"><div class="absolute position-center-center"><i class="fa fa-chevron-right fa-2em"></i></div></div>'],
            
            dots: <?= $dots; ?>,
            dotsClass: 'slider-dots slider-custom-dots absolute position-bottom-left w100 list-inline text-center',

            lazyLoad: true,
            
            //animateOut: 'slideOutDown',
            //animateIn: 'slideInDown',
        });
    });
</script>