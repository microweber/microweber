<script>
    mw.require("content.js");
    mw.require("files.js");
    mw.require("admin_custom_fields.js");
</script>
<script>
    /* FUNCTIONS */

    if (self !== parent && !!parent.mw) {

        mw.top().win.iframe_editor_window = window.self;
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
            window.mw.parent().askusertostay = false;
        }
    }
    mw.edit_content.after_save = function (saved_id) {
        var saved_id = typeof saved_id === "number" ? saved_id : saved_id.id;

        mw.askusertostay = false;
        mw.$('.post-header-content-changed').removeClass('post-header-content-changed')
        var content_id = mw.$('#mw-content-id-value').val();
        var quick_add_holder = document.getElementById('mw-quick-content');
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
            mw.notification.success('<?php _ejs('Content saved!'); ?>');
        }

        mw.reload_module('content/views/edit_default_sidebar_variants');

        if (parent !== self && !!parent.mw) {

            mw.reload_module_parent('posts');
            mw.reload_module_parent('shop/products');
            mw.reload_module_parent('shop/cart_add');
            mw.reload_module_parent('pages');
            mw.reload_module_parent('content');
            mw.reload_module_parent('custom_fields');
            mw.tools.removeClass(document.getElementById('mw-quick-content'), 'loading');
            mw.reload_module('pages');
            mw.parent().askusertostay = false;
        } else {
            mw.reload_module('[data-type="pages"]', function () {
                if (mw.$("#pages_tree_toolbar .mw_del_tree_content").length === 0) {
                    mw.$("#pages_tree_toolbar").removeClass("activated");
                    var action = mw.url.windowHashParam('action');
                    if (action) {
                        var id = action.split(':')[1];
                        if (id) {
                            $('[data-page-id="' + id + '"]').addClass("active-bg")
                        }
                    }


                }
                mw.tools.removeClass(document.getElementById('mw-quick-content'), 'loading');
            });
        }
    }

    mw.edit_content.set_category = function (id) {
        /* FILLING UP THE HIDDEN FIELDS as you change category or parent page */
        var names = [];
        var inputs = document.getElementById(id).querySelectorAll('input[type="checkbox"]'), i = 0, l = inputs.length;
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
        var inputs = document.getElementById(id).querySelectorAll('input[type="radio"]'), i = 0, l = inputs.length;
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


     mw.edit_content.handle_form_submit = function (go_live) {


        if (mw.edit_content.saving) {
            return false;
        }
        mw.edit_content.saving = true;
        var go_live_edit = go_live || false;
        var el = document.getElementById('quickform-edit-content');
        if (el === null) {
            return;
        }

        mw.edit_content.before_save();
        var module = $(mw.tools.firstParentWithClass(el, 'module'));


        var data = mw.serializeFields(el);
        data.id = mw.$('#mw-content-id-value').val();

        var categories = [];


        if (window.categorySelector) {
            $.each(categorySelector.tree.selectedData, function () {
                if (this.type == 'category') {
                    categories.push(this.id);
                }
                if (this.type == 'page') {
                    data.parent = this.id;
                }
            });
        }


        if (categories.length) {
            data.category_ids  = categories.join(',')
        } else {
            data.category_ids = 0;

        }

         var has_menu_edit = document.getElementById('menu-selector-item');
         if (has_menu_edit !== null &&  !data['add_content_to_menu[]'] ) {
              data['add_content_to_menu[]'] = [0];
         }





         //
        // if (data.tag_names.length) {
        //     data.tag_names  = data.tag_names.join(',')
        // } else {
        //     data.tag_names = false;
        // }


        module.addClass('loading');


        mw.content.save(data, {
            url: el.getAttribute('action'),
            onSuccess: function (a) {
                if (window.pagesTreeRefresh) {
                    pagesTreeRefresh()
                }

                if (typeof(data.id) !== 'undefined') {
                mw.$('.mw-admin-go-live-now-btn').attr('content-id', data.id);
                }
                mw.askusertostay = false;
                mw.is_new_content_added = false;
                if ( typeof(data.id) !== 'undefined' && (data.id) == 0) {
                    mw.is_new_content_added = true;
                }
                if (parent !== self && !!window.parent.mw) {
                    window.mw.parent().askusertostay = false;
                    if (typeof(data.is_active) !== 'undefined' && typeof(data.id) !== 'undefined') {

                        if ((data.id) != 0) {
                            if ((data.is_active) == 0) {
                                window.mw.parent().$('.mw-set-content-unpublish').hide();
                                window.mw.parent().$('.mw-set-content-publish').show();
                            }
                            else if ((data.is_active) == 1) {
                                window.mw.parent().$('.mw-set-content-publish').hide();
                                window.mw.parent().$('.mw-set-content-unpublish').show();
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
                            mw.trigger('adminSaveEnd');
                        });
                        xhr.fail(function () {
                            $(window).trigger('adminSaveFailed');
                        });

                    }
                }
                if (go_live_edit != false) {
                    if (parent !== self && !!window.parent.mw) {
                        if (window.mw.parent().drag != undefined && window.mw.parent().drag.save != undefined) {
                            window.mw.parent().drag.save();
                        }
                        window.mw.parent().askusertostay = false;
                    }
                    var nid = typeof this === "number" ? this : this.id;

                    $.get('<?php print site_url('api/content/get_link_admin/?id=') ?>' + nid, function (data) {

                        if (data == null) {
                            return false;
                        }
                         if(go_live_edit === 'n'){
                        mw.top().win.location.href = data.url + '?editmode=n';
                        } else {
                        mw.top().win.location.href = data.url + '?editmode=y';
                        }
                    });
                }
                else {
                    var nid = typeof this === "number" ? this : this.id;
                     $.get('<?php print site_url('api/content/get_link_admin/?id=') ?>' + nid, function (data) {

                        if (data == null) {
                            return false;
                        }

                        var slug = data.slug;
                        mw.$("#edit-content-url").val(slug);
                        mw.$(".view-post-slug").html(slug);
                        mw.$("#slug-base-url").html(data.slug_prefix_url);
                         if(go_live_edit === 'n') {
                             mw.$("a.quick-post-done-link").attr("href", data.url + '?editmode=n');
                         } else {
                             mw.$("a.quick-post-done-link").attr("href", data.url + '?editmode=y');

                         }
                        mw.$("a.quick-post-done-link").html(data.url);



                         mw.$("#<?php print $module_id ?>").attr("content-id", nid);
                         <?php if($is_quick != false) : ?>
                         //  mw.$("#<?php print $module_id ?>").attr("just-saved",this);
                         <?php else: ?>
                         //if (self === parent) {
                         if (self === parent) {

                             if(mw.is_new_content_added){
                                 window.location = data.admin_url;
                             }
                             //var type =  el['subtype'];
                             // mw.url.windowHashParam("action", "editpage:" + nid);
                             // window.location = window.location;
                         }
                         <?php endif; ?>

                         if ($('.mw_admin_edit_content_form').attr('content-type-is-changed') == 1) {
                             location.reload();
                             // This will redirect the full page with the new content type fields and changes
                         }
                         mw.edit_content.after_save(this);

                    });

                }
                mw.edit_content.saving = false;


                $(window).trigger('adminSaveContentCompleted');

                if (self !== parent) {



                    if ((data.id) == 0) {



                        var nid = typeof this === "number" ? this : this.id;


                        mw.$("#<?php print $module_id ?>").attr("content-id", nid);

                        mw.reload_module("#<?php print $module_id ?>");

                        mw.reload_module_everywhere('menu');
                        mw.reload_module_everywhere('pages');
                        mw.reload_module_everywhere('posts');
                        mw.reload_module_everywhere('shop/products');


                    }
                }






            },
            onError: function () {
                $(window).trigger('adminSaveFailed');
                module.removeClass('loading');

                mw.edit_content.saving = false;
            }
        });
    }

    mw.collect_inner_edit_fields = function (data) {
        var frame = document.querySelector('#mw-admin-content-iframe-editor iframe');
        if (frame === null) return false;
        var frameWindow = frame.contentWindow;
        if (typeof(frameWindow.mwd) === 'undefined') return false;
        var root = frameWindow.document.getElementById('mw-iframe-editor-area');
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
            if (e.keyCode == 13) {
                e.preventDefault()
            }
        })
        $(window).on('hashchange beforeunload', function (e) {
            mw.$(".mw-admin-go-live-now-btn").off('click');
        });


        mw.$(".mw-admin-go-live-now-btn").off('click');

        mw.$(".mw-admin-go-live-now-btn").on('click', function (e) {
            mw.edit_content.handle_form_submit(true);
            return false;
        });

        mw.reload_module('#edit-post-gallery-main');

        mw.edit_content.load_editor();
        <?php if($just_saved != false) : ?>
        mw.$("#<?php print $module_id ?>").removeAttr("just-saved");
        <?php endif; ?>
        // mw.edit_content.render_category_tree("<?php print $rand; ?>");
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
        $(window).on('templateChanged', function (e) {

            var iframe_ed = $('.mw-iframe-editor')
            var changed = iframe_ed.contents().find('.changed').size();
            if (changed == 0) {
                // mw.edit_content.load_editor();
            }
            mw.edit_content.load_editor();
        });
        if (document.querySelector('.mw-iframe-editor') !== null) {
            document.querySelector('.mw-iframe-editor').onload = function () {
                $(window).on('scroll', function () {
                    var scrolltop = $(window).scrollTop();
                    if (document.getElementById('mw-edit-page-editor-holder') !== null) {
                        var otop = document.getElementById('mw-edit-page-editor-holder').offsetTop;
                        if ((scrolltop + 100) > otop) {
                            var ewr = document.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
                            if (ewr === null) {
                                return false;
                            }
                            ewr.style.position = 'absolute';
                            ewr.style.top = scrolltop + otop + 'px';
                            ewr.style.top = scrolltop - otop /*+ document.querySelector('.admin-manage-toolbar').offsetTop*/ + document.querySelector('.admin-manage-toolbar').offsetHeight - 98 + 'px';
                            mw.$('.admin-manage-toolbar-scrolled').addClass('admin-manage-toolbar-scrolled-wysiwyg');
                            mw.tools.addClass(ewr, 'editor_wrapper_fixed');
                        }
                        else {
                            var ewr = document.querySelector('.mw-iframe-editor').contentWindow.document.querySelector('.editor_wrapper');
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
            $(title_field_shanger).on("change", function () {
                var newtitle = $(this).val();
                $('#content-title-field-master').val(newtitle);
            });
        }

        $(".postbtnmore").on('mousedown', function () {
            $(this).remove()
        })

        window.QTABS = mw.tabs({
            nav: mw.$("#quick-add-post-options .mw-ui-abtn"),
            tabs: mw.$("#quick-add-post-options-items-holder .quick-add-post-options-item"),
            toggle: true,
            onclick: function (qtab) {

                var tabs = $(document.getElementById('quick-add-post-options-items-holder'));
                if (mw.$("#quick-add-post-options .mw-ui-abtn.active").length > 0) {
                    var tabsnav = $(document.getElementById('quick-add-post-options'));
                    var off = tabsnav.offset();
                    $(tabs).show();
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
                    document.querySelector('.mw-iframe-editor').contentWindow.GalleriesRemote()
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

        $(mww).on('mousedown', function (e) {
            var el = document.getElementById('content-edit-settings-tabs-holder');
            var cac = mw.wysiwyg.validateCommonAncestorContainer(e.target);
            if (el != null && !el.contains(e.target)
                && !!cac
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-btn')
                && cac.className.indexOf('grammarly') !== -1
                && cac.querySelector('[class*="grammarly"]') === null
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-ghost')
                && !mw.tools.hasParentsWithTag(e.target, 'grammarly-card')) {
                window.QTABS.unset()
                mw.$(".quick-add-post-options-item, #quick-add-post-options-items-holder").hide();
                mw.$("#quick-add-post-options .active").removeClass('active');
            }
        });

        mw.$(".mw-iframe-editor").on("editorKeyup", function () {
            mw.tools.addClass(document.body, 'editorediting');
        });
        $(document.body).on("mousedown", function () {
            mw.tools.removeClass(document.body, 'editorediting');
        });
        mw.$(".admin-manage-toolbar").on("mousemove", function () {
            mw.tools.removeClass(document.body, 'editorediting');
        });



    });
</script>
