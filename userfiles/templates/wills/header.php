<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>vertical_files/jquxery-1.js"></script>
<script type="text/javascript" src="<? print site_url('api/js') ?>"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/validations.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jScrollPane.js"></script>
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>carousel/tools.js"></script>
--><script type="text/javascript" src="<? print TEMPLATE_URL ?>vertical_files/jquery.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>vertical_files/apps1.js"></script>
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/general.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/jScrollPane.css" type="text/css" media="screen" />
<!--[if IE 7]><link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/ie.css" type="text/css" media="screen" /><![endif]-->
<!--[if IE 8]><link rel="stylesheet" href="<? print TEMPLATE_URL ?>css/ie8.css" type="text/css" media="screen" /><![endif]-->
<style>
body {
	height:100%;
	width:100%;
	margin:0;
	padding:0;
}
#wrapper {
	 
	width:670px;
	 
	top:40;
	left:0px;
	 
}
#mask {
	width:100%;
	height:500%;
	overflow:hidden;
}
.item {
	width:100%;
	display:block;
}
.content {
	top:0px;
	margin:0 auto;
	position:relative;
}
.selected {
	background:#fff;
	font-weight:700;
}

#fssList {
 display:none;	
}
</style>
<script type="text/javascript">
			
$(document).ready(function() {
	$(".panel0").addClass("selected");
	
	
	
	
	
	
	
	
	
	<? if($page['content_layout_name'] != 'will_manager'):  ?>
	
	
	
	$('a.panel').click(function () {
		$(".contantMain").click();
	
			
		current = $(this);
		var currentItem = ($(".menuTree .selected").attr("href"));
	
		var sel=$(this).attr('href');	
		$.each($(".menuTree a"),function(){
				$(".menuTree a").removeClass("selected")
			});

			$(this).addClass('selected');		
		var height  = $(sel).height();
			$('#wrapper').css("height",height);
			$('.contentMain').css("height",height+100);
			$('#wrapper').scrollTo($(this).attr('href'), 1000);		
			
	
		
		
		return false;
	});
	
	//$("#form .item2").validate();	
	
	
	<? else: ?>
	
	<? //p($page); ?>
	
	
	
 
	
	$('a.panel').click(function () {
		$(".contantMain").click();
	
			
		current = $(this);
		var currentItem = ($(".menuTree .selected").attr("href"));
		var sel=$(this).attr('href');		
		var hasErrors = 0;
	
		$(currentItem+" .required").each(function(){
			if($(this).val() == ""){
				$(this).parent().parent().addClass("error");
				hasErrors = 1;
				$(this).parents("div.floatForm").addClass("errorForm");
				$(this).parents("div.floatForm1").addClass("errorForm");
				$(this).parents("div.form").addClass("errorForm");
			}
			else{
				hasErrors = 0; 	
				$(this).parents("div.floatForm").removeClass("errorForm");
				$(this).parents("div.floatForm1").removeClass("errorForm");
				$(this).parents("div.form").removeClass("errorForm");
			}
			
			$(this).click(function(){
				$(this).parent().parent().removeClass("error");
				//$(this).val("")
			});
			$(this).blur(function(){
				if($(this).val() == ""){
					$(this).parent().parent().addClass("error");
					//$(this).val("");
				}
			});

		});
		
		$(currentItem+" .error").each(function(){
				hasErrors = 1;
		});
		
		//alert(hasErrors);
		if(hasErrors == 1){
			/*var height  = $(sel).height();
			$('#wrapper').css("height",height);
			$('.contentMain').css("height",height+100);
			$('#wrapper').scrollTo($(this).attr('href'), 1000);	
*/			
		}
		else{
			//remove class="selected" from all of the menu items
			$.each($(".menuTree a"),function(){
				$(".menuTree a").removeClass("selected")
			});

			//$(this).addClass('selected');
			$(".menuTree a[href='"+sel+"']").addClass("selected");
			var height  = $(sel).height();
			$('#wrapper').css("height",height);
			$('.contentMain').css("height",height+100);
			$('#wrapper').scrollTo($(this).attr('href'), 1000);		
		}	
			
	
		return false;
	});
	
	//$("#form .item2").validate();	
	
	
	<? endif; ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

});
	 (function($){
			$.fn.extend({
			customStyle : function(options) {
				if(!$.browser.msie || ($.browser.msie&&$.browser.version>6)) {
					return this.each(function() {
						var currentSelected = $(this).find(':selected');
						$(this).after('<span class="customStyleSelectBox"><span class="customStyleSelectBoxInner">'+currentSelected.text()+'</span></span>').css({position:'absolute', opacity:0,fontSize:$(this).next().css('font-size')});
						var selectBoxSpan = $(this).next();
						var selectBoxWidth = parseInt($(this).width()) - parseInt(selectBoxSpan.css('padding-left')) -parseInt(selectBoxSpan.css('padding-right'));            
						var selectBoxSpanInner = selectBoxSpan.find(':first-child');
						selectBoxSpan.css({display:'inline-block'});
						selectBoxSpanInner.css({width:selectBoxWidth, display:'inline-block'});
						var selectBoxHeight = parseInt(selectBoxSpan.height()) + parseInt(selectBoxSpan.css('padding-top')) + parseInt(selectBoxSpan.css('padding-bottom'));
						$(this).height(selectBoxHeight).change(function() {
							selectBoxSpanInner.text($(this).find(':selected').text()).parent().addClass('changed');
						});
				 });
				}
			}
			});
		})(jQuery);

		$(function() {
			$('select.styled').customStyle();
		});

	</script>
<script type="text/javascript">
		/*$(document).ready(function(){

			if($('.images .image').length == 1){
				$('.carousel_nav').addClass('hidden');
			}

			$('.carousel_nav').tabs('.images > .image', {
				effect: 'fade',
				fadeInSpeed: 'slow',
				fadeOutSpeed: 'slow',
				rotate: true
			}).slideshow({
				interval: 3000,
				clickable: false
			});

			$('.carousel_nav').data('slideshow').play();
		});*/
	</script>
    <script type="text/javascript" src="<? print TEMPLATE_URL ?>/slider/fadeSlideShow.js"></script>
    <script type="text/javascript">
jQuery(document).ready(function(){
	jQuery('.images').fadeSlideShow(
									
									{
										
										width: 970, // default width of the slideshow
height: 178, // default height of the slideshow
speed: 'slow', // default animation transition speed
interval: 3000, // default interval between image change
PlayPauseElement: 'fssPlayPause', // default css id for the play / pause element
PlayText: 'Play', // default play text
PauseText: 'Pause', // default pause text
NextElement: 'fssNext', // default id for next button
NextElementText: 'Next >', // default text for next button
PrevElement: 'fssPrev', // default id for prev button
PrevElementText: '< Prev', // default text for prev button
ListElement: 'fssList', // default id for image / content controll list
ListLi: 'fssLi', // default class for li's in the image / content controll
ListLiActive: 'fssActive', // default class for active state in the controll list
addListToId: false, // add the controll list to special id in your code - default false
allowKeyboardCtrl: true, // allow keyboard controlls left / right / space
autoplay: true // autoplay the slideshow
										
										}
									);
});
</script>
<script type="text/javascript">

//var img_url = '<? print TEMPLATE_URL ?>/img/'

</script>
<link rel="shortcut icon" href="<?  print site_url('favicon.ico'); ?>">
<link rel="apple-touch-icon" href="<?  print site_url('favicon.ico'); ?>">
<script type="text/javascript">

  $(document).ready(function(){
   
  });

  </script>
</head>
<body>
<div class="wrapAll">
<div class="wrapTop">
  <div class="wrapContent">
    <div class="Header"> <a class="Logo" href="<? print site_url(); ?>" title=""><img src="<? print TEMPLATE_URL ?>images/Logo.png" alt=""/></a>
      <div class="RightColumn">
        <div class="callIcon">Call now for FREE consultation
          <div class="phone">0044 857 896 541</div>
        </div>
        <a href="#" class="liveChatIcon">Click here to start Live Chat</a> </div>
      <div class="clear"></div>
    </div>
    <div class="wrapNavigation">
      <div class="Navigation">
        <div class="menuTop">
          <? $header_menu = CI::model ( 'content' )->getMenuItemsByMenuUnuqueId('main_menu', $set_active_to_all = true);
		
		// p( $header_menu,1);
		?>
          <ul>
            <? $i=0; foreach($header_menu as $item): ?>
            <li><a class="itemLink<? if($item['is_active'] == true and $i > 0): ?> active<? endif; ?><? if($item['is_active'] == true and $i == 0): ?> itemFirstActive<? endif; ?><? if($i == 0): ?> itemFirst<? endif; ?>" href="<? print  $item['the_url']; ?>" title="<? print  $item['title']; ?>">
              <? if($i > 0): ?>
              <? print  $item['title']; ?>
              <? endif; ?>
              </a></li>
            <? $i++; endforeach; ?>
          </ul>
          <!--       <ul>
            <li><a class="itemLink itemFirst itemFirstActive" href="" title="Home"></a></li>
            <li><a class="itemLink" href="" title="Get Started">Learn about Wills</a></li>
            <li><a class="itemLink" href="" title="Get Started">Get Started</a></li>
            <li><a class="itemLink" href="" title="About us">About us</a></li>
            <li><a class="itemLink" href="" title="Contact us">Contact us</a></li>
            <li><a class="itemLink" href="" title="Prices">Prices</a></li>
            <li><a class="itemLink" href="" title="Will Manager">Will Manager</a></li>
          </ul>-->
          <div class="clear"></div>
        </div>
        <div class="clear"></div>
      </div>
    </div>
    <div class="relativeContainerHome">
      <div id="carousel" class="carouselHome">
       
       
       <?
	   $pics = get_pictures(PAGE_ID, $for = 'post', $media_type = false, $queue_id = false, $collection = false);
	   
	   if(empty($pics)){
		 	   $pics = get_pictures(MAIN_PAGE_ID, $for = 'post', $media_type = false, $queue_id = false, $collection = false);		   
	   }
	   
	   if(empty($pics)){
		 	   $pics = get_pictures(HOME_PAGE_ID, $for = 'post', $media_type = false, $queue_id = false, $collection = false);		   
	   }
	  // if() ?>
       
        <div class="images">
        <? if(!empty($pics)): ?>
        <? foreach($pics as $pic): ?>
        
          <div style="background-image:url('<? print $pic['urls']['original']; ?>')" class="image">
            <div class="image_text">
              <? print($pic['media_description']); ?>
            </div>
          </div>
        
        <? endforeach; ?>
        <? endif; ?>
        
        
          
          <div class="carousel_nav"> <a href="#"></a> </div>
        </div>
      </div>
      
      
      
      
      <div class="menuSecond">
        <div class="leftHolder"></div>
        <div class="centerHolder">
          <table width="100%" border="0">
            <tr valign="middle">
              <td><editable  rel="page" field="custom_field_subscribe_teaser" page_id="<? print HOME_PAGE_ID ?>"> <a href="#">HOW CAN WE HELP YOU TO MANAGE YOUR WEALTH?</a> </editable></td>
              <td><div class="subscribeBoxHolder">
                  <form class="subscribeForm" action="?">
                    <span>SUBSCRIBE FOR NEWSLETTER</span>
                    <input type="text" value = "Type your e-mail here" onclick="value=''" onblur="this.value=!this.value?'Type your e-mail here':this.value;" />
                    <input type="submit" class="Submit" value = "SUBSCRIBE"/>
                    <div class="clear"></div>
                  </form>
                </div></td>
            </tr>
          </table>
          <div class="clear"></div>
        </div>
        <div class="rightHolder"></div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
