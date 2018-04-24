<?php  

$facebook_app_id = get_option('facebook_app_id', 'comments');  
$facebook_number_of_comments = get_option('facebook_number_of_comments', 'comments'); 
$facebook_color_scheme = get_option('facebook_color_scheme', 'comments');

if(intval($facebook_number_of_comments) <= 0){
$facebook_number_of_comments = 5;	
}
 
?>
<div id="fb-root-comments-engine-<?php print $params['id'] ?>"></div>
<script>

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php print $facebook_app_id; ?>&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

</script>



<div class="fb-comments" data-width="100%" data-href="<?php print url_current(); ?>" data-numposts="<?php print $facebook_number_of_comments; ?>" data-colorscheme="<?php print $facebook_color_scheme; ?>"></div>


 
 
 