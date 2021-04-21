








$(window).load(function(){



   // Simple way to enable the 'placeholder' attribute for browsers that doesn't support it
   if('placeholder' in document.createElement('input') === false){
       mw.$("[placeholder]").each(function(){
          var el = $(this), p = el.attr("placeholder");
          el.val() == '' ? el.val(p) : '' ;
          el.bind('focus', function(e){ el.val() == p ? el.val('') : ''; });
          el.bind('blur', function(e){ el.val() == '' ? el.val(p) : '';});
       });
   }


   /* Fixed shop cart */

   if(typeof _shop === 'boolean'){
      var header = document.getElementById('header');
      $(window).bind("scroll", function(){
          var cart = mw.$(".mw-cart-small", header)[0];
          var cart_module = mw.tools.firstParentWithClass(cart, 'module');
          if($(window).scrollTop() > $(header).outerHeight()){
            $(cart_module).addClass("mw-cart-small-fixed");
          }
          else{
             $(cart_module).removeClass("mw-cart-small-fixed");
          }
      });
  }
 });