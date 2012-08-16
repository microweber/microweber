if (window.console != undefined) {
	console.log('Microweber Javascript Framework Loaded');
}

/*
 * Microweber - Javascript Framework
 *
 * Copyright (c) Mass Media Group (www.ooyes.net) Licensed under the Microweber
 * license http://microweber.com/license
 *
 */

window.mw = window.mw ? window.mw : {};


mw.random = function(){return Math.floor(Math.random()*(new Date().getTime()));}

String.prototype.contains = function(it) { return this.indexOf(it) != -1; };


mw.required = [];
mw.required_loaded = 0;
mw.require = function(lib_url, callback){
    var libs =  lib_url.replace(/\s/gi,'').split(',');
    var len = libs.length;
    mw.required_loaded = 0;
    for(var i=0;i<len;i++){
        var lib_url = libs[i];
    	var filetype = lib_url.contains(".") ? lib_url.split('.').pop() : 'js';
    	var full_url = lib_url.contains('//') ? lib_url : mw.settings.site_url + "api/js/" + lib_url;
        if(mw.required.indexOf(full_url)==-1){  //if is not already required
            mw.required.push(full_url);
        	if(filetype=='js'){
        		$.getScript(full_url).complete(function(jqXHR, textStatus){
        		  if(textStatus==='success' && typeof callback == 'function'){
                        mw.required_loaded = mw.required_loaded + 1;
        		        if(mw.required_loaded==len){
            		        callback.call(true);
                        }
        		  }
                  if(textStatus.contains("error")){
                    console.error("Error loading the script!");
                  }
        		});
        	}
            else if(filetype=='css'){
             var link = document.createElement('link');
              link.type = 'text/css';
              link.rel = 'stylesheet';
              link.href = full_url;
              document.getElementsByTagName('head')[0].appendChild(link);
              link.onload = function(){
                  mw.required_loaded = mw.required_loaded + 1;
    		      if(mw.required_loaded==len){
        		      callback.call(true);
                  }
              }
            }
       }
       else{
           mw.required_loaded = mw.required_loaded + 1;
           if(mw.required_loaded==len){
                callback.call(true);
           }
       }
    }
}



mw.module = function($vars, $update_element) {
	$.ajax({
		url : '{SITE_URL}api/module',
		type : "POST",
		data : ($vars),
		async : false,
		success : function(resp) {
			$($update_element).html(resp);
			if ($vars.callback != undefined) {
				$vars.callback.call(this);
			}
		}
	});
}

mw.load_module = function($module_name, $update_element) {

	var attributes = {};
	attributes.module = $module_name;

	url1 = '{SITE_URL}api/module';
	$($update_element).load(url1, attributes, function() {
		mw.settings.sortables_created = false;
	});

}

mw.load_layout_element = function($layout_element_name, $update_element) {

	var attributes = {};
	attributes.element = $layout_element_name;

	url1 = '{SITE_URL}api/content/load_layout_element';
	$($update_element).load(url1, attributes, function() {
		mw.settings.sortables_created = false;
	});
}
// mw.reload_module_interval('custom_fields/');
mw.reload_module_interval = function($module_name, $interval) {
	if ($interval == undefined) {
		$interval = 500;
	}

	$interval = parseInt($interval);

	t_reload_module_interval = setInterval("mw.reload_module('" + $module_name + "')", $interval);

}

mw.reload_module = function($module_name) {

	if ($module_name == undefined) {

	} else {
		var module_name = $module_name.toString();
		refresh_modules_explode = module_name.split(",");
		//	alert(refresh_modules_explode);
		for (var i = 0; i < refresh_modules_explode.length; i++) {
			var $module_name = refresh_modules_explode[i];

			if ($module_name != undefined) {
				//	$("div.module").each(
				//$("div.module[mw_params_module='"+$module_name+"']").each(
				//  $mods = $("div.module");

				$mods = $(".module[data-type='" + $module_name + "']", '.edit');
				if ($mods.length == 0) {
					$mods = $($module_name);
					//alert('cant_reload_mod' + $module_name)	;
				}

				$mods.each(function() {

					var mw_params_module = $(this).attr("data-module");
					var mw_params_module_id = $(this).attr("module_id");
					if (mw_params_module != undefined) {
						mw_params_module = mw_params_module.replace(/\\/g, "/");
					} else {
						mw_params_module = $module_name;
					}
					//$all_attr = 	 $.getAttributes('#foo'), true );
					//$all_attr =  $(this).getAttributes();

					mw_params_module_no_adm = mw_params_module.replace(/admin/i, "").replace(/^\/|\/$/g, '');

					var attributes = {};

					$.each(this.attributes, function(index, attr) {
						attributes[attr.name] = attr.value;

					});

					$all_attr = attributes;
					if (window.console != undefined) {
						console.log(mw_params_module_no_adm);
						console.log($module_name);
						console.log(mw_params_module);

					}

					if (mw_params_module == $module_name || mw_params_module_id == $module_name || mw_params_module_no_adm == $module_name) {
						// encoded = $(this).attr("data-params-encoded");
						elem = $(this);

						url1 = mw.settings.site_url + 'api/module/index/reload_module';
						elem.load(url1, $all_attr, function() {
							mw.settings.sortables_created = false;
						});

					}

				});

			}

		}
		if ( typeof init_edits == 'function') {
			//	 mw.settings.sortables_created = false;
			// init_edits();
		}

		//	 $('.mw').trigger('mw_module_reloaded', [$module_name]);

	}

}

mw.clear_cache = function() {
	$.ajax({
		url : '{SITE_URL}ajax_helpers/clearcache',
		type : "POST",
		success : function(resp) {

		}
	});
}

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
    settings:{
        filetypes:"png,gif,jpg,jpeg,tiff,bmp",
        url:"http://pecata/Microweber/iframe_submit_test.php"
    },
    what_is_dragging:function(event){
        var types = event.dataTransfer.types;
        var g = {}
        g.toreturn = '';
        for(var obj in types){
          var item = types[obj];
          if(item.contains('text/plain') || item.contains('text/html')){
            g.toreturn = 'link';
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
                               typeof settings.fileuploaded == 'function' ? settings.fileuploaded.call(this) : '';
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
                  file.type.contains("html")){
            reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn);
  		    }
            reader.readAsText(file,"UTF-8");
          }
          else{
             callback.call(toreturn);
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
       mw.files.processer(file, function(){
             obj.file = this.result;
             obj.name = this.name;
             $.post(obj.url, obj, function(data){
               var json = $.parseJSON( data );
                callback.call(json);
             });
       });
    },
    upload:function(uploader, object, single_file_uploaded, all_uploaded){

        var obj = typeof object=='object' ? $.extend({}, mw.files.settings, object) : $.extend({}, mw.files.settings);
        if(uploader.files){
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
    }
}



mw.filechange = function(input, callback){ //msie does not support 'change' event for <input type='file' />
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




