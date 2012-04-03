<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta name="google-site-verification" content="93gxf1wEc2wJLRiYWQ5Tvz82fFwuFA8tKJCkg2C27iQ" />


<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta name="keywords" content="{content_meta_keywords}">

<link href='https://fonts.googleapis.com/css?family=Knewave' rel='stylesheet' type='text/css'>

<link rel="shortcut icon" type="image/x-icon" href="<? print TEMPLATE_URL ?>favicon.ico">
<link rel="profile" href="https://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>style2.css" />

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.js"></script>

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_ui.js"></script>

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jqModal.js"></script>
<script type="text/javascript" src="<? print site_url("api/js"); ?>"></script>

<link rel="stylesheet" type="text/css" media="all" href="<? print TEMPLATE_URL ?>js/jqModal.css" />
<!--<link type='text/css' href='<? print TEMPLATE_URL ?>js/simple_modal/css/osx.css' rel='stylesheet' media='screen' />

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/simple_modal/jquery.simplemodal.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/simple_modal/osx.js"></script>-->




<!--<script type="text/javascript" src="http://google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1.6.0")</script>
<script type="text/javascript">google.load("jqueryui", "1.8.16");</script>-->
<script>
var template_url = "<? print TEMPLATE_URL ?>";
var post_name = "<?php print $post->post_name; ?>";

</script>

<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/libs.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/functions.js"></script>
 





<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery.lightbox_me.js"></script>
<script src="<? print TEMPLATE_URL ?>ed_video/js/flash_detect.js" language="javascript"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>ed_video/js/swfobject.js"></script>
<style type="text/css">
#flashid {
	float:right;
	position: absolute;
	left: 0px;
	bottom: 0px;
}
div > div#flashid {
	position: fixed;
}
pre.fixit {
	overflow:auto;
	border-left:1px dashed #000;
	border-right:1px dashed #000;
	padding-left:2px;
}

#top_fb {
	color: #EC1F24;
float: right;
font-family: Verdana,Geneva,sans-serif;
font-size: 10px;
text-decoration: none;
top: 62px;
position: relative;
right: 0px;
left: 555px;
}


.not_bundle {
 display:none;	
}
</style>
<script src="<? print TEMPLATE_URL ?>ed_video/js/AC_RunActiveContent.js" type="text/javascript"></script>


<script>


function add_to_cart_form($selector){
	mw.cart.add($selector, function(){add_to_cart_callback()});
}







function close_model(){
 
	
	
	popup.close();
	
	$(".PopUp").fadeOut();
 
 
}

function add_to_cart_callback(){
	 
	 
	 
	mw.reload_module("cart/items");
 
 
	
	
	
	var string =  $("#"+'goto_checkout').html();

Modal.content.innerHTML = string;


$(Modal.main).css({
  width:360,
  height:170,
  display:'block'
}).find(".PopUpContentBox").css({
  height:140,
  overflow:'auto'
});

$(Modal.overlay).show()





	
	 
	 
}


function see_all_offers(){
	$(".offer_ctrl").fadeIn();
	$(".cox_content_holder").hide();
	$(".not_bundle").hide();
	
	
	
	
	
}


function see_only_offers($sel){
	$(".offer_ctrl").hide();
	$(".cox_content_holder").hide();
	
	
	
	if($sel == undefined){
	
	$(".offer_ctrl_select_boxes:checked").each(function(){
														
														
														
														
					$v = $(this).val() ;									
						$($v).show();								
														
														
														
    // add to your array
});

	
	
	} else {
	
	
	
	
	
	$($sel).fadeIn();
	}
}

function set_order($number){
	
	
	$(".offer_ctrl").fadeOut();
	$(".offer_ctrl_"+$number).fadeIn();
	
	
	if($number == 0){
		$(".offer_ctrl").show();
	}

	
	$("#offer_plan").find("option").each(function(){
	
			$(this).removeAttr("selected");
		
	});
	
	
	var ins = $("#offer_plan_sel_val");

if($('#offer_name').length == 0){
    ins.parent().prepend('<input id="offer_name" name="offer_name" type="hidden" >');
}
	
	
	
	
	$("#offer_plan").find("option").each(function(){
		if( $(this).val() == $number ) {
			$(this).attr("selected","selected");
			$text = $(this).text()
			$("#offer_plan_sel_val").html($text);
				$("#offer_name").val($text);
			$(".chosen_off").html($text);
			
			
		}
	});
	
	
	
	
}


  function scrollTo(selector) {
        var targetOffset = $(selector).offset().top;
        $('html,body').animate({scrollTop: targetOffset}, 500);
    }

function toggle_el(sel){
	
	  $(sel).toggle();
	 //  scrollTo(sel)
 
}


 

function open_lightbox(sel){
	$(sel).lightbox_me();
}
function open_promo_details(div_id){
	


//$("#"+div_id).html().popup()


var string =  $("#"+div_id).html();

Modal.content.innerHTML = string;


$(Modal.main).css({
  width:600,
  height:470,
  display:'block'
}).find(".PopUpContentBox").css({
  height:440,
  overflow:'auto'
});

$(Modal.overlay).show()


}


</script><!-- K_yiZ5buyrHQs3CgqVafrX2Gkrk -->

<script type="text/javascript">
$(document).ready(function(){

  $(".TheForm").validate({
    valid:function(){

        var data = getData(this);
		if(data.post_name != undefined){
			post_name = data.post_name;
		}
		if(post_name != undefined){
		data.post_name	= post_name;
		}
	//	$('#conf').lightbox_me();	
	'<center><h1>Your request has been sent. You will be receiving an email confirmation shortly.</h1></center>'.popup();
        $.post("<?php echo TEMPLATE_URL ; ?>mailsender.php", data, function(){
																		  
																			  
																			  
//





        });

      return false;
    },
    error:function(){
      "Please complete every field".alert();
    },
    preventSubmit:true
});		
  });		
</script>
 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body  class="page page-id-<? print PAGE_ID ?> page-child parent-pageid-32 page-template page-template-empty-php">
 
<div id="wrapper">
<div id="header">
  <div class="wrapper"> 
  <!-- LiveZilla Chat Button Link Code (ALWAYS PLACE IN BODY ELEMENT) --><div style="display:none;"><a href="javascript:void(window.open('<?php echo SITE_URL; ?>chats/digital-connections/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img id="chat_button_image" src="<?php echo SITE_URL; ?>chats/digital-connections/image.php?id=04&amp;type=overlay" width="37" height="123" border="0" alt="LiveZilla Live Help" /></a></div><!-- http://www.LiveZilla.net Chat Button Link Code --><!-- LiveZilla Tracking Code (ALWAYS PLACE IN BODY ELEMENT) --><div id="livezilla_tracking" style="display:none"></div><script type="text/javascript">
/* <![CDATA[ */
var script = document.createElement("script");script.type="text/javascript";var src = "<?php echo SITE_URL; ?>chats/digital-connections/server.php?request=track&output=jcrpt&fbpos=10&fbml=0&fbmt=0&fbmr=0&fbmb=0&fbw=37&fbh=123&nse="+Math.random();setTimeout("script.src=src;document.getElementById('livezilla_tracking').appendChild(script)",1);
/* ]]> */
</script><noscript><img src="<?php echo SITE_URL; ?>chats/digital-connections/server.php?request=track&amp;output=nojcrpt&amp;fbpos=10&amp;fbml=0&amp;fbmt=0&amp;fbmr=0&amp;fbmb=0&amp;fbw=37&amp;fbh=123" width="0" height="0" style="visibility:hidden;" alt="" /></noscript><!-- http://www.LiveZilla.net Tracking Code -->
   <a id="top_phone" style="margin-left:3px" href="javascript:void(window.open('<?php echo SITE_URL; ?>chats/digital-connections/chat.php','','width=590,height=610,left=0,top=0,resizable=yes,menubar=no,location=no,status=yes,scrollbars=yes'))"><img id="top_chat" src="<? print TEMPLATE_URL ?>images/top_chat.jpg" /></a>
  <img id="top_phone" src="<? print TEMPLATE_URL ?>images/top_phone.jpg" /> 
  
     <div id="top_slogan">DON'T BE FOOLED BY OTHERS. WE ARE A REAL LOCAL DEALER</div>
 
<div id="top_fb">
 <a href="https://www.facebook.com/pages/Digital-Connections-Inc/247969741895199" target="_blank" ><img  src="<? print TEMPLATE_URL ?>images/facebook-icon-gray.png" height="20" align="left" style="margin-right:3px" /></a> 
     <iframe   src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FDigital-Connections-Inc%2F247969741895199&amp;send=false&amp;layout=button_count&amp;width=300&amp;show_faces=false&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=213414455371956" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:21px;" allowTransparency="true"></iframe>
     </div>
   
  <a id="logo" href="<?php echo SITE_URL; ?>"><img src="<? print TEMPLATE_URL ?>images/logo.jpg" alt="" /></a>

    <div class="c" style="height: 22px;">&nbsp;</div>
  </div>
  <div id="nav">
    <div class="wrapper">
      <?php //current_page_item wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
      <div class="menu">
      <microweber module="content/menu"  name="main_menu"  />
        
      </div>
    </div>
  </div>
</div>
<div  id="conf" style="display:none"><center><h1>Your request has been sent. You will be receiving an email confirmation shortly.</h1></center></div>
<!-- #header -->
<div id="main">
