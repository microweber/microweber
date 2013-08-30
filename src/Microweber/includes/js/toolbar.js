
mwd.body.className = mwd.body.className + " mw-live-edit";

 
mw.designTool = {
  position:function(rel){
    var rel = rel || mw.$("#design_bnav");
	
	if(rel.length == 0){
		return false;
		
	}
	
	
    var ww = $(window).width();
    var wh = $(window).height();

    var off = rel.offset();
    var w = rel.width();
    var h = rel.height();

    var sumWidth = off.left + w;
    var sumHeight = off.top + h;

    sumWidth > ww ? rel.css('left', ww-w-20) : '';
    sumHeight > wh ? rel.css('top', wh-h-20) : '';
  }
}




$(window).load(function(){


mw.$(".mw_dropdown_type_navigation a").each(function(){
  var el = $(this);
  var li = el.parent();
  el.attr("href", "javascript:;");
  var val = li.dataset("category-id");
  li.attr("value", val);
});

mw.$("#module_category_selector").change(function(){
    var val = $(this).getDropdownValue();

    if(val=='all'){
        mw.$(".list-modules li").show();
        return false;
    }
    (val!=-1&&val!="-1") ? mw.tools.toolbar_sorter(Modules_List_modules, val):'';
});
mw.$("#elements_category_selector").change(function(){
    var val = $(this).getDropdownValue();

    if(val=='all'){
        mw.$(".list-elements li").show();
        return false;
    }
    (val!=-1&&val!="-1") ? mw.tools.toolbar_sorter(Modules_List_elements, val):'';
});

mw.$("#dd_module_search, #dd_elements_search").bind("keyup paste", function(event){
       var val = this.value;


       var a = this.parentNode.querySelector(".dd_custom a")
       var li = a.parentNode;
       if(val!=""){
             $(li).show();
             //$(li).attr("value", val);
             a.innerHTML = val;
       }
       else{
          $(li).hide();
       }

       $(mw.tools.firstParentWithClass(this, 'mw_dropdown')).setDropdownValue(-1, false);


       var obj = this.id == 'dd_module_search'?Modules_List_modules:Modules_List_elements;
       mw.tools.toolbar_searh(obj, val);


       event.preventDefault();
       event.stopPropagation();
       return false;

});


mw.$("#module_category_selector .dd_custom a").mousedown(function(e){
   mw.tools.toolbar_searh(Modules_List_modules, $(this).html());
   mw.tools.firstParentWithClass(this, 'mw_dropdown').querySelector('.mw_dropdown_val').innerHTML = $(this).html();
   e.preventDefault();
});
mw.$("#elements_category_selector .dd_custom a").mousedown(function(e){
   mw.tools.toolbar_searh(Modules_List_elements, $(this).html());
   mw.tools.firstParentWithClass(this, 'mw_dropdown').querySelector('.mw_dropdown_val').innerHTML = $(this).html();
   e.preventDefault();
});




mw.$("#design_bnav, .mw_ex_tools").addClass(mw.cookie.ui("designtool"));


var design_pos = mw.cookie.ui("designtool_position");

if(design_pos!=""){
    var design_pos = design_pos.split("|");
    mw.$("#design_bnav").css({
      top:design_pos[0]+"px",
      left:design_pos[1]+"px"
    });
}

mw.designTool.position();


mw.$(".mw_ex_tools").click(function(){

  var rel = mw.$($(this).attr("href"));

  rel.toggleClass('active');

  $(this).toggleClass('active');

  mw.cookie.ui("designtool", rel.hasClass("active") ? "active" : "");

  mw.designTool.position(rel);

  return false;
});


mw.$(".ts_main_li").mouseenter(function(){

  var selected_el = mwd.querySelector('.element-current');
  var parentedit = mw.tools.firstParentWithClass(selected_el, 'edit');
  $(parentedit).addClass('changed');

  if(!mw.$("#design_bnav").hasClass('ui-draggable-dragging')){
  $(this).addClass("hovered");
  mw.$(".ts_main_ul .ts_action").invisible();
  mw.$(".ts_main_ul .ts_action").css({left:"100%", top:0});
  var toshow = $(this).find(".ts_action:first");
  toshow.visible();
  toshow.css("top", 0);
  var offset = toshow.offset();
  var width = toshow.outerWidth();
  var height = toshow.outerHeight();
  var window_w = $(window).width();
  var window_h = $(window).height();
  var scroll = $(window).scrollTop();

    toshow.css({
       left:((offset.left+width) < window_w) ? "100%" : -width ,
       top:(offset.top+height-scroll)<window_h ? 0 : -(offset.top+height-scroll-window_h)
    });

 }
});
mw.$(".ts_main_li").mouseleave(function(){
    $(this).removeClass("hovered");
})


    mw.$(".ts_main_li .ts_action_item").mouseenter(function(){
      $(this).parent().find(".ts_action").invisible();
      $(this).parent().find(".ts_action").css("left", "100%");
      var toshow = $(this).find(".ts_action:first");
      var offset = toshow.offset();
      if(typeof offset === 'object' && offset !== null){

      var width = toshow.outerWidth();
      var window_w = $(window).width();
      if((offset.left+width) < window_w){
        toshow.css({
           left:"100%",
           visibility:'visible'
        });
      }
      else{
         toshow.css({
           left:-width,
           visibility:'visible'
        });
      }
      }
    });

    mw.$(".toolbar_bnav").hover(function(){
      $(this).addClass("toolbar_bnav_hover");
    }, function(){
      $(this).removeClass("toolbar_bnav_hover");
    });


});

mw.$(".ts_action_item").mouseenter(function(){
   var toshow = $(this).find(".ts_action:first");

   toshow.css({
       left:"100%",
       visibility:'visible'
    });
});



mw.image = {
    isResizing:false,
    currentResizing:null,
    resize:{
      create_resizer:function(){
        if(mw.image_resizer==undefined){
          var resizer = document.createElement('div');
          resizer.className = 'mw_image_resizer';
          resizer.innerHTML = '<span onclick="mw.wysiwyg.media(\'#editimage\');" class="image_change">Change</span>';
          document.body.appendChild(resizer);
          mw.image_resizer = resizer
        }
      },
      prepare:function(){
        mw.image.resize.create_resizer();
        $(mw.image_resizer).resizable({
            handles: "all",
            minWidth: 60,
            minHeight: 60,
            start:function(){
              mw.image.isResizing = true;
              $(mw.image_resizer).resizable("option", "maxWidth", mw.image.currentResizing.parent().width());
              $(mw.tools.firstParentWithClass(mw.image.currentResizing[0], 'edit')).addClass("changed");
            },
            stop:function(){
              mw.image.isResizing = false;
              mw.drag.fix_placeholders();
            },
            resize:function(){
              var offset = mw.image.currentResizing.offset();
              $(this).css(offset);
            },
            aspectRatio: 16 / 9
        });
      },
      init:function(selector){
        mw.image_resizer == undefined?mw.image.resize.prepare():'';   /*
        mw.$(".element-image").each(function(){
          var img = this.getElementsByTagName('img')[0];
          this.style.width = $(img).width()+'px';
          this.style.height = $(img).height()+'px';
        });     */

        $(window).bind("onImageClick", function(e, el){

         if( !mw.image.isResizing && !mw.isDrag && !mw.settings.resize_started && el.tagName=='IMG'){
           //  var order = mw.tools.parentsOrder(el, ['edit', 'module']);

           //  if(!(order.module > -1 && order.edit > order.module) && order.edit>-1){

               var el = $(el);
               var offset = el.offset();
               var r = $(mw.image_resizer);
               var width = el.outerWidth();
               var height = el.outerHeight();
               r.css({
                  left:offset.left,
                  top:offset.top,
                  width:width,
                  height:height
               });
               r.addClass("active");
               $(mw.image_resizer).resizable( "option", "alsoResize", el);
               $(mw.image_resizer).resizable( "option", "aspectRatio", width/height);
               mw.image.currentResizing = el;
               el[0].contentEditable = true ;

               if(el[0].parentNode.tagName !== 'A'){
                  mw.wysiwyg.select_element(el[0]);
               }
               else{
                  mw.wysiwyg.select_element(el[0].parentNode);

               }


        // }
         }
        })

        }
      },
      linkIt:function(img_object){
        var img_object = img_object || document.querySelector("img.element-current");

        if(img_object.parentNode.tagName === 'A'){
           $(img_object.parentNode).replaceWith(img_object);
        }
        else{
            mw.tools.modal.frame({
              url:"rte_link_editor#image_link",
              title:"Add/Edit Link",
              name:"mw_rte_link",
              width:340,
              height:535
            });
        }
      },
      _isrotating:false,
      rotate:function(img_object, angle){

        if(!mw.image.Rotator){
           mw.image.Rotator = document.createElement('canvas');
           mw.image.Rotator.style.top = '-9999px';
           mw.image.Rotator.style.position = 'absolute';
           mw.image.RotatorContext = mw.image.Rotator.getContext('2d');
           document.body.appendChild(mw.image.Rotator);
        }


        if(!mw.image._isrotating){
          mw.image._isrotating = true;
        var img_object = img_object || mwd.querySelector("img.element-current");
        if(img_object === null ) {return false;}
        mw.image.preload(img_object.src, function(){


        if(!img_object.src.contains("base64")){
          var currDomain = mw.url.getDomain(window.location.href);

          var srcDomain = mw.url.getDomain(img_object.src);

          if(currDomain!==srcDomain){
               mw.tools.alert("This action is allowed for images on the same domain.");
               return false;
          }
        }


            var angle = angle || 90;
            var image = $(this);
            var w = image.width();
            var h = image.height();

            var contextWidth = w
            var contextHeight = h
            var x = 0;
            var y = 0;

             switch(angle){
                  case 90:
                      var contextWidth = h;
                      var contextHeight = w;
                      var y = -h;
                      break;
                  case 180:
                      var x = -w;
                      var y = -h;
                      break;
                  case 270:
                      var contextWidth = h;
                      var contextHeight = w;
                      var x = -w;
                      break;
                  default:
                      var contextWidth = h;
                      var contextHeight = w;
                      var y = -h;
             }

           mw.image.Rotator.setAttribute('width', contextWidth);
  		   mw.image.Rotator.setAttribute('height', contextHeight);
  		   mw.image.RotatorContext.rotate(angle * Math.PI / 180);
  		   mw.image.RotatorContext.drawImage(img_object, x, y);

           var data =  mw.image.Rotator.toDataURL("image/png");
           img_object.src = data;
           mw.image._isrotating = false;
           mw.wysiwyg.normalizeBase64Image(img_object);
        });
        }
      },
      grayscale:function(node){
        var node = node || mwd.querySelector("img.element-current");
        if(node === null ) {return false;}
        mw.image.preload(node.src, function(){
        var canvas = mwd.createElement('canvas');
        var ctx = canvas.getContext('2d');
        canvas.width = $(this).width();
        canvas.height = $(this).height();
        ctx.drawImage(node, 0, 0);
        var imgPixels = ctx.getImageData(0, 0, canvas.width, canvas.height);
        for(var y = 0; y < imgPixels.height; y++){
            for(var x = 0; x < imgPixels.width; x++){
                var i = (y * 4) * imgPixels.width + x * 4; //Why is this multiplied by 4?
                var avg = (imgPixels.data[i] + imgPixels.data[i + 1] + imgPixels.data[i + 2]) / 3;
                imgPixels.data[i] = avg;
                imgPixels.data[i + 1] = avg;
                imgPixels.data[i + 2] = avg;
            }
        }
        ctx.putImageData(imgPixels, 0, 0, 0, 0, imgPixels.width, imgPixels.height);
        node.src = canvas.toDataURL();
        mw.wysiwyg.normalizeBase64Image(node);

        })
    },
    vr : [0, 0, 0, 1, 1, 2, 3, 3, 3, 4, 4, 4, 5, 5, 5, 6, 6, 7, 7, 7, 7, 8, 8, 8, 9, 9, 9, 9, 10, 10, 10, 10, 11, 11, 12, 12, 12, 12, 13, 13, 13, 14, 14, 15, 15, 16, 16, 17, 17, 17, 18, 19, 19, 20, 21, 22, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 39, 40, 41, 42, 44, 45, 47, 48, 49, 52, 54, 55, 57, 59, 60, 62, 65, 67, 69, 70, 72, 74, 77, 79, 81, 83, 86, 88, 90, 92, 94, 97, 99, 101, 103, 107, 109, 111, 112, 116, 118, 120, 124, 126, 127, 129, 133, 135, 136, 140, 142, 143, 145, 149, 150, 152, 155, 157, 159, 162, 163, 165, 167, 170, 171, 173, 176, 177, 178, 180, 183, 184, 185, 188, 189, 190, 192, 194, 195, 196, 198, 200, 201, 202, 203, 204, 206, 207, 208, 209, 211, 212, 213, 214, 215, 216, 218, 219, 219, 220, 221, 222, 223, 224, 225, 226, 227, 227, 228, 229, 229, 230, 231, 232, 232, 233, 234, 234, 235, 236, 236, 237, 238, 238, 239, 239, 240, 241, 241, 242, 242, 243, 244, 244, 245, 245, 245, 246, 247, 247, 248, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 254, 254, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255, 255],
    vg : [0, 0, 1, 2, 2, 3, 5, 5, 6, 7, 8, 8, 10, 11, 11, 12, 13, 15, 15, 16, 17, 18, 18, 19, 21, 22, 22, 23, 24, 26, 26, 27, 28, 29, 31, 31, 32, 33, 34, 35, 35, 37, 38, 39, 40, 41, 43, 44, 44, 45, 46, 47, 48, 50, 51, 52, 53, 54, 56, 57, 58, 59, 60, 61, 63, 64, 65, 66, 67, 68, 69, 71, 72, 73, 74, 75, 76, 77, 79, 80, 81, 83, 84, 85, 86, 88, 89, 90, 92, 93, 94, 95, 96, 97, 100, 101, 102, 103, 105, 106, 107, 108, 109, 111, 113, 114, 115, 117, 118, 119, 120, 122, 123, 124, 126, 127, 128, 129, 131, 132, 133, 135, 136, 137, 138, 140, 141, 142, 144, 145, 146, 148, 149, 150, 151, 153, 154, 155, 157, 158, 159, 160, 162, 163, 164, 166, 167, 168, 169, 171, 172, 173, 174, 175, 176, 177, 178, 179, 181, 182, 183, 184, 186, 186, 187, 188, 189, 190, 192, 193, 194, 195, 195, 196, 197, 199, 200, 201, 202, 202, 203, 204, 205, 206, 207, 208, 208, 209, 210, 211, 212, 213, 214, 214, 215, 216, 217, 218, 219, 219, 220, 221, 222, 223, 223, 224, 225, 226, 226, 227, 228, 228, 229, 230, 231, 232, 232, 232, 233, 234, 235, 235, 236, 236, 237, 238, 238, 239, 239, 240, 240, 241, 242, 242, 242, 243, 244, 245, 245, 246, 246, 247, 247, 248, 249, 249, 249, 250, 251, 251, 252, 252, 252, 253, 254, 255],
    vb : [53, 53, 53, 54, 54, 54, 55, 55, 55, 56, 57, 57, 57, 58, 58, 58, 59, 59, 59, 60, 61, 61, 61, 62, 62, 63, 63, 63, 64, 65, 65, 65, 66, 66, 67, 67, 67, 68, 69, 69, 69, 70, 70, 71, 71, 72, 73, 73, 73, 74, 74, 75, 75, 76, 77, 77, 78, 78, 79, 79, 80, 81, 81, 82, 82, 83, 83, 84, 85, 85, 86, 86, 87, 87, 88, 89, 89, 90, 90, 91, 91, 93, 93, 94, 94, 95, 95, 96, 97, 98, 98, 99, 99, 100, 101, 102, 102, 103, 104, 105, 105, 106, 106, 107, 108, 109, 109, 110, 111, 111, 112, 113, 114, 114, 115, 116, 117, 117, 118, 119, 119, 121, 121, 122, 122, 123, 124, 125, 126, 126, 127, 128, 129, 129, 130, 131, 132, 132, 133, 134, 134, 135, 136, 137, 137, 138, 139, 140, 140, 141, 142, 142, 143, 144, 145, 145, 146, 146, 148, 148, 149, 149, 150, 151, 152, 152, 153, 153, 154, 155, 156, 156, 157, 157, 158, 159, 160, 160, 161, 161, 162, 162, 163, 164, 164, 165, 165, 166, 166, 167, 168, 168, 169, 169, 170, 170, 171, 172, 172, 173, 173, 174, 174, 175, 176, 176, 177, 177, 177, 178, 178, 179, 180, 180, 181, 181, 181, 182, 182, 183, 184, 184, 184, 185, 185, 186, 186, 186, 187, 188, 188, 188, 189, 189, 189, 190, 190, 191, 191, 192, 192, 193, 193, 193, 194, 194, 194, 195, 196, 196, 196, 197, 197, 197, 198, 199],
    vintage : function(node){
        var node = node || mwd.querySelector("img.element-current");
        if(node === null ) {return false;}
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        mw.image.preload(node.src, function(){
            canvas.width = $(this).width();
            canvas.height = $(this).height();
            ctx.drawImage(node, 0, 0);
            var imageData = ctx.getImageData(0,0,canvas.width,canvas.height), l=imageData.data.length, i=0;
            for ( ; i < l; i+=4) {
                imageData.data[i] = mw.image.vr[imageData.data[i]];
                imageData.data[i+1] = mw.image.vg[imageData.data[i+1]];
                imageData.data[i+2] = mw.image.vb[imageData.data[i+2]];
                if (noise > 0) {
                    var noise = Math.round(noise - Math.random() * noise), j=0;
                    for( ; j<3; j++){
                        var iPN = noise + imageData.data[i+j];
                        imageData.data[i+j] = (iPN > 255) ? 255 : iPN;
                    }
                }
            }
            ctx.putImageData(imageData, 0, 0);
            node.src = canvas.toDataURL();
            mw.wysiwyg.normalizeBase64Image(node);
            $(canvas).remove()
        });
      },
      _dragActivated : false,
      _dragcurrent : null,
      _dragparent : null,
      _dragcursorAt : {x:0,y:0},
      _dragTxt:function(e){
        if(mw.image._dragcurrent!==null){
          mw.image._dragcursorAt.x = e.pageX-mw.image._dragcurrent.offsetLeft;
          mw.image._dragcursorAt.y = e.pageY-mw.image._dragcurrent.offsetTop;
          var x = e.pageX - mw.image._dragparent.offsetLeft - mw.image._dragcurrent.startedX  - mw.image._dragcursorAt.x;
          var y = e.pageY - mw.image._dragparent.offsetTop - mw.image._dragcurrent.startedY  - mw.image._dragcursorAt.y;
          mw.image._dragcurrent.style.top = y + 'px';
          mw.image._dragcurrent.style.left = x + 'px';



        }
      },
      text_object:function(tag, text){
        var el = mwd.createElement(tag);
        el.className = "image_free_text";
        el.innerHTML = text;
        el.style.position = 'relative';
        el.style.display = 'inline-block';
        el.contenteditable = false;
        el.style.top = '0px';
        el.style.left = '40px';
        el.style.color = 'white';
        el.style.textShadow = '0 0 6px black';
        el.style.cursor = 'move';
        el.style.zIndex = '999';
        el.style.height = 'auto';
        el.ondblclick = function(e){
          e.preventDefault();
          mw.wysiwyg.select_all(this);
        }
        return el;
      },
      enterText:function(img_object){
          var img_object = img_object || document.querySelector("img.element-current");
          var image = $(img_object);
          if(!img_object.is_activated){
                img_object.is_activated = true;
                image.removeClass("element");
                image.wrap("<div class='element mw_image_txt'></div>");
                var obj = mw.image.text_object('span', "Lorem ipsum a asd a as asd");
                image.before(obj);
          }
      },
      preload:function(url, callback){
        var img = mwd.createElement('img');
        img.className = 'semi_hidden';
        img.src = url;
        img.onload = function(){
          setTimeout(function(){
            if(typeof callback === 'function'){
              callback.call(img);
            }
            $(img).remove();
          },33);
        }
        mwd.body.appendChild(img);
      },
      description:{
        add:function(text){
            var img = document.querySelector("img.element-current");
            img.title = text;
        },
        get:function(){
           return document.querySelector("img.element-current").title;
        },
        init:function(id){
            var area = $(id);
            area.hover(function(){
              area.addClass("desc_area_hover");
            }, function(){
              area.removeClass("desc_area_hover");
            });
            var curr = mw.image.description.get();
            if(!area.hasClass("inited")){
              area.addClass("inited");
              area.bind("keyup change paste", function(){
                var val = $(this).val();
                mw.image.description.add(val);
              });
            }
            area.val(curr);
            area.show();
        }
      }
    }








$.fn.notmouseenter = function() {
  return this.filter(function(){
    var el = $(el);
    var events = el.data("events");
    return (events==undefined || events.mouseover==undefined || events.mouseover[0].origType!='mouseenter');
  });
};

$.fn.notclick = function() {
  return this.filter(function(){
    var el = $(el);
    var events = el.data("events");
    return (events==undefined || events.click==undefined);
  });
};





$.expr[':'].isHidden = function(obj, index, meta, stack){
    return  mw.is.invisible(obj);
};
$.expr[':'].isVisible = function(obj, index, meta, stack){

    return window.getComputedStyle(obj, null).visibility === 'visible';
};















PagesFrameSRCSet = false;


$(document).ready(function(){



    mw.wysiwyg.prepare();
    mw.wysiwyg.init();

    set_pagetab_size();

    mw.on.hashParam("tab", function(){
      mw.tools.sidebar();
      mw.$("#mw_tabs_small .mw-dropdown-list").hide();
      setTimeout(function(){
        mw.$("#mw_tabs_small .mw-dropdown-list").show();
      }, 222);
      var tab = this;
      if(tab==false){
        mw.url.windowHashParam('tab', 'modules');
        mw.$("#mw_small_menu_text").html('Modules');
        return false;
      }
      mw.$("#mw_small_menu_text").html(this);
      mw.$(".mw_toolbar_tab").removeClass("mw_tab_active");
      mw.$("#tab_"+tab).addClass("mw_tab_active");
      mw.$("#mw_tabs li").removeClass("active");
      mw.$("#mw_tabs li#t_"+tab).addClass("active");
      if(tab=='pages'){
        mw.$("html").addClass("mw_pages");
        if(!PagesFrameSRCSet){
          PagesFrameSRCSet = true;
          mw.$("#mw_edit_pages").attr("src", mw.$("#mw_edit_pages").dataset("src"));
        }
      }
      else{
        mw.$("html").removeClass("mw_pages");
      }
 });







 mw.$(".edit a, #mw-toolbar-right a").click(function(){
  var el = this;

  if(!el.isContentEditable){
      if(el.onclick === null){
        if(!(el.href.indexOf("javascript:") === 0 || el.href == '#' || $(el).attr("href").indexOf('#') == 0 || typeof el.attributes['href'] == 'undefined')){

           return mw.beforeleave(this.href);
      }
      }

  }


 });






});

mw.toolbar = {
  center_icons:function(){
    mw.$(".list-modules .mw_module_image img").each(function(){
      var Istyle = window.getComputedStyle(this, null);
      var img_height = parseFloat(Istyle.height);
      img_height < 32 ? $(this).css("marginTop", 16 - img_height/2) : '';
    });
  }
}






$(window).load(function(){



 mw.$("#history_dd").hover(function(){
      $(this).addClass("hover");
    }, function(){
       $(this).removeClass("hover");
    });



    mw.toolbar.center_icons();


  //mw.image.resize.init(".edit img");
  mw.image.resize.init(".element-image");



    //mw.$("#live_edit_toolbar_holder").height(mw.$("#live_edit_toolbar").height());

    $(window).bind("scrollstop",function(){
      setTimeout(function(){
      if(mw.isDrag && mw.$(".ui-draggable-dragging").css("position")=='relative'){
        var curr_el = mw.$(".ui-draggable-dragging").css("position", "static");
        var offset = curr_el.offset();
        curr_el.css("position", "relative");
        var scroll_top = $(window).scrollTop();
        curr_el.css({
          top:mw.mouse.y-offset.top+(scroll_top)+30
        });
      }  }, 100);
    });



    $(document.body).mousedown(function(event){

      if(mw.$(".editor_hover").length==0){
        $(mw.wysiwyg.external).empty().css("top", "-9999px");

        $(mwd.body).removeClass('hide_selection');
      }

      if(!mw.$("#history_dd").hasClass("hover")){
        $("#historycontainer").hide()
      }

    });


    mw.$("#mw_small_editor").draggable({
        drag:function(){
          mw.SmallEditorIsDragging = true;
        },
        stop:function(){
          mw.SmallEditorIsDragging = false;
        }
    });

    mw.$("#mw-text-editor").mousedown(function(){
      if(mw.$(".mw_editor_btn_hover").length==0){
        mw.mouseDownOnEditor = true;
        $(this).addClass("hover");
      }
    });
    mw.$("#mw-text-editor").mouseup(function(){
        mw.mouseDownOnEditor = false;
        $(this).removeClass("hover");
    });
    mw.$("#mw-text-editor").mouseleave(function(){
        if(mw.mouseDownOnEditor){
            mw.$("#mw_small_editor").visible();
            mw.$("#mw-text-editor").invisible();
            mw.$("#mw-text-editor").removeClass("hover");
        }
    });
    $(document.body).mouseup(function(event){
         mw.target.item = event.target;
         mw.target.tag = event.target.tagName.toLowerCase();
         mw.mouseDownOnEditor = false;
         mw.SmallEditorIsDragging = false;

        if( !mw.image.isResizing &&
             mw.target.tag!='img' &&
             mw.target.item.className!='image_change' && $(mw.image_resizer).hasClass("active")){
           $(mw.image_resizer).removeClass("active");


           //mw.$("#module_design_selector").setDropdownValue("#tb_el_style", true);

        }
    });





   if(mw.hash()==='' || mw.url.getHashParams(mw.hash()).tab===undefined){
     mw.url.windowHashParam("tab", "modules");
   }


   mw.tools.sidebar();




if(typeof mw.hasDraft === 'object'){
   var html = ""
   + "<div class='hasdraft'>"
      + "<p>Load last Draft?</p>"
      + "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-green' onclick='mw.history.load(\""+mw.hasDraft.draft+"\")'>Yes</span>"
      + "<span class='mw-ui-btn mw-ui-btn-small mw-ui-btn-red' onclick='$(this.parentNode).remove();'>No</span>"
   +"</div>";

   mw.$("#mw_tabs_small").after(html);

   setTimeout(function(){
         mw.$(".hasdraft").addClass("active");
   }, 10000);

 }

});





mw.toggle_subpanel = function(){
  var _speed = 200;
  var el = mw.$("#show_hide_sub_panel");
  if(el.hasClass("state-off")){
     el.removeClass("state-off");
     mw.$("#show_hide_sub_panel_slider").animate({left:0}, _speed);
     mw.$("#show_hide_sub_panel_info").fadeOut(_speed, function(){
       $(this).css({left:'auto'}).html(mw.msg.less).fadeIn(_speed);
     });
     mw.$(".mw_tab_active").slideDown(_speed);
     mw.$("#mw_toolbar_nav").slideDown(_speed, function(){
        mw.$("#mw-toolbar-right").css("top", 6);
        mw.$("#show_hide_sub_panel").css("top", 16);
     });
     $(mwd.body).animate({paddingTop:170});

     mw.$("#mw-toolbar-right").show();
     mw.$("#editor_save").hide();
  }
  else{
    el.addClass("state-off");
    mw.$("#show_hide_sub_panel_slider").animate({left:35}, _speed);
    mw.$("#show_hide_sub_panel_info").fadeOut(_speed, function(){
      $(this).css({left:3}).html(mw.msg.more).fadeIn(_speed);
    });

    mw.$(".mw_tab_active").slideUp(_speed);
    mw.$("#mw_toolbar_nav").slideUp(_speed, function(){
       mw.$("#mw-toolbar-right").css("top", 0);
       mw.$("#show_hide_sub_panel").css("top", 10);
       mw.$("#editor_save").fadeIn();
    });
    //$(mwd.body).animate({paddingTop:0});
    $(mwd.body).animate({paddingTop:mw.$("#mw-text-editor").height()});
    mw.$("#mw-toolbar-right").hide();

  }
}

set_pagetab_size = function(){
    mw.$("#mw_edit_pages").css({
       width:window.innerWidth,
       height:window.innerHeight-49
     });
}


$(window).resize(function(){
    mw.tools.module_slider.scale();
    mw.tools.toolbar_slider.ctrl_show_hide();
    set_pagetab_size();
    mw.designTool.position();
});




mw.preview = function(){
    var url = mw.url.removeHash(window.location.href);
    var url = mw.url.set_param('preview', true, url);

    window.open(url, '_blank');
    window.focus();

   /*
    mw.tools.modal.frame({
      url:url,
      width:$(window).width(),
      height:$(window).height()
    }); */
}

mw.iphonePreview = function(){
    var url = mw.url.removeHash(window.location.href);
    var url = mw.url.set_param('preview', true, url);


    mw.tools.modal.frame({
      url:url,
      width:382,
      height:802,
      width:320, //originalnoto
      height:568,//originalnoto
      height:592,
      template:'modal-iphone'
    });

    mw.tools.modal.overlay();
}


