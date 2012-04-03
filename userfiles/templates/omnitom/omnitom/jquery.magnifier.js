/**
 * @author DIO5 aka Dieter Orens
 * @version 0.1
 *
 * A simple plugin to show a magnifier effect on images, falls back as link to a larger image.
 *
 */
(function($){
    $.fn.magnify = function(options){
        var settings = {
            lensWidth: 100,
            lensHeight: 100,
            link: true
        }
        
        var opts = $.extend(settings, options);
		
        return this.each(function(){
            var $a = $(this).click(function(){
                return false;
            });
            var $img = $("img", this);
            var $largeImage = $(new Image());
            var $lens = $("<div id='dio-lens' style='display:none; overflow:hidden; position:absolute;'></div>");
            var $sensor = $("<div id='dio-sensor' style='position:absolute;'></div>");
            var $loader = $("<div id='dio-loader'>loading</div>").css({
                width: settings.lensWidth,
                height: settings.lensHeight
            });
            
            $largeImage.attr('src', $a[0].href);
            
            if (settings.link) {
                $sensor.click(function(){
                    window.location = $a[0].href;
                })
            }
            
            $lens.append($loader);
            
            $largeImage.load(function(){
                loadCallback();
            });
			
			if($largeImage[0].complete)
			{
				loadCallback();
			}
			
			function loadCallback()
			{
				$lens.append($largeImage);
                $loader.remove();
			}
            
            $('body').append($lens).append($sensor);
            
            $lens.css({
                width: settings.lensWidth,
                height: settings.lensHeight
            });

            $(window).resize(function(){
              $lens.css({
                  width: settings.lensWidth,
                  height: settings.lensHeight
              });
            })

            $sensor.css({
                width: $img.width() + "px",
                height: $img.height() + "px",
                top: $img.offset().top + "px",
                left: $img.offset().left + "px",
                backgroundColor: '#fff',
                opacity: '0'
            }).mousemove(function(e){
                $lens.css({
                    left: parseInt(e.pageX - (settings.lensWidth * .5)) + "px",
                    top: parseInt(e.pageY - (settings.lensHeight * .5)) + "px"
                }).show();
                var scale = {};
                scale.x = $largeImage.width() / $img.width();
                scale.y = $largeImage.height() / $img.height();

                var left = -scale.x * Math.abs((e.pageX - $img.offset().left)) + settings.lensWidth / 2 + "px";
                var top = -scale.y * Math.abs((e.pageY - $img.offset().top)) + settings.lensHeight / 2 + "px";
                
                $largeImage.css({
                    position: 'absolute',
                    left: left,
                    top: top
                });
                
            }).mouseout(function(){
                $lens.hide();
            });

            $(window).resize(function(){
              $lens.css({
                  width: settings.lensWidth,
                  height: settings.lensHeight
              });
              $sensor.css({
                width: $img.width() + "px",
                height: $img.height() + "px",
                top: $img.offset().top + "px",
                left: $img.offset().left + "px",
                backgroundColor: '#fff',
                opacity: '0'
            })
            });


        });
    }
})(jQuery);
