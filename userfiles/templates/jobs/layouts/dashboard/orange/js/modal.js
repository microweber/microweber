$(document).ready(function() {	
	$('a.modal').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('href');
		var maskHeight = $(document).height();
		var maskWidth = $(window).width();
		$('#mask').css({'width':maskWidth,'height':maskHeight});
		$('#mask').fadeTo(800,0.95);	
		var winH = $(window).height();
		var winW = $(window).width();
		$(id).css('top',  winH/2.5-$(id).height()/2);
		$(id).css('left', winW/2-$(id).width()/2);
		$(id).fadeIn(2000); 
	});	
	$('#mask').click(function () {
		$(this).hide();
		$('.modal-container').hide();
	});			
});