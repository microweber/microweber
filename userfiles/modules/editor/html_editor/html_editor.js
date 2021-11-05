wroot = window.opener || mw.top().win;

mw.html_editor = {};
mw.html_editor.map = {};
mw.html_editor.init = function () {

    var fields = mw.html_editor.get_edit_fields(true);
    mw.html_editor.build_dropdown(fields);

    mw.html_editor.populate_editor();
    mw.html_editor.set_height();

};


mw.html_editor.set_height = function () {

}
mw.html_editor.get_edit_fields = function (also_in_modules, root_element_selector) {

    also_in_modules = typeof also_in_modules === 'undefined' ? false : also_in_modules;


    var fields_arr = [];


    var get_edit_fields = wroot.$('.edit');

    if (typeof(root_element_selector) != 'undefined') {
        get_edit_fields = wroot.$('.edit', root_element_selector);
    }


    get_edit_fields.each(function () {
        var is_in_module = wroot.mw.tools.firstParentWithClass(this, 'module');
        if (!is_in_module || also_in_modules) {
            fields_arr.push(this);
        }
    });


    return fields_arr;
};

mw.html_editor.createItemContent = function (option) {
    var method = 'frame';
    var text = option.el.textContent.trim();
    if (method === 'text') {
        return text.substring(0, 45) + '...';
    }
    else {
        var framehold = document.createElement('div');
        framehold.className = 'htmleditliframe'
        var frame = document.createElement('iframe');
        framehold.appendChild(frame)
        frame.src = 'about:blank'
        frame.frameBorder = 0;
        frame.scrolling = 'no';
        setTimeout(function () {
            frame.contentDocument.body.innerHTML = option.el.innerHTML;
            var root = mw.top().win.opener ? mw.top().win.opener.document.documentElement : mw.top().win.document.documentElement;
            $('link', root).each(function () {
                $(this).clone(true).appendTo(frame.contentDocument.body);
            });
        }, 78)
        return framehold;
    }
}
mw.html_editor.build_dropdown = function (fields_array, screenShot) {
    screenShot = typeof screenShot === 'undefined' ? true : screenShot
    var html_dd = {};
    $(fields_array).each(function () {
        var dd_grp = $(this).attr('rel');
        var dd_field = $(this).attr('field');

        if (dd_grp) {
            if (typeof(html_dd[dd_grp]) == 'undefined') {
                html_dd[dd_grp] = [];
            }
            var temp = {};
            temp.field = dd_field;
            temp.rel = dd_grp;
            temp.el = this;

            mw.html_editor.map[dd_grp + '/' + dd_field] = temp;
            html_dd[dd_grp].push(temp);
        }

    });


    var $select = $("<ul>");
    $select.attr('id', 'select_edit_field');
    //$select.attr('class', 'mw-ui-field');

    var has_selected = false;

    $select.appendTo("#select_edit_field_wrap");
    $.each(html_dd, function (groupName, options) {
        var $optgroup = $("<li>", {label: groupName, rel: groupName});
        $optgroup.appendTo($select);
        $optgroup.html(groupName);
        var $optgroupul = $("<ul>", {label: groupName, rel: groupName});
        $optgroupul.appendTo($optgroup);
        $.each(options, function (j, option) {

            if (!option.field) {
                mw.log('Warning: Your editable region does not have a "field" attribute');
                mw.log(option);

            } else {


                var $option = $("<li>", {

                    value: option.rel,
                    rel: option.rel,
                    field: option.field
                }).append(screenShot ? mw.html_editor.createItemContent(option) : option.field);
                if (!has_selected && option.rel == 'content') {
                    has_selected = true;
                    $($option).attr('selected', 'selected');
                }
                $option.appendTo($optgroupul);
            }

        });
    });


    $('li li', $select).on('click', function (e) {
        e.stopPropagation()
        $('li', $select).removeClass('selected');
        $(this).addClass('selected');
        mw.html_editor.populate_editor()
    });

};


mw.html_editor.populate_editor = function () {
    var value = $('#select_edit_field li.selected');

    if (!value.length) {
        value = $('select#select_edit_field li li').eq(0);
    }
    if (!value.length) {
        return;
    }

    $('#fragment-holder').remove();
    var ed_val = '';
    var dd_grp = value.attr('rel');
    var dd_field = value.attr('field');


    if (typeof(mw.html_editor.map[dd_grp + '/' + dd_field]) != 'undefined') {


        ed_val = $(mw.html_editor.map[dd_grp + '/' + dd_field].el).html();

        if(wroot.mw === mw.top()) {
            wroot.mw.tools.scrollTo('[field="' + dd_field + '"][rel="' + dd_grp + '"]')
        }

        wroot.$('.html-editor-selcted').removeClass('html-editor-selcted');
        wroot.$('[field="' + dd_field + '"][rel="' + dd_grp + '"]').addClass('html-editor-selcted');

        var frag = document.createDocumentFragment();
        var html = ed_val;
        var holder = document.createElement("div")
        holder.id = 'fragment-holder'
        holder.innerHTML = window.html_beautify ? html_beautify(html) : html;
        frag.appendChild(holder)
        //var s = $('.module', $(frag)).html('[module]');
        var s = $('.module', $(frag)).html('[module]');

        // $('#divHtml').find("*").removeClass();


        var ed_val = $(holder).html();
        //    d(ed_val);
    } else {
        var ed_val = 'Select element to edit';
    }

    if (typeof html_code_area_editor != 'undefined') {

        if (typeof html_beautify != 'undefined') {
            //var formattedXML = js_beautify.beautify_html(ed_val, { indent_size: 2 });
            var formattedXML = (html_beautify(ed_val, {
                "indent_size": 4,
                "indent_char": "",
                "indent_with_tabs": false,
                "eol": "\n",
                "end_with_newline": true,
                "indent_level": 100,
                "preserve_newlines": false,
                "max_preserve_newlines": 100,
                "space_in_paren": false,
                "space_in_empty_paren": false,
                "jslint_happy": false,
                "space_after_anon_function": false,
                "brace_style": "none",
                "unindent_chained_methods": true,
                "break_chained_methods": false,
                "keep_array_indentation": false,
                "unescape_strings": true,
                "wrap_line_length": 111110,
                "wrap_attributes": 'none',
                "e4x": false,
                "comma_first": false,
                "operator_position": "before-newline"
            }));
            ed_val = formattedXML;
            //     d(formattedXML);
        }


        //  d(ed_val);

        html_code_area_editor.setValue(ed_val);
        html_code_area_editor.refresh();
    }

    $('#custom_html_code_mirror').val(ed_val);
    $('#custom_html_code_mirror').attr('current', dd_grp + '/' + dd_field);


};


mw.html_editor.apply_and_save = function () {


    mw.html_editor.apply();
    if(wroot.mw.drag.save) {
        mw.tools.loading('#module-id-mw_global_html_editor', true);
        wroot.mw.drag.save(undefined, function () {
            mw.tools.loading('#module-id-mw_global_html_editor', false);
            mw.notification.success(mw.msg.saved)
        })
    }
    var form = wroot.mw.top().$('#quickform-edit-content');
    if(form.length) {
        form.submit()
    }

}
mw.html_editor.apply = function () {
    var cur = $('#custom_html_code_mirror').attr('current');
    var html = $('#custom_html_code_mirror').val();
    if (typeof(mw.html_editor.map[cur]) != 'undefined') {


        var el = mw.html_editor.map[cur].el;
        $(el).html(html);

        if ($(el).hasClass('edit')) {
            var master_edit_field_holder = el;

        } else {
            var master_edit_field_holder = wroot.mw.tools.firstParentWithClass(el, 'edit');

        }

        var selected_el =  $(el);
        var modules_ids = {};
        var modules_list = $('.module', selected_el);


        $(modules_list).each(function () {
            var id = $(this).attr('id');
            if (id) {
                id = '#' + id;
            } else {
                var id = $(this).attr('data-type');
            }
            if (!id) {
                var id = $(this).attr('type');
            }
            modules_ids[id] = true;
        });


        $.each(modules_ids, function (index, value) {
            wroot.mw.reload_module(index);
        });


        if (master_edit_field_holder) {
            $(master_edit_field_holder).addClass("changed");
            setTimeout(function () {
                wroot.mw.drag.fix_placeholders(true);
                wroot.mw.on.DOMChangePause = false;


            }, 200);
        }

        mw.html_editor.populate_editor();
    }

}


mw.html_editor.reset_content = function (also_reset_modules) {

    var value = $('#select_edit_field li.selected');

    if (value.length == 0) {
        value = $('#select_edit_field li li').eq(0);
    }
    if (value.length == 0) {
        return;
    }
    var field = value.attr('field');
    var rel = value.attr('rel');
    var cur = rel + '/' + field;


    var html = '';


    if (typeof(mw.html_editor.map[cur]) != 'undefined') {


        var el = mw.html_editor.map[cur].el;
        var mod_ids = [];

        var mod_ids_inside_el = mw.html_editor.find_all_module_ids_in_element(el);
        if(mod_ids_inside_el){
            mod_ids = mod_ids.concat(mod_ids_inside_el);
        }

        // if we are in layout, we will also reset layout settings
        var is_inside_layout = mw.tools.firstParentWithClass(el, 'module');
        if (is_inside_layout) {
            var is_inside_layout_attr = $(is_inside_layout).attr('type');
            if (typeof is_inside_layout_attr === 'undefined') {
                is_inside_layout_attr = $(is_inside_layout).attr('data-type');
            }
            if (typeof is_inside_layout_attr !== 'undefined' && is_inside_layout_attr === 'layouts') {
                var is_inside_layout_attr_id = $(is_inside_layout).attr('id');
                if(is_inside_layout_attr_id){
                    mod_ids = mod_ids.concat([is_inside_layout_attr_id]);
                }
            }
        }


        var mod_ids_with_presets = mw.html_editor.find_all_module_ids_in_element(el, true);


        if (also_reset_modules) {
            var data = {};
            data.modules_ids = mod_ids;
            if (mod_ids) {
                $.post(mw.settings.api_url + "content/reset_modules_settings", data);
            }
        }


        $.each(mod_ids_with_presets, function () {
            var el_with_preset_id = mw.top().$('#' + this).attr('data-module-original-id');
            if (el_with_preset_id) {
                mw.top().$('#' + this).attr('id', el_with_preset_id);
                mw.top().$('#' + this).removeAttr('data-module-original-id');
                mw.top().$('#' + this).removeAttr('data-module-original-attrs');
            }
        });


        if (field == 'title') {
            var html = 'Untitled content';
        }


        var childs_arr = {};

        $(el).find('.edit').andSelf().each(function (i) {
            var some_child = {};
            some_child.rel = $(this).attr('rel');
            some_child.field = $(this).attr('field');
            if(some_child.rel && some_child.field){
            childs_arr[i] = some_child;
            }
        });


        var childs_arr_data = {'reset':childs_arr};


        //if (childs_arr.length) {

        $.post(mw.settings.api_url + "content/reset_edit", childs_arr_data);

        //}

        $(el).html(html);

        if ($(el).hasClass('edit')) {
            var master_edit_field_holder = el;

        } else {
            var master_edit_field_holder = wroot.mw.tools.firstParentWithClass(el, 'edit');

        }


        mw.tools.addClass(el, 'changed')
        mw.tools.foreachParents(el, function () {
            if (mw.tools.hasClass(this, 'edit')) {
                mw.tools.addClass(this, 'changed')
            }
        });
        var all = el.querySelectorAll('.edit'), i = 0;
        for (var i = 0; i < all.length; i++) {
            mw.tools.addClass(all[i], 'changed')
        }


        if (master_edit_field_holder) {


            wroot.mw.on.DOMChangePause = true;
            setTimeout(function () {
                wroot.mw.drag.fix_placeholders(true);
                wroot.mw.on.DOMChangePause = false;
                var saved = wroot.mw.drag.save();
                if (saved) {
                    saved.success(function (saved_data) {

                        if (typeof saved_data[0] == 'undefined') {
                            return;
                        }
                        saved_data = saved_data[0];


                        setTimeout(function () {
                            wroot.window.location.reload()

                        }, 1000);

                    })
                }


            }, 200);
        }
    }


}


mw.html_editor.find_all_module_ids_in_element = function (element, only_with_presets) {

    var mod_ids = [];

    var mod_in_mods_html_btn = '';
    //  var mods_in_mod = wroot.$(element).find('.module');
    var mods_in_mod = wroot.$(element).find('.module');

    if (mods_in_mod) {


        $(mods_in_mod).each(function () {

            var inner_mod_type = $(this).attr("type");
            var inner_mod_id = $(this).attr("id");
            var preset_id = $(this).attr("data-module-original-id");

            if (typeof only_with_presets != 'undefined' && only_with_presets) {

                if (preset_id) {
                    mod_ids.push(inner_mod_id);
                }
            } else {
                if (!preset_id) {
                    mod_ids.push(inner_mod_id);
                }
            }


        });
        return mod_ids;
    }


}

