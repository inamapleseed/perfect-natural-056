<div id="cart" class="relative slide-out-cart" > <!-- add/remove class slide-out-cart for normal opencart cart dropdown-->
    <a class="cart-dropdown pointer" id="cart_dropdown_icon" <?php if($show_cart) { ?> href="<?= $show_cart_url ?>" <?php }else{ ?> data-toggle="dropdown" onclick="$('body, #cart').toggleClass('open-custom');" <?php } ?> >
    <img src="image/catalog/slicing/header/header-cart.png" class="nav-img" alt="image"/>
      <span class="badge" >
        <span id="cart-quantity-total" ><?= $total_item; ?></span>
      </span>
    </a>

    <ul class="dropdown-menu pull-right"  >
      
      <div class="cart-header">
        <div class="cart-header-text"><?= $text_my_cart; ?></div>
        <button type="button" class="pointer cart_close" onclick="$('#cart_dropdown_icon').click(); return false;" ></button>
      </div>

      <?php if ($products) { ?>

      <li class="cart-dorpdown-indicator" >
        <?php if($free_shipping_indicator){ echo $free_shipping_indicator; }else{ ?>
          <span id="cart-total"><?php echo $text_items; ?></span>
        <?php } ?>
      </li>

      <li class="cart-dorpdown-items">
        <?php foreach ($products as $product) { ?>
          <div class="item">

              <a href="<?php echo $product['href']; ?>">
                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" />
              </a>

              <div class="item-details">
                <button type="button" onclick="cart.remove('<?= isset($product['cart_id']) ? $product['cart_id'] : $product['key']; ?>');" title="<?= $button_remove; ?>" class="btn btn-danger no-custom pull-right">
                  <i class="fa fa-times"></i>
                </button> 

                <a class="item-name" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <div class="item-option">

                  <?php if ($product['option']) { ?>
                    <?php foreach ($product['option'] as $option) { ?>
                        <?php if ($option['type'] != 'file') { ?>
                          <small><?php echo $option['name']; ?>: <?= $option['value'] . $option['price']; ?></small><br />
                        <?php } else { ?>
                          <small>
                                <?= $option['name']; ?>:
                                <a href="<?= $option['href']; ?>"><?= $option['value']; ?></a><br />
                            </small>
                        <?php } ?>
                    <?php } ?>

                  <?php } ?>
                  
                  <?php if ($product['recurring']) { ?>
                    <br/><small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
                  <?php } ?>

                </div>

                  <div class="cart-dorpdown-item-charges with-control">
                    <div class="input-group" id="header-cart-item-<?= $product['cart_id']; ?>" >
                      <span class="input-group-btn">
                        <button type="button" data-loading="-" class="btn btn-default btn-number no-custom <?= $product['quantity']==1?'disabled':''; ?>" data-type="minus" 
                        <?php if($product['quantity'] > 1){ ?>
                          onclick="descrement($(this).parent().parent()); refreshHeaderCart(this);" 
                        <?php } ?>
                        >
                          <span class=" glyphicon-minus"></span>
                        </button>
                      </span>

                      <input type="text" name="quantity[<?= $product['cart_id']; ?>]" class="form-control input-number integer text-center update-cart no-custom" value="<?= $product['quantity']; ?>" onfocus="rememberHeaderQuantity('<?= $product['quantity']; ?>');" onblur="refreshHeaderCart(this);" >
                      
                      <span class="input-group-btn">
                        <button type="button" data-loading="+" class="btn btn-default btn-number no-custom" data-type="plus" onclick="increment($(this).parent().parent());  refreshHeaderCart(this);">
                          <span class=" glyphicon-plus"  ></span>
                        </button>
                      </span>
                    </div>

                    <span>
                      <span><?= $text_price; ?></span><span>:</span> <span><?php echo $product['total']; ?></span>
                    </span>

                  </div>

                  <div class="cart-dorpdown-item-charges label-only">
                    <span>
                      <span><?= $text_quantity; ?></span><span>:</span> <span><?php echo $product['quantity']; ?></span>
                    </span>
                    <span>
                      <span><?= $text_price; ?></span><span>:</span> <span><?php echo $product['total']; ?></span>
                    </span>
                  </div>


            </div>
            

          </div>
        <?php } ?>
      </li>
      <li class="cart-dropdown-order-totals" >
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
      </li>
      <li class="cart-dorpdown-footer" >
          <a href="<?php echo $cart; ?>" class="btn btn-default"><?php echo $text_cart; ?></a>
          <a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $text_checkout; ?></a>
      </li>
      <?php } else { ?>
      <li class="cart-dropdown-empty text-center" >
        <i class="fa fa-meh-o" aria-hidden="true"></i>
        <p><?php echo $text_empty; ?></p>
      </li>
      <?php } ?>
    </ul>
</div>

<script type="text/javascript">

  var current_focus_header_quantity = 0;
  function rememberHeaderQuantity(qty){
    current_focus_header_quantity = parseInt(qty);
  }
  var header_request = null;
  
  function refreshHeaderCart(ele) {
    element = $(ele);
    id = element.parent().parent().attr("id");
    if (element.is("input")) {
      id = element.parent().attr("id");
    }

    if($("#" + id + " input").val() == current_focus_header_quantity){
      return;
    }
    
    if(header_request) header_request.abort();

    header_request = $.ajax({
      url: '<?= $action; ?>',
      data: $("#" + id + " input").serialize(),
      dataType: 'HTML',
      type: 'post',
      beforeSend: function () {
        $('.alert').remove();
        if(element.hasClass('btn-number')){
          cache_text = element.html();
          // element.button("loading");
          element.html(cache_text);
        }
        else{
          element.button("loading");
        }
        current_focus_header_quantity = 0;
      },
      complete: function () {
        element.button("reset");
      },
      success: function (html) {

        $(".alert-success").remove();

        content_total_dropdown_body = $(html).find("#loadFrom").html();
        content_total_dropdown = $(html).find("#cart-quantity-total").text();
        content_total = $(html).find("#cartTotals");
        content = $(html).find("#cartSummary tbody");
        alert = $(html).find(".alert-success");
        alert_error = $(html).find(".alert-danger");
        
        if(alert_error.length){
          error = alert_error.html().split(":");
          if(error.length > 1){
            swal({
              title: error[0],
              html: error[1],
              type: "error"
            });
          }
          else{
            swal({
              title: '<?= $text_title_warning; ?>',
              html: error[0],
              type: "error"
            });
          }
        }
        else if(alert.length){
          success = alert.html().split(":");
          if(success.length > 1){
            swal({
              title: success[0],
              html: success[1],
              type: "success"
            });
          }
        }

        if (content.length) {            
          content = content.html();
          $("#cartSummary tbody").html(content);
          var parent_element = $("#cartTotals").parent(); $("#cartTotals").remove();
          parent_element.html(content_total);
          
          $('#cart-quantity-total').text(content_total_dropdown);
          $('#loadTo').html(content_total_dropdown_body);

          $('#cart > ul').load('index.php?route=common/cart/info ul > *');

        } else {
          location.reload();
        }

      }
    })
  }
</script>