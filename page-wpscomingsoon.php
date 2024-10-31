<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en">
<head profile="http://gmpg.org/xfn/11">
<meta charset="utf-8" />
	<meta name="keywords" content="<?php echo get_option('meta_keywords'); ?>" />
    <meta name="description" content="<?php echo get_option('meta_desc'); ?>" />
    <title><?php echo get_option('meta_title'); ?></title>
    <?php if (get_option('favicon_comingsoon')) { ?>
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo get_option('favicon_comingsoon');?>">
    <?php } ?>
    <script type="text/javascript" src="<?php echo $siteUrl; ?>/wp-includes/js/jquery/jquery.js?ver=1.8.3"></script>
    <?php 
    date_default_timezone_set(ini_get('date.timezone'));
    $seconds_online = get_option('online_date')." ".get_option('online_time').":00";
    $site_online	= strtotime($seconds_online);
    $seconds_online = (strtotime($seconds_online) - time()); 
    if (!empty($seconds_online)) { ?>  
    <script type="text/javascript">
      setInterval(function(){calcSoon()},1000);
      var secondsTotal=<?php echo $seconds_online; ?>;
      function calcSoon() {
        secondsTotal--;
        
        if (secondsTotal<=0) {
        		<?php 
        	 	 $nonce = wp_create_nonce("comingsoon_nonce");
			     $link = admin_url('admin-ajax.php?action=unlock_page&nonce='.$nonce);
			     ?>
			    jQuery(document).ready(function() { 
			    	jQuery.ajax({
									dataType : "html" ,
									url: "<?php echo $link; ?>" ,	
									success: function(html) {
										if(html){
											//console.log(html);
											window.location.href = "<?php echo $siteUrl; ?>";
										}
									}
							});
			    });
          		
        } else {
          var seconds = secondsTotal; 
          var days = Math.floor(seconds/86400);
          seconds = seconds%86400;
          var hours = Math.floor(seconds/3600);
          seconds = seconds%3600;
          var minutes = Math.floor(seconds/60);
          seconds = seconds%60;
        
          document.getElementById('soonDays').innerHTML  = days;
          document.getElementById('soonHours').innerHTML  = hours;
          document.getElementById('soonMinutes').innerHTML  = minutes;
          document.getElementById('soonSeconds').innerHTML  = seconds;
         
          
        }                
      }
    </script>
    <?php } ?>
  <link rel="stylesheet" href="<?php echo $urlPath.'/'; ?>css/<?php echo get_option('thmemeset'); ?>.css" type="text/css" />
  </head>
  <body onload="calcSoon();">
    <div id="soonWrapper">
      <h1><?php echo get_option('title_comingsoon'); ?></h1>
      <?php if(get_option('logo_path')) { ?>
      <img src="<?php echo get_option('logo_path'); ?>" alt="<?php echo get_option('logo_path'); ?>" id="soonLogo" />
      <?php } ?>
      <div id="soonText"><?php echo get_option('desc_coming_soon');  ?></div>
      <?php if($seconds_online) { ?>
      <div id="soonCountDown">
        <?php
          $days_online = (int)($seconds_online / 86400);
          $seconds_online %= 86400;
          $hours_online = (int)($seconds_online / 3600);
          $seconds_online %= 3600;
          $minutes_online = (int)($seconds_online / 60);
          $seconds_online %= 60;
        ?>
        <span id="soonDaysWrapper"><span id="soonDays"><?php echo $days_online;?></span> <span id="soonDaysLabel"><?php echo _e('days'); ?></span></span>
        <span id="soonHoursWrapper"><span id="soonHours"><?php echo $hours_online; ?></span> <span id="soonHoursLabel"><?php echo _e('Hours'); ?></span></span>
        <span id="soonMinutesWrapper"><span id="soonMinutes"><?php echo $minutes_online; ?></span> <span id="soonMinutesLabel"><?php echo _e('Minutes'); ?></span></span>
        <span id="soonSecondsWrapper"><span id="soonSeconds"><?php echo $seconds_online;?></span> <span id="soonSecondsLabel"><?php echo _e('Seconds'); ?></span></span>
      </div>
      <?php if(get_option('online_date')) { ?>
        <h2><?php echo _e('The site will get online on')." ".date("F j, Y | g:i a",$site_online); ?></h2>
      <?php } ?>
      <?php } ?>
    </div>
    <?php
      if (get_option('set_facebook') || get_option('set_twitter') || get_option('set_googleplus')) {
        $social_links_target = $social_links_target ? ' target="'.$social_links_target.'"' : '';
    ?>
      <ul id="social">
      <?php if (get_option('set_facebook')) { ?>
        <li><a href="https://www.facebook.com/<?php echo get_option('set_facebook'); ?>" id="facebook" title="<?php echo _e('Follow us on Facebook'); ?>"<?php echo $social_links_target; ?>><span><?php echo _e('Follow us on Facebook'); ?></span></a></li>
      <?php } ?>
      <?php if (get_option('set_twitter')) { ?>
        <li><a href="https://twitter.com/<?php echo get_option('set_twitter'); ?>" id="twitter" title="<?php echo _e('Follow us on Twitter'); ?>"<?php echo $social_links_target; ?>><span><?php echo _e('Follow us on Twitter'); ?></span></a></li>
      <?php } ?>
      <?php if (get_option('set_googleplus')) { ?>
        <li><a href="https://plus.google.com/<?php echo get_option('set_googleplus'); ?>" id="googleplus" title="<?php echo _e('Follow us on Google+'); ?>"<?php echo $social_links_target; ?>><span><?php echo _e('Follow us on Google+');?></span></a></li>
      <?php } ?>
      </ul>
    <?php } ?>
  </body>
</html>