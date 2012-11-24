mw.files = {
    settings:{
        filetypes:"png,gif,jpg,jpeg,tiff,bmp",
        url:mw.settings.upload_url
    },
    what_is_dragging:function(event){
        var types = event.dataTransfer.types;
        var g = {}
        g.toreturn = '';
        for(var obj in types){
          var item = types[obj];
          if(item.contains('text/plain') || item.contains('text/html')){
            g.toreturn = 'html';
            break;
          }
          else if(item.contains('Files')){
            g.toreturn = 'file';
            break;
          }
        }
        return g.toreturn;
    },
    drag_from_pc:function(obj){
        var settings = $.extend({}, mw.files.settings, obj);
        if(window.FileReader){
            $(settings.selector).each(function(){
                var el = $(this);
                this.addEventListener('dragover', function(event){
                    event.stopPropagation();
                    event.preventDefault();
                    event.dataTransfer.dropEffect = 'copy';
                    if(!this.checked){
                      this.checked=true;
                      var what = mw.files.what_is_dragging(event);
                      if(what=='file'){
                         $(this).addClass("drag_files_over");
                      }
                    }
                }, false);
                this.addEventListener('dragleave', function(event){
                  this.checked=false;
                    if(event.dataTransfer){
                        $(this).removeClass("drag_files_over");
                    }
                }, false);
                this.addEventListener('drop', function(event){
                   this.checked=false;
                   $(this).removeClass("drag_files_over");
                   mw.log(event.dataTransfer);
                    event.stopPropagation();
                    event.preventDefault();

                    var files = event.dataTransfer.files;

                    var len = files.length;
                    var count = 0;
                    var all = {}
                    typeof settings.filesadded == 'function' ? settings.filesadded.call(files) : '';
                    $.each(files, function(i){
                        var file = this;
                        var is_valid =  mw.files.validator(file.name, settings.filetypes);
                        if(is_valid){
                            mw.files.ajax_uploader(file, {url:settings.url}, function(){
                               count+=1;
                               typeof settings.fileuploaded === 'function' ? settings.fileuploaded.call(this) : '';
                               all['item_'+i] = this;
                               if(count==len) {
                                 if(typeof settings.done == 'function') {
                                     settings.done.call(all);
                                 }
                               }
                            });
                        }
                        else{
                          count+=1;
                          typeof settings.skip == 'function' ? settings.skip.call(file) : '';
                          if(count==len) {
                             if(typeof settings.done == 'function') {
                                 settings.done.call(all);
                             }
                           }
                        }
                    });
                }, false);
            });
        }
    },

    image_url_test:function(url, valid, invalid){
        var url = url.replace(/\s/gi,'');
        if(url.length<6){
            typeof invalid =='function'? invalid.call(url) : '';
            return false;
        }
        else{
          if(!url.contains('http')){var url = 'http://'+url}
          if(!window.ImgTester){
              window.ImgTester = new Image();
              document.body.appendChild(window.ImgTester);
              window.ImgTester.className = 'semi_hidden';
              window.ImgTester.onload = function(){
                typeof valid =='function'? valid.call(url) : '';
              }
              window.ImgTester.onerror = function(){
                typeof invalid =='function'? invalid.call(url) : '';
              }
          }
          window.ImgTester.src = url;
        }
    },
    url_to_base64:function(url, done){
      var proxy = new Image();
      document.body.appendChild(proxy);
      proxy.className = 'semi_hidden';
      proxy.onload = function(){
        var canvas = document.createElement("canvas");
        canvas.width = $(proxy).width();
        canvas.height = $(proxy).height();
        var ctx = canvas.getContext("2d");
        ctx.drawImage(proxy, 0, 0);
        var data = canvas.toDataURL("image/png");
        done.call(data);
      }
      proxy.src = url;
    },
    processer : function(file, callback){ //to read the file before upload
          var reader = new FileReader();
          var toreturn = {}

          toreturn.name = file.name;
          toreturn.type = file.type;
          toreturn.size = file.size;
          toreturn.extension = file.name.split('.').pop();

          if(file.type.contains("image")){
             reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn);
  		     }
             reader.readAsDataURL(file);
          }
          else if(file.type.contains("txt") ||
                  file.type.contains("rtf") ||
                  file.type.contains("html") ||
                  file.type.contains("htm")){
            reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn);
  		    }
            reader.readAsText(file,"UTF-8");
          }
          else{
             reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn);
  		     }
             reader.readAsDataURL(file);
          }
    },
    browser_connector:function(element, uploader){
        var el = $(element);
        var uploader = $(uploader);
        if(!$.browser.msie){
            el.click(function(){
              uploader.click();
            });
        }
        else{
          $(element).mouseenter(function(){
               var offset = el.offset();
               var w = el.outerWidth();
               var h = el.outerHeight();
               var z = parseFloat(el.css("zIndex"));
               uploader.css({
                  top:offset.top,
                  left:offset.left,
                  width:w,
                  height:h,
                  zIndex:z+1
               });
          });
        }
    },
    validator:function(file_name, extensions_string){
            var filetype = file_name.split('.').pop();
            if(extensions_string.contains(filetype)){
                return true;;
            }
            else{
                return false;
            }
    },
    browser:function(obj){
        var settings = typeof obj=='object' ? $.extend({}, mw.files.settings, obj) : $.extend({}, mw.files.settings);
        var g = {};
        g.toreturn = false;
        var u = document.createElement('input');
        u.type = 'file';
        u.name = 'files_'+mw.random();
        u.multiple = 'multiple';
        u.className = !$.browser.msie?'semi_hidden':'msie_uploader';
        document.body.appendChild(u);
        u.validate = function(){
          this.filetypes = settings.filetypes;
          var el = u;
          if(el.files){
            var files = el.files;
            var len = files.length;

            $.each(files, function(i){
                 var is_valid = mw.files.validator(this.name, settings.filetypes);
                 if(is_valid){
                   if((i+1)==len){
                      g.toreturn = true;

                   }
                 }
                 else{
                   g.toreturn = false;
                 }
            });
          }
          else{ // browser has no filereader;
            var is_valid = mw.files.validator(this.value, settings.filetypes);
            if(is_valid){
                g.toreturn = true;
            }
            else{
                g.toreturn = false;
            }
          }
          return g.toreturn;
        }
        return u;
    },
    iframe_uploader:function(input_file, url, callback){
      if($(input_file).parents("form").length==0){
          var target = 'target_' + mw.random();
          var form = document.createElement('form');
          form.enctype = 'multipart/form-data';
          form.action = url;
          form.method = 'post';
          form.target = target;
          form.id = "form_"+target;
          $(form).append(input_file);
          var frame = document.createElement('iframe');
          frame.src= "javascript:false;";
          frame.className= 'semi_hidden';
          frame.id = target;
          frame.name = target;
          frame.onload = function(){
            if(!$(frame).hasClass("submitet")){
                $(frame).addClass("submitet");
                $("#form_"+target).submit();
            }
            else{
              var data = frame.contentDocument.body.innerHTML;
              var json = $.parseJSON(data);
              callback.call(json);
            }
          }
          form.appendChild(frame);
          document.body.appendChild(form);
      }
      else{
         $(input_file).parents("form").submit();
      }
    },
    ajax_uploader:function(file, xobj, callback){
       var obj = typeof xobj=='object' ? $.extend({}, mw.files.settings, xobj) : $.extend({}, mw.files.settings);
       var data = new FormData();
       data.append('file', file);
      $.ajax({
        url: obj.url,
        data: data,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function(data){
          var json = $.parseJSON( data );
          callback.call(json);
        }
      });
    },
    upload:function(uploader, object, single_file_uploaded, all_uploaded){
        var obj = typeof object=='object' ? $.extend({}, mw.files.settings, object) : $.extend({}, mw.files.settings);
        if(uploader.files && obj.upload_type!='iframe'){
            var files = uploader.files;
            var len = files.length;
            var all = {};
            var count = 0;
            $.each(files, function(i){
                 mw.files.ajax_uploader(this, obj, function(){
                        count += 1; //increasing after success
                        var json = this;
                        all['item_'+i] = json;
                        if(typeof single_file_uploaded == 'function'){
                           single_file_uploaded.call(json);
                        }
                        if(count==len && typeof all_uploaded == 'function'){
                           all_uploaded.call(all);
                        }
                   });
            });
        }
        else{  // browser has no filereader;
            mw.files.iframe_uploader(uploader, obj.url, function(){
              if(typeof single_file_uploaded == 'function'){
                single_file_uploaded.call(this);
              }
              if(typeof all_uploaded == 'function'){
                all_uploaded.call(this);
              }
            });
        }
    },
    pl:function(obj){
        var obj = $.extend({}, mw.settings.plupload, obj);
        var uploader = new plupload.Uploader({
      	runtimes : obj.runtimes,
      	browse_button : obj.browse_button,
      	container: obj.container,
      	max_file_size :  obj.max_file_size,
      	url : obj.url,
      	flash_swf_url : obj.flash_swf_url,
          multi_selection:obj.multi_selection,
          filters:obj.filters,
          resize:obj.resize
        });
        return uploader;
    }
}

mw.filechange = function(input, callback){
    if(!$.browser.msie){
      $(input).change(function(){
        callback.call(this);
      });
    }
    else{  //msie does not support 'change' event for <input type='file' />
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