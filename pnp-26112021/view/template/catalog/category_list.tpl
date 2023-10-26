<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				
				<a id="import" class="btn btn-success disabled hidden" disabled="disabled"  data-loading-text="<?php echo $text_loading; ?>" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Upload</a>
				<a id="export" class="btn btn-info disabled hidden" disabled="disabled" ><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download</a>
				
				<script type="text/javascript">
					$(window).load(function(){
						$("#export")
						.removeClass("disabled")
						.removeAttr("disabled")
						.click(function(e){
							e.preventDefault();
							ext = "";
							if($("input[name=\'selected[]\']:checked").length){
								ext = "&" + $("input[name=\'selected[]\']:checked").serialize();
							}
							
							window.open('<?php echo $export_url; ?>' + ext, '_blank');
						});
						
						$("#import")
						.removeClass("disabled")
						.removeAttr("disabled")
						.click(function(e){
							e.preventDefault();
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
										url: 'index.php?route=catalog/category/import&token=<?php echo $token; ?>',
										type: 'post',		
										dataType: 'json',
										data: new FormData($('#form-upload')[0]),
										cache: false,
										contentType: false,
										processData: false,		
										beforeSend: function() {
											$('#button-upload').button('loading');
										},
										complete: function() {
											$('#button-upload').button('reset');
										},	
										success: function(json) {
											if (json['error']) {
												alert(json['error']);
											}
											
											if (json['success']) {
												alert(json['success']);
												location.reload();
											}
										},			
										error: function(xhr, ajaxOptions, thrownError) {
											alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
										}
									});
								}
							}, 500);
						});
					});
					
					
				</script>
				
				<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> <a href="<?php echo $repair; ?>" data-toggle="tooltip" title="<?php echo $button_rebuild; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></a>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
	              <button type="button" id="button-clear" class="btn btn-default pull-right"><i class="fa fa-refresh"></i> <?php echo $button_clear; ?></button>
	              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
            <div class="col-sm-3">
            	<?php /*
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>*/ ?>
            </div>
            
          </div>
        </div>
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left"><?php if ($sort == 'name') { ?>
										<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
									<?php } ?></td>
									<td class="text-right"><?php if ($sort == 'sort_order') { ?>
										<a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
										<?php } else { ?>
										<a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>
									<?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
									<td class="text-right"><?php echo $column_action; ?></td>
								</tr>
							</thead>
							<tbody>
								<?php if ($categories) { ?>
									<?php foreach ($categories as $category) { ?>
										<tr>
											<td class="text-center"><?php if (in_array($category['category_id'], $selected)) { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
												<?php } else { ?>
												<input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
											<?php } ?></td>
											<td class="text-left"><?php echo $category['name']; ?></td>
											<td class="text-right"><?php echo $category['sort_order']; ?></td>
		                  <td class="text-left"><?= $category['status'] == 'Enabled'?'Enabled':'Disabled'; ?>
		                  <?php /*
		                    <label class="switch">
		                      <input type="checkbox" value="<?=$category['category_id']?>" <?= $category['status'] == 'Enabled'?'checked':''; ?> class="toggle-status">
		                      <span class="slider round"></span>
		                    </label>      
		                    */ ?>
		                  </td>
											<td class="text-right">

												<?php if($category['shref']){ ?>
													<a data-href = "<?php echo $category['shref']; ?>" onclick = 'copythis(this);' data-toggle="tooltip" title="Click to copy Url" class="btn btn-warning">
													<i class="fa fa-copy"></i>&nbsp;&nbsp;Promotional&nbsp;Url</a>
												<?php } ?>

												<?php if($category['href']){ ?>
													<a data-href = "<?php echo $category['href']; ?>" onclick = 'copythis(this);' data-toggle="tooltip" title="Click to copy Url" class="btn btn-success">
													<i class="fa fa-copy"></i>&nbsp;&nbsp;Url</a>
												<?php } ?>
												
												<a href="<?php echo $category['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
										</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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


<script type="text/javascript"><!--
  $('#button-clear').on('click', function() {
    var url = 'index.php?route=catalog/category&token=<?php echo $token; ?>';
    window.location= url;
  });
  $('#button-filter').on('click', function() {
  	var url = 'index.php?route=catalog/category&token=<?php echo $token; ?>';

  	var filter_name = $('input[name=\'filter_name\']').val();

  	if (filter_name) {
  		url += '&filter_name=' + encodeURIComponent(filter_name);
  	}

  	// var filter_status = $('select[name=\'filter_status\']').val();

  	// if (filter_status != '*') {
  	// 	url += '&filter_status=' + encodeURIComponent(filter_status);
  	// }

  	location = url;
  });

	$('.toggle-status').on('click', function() {
	  $.ajax({url: 'index.php?route=catalog/category/changeStatus&token=<?php echo $token; ?>&category_id=' + $(this).val()});
	});

	$('input[name=\'filter_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete_filter&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_name\']').val(item['label']);
		}
	});
</script>


</div>
<?php echo $footer; ?>