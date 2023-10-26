<!-- Google Login for OpenCart http://www.marketinsg.com -->
<div id="marketing-google-login">
  <meta name="google-signin-client_id" content="<?php echo $googlelogin_client_id; ?>">
  <?php if ($box) { ?>
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo $heading_title; ?></div>
  <?php } ?>
  <div class="box-content">
    <div style="text-align:<?php echo strtolower($align); ?>;padding:10px;">
      <div id="marketinsg-glogin" align="<?php echo strtolower($align); ?>"></div>
	  <?php if ($text) { ?>
	    <br /><br />
	    <?php echo $text; ?>
	  <?php } ?>
	</div>
  </div>
  <?php if ($box) { ?>
  </div>
  <?php } ?>
</div>

<?php if ($google_error) { ?>
<script type="text/javascript">
alert('<?php echo addslashes($google_error); ?>');
</script>
<?php } ?>
<?php if ($target_location) { ?>
<script type="text/javascript">
$(document).ready(function() {
    var html = $('#marketing-google-login').html();
    
    $('#marketing-google-login').html('');
    
    $('<?php echo $target_location; ?>').<?php echo strtolower($target_action); ?>(html);
    
    <?php echo $additional_javascript; ?>
});
</script>
<?php } ?>
<script type="text/javascript">
function onSuccess(googleUser) {
    var profile = googleUser.getBasicProfile();
    var id_token = googleUser.getAuthResponse().id_token;
    
    googleUser.disconnect();
  
    $.ajax({
        url: 'index.php?route=extension/module/googlelogin/redirect',
        type: 'post',
        data: {google_token: id_token},
        dataType: 'json',
        success: function(json) {
            if (typeof json['success'] !== 'undefined') {
                location = json['success'];
            } else {
                location.reload();
            }
        },
        error: function() {
            location.reload();
        }
    });
}

function onFailure(error) {
    console.log(error);
}

function renderButton() {
    gapi.signin2.render('marketinsg-glogin', {
        'scope': 'profile email',
        'width': <?php echo $googlelogin_button_width; ?>,
        'height': <?php echo $googlelogin_button_height; ?>,
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSuccess,
        'onfailure': onFailure
    });
}
</script>
<script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>