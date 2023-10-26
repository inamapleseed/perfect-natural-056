<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo "http://docs.marketinsg.com/google_login/"; ?>" target="_blank" data-toggle="tooltip" title="Installation Guide" class="btn btn-primary"><i class="fa fa-file"></i></a>
        <button type="submit" form="form-module" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-module" class="form-horizontal">
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
            <li><a href="#tab-advanced" data-toggle="tab"><?php echo $tab_advanced; ?></a></li>
            <?php //echo $about; ?>
          </ul>
		  <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-10">
				  <select name="googlelogin_status" id="input-status" class="form-control">
					<option value="1"<?php echo $googlelogin_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					<option value="0"<?php echo $googlelogin_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
				  </select>
				</div>
			  </div>
			  <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-client-id"><span data-toggle="tooltip" title="<?php echo $help_client_id; ?>"><?php echo $entry_client_id; ?></span></label>
                <div class="col-sm-10">
                  <?php foreach ($stores as $store) { ?>
                  <div class="input-group"><span class="input-group-addon"><?php echo $store['name']; ?></span>
                    <input type="text" name="googlelogin_client_id[<?php echo $store['store_id']; ?>]" value="<?php echo !empty($googlelogin_client_id[$store['store_id']]) ? $googlelogin_client_id[$store['store_id']] : ''; ?>" class="form-control" />
                  </div><br />
				  <?php } ?>
                </div>
              </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
				<div class="col-sm-10">
				  <select name="googlelogin_customer_group_id" id="input-customer-group" class="form-control"><?php foreach ($customer_groups as $customer_group) { ?>
					<?php if ($customer_group['customer_group_id'] == $googlelogin_customer_group_id) { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
					<?php } else { ?>
					<option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
					<?php } ?>
			      <?php } ?></select>
				</div>
			  </div>
			</div>
            <div class="tab-pane" id="tab-design">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-heaidng"><?php echo $entry_heading; ?></label>
                <div class="col-sm-10">
				  <?php foreach ($languages as $language) { ?>
					<div class="input-group"><span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
					  <input type="text" name="googlelogin_heading[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($googlelogin_heading[$language['language_id']]) ? $googlelogin_heading[$language['language_id']] : ''; ?>" class="form-control" />
					</div><br />
				  <?php } ?>
                </div>
              </div>
			  <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-text"><?php echo $entry_text; ?></label>
                <div class="col-sm-10">
				  <?php foreach ($languages as $language) { ?>
					<div class="input-group"><span class="input-group-addon"><img src="<?php echo version_compare(VERSION, '2.2.0.0', '<') ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>" title="<?php echo $language['name']; ?>" /></span>
					  <input type="text" name="googlelogin_text[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($googlelogin_text[$language['language_id']]) ? $googlelogin_text[$language['language_id']] : ''; ?>" class="form-control" />
					</div><br />
				  <?php } ?>
                </div>
              </div>
              <div class="form-group required"> 
              	<label class="col-sm-2 control-label" for="input-button-width"><?php echo $entry_button_width; ?></label>
              	<div class="col-sm-10">
              		<input type="text" name="googlelogin_button_width" class="form-control" id="input-button-width" value="<?php echo $googlelogin_button_width; ?>" />
              	</div>
              </div>
              <div class="form-group required"> 
              	<label class="col-sm-2 control-label" for="input-button-height"><?php echo $entry_button_height; ?></label>
              	<div class="col-sm-10">
              		<input type="text" name="googlelogin_button_height" class="form-control" id="input-button-height" value="<?php echo $googlelogin_button_height; ?>" />
              	</div>
              </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-box"><?php echo $entry_box; ?></label>
				<div class="col-sm-10">
				  <select name="googlelogin_box" id="input-box" class="form-control">
					<option value="1"<?php echo $googlelogin_box ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
					<option value="0"<?php echo $googlelogin_box ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-align"><?php echo $entry_align; ?></label>
				<div class="col-sm-10">
				  <select name="googlelogin_align" id="input-align" class="form-control">
					<option value="LEFT"<?php echo $googlelogin_align == 'LEFT' ? ' selected="selected"' : ''; ?>>LEFT</option>
					<option value="CENTER"<?php echo $googlelogin_align == 'CENTER' ? ' selected="selected"' : ''; ?>>CENTER</option>
					<option value="RIGHT"<?php echo $googlelogin_align == 'RIGHT' ? ' selected="selected"' : ''; ?>>RIGHT</option>
				  </select>
				</div>
			  </div>
            </div>
            <div class="tab-pane" id="tab-advanced">
              <div class="form-group">
				<label class="col-sm-2 control-label" for="input-target-location"><span data-toggle="tooltip" title="<?php echo $help_target_location; ?>"><?php echo $entry_target_location; ?></span></label>
				<div class="col-sm-10">
				  <input type="text" name="googlelogin_target_location" class="form-control" id="input-target-location" value="<?php echo $googlelogin_target_location; ?>" />
				</div>
              </div>
              <div class="form-group">
				<label class="col-sm-2 control-label" for="input-target-action"><?php echo $entry_target_action; ?></label>
				<div class="col-sm-10">
				  <select name="googlelogin_target_action" id="input-target-action" class="form-control">
					<option value="APPEND"<?php echo $googlelogin_target_action == 'APPEND' ? ' selected="selected"' : ''; ?>>APPEND</option>
					<option value="PREPEND"<?php echo $googlelogin_target_action == 'PREPEND' ? ' selected="selected"' : ''; ?>>PREPEND</option>
					<option value="AFTER"<?php echo $googlelogin_target_action == 'AFTER' ? ' selected="selected"' : ''; ?>>AFTER</option>
					<option value="BEFORE"<?php echo $googlelogin_target_action == 'BEFORE' ? ' selected="selected"' : ''; ?>>BEFORE</option>
				  </select>
				</div>
              </div>
              <div class="form-group">
				<label class="col-sm-2 control-label" for="input-additional-javascript"><?php echo $entry_additional_javascript; ?></label>
				<div class="col-sm-10">
				  <textarea name="googlelogin_additional_javascript" class="form-control" id="input-additional-javascript" rows="4"><?php echo $googlelogin_additional_javascript; ?></textarea>
				</div>
              </div>
            </div>
			<?php echo $tab; ?>
		  </div>
		</form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>