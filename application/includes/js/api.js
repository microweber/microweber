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



window.onerror = function(err, file, row){
    alert(err + "\nFile: " + file + "\nRow: " + row)
    }


posts = '<? get_posts("json"); ?>'


mw.settings = {
    site_url:'<?php print site_url(); ?>', //mw.settings.site_url
    includes_url: '<?php   print( INCLUDES_URL);  ?>',
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

    row_id : false,
    //	empty_column_placeholder : '<div class="ui-state-highlight ui-sortable-placeholder"><span>Please drag items here 1</span></div>',

    edit_area_placeholder : '<div class="empty-element-edit-area empty-element ui-state-highlight ui-sortable-placeholder"><span>Please drag items here</span></div>',

    empty_column_placeholder : '<div id="_ID_" class="empty-element empty-element-column">Please drag items here</div>',

    //handles
    sorthandle_row : "<div contenteditable='false' class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='columns_set'></div>\
	    	    <div class='mw-sorthandle mw-sorthandle-row'>\
	    	    <div class='mw_row_delete mw.edit.delete_element'>&nbsp;</div>\
    	    </div>",
    sorthandle_row_columns_controlls :
    '<a  href="javascript:mw.edit.create_columns(ROW_ID,1)" class="mw-make-cols mw-make-cols-1" >1</a> \
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



mw.module = function ($vars, $update_element) {

    $.ajax({
        url: '{SITE_URL}api/module',
        type: "POST",
        data: ($vars),
        async: false,

        success: function (resp) {
            $($update_element).html(resp);

            if ($vars.callback != undefined) {
                $vars.callback.call(this);

            }

        }
    });
}

mw.load_module = function ($module_name, $update_element) {

    var attributes = {};
    attributes.module = $module_name;

    url1 = '{SITE_URL}api/module';
    $($update_element).load(url1, attributes, function () {
        mw.settings.sortables_created = false;
    });


}

mw.load_layout_element = function ($layout_element_name, $update_element) {

    var attributes = {};
    attributes.element = $layout_element_name;

    url1 = '{SITE_URL}api/content/load_layout_element';
    $($update_element).load(url1, attributes, function () {
        mw.settings.sortables_created = false;
    });
}

// mw.reload_module_interval('custom_fields/');
mw.reload_module_interval = function ($module_name, $interval) {
    if ($interval == undefined) {
        $interval = 500;
    }

    $interval = parseInt($interval);






    t_reload_module_interval = setInterval("mw.reload_module('" + $module_name + "')", $interval);





}

mw.reload_module = function ($module_name) {


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

                $mods.each(

                    function () {

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



                        $.each(this.attributes, function (index, attr) {
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
                            elem.load(url1, $all_attr, function () {
                                mw.settings.sortables_created = false;
                            });




                        }

                    });

            }

        }
        if (typeof init_edits == 'function') {
        //	 mw.settings.sortables_created = false;
        // init_edits();
        }

    //	 $('.mw').trigger('mw_module_reloaded', [$module_name]);

    }

}

mw.clear_cache = function () {
    $.ajax({
        url: '{SITE_URL}ajax_helpers/clearcache',
        type: "POST",
        success: function (resp) {

        }
    });
}