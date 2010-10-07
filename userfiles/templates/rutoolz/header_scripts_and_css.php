<script type="text/javascript">
function css_browser_selector(u){var ua = u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
</script>
<script type="text/javascript">



			var template_url = '<? print TEMPLATE_URL; ?>';
            <? if(!empty($subdomain_user)): ?>
              //  var img_url = '<? print TEMPLATE_URL; ?>affimg/';
  			   // var imgurl = '<? print TEMPLATE_URL; ?>affimg/';
            <?  else: ?>
                var img_url = '<? print TEMPLATE_URL; ?>img/';
  			    var imgurl = '<? print TEMPLATE_URL; ?>img/';
            <? endif;?>
			var site_url = '<? print site_url(); ?>/';
			var ajax_mail_form_url = '<? print site_url('main/mailform_send'); ?>';
			var ajax_mail_form_url_validate = '<? print site_url('main/mailform_send/validate:yes'); ?>';
			var ajax_mail_form_url = '<? print site_url('main/mailform_send2'); ?>';
			var current_url = '<? print current_url(); ?>';
			var imgurl = '<? print TEMPLATE_URL; ?>img/';
        </script>
<link rel="stylesheet" href="<? print TEMPLATE_URL; ?>css/visual.css" type="text/css" media="all"  />
<script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/jquery.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/libs.js"></script>
 <script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/jquery.form.js"></script>
 <script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/jquery.fn.handleKeyboardChange.js"></script>
  <script type="text/javascript" src="<? print TEMPLATE_URL; ?>js/functions.js"></script>
 <!--[if IE]><? echo '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<link rel="shortcut icon" type="image/x-icon" href="<? print TEMPLATE_URL; ?>favicon.ico" />

<script type="text/javascript">
$(window).load(function(){
   $(".submit").click(function(){
     $(this).parents("form").submit();
     return false;
   });
   $(".submitenter").click(function(){
     $(this).parents("form").find("input[type=submit]").click();
     return false;
   });


});

$(document).ready(function(){
	
$("form").submit(function(){
		$(this).find('iframe').contents().find('.remove-on-submit').remove();
    $('.remove-on-submit').remove();
	//$('.remove-on-submit').remove();
   
   //return false;
});

});



   
                </script>

<? //if(is_file(ACTIVE_TEMPLATE_DIR.'shop_cart.js.php') == true){ include (ACTIVE_TEMPLATE_DIR.'shop_cart.js.php'); } ?>
<? if(is_file(ACTIVE_TEMPLATE_DIR.'users/users.js.php') == true){ include (ACTIVE_TEMPLATE_DIR.'users/users.js.php'); }  ?> 
<? if($load_tiny_mce == true) : ?>
<? if(is_file(ACTIVE_TEMPLATE_DIR.'header_scripts_editor.php') == true){ include (ACTIVE_TEMPLATE_DIR.'header_scripts_editor.php'); }  ?>
<? endif; ?>

<script type="text/javascript" src="<? print TEMPLATE_URL; ?>videos/flowplayer/flowplayer-3.2.0.min.js"></script>

