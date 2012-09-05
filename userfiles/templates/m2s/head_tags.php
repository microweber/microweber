
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<? print TEMPLATE_URL ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<? print TEMPLATE_URL ?>css/styles_peter.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/cufon-yui.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Kozuka_Gothic_Pro_OpenType_700.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Kozuka_Gothic_Pro_OpenType_400.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Helvetica_LT_700.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/Myriad_Pro_400.font.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/cufon-replace.js"></script>
<!--  button scroller files -->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/l10n.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_003.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_002.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/nivo-slider/jquery.nivo.slider.js"></script>
<link href="<? print TEMPLATE_URL ?>js/nivo-slider/nivo-slider.css" type="text/css" />
<script type="text/javascript">
var $ = jQuery.noConflict();
 
</script>
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/field_label/src/jquery.infieldlabel.js"></script>
-->
<script type="text/javascript" src="<? print site_url('api/js'); ?>"></script>
<!--
  jCarousel library
-->
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>/lib/jquery.jcarousel.js"></script>
-->
<!--
  jCarousel skin stylesheet
-->
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/skins/tango/skin.css" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/layouts/dashboard/dashboard.css" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/layouts/dashboard/orange/style.css" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/layouts/dashboard/orange/color.css" />
<script type="text/javascript" src="<? print TEMPLATE_URL ?>/layouts/dashboard/orange/js/modal.js"></script>
<!-- JS/JQuery Modal Dialog -->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>/layouts/dashboard/orange/js/common.js"></script>
<script type="text/javascript" type='text/javascript' src='http://money2study.co.uk/openads/adx.js'></script>
<script type="text/javascript">

jQuery(document).ready(function() {
  /*  jQuery('#mycarousel').jcarousel({
    	wrap: 'circular'
    });*/
	
	jQuery("#mycarousel .bx-prev").hover(function() {
/*alert("Testing");*/

 jQuery("#mycarousel .bx-prev").click();

});

	
});

<?  if(!stristr(url(), 'tube')): ?>
    $(document).ready(function(){
      $("a[href*='http://']").not("[href*='<? print site_url(); ?>']").each(function(){
        var html = $(this).html();
        var href = $(this).attr("href");
        $(this).attr("target", "_blank");
		
		 
		

		<? if(!empty($post)): ?>
		 $(this).attr("href", "<? print site_url(); ?>surl.php?post=<? print $post['id'] ?>&url=" + href);
		 <? else: ?>
		  $(this).attr("href", "<? print site_url(); ?>surl.php?url=" + href);
		<? endif; ?>
		

        if(mw.browser.msie()){ // msie !!!
           $(this).html(html);
        }

      });
    });
	
	<? endif; ?>
	
</script>
<!--  button scroller files -->
<style>
body {
	background-color: #ffffff;
 background-image: url(<? print TEMPLATE_URL ?>images/innerbg.jpg);
	background-repeat: repeat-x;
	background-position: top;
}
</style>
 