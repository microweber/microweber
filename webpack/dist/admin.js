/******/ (() => { // webpackBootstrap
(() => {
/*!************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/admin_custom_fields.js ***!
  \************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.admin = mw.admin || {};
mw.admin.custom_fields = mw.admin.custom_fields || {};

mw.admin.custom_fields.initValues = function () {
    var master = mwd.getElementById('custom-fields-post-table');
    if ( master === null ) {
        return false;
    }
    var all = master.querySelectorAll('.mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-placeholder-edit-inline, .mw-admin-custom-field-value-edit-inline'),
        l = all.length,
        i = 0;
    for (; i < l; i++) {
        mw.admin.custom_fields.initValue(all[i]);
    }
    mw.admin.custom_fields.addValueButtons();
    all = master.querySelectorAll('.mw-admin-custom-field-value-edit-text');
    l = all.length;
    i = 0;
    for ( ; i < l; i++ ) {
        mw.admin.custom_fields.initTextAreaValue(all[i]);
    }
};


mw.admin.custom_fields.initTextAreaValue = function (node) {
    if (!node.fieldBinded) {
        node.fieldBinded = true;
        mw.$(node).bind('keyup paste click', function (e) {
            var sh = this.scrollHeight;
            var oh = this.offsetHeight;
            if(sh > oh){
                this.style.height = sh+"px";
            }

        });
        node.onchange = function (e) {
            var data = {
                id: mw.$(this).dataset('id'),
                value: mw.$(this).val()
            };
            $.post(mw.settings.api_url + 'fields/save', data, function () {
                 mw.custom_fields.after_save();
            });
        }
    }
};

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
            span.className = 'mw-admin-custom-field-value-edit-inline-holder mw-admin-custom-field-checkbox bg-secondary-opacity-8 d-inline-flex mr-2 my-1 p-0';
            span.innerHTML = '<small class="mw-admin-custom-field-value-edit-inline p-1 text-dark" data-id="' + mw.$(this).dataset('id') + '"></small><small onclick="mw.admin.custom_fields.deleteFieldValue(this);" class="delete-custom-fields bg-danger text-white p-1"><i class="mdi mdi-close"></i></small>   ';
            mw.admin.custom_fields.initValue(span.querySelector('.mw-admin-custom-field-value-edit-inline'));
            mw.$(this).prev().append(span);
            mw.admin.custom_fields.valueLiveEdit(span.querySelector('.mw-admin-custom-field-value-edit-inline'));
        }
    }

}

mw.admin.custom_fields.valueLiveEdit = function (span) {
    mw.$(span.parentNode).addClass('active');
    mw.tools.addClass(mw.tools.firstParentWithTag(span, 'tr'), 'active');
    var input = mw.tools.elementEdit(span, true, function (el) {
        var data;
        if (mw.tools.hasClass(el, 'mw-admin-custom-field-value-edit-inline')) {
            var vals = [],
                all = mw.tools.firstParentWithClass(el, 'custom-fields-values-holder').parentNode.querySelectorAll('.mw-admin-custom-field-value-edit-inline'),
                l = all.length,
                i = 0;
            for (; i < l; i++) {
                vals.push(all[i].textContent);
            }

            data = {
                id: mw.$(el).dataset('id'),
                value: vals
            };
        }

        else if (mw.tools.hasClass(el, 'mw-admin-custom-field-placeholder-edit-inline')) {

            data = {
                id: mw.$(el).dataset('id'),
                placeholder: mw.$(el).text()
            };

        }

        else {
            data = {
                id: mw.$(el).dataset('id'),
                name: mw.$(el).text()
            };
        }
        mw.tools.removeClass(mw.tools.firstParentWithTag(this, 'tr'), 'active');
        $.post(mw.settings.api_url + 'fields/save', data, function (data) {

        	if (mwd.getElementById('mw-custom-fields-list-settings-' + data.id) != null) {

	            var rstr = mwd.getElementById('mw-custom-fields-list-settings-' + data.id).innerHTML.replace(/\s+/g, '');

	            if (rstr && !!data.value) {
	                mw.reload_module('#mw-custom-fields-list-settings-' + data.id);
	            }
        	}

        	mw.custom_fields.after_save();

        });
        mw.$(el.parentNode).removeClass('active');
        mw.tools.removeClass(mw.tools.firstParentWithTag(el, 'tr'), 'active');
    });
    mw.$(input).bind('blur', function () {
        mw.$('.mw-admin-custom-field-value-edit-inline-holder.active').removeClass('active');
        mw.tools.removeClass(mw.tools.firstParentWithTag(this, 'tr'), 'active');
    });
    mw.$(input).bind('keydown', function (e) {

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
        } else {

	/*
				var el = mw.$( e.target)[0];
				mw.on.stopWriting(el, function () {

		             var parent = mw.tools.firstParentWithClass(el, 'mw-admin-custom-field-value-edit-inline');
					 d(parent);
                    mw.admin.custom_fields.valueLiveEdit(parent);


			 });*/

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
                mw.$(this).find(".mw-admin-custom-field-name-edit-inline, .mw-admin-custom-field-placeholder-edit-inline").each(function () {
                    var id = mw.$(this).dataset("id");
                    obj.ids.push(id);
                });


                $.post(mw.settings.api_url + "fields/reorder", obj, function () {

                });
            }
        });
    }
    return sortable_holder;
};
mw.admin.custom_fields.del = function (id, toremove) {
    var q = "Are you sure you want to delete '" + mw.$('#mw-custom-list-element-' + id + ' .mw-admin-custom-field-name-edit-inline').text() + "' ?";
    mw.tools.confirm(q, function () {

        mw.custom_fields.remove(id, function (data) {
            mw.$('#mw-custom-list-element-' + id).addClass('scale-out');
            setTimeout(function () {
                mw.reload_module_parent('custom_fields');
                mw.reload_module('custom_fields/list', function () {
                    if (!!toremove) {
                        mw.$(toremove).remove();
                    }
                    mw.$("#custom-field-editor").removeClass('mw-custom-field-created').hide();
                    mw.trigger('customFieldSaved', id);
                    if (typeof load_iframe_editor === 'function') {
                        load_iframe_editor();
                    }
                    mw.admin.custom_fields.initValues();
                });
            }, 300);

        });
    });
};
mw.admin.custom_fields.deleteFieldValue = function (el) {
    var xel  = el.parentNode.parentNode
    mw.$(el.parentNode).remove();
    mw.admin.custom_fields.valueLiveEdit(xel.querySelector('.mw-admin-custom-field-value-edit-inline'));



};



mw.admin.custom_fields.edit_custom_field_item = function ($selector, id, callback, event) {

    var mTitle = (id ? 'Edit custom field' : 'Add new custom field');

    var data = {};
    data.settings = 'y';
    data.field_id = id;
    data.live_edit = true;
    data.module_settings = true;
    data.id = id;


    data.params = {};
    data.params.field_id = id;

    editModal = mw.top().tools.open_module_modal('custom_fields/values_edit', data, {
        overlay: false,
        width:'450px',
        height:'auto',
        autoHeight: true,
        iframe: true,
        title: mTitle
    });

};

$(mww).bind('load', function () {
    mw.admin.custom_fields.initValues();
});
$(mwd).ready(function () {
    mw.admin.custom_fields.initValues();
});

})();

(() => {
/*!**************************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/admin_package_manager.js ***!
  \**************************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.admin = mw.admin || {};
mw.admin.admin_package_manager = mw.admin.admin_package_manager || {}


mw.admin.admin_package_manager.set_loading = function (is_loading) {

    mw.tools.loading(mwd.querySelector('.js-install-package-loading-container-confirm'), is_loading, 'slow');
    mw.tools.loading(mwd.querySelector('#mw-packages-browser-nav-tabs-nav'), is_loading, 'slow');
    mw.tools.loading(mwd.querySelector('.admin-toolbar'), is_loading, 'slow');
    mw.tools.loading(mwd.querySelector('#update_queue_set_modal'), is_loading, 'slow');

}


mw.admin.admin_package_manager.reload_packages_list = function () {
    mw.admin.admin_package_manager.set_loading(true)
    setTimeout(function () {
        mw.notification.success('Reloading packages', 15000);
    }, 1000);
    mw.clear_cache();
    mw.admin.admin_package_manager.set_loading(true)

    setTimeout(function () {
        mw.reload_module('admin/developer_tools/package_manager/browse_packages', function () {
            mw.admin.admin_package_manager.set_loading(false)
            mw.notification.success('Packages are reloaded');
        })

    }, 1000);


}

mw.admin.admin_package_manager.show_licenses_modal = function () {
    var data = {}
    licensesModal = mw.tools.open_module_modal('settings/group/licenses', data, {
        //  overlay: true,
        //  iframe: true,

        title: 'Licenses',
        skin: 'simple'
    })


}


mw.admin.admin_package_manager.install_composer_package_by_package_name = function ($key, $version) {

    mw.notification.success('Loading...', 25000);
    //mw.load_module('updates/worker', '#mw-updates-queue');


    var update_queue_set_modal = mw.dialog({
        content: '<div class="module" type="updates/worker" id="update_queue_process_alert"></div>',
        overlay: false,
        id: 'update_queue_set_modal',
        title: 'Preparing'
    });

    mw.reload_module('#update_queue_process_alert');


    mw.admin.admin_package_manager.set_loading(50)


    var values = {require_name: $key, require_version: $version};

    mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(values);


}


mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals = null;


mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax = function (values) {
    $.ajax({
        url: mw.settings.api_url + "mw_composer_install_package_by_name",
        type: "post",
        data: values,
        success: function (msg) {
            mw.admin.admin_package_manager.set_loading(true);

            if (typeof msg == 'object' && msg.try_again  && msg.unzip_cache_key) {
                if (msg.try_again) {
                    values.try_again_step = true;
                    values.unzip_cache_key =  msg.unzip_cache_key;
                    mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals = values;
                    setTimeout(function(){
                        mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(values);

                    }, 500);



                    return;
                }
            } else {
                mw.notification.msg(msg);
                mw.admin.admin_package_manager.set_loading(false)



                mw.admin.admin_package_manager.reload_packages_list();
                mw.admin.admin_package_manager.set_loading(false);

                mw.$('#update_queue_set_modal').remove();
            }



        },

        error: function (jqXHR, textStatus, errorThrown) {
            mw.admin.admin_package_manager.set_loading(false);

            setTimeout(function(){
                mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax(mw.admin.admin_package_manager.install_composer_package_by_package_name_do_ajax_last_step_vals);

            }, 500);
        }

    }).always(function (jqXHR, textStatus) {


        if(typeof(context) != 'undefined' ) {
            mw.spinner({ element: $(context).next() }).hide();
            $(context).show();
        }

        mw.$('#update_queue_set_modal').remove();

        mw.admin.admin_package_manager.set_loading(false);


    })

}


})();

(() => {
/*!**********************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/admin.js ***!
  \**********************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.require('tree.js');
mw.require('link-editor.js');
mw.require('tags.js');


mw.admin = {
    language: function(language) {
        if (!language) {
            return mw.cookie.get("lang");
        }
        mw.cookie.set("lang", language);
        location.reload();
    },
    editor: {
        set: function (frame) {
            mw.$(frame).width('100%');
          /*
            if (!!frame && frame !== null && !!frame.contentWindow) {
                var width_mbar = mw.$('#main-bar').width(),
                    tree = mwd.querySelector('.tree-column'),
                    width_tbar = mw.$(tree).width(),
                    ww = mw.$(window).width();
                if (tree.style.display === 'none') {
                    width_tbar = 0;
                }
                if (width_mbar > 200) {
                    width_mbar = 0;
                }
                mw.$(frame)
                    .width(ww - width_tbar - width_mbar - 35)
                    .height(frame.contentWindow.document.body.offsetHeight);
            }*/
        },
        init: function (area, params) {
            params = params || {};
            if (typeof params === 'object') {
                if (typeof params.src != 'undefined') {
                    delete(params.src);
                }
            }
            params.live_edit=false;
            params = typeof params === 'object' ? json2url(params) : params;
            area = mw.$(area);
            var frame = mwd.createElement('iframe');
            frame.src = mw.external_tool('wysiwyg?' + params);
            console.log(mw.external_tool('wysiwyg?' + params))
            frame.className = 'mw-iframe-editor';
            frame.scrolling = 'no';
            var name = 'mweditor' + mw.random();
            frame.id = name;
            frame.name = name;
            frame.style.backgroundColor = "transparent";
            frame.setAttribute('frameborder', 0);
            frame.setAttribute('allowtransparency', 'true');
            area.empty().append(frame);
            mw.$(frame).load(function () {
                frame.contentWindow.thisframe = frame;
                if (typeof frame.contentWindow.PrepareEditor === 'function') {
                    frame.contentWindow.PrepareEditor();
                }
                mw.admin.editor.set(frame);
                mw.$(frame.contentWindow.document.body).bind('keyup paste', function () {
                    mw.admin.editor.set(frame);
                });
            });
            mw.admin.editor.set(frame);
            mw.$(window).bind('resize', function () {
                mw.admin.editor.set(frame);
            });
            return frame;
        }
    },
    manageToolbarQuickNav: null,
    insertModule: function (module) {
        mwd.querySelector('.mw-iframe-editor').contentWindow.InsertModule(module);
    },


        simpleRotator: function (rotator) {
        if (rotator === null) {
            return undefined;
        }
        if (typeof rotator !== 'undefined') {
            if (!$(rotator).hasClass('activated')) {
                mw.$(rotator).addClass('activated')
                var all = rotator.children;
                var l = all.length;
                mw.$(all).addClass('mw-simple-rotator-item');

                rotator.go = function (where, callback, method) {
                    method = method || 'animate';
                    mw.$(rotator).dataset('state', where);
                    mw.$(rotator.children).hide().eq(where).show()
                        if (typeof callback === 'function') {
                            callback.call(rotator);
                        }

                    if (rotator.ongoes.length > 0) {
                        var l = rotator.ongoes.length;
                        i = 0;
                        for (; i < l; i++) {
                            rotator.ongoes[i].call(rotator);
                        }
                    }
                };
                rotator.ongoes = [];
                rotator.ongo = function (c) {
                    if (typeof c === 'function') {
                        rotator.ongoes.push(c);
                    }
                };
            }
        }
        return rotator;
    },

    postImageUploader: function () {
        if (mwd.querySelector('#images-manager') === null) {
            return false;
        }
        if (mwd.querySelector('.mw-iframe-editor') === null) {
            return false;
        }
        if (mwd.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.edit') === null) {
            return false;
        }
        var uploader = mw.uploader({
            filetypes: "images",
            multiple: true,
            element: "#insert-image-uploader"
        });
        mw.$(uploader).bind("FileUploaded", function (obj, data) {
            var frameWindow = mwd.querySelector('.mw-iframe-editor').contentWindow;
            var hasRanges = frameWindow.getSelection().rangeCount > 0;
            var img = '<img class="element" src="' + data.src + '" />';
            if (hasRanges && frameWindow.mw.wysiwyg.isSelectionEditable()) {
                frameWindow.mw.wysiwyg.insert_html(img);
            }
            else {
                frameWindow.mw.$(frameWindow.mwd.querySelector('.edit')).append(img);
            }
        });

    },
    listPostGalleries: function () {
        if (mwd.querySelector('#images-manager') === null) {
            return false;
        }
        if (mwd.querySelector('.mw-iframe-editor') === null) {
            return false;
        }
        if (mwd.querySelector('.mw-iframe-editor').contentWindow.mwd.querySelector('.edit') === null) {
            return false;
        }
    },


    beforeLeaveLocker: function () {
        var roots = '#pages_tree_toolbar, #main-bar',
            all = mwd.querySelectorAll(roots),
            l = all.length,
            i = 0;
        for (; i < l; i++) {
            if (!!all[i].MWbeforeLeaveLocker) continue;
            all[i].MWbeforeLeaveLocker = true;
            var links = all[i].querySelectorAll('a'), ll = links.length, li = 0;
            for (; li < ll; li++) {
                mw.$(links[li]).bind('mouseup', function (e) {
                    if (mw.askusertostay === true) {
                        e.preventDefault();
                        return false;

                    }
                });
            }
        }
    }
};


mw.contactForm = function () {
    mw.dialogIframe({
        url: 'https://microweber.com/contact-frame/',
        overlay: true,
        height: 600
    })
};


$(mwd).ready(function () {

    mw.$(mwd.body).on('keydown', function (e) {
        if (mw.event.key(e, 8) && (e.target.nodeName === 'DIV' || e.target === mwd.body)) {
            if (!e.target.isContentEditable) {
                mw.event.cancel(e);
                return false;
            }
        }
    });

    mw.admin.beforeLeaveLocker();

    mw.$(document.body).on('click', '[data-href]', function(e){
        e.preventDefault();
        e.stopPropagation();
        location.href = $(this).attr('data-href');
    });
});

$(mww).on('load', function () {
    mw.on.moduleReload('pages_tree_toolbar', function () {

    });



    if (mwd.getElementById('main-bar-user-menu-link') !== null) {

        mw.$(document.body).on('click', function (e) {
            if (e.target !== mwd.getElementById('main-bar-user-menu-link') && e.target.parentNode !== mwd.getElementById('main-bar-user-menu-link')) {
                mw.$('#main-bar-user-tip').removeClass('main-bar-user-tip-active');
            }
            else {

                mw.$('#main-bar-user-tip').toggleClass('main-bar-user-tip-active');
            }
        });
    }

    mw.on('adminSaveStart', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.saving + '...';
    });
    mw.$(window).on('adminSaveEnd', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.save;
    });

    mw.$(".dr-item-table > table").click(function(){
        mw.$(this).toggleClass('active').next().stop().slideToggle().parents('.dr-item').toggleClass('active')
    });

});


QTABSArrow = function (el) {
    el = mw.$(el);
    if (el == null) {
        return;
    }
    if (!el.length) {
        return;
    }
    var left = el.offset().left - mw.$(mwd.getElementById('quick-add-post-options')).offset().left + (el[0].offsetWidth / 2) - 5;
    mw.$('#quick-add-post-options-items-holder .mw-tooltip-arrow').css({left: left});
};


})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/content.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.content = mw.content || {

    deleteContent: function (id, callback) {
        mw.tools.confirm(mw.msg.del, function () {
            $.post(mw.settings.api_url + "content/delete", {id: id}, function (data) {
                if (callback) {
                    callback.call(data, data);
                }
                mw.notification.success('Content deleted');
            });
        });
    },
    deleteCategory: function (id, callback) {
        mw.tools.confirm('Are you sure you want to delete this?', function () {
            $.post(mw.settings.api_url + "category/delete", {id: id}, function (data) {
                mw.notification.success('Category deleted');
                if (callback) {
                    callback.call(data, data);
                }
                mw.reload_module_everywhere('content/manager');
            });
        });
    },
    publish: function ($id) {
        var master = {};
        master.id = $id;
        mw.$(mwd.body).addClass("loading");
        mw.drag.save();
        $.ajax({
            type: 'POST',
            url: mw.settings.site_url + 'api/content/set_published',
            data: master,
            datatype: "json",
            async: true,
            beforeSend: function () {

            },
            success: function (data) {
                mw.$(mwd.body).removeClass("loading");
                $('.mw-set-content-publish').hide();
                mw.$('.mw-set-content-unpublish').fadeIn();
                mw.askusertostay = false;
                mw.notification.success("Content is Published.");
            },
            error: function () {
                mw.$(mwd.body).removeClass("loading");
            },
            complete: function () {
                mw.$(mwd.body).removeClass("loading");
            }
        });
    },
    unpublish: function ($id) {
        var master = {};
        master.id = $id;
        mw.$(mwd.body).addClass("loading");

        mw.drag.save();
        $.ajax({
            type: 'POST',
            url: mw.settings.site_url + 'api/content/set_unpublished',
            data: master,
            datatype: "json",
            async: true,
            beforeSend: function () {

            },
            success: function (data) {
                mw.$(mwd.body).removeClass("loading");
                mw.$('.mw-set-content-unpublish').hide();
                mw.$('.mw-set-content-publish').fadeIn();
                mw.askusertostay = false;
                mw.notification.warning("Content is Unpublished.");
            },
            error: function () {
                mw.$(mwd.body).removeClass("loading");
            },
            complete: function () {
                mw.$(mwd.body).removeClass("loading");
            }
        });

    },
    save: function (data, e) {
        var master = {};
        var calc = {};
        var e = e || {};
        //   data.subtype === 'category'
        if (data.content == "" || typeof data.content === 'undefined') {
            // calc.content = false;
        }
        else {
            var doc = mw.tools.parseHtml(data.content);
            var all = doc.querySelectorAll('[contenteditable]'), l = all.length, i = 0;
            for (; i < l; i++) {
                all[i].removeAttribute('contenteditable');
            }
            data.content = doc.body.innerHTML;
        }

        if (!data.title) {
            calc.title = false;
        }
        if (!mw.tools.isEmptyObject(calc)) {
            if (typeof e.onError === 'function') {
                e.onError.call(calc);
            }
            return false;
        }
        if (!data.content_type) {
            data.content_type = "post";
        }
        if (!data.id) {
            data.id = 0;
        }
        master.title = data.title;
        master.content = data.content;
        mw.$(mwd.body).addClass("loading");
        mw.trigger('adminSaveStart');
        $.ajax({
            type: 'POST',
            url: e.url || (mw.settings.api_url + 'save_content_admin'),
            data: data,
            datatype: "json",
            async: true,
            success: function (data) {
                if(data.data) {
                    data = data.data;
                }
                mw.$(mwd.body).removeClass("loading");
                if (typeof data === 'object' && typeof data.error != 'undefined') {
                    if (typeof e.onError === 'function') {
                        e.onError.call(data);
                    }
                }
                else {
                    if (typeof e.onSuccess === 'function') {
                        e.onSuccess.call(data);
                        mw.trigger('adminSaveEnd');
                    }
                }
            },
            error: function (data) {
                mw.$(mwd.body).removeClass("loading");
                if (typeof e.onError === 'function') {
                    e.onError.call(data.data || data);
                }
            },
            complete: function () {
                mw.$(mwd.body).removeClass("loading");
            }
        });
    }
};


mw.post = mw.post || {
    del: function (a, callback) {
        var arr = $.isArray(a) ? a : [a];
        var obj = {ids: arr}
        $.post(mw.settings.api_url + "content/delete", obj, function (data) {
            typeof callback === 'function' ? callback.call(data) : '';
        });
    },
    publish: function (id, c) {
        var obj = {
            id: id
        }
        $.post(mw.settings.api_url + 'content/set_published', obj, function (data) {
            if (typeof c === 'function') {
                c.call(id, data);
            }
        });
    },
    unpublish: function (id, c) {
        var obj = {
            id: id
        }
        $.post(mw.settings.api_url + 'content/set_unpublished', obj, function (data) {
            if (typeof c === 'function') {
                c.call(id, data);
            }
        });
    },
    set: function (id, state, e) {
        if (typeof e !== 'undefined') {
            e.preventDefault();
            e.stopPropagation();
        }
        if (state == 'unpublish') {
            mw.post.unpublish(id, function (data) {
                mw.notification.warning(mw.msg.contentunpublished);
            });
        }
        else if (state == 'publish') {
            mw.post.publish(id, function (data) {
                mw.notification.success(mw.msg.contentpublished);
                mw.$(".manage-post-item-" + id).removeClass("content-unpublished").find(".post-un-publish").remove();
                if (typeof e !== 'undefined') {
                    mw.$(e.target.parentNode).removeClass("content-unpublished");
                    mw.$(e.target).remove();
                }
            });
        }
    }
}

})();

(() => {
/*!******************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/custom_fields.js ***!
  \******************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
        group = mwd.getElementById(group);
        if (group == null) {
            return;
        }
        if (group.querySelectorAll('.mw-custom-field-form-controls').length > 0) {
            mw.$(group).sortable({
                handle: '.custom-fields-handle-field',
                placeholder: 'custom-fields-placeholder',
                //containment: "parent",
                axis: 'y',
                items: ".mw-custom-field-form-controls",
                start: function (a, ui) {
                    mw.$(ui.placeholder).height($(ui.item).outerHeight())
                },
                //scroll:false,
                update: function () {
                    var par = mw.tools.firstParentWithClass(group, 'mw-admin-custom-field-edit-item-wrapper');
                    if (!!par) {
                        mw.custom_fields.save(par);
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
        //  mw.reload_module('custom_fields/list');

        mw.reload_module('custom_fields');
        mw.reload_module_parent('custom_fields/list');
        mw.reload_module_parent('custom_fields');


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
        mw.$(fields, el).not(':disabled').each(function () {
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

})();

(() => {
/*!****************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/elementedit.js ***!
  \****************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
mw.tools.elementEdit = function (el, textonly, callback, fieldClass) {
    if (!el || el.querySelector('.mw-live-edit-input') !== null) {
        return;
    }
    textonly = (typeof textonly === 'undefined') ? true : textonly;
    var input = mwd.createElement('span');
    input.className = (fieldClass || "") + ' mw-live-edit-input mw-liveedit-field';
    input.contentEditable = true;
    var $input = $(input);
    if (textonly === true) {
        input.innerHTML = el.textContent;
        input.onblur = function () {
            var val = $input.text();
            var ischanged = true;
            setTimeout(function () {
                mw.$(el).text(val);
                if (typeof callback === 'function' && ischanged) {
                    callback.call(val, el);
                }
            }, 3);
        };
        input.onkeydown = function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                mw.$(el).text($input.text());
                if (typeof callback === 'function') {
                    callback.call($input.text(), el);
                }
                return false;
            }
        }
    }
    else {
        input.innerHTML = el.innerHTML;
        input.onblur = function () {
            var val = this.innerHTML;
            var ischanged = this.changed === true;
            setTimeout(function () {
                el.innerHTML = val;
                if (typeof callback === 'function' && ischanged) {
                    callback.call(val, el);
                }
            }, 3)
        }
        input.onkeydown = function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                var val = this.innerHTML;
                el.innerHTML = val;
                if (typeof callback === 'function') {
                    callback.call(val, el);
                }
                return false;
            }
        }
    }
    mw.$(el).empty().append(input);
    $input.focus();
    input.changed = false;
    $input.change(function () {
        this.changed = true;
    });
    return input;
}
})();

(() => {
/*!************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/options.js ***!
  \************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
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
        var data = {
            option_group: group,
            option_key: key,
            option_value: value
        };
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
        if (typeof(og) == 'undefined' && typeof(og) == 'undefined' && og_parent) {
            og = og_parent;
        }


        //  alert(og + '       ' +og1);


        var o_data = {
            option_key: el.attr('name'),
            option_group: og,
            option_value: val
        }


        if (mname === undefined) {
            if (og_test !== undefined && og_test && $(og_test).attr('parent-module')) {
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


                //  alert('refresh_modules11     '+refresh_modules11);
                //  alert('which_module_to_reload     '+which_module_to_reload);
                // alert('og1      '+og1);


                if (mw.admin) {
                    if (top.mweditor && top.mweditor.contentWindow) {
                        setTimeout(function () {
                            top.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload);

                        }, 777);
                    }
                }
                if (window.parent.mw) {

                    if (self !== top) {

                        setTimeout(function () {

                            var mod_element = window.parent.document.getElementById(which_module_to_reload);
                            if (mod_element) {
                                // var module_parent_edit_field = window.parent.mw.tools.firstParentWithClass(mod_element, 'edit')
                               // var module_parent_edit_field = window.parent.mw.tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]'])
                                var module_parent_edit_field = window.parent.mw.tools.firstMatchesOnNodeOrParent(mod_element, ['.edit:not([itemprop=dateModified])']);
                                if (!module_parent_edit_field) {
                                   module_parent_edit_field = window.parent.mw.tools.firstMatchesOnNodeOrParent(mod_element, ['.edit[rel=inherit]']);
                                }

                                if (module_parent_edit_field) {
                                   // window.parent.mw.tools.addClass(module_parent_edit_field, 'changed');
                                    window.parent.mw.wysiwyg ? window.parent.mw.wysiwyg.change(module_parent_edit_field) : '';
                                    window.parent.mw.askusertostay = true;

                                }
                            }

                            mw.reload_module_parent("#" + which_module_to_reload);
                            if (which_module_to_reload != og1) {
                                mw.reload_module_parent("#" + og1);
                            }
                            reload_in_parent_trieggered = 1;


                        }, 777);
                    }

                    if (window.parent.mw.reload_module != undefined) {

                        if (!!mw.admin) {
                            setTimeout(function () {
                                window.parent.mw.reload_module("#" + which_module_to_reload);
                                mw.options.___rebindAllFormsAfterReload();
                            }, 777);
                        }
                        else {
                            if (window.parent.mweditor != undefined) {
                                window.parent.mweditor.contentWindow.mw.reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                            if (window.parent.mw != undefined) {
                                window.parent.mw.reload_module("#" + which_module_to_reload, function () {
                                    setTimeout(function () {
                                        window.parent.mw.exec("mw.admin.editor.set", window.parent.mweditor);
                                        mw.options.___rebindAllFormsAfterReload();
                                    }, 777);
                                });
                            }
                        }
                        reload_in_parent_trieggered = 1;

                    }
                }


                // if (reaload_in_parent != undefined && reaload_in_parent !== null) {
                //     //     window.parent.mw.reload_module("#"+refresh_modules11);
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
                                if (top !== self && window.parent.mw.drag && window.parent.mw.drag.save) {
                                    window.parent.mw.drag.save();
                                }
                                mw.options.save(this, root._optionsEvents.callback);
                            });
                            //}
                        });
                    }
                }
            });
    }


    //  alert($selector +'   --   ' +numOfbindigs);


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

})();

(() => {
/*!*************************************************************!*\
  !*** ../userfiles/modules/microweber/api/admin/upgrades.js ***!
  \*************************************************************/
/*! unknown exports (runtime-defined) */
/*! runtime requirements:  */
window.onmessage = function (e) {

//    if ( e.origin !== "http://html5demos.com" ) {
//        return;
//    }


    if (typeof e.data != 'undefined') {


        if (typeof e.data.market_id != 'undefined' || typeof e.data.mw_version != 'undefined') {
            mw.notification.success("Installing item", 9000);

            if (typeof e.data.market_id != 'undefined') {
                var url = mw.settings.api_url + "mw_install_market_item";
            } else if (typeof e.data.mw_version != 'undefined') {
                var url = mw.settings.api_url + "mw_set_updates_queue";

            }

            $.post(url, e.data)
                .done(function (data) {
                    mw.notification.msg(data, 5000);

                    if (typeof(data.update_queue_set != 'undefined')) {


                        var update_queue_set_modal = mw.dialog({
                            content: '<div class="module" type="updates/worker" id="update_queue_process_alert"></div>',
                            overlay: false,
                            id: 'update_queue_set_modal',
                            title: 'Installing'
                        });


                        mw.reload_module('#update_queue_process_alert');
                        mw.reload_module('updates/list');
                    }

                });
        }
    }
    // document.getElementById("test").innerHTML = e.origin + " said: " + e.data;
};



})();

/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvYWRtaW4vYWRtaW5fY3VzdG9tX2ZpZWxkcy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvYWRtaW4vYWRtaW5fcGFja2FnZV9tYW5hZ2VyLmpzIiwid2VicGFjazovL21pY3Jvd2ViZXItd2VicGFjay8uLi91c2VyZmlsZXMvbW9kdWxlcy9taWNyb3dlYmVyL2FwaS9hZG1pbi9hZG1pbi5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvYWRtaW4vY29udGVudC5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvYWRtaW4vY3VzdG9tX2ZpZWxkcy5qcyIsIndlYnBhY2s6Ly9taWNyb3dlYmVyLXdlYnBhY2svLi4vdXNlcmZpbGVzL21vZHVsZXMvbWljcm93ZWJlci9hcGkvYWRtaW4vZWxlbWVudGVkaXQuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2FkbWluL29wdGlvbnMuanMiLCJ3ZWJwYWNrOi8vbWljcm93ZWJlci13ZWJwYWNrLy4uL3VzZXJmaWxlcy9tb2R1bGVzL21pY3Jvd2ViZXIvYXBpL2FkbWluL3VwZ3JhZGVzLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7Ozs7QUFBQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxVQUFVLE9BQU87QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsV0FBVyxPQUFPO0FBQ2xCO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxVQUFVLE9BQU87QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvTkFBb047QUFDcE47QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGtCQUFrQixPQUFPO0FBQ3pCO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOztBQUVBLFNBQVM7QUFDVDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQSxLQUFLLEVBQUU7O0FBRVA7QUFDQSxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDJCQUEyQjtBQUMzQjtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7OztBQUdqQjs7QUFFQSxpQkFBaUI7QUFDakI7QUFDQSxTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakIsYUFBYTs7QUFFYixTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Ozs7QUFJQTs7OztBQUlBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7O0FBRUE7QUFDQTtBQUNBLENBQUM7QUFDRDtBQUNBO0FBQ0EsQ0FBQzs7Ozs7Ozs7OztBQ25RRDtBQUNBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQsS0FBSzs7O0FBR0w7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsS0FBSzs7O0FBR0w7OztBQUdBOztBQUVBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMOzs7QUFHQTs7O0FBR0Esa0JBQWtCOztBQUVsQjs7O0FBR0E7OztBQUdBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxxQkFBcUI7Ozs7QUFJckI7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBOzs7O0FBSUE7QUFDQTs7QUFFQTtBQUNBOzs7O0FBSUEsU0FBUzs7QUFFVDtBQUNBOztBQUVBO0FBQ0E7O0FBRUEsYUFBYTtBQUNiOztBQUVBLEtBQUs7OztBQUdMO0FBQ0Esd0JBQXdCLDZCQUE2QjtBQUNyRDtBQUNBOztBQUVBOztBQUVBOzs7QUFHQSxLQUFLOztBQUVMOzs7Ozs7Ozs7OztBQzNJQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLOzs7QUFHTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSw4QkFBOEIsT0FBTztBQUNyQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7O0FBRVQsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSzs7O0FBR0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGNBQWMsT0FBTztBQUNyQjtBQUNBO0FBQ0E7QUFDQSxrQkFBa0IsU0FBUztBQUMzQjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7O0FBR0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsS0FBSztBQUNMLENBQUM7O0FBRUQ7QUFDQTs7QUFFQSxLQUFLOzs7O0FBSUw7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsU0FBUztBQUNUOztBQUVBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQSxLQUFLOztBQUVMO0FBQ0E7QUFDQSxLQUFLOztBQUVMLENBQUM7OztBQUdEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLHdFQUF3RSxXQUFXO0FBQ25GOzs7Ozs7Ozs7OztBQy9QQTs7QUFFQTtBQUNBO0FBQ0EsNERBQTRELE9BQU87QUFDbkU7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2IsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0EsNkRBQTZELE9BQU87QUFDcEU7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYixTQUFTO0FBQ1QsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0EsU0FBUztBQUNULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQSxTQUFTOztBQUVULEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0Esa0JBQWtCLE9BQU87QUFDekI7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQSxtQkFBbUI7QUFDbkI7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBOzs7Ozs7Ozs7O0FDN01BO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0EseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQSx5QkFBeUI7O0FBRXpCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0EsU0FBUztBQUNUO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7QUFDQSxTQUFTO0FBQ1QsS0FBSzs7QUFFTDtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTs7QUFFYjtBQUNBLFNBQVM7QUFDVCxLQUFLOztBQUVMO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFHQTs7QUFFQSxLQUFLOztBQUVMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsYUFBYTtBQUNiLFNBQVMsRUFBRTtBQUNYLEtBQUs7O0FBRUw7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTs7Ozs7Ozs7OztBQzVOQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLGFBQWE7QUFDYjtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxLQUFLO0FBQ0w7QUFDQSxDOzs7Ozs7Ozs7QUMvREE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxhQUFhO0FBQ2I7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVCxLQUFLO0FBQ0w7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUdBOztBQUVBOzs7QUFHQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTtBQUNBOzs7QUFHQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTs7O0FBR0E7O0FBRUE7QUFDQSxxQkFBcUIsT0FBTztBQUM1QjtBQUNBO0FBQ0EsWUFBWTtBQUNaO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxvQkFBb0I7QUFDcEI7QUFDQTtBQUNBO0FBQ0E7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBLHNCQUFzQixTQUFTO0FBQy9CO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBOzs7OztBQUtBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7Ozs7QUFLQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7QUFDQTtBQUNBLFNBQVM7QUFDVDs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBOzs7QUFHQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFFQTs7O0FBR0E7QUFDQTtBQUNBLGlCQUFpQjtBQUNqQjtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7OztBQUdBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUEseUJBQXlCO0FBQ3pCO0FBQ0E7QUFDQTs7QUFFQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0EseUJBQXlCO0FBQ3pCOztBQUVBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsNkJBQTZCO0FBQzdCO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EscUNBQXFDO0FBQ3JDLGlDQUFpQztBQUNqQztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSxxQ0FBcUM7QUFDckMsaUNBQWlDO0FBQ2pDO0FBQ0E7QUFDQTs7QUFFQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTs7O0FBR0E7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0EsNkJBQTZCO0FBQzdCOztBQUVBO0FBQ0E7QUFDQSw2QkFBNkI7QUFDN0I7QUFDQTs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7Ozs7O0FBS0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDBDQUEwQztBQUMxQztBQUNBO0FBQ0EsNEJBQTRCOzs7QUFHNUI7QUFDQTs7O0FBR0E7O0FBRUE7QUFDQTs7O0FBR0E7OztBQUdBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQSxpQkFBaUI7QUFDakI7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLFNBQVM7QUFDVDtBQUNBOztBQUVBOztBQUVBOztBQUVBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBLFNBQVM7O0FBRVQ7QUFDQTs7OztBQUlBOzs7QUFHQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7O0FBRUE7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7OztBQUdBOztBQUVBO0FBQ0E7OztBQUdBOzs7QUFHQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLDZCQUE2QjtBQUM3QjtBQUNBLHlCQUF5QjtBQUN6QjtBQUNBO0FBQ0EsYUFBYTtBQUNiOzs7QUFHQTs7O0FBR0E7QUFDQTtBQUNBO0FBQ0EseUNBQXlDLHdCQUF3QiwyQ0FBMkM7OztBQUc1RztBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQTtBQUNBO0FBQ0EsU0FBUzs7QUFFVDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFHQSxPQUFPOzs7QUFHUDs7O0FBR0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBLEtBQUs7QUFDTDs7QUFFQTs7QUFFQTs7O0FBR0E7OztBQUdBLHVFQUF1RSxPQUFPO0FBQzlFO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EseUJBQXlCOztBQUV6QjtBQUNBO0FBQ0E7QUFDQTs7QUFFQTtBQUNBO0FBQ0EsNEJBQTRCOztBQUU1QjtBQUNBOzs7QUFHQTtBQUNBO0FBQ0EsS0FBSztBQUNMO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0EsZUFBZSxzQkFBc0IsRUFBRTtBQUN2QztBQUNBO0FBQ0E7Ozs7Ozs7Ozs7QUMxbEJBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7OztBQUdBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBLGFBQWE7QUFDYjs7QUFFQTs7QUFFQTtBQUNBO0FBQ0E7O0FBRUE7OztBQUdBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQSx5QkFBeUI7OztBQUd6QjtBQUNBO0FBQ0E7O0FBRUEsaUJBQWlCO0FBQ2pCO0FBQ0E7QUFDQTtBQUNBIiwiZmlsZSI6ImFkbWluLmpzIiwic291cmNlc0NvbnRlbnQiOlsibXcuYWRtaW4gPSBtdy5hZG1pbiB8fCB7fTtcclxubXcuYWRtaW4uY3VzdG9tX2ZpZWxkcyA9IG13LmFkbWluLmN1c3RvbV9maWVsZHMgfHwge307XHJcblxyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmluaXRWYWx1ZXMgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICB2YXIgbWFzdGVyID0gbXdkLmdldEVsZW1lbnRCeUlkKCdjdXN0b20tZmllbGRzLXBvc3QtdGFibGUnKTtcclxuICAgIGlmICggbWFzdGVyID09PSBudWxsICkge1xyXG4gICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgIH1cclxuICAgIHZhciBhbGwgPSBtYXN0ZXIucXVlcnlTZWxlY3RvckFsbCgnLm13LWFkbWluLWN1c3RvbS1maWVsZC1uYW1lLWVkaXQtaW5saW5lLCAubXctYWRtaW4tY3VzdG9tLWZpZWxkLXBsYWNlaG9sZGVyLWVkaXQtaW5saW5lLCAubXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJyksXHJcbiAgICAgICAgbCA9IGFsbC5sZW5ndGgsXHJcbiAgICAgICAgaSA9IDA7XHJcbiAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgIG13LmFkbWluLmN1c3RvbV9maWVsZHMuaW5pdFZhbHVlKGFsbFtpXSk7XHJcbiAgICB9XHJcbiAgICBtdy5hZG1pbi5jdXN0b21fZmllbGRzLmFkZFZhbHVlQnV0dG9ucygpO1xyXG4gICAgYWxsID0gbWFzdGVyLnF1ZXJ5U2VsZWN0b3JBbGwoJy5tdy1hZG1pbi1jdXN0b20tZmllbGQtdmFsdWUtZWRpdC10ZXh0Jyk7XHJcbiAgICBsID0gYWxsLmxlbmd0aDtcclxuICAgIGkgPSAwO1xyXG4gICAgZm9yICggOyBpIDwgbDsgaSsrICkge1xyXG4gICAgICAgIG13LmFkbWluLmN1c3RvbV9maWVsZHMuaW5pdFRleHRBcmVhVmFsdWUoYWxsW2ldKTtcclxuICAgIH1cclxufTtcclxuXHJcblxyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmluaXRUZXh0QXJlYVZhbHVlID0gZnVuY3Rpb24gKG5vZGUpIHtcclxuICAgIGlmICghbm9kZS5maWVsZEJpbmRlZCkge1xyXG4gICAgICAgIG5vZGUuZmllbGRCaW5kZWQgPSB0cnVlO1xyXG4gICAgICAgIG13LiQobm9kZSkuYmluZCgna2V5dXAgcGFzdGUgY2xpY2snLCBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICB2YXIgc2ggPSB0aGlzLnNjcm9sbEhlaWdodDtcclxuICAgICAgICAgICAgdmFyIG9oID0gdGhpcy5vZmZzZXRIZWlnaHQ7XHJcbiAgICAgICAgICAgIGlmKHNoID4gb2gpe1xyXG4gICAgICAgICAgICAgICAgdGhpcy5zdHlsZS5oZWlnaHQgPSBzaCtcInB4XCI7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbm9kZS5vbmNoYW5nZSA9IGZ1bmN0aW9uIChlKSB7XHJcbiAgICAgICAgICAgIHZhciBkYXRhID0ge1xyXG4gICAgICAgICAgICAgICAgaWQ6IG13LiQodGhpcykuZGF0YXNldCgnaWQnKSxcclxuICAgICAgICAgICAgICAgIHZhbHVlOiBtdy4kKHRoaXMpLnZhbCgpXHJcbiAgICAgICAgICAgIH07XHJcbiAgICAgICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ2ZpZWxkcy9zYXZlJywgZGF0YSwgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgIG13LmN1c3RvbV9maWVsZHMuYWZ0ZXJfc2F2ZSgpO1xyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbn07XHJcblxyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmluaXRWYWx1ZSA9IGZ1bmN0aW9uIChub2RlKSB7XHJcbiAgICBpZiAoIW5vZGUuZmllbGRCaW5kZWQpIHtcclxuICAgICAgICBub2RlLmZpZWxkQmluZGVkID0gdHJ1ZTtcclxuICAgICAgICBub2RlLm9uY2xpY2sgPSBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICBtdy5hZG1pbi5jdXN0b21fZmllbGRzLnZhbHVlTGl2ZUVkaXQodGhpcyk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG59XHJcbm13LmFkbWluLmN1c3RvbV9maWVsZHMuYWRkVmFsdWVCdXR0b25zID0gZnVuY3Rpb24gKHJvb3QpIHtcclxuICAgIHZhciByb290ID0gcm9vdCB8fCBtd2QsIGFsbCA9IHJvb3QucXVlcnlTZWxlY3RvckFsbChcIi5idG4tY3JlYXRlLWN1c3RvbS1maWVsZC12YWx1ZVwiKSwgbCA9IGFsbC5sZW5ndGgsIGkgPSAwO1xyXG4gICAgZm9yICg7IGkgPCBsOyBpKyspIHtcclxuICAgICAgICBpZiAoISFhbGxbaV0uYXZiaW5kZWQpIHtcclxuICAgICAgICAgICAgY29udGludWU7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGFsbFtpXS5hdmJpbmRlZCA9IHRydWU7XHJcbiAgICAgICAgYWxsW2ldLm9uY2xpY2sgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHZhciBzcGFuID0gbXdkLmNyZWF0ZUVsZW1lbnQoJ3NwYW4nKTtcclxuICAgICAgICAgICAgc3Bhbi5jbGFzc05hbWUgPSAnbXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lLWhvbGRlciBtdy1hZG1pbi1jdXN0b20tZmllbGQtY2hlY2tib3ggYmctc2Vjb25kYXJ5LW9wYWNpdHktOCBkLWlubGluZS1mbGV4IG1yLTIgbXktMSBwLTAnO1xyXG4gICAgICAgICAgICBzcGFuLmlubmVySFRNTCA9ICc8c21hbGwgY2xhc3M9XCJtdy1hZG1pbi1jdXN0b20tZmllbGQtdmFsdWUtZWRpdC1pbmxpbmUgcC0xIHRleHQtZGFya1wiIGRhdGEtaWQ9XCInICsgbXcuJCh0aGlzKS5kYXRhc2V0KCdpZCcpICsgJ1wiPjwvc21hbGw+PHNtYWxsIG9uY2xpY2s9XCJtdy5hZG1pbi5jdXN0b21fZmllbGRzLmRlbGV0ZUZpZWxkVmFsdWUodGhpcyk7XCIgY2xhc3M9XCJkZWxldGUtY3VzdG9tLWZpZWxkcyBiZy1kYW5nZXIgdGV4dC13aGl0ZSBwLTFcIj48aSBjbGFzcz1cIm1kaSBtZGktY2xvc2VcIj48L2k+PC9zbWFsbD4gICAnO1xyXG4gICAgICAgICAgICBtdy5hZG1pbi5jdXN0b21fZmllbGRzLmluaXRWYWx1ZShzcGFuLnF1ZXJ5U2VsZWN0b3IoJy5tdy1hZG1pbi1jdXN0b20tZmllbGQtdmFsdWUtZWRpdC1pbmxpbmUnKSk7XHJcbiAgICAgICAgICAgIG13LiQodGhpcykucHJldigpLmFwcGVuZChzcGFuKTtcclxuICAgICAgICAgICAgbXcuYWRtaW4uY3VzdG9tX2ZpZWxkcy52YWx1ZUxpdmVFZGl0KHNwYW4ucXVlcnlTZWxlY3RvcignLm13LWFkbWluLWN1c3RvbS1maWVsZC12YWx1ZS1lZGl0LWlubGluZScpKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcblxyXG59XHJcblxyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLnZhbHVlTGl2ZUVkaXQgPSBmdW5jdGlvbiAoc3Bhbikge1xyXG4gICAgbXcuJChzcGFuLnBhcmVudE5vZGUpLmFkZENsYXNzKCdhY3RpdmUnKTtcclxuICAgIG13LnRvb2xzLmFkZENsYXNzKG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aFRhZyhzcGFuLCAndHInKSwgJ2FjdGl2ZScpO1xyXG4gICAgdmFyIGlucHV0ID0gbXcudG9vbHMuZWxlbWVudEVkaXQoc3BhbiwgdHJ1ZSwgZnVuY3Rpb24gKGVsKSB7XHJcbiAgICAgICAgdmFyIGRhdGE7XHJcbiAgICAgICAgaWYgKG13LnRvb2xzLmhhc0NsYXNzKGVsLCAnbXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJykpIHtcclxuICAgICAgICAgICAgdmFyIHZhbHMgPSBbXSxcclxuICAgICAgICAgICAgICAgIGFsbCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKGVsLCAnY3VzdG9tLWZpZWxkcy12YWx1ZXMtaG9sZGVyJykucGFyZW50Tm9kZS5xdWVyeVNlbGVjdG9yQWxsKCcubXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJyksXHJcbiAgICAgICAgICAgICAgICBsID0gYWxsLmxlbmd0aCxcclxuICAgICAgICAgICAgICAgIGkgPSAwO1xyXG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xyXG4gICAgICAgICAgICAgICAgdmFscy5wdXNoKGFsbFtpXS50ZXh0Q29udGVudCk7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGRhdGEgPSB7XHJcbiAgICAgICAgICAgICAgICBpZDogbXcuJChlbCkuZGF0YXNldCgnaWQnKSxcclxuICAgICAgICAgICAgICAgIHZhbHVlOiB2YWxzXHJcbiAgICAgICAgICAgIH07XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBlbHNlIGlmIChtdy50b29scy5oYXNDbGFzcyhlbCwgJ213LWFkbWluLWN1c3RvbS1maWVsZC1wbGFjZWhvbGRlci1lZGl0LWlubGluZScpKSB7XHJcblxyXG4gICAgICAgICAgICBkYXRhID0ge1xyXG4gICAgICAgICAgICAgICAgaWQ6IG13LiQoZWwpLmRhdGFzZXQoJ2lkJyksXHJcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogbXcuJChlbCkudGV4dCgpXHJcbiAgICAgICAgICAgIH07XHJcblxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgIGRhdGEgPSB7XHJcbiAgICAgICAgICAgICAgICBpZDogbXcuJChlbCkuZGF0YXNldCgnaWQnKSxcclxuICAgICAgICAgICAgICAgIG5hbWU6IG13LiQoZWwpLnRleHQoKVxyXG4gICAgICAgICAgICB9O1xyXG4gICAgICAgIH1cclxuICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtdy50b29scy5maXJzdFBhcmVudFdpdGhUYWcodGhpcywgJ3RyJyksICdhY3RpdmUnKTtcclxuICAgICAgICAkLnBvc3QobXcuc2V0dGluZ3MuYXBpX3VybCArICdmaWVsZHMvc2F2ZScsIGRhdGEsIGZ1bmN0aW9uIChkYXRhKSB7XHJcblxyXG4gICAgICAgIFx0aWYgKG13ZC5nZXRFbGVtZW50QnlJZCgnbXctY3VzdG9tLWZpZWxkcy1saXN0LXNldHRpbmdzLScgKyBkYXRhLmlkKSAhPSBudWxsKSB7XHJcblxyXG5cdCAgICAgICAgICAgIHZhciByc3RyID0gbXdkLmdldEVsZW1lbnRCeUlkKCdtdy1jdXN0b20tZmllbGRzLWxpc3Qtc2V0dGluZ3MtJyArIGRhdGEuaWQpLmlubmVySFRNTC5yZXBsYWNlKC9cXHMrL2csICcnKTtcclxuXHJcblx0ICAgICAgICAgICAgaWYgKHJzdHIgJiYgISFkYXRhLnZhbHVlKSB7XHJcblx0ICAgICAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoJyNtdy1jdXN0b20tZmllbGRzLWxpc3Qtc2V0dGluZ3MtJyArIGRhdGEuaWQpO1xyXG5cdCAgICAgICAgICAgIH1cclxuICAgICAgICBcdH1cclxuXHJcbiAgICAgICAgXHRtdy5jdXN0b21fZmllbGRzLmFmdGVyX3NhdmUoKTtcclxuXHJcbiAgICAgICAgfSk7XHJcbiAgICAgICAgbXcuJChlbC5wYXJlbnROb2RlKS5yZW1vdmVDbGFzcygnYWN0aXZlJyk7XHJcbiAgICAgICAgbXcudG9vbHMucmVtb3ZlQ2xhc3MobXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoVGFnKGVsLCAndHInKSwgJ2FjdGl2ZScpO1xyXG4gICAgfSk7XHJcbiAgICBtdy4kKGlucHV0KS5iaW5kKCdibHVyJywgZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIG13LiQoJy5tdy1hZG1pbi1jdXN0b20tZmllbGQtdmFsdWUtZWRpdC1pbmxpbmUtaG9sZGVyLmFjdGl2ZScpLnJlbW92ZUNsYXNzKCdhY3RpdmUnKTtcclxuICAgICAgICBtdy50b29scy5yZW1vdmVDbGFzcyhtdy50b29scy5maXJzdFBhcmVudFdpdGhUYWcodGhpcywgJ3RyJyksICdhY3RpdmUnKTtcclxuICAgIH0pO1xyXG4gICAgbXcuJChpbnB1dCkuYmluZCgna2V5ZG93bicsIGZ1bmN0aW9uIChlKSB7XHJcblxyXG4gICAgICAgIHZhciBjb2RlID0gKGUua2V5Q29kZSA/IGUua2V5Q29kZSA6IGUud2hpY2gpO1xyXG5cclxuICAgICAgICBpZiAoY29kZSA9PSA5KSB7XHJcbiAgICAgICAgICAgIHZhciBwYXJlbnQgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyhlLnRhcmdldCwgJ213LWFkbWluLWN1c3RvbS1maWVsZC12YWx1ZS1lZGl0LWlubGluZS1ob2xkZXInKTtcclxuICAgICAgICAgICAgaWYgKCFlLnNoaWZ0S2V5KSB7XHJcbiAgICAgICAgICAgICAgICBpZiAocGFyZW50Lm5leHRFbGVtZW50U2libGluZyAhPT0gbnVsbCAmJiBtdy50b29scy5oYXNDbGFzcyhwYXJlbnQubmV4dEVsZW1lbnRTaWJsaW5nLCAnbXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lLWhvbGRlcicpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuYWRtaW4uY3VzdG9tX2ZpZWxkcy52YWx1ZUxpdmVFZGl0KHBhcmVudC5uZXh0RWxlbWVudFNpYmxpbmcucXVlcnlTZWxlY3RvcignLm13LWFkbWluLWN1c3RvbS1maWVsZC12YWx1ZS1lZGl0LWlubGluZScpKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGVsc2Uge1xyXG5cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgIGlmIChwYXJlbnQucHJldmlvdXNFbGVtZW50U2libGluZyAhPT0gbnVsbCAmJiBtdy50b29scy5oYXNDbGFzcyhwYXJlbnQucHJldmlvdXNFbGVtZW50U2libGluZywgJ213LWFkbWluLWN1c3RvbS1maWVsZC12YWx1ZS1lZGl0LWlubGluZS1ob2xkZXInKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG13LmFkbWluLmN1c3RvbV9maWVsZHMudmFsdWVMaXZlRWRpdChwYXJlbnQucHJldmlvdXNFbGVtZW50U2libGluZy5xdWVyeVNlbGVjdG9yKCcubXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJykpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgZWxzZSB7XHJcblxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgfSBlbHNlIHtcclxuXHJcblx0LypcclxuXHRcdFx0XHR2YXIgZWwgPSBtdy4kKCBlLnRhcmdldClbMF07XHJcblx0XHRcdFx0bXcub24uc3RvcFdyaXRpbmcoZWwsIGZ1bmN0aW9uICgpIHtcclxuXHJcblx0XHQgICAgICAgICAgICAgdmFyIHBhcmVudCA9IG13LnRvb2xzLmZpcnN0UGFyZW50V2l0aENsYXNzKGVsLCAnbXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJyk7XHJcblx0XHRcdFx0XHQgZChwYXJlbnQpO1xyXG4gICAgICAgICAgICAgICAgICAgIG13LmFkbWluLmN1c3RvbV9maWVsZHMudmFsdWVMaXZlRWRpdChwYXJlbnQpO1xyXG5cclxuXHJcblx0XHRcdCB9KTsqL1xyXG5cclxuXHRcdH1cclxuICAgIH0pO1xyXG59XHJcbm13LmFkbWluLmN1c3RvbV9maWVsZHMubWFrZV9maWVsZHNfc29ydGFibGUgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICB2YXIgc29ydGFibGVfaG9sZGVyID0gbXcuJChcIiNjdXN0b20tZmllbGRzLXBvc3QtdGFibGVcIikuZXEoMCk7XHJcbiAgICBpZiAoIXNvcnRhYmxlX2hvbGRlci5oYXNDbGFzcygndWktc29ydGFibGUnKSAmJiBzb3J0YWJsZV9ob2xkZXIuZmluZCgndHInKS5sZW5ndGggPiAxKSB7XHJcbiAgICAgICAgc29ydGFibGVfaG9sZGVyLnNvcnRhYmxlKHtcclxuICAgICAgICAgICAgaXRlbXM6ICd0cicsXHJcbiAgICAgICAgICAgIGRpc3RhbmNlOiAzNSxcclxuICAgICAgICAgICAgdXBkYXRlOiBmdW5jdGlvbiAoZXZlbnQsIHVpKSB7XHJcbiAgICAgICAgICAgICAgICB2YXIgb2JqID0ge2lkczogW119O1xyXG4gICAgICAgICAgICAgICAgbXcuJCh0aGlzKS5maW5kKFwiLm13LWFkbWluLWN1c3RvbS1maWVsZC1uYW1lLWVkaXQtaW5saW5lLCAubXctYWRtaW4tY3VzdG9tLWZpZWxkLXBsYWNlaG9sZGVyLWVkaXQtaW5saW5lXCIpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBpZCA9IG13LiQodGhpcykuZGF0YXNldChcImlkXCIpO1xyXG4gICAgICAgICAgICAgICAgICAgIG9iai5pZHMucHVzaChpZCk7XHJcbiAgICAgICAgICAgICAgICB9KTtcclxuXHJcblxyXG4gICAgICAgICAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyBcImZpZWxkcy9yZW9yZGVyXCIsIG9iaiwgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcbiAgICByZXR1cm4gc29ydGFibGVfaG9sZGVyO1xyXG59O1xyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmRlbCA9IGZ1bmN0aW9uIChpZCwgdG9yZW1vdmUpIHtcclxuICAgIHZhciBxID0gXCJBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gZGVsZXRlICdcIiArIG13LiQoJyNtdy1jdXN0b20tbGlzdC1lbGVtZW50LScgKyBpZCArICcgLm13LWFkbWluLWN1c3RvbS1maWVsZC1uYW1lLWVkaXQtaW5saW5lJykudGV4dCgpICsgXCInID9cIjtcclxuICAgIG13LnRvb2xzLmNvbmZpcm0ocSwgZnVuY3Rpb24gKCkge1xyXG5cclxuICAgICAgICBtdy5jdXN0b21fZmllbGRzLnJlbW92ZShpZCwgZnVuY3Rpb24gKGRhdGEpIHtcclxuICAgICAgICAgICAgbXcuJCgnI213LWN1c3RvbS1saXN0LWVsZW1lbnQtJyArIGlkKS5hZGRDbGFzcygnc2NhbGUtb3V0Jyk7XHJcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgbXcucmVsb2FkX21vZHVsZV9wYXJlbnQoJ2N1c3RvbV9maWVsZHMnKTtcclxuICAgICAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoJ2N1c3RvbV9maWVsZHMvbGlzdCcsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoISF0b3JlbW92ZSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBtdy4kKHRvcmVtb3ZlKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuJChcIiNjdXN0b20tZmllbGQtZWRpdG9yXCIpLnJlbW92ZUNsYXNzKCdtdy1jdXN0b20tZmllbGQtY3JlYXRlZCcpLmhpZGUoKTtcclxuICAgICAgICAgICAgICAgICAgICBtdy50cmlnZ2VyKCdjdXN0b21GaWVsZFNhdmVkJywgaWQpO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgbG9hZF9pZnJhbWVfZWRpdG9yID09PSAnZnVuY3Rpb24nKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGxvYWRfaWZyYW1lX2VkaXRvcigpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICBtdy5hZG1pbi5jdXN0b21fZmllbGRzLmluaXRWYWx1ZXMoKTtcclxuICAgICAgICAgICAgICAgIH0pO1xyXG4gICAgICAgICAgICB9LCAzMDApO1xyXG5cclxuICAgICAgICB9KTtcclxuICAgIH0pO1xyXG59O1xyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmRlbGV0ZUZpZWxkVmFsdWUgPSBmdW5jdGlvbiAoZWwpIHtcclxuICAgIHZhciB4ZWwgID0gZWwucGFyZW50Tm9kZS5wYXJlbnROb2RlXHJcbiAgICBtdy4kKGVsLnBhcmVudE5vZGUpLnJlbW92ZSgpO1xyXG4gICAgbXcuYWRtaW4uY3VzdG9tX2ZpZWxkcy52YWx1ZUxpdmVFZGl0KHhlbC5xdWVyeVNlbGVjdG9yKCcubXctYWRtaW4tY3VzdG9tLWZpZWxkLXZhbHVlLWVkaXQtaW5saW5lJykpO1xyXG5cclxuXHJcblxyXG59O1xyXG5cclxuXHJcblxyXG5tdy5hZG1pbi5jdXN0b21fZmllbGRzLmVkaXRfY3VzdG9tX2ZpZWxkX2l0ZW0gPSBmdW5jdGlvbiAoJHNlbGVjdG9yLCBpZCwgY2FsbGJhY2ssIGV2ZW50KSB7XHJcblxyXG4gICAgdmFyIG1UaXRsZSA9IChpZCA/ICdFZGl0IGN1c3RvbSBmaWVsZCcgOiAnQWRkIG5ldyBjdXN0b20gZmllbGQnKTtcclxuXHJcbiAgICB2YXIgZGF0YSA9IHt9O1xyXG4gICAgZGF0YS5zZXR0aW5ncyA9ICd5JztcclxuICAgIGRhdGEuZmllbGRfaWQgPSBpZDtcclxuICAgIGRhdGEubGl2ZV9lZGl0ID0gdHJ1ZTtcclxuICAgIGRhdGEubW9kdWxlX3NldHRpbmdzID0gdHJ1ZTtcclxuICAgIGRhdGEuaWQgPSBpZDtcclxuXHJcblxyXG4gICAgZGF0YS5wYXJhbXMgPSB7fTtcclxuICAgIGRhdGEucGFyYW1zLmZpZWxkX2lkID0gaWQ7XHJcblxyXG4gICAgZWRpdE1vZGFsID0gbXcudG9wKCkudG9vbHMub3Blbl9tb2R1bGVfbW9kYWwoJ2N1c3RvbV9maWVsZHMvdmFsdWVzX2VkaXQnLCBkYXRhLCB7XHJcbiAgICAgICAgb3ZlcmxheTogZmFsc2UsXHJcbiAgICAgICAgd2lkdGg6JzQ1MHB4JyxcclxuICAgICAgICBoZWlnaHQ6J2F1dG8nLFxyXG4gICAgICAgIGF1dG9IZWlnaHQ6IHRydWUsXHJcbiAgICAgICAgaWZyYW1lOiB0cnVlLFxyXG4gICAgICAgIHRpdGxlOiBtVGl0bGVcclxuICAgIH0pO1xyXG5cclxufTtcclxuXHJcbiQobXd3KS5iaW5kKCdsb2FkJywgZnVuY3Rpb24gKCkge1xyXG4gICAgbXcuYWRtaW4uY3VzdG9tX2ZpZWxkcy5pbml0VmFsdWVzKCk7XHJcbn0pO1xyXG4kKG13ZCkucmVhZHkoZnVuY3Rpb24gKCkge1xyXG4gICAgbXcuYWRtaW4uY3VzdG9tX2ZpZWxkcy5pbml0VmFsdWVzKCk7XHJcbn0pO1xyXG4iLCJtdy5hZG1pbiA9IG13LmFkbWluIHx8IHt9O1xyXG5tdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIgPSBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIgfHwge31cclxuXHJcblxyXG5tdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuc2V0X2xvYWRpbmcgPSBmdW5jdGlvbiAoaXNfbG9hZGluZykge1xyXG5cclxuICAgIG13LnRvb2xzLmxvYWRpbmcobXdkLnF1ZXJ5U2VsZWN0b3IoJy5qcy1pbnN0YWxsLXBhY2thZ2UtbG9hZGluZy1jb250YWluZXItY29uZmlybScpLCBpc19sb2FkaW5nLCAnc2xvdycpO1xyXG4gICAgbXcudG9vbHMubG9hZGluZyhtd2QucXVlcnlTZWxlY3RvcignI213LXBhY2thZ2VzLWJyb3dzZXItbmF2LXRhYnMtbmF2JyksIGlzX2xvYWRpbmcsICdzbG93Jyk7XHJcbiAgICBtdy50b29scy5sb2FkaW5nKG13ZC5xdWVyeVNlbGVjdG9yKCcuYWRtaW4tdG9vbGJhcicpLCBpc19sb2FkaW5nLCAnc2xvdycpO1xyXG4gICAgbXcudG9vbHMubG9hZGluZyhtd2QucXVlcnlTZWxlY3RvcignI3VwZGF0ZV9xdWV1ZV9zZXRfbW9kYWwnKSwgaXNfbG9hZGluZywgJ3Nsb3cnKTtcclxuXHJcbn1cclxuXHJcblxyXG5tdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIucmVsb2FkX3BhY2thZ2VzX2xpc3QgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuc2V0X2xvYWRpbmcodHJ1ZSlcclxuICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIG13Lm5vdGlmaWNhdGlvbi5zdWNjZXNzKCdSZWxvYWRpbmcgcGFja2FnZXMnLCAxNTAwMCk7XHJcbiAgICB9LCAxMDAwKTtcclxuICAgIG13LmNsZWFyX2NhY2hlKCk7XHJcbiAgICBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuc2V0X2xvYWRpbmcodHJ1ZSlcclxuXHJcbiAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcclxuICAgICAgICBtdy5yZWxvYWRfbW9kdWxlKCdhZG1pbi9kZXZlbG9wZXJfdG9vbHMvcGFja2FnZV9tYW5hZ2VyL2Jyb3dzZV9wYWNrYWdlcycsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgbXcuYWRtaW4uYWRtaW5fcGFja2FnZV9tYW5hZ2VyLnNldF9sb2FkaW5nKGZhbHNlKVxyXG4gICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uc3VjY2VzcygnUGFja2FnZXMgYXJlIHJlbG9hZGVkJyk7XHJcbiAgICAgICAgfSlcclxuXHJcbiAgICB9LCAxMDAwKTtcclxuXHJcblxyXG59XHJcblxyXG5tdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuc2hvd19saWNlbnNlc19tb2RhbCA9IGZ1bmN0aW9uICgpIHtcclxuICAgIHZhciBkYXRhID0ge31cclxuICAgIGxpY2Vuc2VzTW9kYWwgPSBtdy50b29scy5vcGVuX21vZHVsZV9tb2RhbCgnc2V0dGluZ3MvZ3JvdXAvbGljZW5zZXMnLCBkYXRhLCB7XHJcbiAgICAgICAgLy8gIG92ZXJsYXk6IHRydWUsXHJcbiAgICAgICAgLy8gIGlmcmFtZTogdHJ1ZSxcclxuXHJcbiAgICAgICAgdGl0bGU6ICdMaWNlbnNlcycsXHJcbiAgICAgICAgc2tpbjogJ3NpbXBsZSdcclxuICAgIH0pXHJcblxyXG5cclxufVxyXG5cclxuXHJcbm13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5pbnN0YWxsX2NvbXBvc2VyX3BhY2thZ2VfYnlfcGFja2FnZV9uYW1lID0gZnVuY3Rpb24gKCRrZXksICR2ZXJzaW9uKSB7XHJcblxyXG4gICAgbXcubm90aWZpY2F0aW9uLnN1Y2Nlc3MoJ0xvYWRpbmcuLi4nLCAyNTAwMCk7XHJcbiAgICAvL213LmxvYWRfbW9kdWxlKCd1cGRhdGVzL3dvcmtlcicsICcjbXctdXBkYXRlcy1xdWV1ZScpO1xyXG5cclxuXHJcbiAgICB2YXIgdXBkYXRlX3F1ZXVlX3NldF9tb2RhbCA9IG13LmRpYWxvZyh7XHJcbiAgICAgICAgY29udGVudDogJzxkaXYgY2xhc3M9XCJtb2R1bGVcIiB0eXBlPVwidXBkYXRlcy93b3JrZXJcIiBpZD1cInVwZGF0ZV9xdWV1ZV9wcm9jZXNzX2FsZXJ0XCI+PC9kaXY+JyxcclxuICAgICAgICBvdmVybGF5OiBmYWxzZSxcclxuICAgICAgICBpZDogJ3VwZGF0ZV9xdWV1ZV9zZXRfbW9kYWwnLFxyXG4gICAgICAgIHRpdGxlOiAnUHJlcGFyaW5nJ1xyXG4gICAgfSk7XHJcblxyXG4gICAgbXcucmVsb2FkX21vZHVsZSgnI3VwZGF0ZV9xdWV1ZV9wcm9jZXNzX2FsZXJ0Jyk7XHJcblxyXG5cclxuICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5zZXRfbG9hZGluZyg1MClcclxuXHJcblxyXG4gICAgdmFyIHZhbHVlcyA9IHtyZXF1aXJlX25hbWU6ICRrZXksIHJlcXVpcmVfdmVyc2lvbjogJHZlcnNpb259O1xyXG5cclxuICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5pbnN0YWxsX2NvbXBvc2VyX3BhY2thZ2VfYnlfcGFja2FnZV9uYW1lX2RvX2FqYXgodmFsdWVzKTtcclxuXHJcblxyXG59XHJcblxyXG5cclxubXcuYWRtaW4uYWRtaW5fcGFja2FnZV9tYW5hZ2VyLmluc3RhbGxfY29tcG9zZXJfcGFja2FnZV9ieV9wYWNrYWdlX25hbWVfZG9fYWpheF9sYXN0X3N0ZXBfdmFscyA9IG51bGw7XHJcblxyXG5cclxubXcuYWRtaW4uYWRtaW5fcGFja2FnZV9tYW5hZ2VyLmluc3RhbGxfY29tcG9zZXJfcGFja2FnZV9ieV9wYWNrYWdlX25hbWVfZG9fYWpheCA9IGZ1bmN0aW9uICh2YWx1ZXMpIHtcclxuICAgICQuYWpheCh7XHJcbiAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJtd19jb21wb3Nlcl9pbnN0YWxsX3BhY2thZ2VfYnlfbmFtZVwiLFxyXG4gICAgICAgIHR5cGU6IFwicG9zdFwiLFxyXG4gICAgICAgIGRhdGE6IHZhbHVlcyxcclxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAobXNnKSB7XHJcbiAgICAgICAgICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5zZXRfbG9hZGluZyh0cnVlKTtcclxuXHJcbiAgICAgICAgICAgIGlmICh0eXBlb2YgbXNnID09ICdvYmplY3QnICYmIG1zZy50cnlfYWdhaW4gICYmIG1zZy51bnppcF9jYWNoZV9rZXkpIHtcclxuICAgICAgICAgICAgICAgIGlmIChtc2cudHJ5X2FnYWluKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFsdWVzLnRyeV9hZ2Fpbl9zdGVwID0gdHJ1ZTtcclxuICAgICAgICAgICAgICAgICAgICB2YWx1ZXMudW56aXBfY2FjaGVfa2V5ID0gIG1zZy51bnppcF9jYWNoZV9rZXk7XHJcbiAgICAgICAgICAgICAgICAgICAgbXcuYWRtaW4uYWRtaW5fcGFja2FnZV9tYW5hZ2VyLmluc3RhbGxfY29tcG9zZXJfcGFja2FnZV9ieV9wYWNrYWdlX25hbWVfZG9fYWpheF9sYXN0X3N0ZXBfdmFscyA9IHZhbHVlcztcclxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5pbnN0YWxsX2NvbXBvc2VyX3BhY2thZ2VfYnlfcGFja2FnZV9uYW1lX2RvX2FqYXgodmFsdWVzKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgfSwgNTAwKTtcclxuXHJcblxyXG5cclxuICAgICAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24ubXNnKG1zZyk7XHJcbiAgICAgICAgICAgICAgICBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuc2V0X2xvYWRpbmcoZmFsc2UpXHJcblxyXG5cclxuXHJcbiAgICAgICAgICAgICAgICBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIucmVsb2FkX3BhY2thZ2VzX2xpc3QoKTtcclxuICAgICAgICAgICAgICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5zZXRfbG9hZGluZyhmYWxzZSk7XHJcblxyXG4gICAgICAgICAgICAgICAgbXcuJCgnI3VwZGF0ZV9xdWV1ZV9zZXRfbW9kYWwnKS5yZW1vdmUoKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuXHJcblxyXG4gICAgICAgIH0sXHJcblxyXG4gICAgICAgIGVycm9yOiBmdW5jdGlvbiAoanFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duKSB7XHJcbiAgICAgICAgICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5zZXRfbG9hZGluZyhmYWxzZSk7XHJcblxyXG4gICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7XHJcbiAgICAgICAgICAgICAgICBtdy5hZG1pbi5hZG1pbl9wYWNrYWdlX21hbmFnZXIuaW5zdGFsbF9jb21wb3Nlcl9wYWNrYWdlX2J5X3BhY2thZ2VfbmFtZV9kb19hamF4KG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5pbnN0YWxsX2NvbXBvc2VyX3BhY2thZ2VfYnlfcGFja2FnZV9uYW1lX2RvX2FqYXhfbGFzdF9zdGVwX3ZhbHMpO1xyXG5cclxuICAgICAgICAgICAgfSwgNTAwKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgfSkuYWx3YXlzKGZ1bmN0aW9uIChqcVhIUiwgdGV4dFN0YXR1cykge1xyXG5cclxuXHJcbiAgICAgICAgaWYodHlwZW9mKGNvbnRleHQpICE9ICd1bmRlZmluZWQnICkge1xyXG4gICAgICAgICAgICBtdy5zcGlubmVyKHsgZWxlbWVudDogJChjb250ZXh0KS5uZXh0KCkgfSkuaGlkZSgpO1xyXG4gICAgICAgICAgICAkKGNvbnRleHQpLnNob3coKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIG13LiQoJyN1cGRhdGVfcXVldWVfc2V0X21vZGFsJykucmVtb3ZlKCk7XHJcblxyXG4gICAgICAgIG13LmFkbWluLmFkbWluX3BhY2thZ2VfbWFuYWdlci5zZXRfbG9hZGluZyhmYWxzZSk7XHJcblxyXG5cclxuICAgIH0pXHJcblxyXG59XHJcblxyXG4iLCJtdy5yZXF1aXJlKCd0cmVlLmpzJyk7XG5tdy5yZXF1aXJlKCdsaW5rLWVkaXRvci5qcycpO1xubXcucmVxdWlyZSgndGFncy5qcycpO1xuXG5cbm13LmFkbWluID0ge1xuICAgIGxhbmd1YWdlOiBmdW5jdGlvbihsYW5ndWFnZSkge1xuICAgICAgICBpZiAoIWxhbmd1YWdlKSB7XG4gICAgICAgICAgICByZXR1cm4gbXcuY29va2llLmdldChcImxhbmdcIik7XG4gICAgICAgIH1cbiAgICAgICAgbXcuY29va2llLnNldChcImxhbmdcIiwgbGFuZ3VhZ2UpO1xuICAgICAgICBsb2NhdGlvbi5yZWxvYWQoKTtcbiAgICB9LFxuICAgIGVkaXRvcjoge1xuICAgICAgICBzZXQ6IGZ1bmN0aW9uIChmcmFtZSkge1xuICAgICAgICAgICAgbXcuJChmcmFtZSkud2lkdGgoJzEwMCUnKTtcbiAgICAgICAgICAvKlxuICAgICAgICAgICAgaWYgKCEhZnJhbWUgJiYgZnJhbWUgIT09IG51bGwgJiYgISFmcmFtZS5jb250ZW50V2luZG93KSB7XG4gICAgICAgICAgICAgICAgdmFyIHdpZHRoX21iYXIgPSBtdy4kKCcjbWFpbi1iYXInKS53aWR0aCgpLFxuICAgICAgICAgICAgICAgICAgICB0cmVlID0gbXdkLnF1ZXJ5U2VsZWN0b3IoJy50cmVlLWNvbHVtbicpLFxuICAgICAgICAgICAgICAgICAgICB3aWR0aF90YmFyID0gbXcuJCh0cmVlKS53aWR0aCgpLFxuICAgICAgICAgICAgICAgICAgICB3dyA9IG13LiQod2luZG93KS53aWR0aCgpO1xuICAgICAgICAgICAgICAgIGlmICh0cmVlLnN0eWxlLmRpc3BsYXkgPT09ICdub25lJykge1xuICAgICAgICAgICAgICAgICAgICB3aWR0aF90YmFyID0gMDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKHdpZHRoX21iYXIgPiAyMDApIHtcbiAgICAgICAgICAgICAgICAgICAgd2lkdGhfbWJhciA9IDA7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQoZnJhbWUpXG4gICAgICAgICAgICAgICAgICAgIC53aWR0aCh3dyAtIHdpZHRoX3RiYXIgLSB3aWR0aF9tYmFyIC0gMzUpXG4gICAgICAgICAgICAgICAgICAgIC5oZWlnaHQoZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5Lm9mZnNldEhlaWdodCk7XG4gICAgICAgICAgICB9Ki9cbiAgICAgICAgfSxcbiAgICAgICAgaW5pdDogZnVuY3Rpb24gKGFyZWEsIHBhcmFtcykge1xuICAgICAgICAgICAgcGFyYW1zID0gcGFyYW1zIHx8IHt9O1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBwYXJhbXMgPT09ICdvYmplY3QnKSB7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBwYXJhbXMuc3JjICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGRlbGV0ZShwYXJhbXMuc3JjKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBwYXJhbXMubGl2ZV9lZGl0PWZhbHNlO1xuICAgICAgICAgICAgcGFyYW1zID0gdHlwZW9mIHBhcmFtcyA9PT0gJ29iamVjdCcgPyBqc29uMnVybChwYXJhbXMpIDogcGFyYW1zO1xuICAgICAgICAgICAgYXJlYSA9IG13LiQoYXJlYSk7XG4gICAgICAgICAgICB2YXIgZnJhbWUgPSBtd2QuY3JlYXRlRWxlbWVudCgnaWZyYW1lJyk7XG4gICAgICAgICAgICBmcmFtZS5zcmMgPSBtdy5leHRlcm5hbF90b29sKCd3eXNpd3lnPycgKyBwYXJhbXMpO1xuICAgICAgICAgICAgY29uc29sZS5sb2cobXcuZXh0ZXJuYWxfdG9vbCgnd3lzaXd5Zz8nICsgcGFyYW1zKSlcbiAgICAgICAgICAgIGZyYW1lLmNsYXNzTmFtZSA9ICdtdy1pZnJhbWUtZWRpdG9yJztcbiAgICAgICAgICAgIGZyYW1lLnNjcm9sbGluZyA9ICdubyc7XG4gICAgICAgICAgICB2YXIgbmFtZSA9ICdtd2VkaXRvcicgKyBtdy5yYW5kb20oKTtcbiAgICAgICAgICAgIGZyYW1lLmlkID0gbmFtZTtcbiAgICAgICAgICAgIGZyYW1lLm5hbWUgPSBuYW1lO1xuICAgICAgICAgICAgZnJhbWUuc3R5bGUuYmFja2dyb3VuZENvbG9yID0gXCJ0cmFuc3BhcmVudFwiO1xuICAgICAgICAgICAgZnJhbWUuc2V0QXR0cmlidXRlKCdmcmFtZWJvcmRlcicsIDApO1xuICAgICAgICAgICAgZnJhbWUuc2V0QXR0cmlidXRlKCdhbGxvd3RyYW5zcGFyZW5jeScsICd0cnVlJyk7XG4gICAgICAgICAgICBhcmVhLmVtcHR5KCkuYXBwZW5kKGZyYW1lKTtcbiAgICAgICAgICAgIG13LiQoZnJhbWUpLmxvYWQoZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIGZyYW1lLmNvbnRlbnRXaW5kb3cudGhpc2ZyYW1lID0gZnJhbWU7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBmcmFtZS5jb250ZW50V2luZG93LlByZXBhcmVFZGl0b3IgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgZnJhbWUuY29udGVudFdpbmRvdy5QcmVwYXJlRWRpdG9yKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LmFkbWluLmVkaXRvci5zZXQoZnJhbWUpO1xuICAgICAgICAgICAgICAgIG13LiQoZnJhbWUuY29udGVudFdpbmRvdy5kb2N1bWVudC5ib2R5KS5iaW5kKCdrZXl1cCBwYXN0ZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcuYWRtaW4uZWRpdG9yLnNldChmcmFtZSk7XG4gICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIG13LmFkbWluLmVkaXRvci5zZXQoZnJhbWUpO1xuICAgICAgICAgICAgbXcuJCh3aW5kb3cpLmJpbmQoJ3Jlc2l6ZScsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy5hZG1pbi5lZGl0b3Iuc2V0KGZyYW1lKTtcbiAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgcmV0dXJuIGZyYW1lO1xuICAgICAgICB9XG4gICAgfSxcbiAgICBtYW5hZ2VUb29sYmFyUXVpY2tOYXY6IG51bGwsXG4gICAgaW5zZXJ0TW9kdWxlOiBmdW5jdGlvbiAobW9kdWxlKSB7XG4gICAgICAgIG13ZC5xdWVyeVNlbGVjdG9yKCcubXctaWZyYW1lLWVkaXRvcicpLmNvbnRlbnRXaW5kb3cuSW5zZXJ0TW9kdWxlKG1vZHVsZSk7XG4gICAgfSxcblxuXG4gICAgICAgIHNpbXBsZVJvdGF0b3I6IGZ1bmN0aW9uIChyb3RhdG9yKSB7XG4gICAgICAgIGlmIChyb3RhdG9yID09PSBudWxsKSB7XG4gICAgICAgICAgICByZXR1cm4gdW5kZWZpbmVkO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2Ygcm90YXRvciAhPT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIGlmICghJChyb3RhdG9yKS5oYXNDbGFzcygnYWN0aXZhdGVkJykpIHtcbiAgICAgICAgICAgICAgICBtdy4kKHJvdGF0b3IpLmFkZENsYXNzKCdhY3RpdmF0ZWQnKVxuICAgICAgICAgICAgICAgIHZhciBhbGwgPSByb3RhdG9yLmNoaWxkcmVuO1xuICAgICAgICAgICAgICAgIHZhciBsID0gYWxsLmxlbmd0aDtcbiAgICAgICAgICAgICAgICBtdy4kKGFsbCkuYWRkQ2xhc3MoJ213LXNpbXBsZS1yb3RhdG9yLWl0ZW0nKTtcblxuICAgICAgICAgICAgICAgIHJvdGF0b3IuZ28gPSBmdW5jdGlvbiAod2hlcmUsIGNhbGxiYWNrLCBtZXRob2QpIHtcbiAgICAgICAgICAgICAgICAgICAgbWV0aG9kID0gbWV0aG9kIHx8ICdhbmltYXRlJztcbiAgICAgICAgICAgICAgICAgICAgbXcuJChyb3RhdG9yKS5kYXRhc2V0KCdzdGF0ZScsIHdoZXJlKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChyb3RhdG9yLmNoaWxkcmVuKS5oaWRlKCkuZXEod2hlcmUpLnNob3coKVxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwocm90YXRvcik7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvdGF0b3Iub25nb2VzLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBsID0gcm90YXRvci5vbmdvZXMubGVuZ3RoO1xuICAgICAgICAgICAgICAgICAgICAgICAgaSA9IDA7XG4gICAgICAgICAgICAgICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJvdGF0b3Iub25nb2VzW2ldLmNhbGwocm90YXRvcik7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9O1xuICAgICAgICAgICAgICAgIHJvdGF0b3Iub25nb2VzID0gW107XG4gICAgICAgICAgICAgICAgcm90YXRvci5vbmdvID0gZnVuY3Rpb24gKGMpIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByb3RhdG9yLm9uZ29lcy5wdXNoKGMpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgICAgICByZXR1cm4gcm90YXRvcjtcbiAgICB9LFxuXG4gICAgcG9zdEltYWdlVXBsb2FkZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgaWYgKG13ZC5xdWVyeVNlbGVjdG9yKCcjaW1hZ2VzLW1hbmFnZXInKSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChtd2QucXVlcnlTZWxlY3RvcignLm13LWlmcmFtZS1lZGl0b3InKSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmIChtd2QucXVlcnlTZWxlY3RvcignLm13LWlmcmFtZS1lZGl0b3InKS5jb250ZW50V2luZG93LmRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5lZGl0JykgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICB2YXIgdXBsb2FkZXIgPSBtdy51cGxvYWRlcih7XG4gICAgICAgICAgICBmaWxldHlwZXM6IFwiaW1hZ2VzXCIsXG4gICAgICAgICAgICBtdWx0aXBsZTogdHJ1ZSxcbiAgICAgICAgICAgIGVsZW1lbnQ6IFwiI2luc2VydC1pbWFnZS11cGxvYWRlclwiXG4gICAgICAgIH0pO1xuICAgICAgICBtdy4kKHVwbG9hZGVyKS5iaW5kKFwiRmlsZVVwbG9hZGVkXCIsIGZ1bmN0aW9uIChvYmosIGRhdGEpIHtcbiAgICAgICAgICAgIHZhciBmcmFtZVdpbmRvdyA9IG13ZC5xdWVyeVNlbGVjdG9yKCcubXctaWZyYW1lLWVkaXRvcicpLmNvbnRlbnRXaW5kb3c7XG4gICAgICAgICAgICB2YXIgaGFzUmFuZ2VzID0gZnJhbWVXaW5kb3cuZ2V0U2VsZWN0aW9uKCkucmFuZ2VDb3VudCA+IDA7XG4gICAgICAgICAgICB2YXIgaW1nID0gJzxpbWcgY2xhc3M9XCJlbGVtZW50XCIgc3JjPVwiJyArIGRhdGEuc3JjICsgJ1wiIC8+JztcbiAgICAgICAgICAgIGlmIChoYXNSYW5nZXMgJiYgZnJhbWVXaW5kb3cubXcud3lzaXd5Zy5pc1NlbGVjdGlvbkVkaXRhYmxlKCkpIHtcbiAgICAgICAgICAgICAgICBmcmFtZVdpbmRvdy5tdy53eXNpd3lnLmluc2VydF9odG1sKGltZyk7XG4gICAgICAgICAgICB9XG4gICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICBmcmFtZVdpbmRvdy5tdy4kKGZyYW1lV2luZG93Lm13ZC5xdWVyeVNlbGVjdG9yKCcuZWRpdCcpKS5hcHBlbmQoaW1nKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICB9LFxuICAgIGxpc3RQb3N0R2FsbGVyaWVzOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgIGlmIChtd2QucXVlcnlTZWxlY3RvcignI2ltYWdlcy1tYW5hZ2VyJykgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAobXdkLnF1ZXJ5U2VsZWN0b3IoJy5tdy1pZnJhbWUtZWRpdG9yJykgPT09IG51bGwpIHtcbiAgICAgICAgICAgIHJldHVybiBmYWxzZTtcbiAgICAgICAgfVxuICAgICAgICBpZiAobXdkLnF1ZXJ5U2VsZWN0b3IoJy5tdy1pZnJhbWUtZWRpdG9yJykuY29udGVudFdpbmRvdy5td2QucXVlcnlTZWxlY3RvcignLmVkaXQnKSA9PT0gbnVsbCkge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgfSxcblxuXG4gICAgYmVmb3JlTGVhdmVMb2NrZXI6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIHJvb3RzID0gJyNwYWdlc190cmVlX3Rvb2xiYXIsICNtYWluLWJhcicsXG4gICAgICAgICAgICBhbGwgPSBtd2QucXVlcnlTZWxlY3RvckFsbChyb290cyksXG4gICAgICAgICAgICBsID0gYWxsLmxlbmd0aCxcbiAgICAgICAgICAgIGkgPSAwO1xuICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgaWYgKCEhYWxsW2ldLk1XYmVmb3JlTGVhdmVMb2NrZXIpIGNvbnRpbnVlO1xuICAgICAgICAgICAgYWxsW2ldLk1XYmVmb3JlTGVhdmVMb2NrZXIgPSB0cnVlO1xuICAgICAgICAgICAgdmFyIGxpbmtzID0gYWxsW2ldLnF1ZXJ5U2VsZWN0b3JBbGwoJ2EnKSwgbGwgPSBsaW5rcy5sZW5ndGgsIGxpID0gMDtcbiAgICAgICAgICAgIGZvciAoOyBsaSA8IGxsOyBsaSsrKSB7XG4gICAgICAgICAgICAgICAgbXcuJChsaW5rc1tsaV0pLmJpbmQoJ21vdXNldXAnLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgICAgICAgICBpZiAobXcuYXNrdXNlcnRvc3RheSA9PT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuXG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfVxuICAgIH1cbn07XG5cblxubXcuY29udGFjdEZvcm0gPSBmdW5jdGlvbiAoKSB7XG4gICAgbXcuZGlhbG9nSWZyYW1lKHtcbiAgICAgICAgdXJsOiAnaHR0cHM6Ly9taWNyb3dlYmVyLmNvbS9jb250YWN0LWZyYW1lLycsXG4gICAgICAgIG92ZXJsYXk6IHRydWUsXG4gICAgICAgIGhlaWdodDogNjAwXG4gICAgfSlcbn07XG5cblxuJChtd2QpLnJlYWR5KGZ1bmN0aW9uICgpIHtcblxuICAgIG13LiQobXdkLmJvZHkpLm9uKCdrZXlkb3duJywgZnVuY3Rpb24gKGUpIHtcbiAgICAgICAgaWYgKG13LmV2ZW50LmtleShlLCA4KSAmJiAoZS50YXJnZXQubm9kZU5hbWUgPT09ICdESVYnIHx8IGUudGFyZ2V0ID09PSBtd2QuYm9keSkpIHtcbiAgICAgICAgICAgIGlmICghZS50YXJnZXQuaXNDb250ZW50RWRpdGFibGUpIHtcbiAgICAgICAgICAgICAgICBtdy5ldmVudC5jYW5jZWwoZSk7XG4gICAgICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgfSk7XG5cbiAgICBtdy5hZG1pbi5iZWZvcmVMZWF2ZUxvY2tlcigpO1xuXG4gICAgbXcuJChkb2N1bWVudC5ib2R5KS5vbignY2xpY2snLCAnW2RhdGEtaHJlZl0nLCBmdW5jdGlvbihlKXtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICBlLnN0b3BQcm9wYWdhdGlvbigpO1xuICAgICAgICBsb2NhdGlvbi5ocmVmID0gJCh0aGlzKS5hdHRyKCdkYXRhLWhyZWYnKTtcbiAgICB9KTtcbn0pO1xuXG4kKG13dykub24oJ2xvYWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgbXcub24ubW9kdWxlUmVsb2FkKCdwYWdlc190cmVlX3Rvb2xiYXInLCBmdW5jdGlvbiAoKSB7XG5cbiAgICB9KTtcblxuXG5cbiAgICBpZiAobXdkLmdldEVsZW1lbnRCeUlkKCdtYWluLWJhci11c2VyLW1lbnUtbGluaycpICE9PSBudWxsKSB7XG5cbiAgICAgICAgbXcuJChkb2N1bWVudC5ib2R5KS5vbignY2xpY2snLCBmdW5jdGlvbiAoZSkge1xuICAgICAgICAgICAgaWYgKGUudGFyZ2V0ICE9PSBtd2QuZ2V0RWxlbWVudEJ5SWQoJ21haW4tYmFyLXVzZXItbWVudS1saW5rJykgJiYgZS50YXJnZXQucGFyZW50Tm9kZSAhPT0gbXdkLmdldEVsZW1lbnRCeUlkKCdtYWluLWJhci11c2VyLW1lbnUtbGluaycpKSB7XG4gICAgICAgICAgICAgICAgbXcuJCgnI21haW4tYmFyLXVzZXItdGlwJykucmVtb3ZlQ2xhc3MoJ21haW4tYmFyLXVzZXItdGlwLWFjdGl2ZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZWxzZSB7XG5cbiAgICAgICAgICAgICAgICBtdy4kKCcjbWFpbi1iYXItdXNlci10aXAnKS50b2dnbGVDbGFzcygnbWFpbi1iYXItdXNlci10aXAtYWN0aXZlJyk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH1cblxuICAgIG13Lm9uKCdhZG1pblNhdmVTdGFydCcsIGZ1bmN0aW9uICgpIHtcbiAgICAgICAgdmFyIGJ0biA9IG13ZC5xdWVyeVNlbGVjdG9yKCcjY29udGVudC10aXRsZS1maWVsZC1idXR0b25zIC5idG4tc2F2ZSBzcGFuJyk7XG4gICAgICAgIGJ0bi5pbm5lckhUTUwgPSBtdy5tc2cuc2F2aW5nICsgJy4uLic7XG4gICAgfSk7XG4gICAgbXcuJCh3aW5kb3cpLm9uKCdhZG1pblNhdmVFbmQnLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgIHZhciBidG4gPSBtd2QucXVlcnlTZWxlY3RvcignI2NvbnRlbnQtdGl0bGUtZmllbGQtYnV0dG9ucyAuYnRuLXNhdmUgc3BhbicpO1xuICAgICAgICBidG4uaW5uZXJIVE1MID0gbXcubXNnLnNhdmU7XG4gICAgfSk7XG5cbiAgICBtdy4kKFwiLmRyLWl0ZW0tdGFibGUgPiB0YWJsZVwiKS5jbGljayhmdW5jdGlvbigpe1xuICAgICAgICBtdy4kKHRoaXMpLnRvZ2dsZUNsYXNzKCdhY3RpdmUnKS5uZXh0KCkuc3RvcCgpLnNsaWRlVG9nZ2xlKCkucGFyZW50cygnLmRyLWl0ZW0nKS50b2dnbGVDbGFzcygnYWN0aXZlJylcbiAgICB9KTtcblxufSk7XG5cblxuUVRBQlNBcnJvdyA9IGZ1bmN0aW9uIChlbCkge1xuICAgIGVsID0gbXcuJChlbCk7XG4gICAgaWYgKGVsID09IG51bGwpIHtcbiAgICAgICAgcmV0dXJuO1xuICAgIH1cbiAgICBpZiAoIWVsLmxlbmd0aCkge1xuICAgICAgICByZXR1cm47XG4gICAgfVxuICAgIHZhciBsZWZ0ID0gZWwub2Zmc2V0KCkubGVmdCAtIG13LiQobXdkLmdldEVsZW1lbnRCeUlkKCdxdWljay1hZGQtcG9zdC1vcHRpb25zJykpLm9mZnNldCgpLmxlZnQgKyAoZWxbMF0ub2Zmc2V0V2lkdGggLyAyKSAtIDU7XG4gICAgbXcuJCgnI3F1aWNrLWFkZC1wb3N0LW9wdGlvbnMtaXRlbXMtaG9sZGVyIC5tdy10b29sdGlwLWFycm93JykuY3NzKHtsZWZ0OiBsZWZ0fSk7XG59O1xuXG4iLCJtdy5jb250ZW50ID0gbXcuY29udGVudCB8fCB7XG5cbiAgICBkZWxldGVDb250ZW50OiBmdW5jdGlvbiAoaWQsIGNhbGxiYWNrKSB7XG4gICAgICAgIG13LnRvb2xzLmNvbmZpcm0obXcubXNnLmRlbCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyBcImNvbnRlbnQvZGVsZXRlXCIsIHtpZDogaWR9LCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgIGlmIChjYWxsYmFjaykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGRhdGEsIGRhdGEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uc3VjY2VzcygnQ29udGVudCBkZWxldGVkJyk7XG4gICAgICAgICAgICB9KTtcbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBkZWxldGVDYXRlZ29yeTogZnVuY3Rpb24gKGlkLCBjYWxsYmFjaykge1xuICAgICAgICBtdy50b29scy5jb25maXJtKCdBcmUgeW91IHN1cmUgeW91IHdhbnQgdG8gZGVsZXRlIHRoaXM/JywgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyBcImNhdGVnb3J5L2RlbGV0ZVwiLCB7aWQ6IGlkfSwgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICBtdy5ub3RpZmljYXRpb24uc3VjY2VzcygnQ2F0ZWdvcnkgZGVsZXRlZCcpO1xuICAgICAgICAgICAgICAgIGlmIChjYWxsYmFjaykge1xuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGRhdGEsIGRhdGEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBtdy5yZWxvYWRfbW9kdWxlX2V2ZXJ5d2hlcmUoJ2NvbnRlbnQvbWFuYWdlcicpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgcHVibGlzaDogZnVuY3Rpb24gKCRpZCkge1xuICAgICAgICB2YXIgbWFzdGVyID0ge307XG4gICAgICAgIG1hc3Rlci5pZCA9ICRpZDtcbiAgICAgICAgbXcuJChtd2QuYm9keSkuYWRkQ2xhc3MoXCJsb2FkaW5nXCIpO1xuICAgICAgICBtdy5kcmFnLnNhdmUoKTtcbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6ICdQT1NUJyxcbiAgICAgICAgICAgIHVybDogbXcuc2V0dGluZ3Muc2l0ZV91cmwgKyAnYXBpL2NvbnRlbnQvc2V0X3B1Ymxpc2hlZCcsXG4gICAgICAgICAgICBkYXRhOiBtYXN0ZXIsXG4gICAgICAgICAgICBkYXRhdHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICBhc3luYzogdHJ1ZSxcbiAgICAgICAgICAgIGJlZm9yZVNlbmQ6IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoXCJsb2FkaW5nXCIpO1xuICAgICAgICAgICAgICAgICQoJy5tdy1zZXQtY29udGVudC1wdWJsaXNoJykuaGlkZSgpO1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy1zZXQtY29udGVudC11bnB1Ymxpc2gnKS5mYWRlSW4oKTtcbiAgICAgICAgICAgICAgICBtdy5hc2t1c2VydG9zdGF5ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLnN1Y2Nlc3MoXCJDb250ZW50IGlzIFB1Ymxpc2hlZC5cIik7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgZXJyb3I6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5yZW1vdmVDbGFzcyhcImxvYWRpbmdcIik7XG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgY29tcGxldGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICBtdy4kKG13ZC5ib2R5KS5yZW1vdmVDbGFzcyhcImxvYWRpbmdcIik7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgdW5wdWJsaXNoOiBmdW5jdGlvbiAoJGlkKSB7XG4gICAgICAgIHZhciBtYXN0ZXIgPSB7fTtcbiAgICAgICAgbWFzdGVyLmlkID0gJGlkO1xuICAgICAgICBtdy4kKG13ZC5ib2R5KS5hZGRDbGFzcyhcImxvYWRpbmdcIik7XG5cbiAgICAgICAgbXcuZHJhZy5zYXZlKCk7XG4gICAgICAgICQuYWpheCh7XG4gICAgICAgICAgICB0eXBlOiAnUE9TVCcsXG4gICAgICAgICAgICB1cmw6IG13LnNldHRpbmdzLnNpdGVfdXJsICsgJ2FwaS9jb250ZW50L3NldF91bnB1Ymxpc2hlZCcsXG4gICAgICAgICAgICBkYXRhOiBtYXN0ZXIsXG4gICAgICAgICAgICBkYXRhdHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICBhc3luYzogdHJ1ZSxcbiAgICAgICAgICAgIGJlZm9yZVNlbmQ6IGZ1bmN0aW9uICgpIHtcblxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoXCJsb2FkaW5nXCIpO1xuICAgICAgICAgICAgICAgIG13LiQoJy5tdy1zZXQtY29udGVudC11bnB1Ymxpc2gnKS5oaWRlKCk7XG4gICAgICAgICAgICAgICAgbXcuJCgnLm13LXNldC1jb250ZW50LXB1Ymxpc2gnKS5mYWRlSW4oKTtcbiAgICAgICAgICAgICAgICBtdy5hc2t1c2VydG9zdGF5ID0gZmFsc2U7XG4gICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLndhcm5pbmcoXCJDb250ZW50IGlzIFVucHVibGlzaGVkLlwiKTtcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBlcnJvcjogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBjb21wbGV0ZTogZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbiAgICB9LFxuICAgIHNhdmU6IGZ1bmN0aW9uIChkYXRhLCBlKSB7XG4gICAgICAgIHZhciBtYXN0ZXIgPSB7fTtcbiAgICAgICAgdmFyIGNhbGMgPSB7fTtcbiAgICAgICAgdmFyIGUgPSBlIHx8IHt9O1xuICAgICAgICAvLyAgIGRhdGEuc3VidHlwZSA9PT0gJ2NhdGVnb3J5J1xuICAgICAgICBpZiAoZGF0YS5jb250ZW50ID09IFwiXCIgfHwgdHlwZW9mIGRhdGEuY29udGVudCA9PT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIC8vIGNhbGMuY29udGVudCA9IGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGVsc2Uge1xuICAgICAgICAgICAgdmFyIGRvYyA9IG13LnRvb2xzLnBhcnNlSHRtbChkYXRhLmNvbnRlbnQpO1xuICAgICAgICAgICAgdmFyIGFsbCA9IGRvYy5xdWVyeVNlbGVjdG9yQWxsKCdbY29udGVudGVkaXRhYmxlXScpLCBsID0gYWxsLmxlbmd0aCwgaSA9IDA7XG4gICAgICAgICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAgICAgICAgIGFsbFtpXS5yZW1vdmVBdHRyaWJ1dGUoJ2NvbnRlbnRlZGl0YWJsZScpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgZGF0YS5jb250ZW50ID0gZG9jLmJvZHkuaW5uZXJIVE1MO1xuICAgICAgICB9XG5cbiAgICAgICAgaWYgKCFkYXRhLnRpdGxlKSB7XG4gICAgICAgICAgICBjYWxjLnRpdGxlID0gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFtdy50b29scy5pc0VtcHR5T2JqZWN0KGNhbGMpKSB7XG4gICAgICAgICAgICBpZiAodHlwZW9mIGUub25FcnJvciA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgIGUub25FcnJvci5jYWxsKGNhbGMpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIGlmICghZGF0YS5jb250ZW50X3R5cGUpIHtcbiAgICAgICAgICAgIGRhdGEuY29udGVudF90eXBlID0gXCJwb3N0XCI7XG4gICAgICAgIH1cbiAgICAgICAgaWYgKCFkYXRhLmlkKSB7XG4gICAgICAgICAgICBkYXRhLmlkID0gMDtcbiAgICAgICAgfVxuICAgICAgICBtYXN0ZXIudGl0bGUgPSBkYXRhLnRpdGxlO1xuICAgICAgICBtYXN0ZXIuY29udGVudCA9IGRhdGEuY29udGVudDtcbiAgICAgICAgbXcuJChtd2QuYm9keSkuYWRkQ2xhc3MoXCJsb2FkaW5nXCIpO1xuICAgICAgICBtdy50cmlnZ2VyKCdhZG1pblNhdmVTdGFydCcpO1xuICAgICAgICAkLmFqYXgoe1xuICAgICAgICAgICAgdHlwZTogJ1BPU1QnLFxuICAgICAgICAgICAgdXJsOiBlLnVybCB8fCAobXcuc2V0dGluZ3MuYXBpX3VybCArICdzYXZlX2NvbnRlbnRfYWRtaW4nKSxcbiAgICAgICAgICAgIGRhdGE6IGRhdGEsXG4gICAgICAgICAgICBkYXRhdHlwZTogXCJqc29uXCIsXG4gICAgICAgICAgICBhc3luYzogdHJ1ZSxcbiAgICAgICAgICAgIHN1Y2Nlc3M6IGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgaWYoZGF0YS5kYXRhKSB7XG4gICAgICAgICAgICAgICAgICAgIGRhdGEgPSBkYXRhLmRhdGE7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGRhdGEgPT09ICdvYmplY3QnICYmIHR5cGVvZiBkYXRhLmVycm9yICE9ICd1bmRlZmluZWQnKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZS5vbkVycm9yID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBlLm9uRXJyb3IuY2FsbChkYXRhKTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBlLm9uU3VjY2VzcyA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgZS5vblN1Y2Nlc3MuY2FsbChkYXRhKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ2FkbWluU2F2ZUVuZCcpO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGVycm9yOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgIG13LiQobXdkLmJvZHkpLnJlbW92ZUNsYXNzKFwibG9hZGluZ1wiKTtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGUub25FcnJvciA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBlLm9uRXJyb3IuY2FsbChkYXRhLmRhdGEgfHwgZGF0YSk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGNvbXBsZXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgbXcuJChtd2QuYm9keSkucmVtb3ZlQ2xhc3MoXCJsb2FkaW5nXCIpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KTtcbiAgICB9XG59O1xuXG5cbm13LnBvc3QgPSBtdy5wb3N0IHx8IHtcbiAgICBkZWw6IGZ1bmN0aW9uIChhLCBjYWxsYmFjaykge1xuICAgICAgICB2YXIgYXJyID0gJC5pc0FycmF5KGEpID8gYSA6IFthXTtcbiAgICAgICAgdmFyIG9iaiA9IHtpZHM6IGFycn1cbiAgICAgICAgJC5wb3N0KG13LnNldHRpbmdzLmFwaV91cmwgKyBcImNvbnRlbnQvZGVsZXRlXCIsIG9iaiwgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJyA/IGNhbGxiYWNrLmNhbGwoZGF0YSkgOiAnJztcbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBwdWJsaXNoOiBmdW5jdGlvbiAoaWQsIGMpIHtcbiAgICAgICAgdmFyIG9iaiA9IHtcbiAgICAgICAgICAgIGlkOiBpZFxuICAgICAgICB9XG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ2NvbnRlbnQvc2V0X3B1Ymxpc2hlZCcsIG9iaiwgZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgIGlmICh0eXBlb2YgYyA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgIGMuY2FsbChpZCwgZGF0YSk7XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgdW5wdWJsaXNoOiBmdW5jdGlvbiAoaWQsIGMpIHtcbiAgICAgICAgdmFyIG9iaiA9IHtcbiAgICAgICAgICAgIGlkOiBpZFxuICAgICAgICB9XG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgJ2NvbnRlbnQvc2V0X3VucHVibGlzaGVkJywgb2JqLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgaWYgKHR5cGVvZiBjID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgYy5jYWxsKGlkLCBkYXRhKTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfSxcbiAgICBzZXQ6IGZ1bmN0aW9uIChpZCwgc3RhdGUsIGUpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBlICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgZS5zdG9wUHJvcGFnYXRpb24oKTtcbiAgICAgICAgfVxuICAgICAgICBpZiAoc3RhdGUgPT0gJ3VucHVibGlzaCcpIHtcbiAgICAgICAgICAgIG13LnBvc3QudW5wdWJsaXNoKGlkLCBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgICAgIG13Lm5vdGlmaWNhdGlvbi53YXJuaW5nKG13Lm1zZy5jb250ZW50dW5wdWJsaXNoZWQpO1xuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICAgICAgZWxzZSBpZiAoc3RhdGUgPT0gJ3B1Ymxpc2gnKSB7XG4gICAgICAgICAgICBtdy5wb3N0LnB1Ymxpc2goaWQsIGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLnN1Y2Nlc3MobXcubXNnLmNvbnRlbnRwdWJsaXNoZWQpO1xuICAgICAgICAgICAgICAgIG13LiQoXCIubWFuYWdlLXBvc3QtaXRlbS1cIiArIGlkKS5yZW1vdmVDbGFzcyhcImNvbnRlbnQtdW5wdWJsaXNoZWRcIikuZmluZChcIi5wb3N0LXVuLXB1Ymxpc2hcIikucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBlICE9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICBtdy4kKGUudGFyZ2V0LnBhcmVudE5vZGUpLnJlbW92ZUNsYXNzKFwiY29udGVudC11bnB1Ymxpc2hlZFwiKTtcbiAgICAgICAgICAgICAgICAgICAgbXcuJChlLnRhcmdldCkucmVtb3ZlKCk7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG4gICAgICAgIH1cbiAgICB9XG59XG4iLCJtdy5jdXN0b21fZmllbGRzID0ge1xyXG4gICAgc2V0dGluZ3M6IHtcclxuICAgICAgICBpZDogMFxyXG4gICAgfSxcclxuICAgIHNhdmV1cmw6IG13LnNldHRpbmdzLmFwaV91cmwgKyAnZmllbGRzL3NhdmUnLFxyXG4gICAgY3JlYXRlOiBmdW5jdGlvbiAob2JqLCBjYWxsYmFjaywgZXJyb3IpIHtcclxuICAgICAgICBvYmogPSAkLmV4dGVuZCh7fSwgdGhpcy5zZXR0aW5ncywgb2JqKTtcclxuICAgICAgICBvYmouaWQgPSAwO1xyXG4gICAgICAgIHRoaXMuZWRpdChvYmosIGNhbGxiYWNrLCBlcnJvcik7XHJcbiAgICB9LFxyXG4gICAgZWRpdDogZnVuY3Rpb24gKG9iaiwgY2FsbGJhY2ssIGVycm9yKSB7XHJcbiAgICAgICAgb2JqID0gJC5leHRlbmQoe30sIHRoaXMuc2V0dGluZ3MsIG9iaik7XHJcblxyXG4gICAgICAgICQucG9zdChtdy5jdXN0b21fZmllbGRzLnNhdmV1cmwsIG9iaiwgZnVuY3Rpb24gKGRhdGEpIHtcclxuICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgaWYgKCEhZGF0YS5lcnJvcikge1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgZXJyb3IgPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgZXJyb3IuY2FsbChkYXRhLmVycm9yKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKGRhdGEpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG5cclxuICAgICAgICAgICAgICAgIG13LmN1c3RvbV9maWVsZHMuYWZ0ZXJfc2F2ZSgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSlcclxuICAgICAgICAgICAgLmZhaWwoZnVuY3Rpb24gKGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93bikge1xyXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBlcnJvciA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGVycm9yLmNhbGwodGV4dFN0YXR1cyk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG4gICAgfSxcclxuICAgIHNvcnQ6IGZ1bmN0aW9uIChncm91cCkge1xyXG4gICAgICAgIGdyb3VwID0gbXdkLmdldEVsZW1lbnRCeUlkKGdyb3VwKTtcclxuICAgICAgICBpZiAoZ3JvdXAgPT0gbnVsbCkge1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGlmIChncm91cC5xdWVyeVNlbGVjdG9yQWxsKCcubXctY3VzdG9tLWZpZWxkLWZvcm0tY29udHJvbHMnKS5sZW5ndGggPiAwKSB7XHJcbiAgICAgICAgICAgIG13LiQoZ3JvdXApLnNvcnRhYmxlKHtcclxuICAgICAgICAgICAgICAgIGhhbmRsZTogJy5jdXN0b20tZmllbGRzLWhhbmRsZS1maWVsZCcsXHJcbiAgICAgICAgICAgICAgICBwbGFjZWhvbGRlcjogJ2N1c3RvbS1maWVsZHMtcGxhY2Vob2xkZXInLFxyXG4gICAgICAgICAgICAgICAgLy9jb250YWlubWVudDogXCJwYXJlbnRcIixcclxuICAgICAgICAgICAgICAgIGF4aXM6ICd5JyxcclxuICAgICAgICAgICAgICAgIGl0ZW1zOiBcIi5tdy1jdXN0b20tZmllbGQtZm9ybS1jb250cm9sc1wiLFxyXG4gICAgICAgICAgICAgICAgc3RhcnQ6IGZ1bmN0aW9uIChhLCB1aSkge1xyXG4gICAgICAgICAgICAgICAgICAgIG13LiQodWkucGxhY2Vob2xkZXIpLmhlaWdodCgkKHVpLml0ZW0pLm91dGVySGVpZ2h0KCkpXHJcbiAgICAgICAgICAgICAgICB9LFxyXG4gICAgICAgICAgICAgICAgLy9zY3JvbGw6ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICB1cGRhdGU6IGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgcGFyID0gbXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3MoZ3JvdXAsICdtdy1hZG1pbi1jdXN0b20tZmllbGQtZWRpdC1pdGVtLXdyYXBwZXInKTtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoISFwYXIpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgbXcuY3VzdG9tX2ZpZWxkcy5zYXZlKHBhcik7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9KTtcclxuICAgICAgICB9XHJcbiAgICB9LFxyXG4gICAgcmVtb3ZlOiBmdW5jdGlvbiAoaWQsIGNhbGxiYWNrLCBlcnIpIHtcclxuICAgICAgICB2YXIgb2JqID0ge1xyXG4gICAgICAgICAgICBpZDogaWRcclxuICAgICAgICB9O1xyXG4gICAgICAgICQucG9zdChtdy5zZXR0aW5ncy5hcGlfdXJsICsgXCJmaWVsZHMvZGVsZXRlXCIsIG9iaiwgZnVuY3Rpb24gKGRhdGEpIHtcclxuICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbChkYXRhKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICBtdy5jdXN0b21fZmllbGRzLmFmdGVyX3NhdmUoKTtcclxuICAgICAgICB9KS5mYWlsKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgaWYgKHR5cGVvZiBlcnIgPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgIGVyci5jYWxsKCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9KTtcclxuICAgIH0sXHJcblxyXG4gICAgc2F2ZTogZnVuY3Rpb24gKGlkLCBjYWxsYmFjaykge1xyXG4gICAgICAgIHJldHVybiB0aGlzLnNhdmVfZm9ybShpZCwgY2FsbGJhY2spO1xyXG4gICAgfSxcclxuICAgIHNhdmVfZm9ybTogZnVuY3Rpb24gKGlkLCBjYWxsYmFjaykge1xyXG4gICAgICAgIHZhciBvYmogPSBtdy5jdXN0b21fZmllbGRzLnNlcmlhbGl6ZShpZCk7XHJcbiAgICAgICAgJC5wb3N0KG13LmN1c3RvbV9maWVsZHMuc2F2ZXVybCwgb2JqLCBmdW5jdGlvbiAoZGF0YSkge1xyXG4gICAgICAgICAgICBpZiAoZGF0YS5lcnJvciAhPSB1bmRlZmluZWQpIHtcclxuICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgdmFyICRjZmFkbV9yZWxvYWQgPSBmYWxzZTtcclxuICAgICAgICAgICAgaWYgKG9iai5jZl9pZCA9PT0gdW5kZWZpbmVkKSB7XHJcbiAgICAgICAgICAgICAgICAvLyAgICAgIG13LnJlbG9hZF9tb2R1bGUoJy5lZGl0IFtkYXRhLXBhcmVudC1tb2R1bGU9XCJjdXN0b21fZmllbGRzXCJdJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgbXcuJChcIi5tdy1saXZlLWVkaXQgW2RhdGEtdHlwZT0nY3VzdG9tX2ZpZWxkcyddXCIpLmVhY2goZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgaWYgKCFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKHRoaXMsICdtd19tb2RhbCcpICYmICFtdy50b29scy5oYXNQYXJlbnRzV2l0aENsYXNzKHRoaXMsICdpc19hZG1pbicpKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLy9tdy5yZWxvYWRfbW9kdWxlKHRoaXMpO1xyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgJGNmYWRtX3JlbG9hZCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0pO1xyXG5cclxuICAgICAgICAgICAgbXcucmVsb2FkX21vZHVsZV9wYXJlbnQoJ2N1c3RvbV9maWVsZHMnKTtcclxuICAgICAgICAgICAgaWYgKHR5cGVvZiBsb2FkX2lmcmFtZV9lZGl0b3IgPT09ICdmdW5jdGlvbicpIHtcclxuICAgICAgICAgICAgICAgIGxvYWRfaWZyYW1lX2VkaXRvcigpO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICBtdy5yZWxvYWRfbW9kdWxlKCcjbXctYWRtaW4tY3VzdG9tLWZpZWxkLWVkaXQtaXRlbS1wcmV2aWV3LScgKyBkYXRhKTtcclxuXHJcbiAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGVfZXZlcnl3aGVyZSgnY3VzdG9tX2ZpZWxkcy9saXN0JywgZnVuY3Rpb24od2luKXtcclxuICAgICAgICAgICAgICAgIGlmKHdpbiAhPT0gd2luZG93KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKGNhbGxiYWNrKSBjYWxsYmFjay5jYWxsKGRhdGEpO1xyXG4gICAgICAgICAgICAgICAgICAgIG13LnRyaWdnZXIoJ2N1c3RvbUZpZWxkU2F2ZWQnLCBbaWQsIGRhdGFdKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcblxyXG4gICAgICAgICAgICBtdy5jdXN0b21fZmllbGRzLmFmdGVyX3NhdmUoKTtcclxuICAgICAgICB9KTtcclxuICAgIH0sXHJcblxyXG4gICAgYWZ0ZXJfc2F2ZTogZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIC8vICBtdy5yZWxvYWRfbW9kdWxlKCdjdXN0b21fZmllbGRzL2xpc3QnKTtcclxuXHJcbiAgICAgICAgbXcucmVsb2FkX21vZHVsZSgnY3VzdG9tX2ZpZWxkcycpO1xyXG4gICAgICAgIG13LnJlbG9hZF9tb2R1bGVfcGFyZW50KCdjdXN0b21fZmllbGRzL2xpc3QnKTtcclxuICAgICAgICBtdy5yZWxvYWRfbW9kdWxlX3BhcmVudCgnY3VzdG9tX2ZpZWxkcycpO1xyXG5cclxuXHJcbiAgICAgICAgbXcudHJpZ2dlcihcImN1c3RvbV9maWVsZHMuc2F2ZVwiKTtcclxuXHJcbiAgICB9LFxyXG5cclxuICAgIGF1dG9TYXZlT25Xcml0aW5nOiBmdW5jdGlvbiAoZWwsIGlkKSB7XHJcbiAgICAgICAgcmV0dXJuIGZhbHNlO1xyXG4gICAgICAgIC8qbXcub24uc3RvcFdyaXRpbmcoZWwsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgdGhpcy5zYXZlX2Zvcm0oaWQsIGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgICAgIGlmICh0eXBlb2YgX19zb3J0X2ZpZWxkcyA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIF9fc29ydF9maWVsZHMoKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfSk7XHJcbiAgICAgICAgfSk7Ki9cclxuICAgIH0sXHJcblxyXG4gICAgYWRkOiBmdW5jdGlvbiAoZWwpIHtcclxuICAgICAgICB2YXIgcGFyZW50ID0gbXcuJChtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyhlbCwgJ213LWN1c3RvbS1maWVsZC1mb3JtLWNvbnRyb2xzJykpO1xyXG4gICAgICAgIHZhciBjbG9uZSA9IHBhcmVudC5jbG9uZSh0cnVlKTtcclxuICAgICAgICBwYXJlbnQuYWZ0ZXIoY2xvbmUpO1xyXG4gICAgICAgIGNsb25lLmZpbmQoXCJpbnB1dFwiKS52YWwoXCJcIikuZm9jdXMoKTtcclxuICAgIH0sXHJcbiAgICBzZXJpYWxpemU6IGZ1bmN0aW9uIChpZCkge1xyXG4gICAgICAgIHZhciBlbCA9IG13LiQoaWQpLFxyXG4gICAgICAgIGZpZWxkcyA9XHJcbiAgICAgICAgICAgIFwiaW5wdXRbdHlwZT0ndGV4dCddLCBcIiArXHJcbiAgICAgICAgICAgIFwiaW5wdXRbdHlwZT0nZW1haWwnXSwgaW5wdXRbdHlwZT0nbnVtYmVyJ10sIGlucHV0W3R5cGU9J3Bhc3N3b3JkJ10sIGlucHV0W3R5cGU9J2hpZGRlbiddLCBcIiArXHJcbiAgICAgICAgICAgIFwidGV4dGFyZWEsIHNlbGVjdCwgaW5wdXRbdHlwZT0nY2hlY2tib3gnXTpjaGVja2VkLCBpbnB1dFt0eXBlPSdyYWRpbyddOmNoZWNrZWRcIjtcclxuICAgICAgICB2YXIgZGF0YSA9IHt9O1xyXG4gICAgICAgIGRhdGEub3B0aW9ucyA9IHt9O1xyXG4gICAgICAgIG13LiQoZmllbGRzLCBlbCkubm90KCc6ZGlzYWJsZWQnKS5lYWNoKGZ1bmN0aW9uICgpIHtcclxuICAgICAgICAgICAgdmFyIGVsID0gdGhpcywgX2VsID0gbXcuJChlbCk7XHJcbiAgICAgICAgICAgIHZhciB2YWwgPSBfZWwudmFsKCk7XHJcbiAgICAgICAgICAgIHZhciBuYW1lID0gZWwubmFtZTtcclxuICAgICAgICAgICAgdmFyIG5vdEFycmF5U2VsZWN0ID0gdGhpcy5ub2RlTmFtZSA9PT0gJ1NFTEVDVCcgJiYgdGhpcy5tdWx0aXBsZSA9PT0gZmFsc2U7XHJcbiAgICAgICAgICAgIHZhciBub3RBcnJheURlZmF1bHQgPSB0aGlzLm5hbWUgIT09ICdvcHRpb25zW2ZpbGVfdHlwZXNdJztcclxuICAgICAgICAgICAgdmFyIG5vdEFycmF5ID0gbm90QXJyYXlTZWxlY3QgfHwgbm90QXJyYXlEZWZhdWx0O1xyXG5cclxuICAgICAgICAgICAgaWYgKG5hbWUuY29udGFpbnMoXCJbXCIpKSB7XHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKG5hbWUuY29udGFpbnMoJ1tdJykpIHtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgX25hbWUgPSBuYW1lLnJlcGxhY2UoL1tcXFtcXF0nXSsvZywgJycpO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAobmFtZS5pbmRleE9mKCdvcHRpb24nKSA9PT0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB0cnkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGF0YS5vcHRpb25zLnB1c2godmFsKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICBjYXRjaCAoZSkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGF0YS5vcHRpb25zID0gW3ZhbF07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHRyeSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhW19uYW1lXS5wdXNoKHZhbCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgY2F0Y2ggKGUpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRhdGFbX25hbWVdID0gW3ZhbF07XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAobmFtZS5pbmRleE9mKCdvcHRpb24nKSA9PT0gMCkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBuYW1lID0gbmFtZS5zbGljZShuYW1lLmluZGV4T2YoXCJbXCIpICsgMSwgbmFtZS5pbmRleE9mKFwiXVwiKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmKG5vdEFycmF5KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhLm9wdGlvbnNbbmFtZV0gPSB2YWw7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhLm9wdGlvbnNbbmFtZV0gPSBkYXRhLm9wdGlvbnNbbmFtZV0gfHwgW107XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhLm9wdGlvbnNbbmFtZV0ucHVzaCh2YWwpO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG5cclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBhcnJfbmFtZSA9IG5hbWUuc2xpY2UoMCwgbmFtZS5pbmRleE9mKFwiW1wiKSk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHZhciBrZXkgPSBuYW1lLnNsaWNlKG5hbWUuaW5kZXhPZihcIltcIikgKyAxLCBuYW1lLmluZGV4T2YoXCJdXCIpKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBkYXRhW2Fycl9uYW1lXSA9PT0gJ29iamVjdCcpIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRhdGFbYXJyX25hbWVdW2tleV0gPSBkYXRhW2Fycl9uYW1lXVtrZXldIHx8IFtdO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGF0YVthcnJfbmFtZV1ba2V5XS5wdXNoKHZhbCk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBkYXRhW2Fycl9uYW1lXSA9IHt9O1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZGF0YVthcnJfbmFtZV1ba2V5XSA9IFt2YWxdO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgZGF0YVtuYW1lXSA9IHZhbDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIGlmIChtdy50b29scy5pc0VtcHR5T2JqZWN0KGRhdGEub3B0aW9ucykpIHtcclxuICAgICAgICAgICAgZGF0YS5vcHRpb25zID0gJyc7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICByZXR1cm4gZGF0YTtcclxuICAgIH1cclxuXHJcbn1cclxuIiwibXcudG9vbHMuZWxlbWVudEVkaXQgPSBmdW5jdGlvbiAoZWwsIHRleHRvbmx5LCBjYWxsYmFjaywgZmllbGRDbGFzcykge1xyXG4gICAgaWYgKCFlbCB8fCBlbC5xdWVyeVNlbGVjdG9yKCcubXctbGl2ZS1lZGl0LWlucHV0JykgIT09IG51bGwpIHtcclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcbiAgICB0ZXh0b25seSA9ICh0eXBlb2YgdGV4dG9ubHkgPT09ICd1bmRlZmluZWQnKSA/IHRydWUgOiB0ZXh0b25seTtcclxuICAgIHZhciBpbnB1dCA9IG13ZC5jcmVhdGVFbGVtZW50KCdzcGFuJyk7XHJcbiAgICBpbnB1dC5jbGFzc05hbWUgPSAoZmllbGRDbGFzcyB8fCBcIlwiKSArICcgbXctbGl2ZS1lZGl0LWlucHV0IG13LWxpdmVlZGl0LWZpZWxkJztcclxuICAgIGlucHV0LmNvbnRlbnRFZGl0YWJsZSA9IHRydWU7XHJcbiAgICB2YXIgJGlucHV0ID0gJChpbnB1dCk7XHJcbiAgICBpZiAodGV4dG9ubHkgPT09IHRydWUpIHtcclxuICAgICAgICBpbnB1dC5pbm5lckhUTUwgPSBlbC50ZXh0Q29udGVudDtcclxuICAgICAgICBpbnB1dC5vbmJsdXIgPSBmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgIHZhciB2YWwgPSAkaW5wdXQudGV4dCgpO1xyXG4gICAgICAgICAgICB2YXIgaXNjaGFuZ2VkID0gdHJ1ZTtcclxuICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XHJcbiAgICAgICAgICAgICAgICBtdy4kKGVsKS50ZXh0KHZhbCk7XHJcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nICYmIGlzY2hhbmdlZCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodmFsLCBlbCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0sIDMpO1xyXG4gICAgICAgIH07XHJcbiAgICAgICAgaW5wdXQub25rZXlkb3duID0gZnVuY3Rpb24gKGUpIHtcclxuICAgICAgICAgICAgaWYgKGUua2V5Q29kZSA9PT0gMTMpIHtcclxuICAgICAgICAgICAgICAgIGUucHJldmVudERlZmF1bHQoKTtcclxuICAgICAgICAgICAgICAgIG13LiQoZWwpLnRleHQoJGlucHV0LnRleHQoKSk7XHJcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGNhbGxiYWNrID09PSAnZnVuY3Rpb24nKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY2FsbGJhY2suY2FsbCgkaW5wdXQudGV4dCgpLCBlbCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICBlbHNlIHtcclxuICAgICAgICBpbnB1dC5pbm5lckhUTUwgPSBlbC5pbm5lckhUTUw7XHJcbiAgICAgICAgaW5wdXQub25ibHVyID0gZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICB2YXIgdmFsID0gdGhpcy5pbm5lckhUTUw7XHJcbiAgICAgICAgICAgIHZhciBpc2NoYW5nZWQgPSB0aGlzLmNoYW5nZWQgPT09IHRydWU7XHJcbiAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgICAgICAgICAgZWwuaW5uZXJIVE1MID0gdmFsO1xyXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJyAmJiBpc2NoYW5nZWQpIHtcclxuICAgICAgICAgICAgICAgICAgICBjYWxsYmFjay5jYWxsKHZhbCwgZWwpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9LCAzKVxyXG4gICAgICAgIH1cclxuICAgICAgICBpbnB1dC5vbmtleWRvd24gPSBmdW5jdGlvbiAoZSkge1xyXG4gICAgICAgICAgICBpZiAoZS5rZXlDb2RlID09PSAxMykge1xyXG4gICAgICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xyXG4gICAgICAgICAgICAgICAgdmFyIHZhbCA9IHRoaXMuaW5uZXJIVE1MO1xyXG4gICAgICAgICAgICAgICAgZWwuaW5uZXJIVE1MID0gdmFsO1xyXG4gICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xyXG4gICAgICAgICAgICAgICAgICAgIGNhbGxiYWNrLmNhbGwodmFsLCBlbCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICByZXR1cm4gZmFsc2U7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICBtdy4kKGVsKS5lbXB0eSgpLmFwcGVuZChpbnB1dCk7XHJcbiAgICAkaW5wdXQuZm9jdXMoKTtcclxuICAgIGlucHV0LmNoYW5nZWQgPSBmYWxzZTtcclxuICAgICRpbnB1dC5jaGFuZ2UoZnVuY3Rpb24gKCkge1xyXG4gICAgICAgIHRoaXMuY2hhbmdlZCA9IHRydWU7XHJcbiAgICB9KTtcclxuICAgIHJldHVybiBpbnB1dDtcclxufSIsIi8vIEphdmFTY3JpcHQgRG9jdW1lbnRcblxuLyoqXG4gKlxuICogT3B0aW9ucyBBUElcbiAqXG4gKiBAcGFja2FnZSAgICAgICAganNcbiAqIEBzdWJwYWNrYWdlICAgICAgICBvcHRpb25zXG4gKiBAc2luY2UgICAgICAgIFZlcnNpb24gMC41NjdcbiAqL1xuXG4vLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cblxuLyoqXG4gKiBtdy5vcHRpb25zXG4gKlxuICogIG13Lm9wdGlvbnMgb2JqZWN0XG4gKlxuICogQHBhY2thZ2UgICAgICAgIGpzXG4gKiBAc3VicGFja2FnZSAgICBvcHRpb25zXG4gKiBAY2F0ZWdvcnkgICAgb3B0aW9ucyBpbnRlcm5hbCBhcGlcbiAqIEB2ZXJzaW9uIDEuMFxuICovXG5tdy5vcHRpb25zID0ge1xuICAgIHNhdmVPcHRpb246IGZ1bmN0aW9uIChvLCBjLCBlcnIpIHtcbiAgICAgICAgaWYgKHR5cGVvZiBvICE9PSAnb2JqZWN0Jykge1xuICAgICAgICAgICAgcmV0dXJuIGZhbHNlO1xuICAgICAgICB9XG4gICAgICAgIHZhciBncm91cCA9IG8uZ3JvdXAgfHwgby5vcHRpb25fZ3JvdXAsXG4gICAgICAgICAgICBrZXkgPSBvLmtleSB8fCBvLm9wdGlvbl9rZXksXG4gICAgICAgICAgICB2YWx1ZSA9IHR5cGVvZiBvLnZhbHVlICE9PSAndW5kZWZpbmVkJyA/IG8udmFsdWUgOiBvLm9wdGlvbl92YWx1ZTtcblxuICAgICAgICBpZiAoIWdyb3VwIHx8ICFrZXkgfHwgKHR5cGVvZiB2YWx1ZSA9PT0gJ3VuZGVmaW5lZCcpKSB7XG4gICAgICAgICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgIH1cbiAgICAgICAgdmFyIGRhdGEgPSB7XG4gICAgICAgICAgICBvcHRpb25fZ3JvdXA6IGdyb3VwLFxuICAgICAgICAgICAgb3B0aW9uX2tleToga2V5LFxuICAgICAgICAgICAgb3B0aW9uX3ZhbHVlOiB2YWx1ZVxuICAgICAgICB9O1xuICAgICAgICByZXR1cm4gJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5zaXRlX3VybCArIFwiYXBpL3NhdmVfb3B0aW9uXCIsXG4gICAgICAgICAgICBkYXRhOiBkYXRhLFxuICAgICAgICAgICAgc3VjY2VzczogZnVuY3Rpb24gKGEpIHtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGMgPT09ICdmdW5jdGlvbicpIHtcbiAgICAgICAgICAgICAgICAgICAgYy5jYWxsKGEpO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBlcnJvcjogZnVuY3Rpb24gKGEpIHtcbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mIGVyciA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICBlcnIuY2FsbChhKTtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG4gICAgICAgIH0pO1xuICAgIH0sXG4gICAgc2F2ZTogZnVuY3Rpb24gKGVsLCBjYWxsYmFjaykge1xuXG5cbiAgICAgICAgZWwgPSBtdy4kKGVsKTtcbiAgICAgICAgdmFyIG9nLCBvZzEsIHJlZnJlc2hfbW9kdWxlczExO1xuICAgICAgICBpZiAoIWVsKSB7XG4gICAgICAgICAgICByZXR1cm47XG4gICAgICAgIH1cblxuXG4gICAgICAgIHZhciBvcHRfaWQgPSBlbC5hdHRyKCdkYXRhLWlkJyk7XG5cbiAgICAgICAgb2cxID0gb2cgPSBlbC5hdHRyKCdvcHRpb24tZ3JvdXAnKSB8fCBlbC5hdHRyKCdvcHRpb25fZ3JvdXAnKSB8fCBlbC5hdHRyKCdkYXRhLW9wdGlvbi1ncm91cCcpO1xuXG5cbiAgICAgICAgaWYgKG9nMSA9PSBudWxsIHx8ICh0eXBlb2Yob2cxKSA9PT0gJ3VuZGVmaW5lZCcpIHx8IG9nMSA9PSAnJykge1xuXG4gICAgICAgIH1cbiAgICAgICAgdmFyIG9nX3BhcmVudCA9IG51bGxcbiAgICAgICAgdmFyIG9nX3Rlc3QgPSBtdy50b29scy5maXJzdFBhcmVudFdpdGhDbGFzcyhlbFswXSwgJ21vZHVsZScpO1xuICAgICAgICBpZiAob2dfdGVzdCkge1xuICAgICAgICAgICAgb2dfcGFyZW50ID0gb2dfdGVzdC5pZDtcblxuICAgICAgICAgICAgb2dfcGFyZW50ID0gbXcuJChvZ190ZXN0KS5hdHRyKCdmb3ItbW9kdWxlLWlkJykgfHwgb2dfdGVzdC5pZDtcblxuXG4gICAgICAgIH1cbiAgICAgICAgLy8gcmVmcmVzaF9tb2R1bGVzMTEgPSBvZzEgPSBvZyA9IG9nX3Rlc3QuaWQ7XG5cblxuICAgICAgICB2YXIgcmVmcmVzaF9tb2R1bGVzMTIgPSBlbC5hdHRyKCdkYXRhLXJlbG9hZCcpIHx8IGVsLmF0dHIoJ2RhdGEtcmVmcmVzaCcpO1xuXG4gICAgICAgIHZhciBhbHNvX3JlbG9hZCA9IGVsLmF0dHIoJ2RhdGEtcmVsb2FkJykgfHwgZWwuYXR0cignZGF0YS1hbHNvLXJlbG9hZCcpO1xuXG4gICAgICAgIHZhciBtb2RhbCA9IG13LiQobXcuZGlhbG9nLmdldChlbCkuY29udGFpbmVyKTtcblxuICAgICAgICBpZiAocmVmcmVzaF9tb2R1bGVzMTEgPT0gdW5kZWZpbmVkICYmIG1vZGFsICE9PSB1bmRlZmluZWQpIHtcblxuICAgICAgICAgICAgdmFyIGZvcl9tX2lkID0gbW9kYWwuYXR0cignZGF0YS1zZXR0aW5ncy1mb3ItbW9kdWxlJyk7XG5cbiAgICAgICAgfVxuICAgICAgICBpZiAocmVmcmVzaF9tb2R1bGVzMTEgPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICB2YXIgcmVmcmVzaF9tb2R1bGVzMTEgPSBlbC5hdHRyKCdkYXRhLXJlZnJlc2gnKTtcblxuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGEgPSBbJ2RhdGEtbW9kdWxlLWlkJywgJ2RhdGEtc2V0dGluZ3MtZm9yLW1vZHVsZScsICdvcHRpb24tZ3JvdXAnLCAnZGF0YS1vcHRpb24tZ3JvdXAnLCAnZGF0YS1yZWZyZXNoJ10sXG4gICAgICAgICAgICBpID0gMCwgbCA9IGEubGVuZ3RoO1xuXG5cbiAgICAgICAgdmFyIG1uYW1lID0gbW9kYWwgIT09IHVuZGVmaW5lZCA/IG1vZGFsLmF0dHIoJ2RhdGEtdHlwZScpIDogdW5kZWZpbmVkO1xuXG4gICAgICAgIC8vIGlmICh0eXBlb2YocmVmcmVzaF9tb2R1bGVzMTEpID09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIC8vICAgICBmb3IgKDsgaSA8IGw7IGkrKykge1xuICAgICAgICAvLyAgICAgICAgIHZhciBvZyA9IG9nID09PSB1bmRlZmluZWQgPyBlbC5hdHRyKGFbaV0pIDogb2c7XG4gICAgICAgIC8vICAgICB9XG4gICAgICAgIC8vIH0gZWxzZSB7XG4gICAgICAgIC8vICAgICB2YXIgb2cgPSByZWZyZXNoX21vZHVsZXMxMTtcbiAgICAgICAgLy8gfVxuICAgICAgICAvL1xuICAgICAgICAvLyBpZiAob2cxICE9IHVuZGVmaW5lZCkge1xuICAgICAgICAvLyAgICAgdmFyIG9nID0gb2cxO1xuICAgICAgICAvLyAgICAgaWYgKHJlZnJlc2hfbW9kdWxlczExID09IHVuZGVmaW5lZCkge1xuICAgICAgICAvLyAgICAgICAgIGlmIChyZWZyZXNoX21vZHVsZXMxMiA9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgLy8gICAgICAgICAgICAgcmVmcmVzaF9tb2R1bGVzMTEgPSBvZzE7XG4gICAgICAgIC8vICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgLy8gICAgICAgICAgICAgcmVmcmVzaF9tb2R1bGVzMTEgPSByZWZyZXNoX21vZHVsZXMxMjtcbiAgICAgICAgLy8gICAgICAgICB9XG4gICAgICAgIC8vICAgICB9XG4gICAgICAgIC8vIH1cblxuXG4gICAgICAgIHZhciB2YWw7XG4gICAgICAgIGlmIChlbFswXS50eXBlID09PSAnY2hlY2tib3gnKSB7XG4gICAgICAgICAgICB2YWwgPSAnJyxcbiAgICAgICAgICAgICAgICBkdnUgPSBlbC5hdHRyKCdkYXRhLXZhbHVlLXVuY2hlY2tlZCcpLFxuICAgICAgICAgICAgICAgIGR2YyA9IGVsLmF0dHIoJ2RhdGEtdmFsdWUtY2hlY2tlZCcpO1xuICAgICAgICAgICAgaWYgKCEhZHZ1ICYmICEhZHZjKSB7XG4gICAgICAgICAgICAgICAgdmFsID0gZWxbMF0uY2hlY2tlZCA/IGR2YyA6IGR2dTtcbiAgICAgICAgICAgIH1cbiAgICAgICAgICAgIGVsc2Uge1xuXG4gICAgICAgICAgICAgICAgdmFyIGl0ZW1zID0gbXdkLmdldEVsZW1lbnRzQnlOYW1lKGVsWzBdLm5hbWUpLCBpID0gMCwgbGVuID0gaXRlbXMubGVuZ3RoO1xuICAgICAgICAgICAgICAgIGZvciAoOyBpIDwgbGVuOyBpKyspIHtcbiAgICAgICAgICAgICAgICAgICAgdmFyIF92YWwgPSBpdGVtc1tpXS52YWx1ZTtcbiAgICAgICAgICAgICAgICAgICAgdmFsID0gaXRlbXNbaV0uY2hlY2tlZCA9PSB0cnVlID8gKHZhbCA9PT0gJycgPyBfdmFsIDogdmFsICsgXCIsXCIgKyBfdmFsKSA6IHZhbDtcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgfVxuICAgICAgICBlbHNlIHtcbiAgICAgICAgICAgIHZhbCA9IGVsLnZhbCgpO1xuICAgICAgICB9XG4gICAgICAgIGlmICh0eXBlb2Yob2cpID09ICd1bmRlZmluZWQnICYmIHR5cGVvZihvZykgPT0gJ3VuZGVmaW5lZCcgJiYgb2dfcGFyZW50KSB7XG4gICAgICAgICAgICBvZyA9IG9nX3BhcmVudDtcbiAgICAgICAgfVxuXG5cbiAgICAgICAgLy8gIGFsZXJ0KG9nICsgJyAgICAgICAnICtvZzEpO1xuXG5cbiAgICAgICAgdmFyIG9fZGF0YSA9IHtcbiAgICAgICAgICAgIG9wdGlvbl9rZXk6IGVsLmF0dHIoJ25hbWUnKSxcbiAgICAgICAgICAgIG9wdGlvbl9ncm91cDogb2csXG4gICAgICAgICAgICBvcHRpb25fdmFsdWU6IHZhbFxuICAgICAgICB9XG5cblxuICAgICAgICBpZiAobW5hbWUgPT09IHVuZGVmaW5lZCkge1xuICAgICAgICAgICAgaWYgKG9nX3Rlc3QgIT09IHVuZGVmaW5lZCAmJiBvZ190ZXN0ICYmICQob2dfdGVzdCkuYXR0cigncGFyZW50LW1vZHVsZScpKSB7XG4gICAgICAgICAgICAgICAgb19kYXRhLm1vZHVsZSA9ICQob2dfdGVzdCkuYXR0cigncGFyZW50LW1vZHVsZScpO1xuICAgICAgICAgICAgIH1cbiAgICAgICAgfVxuXG5cbiAgICAgICAgaWYgKG1uYW1lICE9PSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIG9fZGF0YS5tb2R1bGUgPSBtbmFtZTtcbiAgICAgICAgfVxuXG5cblxuXG4gICAgICAgIGlmIChmb3JfbV9pZCAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICBvX2RhdGEuZm9yX21vZHVsZV9pZCA9IGZvcl9tX2lkO1xuICAgICAgICB9XG4gICAgICAgIGlmIChvZyAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgIG9fZGF0YS5pZCA9IGhhdmVfaWQ7XG4gICAgICAgIH1cblxuXG5cblxuICAgICAgICB2YXIgaGF2ZV9pZCA9IGVsLmF0dHIoJ2RhdGEtY3VzdG9tLWZpZWxkLWlkJyk7XG5cbiAgICAgICAgaWYgKGhhdmVfaWQgIT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICBvX2RhdGEuaWQgPSBoYXZlX2lkO1xuICAgICAgICB9XG5cbiAgICAgICAgdmFyIGhhdmVfb3B0aW9uX3R5cGUgPSBlbC5hdHRyKCdkYXRhLW9wdGlvbi10eXBlJyk7XG5cbiAgICAgICAgaWYgKGhhdmVfb3B0aW9uX3R5cGUgIT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICBvX2RhdGEub3B0aW9uX3R5cGUgPSBoYXZlX29wdGlvbl90eXBlO1xuICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgdmFyIGhhdmVfb3B0aW9uX3R5cGUgPSBlbC5hdHRyKCdvcHRpb24tdHlwZScpO1xuXG4gICAgICAgICAgICBpZiAoaGF2ZV9vcHRpb25fdHlwZSAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICBvX2RhdGEub3B0aW9uX3R5cGUgPSBoYXZlX29wdGlvbl90eXBlO1xuICAgICAgICAgICAgfVxuICAgICAgICB9XG4gICAgICAgIHZhciByZWFsb2FkX2luX3BhcmVudCA9IGVsLmF0dHIoJ3BhcmVudC1yZWxvYWQnKTtcblxuICAgICAgICBpZiAob3B0X2lkICE9PSB1bmRlZmluZWQpIHtcblxuXG4gICAgICAgICAgICBvX2RhdGEuaWQgPSBvcHRfaWQ7XG5cbiAgICAgICAgfVxuXG5cbiAgICAgICAgJC5hamF4KHtcbiAgICAgICAgICAgIHR5cGU6IFwiUE9TVFwiLFxuICAgICAgICAgICAgdXJsOiBtdy5zZXR0aW5ncy5zaXRlX3VybCArIFwiYXBpL3NhdmVfb3B0aW9uXCIsXG4gICAgICAgICAgICBkYXRhOiBvX2RhdGEsXG4gICAgICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xuXG4gICAgICAgICAgICAgICAgdmFyIHdoaWNoX21vZHVsZV90b19yZWxvYWQgPSBudWxsO1xuXG5cbiAgICAgICAgICAgICAgICBpZiAodHlwZW9mKHJlZnJlc2hfbW9kdWxlczExKSA9PSAndW5kZWZpbmVkJykge1xuICAgICAgICAgICAgICAgICAgICB3aGljaF9tb2R1bGVfdG9fcmVsb2FkID0gb2cxO1xuICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAocmVmcmVzaF9tb2R1bGVzMTIpIHtcbiAgICAgICAgICAgICAgICAgICAgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCA9IHJlZnJlc2hfbW9kdWxlczEyO1xuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICgodHlwZW9mKGxpdmVFZGl0U2V0dGluZ3MpICE9ICd1bmRlZmluZWQnICYmIGxpdmVFZGl0U2V0dGluZ3MpIHx8IG13LnRvcCgpLndpbi5saXZlRWRpdFNldHRpbmdzKSB7XG4gICAgICAgICAgICAgICAgICAgIGlmICghb2cxICYmIG9nX3BhcmVudCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCA9IG9nX3BhcmVudDtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIHZhciByZWxvYWRfaW5fcGFyZW50X3RyaWVnZ2VyZWQgPSBmYWxzZTtcblxuXG4gICAgICAgICAgICAgICAgLy8gIGFsZXJ0KCdyZWZyZXNoX21vZHVsZXMxMSAgICAgJytyZWZyZXNoX21vZHVsZXMxMSk7XG4gICAgICAgICAgICAgICAgLy8gIGFsZXJ0KCd3aGljaF9tb2R1bGVfdG9fcmVsb2FkICAgICAnK3doaWNoX21vZHVsZV90b19yZWxvYWQpO1xuICAgICAgICAgICAgICAgIC8vIGFsZXJ0KCdvZzEgICAgICAnK29nMSk7XG5cblxuICAgICAgICAgICAgICAgIGlmIChtdy5hZG1pbikge1xuICAgICAgICAgICAgICAgICAgICBpZiAodG9wLm13ZWRpdG9yICYmIHRvcC5td2VkaXRvci5jb250ZW50V2luZG93KSB7XG4gICAgICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB0b3AubXdlZGl0b3IuY29udGVudFdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlKFwiI1wiICsgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIH0sIDc3Nyk7XG4gICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgaWYgKHdpbmRvdy5wYXJlbnQubXcpIHtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoc2VsZiAhPT0gdG9wKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG1vZF9lbGVtZW50ID0gd2luZG93LnBhcmVudC5kb2N1bWVudC5nZXRFbGVtZW50QnlJZCh3aGljaF9tb2R1bGVfdG9fcmVsb2FkKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAobW9kX2VsZW1lbnQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gdmFyIG1vZHVsZV9wYXJlbnRfZWRpdF9maWVsZCA9IHdpbmRvdy5wYXJlbnQubXcudG9vbHMuZmlyc3RQYXJlbnRXaXRoQ2xhc3MobW9kX2VsZW1lbnQsICdlZGl0JylcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyB2YXIgbW9kdWxlX3BhcmVudF9lZGl0X2ZpZWxkID0gd2luZG93LnBhcmVudC5tdy50b29scy5maXJzdE1hdGNoZXNPbk5vZGVPclBhcmVudChtb2RfZWxlbWVudCwgWycuZWRpdFtyZWw9aW5oZXJpdF0nXSlcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIG1vZHVsZV9wYXJlbnRfZWRpdF9maWVsZCA9IHdpbmRvdy5wYXJlbnQubXcudG9vbHMuZmlyc3RNYXRjaGVzT25Ob2RlT3JQYXJlbnQobW9kX2VsZW1lbnQsIFsnLmVkaXQ6bm90KFtpdGVtcHJvcD1kYXRlTW9kaWZpZWRdKSddKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCFtb2R1bGVfcGFyZW50X2VkaXRfZmllbGQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgbW9kdWxlX3BhcmVudF9lZGl0X2ZpZWxkID0gd2luZG93LnBhcmVudC5tdy50b29scy5maXJzdE1hdGNoZXNPbk5vZGVPclBhcmVudChtb2RfZWxlbWVudCwgWycuZWRpdFtyZWw9aW5oZXJpdF0nXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAobW9kdWxlX3BhcmVudF9lZGl0X2ZpZWxkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIHdpbmRvdy5wYXJlbnQubXcudG9vbHMuYWRkQ2xhc3MobW9kdWxlX3BhcmVudF9lZGl0X2ZpZWxkLCAnY2hhbmdlZCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy53eXNpd3lnID8gd2luZG93LnBhcmVudC5tdy53eXNpd3lnLmNoYW5nZShtb2R1bGVfcGFyZW50X2VkaXRfZmllbGQpIDogJyc7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB3aW5kb3cucGFyZW50Lm13LmFza3VzZXJ0b3N0YXkgPSB0cnVlO1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5yZWxvYWRfbW9kdWxlX3BhcmVudChcIiNcIiArIHdoaWNoX21vZHVsZV90b19yZWxvYWQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh3aGljaF9tb2R1bGVfdG9fcmVsb2FkICE9IG9nMSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5yZWxvYWRfbW9kdWxlX3BhcmVudChcIiNcIiArIG9nMSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJlbG9hZF9pbl9wYXJlbnRfdHJpZWdnZXJlZCA9IDE7XG5cblxuICAgICAgICAgICAgICAgICAgICAgICAgfSwgNzc3KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGlmICh3aW5kb3cucGFyZW50Lm13LnJlbG9hZF9tb2R1bGUgIT0gdW5kZWZpbmVkKSB7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICghIW13LmFkbWluKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5wYXJlbnQubXcucmVsb2FkX21vZHVsZShcIiNcIiArIHdoaWNoX21vZHVsZV90b19yZWxvYWQpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5vcHRpb25zLl9fX3JlYmluZEFsbEZvcm1zQWZ0ZXJSZWxvYWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9LCA3NzcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgZWxzZSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHdpbmRvdy5wYXJlbnQubXdlZGl0b3IgIT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5wYXJlbnQubXdlZGl0b3IuY29udGVudFdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlKFwiI1wiICsgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy5leGVjKFwibXcuYWRtaW4uZWRpdG9yLnNldFwiLCB3aW5kb3cucGFyZW50Lm13ZWRpdG9yKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5vcHRpb25zLl9fX3JlYmluZEFsbEZvcm1zQWZ0ZXJSZWxvYWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0sIDc3Nyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAod2luZG93LnBhcmVudC5tdyAhPSB1bmRlZmluZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy5yZWxvYWRfbW9kdWxlKFwiI1wiICsgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCwgZnVuY3Rpb24gKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy5leGVjKFwibXcuYWRtaW4uZWRpdG9yLnNldFwiLCB3aW5kb3cucGFyZW50Lm13ZWRpdG9yKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5vcHRpb25zLl9fX3JlYmluZEFsbEZvcm1zQWZ0ZXJSZWxvYWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0sIDc3Nyk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgIHJlbG9hZF9pbl9wYXJlbnRfdHJpZWdnZXJlZCA9IDE7XG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICAgICAgLy8gaWYgKHJlYWxvYWRfaW5fcGFyZW50ICE9IHVuZGVmaW5lZCAmJiByZWFsb2FkX2luX3BhcmVudCAhPT0gbnVsbCkge1xuICAgICAgICAgICAgICAgIC8vICAgICAvLyAgICAgd2luZG93LnBhcmVudC5tdy5yZWxvYWRfbW9kdWxlKFwiI1wiK3JlZnJlc2hfbW9kdWxlczExKTtcbiAgICAgICAgICAgICAgICAvL1xuICAgICAgICAgICAgICAgIC8vICAgICByZXR1cm4gZmFsc2U7XG4gICAgICAgICAgICAgICAgLy8gfVxuXG5cbiAgICAgICAgICAgICAgICBpZiAoYWxzb19yZWxvYWQgIT0gdW5kZWZpbmVkKSB7XG5cblxuICAgICAgICAgICAgICAgICAgICBpZiAod2luZG93Lm13ICE9IHVuZGVmaW5lZCAmJiByZWFsb2FkX2luX3BhcmVudCAhPT0gdHJ1ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKHdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlICE9PSB1bmRlZmluZWQpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlKGFsc29fcmVsb2FkLCBmdW5jdGlvbiAocmVsb2FkZWRfZWwpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyAgbXcub3B0aW9ucy5mb3JtKHJlbG9hZGVkX2VsLCBjYWxsYmFjayk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIG13Lm9wdGlvbnMuX19fcmViaW5kQWxsRm9ybXNBZnRlclJlbG9hZCgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlKCcjJyArIGFsc29fcmVsb2FkLCBmdW5jdGlvbiAocmVsb2FkZWRfZWwpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL213Lm9wdGlvbnMuZm9ybShyZWxvYWRlZF9lbCwgY2FsbGJhY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5vcHRpb25zLl9fX3JlYmluZEFsbEZvcm1zQWZ0ZXJSZWxvYWQoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgLyogICAgICAgICAgIGlmIChyZWFsb2FkX2luX3BhcmVudCAhPT0gdHJ1ZSAmJiBmb3JfbV9pZCAhPSB1bmRlZmluZWQgJiYgZm9yX21faWQgIT0gJycpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBmb3JfbV9pZCA9IGZvcl9tX2lkLnRvU3RyaW5nKClcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAod2luZG93Lm13ICE9IHVuZGVmaW5lZCkge1xuXG5cblxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIGlmICh3aW5kb3cubXcucmVsb2FkX21vZHVsZSAhPT0gdW5kZWZpbmVkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFx0XHRcdHdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlKCcjJytmb3JfbV9pZCwgZnVuY3Rpb24ocmVsb2FkZWRfZWwpe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvL1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBcdFx0XHRcdG13Lm9wdGlvbnMuZm9ybShyZWxvYWRlZF9lbCwgY2FsbGJhY2spO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBcdFx0XHR9KTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gICAgICAgIH1cbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICB9IGVsc2UqL1xuXG5cbiAgICAgICAgICAgICAgICBpZiAocmVsb2FkX2luX3BhcmVudF90cmllZ2dlcmVkID09IGZhbHNlICYmIHJlYWxvYWRfaW5fcGFyZW50ICE9PSB0cnVlICYmIHdoaWNoX21vZHVsZV90b19yZWxvYWQgIT0gdW5kZWZpbmVkICYmIHdoaWNoX21vZHVsZV90b19yZWxvYWQgIT0gJycpIHtcbiAgICAgICAgICAgICAgICAgICAgd2hpY2hfbW9kdWxlX3RvX3JlbG9hZCA9IHdoaWNoX21vZHVsZV90b19yZWxvYWQudG9TdHJpbmcoKVxuXG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHdpbmRvdy5tdy5yZWxvYWRfbW9kdWxlICE9PSB1bmRlZmluZWQpIHtcblxuICAgICAgICAgICAgICAgICAgICAgICAgbXcucmVsb2FkX21vZHVsZV9wYXJlbnQod2hpY2hfbW9kdWxlX3RvX3JlbG9hZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICBtdy5yZWxvYWRfbW9kdWxlX3BhcmVudChcIiNcIiArIHdoaWNoX21vZHVsZV90b19yZWxvYWQpO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgICAgIHR5cGVvZiBjYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJyA/IGNhbGxiYWNrLmNhbGwoZGF0YSkgOiAnJztcbiAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcub3B0aW9ucy5fX19yZWJpbmRBbGxGb3Jtc0FmdGVyUmVsb2FkKCk7XG4gICAgICAgICAgICAgICAgfSwgMTExKTtcbiAgICAgICAgICAgICAgICAvL1xuICAgICAgICAgICAgICAgIC8vXG4gICAgICAgICAgICAgICAgLy9kKHJlZnJlc2hfbW9kdWxlczExKTtcbiAgICAgICAgICAgICAgICAvL2QobXcub3B0aW9ucy5fYmluZGVkUm9vdEZvcm1zUmVnaXN0cnkpO1xuICAgICAgICAgICAgfVxuICAgICAgICB9KVxuICAgIH1cbn07XG5cbm13Lm9wdGlvbnMuX29wdGlvblNhdmVkID0gbnVsbDtcblxubXcub3B0aW9ucy5fYmluZGVkUm9vdEZvcm1zUmVnaXN0cnkgPSBbXTtcblxubXcub3B0aW9ucy5yZW1vdmVfYmluZGluZ3MgPSBmdW5jdGlvbiAoJHNlbGVjdG9yKSB7XG5cbiAgICB2YXIgJHJvb3QgPSBtdy4kKCRzZWxlY3Rvcik7XG4gICAgdmFyIHJvb3QgPSAkcm9vdFswXTtcbiAgICBpZiAoIXJvb3QpIHJldHVybjtcblxuICAgIGlmIChyb290Ll9vcHRpb25zRXZlbnRzKSB7XG4gICAgICAgIGRlbGV0ZShyb290Ll9vcHRpb25zRXZlbnRzKTtcbiAgICAgICAgcm9vdC5fb3B0aW9uc0V2ZW50c0NsZWFyQmlkaW5ncyA9IHRydWU7XG4gICAgfVxuICAgIHJvb3QuYWRkQ2xhc3MoJ213LW9wdGlvbnMtZm9ybS1mb3JjZS1yZWJpbmQnKTtcblxuXG4gICAgbXcuJChcImlucHV0LCBzZWxlY3QsIHRleHRhcmVhXCIsIHJvb3QpXG4gICAgICAgIC5ub3QoJy5tdy1vcHRpb25zLWZvcm0tYmluZGVkLWN1c3RvbScpXG4gICAgICAgIC5lYWNoKGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHZhciBpdGVtID0gbXcuJCh0aGlzKTtcblxuXG4gICAgICAgICAgICBpZiAoaXRlbSAmJiBpdGVtWzBdICYmIGl0ZW1bMF0uX29wdGlvbnNFdmVudHNCaW5kZWQpIHtcbiAgICAgICAgICAgICAgICBkZWxldGUoaXRlbVswXS5fb3B0aW9uc0V2ZW50c0JpbmRlZCk7XG5cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG5cbn07XG5tdy5vcHRpb25zLmZvcm0gPSBmdW5jdGlvbiAoJHNlbGVjdG9yLCBjYWxsYmFjaywgYmVmb3JlcG9zdCkge1xuXG5cblxuICAgIC8vc2V0VGltZW91dChmdW5jdGlvbiAoKSB7XG5cblxuICAgIHZhciBudW1PZmJpbmRpZ3MgPSAwO1xuICAgIHZhciBmb3JjZV9yZWJpbmQgPSBmYWxzZTtcblxuICAgIHZhciAkcm9vdCA9IG13LiQoJHNlbGVjdG9yKTtcbiAgICB2YXIgcm9vdCA9ICRyb290WzBdO1xuICAgIGlmICghcm9vdCkgcmV0dXJuO1xuXG4gICAgLy9cbiAgICBpZiAocm9vdCAmJiAkcm9vdC5oYXNDbGFzcygnbXctb3B0aW9ucy1mb3JtLWZvcmNlLXJlYmluZCcpKSB7XG4gICAgICAgIGZvcmNlX3JlYmluZCA9IHRydWU7XG5cbiAgICB9XG5cbiAgICBpZiAoIXJvb3QuX29wdGlvbnNFdmVudHMpIHtcblxuICAgICAgICBtdy4kKFwiaW5wdXQsIHNlbGVjdCwgdGV4dGFyZWFcIiwgcm9vdClcbiAgICAgICAgICAgIC5ub3QoJy5tdy1vcHRpb25zLWZvcm0tYmluZGVkLWN1c3RvbScpXG4gICAgICAgICAgICAuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgLy90aGlzLl9vcHRpb25TYXZlZCA9IHRydWU7XG5cbiAgICAgICAgICAgICAgICB2YXIgaXRlbSA9IG13LiQodGhpcyk7XG4gICAgICAgICAgICAgICAgaWYgKGZvcmNlX3JlYmluZCkge1xuICAgICAgICAgICAgICAgICAgICBpdGVtWzBdLl9vcHRpb25zRXZlbnRzQmluZGVkID0gbnVsbDtcbiAgICAgICAgICAgICAgICB9XG5cblxuICAgICAgICAgICAgICAgIGlmIChpdGVtICYmIGl0ZW1bMF0gJiYgIWl0ZW1bMF0uX29wdGlvbnNFdmVudHNCaW5kZWQpIHtcblxuICAgICAgICAgICAgICAgICAgICBpZiAoaXRlbS5oYXNDbGFzcygnbXdfb3B0aW9uX2ZpZWxkJykpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgIG51bU9mYmluZGlncysrO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW1bMF0uX29wdGlvbnNFdmVudHNCaW5kZWQgPSB0cnVlO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChyb290Ll9vcHRpb25zRXZlbnRzQ2xlYXJCaWRpbmdzKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaXRlbS5vZmYoJ2NoYW5nZSBpbnB1dCBwYXN0ZScpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgICAgICBpdGVtLmFkZENsYXNzKCdtdy1vcHRpb25zLWZvcm0tYmluZGVkJyk7XG4gICAgICAgICAgICAgICAgICAgICAgICBpdGVtLm9uKCdjaGFuZ2UgaW5wdXQgcGFzdGUnLCBmdW5jdGlvbiAoZSkge1xuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGlzQ2hlY2tMaWtlID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB2YXIgdG9rZW4gPSBpc0NoZWNrTGlrZSA/IHRoaXMubmFtZSA6IHRoaXMubmFtZSArIG13LiQodGhpcykudmFsKCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgbXcub3B0aW9ucy5fX19zbG93RG93bkV2ZW50KHRva2VuLCB0aGlzLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0eXBlb2Ygcm9vdC5fb3B0aW9uc0V2ZW50cy5iZWZvcmVwb3N0ID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICByb290Ll9vcHRpb25zRXZlbnRzLmJlZm9yZXBvc3QuY2FsbCh0aGlzKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAodG9wICE9PSBzZWxmICYmIHdpbmRvdy5wYXJlbnQubXcuZHJhZyAmJiB3aW5kb3cucGFyZW50Lm13LmRyYWcuc2F2ZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd2luZG93LnBhcmVudC5tdy5kcmFnLnNhdmUoKTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBtdy5vcHRpb25zLnNhdmUodGhpcywgcm9vdC5fb3B0aW9uc0V2ZW50cy5jYWxsYmFjayk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy99XG4gICAgICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pO1xuICAgIH1cblxuXG4gICAgLy8gIGFsZXJ0KCRzZWxlY3RvciArJyAgIC0tICAgJyArbnVtT2ZiaW5kaWdzKTtcblxuXG4gICAgLy8gUkVCSU5EXG4gICAgaWYgKG51bU9mYmluZGlncyA+IDApIHtcbiAgICAgICAgcm9vdC5fb3B0aW9uc0V2ZW50cyA9IHJvb3QuX29wdGlvbnNFdmVudHMgfHwge307XG4gICAgICAgIHJvb3QuX29wdGlvbnNFdmVudHMgPSAkLmV4dGVuZCh7fSwgcm9vdC5fb3B0aW9uc0V2ZW50cywge2NhbGxiYWNrOiBjYWxsYmFjaywgYmVmb3JlcG9zdDogYmVmb3JlcG9zdH0pO1xuXG5cbiAgICAgICAgdmFyIHJlYmluZCA9IHt9O1xuICAgICAgICBpZiAodHlwZW9mIHJvb3QuX29wdGlvbnNFdmVudHMuYmVmb3JlcG9zdCA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgcmViaW5kLmJlZm9yZXBvc3QgPSByb290Ll9vcHRpb25zRXZlbnRzLmJlZm9yZXBvc3Q7XG4gICAgICAgIH1cbiAgICAgICAgcmViaW5kLmNhbGxiYWNrID0gcm9vdC5fb3B0aW9uc0V2ZW50cy5jYWxsYmFjaztcbiAgICAgICAgcmViaW5kLmJpbmRlZF9zZWxlY3RvciA9ICRzZWxlY3RvcjtcbiAgICAgICAgdmFyIHJlYmluZHRlbXAgPSBtdy50b29scy5jbG9uZU9iamVjdChyZWJpbmQpO1xuICAgICAgICAvL2ZpeCBoZXJlIGNoZWsgaWYgaW4gYXJyYXlcblxuXG4gICAgICAgIHZhciBpc19pbiA9IG13Lm9wdGlvbnMuX2JpbmRlZFJvb3RGb3Jtc1JlZ2lzdHJ5LmZpbHRlcihmdW5jdGlvbiAoYSkge1xuICAgICAgICAgICAgcmV0dXJuIGEuYmluZGVkX3NlbGVjdG9yID09PSAkc2VsZWN0b3I7XG4gICAgICAgIH0pXG5cbiAgICAgICAgaWYgKCFpc19pbi5sZW5ndGgpIHtcbiAgICAgICAgICAgIG13Lm9wdGlvbnMuX2JpbmRlZFJvb3RGb3Jtc1JlZ2lzdHJ5LnB1c2gocmViaW5kdGVtcCk7XG4gICAgICAgIH1cbiAgICB9XG4gICAgLy8gRU5EIE9GIFJFQklORFxuXG5cbiAgICAvL30sIDEwLCRzZWxlY3RvciwgY2FsbGJhY2ssIGJlZm9yZXBvc3QpO1xuXG5cbn07XG5cblxubXcub3B0aW9ucy5fX19zbG93RG93bkV2ZW50cyA9IHt9O1xubXcub3B0aW9ucy5fX19zbG93RG93bkV2ZW50ID0gZnVuY3Rpb24gKHRva2VuLCBlbCwgY2FsbCkge1xuICAgIGlmICh0eXBlb2YgbXcub3B0aW9ucy5fX19zbG93RG93bkV2ZW50c1t0b2tlbl0gPT09ICd1bmRlZmluZWQnKSB7XG4gICAgICAgIG13Lm9wdGlvbnMuX19fc2xvd0Rvd25FdmVudHNbdG9rZW5dID0gbnVsbDtcbiAgICB9XG4gICAgY2xlYXJUaW1lb3V0KG13Lm9wdGlvbnMuX19fc2xvd0Rvd25FdmVudHNbdG9rZW5dKTtcbiAgICBtdy5vcHRpb25zLl9fX3Nsb3dEb3duRXZlbnRzW3Rva2VuXSA9IHNldFRpbWVvdXQoZnVuY3Rpb24gKCkge1xuICAgICAgICBjYWxsLmNhbGwoZWwpO1xuICAgIH0sIDcwMCk7XG59O1xuXG5tdy5vcHRpb25zLl9fX3JlYmluZEFsbEZvcm1zQWZ0ZXJSZWxvYWQgPSBmdW5jdGlvbiAoKSB7XG5cbiAgICB2YXIgdG9rZW4gPSAnX19fcmViaW5kQWxsRm9ybXNBZnRlclJlbG9hZCc7XG5cblxuICAgIG13Lm9wdGlvbnMuX19fc2xvd0Rvd25FdmVudCh0b2tlbiwgdGhpcywgZnVuY3Rpb24gKCkge1xuXG5cbiAgICAgICAgZm9yICh2YXIgaSA9IDAsIGwgPSBtdy5vcHRpb25zLl9iaW5kZWRSb290Rm9ybXNSZWdpc3RyeS5sZW5ndGg7IGkgPCBsOyBpKyspIHtcbiAgICAgICAgICAgIHZhciBiaW5kZWRfcm9vdCA9IG13Lm9wdGlvbnMuX2JpbmRlZFJvb3RGb3Jtc1JlZ2lzdHJ5W2ldO1xuICAgICAgICAgICAgaWYgKGJpbmRlZF9yb290LmJpbmRlZF9zZWxlY3Rvcikge1xuXG4gICAgICAgICAgICAgICAgdmFyICRyb290ID0gbXcuJChiaW5kZWRfcm9vdC5iaW5kZWRfc2VsZWN0b3IpO1xuICAgICAgICAgICAgICAgIHZhciByb290ID0gJHJvb3RbMF07XG4gICAgICAgICAgICAgICAgaWYgKHJvb3QpIHtcblxuICAgICAgICAgICAgICAgICAgICB2YXIgcmViaW5kX2JlZm9yZXBvc3QgPSBudWxsO1xuICAgICAgICAgICAgICAgICAgICB2YXIgcmViaW5kX2NhbGxiYWNrID0gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBiaW5kZWRfcm9vdC5iZWZvcmVwb3N0ID09PSAnZnVuY3Rpb24nKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgcmViaW5kX2JlZm9yZXBvc3QgPSBiaW5kZWRfcm9vdC5iZWZvcmVwb3N0O1xuICAgICAgICAgICAgICAgICAgICB9XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHR5cGVvZiBiaW5kZWRfcm9vdC5jYWxsYmFjayA9PT0gJ2Z1bmN0aW9uJykge1xuICAgICAgICAgICAgICAgICAgICAgICAgdmFyIHJlYmluZF9jYWxsYmFjayA9IGJpbmRlZF9yb290LmNhbGxiYWNrO1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIHZhciBoYXNfbm9uX2JpbmRlZCA9IGZhbHNlO1xuICAgICAgICAgICAgICAgICAgICBtdy4kKFwiaW5wdXQsIHNlbGVjdCwgdGV4dGFyZWFcIiwgcm9vdClcbiAgICAgICAgICAgICAgICAgICAgICAgIC5ub3QoJy5tdy1vcHRpb25zLWZvcm0tYmluZGVkLWN1c3RvbScpXG4gICAgICAgICAgICAgICAgICAgICAgICAubm90KCcubXctb3B0aW9ucy1mb3JtLWJpbmRlZCcpXG4gICAgICAgICAgICAgICAgICAgICAgICAuZWFjaChmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgdmFyIGl0ZW0gPSBtdy4kKHRoaXMpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChpdGVtLmhhc0NsYXNzKCdtd19vcHRpb25fZmllbGQnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIWl0ZW1bMF0uX29wdGlvbnNFdmVudHNCaW5kZWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGhhc19ub25fYmluZGVkID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGl0ZW0uYXR0cignYXV0b2NvbXBsZXRlJywgJ29mZicpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHJvb3QuX29wdGlvbnNFdmVudHMgJiYgaGFzX25vbl9iaW5kZWQgJiYgcmViaW5kX2NhbGxiYWNrKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICByb290Ll9vcHRpb25zRXZlbnRzID0gbnVsbDtcbiAgICAgICAgICAgICAgICAgICAgICAgIHJvb3QuX29wdGlvbnNFdmVudHNDbGVhckJpZGluZ3MgPSB0cnVlO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcub3B0aW9ucy5mb3JtKGJpbmRlZF9yb290LmJpbmRlZF9zZWxlY3RvciwgcmViaW5kX2NhbGxiYWNrLCByZWJpbmRfYmVmb3JlcG9zdCk7XG5cbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIG13Lm9wdGlvbnMuX2JpbmRlZFJvb3RGb3Jtc1JlZ2lzdHJ5ID0gIG13Lm9wdGlvbnMuX2JpbmRlZFJvb3RGb3Jtc1JlZ2lzdHJ5LmZpbHRlcihmdW5jdGlvbiAoYSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gICAgIHJldHVybiBhLmJpbmRlZF9zZWxlY3RvciAhPSBiaW5kZWRfcm9vdC5iaW5kZWRfc2VsZWN0b3JcbiAgICAgICAgICAgICAgICAgICAgICAgIC8vIH0pXG5cbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cblxuXG4gICAgICAgICAgICB9XG4gICAgICAgIH1cbiAgICB9KTtcbn1cbi8vXG4vLyBtdy5vcHRpb25zLl9fX2xvY2F0ZU1vZHVsZU5vZGVzVG9CZVJlYWxvYWRlZCA9IGZ1bmN0aW9uIChzZWxlY3Ryb3Isd2luZG93X3Njb3BlKSB7XG4vL1xuLy8gICAgdmFyIG1vZHVsZSA9IG1vZHVsZS5yZXBsYWNlKC8jIy9nLCAnIycpO1xuLy8gICAgdmFyIG0gPSBtdy4kKFwiLm1vZHVsZVtkYXRhLXR5cGU9J1wiICsgbW9kdWxlICsgXCInXVwiKTtcbi8vICAgIGlmIChtLmxlbmd0aCA9PT0gMCkge1xuLy8gICAgICAgIHRyeSB7IHZhciBtID0gbXcuJChtb2R1bGUpOyB9ICBjYXRjaChlKSB7fTtcbi8vICAgIH1cbi8vXG4vL31cbiIsIndpbmRvdy5vbm1lc3NhZ2UgPSBmdW5jdGlvbiAoZSkge1xuXG4vLyAgICBpZiAoIGUub3JpZ2luICE9PSBcImh0dHA6Ly9odG1sNWRlbW9zLmNvbVwiICkge1xuLy8gICAgICAgIHJldHVybjtcbi8vICAgIH1cblxuXG4gICAgaWYgKHR5cGVvZiBlLmRhdGEgIT0gJ3VuZGVmaW5lZCcpIHtcblxuXG4gICAgICAgIGlmICh0eXBlb2YgZS5kYXRhLm1hcmtldF9pZCAhPSAndW5kZWZpbmVkJyB8fCB0eXBlb2YgZS5kYXRhLm13X3ZlcnNpb24gIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgIG13Lm5vdGlmaWNhdGlvbi5zdWNjZXNzKFwiSW5zdGFsbGluZyBpdGVtXCIsIDkwMDApO1xuXG4gICAgICAgICAgICBpZiAodHlwZW9mIGUuZGF0YS5tYXJrZXRfaWQgIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICB2YXIgdXJsID0gbXcuc2V0dGluZ3MuYXBpX3VybCArIFwibXdfaW5zdGFsbF9tYXJrZXRfaXRlbVwiO1xuICAgICAgICAgICAgfSBlbHNlIGlmICh0eXBlb2YgZS5kYXRhLm13X3ZlcnNpb24gIT0gJ3VuZGVmaW5lZCcpIHtcbiAgICAgICAgICAgICAgICB2YXIgdXJsID0gbXcuc2V0dGluZ3MuYXBpX3VybCArIFwibXdfc2V0X3VwZGF0ZXNfcXVldWVcIjtcblxuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAkLnBvc3QodXJsLCBlLmRhdGEpXG4gICAgICAgICAgICAgICAgLmRvbmUoZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICAgICAgbXcubm90aWZpY2F0aW9uLm1zZyhkYXRhLCA1MDAwKTtcblxuICAgICAgICAgICAgICAgICAgICBpZiAodHlwZW9mKGRhdGEudXBkYXRlX3F1ZXVlX3NldCAhPSAndW5kZWZpbmVkJykpIHtcblxuXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgdXBkYXRlX3F1ZXVlX3NldF9tb2RhbCA9IG13LmRpYWxvZyh7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY29udGVudDogJzxkaXYgY2xhc3M9XCJtb2R1bGVcIiB0eXBlPVwidXBkYXRlcy93b3JrZXJcIiBpZD1cInVwZGF0ZV9xdWV1ZV9wcm9jZXNzX2FsZXJ0XCI+PC9kaXY+JyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBvdmVybGF5OiBmYWxzZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZDogJ3VwZGF0ZV9xdWV1ZV9zZXRfbW9kYWwnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHRpdGxlOiAnSW5zdGFsbGluZydcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuXG5cbiAgICAgICAgICAgICAgICAgICAgICAgIG13LnJlbG9hZF9tb2R1bGUoJyN1cGRhdGVfcXVldWVfcHJvY2Vzc19hbGVydCcpO1xuICAgICAgICAgICAgICAgICAgICAgICAgbXcucmVsb2FkX21vZHVsZSgndXBkYXRlcy9saXN0Jyk7XG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICB9XG4gICAgfVxuICAgIC8vIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKFwidGVzdFwiKS5pbm5lckhUTUwgPSBlLm9yaWdpbiArIFwiIHNhaWQ6IFwiICsgZS5kYXRhO1xufTtcblxuXG4iXSwic291cmNlUm9vdCI6IiJ9