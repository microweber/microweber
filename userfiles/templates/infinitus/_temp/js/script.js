	// Initialize prettyPhoto plugin
	
	$(document).ready(function(){
		$("a[data-gal^='prettyPhoto']").prettyPhoto({
			theme:'light_square', 
			autoplay_slideshow: true, 
			overlay_gallery: false, 
			show_title: false,
		});
	});
	// Create Dropdown Menu
	$(document).ready(function() {	   
      // Create the dropdown base
      $("<select />").appendTo("nav");
      
      // Create default option "Go to..."
      $("<option />", {
         "selected": "selected",
         "value"   : "",
         "text"    : "Go to..."
      }).appendTo("nav select");
      
      // Populate dropdown with menu items
      $("nav a").each(function() {
       var el = $(this);
       $("<option />", {
           "value"   : el.attr("href"),
           "text"    : el.text()
       }).appendTo("nav select");
      });
      
	   // To make dropdown actually work
	   // To make more unobtrusive: http://css-tricks.com/4064-unobtrusive-page-changer/
      $("nav select").change(function() {
        window.location = $(this).find("option:selected").val();
      });
	 
	 });
	 
	$(document).ready(function(){
      $('.testimonials-slider.flexslider').flexslider({
		animation: "slide",
		selector: ".slides > li",
		slideshow: false,
		smoothHeight: true,
		directionNav: false,
	  });
    });
	$(document).ready(function() {
	  	$('.flexslider').flexslider({
			animation: "fade",
			directionNav: true,
			controlNav: true, 
			easing: "swing", 
			direction: "horizontal",
			controlsContainer: ".flex-container",
			before: function(){
				$('.flex-caption').animate({'left':'0px','opacity':'0'},0,'easeOutBack'); 
				},
				after: function(){
					$('.flex-caption').animate({'left':'50px','opacity':'1'},800,'easeOutBack'); 
					},
			
				});
		});
	$(document).ready(function(){
      $('.home-slider .flexslider').flexslider({
		animation: "slide",
		selector: ".slides > li",
		slideshow: false,
		smoothHeight: false,
		directionNav: true,
	  });
    });
	
	
	//INITIALIZES BACK TO TOP
	$(document).ready(function(){
		// hide #back-top first
		$("#back-top").hide();		
		// fade in #back-top
		$(function () {
			$(window).scroll(function () {
				if ($(this).scrollTop() > 100) {
					$('#back-top').fadeIn();
				} else {
					$('#back-top').fadeOut();
				}
			});	
			// scroll body to 0px on click
			$('#back-top a').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 800);
				return false;
			});
		});	
	});
	
	//INITIALIZES TWITTER FEED PLUGIN
	$(document).ready(function() { 
		$(".tweet").tweet({
			username: "seaofclouds",//Change with your own twitter id
			//join_text: "auto",
			//avatar_size: 32,
			count: 4,
			//auto_join_text_default: "we said,",
			//auto_join_text_ed: "we",
			//auto_join_text_ing: "we were",
			//auto_join_text_reply: "we replied to",
			//auto_join_text_url: "we were checking out",
			loading_text: "loading tweets..."
		});		
	});
	
	//Initialize Main Menu
	$(document).ready(function() { 
		$('.sf-menu').superfish({ 
			delay:       500,                            // one second delay on mouseout 
			animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
			speed:       'fast',                          // faster animation speed
			autoArrows:  false,                           // disable generation of arrow mark-up 
			dropShadows: false                            // disable drop shadows 
		});
	});
	//INITIALIZW TOOLTIP JQUERY
	$(document).ready(function() {
		$('a.tipsy').tipsy({	
			delayIn: 100,      // delay before showing tooltip (ms)
			delayOut: 500,     // delay before hiding tooltip (ms)
			fade: true,     // fade tooltips in/out?
			fallback: '',    // fallback text to use when no tooltip text
			gravity: 's',    // gravity
			html: true,     // is tooltip content HTML?
			live: false,     // use live event support?
			offset: 15,       // pixel offset of tooltip from element
			opacity: 0.8,    // opacity of tooltip
			title: 'title',  // attribute/callback containing tooltip text
			trigger: 'hover' // how tooltip is triggered - hover | focus | manual	
		});
	});
	
	$(document).ready(function() {

		//When page loads...
		$(".tab-content-1").hide(); //Hide all content
		$("ul.tab-menu li:first").addClass("active").show(); //Activate first tab
		$(".tab-content-1:first").show(); //Show first tab content
	
		//On Click Event
		$("ul.tab-menu li").click(function() {
	
			$("ul.tab-menu li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab-content-1").hide(); //Hide all tab content
	
			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
		});
	
	});
	
	$(document).ready(function() {
	var $zcarousel = $('#our-blog, #our-work');
	
		if( $zcarousel.length ) {
	
			var scrollCount;
			var itemWidth;
	
			if( $(window).width() < 479 ) {
					scrollCount = 1;
					itemWidth = 300;
				} else if( $(window).width() < 768 ) {
					scrollCount = 1;
					itemWidth = 320;
				} else if( $(window).width() < 960 ) {
					scrollCount = 4;
					itemWidth = 220;
				} else {
					scrollCount = 4;
					itemWidth = 320;
			}
	
			$zcarousel.jcarousel({
				   easing: 'easeInOutQuint',
				   animation : 800,
				   scroll    : scrollCount,
				   setupCallback: function(carousel) {
				   carousel.reload();
					},
					reloadCallback: function(carousel) {
						var num = Math.floor(carousel.clipping() / itemWidth);
						carousel.options.scroll = num;
						carousel.options.visible = num;
					}
				});
			}
	});  
	
	jQuery(function($){
				
				$('.circle').mosaic({
					opacity		:	0.8			//Opacity for overlay (0-1)
				});
				
				$('.faded').mosaic();
				
				$('.bar').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				$('.bar2').mosaic({
					animation	:	'slide'		//fade or slide
				});
				
				$('.bar3').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top'		//Vertical anchor position
				});
				
				$('.cover').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px'		//Horizontal position on hover
				});
				
				$('.cover2').mosaic({
					animation	:	'slide',	//fade or slide
					anchor_y	:	'top',		//Vertical anchor position
					hover_y		:	'80px'		//Vertical position on hover
				});
				
				$('.cover3').mosaic({
					animation	:	'slide',	//fade or slide
					hover_x		:	'400px',	//Horizontal position on hover
					hover_y		:	'300px'		//Vertical position on hover
				});
		    
		    });
			
			
		//=================================== TABS AND TOGGLE ===================================//
	//jQuery tab
	jQuery(".tab-content").hide(); //Hide all content
	jQuery("ul.tabs li:first").addClass("active").show(); //Activate first tab
	jQuery(".tab-content:first").show(); //Show first tab content
	//On Click Event
	jQuery("ul.tabs li").click(function () {
	    jQuery("ul.tabs li").removeClass("active"); //Remove any "active" class
	    jQuery(this).addClass("active"); //Add "active" class to selected tab
	    jQuery(".tab-content").hide(); //Hide all tab content
	    var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
	    jQuery(activeTab).fadeIn(200); //Fade in the active content
	    return false;
	});

	//jQuery toggle
	jQuery(".toggle_container").hide();
	jQuery("h2.trigger").click(function () {
	    jQuery(this).toggleClass("active").next().slideToggle("slow");
	});


	// Accordion
	jQuery("ul.ts-accordion li").each(function () {
	    if (jQuery(this).index() > 0) {
	        jQuery(this).children(".accordion-content").css('display', 'none');
	    } else {
	        jQuery(this).find(".accordion-title").addClass('active');
	    }


	    jQuery(this).children(".accordion-title").bind("click", function () {
	        jQuery(this).addClass(function () {
	            if (jQuery(this).hasClass("active")) return "";
	            return "active";
	        });
	        jQuery(this).siblings(".accordion-content").slideDown();
	        jQuery(this).parent().siblings("li").children(".accordion-content").slideUp();
	        jQuery(this).parent().siblings("li").find(".active").removeClass("active");
	    });
	});
	
	// Search Field Text
	$(function(){
		$('input[placeholder]').each(function(){
			var $placeInput = $(this);			
			if( 'placeholder' in document.createElement('input') ) {
				var placeholder = true;
			}
			else {
				var placeholder = false;
				$placeInput.val( $placeInput.attr('placeholder') );
			}			
			if( !placeholder ) {
				$placeInput.focusin(function(){
					if( $placeInput.val() === $placeInput.attr('placeholder') ) {				
						$placeInput.val('');				
					}
				})
				.focusout(function(){
					if( $placeInput.val() === '' ) {
						$placeInput.val( $placeInput.attr('placeholder') );
					}
				});
			}		
		});
	});