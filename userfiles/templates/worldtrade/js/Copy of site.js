// JavaScript Document
$(document).ready(function() {
	
	

	
	
	
	if($('#related_products')){
		$('#related_products').cycle({
			fastOnEvent: 2000,
			timeout: 10000,
			speed: 2000,
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
});


	$('.mw').live('mw_module_reloaded', function(name) {
  $('#top_nav > li > a').css({'width': $('#top_nav').width()/6});
});