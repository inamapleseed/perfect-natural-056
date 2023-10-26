<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
  <div id="fb-root"></div>
  <?php if ($setting['api_source'] == 'graph' && $setting['graph_app_id']) { ?>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=<?php echo $setting['graph_app_id']; ?>&autoLogAppEvents=1" nonce="XQk8J0Z4"></script>
  <?php } ?>

  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>

      <div class="pull-right">
        <button onclick="$('#form-instagramshop-gallery').submit();" type="submit" class="save-changes btn btn-primary" data-toggle="tooltip" title="<?php echo $button_save; ?>"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"><i class="fa fa-reply"></i></a>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <?php echo $unlincensedHtml; ?>

    <?php if ($error_warning) { ?>
      <div class="alert alert-danger autoSlideUp"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <script>$('.autoSlideUp').delay(4000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
    <?php } ?>

    <div class="panel panel-default panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-instagramshop-gallery" class="form-horizontal">
        <div class="tab-navigation form-inline" style="position:relative">
          <ul class="nav nav-tabs mainMenuTabs" id="mainTabs">
            <li><a href="#photos-setting" data-toggle="tab"><?php echo $text_main_setting; ?></a></li>
            <li><a href="#module-setting" data-toggle="tab"><?php echo $text_module; ?></a></li>
            <li><a href="#page-setting" data-toggle="tab"><?php echo $text_page; ?></a></li>
            <li><a href="#isense_support" data-toggle="tab"><?php echo $text_support; ?></a></li>
          </ul>

          <div style="position:absolute; top:11px; right:0;">
            <div class="form-group" style="margin:0; padding:0;">
              <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo $store['name']; ?>&nbsp;<span class="caret"></span></button>
              <ul class="dropdown-menu dropdown-menu-right">
                <?php foreach ($stores as $store) { ?>
                  <li><a href="index.php?route=<?php echo $module['path']; ?>&store_id=<?php echo $store['store_id']; ?>&token=<?php echo $token; ?>"><?php echo $store['name']; ?></a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>

        <div class="tabbable">
          <div class="tab-content">
              <div id="photos-setting" class="tab-pane fade"><?php echo $tab_main_setting; ?></div>
              <div id="module-setting" class="tab-pane fade"><?php echo $tab_module_setting; ?></div>
              <div id="page-setting" class="tab-pane fade"><?php echo $tab_page_setting; ?></div>
              <div id="isense_support" class="tab-pane fade"><?php echo $tab_support; ?></div>
          </div>
        </div>
      </form>

    </div> <!-- /.panel -->

  </div>
</div>

<?php echo $footer; ?>

<style>
.page-header { border: 0; }
.panel-body .panel-heading { font-weight:bold; font-size:14px; padding:7px 10px; }
.panel-body .panel-body { padding:10px; }
.panel-body label { font-size: 12.8px; }
.isl-list { padding-left:15px; margin:0;}
.isl-list li + li { margin-top: 4px; }
.isl-hr { margin:35px 0 25px; border-top:1px solid #ccc; border-bottom:5px solid #f4f4f4; }
.isl-table { margin: 20px 0; }
.js-media-container { overflow:hidden;margin:10px 0 25px; padding:20px 0 0; border-top:1px solid #ddd; }
.js-media-container [class*=col-] { padding:10px 8px; }
.js-media-container [class*=col-]:hover { background:#e4e4e4; }
.cursor-pointer { cursor: pointer; }
.label-muted { background-color: #aaa; }
.help { font-size: 12px; color: #777;}
.mini-noty-red { color:#c7254e; background:#ffe7e7; padding:6px 8px; border-left:2px solid #d00; }
* + .help { margin-top: 5px; }
@media (max-width: 992px) {
  .js-modal-container .row {
    margin: 0;
  }
  .js-modal-container .row .col-md-6:first-child {
    padding: 0;
  }
}
#container {
  background: #fbfbfb;
}
.mainMenuTabs {
  background: #f8f8f8;
  padding: 10px 15px 0 15px;
  margin: -15px -15px 25px -15px;
}
.nav-tabs > li > a {
  color: #555;
  border-radius: 2px 2px 0 0;
}
.panel-default {
  border: 1px solid #dcdcdc;
  border-top: 1px solid #dcdcdc;
}
.panel-default .panel-default {
  border: 1px solid #dcdcdc;
  border-top: 2px solid #bbb;
}
.panel-warning {
  border-color: #f9c59d !important;
  border-top-color: #ecaa77 !important;
}
.panel-warning .panel-heading {
  border-color: #f9c59d !important;
  background-color: #fef8f4;
}
.modal-content {
  border: 0;
}
.modal-content label {
  display: block;
  font-weight: normal;
  margin: 0;
}
.modal-content label + label {
  margin-top: 5px;
}

/* Auto-break bootstrap row */
@media (max-width:991px) {
  /* every 5th element falls in new row */
  .js-media-item:nth-child(4n+1) {
    clear:left;
  }
}
@media (min-width:992px) {
  /* every 7th element falls in new row */
  .js-media-item:nth-child(6n+1) {
    clear:left;
  }
}
</style>

<script>
$('#mainTabs a:first').tab('show'); // Select first tab
$('#langtabs a:first').tab('show');
if (window.localStorage && window.localStorage['currentTab']) {
  $('.mainMenuTabs a[href="'+window.localStorage['currentTab']+'"]').tab('show');
}
$('.mainMenuTabs a[data-toggle="tab"]').click(function() {
  if (window.localStorage) {
    window.localStorage['currentTab'] = $(this).attr('href');
  }
});

// Select input change, show class match it's value
$('[data-isl-change]').on('change', function() {
  var selector = $(this).data('isl-change');
  $('.' + selector).hide();
  $('.' + $(this).val()).show();
});
</script>
