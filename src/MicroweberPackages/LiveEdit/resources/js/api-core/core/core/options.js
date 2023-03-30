// JavaScript Document

/**
 *
 * Options API
 *
 * @package        js
 * @subpackage        options
 * @since        Version 0.567
 */

// ------------------------------------------------------------------------

/**
 * mw.options
 *
 *  mw.options object
 *
 * @package        js
 * @subpackage    options
 * @category    options internal api
 * @version 1.0
 */
mw.options = {
    saveOption: function (o, c, err) {
        if (typeof o !== 'object') {
            return false;
        }
        var group = o.group || o.option_group,
            key = o.key || o.option_key,
            value = typeof o.value !== 'undefined' ? o.value : o.option_value;

        if (!group || !key || (typeof value === 'undefined')) {
            return false;
        }
        var lang = false;
        if (typeof(o.lang) !== 'undefined') {
            lang = o.lang;
        }

        var data = {
            option_group: group,
            option_key: key,
            option_value: value,

        };

        if(lang){
            // for multilanguage module
            data.lang=lang;
        }



        return $.ajax({
            type: "POST",
            url: mw.settings.site_url + "api/save_option",
            data: data,
            success: function (a) {
                if (typeof c === 'function') {
                    c.call(a);
                }
            },
            error: function (a) {
                if (typeof err === 'function') {
                    err.call(a);
                }
            }
        });
    },
    save: function (el, callback) {


        el = mw.$(el);
        var og, og1, refresh_modules11;
        if (!el) {
            return;
        }


        var opt_id = el.attr('data-id');

        og1 = og = el.attr('option-group') || el.attr('option_group') || el.attr('data-option-group');


        if (og1 == null || (typeof(og1) === 'undefined') || og1 == '') {

        }
        var og_parent = null
        var og_test = mw.tools.firstParentWithClass(el[0], 'module');
        if (og_test) {
            og_parent = og_test.id;

            og_parent = mw.$(og_test).attr('for-module-id') || og_test.id;


        }
        // refresh_modules11 = og1 = og = og_test.id;


        var refresh_modules12 = el.attr('data-reload') || el.attr('data-refresh');

        var also_reload = el.attr('data-reload') || el.attr('data-also-reload');

        var modal = mw.$(mw.dialog.get(el).container);

        if (refresh_modules11 == undefined && modal !== undefined) {

            var for_m_id = modal.attr('data-settings-for-module');

        }
        if (refresh_modules11 == undefined) {
            var refresh_modules11 = el.attr('data-refresh');

        }

        var a = ['data-module-id', 'data-settings-for-module', 'option-group', 'data-option-group', 'data-refresh'],
            i = 0, l = a.length;


        var mname = modal !== undefined ? modal.attr('data-type') : undefined;

        // if (typeof(refresh_modules11) == 'undefined') {
        //     for (; i < l; i++) {
        //         var og = og === undefined ? el.attr(a[i]) : og;
        //     }
        // } else {
        //     var og = refresh_modules11;
        // }
        //
        // if (og1 != undefined) {
        //     var og = og1;
        //     if (refresh_modules11 == undefined) {
        //         if (refresh_modules12 == undefined) {
        //             refresh_modules11 = og1;
        //         } else {
        //             refresh_modules11 = refresh_modules12;
        //         }
        //     }
        // }


        var val;
        if (el[0].type === 'checkbox') {
            val = '',
                dvu = el.attr('data-value-unchecked'),
                dvc = el.attr('data-value-checked');
            if (!!dvu && !!dvc) {
                val = el[0].checked ? dvc : dvu;
            }
            else {

                var items = document.getElementsByName(el[0].name), i = 0, len = items.length;
                for (; i < len; i++) {
                    var _val = items[i].value;
                    val = items[i].checked == true ? (val === '' ? _val : val + "," + _val) : val;
                }
            }

        }
        else {
            val = el.val();
        }
        if (typeof(og) == 'undefined' && typeof(og) == 'undefined' && og_parent) {
            og = og_parent;
        }




        var o_data = {
            option_key: el.attr('name'),
            option_group: og,
            option_value: val
        }


        if (mname === undefined) {


       if (mname === undefined && og_test !== undefined && og_test &&  $(og_test).attr('data-type')) {
            var mname_from_type = $(og_test).attr('data-type');
            mname = (mname_from_type.replace('/admin', ''));
            o_data.module = mname;
        } else if (og_test !== undefined && og_test && $(og_test).attr('parent-module')) {
              o_data.module = $(og_test).attr('parent-module');
             }
        }


        if (mname !== undefined) {
            o_data.module = mname;
        }


        if (for_m_id !== undefined) {
            o_data.for_module_id = for_m_id;
        }
        if (og != undefined) {
            o_data.id = have_id;
        }




        var have_id = el.attr('data-custom-field-id');

        if (have_id != undefined) {
            o_data.id = have_id;
        }

        var have_option_type = el.attr('data-option-type');

        if (have_option_type != undefined) {
            o_data.option_type = have_option_type;
        } else {
            var have_option_type = el.attr('option-type');

            if (have_option_type != undefined) {
                o_data.option_type = have_option_type;
            }
        }
        var reaload_in_parent = el.attr('parent-reload');

        if (opt_id !== undefined) {
            o_data.id = opt_id;
        }

        var attrLang = el.attr('lang');
        if (typeof(attrLang) !== 'undefined') {
            o_data.lang = attrLang;
        }

        var attrModule = el.attr('module');
        if (typeof(attrModule) !== 'undefined') {
            o_data.module = attrModule;
        }

        $.ajax({
            type: "POST",
            url: mw.settings.site_url + "api/save_option",
            data: o_data,
            success: function (data) {

                var which_module_to_reload = null;


                if (typeof(refresh_modules11) == 'undefined') {
                    which_module_to_reload = og1;
                } else if (refresh_modules12) {
                    which_module_to_reload = refresh_modules12;
                }

                if ((typeof(liveEditSettings) != 'undefined' && liveEditSettings) || mw.top().win.liveEditSettings) {
                    if (!og1 && og_parent) {
                        which_module_to_reload = og_parent;
                    }
                }

                var reload_in_parent_trieggered = false;





                if (mw.admin) {
                    if (mw.top().win.mweditor && mw.top().win.mweditor.contentWindow) {
                        setTimeout(function () {
                            mw.top().win.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload);

                        }, 777);
                    }
                }
                if (window.parent.mw) {

                    if (self !== top) {

                        setTimeout(function () {

                            var mod_element = window.parent.document.getElementById(which_module_to_reload);
                            if (mod_element) {
                                // var module_parent_edit_field = window.mw.parent().tools.firstParentWithClass(mod_element, 'edit')
                               // var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                                var module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit:not([itemprop=dateModified])']);
                                if (!module_parent_edit_field) {
                                   module_parent_edit_field = window.mw.parent().tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]']);
                                }

                                if (module_parent_edit_field) {
                                   // window.mw.parent().tools.addClass(module_parent_edit_field, 'changed');
                                    window.mw.parent().wysiwyg.change(module_parent_edit_field);
                                    window.mw.parent().askusertostay = true;


                                }
                            }

                            mw.reload_module_parent("#" + which_module_to_reload);
                            if (which_module_to_reload != og1) {
                                mw.reload_module_parent("#" + og1);
                            }
                            reload_in_parent_trieggered = 1;


                        }, 777);
                    }

                    if (window.mw.parent().reload_module != undefined) {

                        if (!!mw.admin) {
                            setTimeout(function () {
                                window.mw.parent().reload_module("#" + which_module_to_reload);
                                mw.options.___rebindAllFormsAfterReload();
                            }, 777);
                        }
                        else {
                            if (window.parent.mweditor != undefined) {
                                window.parent.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                            if (window.parent.mw != undefined) {
                                window.mw.parent().reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.mw.parent().exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                        }
                        reload_in_parent_trieggered = 1;

                    }
                }


                // if (reaload_in_parent != undefined && reaload_in_parent !== null) {
                //     //     window.mw.parent().reload_module("#"+refresh_modules11);
                //
                //     return false;
                // }


                if (also_reload != undefined) {


                    if (window.mw != undefined && reaload_in_parent !== true) {
                        if (window.mw.reload_module !== undefined) {

                            window.mw.reload_module(also_reload, function (reloaded_el) {

                                //  mw.options.form(reloaded_el, callback);
                                mw.options.___rebindAllFormsAfterReload();
                            });
                            window.mw.reload_module('#' + also_reload, function (reloaded_el) {

                                //mw.options.form(reloaded_el, callback);
                                mw.options.___rebindAllFormsAfterReload();
                            });
                        }
                    }

                }

                /*           if (reaload_in_parent !== true && for_m_id != undefined && for_m_id != '') {
                               for_m_id = for_m_id.toString()
                               if (window.mw != undefined) {




                                   // if (window.mw.reload_module !== undefined) {
                                   //
                                   // 			window.mw.reload_module('#'+for_m_id, function(reloaded_el){
                                   //
                                   // 				mw.options.form(reloaded_el, callback);
                                   // 			});
                                   //        }
                               }
                           } else*/


                if (reload_in_parent_trieggered == false && reaload_in_parent !== true && which_module_to_reload != undefined && which_module_to_reload != '') {
                    which_module_to_reload = which_module_to_reload.toString()


                    if (window.mw.reload_module !== undefined) {

                        mw.reload_module_parent(which_module_to_reload);
                        mw.reload_module_parent("#" + which_module_to_reload);


                    }


                }


                typeof callback === 'function' ? callback.call(data) : '';
                setTimeout(function () {
                    mw.options.___rebindAllFormsAfterReload();
                }, 111);
                //
                //
                //d(refresh_modules11);
                //d(mw.options._bindedRootFormsRegistry);
            }
        })
    }
};

mw.options._optionSaved = null;

mw.options._bindedRootFormsRegistry = [];

mw.options.remove_bindings = function ($selector) {

    var $root = mw.$($selector);
    var root = $root[0];
    if (!root) return;

    if (root._optionsEvents) {
        delete(root._optionsEvents);
        root._optionsEventsClearBidings = true;
    }
    root.addClass('mw-options-form-force-rebind');


    mw.$("input, select, textarea", root)
        .not('.mw-options-form-binded-custom')
        .each(function () {
            var item = mw.$(this);


            if (item && item[0] && item[0]._optionsEventsBinded) {
                delete(item[0]._optionsEventsBinded);

            }
        });

};
mw.options.form = function ($selector, callback, beforepost) {



    //setTimeout(function () {


    var numOfbindigs = 0;
    var force_rebind = false;

    var $root = mw.$($selector);
    var root = $root[0];
    if (!root) return;

    //
    if (root && $root.hasClass('mw-options-form-force-rebind')) {
        force_rebind = true;

    }

    if (!root._optionsEvents) {

        mw.$("input, select, textarea", root)
            .not('.mw-options-form-binded-custom')
            .each(function () {
                //this._optionSaved = true;

                var item = mw.$(this);
                if (force_rebind) {
                    item[0]._optionsEventsBinded = null;
                }


                if (item && item[0] && !item[0]._optionsEventsBinded) {

                    if (item.hasClass('mw_option_field')) {
                        numOfbindigs++;


                        item[0]._optionsEventsBinded = true;


                        if (root._optionsEventsClearBidings) {
                            item.off('change input paste');
                        }

                        item.addClass('mw-options-form-binded');
                        item.on('change input paste', function (e) {

                            var isCheckLike = true;
                            var token = isCheckLike ? this.name : this.name + mw.$(this).val();
                            mw.options.___slowDownEvent(token, this, function () {
                                if (typeof root._optionsEvents.beforepost === 'function') {
                                    root._optionsEvents.beforepost.call(this);
                                }
                                if (top !== self && window.mw.parent().drag && window.mw.parent().drag.save) {
                                    window.mw.parent().drag.save();
                                }
                                mw.options.save(this, root._optionsEvents.callback);
                            });
                            //}
                        });
                    }
                }
            });
    }




    // REBIND
    if (numOfbindigs > 0) {
        root._optionsEvents = root._optionsEvents || {};
        root._optionsEvents = $.extend({}, root._optionsEvents, {callback: callback, beforepost: beforepost});


        var rebind = {};
        if (typeof root._optionsEvents.beforepost === 'function') {
            rebind.beforepost = root._optionsEvents.beforepost;
        }
        rebind.callback = root._optionsEvents.callback;
        rebind.binded_selector = $selector;
        var rebindtemp = mw.tools.cloneObject(rebind);
        //fix here chek if in array


        var is_in = mw.options._bindedRootFormsRegistry.filter(function (a) {
            return a.binded_selector === $selector;
        })

        if (!is_in.length) {
            mw.options._bindedRootFormsRegistry.push(rebindtemp);
        }
    }
    // END OF REBIND


    //}, 10,$selector, callback, beforepost);


};


mw.options.___slowDownEvents = {};
mw.options.___slowDownEvent = function (token, el, call) {
    if (typeof mw.options.___slowDownEvents[token] === 'undefined') {
        mw.options.___slowDownEvents[token] = null;
    }
    clearTimeout(mw.options.___slowDownEvents[token]);
    mw.options.___slowDownEvents[token] = setTimeout(function () {
        call.call(el);
    }, 700);
};

mw.options.___rebindAllFormsAfterReload = function () {

    var token = '___rebindAllFormsAfterReload';


    mw.options.___slowDownEvent(token, this, function () {


        for (var i = 0, l = mw.options._bindedRootFormsRegistry.length; i < l; i++) {
            var binded_root = mw.options._bindedRootFormsRegistry[i];
            if (binded_root.binded_selector) {

                var $root = mw.$(binded_root.binded_selector);
                var root = $root[0];
                if (root) {

                    var rebind_beforepost = null;
                    var rebind_callback = null;
                    if (typeof binded_root.beforepost === 'function') {
                        var rebind_beforepost = binded_root.beforepost;
                    }

                    if (typeof binded_root.callback === 'function') {
                        var rebind_callback = binded_root.callback;
                    }
                    var has_non_binded = false;
                    mw.$("input, select, textarea", root)
                        .not('.mw-options-form-binded-custom')
                        .not('.mw-options-form-binded')
                        .each(function () {
                            var item = mw.$(this);
                            if (item.hasClass('mw_option_field')) {
                                if (!item[0]._optionsEventsBinded) {
                                    has_non_binded = true;
                                    item.attr('autocomplete', 'off');
                                }
                            }
                        });

                    if (root._optionsEvents && has_non_binded && rebind_callback) {
                        root._optionsEvents = null;
                        root._optionsEventsClearBidings = true;
                        mw.options.form(binded_root.binded_selector, rebind_callback, rebind_beforepost);

                        // mw.options._bindedRootFormsRegistry =  mw.options._bindedRootFormsRegistry.filter(function (a) {
                        //     return a.binded_selector != binded_root.binded_selector
                        // })

                    }
                }


            }
        }
    });
}
//
// mw.options.___locateModuleNodesToBeRealoaded = function (selectror,window_scope) {
//
//    var module = module.replace(/##/g, '#');
//    var m = mw.$(".module[data-type='" + module + "']");
//    if (m.length === 0) {
//        try { var m = mw.$(module); }  catch(e) {};
//    }
//
//}
