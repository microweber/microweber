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
        if ((!o.group && !o.option_group) || (!o.key && !o.option_key) || (typeof o.value === 'undefined' && typeof o.option_value === 'undefined')) {
            return false;
        }
        var data = {
            option_group: o.group || o.option_group,
            option_key: o.key || o.option_key,
            option_value: typeof o.value !== 'undefined' ? o.value : o.option_value
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


        var el = $(el), og,og1 , refresh_modules11;

        if (!el) {
            return;
        }




        var opt_id = el.attr('data-id');

        og1= og = el.attr('option-group') || el.attr('option_group') || el.attr('data-option-group');




        if (og1 == null || (typeof(og1) === 'undefined') || og1 == '') {

        }
        var og_parent = null
        var og_test = mw.tools.firstParentWithClass(el[0], 'module');
        if(og_test){
            og_parent = og_test.id;
        }
       // refresh_modules11 = og1 = og = og_test.id;


         var refresh_modules12 = el.attr('data-reload') || el.attr('data-refresh') ;

        var also_reload = el.attr('data-reload') || el.attr('data-also-reload');

        var modal = $(mw.tools.modal.get(el).container);

        if (refresh_modules11 == undefined && modal !== undefined) {

            var for_m_id = modal.attr('data-settings-for-module');

        }
        if (refresh_modules11 == undefined ) {
            var refresh_modules11 = el.attr('data-refresh');

        }

        var a = ['data-module-id', 'data-settings-for-module', 'data-refresh', 'option-group', 'data-option-group'],
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

                var items = mwd.getElementsByName(el[0].name), i = 0, len = items.length;
                for (; i < len; i++) {
                    var _val = items[i].value;
                    val = items[i].checked == true ? (val === '' ? _val : val + "," + _val) : val;
                }
            }

        }
        else {
            val = el.val();
        }


        var o_data = {
            option_key: el.attr('name'),
            option_group: og,
            option_value: val
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


        $.ajax({
            type: "POST",
            url: mw.settings.site_url + "api/save_option",
            data: o_data,
            success: function (data) {
                if (typeof(refresh_modules11) == 'undefined') {
                    refresh_modules11 = og1;


                }
                if (refresh_modules12) {
                    refresh_modules11 = refresh_modules12;
                }
                if (typeof(liveEditSettings) != 'undefined' && liveEditSettings) {
                    if (og_parent) {
                        refresh_modules11 = og_parent;
                    }
                }

                if (mw.admin) {
                    if (top.mweditor && top.mweditor.contentWindow) {
                        setTimeout(function () {
                            top.mweditor.contentWindow.mw.reload_module("#" + refresh_modules11)
                        }, 777);
                    }
                }
                if (window.parent.mw) {

                    if (self !== top) {
                        setTimeout(function () {

                            var mod_element = window.parent.document.getElementById(refresh_modules11);
                            if (mod_element) {
                                // var module_parent_edit_field = window.parent.mw.tools.firstParentWithClass(mod_element, 'edit')
                                var module_parent_edit_field = window.parent.mw.tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                                if (module_parent_edit_field) {
                                    window.parent.mw.tools.addClass(module_parent_edit_field, 'changed');
                                    window.parent.mw.askusertostay = true;

                                }
                            }

                            mw.reload_module_parent("#" + refresh_modules11);
                        }, 777);
                    }

                    if (window.parent.mw.reload_module != undefined) {
                        if (!!mw.admin) {
                            setTimeout(function () {
                                window.parent.mw.reload_module("#" + refresh_modules11);
                            }, 777);
                        }
                        else {
                            if (window.parent.mweditor != undefined) {
                                window.parent.mweditor.contentWindow.mw.reload_module("#" + refresh_modules11, function () {
                                    setTimeout(function () {
                                        window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                    }, 777);
                                });
                            }
                            if (window.parent.mw != undefined) {
                                window.parent.mw.reload_module("#" + refresh_modules11, function () {
                                    setTimeout(function () {
                                        window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                    }, 777);
                                });
                            }
                        }

                    }
                }


                if (reaload_in_parent != undefined && reaload_in_parent !== null) {
                    //     window.parent.mw.reload_module("#"+refresh_modules11);

                    return false;
                }


                if (also_reload != undefined) {


                    if (window.mw != undefined && reaload_in_parent !== true) {
                        if (window.mw.reload_module !== undefined) {

                            window.mw.reload_module(also_reload, function (reloaded_el) {

                                mw.options.form(reloaded_el, callback);
                            });
                            window.mw.reload_module('#' + also_reload, function (reloaded_el) {

                                mw.options.form(reloaded_el, callback);
                            });
                        }
                    }

                }


                if (reaload_in_parent !== true && for_m_id != undefined && for_m_id != '') {
                    for_m_id = for_m_id.toString()
                    if (window.mw != undefined) {




                        //if (window.mw.reload_module !== undefined) {
//	
//									window.mw.reload_module('#'+for_m_id, function(reloaded_el){
//
//										mw.options.form(reloaded_el, callback);
//									});
//                                }
                    }
                } else if (reaload_in_parent !== true && refresh_modules11 != undefined && refresh_modules11 != '') {
                    refresh_modules11 = refresh_modules11.toString()

                  //  mw.log(refresh_modules11);


                    //if (window.mw != undefined) {
                    //
                    //
                    //    if (reaload_in_parent !== true) {
                    //
                    //        if (refresh_modules11 == refresh_modules12) {
                    //            mw.reload_module(refresh_modules11);
                    //        }
                    //
                    //
                    //        if (window.mw.reload_module !== undefined) {
                    //
                    //            mw.reload_module_parent(refresh_modules11);
                    //            mw.reload_module_parent("#" + refresh_modules11);
                    //
                    //        }
                    //
                    //    }
                    //
                    //}


                }

                typeof callback === 'function' ? callback.call(data) : '';

            }
        })
    }
};

mw.options._optionSaved = null;

mw.options.form = function ($selector, callback, beforepost) {
    var $root = mw.$($selector);
    var root = $root[0];
    if(!root) return;
    if(!root._optionsEvents){
        mw.$("input, select, textarea", root)
            .not('.mw-options-form-binded-custom')
            .each(function () {
                this._optionSaved = true;
                var item = $(this);
                if (item.hasClass('mw_option_field')) {
                    item.addClass('mw-options-form-binded');
                    item.on('change input paste', function (e) {
                        var token = this.name + this.value;
                        if(mw.options._optionSaved !== token){
                            mw.options._optionSaved = token;
                            mw.options.___slowDownEvent(this, function () {
                                if (typeof root._optionsEvents.beforepost === 'function') {
                                    root._optionsEvents.beforepost.call(this);
                                }
                                mw.options.save(this, root._optionsEvents.callback);
                            });
                        }
                    });
                }
            });
    }
    root._optionsEvents = root._optionsEvents || {};
    root._optionsEvents = $.extend({}, root._optionsEvents, {callback:callback, beforepost:beforepost});
};

mw.options.___slowDownEventTimeOut = null;
mw.options.___slowDownEvent = function (el, call) {
    clearTimeout(mw.options.___slowDownEventTimeOut);
    mw.options.___slowDownEventTimeOut = setTimeout(function () {
        call.call(el);
    }, 400);
};





