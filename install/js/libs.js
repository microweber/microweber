/*
    $(element).toggleSlide()
    $(element).toggleFade()
    $(element).enable()
    $(element).disable()
    $(element).check()
    $(element).uncheck()
    $(element).visible()
    $(element).hidden()
    $(element).xDisable(opacity)        //puts an overlay over the element
    $(element).xEnable()
    $(element).multiWrap()              //Example: $(element).multiWrap(2, '<div></div>') --> wraps each 2 elements into a div
    $(element).destroy()

    $.imgpreload(
      'img1.jpg',
      'img2.jpg'
    )

*/
$.fn.toggleSlide = function (s, callback) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).slideDown(s);
        else $(this).slideUp(s, function(){
          if(typeof callback == 'function' && callback != undefined){
            callback.call(this);
          }
        });
    });
}
$.fn.toggleFade = function (s, callback) {
    return $(this).each(function () {
        var d = $(this).css("display");
        if (d == "none" || d == '') $(this).fadeIn(s);
        else $(this).fadeOut(s, function(){
          if(typeof callback == 'function' && callback != undefined){
            callback.call(this);
          }
        });
    });
}


$.fn.disable = function() {
      $(this).each(function(){
        var d = $(this);
        if(d.find('input').length>0 || d.find('select').length>0 || d.find('textarea').length>0){
          d.find('input').attr("disabled", true);
          d.find('select').attr("disabled", true);
          d.find('textarea').attr("disabled", true);
        }
        else{
          d.attr("disabled", true);
        }
      });
};



$.fn.enable = function() {
    $(this).each(function(){
      var d = $(this);
      if(d.find('input').length>0 || d.find('select').length>0 || d.find('textarea').length>0){
        d.find('input').removeAttr("disabled");
        d.find('select').removeAttr("disabled");
        d.find('textarea').removeAttr("disabled");
      }
      else{
        d.removeAttr("disabled");
      }
    });
};


jQuery.fn.extend({
  check: function(){
    return this.each(function(){this.checked = true;});
  },
  uncheck: function(){
    return this.each(function(){this.checked = false;});
  },
  hidden: function(){
    return this.each(function(){this.style.visibility = 'hidden'});
  },
  visible: function(){
    return this.each(function(){this.style.visibility = 'visible'});
  }
});

$.fn.xDisable = function(opacity) {
      $(this).each(function(){
        var d = $(this);
        var w = d.outerWidth();
        var h = d.outerHeight();
        var o = document.createElement('div');
        o.id = d.attr('id') + '-overlay';
        o.style.width = w+'px';
        o.style.height = h+'px';
        o.style.position = 'absolute';
        o.style.zIndex = '20';
        o.style.top = 0;
        o.style.left = 0;
        o.style.background = 'white';
        if(opacity!=undefined){
          o.style.opacity = opacity;
          o.style.filter = 'alpha(opacity=' + (opacity*100) + ')';
        }
        else{
          o.style.opacity = 0.1;
          o.style.filter = 'alpha(opacity=10)';
        }
        d.append(o);
      });
};

$.fn.xEnable = function() {
    $(this).each(function(){
      var d = $(this);
      var id = d.attr('id');
      $("#" + id + '-overlay').remove();
    });
};


$.fn.multiWrap = function(each, wrapString){
    var results =[];
    var elements = $(this);
    if(elements.length>0){
        $.map(elements, function(i, n){
            if(n%each === 0 ){
                results.push(n);
            }
        });
        $.each(results , function(i,v){
            elements.slice(v, v+each).wrapAll(wrapString);
        });
    }
};


//'remove' function sometimes makes the fields non editable in IE
$.fn.destroy = function(){
  $(this).each(function(){
    this.parentNode.removeChild(this);
  });
};

(function($) {
  var cache = [];
  $.imgpreload = function() {
    var args_len = arguments.length;
    for (var i = args_len; i--;) {
      var cacheImage = document.createElement('img');
      cacheImage.src = arguments[i];
      cache.push(cacheImage);
    }
  }
})(jQuery);










