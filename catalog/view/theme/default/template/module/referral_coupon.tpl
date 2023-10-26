<style type="text/css">
a {
  cursor: pointer;
}
.referral-coupon-email {
  display: none;
  background-color: #f5f5f5;
}
</style>
<div class="panel panel-default referral-coupon">
  <div class="panel-heading panel-title"><?php echo $lng['heading_title']; ?></div>
  <div class="panel-body">
    <?php if ($is_logged) { ?>
    <div class="referral-coupon-info"><?php echo $info; ?></div>
    <div class="referral-coupon-form table-responsive">
      <h4><?php echo $lng['heading_referral_form']; ?></h3>
      <div class="text-info">
        <?php if ($sending_limit['remain'] <= 0) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $sending_limit['text']; ?></div>
        <?php } else { ?>
        <?php echo $sending_limit['text']; ?>
        <?php } ?>
      </div>
      <form>
        <div class="form-group">
          <?php echo $lng['entry_message']; ?><br/>
          <textarea name="referrer_message" class="referrer_message form-control"></textarea>
        </div>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td><?php echo $lng['column_name']?></td>
              <td><?php echo $lng['column_email']?></td>
              <td></td>
            </tr>
          </thead>
          <tbody></tbody>
          <tfoot>
            <tr>
              <td colspan="2">
                <button class="btn btn-success" onclick="sendReferralCoupon(this);" type="button"><i class="fa fa-envelope"></i> <?php echo $lng['button_send']; ?></button>
                <button type="button" class="btn btn-info" onclick="$('.referral-coupon-email').toggle();"><?php echo $lng['text_sample_email']; ?></button>
              </td>
              <td><button class="btn btn-primary" onclick="addRow(this);" type="button"><i class="fa fa-plus"></i> <?php echo $lng['button_add']; ?></button></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
    <?php } else { ?>
    <?php echo $lng['html_login']; ?>
    <?php } ?>
    <div class="referral-coupon-email table-bordered"><?php echo $sample_email; ?></div>
    <div class="referral-coupon-table table-responsive"></div>
  </div>
</div>
<script type="text/javascript">
addRow($('.referral-coupon-form table tbody'));

getReferrals(1);

function removeRow(obj) {
  $(obj).parents('tr').remove();
} //removeRow end

function getReferrals(page) {
  $('.referral-coupon-table').load('index.php?route=module/referral_coupon/getReferrals&page=' + page);
} //getReferrals end

function addRow(obj) {
  html = '<tr class="referee">';
  html += '  <td><input type="text" name="referee_name[]" class="form-control referee_name" /></td>';
  html += '  <td><input type="text" name="referee_email[]" class="form-control referee_email" /></td>';
  html += '  <td><button class="btn btn-default" type="button" onclick="removeRow(this);"><i class="fa fa-minus"></i> <?php echo $lng['button_remove']; ?></button></td>';
  html += '</tr>';
  
  $(obj).parents('table').children('tbody').append(html);
} //addRow end

function sendReferralCoupon(obj) {
  $(obj).parents('form').find('.alert').remove();
  
  error = 0;
  
  $(obj).parents('table').find('tr.referee').each(function() {
    referee_name = $(this).find('input.referee_name');
    referee_email = $(this).find('input.referee_email');
    
    if (referee_name.val().length < 1 || referee_name.val().length > 64) {
      $(referee_name).after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $lng['error_name']; ?></div>');
      error++;
    }
    
    if (!checkEmailFormat(referee_email.val())) {
      $(referee_email).after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $lng['error_email_format']; ?></div>');
      error++;
    }
  });
  
  if (error) return false;
  
  $.ajax({
    url: 'index.php?route=module/referral_coupon/sendReferral',
    data: $(obj).parents('form').serialize(),
    dataType: 'json',
    beforeSend: function() {
      $(obj).button('loading');
    },
    complete: function() {
      getReferrals(1);
      $(obj).button('reset');
    },
    error: function(xhr, ajaxOptions, thrownError) {
			console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
		},
		success: function(json) {
		  $.each(json.referee, function(index, referee) {
	      if (referee.sending_limit.remain <= 0) {
	        $(obj).parents('.referral-coupon-form').find('.text-info').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + referee.sending_limit.text + '</div>');
	        return false;
	      } else {
	        $(obj).parents('.referral-coupon-form').find('.text-info').html(referee.sending_limit.text);
	        
	        $(obj).parents('table').find('tr.referee').each(function() {
	          if ($(this).find('input.referee_email').val() == referee.email) {
	            if (referee.error.email_existed) {
	              $(this).find('input.referee_email').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + referee.error.email_existed + '</div>');
	            } else {
	              $(this).find('input.referee_email').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + referee.success + '</div>');
	            }
	            return false;
	          }
	        });
	      }
	    });
		}
  });
} //sendReferralCoupon end

function checkEmailFormat(email) {
  email_filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
  return email_filter.test(email);
} //checkEmailFormat end
</script>