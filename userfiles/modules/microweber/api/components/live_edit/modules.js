mw.components.live_edit = mw.components.live_edit || {}
mw.components.live_edit.modules = mw.components.live_edit.modules || {}

mw.components.live_edit.modules.registry = mw.components.live_edit.modules.registry || {}


mw.components.live_edit.modules.showHandle = function (element) {

    var el = $(element);
    var title = el.dataset("mw-title");
    var id = el.attr("id");
    var module_type = el.dataset("type");
    if (!module_type) {
        var module_type = el.attr("type");
    }


    var mod_icon = mw.components.live_edit.modules.getModuleIcon(module_type);

    if (title != '') {
        if(mod_icon){
            title = '<span class="mw-module-options-icon"><img src="'+mod_icon+'"></span>' + title;
        }

        //mw_master_handle

        mw.$(".mw-element-name-handle", mw.handle_module).html(title);
    } else {
        mw.$(".mw-element-name-handle", mw.handle_module).html(mw.msg.settings);
    }



    var mw_edit_settings_multiple_holder_id = 'mw_edit_settings_multiple_holder-' + id;




    mw.$(".mw_edit_settings_multiple_holder:visible", mw.handle_module).not("#" + mw_edit_settings_multiple_holder_id).hide();
    if (typeof(mw.live_edit_module_settings_array) != 'undefined' &&
        typeof(mw.live_edit_module_settings_array[module_type]) != 'undefined' &&
        typeof(mw.live_edit_module_settings_array[module_type]) == 'object'
    ) {

        mw.$(".mw_edit_settings", mw.handle_module).hide();

        if (document.getElementsByTagName('iframe').length > 90) {
            mw.$(".mw_edit_settings_multiple_holder").remove();

        }

        if (mw.$('#' + mw_edit_settings_multiple_holder_id).length == 0) {
            var new_el = mwd.createElement('div');
            new_el.className = 'mw_edit_settings_multiple_holder';
            new_el.id = mw_edit_settings_multiple_holder_id;
            $('.mw_edit_settings', mw.handle_module).after(new_el);

            // mw.$('#' + mw_edit_settings_multiple_holder_id).html(make_module_settings_handle_html);
            var settings = mw.live_edit_module_settings_array[module_type];


            mw.$(settings).each(function () {
                if (typeof(this.view) != 'undefined' && typeof(this.title) != 'undefined') {
                    var new_el = mwd.createElement('a');
                    new_el.className = 'mw_edit_settings_multiple';
                    new_el.title = this.title;
                    new_el.draggable = 'false';
                    var btn_id = 'mw_edit_settings_multiple_btn_' + mw.random();
                    new_el.id = btn_id;

                    if (typeof(this.type) != 'undefined' && (this.type) == 'tooltip') {
                        new_el.href = 'javascript:mw.drag.current_module_settings_tooltip_show_on_element("' + btn_id + '","' + this.view + '", "tooltip"); void(0);';

                    } else {
                        new_el.href = 'javascript:mw.drag.module_settings(undefined,"' + this.view + '"); void(0);';

                    }

                    var icon = '';
                    if (typeof(this.icon) != 'undefined') {
                        icon = '<i class="mw-edit-module-settings-tooltip-icon ' + this.icon + '"></i>'
                    }
                    new_el.innerHTML = '' +
                        icon +
                        '<span class="mw-edit-module-settings-tooltip-btn-title">' +
                        this.title +
                        '</span>' +
                        '';
                    mw.$('#' + mw_edit_settings_multiple_holder_id).append(new_el);
                }

            });
        }
        $('#' + mw_edit_settings_multiple_holder_id + ':hidden').show();
    } else {
        mw.$(".mw_edit_settings", mw.handle_module).show();

    }


    if(mod_icon){
        var sorthandle_main =  mw.$(".mw-element-name-handle", mw.handle_module).parent().parent();
        if(sorthandle_main){
            mw.$(sorthandle_main).addClass('mw-element-name-handle-no-fallback-icon');

        }
     }

}

mw.components.live_edit.modules.showSettings = function (a, opts) {


    var liveedit = opts.liveedit = opts.liveedit || false;

    var view = opts.view || 'admin';


    if (typeof a === 'string') {
        var module_type = a;
        var module_id = a;
        var mod_sel = mw.$(a + ':first');
        if (mod_sel.length > 0) {
            var attr = $(mod_sel).attr('id');
            if (typeof attr !== typeof undefined && attr !== false) {
                var attr = !attr.contains("#") ? attr : attr.replace("#", '');
                module_id = attr;
            }
            var attr2 = $(mod_sel).attr('type');
            var attr = $(mod_sel).attr('data-type');
            if (typeof attr !== typeof undefined && attr !== false) {
                module_type = attr;
            } else if (typeof attr2 !== typeof undefined && attr2 !== false) {
                module_type = attr2;
            }
        }
        var src = mw.settings.site_url + "api/module?id=" + module_id + "&live_edit=" + liveedit + "&module_settings=true&type=" + module_type;
        return mw.tools.modal.frame({
            url: src,
            width: 532,
            height: 250,
            name: 'module-settings-' + a.replace(/\//g, '_'),
            title: '',
            callback: function () {

            }
        });
    }
    var curr = a || $("#mw_handle_module").data("curr");
    var attributes = {};
    if (curr && curr.id && mw.$('#module-settings-' + curr.id).length > 0) {
        var m = mw.$('#module-settings-' + curr.id)[0];
        m.scrollIntoView();
        mw.tools.highlight(m);
        return false;
    }
    if (curr && curr.attributes) {
        $.each(curr.attributes, function (index, attr) {
            attributes[attr.name] = attr.value;
        });
    }

    var data1 = attributes;
    var module_type = null
    if (data1['data-type'] != undefined) {
        module_type = data1['data-type'];
        data1['data-type'] = data1['data-type'] + '/admin';
    }
    if (data1['data-module-name'] != undefined) {
        delete(data1['data-module-name']);
    }
    if (data1['type'] != undefined) {
        module_type = data1['type'];
        data1['type'] = data1['type'] + '/admin';
    }
    if (module_type != null && view != undefined) {
        data1['data-type'] = data1['type'] = module_type + '/' + view;
    }
    if (typeof data1['class'] != 'undefined') {
        delete(data1['class']);
    }
    if (typeof data1['style'] != 'undefined') {
        delete(data1['style']);
    }
    if (typeof data1.contenteditable != 'undefined') {
        delete(data1.contenteditable);
    }
    data1.live_edit = liveedit;
    data1.module_settings = 'true';
    if (view != undefined) {
        data1.view = view;
    }
    else {
        data1.view = 'admin';
    }
    if (data1.from_url == undefined) {
        //data1.from_url = window.top.location;
        data1.from_url = window.parent.location;
    }
    var modal_name = 'module-settings-' + curr.id;
    if (typeof(data1.view.hash) == 'function') {
        //var modal_name = 'module-settings-' + curr.id +(data1.view.hash());
    }
    var src = mw.settings.site_url + "api/module?" + json2url(data1);

    if (self != top) {
        var modal = top.mw.tools.modal.frame({
            url: src,
            width: 532,
            height: 150,
            name: modal_name,
            title: '',
            callback: function () {
                $(this.container).attr('data-settings-for-module', curr.id);
            }
        });
        return modal;
    } else {

        var iframe_id = 'js-iframe-module-settings-' + curr.id;

        var mod_settings_iframe_html = '<iframe src="' + src + '" class="js-module-settings-edit-item-group" id="' + iframe_id + '"  style="width:100%;height: 90vh;position: absolute" frameborder="0">';

        if (!$("#" + iframe_id).length) {
            $("#js-live-edit-module-settings-items").append(mod_settings_iframe_html);
        }

        if ($("#" + iframe_id).length) {
            $('.js-module-settings-edit-item-group').hide();

            $("#" + iframe_id).show();
        }
    }

}


mw.components.live_edit.modules.getModuleIcon = function (module_type) {

    if (typeof(mw.components.live_edit.modules.registry) != 'undefined' &&
        typeof(mw.components.live_edit.modules.registry[module_type]) != 'undefined' &&
        typeof(mw.components.live_edit.modules.registry[module_type]) == 'object'
    ) {

        if (typeof(mw.components.live_edit.modules.registry[module_type].icon) != 'undefined') {
            return mw.components.live_edit.modules.registry[module_type].icon;
        }


    }

}

