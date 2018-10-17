mw.components.live_edit = mw.components.live_edit || {}
mw.components.live_edit.modules = mw.components.live_edit.modules || {}



mw.components.live_edit.modules.showHandle = function (element) {

    var el = $(element);
    var title = el.dataset("mw-title");
    var id = el.attr("id");
    var module_type = el.dataset("type");
    if (!module_type) {
        var module_type = el.attr("type");
    }

    if (title != '') {
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

            mw.$(settings).each(function() {
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
}

mw.components.live_edit.modules.showSettings = function (el) {


}

