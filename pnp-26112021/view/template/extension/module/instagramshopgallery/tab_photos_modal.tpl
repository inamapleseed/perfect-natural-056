<div class="row">

  <div class="col-md-6 uk-text-center">
    <a href="https://www.instagram.com/p/<?php echo $media['shortcode']; ?>/" target="_blank">
      <img src="<?php echo $media['media_image']; ?>" class="img-responsive" style="min-height:240px;">
    </a>
  </div>

  <div class="col-md-6">
    <form id="form-modal" style="padding:15px 30px 15px 0">

      <div style="padding:10px 0; overflow:hidden;">
        <?php if ($media['username']) { ?>
          <h3 style="margin:0 0 6px;font-size:18px;">
            <i class="fa fa-instagram"></i>
            <a href="https://www.instagram.com/<?php echo $media['username']; ?>" target="_blank"><?php echo $media['username']; ?></a><br>
          </h3>
        <?php } ?>
        <?php if ($media['date']) { ?>
          <p style="color:#999; font-size:12px; margin: 0;">
            <span style="margin-right:15px"><?php echo $media['date']; ?></span>
          </p>
        <?php } ?>
      </div>
      <div style="padding:15px 0 0; border-top:1px solid #ededed; overflow:hidden;">
        <div class="col-xs-4" style="padding: 5px 0 0;"><b><?php echo $entry_approve; ?></b></div>
        <div class="col-xs-8">
          <select name="approve" id="photo-approval" class="form-control input-sm js-modal-approve">
              <option value="0" <?php echo $media['approve'] != '1' ? 'selected' : ''; ?>>No</option>
              <option value="1" <?php echo $media['approve'] == '1' ? 'selected' : ''; ?>>Yes</option>
            </select>
        </div>
      </div>
      <div style="padding:10px 0 0; overflow:hidden;">
        <div class="col-xs-4" style="padding: 5px 0 0;"><b><?php echo $entry_store; ?></b></div>
        <div class="col-xs-8">
          <?php foreach ($stores as $store) { ?>
            <label for="store-<?php echo $store['store_id']; ?>">
              <input id="store-<?php echo $store['store_id']; ?>" type="checkbox" name="stores[]" value="<?php echo $store['store_id']; ?>" <?php echo in_array($store['store_id'], $media['stores']) ? 'checked' : ''; ?>>
              <?php echo $store['name']; ?>
            </label>
          <?php } ?>
        </div>
      </div>
      <div style="padding:10px 0 0; overflow:hidden;">
        <div class="col-xs-4" style="padding: 5px 0 0;"><b><?php echo $entry_products; ?></b></div>
        <div class="col-xs-8">
          <input type="text" name="related" value="" placeholder="Start typing in your product name" id="input-related" class="form-control input-sm js-related-<?php echo $media['shortcode']; ?>" style="margin-bottom:10px;"/>
        </div>
        <div class="col-xs-12" style="padding-left:0">
          <div id="product-related" class="well well-sm" style="height:120px; overflow:auto; margin-bottom:10px;">
            <?php foreach ($media['related_products'] as $product) { ?>
              <div id="product-related<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                <input type="hidden" name="related_products[]" class="js-modal-related" value="<?php echo $product['product_id']; ?>" />
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <textarea name="data" cols="30" rows="10" class="hidden"><?php echo $mediaData; ?></textarea>

      <div style="padding:15px 15px 15px 0; overflow:hidden;">
        <div class="pull-left js-modal-noty" style="display:none">
          <b class="text-success"><?php echo $text_success_save; ?></b>
        </div>
        <div class="pull-right">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary js-modal-save" style="margin-left:10px">Save</button>
        </div>
      </div>
    </form>
  </div>

</div>
<script>
$('.js-related-<?php echo $media['shortcode']; ?>').on('keydown', function(e) {
  if (e.which == 13) { // enter
    e.preventDefault();
  }
});
$('.js-related-<?php echo $media['shortcode']; ?>').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('.js-related-<?php echo $media['shortcode']; ?>').val('');
    $('#product-related' + item['value']).remove();
    $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="related_products[]" value="' + item['value'] + '" /></div>');
  }
});
$('#product-related').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
</script>
