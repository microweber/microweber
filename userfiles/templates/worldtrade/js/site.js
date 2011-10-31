// JavaScript Document
$(document).ready(function() {
	$('#top_nav > li > a').css({'width': $('#top_nav').width()/6});
	
	set_min_height();
	
	if($('#related_products')){
		$('#related_products').cycle({
			fastOnEvent: 2000,
			timeout: 10000,
			speed: 2000,
			 height:        400,
			pager: '#pager',
			next: '#next', 
    		prev: '#prev'
		});
		$('#related_products').mouseover(function() {
			$('#related_products').cycle('pause');
			$('#related_products').mouseleave(function() {
				$('#related_products').cycle('resume');
			});
		});
	}
	
	$('body').css('overflow-x','hidden');
});

$(window).resize( function(){
	set_min_height();
})

function set_min_height(){
	if($('#content > .all_width')){
		//$('#middle').css({'min-height': $(window).height() - ($('#header').height() + $('#footer').height() + $('#content > .all_width').height())});
	}else{
		//$('#middle').css({'min-height': $(window).height() - ($('#header').height() + $('#footer').height())});
	}
}