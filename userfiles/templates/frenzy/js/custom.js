$(document).ready(function(){


	$('.collapse').live('show', function(){
	    $(this).parent().find('a .icon').attr('class', 'icon ent-minus'); //add active state to button on open
	});

	$('.collapse').live('hide', function(){
	    $(this).parent().find('a .icon').attr('class', 'icon ent-plus'); //remove active state to button on close
	});


	// carousel demo
 
	//========================== SlideJS Slider Initiation ============================// 

	var triangle = "<div class='triangle'></div>";
	var block = "<div class='block'></div>";

 
	//========================== Adding Pagination to Carousel ============================//

	$('.carousel[id]').each(
		function() {
			var html = '<div class="carousel-nav" data-target="' + $(this).attr('id') + '"><ul>';
			
			for(var i = 0; i < $(this).find('.item').size(); i ++) {
				html += '<li><a';
				if(i == 0) {
					html += ' class="active"';
				}
				
				html += ' href="#">•</a></li>';
			}
			
			html += '</ul></li>';
			$(this).before(html);
			//$('.carousel-control.left[href="#' + $(this).attr('id') + '"]').hide();
		}
	).bind('slid',
		function(e) {
			var nav = $('.carousel-nav[data-target="' + $(this).attr('id') + '"] ul');
			var index = $(this).find('.item.active').index();
			var item = nav.find('li').get(index);
			
			nav.find('li a.active').removeClass('active');
			$(item).find('a').addClass('active');
			
			/*if(index == 0) {
				$('.carousel-control.left[href="#' + $(this).attr('id') + '"]').fadeOut();
			} else {
				$('.carousel-control.left[href="#' + $(this).attr('id') + '"]').fadeIn();
			}
			
			if(index == nav.find('li').size() - 1) {
				$('.carousel-control.right[href="#' + $(this).attr('id') + '"]').fadeOut();
			} else {
				$('.carousel-control.right[href="#' + $(this).attr('id') + '"]').fadeIn();
			}
			*/
		}
	);
	
	$('.carousel-nav a').bind('click',
		function(e) {
			var index = $(this).parent().index();
			var carousel = $('#' + $(this).closest('.carousel-nav').attr('data-target'));
			
			carousel.carousel(index);
			e.preventDefault();
		}
	);

	//========================== Activating Bootstrap Tabs ============================// 

    $('#tab1 a, #tab-content a').click(function (e) {
	    e.preventDefault();
	    $(this).tab('show');
    })


    //========================== SideBar Menu Children Slide In ============================// 

    $('.menu-widget ul li a, #responsive-nav ul li a').click( function (e){
    	$(this).find('span.icon').toggleClass(function() {
		    if ($(this).is('.awe-chevron-up')) {
                $(this).removeClass('awe-chevron-up');
		        return 'awe-chevron-down';
		    } else {
                $(this).removeClass('awe-chevron-down');
		        return 'awe-chevron-up';
		    }
		});
    	$(this).siblings('ul').slideToggle();
    });

    $('#responsive-nav .collapse-menu .collapse-trigger').click( function (e) {
        $(this).toggleClass(function() {
            if ($(this).is('.awe-chevron-down')) {
              $(this).removeClass('awe-chevron-down');
              return 'awe-chevron-up';
            } else {
              $(this).removeClass('awe-chevron-up');
              return 'awe-chevron-down';
            }
        });
        $(this).parent().siblings('ul').slideToggle();
    });


    //========================== Article Info Popover ============================// 

    $('.article-info .author-info span').popover({
    	animation: true,
    	html: true,
    	placement: 'left',
    	trigger: 'click',
    	content: function (i){
    		var src = $(this).data('image');
    		var author = $(this).data('author-desc');
    		var content = "<div class='popover-image image-polaroid'><img src='" + src + "' /></div><div class='popover-desc'>" + author + "</div>";
    		return content;
    	}
    });

    $('.article-info .time span').popover({
    	animation: true,
    	html: true,
    	placement: 'bottom',
    	trigger: 'click',
    	content: function(i) {
    		var date = $(this).data('date'),
    			day = date.day,
    			month = date.month,
    			year = date.year,
    			time = $(this).data('time');

    		var content = "<div class='popover-date'><span class='ent-calendar'></span>"+
    						month+" "+day+", "+year+"</div><div class='popover-time'>"+
    						"<span class='ent-clock'></span>"+time+"</div>";

    		return content;
    	}
    });

    $('.article-info .comment span').popover({
    	animation: true,
    	html: true,
    	placement: 'left',
    	trigger: 'click',
    	content: function(i) {
    		var data = $(this).data('comment-latest'),
    			author = data.author,
    			authorurl = data.authorurl,
    			comment = data.comment,
    			avatar = data.avatar;

    		var content = "<div class='info-title'><strong>Latest comment by:</strong></div>"+
    					  "<div class='popover-image image-polaroid'>" +
    					  "<img src='" + avatar + "' /></div>" +
    					  "<div class='popover-author'><a href='"+ authorurl + "' alt='"+author+"'>"+author+"</a></div>" +
    					  "<div class='popover-desc'>" + comment + "</div>";

    		return content;
    	}
    });

	//========================== Commnet Form ============================//

    $('input[type="text"], input[type="password"], input[type="email"], textarea').focus(function(){
    	$(this).parent('.input-border').addClass('focus');
    });
    $('input[type="text"], input[type="password"], input[type="email"], textarea').blur(function(){
    	$(this).parent('.input-border').removeClass('focus');
    });


    //========================== Article Info Popover ============================//
	
    changeSpan();

	$(window).resize(function (){
		changeSpan();
	});

      
   $("#all").click(function(){
   	   $('.gallery-item').removeClass('last-col');
   	   $('.gallery-item:nth-child(4n+4)').addClass('last-col');
       $('.gallery-item').slideDown();
       $(this).parent().siblings().children('.active').removeClass('active');
       $(this).addClass("active");
       return false;
   });
   
   $(".filter").click(function(){
   		$('.gallery-item').slideUp();
   		$('.gallery-item').removeClass('last-col');

        var filter = $(this).attr("id");
        var i = 0;var j = 0;
        $('.'+filter).each(function(){
        		
        		if(i==3) {
        			j++;
	       			$('.'+filter).eq(i*j).addClass('last-col');
	       			i = 0;
	       		}
	       		i++;
	       		console.log("i", i);
	       		console.log("a",j);
        });
        $("."+ filter).slideDown();
        $(this).parent().siblings().children('.active').removeClass('active');
        $(this).addClass('active'); 


        return false;

   });
   


// document.ready END //	
});

function changeSpan() {
	
	$(".team .member").each(function() {
	  	if (Modernizr.mq('(min-width: 1200px)')) {
	    	$(this).attr('class', 'member span3');
	  	}
	  	
	  	if (Modernizr.mq('(max-width: 1180px) and (min-width:944px)')) {
	    	$(this).attr('class', 'member span4');
	  	}
	  	
	  	if (Modernizr.mq('(min-width: 768px) and (max-width: 979px)')) {
	   		$(this).attr('class', 'member span6');
	  	}
	});

	$(".footer-extra").each(function (i) {
		if (Modernizr.mq('(max-width: 1180px) and (min-width:944px)') || (Modernizr.mq('(min-width:1200px)'))) {
	    	$(this).attr('class', 'footer-extra span3');

	  		if(i == 2) {
	  			$(this).attr('class', 'footer-extra span2');
	  		}
	  	}

		if (Modernizr.mq('(min-width: 768px) and (max-width: 979px)')) {
	   		$(this).attr('class', 'footer-extra span4');
	  	}
	  	
	});

	if (Modernizr.mq('(min-width: 768px) and (max-width: 979px)')) {
   		if($('#slidejs').parent().is('.span8') || $('#cameraslide').parent().is('.span8')) {
   			$('#slidejs').parent().attr('class', 'span12');
   			$('#slidejs').find('.slide-outer').attr('class', 'slide-outer span12');
   			$('#cameraslide').parent().attr('class', 'span12');
   			$('#cameraslide').find('.camera-slide-wrapper').attr('class', 'camera-slide-wrapper span12');
   		}

   		if($('.home #content').is('.span6')) {
   			$('.home #content').attr('class', 'span8');
   		}

   		if($('#right-sidebar').is('.span3')) {
   			$('#right-sidebar').attr('class', 'span4');
   		}
  	}
  	else {
  		$('.home #content').attr('class', 'span6');
  		$('.home #right-sidebar').attr('class', 'span3');
  		$('#slidejs').parent().attr('class', 'span8');
  		$('#slidejs').find('.slide-outer').attr('class', 'slide-outer span8');
  		$('#cameraslide').parent().attr('class', 'span8');
  		$('#cameraslide').find('.camera-slide-wrapper').attr('class', 'camera-slide-wrapper span8');
  	}
}