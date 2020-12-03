mw.require('tree.js');
mw.require('link-editor.js');
mw.require('tags.js');
mw.require('tags.js');
mw.require(mw.settings.modules_url + '/categories/categories.js');




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
        var loc = $(this).attr('data-href');
        if (mw.askusertostay) {
            mw.confirm(mw.lang("Continue without saving") + '?', function () {
                mw.askusertostay = false;
                location.href = loc;
            });
        } else {
            location.href = loc;
        }
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

    mw.on('adminSaveStart saveStart', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.saving + '...';
    });
    mw.on('adminSaveEnd saveEnd', function () {
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

