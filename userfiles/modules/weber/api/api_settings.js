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
    modules_url:'<?php print MW_MODULES_URL; ?>',
    includes_url: '<?php   print( INCLUDES_URL);  ?>',
    upload_url: '<?php print site_url(); ?>api/upload/',
    api_url: '<?php print site_url(); ?>api/',
    libs_url: '<?php   print( INCLUDES_URL);  ?>api/libs/',
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


