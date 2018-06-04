<script>
    mw.require("content.js");
    mw.require("files.js");
    mw.require("admin_custom_fields.js");
</script>
<script>
    /* FUNCTIONS */

    if (self !== parent && !!parent.mw) {

        window.top.iframe_editor_window = window.self;
    }


    mw.edit_content = {};

    mw.edit_content.saving = false;


    mw.edit_content.create_new = function () {
        mw.$('#<?php print $module_id ?>').attr("content-id", "0");
        mw.$('#<?php print $module_id ?>').removeAttr("just-saved");
        mw.reload_module('#<?php print $module_id ?>');
    };

    mw.edit_content.close_alert = function () {
        mw.$('#quickform-edit-content').show();
        mw.$('#post-added-alert-<?php print $rand; ?>').hide();

    };

    mw.edit_content.load_page_preview = function (element_id) {
        var element_id = element_id || 'mw-admin-content-iframe-editor';
        var area = mwd.getElementById(element_id);
        var parent_page = mw.$('#mw-parent-page-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var content_id = mw.$('#mw-content-id-value', '#<?php print $params['id'] ?>').val();
        var content_type = mw.$('#mw-content-type-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val()
        var subtype = mw.$('#mw-content-subtype', '#<?php print $params['id'] ?>').val();
        var subtype_value = mw.$('#mw-content-subtype-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var active_site_template = $('#mw-active-template-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var active_site_layout = $('#mw-layout-file-value-<?php print $rand; ?>').val();
        // var name = 'content/views/edit_default_inner';
        var name = 'content/views/layout_selector';
        var selector = '#mw-admin-edit-content-main-area';


        var callback = false;
        var attributes = {}
        attributes.parent_page = parent_page;
        attributes.content_id = content_id;
        attributes.content_id = content_id;
        attributes.content_type = content_type;
        attributes.subtype = subtype;
        attributes.subtype_value = subtype_value;
        attributes.active_site_template = active_site_template;
        attributes.active_site_layout = active_site_layout;
        attributes['template-selector-position'] = 'none';
        attributes['live-edit-overlay'] = true;
        attributes['edit_page_id'] = content_id;
        mw.load_module(name, selector, callback, attributes);
    }


    mw.edit_content.load_editor = function (element_id) {

        var parent_page = mw.$('#mw-parent-page-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var content_id = mw.$('#mw-content-id-value', '#<?php print $params['id'] ?>').val();
        var content_type = mw.$('#mw-content-type-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val()
        var subtype = mw.$('#mw-content-subtype', '#<?php print $params['id'] ?>').val();
        var subtype_value = mw.$('#mw-content-subtype-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var active_site_template = $('#mw-active-template-value-<?php print $rand; ?>', '#<?php print $params['id'] ?>').val();
        var active_site_layout = $('#mw-layout-file-value-<?php print $rand; ?>').val();
        var name = 'content/views/edit_default_inner';
        var selector = '#mw-admin-edit-content-main-area';


        var callback = false;
        var attributes = {}
        attributes.parent_page = parent_page;
        attributes.content_id = content_id;
        attributes.content_type = content_type;
        attributes.subtype = subtype;
        attributes.subtype_value = subtype_value;
        attributes.active_site_template = active_site_template;
        attributes.active_site_layout = active_site_layout;
        mw.load_module(name, selector, callback, attributes);
    }
    mw.edit_content.before_save = function () {
        mw.askusertostay = false;
        if (window.parent != undefined && window.parent.mw != undefined) {
            window.parent.mw.askusertostay = false;
        }
    }
    mw.edit_content.after_save = function (saved_id) {
        mw.askusertostay = false;
        var content_id = mw.$('#mw-content-id-value').val();
        var quick_add_holder = mwd.getElementById('mw-quick-content');
        if (quick_add_holder != null) {
            mw.tools.removeClass(quick_add_holder, 'loading');
        }
        if (content_id == 0) {
            if (saved_id !== undefined) {
                mw.$('#mw-content-id-value').val(saved_id);
            }
            <?php if($is_quick != false) : ?>
            mw.$('#quickform-edit-content').hide();
            mw.$('#post-added-alert-<?php print $rand; ?>').show();
            <?php endif; ?>
        }
        if (mw.notification != undefined) {
            mw.notification.success('<?php _e('Content saved!'); ?>');
        }
        if (parent !== self && !!parent.mw) {


            mw.reload_module_parent('posts');
            mw.reload_module_parent('shop/products');
            mw.reload_module_parent('shop/cart_add');
            mw.reload_module_parent('pages');
            mw.reload_module_parent('content');
            mw.reload_module_parent('custom_fields');
            mw.tools.removeClass(mwd.getElementById('mw-quick-content'), 'loading');
            mw.reload_module('pages');
            parent.mw.askusertostay = false;
            <?php if($is_current != false) :  ?>
            if (window.parent.mw.history != undefined) {
                setTimeout(function () {
                    window.parent.mw.history.load('latest_content_edit');
                }, 200);
            }
            <?php endif; ?>
        } else {
            mw.reload_module('[data-type="pages"]', function () {
                if (mw.$("#pages_tree_toolbar .mw_del_tree_content").length === 0) {
                    mw.$("#pages_tree_toolbar").removeClass("activated");
                    mw.treeRenderer.appendUI('#pages_tree_toolbar');
                    mw.tools.tree.recall(mwd.querySelector('.mw_pages_posts_tree'));
                }
                mw.tools.removeClass(mwd.getElementById('mw-quick-content'), 'loading');
            });
        }


    }

    mw.edit_content.set_category = function (id) {
        /* FILLING UP THE HIDDEN FIELDS as you change category or parent page */
        var names = [];
        var inputs = mwd.getElementById(id).querySelectorAll('input[type="checkbox"]'), i = 0, l = inputs.length;
        for (; i < l; i++) {
            if (inputs[i].checked === true) {
                names.push(inputs[i].value);
            }
        }
        if (names.length > 0) {
            mw.$('#mw_cat_selected_for_post').val(names.join(',')).trigger("change");
        } else {
            mw.$('#mw_cat_selected_for_post').val('__EMPTY_CATEGORIES__').trigger("change");
        }
        var names = [];
        var inputs = mwd.getElementById(id).querySelectorAll('input[type="radio"]'), i = 0, l = inputs.length;
        for (; i < l; i++) {
            if (inputs[i].checked === true) {
                names.push(inputs[i].value);
            }
        }
        if (names.length > 0) {
            mw.$('#mw-parent-page-value-<?php print $rand; ?>').val(names[0]).trigger("change");
        } else {
            mw.$('#mw-parent-page-value-<?php print $rand; ?>').val(0).trigger("change");
        }
    }

    mw.edit_content.render_category_tree = function (id) {
        if (mw.treeRenderer != undefined) {
            mw.treeRenderer.appendUI('#mw-category-selector-' + id);
            mw.admin.tag({
                tagholder: '#mw-post-added-' + id,
                items: ".mw-ui-check",
                itemsWrapper: mwd.querySelector('#mw-category-selector-' + id),
                method: 'parse',
                onTag: function () {
                    mw.edit_content.set_category('mw-category-selector-' + id);
                },
                onUntag: function (a) {
                    mw.edit_content.set_category('mw-category-selector-' + id);
                },
                onFound: function () {
                    CreateCategoryForPost(0)
                },
                onNotFound: function () {
                    CreateCategoryForPost(1)
                }
            });

            var tree_sidebar = mwd.getElementById('pages_tree_toolbar');
            if (tree_sidebar != null) {

                var selected = $('#mw-category-selector-' + id).find('.mw-ui-check-input-sel:checked');
                var active_bg_set = false
                if (selected != null) {
                    var last = selected.last().val();
                    $.each(selected, function (i, value) {
                        var cat_open = $(this).val();
                        if (cat_open != null) {
                            var tree_selected = tree_sidebar.querySelector('.category_element.item_' + cat_open + ' > a.pages_tree_link');
                            if (tree_selected != null) {

                                mw.tools.tree.open(tree_selected, true);

                            }

                        }
                    })

                    if (last != null && active_bg_set == false) {
                        var tree_selected = tree_sidebar.querySelector('.category_element.item_' + last + ' > a.pages_tree_link');
                        active_bg_set = true;
                        $(tree_selected).parent().addClass('active-bg')
                    }


                }
            }
            $(mwd.querySelectorAll('#mw-category-selector-' + id + " .pages_tree_item")).bind("mouseup", function (e) {
                if (!mw.tools.hasClass(e.target, 'mw_toggle_tree')) {
                    $(this).addClass("active");
                }
            });
        }
    }

    mw.edit_content.handle_form_submit = function (go_live) {


        if (mw.edit_content.saving) {
            return false;
        }
        mw.edit_content.saving = true;
        var go_live_edit = go_live || false;
        var el = mwd.getElementById('quickform-edit-content');
        if (el === null) {
            return;
        }

        mw.edit_content.before_save();
        var module = $(mw.tools.firstParentWithClass(el, 'module'));


        var data = mw.serializeFields(el);
        data.id = mw.$('#mw-content-id-value').val();


        module.addClass('loading');
        mw.content.save(data, {
            onSuccess: function (a) {
                mw.$('.mw-admin-go-live-now-btn').attr('content-id', this);
                mw.askusertostay = false;

                if (parent !== self && !!window.parent.mw) {
                    window.parent.mw.askusertostay = false;
                    if (typeof(data.is_active) !== 'undefined' && typeof(data.id) !== 'undefined') {
                        if ((data.id) != 0) {
                            if ((data.is_active) == 0) {
                                window.parent.mw.$('.mw-set-content-unpublish').hide();
                                window.parent.mw.$('.mw-set-content-publish').show();
                            }
                            else if ((data.is_active) == 1) {
                                window.parent.mw.$('.mw-set-content-publish').hide();
                                window.parent.mw.$('.mw-set-content-unpublish').show();
                            }
                        }

                    }
                }

                if (typeof(this) != "undefined") {
                    var inner_edits = mw.collect_inner_edit_fields();

                    if (inner_edits !== false) {
                        var save_inner_edit_data = inner_edits;
                        save_inner_edit_data.id = this;

                        var xhr = mw.save_inner_editable_fields(save_inner_edit_data);
                        xhr.success(function () {
                            $(window).trigger('adminSaveEnd');
                        });
                        xhr.fail(function () {
                            $(window).trigger('adminSaveFailed');
                        });

                    }
                }
                if (go_live_edit != false) {
                    if (parent !== self && !!window.parent.mw) {
                        if (window.parent.mw.drag != undefined && window.parent.mw.drag.save != undefined) {
                            window.parent.mw.drag.save();
                        }
                        window.parent.mw.askusertostay = false;
                    }
                    $.get('<?php print site_url('api_html/content_link/?id=') ?>' + this, function (data) {
                        window.top.location.href = data + '?editmode=y';
                    });
                }
                else {
                    $.get('<?php print site_url('api_html/content_link/?id=') ?>' + this, function (data) {
                        if (data == null) {
                            return false;
                        }
                        var slug = data.replace("<?php print site_url() ?>", "").replace("/", "");
                        mw.$("#edit-content-url").val(slug);
                        mw.$(".view-post-slug").html(slug);
                        mw.$("a.quick-post-done-link").attr("href", data + '?editmode=y');
                        mw.$("a.quick-post-done-link").html(data);
                    });
                    mw.$("#<?php print $module_id ?>").attr("content-id", this);
                    <?php if($is_quick != false) : ?>
                    //  mw.$("#<?php print $module_id ?>").attr("just-saved",this);
                    <?php else: ?>
                    //if (self === parent) {
                    if (self === parent) {
                        //var type =  el['subtype'];
                        mw.url.windowHashParam("action", "editpage:" + this);
                    }
                    <?php endif; ?>
                    mw.edit_content.after_save(this);
                }
                mw.edit_content.saving = false;


                $(window).trigger('adminSaveContentCompleted');

                if (self !== parent) {
                    if ((data.id) == 0) {
                        mw.$("#<?php print $module_id ?>").attr("content-id", this);

                        mw.reload_module("#<?php print $module_id ?>");
                    }
                }


            },
            onError: function () {
                $(window).trigger('adminSaveFailed');
                module.removeClass('loading');
                if (typeof this.title !== 'undefined') {
                    mw.notification.error('<?php _e('Please enter title'); ?>');

                    $('#content-title-field-row').animate({
                        backgroundColor: "red"
                    }, function(){
                        $('#content-title-field-row').animate({
                            backgroundColor: "transparent"
                        })
                    })

                }
                if (typeof this.content !== 'undefined') {
                    mw.notification.error('<?php _e('Please enter content'); ?>');
                }
                if (typeof this.error !== 'undefined') {
                    mw.session.checkPause = false;
                    mw.session.checkPauseExplicitly = false;
                    mw.session.logRequest();
                }
                mw.edit_content.saving = false;
            }
        });
    }

    mw.collect_inner_edit_fields = function (data) {
        var frame = mwd.querySelector('#mw-admin-content-iframe-editor iframe');
        if (frame === null) return false;
        var frameWindow = frame.contentWindow;
        if (typeof(frameWindow.mwd) === 'undefined') return false;
        var root = frameWindow.mwd.getElementById('mw-iframe-editor-area');
        var data = frameWindow.mw.drag.getData(root);
        return data;
    }

    mw.save_inner_editable_fields = function (data) {
        var xhr = $.ajax({
            type: 'POST',
            url: mw.settings.site_url + 'api/save_edit',
            data: data,
            datatype: "json"
        });
        return xhr;
    }


    /* END OF FUNCTIONS */

</script>
<script>
    $(mwd).ready(function () {


        $("#quickform-edit-content").on('keydown', "input[type='text']", function (e) {
            if(e.keyCode  == 13){
                e.preventDefault()
            }
        })


        mw.reload_module('#edit-post-gallery-main');


        mw.edit_content.load_editor();
        <?php if($just_saved != false) : ?>
        mw.$("#<?php print $module_id ?>").removeAttr("just-saved");
        <?php endif; ?>
        mw.edit_content.render_category_tree("<?php print $rand; ?>");
        mw.$("#quickform-edit-content").submit(function () {
            mw.edit_content.handle_form_submit();
            return false;
        });
        <?php if($data['id'] != 0) : ?>
        mw.$(".mw-admin-go-live-now-btn").attr('content-id', <?php print $data['id']; ?>);
        <?php endif; ?>
        mw.$('#mw-parent-page-value-<?php print $rand; ?>').on('change', function (e) {
            var iframe_ed = $('.mw-iframe-editor');


            var changed = iframe_ed.contents().find('.changed').size();
            if (changed == 0) {

                mw.edit_content.load_editor();
            }
            //mw.edit_content.load_editor();
        });
        $(window).bind('templateChanged', function (e) {

            var iframe_ed = $('.mw-iframe-editor')
            var changed = iframe_ed.contents().find('.changed').size();
            if (changed == 0) {
                // mw.edit_content.load_editor();
            }
            mw.edit_content.load_editor();
        });
        if (mwd.querySelector('.mw-iframe-editor') !== null) {
            mwd.querySelector('.mw-iframe-editor').onload = function () {
                $(window).bind('scroll', function () {
                    var scrolltop = $(window).scrollTop();
                    if (mwd.getElementById('mw-edit-page-editor-holder') !== null) {
                        var otop = mwd.getElementById('mw-edit-page-editor-holder').offsetTop;
                        if ((scrolltop + 100) > otop) {
                            var ewr = mwd.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
                            if (ewr === null) {
                                return false;
                            }
                            ewr.style.position = 'absolute';
                            ewr.style.top = scrolltop + otop + 'px';
                            ewr.style.top = scrolltop - otop /*+ mwd.querySelector('.admin-manage-toolbar').offsetTop*/ + mwd.querySelector('.admin-manage-toolbar').offsetHeight - 98 + 'px';
                            mw.$('.admin-manage-toolbar-scrolled').addClass('admin-manage-toolbar-scrolled-wysiwyg');
                            mw.tools.addClass(ewr, 'editor_wrapper_fixed');
                        }
                        else {
                            var ewr = mwd.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
                            if (ewr === null) {
                                return false;
                            }
                            ewr.style.position = 'static';
                            mw.$('.admin-manage-toolbar-scrolled').removeClass('admin-manage-toolbar-scrolled-wysiwyg');
                            mw.tools.removeClass(ewr, 'editor_wrapper_fixed');
                        }
                    }
                });
            }
        }

        var title_field_shanger = $('#content-title-field');

        if (title_field_shanger.length > 0) {
            $(title_field_shanger).unbind("change");
            $(title_field_shanger).bind("change", function () {
                var newtitle = $(this).val();
                $('#content-title-field-master').val(newtitle);
            });
        }

        $(".postbtnmore").on('mousedown', function () {
            $(this).remove()
        })

        mww.QTABS = mw.tools.tabGroup({
            nav: mw.$("#quick-add-post-options .mw-ui-abtn"),
            tabs: mw.$("#quick-add-post-options-items-holder .quick-add-post-options-item"),
            toggle: true,
            onclick: function (qtab) {

                var tabs = $(mwd.getElementById('quick-add-post-options-items-holder'));
                if (mw.$("#quick-add-post-options .mw-ui-abtn.active").length > 0) {
                    var tabsnav = $(mwd.getElementById('quick-add-post-options'));
                    var off = tabsnav.offset();
                    $(tabs).show();
                    QTABSArrow(this);
                    QTABMaxHeight();
                }
                else {
                    $(tabs).hide();
                }
                if (qtab.id === 'post-gallery-manager') {
                    $(qtab).width(mw.$("#mw-edit-page-editor-holder").width())
                } else if (qtab.id === 'quick-add-post-options-item-template') {
                    mw.reload_module('#mw-quick-add-choose-layout');
                }


                try {
                    mwd.querySelector('.mw-iframe-editor').contentWindow.GalleriesRemote()
                } catch (err) {
                }

            }
        });

        QTABMaxHeight = function () {
            var qt = mw.$('#quick-add-post-options-items-holder-container'),
                wh = $(window).height(),
                st = $(window).scrollTop();
            if (qt.length == 0) {
                return false;
            }
            qt.css('maxHeight', (wh - (qt.offset().top - st + 20)));
            qt.css('width', ($(".admin-manage-content-wrap").width()));
        }

        $(mww).bind('mousedown', function (e) {
            var el = mwd.getElementById('content-edit-settings-tabs-holder');
            var cac = mw.wysiwyg.validateCommonAncestorContainer(e.target);
            if (el != null && !el.contains(e.target)
                && !!cac
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-btn')
                && cac.className.indexOf('grammarly') !== -1
                && cac.querySelector('[class*="grammarly"]') === null
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-ghost')
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-card')) {
                mww.QTABS.unset()
                mw.$(".quick-add-post-options-item, #quick-add-post-options-items-holder").hide();
                mw.$("#quick-add-post-options .active").removeClass('active');
            }
        });

        mw.$(".mw-iframe-editor").bind("editorKeyup", function () {
            mw.tools.addClass(mwd.body, 'editorediting');
        });
        $(mwd.body).bind("mousedown", function () {
            mw.tools.removeClass(mwd.body, 'editorediting');
        });
        mw.$(".admin-manage-toolbar").bind("mousemove", function () {
            mw.tools.removeClass(mwd.body, 'editorediting');
        });

        $(window).bind("resize scroll", function () {

            QTABMaxHeight();
            /*

             $(window).scrollTop(0);
             mw.tools.toggleFullscreen(mwd.getElementById('pages_edit_container'));

             */
        });


        mw.tabs({
            nav: '#settings-tabs .mw-ui-btn-nav-tabs a',
            tabs: '#settings-tabs .mw-settings-tabs-content'
        });

        $('.btn-settings').on('click', function () {
            if ($('#settings-tabs').hasClass('hidden')) {
                $('#settings-tabs').slideDown();
                $('#settings-tabs').toggleClass('hidden');
            } else {
                $('#settings-tabs').slideUp();
                $('#settings-tabs').toggleClass('hidden');
            }

        });

        $("")
    });
</script>