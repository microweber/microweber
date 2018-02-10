mw.admin = {
    scrollBoxSettings: {
        height: 'auto',
        size: 5,
        distance: 5,
        position:(document.documentElement.dir == 'rtl' ? 'left': 'right')
    },
    scrollBox: function (selector, settings) {
        var settings = $.extend({}, mw.admin.scrollBoxSettings, settings);
        var el = mw.$(selector);

        if(typeof(el.slimScroll) == 'undefined'){
            return;
        }

        el.slimScroll(settings);
        var scroller = mw.$('.slimScrollBar', el[0].parentNode);
        scroller.bind('mousedown', function () {
            $(this).addClass('scrollMouseDown');
        });
        $(mwd.body).bind('mouseup', function () {
            mw.$('.scrollMouseDown').removeClass('scrollMouseDown');
        });
    },
    contentScrollBoxHeightMinus: 0,
    contentScrollBoxHeightFix: function (node) {
        mw.admin.contentScrollBoxHeightMinus = 0,
            exceptor = mw.tools.firstParentWithClass(node, 'scroll-height-exception-master');
        if (!exceptor) {
            return $(window).height();
        }
        mw.$('.scroll-height-exception', exceptor).each(function () {
            mw.admin.contentScrollBoxHeightMinus = mw.admin.contentScrollBoxHeightMinus + $(this).outerHeight(true);
        });

        return $(window).height() - mw.admin.contentScrollBoxHeightMinus;
    },
    contentScrollBox: function (selector, settings) {
        var el = mw.$(selector)[0];
        if (typeof el === 'undefined') {
            return false;
        }
        mw.admin.scrollBox(el, settings);
        var newheight = mw.admin.contentScrollBoxHeightFix(el)
        el.style.height = newheight + 'px';
        el.parentNode.style.height = newheight + 'px'
        $(window).bind('resize', function () {
            var newheight = mw.admin.contentScrollBoxHeightFix(el)
            el.style.height = newheight + 'px';
            el.parentNode.style.height = newheight + 'px';
            $(el).slimscroll({
                position:(document.documentElement.dir == 'rtl' ? 'left': 'right')
            });
        });
    },
    treeboxwidth: function () {
        /*if (mwd.querySelector('.tree-column-active') === null) {
            var w = mw.$('.fixed-side-column').width();
            mw.$('.tree-column').width(w);
        }*/
    },

    createContentBtns: function () {
        var create_content_btn = mwd.querySelectorAll('.create-content-btn');
        if (create_content_btn.length !== 0) {
            $(create_content_btn).each(function () {
                if (!this.mwtooltip) {
                    this.mwtooltip = mw.tooltip({
                        position: $(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center',
                        content: mw.$('#create-content-menu').html(),
                        element: this,
                        skin: 'mw-tooltip-dark mw-tooltip-action'
                    });
                    var tip = this.mwtooltip;
                    mw.$('.create-content-menu', this.mwtooltip).click(function () {
                        $(tip).hide();
                    });
                    var el = this;
                    this.mwtooltip.style.display = 'none';

                    $(this).on('click', function () {
                        mw.tools.tooltip.setPosition(this.mwtooltip, this, ($(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center'));
                        $(this).addClass('active');
                        $(this.mwtooltip).show();

                    });

                    $(document.body).on('click', function (e) {
                      if(!mw.tools.hasAnyOfClassesOnNodeOrParent(e.target, ['create-content-btn'])){
                        $(tip).hide();
                      }

                    });




                    /*
                    $(this).timeoutHover(function () {
                        mw.tools.tooltip.setPosition(this.mwtooltip, this, ($(this).dataset('tip') != '' ? $(this).dataset('tip') : 'bottom-center'));
                        $(el).addClass('active');
                        $(this.mwtooltip).show();
                    }, function () {
                        if (this.mwtooltip.originalOver === false) {
                            $(this.mwtooltip).hide();
                            $(el).removeClass('active');
                        }
                    });

                    $(this.mwtooltip).timeoutHover(function () {
                        $(el).addClass('active');
                    }, function () {
                        if (this.originalOver === false) {
                            $(this).hide();
                            $(el).removeClass('active');
                        }
                    });*/
                }
            });
        }
    },
    editor: {
        set: function (frame) {
            $(frame).width('100%');
            return;
            if (!!frame && frame !== null && !!frame.contentWindow) {
                var width_mbar = mw.$('#main-bar').width(),
                    tree = mwd.querySelector('.tree-column'),
                    width_tbar = $(tree).width(),
                    ww = $(window).width();
                if (tree.style.display === 'none') {
                    width_tbar = 0;
                }
                if (width_mbar > 200) {
                    width_mbar = 0;
                }
                $(frame)
                    .width(ww - width_tbar - width_mbar - 35)
                    .height(frame.contentWindow.document.body.offsetHeight);
            }
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
            $(frame).load(function () {
                frame.contentWindow.thisframe = frame;
                if (typeof frame.contentWindow.PrepareEditor === 'function') {
                    frame.contentWindow.PrepareEditor();
                }
                mw.admin.editor.set(frame);
                $(frame.contentWindow.document.body).bind('keyup paste', function () {
                    mw.admin.editor.set(frame);
                });
            });
            mw.admin.editor.set(frame);
            $(window).bind('resize', function () {
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
        var p = p || false;
        var locked = mw.cookie.ui('adminsidebarpin') == 'true';
        AdminCategoryTree = mwd.querySelector('.tree-column');
		if(AdminCategoryTree == null){
		return;	
		}
        if ((p != false) && (p.contains('edit') || p.contains('new'))) {
            if (AdminCategoryTree !== null) {
                AdminCategoryTree.treewidthactivated = true;
                !locked ? mw.$(AdminCategoryTree).addClass('tree-column-active') : '';
                mw.$('.tree-column').click(function () {
                    if (AdminCategoryTree.treewidthactivated === true) {
                        $(this).removeClass('tree-column-active');
                        mw.admin.treeboxwidth();
                        clearInterval(mw.admin.manageToolbarInt);
                        mw.admin.manageToolbarInt = setInterval(function () {
                            mw.admin.manageToolbarSet();
                        }, 5);
                        setTimeout(function () {
                            clearInterval(mw.admin.manageToolbarInt);
                        }, 205);
                    }
                });
                $(mwd.body).bind('click', function (e) {

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
    titleColumnNavWidth: function () {
        var _n = mwd.getElementById('content-title-field-buttons');
        if (_n !== null) {
            var n1 = _n.querySelector('.content-title-field-buttons');
            var n2 = _n.querySelector('.mw-ui-btn-nav');
            if (n1 !== null) {
                _n.style.width = n1.offsetWidth + 20 + 'px';
            }
            else if (n2 !== null) {
                _n.style.width = n2.offsetWidth + 20 + 'px';
            }
        }
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
            $(mw.tools._titleTip).hide();
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
            $(mw.admin.postStatesTip).addClass('posts-states-tooltip');
            mw.admin.postStatesTip.style.display = 'none';
            mw.admin.postStatesTip._over = false;
            $(mw.admin.postStatesTip).hover(function () {
                this._over = true;
            }, function () {
                this._over = false;
                //mw.admin.postStatesTip.style.display = 'none';
            });
            $(mwd.body).bind('mousedown', function (e) {
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
            }
            else if (a == 'unpublish') {
                mw.$('.btn-publish').removeClass('active');
                mw.$('.btn-unpublish').addClass('active');
                mw.$('.btn-posts-state > span').attr('class', 'mw-icon-unpublish').parent().dataset("tip", mw.msg.unpublished);
                mw.$('#is_post_active').val('0');
                mw.$('.btn-posts-state.tip-disabled').removeClass('tip-disabled');
                mw.admin.postStatesTip.style.display = 'none';
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
    showLinkNav: function () {
        var all = mwd.querySelector('.select_posts_for_action:checked');
        if (all === null) {
            mw.$('.mw-ui-link-nav').hide();
        }
        else {
            mw.$('.mw-ui-link-nav').show();
        }
    },
    simpleRotator: function (rotator) {
        if (rotator === null) {
            return undefined;
        }
        if (typeof rotator !== 'undefined') {
            if (!$(rotator).hasClass('activated')) {
                $(rotator).addClass('activated')
                var all = rotator.children;
                var l = all.length;
                $(all).addClass('mw-simple-rotator-item');

                rotator.go = function (where, callback, method) {
                    var method = method || 'animate';
                    $(rotator).dataset('state', where);
                    $(rotator.children).hide().eq(where).show()
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
                        rotator.ongoes.push(c)
                    };
                }
            }
        }
        return rotator;
    },
    tag: function (obj) {


        var o = {};
        var itemsWrapper = obj.itemsWrapper;

        if (itemsWrapper == null) return false;
        $(itemsWrapper).hide();
        var items = obj.itemsWrapper.querySelectorAll(obj.items);
        var tagMethod = obj.method || 'parse';

        var tagholder = $(obj.tagholder);
        var field = mw.$('input[type="text"]', tagholder[0]);

        if (field == null) {
            return false;
        }
        var def = field.dataset('default');
        o.createTag = function (el) {
            var span_holder = mwd.createElement('span');
            var span_x = mwd.createElement('span');

            span_holder.className = 'mw-ui-btn mw-ui-btn-small';
            span_holder.id = 'id-' + el.value;
            span_holder.innerHTML = '<span class="tag-label-content">' + el.parentNode.textContent + '</span>';

            var icon = mwd.createElement('i');
            icon.className = mw.tools.firstParentWithTag(el, 'li').className;

            $(span_holder).prepend(icon);

            span_holder.onclick = function (e) {

                if (e.target.className != 'mw-icon-close') {
                    mw.tools.highlight(mw.$('item_' + el.value)[0], 'green');

                    var input = itemsWrapper.querySelector(".item_" + el.value + " input");

                    if (input !== null) {

                        mw.tools.foreachParents(input, function (loop) {

                            if (mw.tools.hasClass(this.className, 'mw-ui-category-selector')) {
                                mw.tools.stopLoop(loop);
                            }
                            if (this.tagName === 'LI') {
                                $(this).addClass('active');
                            }
                        });

                        var label = itemsWrapper.querySelector(".item_" + el.value + " label");

                        setTimeout(function () {
                            label.scrollIntoView(false);

                            mw.tools.highlightStop(mw.$(".highlighted").removeClass("highlighted"));
                            mw.tools.highlight(label);
                            $(label).addClass("highlighted");
                        }, 55);
                    }
                }
            }
            span_x.className = 'mw-icon-close';
            span_x.onclick = function () {
                o.untag(this.parentNode, el);
            }
            span_holder.appendChild(span_x);
            return span_holder;
        }

        o.rend = function (method, el) {
            var method = method || 'parse';
            if (method === 'parse' || el === 'all') {
                var html = [];
                var checks = itemsWrapper.querySelectorAll('input[type="radio"], input[type="checkbox"]');
                $(checks).each(function () {
                    if (this.checked == true) {
                        $(mw.tools.firstParentWithClass(this, 'mw-ui-check')).addClass("active");
                        var tag = o.createTag(this);
                        html.push(tag);
                    }
                    else {
                        $(mw.tools.firstParentWithClass(this, 'mw-ui-check')).removeClass("active");
                    }
                });
                $(tagholder).prepend(html);
            }
            else if (method === 'prepend') {
                var tag = o.createTag(el);
                if ($('.mw-ui-btn', tagholder).length == 0) {
                    tagholder.prepend(tag);
                }
                else {
                    $('.mw-ui-btn:last', tagholder).after(tag);
                }
            }
        }

        o.untag = function (pill, input) {
            $(pill).remove();
            if (!!input) {
                $(input)[0].checked = false;
                $(mw.tools.firstParentWithClass($(input)[0], 'mw-ui-check')).removeClass("active");
            }
            if (typeof obj.onUntag === 'function') {
                obj.onUntag.call(o);
            }
        }

        o.rend(tagMethod, 'all');

        tagholder.click(function (e) {

            if (e.target.tagName != 'INPUT') {
                field.focus();
            }

            itemsWrapper.style.top = '100%';
            itemsWrapper.style.display = 'block';
            var off = $(itemsWrapper).offset();
            if ((off.top + $(itemsWrapper).outerHeight()) > ($(window).scrollTop() + $(window).height())) {
                itemsWrapper.style.top = 'auto';
                itemsWrapper.style.bottom = '100%';
            }
            else {
                itemsWrapper.style.top = '100%';
                itemsWrapper.style.bottom = 'auto';
            }
            if (itemsWrapper.querySelector('input').binded != true) {
                itemsWrapper.querySelector('input').binded = true;
                var checks = itemsWrapper.querySelectorAll('input[type="radio"], input[type="checkbox"]');
                $(checks).commuter(function () {
                    if (tagMethod === 'prepend') {
                        o.rend(tagMethod, this);
                    }
                    else {
                        $('.mw-ui-btn', tagholder).remove();
                        o.rend(tagMethod);
                    }
                    if (typeof obj.onTag === 'function') {
                        obj.onTag.call(o);
                    }
                    field.val('');
                }, function () {
                    o.untag($("#id-" + this.value, tagholder));
                });

                tagholder.hover(function () {
                    $(this).addClass('mw-tagger-hover')
                }, function () {
                    $(this).removeClass('mw-tagger-hover')
                });
                $(itemsWrapper).hover(function () {
                    $(this).addClass('mw-tagger-hover')
                }, function () {
                    $(this).removeClass('mw-tagger-hover')
                });
                $(mwd.body).bind('mousedown', function (e) {
                    if (mw.$(".mw-tagger-hover").length == 0) {
                        itemsWrapper.style.display = 'none';
                        if (mw.$('.mw-ui-btn', tagholder).length == 0) {
                            field.val(def);
                        }
                        else {
                            field.val('');
                        }
                        $(items).show();
                    }
                });
            }
        });
        field[0].tagSettings = obj;
        field.keyup(function () {
            var val = $(this).val();
            var el = this;
            var foundlen = 0;
            mw.tools.search(val, items, function (found) {
                if (found) {
                    foundlen++;
                    $(this).show();
                }
                else {
                    $(this).hide();
                }
            });
            if (foundlen === 0) {
                if (typeof el.tagSettings.onNotFound === 'function') {
                    el.tagSettings.onNotFound.call();
                }
            }
            else {
                if (typeof el.tagSettings.onFound === 'function') {
                    el.tagSettings.onFound.call();
                }
            }
        });
        field.focus(function () {
            this.value === def ? this.value = '' : '';
        });
        field.blur(function () {
            if (this.value === '' && mw.$('.mw-ui-btn', tagholder).length == 0) {
                this.value = def;
            }
        });
        return o;
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
        $(uploader).bind("FileUploaded", function (obj, data) {
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
    treeRadioSelector: function (holder, callback) {
        var module = '<div class="mw-tree"><div data-type="categories/selector" id="categoryparent" input-name="categoryparent" input-name-categories="categoryparent" active_ids="4" input-type-categories="radio" class="module"></div></div>';
        mw.$(holder).append(module);
        mw.reload_module('#categoryparent', function () {
            mw.treeRenderer.appendUI('#categoryparent');
            if (typeof callback === 'function') {
                mw.$('#categoryparent input').commuter(function () {
                    callback.call(this.value);
                });
            }
        });
    },
    ChangeListener: function (acase, win) {
        var acase = acase || 'contentmanagement';
        if (acase === 'contentmanagement') {
            mw.$('#content-title-field').bind('keyup paste', function () {
                mw.askusertostay = true;
            });
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
                $(links[li]).bind('mouseup', function (e) {
                    if (mw.askusertostay === true) {
                        e.preventDefault();
                        return false;

                    }
                });
            }
        }
    },
    insertGallery: function () {
        var id = 'mwemodule-' + mw.random(), framewindow = mwd.querySelector('.mw-iframe-editor').contentWindow;
        var el = '<div data-type="pictures" id="' + id + '" class="module">&nbsp;</div>';
        framewindow.mw.wysiwyg.insert_html(el);
        framewindow.mw.load_module('pictures', '#' + id, function () {
            if(typeof framewindow.mw.drag != "undefined"){
                framewindow.mw.drag.fixes();
                setTimeout(function () {
                    framewindow.mw.drag.fix_placeholders();
                }, 40);
                framewindow.mw.resizable_columns();
                framewindow.mw.dropable.hide();
            }

        });
        try {
            QTABS.unset(0);
        } catch (err) {
        }
        mw.$(".tip-box .mw-tooltip-arrow").css('left', -9999);
    }
}


mw.contactForm = function () {
    mw.modalFrame({
        url: 'https://microweber.com/contact-frame/',
        overlay: true,
        height: 600
    })
}


$(mwd).ready(function () {
    mw.admin.mobileMessage();
    mw.admin.treeboxwidth();
    $(mwd.body).bind('keydown', function (e) {
        if (mw.event.key(e, 8) && (e.target.nodeName === 'DIV' || e.target === mwd.body)) {
            mw.event.cancel(e);
            return false;
        }
    });


    mw.admin.beforeLeaveLocker();

    $(document.body).on('click', '[data-href]', function(e){
        e.preventDefault();
        e.stopPropagation()
        location.href = e.target.getAttribute('data-href')
    })


});

$(mww).bind('load', function () {
  $(".mobile-tree-menu").on('click', function(){
    $(".tree-column").toggleClass('tree-column-mobile-active')
  })
    mw.admin.contentScrollBox('.fixed-side-column-container');
    mw.admin.contentScrollBox('#mw-admin-main-menu', {color: 'white'});
    var locked = mw.cookie.ui('adminsidebarpin');

    if(locked == ''){
        mw.admin.CategoryTreeWidth(mw.url.getHashParams(location.hash).action);
        mw.cookie.ui('adminsidebarpin', 'true');
        $(".tree-column-active").removeClass('tree-column-active')
    }
    else{
        mw.admin.treeboxwidth();
    }

    mw.on.moduleReload('pages_tree_toolbar', function () {

        setTimeout(function () {
            mw.admin.treeboxwidth();
        }, 90);
    });
    mw.admin.createContentBtns();
    mw.admin.manageToolbarSet();


    if (mwd.getElementById('main-bar-user-menu-link') !== null) {

        //mainbarusermenulink = mw.tooltip({
        //    content: mw.$('#main-bar-user-tip').html(),
        //    position: 'center-right',
        //    group: 'main-bar-user-tip',
        //    element: mwd.getElementById('main-bar-user-menu-link')
        //});
        //mainbarusermenulink.id = 'main-bar-user-menu-tooltip';

       // mw.tools.addClass(mainbarusermenulink, 'main-bar-user-menu-tooltip');
      //  mw.tools.tooltip.setPosition(mainbarusermenulink, mwd.getElementById('main-bar-user-menu-link'), 'top-left');
      //  mainbarusermenulink.style.display = 'none';

        $(document.body).bind('click', function (e) {
          //  mainbarusermenulink.style.display = 'block';


        //    mw.$('#main-bar-user-tip').toggle();
            //
            if (e.target !== mwd.getElementById('main-bar-user-menu-link') && e.target.parentNode !== mwd.getElementById('main-bar-user-menu-link')) {
               // mw.$('.main-bar-user-menu-tooltip').removeClass('main-bar-user-menu-tooltip-active');
                mw.$('#main-bar-user-tip').removeClass('main-bar-user-tip-active');
               //  mw.$('#main-bar-user-tip:visible').hide();
            }
            else {

              //  mw.$('#main-bar-user-tip').show();
              //  mw.$('.main-bar-user-menu-tooltip').toggleClass('main-bar-user-menu-tooltip-active');
                mw.$('#main-bar-user-tip').toggleClass('main-bar-user-tip-active');
            }
            //mw.tools.tooltip.setPosition(mainbarusermenulink, mwd.getElementById('main-bar-user-menu-link'), 'center-right');

        });
    }



    mw.$('#pin-sidebar').on('click', function () {
        var locked = mw.cookie.ui('adminsidebarpin');
        if (locked == 'false') {
            mw.cookie.ui('adminsidebarpin', 'true');
        }
        else {
            mw.cookie.ui('adminsidebarpin', 'false');
        }
    });


    mw.cookie.onchange('adminsidebarpin', function () {
        if (this == 'true') {
            mw.$('#pin-sidebar').addClass('active');
        }
        else {
            mw.$('#pin-sidebar').removeClass('active');
        }
    });


    if (mw.cookie.ui('adminsidebarpin') == 'true') {
        mw.$('#pin-sidebar').addClass('active');
    }

    $(window).bind('adminSaveStart', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .mw-ui-btn[type="submit"]');
        btn.innerHTML = mw.msg.saving + '...';
    });
    $(window).bind('adminSaveEnd', function () {
        var btn = mwd.querySelector('#content-title-field-buttons .mw-ui-btn[type="submit"]');
        btn.innerHTML = mw.msg.save;
    });




});


$(mww).bind('hashchange', function () {
    mw.admin.treeboxwidth();
});

$(mww).bind('scroll resize load', function (e) {
    if (e.type == "scroll" || e.type == 'resize') {
        mw.admin.manageToolbarSet();
    }
    if (self === top) {
        var bottommenu = mwd.getElementById('mw-admin-main-menu-bottom');
        if (bottommenu !== null) {
            var usermenu = mwd.getElementById('user-menu'),
                lft = bottommenu.previousElementSibling,
                wh = $(window).height();

                if(lft === null){
                    bottommenu.style.position = '';
                    return;
                }

            if (wh < ($(lft).offset().top - $(window).scrollTop() + lft.offsetHeight + usermenu.offsetHeight + bottommenu.offsetHeight)) {
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
    var el = $(el);
    if (el == null) {
        return;
    }
    if (el.length == 0) {
        return;
    }
    var left = el.offset().left - $(mwd.getElementById('quick-add-post-options')).offset().left + (el[0].offsetWidth / 2) - 5;
    mw.$('#quick-add-post-options-items-holder .mw-tooltip-arrow').css({left: left});
}

