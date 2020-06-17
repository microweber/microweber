<?php
only_admin_access();
$form_rand_id = uniqid() . rand();
$data = false;
if (isset($params["data-category-id"])) {
    $data = get_category_by_id($params["data-category-id"]);
}

if ($data == false or empty($data)) {
    include('_empty_category_data.php');
}
if (!$data['id'] and isset($params["parent-category-id"])) {
    $data['parent_id'] = intval($params["parent-category-id"]);
}
$just_saved = false;
$quick_edit = false;
if (isset($params['just-saved'])) {
    $just_saved = $params['just-saved'];
}
if (isset($params['quick_edit'])) {
    $quick_edit = $params['quick_edit'];
}

$wrapper_class = 'mw-edit-category-item-admin';

if (isset($params['live_edit'])) {
    $wrapper_class = 'module-live-edit-settings';

}
?>
<div class="card style-1 mb-3 <?php print $wrapper_class; ?>">
    <script type="text/javascript">mw.require('forms.js');</script>

    <script type="text/javascript">
        function save_cat(el) {
            if (mwd.querySelector('.mw-ui-category-selector input:checked') !== null) {
                $(document.forms['admin_edit_category_form']).submit();
            } else {
                Alert('<?php _e("Please choose Page or Category"); ?>.');
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

            <?php endif; ?>
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

            mw.category_is_saving = false;
            <?php if(intval($data['id']) == 0): ?>
            <?php endif; ?>
            var h = mwd.getElementById('edit_category_set_par');
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
                mw.form.post(mw.$('#admin_edit_category_form'), '<?php print api_link('category/save') ?>', function (val) {
                    if (typeof(this.error) != "undefined") {
                        mw.notification.msg(this);
                        mw.category_is_saving = false;
                        return false;
                    }

                    mw.$('#mw-notifications-holder').empty();
                    mw.notification.success("Category changes are saved");
                    var v = this.toString();
                    mw.$('#mw_admin_edit_cat_id').val(v);
                    mw.$('#mw-cat-pics-admin').attr("for-id", v);
                    mw.reload_module('[data-type="categories"]');
                    if (self !== parent && !!parent.mw) {
                        parent.mw.reload_module('categories');
                    }
                    mw.reload_module('categories/manage');
                    mw.reload_module('content/manager');

                    if (window.pagesTreeRefresh) {
                        pagesTreeRefresh()
                    }


                    <?php if(intval($data['id']) == 0): ?>
                    mw.url.windowHashParam("new_content", "true");

                    //	mw.url.windowHashParam("action", "editcategory:" + this);

                    <?php endif; ?>
                    mw.reload_module('#<?php print $params['id'] ?>');

                    var module = mw.tools.firstParentWithClass(form, 'module');
                    mw.tools.removeClass(module, 'loading');
                    mw.category_is_saving = false;
                    mw.$('.mw-cat-save-submit').removeClass('disabled');
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
        <div class="card-header">
            <h5>
                <span class="mdi mdi-folder text-primary mr-3"></span>
                <?php if ($data['id'] == 0): ?><?php _e('Add') ?><?php else: ?><?php _e('Edit') ?><?php endif; ?><?php echo ' '; ?><?php _e('category'); ?>
            </h5>
            <span onclick="save_cat(this);" class="btn btn-success btn-sm" form="quickform-edit-content"><?php _e('Save') ?></span>
        </div>
    <?php endif; ?>

    <?php if (!isset($params['no-toolbar'])): ?>
    <div class="admin-side-content">
        <div class="card-body pt-3">
            <?php endif; ?>

            <form id="admin_edit_category_form" name="admin_edit_category_form" autocomplete="off" style="<?php if ($just_saved != false) { ?> display: none; <?php } ?>">
                <input name="id" type="hidden" id="mw_admin_edit_cat_id" value="<?php print ($data['id']) ?>"/>
                <input name="rel" type="hidden" value="<?php print ($data['rel_type']) ?>"/>
                <input name="rel_id" type="hidden" value="<?php print ($data['rel_id']) ?>" id="rel_id"/>
                <input name="data_type" type="hidden" value="<?php print ($data['data_type']) ?>"/>
                <input name="parent_id" type="hidden" value="<?php print ($data['parent_id']) ?>" id="parent_id"/>

                <div class="create-root">
                    <div id="content-title-field-buttons">
                        <div class="row">
                            <div class="col-lg-8 mx-auto">
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

                                            mw_admin_edit_category_item_module_opened = mw.modal({
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

                                    <a href='javascript:mw.quick_cat_edit_create(0)' class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-info"><i class="mw-icon-plus"></i>&nbsp; <?php _e("New category"); ?></a> &nbsp;
                                    <a href="<?php print admin_url(); ?>view:content#action=addsubcategory:<?php print $data['id'] ?>" target="_top" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-info"><?php _e("Add subcategory"); ?></a> &nbsp;
                                <?php endif; ?>

                                <?php if (intval($data['id']) != 0): ?>
                                    <a href="javascript:mw.content.deleteCategory('<?php print ($data['id']) ?>');" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-important"><i class="mw-icon-bin"></i>&nbsp; <?php _e('Delete') ?></a>
                                <?php endif; ?>
                            </div>





                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div  id="content-title-field-row">
                        <div>
                            <div class="form-group">
                                <label class="control-label">Category name</label>
                                <div class="input-group mb-3 prepend-transparent">
                            <?php if ($data['id'] == 0 and isset($data['parent_id']) and $data['parent_id'] > 0): ?>
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="mdi mdi-folder-move text-silver"></i></span>
                                </div>
                                <input
                                    id="content-title-field"
                                    class="form-control"
                                    name="title"
                                    type="text"
                                    placeholder="<?php _e("Subcategory Name"); ?>"/>
                            <?php else: ?>
                                <?php if (isset($data['parent_id']) and $data['parent_id'] > 0): ?>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="mdi mdi-folder-move text-silver"></i></span>
                                    </div>
                                <?php else: ?>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="mdi mdi-folder text-silver"></i></span>
                                    </div>
                                <?php endif; ?>
                                <input
                                    class="form-control"
                                    id="content-title-field"
                                    name="title"
                                    type="text"
                                    <?php if ($data['id'] == 0) { ?>placeholder<?php } else { ?>value<?php } ?>="<?php print ($data['title']); ?>"/>
                            <?php endif; ?>




                                 </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="bootstrap-select form-control">
                        <label class="control-label"><?php print _e('Parent'); ?>:</label>
                        <span class="btn dropdown-toggle btn-light" onclick="$('.mw-tree-selector').stop().slideToggle()" id="category-dropdown-holder">
                            <?php _e("Select Parent page or category"); ?>
                        </span>
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


                <div class="form-group">
                    <label class="control-label" for="exampleTextarea"><?php _e("Description"); ?></label>
                    <small class="text-muted d-block mb-2"><?php _e("Type description of your category in the field"); ?></small>
                    <textarea class="form-control" name="description" rows="3" spellcheck="false"></textarea>
                </div>

                <script type="text/javascript">
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
                    $(mwd).ready(function () {

                        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (data) {
                            var categoryParentSelector = new mw.tree({
                                id: 'category-parent-selector',
                                element: '.category-parent-selector',
                                selectable: true,
                                data: data,
                                singleSelect: true,
                                selectedData: selectedData,
                                skip: skip
                            });
                            if(selectedData.length){
                                mw.$('#category-dropdown-holder').html(categoryParentSelector.selectedData[0].title)
                            }
                            $(categoryParentSelector).on("selectionChange", function (e, selected) {
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

                        var _parent = mwd.querySelector('#edit_category_set_par input:checked');

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
                    });
                </script>
                <input name="position" type="hidden" value="<?php print ($data['position']) ?>"/>

                <div class="advanced_settings">


                    <div class="quick-add-post-options-item">
                        <div class="pictures-editor-holder">
                            <module type="pictures/admin" title="<?php _e("Category images"); ?>" for="categories" for-id="<?php print $data['id'] ?>" id="mw-cat-pics-admin"/>
                        </div>
                    </div>

                    <div class="quick-add-post-options-item" style="display: none" id="category-edit-advanced">
                        <?php if (isset($data['id']) and $data['id'] != 0): ?>
                            <div class="form-group">
                                <label class="control-label"><?php _e("Link"); ?></label>
                                <small><a href="<?php print category_link($data['id']); ?>" target="_blank"><?php print category_link($data['id']); ?></a></small>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="control-label"><?php _e("Slug"); ?></label>
                            <input type="text" class="form-control w100" name="url" value="<?php (isset($data['url'])) ? print ($data['url']) : '' ?>"/>
                        </div>



                        <div class="form-group">
                            <label class="control-label"><?php _e("Meta Title"); ?></label>
                            <input type="text" class="form-control w100" name="category_meta_title" value="<?php (isset($data['category_meta_title'])) ? print ($data['category_meta_title']) : '' ?>"/>
                        </div>



                        <div class="form-group">
                            <label class="control-label"><?php _e("Meta Keywords"); ?></label>
                            <input type="text" class="form-control w100" name="category_meta_keywords" value="<?php (isset($data['category_meta_keywords'])) ? print ($data['category_meta_keywords']) : '' ?>"/>
                        </div>



                        <div class="form-group">
                            <label class="control-label"><?php _e("Meta Description"); ?>:</label>
                            <textarea class="form-control w100" name="category_meta_description"><?php (isset($data['category_meta_description'])) ? print ($data['category_meta_description']) : '' ?></textarea>
                        </div>







                        <?php if (isset($data['id'])): ?>
                            <module type="content/views/settings_from_template" content-type="category" category-id="<?php print $data['id'] ?>"/>
                        <?php endif; ?>


                        <div class="mw-edit-cat-edit-mote-advanced-toggle">
                            <div class="form-group">
                                <?php if (!isset($data['users_can_create_content'])) {
                                    $data['users_can_create_content'] = 0;
                                } ?>
                                <div class="mw-ui-check-selector">
                                    <div class="control-label">
                                        <?php _e("Can users create content"); ?>
                                        <small class="mw-help" data-help="<?php _e("If you set this to YES the website users will be able to add content under this category"); ?>">(?)</small>
                                    </div>

                                    <label class="mw-ui-check">
                                        <input name="users_can_create_content" type="radio" value="0" <?php if ('' == trim($data['users_can_create_content']) or '0' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
                                        <span></span><span><?php _e("No"); ?></span>
                                    </label>
                                    <label class="mw-ui-check">
                                        <input name="users_can_create_content" type="radio" value="1" <?php if ('1' == trim($data['users_can_create_content'])): ?>   checked="checked"  <?php endif; ?> />
                                        <span></span><span><?php _e("Yes"); ?></span>
                                    </label>
                                </div>
                            </div>

                            <?php if (isset($data['id'])): ?>
                                <?php if (!isset($data['category_subtype'])) {
                                    $data['category_subtype'] = 'default';
                                } ?>
                                <input type="hidden" name="category_subtype"
                                       value="<?php print $data['category_subtype'] ?>"/>
                                <script type="text/javascript">

                                    $(document).ready(function () {

                                        mw.dropdown();


                                        $('.edit-category-choose-subtype-dd').on('change', function () {
                                            var val = $(this).val();
                                            $('[name="category_subtype"]', '#admin_edit_category_form').val(val)
                                            $('#admin_edit_category_subtype_settings').attr('category_subtype', val);
                                            mw.reload_module('#admin_edit_category_subtype_settings');
                                        });
                                    });
                                </script>

                                <div class="form-group">
                                    <div class="control-label">
                                        <?php _e("Category"); ?>
                                        <?php _e("Subtype"); ?>
                                        <small class="mw-help" data-help="You can set the category behaviour by changing its subtype">(?)</small>
                                    </div>
                                    <select class="selectpicker edit-category-choose-subtype-dd" placeholder>
                                        <option value="default" <?php if($data['category_subtype'] === 'default') { print 'selected'; }  ?>><?php _e("Default"); ?></option>
                                        <option value="content_filter" <?php if($data['category_subtype'] === 'default') { print 'selected'; }  ?>><?php _e("Content filter"); ?></option>
                                    </select>
                                </div>
                                <module type="categories/edit_category_subtype_settings" category_subtype="<?php print $data['category_subtype'] ?>" category-id="<?php print $data['id'] ?>" id="admin_edit_category_subtype_settings"/>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="js-category-advanced-seetings-button-block">
                    <div class="mw-ui-btn mw-ui-btn-small mw-ui-btn-outline mw-ui-btn-info js-category-advanced-seetings-button">
                        <?php _e('show more'); ?>
                    </div>
                </div>
            </form>

            <?php if (!isset($params['no-toolbar'])): ?>
        </div>
    </div>
<?php endif; ?>

</div>
