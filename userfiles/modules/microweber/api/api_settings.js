if(typeof mw === 'undefined'){
    mw = {}
}
if(typeof mw.settings === 'undefined'){
    mw.settings = {}
}




mw.settings = {
    regions:false,
    liveEdit:false,
    debug: true,
    basic_mode:false,
    site_url: '<?php print site_url(); ?>',
    template_url: '<?php print TEMPLATE_URL; ?>',
    modules_url:'<?php print modules_url(); ?>',
    includes_url: '<?php   print( mw_includes_url());  ?>',
    upload_url: '<?php print site_url(); ?>api/upload/',
    api_url: '<?php print site_url(); ?>api/',
    libs_url: '<?php   print( mw_includes_url());  ?>api/libs/',
    api_html: '<?php print site_url(); ?>api_html/',
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
    handles: {
        module: "\
        <div contenteditable='false' id='mw_handle_module' class='mw-defaults mw_master_handle mw-sorthandle mw-sorthandle-col mw-sorthandle-module' draggable='false'>\
            <div class='mw_col_delete mw_edit_delete_element' draggable='false'>\
                <a class='mw_edit_btn mw_edit_delete right' href='javascript:void(0);' onclick='mw.drag.delete_element(mw.handle_module);return false;' draggable='false'><span></span></a>\
            </div>\
            <a title='Click to edit this module.' class='mw_edit_settings' href='javascript:void(0);' onclick='mw.drag.module_settings();return false;' draggable='false'><span class='mw-element-name-handle' draggable='false'></span></a>\
            <span title='Click to select this module.' class='mw-sorthandle-moveit' draggable='false' title='<?php _e("Move"); ?>'></span>\
        </div>",
        row: "\
        <div contenteditable='false' class='mw-defaults mw_master_handle mw_handle_row' id='mw_handle_row' draggable='false'>\
            <span title='<?php _e("Click to select this column"); ?>.' class='column_separator_title'><?php _e("Columns"); ?></span>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,1);' class='mw-make-cols mw-make-cols-1 active'  draggable='false'>1</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,2);' class='mw-make-cols mw-make-cols-2'  draggable='false'>2</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,3);' class='mw-make-cols mw-make-cols-3'  draggable='false'>3</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,4);' class='mw-make-cols mw-make-cols-4'  draggable='false'>4</a>\
            <a href='javascript:;' onclick='event.preventDefault();mw.drag.create_columns(this,5);' class='mw-make-cols mw-make-cols-5'  draggable='false'>5</a>\
            <a class='mw_edit_delete mw_edit_btn right' onclick='mw.drag.delete_element(mw.handle_row);' href='javascript:;' draggable='false'><span></span></a>\
        </div>",
        element: "\
        <div contenteditable='false' draggable='false' id='mw_handle_element' class='mw-defaults mw_master_handle mw-sorthandle mw-sorthandle-element'>\
            <div contenteditable='false' draggable='false' class='mw_col_delete mw_edit_delete_element'>\
                <a contenteditable='false' draggable='false' class='mw_edit_btn mw_edit_delete'  onclick='mw.drag.delete_element(mw.handle_element);'><span></span></a>\
            </div>\
            <span contenteditable='false' draggable='false' class='mw-sorthandle-moveit' title='<?php _e("Move"); ?>'></span>\
        </div>",
        item: "<div title='<?php _e("Click to select this item"); ?>.' class='mw_master_handle' id='items_handle'></div>"
    },
    sorthandle_delete_confirmation_text: "<?php _e("Are you sure you want to delete this element"); ?>?"
}

mw.settings.libs = {
      jqueryui:['jquery-ui.min.css', 'jquery-ui.min.js'],
      morris:['morris.css', 'raphael.js', 'morris.js'],
      rangy:['rangy-core.js','rangy-cssclassapplier.js','rangy-selectionsaverestore.js','rangy-serializer.js'],
      bootstrap2:[
        function(){
          var v = mwd.querySelector('meta[name="viewport"]');
          if(v === null){ var v = mwd.createElement('meta'); v.name = "viewport"; }
          v.content = "width=device-width, initial-scale=1.0";
          mwhead.appendChild(v);
        },
        'css/bootstrap.min.css',
        'css/bootstrap-responsive.min.css',
        'js/bootstrap.min.js'],
      bootstrap3:[
        function(){
            var v = mwd.querySelector('meta[name="viewport"]');
            if(v === null){ var v = mwd.createElement('meta'); v.name = "viewport"; }
            v.content = "width=device-width, initial-scale=1.0";
            mwhead.appendChild(v);
        },
        'css/bootstrap.min.css',
        'js/bootstrap.min.js'
      ],
      bootstrap3ns:[
          function(){
              var bootstrap_enabled = (typeof $().modal == 'function');
              if(bootstrap_enabled == false){
                  mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/dist/js/bootstrap.min.js');
                  mw.require(mw.settings.libs_url + 'bootstrap3ns' + '/dist/css/bootstrap.min.css');
              }
          }
      ],
      flatstrap3:[
        function(){
        var v = mwd.querySelector('meta[name="viewport"]');
        if(v === null){ var v = mwd.createElement('meta'); v.name = "viewport"; }
        v.content = "width=device-width, initial-scale=1.0";
        mwhead.appendChild(v);
      },
      'css/bootstrap.min.css',
      'js/bootstrap.min.js'
    ]
  }

   mw.lib = {
    _required:[],
    require:function(name){
          if(mw.lib._required.indexOf(name) !== -1){
              return false;
          };
          mw.lib._required.push(name);
          if(typeof mw.settings.libs[name] === 'undefined') return false;
          if(mw.settings.libs[name].constructor !== [].constructor) return false;
          var path = mw.settings.libs_url + name + '/',
              arr = mw.settings.libs[name],
              l = arr.length,
              i = 0,
              c = 0;
          for( ; i<l ; i++){
              (typeof arr[i] === 'string') ? mw.require(path + arr[i]) : (typeof arr[i] === 'function') ? arr[i].call() : '';
          }
    },
    get:function(name, done, error){
          if(mw.lib._required.indexOf(name) !== -1){
              if(typeof done === 'function'){
                done.call();
              }
              return false;
          };
          if(typeof mw.settings.libs[name] === 'undefined') return false;
          if(mw.settings.libs[name].constructor !== [].constructor) return false;
          mw.lib._required.push(name);
          var path = mw.settings.libs_url + name + '/',
              arr = mw.settings.libs[name],
              l = arr.length,
              i = 0,
              c = 1;
          for( ; i<l ; i++){
            var xhr = $.cachedScript(path + arr[i]);
            xhr.done(function(){
              c++;
              if(c === l){
                   if(typeof done === 'function'){
                     done.call();
                   }
              }
            });
            xhr.fail(function(jqxhr, settings, exception){

               if(typeof error === 'function'){
                 error.call(jqxhr, settings, exception);
               }

            });
          }
    }
  }

  mw.msg = {
    ok: "<?php _e('OK');  ?>",
    published: "<?php _e('Published');  ?>",
    unpublished: "<?php _e('Unpublished');  ?>",
    contentunpublished:"<?php _e("Content is unpublished"); ?>",
    contentpublished:"<?php _e("Content is published"); ?>",
    save: "<?php _e('Save');  ?>",
    saving: "<?php _e('Saving');  ?>",
    saved: "<?php _e('Saved');  ?>",
    settings: "<?php _e('Settings');  ?>",
    cancel: "<?php _e('Cancel');  ?>",
    remove: "<?php _e('Remove');  ?>",
    close: "<?php _e('Close');  ?>",
    to_delete_comment:"<?php _e('Are you sure you want to delete this comment'); ?>",
    del:"<?php _e('Are you sure you want to delete this?'); ?>",
    save_and_continue:"<?php _e('Save &amp; Continue'); ?>",
    before_leave:"<?php _e("Leave without saving"); ?>",
    session_expired:"<?php _e("Your session has expired"); ?>",
    login_to_continue:"<?php _e("Please login to continue"); ?>",
    more:"<?php _e("More"); ?>",
    templateSettingsHidden:"<?php _e("Template settings"); ?>",
    less:"<?php _e("Less"); ?>",
    product_added:"<?php _e("Your product is added to cart"); ?>",
    no_results_for:"<?php _e("No results for"); ?>",
    switch_to_modules:'<?php _e("Switch to Modules"); ?>',
    switch_to_layouts:'<?php _e("Switch to Layouts"); ?>',
    loading:'<?php _e("Loading"); ?>',
    edit:'<?php _e("Edit"); ?>',
    change:'<?php _e("Change"); ?>'
  }



