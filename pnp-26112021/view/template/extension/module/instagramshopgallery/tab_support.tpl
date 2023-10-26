<div class="container-fluid tab-support">
  <div class="row">
    <div class="col-md-4">
      <div class="box-heading">
        <h3><i class="fa fa-user"></i>&nbsp;Your License</h3>
      </div>
      <?php if (empty($setting['LicensedOn'])) { ?>
        <div class="licenseAlerts"></div>
        <div class="licenseDiv"></div>
        <table class="table notLicensedTable">
          <tr>
            <td colspan="2">
              <div class="form-group">
                <label for="moduleLicense"><?php echo $text_please_enter_the_code; ?></label>
                <input type="text" class="licenseCodeBox form-control" placeholder="License Code e.g. XXXXXX-XXXXXX-XXXXXX-XXXXXX-XXXXXX" name="<?php echo $module_setting; ?>[LicenseCode]" id="moduleLicense" value="<?php echo !empty($setting['LicenseCode']) ? $setting['LicenseCode'] : ''; ?>" />
              </div>
              <button type="button" class="btn btn-success btnActivateLicense"><i class="icon-ok"></i> <?php echo $text_activate_license; ?></button>
              <div class="pull-right"><button type="button" class="btn btn-link small-link" onclick="window.open('http://isenselabs.com/users/purchases/')"><?php echo $text_not_having_a_license; ?>.&nbsp;<i class="fa fa-external-link"></i></button></div>
            </td>
          </tr>
        </table>
        <script type="text/javascript">
        var domainraw = location.protocol + '//' + location.host;
        var domain = btoa(domainraw);
        var timenow= parseInt(Date.now()/1000);
        var MID = 'NX7FSK11O8';
        </script>
        <script type="text/javascript" src="//isenselabs.com/external/validate/"></script>
		<div class="alert alert-info stick">	
          <strong>Reminder! </strong><br> Please activate using LFQXYT-03T2BS-J6ORX6-LO1QRD-R73RFD on live site. <br>Not on demolink or localhost.	
        </div>
      <?php } else { ?>
        <input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo $licenseDataBase64; ?>" />
        <input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $setting['LicensedOn']; ?>" />
        <table class="table licensedTable">
          <tr>
            <td><?php echo $text_license_holder; ?></td>
            <td><?php echo $setting['License']['customerName']; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_registered_domains; ?></td>
            <td>
              <ul class="registeredDomains">
                <?php foreach ($setting['License']['licenseDomainsUsed'] as $domain) { ?>
                  <li><i class="fa fa-check"></i>&nbsp;<?php echo $domain; ?></li>
                <?php } ?>
              </ul>
            </td>
          </tr>
          <tr>
            <td><?php echo $text_expires_on; ?> </td>
            <td><?php echo $setting['License']['licenseExpireDate']; ?></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align:center;background-color:#EAF7D9;"><?php echo $text_valid_license; ?> ( <a href="http://isenselabs.com/users/purchases" target="_blank">manage</a> )</td>
          </tr>
        </table>
      <?php } ?>
    </div>

    <div class="col-md-8">
      <div class="box-heading">
        <h3><i class="fa fa-users"></i>&nbsp;<?php echo $text_get_support; ?></h3>
      </div>
      <div class="box-contents">
        <div class="row">
          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="Community support" style="width: 300px;" src="view/image/<?php echo $module['name']; ?>/community.png">
              <div class="caption" style="text-align:center;padding-top:0px;">
                <h3><?php echo $text_community; ?></h3>
                <p><?php echo $text_ask_our_community; ?>. </p>
                <p style="padding-top: 5px;"><a href="http://isenselabs.com/forum" target="_blank" class="btn btn-lg btn-default"><?php echo $text_browse_forums; ?></a></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="thumbnail">
              <img data-src="holder.js/300x200" alt="Ticket support" style="width: 300px;" src="view/image/<?php echo $module['name']; ?>/tickets.png">
              <div class="caption" style="text-align:center;padding-top:0px;">
                <h3><?php echo $text_tickets; ?></h3>
                <p><?php echo $text_open_a_ticket; ?></p>
                <p style="padding-top: 5px;"><a href="<?php echo $supportTicketLink; ?>" target="_blank" class="btn btn-lg btn-default"><?php echo $text_open_ticket_for_real; ?></a></p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="thumbnail">
              <img alt="Pre-sale support" style="width: 300px;" src="view/image/<?php echo $module['name']; ?>/pre-sale.png">
              <div class="caption" style="text-align:center;padding-top:0px;">
                <h3><?php echo $text_pre_sale; ?></h3>
                <p><?php echo $text_pre_sale_desc; ?></p>
                <p style="padding-top: 5px;"><a href="mailto:sales@isenselabs.com?subject=Pre-sale question" target="_blank" class="btn btn-lg btn-default"><?php echo $text_bump_the_sales; ?></a></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<style>
.tab-support .notLicensedTable,
.tab-support .licensedTable {
  table-layout: fixed;
}
.tab-support .notLicensedTable .form-group,
.tab-support .licensedTable .form-group {
  margin: 0 0 15px 0;
}
.tab-support .notLicensedTable ul,
.tab-support .licensedTable ul {
  margin: 0;
  padding: 0;
  list-style: none;
}
.tab-support .notLicensedTable tr > td {
  padding: 10px 0;
}
.tab-support .box-content {
  padding: 10px;
  border: 1px solid #DBDBDB;
  min-height: 300px;
  overflow: auto;
}
.tab-support .notLicensedTable .small-link {
  font-size: 12px;
}
</style>
</div>
