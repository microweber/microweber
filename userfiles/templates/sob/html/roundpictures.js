(function($) {
	$.fn.roundImage = function(radius,stroke,strokeColor){
        $(this).each(function(){
            var src = $(this).attr('src');
            if($.browser.msie){
                 var stroke;
                 var strokeColor;
                 if(stroke=='undefined'){
                   stroke=0;
                 }
                 if(strokeColor=='undefined'){
                   strokeColor='#ffffff';
                 }
                 document.write('<v:roundrect strokeweight="'+stroke+'px" strokeColor="' + strokeColor + '" arcsize="50%" style="display:block;width:' +radius*2+ 'px;height:' +radius*2+ 'px"><v:fill type="tile" src="' + src + '" /></v:roundrect>')
            }
            else{
               $(this).each(function(){
                   var canvas = document.createElement('canvas');
                   $(".canvas").append(canvas);
                   $(".canvas canvas").each(function(i){
                          var ctx = $(this)[0].getContext("2d");
                          img=new Image();
                          img.src=src;
                             var ptrn = ctx.createPattern(img,'no-repeat');
                             ctx.strokeStyle=strokeColor;
                             ctx.fillStyle = ptrn;
                             ctx.beginPath();
                             ctx.arc(radius, radius, radius, 0, Math.PI*2, true);
                             ctx.fill();
                             ctx.stroke();


                   });
               });

            }
        });
	};
})(jQuery);

