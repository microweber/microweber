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
    deleteCategory: function (id, callback, reload) {
        if(typeof reload === 'undefined') {
            reload = true;
        }
        mw.tools.confirm('Are you sure you want to delete this?', function () {
         $.ajax({
                url: mw.settings.api_url + 'category/delete/' + id,
                method: 'DELETE',
                contentType: 'application/json',
                success: function (result) {
                    mw.notification.success('Category deleted');
                    if (callback) {
                        callback.call(result, result);
                    }
                    if(reload) {
                        mw.reload_module_everywhere('content/manager');
                        mw.reload_module_everywhere('categories');
                        mw.url.windowDeleteHashParam('action');
                    }
                }
            });

        });
    },
    publish: function ($id) {
        var master = {};
        master.id = $id;
        mw.$(document.body).addClass("loading");
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
                mw.$(document.body).removeClass("loading");
                $('.mw-set-content-publish').hide();
                mw.$('.mw-set-content-unpublish').fadeIn();
                mw.askusertostay = false;
                mw.notification.success("Content is Published.");
            },
            error: function () {
                mw.$(document.body).removeClass("loading");
            },
            complete: function () {
                mw.$(document.body).removeClass("loading");
            }
        });
    },
    unpublish: function ($id) {
        var master = {};
        master.id = $id;
        mw.$(document.body).addClass("loading");

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
                mw.$(document.body).removeClass("loading");
                mw.$('.mw-set-content-unpublish').hide();
                mw.$('.mw-set-content-publish').fadeIn();
                mw.askusertostay = false;
                mw.notification.warning("Content is Unpublished.");
            },
            error: function () {
                mw.$(document.body).removeClass("loading");
            },
            complete: function () {
                mw.$(document.body).removeClass("loading");
            }
        });

    },
    save: function (data, e) {
        var master = {};
        var calc = {};
        e = e || {};
         if (!data.content) {

         } else {
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
        }
        if (!data.content_type) {
            data.content_type = "post";
        }
        if (!data.id) {
            data.id = 0;
        }
        master.title = data.title;
        master.content = data.content;
        mw.$(document.body).addClass("loading");
        mw.trigger('adminSaveStart');
        $.ajax({
            type: 'POST',
            url: e.url || (mw.settings.api_url + 'save_content_admin'),
            data: data,
            datatype: "json",
            async: true,

            error: function (data, x) {
                mw.$(document.body).removeClass("loading");
                if (typeof e.onError === 'function') {
                    e.onError.call(data.data || data);
                }
                mw.errorsHandle(data.responseJSON);
            },
            complete: function (a,b,c) {
                 mw.$(document.body).removeClass("loading");
                mw.trigger('adminSaveEnd');
            }
        }).done(function (data) {
            if(data.data) {
                data = data.data;
            }
            mw.$(document.body).removeClass("loading");
            if (typeof data === 'object' && typeof data.error != 'undefined') {
                if (typeof e.onError === 'function') {
                    e.onError.call(data);
                }
            }
            else {
                if (typeof e.onSuccess === 'function') {
                    e.onSuccess.call(data);
                }

            }
             mw.askusertostay = false;
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
