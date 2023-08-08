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
        margin: 0;
        padding: 10px 0 0 20px;
        border-radius: 3px;
        max-height: calc(100vh - 100px);
        overflow: auto;
    }

    .mw-tags--container:empty{
        border-bottom-color:  rgba(146, 148, 166, 0)
    }
     .mw-tags--container{
        padding-bottom: 22px;
        margin-bottom: 22px;
        border-bottom: 1px solid rgba(146, 148, 166, 0.38)
    }

</style>

<script>

    <?php
        $categoryTreeEndPoint = api_url('content/get_admin_js_tree_json');
        if (isset($data['content_type'])) {
            if ($data['content_type'] == 'product') {
                $categoryTreeEndPoint .= '?is_shop=1';
            }
            if ($data['content_type'] == 'post') {
                $categoryTreeEndPoint .= '?is_blog=1';
            }
        }
    ?>

    var loadCategoriesTree = function () {
        var request = new XMLHttpRequest();
        request.open('GET', '<?php print $categoryTreeEndPoint; ?>', true);
        request.send();
        request.onload = function() {
            if (request.status >= 200 && request.status < 400) {
                var tdata = JSON.parse(request.responseText);

                if(!tdata || !tdata.length){
                    tdata = [];
                }

                window.selectedPages = [ <?php print $data['parent']; ?>];
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
                    color: '',
                    size: 'sm',
                    outline: true,
                    saveState: false,
                    on: {
                        selectionChange: function () {
                            //  document.querySelector('.btn-save').disabled = false;
                            mw.askusertostay = true;

                            var selected = categorySelector.tree.getSelected();
                            if(selected.length){

                                var hasPage = selected.find(function (item){
                                    return item.type === 'page';
                                });

                                for(var i = 0; i < selected.length; i++){
                                    if(selected[i].type === 'page') {
                                        window.selectedPages = [selected[i].id];
                                    }
                                }

                                // console.log(window.selectedPages);

                                if(typeof hasPage === 'undefined'){
                                    var category = selected[0];
                                    categorySelector.tree.select(category.parent_id, 'page', true);
                                }
                             }

                        }
                    }
                });

                $(categorySelector.tree).on('ready', function () {
                    if (window.pagesTree && pagesTree.selectedData.length) {
                        $.each(pagesTree.selectedData, function () {
                            categorySelector.tree.select(this, undefined, false)
                        })
                    } else {

                        $.each(window.selectedPages, function () {
                            categorySelector.tree.select(this, 'page', false);
                            categorySelector.tree.open(this, 'page', false);

                        });
                        $.each(selectedCategories, function () {
                            categorySelector.tree.select(this, 'category', false);
                            categorySelector.tree.open(this, 'category', false);
                        });

                    }
                    categorySelector.tags.setData(categorySelector.tree.getSelected());

                    var atcmplt = mw.element('<div class="input-group mb-0 prepend-transparent"> <div class="input-group-prepend"> <span class="input-group-text"><i class="mdi mdi-magnify"></i></span> </div> <input type="text" class="form-control form-control-sm" placeholder= <?php _e("Search"); ?>> </div>');

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
                    });
                    $('.mw-page-component-disabled').removeClass('mw-page-component-disabled');
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

<div class="col-lg-4 mt-6 pt-4 px-5 manage-content-sidebar">
    <div class="card mb-5">
        <div class="card-body">
            <div class="card-header ps-0 pt-1 mb-0">
                <strong><?php _e("Visibility"); ?></strong>
            </div>
            <div>
                <div><input type="hidden" name="is_active" id="is_post_active" value="<?php print $data['is_active']; ?>"/>
                    <div class="form-group">
                        <div class="custom-control custom-radio mb-3">
                            <input type="radio" id="is_active_1"  name="is_active" class="form-check-input mt-1" value="1" <?php if ($data['is_active']): ?>checked<?php endif; ?>>
                            <label class="custom-control-label ms-1 fs-4 mw-admin-edit-post-publish " style="cursor:pointer" for="is_active_1"><?php _e("Published"); ?></label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" id="is_active_0" name="is_active" class="form-check-input mt-1" value="0" <?php if (!$data['is_active']): ?>checked<?php endif; ?>>
                            <label class="custom-control-label ms-1 fs-4 mw-admin-edit-post-unpublish " style="cursor:pointer" for="is_active_0"><?php _e("Unpublished"); ?></label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <module type="content/views/edit_default_sidebar_variants" content-id="<?php echo $data['id']; ?>" />

    <div class="mb-5 categories js-sidebar-categories-card">

               <div class="card">
                   <div class="card-body">

                           <?php if ($data['content_type'] == 'page') : ?>

                                 <div class="card-header ps-0 pt-1 mb-0">
                                     <strong>
                                         <?php _e("Select parent page"); ?>
                                     </strong>
                                 </div>

                                  <div>
                                      <div class="quick-parent-selector mt-2">
                                          <module

                                                type="content/views/selector"
                                                  hide-categories="true"
                                                  no-parent-title="<?php _e('No parent page'); ?>"
                                                  field-name="parent_id_selector"
                                                  change-field="parent"
                                                  selected-id="<?php print $data['parent']; ?>"
                                                  remove_id="<?php print $data['id']; ?>"
                                                  recommended-id="<?php print $recommended_parent; ?>"

                                          />
                                      </div>
                                  </div>

                           <?php else: ?>
                               <div class="d-flex justify-content-between flex-wrap">
                                   <strong><?php _e('Categories'); ?></strong>
                                   <script>
                                       function manage_cats_for_add_post() {

                                           var manage_cats_for_add_post_opts = {};
                                           var additional_params = {};
                                           additional_params.show_add_post_to_category_button = 'true';

                                           <?php if (isset($data['content_type']) && $data['content_type'] == 'product'): ?>
                                           additional_params.is_shop = 1;
                                           <?php endif; ?>

                                           <?php if (isset($data['content_type']) && $data['content_type'] == 'post'): ?>
                                           additional_params.is_blog = 1;
                                           <?php endif; ?>

                                           if (window.selectedPages && window.selectedPages.length > 0) {
                                               additional_params.parent = window.selectedPages[0];
                                           }

                                           manage_cats_for_add_post_dialog = mw.top().tools.open_global_module_settings_modal('categories/edit_category', 'categories-admin',manage_cats_for_add_post_opts,additional_params)
                                       }
                                   </script>

                                   <a
                                    onclick="manage_cats_for_add_post();void(0);return false;"
                                    href="<?php  echo admin_url(); ?>category"
                                    > <?php _e("Add category"); ?></a>
                               </div>

                           <?php endif; ?>




                <div>
                    <div>
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


                    <div>
                        <div class="col-12">
                            <div id="show-categories-tree-wrapper" >
                                <label  class="form-label font-weight-bold"><?php _e('Select'); ?> <?php echo $data['content_type']; ?> <?php _e('categories'); ?></label>

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
        </div>

    <?php if ($data['content_type'] == 'page'): ?>
        <div class="card-body mb-5 menus">
            <div class=" ">
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
        <div class="card">
        <div class="card-body">
            <div class=" ">
                <div class="row py-0">
                    <div class="col-12">
                        <label  class="form-label font-weight-bold"><?php _e("Tags"); ?></label>
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
        </div>
    <?php endif; ?>


    <div class="card-body mb-3 d-none">
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
