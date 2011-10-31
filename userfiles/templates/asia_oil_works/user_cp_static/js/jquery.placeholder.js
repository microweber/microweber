/**
 * --------------------------------------------------------------------
 * jQuery-Plugin "placeholder support for non webkit browsers"
 * Version: 1.0, 08.05.2011
 * by Christian Fillies, contact@christianfillies.com
 *                       http://christianfillies.com/
 *
 * Copyright (c) 2011 Christian Fillies
 * Licensed under GPL (http://www.opensource.org/licenses/gpl-license.php)
 *
 * (en) specify the elements to get the placeholder function
 *		the passing value is the CSS Class that gets added to the parent <DIV> when the input is in focus
 *		you need to have a <DIV> around the <INPUT> to use for focusing css styles
 *
 *	EXAMPLE: $('input').placeholderFunction('input-focused');
 */ 
 
// Value as Placeholder including focusing Styles for the parent DIV
// (en) setup a placeholder using the html5 placeholder attribute as supported by webkit browsers, 
//		it then gets replaced into a standard value technique supported by all browsers.
if ($.browser.webkit) {
	$.fn.extend  ({
		placeholderFunction : function (focusClass) {
			//return this.each(function() {
//				$(this).focus(function() {
//					$(this).parentsUntil('div').parent().addClass(focusClass);
//				$(this).blur(function () {
//					$(this).parentsUntil('div').parent().removeClass(focusClass);
//			});
//			});
//		});
	} });
} else {
	$.fn.extend  ({
		placeholderFunction : function (focusClass) {
//			return this.each(function() {
//				var placeholder = $(this).attr("placeholder");
//					$(this).val(placeholder);
//					$(this).removeAttr("placeholder");
//				$(this).focus(function() {
//					$(this).parentsUntil('div').parent().addClass(focusClass);
//					if ($(this).val() == placeholder) { $(this).val(''); }
//				$(this).blur(function () {
//					$(this).parentsUntil('div').parent().removeClass(focusClass);
//					if ($.trim($(this).val()) == '') { $(this).val(placeholder); }
//				});
//			});
//		});
	}
	
	
	});
};