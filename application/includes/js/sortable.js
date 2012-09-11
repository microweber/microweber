

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

  }
}


$(document).ready(function(){
   mw.drag.create();
});

mw.isDragItem = function(obj){
  var items = /^(blockquote|center|dir|fieldset|form|h[1-6]|hr|menu|ul|ol|dl|p|pre|table)$/i;
  return items.test(obj.nodeName);
}


mw.drag = {
	create: function () {
         mw.top_half = false;


         $(document.body).mousemove(function(event){

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
                    mw.bigEditor.animate({opacity:1}, 200);
                }
           }

           mw.mouse = {
             x:event.pageX,
             y:event.pageY
           }

           mw.mm_target = event.target;
           mw.$mm_target = $(mw.mm_target);

           if(!mw.isDrag){
               if(mw.mouse.x%2==0){ //not on every pixel
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
                   if(mw.$mm_target.hasClass("row")){
                     $(window).trigger("onRowOver", mw.mm_target);
                   }
                   else if(mw.$mm_target.parents(".row").length>0){
                     $(window).trigger("onRowOver", mw.$mm_target.parents(".row:first")[0]);
                   }
                   else if(mw.mm_target.id!='mw_handle_row' && mw.$mm_target.parents("#mw_handle_row").length==0){
                     $(window).trigger("onRowLeave", mw.mm_target);
                   }

                   //trigger on item
                   if((mw.isDragItem(mw.mm_target) && mw.$mm_target.parent().hasClass("element")) || mw.mm_target.className.contains('mw_item')){
                     $(window).trigger("onItemOver", mw.mm_target);
                     mw.$mm_target.addClass("mw_item");
                   }
                   else if(mw.mm_target.id!='items_handle' && mw.$mm_target.parents("#items_handle").length==0){
                     $(window).trigger("onItemLeave", mw.mm_target);
                   }
                   if(mw.$mm_target.parents(".edit,.mw_master_handle").length==0){
                     if(!mw.$mm_target.hasClass(".edit") && !mw.$mm_target.hasClass("mw_master_handle")){
                          $(window).trigger("onAllLeave", mw.mm_target);
                     }
                   }

               }
           }
           else{
             if(mw.$mm_target.hasClass("element") || mw.$mm_target.hasClass("empty-element") || mw.$mm_target.parents(".element").length>0){
               if(!mw.$mm_target.hasClass("ui-draggable-dragging") && mw.$mm_target.parents(".ui-draggable-dragging").length==0){
                   mw.currentDragMouseOver = mw.mm_target;
               }
             }


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

           if(mw.isDrag && mw.currentDragMouseOver!=null  && ( $(mw.currentDragMouseOver).parents(".module").length==0)){

            var el = $(mw.currentDragMouseOver);
            $(".ui-draggable-dragging").show();
            if(el.hasClass("ui-draggable-dragging") || el.parents(".ui-draggable-dragging").length>0){
              // check if mouse is over the dragging element
              return false;
            }

            var body = $(this);

            var offset = el.offset();
            var height = el.outerHeight();
            var width = el.width();


            if(mw.drop_regions.global_drop_is_in_region && $(mw.dragCurrent).hasClass("element-image")){

              mw.dropable.addClass("mw_dropable_vertical");
              if(mw.drop_regions.which=='left'){
                mw.dropable.data("position", 'left');

                 mw.dropable.css({
                      top:offset.top,
                      height:height,
                      left:offset.left,
                      width:2
                 });
              }
              else{
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
            else{
                mw.dropable.removeClass("mw_dropable_vertical");
                mw.dropable.removeClass("mw_dropable_arr_rigt");
                if(event.pageY > offset.top+(height/2)){  //is on the bottom part

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
                else{
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
            }

            if(el.hasClass("element") || el.hasClass("row") || el.parents(".row").length>0 || el.parents(".element").length>0){
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

        $(document.body).mouseup(function(event){
        	if(mw.isDrag && mw.dropable.is(":hidden")){
        		$(".ui-draggable-dragging").css({top:0,left:0});
        	}
            $(this).removeClass("not-allowed");
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
        });
        $(window).bind("onItemOver", function(a, element){
          var el = $(element);
          var o = el.offset();
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
            $("#mw_handle_row,#mw_handle_module,#mw_handle_element,#items_handle").css({
              top:"",
              left:""
            })
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
            $(mw.handle_item).css({
              top:"",
              left:""
            });
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
                  $(mw.dragCurrent).invisible();
                  $(window).trigger("onAllLeave");
               },
               stop:function(){
                  mw.isDrag = false;
               }
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
                  $(mw.dragCurrent).invisible();
                  $(window).trigger("onAllLeave");
               },
               stop:function(){
                  mw.isDrag = false;
               }
            });
            $(mw.handle_row).draggable({
               handle:".column_separator_title",
               cursorAt:{
                 top:-30
               },
               start:function(){
                  mw.isDrag = true;
                  var curr = $(mw.handle_row).data("curr");
                  mw.dragCurrent = curr;
                  $(mw.dragCurrent).invisible().addClass("mw_drag_current");
                  $(window).trigger("onAllLeave");
               },
               stop:function(){
                  mw.isDrag = false;
               }
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
               },
               stop:function(){
                  mw.isDrag = false;
               }
            });

            $(".modules-list li").draggable({
                helper:'clone',
                start:function(){
                    mw.isDrag = true;
                    var clone = $(this).clone(true).attr("id", "module-"+mw.random());
                    mw.dragCurrent = clone[0];
                },
               stop:function(){
                  mw.isDrag = false;
                  setTimeout(function(){mw.drag.load_new_modules();}, 200);
               }
            });

        }

        mw.drag.the_drop();
	},
    the_drop: function () {
        if(!$(document.body).hasClass("bup")){
          $(document.body).addClass("bup");

		$(document.body).bind("mouseup", function (event) {
		    var target = event.target;
            if($(target).hasClass("element")){
              $(window).trigger("onElementClick", target);
            }
            if($(target).parents(".element").length>0){
              $(window).trigger("onElementClick", $(target).parents(".element")[0]);
            }
			if (mw.isDrag) {
				setTimeout(function () {
                        $(mw.dragCurrent).visibilityDefault().removeClass("mw_drag_current");
                        var position = mw.dropable.data("position");
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

                                    hovered.before(mw.dragCurrent);

                                    setTimeout(function(){
                                       $(mw.dragCurrent).addClass("mw_drag_float");
                                       $(mw.dragCurrent).removeClass("mw_drag_float_right");
                                    }, 73);

                                  }
                                  else if(position=='right'){
                                    $(mw.dragCurrent).removeClass("clear");
                                    $(mw.dragCurrent).removeClass("mw_drag_float");
                                    $(mw.dragCurrent).addClass("mw_drag_float_right");

                                    hovered.before(mw.dragCurrent);

                                    setTimeout(function(){
                                        hovered.removeClass("mw_drag_float");
                                    }, 73);
                                  }
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
				}, 37);
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
		$(".column, .element, .row", '.edit').height('auto');
        $(mw.dragCurrent).css({
          top:'',
          left:''
        });
		//$(mw.dragCurrent).removeAttr('style');
		//$(".element", '.edit').removeAttr('style');
		$(".column", '.edit').each(function () {
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
				el.parents('.row:first').height('auto')
			}
		});
	},
    /**
	 * fix_placeholders in the layout
	 *
	 * @example mw.drag.fix_placeholders(isHard , selector)
	 */
    fix_placeholders:function(isHard, selector){
      var selector = selector || '.row';
      if(isHard){ //append the empty elements
       $(selector).each(function(){
          var el = $(this);
          el.children("div.column").each(function(){
            var the_empty_child = $(this).children("div.empty-element");
            if(the_empty_child.length==0){
              $(this).append('<div class="empty-element" id="mw-placeholder-'+mw.random()+'"></div>');
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
        var the_row_height = el.parents(".row").eq(0).height();
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

	   	$('*[contenteditable]', '.edit').removeAttr("contenteditable");

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


      $(el).bind('focus', function() {
    var $this = $(this);
    $this.data('before', $this.html());
    return $this;
}).bind('blur keyup paste', function() {
    var $this = $(this);
    if ($this.data('before') !== $this.html()) {
        $this.data('before', $this.html());
        $this.trigger('change');
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
        $need_re_init = false;
		$(".module-item", '.edit').each(function (c) {
                mw._({
                  selector:this,
                  done:function(){

                  }
                })
			$need_re_init = true;
		});
        if(mw.have_new_items == true){
            $need_re_init = true;
        }
		if ($need_re_init == true) {
			if (!mw.isDrag) {
                if (typeof callback === 'function') {
    				callback.call(this);
				}
			}
		}
        mw.have_new_items = false;
	},




/**
   * Loads module settings in lightbox
   *
   * @method mw.drag.module_settings()
   */
  module_settings: function($module_id) {
    $module = $('#' + $module_id);
    var attributes = {};
    $.each($module[0].attributes, function(index, attr) {
      attributes[attr.name] = attr.value;
    });


    data1 = attributes
    data1.view = 'admin';

    mw.tools.modal.init({
	html:"",
	width:600, 
	height:450, 
	callback:function() {
      $(this.container).load(mw.settings.site_url + "api/module", data1);
      $(this.container).dataset('settings-for-module', $module_id);
    }
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
    var id = mw.is.obj(idobj) ? $(idobj).dataset("delete") : idobj;
    if (confirm(mw.settings.sorthandle_delete_confirmation_text)) {
      if (id == "") {
        id = mw.settings.element_id;
      }
      id.contains("#") ? $(id).remove() : $('#' + id).remove();
      $(mw.handle_module).css({left:'', top:''});
	  mw.drag.fix_placeholders(true);
    }
  },

	
	
  /**
   * Saves the page
   *
   * @method mw.drag.save()
   */
  save: function() {

    $("#mw-saving-loader").fadeIn();


    $(".mw_non_sortable", '.edit').removeClass('mw_non_sortable');

    $(".mw-sorthandle-parent-outline", '.edit').removeClass('mw-sorthandle-parent-outline');

    $(".mw-sorthandle", '.edit').remove();
    $('.ui-resizable-handle', '.edit').remove();
    $('.ui-draggable', '.edit').removeClass("ui-draggable");
    $('.ui-resizable', '.edit').removeClass("ui-resizable");
    $('.column', '.edit').removeClass("selected");




    var custom_styles = new Array();
    var regEx = /^mw-style/;
    var elm = $(".mw-custom-style", '.edit');
    $save_custom_styles = false
    elm.each(function(j) {
      var classes = $(this).attr('class').split(/\s+/);
      //it will return  foo1, foo2, foo3, foo4
      for (var i = 0; i < classes.length; i++) {
        var className = classes[i];

        if (className.match(regEx)) {
          $save_custom_styles = true
          custom_styles.push(className);
          //elm.removeClass(className);
        }
      }
    });

    if ($save_custom_styles == true) {
      custom_styles.unique();
      $styles_join = custom_styles.join(',');
      $sav = {};
      $sav['content_id'] = window.content_id;
      $sav['save_field_content_layout_style'] = $styles_join;
      $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/content/save_field_simple',
        data: $sav,
        async: true
      });
    }

    var master = {};

    $('.edit').each(function(j) {
      j++;
      content = $(this).get(0).innerHTML;
      if (window.no_async == true) {
        $async_save = false;
        window.no_async = false;
      } else {
        $async_save = true;
      }
      var nic_obj = {};
      var attrs = $(this).get(0).attributes;
      for (var i = 0; i < attrs.length; i++) {
        temp1 = attrs[i].nodeName;
        temp2 = attrs[i].nodeValue;
        if ((temp2 != null) && (temp1 != null) && (temp1 != undefined) && (temp2 != undefined)) {
          if ((new String(temp2).indexOf("function(") == -1) && (temp2 != "") && (temp1 != "")) {
            nic_obj[temp1] = temp2;
          }
        }
      }
      var obj = {
        attributes: nic_obj,
        html: content
      }
      var objX = "field_data_" + j;
      var arr1 = [{
        "attributes": nic_obj
      }, {
        "html": (content)
      }];
      master[objX] = obj;
    });
    $emp = false;
    if (!$emp) {
      master_prev = master;
      $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/save_edit',
        data: master,
        datatype: "json",
        async: true,
        beforeSend: function() {
          window.saving = true;
          $("#mw-saving-loader").fadeIn();
        },
        success: function(data) {
          mw.history.init();
          window.saving = false;
          window.mw_sortables_created = false;
          window.mw_drag_started = false;
          $("#mw-saving-loader").fadeOut();
        }
      });
    }
  }
}





mw.px2pc = function(selector){
    $(selector).each(function(){
      var parent = $(this).parents('.row:first');
		$(this).css({
			width: ((($(this).width() / parent.width()) * 100)+"%")
		});
    });
}





/**
 * Makes resizable columns
 *
 * @example mw.resizable_columns()
 */
mw.resizable_columns = function () {



	$('.edit').find('.column').each(function () {

		$el_id_column = $(this).attr('id');
		if ($el_id_column == undefined || $el_id_column == 'undefined') {
			$el_id_column = 'mw-column-' + mw.random();
			$(this).attr('id', $el_id_column);
		}

        mw.px2pc(this);


		$is_done = $(this).hasClass('ui-resizable')
		$ds = mw.settings.drag_started;
		$is_done = false;
		if ($is_done == false) {

			$inner_column = $(this).children(".column:first");
			$prow = $(this).parent('.row').attr('id');
			$no_next = false;


			$also = $(this).next(".column");
			$also_check_exist = $also.size();
			if ($also_check_exist == 0) {
				$no_next = true;
				$also = $(this).prev(".column");
			}



			$also_el_id_column = $also.attr('id');
			if ($also_el_id_column == undefined || $also_el_id_column == 'undefined' || $also_el_id_column == '') {
				$also_el_id_column = 'mw-column-' + mw.random();
				$also.attr('id', $also_el_id_column);
			}
			$also_reverse_id = $also_el_id_column;

			$also_inner_items = $inner_column.attr('id');



		  $(this).parent(".column").resizable("destroy");
		  $(this).children(".column").resizable("destroy");

			if ($no_next == false) {
				$handles = 'e'
			}
			else {
				$handles = 'none'
			}


			if ($no_next == false ) {

				$last_c_w = $(this).parent('.row').children('.column').last().width();
				$row_max_w = $(this).parent('.row').width();


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
					},
					start: function (event, ui) {
					  $(this).resizable("option", "maxWidth", 9999);
						$(".column", '.edit').each(function () {
							$(this).removeClass('selected');
						});

						mw.global_resizes.next = $(this).next().length>0?$(this).next():$(this).prev();

						mw.global_resizes.sum = ui.size.width + mw.global_resizes.next.width();

						$r = $(this).parent('.row');

						$row_w = $r.width();
						mw.resizable_row_width = $row_w;


						ui.element.addClass('selected');
						mw.settings.resize_started = true;
					},
					stop: function (event, ui) {
 						mw.settings.resize_started = false;
						mw.drag.fixes();
						mw.drag.fix_placeholders();

                        mw.px2pc(".column");
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
      r:offset.left+50,
      t:offset.top,
      b:offset.top+height
    }
    var region_right = {
      l:offset.left+width-50,
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
		$('#mw-history-panel').load(mw.settings.site_url + 'api/module', data);
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
				url: mw.settings.site_url + "api/content/load_history_file",
				data: {
					history_file: $base64fle
				},
				dataType: "json",
				success: function (data) {
					$.each(data, function (i, d) {
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

$(window).load(function(){
    $(".element").mousemove(function(event){
      if(mw.isDrag){
        mw.drop_regions.init(this, event, function(region){

        });
      }
     // event.stopPropagation();
  });





});





