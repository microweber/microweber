<script type="text/javascript">
function css_browser_selector(u){var ua = u.toLowerCase(),is=function(t){return ua.indexOf(t)>-1;},g='gecko',w='webkit',s='safari',o='opera',h=document.documentElement,b=[(!(/opera|webtv/i.test(ua))&&/msie\s(\d)/.test(ua))?('ie ie'+RegExp.$1):is('firefox/2')?g+' ff2':is('firefox/3.5')?g+' ff3 ff3_5':is('firefox/3')?g+' ff3':is('gecko/')?g:is('opera')?o+(/version\/(\d+)/.test(ua)?' '+o+RegExp.$1:(/opera(\s|\/)(\d+)/.test(ua)?' '+o+RegExp.$2:'')):is('konqueror')?'konqueror':is('chrome')?w+' chrome':is('iron')?w+' iron':is('applewebkit/')?w+' '+s+(/version\/(\d+)/.test(ua)?' '+s+RegExp.$1:''):is('mozilla/')?g:'',is('j2me')?'mobile':is('iphone')?'iphone':is('ipod')?'ipod':is('mac')?'mac':is('darwin')?'mac':is('webtv')?'webtv':is('win')?'win':is('freebsd')?'freebsd':(is('x11')||is('linux'))?'linux':'','js']; c = b.join(' '); h.className += ' '+c; return c;}; css_browser_selector(navigator.userAgent);
</script>
<script type="text/javascript">

			var template_url = '<?php print TEMPLATE_URL; ?>';
           
                var img_url = '<?php print TEMPLATE_URL; ?>img/';
  			    var imgurl = '<?php print TEMPLATE_URL; ?>img/';

			var site_url = '<?php print site_url(); ?>';
			var ajax_mail_form_url = '<?php print site_url('main/mailform_send'); ?>';
			var ajax_mail_form_url_validate = '<?php print site_url('main/mailform_send/validate:yes'); ?>';
			var ajax_mail_form_url = '<?php print site_url('main/mailform_send2'); ?>';
			var current_url = '<?php print current_url(); ?>';
			var imgurl = '<?php print TEMPLATE_URL; ?>img/';
        </script>
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>css/style.css" type="text/css" media="all"  />
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>css/ooyes.framework.css" type="text/css" media="all"  />
<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">
google.load("jquery", "1.4.2");
google.load("jqueryui", "1.8.1");
</script>
<script type="text/javascript">
 
 var categories_search="<?php print $this->core_model->getParamFromURL ( 'selected_categories' ); ?>";
 //alert(categories_search);
</script>
<!--<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/functions.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>js/libs.js"></script>
-->
<script type="text/javascript" src="{JS_API_URL}"></script>




 <!--[if IE]><?php echo '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<link rel="shortcut icon" type="image/x-icon" href="<?php print TEMPLATE_URL; ?>favicon.ico" />
 