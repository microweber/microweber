mw.admin = mw.admin || {};
mw.admin.custom_fields = mw.admin.custom_fields || {}

mw.admin.custom_fields.initValues = function () {
    var master = mwd.getElementById('custom-fields-post-table');
    if (master === null) {
        return false;
    }
    var all = master.querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-value-edit-inline'),
        l = all.length,
        i = 0;
    for (; i < l; i++) {
        mw.admin.custom_fields.initValue(all[i]);
    }
    mw.admin.custom_fields.addValueButtons();



    var all = master.querySelectorAll('.mw-admin-custom-field-value-edit-text'),
        l = all.length,
        i = 0;
    for (; i < l; i++) {
        mw.admin.custom_fields.initTextAreaValue(all[i]);
    }

    //


}
mw.admin.custom_fields.initTextAreaValue = function (node) {
    if (!node.fieldBinded) {
        node.fieldBinded = true;


        $(node).bind('keyup paste click', function (e) {
            var sh = this.scrollHeight;
            var oh = this.offsetHeight;
            if(sh > oh){
                this.style.height = sh+"px";
            }

        })


        node.onchange = function (e) {
            var data = {
                id: $(this).dataset('id'),
                value: $(this).val()
            }

            $.post(mw.settings.api_url + 'fields/save', data, function () {
              //  mw.reload_module_parent('custom_fields');
                 mw.custom_fields.after_save();


            });


        }
    }
}
mw.admin.custom_fields.initValue = function (node) {
    if (!node.fieldBinded) {
        node.fieldBinded = true;
        node.onclick = function (e) {
            mw.admin.custom_fields.valueLiveEdit(this);
        }
    }
}
mw.admin.custom_fields.addValueButtons = function (root) {
    var root = root || mwd, all = root.querySelectorAll(".btn-create-custom-field-value"), l = all.length, i = 0;
    for (; i < l; i++) {
        if (!!all[i].avbinded) {
            continue;
        }
        all[i].avbinded = true;
        all[i].onclick = function () {
            var span = mwd.createElement('span');
            span.className = 'mw-admin-custom-field-value-edit-inline-holder';
            span.innerHTML = '<span class="mw-admin-custom-field-value-edit-inline" data-id="' + $(this).dataset('id') + '"></span><span onclick="mw.admin.custom_fields.deleteFieldValue(this);" class="delete-custom-fields"></span><span class="custom-field-comma">,</span>';
            mw.admin.custom_fields.initValue(span.querySelector('.mw-admin-custom-field-value-edit-inline'));
            $(this).prev().append(span);
            mw.admin.custom_fields.valueLiveEdit(span.querySelector('.mw-admin-custom-field-value-edit-inline'));
        }
    }

}

mw.admin.custom_fields.valueLiveEdit = function (span) {
    $(span.parentNode).addClass('active');
    mw.tools.addClass(mw.tools.firstParentWithTag(span, 'tr'), 'active');
    var input = mw.tools.liveEdit(span, true, function (el) {
        if (mw.tools.hasClass(el, 'mw-admin-custom-field-value-edit-inline')) {
            var vals = [],
                all = mw.tools.firstParentWithClass(el, 'custom-fields-values-holder').parentNode.querySelectorAll('.mw-admin-custom-field-value-edit-inline'),
                l = all.length,
                i = 0;
            for (; i < l; i++) {
                vals.push(all[i].textContent);
            }

            var data = {
                id: $(el).dataset('id'),
                value: vals
            }
        }
        else {
            var data = {
                id: $(el).dataset('id'),
                name: $(el).text()
            }
        }
        mw.tools.removeClass(mw.tools.firstParentWithTag(this, 'tr'), 'active');
        $.post(mw.settings.api_url + 'fields/save', data, function (adata) {
            var rstr = mwd.getElementById('mw-custom-fields-list-settings-' + data.id).innerHTML.replace(/\s+/g, '');
            if (rstr != '' && !!data.value) {
                mw.reload_module('#mw-custom-fields-list-settings-' + data.id);
            }
            mw.custom_fields.after_save();


        });
        $(el.parentNode).removeClass('active');
        mw.tools.removeClass(mw.tools.firstParentWithTag(el, 'tr'), 'active');
    }, 'mw-ui-field mw-ui-field-small');
    $(input).bind('blur', function () {
        mw.$('.mw-admin-custom-field-value-edit-inline-holder.active').removeClass('active');
        mw.tools.removeClass(mw.tools.firstParentWithTag(this, 'tr'), 'active');
    });
    $(input).bind('keydown', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 9) {
            var parent = mw.tools.firstParentWithClass(e.target, 'mw-admin-custom-field-value-edit-inline-holder');
            if (!e.shiftKey) {
                if (parent.nextElementSibling !== null && mw.tools.hasClass(parent.nextElementSibling, 'mw-admin-custom-field-value-edit-inline-holder')) {
                    mw.admin.custom_fields.valueLiveEdit(parent.nextElementSibling.querySelector('.mw-admin-custom-field-value-edit-inline'));
                }
                else {

                }
            }
            else {
                if (parent.previousElementSibling !== null && mw.tools.hasClass(parent.previousElementSibling, 'mw-admin-custom-field-value-edit-inline-holder')) {
                    mw.admin.custom_fields.valueLiveEdit(parent.previousElementSibling.querySelector('.mw-admin-custom-field-value-edit-inline'));
                }
                else {

                }
            }

            return false;
        }
    });
}
mw.admin.custom_fields.make_fields_sortable = function () {
    var sortable_holder = mw.$("#custom-fields-post-table").eq(0);
    if (!sortable_holder.hasClass('ui-sortable') && sortable_holder.find('tr').length > 1) {
        sortable_holder.sortable({
            items: 'tr',
            distance: 35,
            update: function (event, ui) {
                var obj = {ids: []};
                $(this).find(".mw-admin-custom-field-name-edit-inline").each(function () {
                    var id = $(this).dataset("id");
                    obj.ids.push(id);
                });


                $.post(mw.settings.api_url + "fields/reorder", obj, function () {

                });
            }
        });
    }
    return sortable_holder;
}
mw.admin.custom_fields.del = function (id, toremove) {
    var q = "Are you sure you want to delete '" + mw.$('#mw-custom-list-element-' + id + ' .mw-admin-custom-field-name-edit-inline').text() + "' ?";
    mw.tools.confirm(q, function () {

        mw.custom_fields.remove(id, function (data) {
            mw.$('#mw-custom-list-element-' + id).addClass('scale-out');
            setTimeout(function () {
                mw.reload_module_parent('custom_fields');
                mw.reload_module('custom_fields/list', function () {
                    if (!!toremove) {
                        $(toremove).remove();
                    }
                    mw.$("#custom-field-editor").removeClass('mw-custom-field-created').hide();
                    $(window).trigger('customFieldSaved', id);
                    if (typeof load_iframe_editor === 'function') {
                        load_iframe_editor();
                    }
                    mw.admin.custom_fields.initValues();
                });
            }, 300);

        });
    });
}
mw.admin.custom_fields.deleteFieldValue = function (el) {
    $(el.parentNode).remove();
}
mw.admin.custom_fields.edit_custom_field_item = function ($selector, id, callback, event) {
    var preview = mwd.getElementById('mw-custom-fields-list-preview-' + id),
        settings = mwd.getElementById('mw-custom-fields-list-settings-' + id);
    if (preview.style.display != 'none') {
        $(preview).slideUp();
        $(settings).slideDown();
        var data = {};
        data.settings = 'y';
        data.field_id = id;
        mw.$($selector).load(mw.settings.api_html + 'fields/make', data, function (a) {
            mw.is.func(callback) ? callback.call(this) : '';
            mw.custom_fields.sort($selector);
            mw.$($selector + " input").bind("change", function () {
                mw.custom_fields.save_form($selector);
            });
            mw.$($selector + " input").bind('focus blur', function (e) {
                var func = e.type === 'focus' ? 'addClass' : 'removeClass';
                mw.tools[func](mw.tools.firstParentWithTag(e.target, 'tr'), 'active');
            });
        });
    }
    else {
        $(settings).slideUp();
        $(preview).slideDown();

    }
}

$(mww).bind('load', function () {
    mw.admin.custom_fields.initValues();
});
$(mwd).ready(function () {
    mw.admin.custom_fields.initValues();
});