<?php echo $header; ?>
<div id="container" class="container j-container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <?php if($totaloffers) { ?>
      <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $totaloffers; ?></div>
      <?php } ?>
      <div class="row main-products product-grid" data-grid-classes="xs-100 sm-50 md-50 lg-33 xl-25 display-both block-button">
        <?php foreach ($salescombopge_info as $offer) { ?>
        <div class="product-offer product-layout col-lg-4 col-md-4 col-sm-6 col-xs-12 product-grid-item xs-100 sm-100 md-100 lg-50 xl-33 display-icon inline-button">

          <div class="gift-thumb product-wrapper">
            <?php if($offer['thumb']) { ?>
            <div id="card-<?php echo $offer['salescombopge_id']; ?>" class="card" style="perspective: 1110px; position: relative; transform-style: preserve-3d;"> 
              <div class="front"> 
              <div class="image"><img src="<?php echo $offer['thumb']; ?>" alt="<?php echo $offer['title']; ?>" title="<?php echo $offer['title']; ?>" class="img-responsive" /></div>
              </div> 
              <div class="back">
              <?php echo html_entity_decode($offer['rules']); ?>
              </div> 
            </div>
            <div>
            <?php } ?>
              <div class="caption">
                <h4><a href="<?php echo $offer['href']; ?>"><?php echo $offer['title']; ?></a></h4>
              </div>
              <div class="button-group">
                <button  type="button" onclick='$("#card-<?php echo $offer['salescombopge_id']; ?>").flip("toggle");'><?php echo $button_viewdetails; ?></button>
                <a href="<?php echo $offer['href']; ?>"><?php echo $button_viewmore; ?></a>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript">
 <?php foreach ($salescombopge_info as $offer) { ?>
$("#card-<?php echo $offer['salescombopge_id']; ?>").flip();
<?php } ?>
</script>
<?php echo $footer; ?>