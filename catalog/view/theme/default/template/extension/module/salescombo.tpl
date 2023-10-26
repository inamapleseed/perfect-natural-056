<?php foreach ($offers as $offernumber) { ?> 
<?php foreach ($offernumber as $offer) { ?>
<div class="<?php echo $offer['id'] ?> availableofferblock">
<h3 class="offerheading"><?php echo $offer['name']; ?></h3>
<?php if(isset($offer['offerpage']) && $offer['offerpage']) { ?>
<label><?php echo $offer['offerpage']; ?></label>
<?php } ?>
<div class="offerhexagon"><span><?php echo $offer['offervalue']; ?></span></div>
<div class="addproducts">
<?php foreach ($offer['addproducts'] as $key => $product) { ?>
<input type="hidden" name="offer_productid" value="<?php echo $product['product_id']; ?>" id="<?php echo $product['priqty']; ?>">
<?php if($key) { ?>
<div class="product-layout col-xsm-1 col-xxs-1 plussign">
<i class="fa fa-plus" aria-hidden="true"></i>
</div>
<?php } ?>
<div class="product-layout col-xlg-2 col-xsm-3 col-xxs-4">
  <div class="gift-thumb transition">
    <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
      <span class="qtyoffer"><?php echo $addqty; ?> <?php echo $product['priqty']; ?></span>
      <?php if(isset($product['price']) && $product['price']) { ?>
      <span class="availableprice"><?php echo $product['price']; ?></span>
       <?php } ?>
      <?php if(isset($product['optionnames']) && $product['optionnames']) { ?>
       <br><?php echo $foroptions; ?><br><?php echo $product['optionnames']; ?>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
<?php if(isset($offer['getproducts'])) { ?>
<?php foreach ($offer['getproducts'] as $key => $product) { ?>
<input type="hidden" name="offer_productid" value="<?php echo $product['product_id']; ?>" id="<?php echo $product['secqty']; ?>">
<div class="product-layout col-xsm-1 col-xxs-1 plussign">
<i class="fa fa-plus" aria-hidden="true"></i>
</div>
<div class="product-layout col-xlg-2 col-xsm-3 col-xxs-4">
  <div class="gift-thumb transition">
    <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
      <span class="qtyoffer"><?php echo $getqty; ?> <?php echo $product['secqty']; ?></span>
      <?php if(isset($product['price']) && $product['price'] && isset($product['discount']) && $product['discount']) { ?>
      <span class="price-new"><?php echo $product['discount']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
       <?php } ?>
      <?php if(isset($product['optionnames']) && $product['optionnames']) { ?>
       <br><?php echo $foroptions; ?><br><?php echo $product['optionnames']; ?>
      <?php } ?>
    </div>
    <div class="offerblockproduct"><span class="offerblockvalue"><?php echo $offertag; ?></span></div>
  </div>
</div>
<?php } ?>
<?php } ?>
<?php if(isset($offer['getcategories'])) { ?>
<?php foreach ($offer['getcategories'] as $key => $category) { ?>
<div class="product-layout col-xsm-1 col-xxs-1 plussign">
<i class="fa fa-plus" aria-hidden="true"></i>
</div>
<div class="product-layout col-xlg-2 col-xsm-3 col-xxs-4">
  <div class="gift-thumb transition">
    <div class="image"><a href="<?php echo $category['href']; ?>"><img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" class="img-responsive" /></a>
    <span class="qtyoffer"><?php echo $getqty; ?> <?php echo $category['secqty']; ?></span>
    </div>
    <div class="offerblockproduct"><span class="offerblockvalue"><?php echo $offertag; ?></span></div>
  </div>
</div>
<?php } ?>
<?php } ?>
</div>
<?php if(isset($offer['totalsavings']) && $offer['totalsavings']) { ?>
  <div class="savingblock">
  Total Price For Bundle: <span class="price-old"><?php echo $offer['totalvalue']; ?></span><br>
  Available In: <span class="price-new"><?php echo $offer['availableprice']; ?></span><br>
  You Save: <span><?php echo $offer['totalsavings']; ?></span><br>
</div>
<?php } ?>
<?php if(isset($offer['bundle']) && $offer['bundle']) { ?>
<button type="button" onclick="offer('<?php echo $offer['id'] ?>');" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> <?php echo $button_bundle; ?></button>
<script type="text/javascript">
function offer(id) {
  $(".availableofferblock."+id + " input[name=offer_productid]").each(function() {
    cl(1);
    cart.add($(this).val(),$(this).attr("id"));
  });
};
</script>
<?php } ?>
</div>
<?php } ?>
<?php } ?>