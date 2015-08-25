<?php

$option_group=$params['id'];

if(isset($params['option-group'])){
$option_group=$params['option-group'];	
	
}



    $facebook_enabled = get_option('facebook_enabled', $option_group) == 'y';
    $twitter_enabled = get_option('twitter_enabled', $option_group) == 'y';
    $googleplus_enabled = get_option('googleplus_enabled', $option_group) == 'y';
    $pinterest_enabled = get_option('pinterest_enabled', $option_group) == 'y';
    $youtube_enabled = get_option('youtube_enabled', $option_group) == 'y';
    $linkedin_enabled = get_option('linkedin_enabled', $option_group) == 'y';
    $instagram_enabled = get_option('instagram_enabled', $option_group) == 'y';

    $instagram_url =  get_option('instagram_url', $option_group);
	if($instagram_url == false){
	$instagram_url =  get_option('instagram_url', 'website');
	}
	
	
    $facebook_url =  get_option('facebook_url', $option_group);
	
	if($facebook_url == false){
	$facebook_url =  get_option('facebook_url', 'website');
	}
	
    $twitter_url =  get_option('twitter_url', $option_group);
	
	if($twitter_url == false){
	$twitter_url =  get_option('twitter_url', 'website');
	}
	
	
	
	
    $googleplus_url =  get_option('googleplus_url', $option_group);
	
	if($googleplus_url == false){
	$googleplus_url =  get_option('googleplus_url', 'website');
	}
	
	
    $pinterest_url =  get_option('pinterest_url', $option_group);
	
	
	if($pinterest_url == false){
	$pinterest_url =  get_option('pinterest_url', 'website');
	}
	
	
    $youtube_url =  get_option('youtube_url', $option_group);
	
	if($youtube_url == false){
	$youtube_url =  get_option('youtube_url', 'website');
	}
	
	
	
	
    $linkedin_url =  get_option('linkedin_url', $option_group);
	
	if($linkedin_url == false){
	$linkedin_url =  get_option('linkedin_url', 'website');
	}
	

    $social_links_has_enabled = false;

    if($facebook_enabled or $twitter_enabled  or $googleplus_enabled  or $pinterest_enabled  or $youtube_enabled or $linkedin_enabled){
        $social_links_has_enabled = true;
    }



?>

<script>mw.moduleCSS('<?php print module_url(); ?>style.css');</script>
<div class="mw-social-links">

   <?php if($social_links_has_enabled == false){ print lnotif('Social links'); } ?>


<?php  if($facebook_enabled){ ?>


    <a href="//facebook.com/<?php print $facebook_url; ?>" target="_blank"><span class="mw-icon-facebook"></span></a>

<?php } ?>

<?php  if($twitter_enabled){ ?>

    <a href="//twitter.com/<?php print $twitter_url; ?>" target="_blank"><span class="mw-icon-twitter"></span></a>

<?php } ?>


<?php  if($googleplus_enabled){ ?>

    <a href="//plus.google.com/+<?php print $googleplus_url; ?>" target="_blank"><span class="mw-icon-googleplus"></span></a>

<?php } ?>

<?php  if($pinterest_enabled){ ?>

    <a href="//pinterest.com/<?php print $pinterest_url; ?>" target="_blank"><span class="mw-icon-social-pinterest"></span></a>

<?php } ?>

<?php  if($youtube_enabled){ ?>

    <a href="//youtube.com/<?php print $youtube_url; ?>" target="_blank"><span class="mw-icon-social-youtube"></span></a>

<?php } ?>

<?php  if($instagram_enabled){ ?>

    <a href="https://instagram.com/<?php print $instagram_url; ?>" target="_blank"><span class="mw-icon-social-instagram"></span></a>

<?php } ?>

<?php  if($linkedin_enabled){ ?>

    <a href="//linkedin.com/<?php print $linkedin_url; ?>" target="_blank"><span class="mw-icon-social-linkedin"></span></a>

<?php } ?>




</div>