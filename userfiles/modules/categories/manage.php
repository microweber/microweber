<div class="mw-module-category-manager admin-side-content">
    <div class="card style-1 mb-3">

        <div class="card-header">
            <h5><i class="mdi mdi-folder text-primary mr-3"></i> <strong><?php _e("Categories"); ?></strong></h5>
            <div class="js-hide-when-no-items">
                <div class="d-flex">
                    <?php
                    if (user_can_access('module.categories.edit')):
                        ?>

                    <?php if (isset($params['is_shop'])): ?>
                        <a href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-primary btn-sm mr-2"><i class="mdi mdi-plus"></i> <?php _e("New category"); ?></a>
                    <?php else: ?>
                        <a href="<?php echo route('admin.category.create'); ?>" class="btn btn-primary btn-sm mr-2"><i class="mdi mdi-plus"></i> <?php _e("New category"); ?></a>
                    <?php endif; ?>


                    <?php endif; ?>

                    <div class="form-group mb-0">
                        <div class="input-group mb-0 prepend-transparent">
                            <div class="input-group-prepend">
                                <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                            </div>

                            <input type="text" class="form-control form-control-sm" aria-label="Search" placeholder="<?php _e('Search') ?>" oninput="categorySearch(this)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body pt-3">
            <div class="mw-ui-category-selector mw-ui-manage-list m-0" id="mw-ui-category-selector-manage">

                <?php
                $field_name = "categories";
                $selected = 0;
                $mainFilterTree = array();
                $mainFilterTree['ul_class'] = 'mw-ui-category-tree';
                $mainFilterTree['li_class'] = 'sub-nav';
                $mainFilterTree['rel_type'] = 'content';
                $mainFilterTree['li_hidden_class'] = 'text-muted';

                if (isset($params['page-id']) and $params['page-id'] != false) {
                    $mainFilterTree['rel_id'] = intval($params['page-id']);
                }
                $more_link_params_txt = '';
                if(isset($params['show_add_post_to_category_button'])){
                    $more_link_params_txt   =$more_link_params_txt. "<span class=\"btn btn-outline-primary btn-sm\"  onclick='mw.quick_cat_add_post_to_category_from_modal({id})'>  <span>". _e("Add to category", true) . "</span> </span>";
                }

                if (user_can_access('module.categories.edit')) {

                    if(isset($params['show_add_post_to_category_button'])){
                        $more_link_params_txt   =$more_link_params_txt. "<span class=\"mr-1 btn btn-outline-primary mdi mdi-folder-edit btn-sm\"  onclick='mw.quick_cat_edit({id})'>  </span>  <span class=\" mr-1 btn btn-outline-danger mdi mdi-delete btn-sm\" onclick='event.stopPropagation();event.preventDefault();mw.quick_cat_delete({id})'>"."</span></span>";

                    } else {
                        $more_link_params_txt   =$more_link_params_txt. "<span class=\" mr-1 btn btn-outline-danger btn-sm\" onclick='event.stopPropagation();event.preventDefault();mw.quick_cat_delete({id})'>". _e("Delete", true) . "</span> <span class=\"mr-1 btn btn-outline-primary btn-sm\"  onclick='mw.quick_cat_edit({id})'>  <span>". _e("Edit", true) . "</span> </span>  </span>";

                    }


                    $mainFilterTree['link'] = "<span class='category_element mw-ui-category-tree-row'  value='{id}' ><span value='{id}' class='mdi mdi-folder text-muted mdi-18px mr-2' style='cursor: move'></span>&nbsp;{title} {$more_link_params_txt}  ";
                } else {
                    $mainFilterTree['link'] = "<span class='mw-ui-category-tree-row'><span class='mdi mdi-folder text-muted mdi-18px mr-2'></span>&nbsp;{title}</span>";
                }
                ?>

                <?php
                $is_shop = 0;
                if (isset($params['is_shop']) && $params['is_shop'] == 1) {
                    $is_shop = 1;
                }
                $founded_cats = false;
                $pages_with_cats = get_pages('no_limit=true&is_shop='.$is_shop);
                if ($pages_with_cats): ?>
                    <?php foreach ($pages_with_cats as $page):
                        $pageTreeFilter = $mainFilterTree;
                        $pageTreeFilter['rel_id'] = $page['id'];
                        ?>

                        <?php
                        $pageTreeFilter['return_data'] = true;
                        $categoryTree = category_tree($pageTreeFilter);
                        if (empty($categoryTree)) {
                            continue;
                        }
                        $founded_cats = true;
                        ?>
                        <div class="card border-0">
                            <div class="card-header pl-0">
                                <h6><i class="mdi mdi-post-outline text-primary mr-3"></i> <?php _e($page['title']); ?></h6>
                            </div>

                            <div class="card-body py-2">
                                <?php echo $categoryTree; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (!$founded_cats): ?>
                    <div class="no-items-found categories py-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_categories.svg'); ">
                                    <h4><?php _e('You don’t have any categories yet'); ?></h4>
                                    <p><?php _e('Create your first category right now.'); ?><br/>
                                        <?php _e('You are able to do that in very easy way!'); ?></p>
                                    <br/>
                                    <?php if (isset($params['is_shop'])): ?>
                                    <a href="<?php echo route('admin.shop.category.create'); ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Category'); ?></a>
                                    <?php else: ?>
                                        <a href="<?php echo route('admin.category.create'); ?>" class="btn btn-primary btn-rounded"><?php _e('Create a Category'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <script>
                            $(document).ready(function () {
                                $('.js-hide-when-no-items').hide()
                                // $('body > #mw-admin-container > .main').removeClass('show-sidebar-tree');
                            });
                        </script>
                    </div>
                <?php endif; ?>


                <?php
                $mainFilterTree['return_data'] = true;
                $mainFilterTree['content_id'] = false;
                $otherCategories = category_tree($mainFilterTree);
                ?>

                <?php if (!empty($otherCategories)): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="card-header">
                                <h5><?php _e('Other') ?></h5>
                            </div>
                            <?php echo $otherCategories; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
            <script>
                mw.require('block-edit.js');
                mw.require('content.js');

                categorySearch = function (el) {
                    var val = el.value.trim().toLowerCase();
                    if (!val || val === '') {
                        $(".mw-ui-category-selector li").show()
                    }
                    else {
                        $(".mw-ui-category-selector li").each(function () {
                            var currel = $(this);
                            var curr = currel.attr('title').trim().toLowerCase();
                            if (curr.indexOf(val) !== -1) {
                                currel.show()
                            }
                            else {
                                currel.hide()
                            }
                        })
                    }
                }


                mw.live_edit_load_cats_list = function () {
                    mw.load_module('categories/manage', '#mw_add_cat_live_edit', function () {

                    });
                }

                mw.quick_cat_edit_create = mw.quick_cat_edit_create || function (id) {


                    if(self !== top && mw.top().settings.liveEdit){

                        var additional_params = {};
                        additional_params.category_id = 0;

                        mw.load_module('categories/edit_category', '#<?php print $params['id'] ?>', null, additional_params);


                        //
                        // var opts = {};
                        // opts.width = '800';
                        // opts.height =  '600';
                        //
                        // opts.liveedit = true;
                        // opts.mode = 'modal';
                        //
                        // var additional_params = {};
                        // additional_params.category_id = id;
                        // return mw.tools.open_global_module_settings_modal('categories/edit_category',  '#mw_edit_category_admin_holder_modal', opts,additional_params);


                    } else {
                        mw.url.windowHashParam('action', 'editcategory:' + id)

                    }

                }
                mw.quick_cat_add_post_to_category_from_modal = function (id) {

                     mw.top().trigger("mwSelectToAddCategoryToContent", id);



                }
                mw.quick_cat_edit = function (id) {
                    if (!!id) {
                        var modalTitle = '<?php _e('Edit category'); ?>';
                    } else {
                        var modalTitle = '<?php _e('Add category'); ?>';
                    }



                    var params = {}
                    params['data-category-id'] = id;
                    params['no-toolbar'] = true;
                    /*mw.load_module('categories/edit_category', '#mw_admin_edit_category_item_module', null, params);*/

                    // mw.categoryEditor.moduleEdit('categories/edit_category', params)


                    // if(typeof mw_select_category_for_editing  == 'undefined'){
                    //
                    //
                    //
                    //     mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
                    //     mw.$(".category_element.active-bg").removeClass('active-bg');
                    //
                    //
                    //     mw.$('#pages_edit_container').removeAttr('parent_id');
                    //     mw.$('#pages_edit_container').removeAttr('data-parent-category-id');
                    //     cat_edit_load_from_modal('categories/edit_category');
                    //
                    //
                    //
                    //
                    // } else {
                    //
                    //
                    //
                    // }

                    if(self !== top && mw.top().settings.liveEdit){


                        var opts = {};
                        opts.width = '800';
                        opts.height =  '600';

                        opts.liveedit = true;
                        opts.mode = 'modal';

                        var additional_params = {};
                        additional_params.category_id = id;

                        mw.load_module('categories/edit_category', '#<?php print $params['id'] ?>', null, additional_params);


                        // mw.top().tools.open_module_modal('categories/edit_category', additional_params, {
                        //       overlay: true,
                        //       iframe: true,
                        //
                        //     title: 'Edit category',
                        //
                        // })






                    } else {

                        <?php if (isset($params['is_shop'])): ?>
                        window.location = "<?php print admin_url() ?>shop/category/"+id+"/edit";
                        <?php else: ?>
                        window.location = "<?php print admin_url() ?>category/"+id+"/edit";
                        <?php endif; ?>

                  //  mw.url.windowHashParam('action', 'editcategory:' + id)
                    }
                }

                mw.quick_cat_delete = function (id) {
                    mw.tools.confirm("Are you sure you want to delete this category?", function () {
                        $.ajax({
                            url: "<?php echo api_url('category/'); ?>" + id,
                            type: 'DELETE',
                            data: {
                                "id": id
                            },
                            success: function () {
                                mw.reload_module_everywhere('categories');
                                mw.reload_module_everywhere('categories/manage');
                                mw.reload_module_everywhere('content/manager');
                            }
                        });
                    });
                }



                $(document).ready(function () {
                    mw.categoryEditor = new mw.blockEdit({
                        element: '#edit-content-row'
                    })


                    if(typeof mw_select_category_for_editing  == 'undefined'){


                       /* mw.quick_cat_edit = mw_select_category_for_editing_from_modal;
                        mw.quick_cat_delete =   function (id, callback) {
                            mw.tools.confirm('Are you sure you want to delete this?', function () {
                                $.post(mw.settings.api_url + "category/delete", {id: id}, function (data) {
                                    mw.notification.success('Category deleted');
                                    if (callback) {
                                        callback.call(data, data);
                                    }



                                    mw.reload_module_everywhere('content/manager');
                                    mw.reload_module_everywhere('categories/manage');
                                    mw.reload_module_everywhere('categories/admin_backend');
                                    mw.url.windowDeleteHashParam('action');

                                });
                            });
                        };
                        mw.on.hashParam("action", function () {


                            if (this == false) {

                                cat_edit_load_from_modal('categories/admin_backend');
                                return false;
                            } else {


                            var arr = this.split(":");

                            if (arr[0] === 'editcategory') {
                                mw_select_category_for_editing_from_modal(arr[1])
                            }if (arr[0] === 'addsubcategory') {
                                mw_select_add_sub_category(arr[1]);
                            }
                            }


                        });*/
                    }
                })
            </script>


            <script type="text/javascript">
                mw.on.moduleReload("<?php print $params['id'] ?>", function () {
                    mw.manage_cat_sort();
                    $(".mw-ui-category-selector a").append('<span class="category-edit-label">' + mw.msg.edit + ' ' + mw.msg.category + '</span>')
                });

                mw.manage_cat_sort = function () {



                    mw.$("#<?php print $params['id'] ?> .mw-ui-category-tree").sortable({
                        items: '.sub-nav',
                        axis: 'y',
                        handle: '.mw-ui-category-tree-row',
                        update: function () {


                            var obj = {ids: []}
                            $(this).find('.category_element').each(function () {
                                var id = this.attributes['value'].nodeValue;
                                obj.ids.push(id);
                            });
                            $.post("<?php print api_link('category/reorder'); ?>", obj, function () {
                                if (self !== parent && !!parent.mw) {
                                    mw.parent().reload_module('categories');
                                }
                                mw.parent().trigger('pagesTreeRefresh')

                            });
                        },
                        start: function (a, ui) {
                            $(this).height($(this).outerHeight());
                            $(ui.placeholder).height($(ui.item).outerHeight())
                            $(ui.placeholder).width($(ui.item).outerWidth())
                        },
                        scroll: false
                    });
                }
                 mw.manage_cat_sort();


                /*function mw_select_category_for_editing_from_modal($p_id) {


                    mw.$(".pages_tree_item.active-bg").removeClass('active-bg');
                    mw.$(".category_element.active-bg").removeClass('active-bg');


                    mw.$('#categories-admin').removeAttr('parent_id');
                    mw.$('#categories-admin').removeAttr('data-parent-category-id');

                     mw.$('#categories-admin').attr('data-category-id', $p_id);



                    mw.$(".mw_edit_page_right").css("overflow", "hidden");
                    cat_edit_load_from_modal('categories/edit_category');
                }


                function mw_select_add_sub_category($p_id) {



                    mw.$('#categories-admin').removeAttr('parent_id');
                    mw.$('#categories-admin').attr('data-category-id', 0);
                    mw.$('#categories-admin').attr('data-parent-category-id', $p_id);
                    mw.$(".mw_edit_page_right").css("overflow", "hidden");
                    cat_edit_load_from_modal('categories/edit_category');
                }



                cat_edit_load_from_modal = function (module, callback) {


                    var action = mw.url.windowHashParam('action');
                    var holder = $('#categories-admin');

                    var time = !action ? 300 : 0;
                    if (!action) {
                        mw.$('.fade-window').removeClass('active');
                    }
                    setTimeout(function () {
                        mw.load_module(module, holder, function () {

                            mw.$('.fade-window').addClass('active')
                            if (callback) callback.call();

                        });
                    }, time)


                }



                    */


            </script>
        </div>
    </div>
    <div id="mw_edit_category_admin_holder"></div>
</div>
