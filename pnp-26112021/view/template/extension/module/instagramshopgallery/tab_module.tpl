<div class="row">
  <div class="col-md-9">

    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-module-title"><?php echo $entry_title; ?></label>
      <div class="col-sm-9">
        <?php foreach ($languages as $lang) { ?>
          <div class="input-group" style="margin-bottom:5px;">
            <div class="input-group-addon"><img src="<?php echo $lang['flag_url']; ?>" title="<?php echo $lang['name']; ?>"></div>
            <input type="text" name="<?php echo $module_setting; ?>[module][title][<?php echo $lang['language_id']; ?>]" value="<?php echo !empty($setting['module']['title'][$lang['language_id']]) ? $setting['module']['title'][$lang['language_id']] : ''; ?>" placeholder="Module heading title" id="input-module-title" class="form-control" />
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-module-status"><?php echo $entry_status; ?></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[module][status]" id="input-module-status" class="form-control">
          <option value="1" <?php echo $setting['module']['status'] == '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
          <option value="0" <?php echo $setting['module']['status'] != '1' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-module-visibility"><?php echo $entry_visibility; ?></label>
      <div class="col-sm-6 col-md-4">
        <select name="<?php echo $module_setting; ?>[module][visibility]" id="input-module-visibility" class="form-control">
          <option value="approve" <?php echo $setting['module']['visibility'] == 'approve' ? 'selected="selected"' : ''; ?>><?php echo $text_only_approved; ?></option>
          <option value="product" <?php echo $setting['module']['visibility'] == 'product' ? 'selected="selected"' : ''; ?>><?php echo $text_have_related; ?></option>
          <option value="both" <?php echo $setting['module']['visibility'] == 'both' ? 'selected="selected"' : ''; ?>><?php echo $text_approve_have_related; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-module-limit"><?php echo $entry_media_limit; ?></label>
      <div class="col-sm-1" style="min-width:100px">
        <input type="number" name="<?php echo $module_setting; ?>[module][limit]" value="<?php echo $setting['module']['limit']; ?>" placeholder="<?php echo $entry_media_limit; ?>" id="input-module-limit" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_extra_image_help; ?>"><?php echo $entry_extra_image; ?></span></label>
      <div class="col-sm-9"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $setting['module']['extra_thumb']; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
        <input type="hidden" name="<?php echo $module_setting; ?>[module][extra_image]" value="<?php echo $setting['module']['extra_image']; ?>" id="input-image" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-extra-link"><span data-toggle="tooltip" title="<?php echo $entry_extra_link_help; ?>"><?php echo $entry_extra_link; ?></span></label>
      <div class="col-sm-9">
        <input type="text" name="<?php echo $module_setting; ?>[module][extra_link]" value="<?php echo $setting['module']['extra_link']; ?>" placeholder="http(s)://" id="input-extra-link" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-module-css"><?php echo $entry_custom_css; ?></label>
      <div class="col-sm-9">
        <textarea class="form-control" name="<?php echo $module_setting; ?>[module][custom_css]" id="input-module-css" placeholder="" rows="8"><?php echo $setting['module']['custom_css']; ?></textarea>
      </div>
    </div>

  </div>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $text_information; ?></div>
      <div class="panel-body">
        <ul class="isl-list">
          <?php foreach ($text_info_module as $item) { ?>
            <li><?php echo $item; ?></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>

