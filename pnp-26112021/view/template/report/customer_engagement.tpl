<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    <div class="pull-right"><a href="<?php echo $export; ?>" data-toggle="tooltip" title="<?php echo $button_export; ?>" class="btn btn-success"><i class="fa fa-download"></i></a></div>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
			  <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
			  <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group;?></label>
                <select name="filter_customer_group" id="input-customer-group" class="form-control">
                  <option value=""></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-clear" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> Clear</button>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
           <table class="table table-bordered table-hover">
              <thead>
                <tr>
                <td class="text-left">&nbsp;
				<?php if ($sort == 'customer_name') { ?>
                    <a href="<?php echo $sort_customername; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customername; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?>
				
				</td>
				<td class="text-left">&nbsp;
				<?php if ($sort == 'customer_email') { ?>
                    <a href="<?php echo $sort_customeremail; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customeremail; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customeremail; ?>"><?php echo $column_customeremail; ?></a>
                    <?php } ?>
				
				</td>
                <td class="text-right">&nbsp;
				<?php if ($sort == 'total_success_login') { ?>
                    <a href="<?php echo $sort_totalsuccesslogin; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_successlogincount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_totalsuccesslogin; ?>"><?php echo $column_successlogincount; ?></a>
                    <?php } ?>
				</td>
                <td class="text-right">&nbsp;
				<?php if ($sort == 'total_failed_login') { ?>
                    <a href="<?php echo $sort_totalfailedlogin; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_failedlogincount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_totalfailedlogin; ?>"><?php echo $column_failedlogincount; ?></a>
                    <?php } ?>
				</td>
				<td class="text-left">&nbsp;
				<?php if ($sort == 'last_login') { ?>
                    <a href="<?php echo $sort_lastlogin; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_last_login_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_lastlogin; ?>"><?php echo $column_last_login_date; ?></a>
                    <?php } ?>
				</td>
				<td class="text-left">&nbsp;
				<?php if ($sort == 'ip') { ?>
                    <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                    <?php } ?>
				</td>
				<td class="text-right">&nbsp;
				<?php if ($sort == 'total_amount_spent') { ?>
                    <a href="<?php echo $sort_totalamount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_amount_spent; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_totalamount; ?>"><?php echo $column_total_amount_spent; ?></a>
                    <?php } ?>
				</td>
				<td class="text-right">&nbsp;
				<?php if ($sort == 'cart_value') { ?>
                    <a href="<?php echo $sort_cartvalue; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_cart_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_cartvalue; ?>"><?php echo $column_cart_total; ?></a>
                    <?php } ?>
				</td>
				<td class="text-right">&nbsp;
				<?php if ($sort == 'wishlist_value') { ?>
                    <a href="<?php echo $sort_wishlistvalue; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_wishlist_value; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_wishlistvalue; ?>"><?php echo $column_wishlist_value; ?></a>
                    <?php } ?>
				</td>
				<td class="text-right">&nbsp;
				<?php if ($sort == 'number_of_orders') { ?>
                    <a href="<?php echo $sort_nooforders; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_orders; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_nooforders; ?>"><?php echo $column_total_orders; ?></a>
                    <?php } ?>
				</td>
				
              </tr>
            </thead>
            <tbody>
              <?php if ($activities) { ?>
              <?php foreach ($activities as $activity) { ?>
              <tr>
                <td class="text-left"><?php echo $activity['customer_name']; ?></td>
				<td class="text-left"><?php echo $activity['customer_email']; ?></td>
                <td class="text-right"><?php echo $activity['total_success_login']; ?></td>
                <td class="text-right"><?php echo $activity['total_failed_login']; ?></td>
				<td class="text-left">
				<?php if (is_null($activity['last_login'])) 
				$lastlogin=$text_neverlogin;
				else
				$lastlogin=$activity['last_login'] . " Days";?>
				<?php echo $lastlogin ?></td>
				<td class="text-left"><?php echo $activity['ip']; ?></td>
                <td class="text-right"><?php echo $activity['total_amount_spent']; ?></td>
                <td class="text-right"><?php echo $activity['cart_value']; ?></td>
				<td class="text-right"><?php echo $activity['wishlist_value']; ?></td>
				<td class="text-right"><?php echo $activity['number_of_orders']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--

$('#button-clear').on('click', function() {
  var url = 'index.php?route=report/customer_engagement&token=<?php echo $token; ?>';
  window.location= url;
});
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/customer_engagement&token=<?php echo $token; ?>';

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_customer_group = $('select[name=\'filter_customer_group\']').val();

	if (filter_customer_group) {
		url += '&filter_customer_group=' + encodeURIComponent(filter_customer_group);
	}

	var filter_ip = $('input[name=\'filter_ip\']').val();

	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
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
//--></script></div>
<?php echo $footer; ?>