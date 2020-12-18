mw.require('tree.js');
mw.require('tags.js');


mw.admin = {
    scrollBoxSettings: {
        height: 'auto',
        size: 5,
        distance: 5,
        position:(document.documentElement.dir === 'rtl' ? 'left': 'right')
    },
    scrollBox: function (selector, settings) {
        settings = $.extend({}, mw.admin.scrollBoxSettings, settings);
        var el = mw.$(selector);

        if(!el.slimScroll){
            return;
        }

        //el.slimScroll(settings);
        var scroller = mw.$('.slimScrollBar', el[0].parentNode);
        scroller.bind('mousedown', function () {
            mw.$(this).addClass('scrollMouseDown');
        });
        mw.$(mwd.body).bind('mouseup', function () {
            mw.$('.scrollMouseDown').removeClass('scrollMouseDown');
        });
    },
    contentScrollBoxHeightFix: function (node) {
        var exceptor = mw.tools.firstParentWithClass(node, 'scroll-height-exception-master');
        var contentScrollBoxHeightMinus = ($(exceptor).offset().top - mw.$(window).scrollTop());
        if (!exceptor) {
            return mw.$(window).height();
        }
        mw.$('.scroll-height-exception', exceptor).each(function () {
            contentScrollBoxHeightMinus = contentScrollBoxHeightMinus + mw.$(this).outerHeight(true);
        });

        return mw.$(window).height() - contentScrollBoxHeightMinus;
    },
    contentScrollBox: function (selector, settings) {
        var el = mw.$(selector)[0];
        if (typeof el === 'undefined') {
            return false;
        }
        mw.admin.scrollBox(el, settings);
        var newheight = mw.admin.contentScrollBoxHeightFix(el);
        el.style.height = newheight + 'px';
        mw.$(window).bind('resize', function () {
            var newheight = mw.admin.contentScrollBoxHeightFix(el);
            el.style.height = newheight + 'px';
        });
    },


    createContentBtns: function () {
        var create_content_btn = mwd.querySelectorAll('.create-content-btn');
        if (create_content_btn.length !== 0) {
            mw.$(create_content_btn).each(function () {
                if (!this.mwtooltip) {
                    this.mwtooltip = mw.tooltip({
                        position: mw.$(this).dataset('tip') != '' ? mw.$(this).dataset('tip') : 'bottom-center',
                        content: mw.$('#create-content-menu').html(),
                        element: this,
                        skin: 'mw-tooltip-dark mw-tooltip-action'
                    });
                    var tip = this.mwtooltip;
                    mw.$('.create-content-menu', this.mwtooltip).click(function () {
                        mw.$(tip).hide();
                    });
                    var el = this;
                    this.mwtooltip.style.display = 'none';
                    this.__tooltipActive = false;
                    mw.$(this).on('click', function () {
                        if(!this.__tooltipActive){
                            this.__tooltipActive = true;
                            mw.tools.tooltip.setPosition(this.mwtooltip, this, ($(this).dataset('tip') != '' ? mw.$(this).dataset('tip') : 'bottom-center'));
                            mw.$(this).addClass('active');
                            mw.$(this.mwtooltip).show();
                        }
                        else{
                            this.__tooltipActive = false;
                            mw.$(this).removeClass('active');
                            mw.$(this.mwtooltip).hide();
                        }


                    });

                    mw.$(document.body).on('click', function (e) {
                      if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['create-content-btn'])){

                          var create_content_btn = mwd.querySelectorAll('.create-content-btn');
                          mw.$(create_content_btn).each(function () {
                              mw.$(this.mwtooltip).hide();
                              this.__tooltipActive = false;
                              mw.$(this).removeClass('active');
                          })

                      }

                    });





                }
            });
        }
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
            var params = params || {};
            if (typeof params === 'object') {
                if (typeof params.src != 'undefined') {
                    delete(params.src);
                }
            }
            var params = typeof params === 'object' ? json2url(params) : params;
            var area = mw.$(area);
            var frame = mwd.createElement('iframe');
            frame.src = mw.external_tool('wysiwyg?' + params);
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
    manageToolbarInt: null,
    manageToolbarSet: function () {
        return false;
        var toolbar = mwd.querySelector('.admin-manage-toolbar');
        if (toolbar === null) {
            return false;
        }

        if (mw.admin.manageToolbarQuickNav === null && mwd.getElementById('content-edit-settings-tabs') !== null) {
            mw.admin.manageToolbarQuickNav = mwd.getElementById('content-edit-settings-tabs');
        }
        if (mw.admin.manageToolbarQuickNav !== null) {
            if ((scrolltop) > 0) {
                if (mwd.getElementById('content-edit-settings-tabs') != null) {

                    mw.$(".admin-manage-toolbar-scrolled").addClass('fix-tabs');
                }
            }
            else {

                mw.$(".admin-manage-toolbar-scrolled").removeClass('fix-tabs');
            }
            QTABSArrow('#quick-add-post-options .active');
        }
    },
    CategoryTreeWidth: function (p) {
        p = p || false;
        AdminCategoryTree = mwd.querySelector('.tree-column');
		if(AdminCategoryTree == null){
		return;
		}
        if (p && (p.contains('edit') || p.contains('new'))) {
            if (AdminCategoryTree !== null) {
                AdminCategoryTree.treewidthactivated = true;
                mw.$('.tree-column').click(function () {
                    if (AdminCategoryTree.treewidthactivated === true) {
                        mw.$(this).removeClass('tree-column-active');
                        clearInterval(mw.admin.manageToolbarInt);
                        mw.admin.manageToolbarInt = setInterval(function () {
                            mw.admin.manageToolbarSet();
                        }, 5);
                        setTimeout(function () {
                            clearInterval(mw.admin.manageToolbarInt);
                        }, 205);
                    }
                });
                mw.$(mwd.body).bind('click', function (e) {

                    if (AdminCategoryTree.treewidthactivated === true && mw.cookie.ui('adminsidebarpin') !== 'true') {
                        if (!mw.tools.hasParentsWithClass(e.target, 'tree-column')) {
                            mw.$(AdminCategoryTree).addClass('tree-column-active');
                            mw.admin.manageToolbarSet();
                            clearInterval(mw.admin.manageToolbarInt);
                            mw.admin.manageToolbarInt = setInterval(function () {
                                mw.admin.manageToolbarSet();
                            }, 5);
                            setTimeout(function () {
                                clearInterval(mw.admin.manageToolbarInt);
                            }, 205);
                        }
                    }
                });
            }
        }
        else {
            mw.$(AdminCategoryTree).removeClass('tree-column-active');
            AdminCategoryTree.treewidthactivated = false;
        }
        clearInterval(mw.admin.manageToolbarInt);
        mw.admin.manageToolbarInt = setInterval(function () {
            mw.admin.manageToolbarSet();
        }, 5);
        setTimeout(function () {
            clearInterval(mw.admin.manageToolbarInt);
        }, 205);
    },
    insertModule: function (module) {

        mwd.querySelector('.mw-iframe-editor').contentWindow.InsertModule(module);
    },

    postStates: {
        show: function (el, pos) {
            if (!mw.admin.postStatesTip) {
                mw.admin.postStates.build();
            }
            var el = el || mwd.querySelector('.btn-posts-state');
            var pos = pos || 'bottom-left';
            mw.tools.tooltip.setPosition(mw.admin.postStatesTip, el, pos);
            mw.admin.postStatesTip.style.display = 'block';
            mw.$('.btn-posts-state.tip').addClass('tip-disabled');
            mw.$(mw.tools._titleTip).hide();
        },
        hide: function (e, d) {
            if (!mw.admin.postStatesTip) {
                mw.admin.postStates.build();
            }
            if (mw.admin.postStatesTip._over == false) {
                mw.admin.postStatesTip.style.display = 'none';
            }
            mw.$('.btn-posts-state.tip').removeClass('tip-disabled');
        },
        timeoutHide: function () {
            if (!mw.admin.postStatesTip) {
                mw.admin.postStates.build();
            }
            setTimeout(function () {
                if (mw.admin.postStatesTip._over == false) {
                    mw.admin.postStatesTip.style.display = 'none';
                }
            }, 444);
        },
        build: function () {
            mw.admin.postStatesTip = mw.tooltip({
                content: mwd.getElementById('post-states-tip').innerHTML,
                position: 'bottom-left',
                element: '.btn-posts-state'
            });
            mw.$(mw.admin.postStatesTip).addClass('posts-states-tooltip');
            mw.admin.postStatesTip.style.display = 'none';
            mw.admin.postStatesTip._over = false;
            mw.$(mw.admin.postStatesTip).hover(function () {
                this._over = true;
            }, function () {
                this._over = false;
                //mw.admin.postStatesTip.style.display = 'none';
            });
            mw.$(mwd.body).bind('mousedown', function (e) {
                if (mw.admin.postStatesTip._over === false && mw.admin.postStatesTip.style.display == 'block' && !mw.tools.hasClass(e.target, 'btn-posts-state') && !mw.tools.hasParentsWithClass(e.target, 'btn-posts-state')) {
                    mw.admin.postStatesTip.style.display = 'none';
                }
            });
        },
        set: function (a) {
            if (a == 'publish') {
                mw.$('.btn-publish').addClass('active');
                mw.$('.btn-unpublish').removeClass('active');
                mw.$('.btn-posts-state > span').attr('class', 'mw-icon-check').parent().dataset("tip", mw.msg.published);
                mw.$('#is_post_active').val('1');
                mw.$('.btn-posts-state.tip-disabled').removeClass('tip-disabled');
                mw.admin.postStatesTip.style.display = 'none';
                mw.$(".btn-posts-state").html($('.btn-publish').html())
            }
            else if (a == 'unpublish') {
                mw.$('.btn-publish').removeClass('active');
                mw.$('.btn-unpublish').addClass('active');
                mw.$('.btn-posts-state > span').attr('class', 'mw-icon-unpublish').parent().dataset("tip", mw.msg.unpublished);
                mw.$('#is_post_active').val('0');
                mw.$('.btn-posts-state.tip-disabled').removeClass('tip-disabled');
                mw.admin.postStatesTip.style.display = 'none';
                mw.$(".btn-posts-state").html($('.btn-unpublish').html())
            }


        },
        toggle: function () {
            if (!mw.admin.postStatesTip || mw.admin.postStatesTip.style.display == 'none') {
                mw.admin.postStates.show();
            }
            else {
                mw.admin.postStates.hide();
            }
        }
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
                    var method = method || 'animate';
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
                }
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
    mobileMessage: function (set, val) {
        if (!!set) {
            mw.cookie.ui('ignoremobilemessage', val)
        }
        var cookie = mw.cookie.ui('ignoremobilemessage');
        if (cookie == 'true') {
            mw.$('#mobile-message').invisible();
        }
        else {
            mw.$('#mobile-message').visibilityDefault();
        }
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
    mw.modalFrame({
        url: 'https://microweber.com/contact-frame/',
        overlay: true,
        height: 600
    })
};


$(mwd).ready(function () {
    mw.admin.mobileMessage();
    mw.$(mwd.body).bind('keydown', function (e) {
        if (mw.event.key(e, 8) && (e.target.nodeName === 'DIV' || e.target === mwd.body)) {
            mw.event.cancel(e);
            return false;
        }
    });


    mw.admin.beforeLeaveLocker();

    mw.$(document.body).on('click', '[data-href]', function(e){
        e.preventDefault();
        e.stopPropagation();
        location.href = e.target.getAttribute('data-href')
    })


});

$(mww).bind('load', function () {
  mw.$(".mobile-tree-menu").on('click', function(){
    mw.$(".tree-column").toggleClass('tree-column-mobile-active')
  })
    mw.admin.contentScrollBox('.fixed-side-column-container');
    mw.admin.contentScrollBox('#mw-admin-main-menu', {color: 'white'});


    mw.on.moduleReload('pages_tree_toolbar', function () {

    });
    mw.admin.createContentBtns();
    mw.admin.manageToolbarSet();


    if (mwd.getElementById('main-bar-user-menu-link') !== null) {

        mw.$(document.body).bind('click', function (e) {
            if (e.target !== mwd.getElementById('main-bar-user-menu-link') && e.target.parentNode !== mwd.getElementById('main-bar-user-menu-link')) {
                mw.$('#main-bar-user-tip').removeClass('main-bar-user-tip-active');
            }
            else {

                mw.$('#main-bar-user-tip').toggleClass('main-bar-user-tip-active');
            }
        });
    }

    mw.$(window).on('adminSaveStart', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.saving + '...';
    });
    mw.$(window).on('adminSaveEnd', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .btn-save span');
        btn.innerHTML = mw.msg.save;
    });

    mw.$(".dr-item-table > table").click(function(){
        mw.$(this).toggleClass('active').next().stop().slideToggle().parents('.dr-item').toggleClass('active')
    })

});


$(mww).on('scroll resize load', function (e) {
    if (e.type === "scroll" || e.type === 'resize') {
        mw.admin.manageToolbarSet();
    }
    if (self === top) {
        var bottommenu = mwd.getElementById('mw-admin-main-menu-bottom');
        if (bottommenu !== null) {
            var usermenu = mwd.getElementById('user-menu'),
                lft = bottommenu.previousElementSibling,
                wh = mw.$(window).height();

                if(lft === null){
                    bottommenu.style.position = '';
                    return;
                }

            if (wh < ($(lft).offset().top - mw.$(window).scrollTop() + lft.offsetHeight + usermenu.offsetHeight + bottommenu.offsetHeight)) {
                bottommenu.style.position = "static";
            }
            else {
                bottommenu.style.position = '';
            }

        }

    }
});
mw.on.moduleReload('pages_edit_container', function () {
    mw.admin.createContentBtns();
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

