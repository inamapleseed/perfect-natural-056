<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a id="button-edit" data-toggle="tooltip" style="display:none;" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a id="button-search" data-toggle="tooltip" title="<?php echo $button_search; ?>" class="btn btn-info"><i class="fa fa-search"></i></a></div>
      <h1><i class="fa fa-search"></i> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <div id="search-input">
          <form id="form" class="form-horizontal">
            <h3><?php echo $text_date_search; ?></h3>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
              <div class="col-sm-10">
                <div class="input-group date">
                  <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
              <div class="col-sm-10">
                <div class="input-group date">
                  <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" data-date-format="YYYY-MM-DD" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <h3><?php echo $entry_transaction; ?></h3>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_transaction_type; ?></label>
              <div class="col-sm-10">
                <select name="transaction_class" class="form-control">
                  <option value="ALL"><?php echo 'ALL';?></option>
                  <option value="INITIATED"><?php echo 'INITIATED';?></option>
                  <option value="APPROVED"><?php echo 'APPROVED';?></option>
                  <option value="CANCELLED"><?php echo 'CANCELLED';?></option>
                  <option value="REJECTED"><?php echo 'REJECTED';?></option>
                  <option value="REVERSED"><?php echo 'REVERSED';?></option>
                  <option value="REFUNDED"><?php echo 'REFUNDED';?></option>
                  <option value="ACCEPTED"><?php echo 'ACCEPTED';?></option>
                  <option value="IN_PROCESS"><?php echo 'IN_PROCESS';?></option>
                  <option value="RETRY_NEEDED"><?php echo 'RETRY_NEEDED';?></option>
                  <option value="DONE"><?php echo 'DONE';?></option>
                  <option value="FAILED"><?php echo 'FAILED';?></option>
                  
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_transaction_id; ?></label>
              <div class="col-sm-10">
                <input type="text" name="transaction_id" value="" placeholder="<?php echo $entry_transaction_id; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_order_no; ?></label>
              <div class="col-sm-10">
                <input type="text" name="order_id" value="" placeholder="<?php echo $entry_order_no; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_amount; ?></label>
              <div class="col-sm-10">
                <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" class="form-control" />
                <br />
                <select name="currency_code" class="form-control">
                  <?php foreach($currency_codes as $code) { ?>
                  <option <?php if ($code == $default_currency) { echo 'selected'; } ?>><?php echo $code; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div id="search-box" style="display:none;">
          <div id="searching"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i> <?php echo $text_searching; ?></div>
          <div style="display:none;" id="error" class="alert alert-danger"></div>
          <table id="search_results" style="display:none;" class="table table-striped table-bordered" >
          </table>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-search').on('click', function() {
  var html = '';

	$.ajax({
		url: 'index.php?route=extension/payment/hoolah/doSearch&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#form').serialize(),
		beforeSend: function () {
			$('#search-input').hide();
			$('#search-box').show();
			$('#button-search').hide();
			$('#button-edit').show();
		},
		success: function (data) {
		    
			if (data.error == true) {
				$('#searching').hide();
				$('#error').html('<i class="fa fa-exclamation-circle"></i> ' + data.error_msg).fadeIn();
			} else {
				if (data.result != '') {
					html += '<thead><tr>';
					html += '<td class="left"><?php echo $column_date; ?></td>';
					html += '<td class="left"><?php echo $column_type; ?></td>';
					html += '<td class="left"><?php echo $column_email; ?></td>';
					html += '<td class="left"><?php echo $column_name; ?></td>';
					html += '<td class="left"><?php echo $column_transid; ?></td>';
					html += '<td class="left"><?php echo $column_status; ?></td>';
					html += '<td class="left"><?php echo $column_currency; ?></td>';
					html += '<td class="right"><?php echo $column_amount; ?></td>';
					html += '<td class="center"><?php echo $column_action; ?></td>';
					html += '</tr></thead>';
				
          $.each(data, function (k, v) {
            if ("L_LONGMESSAGE" in v) {
              $('#error').text(v.L_LONGMESSAGE).fadeIn();
            } else {
              if (!("L_EMAIL" in v)) {
                v.L_EMAIL = '';
              }

              html += '<tr>';
              html += '<td class="left">' + v.createdAt + '</td>';
              html += '<td class="left">' + v.orderType + '</td>';
              html += '<td class="left">' + v.email + '</td>';
              html += '<td class="left">' +  v.name + '</td>';
              html += '<td class="left">' + v.uuid + '</td>';
              html += '<td class="left">' + v.status + '</td>';
              html += '<td class="left">' + v.currency + '</td>';
              html += '<td class="right">' + v.total + '</td>';
              html += '<td class="center">';
              html += '<a href="<?php echo $view_link; ?>&tid=' + v.id + '&order_id=' + v.order_no + '"><?php echo $text_view; ?></a>';
              html += '</td>';
              html += '</tr>';
            }
          });
	
          $('#searching').hide();
          $('#search_results').append(html).fadeIn();
	      } else {
	        $('#searching').hide();
	        $('#error').html('<i class="fa fa-exclamation-circle"></i> <?php echo $text_no_results; ?>').fadeIn();
	      }
	    }
	  }
	});
});

$('#button-edit').on('click', function() {
  $('#search-box').hide();
  $('#search-input').show();
  $('#button-edit').hide();
  $('#button-search').show();
  $('#searching').show();
  $('#search_results').empty().hide();
  $('#error').empty().hide();
});

$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script></div>
<?php echo $footer; ?>