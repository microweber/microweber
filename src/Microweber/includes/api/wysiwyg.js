/* WYSIWYG Editor */
/* ContentEditable Functions */



mw.require('css_parser.js');
mw.require('events.js');





if(typeof Selection.prototype.containsNode === 'undefined'){
      Selection.prototype.containsNode = function(a){
          if(this.rangeCount === 0){ return false; }
          var r = this.getRangeAt(0);
          if(r.commonAncestorContainer === a){ return true; }
          if(r.endContainer === a){ return true; }
          if(r.startContainer === a){ return true; }
          if(r.commonAncestorContainer.parentNode === a){ return true; }
          if(a.nodeType!==3){
            var c = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer),
                b = c.querySelectorAll(a.nodeName.toLowerCase()),
                l = b.length,
                i = 0;
                if(l>0){
                  for( ; i<l; i++){
                      if(b[i] === a){
                        return true;
                      }
                  }
                }
          }
          return false;
      }
}




mw.wysiwyg = {
    globalTarget: mwd.body,
    action:{
      removeformat:function(){
        var sel = window.getSelection();
        var r = sel.getRangeAt(0);
        var c = r.commonAncestorContainer;
        mw.wysiwyg.removeStyles(c, sel);
      }
    },
    removeStyles:function(common, sel){
        if(!!common.querySelectorAll){
          var all = common.querySelectorAll('*'), l = all.length, i = 0;
          for( ; i<l; i++){
            var el = all[i];
            if(sel.containsNode(el, true)){
              $(el).removeAttr("style");
            }
          }
        }
        else{
           mw.wysiwyg.removeStyles(common.parentNode);
        }
    },
    init_editables : function(module){
       if (!window['mwAdmin']) {
        if(typeof module !== 'undefined'){
            module.contentEditable = false;
            $(module.querySelectorAll(".edit")).each(function(){
               this.contentEditable = true;
                mw.on.DOMChange(this, function(){
                    this.className.indexOf('changed') ==-1 ? $(this).addClass("changed") :'';
                    mw.askusertostay = true;
                });
            });
        }
        else{

            var editables = mwd.querySelectorAll('[contenteditable]'), l = editables.length, x = 0;
            for( ; x<l; x++){
                editables[x].contentEditable = false;
            }

            mw.$(".edit").each(function(){
                mw.on.DOMChange(this, function(){
                    !mw.tools.hasClass(this.className, 'changed') ? $(this).addClass("changed") :'';
                   mw.askusertostay = true;
                    if(this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {
                       this.innerHTML = '<p class="element" id="el'+mw.random()+'">'+this.innerHTML+'</p>';
                    }

                    mw.wysiwyg.normalizeBase64Images(this);

                }, false, true);
                $(this).mouseenter(function(){
                   if(this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {

                       this.innerHTML = '<p class="element" id="el'+mw.random()+'">'+this.innerHTML+'&nbsp;</p>';
                    }
                })
            });
            mw.$(".empty-element, .ui-resizable-handle").each(function(){
                this.contentEditable = false;
            });
            mw.on.moduleReload(function(){
                  mw.wysiwyg.nceui();
            })
        }
      }
    },
    fixElements:function(parent){
      var a = parent.querySelectorAll(".element"), l = a.length; i=0;
      for( ; i<l; i++){
        if(a[i].innerHTML == '' || a[i].innerHTML.replace(/\s+/g, '') == ''){
           a[i].innerHTML = '&zwj;&nbsp;&zwj;';
           mw.log(a[i].innerHTML)
        }
      }
    },
    removeEditable : function(){
      if(!mw.is.ie){
        var i, all = mwd.getElementsByClassName('edit'), len = all.length;
        for( ; i<len ; i++ ) { all[i].contentEditable = false; }
      }
      else{
         mw.$(".edit [contenteditable='true'], .edit").removeAttr('contenteditable');
      }
    },
    validateEditForIE:function(target){
        if($(target).hasClass("edit")){return true;}
        var arr = [];
        mw.tools.foreachParents(target, function(loop){
            arr.push(this.className);
            if($(this).hasClass("module")){mw.tools.loop[loop]=false;}
        });
    },
    prepareContentEditable:function(){
      $(window).bind("onEditMouseDown", function(e, el, target){
        mw.wysiwyg.removeEditable();

        mw.$(".edit").attr("contentEditable", "false");
        $(el).attr("contentEditable", "true");

        if(!mw.is.ie){ //Non IE browser
          var _el = $(el);
          if(mw.tools.hasParentsWithClass(el, "module")){
            !el.isContentEditable ? el.contentEditable = true :'';
          }
          else{
              if(!mw.tools.hasParentsWithClass(target, "module")){
                !el.isContentEditable ? el.contentEditable = true :'';
              }
              else{
                el.contentEditable = false;
              }
          }
        }
        else{   // IE browser

            mw.wysiwyg.removeEditable();
            var cls = target.className;
            if(!mw.tools.hasClass(cls, 'empty-element')  && !mw.tools.hasClass(cls, 'ui-resizable-handle')){
                if(mw.tools.hasParentsWithClass(el, 'module')){
                    target.contentEditable = true;
                }
                else{
                    if(!mw.tools.hasParentsWithClass(target, "module")){
                        if(mw.isDragItem(target)){
                           target.contentEditable = true;
                        }
                        else{
                           mw.tools.foreachParents(target, function(loop){
                              if(mw.isDragItem(this)){
                                  this.contentEditable = true;
                                  mw.tools.loop[loop] = false;
                              }
                           });
                        }
                    }
                }
            }
        }
      });
    },
    _external:function(){  //global element to handle the iframe tools
      var external = mwd.createElement('div');
      external.className='wysiwyg_external';
      mwd.body.appendChild(external);
      return external;
    },
    isSelectionEditable:function(){
        var node = window.getSelection().focusNode;
        if(node===null){ return false;}
        if(node.nodeType !== 3){
           return (node.isContentEditable) ? true : false;
        }
        else{
           return (node.parentNode.isContentEditable) ? true : false;
        }

    },
    execCommand:function(a,b,c){
        try{  // 0x80004005

            if(document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable()){
                var b = b || false;
                var c = c || false;
                if(window.getSelection().rangeCount>0){
                   ($.browser.mozilla && !mw.is.ie)?mwd.designMode = 'on':'';  // For Firefox (NS_ERROR_FAILURE: Component returned failure code: 0x80004005 (NS_ERROR_FAILURE) [nsIDOMHTMLDocument.execCommand])
                   mwd.execCommand(a,b,c);
                   ($.browser.mozilla && !mw.is.ie)?mwd.designMode = 'off':'';
                 }
            }
        }
        catch(e){}
    },
    selection:'',
    _do:function(what){
        mw.wysiwyg.execCommand(what);
        if(typeof mw.wysiwyg.action[what] === 'function'){
             mw.wysiwyg.action[what]();
        }
    },
    save_selected_element:function(){
        mw.$("#mw-text-editor").addClass("editor_hover");
    },
    deselect_selected_element:function(){
        mw.$("#mw-text-editor").removeClass("editor_hover");
    },
    nceui:function(){  //remove defaults for browser's content editable tools


        mw.wysiwyg.execCommand('enableObjectResizing', false, 'false');
        mw.wysiwyg.execCommand('2D-Position', false, false);
        mw.wysiwyg.execCommand("enableInlineTableEditing", null, false);

        if(mw.is.ie){   /*
            var all = document.querySelectorAll('.edit *:not(.disable-resize)');
            if(all.length>0){
                for(var i=0;i<all.length;i++){
                    var dis = all[i];
                    dis.className+=' disable-resize';
                    dis.attachEvent("onresizestart", function(e) {
                        e.returnValue = false;
                    }, false);
                    if(dis.tagName == 'IMG' ){
                       dis.attachEvent("onmousedown", function(e) {
                            e.returnValue = false;
                       }, false);
                    }
                }
            }  */
        }
    },
    paste:function(e){
       var text = '';
       if ( window.clipboardData ) {
        var text = window.clipboardData.getData('Text');
        e.preventDefault();
       }
       mw.wysiwyg.save_selection();
        var pro = mwd.createElement('div');
        //var pro = mwd.createElement('textarea');
        pro.innerHTML = text;
       // pro.className = 'semi_hidden';


       var rects = mw.wysiwyg.selection.range.getClientRects()[0];
       var rtop = typeof rects != 'undefined' ? rects.top : 50;
       var rleft = typeof rects != 'undefined' ? rects.left : 50;
       $(pro).css({
            position:'absolute',
            width:1,
            height:1,
            opacity:0,
            overflow:'hidden',
            top:rtop,
            left:rleft
       })
        pro.contentEditable = true;
        mwd.body.appendChild(pro);
        pro.focus();
        var range = mwd.createRange();
        range.selectNodeContents(pro);
        range.collapse(false);
        setTimeout(function(){
          mw.wysiwyg.restore_selection();
           if(mw.wysiwyg.hasContentFromWord(pro)){
             pro.innerHTML = mw.wysiwyg.clean_word(pro.innerHTML);
           }
            /*

            $(pro.querySelectorAll("*")).each(function(){
               $(this).removeAttr("style");
               var n = this.nodeName;
               if(n =='DIV' || n =='P'){
                   $(this).addClass("element");
                   mw.tools.addClass(this, 'element');
               }
            });

            var c = pro.childNodes, l=c.length,i=0;

            for(; i<l;i++){
              if(c[i].nodeType === 3){
                 $(c[i]).replaceWith("<p class='element'>" + c[i].nodeValue + "</p>");
              }
            }  */



          mw.wysiwyg.insert_html( pro.innerHTML );
          $(pro).remove();
        }, 120);
    },

    hasContentFromWord:function(node){
        if(node.getElementsByTagName("o:p").length > 0 ||
           node.getElementsByTagName("v:shapetype").length > 0 ||
           node.getElementsByTagName("v:path").length > 0 ||
           node.querySelector('.MsoNormal') !== null){
          return true;
        }
        return false;
    },
    prepare:function(){

      mw.wysiwyg.external = mw.wysiwyg._external();

      mw.$("#liveedit_wysiwyg").bind("mousedown mouseup click", function(event){event.preventDefault()});

      var items = $(".element").not(".module");
      $(mwd.body).bind("paste", function(event){
        if(event.target.isContentEditable){
            //mw.wysiwyg.paste(event);
        }
      });

      mw.$(".mw_editor").hover(function(){$(this).addClass("editor_hover")}, function(){$(this).removeClass("editor_hover")});
    },
    deselect:function(s){
      var s = s || window.getSelection();
      s.empty ? s.empty() : s.removeAllRanges();
    },
    editors_disabled:false,
    enableEditors:function(){
        mw.$(".mw_editor, #mw_small_editor").removeClass("disabled");
        mw.wysiwyg.editors_disabled = false;
    },
    disableEditors:function(){
       //mw.$(".mw_editor, #mw_small_editor").addClass("disabled");
       mw.wysiwyg.editors_disabled = false;
    },
    checkForTextOnlyElements:function(e, method){
      var e = e || false;
      var method = method || 'selection';
      if(method == 'selection'){
          var sel = mww.getSelection();
          var f = sel.focusNode;
          var f = mw.tools.hasClass(f, 'edit') ? f : mw.tools.firstParentWithClass(f, 'edit');
          if(f.attributes != undefined && !!f.attributes.field && f.attributes.field.nodeValue == 'title'){
            if(!!e){
              mw.e.cancel(e, true);
            }
            return false;
          }
      }
    },
    init:function(selector){
      var selector = selector || ".mw_editor_btn";
      var mw_editor_btns = mw.$(selector);
      mw_editor_btns.bind("mousedown mouseup click", function(event){
          if(mw.wysiwyg.editors_disabled) { return false; }
          event.preventDefault();
          if(event.type=='mouseup' && !$(this).hasClass('disabled')){
             var command = $(this).dataset('command');
              if(!command.contains('custom-')){
                 mw.wysiwyg._do(command);
              }
              else{
                var name = command.replace('custom-', "");
                mw.wysiwyg[name]();
              }
              $(this).removeClass("mw_editor_btn_mousedown");
              $(this).addClass("mw_editor_btn_active");
          }
          if(event.type=='mousedown' && !$(this).hasClass('disabled')){
              $(this).addClass("mw_editor_btn_mousedown");
          }
      });
      mw_editor_btns.hover(function(){
        $(this).addClass("mw_editor_btn_hover");
      }, function(){
        $(this).removeClass("mw_editor_btn_hover");
      });
      $(mwd.body).bind('mouseup keyup keydown', function(event){






        if(event.target.isContentEditable){
          mw.wysiwyg.check_selection(event.target);
        }
        else{
           mw.wysiwyg.check_selection();
        }
        if( event.keyCode == 46  && event.type=='keydown'){
            mw.tools.removeClass(mw.image_resizer, 'active');

            mw.tools.addClass(mw.tools.firstParentWithClass( mwd.querySelector('.element-current'), 'edit'), 'changed orig_changed');
            mw.askusertostay = true;
        }


          if(event.type == 'keydown'){

           if(event.target.nodeName === 'INPUT' || event.target.nodeName === 'TEXTAREA'){
             return true;
           }
           var sel = window.getSelection();
           if(event.keyCode == 13) {
              mw.wysiwyg.checkForTextOnlyElements(event);
           }

           if(sel.rangeCount > 0){
           var r = sel.getRangeAt(0);
           if(event.keyCode == 9 && !event.shiftKey && sel.focusNode.parentNode.iscontentEditable && sel.isCollapsed){
             mw.wysiwyg.insert_html('&nbsp;&nbsp;&nbsp;&nbsp;');
             return false;
           }

           if(event.keyCode == 46 || event.keyCode == 8){


             if(!mw.settings.liveEdit){
               return true;
             }

              if(typeof mw.current_element !== 'undefined' && mw.current_element.innerHTML == ''){
                   $(mw.current_element).remove();
                   return false;
              }

              if( r.cloneContents().querySelector(".module") !== null ||
                    mw.tools.hasClass(r.commonAncestorContainer, 'module') ||
                    mw.tools.hasParentsWithClass(r.commonAncestorContaner, 'module')){
                  return false;
              }

                var n = sel.focusNode.nodeType !== 3 ? sel.focusNode : sel.focusNode.parentNode;
                if(mw.tools.hasClass(n, 'module') || mw.tools.hasParentsWithClass(n, 'module')){
                  return false;
                }

              if(sel.isCollapsed && typeof window.chrome === 'object'){
				  
				  /*
				  delete bug 
				  
				  
                var a = sel.anchorNode;
                var _s = mw.tools.cloneObject(sel);
                if(event.keyCode == 46 || event.keyCode == 8 ){
                  if(a.innerHTML == '' || (a.childNodes.length === 1 && a.childNodes[0].nodeName === 'BR')){
                    mw.wysiwyg.select_element(a);
                    mw.wysiwyg.execCommand('delete');
                   return false;
                  }
                }

                if(event.keyCode == 46 ){

                    if(sel.focusNode.textContent.charAt(sel.focusOffset) === ''){

                      var next = sel.focusNode.nextSibling;
                      if(next === null ){
                        if(sel.focusNode.nodeType === 3){
                            sel.modify('extend', 'forward', 'character');
                            var r = sel.getRangeAt(0);
                            if(r.startContainer === r.endContainer){
                              mw.wysiwyg.execCommand('delete');
                            }

                        }
                        return false;
                      }
                      if(next.nodeType !== 3){

                         if(next.nodeName === 'BR'){ return false; }
                         var cnext =  mww.getComputedStyle(next, null);
                         if(cnext === null){ return false; }
                         if(cnext.display === 'block'){
                           return false;
                         }
                      }
                    }


                  sel.modify('move', 'forward', 'character');
                  if( _s.anchorOffset === sel.anchorOffset ) return false;
                  if( mw.wysiwyg.selection_length() > 0 ) return false;
                }
                else if(event.keyCode == 8 ){


                    if(sel.focusNode.textContent.charAt(sel.focusOffset) === ''){
                      var prev = sel.focusNode.previousSibling;

                      if(prev === null ){
                        if(sel.focusNode.nodeType === 3){
                            sel.modify('extend', 'backward', 'character');

                            if(sel.toString().length === 1){
                                mw.wysiwyg.insert_html(' ');
                                return false;
                            }
                            mw.wysiwyg.execCommand('delete');

                        }
                        return false;
                      }
                      if(prev.nodeType !== 3){
                         if(prev.nodeName === 'BR'){return false;}
                         var cnext =  mww.getComputedStyle(prev, null);
                         if(cnext === null){ return false; }
                         if(cnext.display === 'block'){
                           return false;
                         }
                      }
                    }

                    sel.modify('extend', 'backward', 'character');

                    if( mw.wysiwyg.selection_length() > 1 ) return false;
                }
                var b = sel.anchorNode;
                if(a === b || b.nodeType === 3){
                     mw.wysiwyg.execCommand('delete');

                }
                else{
                  var cb =  mww.getComputedStyle(b, null);
                  if(cb === null){ return false; }
                  if(cb.display !== "block"){

                     mw.wysiwyg.execCommand('delete');
                  }
                  else{}
                }
                return false;
              */
			  
			  
			  }
              else{  /* If Not Colapsed */

              }
           }

         }
         }

      });

      mw.on.tripleClick(mwd.body, function(target){
        mw.wysiwyg.select_all(target);

        if(mw.tools.hasParentsWithClass(target, 'element')){
          //mw.wysiwyg.select_all(mw.tools.firstParentWithClass(target, 'element'));
        }

        var s = window.getSelection();
        var r = s.getRangeAt(0);
        var c = r.cloneContents();

        var common =  mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);

        var a = common.querySelectorAll('*'), l = a.length, i=0;

        for( ; i<l; i++ ){
          if(!!s.containsNode && s.containsNode(a[i], true)){
              r.setEndBefore(a[i]);
              break;
              return false;
          }
        }
      });

      $(mwd.body).keyup(function(e){


        mw.tools.addClass(this, 'isTyping');

        if(mw.tools.isEmpty(e.target)){
            e.target.innerHTML = '&zwnj;&nbsp;';
         }
         if(e.keyCode == 13) {

               mw.$(".element-current").removeClass("element-current");
               var el = mwd.querySelectorAll('.edit .element'), l = el.length, i = 0;
               for( ; i<l; i++){
                   el[i].id =  'row_' + mw.random();
               }
               e.preventDefault();
               if(!e.shiftKey){
                //var pre = mw.wysiwyg.findTagAcrossSelection('pre');
                //var code = mw.wysiwyg.findTagAcrossSelection('code');
                var p = mw.wysiwyg.findTagAcrossSelection('p');

                 if(!!p){
                   //mw.is.ie?'':mw.wysiwyg.insert_html('<p class="element"></p>');
                 }

                 //return false;
               }
         }

      });
    },
    validateCommonAncestorContainer:function(c){
        if(typeof c.querySelectorAll === 'function'){
          return c;
        }
        else{
          return mw.wysiwyg.validateCommonAncestorContainer(c.parentNode);
        }
    },
    applier:function(tag, classname, style_object){
      var classname = classname || '';
      if(mw.wysiwyg.isSelectionEditable()){
          var range = window.getSelection().getRangeAt(0);
          var selectionContents = range.extractContents();
          var el = mwd.createElement(tag);
          el.className = classname;
          typeof style_object !== 'undefined'? $(el).css(style_object) :'';
          el.appendChild(selectionContents);
          range.insertNode(el);
          return el;
      }
    },
    external_tool:function(el, url){
        var el = $(el).eq(0);
        var offset = el.offset();
        $(mw.wysiwyg.external).css({
          top: offset.top - $(window).scrollTop() + el.height(),
          left:offset.left
        });
        $(mw.wysiwyg.external).html("<iframe src='" + url + "' scrolling='no' frameborder='0' />");
        var frame = mw.wysiwyg.external.querySelector('iframe');
        frame.contentWindow.thisframe = frame;
    },
    createelement : function(){
       var el = mw.wysiwyg.applier('div', 'mw_applier element');
    },
    fontcolorpicker:function(){
        var el = ".mw_editor_font_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontColor");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontbgcolorpicker:function(){
        var el = ".mw_editor_font_background_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontbg");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontColor:function(color){
         mw.wysiwyg.execCommand('forecolor', null, color);
    },
    fontbg:function(color){
        var color = color != 'transparent' ? '#' + color : color;
        mw.wysiwyg.execCommand('backcolor', null, color);
    },
    request_change_bg_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
       $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_bg_color : function(color){
       var color = color != 'transparent' ? '#' + color : color;
        $(".element-current").css("backgroundColor", color);
    },
    request_border_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
       $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_border_color : function(color){
        if(color!="transparent"){
          $(".element-current").css(mw.border_which + "Color", "#"+color);
          $(".ed_bordercolor_pick span").css("background", "#"+color);
        }
        else{
          $(".element-current").css(mw.border_which + "Color", "transparent");
          $(".ed_bordercolor_pick span").css("background", "");
        }
    },

    request_change_shadow_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_shadow_color');
       $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_shadow_color:function(color){
        if( mw.current_element_styles.boxShadow !="none" ){
           var arr = mw.current_element_styles.boxShadow.split(' ');
           var len = arr.length;
           var x =  parseFloat(arr[len-4]);
           var y =  parseFloat(arr[len-3]);
           var blur =  parseFloat(arr[len-2]);
           $(".element-current").css("box-shadow", x+"px " + y + "px "+ blur +"px #"+color);
           $(".ed_shadow_color").dataset("color", color);
        }
        else{
           $(".element-current").css("box-shadow", "0px 0px 6px #"+color);
           $(".ed_shadow_color").dataset("color", color);
        }
    },
    fontFamily:function(font_name){
         mw.wysiwyg.execCommand('fontname', null, font_name);
    },
    fontSize:function(px){
        var obj = {
          fontSize:px+'px'
        }
        //var el = mw.wysiwyg.applier('span', 'mw_applier', obj);
        mw.wysiwyg.execCommand('fontsize', null, px);
    },
    resetActiveButtons:function(){
      mw.$('.mw_editor_btn_active').removeClass('mw_editor_btn_active')
    },
    setActiveButtons:function(node){
        var css = mw.CSSParser(node);
        if(typeof css.get !== 'undefined'){
          var is = css.get.isit();
          is.bold?mw.$('.mw_editor_bold').addClass('mw_editor_btn_active'):'';
          is.italic?mw.$('.mw_editor_italic').addClass('mw_editor_btn_active'):'';
          is.underlined?mw.$('.mw_editor_underline').addClass('mw_editor_btn_active'):'';
          var font = css.get.font();
           var family_array = font.family.split(',');
           var fam = mw.tools.getFirstEqualFromTwoArrays(family_array, mw.wysiwyg.editorFonts);
		   var ddval = mw.$(".mw_dropdown_action_font_family");
		   if(ddval.length!=0 && ddval.setDropdownValue != undefined){
			  
			    mw.$(".mw_dropdown_action_font_family").setDropdownValue(fam);
		   }
          
         }
    },
    setActiveFontSize:function(node){
        var size = Math.round(parseFloat(mw.CSSParser(node).get.font().size));
		
		var ddval = mw.$(".mw_dropdown_action_font_size");

		
		if(ddval.length!=0 && ddval.setDropdownValue != undefined){
        mw.$(".mw_dropdown_action_font_size").setDropdownValue(mw.wysiwyg.editorFontSizes[size]);
		}
    },
    isFormatElement:function(obj){
        var items = /^(div|h[1-6]|p)$/i;
        return items.test(obj.nodeName);
    },
    started_checking:false,
    check_selection:function(){ /* TODO */
       var cmds = ['italic', 'bold', 'underline', 'justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
           l=cmds.length, i=0;
       for( ; i<l; i++ ){
           var cmd = cmds[i], is = mwd.queryCommandState(cmd), el=mw.$(".mw_editor_"+cmd);
           if(is){
             el.addClass("mw_editor_btn_active");
           }
           else{
             el.removeClass("mw_editor_btn_active");
           }
       }
    },
    check_selection:function(target){
         var target = target || false;
         if(!mw.wysiwyg.started_checking){
             mw.wysiwyg.started_checking = true;

             var selection = window.getSelection();

             if(selection.rangeCount>0){
                  mw.wysiwyg.resetActiveButtons();
                 var range = selection.getRangeAt(0);
                 var start = range.startContainer;
                 var end = range.endContainer;
                 var common = range.commonAncestorContainer;
                 var children = range.cloneContents().childNodes, i=0, l=children.length;

                 var list = mw.tools.firstParentWithTag(common, ['ul','ol']);
                 if(!!list){
                   mw.$('.mw_editor_'+list.nodeName.toLowerCase()).addClass('mw_editor_btn_active');
                 }

                 if(common.nodeType !== 3){
                     var commonCSS = mw.CSSParser(common);
                     var align = commonCSS.get.alignNormalize();
                     mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                     mw.$(".mw-align-"+align).addClass('mw_editor_btn_active');
                     for( ; i<l; i++){
                        mw.wysiwyg.setActiveButtons(children[i]);
                     }

                     mw.wysiwyg.setActiveFontSize(common);
                 }
                 else{
                   if(typeof common.parentElement !== 'undefined' && common.parentElement !== null){
                      var align = mw.CSSParser(common.parentElement).get.alignNormalize();
                      mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                      mw.$(".mw-align-"+align).addClass('mw_editor_btn_active');
                      mw.wysiwyg.setActiveButtons(common.parentElement);
                      mw.wysiwyg.setActiveFontSize(common.parentElement);
                   }

                 }
				 
				 
				 


                 if(mw.wysiwyg.isFormatElement(common)){
                   var format = common.nodeName.toLowerCase();
				   
				   var ddval = mw.$(".mw_dropdown_action_format");
				  if(ddval.length!=0 && ddval.setDropdownValue != undefined){

                   mw.$(".mw_dropdown_action_format").setDropdownValue(format);
				  }
				   
				   
                 }
                 else{
                     mw.tools.foreachParents(common, function(loop){
                        if(mw.wysiwyg.isFormatElement(this)){
                            var format = this.nodeName.toLowerCase();
							

							var ddval = mw.$(".mw_dropdown_action_format");
							if(ddval.length!=0 && ddval.setDropdownValue != undefined){
								 
							 mw.$(".mw_dropdown_action_format").setDropdownValue(format);

								
							}
                            mw.tools.stopLoop(loop);
                        }
                     });
                 }
            }

            if(!!target){
                mw.wysiwyg.setActiveButtons(target);
                if(target.tagName == 'A'){
                  mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
                var parent_a = mw.tools.firstParentWithTag(target, 'a');
                if(!!parent_a){
                    mw.$(".mw_editor_link").addClass('mw_editor_btn_active');
                }
             }
            mw.wysiwyg.started_checking = false;
        }
    },
    containsNode:function(node, sel){
        if(node === null || typeof node === 'undefined'){return false;}
        var sel = sel || window.getSelection();
        if( typeof Selection.prototype.containsNode !== 'undefined' ){
          return sel.containsNode(node);
        }
        else{

        }
    },
    link:function(){
         mw.wysiwyg.save_selection();
         var modal = mw.tools.modal.frame({
          url:"rte_link_editor",
          title:"",
          name:"mw_rte_link",
          template:'mw_modal_simple',
          width:430,
          height:300
        });
        var link = mw.wysiwyg.findTagAcrossSelection('a', mw.wysiwyg.selection.sel);
        if(!! link){
            modal.main.find("iframe").load(function(){
                  $(this).contents().find("#customweburl").val(link.href);
                  if(link.target == '_blank'){
                     $(this).contents().find("#url_target")[0].checked = true;
                  }
            })
        }
    },
    unlink:function(){
        if(mw.wysiwyg.selection_length()>0){
             mw.wysiwyg.execCommand('unlink', null, null);
          }
          else{
            var link = mw.wysiwyg.findTagAcrossSelection('a');
            if(!!link){
                 mw.wysiwyg.select_element(link);
                 mw.wysiwyg.execCommand('unlink', null, null);
            }
          }
          mw.$(".mw_editor_link").removeClass("mw_editor_btn_active");
    },
    findTagAcrossSelection:function(tag, selection){
          var selection = selection || window.getSelection();
          if(selection.anchorNode.nodeName.toLowerCase() === tag) { return  selection.anchorNode; }
          var range = selection.getRangeAt(0);
          var common = range.commonAncestorContainer;
          var parent = mw.tools.firstParentWithTag(common, [tag]);
          if(!!parent){return parent}
          if(typeof common.querySelectorAll !== 'undefined'){
              var items = common.querySelectorAll(tag), l = items.length, i = 0, arr = [];
              if(l > 0){
                for( ; i<l; i++){
                  if( selection.containsNode(items[i], true)) {
                    arr.push(items[i])
                  }
                }
                if(arr.length > 0){
                  return arr.length === 1 ? arr[0] : arr;
                }
              }
          }
          return false;
    },
    image_link:function(url){
        $("img.element-current").wrap("<a href='" + url + "'></a>");
    },
    request_media:function(hash, types){
        var types = types || false;
        if( hash == '#editimage' ) { var types = 'images'; }
        var url = !!types?"rte_image_editor?types="+types+''+hash:"rte_image_editor"+hash;
        mw.tools.modal.frame({
          url:url,
          //title:"Upload Picture",
          name:"mw_rte_image",
          width:430,
          height:230,
          template:'mw_modal_simple'
        });
    },
    media:function(hash){

        if(mw.settings.liveEdit && typeof mw.target.item === 'undefined') return false;
        var hash = hash || '#insert_html';
        if($("#mw_rte_image").length>0){
           $("#mw_rte_image").remove();
        }
        else{
          if(mw.wysiwyg.isSelectionEditable() || mw.target.item.className=='image_change'){
              mw.wysiwyg.save_selection();
              mw.wysiwyg.request_media(hash);
          }
        }
    },
    request_bg_image:function(){
      mw.wysiwyg.request_media('#set_bg_image');
    },
    set_bg_image:function(url){

      $(".element-current").css("backgroundImage", "url(" + url + ")");
    },
    insert_html:function(html){
      if(typeof html === 'string'){
        var isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
      }
      else{
         var isembed = false;
      }

      if(isembed){
        var id = 'frame-'+mw.random();
        var frame = html;
        var html = '<span id="'+id+'"></span>';
      }
      if(!document.selection){
         mw.wysiwyg.execCommand('inserthtml', false, html);
      }
      else{
        document.selection.createRange().pasteHTML(html)
      }
      if(isembed){
        var el = mwd.getElementById(id);
        el.parentNode.contentEditable = false;
        $(el).replaceWith(frame);
      }
    },
    selection_length:function(){
      return window.getSelection().getRangeAt(0).cloneContents().childNodes.length;
    },
    fontFX:function(cls){
       mw.wysiwyg.applier('span', cls);
    },
    insert_image:function(url){
        var id = 'image_' + mw.random();
        var img = '<img id="'+id+'" contentEditable="true" class="element" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        $("#"+id).attr("contenteditable", false);
        $("#"+id).removeAttr("_moz_dirty");
        mw.wysiwyg.save_selection();
        return id;
    },
    save_selection:function(){
        var selection = window.getSelection();
        var range =  selection.getRangeAt(0);
        mw.wysiwyg.selection = {
          sel:selection,
          range:range,
          element:mw.$('[contenteditable="true"]').eq(0)
        }
    },
    restore_selection:function(){
        mw.wysiwyg.selection.element.attr("contenteditable", "true");
        mw.wysiwyg.selection.element.focus();
        mw.wysiwyg.selection.sel.removeAllRanges()
        mw.wysiwyg.selection.sel.addRange(mw.wysiwyg.selection.range);
    },
    select_all:function(el){
        var range = document.createRange();
        range.selectNodeContents(el);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);
    },
    select_element:function(el){
        var range = document.createRange();
        range.selectNode(el);
        var selection = window.getSelection();
        selection.removeAllRanges();
        selection.addRange(range);

    },
    format:function(command){
      if(mw.wysiwyg.selection_length() > 0){
        mw.wysiwyg.applier(command);
      }
      else{
        mw.wysiwyg.execCommand('FormatBlock', false, '<' + command + '>');
      }
    },
    _undo:false,
    _redo:false,
    undoredo:false,
    undoRedoFixes:function(){
        mw.wysiwyg.undoredo = true;
        mw.askusertostay = true;
        var curr = mw.historyActive;
        var len  = mw.tools.objLenght(mw.undoHistory);
        if(typeof mw.undoHistory[curr] === 'undefined' && curr > 0){
            mw.$(".mw_editor_undo").addClass("disabled");
            mw.$(".mw_editor_redo").removeClass("disabled");
        }
        if(typeof mw.undoHistory[curr] === 'undefined' && curr < 0){
            mw.$(".mw_editor_undo").removeClass("disabled");
            mw.$(".mw_editor_redo").addClass("disabled");
        }

        if(typeof mw.undoHistory[curr] !== 'undefined' && curr > 0 && curr < len){
            mw.$(".mw_editor_undo").removeClass("disabled");
            mw.$(".mw_editor_redo").removeClass("disabled");
        }
    },
    historyUndo:function(){
      mw.wysiwyg.undoredo = true;
      mw.askusertostay = true;
      if(typeof mw.undoHistory === 'object'){
        var len = mw.tools.objLenght(mw.undoHistory);
         if( len > 0 ){
            var active = mw.historyActive ++;
         }
         mw.history.load(mw.undoHistory[active]);
         mw.wysiwyg.undoRedoFixes();
        }
    },
    historyRedo:function(){
      if(typeof mw.undoHistory === 'object'){
        var len = mw.tools.objLenght(mw.undoHistory);
         if( len > 0 ){
            var active = mw.historyActive --;
         }
         mw.history.load(mw.undoHistory[active]);
         mw.wysiwyg.undoRedoFixes();
      }
    },

    fontFamilies:['Arial', 'Tahoma', 'Verdana', 'Georgia', 'Times New Roman'],
    initFontFamilies:function(){
        var body_font = window.getComputedStyle(mwd.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
        if(mw.wysiwyg.fontFamilies.indexOf(body_font) === -1){
             mw.wysiwyg.fontFamilies.push(body_font);
             var l = mw.wysiwyg.fontFamilies.length, i = 0, html = '';
             for(; i<l; i++){
                html += '<li value="'+mw.wysiwyg.fontFamilies[i]+'"><a style="font-family:'+mw.wysiwyg.fontFamilies[i]+'" href="#">'+mw.wysiwyg.fontFamilies[i]+'</a></li>'
             }
             mw.$("#font_family_selector_main ul").append(html);
        }
    },
	iframe_editor:function(textarea, iframe_url, content_to_set){
        var content_to_set = content_to_set || false;
	    var id = $(textarea).attr("id");
		$("#iframe_editor_"+id).remove();
	    var url = iframe_url;
        var iframe = mwd.createElement('iframe');
        iframe.className = 'mw-editor-iframe-loading';
		iframe.id = "iframe_editor_"+id;
        iframe.width = $(textarea).width();
        iframe.height = $(textarea).height();
        iframe.scrolling = "no";
        iframe.setAttribute('frameborder', 0);
        iframe.src = url;
        iframe.style.resize = 'vertical';
        iframe.onload = function(){
          iframe.className = 'mw-editor-iframe-loaded';
          var b = $(this).contents().find(".edit");
          var b =  $(this).contents().find("[field='content']")[0];
		  if(typeof b != 'undefined' && b !== null){
              b.contentEditable = true;
              $(b).bind("blur keyup", function(){
                textarea.value = $(this).html();
              });
              if(!!content_to_set){
                 $(b).html(content_to_set);
              }
             mw.on.DOMChange(b, function(){
                  textarea.value = $(this).html();
                  mw.askusertostay = true;
             });
		  }
        }
        $(textarea).after(iframe);
        $(textarea).hide();
        return iframe;
    },
    clean_word:function( html ){
        html = html.replace( /<td([^>]*)>/gi, '<td>' ) ;
        html = html.replace( /<table([^>]*)>/gi, '<table cellspacing="0" cellpadding="0" border="1">' ) ;
    	html = html.replace(/<o:p>\s*<\/o:p>/g, '') ;
    	html = html.replace(/<o:p>[\s\S]*?<\/o:p>/g, '&nbsp;') ;
    	html = html.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;
    	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*;/gi, '' ) ;
    	html = html.replace( /\s*MARGIN: 0cm 0cm 0pt\s*"/gi, "\"" ) ;
    	html = html.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
    	html = html.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;
    	html = html.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;
    	html = html.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;
    	html = html.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;
    	html = html.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
    	html = html.replace( /\s*tab-stops:[^"]*/gi, '' ) ;
        html = html.replace( /\s*face="[^"]*"/gi, '' ) ;
        html = html.replace( /\s*face=[^ >]*/gi, '' ) ;
        html = html.replace( /\s*FONT-FAMILY:[^;"]*;?/gi, '' ) ;
    	html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;
    	html = html.replace( /<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi, '' ) ;
    	html = html.replace( /<(?:META|LINK)[^>]*>\s*/gi, '' ) ;
    	html =  html.replace( /\s*style="\s*"/gi, '' ) ;
    	html = html.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;
    	html = html.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;
    	html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;
    	html = html.replace( /<SPAN\s*>([\s\S]*?)<\/SPAN>/gi, '$1' ) ;
    	html = html.replace( /<FONT\s*>([\s\S]*?)<\/FONT>/gi, '$1' ) ;
    	html = html.replace(/<\\?\?xml[^>]*>/gi, '' ) ;
    	html = html.replace( /<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi, '' ) ;
    	html = html.replace(/<\/?\w+:[^>]*>/gi, '' ) ;
    	html = html.replace(/<\!--[\s\S]*?-->/g, '' ) ;
    	html = html.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;
    	html = html.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;
    	html = html.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/ig, '' ) ;
    	html = html.replace( /<(\w[^>]*) language=([^ |>]*)([^>]*)/gi, "<$1$3") ;
    	html = html.replace( /<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi, "<$1$3") ;
    	html = html.replace( /<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi, "<$1$3") ;
    	html = html.replace( /<H(\d)([^>]*)>/gi, '<h$1>' ) ;
        html = html.replace(/<font size=2>(.*)<\/font>/gi,'$1') ;
        html = html.replace(/<font size=3>(.*)<\/font>/gi,'$1') ;
        html = html.replace(/<a name=.*>(.*)<\/a>/gi,'$1') ;
        html = html.replace( /<H1([^>]*)>/gi, '<H2$1>' ) ;
        html = html.replace( /<\/H1\d>/gi, '<\/H2>' ) ;
        html = html.replace( /<span>/gi, '$1' ) ;
        html = html.replace( /<\/span\d>/gi, '' ) ;
        html = html.replace( /<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>' );
        html = html.replace( /<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>' );
    	return html ;
    },
    normalizeBase64Image:function(node, callback){
        if(typeof node.src !== 'undefined' && node.src.indexOf('data:image/') === 0){
            var type = node.src.split('/')[1].split(';')[0];
            var obj = {
              file : node.src,
              name: mw.random().toString(36) + "." + type
            }
            $.post(mw.settings.api_url + "media/upload", obj, function(data){
                var data = $.parseJSON(data);
                node.src =  data.src;
                if(typeof callback === 'function'){
                  callback.call(node);
                }
            });
        }
    },
    normalizeBase64Images:function(root){
        var root = root || mwd.body;
        var all = root.querySelectorAll(".edit img[src*='data:image/']"), l = all.length, i = 0;
        if(l > 0){
          for( ; i<l; i++){
            mw.tools.addClass(all[i], 'element');
            mw.wysiwyg.normalizeBase64Image(all[i]);
          }
        }
    }
}





mw.disable_selection = function(element){
    var el = element || ".module";
    var el = $(el, ".edit").not(".unselectable");

    el.attr("unselectable", "on");
    el.addClass("unselectable");
    el.bind("selectstart", function(event){
      event.preventDefault();
      return false;
    });
}


$(mwd).ready(function(){





mw.$(".mw_dropdown_action_font_family").change(function(){
    var val = $(this).getDropdownValue();
     mw.wysiwyg.fontFamily(val);
});
mw.$(".mw_dropdown_action_font_size").change(function(){
    var val = $(this).getDropdownValue();
     mw.wysiwyg.fontSize(val);
});
mw.$(".mw_dropdown_action_format").change(function(){
    var val = $(this).getDropdownValue();
    mw.wysiwyg.format(val);
});


mw.$(".mw_dropdown_action_fontfx").change(function(){
    var val = $(this).getDropdownValue();
     mw.wysiwyg.fontFX(val);
});




  mw.wysiwyg.nceui();

  mw.smallEditor = mw.$("#mw_small_editor");
  mw.bigEditor = mw.$("#mw-text-editor");


  $(mwd.body).mousedown(function(event){
    var target = event.target;
    if($(target).hasClass("element")){
      $(window).trigger("onElementMouseDown", target);
    }
    else if($(target).parents(".element").length>0){
      $(window).trigger("onElementMouseDown", $(target).parents(".element")[0]);
    }
    if($(target).hasClass("edit")){
      $(window).trigger("onEditMouseDown", [target, target]);
    }
    else if($(target).parents(".edit").length>0){
      $(window).trigger("onEditMouseDown", [$(target).parents(".edit")[0], target]);

    }
  });




 if(!window['mwAdmin']){
   mw.wysiwyg.prepareContentEditable();
 }

 mw.wysiwyg.editorFonts = [];
 mw.wysiwyg.editorFontSizes = {};

  mw.$(".mw_dropdown_action_font_family li").each(function(){
      mw.wysiwyg.editorFonts.push(this.getAttribute('value'));
  });

  mw.$(".mw_dropdown_action_font_size li a").each(function(i){
     mw.wysiwyg.editorFontSizes[Math.round(parseFloat(mw.CSSParser(this).get.font().size))] = this.parentNode.getAttribute("value");
  });

});


$(window).load(function(){
  mw.wysiwyg.init_editables();

  mw.$("#wysiwyg_insert").bind("change", function(){

   if(mw.wysiwyg.isSelectionEditable()){
        var val = $(this).getDropdownValue();
        if( val == 'hr' ){
            mw.wysiwyg._do('InsertHorizontalRule');
        }
        else if( val == 'box' ){
            var div = mw.wysiwyg.applier('div', 'well element');

            if(mw.wysiwyg.selection_length() <= 2){
               $(div).append("<p>&nbsp;</p>");
            }
        }
        else if( val == 'table' ){
             var table = mw.wysiwyg.applier('table', 'mw-wysiwyg-table', {width:"100%"});
             table.innerHTML = '<tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr><tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr>';
             table.setAttribute('onclick', 'mw.inline.tableController(this, event);');
        }
        else if( val == 'quote' ){
            var div = mw.wysiwyg.applier('blockquote', 'element');


              $(div).append("<cite>By Lorem Ipsum</cite>");

        }
   }

  });

  $(window).bind("keydown paste mousedown mouseup", function(e){
    mw.wysiwyg.globalTarget = e.target;
    var selection = window.getSelection();
    if( mw.wysiwyg.globalTarget.isContentEditable
        && selection.containsNode(mw.wysiwyg.globalTarget, true)
        && !mw.tools.hasParentsWithClass(mw.wysiwyg.globalTarget, 'nodrop')
        && !mw.tools.hasClass(mw.wysiwyg.globalTarget.className, 'nodrop')){
                mw.wysiwyg.enableEditors();
    }
    else{
        if(!mw.tools.hasParentsWithClass(mw.wysiwyg.globalTarget, 'mw_editor') &&
           mw.wysiwyg.globalTarget.parentNode !== null &&
           !mw.tools.hasParentsWithClass(mw.wysiwyg.globalTarget, 'mw_modal') &&
           !mw.tools.hasClass(mw.wysiwyg.globalTarget.className, 'mw_editor')){
                mw.wysiwyg.disableEditors();
        }
    }

  if(e.ctrlKey && e.type =='keydown') {
        var code = e.keyCode;
        if( code == 66){
               mw.wysiwyg.execCommand('bold');
               e.preventDefault();
        }
        else if(code == 73) {
            mw.wysiwyg.execCommand('italic');
            e.preventDefault();
        }
        else if(code == 85) {
            mw.wysiwyg.execCommand('underline');
            e.preventDefault();
        }
  }
});

      $(mwd.body).bind("paste", function(event){
        if(event.target.isContentEditable){
            mw.wysiwyg.paste(event);
        }
      });

      mw.$(".mw_editor").each(function(){
         mw.tools.dropdown(this);
      });




        var nodes = mwd.querySelectorAll(".edit"), l = nodes.length, i=0;

        for( ; i<l; i++ ){
            var node = nodes[i];
            var rel = mw.tools.mwattr(node, "rel");
            var field = mw.tools.mwattr(node, "field");
            if( field == 'content' && rel == 'content' ){
              if(node.querySelector('p') !== null){
                var node = node.querySelector('p');
              }
              node.contentEditable = true;
              node.focus();
              var range = mwd.createRange();
              range.setStart(node, 0);
              range.collapse(false);
              var selection = window.getSelection();
              selection.removeAllRanges();
              selection.addRange(range);
              break;
            }
        }





      /**************************************  The Dot   ***********************************



       mw.wysiwyg._C = mwd.createElement('div');
       mw.wysiwyg._C.className = 'mwwysiwygcursor';
       mwd.body.appendChild(mw.wysiwyg._C);

       setInterval(function(){
              //var r = window.getSelection().getRangeAt(0).getBoundingClientRect();
              var r = window.getSelection().getRangeAt(0).getClientRects()[0];
              if(!!r){
                mw.wysiwyg._C.style.top = (r.top - 6) + 'px';
                mw.wysiwyg._C.style.left = (r.left + r.width - 2) + 'px';
              }

       }, 77);



      *************************************************************************************/



});








