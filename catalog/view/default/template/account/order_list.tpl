<?= $header; ?>
<div class="container">
  <?= $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?= $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?= $class; ?>"> 
      <h2><?= $heading_title; ?></h2>
      <?php if ($orders) { ?>
        
        <table class="table table-hover">
          <thead>
            <tr>
              <td class="text-left"><?= $column_order_id; ?></td>
              <td class="text-left"><?= $column_date_added; ?></td>
              <td class="text-left"><?= $column_total; ?></td>
              <td class="text-left"><?= $column_status; ?></td>
              <td class="text-center"><?= $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-left">#<?= $order['order_id']; ?></td>              
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_date_added; ?><br/></small>
                <?= $order['date_added']; ?></td>
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_total; ?><br/></small>
                <?= $order['total']; ?></td>
              <td class="text-left">
                <small class="bold visible-xs"><?= $column_status; ?><br/></small>
                <?= $order['status']; ?></td>
              <td class="text-center">
                  <small class="bold visible-xs"><?= $column_action; ?><br/></small>
                <a href="<?= $order['view']; ?>" class="inline-link"><?= $button_view; ?></a>
                
                <a href="<?= $order['reorder']; ?>" class="inline-link esc" data-toggle="modal-content" 
                  data-title="<?= $button_reorder; ?>: <?= $column_order_id; ?> #<?= $order['order_id']; ?>" >
                  <?= $button_reorder; ?>
                </a>
                <?php if(!$order['show_shipping']){?>
                    <a class="inline-link pointer trackShipping" pid="<?= $order['order_id']; ?>">Shipping Status</a>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>

      <div class="row">
        <div class="col-sm-6 text-left"><?= $pagination; ?></div>
        <div class="col-sm-6 text-right"><?= $results; ?></div>
      </div>
      <?php } else { ?>
      <p><?= $text_empty; ?></p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?= $continue; ?>" class="btn btn-primary"><?= $button_continue; ?></a></div>
      </div>
      </div>
    <?= $column_right; ?></div>
    <?= $content_bottom; ?>
</div>

<input type="hidden" class="lalamove_modal" data-toggle="modal" data-target="#LalamoveModal">
<!-- Lalamove Modal -->
<div class="modal fade" id="LalamoveModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" style="border: 5px solid #484848;">
            <div class="modal-body text-center">
                <button type="button" class="close" data-dismiss="modal" style="color: #436d3e;opacity:1">&times;</button>
                <h3 style="border-bottom: 1px solid #436d3e;padding-bottom: 10px;">Shipping Status</h3>
                <p>Shipping ID: <span class="lalamove_shipping_id">-</span></p>
                <h2 class="lalamove_status" style="color: #820000;margin-bottom:20px">-</h2>
                <h3>Driver Info</h3>
                <p>Name: <span class="lalamove_name">-</span></p>
                <p>Phone: <span class="lalamove_phone">-</span></p>
                <p>Plate No: <span class="lalamove_plate">-</span></p>
                <input type="hidden" value="" class="hidden_order_id">
                <!--<button class="btn btn-danger cancel_lalamove_order">Cancel Order</button>-->
            </div>
        </div>
      
    </div>
</div>
<script>
    $(document).ready(function(){
        var call = 0;
        $('.trackShipping').on("click",function(){
            $('.hidden_order_id').val($(this).attr('pid'));
            getShippingStatus();
            $('.lalamove_modal').click();
            if(call == 0){
                call = 1;
                setInterval(function(){ getShippingStatus(); }, 5000);
            }    
        }); 
        
        $('.cancel_lalamove_order').on("click",function(){
            swal({
				title: 'Are you sure?',
				html: "You won't be able to revert this!",
				icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel order!'
            }).then((result) => {
                if (result.value) {
                    callCancelShipping();
                }
            });
        });
    });
    function callCancelShipping(){
         $.ajax({
    		url: 'index.php?route=extension/lalamove_api/cancelOrder',
    		type: 'post',
    		data: "order_id="+$('.hidden_order_id').val(),
    		dataType: 'json',
    		success: function(json) {
    		    if(json['status'] == 1){
    		        Swal.fire(
                        'Cancelled!',
                        'Yes, your order have been cancelled',
                        'success'
                    )
                    $('.cancel_lalamove_order').hide();
    		    }else{
    		        Swal.fire(
                        'We are sorry!',
                        'Your order is prepared, cannot cancel now',
                        'error',
                    )
    		    }
    		}
    	});
    }
    function getShippingStatus(){
        $.ajax({
    		url: 'index.php?route=account/order/getShippingStatus',
    		type: 'post',
    		data: "order_id="+$('.hidden_order_id').val(),
    		dataType: 'json',
    		success: function(json) {
        		$('.lalamove_status').html(json['lalamove_status']);
    		    if(json['lalamove_driver_name']){
        		    $('.lalamove_name').html(json['lalamove_driver_name']);
    		    }else{
    		        $('.lalamove_name').html("-");
    		    }
    		    if(json['lalamove_driver_phone']){
        		    $('.lalamove_phone').html(json['lalamove_driver_phone']);
    		    }else{
    		        $('.lalamove_phone').html("-");
    		    }
    		    if(json['lalamove_driver_plate']){
        		    $('.lalamove_plate').html(json['lalamove_driver_plate']);
    		    }else{
    		        $('.lalamove_plate').html("-");
    		    }
    		    if(json['lalamove_cancel'] == 1){
                    $('.cancel_lalamove_order').hide();
    		    }else{
                    $('.cancel_lalamove_order').show();
    		    }
    		    if(json['lalamove_cust_order_id']){
        		    $('.lalamove_shipping_id').html(json['lalamove_cust_order_id']);
    		    }else{
    		        $('.lalamove_shipping_id').html("-");
    		    }
    		}
    	});
    }
</script>
<?= $footer; ?>
