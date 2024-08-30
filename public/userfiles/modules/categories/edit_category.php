<?php
must_have_access();
$form_rand_id = uniqid() . rand();
$data = false;
if (isset($params["data-category-id"])) {
    $data = get_category_by_id($params["data-category-id"]);
} elseif (isset($params["category_id"])) {
    $data = get_category_by_id($params["category_id"]);
}

if ($data == false or empty($data)) {
    include('_empty_category_data.php');
}
if (!$data['id'] and isset($params["parent-category-id"])) {
    $data['parent_id'] = intval($params["parent-category-id"]);
}

if(isset($_GET['addsubcategory']) and $_GET['addsubcategory']){
    $data['parent_id'] = intval($_GET['addsubcategory']);

}

$just_saved = false;
$quick_edit = false;
if (isset($params['just-saved'])) {
    $just_saved = $params['just-saved'];
}
if (isset($params['quick_edit'])) {
    $quick_edit = $params['quick_edit'];
}

$padding_class = '';

$wrapper_class = 'mw-edit-category-item-admin';

if (isset($params['live_edit'])) {
    $wrapper_class = 'module-live-edit-settings';
}

if (isset($_GET['iframe'])) {
    $padding_class = 'px-1';

}

$creteCategoryIn = 'blog';
if (route_is('admin.shop.category.create')) {
    $creteCategoryIn = 'shop';
}
if (route_is('admin.shop.category.edit')) {
    $creteCategoryIn = 'shop';
}

if (isset($_GET['rel_id'])) {
    $data['rel_id'] = $_GET['rel_id'];
}

if (isset($params['parent_page_id'])) {
    $data['rel_id'] = intval($params['parent_page_id']);
}

if (isset($_GET['parent_page_id'])) {
    $data['rel_id'] = intval($_GET['parent_page_id']);

}


if( $data['parent_id']){
    $page_for_category = app()->category_manager->get_page($data['parent_id']);
    if($page_for_category){
        $data['rel_id'] = $page_for_category['id'];
    }
 }


if(isset( $data['rel_id'] )){
    //check if page is hop or blog
    $page = get_content_by_id($data['rel_id']);

    if($page and isset($page['is_shop'])){
        if($page['is_shop'] == 1){
            $creteCategoryIn = 'shop';
        } else {
            $creteCategoryIn = 'blog';
        }
    }
}


if (isset($_GET['is_shop']) && $_GET['is_shop'] == '1') {
    $creteCategoryIn = 'shop';
}
if (isset($params['is_shop']) && $params['is_shop'] == '1') {
    $creteCategoryIn = 'shop';
}
if ($creteCategoryIn == 'shop') {
    $shop = app()->content_repository->getFirstShopPage();
    if ($shop) {
        $data['rel_id'] = $shop['id'];
    }
}



?>
<style>

    /*.mw-dialog-container >  .mw-filepicker-component-section {*/
    /*    width: 100%;*/
    /*}*/

    /*#post-media-card-header {*/
    /*    padding: 15px;*/
    /*    background-color: #fff;*/
    /*    display: flex;*/
    /*    align-items: center;*/
    /*    justify-content: space-between;*/
    /*    border-bottom: 0;*/
    /*    position: relative;*/
    /*    width: 100%;*/
    /*}*/

    /*.select_actions_holder {*/
    /*    position: absolute;*/
    /*    top: -55px;*/
    /*    right: 0;*/
    /*}*/

    /*.card-header.fixed{*/
    /*    position: fixed !important;*/
    /*    top: 69px;*/
    /*    z-index: 10;*/
    /*}*/
    /*#settings-container .card-header.fixed{*/
    /*    top: 0;*/

    /*}*/
</style>
<div class="card">
    <div class="card-body mb-3  <?php print $wrapper_class; ?> <?php print $padding_class; ?>">
        <script type="text/javascript">
            mw.require('forms.js');

            mw.lib.require('bootstrap_tags');
        </script>
        <script type="text/javascript">
            function save_cat(el) {
                var invalid_form_msg = false;
                if(document.querySelector('.mw-ui-category-selector input:checked') === null){
                    invalid_form_msg = 1;
                }
                if (!invalid_form_msg) {
                    var has_title = false;
                    $('#content-title-field,[name*="multilanguage[title]"]').each(function (){
                        if(!!this.value.trim()) {
                            has_title = true;
                        }
                    })

                    if (!has_title) {
                        invalid_form_msg = 2;
                    }
                }

                if (!invalid_form_msg) {
                    $(document.forms['admin_edit_category_form']).submit();
                } else {
                    if(invalid_form_msg === 1){
                        mw.alert('<?php _ejs("Please choose Page or Category"); ?>.');
                        mw.tools.highlight(document.getElementById("category-dropdown-holder"), "yellow");
                    } else if(invalid_form_msg === 2){
                        mw.alert('<?php _ejs("The category must have a name"); ?>.');
                        mw.tools.highlight(document.getElementById("content-title-field"), "yellow");

                    }  else {
                        mw.alert('<?php _ejs("Please fill the required fields"); ?>.');
                    }
                }
            }

            make_new_cat_after_save = function (el) {

                $('#<?php print $params['id'] ?>').removeClass('loading');
                $('#<?php print $params['id'] ?>').removeAttr('just-saved');
                $('#<?php print $params['id'] ?>').removeAttr('selected-category-id');
                $('#<?php print $params['id'] ?>').removeAttr('data-category-id');
                $('#<?php print $params['id'] ?>').removeAttr('category-id');
                <?php if(isset($params['live_edit']) != false) : ?>
                window.location.reload();
                <?php else: ?>
                mw.reload_module('#<?php print $params['id'] ?>');
                mw.top().trigger('pagesTreeRefresh');
                <?php endif; ?>

                mw.reload_module_everywhere('content/manager');



            }

            continue_editing_cat = function () {
                mw.$('.add-edit-category-form').show();
                mw.$('.mw-quick-cat-done').hide();
            }

            <?php if($just_saved != false) : ?>
            $('#<?php print $params['id'] ?>').removeClass('loading');
            $('#<?php print $params['id'] ?>').removeAttr('just-saved');

            <?php endif; ?>
            $(document).ready(function () {

                var all = $(window);
                var header = document.querySelector('#mw-admin-container header');
                var postHeader = mw.element(document.querySelector('.card-header'));
                all.push(document)
                // all.on('scroll load resize', function () {
                //     var stop = $(this).scrollTop(),
                //         otop = $('.mw-iframe-editor').offset().top,
                //         tbheight = $('.admin-toolbar').outerHeight(),
                //         is = (stop + tbheight) >= otop;
                //
                //
                //
                //     var isFixed = (stop > (postHeader.get(0).offsetHeight + (header ? header.offsetHeight : 0) + $(postHeader).offset().top));
                //     postHeader[ isFixed ? 'addClass' : 'removeClass' ]('fixed')
                //     postHeader.width( isFixed ? postHeader.parent().width() : 'auto' )
                //
                // });

                mw.category_is_saving = false;
                <?php if(intval($data['id']) == 0): ?>
                <?php endif; ?>
                var h = document.getElementById('edit_category_set_par');
                mw.$('label', h).click(function () {
                    set_category_parent();
                });

                mw.$('#admin_edit_category_form').submit(function () {


                    var form = this;
                    if (mw.category_is_saving) {
                        return false;
                    }

                    mw.notification.success("Saving...", 3000);
                    mw.category_is_saving = true;
                    $('.mw-cat-save-submit').addClass('disabled');
                    mw.tools.addClass(mw.tools.firstParentWithClass(this, 'module'), 'loading');
                    var catSaveUrl = '<?php print route('api.category.store'); ?>';
                    var catSaveUrlMethod = 'POST';
                    mw.category_is_new = true;
                    <?php if(isset($data['id']) and intval($data['id']) != 0): ?>
                    mw.category_is_new = false;
                    var catSaveUrl = '<?php print route('api.category.update',['category'=>$data['id']]); ?>';
                    var catSaveUrlMethod = 'PATCH';

                    <?php endif; ?>


                    mw.form.post(mw.$('#admin_edit_category_form'), catSaveUrl, function (val) {
                        var showmsg = '';
                        var savedcatid = 0;
                        var admin_edit_url = '';

                        if (this.data) {
                            savedcatid = this.data.id;
                            admin_edit_url = this.data.admin_edit_url;
                        }

                        //todo: move method to separate service
                        var dialog = mw.dialog.get(mw.$('#admin_edit_category_form'));
                        if(dialog) {
                            dialog.result(savedcatid)
                            // dialog.result(this.toString())
                        }
                        if (typeof(this.error) != "undefined") {
                            mw.notification.msg(this);
                            mw.category_is_saving = false;
                            return false;
                        }

                        mw.$('#mw-notifications-holder').empty();
                        mw.notification.success("Category changes are saved");
                        var v = savedcatid;
                        mw.$('#mw_admin_edit_cat_id').val(savedcatid);
                        mw.$('#mw-cat-pics-admin').attr("for-id", savedcatid);
                        //mw.reload_module('[data-type="categories"]');
                        // if (self !== parent && !!parent.mw) {
                        //     mw.parent().reload_module('categories');
                        // }
                        mw.reload_module_everywhere('categories');
                        mw.reload_module_everywhere('categories/manage');
                        mw.reload_module_everywhere('content/manager');


                        mw.top().trigger('pagesTreeRefresh')

                        if (window.pagesTreeRefresh) {
                            pagesTreeRefresh()
                        }

                        mw.askusertostay = false;

                        <?php if(intval($data['id']) == 0): ?>
                        // mw.url.windowHashParam("new_content", "true");

                        <?php endif; ?>
                        // mw.reload_module('#<?php print $params['id'] ?>');

                        var module = mw.tools.firstParentWithClass(form, 'module');
                        mw.tools.removeClass(module, 'loading');
                        mw.category_is_saving = false;
                        mw.$('.mw-cat-save-submit').removeClass('disabled');


                        if(self !== top && mw.top().settings.liveEdit){
                            mw.url.windowHashParam('action', 'editcategory:' + savedcatid)
                        } else {
                            if(mw.category_is_new && admin_edit_url){
                               <?php if (isset($_GET['iframe'])): ?>
                                window.location = admin_edit_url + '?iframe=1';
                                <?php else: ?>
                                window.location = admin_edit_url;
                                <?php endif; ?>
                            }
                        }

                    });

                    return false;
                });

                var curr_id = '' +<?php print $data['id']; ?>;

                if (mw.url.mwParams().action == 'categories') {
                    $("#category-page-title").hide()
                } else {
                    if (mw.url.windowHashParam('action') == 'new:category') {
                        $("#category-page-title-edit").hide()
                    } else {
                        $("#category-page-title-add").hide()
                    }
                }
            });
        </script>

        <?php
        if (intval($data['id']) == 0) {
            if (isset($params['selected-category-id']) and intval($params['selected-category-id']) != 0) {
                $data['parent_id'] = intval($params['selected-category-id']);
            } else if (isset($params['recommended_parent'])) {
                $data['rel_id'] = intval($params['recommended_parent']);
            } else if (isset($params['page-id'])) {
                $data['rel_id'] = intval($params['page-id']);
            }
        }
        ?>

        <?php if (!isset($params['no-toolbar'])): ?>

            <?php

            $headerText = (isset($data['id']) and intval($data['id']) != 0) ? _e('Edit category', true) : _e('Add category', true);
            if(isset($_GET['addsubcategory']) and $_GET['addsubcategory']){
                $headerText = _e('Add subcategory', true);
            }

            ?>

            <div class="card-header d-flex justify-content-between align-items-center <?php print $padding_class; ?>">
<!--                <label class="form-label font-weight-bold">--><?php //print  $headerText ?><!--</label>-->

                <?php  if (!isset($_GET['iframe'])) : ?>
                    <div class="create-root">
                    <div id="content-title-field-buttons">
                        <?php if (intval($data['id']) != 0): ?>
                            <script>
                                mw.quick_cat_edit_create = function (id) {
                                    mw.url.windowHashParam('action', 'new:category');
                                    return false;

                                    if (!!id) {
                                        var modalTitle = '<?php _e('Edit category'); ?>';
                                    } else {
                                        var modalTitle = '<?php _e('Add category'); ?>';
                                    }

                                    mw.dialog({
                                        content: '<div id="mw_admin_edit_category_item_module"></div>',
                                        title: modalTitle,
                                        id: 'mw_admin_edit_category_item_popup_modal'
                                    });

                                    var params = {}
                                    params['data-category-id'] = id;
                                    params['no-toolbar'] = true;
                                    mw.load_module('categories/edit_category', '#mw_admin_edit_category_item_module', null, params);
                                }
                            </script>

                        <?php if (isset($params['parent-module']) and $params['parent-module']  == 'categories/admin_backend_modal' ): ?>

                            <a href="#action=managecats:<?php print $data['id'] ?>" class="btn btn-sm btn-outline-primary"><?php _e("Manage"); ?></a>
                        <?php endif; ?>

                        <?php endif; ?>

                    </div>
                </div>
                <?php endif; ?>


                <?php  if (isset($_GET['iframe'])) : ?>
                <div>
                    <h2>
                    <?php  if ($data['id'] > 0) : ?>
                    <?php _e('Edit category'); ?>
                    <?php else: ?>
                    <?php _e('Add category'); ?>
                    <?php endif; ?>
                    </h2>
                </div>
                <div>
                    <button type="button" onclick="save_cat(this);" dusk="category-save" class="btn btn-dark" form="quickform-edit-content"><i class="mdi mdi-content-save me-1"></i> <?php _e('Save') ?></button>
                </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <div class="<?php if (!isset($params['no-toolbar'])): ?> <?php endif; ?>">
            <div class="card-body py-0 <?php print $padding_class; ?>">

                <div class="row p-0">
                    <div class="col-lg-12">
                        <form id="admin_edit_category_form" name="admin_edit_category_form" autocomplete="off" style="<?php if ($just_saved != false) { ?> display: none; <?php } ?>">

                            <input name="id" type="hidden" id="mw_admin_edit_cat_id" value="<?php print ($data['id']) ?>"/>
                            <input name="rel_type" type="hidden" value="<?php print ($data['rel_type']) ?>"/>
                            <input name="rel_id" type="hidden" value="<?php print ($data['rel_id']) ?>" id="rel_id"/>
                            <input name="data_type" type="hidden" value="<?php print ($data['data_type']) ?>"/>
                            <input name="parent_id" type="hidden" value="<?php print ($data['parent_id']) ?>" id="parent_id"/>

                            <?php if ($data['id'] > 0): ?>
                                <input name="_method" type="hidden" value="PATCH">
                            <?php endif; ?>

                            <?php
                            $categoryModel = \MicroweberPackages\Category\Models\Category::where('id', $data['id'])->first();
                            $formBuilder = App::make(\MicroweberPackages\FormBuilder\FormElementBuilder::class);
                            ?>

                            <div class="row p-0">
                                <div class="col-12">
                                    <div class="form-group" id="content-title-field-row">

                                        <label class="form-label font-weight-bold" for="content-title-field"><?php _e('Category name'); ?></label>

                                        <?php
                                        $categoryNamePlaceholder = 'Category name';
                                        $htmlCategoryTitlePrepend = '';



                                        if ($data['id'] == 0 and isset($data['parent_id']) and $data['parent_id'] > 0) {
                                            $categoryNamePlaceholder = 'Subcategory Name';
                                        } else {
                                            if (isset($data['parent_id']) and $data['parent_id'] > 0) {
                                                $htmlCategoryTitlePrepend = '
                                                <div class="input-group-prepend">
                                                     <span class="input-group-text"><i class="mdi mdi-folder-move text-silver"></i></span>
                                                 </div>';
                                            }
                                        }

                                        $titleValue = '';
                                        if ($data['id'] > 0) {
                                            $titleValue = $data['title'];
                                        }

                                        echo $formBuilder->text('title')
                                            ->setModel($categoryModel)
                                            ->prepend($htmlCategoryTitlePrepend)
                                            ->placeholder($categoryNamePlaceholder)
                                            ->value($titleValue)
                                            ->id('content-title-field')
                                            ->autofocus(true);
                                        ?>

                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="bootstrap-select form-control">
                                            <label class="form-label font-weight-bold"><?php _e('Choose a parent'); ?>:</label>
                                            <small class="text-muted d-block mb-2"><?php _e('Choose a parent page or category') ?></small>

                                            <span class="btn dropdown-toggle" onclick="$(this).next().stop().slideToggle()" id="category-dropdown-holder"><?php _e("Select Parent page or category"); ?></span>
                                            <?php $is_shop = ''; ?>
                                            <div class="mw-ui mw-ui-category-selector mw-tree mw-tree-selector" style="display: none" id="edit_category_set_par">
                                                <?php /*
                                        <module type="categories/selector" include_inactive="true"
                                        categories_active_ids="<?php print (intval($data['parent_id'])) ?>"
                                        active_ids="<?php print ($data['rel_id']) ?>" <?php print $is_shop ?>
                                        input-name="temp"
                                        input-name-categories='temp' input-type-categories="radio"
                                        categories_removed_ids="<?php print (intval($data['id'])) ?>"
                                        show_edit_categories_admin_link="true"/>
                                        */ ?>

                                                <div class="category-parent-selector"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label font-weight-bold" for="description"><?php _e("Description"); ?></label>
                                        <small class="text-muted d-block mb-2"><?php _e("Type description of your category in the field"); ?></small>

                                        <?php
                                        $descriptionValue = '';
                                        if ($data['id'] > 0) {
                                            $descriptionValue = $data['description'];
                                        }

                                        echo $formBuilder
                                            ->textarea('description')
                                            ->setModel($categoryModel)
                                            ->value($descriptionValue)
                                            ->rows(3)
                                            ->id('description')
                                            ->spellcheck(false);

                                        ?>

                                    </div>
                                </div>

                                <script>
                                    mw.require('tree.js')
                                    var parent_page = <?php print intval($data['rel_id']);  ?>;
                                    var parent_category = <?php print (intval($data['parent_id']));  ?>;
                                    var current_category = <?php print isset($data['id']) ? $data['id'] : 'false'; ?>;
                                    var skip = [];
                                    if (current_category) {
                                        skip.push({
                                            id: current_category,
                                            type: 'category'
                                        })
                                    }
                                    var selectedData = [];
                                    if (parent_page) {
                                        selectedData.push({
                                            id: parent_page,
                                            type: 'page'
                                        })
                                    }
                                    if (parent_category) {
                                        selectedData.push({
                                            id: parent_category,
                                            type: 'category'
                                        });
                                    }
                                    $(document).ready(function () {

                                        mw.$('input,select,textarea').on('input', function () {
                                            mw.askusertostay = true;
                                        });
                                        $.get("<?php

                                            $apiJsTree = api_url('content/get_admin_js_tree_json');
                                            if ($creteCategoryIn == 'shop') {
                                                $apiJsTree .= '?is_shop=1';
                                            } else {
                                                $apiJsTree .= '?is_blog=1';
                                            }
                                            $apiJsTree .= '&show_hidden=true';
                                            echo $apiJsTree;
                                            ?>", function (data) {
                                            var categoryParentSelector = new mw.tree({
                                                id: 'category-parent-selector',
                                                element: '.category-parent-selector',
                                                selectable: true,
                                                data: data,
                                                singleSelect: true,
                                                selectedData: selectedData,
                                                skip: skip,
                                                searchInput: true
                                            });
                                            if (selectedData.length) {
                                                if(categoryParentSelector.selectedData && categoryParentSelector.selectedData[0]) {
                                                    mw.$('#category-dropdown-holder').html(categoryParentSelector.selectedData[0].title)
                                                }
                                            }
                                            $(categoryParentSelector).on("selectionChange", function (e, selected) {

                                                mw.askusertostay = true;
                                                var parent = selected[0];
                                                if (!parent) {
                                                    mw.$('#rel_id').val(0);
                                                    mw.$('#parent_id').val(0);
                                                    $("#category-dropdown-holder").html(' ');
                                                }
                                                else {
                                                    $("#category-dropdown-holder").html(parent.title);
                                                    if (parent.type === 'category') {
                                                        mw.$('#rel_id').val(0);
                                                        mw.$('#parent_id').val(parent.id);
                                                    }
                                                    if (parent.type === 'page') {
                                                        mw.$('#rel_id').val(parent.id);
                                                        mw.$('#parent_id').val(0);
                                                    }
                                                }
                                            })
                                        });

                                        var _parent = document.querySelector('#edit_category_set_par input:checked');

                                        if (_parent !== null) {
                                            $("#category-dropdown-holder").html($(_parent).parent().find('span:last').html())
                                        }

                                        $('#edit_category_set_par input').on('change', function () {
                                            var html = $(this).parent().find('span:last').html();
                                            $("#category-dropdown-holder").html(html)
                                        });

                                        var advancedBtn = $(".js-category-advanced-seetings-button")
                                        advancedBtn.on('click', function () {
                                            $("#category-edit-advanced").stop().slideDown(function () {
                                                advancedBtn.remove()
                                            })
                                        })
                                        setTimeout(function (){
                                            mw.askusertostay = false;
                                            //   document.querySelector('button[form="quickform-edit-content"]').disabled = true;
                                        }, 999)
                                    });




                                    setTimeout(function (){
                                        var dropdownUploader;
                                        mw.$('#mw-admin-post-media-type')
                                            .selectpicker({
                                                container: mw.$('#mw-admin-post-media-type').parent()
                                            })
                                            .on('changed.bs.select', function () {
                                                mw._postsImageUploader.displayControllerByType($(this).selectpicker('val'))
                                                setTimeout(function () {
                                                    mw.$('#mw-admin-post-media-type').val('0').selectpicker('refresh');
                                                }, 10)

                                            })
                                            .on('show.bs.select', function () {
                                                if (!!dropdownUploader) {
                                                    dropdownUploader.remove()
                                                }
                                                var item = mw.$('#mw-admin-post-media-type').parent().find('li:last');
                                                dropdownUploader = mw.upload({
                                                    element: item,
                                                    accept: 'image/*',
                                                    multiple: true
                                                });
                                                $(dropdownUploader).on('FileAdded', function (e, res) {
                                                    mw._postsImageUploader._thumbpreload()
                                                })
                                                $(dropdownUploader).on('FileUploaded', function (e, res) {
                                                    var url = res.src ? res.src : res;
                                                    if (window.after_upld) {

                                                        mw._postsImageUploader.hide()
                                                    }
                                                    mw.$('.admin-thumb-item-loading:last').remove();
                                                    mw.module_pictures.after_change();
                                                    after_upld(url, 'Result', 'categories', '<?php print $data['id'] ?>', '<?php print $params['id'] ?>');

                                                });
                                            })
                                    }, 200)


                                </script>

                                <input name="position" type="hidden" value="<?php print ($data['position']) ?>"/>

                                <div class="col-12">
                                    <module
                                        type="pictures/admin"
                                        title="<?php _e("Category images"); ?>"
                                        for="categories" for-id="<?php print $data['id'] ?>"
                                        hideHeader="true"
                                        uploaderType="small"
                                        id="mw-cat-pics-admin"/>
                                </div>

                                <?php if (isset($data['id'])): ?>
                                    <div class="col-md-12">
                                        <module type="content/views/settings_from_template" content-type="category" content-id="<?php print $data['id'] ?>" category-id="<?php print $data['id'] ?>"/>
                                    </div>
                                <?php endif; ?>

                                <div class="col-12 mt-3">
                                    <label class="form-label font-weight-bold"><?php _e("Other settings"); ?></label>

                                    <button type="button" class="btn btn-link btn-sm js-edit-category-show-more" data-bs-toggle="collapse" data-bs-target="#show-more"><?php _e("Show more"); ?></button>

                                    <div class="collapse mt-3" id="show-more">
                                        <div class="row p-0">
                                            <?php if (isset($data['id']) and $data['id'] != 0): ?>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold"><?php _e("Link"); ?></label>
                                                        <div class="mb-3">
                                                            <small><a href="<?php print category_link($data['id']); ?>" target="_blank"><?php print category_link($data['id']); ?></a></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>


                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label class="form-label font-weight-bold"><?php _e("Category Slug"); ?></label>
                                                    <div class="input-group mb-2">
                                                        <?php
                                                        $url = '';
                                                        if ($data['id'] > 0) {
                                                            $url = $data['url'];
                                                        }

                                                        echo $formBuilder
                                                            ->text('url')
                                                            ->setModel($categoryModel)
                                                            ->prepend('

                                                          <span class="input-group-text">
                                                           <i class="mdi mdi-link text-silver"></i>
                                                          </span>


                                                        ')
                                                            ->value($url)
                                                            ->id('url')
                                                            ->spellcheck(false);

                                                        ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <?php if (isset($data['id'])): ?>
                                                    <?php if (!isset($data['category_subtype'])) {
                                                        $data['category_subtype'] = 'default';
                                                    } ?>
                                                    <input type="hidden" name="category_subtype" value="<?php print $data['category_subtype'] ?>"/>
                                                    <script type="text/javascript">
                                                        $(document).ready(function () {
                                                            $('.edit-category-choose-subtype-dd').on('change', function () {
                                                                var val = $(this).val();
                                                                $('[name="category_subtype"]', '#admin_edit_category_form').val(val)
                                                                $('#admin_edit_category_subtype_settings').attr('category_subtype', val);
                                                                mw.reload_module('#admin_edit_category_subtype_settings');
                                                            });
                                                        });
                                                    </script>

                                                    <div class="form-group">
                                                        <label class="form-label font-weight-bold"><?php _e("Category subtype"); ?> <span class="help-tooltip" data-bs-toggle="tooltip" title="You can set the category behaviour by changing its subtype"></span></label>

                                                        <div>
                                                            <select class="form-select edit-category-choose-subtype-dd" data-width="100%">
                                                                <option value="default" <?php if ($data['category_subtype'] === 'default') {
                                                                    print 'selected';
                                                                } ?>><?php _e("Default"); ?></option>
                                                                <option value="content_filter" <?php if ($data['category_subtype'] === 'default') {
                                                                    print 'selected';
                                                                } ?>><?php _e("Content filter"); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <module type="categories/edit_category_subtype_settings" category_subtype="<?php print $data['category_subtype'] ?>" category-id="<?php print $data['id'] ?>" id="admin_edit_category_subtype_settings"/>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label class="form-label font-weight-bold"><?php _e("Meta title"); ?></label>
                                                    <small data-bs-toggle="tooltip" title="<?php _e("Title to appear on the search engines results page"); ?>"></small>
                                                    <small class="text-muted d-block mb-2"><?php _e("Title to appear on the search engines results page"); ?></small>

                                                    <?php
                                                    echo $formBuilder->text('category_meta_title')
                                                        ->setModel($categoryModel)
                                                        ->value($data['category_meta_title'])

                                                        ->value($data['category_meta_title']) ->autocomplete('off') ;
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label class="form-label font-weight-bold"><?php _e("Meta description"); ?></label>
                                                    <small data-bs-toggle="tooltip" title="Short description for yor content."></small>

                                                    <?php
                                                    echo $formBuilder->textArea('category_meta_description')
                                                        ->setModel($categoryModel)
                                                        ->value($data['category_meta_description'])
                                                        ->autocomplete('off');
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group ">
                                                    <label class="form-label font-weight-bold"><?php _e("Meta keywords"); ?></label>
                                                    <small data-bs-toggle="tooltip" title="Short description for yor content."></small>
                                                    <small class="text-muted d-block mb-2"><?php _e('Separate keywords with a comma and space') ?></small>

                                                    <?php
                                                    echo $formBuilder->Text('category_meta_keywords')
                                                        ->setModel($categoryModel)
                                                        ->value($data['category_meta_keywords'])
                                                        ->autocomplete('off');
                                                    ?>
                                                </div>

                                                <small class="text-muted"><?php _e("Type keywords that describe your content - Example: Blog, Online News, Phones for Sale etc"); ?></small>
                                            </div>

                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <?php
                                                    if (!isset($data['is_hidden'])) {
                                                        $data['is_hidden'] = 0;
                                                    }
                                                    ?>
                                                    <label class="form-label font-weight-bold"><?php _e("Is category hidden?"); ?>

                                                        <small class="text-muted d-block mb-2"><?php _e("If you set this to YES this category will be hidden from the website"); ?></small>
                                                    </label>

                                                    <div>
                                                        <div class="custom-control custom-radio d-inline-block mr-3">
                                                            <input type="radio" id="is_hidden_1" name="is_hidden" class="form-check-input" value="1" <?php if ('1' == trim($data['is_hidden'])): ?> checked<?php endif; ?>>
                                                            <label class="custom-control-label" for="is_hidden_1"><?php _e("Yes"); ?></label>
                                                        </div>
                                                        <div class="custom-control custom-radio d-inline-block">
                                                            <input type="radio" id="is_hidden_2" name="is_hidden" class="form-check-input" value="0" <?php if ('' == trim($data['is_hidden']) or '0' == trim($data['is_hidden'])): ?> checked<?php endif; ?>>
                                                            <label class="custom-control-label" for="is_hidden_2"><?php _e("No"); ?></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row py-0">
                                <div class="col-md-12 mt-3 text-end">
                                    <?php if (intval($data['id']) != 0): ?>
                                        <?php

                                        $delete_category_link = "javascript:mw.content.deleteCategory('".$data['id']."');";
                                        if (isset($params['live_edit']) and $params['live_edit'] ) {
                                            $delete_category_link = "javascript:mw.quick_cat_delete('".$data['id']."');";
                                        }
                                        ?>

                                        <a href="<?php print $delete_category_link ?>" class="btn btn-sm btn-outline-danger"> <?php _e('Delete') ?></a>

                                    <?php endif; ?>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
