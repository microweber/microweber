
AddToCart = window.AddToCart || function(selector, id, title){
   mw.cart.add(selector, id, function(){
     if(document.getElementById('AddToCartModal') === null){
       AddToCartModal = mw.modal({
          content:AddToCartModalContent(title),
          template:'mw_modal_basic',
          name:"AddToCartModal",
          width:400,
          height:200
       });
     }
     else{
       AddToCartModal.container.innerHTML = AddToCartModalContent(title);
     }
   });
}


EqualHeight = function(selector){
  var max = 0, all = mw.$(selector), l = all.length, i = 0, j = 0;
  for( ; i < l ; i++){ var max = all[i].offsetHeight > max ? all[i].offsetHeight : max; }
  for( ; j < l ; j++){ all[j].style.minHeight =  max + 'px'; }
}






$(document).ready(function(){

    $(window).bind('load', function(){
       var slider = $('#homeslider .magic-slider')[0];
       if(typeof slider !== 'undefined'){
         $(window).bind('scroll', function(){
            if($(window).scrollTop() > 0){
                slider.magicSlider.stopAutorotate()
            }
            else{
              slider.magicSlider.autoRotate();
            }
         });
       }
    });

    $(".menu-button").bind('click', function(){
        $(this).toggleClass('active');
    });

    $(document.body).bind('mousedown', function(e){
        if(!mw.tools.hasParentsWithClass(e.target, 'header-menu')){
            $('.menu-button.active').removeClass('active');
        }
    })

});