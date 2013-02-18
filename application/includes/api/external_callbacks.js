

mw.iframecallbacks = {
    insert_link:function(url, target){
      var target = target || '_self';
      mw.wysiwyg.restore_selection();
      if(mw.wysiwyg.selection_length()>0){
         var text = window.getSelection().getRangeAt(0).cloneContents().textContent;
         var html = "<a href='" + url + "' target='"+target+"'>" + text + "</a>";
         mw.wysiwyg.insert_html(html);
      }
      else{
         var name =  mw.tools.get_filename(url);
         var extension = url.split('.').pop();
         var html = "<a href='" + url + "' target='"+target+"'>" + name + "." + extension + "</a>";
         mw.wysiwyg.insert_html(html);
      }
    },
    insert_html:function(html){ return mw.wysiwyg.insert_html(html);},
    insert_image:function(url){ return mw.wysiwyg.insert_image(url);},
    set_bg_image:function(url){ return mw.wysiwyg.set_bg_image(url);},
    fontColor:function(color){ return mw.wysiwyg.fontColor(color);},
    fontbg:function(color){ return mw.wysiwyg.fontbg(color);},
    change_bg_color:function(color){ return mw.wysiwyg.change_bg_color(color);},
    change_border_color:function(color){ return mw.wysiwyg.change_border_color(color);},
    change_shadow_color:function(color){ return mw.wysiwyg.change_shadow_color(color);},
    editimage:function(url){
        mw.image.currentResizing.attr("src", url);
        mw.image.currentResizing.css('height', 'auto');
    },
    add_link_to_menu:function(){

    }

}


