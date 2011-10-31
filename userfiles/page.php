
<h1>SOLD !</h1>

<? exit(); ?>

<? require 'fbsdk/facebook.php'; ?>

<? if(is_page('save')): ?>  <? include "save.php" ?>  <? endif; ?>


<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<? /*

<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>


  FB.init({appId: '170150863044838', status: true, cookie: true, xfbml: true});
        //Resize the iframe when needed
        FB.Canvas.setAutoResize();
</script>   */ ?>


<?
function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2);

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}


?>

<?
$r = $_REQUEST['signed_request'];

$getit =  parse_signed_request($r, '6750d63d616ac8d6ab3995f5f5d3070d');


$requestID =  $getit['page']['id'];


?>

<?
if (is_page('info')):
?>
   <div style="text-align: center;padding-bottom:20px;padding-top:20px;">
     <object width="560" height="349">

        <param name="movie" value="http://www.youtube.com/v/cSNBLJFvNLI?fs=1&amp;hl=en_US&amp;autoplay=0&amp;rel=0"></param>
        <param name="allowFullScreen" value="true"></param>
        <param name="allowscriptaccess" value="always"></param>
        <embed src="http://www.youtube.com/v/cSNBLJFvNLI?fs=1&amp;hl=en_US&amp;autoplay=0&amp;rel=0" type="application/x-shockwave-flash" width="560" height="349" allowscriptaccess="always" allowfullscreen="true"></embed>
     </object>


   </div>

<? endif; ?>




<?

if(is_page('view')):

?>


<? /* START */ ?>
















<?



   if(get_page_by_title($requestID)){
     echo '<script>window.location.href="http://app.iwanttobehere.com/'. $requestID . '"</script>';
   }
   else{
     global $user_ID;
$new_post = array(
    'post_title' => $requestID,
    'post_content' => ' ',
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => $user_ID,
    'post_type' => 'page',
    'post_category' => array(0)
);



$post_id = wp_insert_post($new_post);


add_post_meta($post_id, 'MO_CONTENT', 'Insert Any Kind Of content That you Want', true);



   }


   echo '<script>window.location.href="http://app.iwanttobehere.com/'. $requestID . '"</script>';





?>



<? /* STOP */ ?>



<? endif; ?>




<? if(is_page('fbeditapp')): ?>




<?
   if (isset($_GET['fb_page_id'])){
      $fbid =  $_GET['fb_page_id'];


   if(get_page_by_title($fbid)){
     echo '<script>window.location.href="http://app.iwanttobehere.com/'. $fbid . '?fbeditapp=true"</script>';
   }
   else{
     global $user_ID;
$new_post = array(
    'post_title' => $fbid,
    'post_content' => 'Insert Any Kind Of content That you Want :)...',
    'post_status' => 'publish',
    'post_date' => date('Y-m-d H:i:s'),
    'post_author' => $user_ID,
    'post_type' => 'page',
    'post_category' => array(0)
);



$post_id = wp_insert_post($new_post);
   }


   echo '<script>window.location.href="http://app.iwanttobehere.com/'. $fbid . '?fbeditapp=true"</script>';



   }

?>

<? endif; ?>




<script type="text/javascript" src="<?php bloginfo( 'template_url' ); ?>/jscripts/tiny_mce/jquery.tinymce.js"></script>



  <script>









  $(document).ready(function(){


  if(window.location.href.indexOf('fbeditapp')==-1){













  }


  else{


  document.body.className = 'editmode';











  }








  })

  </script>










			<?php

			get_template_part( 'loop', 'page' );
			?>


<div id="overlay">&nbsp;</div>

<? get_footer(); ?>
