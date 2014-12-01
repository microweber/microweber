
TempateFunctions = {
  contentHeight:function(){

    /**************************************
     Minimum height for the Main Container
    **************************************/
  if(self !== top){ return false; }

    var content = document.getElementById('content-holder'),
        footer = document.getElementById('footer'),
        header = document.getElementById('header');

    $(content).css('minHeight', $(window).height() - $(header).outerHeight(true) - $(footer).outerHeight(true));

  }
}


$(document).ready(function(){

    TempateFunctions.contentHeight();
	if(typeof(mw.msg.product_added) == "undefined"){
	mw.msg.product_added = 	"Your product is added to the shopping cart";
	}
	
	$(window).bind('productAdded', function(){
   var modal_html = ''
        + '<div id="mw-product-added-popup-holder"> '
		+ '<h4>'+mw.msg.product_added+'</h4>'
		+ '<div id="mw-product-added-popup" class="text-center" style="width:210px;"> '
		+ ' </div>';
        + ' </div>';
		Alert(modal_html)
	mw.load_module('shop/cart','#mw-product-added-popup', false,{template:'small'});


	});


    $("#go-to-content-anchor").bind("click", function(){
       mw.tools.scrollTo('#content-holder', 120);
       return false;
    });

    $(".mobilemenuctrl").bind("click", function(){
        $("#header-main-menu").toggleClass("mobile-view");
        $(this).toggleClass("mobilemenuctrl-active");
        $('html').toggleClass("mobile-menu-active");
    });

});

$(window).bind('load resize', function(e){

    TempateFunctions.contentHeight();



});

$(window).bind('scroll load', function(e){

    if($(window).scrollTop() > ($("#header").height()) - $("#main-menu").height()){
        $("#main-menu").addClass("fixed");
    }
    else{
      $("#main-menu").removeClass("fixed");
    }
    $("#header").css("backgroundPosition", 'center ' + ($(window).scrollTop()/2.5) + 'px');

});






