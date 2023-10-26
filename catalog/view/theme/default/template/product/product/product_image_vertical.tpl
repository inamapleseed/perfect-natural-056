<?php if($images){ ?>
<div class="product-image-column flex vertical">
   <div class="product-image-additional-container related">
    <div class="product-image-additional">
      <?php foreach($additional_images as $k => $image){ ?>
      <img 
      src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" title="<?= $heading_title; ?>" class="pointer" />
      <?php } ?>
    </div>
  </div>
  <div class="product-image-main-container related relative">
    <?php if($sticker && $sticker['name']){ ?>
      <a 
      title="<?= $product_title; ?>" 
      class="sticker absolute <?= isset($sticker['image']) && $sticker['image'] ? 'sticker-image':''; ?>" 
      style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
          <?php if(isset($sticker['image']) && $sticker['image']){ ?>
              <img src="<?= $sticker['image'] ?>" />
          <?php } else { 
              echo $sticker['name']; 
          } ?>
      </a>
      <?php } ?>
      <?php if($show_special_sticker && !$out_sticker){ ?>
      <a 
      title="<?= $product_title; ?>" 
      class="sticker absolute" 
      style="top:<?= $sticker ? '30px' : '10px' ?>; color: #fff; background-color: #D41700;">
        <?= $text_sale; ?>
      </a>
    <?php } ?>
    <div class="product-image-main">
      <?php foreach($images as $j => $image){ ?>
          <img data-thumb="<?php echo $image['thumb']; ?>"
            data-fancybox="gal<?php echo $product_id; ?>" 
            src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" 
            class="m_image pointer" href="<?= $image['popup']; ?>" title="<?= $heading_title; ?>"
            data-zoom-image="<?= $image['zoom']; ?>"
          />
      <?php } ?>
    </div>
      <div class="le-zoom " ><i class="fa fa-search-plus"></i>&nbsp; Click to expand</div>
  </div>
</div>
<?php } ?>
<script>
    let width = $(window).width();
    if(width > 768){
        $('[data-fancybox]').fancybox({
          thumbs : {
            autoStart   : true
          },
        });
    }
</script>
<style>
    .fancybox-thumbs.fancybox-thumbs-y {
        left: inherit;
    }
    .fancybox-inner {
        right: 0 !important;
    }
</style>