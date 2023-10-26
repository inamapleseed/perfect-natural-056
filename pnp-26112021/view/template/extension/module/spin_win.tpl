<?php  echo $header; ?><?php echo $column_left;?>
<div id="content" class="bootstrap">
   <div class="page-header">
      <div class="container-fluid">
         <div class="pull-right">    
            <button type="submit" form="form-exit" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
         </div>
         <h1><?php echo $heading_title; ?></h1>
         <center>
            <div class="pull-right">
               <div class="form-group">
                  <select name="spinwin_store" class="form-control fixed-width-xl" id="spinwin_store" onchange="return getStoreUrl(this.value);" required="true">
                     <?php foreach($stores as $value){   
                        ?>
                     <option value="<?php echo $value['store_id'];?>"<?php if(isset($_GET['store_id'])?$_GET['store_id']:'0'==$value['store_id']){ ?>selected="selected"<?php }?>><?php echo $value['name'];?></option>
                     <?php
                        }?> 
                  </select>
               </div>
            </div>
         </center>
      </div>
   </div>
   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-exit" class="form-horizontal">
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
        <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $general;?></a></li>
                     <li><a href="#tab-display" data-toggle="tab"><?php echo $display;?></a></li>
                     <li><a href="#tab-look" data-toggle="tab"><?php echo $css;?></a></li>
                     <li><a href="#tab-text" data-toggle="tab"><?php echo $rule_text;?></a></li>
                     <li><a href="#tab-discount" data-toggle="tab" id="discount_offer"><?php echo $discount;?></a></li>
                     <li><a href="#tab-mail" data-toggle="tab"><?php echo $marketing;?></a></li>
                     <li><a href="#tab-email" data-toggle="tab"><?php echo $email_sett;?></a></li>
                     <li><a href="#tab-stat" data-toggle="tab" id="statistic"><?php echo $statistic;?></a></li>
        </ul>
         <div class="row">
            <div class="col-lg-12 col-md-9">
               <div class="tab-content">
                  <div class="tab-pane active" id="tab-general">
                     <div class="panel panel-default" id="fieldset_form">
                        <div class="panel-heading">
                           <h3 class="panel-title"> <?php echo $general;?></h3>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $entry_enable_descp; ?>">
                              <?php echo $entry_enable; ?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="form-group">
                                    <div class="col-sm-6">
                                       <select name="spinwin_enable" id="spinwin_enable" class="form-control">
                                          <option value="1"<?php  if ($spinwin_enable) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                          <option value="0"<?php  if (!$spinwin_enable) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $recheck_descp; ?>">
                              <?php echo $recheck; ?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="form-group">
                                    <div class="col-sm-6">
                                       <select name="spinwin_recheck" id="spinwin_recheck" class="form-control">
                                          <option value="1"<?php  if ($spinwin_recheck) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                          <option value="0"<?php  if (!$spinwin_recheck) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $show_pullout_descp; ?>" data-original-title="<?php echo $show_pullout_descp; ?>">
                              <?php echo $show_pullout; ?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="form-group">
                                    <div class="col-sm-6">
                                       <select name="spinwin_show_pullout" id="spinwin_show_pullout" class="form-control">
                                          <option value="1"<?php  if ($spinwin_show_pullout) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                          <option value="0"<?php  if (!$spinwin_show_pullout) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $firework_descp; ?>" data-original-title="<?php echo $firework_descp; ?>">
                              <?php echo $firework; ?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="form-group">
                                    <div class="col-sm-6">
                                       <select name="spinwin_show_firework" id="spinwin_show_firework" class="form-control">
                                          <option value="1"<?php  if ($spinwin_show_firework) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                          <option value="0"<?php  if (!$spinwin_show_firework) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group required" style="display:none">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $check_interval; ?>" data-original-title="<?php echo $check_interval; ?>"> <?php echo $interval; ?></span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group">
                                    <input type="text" name="spinwin_interval" id="spinwin_interval" value="<?php echo $spinwin_interval; ?>" class="form-control" required="true" number="true" digit="true">
                                 </div>
                                 <!--p class="help-block"><?php echo $check_interval; ?></p-->
                              </div>
                           </div>
                           <div class="form-group required">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $check_expiration; ?>"> <?php echo $expiration; ?></span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group">
                                    <input type="text" name="spinwin_expiration" id="spinwin_expiration" value="<?php echo $spinwin_expiration; ?>" class="form-control" required="true" number="true" digit="true" min="0">
                                 </div>
                                 <!--p class="help-block"><?php echo $check_expiration; ?></p-->
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $custom_css_descp; ?>" required="true"><?php echo $custom_css; ?></span>
                              </label>
                              <div class="col-lg-9">
                                 <textarea name="spinwin_css" id="newspinwinup[custom_css]" cols="9" rows="5" class="textarea-autosize form-control" style="overflow: hidden; word-wrap: break-word; resize: none; height: 99px;" ><?php echo $spinwin_css; ?></textarea>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $custom_js_descp; ?>"><?php echo $custom_js; ?></span>
                              </label>
                              <div class="col-lg-9">
                                 <textarea name="spinwin_js" id="newspinwinup[custom_js]" cols="9" rows="5" class="textarea-autosize form-control" style="overflow: hidden; word-wrap: break-word; resize: none; height: 99px;"><?php echo $spinwin_js; ?></textarea>
                              </div>
                           </div>
                        </div>
                        <div class="panel-footer">
                           <button type="submit" value="1" id="configuration_form_submit_btn_1" name="submitspinwinupmodule"  class="submit_lookandfeelsettings btn btn-default">
                           <i class="fa fa-save"></i> <?php echo $button_save; ?>
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-display">
                     <div class="panel panel-default" id="fieldset_form">
                        <div class="panel-heading">
                           <h3 class="panel-title"> <?php echo $display;?></h3>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $screen_descp;?>">
                              <?php echo $screen;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_screen" class="form-control fixed-width-xl" id="spin_wheel[min_screen_size]">
                                    <option value="320_480" <?php  if ($spinwin_fixtime=='320_480') { ?> selected="selected"<?php } ?>><?php echo $smartphone;?></option>
                                    <option value="768_1024"<?php  if ($spinwin_screen=='768_1024') { ?> selected="selected"<?php } ?>><?php echo $tabletp;?></option>
                                    <option value="1024_768"<?php  if ($spinwin_screen=='1024_768') { ?> selected="selected"<?php } ?>><?php echo $tabletl;?></option>
                                    <option value="1366_768"<?php  if ($spinwin_screen=='1366_768') { ?> selected="selected"<?php } ?>><?php echo $laptop;?></option>
                                    <option value="1600_1080"<?php  if ($spinwin_screen=='1600_1080') { ?> selected="selected"<?php } ?>><?php echo $desktop;?></option>
                                    <option value="6"<?php  if ($spinwin_screen=='6') { ?> selected="selected"<?php } ?>><?php echo $only;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $freq_descp;?>">
                              <?php echo $freq;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_freq" class="form-control fixed-width-xl" id="spin_wheel[max_display_freq]">
                                    <option value="0" <?php  if ($spinwin_freq=='0') { ?> selected="selected"<?php } ?>><?php echo $everyv;?></option>
                                    <option value="1"<?php  if ($spinwin_freq=='1') { ?> selected="selected"<?php } ?>><?php echo $hourv;?></option>
                                    <option value="2"<?php  if ($spinwin_freq=='2') { ?> selected="selected"<?php } ?>><?php echo $dayv;?></option>
                                    <option value="3"<?php  if ($spinwin_freq=='3') { ?> selected="selected"<?php } ?>><?php echo $weekv;?></option>
                                    <option value="4"<?php  if ($spinwin_freq=='4') { ?> selected="selected"<?php } ?>><?php echo $monthv;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $hide_descp;?>">
                              <?php echo $hide;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_hide" class="form-control fixed-width-xl" id="spin_wheel[hide_after]">
                                    <option value="1" <?php  if ($spinwin_hide=='1') { ?> selected="selected"<?php } ?>><?php echo $always;?></option>
                                    <option value="10"<?php  if ($spinwin_hide=='10') { ?> selected="selected"<?php } ?>><?php echo $sec10;?></option>
                                    <option value="20"<?php  if ($spinwin_hide=='20') { ?> selected="selected"<?php } ?>><?php echo $sec20;?></option>
                                    <option value="30"<?php  if ($spinwin_hide=='30') { ?> selected="selected"<?php } ?>><?php echo $sec30;?></option>
                                    <option value="60"<?php  if ($spinwin_hide=='60') { ?> selected="selected"<?php } ?>><?php echo $sec40;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $fixtime_descp; ?>">
                              <?php echo $fixtime; ?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_fixtime" id="spinwin_fixtime" class="form-control" onchange="return fixtime(this.value);">
                                    <option value="1"<?php  if ($spinwin_fixtime) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                    <option value="0"<?php  if (!$spinwin_fixtime) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group hide required" id="fixtime_active">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $fix_active_descp;?>"><?php echo $fix_active;?> </span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group date">
                                    <input name="spinwin_fixtimeactive" value="<?php echo $spinwin_fixtimeactive; ?>" placeholder="<?php echo $fix_active;?>" data-date-format="YYYY-MM-DD HH:mm" id="spinwin_fixtimeactive" class="form-control" type="text" required="true">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group hide required" id="fixtime_expire">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $fix_expire_descp;?>"> <?php echo $fix_expire;?></span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group date">
                                    <input name="spinwin_fixtimeexpire" value="<?php echo $spinwin_fixtimeexpire; ?>" placeholder="<?php echo $fix_expire;?>" data-date-format="YYYY-MM-DD HH:mm" id="spinwin_fixtimeexpire" class="form-control" type="text" required="true">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $where_descp;?>">
                              <?php echo $where;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_where_display" class="form-control fixed-width-xl" onchange='return pageselect(this.value)' id="spinwin_where_display">
                                    <option value="1" <?php  if ($spinwin_where_display==1) { ?> selected="selected"<?php } ?>><?php echo $all_page;?></option>
                                    <option value="2"<?php  if ($spinwin_where_display==2) { ?> selected="selected"<?php } ?>><?php echo $selected_page;?></option>
                                    <option value="3"<?php  if ($spinwin_where_display==3) { ?> selected="selected"<?php } ?>><?php echo $no_page;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group hide required" id="selectpage" >
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $sel_pages;?>">
                              <?php echo $sel_pages;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_selectpages[]" class="form-control fixed-width-xl" multiple id="spinwin_selectpages" required="true">
                                    <?php foreach($layouts as $layout){   
                                       ?>
                                    <option value="<?php echo $layout['layout_id'];?>"<?php if(in_array($layout['layout_id'],$spinwin_selectpages) ) { ?> selected="selected" <?php }?>><?php echo $layout['name'];?></option>
                                    <?php
                                       }?>  
                                 </select>
                              </div>
                           </div>
                           <!--div class="form-group">
                              <label class="control-label col-lg-3">
                                <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="Select who to show this pop up.">
                                  <?php echo $who_to;?>
                                </span>
                              </label>
                              <div class="col-lg-9">
                                <select name="spinwin_visit" class="form-control fixed-width-xl" id="spinwin_visit">
                                  <option value="1" <?php  if ($spinwin_visit==1) { ?> selected="selected"<?php } ?>><?php echo $all_visit;?></option>
                                  <option value="2"<?php  if ($spinwin_visit==2) { ?> selected="selected"<?php } ?>><?php echo $new_visit;?></option>
                                  <option value="3"<?php  if ($spinwin_visit==3) { ?> selected="selected"<?php } ?>><?php echo $return_visit;?></option>
                                </select>
                              </div>
                              </div-->
                            <div class="form-group">
                                <label class="control-label col-lg-3">
                                    <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $when_desc;?>">
                                    <?php echo $when;?>
                                    </span>
                                </label>
                                <div class="col-lg-9">
                                   <select name="spinwin_when_display" class="form-control fixed-width-xl" onchange='return whenselect(this.value)' id="spinwin_when_display">
                                        <option value="1" <?php if ($spinwin_when_display==1) { ?> selected="selected"<?php  } ?>><?php echo $when_immediately ?></option>
                                        <option value="2"<?php  if ($spinwin_when_display==2) { ?> selected="selected"<?php  } ?>><?php echo $when_time ?></option>
                                        <option value="3"<?php  if ($spinwin_when_display==3) { ?> selected="selected"<?php  } ?>><?php echo $when_scroll ?></option>
                                        <option value="4"<?php  if ($spinwin_when_display==4) { ?> selected="selected"<?php  } ?>><?php echo $when_exit ?></option>
                                    </select>
                                 </div>
                            </div>
                            <div class="form-group required" id="hide-when-1" style="display:none" >
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $when_1_time_desc ?>"><?php echo $when_1_time ?></span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group">
                                    <input type="text" name="spinwin_when_time" id="spinwin_when_time" value="<?php echo $spinwin_when_time ?>" class="form-control" required="true" number="true" digit="true" min="0">
                                 </div>
                                 <!--p class="help-block"><?php echo $check_expiration; ?></p-->
                              </div>
                           </div>
                           <div class="form-group required" id="hide-when-2" style="display:none">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $when_2_scroll_desc ?>"><?php echo $when_2_scroll ?></span>
                              </label>
                              <div class="col-lg-3">
                                 <div class="input-group">
                                    <input type="text" name="spinwin_when_scroll" id="spinwin_when_scroll" value="<?php echo $spinwin_when_scroll ?>" class="form-control" required="true" number="true" digit="true" min="0">
                                 </div>
                                 <p class="help-block"><?php echo $when_2_scroll_msg; ?></p>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $geo_loc_descp;?>">
                              <?php echo $geo_loc;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_geo_location" class="form-control fixed-width-xl" onchange="return geolocation(this.value);" id="spinwin_geo_location">
                                    <option value="1" <?php  if ($spinwin_geo_location==1) { ?> selected="selected"<?php } ?>><?php echo $world;?></option>
                                    <option value="2"<?php  if ($spinwin_geo_location==2) { ?> selected="selected"<?php } ?>><?php echo $selected_area;?></option>
                                    <option value="3"<?php  if ($spinwin_geo_location==3) { ?> selected="selected"<?php } ?>><?php echo $noselected_area;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group hide required" id="selectcountry" >
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $sel_country;?>">
                              <?php echo $sel_country;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <select name="spinwin_selectcountry[]" class="form-control fixed-width-xl" multiple id="spinwin_selectcountry" required="true">
                                    <?php foreach($countries as $country){   
                                       ?>
                                    <option value="<?php echo $country['name'];?>"<?php if(in_array($country['name'],$spinwin_selectcountry) ) { ?> selected="selected" <?php }?>><?php echo $country['name'];?></option>
                                    <?php
                                       }?>  
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="panel-footer">
                           <button type="submit" value="1" id="configuration_form_submit_btn_1" name="submitspinwinupmodule"  class="submit_lookandfeelsettings btn btn-default">
                           <i class="fa fa-save"></i> <?php echo $button_save; ?>
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-look">
                     <div class="panel panel-default" id="fieldset_form">
                        <div class="panel-heading">
                           <h3 class="panel-title"><?php echo $css;?></h3>
                        </div>
                        <div class="panel-body">
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $theme_descp?>" data-original-title="<?php echo $theme_descp?>"><?php echo $theme; ?>
                              </span>
                              </label>        
                              <div class="col-sm-5">
                                 <select name="spinwin_theme" id="spinwin_theme" class="form-control">
                                    <option value="0"<?php  if ($spinwin_theme==0) { ?> selected="selected"<?php } ?>><?php echo $theme0; ?></option>
                                    <option value="1"<?php  if ($spinwin_theme==1) { ?> selected="selected"<?php } ?>><?php echo $theme1; ?></option>
                                    <option value="2"<?php  if ($spinwin_theme==2) { ?> selected="selected"<?php } ?>><?php echo $theme2; ?></option>
                                    <?php foreach($latest_theme as $th_key=>$th_value) { ?>
                                    <option value="<?php echo $th_key ?>"<?php  if ($spinwin_theme==$th_key) { ?> selected="selected"<?php } ?>><?php echo $th_value ?></option>
                                    <?php } ?> 
                                 </select>
                              </div>
                           </div>
						   <div class="form-group">
                                <label class="control-label col-lg-3">
								</label>
                                    <div class="col-sm-7">
                                            <div id="theme-preview-image-holder" style="width: 30%">
                                                    <img class="theme-preview-image" style="max-width:570px; border: 1px solid #C7D6DB" src="<?php echo HTTPS_SERVER ?>/view/image/image_preview/0.png"> 
                                            </div>
                                    </div>
                            </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $wheel_design_descp?>" data-original-title="<?php echo $wheel_design_descp?>">
                              <?php echo $wheel_design?>
                              </span>
                              </label>        
                              <div class="col-sm-6">
                                 <select name="spinwin_wheel_design" id="spinwin_wheel_design" class="form-control" onchange="return wheel_preview(this.value);">
                                    <option value="0"<?php  if ($spinwin_wheel_design==0) { ?> selected="selected"<?php } ?>><?php echo $wheel_design1;?></option>
                                    <option value="1"<?php  if ($spinwin_wheel_design==1) { ?> selected="selected"<?php } ?>><?php echo $wheel_design2;?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $wheel_prev_descp; ?>" data-original-title="<?php echo $wheel_prev_descp; ?>">
                              <?php echo $wheel_prev; ?>
                              </span>
                              </label>        
                              <div class="col-sm-6">
                                 <a href="" id="wheel-image" data-toggle="image" class="img-thumbnail"><img id="wheel_prev" src="view/image/spinwin/wheel1.png" alt="" title="" data-placeholder="view/image/no-image.jpg" style="    width: 72px !important;"></a>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $display_image_descp?>" data-original-title="<?php echo $display_image?>">
                              <?php echo $display_image?>
                              </span>
                              </label>        
                              <div class="col-sm-6">
                                 <select name="spinwin_imagedisplay" id="spinwin_imagedisplay" class="form-control" onchange="return enable_image(this.value);">
                                    <option value="1"<?php  if ($spinwin_imagedisplay) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                                    <option value="0"<?php  if (!$spinwin_imagedisplay) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group required" id="enable_image">
                              <label class="control-label col-lg-3">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $image_logo_descp; ?>" data-original-title="<?php echo $image_logo; ?>">
                              <?php echo $image_logo; ?>
                              </span>
                              </label>        
                              <div class="col-sm-6">
                                 <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php if($spinwin_logo==''){?>view/image/no_image-100x100.png<?php }else{?>../image/<?php echo $spinwin_logo; ?><?php } ?>" style="width:200px;" alt="" title="" data-placeholder="view/image/no-image.jpg"></a>
                                 <input type="hidden" name="spinwin_logo" value="<?php echo $spinwin_logo; ?>" id="spinwin_logo" required="true">
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3 required">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $wheel_backround;?>" data-original-title="<?php echo $wheel_backround;?> "><?php echo $wheel_backround;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="col-lg-2">
                                    <div class="row">
                                       <div class="input-group">
                                          <input type="text" data-hex="true" value="<?php echo $spinwin_wheel_background;?>" class="mColorPicker form-control" name="spinwin_wheel_background"  id="color_0" style="background-color: <?php echo $spinwin_wheel_background;?>; color: white;"><span style="cursor:pointer;" id="icp_color_0" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="view/image/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3 required">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $font_color_descp;?>" data-original-title="<?php echo $font_color_descp;?>"><?php echo $font_color;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="col-lg-2">
                                    <div class="row">
                                       <div class="input-group">
                                          <input type="text" data-hex="true" class="mColorPicker form-control" name="spinwin_font_color" value="<?php echo $spinwin_font_color;?>" id="color_1" style="background-color: <?php echo $spinwin_font_color;?>; color: white;"><span style="cursor:pointer;" id="icp_color_1" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="view/image/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
						   
						   <div class="form-group">
                              <label class="control-label col-lg-3 required">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $font_family_descp;?>" data-original-title="<?php echo $font_family_descp;?>"><?php echo $font_family;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="col-lg-4">
                                    <div class="row">
                                       <select name="spinwin_font_family" class="form-control valid" id="spin_wheel_font_family">
                                            <option value="inherit" <?php if($spinwin_font_family=='inherit') { ?> selected="selected"  <?php } ?>>Store Font</option>
                                            <option value="Acme" <?php if($spinwin_font_family=='Acme') { ?> selected="selected"  <?php } ?> >Acme</option>
                                            <option value="Gloria Hallelujah" <?php if($spinwin_font_family=='Gloria Hallelujah') { ?> selected="selected"  <?php } ?>>Gloria Hallelujah</option>
                                            <option value="Indie Flower" <?php if($spinwin_font_family=='Indie Flower') { ?> selected="selected"  <?php } ?>>Indie Flower</option>
                                            <option value="Pacifico"<?php if($spinwin_font_family=='Pacifico') { ?> selected="selected"  <?php } ?>>Pacifico</option>
                                            <option value="Bree Serif" <?php if($spinwin_font_family=='Bree Serif') { ?> selected="selected"  <?php } ?>>Bree Serif</option>
                                            <option value="Baloo Bhaijaan" <?php if($spinwin_font_family=='Baloo Bhaijaan') { ?> selected="selected"  <?php } ?>>Baloo Bhaijaan</option>
                                            <option value="Merriweather" <?php if($spinwin_font_family=='Merriweather') { ?> selected="selected"  <?php } ?>>Merriweather</option>
                                            <option value="Roboto" <?php if($spinwin_font_family=='Roboto') { ?> selected="selected"  <?php } ?>>Roboto</option>
                                        </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                          
                           <div class="form-group">
                              <label class="control-label col-lg-3 required">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $button_backg;?>" data-original-title="Change background color of wheel. "><?php echo $button_backg;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="col-lg-2">
                                    <div class="row">
                                       <div class="input-group">
                                          <input type="text" data-hex="true" class="mColorPicker form-control" name="spinwin_button_background" value="<?php echo $spinwin_button_background;?>" id="color_7" style="background-color: <?php echo $spinwin_button_background;?>; color: white;"><span style="cursor:pointer;" id="icp_color_7" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="view/image/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label col-lg-3 required">
                              <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $no_lucky;?>" data-original-title="Change background color of wheel. "><?php echo $no_lucky;?>
                              </span>
                              </label>
                              <div class="col-lg-9">
                                 <div class="col-lg-2">
                                    <div class="row">
                                       <div class="input-group">
                                          <input type="text" data-hex="true" class="mColorPicker form-control" name="spinwin_no_lucky" value="<?php echo $spinwin_no_lucky;?>" id="color_8" style="background-color: <?php echo $spinwin_no_lucky;?>; color: white;"><span style="cursor:pointer;" id="icp_color_8" class="mColorPickerTrigger input-group-addon" data-mcolorpicker="true"><img src="view/image/color.png" style="border:0;margin:0 0 0 3px" align="absmiddle"></span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="panel-footer">
                           <button type="submit" value="1" id="configuration_form_submit_btn_1" name="submitspinwinupmodule" class="submit_lookandfeelsettings btn btn-default">
                           <i class="fa fa-save"></i> Save
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-text">
                     <div class="panel panel-default" id="fieldset_form">
                        <div class="panel-heading">
                           <h3 class="panel-title"> <?php echo $rule_text;?></h3>
                        </div>
                        <div class="form-group required" >
                           <label class="control-label col-lg-3">
                           <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $title_text_descp;?>" data-original-title="<?php echo $title_text;?>">
                           <?php echo $title_text;?>
                           </span>
                           </label>        
                           <div class="col-md-6">
                              <input type="text" name="spinwin_title_text" id="spinwin_title_text" value="<?php echo $spinwin_title_text; ?>" class="form-control" required="true"> 
                           </div>
                        </div>
                        <div class="form-group required" >
                           <label class="control-label col-lg-3">
                           <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $subtitle_text_descp;?>">
                           <?php echo $subtitle_text;?>
                           </span>
                           </label>        
                           <div class="col-md-6">
                              <input type="text" name="spinwin_subtitle_text" id="spinwin_subtitle_text" value="<?php echo $spinwin_subtitle_text; ?>" class="form-control" required="true"> 
                           </div>
                        </div>
                        <div class="form-group required" >
                           <label class="control-label col-lg-3">
                           <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="" data-original-title="<?php echo $rules_text_descp;?>">
                           <?php echo $rules_text;?>
                           </span>
                           </label>        
                           <div class="col-md-6">
                              <textarea type="text" name="spinwin_rules_text" id="spinwin_rules_text" class="form-control" required="true"><?php echo $spinwin_rules_text; ?></textarea>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="tab-discount">
                     <div class="panel panel-default" id="fieldset_form">
                        <div class="panel-heading">
                           <h3 class="panel-title"> <?php echo $discount;?></h3>
                        </div>
                        <div class="table-responsive text-center">
                           <table class="table">
                              <thead>
                                 <tr>
                                    <td class="text-left"><?php echo $sno;?></td>
                                    <td><?php echo $c_type;?></td>
                                    <td><?php echo $label;?></td>
                                    <td><?php echo $value1;?></td>
                                    <td><?php echo $gravity;?></td>
                                    <td class="text-right"><?php echo $edit;?></td>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php if($coupon_data==""){?>
                                 <tr>
                                    <td class="text-center" colspan="6"><?php echo $no_result;?></td>
                                 </tr>
                                 <?php }else{      
                                    foreach($offer_data as $value){
                                    ?>
                                 <tr>
                                    <td class="text-left"><?php echo $value['id'];?></td>
                                    <td><?php echo $value['coupon_type'];?></td>
                                    <td><?php echo $value['label'];?></td>
                                    <td><?php echo $value['value'];?></td>
                                    <td><?php echo $value['gravity'];?></td>
                                    <td class="text-right"> <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#myModal" onclick="return update('<?php echo $value['id'];?>');"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <?php echo $edit;?></button></td>
                                 </tr>
                                 <?php
                                    } }?>
                              </tbody>
                           </table>
                        </div>
                        <div class="modal fade" id="myModal" role="dialog">
                           <div class="modal-dialog">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><?php echo $offer_upd;?></h4>
                                 </div>
                                 <center><span class="label label-success" id="offer_success" style="color:#fff; font-size: 15px; display:none;"><i class="fa fa-thumbs-up" aria-hidden="true"></i> <?php echo $mand_success;?></span></center>
                                 <center><span class="label label-warning" id="offer_failed" style="color:#fff; font-size: 15px; display:none;"><i class="fa fa-thumbs-down" aria-hidden="true"></i> <?php echo $mandatory;?></span></center>
                                 <div class="modal-body">
                                    <div class="panel-body">
                                       <div class="form-group">
                                          <label class="control-label col-lg-6">
                                          <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $discount_type_descp?>" data-original-title="<?php echo $discount_type?>">
                                          <?php echo $discount_type?>
                                          </span>
                                          </label>        
                                          <div class="col-sm-6">
                                             <select name="discount_type" id="discount_type" onchange="return discount_type1(this.value);" class="form-control">
                                                <option value="Fixed"><?php echo $fix_type; ?></option>
                                                <option value="Percentage"><?php echo $per_type; ?></option>
                                                <!-- <option value="Free shiping"><?php echo $range_type; ?></option> -->
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                    <input type="hidden" name="offer_id" id="offer_id">
                                    <input type="hidden" name="store_id" value="<?php if (isset($this->request->get['store_id'])){ echo $this->request->get['store_id'];  
                                    } else {
                                        echo 0;
                                    } ?>">
                                    <?php foreach($languages as $lang){?>
                                    <div class="form-group <?php if($lang['language_id']==1){?>required<?php }?>">
                                       <label class="control-label col-lg-6">
                                       <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $label_descp; ?>" data-original-title="<?php echo $label; ?>" required="true">
                                       <?php echo $label;?> (<?php echo $lang['name'];?>)
                                       </span>
                                       </label>        
                                       <div class="col-sm-6">
                                          <div class="input-group">
                                             <input type="text" name="offer_label[<?php echo $lang['language_id'];?>]" id="offer_label_<?php echo $lang['language_id'];?>" value="" class="form-control">
                                          </div>
                                          <?php if($lang['language_id']==1){?>
                                          <div id="offer_value_error" style="color:#f56b6b;"></div>
                                          <?php }?>
                                       </div>
                                    </div>
                                    <?php } ?>
                                    <div class="form-group required" id="discount_value">
                                       <label class="control-label col-lg-6">
                                       <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $value1; ?>" required="true" >
                                       <?php echo $value1; ?>
                                       </span>
                                       </label>        
                                       <div class="col-sm-6">
                                          <div class="input-group">
                                             <input type="text" name="offer_value" id="offer_value" value="" class="form-control" onkeypress="return isNumber(event)"> 
                                          </div>
                                          <div id="coupon_value_error" style="color:#f56b6b;"></div> 
                                       </div>
                                    </div>
                                    <div class="form-group required">
                                       <label class="control-label col-lg-6">
                                       <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $gravity_descp; ?>" >
                                       <?php echo $gravity; ?>
                                       </span>
                                       </label>        
                                       <div class="col-sm-6">
                                          <div class="input-group">
                                             <input type="text" name="offer_gravity" id="offer_gravity" value="" class="form-control" required="true" onkeypress="return isNumber(event)">                                                
                                          </div>
                                          <div id="gravity_value_error" style="color:#f56b6b;"></div> 
                                       </div>
                                    </div>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="update_discount"><img id="get_loader" src="view/image/loading.gif" form="form-exit" style="width:40px;display:none"/> <?php echo $button_save; ?></button>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  
         <!--tab_discount-->
         <div class="tab-pane" id="tab-mail">
            <div class="panel panel-default" id="fieldset_form">
               <div class="panel-heading">
                  <h3 class="panel-title"> <?php echo $marketing;?></h3>
               </div>
               <div class="panel-body">
                  <div class="form-group">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $enable_chimp_descp?>" data-original-title="<?php echo $enable_chimp?>">
                     <?php echo $enable_chimp?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <select name="spinwin_enable_chimp" id="spinwin_enable_chimp" onchange="return chimp_api(this.value);" class="form-control">
                           <option value="1"<?php  if ($spinwin_enable_chimp) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                           <option value="0"<?php  if (!$spinwin_enable_chimp) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                           enable_chimp
                        </select>
                     </div>
                  </div>
                  <div class="form-group hide required" id="chimp_api">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $chimp_api; ?>" data-original-title="<?php echo $chimp_api; ?>">
                     <?php echo $chimp_api; ?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <div class="input-group">
                           <input type="text" name="spinwin_chimp_api" id="spinwin_chimp_api" value="<?php echo $spinwin_chimp_api; ?>" class="form-control" required="true">
                           <div id="chimp_msg"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group hide required" id="chimp_list">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $chimp_list; ?>" data-original-title="<?php echo $chimp_list; ?>">
                     <?php echo $chimp_list; ?>
                     </span>
                     </label>        
                     <div class="col-sm-3">
                        <div class="input-group">
                           <select name="spinwin_chimp_list" id="spinwin_chimp_list" class="form-control" required="true">
                              <?php 
                                if(isset($chimp_list1)) {
                                 for($i=0;$i<$chimp_list1['total_items'];$i++){           
                                   ?>
                              <option value="<?php echo $chimp_list1['lists'][$i]['id']; ?>" <?php if($chimp_list1['lists'][$i]['id']==$spinwin_chimp_list) { ?> selected="selected" <?php }?>><?php echo $chimp_list1['lists'][$i]['name'];?> </option>
                              <?php
                                 }
                                }
                                 ?>
                           </select>
                           <span class="input-group-addon" style="padding: 0px;">
                           <button type="button" id="get_chimp" class="btn btn-primary"><?php echo $get_list;?></button>
                           <img id="get_email_loader1" src="view/image/loading.gif" style="width:35px;display:none;"/> 
                           </span> 
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $enable_kalav_descp;?>" data-original-title="<?php echo $enable_kalav?>">
                     <?php echo $enable_kalav?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <select name="spinwin_enable_kalav" id="spinwin_enable_kalav" onchange="return kalav_api(this.value);" class="form-control">
                           <option value="1"<?php  if ($spinwin_enable_kalav) { ?> selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
                           <option value="0"<?php  if (!$spinwin_enable_kalav) { ?> selected="selected"<?php } ?>><?php echo $text_no; ?></option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group hide required" id="kalav_api">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $kalav_api; ?>" data-original-title="<?php echo $kalav_api; ?>">
                     <?php echo $kalav_api; ?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <div class="input-group">
                           <input type="text" name="spinwin_kalav_api" id="spinwin_kalav_api" value="<?php echo $spinwin_kalav_api; ?>" class="form-control" required="true">
                           <div id="kalav_msg1"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group hide required" id="kalav_token">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $kalav_token; ?>" data-original-title="<?php echo $kalav_token; ?>" >
                     <?php echo $kalav_token; ?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <div class="input-group">
                           <input type="text" name="spinwin_kalav_token" id="spinwin_kalav_token" value="<?php echo $spinwin_kalav_token; ?>" class="form-control" required="true">   
                           <div id="kalav_msg2"></div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group hide required" id="kalav_list">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $kalav_list; ?>" data-original-title="<?php echo $kalav_list; ?>">
                     <?php echo $kalav_list; ?>
                     </span>
                     </label>        
                     <div class="col-sm-3">
                        <div class="input-group">
                           <select name="spinwin_kalav_list" id="spinwin_kalav_list" class="form-control" required="true">
                              <?php          
                                 foreach($kalaviyo_api as $val){        
                                    ?>
                              <option value="<?php echo isset($val['id'])?$val['id']:'0'; ?>" <?php if(@$val['id']==$spinwin_kalav_list) { ?> selected="selected" <?php }?>><?php echo isset($val['name'])?$val['name']:$kalav_list;?>  </option>
                              <?php
                                 }
                                 ?>
                           </select>
                           <span class="input-group-addon" style="padding: 0px;"><button type="button" id="get_constant" class="btn btn-primary"><?php echo $get_list;?></button><img id="get_email_loader2" src="view/image/loading.gif" style="width:35px;display:none;"/> </span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--tab_mailchimp-->
         <div class="tab-pane" id="tab-email">
            <div class="panel panel-default" id="fieldset_form">
               <div class="panel-heading">
                  <h3 class="panel-title"> <?php echo $email_sett;?></h3>
               </div>
               <div class="panel-body">
                  <div class="form-group">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $display_opt?>" data-original-title="<?php echo $display_opt?>">
                     <?php echo $display_opt?>
                     </span>
                     </label>        
                     <div class="col-sm-6">
                        <select name="spinwin_email_setting" id="spinwin_email_setting" onchange="return email_set(this.value);" class="form-control">
                           <option value="1"<?php  if ($spinwin_email_setting==1) { ?> selected="selected"<?php } ?>><?php echo $popup_only; ?></option>
                           <option value="2"<?php  if ($spinwin_email_setting==2) { ?> selected="selected"<?php } ?>><?php echo $email_only; ?></option>
                           <option value="3"<?php  if ($spinwin_email_setting==3) { ?> selected="selected"<?php } ?>><?php echo $pop_email; ?></option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group required hide" id="email_sub" >
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $subject; ?>" data-original-title="<?php echo $subject; ?>">
                     <?php echo $subject; ?>
                     </span>
                     </label>        
                     <div class="col-md-6">
                        <input type="text" name="spinwin_email_subject" id="spinwin_email_subject" value="<?php echo $spinwin_email_subject; ?>" class="form-control" required="true"> 
                     </div>
                  </div>
                  <div class="form-group required hide" id="email_con">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title=" <?php echo $content_descp; ?>" data-original-title=" <?php echo $content; ?>">
                     <?php echo $content; ?>
                     </span>
                     </label>        
                     <div class="col-md-8">
                        <textarea name="spinwin_emailer_content" placeholder="" id="spinwin_emailer_content" class="form-control summernote" required="true"><?php echo $spinwin_emailer_content;?></textarea>
                        <p><?php echo $var_email; ?></p>
                     </div>
                     <br>
                  </div>
                  <div class="form-group hide" id="email_test">
                     <label class="control-label col-lg-3">
                     <span class="label-tooltip" data-toggle="tooltip" data-html="true" title="<?php echo $test_email_descp; ?>" data-original-title="<?php echo $test_email;?>"> <?php echo $test_email; ?></span>
                     </label>
                     <div class="col-lg-3">
                        <div class="input-group">
                           <input type="text" name="spinwin_test_email" id="spinwin_test_email" value="<?php echo $spinwin_test_email; ?>" class="form-control" >
                           <span class="input-group-addon" style="padding: 0px;"><button type="button" id="send_mail" class="btn btn-primary"><?php echo $send_email;?></button><img id="get_email_loader" src="view/image/loading.gif" style="width:35px;display:none;"/> </span>
                        </div>
                        <p id="success_msg"></p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <!--tab_background-->
         <div class="tab-pane" id="tab-stat">
            <div class="panel panel-default" id="fieldset_form">
               <div class="panel-heading">
                  <h3 class="panel-title"> <?php echo $statistic;?></h3>
               </div>
               <div class="panel-body">
                  <div id="chart-sale" style="width: 100%; height: 260px;"></div>
               </div>
            </div>
            <div class="panel panel-default">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-ticket"></i><?php echo $coupon;?></h3>
               </div>
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <td class="text-left"><?php echo $coupon_id;?></td>
                           <td><?php echo $c_email;?></td>
                           <td><?php echo $c_country;?></td>
                           <td><?php echo $c_device;?></td>
                           <td class="text-right"><?php echo $c_date;?></td>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if($coupon_data==""){?>
                        <tr>
                           <td class="text-center" colspan="6"><?php echo $no_result;?></td>
                        </tr>
                        <?php }else{      
                           foreach($coupon_data as $value){
                           ?>
                        <tr>
                           <td class="text-left"><?php echo $value['coupon'];?></td>
                           <td><?php echo $value['email'];?></td>
                           <td><?php echo $value['country'];?></td>
                           <td><?php echo $value['device'];?></td>
                           <td class="text-right"><?php echo $value['added_date'];?></td>
                        </tr>
                        <?php
                           } }?>
                     </tbody>
                  </table>
                  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
               </div>
            </div>
         </div>
      </div>
      <!--tab content ends-->
</div>
</div>
</div>
<!--container fluid ends-->
</form>
</div>
<script src="view/javascript/colorpicker/jquery.colorpicker.js"></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script>
<?php if(isset($_GET['page'])){?>
<script type="text/javascript">
  $('#statistic').trigger('click');
</script>
<?php }?>
<?php if(isset($_GET['offer'])){?>
<script type="text/javascript">
  $('#discount_offer').trigger('click');
</script>
<?php }?>
<script type="text/javascript"><!--
   function isNumber(evt) {
       evt = (evt) ? evt : window.event;
       var charCode = (evt.which) ? evt.which : evt.keyCode;
       if (charCode > 31 && (charCode < 48 || charCode > 57)) {
           return false;
       }
       return true;
   }
   function update(id){
      $('#myModal input, #myModal select').each(function(){
         $(this).val('');
         //alert(this.id);
      });
      $('#offer_value').css('border-color','');
      $('#coupon_value_error').text('');
      $('#offer_gravity').css('border-color','');
      $('#gravity_value_error').text('');
      $('#offer_label_1').css('border-color','');
      $('#offer_value_error').text('');
       $('#offer_success').css('display','none');
       $.ajax({
       url: 'index.php?route=extension/module/spin_win/get_offer&token=<?php echo $_GET["token"]; ?>',
       method:'post',
       data:'id='+id+'',
       dataType:'JSON',
       success: function(json) {
         $.each(json, function(index, value) {
            if(value!=""){
               $('#offer_id').val(value.slice_id);
               $('#offer_label_'+index+'').val(value.label);
               $('#discount_type').val(value.coupon_type);
               $('#offer_value').val(value.value);  
               $('#offer_gravity').val(value.gravity); 
            }                     
         });
       }
     });
    }
   
    $('#update_discount').click(function(){
       $('#get_loader').css('display','');      
       if($('#offer_value').val()==""){
          $('#offer_value').css('border-color','#f56b6b');
            $('#coupon_value_error').text('<?php echo $required; ?>');
         }else{
            $('#offer_value').css('border-color','');
            $('#coupon_value_error').text('');
         }
         if($('#offer_value').val()<0){
          $('#offer_value').css('border-color','#f56b6b');
            $('#coupon_value_error').text('<?php echo $coupon_error_num; ?>');
         }else{
           $('#offer_value').css('border-color','');
            $('#coupon_value_error').text('');
         }
         if($('#offer_gravity').val()==""){
          $('#offer_gravity').css('border-color','#f56b6b');
            $('#gravity_value_error').text('<?php echo $gravity_error;?>');
         }else{
             $('#offer_gravity').css('border-color','');
            $('#gravity_value_error').text('');
         }
         if($('#offer_gravity').val()<0){
          $('#offer_gravity').css('border-color','#f56b6b');
            $('#gravity_value_error').text('<?php echo $coupon_error_num;?>');
         }else{
             $('#offer_gravity').css('border-color','');
            $('#gravity_value_error').text('');
         }
         if($('#offer_label_1').val()==""){
          $('#offer_label_1').css('border-color','#f56b6b');
            $('#offer_value_error').text('<?php echo $required; ?>');
         }else{
            $('#offer_label_1').css('border-color','');
            $('#offer_value_error').text('');
         }

       $.ajax({
       url: 'index.php?route=extension/module/spin_win/update_offer&token=<?php echo $_GET["token"]; ?>',
       method:'post',
       data:$('#myModal input, select').serialize(),
       dataType:'JSON',
       success: function(data) {
          if(data.type=='success'){
             $('#offer_success').css('display','');
             setTimeout(function () {
                var url=updateQueryStringParameter(document.URL,'offer','1')
               window.location.href=url;
            }, 1000);
          }
          if(data.type=='gravity_value_error'){
            $('#offer_gravity').css('border-color','#f56b6b');
            $('#gravity_value_error').text('<?php echo $gravity_error_value;?> (Total '+data.sum+')');
            $('#get_loader').css('display','none');
          }else if(data.type=='gravity_error'){
            $('#offer_gravity').css('border-color','#f56b6b');
            $('#gravity_value_error').text('<?php echo $gravity_error;?>');
            $('#get_loader').css('display','none');
          }else if(data.type=='coupon_error'){
            $('#offer_value').css('border-color','#f56b6b');
            $('#coupon_value_error').text('<?php echo $required; ?>');
            $('#get_loader').css('display','none');
          }else if(data.type=='coupon_error_per'){
             $('#offer_value').css('border-color','#f56b6b');
            $('#coupon_value_error').text('<?php echo $coupon_error_per; ?>');
            $('#get_loader').css('display','none');
          }else if(data.type=='coupon_error_num'){
             $('#offer_value').css('border-color','#f56b6b');
            $('#coupon_value_error').text('<?php echo $coupon_error_num; ?>');
            $('#get_loader').css('display','none');
          }else if(data.type=='gravity_error_num'){
            $('#offer_gravity').css('border-color','#f56b6b');
            $('#gravity_value_error').text('<?php echo $coupon_error_num;?>');
            $('#get_loader').css('display','none');
          }else if(data.type=='label_error'){
            $('#offer_label_1').css('border-color','#f56b6b');
            $('#offer_value_error').text('<?php echo $required; ?>');
            $('#get_loader').css('display','none');
          }else{
            $('#offer_value').css('border-color','');
            $('#coupon_value_error').text('');
            $('#offer_gravity').css('border-color','');
            $('#gravity_value_error').text('');
            $('#offer_label_1').css('border-color','');
            $('#offer_value_error').text('');
          }
       }
     });
    });

    function discount_type1(data){ 
      if(data=='Percentage'){
       $('#discount_value').removeClass('hide');
      }else if(data=='Free shiping'){
       $('#discount_value').addClass('hide');
       $('#offer_value').val('0');
      }else{
       $('#discount_value').removeClass('hide');
      }
   }
   wheel_preview();
   function wheel_preview(data){
      if(!data){
         data='<?php echo $spinwin_wheel_design;?>';
      }
      if(data==0){
         $('#wheel_prev').attr('src','view/image/spinwin/wheel1.png');
      }else{
         $('#wheel_prev').attr('src','view/image/spinwin/wheel2.png');
      }
   }
   $('.mColorPicker').each(function() {
       var rgb = $(this).css('backgroundColor');
        rgb=rgb.replace(/[^\d,]/g, '').split(",");
        var y = 2.99 * rgb[0] + 5.87 * rgb[1] + 1.14 * rgb[2];
        if (y >= 1275) {
         $(this).css('color','black');
        } else {
         $(this).css('color','white');
        }
        
    }); 		
   
   $.ajax({
     type: 'get',
     url: 'index.php?route=extension/module/spin_win/chart&token=<?php echo $_GET["token"]; ?>',
     dataType: 'json',
     success: function(json) {
       if (typeof json['used'] == 'undefined') { return false; }
       var option = {  
         shadowSize: 0,
         colors: ['#1065D2', '#red'],
         bars: { 
           show: true,
           fill: true,
           lineWidth: 1
         },
         grid: {
           backgroundColor: '#FFFFFF',
           hoverable: true
         },
         points: {
           show: false
         },
         xaxis: {
           show: true,
                 ticks: json['xaxis']
         }
       }
       
       $.plot('#chart-sale', [json['unused'],json['used']], option); 
           
       $('#chart-sale').bind('plothover', function(event, pos, item) {
         $('.tooltip').remove();
         
         if (item) {
           $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
           
           $('#tooltip').css({
             position: 'absolute',
             left: item.pageX - ($('#tooltip').outerWidth() / 2),
             top: item.pageY - $('#tooltip').outerHeight(),
             pointer: 'cusror'
           }).fadeIn('slow');  
           
           $('#chart-sale').css('cursor', 'pointer');    
           } else {
           $('#chart-sale').css('cursor', 'auto');
         }
       });
     },
         error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
         }
   });
   
     email_set();
   function email_set(data){
   if(!data){
     data= $('#spinwin_email_setting').val();
   }
   if(data!=1){
    $('#email_sub').removeClass('hide');
    $('#email_con').removeClass('hide');
    $('#email_test').removeClass('hide');
   }else{
    $('#email_sub').addClass('hide');
    $('#email_con').addClass('hide');
    $('#email_test').addClass('hide');
   }
   }
    $("#spinwin_theme").change(function(){
        image_preview();
    });
    
    function image_preview(){
        //alert("<?php echo HTTPS_SERVER ?>/view/image/image_preview/"+$("#spinwin_theme").val()+".png");
        $('.theme-preview-image').attr('src', '<?php echo HTTPS_SERVER; ?>view/image/spinwin/image_preview/'+$("#spinwin_theme").val()+'.png');
    }
    
   
   $('#range .active a').trigger('click');
   //-->
</script> 
<style type="text/css">
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url(view/javascript/soin_win/fonts/glyphicons-halflings-regular.eot);
        src: url(view/javascript/spin_win/fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'), url(view/javascript/kbcountdown/fonts/glyphicons-halflings-regular.woff) format('woff'), url(view/javascript/kbcountdown/fonts/glyphicons-halflings-regular.ttf) format('truetype'), url(view/javascript/kbcountdown/fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format('svg')
    }
</style> 
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />

<script type="text/javascript">
   $('.date').datetimepicker({
    pickTime: true
   });
   function hideOtherLanguage(id)
   {
    $('.translatable-field').hide();
    $('.lang-' + id).show();
   
    var id_old_language = id_language;
    id_language = id;
   
    if (id_old_language != id)
     changeEmployeeLanguage();
   
   updateCurrentText();
   }
   fixtime();
   function fixtime(data){
   if(!data){
     data= $('#spinwin_fixtime').val();
   }
   if(data==1){
    $('#fixtime_active').removeClass('hide');
    $('#fixtime_expire').removeClass('hide');
   }else{
    $('#fixtime_active').addClass('hide');
    $('#fixtime_expire').addClass('hide');
   }
   }
   pageselect();
   function pageselect(data){
   if(!data){
     data= $('#spinwin_where_display').val();
   }
   if(data!=1){
    $('#selectpage').removeClass('hide');            
   }else{
    $('#selectpage').addClass('hide');
   }
   }
   
   whenselect();
   function whenselect(data) {
       $('#hide-when-1').attr('style','display:none');
       $('#hide-when-2').attr('style','display:none');
       if(!data){
           data= $('#spinwin_when_display').val();
       }
       if(data==2){
           $('#hide-when-1').attr('style','display:block');
       }
       if(data==3){
           $('#hide-when-2').attr('style','display:block');
       }
   }
   
   
   geolocation();
   function geolocation(data){
   if(!data){
     data= $('#spinwin_geo_location').val();
   }
   if(data!=1){
    $('#selectcountry').removeClass('hide');            
   }else{
    $('#selectcountry').addClass('hide');
   }
   }
   
   discount_type();
   function discount_type(data){  
   if(!data){
     data= $('#spinwin_discount_type').val();
   }
   if(data==2){
    $('#fix_type').addClass('hide');
    $('#per_type').removeClass('hide');
    $('#range_from').addClass('hide');
    $('#range_to').addClass('hide');
   }else if(data==3){
    $('#fix_type').addClass('hide');
    $('#per_type').addClass('hide');
    $('#range_from').removeClass('hide');
    $('#range_to').removeClass('hide');
   }else{
    $('#fix_type').removeClass('hide');  
    $('#per_type').addClass('hide');
    $('#range_from').addClass('hide');
    $('#range_to').addClass('hide');
   }
   }
   chimp_api();
   function chimp_api(data){
    if(!data){
      data= $('#spinwin_enable_chimp').val();
    }
    if(data==1){
     $('#chimp_api').removeClass('hide');
     $('#chimp_list').removeClass('hide');
   }else{
     $('#chimp_api').addClass('hide');
     $('#chimp_list').addClass('hide');
   }
   }
   kalav_api();
   function kalav_api(data){
    if(!data){
      data= $('#spinwin_enable_kalav').val();
    }
    if(data==1){
     $('#kalav_api').removeClass('hide');
     $('#kalav_token').removeClass('hide');
     $('#kalav_list').removeClass('hide');
   }else{
     $('#kalav_api').addClass('hide');
     $('#kalav_token').addClass('hide');
     $('#kalav_list').addClass('hide');
   }
   }
</script>
<script src="view/javascript/spin_win/jquery.validate.min.js"></script>
<script src="view/javascript/spin_win/jquery.custom.validation.js"></script>
<script type="text/javascript">
   $.extend( $.validator.messages, {
   required: '<?php echo $required; ?>',
    url: '<?php echo $url; ?>',
    number: '<?php echo $number; ?>',
    min: '<?php echo $min; ?>',
    digit: '<?php echo $digit; ?>',
   } );
   var messages = {
        mandatory: '<?php echo $mandatory; ?>',
        price: '<?php echo $price; ?>',
        decimalNotRequired: '<?php echo $decimalNotRequired; ?>',
        email: '<?php echo $email; ?>',
        passwd: '<?php echo $passwd; ?>',
        notRequiredPasswd: '<?php echo $notRequiredPasswd; ?>',
        mobile: '<?php echo $mobile; ?>',
        addressLine1: '<?php echo $addressLine1; ?>',
        addressLine2: '<?php echo $addressLine2; ?>',
        digit: '<?php echo $digit; ?>',
        notRequiredDigit: '<?php echo $notRequiredDigit; ?>',
        firstname: '<?php echo $firstname; ?>',
        lastname: '<?php echo $lastname; ?>',
        middlename: '<?php echo $middlename; ?>',
        requiredMin2Max60NoSpecial: '<?php echo $requiredMin2Max60NoSpecial; ?>',
        requiredip: '<?php echo $requiredip; ?>',
        optionalip: '<?php echo $optionalip; ?>',
        requiredimage: '<?php echo $requiredimage; ?>',
        optionalimage: '<?php echo $optionalimage; ?>',
        requiredcharonly: '<?php echo $requiredcharonly; ?>',
        optionalcharonly: '<?php echo $optionalcharonly; ?>',
        barcode: '<?php echo $barcode; ?>',
        ean: '<?php echo $ean; ?>',
        upc: '<?php echo $upc; ?>',
        size: '<?php echo $size; ?>',
        requiredurl: '<?php echo $requiredurl; ?>',
        optionalurl: '<?php echo $optionalurl; ?>',
        carrier: '<?php echo $carrier; ?>',
        brand: '<?php echo $brand; ?>',
        optionalcompany: '<?php echo $optionalcompany; ?>',
        requiredcompany: '<?php echo $requiredcompany; ?>',
        sku: '<?php echo $sku; ?>',
        requiredmmddyy: '<?php echo $requiredmmddyy; ?>',
        optionalmmddyy: '<?php echo $optionalmmddyy; ?>',
        requiredddmmyy: '<?php echo $requiredddmmyy; ?>',
        optionalddmmyy: '<?php echo $optionalddmmyy; ?>',
        optionalpercentage: '<?php echo $optionalpercentage; ?>',
        requiredpercentage: '<?php echo $requiredpercentage; ?>',
        checktags: '<?php echo $checktags; ?>',
        checkhtmltags: '<?php echo $checkhtmltags; ?>',
        requireddocs: '<?php echo $requireddocs; ?>',
        optionaldocs: '<?php echo $optionaldocs; ?>',
        requiredcolor: '<?php echo $requiredcolor; ?>',
        optionalcolor: '<?php echo $optionalcolor; ?>',
   
    };
   
    $(document).ready(function() {
       $("#form-exit").validate({
            errorClass: "text-danger",
            highlight: function (label) {
                $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function (label) {
                $(label).closest('.form-group').removeClass('has-error');
                label.remove();
            },
            rules: {
                  spinwin_interval: {
                    required: true,
                    min: 0
                  },
                  spinwin_expiration: {
                    required: true,
                    min: 0
                  },
                  spinwin_when_time: {
                        required: true,
                        min: 0
                  },
                  spinwin_when_scroll: {
                    required: true,
                    range: [0, 100]
                  },
                  spinwin_discount_fixtype: {
                    required: true,
                    min: 0
                  },                  
                  spinwin_discount_pertype: {
                    required: true,
                    range: [0, 100]
                  }
              } 
        });
    });
   $(document).ready(function() {
	image_preview();
    $('#send_mail').click(function(){
        var subject=$('#spinwin_email_subject').val().trim();
        var content=$('#spinwin_emailer_content').val().trim();
        var email=$('#spinwin_test_email').val().trim();
        $('#get_email_loader').show();
        if(subject=="" || content=="" ||  email==""){
          $('#success_msg').text('<?php echo $required; ?>');
          $('#success_msg').css('color','red');
          $('#spinwin_test_email').css('border-color','red');
          $('#get_email_loader').hide();
          return false;
        }       
        postData={'subject':subject,'content':content,'email':email};
        $.ajax({
        url: 'index.php?route=extension/module/spin_win/test_mail&token=<?php echo $_GET['token']; ?>',
        method:'post',
        data:postData,
        dataType:'JSON',
        success: function(data) {
          if(data.type){
            $('#get_email_loader').hide();
            $('#success_msg').text('');
          $('#success_msg').css('color','');
          $('#spinwin_test_email').css('border-color','');
          }
        }
      });
    })  
    $('#get_chimp').click(function(){
        var chimp_api=$('#spinwin_chimp_api').val().trim();        
        $('#get_email_loader1').show();
        var isValid=true;
        if(chimp_api==""){
          $('#spinwin_chimp_api').css('border-color','red');
           $('#chimp_msg').text('<?php echo $required; ?>');
          $('#chimp_msg').css('color','red');
          $('#get_email_loader1').hide();
          isValid=false;
        } else{
           $('#spinwin_chimp_api').css('border-color','');
           $('#chimp_msg').text('');
          $('#chimp_msg').css('color','');
        }
        if(isValid){ 
        $.ajax({
        url: 'index.php?route=extension/module/spin_win/getChimp&token=<?php echo $_GET['token']; ?>',
        method:'post',
        data:'chimp_api='+chimp_api+'',
        success: function(data) {
          if(data!='empty'){
            $('#spinwin_chimp_list').html(data);
            $('#get_email_loader1').hide();
          }
          if(data=='empty'){
            alert('No list available.');
            $('#spinwin_chimp_list').html('<option value="">No list Available</option>');
            $('#get_email_loader1').hide();
          }
        }
      });
      }
    })  
   
    $('#get_constant').click(function(){
        var kalav_api=$('#spinwin_kalav_api').val().trim();   
        var kalav_token=$('#spinwin_kalav_token').val().trim();     
        $('#get_email_loader2').show();
        var isValid=true;
        if(kalav_api==""){
          $('#get_email_loader2').hide();
          $('#spinwin_kalav_api').css('border-color','red');
          $('#kalav_msg1').text('<?php echo $required; ?>');
          $('#kalav_msg1').css('color','red');
          isValid=false;
        } else{
          $('#spinwin_kalav_api').css('border-color','');
          $('#kalav_msg1').text('');
          $('#kalav_msg1').css('color','');
        }
        if(kalav_token==""){
          $('#get_email_loader2').hide();
          $('#spinwin_kalav_token').css('border-color','red');
          $('#kalav_msg2').text('<?php echo $required; ?>');
          $('#kalav_msg2').css('color','red');
          isValid=false;
        } else{
          $('#spinwin_kalav_token').css('border-color','');
          $('#kalav_msg2').text('');
          $('#kalav_msg2').css('color','');
        }
        if(isValid){  
        $.ajax({
        url: 'index.php?route=extension/module/spin_win/getContant&token=<?php echo $_GET['token']; ?>',
        method:'post',
        data:'kalav_api='+kalav_api+'&kalav_token='+kalav_token+'',
        success: function(data) {
          if(data!='empty'){
            $('#spinwin_kalav_list').html(data);
            $('#get_email_loader2').hide();
          }          
        }
      });
      }
    })  
   });
   $('#spinwin_test_email').focus(function(){
          $('#success_msg').text('');
          $('#success_msg').css('color','');
          $('#spinwin_test_email').css('border-color','');
        })
   
   function getStoreUrl(id){
   // window.location.href=document.URL+'&store_id='+id+'';
   var url=updateQueryStringParameter(document.URL,'store_id',id)
   window.location.href=url;
   }
   
   function updateQueryStringParameter(uri, key, value) {
   var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
   var separator = uri.indexOf('?') !== -1 ? "&" : "?";
   if (uri.match(re)) {
    return uri.replace(re, '$1' + key + "=" + value + '$2');
   }
   else {
    return uri + separator + key + "=" + value;
   }
   }
   enable_image();
    function enable_image(data){
        if(!data){
          data=$('#spinwin_imagedisplay').val().trim();  
        }
        if(data==0){
            $('#enable_image').addClass('hide');
            $('#spinwin_logo').removeAttr('required');
        }else{
            $('#enable_image').removeClass('hide');
            $('#spinwin_logo').attr('required','true');
        }
        
    }
</script>
<?php echo $footer; ?>