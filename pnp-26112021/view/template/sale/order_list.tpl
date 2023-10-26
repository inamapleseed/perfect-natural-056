<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-pickpacklist" form="form-order" formaction="<?php echo $pickpacklist; ?>" data-toggle="tooltip" title="<?php echo $text_pickpacklist; ?>" class="btn btn-warning"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-shipping" form="form-order" formaction="<?php echo $shipping; ?>" data-toggle="tooltip" title="<?php echo $button_shipping_print; ?>" class="btn btn-info"><i class="fa fa-truck"></i></button>
        <button type="submit" id="button-invoice" form="form-order" formaction="<?php echo $invoice; ?>" data-toggle="tooltip" title="<?php echo $button_invoice_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></button>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" id="button-delete" form="form-order" formaction="<?php echo $delete; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_date_modified; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" placeholder="<?php echo $entry_date_modified; ?>" data-date-format="YYYY-MM-DD" id="input-date-modified" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-delivery-date"><?php echo $entry_delivery_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" placeholder="<?php echo $entry_delivery_date; ?>" data-date-format="YYYY-MM-DD" id="input-delivery-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-delivery-time"><?php echo $entry_delivery_time; ?></label>
                <select name="filter_delivery_time" id="input-delivery-time" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($delivery_times as $delivery_time) { ?>
                  <?php $delivery_time['delivery_time'] = $delivery_time['delivery_time'] == ''?'N/A':$delivery_time['delivery_time']; ?>
                  <?php if ($delivery_time['delivery_time'] == $filter_delivery_time) { ?>
                  <option value="<?php echo $delivery_time['delivery_time']; ?>" selected="selected"><?php echo $delivery_time['delivery_time']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $delivery_time['delivery_time']; ?>"><?php echo $delivery_time['delivery_time']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <button type="button" id="button-clear" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data" id="form-order">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked); setBtnActive();" /></td>
                  <td class="text-right"><?php if ($sort == 'o.order_id') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'order_status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'o.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.delivery_date') { ?>
                    <a href="<?php echo $sort_delivery_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_delivery_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_delivery_date; ?>"><?php echo $column_delivery_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.delivery_time') { ?>
                    <a href="<?php echo $sort_delivery_time; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_delivery_time; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_delivery_time; ?>"><?php echo $column_delivery_time; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'o.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                    <?php } ?>
                    <input type="hidden" name="shipping_code[]" value="<?php echo $order['shipping_code']; ?>" /></td>
                  <td class="text-right"><?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
                  <td class="text-left"><?php echo $order['order_status']; ?></td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-left"><?php echo $order['delivery_date']; ?></td>
                  <td class="text-left"><?php echo $order['delivery_time']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-left"><?php echo $order['date_modified']; ?></td>
                  <td class="text-right">
                      <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a>
                      <a href="<?php echo $order['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <?php if($order['show_shipping'] && $order['lalamove_status']){ ?>
                          <a class="btn btn-warning arrangeShipping" pid="<?= $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo "Arrange Shipping"; ?>"><i class="fa fa-truck"></i></a>
                      <?php }else{ ?>
                        <a class="btn btn-success trackShipping" pid="<?= $order['order_id']; ?>" data-toggle="tooltip" title="<?php echo "Track Lalamove Status"; ?>"><i class="fa fa-truck"></i></a>
                      <?php } ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  
    <!-- lalamove status -->
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
                    </div>
                </div>
              
            </div>
        </div>
    <!-- lalamove status -->
  
    <!-- lalamove order model -->
    <div class="modal fade bd-example-modal-sm" id="lalamoveSubmitModal" role="dialog">
        <div class="modal-dialog " style="">
            
            <form id = "shipping_form" method = 'post'>
                <!-- Modal content-->
                <div class="modal-content ">
                  <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Arrange for Shipping</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                  </div>
                  <div class="modal-body">
                        <div id = 'error_div' class="mb-3 card text-white card-body bg-danger" style = 'display:none'>
                            <div id = 'error_msg' style="padding: 15px;">test</div>
                        </div>
                        <div id = 'success_div' class="mb-3 card text-white card-body bg-success" style = 'display:none'>
                            <div id = 'success_msg' style="padding: 15px;">test</div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="shipping_partner" class="col-sm-4 col-form-label">Shipping Partner</label>
                            <div class="col-sm-8">
                                <select style = 'width:100%' class="form-control select2" id="shipping_partner" name="shipping_partner">
                                    <option value="lalamove">Lalamove</option>
                                </select>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="shipping_type" class="col-sm-4 col-form-label">Shipping Type</label>
                            <div class="col-sm-8">
                                <select style = 'width:100%' class="form-control select2" id="shipping_type" name="shipping_type">
                                    <option value="MOTORCYCLE">Bike</option>
                                    <option value="CAR">Car</option>
                                    <option value="MINIVAN">1.7m Van</option>
                                    <option value="VAN">2.4m Van</option>
                                    <option value="TRUCK330">10ft Lorry</option>
                                    <option value="TRUCK550">14ft Lorry</option>
                                </select>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label class="col-sm-4 col-form-label" for="input-date-added">Delivery Date</label>
                            <div class="col-sm-8">
                                <div class="input-group date">
                                    <input type="text" name="order_shipping_date" data-date-format="YYYY-MM-DD" id="order_shipping_date" class="form-control" />
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="order_shipping_time" class="col-sm-4 col-form-label">Delivery Time</label>
                            <div class="col-sm-8">
                                <input type = "text" readonly name = 'order_shipping_time' id = 'order_shipping_time' class="form-control time" placeholder="Shipping Time"/>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="order_shipping_contact" class="col-sm-4 col-form-label">Contact Name</label>
                            <div class="col-sm-8">
                                <input type = "text" name = 'order_shipping_contact' id = 'order_shipping_contact' class="form-control" placeholder="Contact Name"/>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="order_shipping_mobile" class="col-sm-4 col-form-label">Contact Mobile</label>
                            <div class="col-sm-8">
                                <input type = "text" name = 'order_shipping_mobile' id = 'order_shipping_mobile' class="form-control" placeholder="Contact Mobile"/>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="order_shipping_postal_code" class="col-sm-4 col-form-label">Postal Code</label>
                            <div class="col-sm-8">
                                <input type = "text" name = 'order_shipping_postal_code' id = 'order_shipping_postal_code' class="form-control" placeholder="Postal Code"/>
                            </div>
                        </div>
                        <div class="position-relative row form-group">
                            <label for="order_shipping_to" class="col-sm-4 col-form-label">Shipping to </label>
                            <div class="col-sm-8">
                                  <textarea class="form-control" rows="3" id="order_shipping_to" name="order_shipping_to" placeholder="Shipping To"></textarea>
                            </div>
                        </div>
                      <hr>
                      
                        <div class="position-relative row form-group">
                            <label for="order_shippingamount" class="col-sm-4 col-form-label">Shipping Cost </label>
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <input style = 'text-align:right;color: red;font-weight: 700;' READONLY name = 'order_shippingamount' id = 'order_shippingamount' placeholder="Shipping Cost" type="text" class="form-control" value = '0.00'>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                  <button type="button" class="btn btn-primary btn-shadow" id="get_quote_btn">Get Quote Now!</button>
                            </div>
                        </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default btn-shadow" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary btn-shadow" id="send_shipping_order_btn" disabled>Submit</button>
                      <input type="hidden" name="order_id" id = 'order_id' value="">
                  </div>
                </div>
            </form>
        </div>
    </div>
    <!-- lalamove order model -->
  
  <script type="text/javascript"><!--
    $(document).ready(function(){
        var call = 0;
        $('.trackShipping').on("click",function(){
            $('.hidden_order_id').val($(this).attr('pid'));
            getShippingStatus();
            $('.lalamove_modal').click();
            if(call == 0){
                call = 1;
                setInterval(function(){ getShippingStatus(); }, 3000);
            }    
        });
        
        $('.arrangeShipping').on("click",function(){
            var order_id = $(this).attr('pid');
            $.ajax({
        		url: 'index.php?route=sale/order/getLalamoveDetail&token=<?php echo $token; ?>',
        		type: 'post',
        		data: "order_id="+$(this).attr('pid'),
        		dataType: 'json',
        		success: function(json) {
        		    $('#order_id').val(order_id);
        		    $('#order_shipping_date').val(json.delivery_date);
        		    $('#order_shipping_time').val(json.lalamove_delivery_time);
        		    $('#order_shipping_contact').val(json.shipping_firstname);
        		    $('#order_shipping_mobile').val(json.telephone);
        		    $('#order_shipping_postal_code').val(json.shipping_postcode);
        		    $('#order_shipping_to').val(json.shipping_address_1 + " " + json.shipping_address_2 + " " + json.shipping_postcode + " " + json.shipping_unit_no);
        		    $('#lalamoveSubmitModal').modal('show');
        		}
        	});
        });
        
        $(document).on("click", '#get_quote_btn', function() {
            var data = "action=getShippingQuote&" + $('#shipping_form').serialize();
            $.ajax({
                type: "POST",
                url: '<?= HTTPS_CATALOG; ?>index.php?route=extension/lalamove_api/createBackendQuatation',
                data:data,
                dataType: "json",
                beforeSend: function() {
                    $('#get_quote_btn').text('Loading..');
                    $('#get_quote_btn').attr("disabled",true);
                },
                success: function(json) { 
                   $('#get_quote_btn').attr("disabled",false);
                   $('#get_quote_btn').text('Get Quote Now!');
                   if(json.status == 1){
                       $('#order_shippingamount').val(json.totalFee);
                       
                        $('#send_shipping_order_btn').attr("disabled",false);
                        $('#error_div').css('display','none');
                        $('#success_div').css('display','');
                        $('#success_msg').html('Get quote successfully.');
                   }else{
                        $('#send_shipping_order_btn').attr("disabled",true);
                        $('#error_div').css('display','');
                        $('#success_div').css('display','none');
                        $('#error_msg').html('Something went wrong , please try again later. <br>error : ' + json.message);
                    
                       $('#order_shippingamount').val(0);
                   }
                }
            });
        });
        
        $(document).on("click", '#send_shipping_order_btn', function() {
            var data = "action=sendShippingOrder&" + $('#shipping_form').serialize()+"&submit=1";
            $.ajax({
                type: "POST",
                url: '<?= HTTPS_CATALOG; ?>index.php?route=extension/lalamove_api/createBackendQuatation',
                data:data,
                dataType: "json",
                beforeSend: function() {
                    $('#send_shipping_order_btn').text('Loading..');
                    $('#send_shipping_order_btn').attr("disabled",true);
                },
                success: function(json) {
                   
                   $('#send_shipping_order_btn').attr("disabled",false);
                   $('#send_shipping_order_btn').text('Submit');
                   if(json.status == 1){
                        var tracking_code = json.orderRef;
                        $('#error_div').css('display','none');
                        $('#success_div').css('display','');
                        $('#success_msg').html('Submitted shipping order successfully.' + "<br>Tracking Code : " + tracking_code);
                   }else{
                        $('#error_div').css('display','');
                        $('#success_div').css('display','none');
                        $('#error_msg').html('Something went wrong , please try again later.');
                    
                   }
                }
            }); 
        });
    });
    function getShippingStatus(){
        $.ajax({
    		url: '<?= HTTPS_CATALOG; ?>index.php?route=account/order/getShippingStatus',
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
    		    if(json['lalamove_cust_order_id']){
        		    $('.lalamove_shipping_id').html(json['lalamove_cust_order_id']);
    		    }else{
    		        $('.lalamove_shipping_id').html("-");
    		    }
    		}
    	});
    }

$('#button-clear').on('click', function() {
  var url = 'index.php?route=sale/order&token=<?php echo $token; ?>';
  window.location= url;
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_order_status = $('select[name=\'filter_order_status\']').val();

	if (filter_order_status != '*') {
		url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

  var filter_delivery_date = $('input[name=\'filter_delivery_date\']').val();

  if (filter_delivery_date) {
    url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
  }

  var filter_delivery_time = $('select[name=\'filter_delivery_time\']').val();

  if (filter_delivery_time != '*') {
    url += '&filter_delivery_time=' + encodeURIComponent(filter_delivery_time);
  }

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name^=\'selected\']').on('change', function() {
  setBtnActive();
});

function setBtnActive(){
  $('#button-pickpacklist, #button-shipping, #button-invoice').prop('disabled', true);

	var selected = $('input[name^=\'selected\']:checked');

	for (i = 0; i < selected.length; i++) {
		if ($(selected[i]).parent().find('input[name^=\'shipping_code\']').val()) {
			$('#button-pickpacklist, #button-shipping, #button-invoice').prop('disabled', false);

			break;
		}
	}

}


$('#button-pickpacklist, #button-shipping, #button-invoice').prop('disabled', true);

$('input[name^=\'selected\']:first').trigger('change');

// IE and Edge fix!
$('#button-pickpacklist, #button-shipping, #button-invoice').on('click', function(e) {
	$('#form-order').attr('action', this.getAttribute('formAction'));
});

$('#button-delete').on('click', function(e) {
	$('#form-order').attr('action', this.getAttribute('formAction'));
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		$('#form-order').submit();
	} else {
		return false;
	}
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
$('.time').datetimepicker({
	pickTime: true,
	pickDate: false,
	icons:
    {
        up: 'fa fa-angle-up',
        down: 'fa fa-angle-down'
    },
});
//--></script></div>
<?php echo $footer; ?> 