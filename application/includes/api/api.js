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
                    var file_data = mw.files.processer(this);
                });
            }, false);
        });
    },
    processer : function(file, callback){
          var reader = new FileReader();
          var toreturn = {}
          if(file.type.contains("image")){
             toreturn.type = "image";
             toreturn.name = file.name;
             reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn)
  		     }
             reader.readAsDataURL(file);
          }
          else if(file.type.contains("txt") ||
                  file.type.contains("rtf") ||
                  file.type.contains("html") ||
                  file.type.contains("html")){
            toreturn.type = "text";
            toreturn.name = file.name;
            reader.onload = function(e) {
               toreturn.result = e.target.result;
               callback.call(toreturn)
  		    }
            reader.readAsText(file,"UTF-8");
          }
    },
    browser_settings:{
        accepts:"png,gif,jpg,jpeg,tiff,bmp",
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
        u.validate = function(callback){
          var accepts = settings.accepts;
          var el = u;
          if(el.files){
            var files = el.files;
            var len = files.length;
            $.each(files, function(i){
                mw.files.processer(this, function(){
                    var filetype = this.name.split('.').pop();
                    if(settings.accepts.contains(filetype)){
                        if((i+1)==len){
                           callback.call(true);
                        }
                    }
                    else{
                        callback.call(false);
                    }
                });
            });
          }
          else{ // browser has no filereader;
            var value = this.value;
            var filetype = value.split('.').pop();
            if(settings.accepts.contains(filetype)){
                callback.call(true)
            }
            else{
                callback.call(false);
            }
          }
        }
        return u;
    },
    iframe_uploader:function(input_file, url, callback){
        var form = document.createElement('form');
        form.enctype = 'multipart/form-data';
        form.action = url;
        form.method = 'post';
        var input = $(input_file).clone(true);
        input.removeAttr("id");
        var frame = document.createElement('iframe');
        frame.src= '#';
        frame.onload = callback;


    },
    upload_settings:{
        url:'/upload'
    },
    upload:function(uploader, object, callback){
        var obj = mw.files.upload_settings;
        typeof object=='object' ? $.extend(obj, object) : '';
        if(uploader.files){
            var files = uploader.files;
            var len = files.length;
            $.each(files, function(i){
                 mw.files.processer(this, function(){
                   var data = {
                     file:this.result,
                     name:this.name
                   }
                   $.post(obj.url, data, function(){
                        if((i+1)==len && typeof callback == 'function'){
                           callback.call(files);
                        }
                   });
                 });
            });
        }
        else{  // browser has no filereader;
            mw.files.iframe_uploader(uploader, obj.url);
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




