<script>
    $(document).ready(function () {

        if (window.thismodal && thismodal.resize) {
            thismodal.resize(991);
        }
    });
</script>
<style>
    .tree-column{
        display: none !important;
    }
</style>
<?php
only_admin_access();

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

$action_text = _e($type . ' ' . "title ", true);
if (isset($edit_page_info['id']) and intval($edit_page_info['id']) != 0) {
    $action_text = _e("Editing " . $type, true);
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
$wrapper_class = '';
if (isset($params['live_edit'])) {
    $wrapper_class = 'active';
}
if (isset($params['quick_edit'])) {
    $wrapper_class = 'active';
}
?>


<div class="fade-window <?php print $wrapper_class; ?> closed    <?php if (!$wrapper_class == ''): ?>in-popup<?php endif; ?>">
    <div class="window-holder">


        <div class="content">








<div class="row">
    <div class="col-md-8">
        <div class="admin-manage-content-wrap">


            <div class="admin-manage-toolbar-holder">
                <div class="admin-manage-toolbar">
                    <div class="admin-manage-toolbar-content">
                        <div class="mw-ui-row-nodrop">
                            <div class="mw-ui-col">
                                <?php if (isset($edit_page_info['title'])): ?>
                                    <div class="card style-1 mb-3" id="content-title-field-row">
                                        <div class="card-body pt-3">
                                            <div class="form-group">
                                                <label><?php print $action_text ?></label>
                                                <input
                                                    type="text"
                                                    autocomplete="off"
                                                    class="form-control"
                                                    id="content-title-field"
                                                    value="<?php print ($title_for_input) ?>">
                                                <span><i class="mdi mdi-link mdi-20px lh-1_3 mr-1 text-silver float-left"></i>
                                                        <small>
                                                            <span class="text-silver"><?php print site_url(); ?></span>
                                                            <span
                                                                class="contenteditable"
                                                                data-toggle="tooltip"
                                                                data-title="edit"
                                                                data-placement="right"
                                                                contenteditable="text"
                                                                data-original-title=""
                                                                title=""><?php print $data['url']; ?></span>
                                                        </small>
                                                    </span>
                                            </div>


                                            <?php $content_edit_modules = mw('ui')->module('admin.content.edit.text'); ?>
                                            <?php $modules = array(); ?>
                                            <?php if (!empty($content_edit_modules) and !empty($data)) {

                                                foreach ($content_edit_modules as $k1=>$content_edit_module) {
                                                    foreach ($data as $k=>$v) {
                                                        if(isset($content_edit_module[$k])){
                                                            $v1 = $content_edit_module[$k];
                                                            $v2 = $v;
                                                            if(trim($v1) == trim($v2)){
                                                                $modules[] = $content_edit_module['module'];
                                                            }
                                                        }

                                                    }
                                                }
                                                $modules = array_unique($modules);
                                            }


                                            ?>

                                            <div class="mw-ui-field-holder" id="mw-edit-page-editor-holder">
                                                <?php event_trigger('content.edit.richtext',$data); ?>
                                                <?php $content_edit_modules = mw()->ui->module('content.edit.richtext'); ?>
                                                <?php $modules = array(); ?>
                                                <?php

                                                if (!empty($content_edit_modules) and !empty($data)) {
                                                    foreach ($content_edit_modules as $k1=>$content_edit_module) {
                                                        if(isset($content_edit_module['module'])){

                                                            $modules[] = $content_edit_module['module'];


                                                        }
                                                    }
                                                    $modules = array_unique($modules);
                                                }

                                                ?>
                                                <?php if(!empty($modules)): ?>
                                                    <?php foreach($modules as $module) : ?>
                                                        <?php print load_module($module,$data); ?>
                                                    <?php endforeach; ?>
                                                <?php else:  ?>
                                                    <div id="mw-admin-content-iframe-editor" class="form-control"></div>
                                                <?php endif; ?>
                                            </div>


                                        </div>




                                        <script>

                                            $(document).ready(function () {
                                                setTimeout(function () {
                                                    $('#content-title-field').focus();
                                                    if(typeof(mw.adminPagesTree) != 'undefined') {
                                                        mw.adminPagesTree.select({
                                                            id:<?php print $edit_page_info['id']  ?>,
                                                            type: 'page'
                                                        })
                                                    }
                                                }, 100);

                                            });

                                        </script>

                                        <?php event_trigger('content.edit.title.after'); ?>
                                        <?php $custom_title_ui = mw()->modules->ui('content.edit.title.after'); ?>
                                        <?php if (!empty($custom_title_ui)): ?>
                                            <?php foreach ($custom_title_ui as $item): ?>
                                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                                <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                                <div class="mw-ui-col <?php print $class; ?>"
                                                    <?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                                     title="<?php print $title; ?>"><?php print $html; ?></div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <?php $custom_title_ui = mw()->modules->ui('content.edit.title.end'); ?>
                                        <?php if (!empty($custom_title_ui)): ?>
                                            <?php foreach ($custom_title_ui as $item): ?>
                                                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                                <?php $width = (isset($item['width'])) ? ($item['width']) : false; ?>
                                                <div class="mw-ui-col <?php print $class; ?>"
                                                    <?php if ($width): ?> style="width: <?php print $width ?>;"  <?php endif; ?>
                                                     title="<?php print $title; ?>"><?php print $html; ?></div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>


                                <?php else: ?>
                                    <h2><span class="mw-icon-<?php print $type; ?>"></span> <?php print $action_text ?></h2>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="post-states-tip" style="display: none">
                    <div class="mw-ui-btn-vertical-nav post-states-tip-nav">
                        <span onclick="mw.admin.postStates.set('unpublish')" data-val="n" class="mw-ui-btn mw-ui-btn-medium btn-publish-unpublish btn-unpublish <?php if ($data['is_active'] == 0): ?> active<?php endif; ?>"><i class="mw-icon-unpublish"></i>&nbsp; <?php _e("Unpublish"); ?></span>
                        <span onclick="mw.admin.postStates.set('publish')" data-val="y" class="mw-ui-btn mw-ui-btn-medium btn-publish-unpublish btn-publish <?php if ($data['is_active'] != 0): ?> active<?php endif; ?>"><i class="mw-icon-check"></i>&nbsp; <?php _e("Publish"); ?></span>
                        <hr>
                        <span class="mw-ui-btn mw-ui-btn-medium post-move-to-trash" onclick="mw.del_current_page('<?php print ($data['id']) ?>');"><i class="mw-icon-bin"></i>&nbsp; <?php _e('Move to trash'); ?></span>
                    </div>
                </div>
            </div>

            <form method="post" <?php if ($just_saved != false) : ?> style="display:none;" <?php endif; ?>
                  class="mw_admin_edit_content_form" action="<?php print site_url(); ?>api/save_content_admin"
                  id="quickform-edit-content" autocomplete="off">
                <input type="hidden" name="id" id="mw-content-id-value" value="<?php print $data['id']; ?>"/>
                <input type="hidden" name="subtype" id="mw-content-subtype" value="<?php print $data['subtype']; ?>"/>
                <input type="hidden" name="subtype_value" id="mw-content-subtype-value-<?php print $rand; ?>"
                       value="<?php print $data['subtype_value']; ?>"/>
                <input type="hidden" name="content_type" id="mw-content-type-value-<?php print $rand; ?>"
                       value="<?php print $data['content_type']; ?>"/>
                <input type="hidden" name="parent" id="mw-parent-page-value-<?php print $rand; ?>"
                       value="<?php print $data['parent']; ?>" class=""/>
                <input type="hidden" name="layout_file" id="mw-layout-file-value-<?php print $rand; ?>"
                       value="<?php print $data['layout_file']; ?>"/>
                <input type="hidden" name="active_site_template" id="mw-active-template-value-<?php print $rand; ?>"
                       value="<?php print $data['active_site_template']; ?>"/>


                <div class="mw-ui-field-holder" id="slug-field-holder">
                    <input type="hidden" id="content-title-field-master" name="title" onkeyup="slugFromTitle();" placeholder="<?php print $title_placeholder; ?>" value="<?php print $title_for_input; ?>" autocomplete="off"/>
                    <input type="hidden" name="is_active" id="is_post_active" value="<?php print $data['is_active']; ?>"/>

                    <div class="edit-post-url">
                        <div class="mw-ui-row">
                            <div class="mw-ui-col" id="slug-base-url-column">
                                <span class="view-post-site-url" id="slug-base-url"></span>
                            </div>

                            <div class="mw-ui-col" id="slug-url-column">
                                <span class="view-post-slug active" onclick="mw.slug.toggleEdit()"></span>
                                <input autocomplete="off" name="content_url" id="edit-content-url" class="mw-ui-invisible-field mw-ui-field-small w100 edit-post-slug"
                                       onblur="mw.slug.toggleEdit();mw.slug.setVal(this);slugEdited=true;" type="text" value="<?php print ($data['url']) ?>"/>
                            </div>
                        </div>


                    </div>
                    <script>
                        slugEdited = false;
                        slugFromTitle = function () {
                            var slugField = mwd.getElementById('edit-content-url');
                            var titlefield = mwd.getElementById('content-title-field');
                            if (slugEdited === false) {
                                var slug = mw.slug.create(titlefield.value);
                                mw.$('.view-post-slug').html(slug);
                                mw.$('#edit-content-url').val(slug);
                            }
                        }
                    </script>
                </div>

                <div class="mw-admin-edit-content-holder">
                    <?php
                    include(__DIR__ . '/tabs.php');
                    /*$data['recommended_parent'] = $recommended_parent;
                    $data['active_categories'] = $categories_active_ids;
                    $data['rand'] = $rand;
                    print load_module('content/views/tabs', $data);*/
                    ?>
                </div>

                <?php if (isset($data['content_type']) and ($data['content_type'] == 'page')): ?>
                    <?php if (isset($data['id']) and ($data['id'] == 0)): ?>
                        <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes"
                                template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>"
                                inherit_from="<?php print $data['parent']; ?>"/>
                    <?php else: ?>
                        <module type="content/views/layout_selector" id="mw-quick-add-choose-layout-middle-pos" autoload="yes"
                                template-selector-position="top" live-edit-btn-overlay="true" content-id="<?php print $data['id']; ?>" edit_page_id="<?php print $data['id']; ?>"
                                inherit_from="<?php print $data['parent']; ?>" small="true" layout_file"="<?php print $data['layout_file']; ?>"   />
                    <?php endif; ?>

                    <?php
                    $data['recommended_parent'] = $recommended_parent;
                    $data['active_categories'] = $categories_active_ids;
                    //print load_module('content/edit_default',$data);
                    ?>
                <?php else: ?>
                    <div id="mw-admin-edit-content-main-area"></div>
                <?php endif; ?>

                <?php if (isset($data['subtype']) and $data['subtype'] == 'dynamic' and (isset($data['content_type']) and $data['content_type'] == 'page')): ?>
                    <script>
                        // mw.$("#quick-add-post-options-item-template").show();
                        mw.$("#quick-add-post-options-item-template-btn").hide();
                    </script>
                <?php endif; ?>
                <?php event_trigger('mw_admin_edit_page_footer', $data); ?>
            </form>


            <?php include(__DIR__ . '/edit_default_scripts.php'); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card style-1 mb-3">
            <div class="right-side">
                <div id="content-title-field-buttons">
                    <ul >
                        <?php if ($data['is_active'] == 0) { ?>
                            <li>
                                <button
                                    onclick="mw.admin.postStates.toggle()"
                                    data-val="0"
                                    class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-warn mw-ui-btn-outline btn-posts-state tip"
                                    data-tip="<?php _e("Unpublished"); ?>"
                                    data-tipposition="left-center">
                                    <i class="mw-icon-unpublish"></i>&nbsp; <?php _e("Unpublish"); ?></button>
                            </li>
                        <?php } else { ?>
                            <li>
                                <button
                                    onclick="mw.admin.postStates.toggle()"
                                    data-val="1"
                                    class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline btn-posts-state tip"
                                    data-tip="<?php _e("Published"); ?>"
                                    data-tipposition="left-center"><i class="mw-icon-check"></i>&nbsp; <?php _e("Published"); ?></button>
                            </li>
                        <?php } ?>
                        <?php if ($is_live_edit == false) : ?>
                            <li>
                                <button type="submit" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-live-edit-top-bar-button" onclick="mw.edit_content.handle_form_submit(true);" form="quickform-edit-content">
                                    <i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                            </li>
                            <li>
                                <button type="submit" class="mw-ui-btn mw-ui-btn-notification btn-save js-bottom-save" form="quickform-edit-content"><i class="fa fa-save"></i> <span>&nbsp; <?php print _e('Save'); ?></span></button>
                            </li>
                        <?php else: ?>
                            <?php if ($data['id'] == 0): ?>
                                <li>
                                    <button type="submit" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline mw-live-edit-top-bar-button tip" data-tip="<?php _e("Live Edit"); ?>" data-tipposition="bottom-center" onclick="mw.edit_content.handle_form_submit(true);"
                                            data-text="<?php _e("Live Edit"); ?>" form="quickform-edit-content">
                                        <i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                                </li>
                            <?php else: ?>
                                <li>
                                    <button type="button" class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline mw-live-edit-top-bar-button tip" data-tip="<?php _e("Live Edit"); ?>" data-tipposition="bottom-center" onclick="mw.edit_content.handle_form_submit(true);"
                                            data-text="<?php _e("Live Edit"); ?>"><i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                                </li>
                            <?php endif; ?>
                            <li>
                                <button type="submit" class="mw-ui-btn mw-ui-btn-notification btn-save js-bottom-save tip" data-tip="<?php _e("Save"); ?>" data-tipposition="bottom-center" form="quickform-edit-content"><i class="fa fa-save"></i> <span>&nbsp; <?php print _e('Save'); ?></span>
                                </button>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-12">
                        <strong>Categories</strong>
                        <a href="#" class="btn btn-link float-right py-1 px-0">Manage</a>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-shopping"></i> Shop</button>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-folder"></i> Category</button>
                        <button type="button" class="btn btn-outline-primary btn-sm mb-2 mr-1"><i class="mdi mdi-folder"></i> Accessoaries</button>
                    </div>
                </div>

                <hr class="thin no-padding">

                <div class="row mb-3">
                    <div class="col-12">
                        <small class="text-silver">Want to add the product in more categories?</small>
                        <br>
                        <button type="button" class="btn btn-outline-primary btn-sm text-dark my-3">Add to category</button>
                        <br>
                        TREE
                    </div>
                </div>
            </div>
        </div>

        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Tags</strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <input class="form-control form-control-sm" type="text" placeholder="healthy, beauty, travel">
                        </div>

                        <div class="btn-group tag tag-xs mb-2 mr-1">
                            <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> car</span>
                            <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                        </div>

                        <div class="btn-group tag tag-xs mb-2 mr-1">
                            <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> someother</span>
                            <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                        </div>

                        <div class="btn-group tag tag-xs mb-2 mr-1">
                            <span class="btn btn-primary btn-sm icon-left no-hover"><i class="mdi mdi-tag"></i> topsellproduct</span>
                            <button type="button" class="btn btn-primary btn-sm btn-icon"><i class="mdi mdi-close"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>














        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#settings-btn-column .btn-settings').on('click', function () {
            if ($(this).hasClass("mw-ui-btn-outline")) {
                $(this).removeClass('mw-ui-btn-outline');
            } else {
                $(this).addClass('mw-ui-btn-outline');
            }
        });
    });
</script>
