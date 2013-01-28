

mw.isDrag = false;
mw.resizable_row_width = false;
mw.mouse_over_handle = false;
mw.external_content_dragged = false;


mw.have_new_items = false;

mw.dragCurrent = null;
mw.currentDragMouseOver = null;



mw.mouseDownOnEditor = false;
mw.SmallEditorIsDragging = false;


/**
 * Makes Droppable area
 *
 * @return Dom Element
 */
mw.dropables = {
  prepare:function(){
    var dropable = document.createElement('div');
    dropable.className = 'mw_dropable';
    dropable.innerHTML = '<span class="mw_dropable_arr"></span>';
    document.body.appendChild(dropable);
    mw.dropable = $(dropable);
    mw.dropable.bind("mouseenter", function(){
      $(this).hide();
    });
  },
  display:function(el){
    var el = $(el);
    var offset = el.offset();
    var width = el.outerWidth();
    var height = el.outerHeight();
    if(mw.drop_regions.global_drop_is_in_region){
        //console.log(1)
    }
    else{
      mw.dropable.css({
          top:offset.top+height,
          left:offset.left,
          width:width
      });
    }
  },
  set:function(pos, offset, height, width){
    if(pos==='top'){
      mw.top_half = true;
      mw.dropable.css({
        top:offset.top-2,
        left:offset.left,
        height:2,
        width:width
      });
      mw.dropable.data("position", "top");
      mw.dropable.addClass("mw_dropable_arr_up");
    }
    else if(pos==='bottom'){
        mw.top_half = false;
        mw.dropable.css({
          top:offset.top+height+2,
          left:offset.left,
          height:2,
          width:width
        });
        mw.dropable.data("position", "bottom");
        mw.dropable.removeClass("mw_dropable_arr_up");
        mw.dropable.removeClass("mw_dropable_arr_rigt");
    }
    else if(pos==='left'){
       mw.dropable.data("position", 'left');
       mw.dropable.css({
            top:offset.top,
            height:height,
            left:offset.left,
            width:2
       });
    }
    else if(pos==='right'){
      mw.dropable.data("position", 'right');
      mw.dropable.addClass("mw_dropable_arr_rigt");
      mw.dropable.css({
          top:offset.top,
          left:offset.left+width,
          height:height,
          width:2
     });
    }
  }
}




$(document).ready(function(){
   mw.drag.create();

   mw.$(mwd.body).keyup(function(){
     mw.$(".mw_master_handle").css({
       left:"",
       top:""
     });
   });

   mw.edits = mw.$('.edit');

   mw.edits.mouseleave(function(e){
     if(mw.isDrag){
       var el = $(this);
       var off = el.offset();
       var h = el.outerHeight();
       var w = el.outerWidth();
       if(e.pageX>off.left && e.pageX<off.left+w){
         mw.currentDragMouseOver = this;
         if(off.top+h<e.pageY){
            mw.dropables.set("top", off, h, w);
         }
         else{
           mw.dropables.set("bottom", off, h, w);
         }
       }
       mw.dropable.addClass("mw_dropable_onleaveedit");
     }
   });


   $(window).bind("onDragHoverOnEmpty", function(e,el){
     if($.browser.webkit){
       var _el = $(el);
       _el.addClass("hover");
       if(!_el.hasClass("mw-webkit-drag-hover-binded")){
          _el.addClass("mw-webkit-drag-hover-binded");
          _el.mouseleave(function(){
            _el.removeClass("hover");
          });
       }
     }
   });


});

mw.isDragItem = function(obj){
  var items = /^(blockquote|center|dir|fieldset|form|h[1-6]|hr|menu|ul|ol|dl|p|pre|table|div)$/i;
  return items.test(obj.nodeName);
}


mw.drag = {
	create: function () {
         mw.top_half = false;

         $(mwd.body).mousemove(function(event){

            if(!mw.settings.resize_started){

           if(mw.mouseDownOnEditor){
            $("#mw_small_editor").css({
               top:event.pageY-$(window).scrollTop(),
               left:event.pageX-100
            });
           }
           if(mw.SmallEditorIsDragging){
                var offset_small = mw.smallEditor.offset();
                var offset_big = mw.bigEditor.offset();
                if(offset_small.top<offset_big.top+50){
                    mw.SmallEditorIsDragging = false;
                    mw.smallEditor.invisible();
                    mw.bigEditor.visible();
                }
           }

           mw.mouse = {
             x:event.pageX,
             y:event.pageY
           }

           mw.mm_target = event.target;
           mw.$mm_target = $(mw.mm_target);


           if(!mw.isDrag){
               if(mw.mouse.x % 2 ===0 ){ //not on every pixel
                   //trigger on element

                   if(mw.$mm_target.hasClass("element")){
                     $(window).trigger("onElementOver", mw.mm_target);
                   }
                   else if(mw.$mm_target.parents(".element").length>0){
                     $(window).trigger("onElementOver", mw.$mm_target.parents(".element:first")[0]);
                   }
                   else if(mw.mm_target.id!='mw_handle_element' && mw.$mm_target.parents("#mw_handle_element").length==0){
                     $(window).trigger("onElementLeave", mw.mm_target);
                   }

                   //trigger on module
                   if(mw.$mm_target.hasClass("module")){
                     $(window).trigger("onModuleOver", mw.mm_target);
                   }
                   else if(mw.$mm_target.parents(".module").length>0){
                     $(window).trigger("onModuleOver", mw.$mm_target.parents(".module:first")[0]);
                   }
                   else if(mw.mm_target.id!='mw_handle_module' && mw.$mm_target.parents("#mw_handle_module").length==0){
                     $(window).trigger("onModuleLeave", mw.mm_target);
                   }


                   //trigger on row
                   if(mw.$mm_target.hasClass("mw-row")){
                     $(window).trigger("onRowOver", mw.mm_target);
                   }
                   else if(mw.$mm_target.parents(".mw-row").length>0){
                     $(window).trigger("onRowOver", mw.$mm_target.parents(".mw-row:first")[0]);
                   }
                   else if(mw.mm_target.id!='mw_handle_row' && mw.$mm_target.parents("#mw_handle_row").length==0){
                     $(window).trigger("onRowLeave", mw.mm_target);

                   }

                   //trigger on item
                   if((mw.isDragItem(mw.mm_target) && mw.$mm_target.parent().hasClass("element")) || mw.mm_target.className.contains('mw_item')){

                        $(window).trigger("onItemOver", mw.tools.firstParentWithClass(mw.mm_target, 'edit'));

                        //$(window).trigger("onItemOver", mw.mm_target);
                        //mw.$mm_target.addClass("mw_item");
                   }
                   else if(mw.$mm_target.parents(".mw_item").length>0){
                       $(window).trigger("onItemOver", mw.$mm_target.parents(".mw_item")[0]);
                   }
                   else if(mw.mm_target.id!='items_handle' && mw.$mm_target.parents("#items_handle").length==0){
                     $(window).trigger("onItemLeave", mw.mm_target);
                   }
                   if(mw.$mm_target.parents(".edit,.mw_master_handle").length==0){
                     if(!mw.$mm_target.hasClass(".edit") && !mw.$mm_target.hasClass("mw_master_handle")){
                          //$(window).trigger("onAllLeave", mw.mm_target);
                     }
                   }

               }


               mw.image._dragTxt(event);

           }
           else{



           if( mw.$mm_target.hasClass("empty-element")){
            $(window).trigger("onDragHoverOnEmpty", mw.mm_target);  //needed for webkit bug
           }
           else if($(mw.mm_target.parentNode).hasClass("empty-element")){
            $(window).trigger("onDragHoverOnEmpty", mw.mm_target.parentNode);
           }




             if(   mw.$mm_target.hasClass("element")
                || mw.$mm_target.hasClass("empty-element")
                || mw.tools.hasParentsWithClass(mw.mm_target, "element")
                || mw.isDragItem(mw.mm_target)
                || mw.mm_target.tagName=='IMG'){



               if(!mw.mm_target.className.contains("ui-") && !mw.mm_target.className.contains("mw-col") && !mw.tools.hasParentsWithClass(mw.mm_target, "ui-draggable-dragging")){
                    if(mw.tools.hasParentsWithClass(mw.mm_target, 'edit') && !mw.tools.hasParentsWithClass(mw.mm_target, 'no-drop') && !mw.$mm_target.hasClass('no-drop')){
                       mw.currentDragMouseOver = mw.mm_target;
                       if(mw.$mm_target.hasClass("empty-element")){
                          mw.dropable.removeClass("mw_dropable_onleaveedit");
                       }
                    }
                    else{
                      if(mw.tools.hasClass(mw.mm_target.className, 'edit')){
                          mw.currentDragMouseOver = mw.mm_target;
                      }
                      else{
                         mw.currentDragMouseOver =  null;
                      }

                    }
               }
             }




              //mw.currentDragMouseOver = mw.mm_target;


            /* if(mw.mm_target.tagName==='BODY' && mw.mouse.y%2==0){
                    var items = mwd.getElementsByClassName('element');
                    for(var i=0; i<items.length; i++ ){
                      var rect = items[i].getClientRects()[0];
                      var top = rect.top;
                      var bot = rect.bottom;
                      var height = rect.height;

                      if(mw.mouse.y > top && mw.mouse.y < bot){
                        if(mw.mouse.y<top+height/2){
                          mw.currentDragMouseOver = items[i];
                          mw.dropable.data("position", 'top');
                        }
                        else if(mw.mouse.y>top+height/2){
                          mw.currentDragMouseOver = items[i];
                          mw.dropable.data("position", 'bottom');
                        }
                      }
                    }
               } */
           }

           if(mw.isDrag && mw.currentDragMouseOver!=null  && !mw.tools.hasParentsWithClass(mw.currentDragMouseOver, 'module')){

            if(mw.tools.hasClass('nodrop', mw.currentDragMouseOver) || mw.tools.hasParentsWithClass('nodrop', mw.currentDragMouseOver)){
              mw.currentDragMouseOver=null;
              return false;
            }
            mw.drop_regions.init(mw.currentDragMouseOver, event, function(region){});


            var el = $(mw.currentDragMouseOver);
            $(".ui-draggable-dragging").show();
            if(el.hasClass("ui-draggable-dragging") || mw.tools.hasParentsWithClass(mw.currentDragMouseOver, "ui-draggable-dragging")){
              // check if mouse is over the dragging element
              return false;
            }

            var body = $(this);

            var offset = el.offset();
            var height = el.outerHeight();
            var width = el.width();


            if(mw.drop_regions.global_drop_is_in_region && !$(mw.dragCurrent).hasClass("mw-row")/*&& $(mw.dragCurrent).hasClass("element-image")*/){

              mw.dropable.addClass("mw_dropable_vertical");
              if(mw.drop_regions.which=='left'){
                mw.dropables.set('left', offset, height);
              }
              else{
                mw.dropables.set('right', offset, height, width);
              }
            }
            else{
                mw.dropable.removeClass("mw_dropable_vertical");
                mw.dropable.removeClass("mw_dropable_arr_rigt");
                if(event.pageY > offset.top+(height/2)){  //is on the bottom part

                    mw.dropables.set('bottom', offset, height, width);
                }
                else{
                 mw.dropables.set('top', offset, height, width);
                }
            }


            if(el.hasClass("element") || el.hasClass("mw-row") || mw.tools.hasParentsWithClass(mw.currentDragMouseOver, 'mw-row') || mw.tools.hasParentsWithClass(mw.currentDragMouseOver, 'element')){
                if(el.hasClass("empty-element")){
                    mw.dropable.hide();
                }
                else{
                    mw.dropable.show();
                }
            }
            else{
               mw.dropable.hide();
            }
           }
           }
         });


        mw.dropables.prepare();

		mw.drag.fix_placeholders(true);
		mw.drag.fixes()

        mw.drag.init();


	 mw.resizable_columns();



        $(mwd.body).mouseup(function(event){
        	if(mw.isDrag && mw.dropable.is(":hidden")){
        		$(".ui-draggable-dragging").css({top:0,left:0});
        	}
            $(this).removeClass("not-allowed");


            if($(".toolbar_bnav_hover").length==0){
            $(".ts_main_ul .ts_action").invisible();
            $(".ts_main_ul .ts_action").css({left:"100%", top:0});
          }

        });

        $(mwd.body).mousedown(function(event){
          var target = event.target;
          if($(target).hasClass("image_free_text")){
            mw.image._dragcurrent = target;
            mw.image._dragparent = target.parentNode;
            mw.image._dragcursorAt.x = event.pageX-target.offsetLeft;
            mw.image._dragcursorAt.y = event.pageY-target.offsetTop;

            target.startedY = target.offsetTop - target.parentNode.offsetTop;
            target.startedX = target.offsetLeft - target.parentNode.offsetLeft;

          }

          if($(".desc_area_hover").length==0){
             $(".desc_area").hide();
          }


        });

        $(window).bind("onElementOver", function(a, element){
          var el = $(element);
          var o = el.offset();
          var width = el.width();
          var pleft = parseFloat(el.css("paddingLeft"));
          $(mw.handle_element).css({
            top:o.top,
            left:o.left+pleft,
            width:width
          });
          $(mw.handle_element).data("curr", element);
          element.id=="" ? element.id="row_"+mw.random() : "";
          mw.dropable.removeClass("mw_dropable_onleaveedit");
        });
        $(window).bind("onModuleOver", function(a, element){

          var el = $(element);
          var title = el.dataset("filter");
          $(mw.handle_module).find(".mw-element-name-handle").html(title);
          $(mw.handle_module).find(".mw_edit_delete").dataset("delete", element.id);
          var o = el.offset();
          var width = el.width();
          var pleft = parseFloat(el.css("paddingLeft"));
          $(mw.handle_module).css({
            top:o.top,
            left:o.left+pleft,
            width:width
          });
          $(mw.handle_module).data("curr", element);
          element.id=="" ? element.id="row_"+mw.random() : "";
        });
        $(window).bind("onRowOver", function(a, element){
          var el = $(element);
          var o = el.offset();
          var width = el.width();
          var pleft = parseFloat(el.css("paddingLeft"));
          $(mw.handle_row).css({
            top:o.top-25,
            left:o.left+pleft,
            width:width
          });
          $(mw.handle_row).data("curr", element);
          var size =  $(element).children(".mw-col").length;
          $("a.mw-make-cols").removeClass("active");
          $("a.mw-make-cols").eq(size-1).addClass("active");
          element.id=="" ? element.id="row_"+mw.random() : "";
        });
        $(window).bind("onItemOver", function(a, element){
          var el = $(element);
          var o = el.offset();
          //mw.log(mw.random());
          var pleft = parseFloat(el.css("paddingLeft"));
          $(mw.handle_item).css({
            top:o.top,
            left:o.left+pleft
          });
          $(mw.handle_item).data("curr", element);
        });



        $("#mw_handle_row,#mw_handle_module,#mw_handle_element,#items_handle").hover(function(){
           var active = $(this).data("curr");
           $(active).addClass("element-active");
        }, function(){
           var active = $(this).data("curr");
           $(active).removeClass("element-active");
        });

        $(window).bind("onAllLeave", function(e, target){
            $("#mw_handle_row,#mw_handle_module,#mw_handle_element").css({
              top:"",
              left:""
            });

        });
        $(window).bind("onElementLeave", function(e, target){
            $(mw.handle_element).css({
              top:"",
              left:""
            })
        });
        $(window).bind("onModuleLeave", function(e, target){
            $(mw.handle_module).css({
              top:"",
              left:""
            });
        });
        $(window).bind("onRowLeave", function(e, target){
            $(mw.handle_row).css({
              top:"",
              left:""
            });
        });

        $(window).bind("onItemLeave", function(e, target){
            /*$(mw.handle_item).css({
              top:"",
              left:""
            });*/
        });

	},

	init: function (selector, callback) {
        if(!mw.handle_item){
            $(mwd.body).append(mw.settings.handles.module);
            $(mwd.body).append(mw.settings.handles.row);
            $(mwd.body).append(mw.settings.handles.element);
            $(mwd.body).append(mw.settings.handles.item);
            mw.handle_module = mwd.getElementById('mw_handle_module');
            mw.handle_row = mwd.getElementById('mw_handle_row');
            mw.handle_element = mwd.getElementById('mw_handle_element');
            mw.handle_item = mwd.getElementById('items_handle');

            $(mw.handle_element).mouseenter(function(){
                var curr = $(this).data("curr");
                $(this).draggable("option", "helper", function(){
                    var clone =  $(curr).clone(true);
                    clone.css({
                       width:$(curr).width(),
                       height:$(curr).height()
                    });
                    return clone;
                });
            }).click(function(){
                var curr = $(this).data("curr");
                if(!$(curr).hasClass("element-current")){
                    $(".element-current").removeClass("element-current");
                    if(curr.tagName=='IMG'){
                      $(window).trigger("onImageClick", curr);
                    }
                    else{
                      $(window).trigger("onElementClick", curr);
                    }
                }
            });
            $(mw.handle_module).mouseenter(function(){
                var curr = $(this).data("curr");

                $(this).draggable("option", "helper", function(){
                    var clone =  $(curr).clone(true);
                    clone.css({
                       width:$(curr).width(),
                       height:$(curr).height()
                    });
                    return clone;
                });
            }).click(function(){
                var curr = $(this).data("curr");
                if(!$(curr).hasClass("element-current")){
                    $(window).trigger("onElementClick", curr);
                }
            });
            $(mw.handle_row).mouseenter(function(){
                var curr = $(this).data("curr");
                $(this).draggable("option", "helper", function(){
                    var clone =  $(curr).clone(true);
                    clone.css({
                       width:$(curr).width(),
                       height:$(curr).height()
                    });
                    return clone;
                });
            }).click(function(){
                var curr = $(this).data("curr");
                if(!$(curr).hasClass("element-current")){
                    $(window).trigger("onElementClick", curr);
                }
            });
            $(mw.handle_item).mouseenter(function(){
                var el = $(this);
                var curr = el.data("curr");
                curr.id = 'item_'+mw.random();
                el.draggable("option", "helper", function(){
                    var clone =  $(curr).clone(true);
                    clone.css({
                       width:$(curr).width(),
                       height:$(curr).height()
                    });
                    return clone;
                });
            }).click(function(){
                var curr = $(this).data("curr");
                if(!$(curr).hasClass("element-current")){
                    $(window).trigger("onItemClick", curr);
                }
            });
            $(mw.handle_element).draggable({
               handle:".mw-sorthandle-moveit",
               cursorAt:{
                 top:-30
               },
               start:function(){
                  mw.isDrag = true;
                  var curr = $(mw.handle_element).data("curr");
                  mw.dragCurrent = curr;
                  mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'element_'+mw.random() : '';
                  $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                  $(window).trigger("onAllLeave");
                  mw.drag.fix_placeholders();
                  $(mwd.body).addClass("dragStart");
                  $(mw.image_resizer).removeClass("active");
               },
               stop:function(){$(mwd.body).removeClass("dragStart");}
            });
            $(mw.handle_module).draggable({
               handle:".mw-sorthandle-moveit",
               cursorAt:{
                 top:-30
               },
               start:function(){
                  mw.isDrag = true;
                  var curr = $(mw.handle_module).data("curr");
                  mw.dragCurrent = curr;
                  mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'module_'+mw.random() : '';
                  $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                  $(window).trigger("onAllLeave");
                  mw.drag.fix_placeholders();
                  $(mwd.body).addClass("dragStart");
                  $(mw.image_resizer).removeClass("active");
               },
               stop:function(){$(mwd.body).removeClass("dragStart");}
            });
            $(mw.handle_row).draggable({
               handle:".mw-col_separator_title",
               cursorAt:{
                 top:-30
               },
               start:function(){
                  mw.isDrag = true;
                  var curr = $(mw.handle_row).data("curr");
                  mw.dragCurrent = curr;
                  mw.dragCurrent.id == "" ? mw.dragCurrent.id = 'row_'+mw.random() : '';
                  $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                  $(window).trigger("onAllLeave");
                  mw.drag.fix_placeholders();
                  $(mwd.body).addClass("dragStart");
                  $(mw.image_resizer).removeClass("active");
               },
               stop:function(){$(mwd.body).removeClass("dragStart");}
            });
            $(mw.handle_item).draggable({
               cursorAt:{
                 top:-30
               },
               start:function(){
                  mw.isDrag = true;
                  var curr = $(mw.handle_item).data("curr");
                  mw.dragCurrent = curr;
                  $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                  $(window).trigger("onAllLeave");
                  mw.drag.fix_placeholders();
                  $(mwd.body).addClass("dragStart");
                  $(mw.image_resizer).removeClass("active");
               },
               stop:function(){$(mwd.body).removeClass("dragStart");}
            });

            mw.drag.toolbar_modules();

        }

        mw.drag.the_drop();
	},
    toolbar_modules:function(selector){
        var items = selector || ".modules-list li";
        $(items).draggable({
            revert: true,
            revertDuration: 0,
            start:function(){
                mw.isDrag = true;
                mw.dragCurrent = mw.GlobalModuleListHelper;
                $(mwd.body).addClass("dragStart");
                $(mw.image_resizer).removeClass("active");
            },
           stop:function(){
              mw.isDrag = false;
              var el = this;
              $(mwd.body).removeClass("dragStart");
              setTimeout(function(){
                mw.drag.load_new_modules();

                mw.recommend.increase($(mw.dragCurrent).attr("data-module-name"))

                mw.drag.toolbar_modules(el);
              }, 200);
           }
        });
        $(items).mouseenter(function(){
            $(this).draggable("option", "helper", function(){
              var clone = $(this).clone(true);
              mw.GlobalModuleListHelper = clone[0];
              return clone[0];
            });
        });
    },
    the_drop: function () {
        if(!$(mwd.body).hasClass("bup")){
          $(mwd.body).addClass("bup");

		$(mwd.body).bind("mouseup", function (event) {


            mw.image._dragcurrent = null;
            mw.image._dragparent = null;

            var sliders = mwd.getElementsByClassName("canvas-slider"), len = sliders.length, i=0;
            for( ; i<len; i++){sliders[i].isDrag = false;}
            if (!mw.isDrag) {
    		    var target = event.target;

                if($(target).hasClass("element")){
                  $(window).trigger("onElementClick", target);
                }
                else if(mw.tools.hasParentsWithClass(target, 'element')){
                  $(window).trigger("onElementClick", $(target).parents(".element")[0]);
                }

                if($(target).hasClass("mw_item")){
                  $(window).trigger("onItemClick", target);
                }
                else if(mw.tools.hasParentsWithClass(target, 'mw_item')){
                  $(window).trigger("onItemClick", $(target).parents(".mw_item")[0]);
                }


                if(target.tagName=='IMG'){
                  $(window).trigger("onImageClick", target);
                }
                if(target.tagName=='BODY'){
                  $(window).trigger("onBodyClick", target);
                }
            }

			if (mw.isDrag) {
			  /*  var history_id = 'history_'+mw.random();

                $(mw.dragCurrent).before('<input type="hidden" id="'+history_id+'" />');

                var story = {
                  pos:history_id,
                  who:mw.dragCurrent
                }
                mw.drag.stories.push(story);
                mw.drag.story_active+=1;  */

				setTimeout(function () {
				  mw.isDrag = false;
                        $(mw.dragCurrent).visibilityDefault().removeClass("mw_drag_current");

                        var curr_prev = $(mw.dragCurrent).prev();
                        var curr_next = $(mw.dragCurrent).next();
                        var curr_parent = $(mw.dragCurrent).parent();


                        var position = mw.dropable.data("position");
                        mw.dropable.removeClass("mw_dropable_onleaveedit");



                        if($(mw.currentDragMouseOver).hasClass("edit")){

                           if(position=='top'){
                                $(mw.currentDragMouseOver).prepend(mw.dragCurrent);
                           }
                           else if(position=='bottom'){
                               $(mw.currentDragMouseOver).append(mw.dragCurrent);
                           }

                          return false;
                        }

                        if($(mw.currentDragMouseOver).hasClass("mw-free-element")){
                              $(mw.currentDragMouseOver).append(mw.dragCurrent);
                              $(window).trigger("onFreeEnter", mw.currentDragMouseOver);
                          return false;
                        }
                        if(mw.currentDragMouseOver==null || (mw.currentDragMouseOver.id === mw.dragCurrent.id)){
                           $(mw.dragCurrent).visibilityDefault().removeClass("mw_drag_current");
                        }
                        else{

                            var hovered =  $(mw.currentDragMouseOver);
                            if(mw.dragCurrent.tagName.toLowerCase()=='li'){
                               mw.dragCurrent = $(mw.dragCurrent).clone(true);
                               $(mw.dragCurrent).removeAttr("id");
                            }
                            if(hovered.hasClass("empty-element")){

                               hovered.before(mw.dragCurrent);
                               $(mw.dragCurrent).removeClass("mw_drag_float");
                               $(mw.dragCurrent).removeClass("mw_drag_float_right");
                            }
                            else{
                                  if(position=='top'){
                                     $(mw.dragCurrent).removeClass("mw_drag_float");
                                     $(mw.dragCurrent).removeClass("mw_drag_float_right");
                                     hovered.removeClass("mw_drag_float");
                                     if(hovered.hasClass("edit")){
                                        hovered.append(mw.dragCurrent);
                                     }
                                     else{
                                         if(hovered.prev(".mw-sorthandle").length==0){//if is NOT the first child ??
                                            hovered.before(mw.dragCurrent);
                                         }
                                         else{
                                           var parent = hovered.parent();
                                           if(parent.hasClass("edit")){
                                              parent.append(mw.dragCurrent);
                                           }
                                           else{
                                             if(!$(mw.dragCurrent).hasClass("mw_pdrag")){
                                                parent.before(mw.dragCurrent);
                                             }
                                             else{
                                                parent.prepend(mw.dragCurrent);
                                             }

                                           }
                                         }
                                     }
                                  }
                                  else if(position=='bottom'){
                                    $(mw.dragCurrent).removeClass("mw_drag_float");
                                    $(mw.dragCurrent).removeClass("mw_drag_float_right");
                                     hovered.removeClass("mw_drag_float");
                                     if(hovered.hasClass("edit")){
                                        hovered.prepend(mw.dragCurrent);
                                     }
                                     else{
                                         if(hovered.next().length==0){  //if is last child
                                            var parent = hovered.parent();
                                            if(parent.hasClass("edit")){
                                                parent.prepend(mw.dragCurrent);
                                             }
                                             else{
                                                if(!$(mw.dragCurrent).hasClass("mw_pdrag")){
                                                   parent.after(mw.dragCurrent);
                                                }
                                                else{parent.append(mw.dragCurrent); }

                                             }
                                         }
                                         else{
                                            hovered.after(mw.dragCurrent);
                                         }
                                     }
                                     $(mw.dragCurrent).addClass("clear");
                                  }
                                  else if(position=='left'){
                                    $(mw.dragCurrent).removeClass("clear");

                                    //hovered.before(mw.dragCurrent);

                                    var row = mwd.createElement('div');
                                    row.className = 'mw-row';
                                    row.id = "row_" + mw.random();
                                    row.innerHTML = "<div class='mw-col temp_column' style='width:50%'></div><div class='mw-col' style='width:50%'></div>";
                                    hovered.before(row);
                                    hovered.addClass("element");

                                    $(row).find(".mw-col").eq(0).append(mw.dragCurrent).append('<div contenteditable="false" class="empty-element" id="mw-placeholder-'+mw.random()+'"><a class="delete_column" href="javascript:;" onclick="mw.delete_column(this);">Delete</a></div>');
                                    $(row).find(".mw-col").eq(1).append(hovered).append('<div contenteditable="false" class="empty-element" id="mw-placeholder-'+mw.random()+'"><a class="delete_column" href="javascript:;" onclick="mw.delete_column(this);">Delete</a></div>');

                                    if(hovered.parent().hasClass("temp_column") && $(mw.dragCurrent).parent().hasClass("temp_column")){
                                         setTimeout(function(){
                                              mw.drag.fix_placeholders(true);
                                         }, 200)
                                    }

                                  }
                                  else if(position=='right'){


                                          var row = mwd.createElement('div');
                                          row.className = 'mw-row';
                                          row.id = "row_" + mw.random();
                                          row.innerHTML = "<div class='mw-col temp_column' style='width:50%'></div><div class='mw-col temp_column' style='width:50%'></div>";
                                          hovered.before(row);
                                          hovered.addClass("element");

                                          $(row).find(".mw-col").eq(0).append(hovered).append('<div contenteditable="false" class="empty-element" id="mw-placeholder-'+mw.random()+'"><a class="delete_column" href="javascript:;" onclick="mw.delete_column(this);">Delete</a></div>');
                                          $(row).find(".mw-col").eq(1).append(mw.dragCurrent).append('<div contenteditable="false" class="empty-element" id="mw-placeholder-'+mw.random()+'"><a class="delete_column" href="javascript:;" onclick="mw.delete_column(this);">Delete</a></div>');

                                          if(hovered.parent().hasClass("temp_column") && $(mw.dragCurrent).parent().hasClass("temp_column")){
                                            setTimeout(function(){
                                              mw.drag.fix_placeholders(true);
                                            }, 200)
                                          }
                                  }
                            }

                            if(curr_prev.length==0 && curr_next.hasClass("empty-element") && curr_parent.hasClass("temp_column") && !hovered.hasClass("empty-element")){
                                 var row = curr_parent.parents(".mw-row").eq(0);
                                 curr_parent.remove();
                                 row.find(".empty-element").remove();
                                 row.replaceWith(row.find(".mw-col").html());
                            }

                        }
                    if(mw.have_new_items == true){
                        mw.drag.load_new_modules();
                    }
                    $(mw.dragCurrent).show();
                    mw.drag.fixes();
                    setTimeout(function(){mw.drag.fix_placeholders();}, 40)
                    mw.resizable_columns();
                    mw.dropable.hide();

                    if(!$(mw.dragCurrent).parent().hasClass("element")){
                      $(mw.dragCurrent).addClass("element");
                    }

                    $(mw.dragCurrent).css({
                       width:"",
                       height:"",
                    });

					event.stopPropagation();

                    $(".currentDragMouseOver").removeClass("currentDragMouseOver");
                    mw.currentDragMouseOver = null;
				}, 77);
			}





		});
        }//toremove
	},
	/**
	 * Various fixes
	 *
	 * @example mw.drag.fixes()
	 */
	fixes: function () {
		$(".mw-col, .element, .mw-row", '.edit').height('auto');
        $(mw.dragCurrent).css({
          top:'',
          left:''
        });
		//$(mw.dragCurrent).removeAttr('style');
		//$(".element", '.edit').removeAttr('style');
		$(".mw-col", '.edit').each(function () {
			var el = $(this);
			if (el.children().length == 0 || (el.children('.empty-element').length > 0) || el.children('.ui-draggable-dragging').length > 0) {
				if (el.height() < el.parent().height()) {
					el.height(el.parent().height());
				}
                else {
					el.height('auto');
				}
			}
			else {
				el.children('.empty-element').height('auto');
				el.height('auto');
				el.parents('.mw-row:first').height('auto')
			}
		});
	},
    /**
	 * fix_placeholders in the layout
	 *
	 * @example mw.drag.fix_placeholders(isHard , selector)
	 */
    fix_placeholders:function(isHard, selector){
      var selector = selector || '.mw-row';
      if(isHard){ //append the empty elements
       $(selector).each(function(){
          var el = $(this);
          el.children("div.mw-col").each(function(){
            var the_empty_child = $(this).children("div.empty-element");
            if(the_empty_child.length==0){
              $(this).append('<div contenteditable="false" class="empty-element" id="mw-placeholder-'+mw.random()+'"><a class="delete_column" href="javascript:;" onclick="mw.delete_column(this);">Delete</a></div>');
              var the_empty_child = $(this).children("div.empty-element");
            }
          });
        });
      }
      //scale the empty elements
      $("div.empty-element").css({position:'absolute'});
      $("div.empty-element").parent().height('auto');
      $("div.empty-element").each(function(){
        var el = $(this);
        var the_row_height = el.parents(".mw-row").eq(0).height();
        var the_column_height = el.parent().height();
        el.css({height:the_row_height-the_column_height, position:'relative'});
      });
    },






	/**
	 * Removes contentEditable for ALL elements
	 *
	 * @example mw.drag.edit_remove();
	 */
	edit_remove: function () {

   //	   	mw.$('.edit [contenteditable]').removeAttr("contenteditable");

	},


    /**
     * Put onchange for contenteditable
	 * One call of this function fixes all ContentEditable elements in the page to have onchange event.
	 *
	 * @example mw.drag.fix_onChange_editable_elements();
	 */
    fix_onChange_editable_elements : function(el)   {

                   //     return false;

    var el = el || '[contenteditable]';


      mw.$(el).bind('focus', function() {
          var $this = $(this);
          $this.data('before', $this.html());
          return $this;
      })
      .bind('blur keyup paste', function() {
          var el = $(this);
          if (el.data('before') !== $this.html()) {
              el.data('before', $this.html());
              el.trigger('change');
          }
          return $this;
      });
    },




	/**
	 * Scans for new dropped modules and loads them
	 *
	 * @example mw.drag.load_new_modules()
	 * @return void
	 */
	load_new_modules: function (callback) {
        var need_re_init = false;
		$(".module-item", '.edit').each(function (c) {
                mw._({
                  selector:this,
                  done:function(){

                  }
                }, true);
			need_re_init = true;
		});
        if(mw.have_new_items == true){
            need_re_init = true;
        }
		if (need_re_init == true) {
			if (!mw.isDrag) {
                if (typeof callback === 'function') {
    				callback.call(this);
				}
			}
		}
        mw.have_new_items = false;
	},



module_settings: function() {
    var curr = $("#mw_handle_module").data("curr");
    var attributes = {};
    $.each(curr.attributes, function(index, attr) {
      attributes[attr.name] = attr.value;
    });


    data1 = attributes
  //  data1.view = 'admin';
  if(data1['data-type'] != undefined){
	 // alert(1);
	 data1['data-type'] = data1['data-type']+'/admin';
  }
  
    if(data1['type'] != undefined){
	 // alert(1);
	 data1['type'] = data1['type']+'/admin';
  }
  
  if(data1.class != undefined){
	  delete(data1.class);
  }

  if(data1.style != undefined){
	  delete(data1.style);
  }
  if(data1.contenteditable != undefined){
	  delete(data1.contenteditable);
  }
	data1.live_edit = 'true';
	data1.view = 'admin';
	//data1.no_wrap = '1';


  var src = mw.settings.site_url + "api/module?"+json2url(data1);



  var modal = mw.tools.modal.frame({
    url:src,
    width:432,
    title:$(curr).dataset('type'),
    callback:function(){
        mw.drag.ModuleSettingsPopupLoaded(this.main[0].id);
        $(this.container).attr('data-settings-for-module', curr.id);
    }
  });

  return modal;

  },

/**
   * Loads module settings in lightbox
   *
   * @method mw.drag.module_settings()
   */
  module_settings_old: function() {
    var curr = $("#mw_handle_module").data("curr");
    var attributes = {};
    $.each(curr.attributes, function(index, attr) {
      attributes[attr.name] = attr.value;
    });


    data1 = attributes
  //  data1.view = 'admin';
  if(data1['data-type'] != undefined){
	 // alert(1);
	 data1['data-type'] = data1['data-type']+'/admin';
  }
  
    if(data1['type'] != undefined){
	 // alert(1);
	 data1['type'] = data1['type']+'';
  }
  
  
  
	data1.live_edit = 'true';
	data1.view = 'admin';
	data1.no_wrap = '1';
    mw.tools.modal.init({
    	html:"",
    	width:600,
    	height:450,
    	callback:function() {
          var id = this.main[0].id;
          $(this.container).load(mw.settings.site_url + "api/module", data1, function(){
            mw.drag.ModuleSettingsPopupLoaded(id);
    		mw.simpletabs(this.container);
          });
          $(this.container).attr('data-settings-for-module', curr.id);
        }
	});

  },

  ModuleSettingsPopupLoaded : function(id){

   mw.$("#"+id+" .mw_option_field").bind("change blur", function () {

                var refresh_modules11 = $(this).attr('data-refresh');

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).attr('data-reload');
				}

				if(refresh_modules11 == undefined){
				    var refresh_modules11 = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module');
                    var refresh_modules11 = '#'+refresh_modules11;
				}

				 var mname = $(this).parents('.module:first').attr('data-type');


				var og = $(this).attr('data-module-id');
				if(og == undefined){
				    var og = $(this).parents('.mw_modal_container:first').attr('data-settings-for-module')
				}


                 if(this.type==='checkbox'){
                   var val = '';
                   var items = mw.$('input[name="'+this.name+'"]');
                   for(var i=0; i<items.length; i++){
                       var _val = items[i].value;
                       var val = items[i].checked==true ? (val==='' ? _val: val+", "+_val) : val;

                   }
                 }
                 else{val = this.value }





				var o_data = {
                    option_key: $(this).attr('name'),
                    option_group: og,
                    option_value: val
                   // chkboxes:checkboxes_obj
                }
				if(mname != undefined){
					o_data.module = mname;
				}


                $.ajax({

                    type: "POST",
                    url: mw.settings.site_url+"api/save_option",
                    data: o_data,
                    success: function () {
                        if (refresh_modules11 != undefined && refresh_modules11 != '') {
                            refresh_modules11 = refresh_modules11.toString()
 
                            if (window.mw != undefined) {
                                if (window.mw.reload_module != undefined) {
                                    window.mw.reload_module(refresh_modules11);
									window.mw.reload_module('#'+refresh_modules11);
                                }
                            }

                        }

                        //  $(this).addClass("done");
                    }
                });
            });


  },


	/**
	 * Loads the module in the given dom element by the $update_element selector .
	 *
	 * @example mw.drag.load_module('user_login', '#login_box')
	 * @param $module_name
	 * @param $update_element
	 * @return void
	 */
	load_module: function ($module_name, $update_element) {
		var attributes = {};
		attributes.module = $module_name;
		var url1 = mw.settings.site_url + 'api/module';
		$($update_element).load_modules(url1, attributes, function () {
			window.mw_sortables_created = false;
		});

	},






  /**
   * Deletes element by id or selector
   *
   * @method mw.edit.delete_element(idobj)
   * @param Element id or selector
   */
  delete_element: function(idobj) {
    var id = mw.is.obj(idobj) ? $(idobj).data("curr").id : idobj;
    if (confirm(mw.settings.sorthandle_delete_confirmation_text)) {
      if (id == "") {
        id = mw.settings.element_id;
      }
      id.contains("#") ? $(id).remove() : mw.$('#' + id).remove();
      $(mw.handle_module).css({left:'', top:''});
	  mw.drag.fix_placeholders(true);
    }
  },

    /**
	 * Creates columns of given row id
	 *
	 * @method mw.edit.create_columns(selector, $numcols)
	 * @param selector - the id of the row element
	 * @param $numcols - number of columns required
	 */

  create_columns: function (selector, $numcols) {

        if(!$(selector).hasClass("active")){

        var id = $(mw.handle_row).data("curr").id;
        $(mw.handle_row).children(".mw-col").removeClass("temp_column");
        $(mw.handle_row).find("a").removeClass("active");
        $(selector).addClass("active")
         var $el_id = id!=''?id:mw.settings.mw-row_id;

			mw.settings.sortables_created = false;
			var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").length;

			if ($numcols == 0) {
				$numcols = 1;
			}
			$numcols = parseInt($numcols);
			if ($exisintg_num == 0) {
				$exisintg_num = 1;
			}
			if ($numcols != $exisintg_num) {
				if ($numcols > $exisintg_num) {  //more columns
					for (i = $exisintg_num; i < $numcols; i++) {
                        var new_col = mwd.createElement('div');
                        new_col.className = 'mw-col';
                        mw.$('#' + $el_id).append(new_col);
                        mw.drag.fix_placeholders(true, '#' + $el_id);
					}
                    mw.resizable_columns();
				}
				else {  //less columns
					var $cols_to_remove = $exisintg_num - $numcols;
					if ($cols_to_remove > 0) {
                        var last_after_remove = mw.$('#' + $el_id).children(".mw-col").eq($numcols-1);
                        var elements_to_clone = mw.$('#' + $el_id).children(".mw-col:gt("+($numcols-1)+")");
                        $(elements_to_clone).each(function(){
                            var el = $(this).children(".element, .module, .mw-row, .mw-layout-holder");
                            last_after_remove.find(".empty-element").before(el);
                           $("#"+this.id).remove();
                        });
                        last_after_remove.resizable("destroy");
                        mw.$('#' + $el_id).children(".empty-element").remove();
                        mw.drag.fix_placeholders(true, '#' + $el_id);
					}
				}

				var $exisintg_num = mw.$('#' + $el_id).children(".mw-col").size();
				var $eq_w = 100 / $exisintg_num;
				var $eq_w1 = $eq_w;
				mw.$('#' + $el_id).children(".mw-col").width($eq_w1 + '%');
			}
        }
	},


  /**
   * Saves the page
   *
   * @method mw.drag.save()
   */


  save: function() {



  //Clean the code before send

  mw.$('.element-current').removeClass('element-current');
  mw.$('.element-active').removeClass('element-active');
  mw.$('.disable-resize').removeClass('disable-resize');
  mw.$('.empty-element').remove();
  mw.$('.empty-element').remove();
  mw.$('.edit .ui-resizable-handle').remove();
  mw.tools.classNamespaceDelete('all', 'ui-');
  //end cleaning the code

    var edits = mw.$(".edit.changed");

	// var edits2 = $('.edit *[rel]');
/*mw.log(edits2);
 	if(edits2 != undefined){
		var edits = $.merge(edits, edits2);
		
	}*/
	
	

    var master = {};

    edits.each(function(j) {
      j++;
      var el = $(this);
      content = el.html();

      var attr_obj = {};
      var attrs = el.get(0).attributes;
      if(attrs.length>0){
        for (var i = 0; i < attrs.length; i++) {
          temp1 = attrs[i].nodeName;
          temp2 = attrs[i].nodeValue;
          attr_obj[temp1] = temp2;
        }
      }
      var obj = {
        attributes: attr_obj,
        html: content
      }
      var objX = "field_data_" + j;
      var arr1 = [{
        "attributes": attr_obj
      }, {
        "html": (content)
      }];
      master[objX] = obj;
    });

      $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/save_edit',
        data: master,
        datatype: "json",
        async: true,
        beforeSend: function() {

        },
        success: function(data) {
          mw.history.init();
        }
      });

  }
}



mw.pcWidthExtend = function(selector, howMuch, cache, final, len){
  if(final<3){
    selector.eq(len-1).width((cache[len-1]+final)+"%");
  }
  else{
     var final = 0;
     selector.each(function(i){
           var el = $(this);
           cache[i] = cache[i] + howMuch;
           final += cache[i];
           el.width(cache[i]+"%");
     });
     if(final<100){
       mw.pcWidthExtend(selector, howMuch, cache, final, len);
     }
     
  }

}


mw.px2pc = function(row){




    var cache = [];
    var row = $(row);
    var width = row.width();
    var cols = row.children(".mw-col");
    var len = cols.length;
    cols.each(function(){
        var el = $(this);
        var w = ((/*Math.floor*/(el.width() / width * 100)));
        cache.push(w);
        el.css({
        	width:w+"%"
        });
    });
    //check them after
    mwcsum = 0;
    for(var x=0; x<len; x++){
        mwcsum+=cache[x];
    }
    var final = 100-mwcsum;
    if(final != 0){
        mw.pcWidthExtend(cols, final/len, cache, final, len);
    }
}




mw.scale_cols = function(){
  $(".mw-row").each(function(){
    var el = $(this);
    var cols = el.children(".mw-col");
    var len = cols.length;
    var width = el.width();
    var w = 0;
    cols.each(function(){
        w+=$(this).width();
    });
    var res = width-w;
    if(res>6){
        var each = res/len;
        cols.each(function(){
            $(this).width($(this).width()+each);
        });
        mw.px2pc(this);
    }
  });
}


mw.delete_column = function(which){
  if(confirm(mw.settings.sorthandle_delete_confirmation_text)){
     var row =  $(which).parents(".mw-row").eq(0);
     var col =  $(which).parents(".mw-col").eq(0);
     if(col.next(".mw-col").length==0){
        col.prev(".mw-col").resizable("destroy");
     }
     col.remove();
     if(row.find(".mw-col").length==0){
       row.remove();
     }
     else{
        mw.px2pc(row[0]);
     }
  }
}



/**
 * Makes resizable columns
 *
 * @example mw.resizable_columns()
 */
mw.resizable_columns = function () {

$(".edit .mw-row").each(function(){
    mw.px2pc(this);
});


	mw.$('.edit .mw-col').not(".ui-resizable").each(function () {

		$el_id_column = $(this).attr('id');
		if ($el_id_column == undefined || $el_id_column == 'undefined') {
			$el_id_column = 'mw-column-' + mw.random();
			$(this).attr('id', $el_id_column);
		}




		$is_done = $(this).hasClass('ui-resizable')
		$ds = mw.settings.drag_started;
		$is_done = false;
		if ($is_done == false) {

			$inner_column = $(this).children(".mw-col:first");
			$prow = $(this).parent('.mw-row').attr('id');
			$no_next = false;


			$also = $(this).next(".mw-col");
			$also_check_exist = $also.size();
			if ($also_check_exist == 0) {
				$no_next = true;
				$also = $(this).prev(".mw-col");
			}



			$also_el_id_column = $also.attr('id');
			if ($also_el_id_column == undefined || $also_el_id_column == 'undefined' || $also_el_id_column == '') {
				$also_el_id_column = 'mw-column-' + mw.random();
				$also.attr('id', $also_el_id_column);
			}
			$also_reverse_id = $also_el_id_column;

			$also_inner_items = $inner_column.attr('id');




		  //$(this).parents(".mw-col").resizable("destroy");
		  //$(this).children(".mw-col").resizable("destroy");

			if ($no_next == false) {
				$handles = 'e'
			}
			else {
				$handles = 'none'
			}


			if ($no_next == false ) {

				$last_c_w = $(this).parent('.mw-row').children('.mw-col').last().width();
				$row_max_w = $(this).parent('.mw-row').width();


				$(this).attr("data-also-rezise-item", $also_reverse_id);
                mw.global_resizes = {
                  next:'',
                  sum:0
                }



				$(this).resizable({
					handles: $handles,
					ghost:false,
					containment: "parent",
                    greedy:true,
					cancel: ".mw-sorthandle",
					minWidth: 150,

					resize: function (event, ui) {
						mw.global_resizes.next.width(Math.floor(mw.global_resizes.sum-ui.size.width-10));
                        if(mw.global_resizes.next.width()<151){
                           $(this).resizable("option", "maxWidth", ui.size.width);
                        }
                        mw.settings.resize_started = true;
                        var w = $(this).width();

                        $(this).find("img").each(function(){

                          $(this).width() <= w ? this.style.height = 'auto' : '';
                        });
                        var nw =mw.global_resizes.next.width();
                        mw.global_resizes.next.find("img").each(function(){
                          $(this).width() <= w ? this.style.height = 'auto' : '';
                        });
					},
					create: function (event, ui) {
						var el = $(this);
						el.find(".ui-resizable-e:first").append('<span class="resize_arrows"></span>');
						el.mousemove(function (event) {
							el.children(".ui-resizable-e").find(".resize_arrows:first").css({
								"top": (event.pageY - el.offset().top) + "px"
							});
						});
                        
                        mw.px2pc(mw.tools.firstParentWithClass(this, 'mw-row'));
					},
					start: function (event, ui) {
					  $(this).resizable("option", "maxWidth", 9999);
						$(".mw-col", '.edit').each(function () {
							$(this).removeClass('selected');
						});

						mw.global_resizes.next = $(this).next().length>0?$(this).next():$(this).prev();

						mw.global_resizes.sum = ui.size.width + mw.global_resizes.next.width();

						$r = $(this).parent('.mw-row');

						$row_w = $r.width();
						mw.resizable_row_width = $row_w;


						ui.element.addClass('selected');
						mw.settings.resize_started = true;
                        $(this).parent().addClass('mw_resizing');
                        $(mw.image_resizer).removeClass("active");

					},
					stop: function (event, ui) {
					   $(this).parent().removeClass('mw_resizing');
 						mw.settings.resize_started = false;
						mw.drag.fixes();
						mw.drag.fix_placeholders();

                        mw.px2pc($(this).parents(".mw-row")[0]);

                       //mw.scale_cols();
					}
				});
			}
		}

	});
}





mw.drop_regions = {

  dropTimeout:null,
  global_drop_is_in_region:false,
  which : 'none',

  create:function(element){
    var el = $(element);
    var height = el.height();
    var width = el.width();
    var offset = el.offset();
    var region_left = {
      l:offset.left,
      r:offset.left+width*0.1,
      t:offset.top,
      b:offset.top+height
    }
    var region_right = {
      l:offset.left+width-width*0.1,
      r:offset.left+width,
      t:offset.top,
      b:offset.top+height
    }
    return {
        left:region_left,
        right:region_right
    }
  },
  is_in_region:function(regions, event){
    var l = regions.left;
    var r = regions.right;
    var mx = event.pageX;
    var my = event.pageY;
    if(mx>l.l && mx<l.r && my>l.t && my<l.b){
        return 'left';
    }
    else if(mx>r.l && mx<r.r && my>r.t && my<r.b){
      return 'right';
    }
    else{return 'none'}
  },
  init:function(element, event, callback){
    if(mw.drop_regions.dropTimeout==null){
        mw.drop_regions.dropTimeout = setTimeout(function(){
            var regions = mw.drop_regions.create(element);
            var is_in_region = mw.drop_regions.is_in_region(regions, event);
            if(is_in_region=='left'){
               callback.call(this, 'left');
               mw.drop_regions.global_drop_is_in_region = true;
               mw.drop_regions.which = 'left';
            }
            else if(is_in_region=='right'){
               callback.call(this, 'right');
               mw.drop_regions.global_drop_is_in_region = true;
               mw.drop_regions.which = 'right';
            }
            else{
              mw.drop_regions.global_drop_is_in_region = false;
               mw.drop_regions.which = 'none';
            }
            mw.drop_regions.dropTimeout = null;
        }, 37);
    }
  }
}









mw.history = {

	/**
	 * Microweber history  class
	 *
	 * @class mw.history
	 */

	/**
	 * Loads the history module
	 *
	 * @method mw.history.init()
	 */
	init: function () {
		data = {}
		data.module = 'admin/mics/edit_block_history';
		data.page_id = mw.settings.page_id;
		data.post_id = mw.settings.post_id;
		data.category_id = mw.settings.category_id;
		data.for_url = document.location.href;
		mw.$('#mw-history-panel').load(mw.settings.site_url + 'api/module', data);
	},

	/**
	 * Loads the history from file
	 *
	 * @method mw.history.load()
	 */
	load: function ($base64fle) {
		if ($base64fle != undefined) {
			$.ajax({
				type: 'POST',
				url: mw.settings.site_url + "api/load_history_file",
				data: {
					history_file: $base64fle
				},
				dataType: "json",
				success: function (data) {
					$.each(data, function (i, d) {
						var $what_is_the_content = ''
						//if(this.page_element_id != un
						
						
						if (window.console && window.console.log) {
							window.console.log('  Replacing from history - element id: ' + this.page_element_id + '  - Content: ' + this.page_element_content);
						}
						$("#" + this.page_element_id).html(this.page_element_content);
					});
				}
			})
		}
	}
}



mw.drag.story_active = -1;

mw.drag.stories = [];

mw.drag.story = {
  forward:function(){

  },
  back:function(){
    var curr = mw.drag.stories[mw.drag.story_active];
    if(mw.is.defined(curr)){
      $("#"+curr.pos).replaceWith(curr.who);
      mw.drag.story_active-=1;
    }
  }
}













