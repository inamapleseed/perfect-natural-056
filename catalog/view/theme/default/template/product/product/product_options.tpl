<div id="product">
<?php if(!$not_avail) { ?>

    <?php if ($options) { ?>
    <?php foreach ($options as $option) { ?>
    <?php if ($option['type'] == 'select') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?> select-con ">
        <label class=" my-option-name"><?= $option['name']; ?></label>
        <div class="hidden"><?= $option['name']; ?></div>
      <select name="option[<?= $option['product_option_id']; ?>]" id="input-option<?= $option['product_option_id']; ?>" class="form-control">
        <option value=""><?= $text_select; ?></option>
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <option value="<?= $option_value['product_option_value_id']; ?>"><?= $option_value['name']; ?>
        <?php if ($option_value['price']) { ?>
        (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
        <?php } ?>
        </option>
        <?php } ?>
      </select>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'radio') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?> radio-con">
        <label class="control-label my-option-name"><?= $option['name']; ?></label>
        <div class="hidden"><?= $option['name']; ?></div>
      <div id="input-option<?= $option['product_option_id']; ?>">
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <div class="radio this-radio">
          <label>
            <input type="radio" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option_value['product_option_value_id']; ?>" data-id="<?= $option_value['name']; ?>"/>
            <?php if ($option_value['image']) { ?>
            <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" data-id="<?= $option_value['name']; ?>"/> 
            <?php } ?>                    
            
            <?php if ($option_value['price']) { ?>
            (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
            <?php } ?>
          </label>
        </div>
        <?php } ?>
      </div>
    </div>
    <script>
        $('.this-radio input[type=radio]').click(function(){
            let id = $(this).data("id");
            $(this).parent().parent().parent().parent().find('> label').empty().append(id);
            $('.my-option-name').next().removeClass('hidden');
        });
        $('.this-radio img').mouseover(function(){
            let id2 = $(this).data("id");
            $(this).parent().parent().parent().parent().find('> label').empty().append(id2);
            $('.my-option-name').next().removeClass('hidden');
        });

    </script>
    <?php } ?>
    
    
    <?php if ($option['type'] == 'checkbox') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label"><?= $option['name']; ?></label>
      <div id="input-option<?= $option['product_option_id']; ?>">
        <?php foreach ($option['product_option_value'] as $option_value) { ?>
        <div class="checkbox">
          <label>
            <input type="checkbox" name="option[<?= $option['product_option_id']; ?>][]" value="<?= $option_value['product_option_value_id']; ?>" />
            <?php if ($option_value['image']) { ?>
            <img src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
            <?php } ?>
            <?= $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
            <?php } ?>
          </label>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'text') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
      <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'textarea') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
      <textarea name="option[<?= $option['product_option_id']; ?>]" rows="5" placeholder="<?= $option['name']; ?>" id="input-option<?= $option['product_option_id']; ?>" class="form-control"><?= $option['value']; ?></textarea>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'file') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label"><?= $option['name']; ?></label>
      <button type="button" id="button-upload<?= $option['product_option_id']; ?>" data-loading-text="<?= $text_loading; ?>" class="btn btn-default btn-block"><i class="fa fa-upload"></i> <?= $button_upload; ?></button>
      <input type="hidden" name="option[<?= $option['product_option_id']; ?>]" value="" id="input-option<?= $option['product_option_id']; ?>" />
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'date') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
      <div class="input-group date">
        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'datetime') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
      <div class="input-group datetime">
        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
    <?php } ?>
    <?php if ($option['type'] == 'time') { ?>
    <div class="form-group<?= ($option['required'] ? ' required' : ''); ?>">
      <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
      <div class="input-group time">
        <input type="text" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option['value']; ?>" data-date-format="HH:mm" id="input-option<?= $option['product_option_id']; ?>" class="form-control" />
        <span class="input-group-btn">
        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
        </span></div>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>

<?php } ?>

    <?php if ($recurrings) { ?>
    <hr>
    <h3><?= $text_payment_recurring; ?></h3>
    <div class="form-group required">
      <select name="recurring_id" class="form-control">
        <option value=""><?= $text_select; ?></option>
        <?php foreach ($recurrings as $recurring) { ?>
        <option value="<?= $recurring['recurring_id']; ?>"><?= $recurring['name']; ?></option>
        <?php } ?>
      </select>
      <div class="help-block" id="recurring-description"></div>
    </div>
    <?php } ?>

    <div class="form-group">
      <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
      
      <input type="hidden" name="quantity" class="form-control input-number integer text-center" id="input-quantity" value="<?= $minimum; ?>" >
      <?php if($download){ ?>
        <a href="<?= $download; ?>" target="_blank" class="btn btn-primary" ><?= $button_download; ?></a>
      <?php } ?>
    </div>

    <?php if ($minimum > 1) { ?>
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?= $text_minimum; ?></div>
    <?php } ?>
  </div>


  
<script type="text/javascript"><!--
    $('.date').datetimepicker({
        pickTime: false
    });
    
    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
    
    $('.time').datetimepicker({
        pickDate: false
    });

    // Fix for datetimepicker positon issue
    $('.date, .datetime, .time').on('dp.show', function() {
      var datepicker = $('body').find('.bootstrap-datetimepicker-widget');
      if (datepicker.hasClass('bottom')) {
        var top = $(this).offset().top + $(this).outerHeight();
        var left = $(this).offset().left;
        datepicker.css({
          'top': top + 'px',
          'bottom': 'auto',
          'left': left + 'px'
        });
      }
      else if (datepicker.hasClass('top')) {
        var top = $(this).offset().top - datepicker.outerHeight();
        var left = $(this).offset().left;
        datepicker.css({
          'top': top + 'px',
          'bottom': 'auto',
          'left': left + 'px'
        });
      }
    });
    // Fix for datetimepicker positon issue
    
    $('button[id^=\'button-upload\']').on('click', function() {
        var node = this;
    
        $('#form-upload').remove();
    
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
    
        $('#form-upload input[name=\'file\']').trigger('click');
    
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }
    
        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);
    
                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(node).button('loading');
                    },
                    complete: function() {
                        $(node).button('reset');
                    },
                    success: function(json) {
                        $('.text-danger').remove();
    
                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }
    
                        if (json['success']) {
                            alert(json['success']);
    
                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
  
  // << OPTIONS IMAGE 
    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }
    function getoptionimages(prodid, poid, povid) {
      $('.product-image-additional').css('opacity',0);
      $.ajax({
            url: 'index.php?route=product/product/getoptionimages',
            type: 'get',
            dataType: 'json',
            //data: {'prodid': prodid, 'poid': poid, 'povid': povid},
            data: $('#product input[type=radio], #product select').serialize() + '&prodid=' + prodid + '&poid=' + poid + '&povid=' + povid,
            success: function(json) {
                //console.log(json);
                if(!isEmpty(json['images']) && !isEmpty(json['additional_images'])) {
                  destroySlick();
                  for(var i = 0; i < json['images'].length; i++) {
                    //console.log(json['images'][i]['thumb']);
                    $('.product-image-main').append('<img src="' + json['images'][i]['thumb'] + '" alt="<?= $product_title ?>" title="<?= $product_title ?>" data-thumb="' + json['images'][i]['thumb'] + '" data-fancybox="gal<?php echo $product_id; ?>" class="m_image pointer" href="' + json['images'][i]['popup'] + '" data-zoom-image="' + json['images'][i]['zoom'] + '" />');
                    $('.product-image-additional').append('<img src="' + json['additional_images'][i]['thumb'] + '" alt="<?= $product_title ?>" title="<?= $product_title ?>" class="pointer" />');
                  }
                  initSlick();
                }
                else {
                  getprodimages(<?= $product_id ?>, '<?= $prod_image ?>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    function getprodimages(prodid, prodimage) {
      $('.product-image-additional').css('opacity',0);
      $.ajax({
            url: 'index.php?route=product/product/ajaxgetprodimages',
            type: 'get',
            dataType: 'json',
            data: {'product_id': prodid, 'prodimage': prodimage},
            success: function(json) {
                //console.log(json);
                if(!isEmpty(json['images']) && !isEmpty(json['additional_images'])) {
                  destroySlick();
                  for(var i = 0; i < json['images'].length; i++) {
                    //console.log(json['images'][i]['thumb']);
                    $('.product-image-main').append('<img src="' + json['images'][i]['thumb'] + '" alt="<?= $product_title ?>" title="<?= $product_title ?>" class="main_images pointer" href="' + json['images'][i]['popup'] + '" data-zoom-image="' + json['images'][i]['zoom'] + '" />');
                    $('.product-image-additional').append('<img src="' + json['additional_images'][i]['thumb'] + '" alt="<?= $product_title ?>" title="<?= $product_title ?>" class="pointer" />');
                  }
                  initSlick();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    $(document).on('change', '#product input[type=radio], #product select', function() {
        if($(this).val()) {
            //console.log($(this).parent().parent().parent().attr('id'));
            if($(this).parent().parent().parent().attr('id') !== undefined){
            var poid = $(this).parent().parent().parent().attr('id').replace('input-option', '');
            
            } else {
              var poid = $(this).attr('id').replace('input-option', '');
            }
            var povid = $(this).val();
            getoptionimages(<?= $product_id ?>, poid, povid);
        }
        else {
          getprodimages(<?= $product_id ?>, '<?= $prod_image ?>');
        }
    });
    // >> OPTIONS IMAGE 



    //--></script>



  <?php if(isset($update_price_status) && $update_price_status) { ?>
					
	<script type="text/javascript">
    $(window).load(function(){
      changeProdPrice();
      $("#product input[type='checkbox']").click(function() {
        changeProdPrice();
      });
      
      $("#product input[type='radio']").click(function() {
        changeProdPrice();
      });
      
      $("#product select").change(function() {
        changeProdPrice();
      });
      
      $("#input-quantity").blur(function() {
        changeProdPrice();
      });

      $("#input-quantity").parent(".input-group").find(".btn-number").click(function() {
        changeProdPrice();
      });
      
      function changeProdPrice() {
        $.ajax({
          url: 'index.php?route=product/product/updatePrice&product_id=<?php echo $product_id; ?>',
          type: 'post',
          dataType: 'json',
          data: $('#product input[name=\'quantity\'], #product select, #product input[type=\'checkbox\']:checked, #product input[type=\'radio\']:checked'),
          beforeSend: function() {
            
          },
          complete: function() {
            
          },
          success: function(json) {
            $('.alert-success, .alert-danger').remove();
            
            if(json['new_price_found']) {
              $('.new-prices').html(json['total_price']);
              $('.product-tax').html(json['tax_price']);
            } else {
              $('.old-prices').html(json['total_price']);
              $('.product-tax').html(json['tax_price']);
            }
          }
        });

        // << CHECK OPTION STOCK 
        <?php if(!$product_has_ro && !$enquiry)	{ ?>
        $.ajax({	
          url: 'index.php?route=product/product/checkOptionStock&product_id=<?php echo $product_id; ?>',	
          type: 'post',	
          dataType: 'json',	
          data: $('#product input[name=\'quantity\'], #product select, #product input[type=\'checkbox\']:checked, #product input[type=\'radio\']:checked'),	
          beforeSend: function() {	
              
          },	
          complete: function() {	
              
          },	
          success: function(json) {	
            $('#waiting_list input[name="no_stock_pov_ids"]').val('');	
            $('#waiting_list input[name="pov_ids"]').val('');	
            if(json['no_stock_option_array'].length > 0){	
                $('#waiting_list input[name="no_stock_pov_ids"]').val(json['no_stock_option_array']);	
            }	
            if(json['selected_option_array'].length > 0){	
                $('#waiting_list input[name="pov_ids"]').val(json['selected_option_array']);	
            }	
            $('.alert-success, .alert-danger').remove();	
            $('#waiting_list input[name="product_no_stock"]').val(false);	
            if(json['has_stock']) {	
                //has stock	
                $('#waiting_list').hide();	
                $('#button-cart').show();	
            } else {	
                //no stock	
                $('#waiting_list').show();	
                $('#button-cart').hide();	
            }	
            if(json['main_product_nostock']){	
                $('#waiting_list input[name="product_no_stock"]').val(true);	
            }	
          }	
        });	
        <?php } else { ?>
          setTimeout(function(){
            $('#waiting_list').hide();
            },300);	
        <?php }  ?>
        // >> CHECK OPTION STOCK
      }
    });
	</script>
		
<?php } ?>
