/* WYSIWYG Editor */



mw.require('css_parser.js');




mw.wysiwyg = {
    init_editables : function(module){
       if (!window['mwAdmin']) {

        if(mw.is.defined(module)){
            module.contentEditable = false;
            $(module.querySelectorAll(".edit")).each(function(){
               this.contentEditable = true;
                mw.on.DOMChange(this, function(){
                    this.className.indexOf('changed') ==-1 ? $(this).addClass("changed") :'';
                });
            });
        }
        else{
            mw.$("[contenteditable]").removeAttr('contenteditable');
            mw.$(".edit").each(function(){
                mw.on.DOMChange(this, function(){
                    this.className.indexOf('changed') ==-1 ? $(this).addClass("changed") :'';
                });
            });
            mw.$("img, .empty-element, .ui-resizable-handle").each(function(){
                this.contentEditable = false;
            });
            mw.on.moduleReload(function(){
                  mw.wysiwyg.nceui();
            })
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
    execCommand:function(a,b,c){
        try{  // 0x80004005
            if(document.queryCommandSupported(a)){
                var b = b || false;
                var c = c || false;
                if(window.getSelection().rangeCount>0){
                var cac = window.getSelection().getRangeAt(0).commonAncestorContainer;
                var isSelectionInEditable = cac.parentElement.isContentEditable || cac.isContentEditable;
                if(isSelectionInEditable){
                   $.browser.mozilla?mwd.designMode = 'on':'';  // For Firefox (NS_ERROR_FAILURE: Component returned failure code: 0x80004005 (NS_ERROR_FAILURE) [nsIDOMHTMLDocument.execCommand])
                   mwd.execCommand(a,b,c);
                   $.browser.mozilla?mwd.designMode = 'off':'';
                } }
            }
        }
        catch(e){}
    },
    isThereEditableContent:function(){
        return true;
    },
    selection:'',
    _do:function(what){
        mw.wysiwyg.execCommand(what);
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

        if(mw.is.ie){
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
            }
        }
    },
    prepare:function(){
      mw.wysiwyg.external = mw.wysiwyg._external();

      mw.$("#mw-text-editor").bind("mousedown mouseup click", function(event){event.preventDefault()});
      var items = $(".element").not(".module");
      items.bind("paste", function(event){

          var id = "el_"+mw.random();
          var el = "<div id='"+id+"'>&nbsp;</div>";
          mw.wysiwyg.insert_html(el);
          var el = mw.$("#"+id);

         setTimeout(function(){
           var html = el.html();
           var newhtml = mw.wysiwyg.clean_word(html);
           el.html(newhtml)
         },50);
      });

      mw.$(".mw_editor").hover(function(){$(this).addClass("editor_hover")}, function(){$(this).removeClass("editor_hover")});

    },
    init:function(selector){
      var selector = selector || ".mw_editor_btn";
      var mw_editor_btns = mw.$(selector);
      mw_editor_btns.bind("mousedown mouseup click", function(event){
          event.preventDefault();
          if(event.type=='mouseup'){
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
          if(event.type=='mousedown'){
              $(this).addClass("mw_editor_btn_mousedown");
          }
      });
      mw_editor_btns.hover(function(){
        $(this).addClass("mw_editor_btn_hover");
      }, function(){
        $(this).removeClass("mw_editor_btn_hover");
      });
      $(mwd.body).bind('mouseup keyup', function(event){
        if(event.target.isContentEditable){
          mw.wysiwyg.check_selection(event.target);
        }
        else{
           mw.wysiwyg.check_selection();
        }

         if(event.keyCode == 13) {
            //event.preventDefault();
            //mw.wysiwyg.insert_html("<br />");
            //return false;

            mw.$("element-current").removeClass("element-current");
         }
      });
    },
    applier:function(tag, classname, style_object){
      var range = window.getSelection().getRangeAt(0);
      var selectionContents = range.extractContents();
      var el = document.createElement(tag);
      el.className = classname;
      style_object!=undefined?$(el).css(style_object):'';
      el.appendChild(selectionContents);
      range.insertNode(el);
      return el;
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
         mw.wysiwyg.execCommand('backcolor', null, "#"+color);
    },
    request_change_bg_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_bg_color');
       $(mw.wysiwyg.external).find("iframe").width(360).height(180);
    },
    change_bg_color : function(color){
        $(".element-current").css("backgroundColor", "#"+color);
    },
    request_border_color:function(el){
       mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + '#change_border_color');
       $(mw.wysiwyg.external).find("iframe").width(360).height(180);
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
       $(mw.wysiwyg.external).find("iframe").width(360).height(180);
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
           mw.$(".mw_dropdown_action_font_family").setDropdownValue(fam);
         }
    },
    setActiveFontSize:function(node){
        var size = Math.round(parseFloat(mw.CSSParser(node).get.font().size));
        mw.$(".mw_dropdown_action_font_size").setDropdownValue(mw.wysiwyg.editorFontSizes[size]);
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

                 if(common.nodeName !== '#text'){
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
                    var align = mw.CSSParser(common.parentElement).get.alignNormalize();
                    mw.$(".mw_editor_alignment").removeClass('mw_editor_btn_active');
                    mw.$(".mw-align-"+align).addClass('mw_editor_btn_active');
                    mw.wysiwyg.setActiveButtons(common.parentElement);
                    mw.wysiwyg.setActiveFontSize(common.parentElement);
                 }

                 if(mw.wysiwyg.isFormatElement(common)){
                   var format = common.nodeName.toLowerCase();
                   mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                 }
                 else{
                     mw.tools.foreachParents(common, function(loop){
                        if(mw.wysiwyg.isFormatElement(this)){
                            var format = this.nodeName.toLowerCase();
                            mw.$(".mw_dropdown_action_format").setDropdownValue(format);
                            mw.tools.stopLoop(loop);
                        }
                     });
                 }
            }

            if(!!target){
                mw.wysiwyg.setActiveButtons(target);
                //mw.wysiwyg.setActiveFontSize(target);

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
    link:function(){
        if(mw.$(".mw_editor_link.mw_editor_btn_active").length>0){
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
        }
        else{
          if(mw.wysiwyg.isThereEditableContent()){
             mw.wysiwyg.save_selection();
             mw.tools.modal.frame({
              url:"rte_link_editor",
              title:"",
              name:"mw_rte_link",
              template:'mw_modal_simple',
              width:430,
              height:300
            });
         }
      }
    },
    findTagAcrossSelection:function(tag){
          var selection = window.getSelection();
          var range = selection.getRangeAt(0);
          var common = range.commonAncestorContainer;
          var children = range.cloneContents().childNodes, i=0, l=children.length;
          if(l>0){
            for(; i<l;i++){
               if(children[i].nodeName.toLowerCase()==tag){return children[i]}
            }
          }
          var parent = mw.tools.firstParentWithTag(common, [tag]);
          if(!!parent){return parent}
          return false;
    },
    image_link:function(url){
        $("img.element-current").wrap("<a href='" + url + "'></a>");
    },
    request_media:function(hash){
        mw.tools.modal.frame({
          url:"rte_image_editor"+hash,
          //title:"Upload Picture",
          name:"mw_rte_image",
          width:430,
          height:230,
          template:'mw_modal_simple'
        });
    },
    media:function(hash){
        var hash = hash || '#insert_html';
        if($("#mw_rte_image").length>0){
           $("#mw_rte_image").remove();
        }
        else{
          if(mw.wysiwyg.isThereEditableContent() || mw.target.item.className=='image_change'){
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

      var isembed = html.contains('<iframe') || html.contains('<embed') || html.contains('<object');
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
      return window.getSelection().getRangeAt(0).cloneContents().textContent.length;
    },
    fontFX:function(cls){
       mw.wysiwyg.applier('span', cls);
    },
    insert_image:function(url){
        var id = 'image_' + mw.random();
        var img = '<img id="'+id+'" class="element element-image" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        $("#"+id).attr("contenteditable", false);
        $("#"+id).removeAttr("_moz_dirty");
        mw.disable_selection("#"+id);
        if(typeof mw.drag==='object'){
          //mw.drag.init("#"+id);
          //mw.image.resize.init("#"+ id);
        }
        mw.wysiwyg.set_cursor('after', "#"+ id);
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
        mw.wysiwyg.execCommand('FormatBlock', false, '<' + command + '>');
    },
    set_cursor : function(before_after, element){     //return false;      //Currently disabled - gives errors

        var el = $(element)[0];
        var range = document.createRange();
        if(before_after=='after'){
          if($(el).next().length>0) {
            var next = $(el).next()[0];
          }
          else{
            $(el).after("<span></span>");
            var next = $(el).next()[0];
          }
          range.selectNodeContents(next);
          range.collapse(false);
        }
        else if(before_after=='before'){
            if($(el).prev().length>0) {
              var prev = $(el).prev()[0];
            }
            else{
              $(el).before("<div></div>");
              var prev = $(el).prev()[0];
            }
            range.selectNodeContents(prev);
            range.collapse(true);
        }
        else if(before_after=='end'){

        }
        else if(before_after=='beginning'){

        }

        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
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
             });
		  }
        }
        $(textarea).after(iframe);
        $(textarea).hide();

    },
    iframe_editor_old:function(textarea, id){
        var url = mw.external_tool('wysiwyg?id='+id);
        var iframe = mwd.createElement('iframe');
        iframe.width = $(textarea).width();
        iframe.height = $(textarea).height();
        iframe.scrolling = "no";
        iframe.setAttribute('frameborder', 0);
        iframe.src = url;
        iframe.style.resize = 'vertical';
        iframe.onload = function(){
          var b = $(this).contents().find("#mw-iframe-editor-area");
          b[0].contentEditable = true;
          b.bind("blur", function(){
            textarea.value = $(this).html();
          });
        }
        $(textarea).after(iframe);
        $(textarea).hide();
    },
    clean_word:function( html ){

        html = html.replace( /<td([^>]*)>/gi, '<td>' ) ;
        html = html.replace( /<table([^>]*)>/gi, '<table cellspacing="1" cellpadding="1" border="1">' ) ;

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


    mw.wysiwyg.init_editables();

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

  $(window).bind("onElementClick", function(e, el){
    if($(el).hasClass("lipsum")){
       $(el).removeClass("lipsum");
       mw.wysiwyg.select_all(el);
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


