<?php if (isset($data['url']) and $data['id'] > 0): ?>
    <script>
        $(document).ready(function () {
            $('.go-live-edit-href-set').attr('href', '<?php print content_link($data['id']); ?>?editmode=y');
            $('.go-live-edit-href-set-view').attr('href', '<?php print content_link($data['id']); ?>?editmode=n');
        });
    </script>
<?php endif; ?>

<style>
    #quick-parent-selector-tree .mw-tree-nav{
        padding: 12px 30px;
        border: 1px solid #cfcfcf;
        margin: 20px 0;
        border-radius: 3px;
        max-height: calc(100vh - 100px);
        overflow: auto;

    }
    .mw-ui-category-selector-abs li li .mw-tree-toggler{
        margin-inline-start: -37px !important;
    }
    .mw-ui-category-selector-abs .mw-tree-toggler{
        margin-inline-start: -22px !important;
    }
</style>

<script>

    var loadCategoriesTree = function () {
        var request = new XMLHttpRequest();
        request.open('GET', '<?php print api_url('content/get_admin_js_tree_json'); ?>', true);
        request.send();
        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var tdata = JSON.parse(request.responseText);

                if(!tdata || !tdata.length){
                    tdata = [];
                }

                var selectedPages = [ <?php print $data['parent']; ?>];
                var selectedCategories = [ <?php print $categories_active_ids; ?>];



                var tags = mw.element();
                var tree = mw.element();

                mw.element('.post-category-tags').empty().append(tags)
                mw.element('#quick-parent-selector-tree').empty().append(tree)



                window.categorySelector = new mw.treeTags({
                    data: tdata,
                    selectable: true,
                    multiPageSelect: false,
                    tagsHolder: tags.get(0),
                    treeHolder: tree.get(0),
                    color: 'primary',
                    size: 'sm',
                    outline: true,
                    saveState: false,
                    on: {
                        selectionChange: function () {
                            //  document.querySelector('.btn-save').disabled = false;
                            mw.askusertostay = true;
                        }
                    }
                });

                $(categorySelector.tree).on('ready', function () {
                    if (window.pagesTree && pagesTree.selectedData.length) {
                        $.each(pagesTree.selectedData, function () {
                            categorySelector.tree.select(this)
                        })
                    } else {
                        $.each(selectedPages, function () {
                            categorySelector.tree.select(this, 'page')
                        });
                        $.each(selectedCategories, function () {
                            categorySelector.tree.select(this, 'category')
                        });
                    }

                    var atcmplt = mw.element('<div class="input-group mb-0 prepend-transparent"> <div class="input-group-prepend"> <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span> </div> <input type="text" class="form-control form-control-sm" placeholder= <?php _e("Search"); ?>> </div>');

                    tree.before(atcmplt);

                    atcmplt.find('input').on('input', function () {
                        var val = this.value.toLowerCase().trim();
                        if (!val) {
                            categorySelector.tree.showAll();
                        }
                        else {
                            categorySelector.tree.options.data.forEach(function (item) {

                                if (item.title.toLowerCase().indexOf(val) === -1) {
                                    categorySelector.tree.hide(item);
                                }
                                else {
                                    categorySelector.tree.show(item);
                                }
                            });
                        }
                    })
                });

                $(categorySelector.tags).on("tagClick", function (e, data) {
                    $(".mw-tree-selector").show();
                    mw.tools.highlight(categorySelector.tree.get(data))
                });

            }
        }

    }
    var catManager;
    var addCategory = function () {
        if(!catManager) {
            catManager = new mw.CategoryManager();
        }
        catManager.addNew().then(function (data){
            loadCategoriesTree()
        })
    }


</script>

<div class="col-md-4 manage-content-sidebar">
    <div class="card style-1 mb-3">
        <div class="card-body pt-3 pb-0">
            <div class="row">
                <div class="col-12">
                    <strong><?php _e("Visibility"); ?></strong>
                </div>
            </div>

            <div class="row my-3">
                <div class="col-12"><input type="hidden" name="is_active" id="is_post_active" value="<?php print $data['is_active']; ?>"/>
                    <div class="form-group">
                        <div class="custom-control custom-radio">
                            <input type="radio" id="is_active_1"  name="is_active" class="custom-control-input" value="1" <?php if ($data['is_active']): ?>checked<?php endif; ?>>
                            <label class="custom-control-label" style="cursor:pointer" for="is_active_1"><?php _e("Published"); ?></label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="is_active_0" name="is_active" class="custom-control-input" value="0" <?php if (!$data['is_active']): ?>checked<?php endif; ?>>
                            <label class="custom-control-label" style="cursor:pointer" for="is_active_0"><?php _e("Unpublished"); ?></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <module type="content/views/edit_default_sidebar_variants" content-id="<?php echo $data['id']; ?>" />

    <div class="card style-1 mb-3 categories js-sidebar-categories-card">
            <div class="card-body pt-3 pb-1">
                <div class="row">
                    <?php if ($data['content_type'] == 'page') : ?>
                        <div class="col-12">
                            <strong><?php _e("Select parent page"); ?></strong>

                            <div class="quick-parent-selector mt-2">
                                <module type="content/views/selector" no-parent-title="<?php _e('No parent page'); ?>" field-name="parent_id_selector" change-field="parent" selected-id="<?php print $data['parent']; ?>" remove_ids="<?php print $data['id']; ?>" recommended-id="<?php print $recommended_parent; ?>"/>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-12">
                            <strong><?php _e('Categories'); ?></strong>

                            <script>
                                function manage_cats_for_add_post() {

                                    var manage_cats_for_add_post_opts = {};
                                    // opts.width = '900';
                                    // opts.height = '800';

                                    //  opts.liveedit = true;
                                    //  opts.mode = 'modal';

                                    var additional_params = {};
                                    additional_params.show_add_post_to_category_button = 'true';



                                    manage_cats_for_add_post_dialog = mw.top().tools.open_global_module_settings_modal('categories/admin_backend_modal', 'categories-admin',manage_cats_for_add_post_opts,additional_params)
                                }
                            </script>

                            <a onclick="manage_cats_for_add_post();void(0);return false;" href="<?php  echo admin_url(); ?>view:content/action:categories" class="btn btn-link float-right py-1 px-0"> <?php _e("Manage categories"); ?></a>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="row mb-3">
                    <div class="col-12">
                        <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                            <script>
                                $(document).ready(function () {

                                    var editContentCategoryTreeSelector;


                                    mw.on("mwSelectToAddCategoryToContent", function(event,catId) {
                                        if (typeof(window.categorySelector) != 'undefined') {
                                            editContentCategoryTreeSelector = window.categorySelector.tree;
                                        }
                                        if (typeof(mw.adminPagesTree) != 'undefined') {
                                            editContentCategoryTreeSelector = mw.adminPagesTree;
                                        }
                                        if (typeof(window.pagesTree) != 'undefined') {
                                            editContentCategoryTreeSelector = window.pagesTree;
                                        }

                                        if (typeof(editContentCategoryTreeSelector) != 'undefined') {
                                            mw.notification.success('The content is added to category');

                                            var all = [];
                                            all.push({
                                                type: 'category',
                                                id: catId
                                            })


                                            editContentCategoryTreeSelector.select(all);
                                            if (typeof(categorySelector) != 'undefined') {
                                                categorySelector.tree.select(catId, 'category')
                                            }

                                            if (typeof(thismodal) != 'undefined') {
                                                thismodal.remove()
                                            }

                                            if (typeof(manage_cats_for_add_post_dialog) != 'undefined') {
                                                manage_cats_for_add_post_dialog.remove()
                                            }



                                            //
                                            // if( mw.dialog.get(event.target)){
                                            //     mw.dialog.get(event.target).remove()
                                            // }




                                        }

                                    });



                                    $('#mw-post-added-<?php print $rand; ?>').on('mousedown touchstart', function (e) {
                                        if (e.target.nodeName === 'DIV') {
                                            setTimeout(function () {
                                                $('.mw-ui-invisible-field', e.target).focus()
                                            }, 78)
                                        }
                                    });

                                    var all = [{type: 'page', id: <?php print !empty($data['parent']) ? $data['parent'] : 'null' ?>}];
                                    var cats = [<?php print $categories_active_ids; ?>];

                                    $.each(cats, function () {
                                        all.push({
                                            type: 'category',
                                            id: this
                                        })
                                    });

                                    if (typeof(editContentCategoryTreeSelector) != 'undefined') {
                                        editContentCategoryTreeSelector.select(all);
                                    }
                                });
                            </script>

                            <div class="mw-tag-selector mt-3" id="mw-post-added-<?php print $rand; ?>">
                                <div class="post-category-tags"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                    <hr class="thin no-padding"/>

                    <div class="row">
                        <div class="col-12">
                            <div id="show-categories-tree-wrapper" >

                                 <strong><?php _e('Select'); ?> <?php echo $data['content_type']; ?> <?php _e('categories'); ?></strong>


                                <div id="show-categories-tree"  >
                                    <div class="mw-admin-edit-page-primary-settings content-category-selector">
                                        <div class="mw-ui-field-holder">
                                            <div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector" id="mw-category-selector-<?php print $rand; ?>">
                                                <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                                                    <script>
                                                        $(document).ready(function () {
                                                            loadCategoriesTree();
                                                        });

                                                        mw.on('pagesTreeRefresh', function () {
                                                            loadCategoriesTree();
                                                        });
                                                    </script>

                                                    <div id="quick-parent-selector-tree"></div>

                                                    <?php include(__DIR__ . '/edit_default_scripts_two.php'); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php if ($data['content_type'] == 'page'): ?>
        <div class="card style-1 mb-3 menus">
            <div class="card-body pt-3">
                <?php event_trigger('mw_edit_page_admin_menus', $data); ?>

                <?php if (isset($data['add_to_menu'])): ?>
                    <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>" add_to_menu="<?php print $data['add_to_menu']; ?>"/>
                <?php else: ?>
                    <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>"/>
                <?php endif; ?>

                <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($data['content_type']) and ($data['content_type'] != 'page')): ?>
        <div class="card style-1 mb-3">
            <div class="card-body pt-3">
                <div class="row mb-3">
                    <div class="col-12">
                        <strong><?php _e("Tags"); ?></strong>
                        <small data-bs-toggle="tooltip" title="<?php _e('Tags/Labels for this content. Use comma (,) to add multiple tags'); ?>"></small>
                    </div>
                </div>

                <?php if (isset($data['content_type']) AND $data['content_type'] != 'page'): ?>
                    <module type="content/views/content_tags" content-type="<?php print $data['content_type'] ?>" content-id="<?php print $data['id'] ?>"/>
                <?php else: ?>
                    <small class="text-muted"><?php _e('The tags are available only for saved content'); ?></small>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>


    <div class="card style-1 mb-3 d-none">
        <div class="card-body">
            <div id="content-title-field-buttons">
                <?php if ($is_live_edit == false) : ?>
                    <button type="submit" class="btn btn-primary mw-live-edit-top-bar-button" onclick="mw.edit_content.handle_form_submit(true);" form="quickform-edit-content"><i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                <?php else: ?>
                    <?php if ($data['id'] == 0): ?>
                        <button type="submit" class="btn btn-primary mw-live-edit-top-bar-button" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Live Edit"); ?>" form="quickform-edit-content"><i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary mw-live-edit-top-bar-button" onclick="mw.edit_content.handle_form_submit(true);" data-text="<?php _e("Live Edit"); ?>"><i class="mai-eye2"></i> <span><?php _e("Live Edit"); ?></span></button>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
