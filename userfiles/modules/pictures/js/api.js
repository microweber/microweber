if(typeof mw.rotator === 'undefined'){
    mw.rotator = function(selector){
        /*

            Simple JS API for CSS3 Transitions

            The Transition styles are for the slider-item with class 'active'

        */
        var rotator = $(selector)[0];
        var holder = rotator.getElementsByTagName('div')[0];
        if(holder === undefined) return false;
        var controlls = mwd.createElement('div');
        controlls.className = "mw-rotator-ctrls";
        rotator.appendChild(controlls);
        var items = holder.getElementsByTagName('div');
        $(items).addClass('mw-rotator-slide');
        $(rotator).hover(function(){
            $(this).addClass('rotator-hover');
        }, function(){
            $(this).removeClass('rotator-hover');
        });



        rotator.next = function(){
            var active = $(holder).children('.active');
            var next = active.next('.mw-gallery-item').length>0 ? active.next('.mw-gallery-item') : $(holder).children('.mw-gallery-item').eq(0);
            active.removeClass('active');
            next.addClass('active');
            rotator.setactive($(holder).children().index(next));
        },
        rotator.prev = function(){
            var active = $(holder).children('.active');
            var prev = active.prev('.mw-gallery-item').length>0 ? active.prev('.mw-gallery-item') : $(holder).children('.mw-gallery-item:last');
            active.removeClass('active');
            prev.addClass('active');
            rotator.setactive($(holder).children().index(prev));
        },
        rotator.setactive = function(i){ //for paging
           var paging = $(rotator).find('.rotator-index-control').eq(0);
           if(paging.length === 0) return false;
           var old = paging.find("a.active");
           old.removeClass('active');
           var active = paging.find("a").eq(i);
           active.addClass('active');
           var a_off = active.offset();
           var p_off = paging.offset();
           if(a_off.left > (paging.width() + p_off.left)){
             var scrl = paging.find('.mw-rotator-thumbnail-scroller').eq(0);
             var s_left = scrl.offset().left;
             var formula = p_off.left - a_off.left;
             var max = scrl.width()-paging.width();
             var formula = -formula<max?-formula:max;
             scrl.animate({left: -formula});
           }
           if( a_off.left<p_off.left){
             var scrl = paging.find('.mw-rotator-thumbnail-scroller').eq(0);
             var s_left = scrl.offset().left;
             var formula = s_left - a_off.left + paging.width() - active.outerWidth(true);
             var formula = formula > 0 ? 0 : formula;
             scrl.animate({left: formula});
           }
        }
        rotator.goto = function(index){
          var _index = $(holder).children().eq(index);
          if(!_index.hasClass('active')){
           $(holder).children('.active').removeClass('active');
           _index.addClass('active');
           rotator.setactive(index);
          }
        }
        rotator.paging = function(selector, mode, reflection){
          var mode = mode || 'numbers';
          var l = items.length, i = 0, paging_holder = $(selector);
          paging_holder.empty();
          if(mode == 'numbers'){
              for( ; i<l; i++){
                var a = mwd.createElement('a');
                var cls =  'notransition rotator-paging-item rotator-paging-item-digit' + (i===0? ' active':'');
                a.className = cls;
                a.onclick = function(){rotator.goto(parseFloat(this.innerHTML)-1)}
                a.innerHTML = i + 1;
                paging_holder[0].appendChild(a);
              }
          }
          else if(mode == 'thumbnails'){
            var well = mwd.createElement('span');
            well.className = 'mw-rotator-thumbnail-scroller';
            if(!reflection){
                for( ; i<l; i++){
                  var a = mwd.createElement('a');
                  var cls = 'notransition thumbnail rotator-paging-item rotator-paging-item-thumbnail' + (i===0? ' active':'');
                  a.className = cls;
                  a.onclick = function(){rotator.goto(parseFloat(this.rel)-1)}
                  a.rel = i + 1;
                  a.innerHTML = "<img src='" + items[i].getElementsByTagName('img')[0].src + "' />";
                  well.appendChild(a);
                }
            }
            else{
               for( ; i<l; i++){
                  var a = mwd.createElement('a');
                  var cls = 'notransition rotator-paging-item rotator-paging-item-thumbnail' + (i===0? ' active':'');
                  a.className = cls;
                  a.onclick = function(){rotator.goto(parseFloat(this.rel)-1)}
                  a.rel = i + 1;
                  var src =  items[i].getElementsByTagName('img')[0].src;
                  a.innerHTML = "<span class='rotator-reflection-original'><img src='" + src + "' /></span><span class='rotator-reflection'><span class='mw-rotator-reflection'><img src='" + src + "' /></span><span class='mw-rotator-reflection-overlay'></span></span>";
                  well.appendChild(a);
                }
            }
            paging_holder[0].appendChild(well);
            var w = $(well.getElementsByTagName('a')[0]).outerWidth(true);
            $(well).width(w*l);
            $(rotator).parents('.module').eq(0).bind("resize", function(e, axis){
              if(axis=='horizontal'){
                 var w = $(well.getElementsByTagName('a')[0]).outerWidth(true);
                $(well).width(w*l);
              }
            });
          }
        }
        rotator.options = function(obj){
            var obj = obj || {};
            var paging = obj.paging;
            var mode = obj.pagingMode || 'numbers';
            var next = obj.next;
            var prev = obj.prev;
            var reflection = obj.reflection || false;
            if(paging){
              var paging_holder = mwd.createElement('span');
              paging_holder.className = 'rotator-index-control rotator-index-control-mode-' + mode;
              controlls.appendChild(paging_holder);
              rotator.paging(paging_holder, mode, reflection)
            }
            if(next){
                var next = mwd.createElement('span');
                next.className = 'unselectable rotator-next';
                //controlls.appendChild(next);
                $(rotator).find('.mw-gallery-holder')[0].appendChild(next);
                next.onclick = function(){
                    rotator.next()
                }
            }
            if(prev){
               var prev = mwd.createElement('span');
               prev.className = 'unselectable rotator-prev';
               //controlls.appendChild(prev);
               $(rotator).find('.mw-gallery-holder')[0].appendChild(prev);
               prev.onclick = function(){
                    rotator.prev()
                }
            }
            return rotator;
        }
        rotator.autoRotate = function(int){
          setTimeout(function(){
            !$(rotator).hasClass('rotator-hover') ? rotator.next() : '';
             rotator.autoRotate(int);
          }, int);
          return rotator;
        }
        if($(holder).children('.active').size()===0){
           rotator.goto(0);
        }

        $(rotator).find('img.valign').each(function(){
          $(this).css("marginTop", -$(this).height()/2);
          $(this).css("marginLeft", -$(this).width()/2);
        });
        return rotator;
    }
    mw.productZoom = function(parent){
            var img =  mw.$('.mw-slider-zoomimg', parent)[0];
            var el = $(parent);
            el.mouseenter(function(){
              $(img).css({
                 visibility:'visible'
              });
            });
            el.mousemove(function(event){
                var img_width = $(img).width();
                var img_height = $(img).height();
                offset = Math.floor( ( img_width - el.width() ) * ( ( event.pageX - el.offset().left ) / el.width() ) )
      	   	    offset2 = Math.floor( ( img_height - el.height() ) * ( ( event.pageY - el.offset().top ) / el.height() ) )
        		if( offset <= img_width - el.width()  ) {
                   img.style.left = -offset+'px';
                }
                else{
                   img.style.left = '';
                }
                if(offset2 <= img_height - el.height()){
                     img.style.top = -offset2+'px';
                }
                else{
                   img.style.top = '';
                }
            });
            el.mouseleave(function(){
              img.style.visibility = 'hidden';
            });
       return img;
    }
    mw.popupZoom = function(selector){
        $(selector).each(function(){
            $(this).click(function(){
                mw._popupZoom.api(this, selector);
                return false;
            });
        });
        $(mwd.body).bind("click", function(e){
            if(!mw.tools.hasParentsWithClass(e.target, 'mw-images-template-default-grid')){
                if($(selector+".active").length > 0){
                    $(selector+".active").removeAttr("style");
                    $(mw.tools.firstParentWithClass($(selector+".active")[0], 'mw-pictures-list')).removeClass("zoomed");
                    $(selector+".active").removeClass("active");
                }
            }
        });
    }

    mw._popupZoom = {
      api: function(el, selector){
          var jqel = $(el);
              if(!jqel.hasClass("active")){
                 if($(selector+".active").length == 0){
                  var info = mw.tools.htmlnfo($(el.parentNode).html());
                  var w = $(info).find(".thumbnail").outerWidth();
                  var h = $(info).find(".thumbnail").outerHeight();
                  var w = w > 700 ? 700 : w;
                  var h = h > 400 ? 400 : h;
                  var l =  - w/2 + $(el.parentNode).width()/2;
                  var t = - h/2+ $(el.parentNode).height()/2;
                  jqel.addClass('active');
                  $(mw.tools.firstParentWithClass(el, 'mw-pictures-list')).addClass("zoomed");
                  jqel.css({
                    width:w,
                    height:h,
                    left:l,
                    top:t,
                    position:'absolute'
                  });
                }
                else{
                    $(selector+".active").removeAttr("style");
                    $(mw.tools.firstParentWithClass($(selector+".active")[0], 'mw-pictures-list')).removeClass("zoomed");
                    $(selector+".active").removeClass("active");
                    mw._popupZoom.api(el, selector);
                }
              }
              else{
                jqel.removeClass("active");
                jqel.removeAttr("style");
                $(mw.tools.firstParentWithClass(el, 'mw-pictures-list')).removeClass("zoomed");
          }
      }
    }
}