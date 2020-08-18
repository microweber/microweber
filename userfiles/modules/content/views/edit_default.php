<script>
    $(document).ready(function () {
        if (window.thismodal && thismodal.resize) {
            thismodal.resize(991);
        }
    });
</script>
<?php

$edit_page_info = $data;

if (!isset($edit_page_info['title'])) {
    $edit_page_info['title'] = _e('Content title', true);
}

$quick_edit = false;

if (isset($params['quick_edit']) and $params['quick_edit']) {
    $quick_edit = true;
}

?>

<?php if (isset($edit_page_info['title'])): ?>
    <?php $title_for_input = str_replace('"', '&quot;', $edit_page_info['title']); ?>
<?php endif; ?>
<style>
    #admin-user-nav {
        display: none;
    }
</style>
<?php
if (isset($data['content_type']) and $data['content_type'] == 'page') {
    $parent_page_active = 0;
    if ($data['parent'] != 0 and $data['id'] == 0) {
        $data['parent'] = $recommended_parent = 0;
    } elseif (isset($data['parent'])) {
        $parent_page_active = $data['parent'];
    }
}


if (isset($data['id']) and intval($data['id']) == 0 and isset($data['parent']) and intval($data['parent']) != 0) {
    $parent_data = get_content_by_id($data['parent']);
    if (is_array($parent_data) and isset($parent_data['is_active']) and ($parent_data['is_active']) == 0) {
        $data['is_active'] = 0;
    }
}

if ($edit_page_info['is_shop'] == 1) {
    $type = 'Shop';
} elseif ($edit_page_info['subtype'] == 'dynamic') {
    $type = 'Dynamic page';
} elseif ($edit_page_info['subtype'] == 'post') {
    $type = 'Post';
} elseif ($edit_page_info['content_type'] == 'product') {
    $type = 'Product';
} else {
    $type = 'Page';
}

$action_text = _e($type, true);
if (isset($edit_page_info['id']) and intval($edit_page_info['id']) != 0) {
    $action_text = _e("Editing " . strtolower($type), true);
} else {
    $action_text = _e("Add " . strtolower($type), true);
}

if (isset($edit_page_info['content_type']) and $edit_page_info['content_type'] == 'post' and isset($edit_page_info['subtype'])) {
    //     $action_text2 = $edit_page_info['subtype'];
}
?>

<?php if (!$quick_edit) { ?>
    <script>
        $(document).ready(function () {
            $('.fade-window .btn-fullscreen').on('click', function () {
                $(this).toggleClass('hidden');
                $('.fade-window .btn-close').toggleClass('hidden');
                $('.fade-window').toggleClass('closed');

            });
            $('.fade-window .btn-close').on('click', function () {
                $(this).toggleClass('hidden');
                $('.fade-window .btn-fullscreen').toggleClass('hidden');
                $('.fade-window').toggleClass('closed');
            });


            $('.fade-window').on('scroll', function () {
                var otop = $('.mw-iframe-editor').offset().top;
                $('#mw-admin-content-iframe-editor iframe').contents().find('#mw-admin-text-editor')[otop <= 0 ? 'addClass' : 'removeClass']('scrolled').css({
                    top: otop <= 0 ? Math.abs(otop) : 0
                });
            })

        });
    </script>
<?php } ?>

<script>
    $(document).ready(function () {
        $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
    });
</script>

<script>
    $(document).ready(function () {
        var all = $(window);

        all.push(document)
        all.on('scroll load resize', function () {
            var stop = $(this).scrollTop(),
                otop = $('.mw-iframe-editor').offset().top,
                tbheight = $('.admin-toolbar').outerHeight(),
                is = (stop + tbheight) >= otop;


            $('#mw-admin-content-iframe-editor iframe').contents().find('#mw-admin-text-editor')[is ? 'addClass' : 'removeClass']('scrolled').css({
                top: is ? Math.abs((stop + tbheight) - otop) : 0
            });
            var fixinheaderTime = null;
            if (stop > $(".admin-toolbar").height()) {

                $(".top-bar").addClass("fix-in-header").css('left', $('.window-holder').offset().left);
                fixinheaderTime = setTimeout(function () {
                    $(".top-bar").addClass("after-fix-in-header")
                    // $("#create-content-btn").hide()
                }, 10)
            }
            else {
                $(".top-bar").removeClass("fix-in-header after-fix-in-header");
                //$("#create-content-btn").show()
                clearTimeout(fixinheaderTime)

            }

        });
    });
</script>

<?php
$wrapper_class = 'in-window';
if (isset($params['live_edit'])) {
    $wrapper_class = 'in-popup';
}
if (isset($params['quick_edit'])) {
    $wrapper_class = 'in-popup';
}
?>

<div class="<?php echo $wrapper_class; ?>">
    <form method="post" <?php if ($just_saved != false) : ?> style="display:none;" <?php endif; ?> class="mw_admin_edit_content_form" action="<?php print site_url(); ?>api/save_content_admin" id="quickform-edit-content" autocomplete="off">
        <input type="hidden" name="id" id="mw-content-id-value" value="<?php print $data['id']; ?>"/>
        <input type="hidden" name="subtype" id="mw-content-subtype" value="<?php print $data['subtype']; ?>"/>
        <input type="hidden" name="subtype_value" id="mw-content-subtype-value-<?php print $rand; ?>" value="<?php print $data['subtype_value']; ?>"/>
        <input type="hidden" name="content_type" id="mw-content-type-value-<?php print $rand; ?>" value="<?php print $data['content_type']; ?>"/>
        <input type="hidden" name="parent" id="mw-parent-page-value-<?php print $rand; ?>" value="<?php print $data['parent']; ?>" class=""/>
        <input type="hidden" name="layout_file" id="mw-layout-file-value-<?php print $rand; ?>" value="<?php print $data['layout_file']; ?>"/>
        <input type="hidden" name="active_site_template" id="mw-active-template-value-<?php print $rand; ?>" value="<?php print $data['active_site_template']; ?>"/>

        <div class="row">
            <div class="col-md-8 manage-content-body">
                <div class="card style-1 mb-3" id="content-title-field-row">
                    <div class="card-header">
                        <?php
                        $type_icon = 'mdi-text';
                        if ($type = 'Product') {
                            $type_icon = 'mdi-shopping';
                        }
                        ?>
                        <h5><i class="mdi <?php echo $type_icon; ?> text-primary mr-3"></i> <strong><?php echo $action_text; ?></strong></h5>
                        <div id="content-title-field-buttons">
                            <button type="submit" class="btn btn-sm btn-success btn-save js-bottom-save" form="quickform-edit-content"><span><?php print _e('Save'); ?></span></button>
                        </div>
                    </div>

                    <?php if (isset($edit_page_info['title'])): ?>
                        <div class="card-body pt-3">
                            <div class="form-group" id="slug-field-holder">
                                <label><?php print $type ?> title</label>
                                <input type="text" autocomplete="off" class="form-control" name="title" onkeyup="slugFromTitle();" id="content-title-field" value="<?php print ($title_for_input) ?>">
                                <span>
                                    <i class="mdi mdi-link mdi-20px lh-1_3 mr-1 text-silver float-left"></i>
                                    <small>
                                            <?php
                                            if (isset($data['slug_prefix_url'])) {
                                                $site_prefix_url = $data['slug_prefix_url'];
                                            } else {
                                                $site_prefix_url = site_url();
                                            }
                                            ?>

                                        <span class="text-silver" id="slug-base-url"><?php print $site_prefix_url; ?></span>
                                        <span class="contenteditable js-slug-base-url" data-toggle="tooltip" data-title="edit" data-placement="right" contenteditable="true"><?php print $data['url']; ?></span>
                                    </small>
                                </span>

                                <div class="d-none">
                                    <input autocomplete="off" name="content_url" id="edit-content-url" class="js-slug-base-url-changed edit-post-slug" type="text" value="<?php print $data['url']; ?>"/>

                                    <script>
                                        var slugEdited = false;
                                        slugFromTitle = function () {
                                            if (slugEdited === false) {
                                                var slug = mw.slug.create($('#content-title-field').val());

                                                $('.js-slug-base-url-changed').val(slug);
                                                $('.js-slug-base-url').text(slug);
                                            }
                                        }

                                        $('body').on('blur', '.js-slug-base-url', function () {
                                            var slug = mw.slug.create($(this).text());
                                            $('.js-slug-base-url-changed').val(slug);
                                            $('.js-slug-base-url').text(slug);
                                        });
                                    </script>
                                </div>
                            </div>


                            <?php $content_edit_modules = mw('ui')->module('admin.content.edit.text'); ?>
                            <?php $modules = array(); ?>
                            <?php
                            if (!empty($content_edit_modules) and !empty($data)) {
                                foreach ($content_edit_modules as $k1 => $content_edit_module) {
                                    foreach ($data as $k => $v) {
                                        if (isset($content_edit_module[$k])) {
                                            $v1 = $content_edit_module[$k];
                                            $v2 = $v;
                                            if (trim($v1) == trim($v2)) {
                                                $modules[] = $content_edit_module['module'];
                                            }
                                        }

                                    }
                                }
                                $modules = array_unique($modules);
                            }
                            ?>

                            <div id="mw-edit-page-editor-holder">
                                <?php event_trigger('content.edit.richtext', $data); ?>
                                <?php $content_edit_modules = mw()->ui->module('content.edit.richtext'); ?>
                                <?php $modules = array(); ?>
                                <?php

                                if (!empty($content_edit_modules) and !empty($data)) {
                                    foreach ($content_edit_modules as $k1 => $content_edit_module) {
                                        if (isset($content_edit_module['module'])) {
                                            $modules[] = $content_edit_module['module'];
                                        }
                                    }
                                    $modules = array_unique($modules);
                                }
                                ?>
                                <?php if (!empty($modules)): ?>
                                    <?php foreach ($modules as $module) : ?>
                                        <?php print load_module($module, $data); ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <?php if (isset($data['content_type']) and ($data['content_type'] != 'page')): ?>
                                        <div class="form-group">
                                            <label>Description</label>

                                            <div id="mw-admin-content-iframe-editor">
                                                <?php
                                                /*var_dump($data);exit;
                                                    $content = get_content_by_id($data['content_id']);*/

                                                ?>
                                                <textarea id="content_template" name="content"><?php print $data['content']; ?></textarea>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div>
                                <script>
                                    $(document).ready(function () {
                                        setTimeout(function () {
                                            $('#content-title-field').focus();
                                            if (typeof(mw.adminPagesTree) != 'undefined') {
                                                mw.adminPagesTree.select({
                                                    id:<?php print $edit_page_info['id']  ?>,
                                                    type: 'page'
                                                })
                                            }
                                        }, 100);
                                    });
                                </script>

                                <?php event_trigger('content.edit.title.after'); ?>
                                <?php $custom_title_ui = mw()->module_manager->ui('content.edit.title.after'); ?>

                                <?php if (!empty($custom_title_ui)): ?>
                                    <?php foreach ($custom_title_ui as $item): ?>
                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                        <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                        <div class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?> title="<?php print $title; ?>"><?php print $html; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php $custom_title_ui = mw()->module_manager->ui('content.edit.title.end'); ?>
                                <?php if (!empty($custom_title_ui)): ?>
                                    <?php foreach ($custom_title_ui as $item): ?>
                                        <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                        <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                        <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                        <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                        <div class="mw-ui-col <?php print $class; ?>"<?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?> title="<?php print $title; ?>"><?php print $html; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="admin-manage-content-wrap">
                    <?php if (isset($data['content_type']) and ($data['content_type'] == 'page')): ?>
                        <?php if (isset($data['id']) and ($data['id'] == 0)): ?>
                            <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes" template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>"/>
                        <?php else: ?>
                            <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes" template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" small="true" layout_file"="<?php print $data['layout_file']; ?>"   />
                        <?php endif; ?>

                        <?php
                        $data['recommended_parent'] = $recommended_parent;
                        $data['active_categories'] = $categories_active_ids;
                        ?>
                    <?php else: ?>
                        <div id="mw-admin-edit-content-main-area"></div>
                    <?php endif; ?>

                    <?php if (isset($data['subtype']) and $data['subtype'] == 'dynamic' and (isset($data['content_type']) and $data['content_type'] == 'page')): ?>
                        <script>
                            mw.$("#quick-add-post-options-item-template-btn").hide();
                        </script>
                    <?php endif; ?>

                    <div class="mw-admin-edit-content-holder">
                        <?php include(__DIR__ . '/tabs.php'); ?>
                    </div>

                    <?php event_trigger('mw_admin_edit_page_footer', $data); ?>

                    <?php include(__DIR__ . '/edit_default_scripts.php'); ?>
                </div>
            </div>

            <?php include 'edit_default_sidebar.php'; ?>
        </div>
    </form>
</div>
