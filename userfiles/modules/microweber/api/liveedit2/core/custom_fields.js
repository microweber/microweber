mw.custom_fields = {
    settings: {
        id: 0
    },
    saveurl: mw.settings.api_url + 'fields/save',
    create: function (obj, callback, error) {
        obj = $.extend({}, this.settings, obj);
        obj.id = 0;
        this.edit(obj, callback, error);
    },
    edit: function (obj, callback, error) {
        obj = $.extend({}, this.settings, obj);

        $.post(mw.custom_fields.saveurl, obj, function (data) {
            if (typeof callback === 'function') {
                if (!!data.error) {
                    if (typeof error === 'function') {
                        error.call(data.error);
                    }
                }
                else {
                    callback.call(data);
                }
            } else {

                mw.custom_fields.after_save();
            }
        })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (typeof error === 'function') {
                    error.call(textStatus);
                }
            });
    },
    sort: function (group) {
        group = document.getElementById(group);
        if (group == null) {
            return;
        }
        if (group.querySelectorAll('.mw-custom-field-form-controls').length > 0) {
            mw.$(group).sortable({
                handle: '.custom-fields-handle-field',
                placeholder: 'custom-fields-placeholder',
                axis: 'y',
                items: ".mw-custom-field-form-controls",
                start: function (a, ui) {
                    mw.$(ui.placeholder).height($(ui.item).outerHeight())
                },
                update: function () {
                    var par = mw.tools.firstParentWithClass(group, 'mw-admin-custom-field-edit-item-wrapper');
                    if (!!par) {
                        // mw.custom_fields.save(par);
                        //mw.$(".custom-fields-settings-save-btn").attr('disabled', false)
                    }
                }
            });
        }
    },
    remove: function (id, callback, err) {
        var obj = {
            id: id
        };
        $.post(mw.settings.api_url + "fields/delete", obj, function (data) {
            if (typeof callback === 'function') {
                callback.call(data);
            }
            mw.custom_fields.after_save();
        }).fail(function () {
            if (typeof err === 'function') {
                err.call();
            }
        });
    },

    save: function (id, callback) {
        return this.save_form(id, callback);
    },
    save_form: function (id, callback) {
        var obj = mw.custom_fields.serialize(id);
        $.post(mw.custom_fields.saveurl, obj, function (data) {
            if (data.error != undefined) {
                return false;
            }

            var $cfadm_reload = false;
            if (obj.cf_id === undefined) {
                //      mw.reload_module('.edit [data-parent-module="custom_fields"]');
            }
            mw.$(".mw-live-edit [data-type='custom_fields']").each(function () {
                if (!mw.tools.hasParentsWithClass(this, 'mw_modal') && !mw.tools.hasParentsWithClass(this, 'is_admin')) {
                    //mw.reload_module(this);
                } else {
                    var $cfadm_reload = true;
                }
            });

            mw.reload_module_parent('custom_fields');
            if (typeof load_iframe_editor === 'function') {
                load_iframe_editor();
            }

            mw.reload_module('#mw-admin-custom-field-edit-item-preview-' + data);

            mw.reload_module_everywhere('custom_fields/list', function(win){
                if(win !== window) {
                    if (callback) callback.call(data);
                    mw.trigger('customFieldSaved', [id, data]);
                }
            });

            mw.custom_fields.after_save();
        });
    },

    after_save: function () {

        mw.reload_module_everywhere('custom_fields');
        mw.reload_module_everywhere('custom_fields/list');


        mw.trigger("custom_fields.save");

    },

    autoSaveOnWriting: function (el, id) {
        return false;
        /*mw.on.stopWriting(el, function () {
            this.save_form(id, function () {
                if (typeof __sort_fields === 'function') {
                    // __sort_fields();
                }
            });
        });*/
    },

    add: function (el) {
        var parent = mw.$(mw.tools.firstParentWithClass(el, 'mw-custom-field-form-controls'));
        var clone = parent.clone(true);
        parent.after(clone);
        clone.find("input").val("").focus();
    },
    serialize: function (id) {
        var el = mw.$(id),
        fields =
            "input[type='text'], " +
            "input[type='email'], input[type='number'], input[type='password'], input[type='hidden'], " +
            "textarea, select, input[type='checkbox']:checked, input[type='radio']:checked";
        var data = {};
        data.options = {};
        mw.$(fields, el).not(':disabled').filter(function() { return !!this.name; }).each(function () {
            var el = this, _el = mw.$(el);
            var val = _el.val();
            var name = el.name;
            var notArraySelect = this.nodeName === 'SELECT' && this.multiple === false;
            var notArrayDefault = this.name !== 'options[file_types]';
            var notArray = notArraySelect || notArrayDefault;

            if (name.contains("[")) {

                if (name.contains('[]')) {
                    var _name = name.replace(/[\[\]']+/g, '');

                    if (name.indexOf('option') === 0) {
                        try {
                            data.options.push(val);
                        }
                        catch (e) {
                            data.options = [val];
                        }
                    }
                    else {
                        try {
                            data[_name].push(val);
                        }
                        catch (e) {
                            data[_name] = [val];
                        }
                    }
                }
                else {
                    if (name.indexOf('option') === 0) {
                        name = name.slice(name.indexOf("[") + 1, name.indexOf("]"));
                        if(notArray) {
                            data.options[name] = val;
                        }
                        else {
                            data.options[name] = data.options[name] || [];
                            data.options[name].push(val);
                        }


                    }
                    else {
                        var arr_name = name.slice(0, name.indexOf("["));
                        var key = name.slice(name.indexOf("[") + 1, name.indexOf("]"));
                        if (typeof data[arr_name] === 'object') {
                            data[arr_name][key] = data[arr_name][key] || [];
                            data[arr_name][key].push(val);
                        }
                        else {
                            data[arr_name] = {};
                            data[arr_name][key] = [val];
                        }
                    }
                }
            }
            else {
                data[name] = val;
            }
        });
        if (mw.tools.isEmptyObject(data.options)) {
            data.options = '';
        }

        return data;
    }

}
