// Initialize jQuery Visualise
$(function(){
	$('#stats').visualize({type: 'line', height: '300px', width: '600px'});
});

// Sidebar Toggle
var fluid = {
Toggle : function(){
	var default_hide = {"grid": true };
	$.each(
		["pagesnav", "commentsnav", "userssnav", "imagesnav"],
		function() {
			var el = $("#" + (this == 'accordon' ? 'accordion-block' : this) );
			if (default_hide[this]) {
				el.hide();
				$("[id='toggle-"+this+"']").addClass("hidden")
			}
			$("[id='toggle-"+this+"']")
			.bind("click", function(e) {
				if ($(this).hasClass('hidden')){
					$(this).removeClass('hidden').addClass('visible');
					el.slideDown();
				} else {
					$(this).removeClass('visible').addClass('hidden');
					el.slideUp();
				}
				e.preventDefault();
			});
		}
	);
}
}
jQuery(function ($) {
	if($("[id^='toggle']").length){fluid.Toggle();}
});

// Notification Animations
$(function () { 
$('.notification').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
$('.notification .close').hover(
function() { $(this).addClass('hover'); },
function() { $(this).removeClass('hover'); }
);
$('.notification .close').click(function() {
$(this).parent().fadeOut('slow', function() { $(this).remove(); });
}); 

});



// jQuery UI - Live Search
$(function() {
		var availableTags = ["dashboard", "pages", "manage pages", "edit pages", "delete pages", "users", "manage users", "edit users", "delete users", "settings", "system settings", "server settings", "documentation", "help", "community forums", "contact"];
		$("#livesearch").autocomplete({
			source: availableTags
		});
	});



// jQuery UI - Dialog Box
	$(function() {
		$('#dialog').dialog({
			autoOpen: false,
			modal: true,
			width: 500
		})
		$('#opener').click(function() {
			$('#dialog').dialog('open');
			return false;
		});

	});
	
	
