

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

           if(!mw.isDrag){

               mw.mm_target = event.target;



               if($(mw.mm_target).hasClass("row") || $(mw.mm_target).parents(".row").length>0){
                     $(".mw-sorthandle").invisible();
                     $(mw.mm_target).find(".mw-sorthandle-row:first").visible();
                     $(mw.mm_target).parents(".row:first").find(".mw-sorthandle-row:first").visible();
               }

               if($(mw.mm_target).hasClass("element") || $(mw.mm_target).parents(".element").length>0){
                     $(mw.mm_target).find(".mw-sorthandle:first").visible();
                     $(mw.mm_target).parents(".element:first").find(".mw-sorthandle:first").visible();
               }

               if(!$(mw.mm_target).hasClass("element") && $(mw.mm_target).parents(".element").length>0){
                 if(mw.mm_target.tagName=='P'){
                   var off = $(mw.mm_target).offset();
                    $(mw.items_handle).css({top:off.top+10,left:off.left+10});
                    $(mw.items_handle).data("target", mw.mm_target);
                 }
                 else{
                   if(!mw.mm_target.id=='items_handle'){
                      $(mw.items_handle).css("top", "-9999px");
                   }

                 }
               }

               if($(mw.mm_target).hasClass("module")){
                 //mw.drag.fix_handles(mw.mm_target);
               }
               if($(mw.mm_target).parents(".module").length>0){
                 //mw.drag.fix_handles($(mw.mm_target).parents(".module").eq(0));
               }

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

	    mw.drag.edit_remove();

		mw.drag.fix_placeholders(true);
		mw.drag.fixes()

        mw.drag.init(".element, .row");
		mw.drag.init(".module-item");


	   	mw.drag.sort(".element > *,.edit,.column > *,.edit > *");

		//mw.drag.sort(".element > *, .element, .edit, .edit > *");    //



        mw.drag.fix_handles();
		mw.resizable_columns();

        $(document.body).mouseup(function(event){
        	if(mw.isDrag && mw.dropable.is(":hidden")){
        		$(".ui-draggable-dragging").css({top:0,left:0});
        	}
            $(this).removeClass("not-allowed");
        });

	},

	init: function (selector, callback) {

        if(!mw.master_drag){
            mw.items_handle = mwd.createElement('div');
            mw.items_handle.id = 'items_handle';
            mwd.body.appendChild(mw.items_handle);
        }


        $(selector).not(".ui-draggable").not(".module .module").each(function(){
            var el = $(this);
            if( el.hasClass("module-item")){
                helper = function(event, ui) {
                    return mw.dragCurrent = $(this).clone(true).appendTo('body').css({'zIndex':5});
                }
            }
            else {
                helper = 'clone'
            }
            el.draggable({
                handle: ".mw-sorthandle-moveit",
            	cursorAt: {
            		top: -30
            	},
                containment:document.body,
				scroll:true,
				scrollSensitivity:100,
            	helper: helper,
            	start: function () {
            	    document.body.style.overflowX='hidden';
            		mw.isDrag = true;
            		mw.dragCurrent = this;
            		mw.drag.edit_remove();
            		$(this).addClass("mw_drag_started");
            		mw.drag.fixes();
                    $(mw.dragCurrent).invisible();
                    setTimeout(function(){
                      $(".ui-draggable-dragging").css({
                        width:$(mw.dragCurrent).width(),
                        height:$(mw.dragCurrent).height(),
                        background:'white'
                      })
                    }, 200);
            	},
            	stop: function (event, ui) {
            	  document.body.style.overflowX='';
            		mw.isDrag = false;
            		$(this).removeClass("mw_drag_started");

            		if ($(mw.dragCurrent).hasClass("module-item")) {
                    mw.have_new_items = true;
                       setTimeout(function () {
                        mw.drag.load_new_modules();
                      }, 300);
            		}
                    else {
                      setTimeout(function () {
                        mw.drag.edit_remove();
                        mw.drag.fix_placeholders();
                      }, 100);
            		}
                 if (typeof callback === 'function') {
            			callback.call(this);
            	 }
                 $(".row").css({marginTop:'0px',marginBottom:'0px'});
            	}
            });

            $(this).mouseleave(function(event){
              $(".mw-sorthandle").invisible();
               event.stopPropagation();
            });
        });

        if(!mw.items_handle){

        mw.items_handle = mwd.createElement('div');
        mw.items_handle.id = 'items_handle';
        mwd.body.appendChild(mw.items_handle);


        $(mw.items_handle).draggable({
            cursorAt:{
              top:-10
            },
            start:function(){
              $(this).css("top", -9999);
              mw.isDrag = true;
              mw.dragCurrent = $(this).data("target");
            },
            stop:function(){
              mw.isDrag = false;
              mw.dragCurrent.style.position = '';
              mw.dragCurrent.style.top = '';
              mw.dragCurrent.style.left = '';
              mw.dragCurrent.style.width = '';
              mw.dragCurrent.style.height = '';
            }
        });
        $(mw.items_handle).mousedown(function(){
            var target = $(this).data("target");
            target.id = 'c'+mw.random();
            $(target).addClass("mw_pdrag");
            var target = $(target);

            $(this).draggable("option", "helper", function(){
              target.css({
                width:target.width(),
                height:target.height()
              });
              return target;
            });
        });
        }

	},


	sort_handles_events: function (selector) {
		if (selector == undefined) {
			selector = '.mw-sorthandle';
		}
		$(selector).unbind('mousedown');
		$(selector).bind("mousedown", function (event) {
			if (!mw.isDrag) {
				mw.drag.sort(".element > *");
				mw.drag.edit_remove();
			}
		});

       $(selector).find(".mw-sorthandle-moveit").hover(function(){
            $(this).parent().parent().addClass("moveit-hover");
       }, function(){
           $(this).parent().parent().removeClass("moveit-hover");
       });
	},
	sort: function (selector) {
         var selector = selector || '.row, .edit';
         var el = $(selector).not(".mw-sorthandle").not(".module *").not(".mw-sorthandle *").notmouseenter();

         el.bind("mouseleave", function(event){
           if (mw.isDrag && ($(this).parents(".module").length==0)) {
             mw.currentDragMouseOver = this;
             var el = this;
             var offset = $(el).offset();
             if(offset.top>event.pageY){
                mw.dropable.data("position", "top");
             }
             else{
                mw.dropable.data("position", "bottom");
             }
           }
         });
         /*
         el.bind("mouseenter", function(){
           if(mw.isDrag && ($(this).parents(".module").length==0)){
                mw.currentDragMouseOver = this;
           }
         });  */

		el.bind("mouseenter", function (event) {
			if (mw.isDrag && ($(this).parents(".module").length==0)) {
    			if (this.className.indexOf('ui-draggable-dragging')==-1 && $(this).parents(".ui-draggable-dragging").length==0) {
                   mw.currentDragMouseOver = this;
                   $(".currentDragMouseOver").removeClass("currentDragMouseOver");
                   $(this).addClass("currentDragMouseOver");
                   if(!$(this).hasClass("empty-element")){
                       mw.dropables.display(this);
                       event.stopPropagation();
                   }
    			}
			}
			else {
				var el = $(this);
				if (el.hasClass("mw-sorthandle")) {
					mw.mouse_over_handle = true;
				}
				else {
					setTimeout(function () {
						mw.mouse_over_handle = false;
					}, 200);
				}
			}
			event.stopPropagation();
		});
        el.bind("mouseleave", function(event){
          if (mw.isDrag && $(this).hasClass("element")) {
            mw.currentDragMouseOver = this;
            event.stopPropagation();
          }
        });

    	mw.drag.the_drop();
		return $(selector);
	},

    the_drop: function () {
        if(!$(document.body).hasClass("bup")){
          $(document.body).addClass("bup");

		$(document.body).bind("mouseup", function (event) {
			if (mw.isDrag) {
				setTimeout(function () {
                        $(mw.dragCurrent).visible();
                        var position = mw.dropable.data("position");
                        if(mw.currentDragMouseOver==null || (mw.currentDragMouseOver.id === mw.dragCurrent.id)){
                           $(mw.dragCurrent).visible();
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
	 * Makes handles for given row
	 *
	 * @example mw.drag.init_row_handles
	 * @param $el_id - the id of the row element
	 */
	init_row_handles: function ($el_id) {
		if ($el_id == undefined || $el_id == 'undefined') {
			$el_id = mw.settings.row_id;
		}
		else {
			mw.settings.row_id = $el_id;
		}
		$(".mw-layout-edit-curent-row-element").html($el_id);
		$exisintg_num = mw.$('#' + $el_id).children(".column").size();
		text = mw.settings.sorthandle_row_columns_controlls
		if (text != undefined) {
			text = text.replace(/ROW_ID/g, "'" + '' + $el_id + "'");
			mw.$('#' + $el_id).children("div:first").find(".columns_set").html(text);
		}
		text1 = mw.settings.sorthandle_row_delete
		if (text1 != undefined) {
			text1 = text1.replace(/ROW_ID/g, "'" + '' + $el_id + "'");
			mw.$('#' + $el_id).children("div:first").find(".mw_row_delete").html(text1);

		}
		$(".mw-make-cols", '#' + $el_id).removeClass('active');
		$(".mw-make-cols-" + $exisintg_num, '#' + $el_id).addClass('active');
	},


	/**
	 * Makes handles for all elements
	 *
	 * @example mw.drag.fix_handles()
	 */
	fix_handles: function (selector) {   //return false;
        if(mw.is.defined(selector)){

            var el = $(selector);
            var has = el.children("div:first").hasClass("mw-sorthandle-module");
            if(!has){
              var name = el.attr('data-type');
              var id = el.attr('id');
              var text = mw.settings.sorthandle_module
              var text = text.replace(/MODULE_NAME/g, "" + '' + name + "");
              var text = text.replace(/MODULE_ID/g, "'" + id + "'");
              text = text.replace(/ELEMENT_ID/g, "'" + '' + id + "'");
              el.prepend(text);
            }
        }
        else{


		if (mw.isDrag == false) {
			mw.$('.row', '.edit').each(function (index) {
				var has = $(this).children("div:first").hasClass("mw-sorthandle-row");
				if (!has) {
					$(this).prepend(mw.settings.sorthandle_row);
				}
				$el_id = $(this).attr('id');
				if ($el_id == undefined || $el_id == 'undefined') {
					$el_id = 'mw-row-' + mw.random();
					$(this).attr('id', $el_id);
				}
				mw.drag.init_row_handles($el_id);
			});

			mw.$('.element:not(.empty-element)', '.edit').each(function (index) {
				$el_id = $(this).attr('id');
				if ($el_id == undefined || $el_id == 'undefined') {
					$el_id = 'mw-element-' + mw.random();
					$(this).attr('id', $el_id);
				}
				var has = $(this).children(":first").hasClass("mw-sorthandle-col");
				if (!has) {
					$has_module = $(this).hasClass("module");
					if ($has_module == false) {
						text = mw.settings.sorthandle_col
					}
					else {
						$m_name = $(this).attr('data-type');

						$m_id = $(this).attr('id');
						text = mw.settings.sorthandle_module
						text = text.replace(/MODULE_NAME/g, "" + '' + $m_name + "");
						text = text.replace(/MODULE_ID/g, "'" + $m_id + "'");
					}
					text = text.replace(/ELEMENT_ID/g, "'" + '' + $el_id + "'");
					$(this).prepend(text);
				}
			})


			mw.$('.mw-sorthandle-main-level', '.edit').removeClass('mw-sorthandle-main-level');
			mw.$('.mw-sorthandle-row-in-column', '.edit').removeClass('mw-sorthandle-row-in-column');
			mw.$('.mw-sorthandle-row-in-element', '.edit').removeClass('mw-sorthandle-row-in-element');
			mw.$('.mw-sorthandle-img-in-element', '.edit').removeClass('mw-sorthandle-img-in-element');
			mw.$('.edit>.row').children('.mw-sorthandle').addClass('.mw-sorthandle-main-level');
			mw.$('.element').find('.row').children('.mw-sorthandle').addClass('mw-sorthandle-row-in-element');
			mw.$('.element').find('img').addClass('mw-sorthandle-img-in-element');
			mw.$('.column').find('.row').children('.mw-sorthandle').addClass('mw-sorthandle-row-in-column');




			mw.drag.sort_handles_events();
 		}

        }
	},




	/**
	 * Removes contentEditable for ALL elements
	 *
	 * @example mw.drag.edit_remove();
	 */
	edit_remove: function () {

		mw.$('*[contenteditable]', '.edit').removeAttr("contenteditable");
 
	},


    /**
     * Put onchange for contenteditable
	 * One call of this function fixes all ContentEditable elements in the page to have onchange event.
	 *
	 * @example mw.drag.fix_onChange_editable_elements();
	 */
    fix_onChange_editable_elements : function(el)   {



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
				setTimeout("mw.drag.create()", 200);
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
    $module = mw.$('#' + $module_id);
    var attributes = {};
    $.each($module[0].attributes, function(index, attr) {
      attributes[attr.name] = attr.value;
    });


    data1 = attributes
    //data1.module = '' + $module_name;
    data1.view = 'admin';

    mw.tools.modal.init({
	html:"",
	width:600, 
	height:450, 
	callback:function() {
      $(this.container).load(mw.settings.site_url + "api/module", data1);
      $(this.container).attr('data-settings-for-module', $module_id);

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

		url1 = mw.settings.site_url + 'api/module';
		$($update_element).load_modules(url1, attributes, function () {
			window.mw_sortables_created = false;
		});

	},
	
	
	
	
	
	
  /**
   * Deletes element by id or selector
   *
   * @method mw.edit.delete_element($el_id)
   * @param Element id or selector
   */
  delete_element: function($el_id) {
    var r = confirm(mw.settings.sorthandle_delete_confirmation_text);
    if (r == true) {
      if ($el_id == undefined || $el_id == 'undefined') {
        $el_id = mw.settings.element_id;
      }
      //	alert($el_id);
      $($el_id).remove();
      mw.$('#' + $el_id).remove();
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
    mw.$('.ui-resizable-handle', '.edit').remove();
    mw.$('.ui-draggable', '.edit').removeClass("ui-draggable");
    mw.$('.ui-resizable', '.edit').removeClass("ui-resizable");
    mw.$('.column', '.edit').removeClass("selected");




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
      $sav['save_field_layout_style'] = $styles_join;
      $.ajax({
        type: 'POST',
        url: mw.settings.site_url + 'api/content/save_field_simple',
        data: $sav,
        async: true
      });
    }

    var master = {};

    mw.$('.edit').each(function(j) {
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



	mw.$('.edit').find('.column').each(function () {

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

                var imgs = $(this).find("img");

				$(this).resizable({
					handles: $handles,
					ghost:false,
					containment: "parent",
                    greedy:true,
					cancel: ".mw-sorthandle",
					minWidth: 150,
					//maxWidth: $row_max_w - $last_c_w,
					alsoResize: imgs,
					resize: function (event, ui) {
						mw.global_resizes.next.width(Math.floor(mw.global_resizes.sum-ui.size.width-10));
                        if(mw.global_resizes.next.width()<151){
                           $(this).resizable("option", "maxWidth", ui.size.width);
                        }
                        mw.settings.resize_started = true;
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





