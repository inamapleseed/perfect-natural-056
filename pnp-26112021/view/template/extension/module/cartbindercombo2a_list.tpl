<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
   <div class="container-fluid">
           <div class="pull-right touragain">
            <a data-toggle="tooltip" title="Display Promotions Using Add Offers Page" class="btn btn-primary addoffers" target="_blank" href="<?php echo $addoffers; ?>"><i class="fa fa-star"></i> <?php echo $text_addoffers; ?></a>
             <a data-toggle="tooltip" title="Learn how to use this combination" class="btn btn-primary documentation" target="_blank" href="http://blog.cartbinder.com/documentation/buy-x-from-category-and-get-y-offer-from-same-category/"><i class="fa fa-book"></i> Documentation</a>
         </div>
    </div>
    </div>
  <div class="page-header">
    <div class="container-fluid">
          <h2><?php echo $heading_title; ?></h2><br>
          <medium><i><?php echo $headerinfo2; ?></i></medium>
    </div>
  </div>
    <div class="page-header">
   <div class="container-fluid">
      <div class="pull-right">
         <a href="<?php echo $insert; ?>" class="btn btn-success newoffer"><i class="fa fa-plus"></i> Insert New Offer</a>
        <a onclick="$('#form').submit();" class="btn btn-danger delete"><i class="fa fa-trash-o"></i> Delete</a>
         <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
          <h3 class="panel-title offerlist"><i class="fa fa-list"></i> <?php echo $headerinfo; ?></h3>
        </div>
           <div class="panel-body">
              <div class="well">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-name"><?php echo $text_name; ?></label>
                      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-status"><?php echo $text_status; ?></label>
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
                    </div>  
                  </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-4 pull-right">
                    <button type="button" id="button-filter" onclick="filter();" class="btn btn-primary pull-right filter"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
                  </div>
                </div>
              </div>
            </div>
             <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="text-center offername"><?php if ($sort == 'name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $text_name; ?></a>
                <?php } ?></td>
              <td class="text-center productstoadd"><?php echo $text_productstoadd; ?></td>
              <td class="text-center primaryquantity"><?php echo $text_primaryquantity; ?></td>
              <td class="text-center secondaryquantity"><?php echo $text_secondaryquantity; ?></td>
              <td class="text-center type"><?php echo $text_type; ?></td>
              <td class="text-center discountlist"><?php echo $text_discountlist; ?></td>
              <td class="text-center total"><?php echo $text_total; ?></td>
              <td class="text-center offersapplied"><?php echo $text_offersapplied; ?></td>
              <td class="text-center status"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $text_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $text_status; ?></a>
                <?php } ?></td>
              <td class="text-center action"><?php echo $column_action; ?></td>
            </tr>
          </thead>
        <tbody>

          <?php if ($cartbindercombo2as) { ?>
          <?php foreach ($cartbindercombo2as as $cartbindercombo2) { ?>
          <tr>
            <td style="text-align: center;">
              <input type="checkbox" name="selected[]" value="<?php echo $cartbindercombo2['id']; ?>"/>
             </td>
            <td class="text-center"><h4><b><?php echo $cartbindercombo2['name']; ?></b></h4></td>
            <td class="text-center"><?php echo html_entity_decode($cartbindercombo2['primarycategories']); ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['primaryquant']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['secondaryquant']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['type']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['discount']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['total']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['offersapplied']; ?></td>
            <td class="text-center"><?php echo $cartbindercombo2['status']; ?></td>
            <td class="text-center"><?php foreach ($cartbindercombo2['action'] as $action) { ?>
            <a href="<?php echo $action['href']; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $action['text']; ?></a>
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
      </form>
  <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=module/cartbindercombo2a&token=<?php echo $token; ?>';
  
  var filter_name = $('input[name=\'filter_name\']').val();
  
  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_status = $('select[name=\'filter_status\']').val();
  
  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  } 


  location = url;
}
</script> 
<script type="text/javascript"><!--
$('.well input').keydown(function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});</script>

<script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=module/cartbindercombo2a/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['id']
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
<script type="text/javascript" async src="view/javascript/jquery/remodal.js"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans' async rel='stylesheet' type='text/css'>
<?php echo $footer; ?>