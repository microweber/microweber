/* A Cool HTML5 WYSIWYG Editor */


String.prototype.toFragment = function(){
  var f = document.createDocumentFragment();
  f.appendChild(document.createTextNode(this));
  return f;
}
String.prototype.toDomObject = function(){

  return f;
}

mw.checker = mwd.createElement('div');
mw.checker.className = 'mw-checker semi_hidden';
mwd.body.appendChild(mw.checker);


mw.wysiwyg = {
    init_editables : function(module){
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
    _external:function(){  //global element for handelig the iframe tools
      var external = mwd.createElement('div');
      external.className='wysiwyg_external';
      mwd.body.appendChild(external);
      return external;
    },
    execCommand:function(a,b,c){
     // if(mw.wysiwyg.isThereEditableContent){
        if(document.queryCommandSupported(a)){
        var b = b || false;
        var c = c || false;
         $.browser.mozilla?mwd.designMode = 'on':'';  // for firefox
         mwd.execCommand(a,b,c);
         $.browser.mozilla?mwd.designMode = 'off':'';
        }
     /* }
      else if(mwd.querySelector(".element-current")!==null){
        $(el).attr('contenteditable','true');
        mw.wysiwyg.isThereEditableContent = true;
        el.focus();
        mw.wysiwyg.select_all(el);
        mw.wysiwyg.execCommand(a,b,c);
      } */
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
      mw.wysiwyg.checker = mw.wysiwyg._checker();
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


      if (mwd.createElement("input").webkitSpeech !== undefined) { /*
         $(".mw_editor").after('<input id="vtest" style="width: 15px; height:20px;border: 0px;background-color:transparent;" type="text" x-webkit-speech="x-webkit-speech" />');
         $("#vtest").mouseenter(function(){
             mw.wysiwyg.save_selection();
         });
         $("#vtest").change(function(){
             mw.wysiwyg.restore_selection();
             mw.wysiwyg.insert_html(this.value);
         });
      */ }

    },
    init:function(){

      var mw_editor_btns = mw.$(".mw_editor_btn");
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
              mw.wysiwyg.check_selection();
              $(this).removeClass("mw_editor_btn_mousedown");
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
      $(mwd.body).keyup(function(event){
         mw.wysiwyg.check_selection();

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
    },
    createelement : function(){
       var el = mw.wysiwyg.applier('div', 'mw_applier element');
    },
    fontcolorpicker:function(){
        var el = ".mw_editor_font_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontColor");
        $(mw.wysiwyg.external).find("iframe").width(360).height(180);
    },
    fontbgcolorpicker:function(){
        var el = ".mw_editor_font_background_color";
        mw.wysiwyg.external_tool(el, mw.external_tool('color_picker') + "#fontbg");
        $(mw.wysiwyg.external).find("iframe").width(360).height(180);
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
    does_selection_has:function(range,tagname){
      try{
        return $(range.commonAncestorContainer).parents(tagname).length>0 || range.commonAncestorContainer.getElementsByTagName(tagname).length>0;
      }catch(err){return false}
    },
    get_format:function(range){
       try{
        return $(range.commonAncestorContainer).parents("p,h1,h2,h3,h4,h5,h6")[0].tagName.toLowerCase();
      }catch(err){return false}
    },
    get_list:function(range){
       try{
        return $(range.commonAncestorContainer).parents("ul,ol")[0].tagName.toLowerCase();
      }catch(err){return false}
    },
    get_size:function(range){
      try{
        var font_size = $(range.commonAncestorContainer).parents("font").attr("size");
        return font_size!=undefined?font_size:'-1';
      }catch(err){return false}
    },
    get_font_family:function(range){
       try{
        return $(range.commonAncestorContainer).parent().css("fontFamily");
       }catch(err){return false}
    },
    _checker:function(){
        var checker = document.createElement('div');
        checker.className='wysiwyg_checker';
        document.body.appendChild(checker);
        return checker;
    },
    get_alignment:function(range){
        try{
          return  $(range.commonAncestorContainer).parent().css("textAlign");
        }catch(err){return false}
    },
    check_selection:function(){
       var selection = window.getSelection();
       if(selection.rangeCount>0){
           var range = selection.getRangeAt(0);
           var selection_clone = range.cloneContents();
           $(mw.wysiwyg.checker).empty().append(selection_clone);
           var checker_tags = $(mw.wysiwyg.checker).find("*");
           wys_is_bold = false;
           wys_is_italic = false;
           wys_is_underlined = false;
           if(checker_tags.length==0){
             wys_is_bold = mw.wysiwyg.does_selection_has(range, 'b') || mw.wysiwyg.does_selection_has(range, 'b');
             wys_is_italic = mw.wysiwyg.does_selection_has(range, 'i') || mw.wysiwyg.does_selection_has(range, 'em');
             wys_is_underlined = mw.wysiwyg.does_selection_has(range, 'u');
             wys_is_link = mw.wysiwyg.does_selection_has(range, 'a');
            }
            else{
               checker_tags.each(function(){
                  var tagname = this.tagName.toLowerCase();
                  if(tagname=='b' || tagname =='strong'){
                       wys_is_bold = true;
                  }
                  if(tagname=='i' || tagname =='em'){
                       wys_is_italic = true;
                  }
                  if(tagname=='u'){
                       wys_is_underlined = true;
                  }
                  if(tagname=='a'){
                       wys_is_link = true;
                  }
               });
           }

          wys_is_bold ? $(".mw_editor_bold").addClass("mw_editor_btn_active") : $(".mw_editor_bold").removeClass("mw_editor_btn_active");
          wys_is_italic ? $(".mw_editor_italic").addClass("mw_editor_btn_active") : $(".mw_editor_italic").removeClass("mw_editor_btn_active");
          wys_is_underlined ? $(".mw_editor_underline").addClass("mw_editor_btn_active") : $(".mw_editor_underline").removeClass("mw_editor_btn_active");
          wys_is_link ? $(".mw_editor_link").addClass("mw_editor_btn_active") : $(".mw_editor_link").removeClass("mw_editor_btn_active");

          var format = mw.wysiwyg.get_format(range);
          var font_size = mw.wysiwyg.get_size(range);
          var font_family = mw.wysiwyg.get_font_family(range);

          $(".mw_dropdown_action_format").setDropdownValue(format);
          $(".mw_dropdown_action_font_size").setDropdownValue(font_size);
          $(".mw_dropdown_action_font_family").setDropdownValue(font_family);

          var the_list = mw.wysiwyg.get_list(range);
          if(the_list=='ul'){
            $(".mw_editor_ul").addClass("mw_editor_btn_active");
            $(".mw_editor_ol").removeClass("mw_editor_btn_active");
          }
          else if(the_list=='ol'){
            $(".mw_editor_ol").addClass("mw_editor_btn_active");
            $(".mw_editor_ul").removeClass("mw_editor_btn_active");
          }
          else{
             $(".mw_editor_ol").removeClass("mw_editor_btn_active");
             $(".mw_editor_ul").removeClass("mw_editor_btn_active");
          }

          var align = mw.wysiwyg.get_alignment(range);
          $(".mw_editor_alignment").removeClass("mw_editor_btn_active");
          if(align.contains("right")) {$(".mw_editor_justifyright").addClass("mw_editor_btn_active");}
          else if(align.contains("center")) {$(".mw_editor_justifycenter").addClass("mw_editor_btn_active");}
          else if(align.contains("justify")) {$(".mw_editor_justifyfull").addClass("mw_editor_btn_active");}
          else {$(".mw_editor_justifyleft").addClass("mw_editor_btn_active");}

      }
    },
    link:function(){
        if($(".mw_editor_link.mw_editor_btn_active").length>0){
          if(mw.wysiwyg.selection_length()>0){
             mw.wysiwyg.execCommand('unlink', null, null);
          }
          else{
            var html = $(mw.target.item).html();
            $(mw.target.item).replaceWith(html);
          }
          mw.wysiwyg.check_selection();
          $(".mw_editor_link.mw_editor_btn_active").removeClass("mw_editor_btn_active");
        }
        else{
          if(mw.wysiwyg.isThereEditableContent()){
             mw.wysiwyg.save_selection();
             mw.tools.modal.frame({
              url:"rte_link_editor",
              title:"Add/Edit Link",
              name:"mw_rte_link",
              width:340,
              height:535
            });
         }
      }
    },
    image_link:function(url){
        $("img.element-current").wrap("<a href='" + url + "'></a>");
    },
    image:function(hash){
        var hash = hash || '';
        if($("#mw_rte_image").length>0){
           $("#mw_rte_image").remove();
        }
        else{
          if(mw.wysiwyg.isThereEditableContent() || mw.target.item.className=='image_change'){
              mw.wysiwyg.save_selection();
              mw.tools.modal.frame({
                url:"rte_image_editor"+hash,
                title:"Upload Picture",
                name:"mw_rte_image",
                width:430
              });
          }
        }
    },
    request_bg_image:function(){
        mw.tools.modal.frame({
          url:"rte_image_editor#set_bg_image",
          title:"Upload Picture",
          name:"mw_rte_image",
          width:430
        });
    },
    set_bg_image:function(url){
      $(".element-current").css("backgroundImage", "url(" + url + ")");
    },
    insert_html:function(html){
      if(!document.selection){
         mw.wysiwyg.execCommand('inserthtml', false, html);
      }
      else{
        document.selection.createRange().pasteHTML(html)
      }
    },
    selection_length:function(){
      return (($(mw.wysiwyg.checker).html()).replace(/\s/g, "")).length;
    },
    insert_link:function(url){
      mw.wysiwyg.restore_selection();
      if(mw.wysiwyg.selection_length()>0){
         mw.wysiwyg.execCommand('createlink', false, url);
      }
      else{
         var html = "<a href='" + url + "'>" + url + "</a>";
         mw.wysiwyg.insert_html(html);
      }

    },
    insert_image:function(url, autoclose){
        var autoclose = autoclose || false;
        var id = 'image_' + mw.random();
        var img = '<img id="'+id+'" class="element element-image" src="' + url + '" />';
        mw.wysiwyg.insert_html(img);
        if(autoclose){
           mw.tools.modal.remove('mw_rte_image');
        }
        $("#"+id).attr("contenteditable", false);
        $("#"+id).removeAttr("_moz_dirty");
        mw.disable_selection("#"+id);
        mw.drag.init("#"+id);
        mw.drag.fix_handles();
        mw.image.resize.init("#"+ id);
        mw.wysiwyg.set_cursor('after', "#"+ id + " img");
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
    set_cursor : function(before_after, element){
        var range = document.createRange();
        if(before_after=='after'){
           var el = $(element)[0].nextSibling;
           var start = 0;
        }
        else if(before_after=='before'){
           var el = $(element)[0].previousSibling;
           var start = el.data.length;
        }
        else if(before_after=='end'){
           var el = element;
           var start = $(element)[0].data.length;
        }
        range.setStart(el,start);
        range.setEnd(el, start);
        range.collapse(true);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
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



  mw.wysiwyg.prepareContentEditable();


});


