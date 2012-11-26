if (!window.CanvasRenderingContext2D) {
  document.write("<div id='UnsupportedBrowserMSG'><h1>Your a need better browser to run <b>Microweber</b></h1></div>");
  document.body.id = 'UnsupportedBrowser';
}

document.getElementsByTagName('html')[0].className = window.location.href.indexOf('pecata/') !==-1 ?  document.getElementsByTagName('html')[0].className + ' mwoffice' : document.getElementsByTagName('html')[0].className;


typeof mw === 'undefined' ?

(function() {


__mwextend = function(el){
      if(el.attributes['data-extended']===undefined){
          el.setAttribute('data-extended', true);
          el.getModal = function(){
              var modal = mw.tools.firstParentWithClass(el, 'mw_modal');
              if(!!modal){
                  return  {
                       main:modal,
                       container:modal.querySelector(".mw_modal_container")
                  }
              }
              else {return false};
          }
          el.attr = function(name, value){
            if(value===undefined){
              return el.attributes[name] !== undefined ? el.attributes[name].nodeValue : undefined;
            }
            else{
              el.setAttribute(name, value);
              return el;
            }
          }
      }
    return el;
}



  mw = {}

  mw.extend = function(el){
    return __mwextend(el);
  }

  mw.module = {} //Global Variable for modules scripts
  mwd = document;

  mw.loaded = false;

  mw._random = 9999999;

  mw.random = function() {
    return mw._random++;
  }

  String.prototype.contains = function(a) {
    return !!~this.indexOf(a)
  };



  mw.onLive = function(callback) {
    if (!window['mwAdmin']) {
      callback.call(this)
    }
  }
  mw.onAdmin = function(callback) {
    if (window['mwAdmin']) {
      callback.call(this)
    }
  }


  if (!Array.indexOf) {
    Array.prototype.indexOf = function(obj) {
      var i=0, l=this.length;
      for ( ; i < l; i++) {
        if (this[i] == obj) {
          return i;
        }
      }
      return -1;
    }
  }




(function() {
    mw.required = [];
    mw.require = function(url) { //Veyron
      var url = url.contains('//') ? url : "<?php print( INCLUDES_URL); ?>api/" + url;
      if (!~mw.required.indexOf(url)) {
        mw.required.push(url);
        var t = url.split('.').pop();
        if (!mw.loaded) {
          t !== 'css' ? mwd.write("<script type='text/javascript' src='" + url + "'></script>") : mwd.write("<link rel='stylesheet' type='text/css' href='" + url + "' />");
        } else {
          var text = t !== 'css' ? "<script type='text/javascript' src='" + url + "'></script>" : "<link rel='stylesheet' type='text/css' href='" + url + "' />";
          $(mwd.body).append(text);
        }
      }
    }
})();







  Wait = function(a, b) {
    window[a] === undefined ? setTimeout(function() {
      Wait(a, b), 52
    }) : b.call(a);
  }



  window.onload = function() {
    mw.loaded = true;
    mwd.body.className+=' loaded';
  }

  mw.target = {} //


  mw.is = {
    obj: function(obj) {
      return typeof obj === 'object'
    },
    func: function(obj) {
      return typeof obj === 'function'
    },
    string: function(obj) {
      return typeof obj === 'string'
    },
    defined: function(obj) {
      return obj !== undefined
    },
    invisible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'hidden'
    },
    visible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'visible'
    },
    ie: /*@cc_on!@*/
    false
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
    debug: true,
    site_url: '<?php print site_url(); ?>',
    //mw.settings.site_url
    includes_url: '<?php   print( INCLUDES_URL);  ?>',
    upload_url: '<?php print site_url(); ?>api/upload/',

    api_url: '<?php print site_url(); ?>api/',


    page_id: '<?php print intval(PAGE_ID) ?>',
    post_id: '<?php print intval(POST_ID) ?>',
    category_id: '<?php print intval(CATEGORY_ID) ?>',
    content_id: '<?php print intval(CONTENT_ID) ?>',
    editables_created: false,
    element_id: false,
    text_edit_started: false,
    sortables_created: false,
    drag_started: false,
    sorthandle_hover: false,
    resize_started: false,
    sorthandle_click: false,

    plupload: {
      runtimes: 'html5,html4',
      browse_button: 'pickfiles',
      container: 'container',
      max_file_size: '<?php print ini_get("upload_max_filesize"); ?>',
      url: '<? print site_url("plupload"); ?>',
      flash_swf_url: '<? print INCLUDES_URL; ?>toolbar/editor_tools/plupload/plupload.flash.swf',
      multi_selection: true
    },

    row_id: false,

    edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span>Please drag items here</span></div>',

    empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column">Please drag items here</div>',

    //handles
    handles: {
      module: "\
        <div contenteditable='false' id='mw_handle_module' class='mw_master_handle mw-sorthandle mw-sorthandle-col mw-sorthandle-module'>\
            <div class='mw-element-name-handle'></div>\
            <div class='mw_col_delete mw_edit_delete_element'>\
                <a class='mw_edit_btn mw_edit_delete right' href='javascript:;' onclick='mw.drag.delete_element(mw.handle_module);'><span></span></a>\
                <a class='mw_edit_btn mw_edit_settings right' href='javascript:;' onclick='mw.drag.module_settings(this);'>Settings</a>\
            </div>\
            <span title='Click to select this module.' class='mw-sorthandle-moveit'>Move</span>\
        </div>",
      row: "\
        <div contenteditable='false' class='mw_master_handle' id='mw_handle_row'>\
            <div contenteditable='false' class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='columns_set'></div>\
	    	    <div class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='mw_row_delete mw_edit_delete_element'></div>\
    	    </div>\
            <span title='Click to select this column.' class='column_separator_title'>Columns</span>\
            <a href='javascript:;' onclick='mw.drag.create_columns(this,1);' class='mw-make-cols mw-make-cols-1 active' >1</a>\
            <a href='javascript:;' onclick='mw.drag.create_columns(this,2);' class='mw-make-cols mw-make-cols-2' >2</a>\
            <a href='javascript:;' onclick='mw.drag.create_columns(this,3);' class='mw-make-cols mw-make-cols-3' >3</a>\
            <a href='javascript:;' onclick='mw.drag.create_columns(this,4);' class='mw-make-cols mw-make-cols-4' >4</a>\
            <a href='javascript:;' onclick='mw.drag.create_columns(this,5);' class='mw-make-cols mw-make-cols-5' >5</a>\
            <a class='mw_edit_delete mw_edit_btn right' onclick='mw.drag.delete_element(mw.handle_row);' href='javascript:;'><span></span></a>\
        </div>",
      element: "\
        <div contenteditable='false' id='mw_handle_element' class='mw_master_handle mw-sorthandle mw-sorthandle-element'>\
            <div contenteditable='false' class='mw_col_delete mw_edit_delete_element'>\
                <a contenteditable='false' class='mw_edit_btn mw_edit_delete' onclick='mw.drag.delete_element(mw.handle_element);'><span></span></a>\
            </div>\
            <span title='Click to select this element.' contenteditable='false' class='mw-sorthandle-moveit'>Move</span>\
        </div>",
      item: "<div title='Click to select this item.' class='mw_master_handle' id='items_handle'></div>"
    },
    sorthandle_delete_confirmation_text: "Are you sure you want to delete this element?"
  }


  mw.load_module = function($module_name, $update_element, callback, attributes) {

  if(attributes == undefined){
   var attributes = {};
   }
    attributes.module = $module_name;
    mw._({
      selector: $update_element,
      params: attributes,
      done: function() {
        mw.settings.sortables_created = false;
        if (mw.is.func(callback)) {
          callback.call($($update_element)[0]);
        }
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

  mw.reload_module = function($module_name, callback) {
    var done = callback || false;
    if ($module_name == undefined) {

    } else {


      if (typeof $module_name == 'object') {
        mw._({
          selector: $module_name,
          done:done
        });

      } else {

        var module_name = $module_name.toString();
        var refresh_modules_explode = module_name.split(",");
        for (var i = 0; i < refresh_modules_explode.length; i++) {
          var $module_name = refresh_modules_explode[i];

          if ($module_name != undefined) {
			 $module_name = $module_name.replace(/##/g, '#');
			   mw.log( $module_name );
			  
            //$mods = $(".module[data-type='" + $module_name + "']", '.edit');
            $mods = $(".module[data-type='" + $module_name + "']");
            if ($mods.length == 0) {
              $mods = $($module_name);
            }

            $mods.each(function() {
              mw._({
                selector: this,
                done:done
              });
            });
          }
        }
      }
    }
  }

  mw.clear_cache = function() {
    $.ajax({
      url: '{SITE_URL}api/clearcache',
      type: "POST"
    });
  }



  mw._ = function(obj, sendSpecific) {
    var url = mw.is.defined(obj.url) ? obj.url : '{SITE_URL}module/';
    var selector = mw.is.defined(obj.selector) ? obj.selector : '';
    var params = mw.is.defined(obj.params) ? obj.params : {};
    var to_send = params;
    var attrs = $(obj.selector)[0].attributes;

    if (sendSpecific) {
      attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : ""
      attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
      attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
    } else {
      for (var i in attrs) {
		  
		  if(attrs[i] != undefined){
        var name = attrs[i].name;
        var val = attrs[i].nodeValue;
        to_send[name] = val;
		  }
      } 
	  
	//  to_send = attrs;
    }

    $.post(url, to_send, function(data) {
      $(selector).after(data);

      var id = to_send.id || $(selector).next()[0].id;
      $(selector).remove();
      typeof obj.done === 'function' ? obj.done.call(selector) : '';


      mw.is.defined(mw.resizable_columns) ? mw.resizable_columns() : '';
      mw.is.defined(mw.drag) ? mw.drag.fix_placeholders(true) : '';

      var m = mwd.getElementById(id);
      if(!!mw.wysiwyg){
        $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '' ;
        mw.on.moduleReload(id, "", true);
      }
    });

  }

  mw.qsas = mwd.querySelector;


  mw.log = function(what) {
    if (window.console && mw.settings.debug) {
      console.log(what);
    }
  }

  mw.$ = function(selector) {
    if (mw.qsas) {
      if (mw.is.string(selector)) {
        try {
          return jQuery(mwd.querySelectorAll(selector));
        } catch (e) {
          return jQuery(selector);
        }
      } else {
        return jQuery(selector);
      }
    } else {
      return jQuery(selector);
    }
  };



})() : '';