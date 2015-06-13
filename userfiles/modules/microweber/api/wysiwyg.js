/* WYSIWYG Editor */
/* ContentEditable Functions */

mw.require('css_parser.js');
mw.require('events.js');
//mw.lib.require('rangy');

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

if(typeof Range.prototype.querySelector === 'undefined'){
  Range.prototype.querySelector = function(s){
      var r = this;
      var f = r.extractContents();
      var node = f.querySelector(s);
      r.insertNode(f);
      return node;
  }
}

if(typeof Range.prototype.querySelectorAll === 'undefined'){
  Range.prototype.querySelectorAll = function(s){
      var r = this;
      var f = r.extractContents();
      var nodes = f.querySelectorAll(s);
      r.insertNode(f);
      return nodes;
  }
}




mw.wysiwyg = {
    globalTarget: mwd.body,
    allStatements:function(c,f){
        var sel = window.getSelection(),
            range = sel.getRangeAt(0),
            common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);
        var nodrop_state = !mw.tools.hasClass(common, 'nodrop') &&  !mw.tools.hasParentsWithClass(common, 'nodrop');

        if(mw.wysiwyg.isSelectionEditable() && nodrop_state){
            if(typeof c === 'function'){
              c.call();
            }
        }
        else{
          if(typeof f === 'function'){
            f.call();
          }
        }
    },
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
            if(typeof sel !== 'undefined' && sel.containsNode(el, true)){
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
                    mw.wysiwyg.change(this);
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
                    mw.wysiwyg.change(this);
                    if(this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {

                      mw.wysiwyg.modify(this, function(){
                          this.innerHTML = '<p class="element">'+this.innerHTML+'</p>';
                      });
                    }
                    mw.wysiwyg.normalizeBase64Images(this);
                }, false, true);
                $(this).mouseenter(function(){
                   if(this.querySelectorAll('*').length === 0 && hasAbilityToDropElementsInside(this)) {
                     mw.wysiwyg.modify(this, function(){
                       this.innerHTML = '<p class="element">'+this.innerHTML+'&nbsp;</p>';
                     });
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
    modify:function(el,callback){
        var curr = mw.askusertostay;
        if(typeof el === 'function'){
          var callback = el;
          callback.call();
        }
        else{
          callback.call(el);
        }
        mw.askusertostay = curr;
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
        var i,
            all = mwd.getElementsByClassName('edit'),
            len = all.length;
        for( ; i<len ; i++ ) { all[i].contentEditable = false; }
      }
      else{
         mw.$(".edit [contenteditable='true'], .edit").removeAttr('contenteditable');
      }
    },
    validateEditForIE:function(target){
        if($(target).hasClass("edit")){ return true; }
        var arr = [];
        mw.tools.foreachParents(target, function(loop){
            arr.push(this.className);
            if($(this).hasClass("module")){mw.tools.stopLoop(loop)}
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
                !el.isContentEditable ? el.contentEditable = true : '' ;
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


    hide_drag_handles:function(){
         mw.$(".mw-wyswyg-plus-element,.mw_handle_row").hide();
    },
    show_drag_handles:function(){
        mw.$(".mw-wyswyg-plus-element,.mw_handle_row").show();
    },

    _external:function(){
      var external = mwd.createElement('div');
      external.className='wysiwyg_external';
      mwd.body.appendChild(external);



      return external;
    },
    isSelectionEditable:function(){
        var node = window.getSelection().focusNode;
        if(node===null){ return false;}
        if(node.nodeType === 1){
           return node.isContentEditable;
        }
        else{
           return node.parentNode.isContentEditable;
        }
    },
    execCommand:function(a,b,c){
        try{  // 0x80004005
            if(document.queryCommandSupported(a) && mw.wysiwyg.isSelectionEditable()){
                var b = b || false;
                var c = c || false;
                if(window.getSelection().rangeCount>0){
                   if($.browser.mozilla && !mw.is.ie){
                      mwd.designMode = 'on';
                      // For Firefox (NS_ERROR_FAILURE: Component returned failure code: 0x80004005 (NS_ERROR_FAILURE) [nsIDOMHTMLDocument.execCommand])
                   }
                   mwd.execCommand(a,b,c);
                   if($.browser.mozilla && !mw.is.ie){
                      mwd.designMode = 'off'
                   }
                }
                var node = window.getSelection().focusNode;
                if(node!==null && mw.loaded){
                    mw.wysiwyg.change(node);
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

       if(mw.settings.liveEdit){
            mw.wysiwyg.execCommand('enableObjectResizing', false, 'false');
            mw.wysiwyg.execCommand('2D-Position', false, false);
            mw.wysiwyg.execCommand("enableInlineTableEditing", null, false);
       }
   },
   paste:function(e){
        var clipboard = e.clipboardData || mww.clipboardData;
        if(typeof clipboard !== 'undefined' && typeof clipboard.getData === 'function' && mw.wysiwyg.editable(e.target)){
            if(!mw.is.ie){
               var html = clipboard.getData('text/plain');
              // var xhtml =  clipboard.getData('text/html');
            }
            else{
              var html = clipboard.getData('text');
            }

            if(!!html) {
				if(typeof mw.form != 'undefined'){
					var is_link = mw.form.validate.url(html);
					if(is_link){
						 var html = "<a href='" + html+ "'>" + html+ "</a>";
						 mw.wysiwyg.insert_html(html);
						 e.preventDefault();
					}
				}

             // var html = "<p class='element'>" + html.replace(/(\r\n|\n|\r)/gm, "</p><p class='element'>") + "</p>";
//              mw.wysiwyg.insert_html(html);
//              e.preventDefault();

            }
        }
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
      var items = mw.$(".element").not(".module");
      mw.$(".mw_editor").hover(function(){$(this).addClass("editor_hover")}, function(){$(this).removeClass("editor_hover")});
    },
    deselect:function(s){
      var s = s || window.getSelection();
      s.empty ? s.empty() : s.removeAllRanges();
    },
    editors_disabled:false,
    enableEditors:function(){
        mw.$(".mw_editor, #mw_small_editor").removeClass("mw-editor-disabled");
        mw.wysiwyg.editors_disabled = false;
    },
    disableEditors:function(){
      /*  mw.$(".mw_editor, #mw_small_editor").addClass("mw-editor-disabled");
       mw.wysiwyg.editors_disabled = false;   */
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
              mw.event.cancel(e, true);
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
      $(mwd.body).bind('mouseup', function(event){
        if(event.target.isContentEditable){
          mw.wysiwyg.check_selection(event.target);
        }
      });
      $(mwd.body).bind('noop', function(event){

        if( event.keyCode == 46  && event.type=='keydown'){
            mw.tools.removeClass(mw.image_resizer, 'active');
            mw.wysiwyg.change('.element-current');
        }
          if(event.type == 'keydown'){

           if(mw.tools.isField(event.target) || !event.target.isContentEditable){
             return true;
           }
           var sel = window.getSelection();
           if(event.keyCode == 13) {    /*
              mw.wysiwyg.checkForTextOnlyElements(event);
              if(event.target.isContentEditable && !mw.tools.isField(event.target)){
                var commonName = mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer).nodeName;
                if(commonName!='P' && !event.ctrlKey && !event.shiftKey){
                  var id = 'temp'+mw.random();
                  mw.wysiwyg.insert_html('<b id="'+id+'">&nbsp;</b>');
                  var br = mwd.createElement('br');
                  br.id = id;
                  mw.$("#"+id).replaceWith(br);
                  mw.wysiwyg.cursorToElement(br, 'after');
                  event.preventDefault();
                  return false;
                }
              }  */
           }
           if(sel.rangeCount > 0){
           var r = sel.getRangeAt(0);
           if(event.keyCode == 9 && !event.shiftKey && sel.focusNode.parentNode.iscontentEditable && sel.isCollapsed){   /* tab key */
             mw.wysiwyg.insert_html('&nbsp;&nbsp;&nbsp;&nbsp;');
             return false;
           }
           if(event.keyCode == 46 || event.keyCode == 8){
              if(!mw.settings.liveEdit){
                return true;
              }
              if(typeof mw.current_element !== 'undefined' && mw.current_element.innerHTML == ''){
                   $(mw.current_element).remove();
              }
              if( r.cloneContents().querySelector(".module") !== null ||
                    mw.tools.hasClass(r.commonAncestorContainer, 'module') ||
                    mw.tools.hasParentsWithClass(r.commonAncestorContainer, 'module')){

                      if(sel.focusOffset > 0 && event.keyCode == 8 ){
                        return true;
                      }

                      if(sel.focusOffset < r.commonAncestorContainer.length && event.keyCode == 46 ){
                        return true;
                      }
                  return false;
              }
              var n = sel.focusNode.nodeType !== 3 ? sel.focusNode : sel.focusNode.parentNode;
              if(mw.tools.hasClass(n, 'module') || mw.tools.hasParentsWithClass(n, 'module')){
                return false;
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
         mw.smallEditorCanceled = true;
         mw.smallEditor.css({
            visibility:"hidden"
         });
         if(e.target.isContentEditable && !mw.tools.isField(e.target)){
             mw.tools.addClass(this, 'isTyping');
             if(mw.tools.isEmpty(e.target)){
                e.target.innerHTML = '&zwnj;&nbsp;';
             }
             if(e.keyCode == 13) {
                 mw.$(".element-current").removeClass("element-current");
                 var el = mwd.querySelectorAll('.edit .element'), l = el.length, i = 0;
                 for( ; i<l; i++ ){
                    if( el[i].id == ''){
                        el[i].id = 'row_' + mw.random();
                    }
                 }
                 e.preventDefault();
                 if(!e.shiftKey){
                   var p = mw.wysiwyg.findTagAcrossSelection('p');
                 }
             }
         }

         if(e.target.isContentEditable
            && !e.shiftKey
            && !e.ctrlKey
            && e.keyCode !== 27
            && e.keyCode !== 116
			&& e.keyCode !== 17
            && (e.keyCode < 37 || e.keyCode > 40)){
            mw.wysiwyg.change(e.target);
         }

      });
    },
    change:function(el){
         if(typeof el === 'string'){ var el = mwd.querySelector(el); }
         var target = null;
         if(mw.tools.hasClass(el, 'edit')) {
            mw.tools.addClass(el, 'changed');
            var target = el;
         }
         else if(mw.tools.hasParentsWithClass(el, 'edit')) {
           var target = mw.tools.firstParentWithClass(el, 'edit');
           mw.tools.addClass(target, 'changed');
         }
         if(target !== null){
            mw.tools.foreachParents(target, function(){
              if(mw.tools.hasClass(this, 'edit')){
                   mw.tools.addClass(this, 'changed');
              }
            });
            mw.askusertostay = true;
            mw.drag.initDraft = true;
         }
    },
    validateCommonAncestorContainer:function(c){
        try{   /* Firefox returns wrong target (div) when you click on a spin-button */
            if(typeof c.querySelector === 'function'){
              return c;
            }
            else{
              return mw.wysiwyg.validateCommonAncestorContainer(c.parentNode);
            }
        }
        catch(e){ return null; }

    },
    editable:function(el){
        var el = mw.wysiwyg.validateCommonAncestorContainer(el);
        return el.isContentEditable;
    },
    cursorToElement:function(node, a){
        if(node===null){return false;}
        if(!node){return false;}
        var a = a || 'start';
        var sel = mww.getSelection();
        var r = mwd.createRange();
        sel.removeAllRanges();
        if(a == 'start'){
            r.selectNodeContents(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if(a == 'end'){
            r.selectNodeContents(node);
            r.collapse(false);
            sel.addRange(r);
        }
        else if( a == 'before' ){
            r.selectNode(node);
            r.collapse(true);
            sel.addRange(r);
        }
        else if( a == 'after' ){
            r.selectNode(node);
            r.collapse(false);
            sel.addRange(r);
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
          mw.wysiwyg.change(el);
          return el;
      }
    },
    external_tool:function(el, url){
        var el = mw.$(el).eq(0);
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
        var el = "#mw_editor_font_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontColor");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontbgcolorpicker:function(){
        var el = ".mw_editor_font_background_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontbg");
        $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    fontColor:function(color){
      if(/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
            color = "#" + color;
      }
      if(color == 'none'){
          mw.wysiwyg.execCommand('removeFormat',false, "foreColor");
      } else {
          mw.wysiwyg.execCommand('forecolor', null, color);
      }
    },
    fontbg:function(color){
        if(/^[0-9A-F]{3,6}$/i.test(color) && !color.contains("#")) {
           color = "#" + color;
        }
        if(color == 'none'){
            mw.wysiwyg.execCommand('removeFormat',false, "backcolor");
        } else {
            mw.wysiwyg.execCommand('backcolor', null, color);
        }
    },
    request_change_bg_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
       $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_bg_color : function(color){
       var color = color != 'transparent' ? '#' + color : color;
        mw.$(".element-current").css("backgroundColor", color);
        mw.wysiwyg.change('.element-current');
    },
    request_border_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
       $(mw.wysiwyg.external).find("iframe").width(280).height(320);
    },
    change_border_color : function(color){
        if(color!="transparent"){
          mw.$(".element-current").css(mw.border_which + "Color", "#"+color);
          $(".ed_bordercolor_pick span").css("background", "#"+color);
          mw.wysiwyg.change('.element-current');
        }
        else{
          mw.$(".element-current").css(mw.border_which + "Color", "transparent");
          mw.$(".ed_bordercolor_pick span").css("background", "");
          mw.wysiwyg.change('.element-current');
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
           mw.$(".element-current").css("box-shadow", x+"px " + y + "px "+ blur +"px #"+color);
           mw.$(".ed_shadow_color").dataset("color", color);

        }
        else{
           mw.$(".element-current").css("box-shadow", "0px 0px 6px #"+color);
           mw.$(".ed_shadow_color").dataset("color", color);
        }
        mw.wysiwyg.change('.element-current');
    },
    fontFamily:function(font_name){
         mw.wysiwyg.execCommand('fontname', null, font_name);
    },
    nestingFixes:function(root){  /*
        var root = root || mwd.body;
        var all = root.querySelectorAll('.mw-span-font-size'),
        l = all.length,
        i=0;
        for( ; i<l; i++ ){
          var el = all[i];
          if(el.firstChild === el.lastChild && el.firstChild.nodeType !== 3){
             // $(el.firstChild).unwrap();
          }
        } */
    },
    fontSize:function(a){
        if(window.getSelection().isCollapsed){ return false; }
        mw.wysiwyg.allStatements(function(){
          rangy.init();
          var clstemp = 'mw-font-size-' + mw.random();
          var classApplier = rangy.createCssClassApplier("mw-font-size " + clstemp, true);
          classApplier.applyToSelection();
          var all = mwd.querySelectorAll('.'+clstemp),
              l=all.length,
              i=0;
          for(; i<l;i++){
            all[i].style.fontSize = a+'px';
            mw.tools.removeClass(all[i], clstemp);
            mw.wysiwyg.change(all[i]);
          }

       });
    },
    fontSizePrompt:function(){
        var size = prompt("Please enter font size", "");

        if (size != null) {
            var a = parseInt(size);
            if(a > 0){
            this.fontSize(a);
            }
        }
    },
    resetActiveButtons:function(){
        mw.$('.mw_editor_btn_active').removeClass('mw_editor_btn_active');
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
              if(family_array.length == 1){
                  var fam = font.family;

              } else {
                  //var fam = mw.tools.getFirstEqualFromTwoArrays(family_array, mw.wysiwyg.editorFonts);
                  var fam = family_array.shift();
              }
              var ddval = mw.$(".mw_dropdown_action_font_family");



              if(ddval.length!=0 && ddval.setDropdownValue != undefined){
                    mw.$(".mw_dropdown_action_font_family").setDropdownValue(fam);
              }
         }
    },
    setActiveFontSize:function(node){
        var size = Math.round(parseFloat(mw.CSSParser(node).get.font().size));
		var ddval = mw.$(".mw_dropdown_action_font_size");
		if(ddval.length !=0 && ddval.setDropdownValue != undefined){
            mw.$(".mw_dropdown_action_font_size .mw-dropdown-val").html(size+'px')
		}
    },
    isFormatElement:function(obj){
        var items = /^(div|h[1-6]|p)$/i;
        return items.test(obj.nodeName);
    },
    started_checking:false,
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
    link:function(prepolulate){
        // mw.wysiwyg.save_selection();
         var modal = mw.tools.modal.frame({
          url:"rte_link_editor",
          title:"",
          name:"mw_rte_link",
          template:'basic',
          width:430,
          height:300
        });
        mw.$('iframe', modal.main).bind('change', function(a,b, c, e){
          mw.iframecallbacks[b](c,e);
        });

        var link = false;
        if(typeof(prepolulate) != 'undefined'){
            link = prepolulate;
        }


        if(!! link){
            modal.main.find("iframe").load(function(){
                $(this).contents().find("#customweburl").val(link);

            })
        }
    },
    unlink:function(){
          var sel = window.getSelection();
          if(!sel.isCollapsed){
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
        mw.$("img.element-current").wrap("<a href='" + url + "'></a>");
        mw.wysiwyg.change('.element-current');
    },
    request_media:function(hash, types){
        var types = types || false;
        if( hash == '#editimage' ) { var types = 'images'; }
        var url = !!types?"rte_image_editor?types="+types+''+hash:"rte_image_editor"+hash;
        var modal = mw.tools.modal.frame({
          url:url,
          name:"mw_rte_image",
          width:430,
          height:230,
          template:'mw_modal_basic',
          overlay:true
        });
        modal.overlay.style.backgroundColor = 'white';
    },
    media:function(hash){
        if(mw.settings.liveEdit && typeof mw.target.item === 'undefined') return false;
        var hash = hash || '#insert_html';
        if($("#mw_rte_image").length>0){
           $("#mw_rte_image").remove();
        }
        else{
          if(mw.wysiwyg.isSelectionEditable() || mw.$(mw.target.item).hasClass("image_change") || mw.$(mw.target.item.parentNode).hasClass("image_change") || mw.target.item === mw.image_resizer){
              mw.wysiwyg.save_selection();
              mw.wysiwyg.request_media(hash);
          }
        }
    },
    request_bg_image:function(){
      mw.wysiwyg.request_media('#set_bg_image');
    },
    set_bg_image:function(url){
      mw.$(".element-current").css("backgroundImage", "url(" + url + ")");
      mw.wysiwyg.change('.element-current');
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
      if(!!window.MSStream){
         mw.wysiwyg.restore_selection();
        if(mw.wysiwyg.isSelectionEditable()){
            var range = window.getSelection().getRangeAt(0);
            var el = mwd.createElement('span');
            el.innerHTML = html;
            range.insertNode(el);
            $(el).replaceWith(el.innerHTML);
        }
      }
      else{
        if(!document.selection){
         mw.wysiwyg.execCommand('inserthtml', false, html);
        }
        else{
          document.selection.createRange().pasteHTML(html)
        }
      }
      if(isembed){
        var el = mwd.getElementById(id);
        el.parentNode.contentEditable = false;
        $(el).replaceWith(frame);
      }
      mw.wysiwyg.change(mw.wysiwyg.validateCommonAncestorContainer(window.getSelection().getRangeAt(0).commonAncestorContainer));
    },
    selection_length:function(){
      var n = window.getSelection().getRangeAt(0).cloneContents().childNodes,
          l = n.length,
          i=0;
      var final = 0;
      for(;i<l;i++){
        var item = n[i];
        if(item.nodeType === 1){
            var final = final + item.textContent.length;
        }
        else if(item.nodeType === 3){
            var final = final + item.nodeValue.length;
        }
      }
      return final;
    },
    insert_image:function(url){
        var id = 'image_' + mw.random();
        var img = '<img id="'+id+'" contentEditable="true" class="element" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        mw.settings.liveEdit?mw.$("#"+id).attr("contenteditable", false):'';
        mw.$("#"+id).removeAttr("_moz_dirty");
        mw.wysiwyg.save_selection();
        mw.wysiwyg.change(mwd.getElementById(id));
        return id;
    },
    save_selection:function(a){
        var a = a || false;
        var selection = window.getSelection();
        if (selection.rangeCount > 0 ){
          var range = selection.getRangeAt(0);
        }
        else{
          var range = mwd.createRange();
          range.selectNode(mwd.querySelector('.edit .element'));
        }
        mw.wysiwyg.selection = {}
        mw.wysiwyg.selection.sel     = selection;
        mw.wysiwyg.selection.range   = range;
        mw.wysiwyg.selection.element = $(mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer));
    },
    restore_selection:function(){
        if(!!mw.wysiwyg.selection){
            mw.wysiwyg.selection.element.attr("contenteditable", "true");
            mw.wysiwyg.selection.element.focus();
            mw.wysiwyg.selection.sel.removeAllRanges();
            mw.wysiwyg.selection.sel.addRange(mw.wysiwyg.selection.range)
        }
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
      if(!window.MSStream){
         mw.wysiwyg.execCommand('FormatBlock', false, '<' + command + '>');
      }
      else{
        var sel = window.getSelection();
        if(mw.wysiwyg.isSelectionEditable()){
          var c = mw.wysiwyg.validateCommonAncestorContainer(sel.getRangeAt(0).commonAncestorContainer);
          mw.tools.setTag(c, command);
          mw.wysiwyg.change(c)
        }
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
    fontFamiliesExtended:[],
    fontFamiliesTemplate:[],



    initFontSelectorBox:function(){
        mw.wysiwyg.initFontFamilies();

        var l = mw.wysiwyg.fontFamilies.length, i = 0, html = '';
        for(; i<l; i++){
            html += '<li value="'+mw.wysiwyg.fontFamilies[i]+'"><a style="font-family:'+mw.wysiwyg.fontFamilies[i]+'" href="javascript:;">'+mw.wysiwyg.fontFamilies[i]+'</a></li>'
        }

        var l = mw.wysiwyg.fontFamiliesTemplate.length, i = 0;
        for(; i<l; i++){
            if(mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesTemplate[i]) === -1){
            html += '<li value="'+mw.wysiwyg.fontFamiliesTemplate[i]+'"><a style="font-family:'+mw.wysiwyg.fontFamiliesTemplate[i]+'" href="javascript:;">'+mw.wysiwyg.fontFamiliesTemplate[i]+'</a></li>'
            }
        }
        var l = mw.wysiwyg.fontFamiliesExtended.length, i = 0;
        for(; i<l; i++){
            if(mw.wysiwyg.fontFamilies.indexOf(mw.wysiwyg.fontFamiliesExtended[i]) === -1){
            html += '<li value="'+mw.wysiwyg.fontFamiliesExtended[i]+'"><a style="font-family:'+mw.wysiwyg.fontFamiliesExtended[i]+'" href="javascript:;">'+mw.wysiwyg.fontFamiliesExtended[i]+'</a></li>'
            }
        }

        mw.$(".mw_dropdown_action_font_family ul").empty().append(html);

        $(".mw_dropdown_action_font_family").unbind('change');
        $(".mw_dropdown_action_font_family").on('change', function(){
            var val = $(this).getDropdownValue();
            mw.wysiwyg.fontFamily(val);
        });


        $( ".mw_dropdown_action_font_family" ).each(function() {
            mw.$("[value]", $(this)).bind('mousedown', function (event) {
                $(mw.tools.firstParentWithClass(this, 'mw-dropdown')).setDropdownValue(this.getAttribute('value'), true);
                return false;
            });
        });
    },

    initFontFamilies:function(){
        var body_font = window.getComputedStyle(mwd.body, null).fontFamily.split(',')[0].replace(/'/g, "").replace(/"/g, '');
        if(mw.wysiwyg.fontFamilies.indexOf(body_font) === -1){
             mw.wysiwyg.fontFamilies.push(body_font);
        }

        var scan_for_fonts = ['h1', 'h2', 'h3', 'h4', 'h5','p'];

        $.each(scan_for_fonts, function( index, value ) {
            var sel = mw.$(value+':first');
            if (sel.length > 0) {
                var body_font = window.getComputedStyle(sel[0], null).fontFamily.split(',')
                $.each(body_font, function( font_index, fvalue ) {
                    var font_value = fvalue;
                    font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
                    if(mw.wysiwyg.fontFamilies.indexOf(font_value) === -1){
                        mw.wysiwyg.fontFamilies.push(font_value);
                    }
                });
            }
        });
    },
    initExtendedFontFamilies:function(string){
        var families = [];
        if(typeof(string) == 'string'){
            families = string.split(',')
        } else if(typeof(string) == 'object'){
            families = string
        }
        $.each(families, function( font_index, fvalue ) {
            var font_value = fvalue;
            font_value = font_value.replace(/'/gi, "").replace(/"/gi, '');
            if(mw.wysiwyg.fontFamilies.indexOf(font_value) === -1 && mw.wysiwyg.fontFamiliesExtended.indexOf(font_value) === -1){
                mw.wysiwyg.fontFamiliesExtended.push(font_value);
            }
        });


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
    cleanTables:function(root){
        var toRemove = "tbody > *:not(tr), thead > *:not(tr), tr > *:not(td)",
            all = root.querySelectorAll(toRemove),
            l = all.length,
            i = 0;
        for( ; i<l; i++){
          $(all[i]).remove();
        }
        var tables = root.querySelectorAll('table'),
            l = tables.length,
            i = 0;
        for( ; i<l; i++ ){
            var item = tables[i],
                l = item.children.length,
                i = 0;
            for( ; i<l; i++){
                var item = item.children[i];
                if(typeof item !== 'undefined' && item.nodeType !== 3){
                    var name = item.nodeName.toLowerCase();
                    var posibles = "thead tfoot tr tbody col colgroup";
                    if(!posibles.contains(name)){
                       $(item).remove();
                    }
                }
            }
        }
    },
    cleanHTML:function(root){
      var root = root || mwd.body;
      mw.tools.foreachChildren(root, function(){
        if(mw.wysiwyg.hasContentFromWord(this)){
            this.innerHTML = mw.wysiwyg.clean_word(this.innerHTML);
        }
        mw.wysiwyg.cleanTables(this);
      });
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
                mw.wysiwyg.change(node);
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
    mw.wysiwyg.initFontSelectorBox();

    //$(".mw_dropdown_action_font_family").bind('change', function(){
    //    var val = $(this).getDropdownValue();
    //    mw.wysiwyg.fontFamily(val);
    //});

  mw.$(".mw_dropdown_action_font_size").change(function(){
      var val = $(this).getDropdownValue();
      mw.wysiwyg.fontSize(val);
      mw.$('.mw-dropdown-val', this).append('px');
  });
  mw.$(".mw_dropdown_action_format").change(function(){
      var val = $(this).getDropdownValue();
      mw.wysiwyg.format(val);
  });
  mw.wysiwyg.nceui();
  mw.smallEditor = mw.$("#mw_small_editor");
  mw.smallEditorCanceled = true;
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
    var hp = mwd.getElementById('mw-history-panel');
    if( hp !== null && hp.style.display != 'none'){
        if(!hp.contains(target)){
            hp.style.display = 'none';
            mw.$("#history_panel_toggle").removeClass('mw_editor_btn_active');
        }
    }
  });

  mw.wysiwyg.editorFonts = [];

});


$(window).load(function(){



  mw.$("#wysiwyg_insert").bind("change", function(){
     if(mw.wysiwyg.isSelectionEditable()){
          var val = $(this).getDropdownValue();
          if( val == 'hr' ){
              mw.wysiwyg._do('InsertHorizontalRule');
          }
          else if( val == 'box' ){
              var div = mw.wysiwyg.applier('div', 'mw-ui-box mw-ui-box-content element');
              if(mw.wysiwyg.selection_length() <= 2){
                 $(div).append("<p>&nbsp;</p>");
              }
          }
          else if( val == 'pre' ){
              var div = mw.wysiwyg.applier('pre', '');
              if(mw.wysiwyg.selection_length() <= 2){
                 $(div).append("&nbsp;");
              }
          } else if( val == 'code' ){
              var div = mw.wysiwyg.applier('code', '');
          }else if( val == 'insert_html' ){

  			var new_insert_html = prompt("Paste your html code in the box");
  			if (new_insert_html!=null){
  				 var div = mw.wysiwyg.applier('div');
  				 div.innerHTML = new_insert_html;
  			  }
          }
          else if( val === 'table' ){
               var el = mw.wysiwyg.applier('div', 'element', {width:"100%"});
               el.innerHTML = '<table class="mw-wysiwyg-table"><tbody><tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr><tr><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td><td onclick="mw.inline.setActiveCell(this, event);" onkeyup="mw.inline.setActiveCell(this, event);">Lorem Ipsum</td></tr></tbody></table>';
               el.querySelector('table').setAttribute('onclick', 'mw.inline.tableController(this, event);');
          }
          else if( val === 'quote' ){
              var div = mw.wysiwyg.applier('blockquote', 'element');
              $(div).append("<cite>By Lorem Ipsum</cite>");
          }
     }
  });

  $(window).bind("keydown mousedown mouseup", function(e){
      if(e.type =='keydown') {
        if(e.keyCode == 13){
          var field = mw.tools.mwattr(e.target, 'field');
          if( field == 'title' ){
            e.preventDefault();
          }
        }
        if(e.ctrlKey) {
              var code = e.keyCode;
              if( code === 66 ){
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
      }
     if(!nodes[i].pasteBinded && !mw.tools.hasParentsWithClass(nodes[i], 'edit')){
       nodes[i].pasteBinded = true;
       nodes[i].addEventListener("paste", function(e){

          mw.wysiwyg.paste(e);
       });
     }

  }
});



mw.linkTip = {
    init:function(root){
      if(root === null || !root){ return false; }
      mw.$(root).bind('mousedown', function(e){
         var node = mw.linkTip.find(e.target);
         if(!!node){
             mw.linkTip.tip(node);
         }
         else{
           mw.$('.mw-link-tip').hide();
         }
      });
    },
    find:function(target){
        if(mw.tools.hasClass(target, 'module') || mw.tools.hasParentsWithClass(target, 'module')){
           return undefined;
        }
        else if(target.nodeName === 'A'){
            return target;
        }
        else if(mw.tools.hasParentsWithTag(target, 'a')){
          return mw.tools.firstParentWithTag(target, 'a');
        }
        else { return undefined; }
    },
    tip:function(node){
        if(!mw.linkTip._tip){
           var content = '<a href="'+node.href+'" class="mw-link-tip-link">'+node.href+'</a><span>-</span><a edit-href="'+node.href+'" href="javascript:;" class="mw-link-tip-edit">Edit</a>';
           mw.linkTip._tip = mw.tooltip({content:content, position:'bottom-center', skin:'dark',element:node});
           $(mw.linkTip._tip).addClass('mw-link-tip');
           mw.$('.mw-link-tip-edit, .mw-link-tip-link').click(function(){
               var prepolulate = '';
               if($(this).hasClass('mw-link-tip-edit')){
                   prepolulate =  $(this).attr('edit-href');
               } else {
                   prepolulate =  $(this).attr('href');

               }
             mw.wysiwyg.link(prepolulate);
             mw.$('.mw-link-tip').hide();
             return false;
           });
        }
        else{
           mw.$('.mw-link-tip-link', mw.linkTip._tip).attr('href', node.href).html(node.href);
           mw.$('.mw-link-tip-edit', mw.linkTip._tip).attr('edit-href', node.href);
           mw.tools.tooltip.setPosition(mw.linkTip._tip, node, 'bottom-center');
           mw.$('.mw-link-tip').show();
        }
    }
  }








