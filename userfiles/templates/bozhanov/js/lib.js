(function(jQuery){
	jQuery.each(['backgroundColor', 'borderBottomColor', 'borderLeftColor', 'borderRightColor', 'borderTopColor', 'color', 'outlineColor'], function(i,attr){
		jQuery.fx.step[attr] = function(fx){
			if ( fx.state == 0 ) {
				fx.start = getColor( fx.elem, attr );
				fx.end = getRGB( fx.end );
			}

			fx.elem.style[attr] = "rgb(" + [
				Math.max(Math.min( parseInt((fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1]), 255), 0),
				Math.max(Math.min( parseInt((fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2]), 255), 0)
			].join(",") + ")";
		}
	});

	function getRGB(color) {
		var result;

		if ( color && color.constructor == Array && color.length == 3 )
			return color;
		if (result = /rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(color))
			return [parseInt(result[1]), parseInt(result[2]), parseInt(result[3])];
		if (result = /rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(color))
			return [parseFloat(result[1])*2.55, parseFloat(result[2])*2.55, parseFloat(result[3])*2.55];
		if (result = /#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(color))
			return [parseInt(result[1],16), parseInt(result[2],16), parseInt(result[3],16)];
		if (result = /#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(color))
			return [parseInt(result[1]+result[1],16), parseInt(result[2]+result[2],16), parseInt(result[3]+result[3],16)];
		return colors[jQuery.trim(color).toLowerCase()];
	}
	function getColor(elem, attr) {
		var color;

		do {
			color = jQuery.curCSS(elem, attr);
			if ( color != '' && color != 'transparent' || jQuery.nodeName(elem, "body") )
				break;
			attr = "backgroundColor";
		} while ( elem = elem.parentNode );

		return getRGB(color);
	};

	var colors = {
		aqua:[0,255,255]
	};

})(jQuery);

function getid(id){
  return document.getElementById(id);
}

function zoomboxClose(){
    $("#overlay").animate({"opacity":0}, function(){$(this).hide()});
    $(".active_link").css('visibility', 'visible');
    $(".oldImage").remove();
    $(".bigImg").remove();
}
(function($) {
    $.fn.zoombox = function() {
        $(this).each(function(){
            $(this).click(function(){
                //preload
                var href = this.href;
                var img = new Image();
                img.src = href;
                var offset_left=$(this).find('img').offset().left;
                var offset_top=$(this).find('img').offset().top;
                var active_link = $(this);
                var old_img = new Image();
                old_img.style.left = offset_left+'px';
                old_img.style.top = offset_top+'px';
                //old_img.style.height = '82px';
                old_img.className = 'oldImage';
                old_img.onload=function(){
                    document.body.appendChild(old_img);
                    $(active_link).css('visibility', 'hidden');
                    $(active_link).addClass('active_link');
                    window.width = $(window).width();
                    window.height = $(window).height();
                    $(old_img).animate({
                      "left":window.width/2-$(old_img).width()/2,
                      "top":$(window).scrollTop() + (window.height)/2-$(old_img).height()/2
                    });
                    $("#overlay").show().animate({"opacity":0.8}, function(){
                        $("#loading").css({
                            "top":$(window).scrollTop() + ($(window).height())/2-6,
                            "visibility":"visible"
                        });
                        var bigImg = new Image();
                        bigImg.className = 'bigImg';
                        bigImg.onmousedown = function(){
                          zoomboxClose();
                        }
                        bigImg.onload = function(){
                          $("#loading").css({
                            "visibility":"hidden"
                          });
                          window.width = $(window).width();
                          window.height = $(window).height();
                          document.body.appendChild(bigImg)
                          var width = $(this).width();
                          var height = $(this).height();
                          var style = $(old_img).attr('style');
                         $(bigImg).attr('style', style);
                          bigImg.width = '108';
                          bigImg.style.height = 'auto';
                          $(old_img).remove();
                          $(bigImg).animate({
                              "left":window.width/2-width/2,
                              "top":$(window).scrollTop() + (window.height)/2-height/2,
                              "height":height,
                              "width":width
                          });
                        }
                        bigImg.src = href;

                    });
                }
                old_img.src = this.getElementsByTagName('img')[0].src;
                return false;
            });
        });
    };
})(jQuery);


//check empty
function require(the_form){
    the_form.find(".required").each(function(){
          if($(this).attr("type")!="checkbox"){
              if(this.value=="" || this.value==this.title){
                $(this).parent().addClass("error");
              }
              else{
                $(this).parent().removeClass("error");
              }
          }
          else{
            if($(this).attr("checked")==""){
              $(this).parent().addClass("error");
            }
          }
    });
}

//check email
function checkMail(the_form){
      the_form.find(".required-email").each(function(){
          var thismail = $(this);
          var thismailval = $(this).val();
          var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;

          if (regexmail.test(thismailval)){
              thismail.parent().removeClass("error");
          }
          else{
             thismail.parent().addClass("error");
          }
    })
}

function checkNumber(the_form){
      the_form.find(".required-number").each(function(){
          var thisnumber = $(this);
          var thismailval = $(this).val();
          var regexmail = /^[0-9]+$/;

          if (regexmail.test(thismailval)){
              thisnumber.parent().removeClass("error");
          }
          else{
             thisnumber.parent().addClass("error");
          }
    })
}


(function($) {
	$.fn.validate = function(callback) {
        $(this).each(function(){
            $(this).submit(function(){
                  oform = $(this);
                  var valid=true;
                  require(oform);
                  checkMail(oform);
                  checkNumber(oform);

                  //Final check
                  if(oform.find(".error").length>0){
                      oform.addClass("error");
                      valid=false;
                  }
                  else{
                      oform.removeClass("error");
                      valid=true;
                  }
                  oform.addClass("submitet");

                  if(valid==true && callback!=undefined && typeof callback == 'function'){
                      callback.call(this);
                      return false;
                  }
                  else{
                     return valid;
                  }

            });
            $(this).find(".required").bind("keyup blur change mouseup", function(){
                if($(this).parents("form").hasClass("submitet")){
                  if($(this).val()=="" || $(this).val()==$(this).attr("title")){
                    $(this).parent().addClass("error");
                  }
                  else{
                    $(this).parent().removeClass("error");
                  }
                }
            });

            $(this).find(".required-email").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thismail = $(this);
                  var thismailval = $(this).val();
                  var regexmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/;
                  if (regexmail.test(thismailval)){
                      thismail.parent().removeClass("error");
                  }
                  else{
                     thismail.parent().addClass("error");
                  }
                }
            });

            $(this).find(".required-number").bind("keyup blur", function(){
                if($(this).parents("form").hasClass("submitet")){
                  var thisnumber = $(this);
                  var thisnumberval = $(this).val();
                  var regexmail = /^[0-9]+$/;
                  if (regexmail.test(thisnumberval)){
                      thisnumber.parent().removeClass("error");
                  }
                  else{
                     thisnumber.parent().addClass("error");
                  }
                }
            });
        });
    };
})(jQuery);

(function($) {
	$.fn.isValid = function(){
        oform = $(this);
        var valid=true;
        require(oform);
        checkMail(oform);
        checkNumber(oform);
        if(oform.find(".error").length>0){
            oform.addClass("error");
            return false;
        }
        else{
            oform.removeClass("error");
            return true;
        }
    };
})(jQuery);







































