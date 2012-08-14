
mw = {}






mw.simpletabs = function(context){
    var context = context || document.body;
    $(".mw_simple_tabs_nav", context).each(function(){
      var parent = $(this).parents(".mw_simple_tabs").eq(0);
      parent.find(".tab").addClass("semi_hidden");
      parent.find(".tab").eq(0).removeClass("semi_hidden");
      $(this).find("a").eq(0).addClass("active");
      $(this).find("a").click(function(){
          var parent = $(this).parents(".mw_simple_tabs_nav").eq(0);
          var parent_master =  $(this).parents(".mw_simple_tabs").eq(0);
          parent.find("a").removeClass("active");
          $(this).addClass("active");
          parent_master.find(".tab").addClass("semi_hidden");
          var index = parent.find("a").index(this);
          parent_master.find(".tab").eq(index).removeClass("semi_hidden");
          return false;
      });
    });
}

mw.files = {
    drag_from_pc:function(){
        $(".element, .element>*").each(function(){
            var el = $(this);
            this.addEventListener('dragover', function(event){
                event.stopPropagation();
                event.preventDefault();
                event.dataTransfer.dropEffect = 'copy';
            }, false);
            this.addEventListener('drop', function(event){
                event.stopPropagation();
                event.preventDefault();
                var files = event.dataTransfer.files;
                $.each(files, function(){
                    var reader = new FileReader();
                    var file_data = mw.files.processer(reader, this);
                });
            }, false);
        });
    },
    processer : function(reader, file){
          var toreturn = {}
          if(file.type.contains("image")){
             toreturn.type = "image";
             reader.onload = function(e) {
               toreturn.result = e.target.result;
               return toreturn;
  		     }
             reader.readAsDataURL(file);
          }
          else if(file.type.contains("txt") ||
                  file.type.contains("rtf") ||
                  file.type.contains("html") ||
                  file.type.contains("html")){

            toreturn.type = "text";
            reader.onload = function(e) {
               toreturn.result = e.target.result;
               return toreturn;
  		    }
            reader.readAsText(file,"UTF-8");
          }
    },
    browser_settings:{
        accept:"png,gif,jpg,jpeg",
        multiple:true,
        open:false
    },
    browser:function(obj){
        if(obj!=undefined){
            var settings = mw.files.browser_settings;
            $.extend(settings, obj);
        }
        var u = document.createElement('input');
        u.type = 'file';
        u.multiple = 'multiple';
        u.className = 'semi_hidden';
        document.body.appendChild(u);
        settings.open?$(u).click():'';
        return u;
    }
}

mw.filechange = function(input, callback){ //msie does not support change event for <input type='file' />
    if(!$.browser.msie){
      $(input).change(function(){
        callback.call(this);
      });
    }
    else{
       $(input).click(function(){
          var el = this;
          var val = el.value;
          setTimeout(function(){
            if(val!=el.value){
                callback.call(el);
            }
          }, 1);
      });
    }
}


