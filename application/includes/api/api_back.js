window.mw = window.mw ? window.mw : {};

mwd = document;

mw.random = function(){return Math.floor(Math.random()*(new Date().getTime()));}

String.prototype.contains = function(a) { return this.indexOf(a) != -1; };



if(!Array.indexOf){
   Array.prototype.indexOf = function(obj){
       for(var i=0; i<this.length; i++){
           if(this[i]==obj){
               return i;
           }
       }
       return -1;
   }
}

(function() {
  mw.required = [];
  mw.require = function(url){ //The Fast and the Furious
     var url = url.contains('//') ? url : "<?php print( INCLUDES_URL); ?>api/" + url;
     if(mw.required.indexOf(url)==-1){
       mw.required.push(url);
       var h = mwd.getElementsByTagName('head')[0];
       var t = url.split('.').pop();
       var j = mwd.createElement('script');
       if(!mw.loaded){
           if(t=='js'){
              j.text = "mwd.write('<script type=\"text/javascript\" src=\""+url+"\"><\/script>')";
           }
           else if(t=='css'){
              var link = mwd.createElement('link');
              j.text = "mwd.write('<link rel=\"stylesheet\" href=\""+url+"\" type=\"text/css\" />')";
           }
           h.insertBefore( j, h.firstChild );
       }
       else{
         var text = "<script src='"+url+"'></script>";
         $(mwd.body).append(text);
       }
     }
  }

})();

mw.require('<?php   print( INCLUDES_URL);  ?>js/jquery.js');



Wait = function(a,b){ !mw.is.defined(a) ? setTimeout(function(){Wait(a,b),22}) : b.call(a); }

mw.loaded = false;

window.onload = function(){
    mw.loaded = true;
}

mw.target = {} //


mw.is = {
  obj:function(obj){return typeof obj=='object'},
  func:function(obj){return typeof obj=='function'},
  string:function(obj){return typeof obj=='string'},
  defined:function(obj){return obj!==undefined}
}

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


mw.settings = {
    site_url:'<?php print site_url(); ?>', //mw.settings.site_url
    includes_url: '<?php   print( INCLUDES_URL);  ?>',
    upload_url:'<?php print site_url(); ?>api/upload/',
    page_id : '<?php print intval(PAGE_ID) ?>',
    post_id : '<?php print intval(POST_ID) ?>',
    category_id : '<?php print intval(CATEGORY_ID) ?>',
    content_id : '<?php print intval(CONTENT_ID) ?>',
    editables_created : false,
    element_id : false,
    text_edit_started : false,
    sortables_created : false,
    drag_started : false,
    sorthandle_hover : false,
    resize_started:false,
    sorthandle_click : false,

    plupload:{
      runtimes : 'html5,html4',
      browse_button : 'pickfiles',
      container: 'container',
      max_file_size : '<?php print ini_get("upload_max_filesize"); ?>',
      url : '<? print site_url("plupload"); ?>',
      flash_swf_url:'<? print INCLUDES_URL; ?>toolbar/editor_tools/plupload/plupload.flash.swf',
      multi_selection:true
    },

    row_id : false,

    edit_area_placeholder : '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span>Please drag items here</span></div>',

    empty_column_placeholder : '<div id="_ID_" class="empty-element empty-element-column">Please drag items here</div>',

    //handles
    sorthandle_row : "<div contenteditable='false' class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='columns_set'></div>\
	    	    <div class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='mw_row_delete mw.edit.delete_element'>&nbsp;</div>\
    	    </div>",
    sorthandle_row_columns_controlls :
          '<span class="column_separator_title">Columns</span>\
          <a  href="javascript:mw.edit.create_columns(ROW_ID,1)" class="mw-make-cols mw-make-cols-1" >1</a> \
          <a  href="javascript:mw.edit.create_columns(ROW_ID,2)" class="mw-make-cols mw-make-cols-2" >2</a> \
          <a  href="javascript:mw.edit.create_columns(ROW_ID,3)" class="mw-make-cols mw-make-cols-3" >3</a> \
          <a  href="javascript:mw.edit.create_columns(ROW_ID,4)" class="mw-make-cols mw-make-cols-4" >4</a> \
          <a  href="javascript:mw.edit.create_columns(ROW_ID,5)" class="mw-make-cols mw-make-cols-5" >5</a> ',    
    sorthandle_row_delete : '<a class=\"mw_edit_delete_element\" href="javascript:mw.drag.delete_element(ROW_ID)"><span>&nbsp;</span></a> ',
    sorthandle_delete_confirmation_text : "Are you sure you want to delete this element?",
    sorthandle_col:
    "<div contenteditable='false' class='mw-sorthandle mw-sorthandle-col mw-sorthandle-element'>\
            <div contenteditable='false' class='mw_col_delete mw_edit_delete_element'>\
                <a contenteditable='false' class='mw_edit_btn mw_edit_delete' onclick=\"mw.drag.delete_element(ELEMENT_ID)\"><span>&nbsp;</span></a>\
            </div>\
            <span contenteditable='false' class='mw-sorthandle-moveit'>Move</span>\
        </div>",
    sorthandle_module:
    "<div contenteditable='false' class='mw-sorthandle mw-sorthandle-col mw-sorthandle-module'>\
        <div class='mw-element-name-handle'>MODULE_NAME</div>\
        <div class='mw_col_delete mw_edit_delete_element'>\
            <a class='mw_edit_btn mw_edit_delete right' href=\"javascript:mw.drag.delete_element(ELEMENT_ID)\"><span>&nbsp;</span></a>\
            <a class='mw_edit_btn mw_edit_settings right' href=\"javascript:mw.drag.module_settings(MODULE_ID)\">Settings</a>\
        </div>\
        <span class='mw-sorthandle-moveit'>Move</span>\
    </div>"
}


mw.load_module = function($module_name, $update_element) {
	var attributes = {};
	attributes.module = $module_name;
    mw._({
      selector:$update_element,
      params:attributes,
      done:function(){
        mw.settings.sortables_created = false;
      }
    });
}

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
		
		
		if(typeof $module_name == 'object'){
			 mw._({
                      selector:$module_name
             });
			
		} else {
		
		var module_name = $module_name.toString();
		var refresh_modules_explode = module_name.split(",");
		for (var i = 0; i < refresh_modules_explode.length; i++) {
			var $module_name = refresh_modules_explode[i];

			if ($module_name != undefined) {
				$mods = $(".module[data-type='" + $module_name + "']", '.edit');
				if ($mods.length == 0) {
					$mods = $($module_name);
				}
				$mods.each(function() {
                    mw._({
                      selector:this
                    });
				});
			}
		}
		}
	}
}

mw.clear_cache = function() {
	$.ajax({
		url : '{SITE_URL}api/clearcache',
		type : "POST"
	});
}

mw._ = function(obj){
    var url = mw.is.defined(obj.url) ? obj.url : '{SITE_URL}module/';
    var selector = mw.is.defined(obj.selector) ? obj.selector : '';
    var params = mw.is.defined(obj.params) ? obj.params : {};
    var to_send = params;
    $.each($(obj.selector)[0].attributes, function(index, attr) {
      to_send[attr.name] = attr.value;
    });
    $.post(url, to_send, function(data){
        $(selector).after(data);
        //$(".element").notclick().attr("contenteditable", true);
        $(selector).remove();
        mw.is.defined(obj.done) ? obj.done.call(selector) :'';
    });
}