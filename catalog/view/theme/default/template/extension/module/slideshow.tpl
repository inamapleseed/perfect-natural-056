<div id="slideshow<?= $module; ?>" class="relative owl-carousel"  style="opacity: 1; width: 100%;">
  <?php foreach ($banners as $i => $banner) { ?>
    <div class="relative h100">
      <img data-src="<?= $banner['image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive hidden-xs owl-lazy" />
      <img data-src="<?= $banner['mobile_image']; ?>" alt="<?= $banner['title']; ?>" class="img-responsive visible-xs owl-lazy" />
      <?php if($banner['description']){ ?>
        <div class="slider-slideshow-description sd<?php echo $i; ?> w100 absolute position-center-center background-type-<?= $banner['theme']; ?>">
          <div class="container <?= $banner['position']; ?>">
          <!-- title -->

            <?php if($banner['design_image']): ?>
              <img src="image/<?php echo $banner['design_image']; ?>" alt="design" class="design-image">
            <?php endif?>

            <h3 style=" text-align: <?= $banner['sub_textpos'] ? $banner['sub_textpos'] : ''; ?>; font-size: calc(20px + (<?= $banner['title_font'] ? $banner['title_font'] : '22'; ?> - 20) * (100vw - 320px) / (1920 - 320)); "><?= $banner['title']; ?></h3>

            <!-- description con -->
            <div class="slider-slideshow-description-texts <?= $banner['title'] ? $banner['title'] :'no-title'; ?>" style="transform: translateY(<?= $banner['text_adj2'] ? $banner['text_adj2'] : ''; ?>); color: <?= $banner['desc_color'] ? $banner['desc_color'] : 'black'; ?>; left: <?= $banner['text_adj'] ? $banner['text_adj'] : ''; ?>; text-align: <?= $banner['sub_textpos'] ? $banner['sub_textpos'] : ''; ?>">

              <span style="display: flex; flex-direction: column; font-size: calc(13px + (<?= $banner['desc_font'] ? $banner['desc_font'] : '26'; ?> - 13) * (100vw - 320px) / (1920 - 320)) !important;">
                <?= $banner['description']; ?>
              </span>

              <?php if ( $banner['link'] && $banner['link_text'] ) { ?>
              <div class="slider-slideshow-description-link">
                <a href="<?= $banner['link']; ?>" class="btn btn-primary">
                  <?= $banner['link_text']; ?>
                </a>
              </div>
              <!--class:slider-slideshow-description-link-->
              <?php } ?>
            </div>
            <!--class:slider-slideshow-description-texts-->
          </div>
          <!--class:container-->
        </div>
        <!--class:slider-slideshow-description-->
      <?php } ?>
      
      <?php if($banner['link']){ ?>
        <!-- <a href="<?= $banner['link']; ?>" class="block absolute position-left-top w100 h100"></a> -->
      <?php } ?>
      
    </div>
  <?php } ?>
</div>
<?php //include('slideshow_script_slick.tpl'); ?>
<?php include('slideshow_script_owl.tpl'); ?>