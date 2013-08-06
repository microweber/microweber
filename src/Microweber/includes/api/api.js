
mw = {}

mw.version = "<?php print MW_VERSION; ?>";


mw.pauseSave = false;

mw.askusertostay = false;





  if (top === self){
    window.onbeforeunload = function() {

        if(mw.askusertostay){
            mw.notification.warning("<?php _e("You have unsaved changes"); ?>!");
            return "<?php _e("You have unsaved changes"); ?>!";
        }
    }
  }

  warnOnLeave = function(){
     mw.tools.confirm("<?php _e("You have unsaved changes! Are you sure"); ?>?");
  }

  mw.module = {} //Global Variable for modules scripts

  mwd = document;
  mww = window;
  mwhead = mwd.head || mwd.getElementsByTagName('head')[0];

  mw.loaded = false;

  mw._random = new Date().getTime();

  mw.random = function() {
    return mw._random++;
  }

  String.prototype.contains = function(a) {
    return !!~this.indexOf(a);
  };



  mw.onLive = function(callback) {
    if (typeof mw.settings.liveEdit === 'boolean' && mw.settings.liveEdit) {
      callback.call(this)
    }
  }
  mw.onAdmin = function(callback) {
    if ( window['mwAdmin'] ) {
      callback.call(this);
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
    mw.rh = mwd.createElement('div');
    mw.require = function(url, inHead) {
      var inHead = inHead || false;
      var url = url.contains('//') ? url : "<?php print( MW_INCLUDES_URL ); ?>api/" + url;
      if (!~mw.required.indexOf(url)) {
        mw.required.push(url);
        var t = url.split('.').pop();
        var url = url.contains("?") ?  url + '&mwv=' + mw.version : url + "?mwv=" + mw.version;
        var string = t !== "css" ? "<script type='text/javascript'  src='" + url + "'></script>" : "<link rel='stylesheet' type='text/css' href='" + url + "' />";
        if ((document.readyState === 'loading' || document.readyState === 'interactive') && !inHead && typeof CanvasRenderingContext2D === 'function') {
           mwd.write(string);
        } else {
          $(mwhead).append(string)
        }
      }
    }
})();



   /**/





  Wait = function(a, b, max) {
    window[a] === undefined ? setTimeout(function() {
      Wait(a, b), 52
    }) : b.call(a);
  }





  mw.target = {} //


  mw.is = {
    obj: function(obj) {
      return typeof obj === 'object';
    },
    func: function(obj) {
      return typeof obj === 'function';
    },
    string: function(obj) {
      return typeof obj === 'string';
    },
    invisible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'hidden';
    },
    visible: function(obj) {
      return window.getComputedStyle(obj, null).visibility === 'visible';
    },
    ie: /*@cc_on!@*/false
  }



/*
 * Microweber - Javascript Framework
 *
 * Copyright (c) Mass Media Group (www.ooyes.net) Licensed under the Microweber
 * license http://microweber.com/license
 *
 */

  mw.msg = {
    ok: "<?php _e('OK');  ?>",
    cancel: "<?php _e('Cancel');  ?>",
    to_delete_comment:"<?php _e('Are you sure you want to delete this comment'); ?>",
    del:"<?php _e('Are you sure you want to delete this?'); ?>",
    save_and_continue:"<?php _e('Save &amp; Continue'); ?>",
    before_leave:"<?php _e("Leave withot saving"); ?>",
    session_expired:"<?php _e("Your session has expired"); ?>",
    login_to_continue:"<?php _e("Please login to continue"); ?>",
    more:"<?php _e("More"); ?>",
    less:"<?php _e("Less"); ?>"
  }

  mw.settings = {
    liveEdit:false,
    debug: true,
    site_url: '<?php print site_url(); ?>',
    template_url: '<?php print TEMPLATE_URL; ?>',
    //mw.settings.site_url
    includes_url: '<?php   print( INCLUDES_URL);  ?>',
    upload_url: '<?php print site_url(); ?>api/upload/',

    api_url: '<?php print site_url(); ?>api/',
    api_html: '<?php print site_url(); ?>api_html/',


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

    row_id: false,

    edit_area_placeholder: '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span><?php _e("Please drag items here"); ?></span></div>',

    empty_column_placeholder: '<div id="_ID_" class="empty-element empty-element-column"><?php _e("Please drag items here"); ?></div>',

    //handles
    handles: {
      module: "\
        <div contenteditable='false' id='mw_handle_module' class='mw_master_handle mw-sorthandle mw-sorthandle-col mw-sorthandle-module'>\
            <div class='mw_col_delete mw_edit_delete_element'>\
                <a class='mw_edit_btn mw_edit_delete right' href='javascript:void(0);' onclick='mw.drag.delete_element(mw.handle_module);return false;'><span></span></a>\
                <a class='mw_edit_settings' href='javascript:void(0);' onclick='mw.drag.module_settings();return false;'><span class='mw-element-name-handle'></span></a>\
            </div>\
            <span title='Click to select this module.' class='mw-sorthandle-moveit'><?php _e("Move"); ?></span>\
        </div>",
      row: "\
        <div contenteditable='false' class='mw_master_handle' id='mw_handle_row'>\
            <span title='<?php _e("Click to select this column"); ?>.' class='column_separator_title'><?php _e("Columns"); ?></span>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,1);' class='mw-make-cols mw-make-cols-1 active' >1</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,2);' class='mw-make-cols mw-make-cols-2' >2</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,3);' class='mw-make-cols mw-make-cols-3' >3</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,4);' class='mw-make-cols mw-make-cols-4' >4</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,5);' class='mw-make-cols mw-make-cols-5' >5</a>\
            <a class='mw_edit_delete mw_edit_btn right' onclick='mw.drag.delete_element(mw.handle_row);' href='javascript:;'><span></span></a>\
        </div>",
      element: "\
        <div contenteditable='false' id='mw_handle_element' class='mw_master_handle mw-sorthandle mw-sorthandle-element'>\
            <div contenteditable='false' class='mw_col_delete mw_edit_delete_element'>\
                <a contenteditable='false' class='mw_edit_btn mw_edit_delete'  onclick='mw.drag.delete_element(mw.handle_element);'><span></span></a>\
            </div>\
            <span title='<?php _e("Click to select this element"); ?>.' contenteditable='false' class='mw-sorthandle-moveit'><?php _e("Move"); ?></span>\
        </div>",
      item: "<div title='<?php _e("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
    },
    sorthandle_delete_confirmation_text: "<?php _e("Are you sure you want to delete this element"); ?>?"
  }


  mw.load_module = function($module_name, $update_element, callback, attributes) {

   var attributes = attributes || {};
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

  mw.loadModuleData = function(name, update_element, callback, attributes){
    var attributes = attributes || {};
    attributes.module = name;
    mw._({
      selector: update_element,
      params: attributes,
      done: function(data) {
        callback.call(this, data);
      }
    }, false, true);
  }

  mw.reload_module_interval = function(module_name, interval) {
    var interval =  interval || 1000;
    var obj = {pause:false};
    var int = setInterval(function(){
        if(!obj.pause){
            obj.pause = true;
            mw.reload_module(module_name, function(){
                obj.pause = false;
            });
        }
    }, interval);
    return int;
  }

  mw.reload_module = function($module_name, callback) {
    var done = callback || false;
    if (typeof $module_name != 'undefined') {
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

          if (typeof $module_name != 'undefined') {
			   $module_name = $module_name.replace(/##/g, '#');

            $mods = $(".module[data-type='" + $module_name + "']");
            if ($mods.length == 0) {
                try {
                    $mods = $($module_name);
                  } catch(err) {}
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
      type: "POST",
      success: function(data){
        if(mw.notification != undefined){
          mw.notification.msg(data);
        }
      }
    });
  }


  mw._ = function(obj, sendSpecific, DONOTREPLACE) {

    var DONOTREPLACE = DONOTREPLACE || false;
    var sendSpecific = sendSpecific || false;
    var url = typeof obj.url !== 'undefined' ? obj.url : '{SITE_URL}module/';
    var selector = typeof obj.selector !=='undefined' ? obj.selector : '';
    var params =  typeof obj.params !=='undefined' ? obj.params : {};
    var to_send = params;
    if(typeof $(obj.selector)[0] === 'undefined') { return false; }
    var attrs = $(obj.selector)[0].attributes;
    if (sendSpecific) {
      attrs["class"] !== undefined ? to_send["class"] = attrs["class"].nodeValue : ""
      attrs["data-module-name"] !== undefined ? to_send["data-module-name"] = attrs["data-module-name"].nodeValue : "";
      attrs["data-type"] !== undefined ? to_send["data-type"] = attrs["data-type"].nodeValue : "";
      attrs["template"] !== undefined ? to_send["template"] = attrs["template"].nodeValue : "";
    } else {
      for (var i in attrs) {
  		  if(attrs[i] != undefined){
              var name = attrs[i].name;
              var val = attrs[i].nodeValue;
              if(typeof to_send[name] === 'undefined'){
                  to_send[name]  = val;
              }
  		  }
      }
    }


    $.post(url, to_send, function(data) {

      if(DONOTREPLACE){
          obj.done.call($(selector)[0], data);
          return false;
      }
      $(selector).after(data);
      var id = to_send.id || $(selector).next()[0].id;
      $(selector).remove();

      typeof mw.resizable_columns === 'function' ? mw.resizable_columns() : '';
      typeof mw.drag !== 'undefined' ? mw.drag.fix_placeholders(true) : '';

      var m = mwd.getElementById(id);

      typeof obj.done === 'function' ? obj.done.call(selector, m) : '';

      if(!!mw.wysiwyg){
        $(m).hasClass("module") ? mw.wysiwyg.init_editables(m) : '' ;
        mw.on.moduleReload(id, "", true);
      }
    });

  }




  mw.log = d = function(what) {
    if (window.console && mw.settings.debug) {
      console.log(what);
    }
  }

  mw.$ = function(selector, context) {
    var context = context || mwd;
    if (typeof mwd.querySelector !== 'undefined') {
      if (typeof selector === 'string') {
        try {
          return jQuery(context.querySelectorAll(selector));
        } catch (e) {
          return jQuery(selector, context);
        }
      } else {
        return jQuery(selector, context);
      }
    } else {
      return jQuery(selector, context);
    }
  };






  api = function(action, params, callback){
    var url = mw.settings.api_url + action;
    var type = typeof params;
    if(type === 'string'){
        var obj = mw.serializeFields(params);
    }
    else if(type.constructor === {}.constructor ){
        var obj = params;
    }
    else{
      var obj = {};
    }
    $.post(url, obj)
        .success(function(data) { return typeof callback === 'function' ? callback.call(data) : data;   })
        .error(function(data) { return typeof callback === 'function' ? callback.call(data) : data;  });
  }

  get_content = function(params, callback){
    var obj = mw.url.getUrlParams("?"+params);
    if(typeof callback!='function'){
       api('get_content_admin', obj);
    }
    else{
       api('get_content_admin', obj, function(){callback.call(this)});
    }
  }







mw.serializeFields =  function(id, ignorenopost){
      var ignorenopost = ignorenopost || false;
      var el = mw.$(id);
      fields = "input[type='text'], input[type='email'], input[type='number'], input[type='password'], input[type='hidden'], textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
      var data = {}
      $(fields, el).each(function(){
          if((!$(this).hasClass('no-post') || ignorenopost) && !this.disabled){
            var el = this, _el = $(el);
            var val = _el.val();
            var name = el.name;
            if(el.name.contains("[]")){
              try {
                 data[name].push(val);
              }
              catch(e){
                data[name] = [val];
              }
            }
            else{
              data[name] = val;
            }
          }
      });
      return data;
 }







mw.response = function(form, data, messages_at_the_bottom){
    var messages_at_the_bottom = messages_at_the_bottom || false;

    if(data == null  ||  typeof data == 'undefined' ){
      return false;
    }

    var data = mw.tools.toJSON(data);
    if(typeof data == 'undefined'){
          return false;
      }

    if(typeof data.error !== 'undefined'){
        mw._response.error(form, data, messages_at_the_bottom);
        return false;
    }
    else if(typeof data.success !== 'undefined'){
        mw._response.success(form, data, messages_at_the_bottom);
        return true;
    }
    else if(typeof data.warning !== 'undefined'){
        mw._response.warning(form, data, messages_at_the_bottom);
        return false;
    }
    else{
return false;
    }
}

mw._response = {
  error:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'error');
    mw._response.createHTML(data.error, err_holder);
  },
  success:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'success');
    mw._response.createHTML(data.success, err_holder);
  },
  warning:function(form, data, _msg){
    var form = mw.$(form);
    var err_holder = mw._response.msgHolder(form, 'warning');
    mw._response.createHTML(data.warning, err_holder);
  },
  msgHolder : function(form, type, method){
    var method = method || 'append';
    var err_holder = form.find(".alert");
    if(err_holder.length==0){
        var err_holder = mwd.createElement('div');
        form[method](err_holder);
    }
    $(err_holder).empty().attr("class", 'alert alert-' + type + ' hide');
    return err_holder;
  },
  createHTML:function(data, holder){
    var i, html = "";
    if(typeof data === 'string'){
        html+= data.toString();
    }
    else{
      for( i in data){
          if(typeof data[i] === 'string'){
              html+='<li>'+data[i]+'</li>';
          }
      }
    }
    $(holder).append('<ul class="mw-error-list">'+html+'</ul>');
    $(holder).show();
  }
}


















