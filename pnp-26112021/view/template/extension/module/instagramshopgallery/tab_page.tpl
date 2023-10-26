<div class="row">
  <div class="col-md-9">

    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-title"><?php echo $entry_title; ?></label>
      <div class="col-sm-9">
        <?php foreach ($languages as $lang) { ?>
          <div class="input-group" style="margin-bottom:5px;">
            <div class="input-group-addon"><img src="<?php echo $lang['flag_url']; ?>" title="<?php echo $lang['name']; ?>"></div>
            <input type="text" name="<?php echo $module_setting; ?>[page][title][<?php echo $lang['language_id']; ?>]" value="<?php echo !empty($setting['page']['title'][$lang['language_id']]) ? $setting['page']['title'][$lang['language_id']] : ''; ?>" placeholder="Page heading title" id="input-module-title" class="form-control" />
          </div>
        <?php } ?>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-status"><?php echo $entry_status; ?></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[page][status]" id="input-page-status" class="form-control">
          <option value="1" <?php echo $setting['page']['status'] == '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
          <option value="0" <?php echo $setting['page']['status'] != '1' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-status"><span data-toggle="tooltip" title="<?php echo $entry_navbar_help; ?>"><?php echo $entry_navbar; ?></span></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[page][navbar]" id="input-page-status" class="form-control">
          <option value="1" <?php echo $setting['page']['navbar'] == '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
          <option value="0" <?php echo $setting['page']['navbar'] != '1' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-visibility"><?php echo $entry_visibility; ?></label>
      <div class="col-sm-6 col-md-4">
        <select name="<?php echo $module_setting; ?>[page][visibility]" id="input-page-visibility" class="form-control">
          <option value="approve" <?php echo $setting['page']['visibility'] == 'approve' ? 'selected="selected"' : ''; ?>><?php echo $text_only_approved; ?></option>
          <option value="product" <?php echo $setting['page']['visibility'] == 'product' ? 'selected="selected"' : ''; ?>><?php echo $text_have_related; ?></option>
          <option value="both" <?php echo $setting['page']['visibility'] == 'both' ? 'selected="selected"' : ''; ?>><?php echo $text_approve_have_related; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-limit"><?php echo $entry_media_limit; ?></label>
      <div class="col-sm-1" style="min-width:100px">
        <input type="number" name="<?php echo $module_setting; ?>[page][limit]" value="<?php echo $setting['page']['limit']; ?>" placeholder="<?php echo $entry_media_limit; ?>" id="input-page-limit" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?php echo $entry_banner_image_help; ?>"><?php echo $entry_banner_image; ?></span></label>
      <div class="col-sm-9"><a href="" id="thumb-banner" data-toggle="image" class="img-thumbnail"><img src="<?php echo $setting['page']['banner_thumb']; ?>" alt="" title="" data-placeholder="<?php echo $no_image; ?>" /></a>
        <input type="hidden" name="<?php echo $module_setting; ?>[page][banner]" value="<?php echo $setting['page']['banner']; ?>" id="input-banner" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-banner-link"><span data-toggle="tooltip" title="<?php echo $entry_extra_link_help; ?>"><?php echo $entry_banner_link; ?></span></label>
      <div class="col-sm-9">
        <input type="text" name="<?php echo $module_setting; ?>[page][banner_link]" value="<?php echo $setting['page']['banner_link']; ?>" placeholder="http(s)://" id="input-banner-link" class="form-control" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-css"><?php echo $entry_custom_css; ?></label>
      <div class="col-sm-9">
        <textarea class="form-control" name="<?php echo $module_setting; ?>[page][custom_css]" id="input-page-css" placeholder="" rows="8"><?php echo $setting['page']['custom_css']; ?></textarea>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-page-css"><?php echo $entry_seo_options; ?></label>
      <div class="col-sm-9">
        <ul class="nav nav-tabs" id="langtabs" role="tablist">
          <?php foreach ($languages as $language) { ?>
            <li><a href="#lang-<?php echo $language['language_id']; ?>" role="tab" data-toggle="tab"><img src="<?php echo $language['flag_url']; ?>" title="<?php echo $language['name']; ?>"/></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <?php foreach ($languages as $language) { ?>
            <div class="tab-pane" id="lang-<?php echo $language['language_id']; ?>">

              <p><?php echo $entry_meta_title; ?><p/>
              <input name="<?php echo $module_setting; ?>[page][meta_title][<?php echo $language['language_id']; ?>]" class="form-control" type="text" value="<?php echo !empty($setting['page']['meta_title'][$language['language_id']]) ? $setting['page']['meta_title'][$language['language_id']] : ''; ?>" />
              <br>

              <p><?php echo $entry_meta_desc; ?></p>
              <textarea name="<?php echo $module_setting; ?>[page][meta_desc][<?php echo $language['language_id']; ?>]" class="form-control" rows="4"><?php echo !empty($setting['page']['meta_desc'][$language['language_id']]) ? $setting['page']['meta_desc'][$language['language_id']] : ''; ?></textarea>
              <br>

              <p><?php echo $entry_meta_keywords; ?></p>
              <input name="<?php echo $module_setting; ?>[page][meta_keyword][<?php echo $language['language_id']; ?>]" class="form-control" type="text" value="<?php echo !empty($setting['page']['meta_keyword'][$language['language_id']]) ? $setting['page']['meta_keyword'][$language['language_id']] : ''; ?>" />
              <br>

              <p><?php echo $entry_url_alias; ?></p>
              <div class="input-group" style="margin:8px auto;">
                <div class="input-group-addon"><?php echo $store['url']; ?></div>
                <input name="<?php echo $module_setting; ?>[page][seo_url][<?php echo $language['language_id']; ?>]" class="form-control" type="text" value="<?php echo !empty($setting['page']['seo_url'][$language['language_id']]) ? $setting['page']['seo_url'][$language['language_id']] : ''; ?>" />
              </div>
              <div class="help">
                Aliasing <code><?php echo $store['url']; ?>index.php?route=<?php echo $module['path']; ?>/page</code>
                <?php if (!empty($setting['page']['seo_url'][$language['language_id']])) { ?>
                  <a href="<?php echo $store['url'] . $setting['page']['seo_url'][$language['language_id']]; ?>" target="_blank"><i class="fa fa-external-link"></i></a>
                <?php } else { ?>
                  <a href="<?php echo $store['url']; ?>index.php?route=<?php echo $module['path']; ?>/page" target="_blank"><i class="fa fa-external-link"></i></a>
                <?php } ?>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>

  </div>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $text_information; ?></div>
      <div class="panel-body">
        <ul class="isl-list">
          <?php foreach ($text_info_page as $item) { ?>
            <li><?php echo $item; ?></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>

