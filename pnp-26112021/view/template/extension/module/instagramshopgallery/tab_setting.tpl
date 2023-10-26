<div class="row">
  <div class="col-md-9">

    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-media-status"><?php echo $entry_global_status; ?></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[status]" id="input-media-status" class="form-control">
          <option value="1" <?php echo $setting['status'] == '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
          <option value="0" <?php echo $setting['status'] != '1' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-validate-media"><?php echo $entry_validate_media; ?></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[validate_media]" id="input-validate-media" class="form-control">
          <option value="1" <?php echo $setting['validate_media'] == '1' ? 'selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
          <option value="0" <?php echo $setting['validate_media'] != '1' ? 'selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 control-label" for="input-api-source"><?php echo $entry_media_source; ?></label>
      <div class="col-sm-6 col-md-3">
        <select name="<?php echo $module_setting; ?>[api_source]" id="input-api-source" class="form-control" data-isl-change="api-source">
          <option value="basic" <?php echo $setting['api_source'] != 'graph' ? 'selected="selected"' : ''; ?>>Account media</option>
          <option value="graph" <?php echo $setting['api_source'] == 'graph' ? 'selected="selected"' : ''; ?>>Hashtag search</option>
        </select>
      </div>
    </div>

    <div class="form-group" style="padding:5px 0"></div>

    <div class="api-source basic" style="display:none;">
      <div class="form-group required">
        <label class="col-sm-3 control-label" for="input-basic-app-id"><?php echo $entry_basic_app_id; ?></label>
        <div class="col-sm-6">
          <input type="text" id="input-basicapp-id" name="<?php echo $module_setting; ?>[basic_app_id]" value="<?php echo $setting['basic_app_id']; ?>" placeholder="<?php echo $entry_basic_app_id; ?>" class="form-control">
        </div>
      </div><div class="hidden" hidden></div>
      <div class="form-group required" style="padding-top:0">
        <label class="col-sm-3 control-label" for="input-basic-app-secret"><?php echo $entry_basic_app_secret; ?></label>
        <div class="col-sm-6">
          <input type="text" id="input-basic-app-secret" name="<?php echo $module_setting; ?>[basic_app_secret]" value="<?php echo $setting['basic_app_secret']; ?>" placeholder="<?php echo $entry_basic_app_secret; ?>" class="form-control">
        </div>
      </div><div class="hidden" hidden></div>
      <div class="form-group required" style="padding-top:0">
        <label class="col-sm-3 control-label"><?php echo $entry_oauth_redirect_uri; ?></label>
        <div class="col-sm-9">
          <div style="margin-top:5px;font-size:12.5px;"><b><?php echo $basic_redirect_uri; ?></b></div>
          <div class="help"><?php echo $entry_oauth_redirect_info . $store['url']; ?></div>
        </div>
      </div><div class="hidden" hidden></div>
      <?php if ($basic_access_token_button || !$setting['basic_access_token']) { ?>
        <div class="form-group required" style="padding-top:0">
          <div class="col-sm-3">&nbsp;</div>
          <div class="col-sm-9">
            <?php if ($basic_access_token_button) { ?>
              <a href="<?php echo $basic_access_token_button; ?>" class="btn btn-primary btn-sm">Get Access Token</a>
            <?php } elseif (!$setting['basic_access_token']) { ?>
              <div class="help mini-noty-red">Fill App ID and App Secret then Save the module to see the "Get Access Token" button.</div>
            <?php } ?>
          </div>
        </div><div class="hidden" hidden></div>
      <?php } ?>
      <div class="form-group required" style="padding-top:0">
        <label class="col-sm-3 control-label" for="input-basic-secret-token"><?php echo $entry_access_token; ?></label>
        <div class="col-sm-9">
          <textarea class="form-control" name="<?php echo $module_setting; ?>[basic_access_token]" id="input-basic-secret-token" placeholder="<?php echo $text_na; ?>" rows="4"><?php echo $setting['basic_access_token']; ?></textarea>
          <input type="text" name="<?php echo $module_setting; ?>[basic_token_expire]" value="<?php echo $setting['basic_token_expire']; ?>" class="hidden" hidden>
          <?php if (!empty($setting['basic_meta'])) { ?>
            <?php foreach ($setting['basic_meta'] as $key => $value) { ?>
              <input type="text" name="<?php echo $module_setting; ?>[basic_meta][<?php echo $key; ?>]" value="<?php echo $value; ?>" class="hidden" hidden>
            <?php } ?>
          <?php } ?>
          <?php if ($setting['basic_token_expire']) { ?>
            <div class="help"><b>Expired:</b> <?php echo $setting['basic_token_expire']; ?> - <a href="<?php echo $extend_token; ?>">Extend Token</a></div>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="api-source graph" style="display:none;">
      <div class="form-group required">
        <label class="col-sm-3 control-label" for="input-graph-app-id"><?php echo $entry_graph_app_id; ?></label>
        <div class="col-sm-6">
          <input type="text" id="input-graph-app-id" name="<?php echo $module_setting; ?>[graph_app_id]" value="<?php echo $setting['graph_app_id']; ?>" placeholder="<?php echo $entry_graph_app_id; ?>" class="form-control">
        </div>
      </div><div class="hidden" hidden></div>
      <div class="form-group required" style="padding-top:0">
        <label class="col-sm-3 control-label" for="input-graph-app-secret"><?php echo $entry_graph_app_secret; ?></label>
        <div class="col-sm-6">
          <input type="text" id="input-graph-app-secret" name="<?php echo $module_setting; ?>[graph_app_secret]" value="<?php echo $setting['graph_app_secret']; ?>" placeholder="<?php echo $entry_graph_app_secret; ?>" class="form-control">
        </div>
      </div><div class="hidden" hidden></div>

      <div class="form-group" style="padding-top:0;<?php echo (!$graph_access_token_button && $setting['basic_access_token'] ? 'padding:0;' : ''); ?>">
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
          <?php if ($graph_access_token_button) { ?>
            <div class="fb-login-button" data-size="medium" data-button-type="continue_with" data-layout="default" data-auto-logout-link="false" data-use-continue-as="false" data-width="" data-scope="public_profile,instagram_basic,pages_show_list,instagram_manage_insights"></div>
          <?php } elseif (!$setting['basic_access_token']) { ?>
            <div class="help mini-noty-red">Fill App ID and App Secret then Save the module to see the "Continue with Facebook" button to get Access Token and make sure to run in secure (Https).</div>
          <?php } ?>
        </div>
<?php if ($setting['api_source'] == 'graph' && $setting['graph_app_id']) { ?>
<script>
window.fbAsyncInit = function() { $(document).trigger('FBloaded'); };
$(document).on('FBloaded', function()
{
  FB.Event.subscribe('auth.authResponseChange', function(response) {
    if (response.status === 'connected') {

      FB.api('/oauth/access_token', 'GET',
        {"grant_type":"fb_exchange_token","client_id":"<?php echo $setting['graph_app_id']; ?>","client_secret":"<?php echo $setting['graph_app_secret']; ?>","fb_exchange_token":response.authResponse.accessToken},
        function(response) {
          $.ajax({
            url: 'index.php?route=<?php echo $module['path']; ?>/graphApiCallback&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&_='+ new Date().getTime(),
            type: 'POST',
            dataType: 'json',
            cache: false,
            data: response,
            success: function(data) {
              if (data.reload) {
                location.reload();
              }
            }
          });
        }
      );
    }
  });
});
</script>
<?php } ?>
      </div><div class="hidden" hidden></div>
      <div class="form-group required" style="padding-top:0">
        <label class="col-sm-3 control-label" for="input-graph-secret-token"><?php echo $entry_access_token; ?></label>
        <div class="col-sm-9">
          <textarea class="form-control" name="<?php echo $module_setting; ?>[graph_access_token]" id="input-graph-secret-token" placeholder="<?php echo $text_na; ?>" rows="4"><?php echo $setting['graph_access_token']; ?></textarea>
          <input type="text" name="<?php echo $module_setting; ?>[graph_token_expire]" value="<?php echo $setting['graph_token_expire']; ?>" class="hidden" hidden>
          <?php if (!empty($setting['graph_meta'])) { ?>
            <?php foreach ($setting['graph_meta'] as $key => $value) { ?>
              <input type="text" name="<?php echo $module_setting; ?>[graph_meta][<?php echo $key; ?>]" value="<?php echo $value; ?>" class="hidden" hidden>
            <?php } ?>
          <?php } ?>
          <?php if ($setting['graph_token_expire']) { ?>
            <div class="help"><b>Expired:</b> <?php echo $setting['graph_token_expire']; ?></div>
          <?php } ?>
        </div>
      </div>

      <div class="form-group required">
        <label class="col-sm-3 control-label" for="input-hashtag"><?php echo $entry_hashtag; ?></label>
        <div class="col-sm-6">
          <div class="input-group">
            <div class="input-group-addon">#</div>
            <input type="text" id="input-hashtag" name="<?php echo $module_setting; ?>[hashtag]" value="<?php echo $setting['hashtag']; ?>" placeholder="Instagram hashtag" class="form-control">
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="col-md-3">
    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $text_information; ?></div>
      <div class="panel-body">
        <ul class="isl-list">
          <?php foreach ($text_info_setting as $item) { ?>
            <li><?php echo $item; ?></li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="api-source basic" style="display:none;">
      <div class="panel panel-default">
        <div class="panel-heading"><?php echo $text_media_source_basic; ?></div>
        <div class="panel-body">
          <ul class="isl-list">
            <?php foreach ($text_info_setting_source_basic as $item) { ?>
              <li><?php echo $item; ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <div class="api-source graph" style="display:none;">
      <div class="panel panel-default">
        <div class="panel-heading"><?php echo $text_media_source_graph; ?></div>
        <div class="panel-body">
          <ul class="isl-list">
            <?php foreach ($text_info_setting_source_graph as $item) { ?>
              <li><?php echo $item; ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>

  </div>
</div>

<div class="row">
  <hr class="isl-hr">
</div>

<div class="row">

  <div class="col-md-9 clearfix">
    <h3 style="display:inline-block"><?php echo $text_moderation; ?></h3>
    <div class="pull-right">
      <select id="js-fetch-source" class="form-control input-sm" style="width:150px; margin-right:15px; display:inline-block;">
        <option value="instagram">Instagram</option>
        <option value="database"><?php echo $text_saved_media; ?></option>
      </select>
      <a class="btn btn-success btn-sm js-fetch" data-isl-fetch="1" style="margin-right:15px"><?php echo $text_fetch; ?></a>
    </div>

    <div class="js-media-container"></div>
    <div class="js-media-notification" style="margin:15px">
      <div class="text-center"><?php echo $text_fetch_info; ?></div>
    </div>
    <div class="text-center">
      <a class="btn btn-default btn-info btn-sm js-fetch-more" data-isl-fetch="1" style="margin-bottom:25px; display:none"><?php echo $text_load_more; ?></a>
    </div>
  </div>

  <div class="col-md-3">
    <?php if ($is_migrate) { ?>
      <div class="panel panel-default panel-warning">
        <div class="panel-heading"><?php echo $text_migration; ?></div>
        <div class="panel-body">
          <ul class="isl-list">
            <?php foreach ($text_info_setting_moderation_migrate as $item) { ?>
              <li><?php echo $item; ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>
    <?php } ?>

    <div class="panel panel-default">
      <div class="panel-heading"><?php echo $text_information; ?></div>
      <div class="panel-body">
        <ul class="isl-list">
          <?php foreach ($text_info_setting_moderation as $item) { ?>
            <li><?php echo $item; ?></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>

</div>
<script>
$(document).ready(function()
{
  $('[data-isl-change]').trigger('change');

  var notyTpl = '<div class="text-center">{content}</div>',
      hashtag = $('#input-hashtag').val();

  // Fetch option
  $('#js-fetch-source').on('change', function() {
    $('.js-fetch').trigger('click');
  });

  // Get media
  $('[data-isl-fetch]').on('click', function() {
    var page = $(this).data('isl-fetch');

    $.ajax({
      url: 'index.php?route=<?php echo $module['path']; ?>/fetch&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&_='+ new Date().getTime(),
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        api     : $('#input-api-source').val(),
        source  : $('#js-fetch-source').val(),
        hashtag : $('#input-hashtag').val(),
        page    : page
      },
      beforeSend: function() {
        if (page == '1') {
          $('.js-media-container').html('');
          $('.js-fetch-more').hide();
        }
        $('.js-media-notification').html(notyTpl.replace('{content}', '<i class="fa fa-spinner fa-spin"></i> Processing..'));
      },
      success: function(data) {
        if (data.error) {
          $('.js-media-notification').html(notyTpl.replace('{content}', '<div class="alert alert-warning" style="margin:0">'+data.output+'</div>'));
          $('.js-fetch-more').fadeOut();
        } else {
          $('.js-media-notification').html('');
          $('.js-media-container').append(data.output);

          if (data.page.show) {
            $('.js-fetch-more').fadeIn();
            $('.js-fetch-more').data('isl-fetch', data.page.next);
          } else {
            $('.js-fetch-more').fadeOut();
          }
        }
      }
    });
  });

  // Remove media from database
  $('.js-media-container').on('click', '[data-isg-remove]', function() {
    var shortcode = $(this).data('isg-remove');

    $.ajax({
      url: 'index.php?route=<?php echo $module['path']; ?>/remove&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&_='+ new Date().getTime(),
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: {
        source    : $('#js-fetch-source').val(),
        shortcode : shortcode,
      },
      beforeSend: function() {
        $('.js-media-remove-' + shortcode).addClass('label-muted');
      },
      success: function(data) {
        if (!data.error) {
          if (data.removed) {
            $('.js-media-item-' + data.shortcode).remove();
          } else {
            $('.js-media-remove-' + data.shortcode).removeClass('label-muted');
            $('.js-media-remove-' + data.shortcode).hide();
          }
        }
      }
    });
  });

  // Load media form when modal is open
  $('#js-media-modal').on('show.bs.modal', function (e) {
    var el = $(e.relatedTarget),
        params = el.data('isl-media');

    $('[data-toggle="tooltip"], .tooltip').tooltip('hide');
    $('#js-media-modal .js-modal-container').load('index.php?route=<?php echo $module['path']; ?>/modalForm&store_id=<?php echo $store_id; ?>&token=<?php echo $token; ?>&_='+ new Date().getTime(), params);
  });
  $('#js-media-modal').on('hidden.bs.modal', function (e) {
    $('#js-media-modal .js-modal-container').html('<div class="text-center" style="padding:100px 20px"><i class="fa fa-spinner fa-spin"></i> Loading..</div>');
  });

  // Save modal
  $('.js-modal-container').on('click', '.js-modal-save', function(e) {
    $.ajax({
      url: 'index.php?route=<?php echo $module['path']; ?>/modalSave&token=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>&_='+ new Date().getTime(),
      type: 'POST',
      dataType: 'json',
      cache: false,
      data: $('#form-modal').serialize(),
      beforeSend: function() {
        $('.js-modal-noty').show().html('<i class="fa fa-spinner fa-spin"></i> Saving..');
      },
      success: function(data) {
        if (!data.error) {
          $('.js-modal-noty').html('<b class="text-success"><?php echo $text_success_save; ?></b>');
          $('.js-media-approve-' + data.shortcode).html(data.approve);
          $('.js-media-relproduct-' + data.shortcode).html(data.related_products);
          $('.js-media-remove-' + data.shortcode).show();
          $('.label-resave-' + data.shortcode).remove();
          $('.label-legacy-' + data.shortcode).remove();

          setTimeout(function() {
            $('#js-media-modal').modal('hide');
          }, 250);
        }
      }
    });
  });
});
</script>

<div id="js-media-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:0;">
      <button type="button" class="close" data-dismiss="modal" style="position:absolute; right:5px; top:2px; z-index:2000;"><span>&times;</span></button>
      <div class="js-modal-container"><div class="text-center" style="padding:100px 20px"><i class="fa fa-spinner fa-spin"></i> Loading..</div></div>
    </div>
  </div>
</div>
