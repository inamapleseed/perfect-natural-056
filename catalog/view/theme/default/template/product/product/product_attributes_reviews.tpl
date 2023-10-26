<?php if ($attribute_groups || $review_status || isset($available_offers['tab']) && $available_offers['tab'] ) { ?>
<ul id="product-tabs" class="nav nav-tabs">
  <!-- <?php if(isset($available_offers['tab']) && $available_offers['tab']) { ?>
  <li><a href="#tab-available-offers" data-toggle="tab"><?php echo $tab_available_offers; ?></a></li>
  <?php } ?> -->

  <?php if ($attribute_groups) { ?>
    <?php foreach ($attribute_groups as $index_1 => $attribute_group) { ?>
      <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute) { ?>
        <?php if($attribute['name'] != 'Product Image Attribute'): ?>
          <button class="accordion">
            <div>
              <a href="#tab-attribute-<?= $index_1; ?>-<?= $index_2; ?>" data-toggle="tab"><?= $attribute['name']; ?></a>
              <span><?= $attribute['sub_title']; ?></span>
            </div>
            <i class="fa fa-angle-down"></i>
          </button>
          
          <div class="panel" style="box-shadow: none !important;">
              <?= html($attribute['text']); ?>
          </div>
        <?php endif ?>
          
      <?php } ?>
    <?php } ?>
  <?php } ?>
  
  <?php if ($review_status) { ?>
    <button class="accordion">
      <div>
        <a href="#tab-review" data-toggle="tab"><?= $tab_review; ?></a>
        <span><?= $attribute['sub_title']; ?></span>
      </div>
      <i class="fa fa-angle-down"></i>
    </button>

    <div class="panel" style="box-shadow: none !important;">
        <form class="form-horizontal" id="form-review">
          <div id="review"></div>
          <h3><?= $text_write; ?></h3>
          <?php if ($review_guest) { ?>
            <?php if ( $purchased ) { ?>
              <div class="form-group required">
                <div class="col-sm-12">
                  <label class="control-label" for="input-name"><?= $entry_name; ?></label>
                  <input type="text" name="name" value="<?= $customer_name; ?>" id="input-name" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <div class="col-sm-12">
                  <label class="control-label" for="input-review"><?= $entry_review; ?></label>
                  <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                  <div class="help-block"><?= $text_note; ?></div>
                </div>
              </div>
              <div class="form-group required">
                <div class="col-sm-12">
                  <label class="control-label"><?= $entry_rating; ?></label>
                  &nbsp;&nbsp;&nbsp; <?= $entry_bad; ?>&nbsp;
                  <input type="radio" name="rating" value="1" />
                  &nbsp;
                  <input type="radio" name="rating" value="2" />
                  &nbsp;
                  <input type="radio" name="rating" value="3" />
                  &nbsp;
                  <input type="radio" name="rating" value="4" />
                  &nbsp;
                  <input type="radio" name="rating" value="5" />
                  &nbsp;<?= $entry_good; ?></div>
              </div>
              <?php // below "col-sm-12" is fix for offset negative margin left right in the recaptcha markup ?>
              <div class="col-sm-12"><?= $captcha; ?></div>
              <div class="buttons clearfix">
                <div class="pull-right">
                  <button type="button" id="button-review" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><?= $button_continue; ?></button>
                </div>
              </div>
            <?php } else { ?>
              <?= $text_purchase ?>
            <?php } ?>
          <?php } else { ?>
              <?= $text_login; ?>
          <?php } ?>
        </form>
    </div>
  <?php } ?>
</ul>

  <div class="tab-conten">

    <?php if(isset($available_offers['tab']) && $available_offers['tab']) { ?>
      <div class="tab-pane" id="tab-available-offers"><?php echo $available_offers['tab']; ?></div>
    <?php } ?>

    <?php if ($review_status) { ?>
      <div class="tab-pane" id="tab-review">
      </div>
    <?php } ?>
  </div>
<?php } ?>

<script>
    var acc = document.getElementsByClassName("accordion");
    var i;
    
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }
</script>

<style>
    .accordion {
      background-color: #fff;
      color: #444;
      cursor: pointer;
      padding: 18px 0;
      width: 100%;
      border: none;
      text-align: left;
      outline: none;
      font-size: 15px;
      transition: 0.4s;
        display: flex;
        flex-wrap: wrap;
    }
    .accordion a {
        color: black;
        font-size: 20px;
    }
    .accordion.active i {
        transform: rotate(180deg);
    }
    .accordion i {
        font-size: 24px;
        transition: .3s ease;
    }
    .panel {
      padding: 0 18px;
      display: none;
      background-color: white;
      overflow: hidden;
    }
</style>
