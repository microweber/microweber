/**
 * jquery.treeview.js
 * JQuery Treeview Plugin
 *
 * Copyright (c) 2010 Guido Neele <http://www.neele.name>
 * Dual licensed under the MIT and GPL licenses. 
 * 
 * ---- Converts unordered lists into a treeview ----
 * 
 * <ul class="treeview">
 *   <li>
 *     <a href class="folder">Item 1</a>
 *       <ul>
 *         <li><a href class="folder">Item 1.1</a></li>
 *         <li><a href class="folder">Item 1.2</a></li>
 *         <li><a href class="folder">Item 1.3</a></li>
 *       </ul>
 *   </li>
 *   <li><a href class="folder">Item 2</a></li>
 * </ul>
 * 
**/
jQuery.TreeView = function(){
	
	var shiftTimerId = 0;
	
	// Zorgen dat links in beeld scrollen als je er met de muis overheen gaat
	$('ul.treeview a')
		.mouseover(function(){
			var center = 0;
			var intScroll = 0;
			var width = $(this).width();
			var divWidth = $('div#tree').width();		
			var offset = $(this).offset()
			
			// Verschuiven als er 1 seconde op de link gehoverd wordt.
			shiftTimerId = setTimeout ( function(){
												 
				if ($(this).parent().parent().is('ul.treeview')){
					
					// Als er over een top-level link gehoverd wordt dan is scrollLeft 0
					$('div#tree').animate({scrollLeft: 0}, 500);
					
				} else {
					
					// Berekenen wanneer link precies in het midden staat
					center = Math.floor((divWidth - width) / 2);
					
					// Kijken of link al in het midden staat. Zo nee, dan rekening houden met de reeds gescrollde breedte
					// en berekenen hoeveel er gescrolld moet worden zodat link in het midden komt te staan.
					if(offset.left != center) {
						intScroll = offset.scrollLeft + (offset.left - center);
						$('div#tree').animate({scrollLeft: intScroll}, 500);
					}
				}
				
			}, 1000);
			
		})
		
		.mouseout(function(){
			// Als er niet 1 seconde op de link gehoverd wordt, dan moet verschuiving afgebroken worden.
			clearTimeout ( shiftTimerId );				
		})
	
		// Zorgen dat de eindcap getoond wordt als er gehoverd wordt.
		.append('<span class="endcap"><img src="img/spacer.gif" width="6" height="22" /></span>')
		
		// Icoontje toevoegen aan link aan de hand van de naam van de class
		.each(function(){
			
			$(this).prepend('<span class="icon"><img src="img/spacer.gif" width="26" height="22" /></span>');							 
		})
		
		// De links een class 'link' geven
		.addClass('link')
		
	.end();
	
	// Spacer toevoegen voor link
	$('ul.treeview li').prepend('<a class="spacer"><img src="img/spacer.gif" width="16" height="22" /></a>');
	
	// Als listitem kinderen bevat dan class 'children' toevoegen zodat handler getoond wordt
	$('ul.treeview li:has(ul) > a.spacer').addClass('children');
	
	// Zorgen dat handlers verschijnen bij mouseovers en verdwijnen bij mouseouts 
	$('ul.treeview a.children').css({'opacity' : 0});
	$('div#tree').hover(
		function(){
			$('ul.treeview a.children').animate({'opacity' : 1}, 'slow');
		}, function(){
			$('ul.treeview a.children').animate({'opacity' : 0}, 'slow');	
		}
	);
	
	// Zorgen dat bij klik op handler de kinderen getoond of verborgen worden
	$('ul.treeview a.children').bind('click', function(){
		$(this).toggleClass('open');
		$(this).next().next().toggle();	
		return false;
	});
};