/*
*************************************************

	SEBASTIAN NITU
	User Interface Functionality for BaseDemo

	Created by Sebastian Nitu
	http://www.sebnitu.com

*************************************************
*/

/*-------------------------------------------
	When Document is Ready (no conflict mode)
---------------------------------------------*/

(function ($) {

	$(document).ready(function () {

		$('.demo').each(function () {

			var tabs = $(this).find('.tabs a'),
					panels = $(this).find('.panel').hide(),
					hash = window.location.hash;

			$(this).find('.tabs a:first').addClass('active');
			$(this).find('.panel:first').show();

			tabs.click(function () {
				var active = $(this).attr("href");

				// Toggle active class
				tabs.parent().removeClass('active');
				$(this).parent().addClass('active');

				// Toggle selected panel
				panels.hide();
				$(active).show();

				// Prevent default behavior
				return false;
			});

		});

		var nav = $('.nav'),
				footer = $('.footer');

		nav.find('.download a').prepend('<img src="' + myScriptSrc + 'bd.download.png">');
		
		// For some reason IE 8 and below does not like this rule
		if ($.browser.msie && ($.browser.version < 9.0) ) { } else {
			footer.append('<p>Powered by <a href="https://github.com/sebnitu/BaseDemo">BaseDemo</a>. Code responsibly.</p>');
		}
		
	});

})(jQuery);

/*-------------------------------------------
	Fin
---------------------------------------------*/