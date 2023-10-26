<h4 class="le-brand2">By <?php echo $manufacturer; ?></h4>
<h3 class="h3"><?= $product_name; ?></h3>
<h4 class="weight"><?php echo $volume > 1 ? $volume : ''; ?></h4>
<?php if (isset($salescombopgeoffers)) {  
  foreach($salescombopgeoffers as $offer) { 
    echo html_entity_decode($offer['html']); 
  } 
} ?>

<?php if ($review_status) { ?>
  <div class="rating me-rating">
    <p>
      <?php for ($i = 1; $i <= 5; $i++) { ?>
      <?php if ($rating < $i) { ?>
      <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
      <?php } else { ?>
      <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>
      <?php } ?>
      <?php } ?>
      <br>
      (<a href="javascript:;" onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?= $reviews; ?></a>)
    </p>
  </div>
<?php } ?>
<?php if ($price && !$enquiry) { ?>
<ul class="list-unstyled price-wrapper">
  <?php if (!$special) { ?>
  <li>
    <div class="product-price old-prices" ><?= $price; ?></div>
  </li>
  <?php } else { ?>
  <li>
    <div class="product-special-price new-prices"><?= $special; ?></div>
  </li>
  <li><span style="text-decoration: line-through;" class="old-prices"><?= $price; ?></span></li>
  <?php } ?>
  <?php if ($tax) { ?>
  <li class="product-tax-price product-tax" ><?= $text_tax; ?> <?= $tax; ?></li>
  <?php } ?>
  <?php if ($points) { ?>
  <li><?= $text_points; ?> <?= $points; ?></li>
  <?php } ?>
  <?php if ($discounts) { ?>
  <li>
    <hr>
  </li>
  <?php foreach ($discounts as $discount) { ?>
  <li><?= $discount['quantity']; ?><?= $text_discount; ?><?= $discount['price']; ?></li>
  <?php } ?>
  <?php } ?>
</ul>
<?php } ?>

<div class="product-description pd-b30">
  <?= $description; ?>
</div>

<?php if ($attribute_groups): ?>
  <div class="img-attrs">
    <?php foreach ($attribute_groups as $index_1 => $attribute_group): ?>
        <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute): ?>
          <?php if($attribute['name'] == 'Product Image Attribute'): ?>
            <?= html($attribute['text']); ?>
          <?php endif ?>
        <?php endforeach ?>
    <?php endforeach ?>
  </div>
<?php endif ?>

<div class="quantity-wrapper <?= $enquiry || !$not_avail ? '' : 'hidden' ?>">
  <div class="form-group">
    <div class="inner mod-qty">
          <label for="" class="mod-qty-label">Quantity</label>
          <select class="le-select form-control" value="<?= $minimum; ?>" >
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="input-trigger">10+</option>
          </select>
          <input type="text" class="more-than-10 le-select-input hidden form-control" placeholder="10+">
          <script>
            $('.le-select').change(function(){
              let select_val = $(this).val();
              $("#input-quantity").val(select_val);

              if($(this).val() == 'input-trigger'){
                $(this).hide();
                $('.more-than-10').removeClass("hidden");
              $("#input-quantity").val(1);
              }
            })
            $('.more-than-10').on("keyup", function(){
              let ten_input = $(this).val();
              $("#input-quantity").val(ten_input);
            })
          </script>
        <?php if(!$enquiry){ ?>
          <?php if(!$not_avail) { ?>
            <button type="button" id="button-cart" data-loading-text="<?= $text_loading; ?>" class="btn button-cart btn-primary">ADD TO CART</button>
          <?php } ?>
        <?php }else{ ?>
          <button type="button" data-loading-text="<?= $text_loading; ?>" style="cursor: not-allowed" class="btn button-cart btn-primary"><?= $button_cart; ?></button>
        <?php } ?>
    </div>
  </div>
</div>

<?php if($share_html){ ?>
<div class="input-group-flex" style="display: block">
  <span>Share this:</span> 
  <div><?= $share_html; ?></div>
</div>
<?php } ?>

<?= $not_avail ? $waiting_module : ''; ?>

<style>
  .product-description td {
    border: none !important;
  }
</style>