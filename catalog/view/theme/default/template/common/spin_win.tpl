<link href="catalog/view/stylesheet/spinwin/css/spin_wheel.css" rel="stylesheet" media="screen" />
<link href="catalog/view/stylesheet/spinwin/css/tooltipster.css" rel="stylesheet" media="screen" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Baloo+Bhaijaan|Merriweather|Roboto|Acme|Bree+Serif|Cinzel|Gloria+Hallelujah|Indie+Flower|Pacifico" type="text/css" media="all" />
<?php 
  extract($setting);
  $theme=$spinwin_theme;
  $path_route=explode('/',$route);
  $ip = $_SERVER['REMOTE_ADDR']; 
  //$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
  //$ip_country=$dataArray->geoplugin_countryName;
  $ip_country="";
  if($ip_country==""){  
    $ip_country='India';
  }
  $curr_date=strtotime(date('Y-m-d'));
  if($spinwin_fixtime==1){
    if(strtotime($spinwin_fixtimeactive)<=$curr_date && strtotime($spinwin_fixtimeexpire)>=$curr_date){
      $show_pop=true;
    }
  }else{
  $show_pop=true;
  }
  if(isset($spinwin_when_display)) { 
    if($spinwin_when_display==1){
        $spinwin_when_time="";
        $spinwin_when_scroll="";
    } else if($spinwin_when_display==2) {
        $spinwin_when_scroll="";
    } else if($spinwin_when_display==3) {
        $spinwin_when_time="";
    } else {
        $spinwin_when_time="";
        $spinwin_when_scroll="";
        $spinwin_exit=true;
    }
  } else {
     $spinwin_when_display=1; 
  }
  ?>
  <script>
   
   
   var show_pull_out = "<?php echo $spinwin_show_pullout; ?>";
   var email_recheck_spinwin = "<?php echo $spinwin_recheck; ?>";
   var wheel_device = "<?php echo $spinwin_screen; ?>";
   var email_check_spinwin = "<?php echo $email_check;?>";
   var time_display = "<?php echo $spinwin_when_time ?>";
   var scroll_display = "<?php echo $spinwin_when_scroll ?>";
   var exit_display = "<?php echo @$spinwin_exit ?>";
   var hide_after_spinwin ="<?php echo $spinwin_hide;?>";
   var min_screen_size_spinwin = "<?php echo $spinwin_screen;?>";
   var copy_msg = "<?php echo $code_copied;?>";
   var display_interval_spinwin="<?php echo $spinwin_interval;?>";
   var display_option_spinwin = "<?php echo $spinwin_email_setting;?>";
   var show_fireworks = "<?php echo $spinwin_show_firework;?>";
   var spin_wheel_front_path = "index.php?route=extension/module/spin_win/generate_coupon";
   var spin_wheel_front_path1 = "index.php?route=extension/module/spin_win/email_check";
   var spin_wheel_front_path2 = "index.php?route=extension/module/spin_win/send_email";
   var email_only_msg_spinwin ="<?php echo $coupon_sent;?>";
   var wheel_design = "<?php echo $spinwin_wheel_design;?>";
   var email_pop_spinwin="<?php echo $spinwin_email_setting;?>";
   var visit_spinwin= "<?php echo $spinwin_freq;?>";
   var empty_email_spinwin= "<?php echo $empty_email;?>";
   var validate_email_spinwin= "<?php echo $validate_email;?>";
   
   <?php if ($spinwin_enable==1 && isset($show_pop)){ 
    if($spinwin_geo_location==2  && in_array($ip_country,$spinwin_selectcountry)){
        ?>    
         var module_enable_spinwin = '<?php echo $spinwin_enable; ?>';
        <?php
        } else if($spinwin_geo_location==3 && !in_array($ip_country,$spinwin_selectcountry)){
        ?>    
         var module_enable_spinwin = '<?php echo $spinwin_enable; ?>';
        <?php
        } else if($spinwin_geo_location==1){
          ?>    
           var module_enable_spinwin = '<?php echo $spinwin_enable; ?>';
          <?php
        }else{
        ?>
          var module_enable_spinwin = '0';
        <?php
        }  
    ?>
    <?php
    if($spinwin_where_display==2 && in_array($route,$select_pages)){
        ?>    
         var module_enable_spinwin1 = '<?php echo $spinwin_enable; ?>';
        <?php
        } else if($spinwin_where_display==3 && !in_array($route,$select_pages)){
        ?>    
         var module_enable_spinwin1 = '<?php echo $spinwin_enable; ?>';
        <?php
        } else if($spinwin_where_display==1){
          ?>    
           var module_enable_spinwin1 = '<?php echo $spinwin_enable; ?>';
          <?php
        }else{
        ?>
          var module_enable_spinwin1 = '0';
        <?php
        }  }else{
          ?>
          var module_enable_spinwin = '0';
          var module_enable_spinwin1 = '0';
          <?php
        }
    ?>

 <?php echo $spinwin_js;?>;
    /*End-Mayank Kumar made changes on 26-Aug-2017 for velovalidation*/
</script>
<style>
    <?php echo $spinwin_css;?>;
</style>
 <style>
.velsof_button {
  background-color: <?php echo $spinwin_button_background;?>;
}
.cancel_button{
  color: <?php echo $spinwin_no_lucky;?>;
  text-align: right;  cursor: pointer;
}
#velsof_wheel_main_container{
  background-color: <?php echo $spinwin_wheel_background;?>;
}
.wheelslices{
  color: <?php echo $spinwin_font_color;?>;
}
#spin_wheel_logo {
   z-index: 9999;
   position: relative;
}
#velsof_wheel_container{
font-family: <?php echo $spinwin_font_family; ?>; 
}
.wheelslices {
    font-family: <?php echo $spinwin_font_family; ?>; 
}
</style>
<div id="pull_out" class="spin_toggle" style="display: none"><img src="image/spinwin/gift.png" alt="slide" style="width:50px; height: 50px;"/></div>
<div id="velsof_wheel_container"  style="display: none; height: 100%; position: fixed; left: 0px; bottom: 0px;  top: 0px; z-index: 100000">
 <div id="velsof_wheel_model"> </div>
 <div id="velsof_wheel_main_container">
        <?php if ($theme == 2) { ?>
            <div id="velsoftop" class="velsoftheme xmas1"></div>
            <div id="velsofbottom" class="velsoftheme xmas1"> </div>
        <?php } else if ($theme == 1) { ?>
            <div id="velsoftop" class="velsoftheme xmas2"></div>
            <div id="velsofbottom" class="velsoftheme xmas2"> </div>
         <?php } else if ($theme == 'chocolate_day') { ?>
                <style> 
                    #velsoftop.chocolate_day_theme {
                        background: url(catalog/view/image/spinwin/chocolate_day/top.gif);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }
                    #velsofbottom.chocolate_day_theme  {
                        background-image: url(catalog/view/image/spinwin/chocolate_day/bottom.png); 
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/chocolate_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme chocolate_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme chocolate_day_theme"> </div>
            <?php } else if ($theme == 'hug_day') { ?>
                <style> 
                    #velsoftop.hug_day_theme {
                        background: url(catalog/view/image/spinwin/hug_day/top.png);
                        opacity: 1;
                        background-position: top center;
                        background-repeat: no-repeat;
                    }
                    #velsofbottom.hug_day_theme  {
                        background-image: url(catalog/view/image/spinwin/hug_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/hug_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme hug_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme hug_day_theme"> </div>
            <?php } else if ($theme == 'kiss_day') { ?>
                <style> 
                    #velsoftop.kiss_day_theme {
                        background-image: url(catalog/view/image/spinwin/kiss_day/top.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top left 120px
                    }
                    #velsofbottom.kiss_day_theme  {
                        background-image: url(catalog/view/image/spinwin/kiss_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                         background-image: url(catalog/view/image/spinwin/kiss_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme kiss_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme kiss_day_theme"> </div>
            <?php } else if ($theme == 'promise_day') { ?>
                <style> 
                    #velsoftop.promise_day_theme {
                        background: url(catalog/view/image/spinwin/promise_day/top.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }
                    #velsofbottom.promise_day_theme  {
                        background-image: url(catalog/view/image/spinwin/promise_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/promise_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme promise_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme promise_day_theme"> </div>
            <?php } else if ($theme == 'propose_day') { ?>
                <style> 
                    #velsoftop.propose_day_theme {
                        background: url(catalog/view/image/spinwin/propose_day/top.gif);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }
                    #velsofbottom.propose_day_theme  {
                        background-image: url(catalog/view/image/spinwin/propose_day/bottom.png); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/propose_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme propose_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme propose_day_theme"> </div>
            <?php } else if ($theme == 'rose_day') { ?>
                <style> 
                    #velsoftop.rose_day_theme {
                        background: url(catalog/view/image/spinwin/rose_day/top.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top left 140px;
                    }
                    #velsofbottom.rose_day_theme  {
                        background-image: url(catalog/view/image/spinwin/rose_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/rose_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme rose_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme rose_day_theme"> </div>
            <?php } else if ($theme == 'teddy_day') { ?>
                <style> 
                    #velsoftop.teddy_day_theme {
                        background: url(catalog/view/image/spinwin/teddy_day/top.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }
                    #velsofbottom.teddy_day_theme  {
                        background-image: url(catalog/view/image/spinwin/teddy_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/teddy_day/background.png);
                    }
                </style>

                <div id="velsoftop" class="velsoftheme teddy_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme teddy_day_theme"> </div>
            <?php } else if ($theme == 'valentine_day') { ?>
                <style> 
                    #velsoftop.valentine_day_theme {
                        background: url(catalog/view/image/spinwin/valentine_day/top.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }
                    #velsofbottom.valentine_day_theme  {
                        background-image: url(catalog/view/image/spinwin/valentine_day/bottom.gif); 
                        background-position: bottom right 30px;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/valentine_day/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme valentine_day_theme"></div>
                <div id="velsofbottom" class="velsoftheme valentine_day_theme"> </div>   
            <?php } else if ($theme == 4) { ?>
                <style> 
                    #velsoftop.halloween_theme1 {
                        background-image: url(catalog/view/image/spinwin/halloween/theme1/overlay.png);
                        background-repeat: no-repeat;
                        background-position: top 5% center;
                    }
                    #velsofbottom.halloween_theme1  {
                        background-image: url(catalog/view/image/spinwin/halloween/theme1/base.png); 
                        background-position: bottom right;
                        background-size: cover;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme halloween_theme1"></div>
                <div id="velsofbottom" class="velsoftheme halloween_theme1"> </div>
            <?php } else if ($theme == 5) { ?>
                <style> 
                    #velsoftop.halloween_theme2 {
                        background-image: url(catalog/view/image/spinwin/halloween/theme2/overlay.png);
                        background-repeat: no-repeat;
                    }
                    #velsofbottom.halloween_theme2  {
                        background-image: url(catalog/view/image/spinwin/halloween/theme2/base.png);
                        background-position: bottom right;
                    }
                    #background-image {
                        background-image: url(catalog/view/image/spinwin/halloween/theme2/background.png);
                    }
                </style>
                <div id="background-image"></div>
                <div id="velsoftop" class="velsoftheme halloween_theme2"></div>
                <div id="velsofbottom" class="velsoftheme halloween_theme2"> </div>
            <?php } else if ($theme == 6) { ?>
                <style> 
                    #velsoftop.halloween_theme3 {
                        background: url(catalog/view/image/spinwin/halloween/theme3/overlay.png);
                        background-repeat: no-repeat;
                        background-position: top 5% center;
                        opacity: 1;
                    }
                    #velsofbottom.halloween_theme3  {
                        background-image: url(catalog/view/image/spinwin/halloween/theme3/base.png);
                        background-position: bottom right;
                        opacity: 1;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme halloween_theme3"></div>
                <div id="velsofbottom" class="velsoftheme halloween_theme3"> </div>
            <?php } else if ($theme == 7) { ?>
                <style> 
                    #velsoftop.halloween_theme4 {
                            background: url(catalog/view/image/spinwin/halloween/theme4/overlay.png);
                            background-repeat: no-repeat;
                            background-position: top right;
                            opacity: 1;
                    }
                    #velsofbottom.halloween_theme4  {
                        background-image: url(catalog/view/image/spinwin/halloween/theme4/base.png);
                        background-position: bottom right;
                        opacity: 1;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme halloween_theme4"></div>
                <div id="velsofbottom" class="velsoftheme halloween_theme4"> </div>
            <?php } else if ($theme == 8) { ?>     <style> 
                    #velsoftop.black_friday_theme1 {
                        background: url(catalog/view/image/spinwin/black_friday/theme1/overlay.png);
                        background-size: cover;
                        opacity: 1;
                    }
                    #velsofbottom.black_friday_theme1  {
                        background-image: url(catalog/view/image/spinwin/black_friday/theme1/base.png);
                        background-position: bottom right;
                        background-size: auto;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme black_friday_theme1"></div>
                <div id="velsofbottom" class="velsoftheme black_friday_theme1"> </div>
            <?php } else if ($theme == 9) { ?>
                <style>
                    #velsoftop.black_friday_theme2 {
                        background: url(catalog/view/image/spinwin/black_friday/theme2/overlay.png);
                        background-repeat: no-repeat;
                        background-position: top 5% center;
                    }

                    #velsofbottom.black_friday_theme2  {
                        background-image: url(catalog/view/image/spinwin/black_friday/theme2/base.gif);
                        background-position: bottom right;
                        background-size: 48%;
                        opacity: 1;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme black_friday_theme2"></div>
                <div id="velsofbottom" class="velsoftheme black_friday_theme2"> </div>
            <?php } else if ($theme == 10) { ?>
                <style>
                    #velsoftop.black_friday_theme3 {
                        background: url(catalog/view/image/spinwin/black_friday/theme3/overlay.png);
                        background-repeat: no-repeat;
                        background-position: bottom right;
                    }

                    #velsofbottom.black_friday_theme3  {
                        background-image: url(catalog/view/image/spinwin/black_friday/theme3/base.gif);
                        background-position: bottom right;
                        opacity: 1;
                        background-size: cover;
                    }

                </style>
                <div id="velsoftop" class="velsoftheme black_friday_theme3"></div>
                <div id="velsofbottom" class="velsoftheme black_friday_theme3"> </div>
            <?php } else if ($theme == 11) { ?>
                <style>
                    #velsoftop.black_friday_theme4 {
                        background: url(catalog/view/image/spinwin/black_friday/theme4/overlay.png);
                        background-repeat: no-repeat;
                        background-position: top 5% center;
                    }

                    #velsofbottom.black_friday_theme4  {
                        background-image: url(catalog/view/image/spinwin/black_friday/theme4/base.png);
                        background-position: bottom 2% right 2%;
                        background-size: 45%;
                        opacity: 1;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme black_friday_theme4"></div>
                <div id="velsofbottom" class="velsoftheme black_friday_theme4"> </div>
            <?php } else if ($theme == 12) { ?>
                <style>
                    #velsoftop.thanks_giving_theme1 {
                        background: url(catalog/view/image/spinwin/thanks_giving/theme1/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 0.5;
                    }

                    #velsofbottom.thanks_giving_theme1  {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme1/base.png);
                        background-position: bottom right;
                        background-size: cover;
                    }
                    #background-image {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme1/background.png);
                    }
                </style>
                <div id="background-image"></div>
                <div id="velsoftop" class="velsoftheme thanks_giving_theme1"></div>
                <div id="velsofbottom" class="velsoftheme thanks_giving_theme1"> </div>
            <?php } else if ($theme == 13) { ?>
                <style>
                    #velsoftop.thanks_giving_theme2 {
                        background: url(catalog/view/image/spinwin/thanks_giving/theme2/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                        background-position: top center;
                    }

                    #velsofbottom.thanks_giving_theme2  {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme2/base.png);
                        background-position: bottom right;
                        opacity: 4;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme2/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme thanks_giving_theme2"></div>
                <div id="velsofbottom" class="velsoftheme thanks_giving_theme2"> </div>
            <?php } else if ($theme == 14) { ?>
                <style>
                    #velsoftop.thanks_giving_theme3 {
                    background: url(catalog/view/image/spinwin/thanks_giving/theme3/overlay.png);
                    background-repeat: no-repeat;
                    opacity: 1;
                    background-position: top center;
                    }

                    #velsofbottom.thanks_giving_theme3  {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme3/base.png);
                        background-position: bottom right;
                        opacity: 1;
                        background-size: cover;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme thanks_giving_theme3"></div>
                <div id="velsofbottom" class="velsoftheme thanks_giving_theme3"> </div>
            <?php } else if ($theme == 15) { ?>
                <style>
                    #velsoftop.thanks_giving_theme4 {
                    background: url(catalog/view/image/spinwin/thanks_giving/theme4/overlay.png);
                    background-repeat: no-repeat;
                    background-position: top 5% left 30%;
                    }

                    #velsofbottom.thanks_giving_theme4  {
                        background-image: url(catalog/view/image/spinwin/thanks_giving/theme4/base.png);
                        background-position: bottom right;
                        opacity: 3;
                        background-size: cover;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme thanks_giving_theme4"></div>
                <div id="velsofbottom" class="velsoftheme thanks_giving_theme4"> </div>
            <?php } else if ($theme == 16) { ?>
                <style>
                    #velsoftop.easter_theme1 {
                        background: url(catalog/view/image/spinwin/easter/theme1/overlay.png);
                        background-repeat: repeat-x;
                        background-position: top center;
                    }

                    #velsofbottom.easter_theme1  {
                        background-image: url(catalog/view/image/spinwin/easter/theme1/base.png);
                        background-position: bottom right;
                        background-size: auto;
                        opacity: 0.7;
                    }
                    #background-image {
                        background-image: url(catalog/view/image/spinwin/easter/theme1/background.png);
                    }
                </style>
                <div id="background-image"></div>
                <div id="velsoftop" class="velsoftheme easter_theme1"></div>
                <div id="velsofbottom" class="velsoftheme easter_theme1"> </div>
            <?php } else if ($theme == 17) { ?>
                <style>
                    #velsoftop.easter_theme2 {
                        background: url(catalog/view/image/spinwin/easter/theme2/overlay.png);
                        background-repeat: repeat-x;
                        opacity: 1;
                    }

                    #velsofbottom.easter_theme2  {
                        background-image: url(catalog/view/image/spinwin/easter/theme2/base.png);
                        background-position: bottom right;
                        background-size: auto;
                        opacity: 0.7;
                    }
                    #background-image {
                        background-image: url(catalog/view/image/spinwin/easter/theme2/background.png);
                    }
                </style>
                <div id="background-image"></div>
                <div id="velsoftop" class="velsoftheme easter_theme2"></div>
                <div id="velsofbottom" class="velsoftheme easter_theme2"> </div>
            <?php } else if ($theme == 18) { ?>
                <style>
                    #velsoftop.easter_theme3 {
                        background: url(catalog/view/image/spinwin/easter/theme3/overlay.png);
                        background-repeat: no-repeat;
                        background-position: top center;
                    }

                    #velsofbottom.easter_theme3  {
                        background-image: url(catalog/view/image/spinwin/easter/theme3/base.png);
                        background-position: bottom right;
                        background-size: cover;
                        opacity: 1;
                    }
                    #background-image {
                        background-image: url(catalog/view/image/spinwin/easter/theme3/background.png);
                    }
                </style>
                <div id="background-image"></div>
                <div id="velsoftop" class="velsoftheme easter_theme3"></div>
                <div id="velsofbottom" class="velsoftheme easter_theme3"> </div>
            <?php } else if ($theme == 19) { ?>
                <style>
                    #velsoftop.diwali_theme1 {
                        background: url(catalog/view/image/spinwin/diwali/theme1/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                    }
                    #velsofbottom.diwali_theme1  {
                        background-image: url(catalog/view/image/spinwin/diwali/theme1/bottom.png);
                        background-position: bottom right;
                        opacity: 1;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/diwali/theme1/base.gif);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme diwali_theme1"></div>
                <div id="velsofbottom" class="velsoftheme diwali_theme1"> </div>
            <?php } else if ($theme == 20) { ?>
                <style>
                    #velsoftop.diwali_theme2 {
                        background: url(catalog/view/image/spinwin/diwali/theme2/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                    }

                    #velsofbottom.diwali_theme2  {
                        background-image: url(catalog/view/image/spinwin/diwali/theme2/base.png);
                        background-position: bottom right;
                        opacity: 1;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/diwali/theme2/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme diwali_theme2"></div>
                <div id="velsofbottom" class="velsoftheme diwali_theme2"> </div>
            <?php } else if ($theme == 21) { ?>
                <style>
                    #velsoftop.diwali_theme3 {
                        background: url(catalog/view/image/spinwin/diwali/theme3/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                    }

                    #velsofbottom.diwali_theme3  {
                        background-image: url(catalog/view/image/spinwin/diwali/theme3/base.png);
                        background-position: bottom right 5%;
                        opacity: 1;
                        background-size: auto;
                    }
                    #velsof_wheel_main_container {
                        background-image: url(catalog/view/image/spinwin/diwali/theme3/background.png);
                    }
                </style>
                <div id="velsoftop" class="velsoftheme diwali_theme3"></div>
                <div id="velsofbottom" class="velsoftheme diwali_theme3"> </div>
            <?php } else if ($theme == 22) { ?>
                <style>
                    #velsoftop.diwali_theme4 {
                        background: url(catalog/view/image/spinwin/diwali/theme4/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 1;
                    }

                    #velsofbottom.diwali_theme4  {
                        background-image: url(catalog/view/image/spinwin/diwali/theme4/base.png);
                        background-position: bottom 10% right 5%;
                        opacity: 1;
                        background-size: auto;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme diwali_theme4"></div>
                <div id="velsofbottom" class="velsoftheme diwali_theme4"> </div>
             <?php } else if ($theme == 23) { ?>
                <style>
                    #velsoftop.diwali_theme5 {
                        background: url(catalog/view/image/spinwin/diwali/theme5/overlay.png);
                        background-repeat: no-repeat;
                        opacity: 0.7;
                    }

                    #velsofbottom.diwali_theme5  {
                        background-image: url(catalog/view/image/spinwin/diwali/theme5/base.gif);
                        opacity: 0.5;
                    }
                </style>
                <div id="velsoftop" class="velsoftheme diwali_theme5"></div>
                <div id="velsofbottom" class="velsoftheme diwali_theme5"> </div>
            <?php } else if ($theme == 0) { ?>
                <div id="velsoftop" class="velsoftheme newyear1"></div>
                <div id="velsofbottom" class="velsoftheme newyear1"> </div>
            <?php } ?>

   <div id="velsof_offer_container">
    <?php if($spinwin_imagedisplay==1){?>
     <div id="spin_wheel_logo_container"><img src='image/<?php echo $spinwin_logo;?>' alt='Logo' id='spin_wheel_logo'/></div>
     <?php }?>
     <div id="velsof_offer_main_container">
      <div id='main_title' class="velsof_main_title"><?php echo $spinwin_title_text;?></div>
      <div id = 'suc_msg' style = 'display: none;' class="velsof_main_title"></div>
      <div>
       <div id='velsof_success_description' class="velsof_subtitle" style="padding-bottom:10px ;display: none;"></div>
       <div id='velsof_description' class="velsof_subtitle" style="padding-bottom:10px;"><?php echo $spinwin_subtitle_text;?></div>
       <ul class="velsof_ul">
        <li> <?php echo $spinwin_rules_text;?></li>
      </ul>
    </div>
    <div>
     <input id='velsof_spin_wheel' type="text" name="spin_wheel_email" class="velsof_input_field" placeholder="<?php echo $email_place;?>" value=''>
     <div class="saving velsof_button" style='display:none;'><span> </span><span> </span><span> </span><span> </span><span> </span></div>
     <input id='rotate_btn' type="button" class="velsof_button" name="Rotate" value="<?php echo $try_luck;?>" onclick="onRotateWheel()" />
     <div id="exit" class="velsof_subtitle cancel_button" ><?php echo $dnt_feel;?> </div>
     <div id="continue_btn" class="velsof_button exit_button" style='display:none;'><?php echo $continue;?></div>
   </div>
 </div>
 <div class='before_loader' id="velsof_offer_main_container" style='display:none;'><img id='spin_after_loader' src="image/spinwin/loader.gif" alt='loader'/> </div>
 <div class='coupon_result'></div>
</div>
<div id="velsof_spinner_container">
 <div id="velsof_spinners">
  <div class="velsof_shadow"></div>
  <div id="velsof_spinner" class="<?php if($spinwin_wheel_design==0){?>velsof_spinner1<?php }else{?>velsof_spinner2<?php } ?>">
    <?php $deg = 0;
    foreach($spin_data as $value){
    ?>
    <div class="wheelslices" style="transform: rotate(-<?php echo $deg;?>deg) translate(0px, -50%);">
      <?php echo $value['label'];?>
    </div>
    <?php
    $deg = $deg + 30;
  }                         
  ?>
</div>
</div>
<img id='velsof_wheel_pointer' class="velsof_wheel_pointer2" src="image/spinwin/pointer2.png" alt="Ponter"/>
</div>
</div>
</div>

<script src="catalog/view/javascript/spinwin/js/ouibounce.js" type="text/javascript"></script>
<script src="catalog/view/javascript/spinwin/js/tooltipster.js" type="text/javascript"></script>
<script src="catalog/view/javascript/spinwin/js/jquery.fireworks.js" type="text/javascript"></script>
<script src="catalog/view/javascript/spinwin/js/spin_wheel.js" type="text/javascript"></script>
<script src="catalog/view/javascript/spinwin/js/velsof_wheel.js" type="text/javascript"></script>