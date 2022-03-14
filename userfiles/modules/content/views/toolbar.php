<script>
    $(document).ready(function () {
        mw.lib.require('mwui_init');
    });
</script>

<?php
$custom_tabs = false;

$type = 'page';
$act = url_param('action', 1);
?>

<?php
if (isset($params['page-id'])) {
    $last_page_front = $params['page-id'];
} else {

    $last_page_front = session_get('last_content_id');
    if ($last_page_front == false) {
        if (isset($_COOKIE['last_page'])) {
            $last_page_front = $_COOKIE['last_page'];
        }
    }
}

$past_page = false;
if ($last_page_front != false) {
    $cont_by_url = mw()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = mw()->content_manager->link($last_page_front);
    }
} else {
    $past_page = mw()->content_manager->get("order_by=updated_at desc&limit=1");
    if (isset($past_page[0])) {
        $past_page = mw()->content_manager->link($past_page[0]['id']);
    } else {
        $past_page = false;
    }
}
?>
<?php if (isset($past_page) and $past_page != false): ?>
    <script>
        $(function () {
            mw.tabs({
                nav: "#manage-content-toolbar-tabs-nav a",
                tabs: '#manage-content-toolbar-tabs .mw-ui-box-content'
            });
        //    $('.go-live-edit-href-set').attr('href', '<?php print $past_page; ?>');
        });
    </script>
<?php endif; ?>

<?php if (isset($params['keyword']) and $params['keyword'] != false): ?>
    <?php $params['keyword'] = urldecode($params['keyword']); ?>

    <script>
        $(function () {
            $('[autofocus]').focus(function () {
                this.selectionStart = this.selectionEnd = this.value.length;
            });

            $('[autofocus]:not(:focus)').eq(0).focus();
        });
    </script>
<?php endif; ?>

<?php if ($page_info): ?>
    <?php
    $content_types = array();
    $available_content_types = get_content('order_by=created_at asc&is_deleted=0&fields=content_type&group_by=content_type&parent=' . $page_info['id']);
    $have_custom_content_types_count = 0;
    if (!empty($available_content_types)) {
        foreach ($available_content_types as $available_content_type) {
            if (isset($available_content_type['content_type'])) {
                $available_content_subtypes = get_content('order_by=created_at asc&is_deleted=0&fields=subtype&group_by=subtype&parent=' . $page_info['id'] . '&content_type=' . $available_content_type['content_type']);
                if (!empty($available_content_subtypes)) {
                    $content_types[$available_content_type['content_type']] = $available_content_subtypes;
                }
            }
        }
    }

    $have_custom_content_types_count = count($content_types);

    if ($have_custom_content_types_count < 3) {
        $content_types = false;
    }
    ?>
    <?php if (isset($content_types) and !empty($content_types)): ?>
        <?php $content_type_filter = (isset($params['content_type_filter'])) ? ($params['content_type_filter']) : false; ?>
        <?php $subtype_filter = (isset($params['subtype_filter'])) ? ($params['subtype_filter']) : false; ?>
        <?php
        $selected = $content_type_filter;
        if ($subtype_filter != false) {
            $selected = $selected . '.' . $subtype_filter;
        }
        ?>

        <script>
            $(function () {
                $("#content_type_filter_by_select").change(function () {
                    var val = $(this).val();
                    if (val != null) {
                        vals = val.split('.');
                        if (vals[0] != null) {
                            mw.$('#<?php print $params['id']; ?>').attr('content_type_filter', vals[0]);
                        } else {
                            mw.$('#<?php print $params['id']; ?>').removeAttr('content_type_filter');
                        }
                        if (vals[1] != null) {
                            mw.$('#<?php print $params['id']; ?>').attr('subtype_filter', vals[1]);
                        } else {
                            mw.$('#<?php print $params['id']; ?>').removeAttr('subtype_filter');
                        }

                        mw.reload_module('#<?php print $params['id']; ?>');
                    }
                });
            });
        </script>
    <?php endif; ?>
<?php endif; ?>


<?php if (!isset($edit_page_info)): ?>
    <?php mw()->event_manager->trigger('module.content.manager.toolbar.start', $page_info) ?>

    <?php
    $type = 'mdi-post-outline';

    if (is_array($page_info)) {
        if ($page_info['is_shop'] == 1) {
            $type = 'mdi-shopping';
        } elseif ($page_info['subtype'] == 'dynamic') {
            $type = 'mdi-post-outline';
        } else if (isset($page_info ['layout_file']) and stristr($page_info ['layout_file'], 'blog')) {
            $type = 'mdi-text';
        } else {
            $type = 'mdi-post-outline';
        }
    }
    ?>

    <div class="card-header d-flex col-12 align-items-center justify-content-between px-md-4">
        <?php if (!isset($params['category-id']) and isset($page_info) and is_array($page_info)): ?>
            <h5>
                <i class="mdi text-primary mr-2 <?php if ($type == 'shop'): ?>mdi-shopping<?php else: ?><?php print $type; ?><?php endif; ?>"></i>
                <?php print ($page_info['title']) ?>

                <?php if (isset($params['page-id']) and intval($params['page-id']) != 0): ?>
                    <?php $edit_link = admin_url('view:content#action=editpost:' . $params['page-id']); ?>
                    <a href="<?php print $edit_link; ?>" class="btn btn-outline-primary btn-sm" id="edit-content-btn"><?php _e("Edit page"); ?></a>
                <?php endif; ?>
            </h5>
        <?php elseif (isset($params['category-id'])): ?>
            <div>
                <h5>
                    <?php $cat = get_category_by_id($params['category-id']); ?>
                    <?php if (isset($cat['title'])): ?>
                        <i class="mdi mdi-folder text-primary mr-3"></i>
                        <strong><?php print $cat['title'] ?></strong>
                    <?php endif; ?>
                </h5>
            </div>
        <?php elseif ($act == 'pages'): ?>
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h5 class="mb-0">
                    <i class="mdi mdi-post-outline text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                    <strong class="d-xl-flex d-none"><?php _e("Add Page"); ?></strong>
                </h5>
                <a href="<?php echo route('admin.page.create'); ?>" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1"><?php _e("Add Page"); ?></a>
            </div>
        <?php elseif ($act == 'posts'): ?>
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h5 class="mb-0">
                    <i class="mdi mdi-text text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                    <strong class="d-xl-flex d-none"><?php _e("Posts"); ?></strong>
                </h5>
                <a href="<?php echo route('admin.post.create'); ?>" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1">
                    <?php _e("Add Post"); ?>
                </a>
            </div>

        <?php elseif ($act == 'products'): ?>
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                 <h5 class="mb-0">
                    <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                    <strong class="d-xl-flex d-none"><?php _e("Products"); ?></strong>
                </h5>
                <a href="<?php echo route('admin.product.create'); ?>" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1"><?php _e("Add Product"); ?></a>
            </div>
        <?php elseif (isset($params['is_shop'])): ?>
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h5 class="mb-0">
                    <i class="mdi mdi-shopping text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                    <strong class="d-xl-flex d-none"><?php _e("My Shop"); ?></strong>
                </h5>
                <a href="<?php echo route('admin.product.create'); ?>" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1"><?php _e("Add Product"); ?></a>
            </div>
        <?php else: ?>
            <div class="col d-flex justify-content-md-start justify-content-center align-items-center px-0">
                <h5 class="mb-0">
                    <i class="mdi mdi-earth text-primary mr-md-3 mr-1 justify-contetn-center"></i>
                    <strong class="d-xl-flex d-none"><?php _e("Website"); ?></strong>
                </h5>
                <a href="<?php echo route('admin.page.create'); ?>" class="btn btn-outline-success btn-sm js-hide-when-no-items ml-md-2 ml-1"><?php _e("Add Page"); ?></a>
            </div>
        <?php endif; ?>

        <?php
        $cat_page = false;
        if (isset($params['category-id']) and $params['category-id']) {
            $cat_page = get_page_for_category($params['category-id']);
        }

        $url_param_action = url_param('action', true);
        $url_param_view = url_param('view', true);

        $url_param_type = 'page';

        if ($type == 'shop' or $url_param_view == 'shop' or $url_param_action == 'products') {
            $url_param_type = 'product';
        } else if ($cat_page and isset($cat_page['is_shop']) and intval($cat_page['is_shop']) != 0) {
            $url_param_type = 'product';
        } else if ($url_param_action == 'categories' or $url_param_view == 'category') {
            $url_param_type = 'category';
        } else if ($url_param_action == 'showposts' or $url_param_action == 'posts' or $type == 'dynamicpage') {
            $url_param_type = 'post';
        } else if ($cat_page and isset($cat_page['subtype']) and ($cat_page['subtype']) == 'dynamic') {
            $url_param_type = 'product';
        }

        $add_new_btn_url = admin_url('view:content#action=new:') . $url_param_type;
        ?>


        <div id="content-view-search-bar" class="js-hide-when-no-items col-auto justify-content-md-end justify-content-center text-md-right my-md-0 mt-2 pr-0">

            <?php if (isset($params['add-to-page-id']) and intval($params['add-to-page-id']) != 0): ?>
                <div class="mw-ui-dropdown">
                    <span class="mw-ui-btn mw-icon-plus"><span class=""></span></span>
                    <div class="mw-ui-dropdown-content">
                        <div class="mw-ui-btn-vertical-nav">
                            <?php event_trigger('content.create.menu'); ?>

                            <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                            <?php if (!empty($create_content_menu)): ?>
                                <?php foreach ($create_content_menu as $type => $item): ?>
                                    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                                    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                                    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                                    <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                                    <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                                    <span class="mw-ui-btn <?php print $class; ?>"><a
                                                href="<?php print admin_url('view:content'); ?>#action=new:<?php print $type; ?><?php if ($subtype != false): ?>.<?php print $subtype; ?><?php endif; ?>&amp;parent_page=<?php print $params['page-id'] ?>">  <?php print $title; ?> </a></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($params['category-id'])): ?>
                <?php $edit_link = admin_url('view:content#action=editcategory:' . $params['category-id']); ?>
                <a href="<?php print $edit_link; ?>" class="btn btn-outline-primary btn-sm mx-2" id="edit-category-btn"><?php _e("Edit category"); ?></a>
            <?php endif; ?>

            <?php if (isset($content_types) and !empty($content_types)): ?>
                <div>
                    <select id="content_type_filter_by_select" class="selectpicker" data-style="btn-sm" <?php if (!$selected): ?> style="display:none" <?php endif; ?>>
                        <option value=""><?php _e('All'); ?></option>
                        <?php foreach ($content_types as $k => $items): ?>
                            <optgroup label="<?php print ucfirst($k); ?>">
                                <option value="<?php print $k; ?>" <?php if ($k == $selected): ?> selected="selected" <?php endif; ?>><?php print ucfirst($k); ?></option>
                                <?php foreach ($items as $item): ?>
                                    <?php if (isset($item['subtype']) and $item['subtype'] != $k): ?>
                                        <option value="<?php print $k; ?>.<?php print $item['subtype']; ?>" <?php if ($k . '.' . $item['subtype'] == $selected): ?> selected="selected" <?php endif; ?>><?php print ucfirst($item['subtype']); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>

                    <?php if (!$selected): ?>
                        <span class="mw-ui-btn mw-icon-menu" onclick="$('#content_type_filter_by_select').toggle(); $(this).hide();"></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <script>
                $(document).ready(function () {
                    $('.js-search-by-selector').on('change', function () {
                        if ($(this).find('option:selected').val() == 'keywords') {
                            $('.js-search-by-tags').hide();
                            $('.js-search-by-keywords').show();
                        }
                        if ($(this).find('option:selected').val() == 'tags') {
                            $('.js-search-by-tags').show();
                            $('.js-search-by-keywords').hide();
                        }
                    });
                });
            </script>
            <div class="d-none d-md-inline-block">
                <select class="selectpicker js-search-by-selector" data-width="150" data-style="btn-sm">
                    <option value="keywords" selected><?php _e('search by keyword'); ?></option>
                    <option value="tags"><?php _e('search by tags'); ?></option>
                </select>
            </div>

            <div class="js-search-by d-inline-block">
                <div class="js-hide-when-no-items">
                    <div class="js-search-by-keywords">
                        <div class="form-inline flex-nowrap">
                            <div class="input-group mb-0 prepend-transparent mx-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text px-1"><i class="mdi mdi-magnify"></i></span>
                                </div>

                                <input type="text" class="js-search-by-keywords-input   form-control form-control-sm" style="width: 100px;" value="<?php if (isset($params['keyword']) and $params['keyword'] != false): ?><?php print $params['keyword'] ?><?php endif; ?>" <?php if (isset($params['keyword']) and $params['keyword'] != false): ?>autofocus="autofocus"<?php endif; ?> placeholder="<?php _e("Search"); ?>" onkeyup="event.keyCode==13?mw.url.windowHashParam('search',this.value):false"/>
                            </div>

                            <button type="button" class="btn btn-primary btn-sm btn-icon" onclick="mw.url.windowHashParam('search',$(this).prev().find('input').val())"><i class="mdi mdi-magnify"></i></button>
                        </div>
                    </div>

                    <div class="js-search-by-tags" style="display: none;">
                        <div id="posts-select-tags" class="js-toggle-search-mode-tags d-flex align-items-center" style="width:120px; height: 30px;"></div>
                    </div>
                </div>
            </div>

            <?php mw()->event_manager->trigger('module.content.manager.toolbar.end', $page_info); ?>
        </div>
    </div>
<?php endif; ?>

<?php if ($page_info): ?>
    <?php mw()->event_manager->trigger('module.content.manager.toolbar', $page_info) ?>
<?php endif; ?>
<?php $custom_tabs = mw()->module_manager->ui('content.manager.toolbar'); ?>
<?php if (!empty($custom_tabs)): ?>
    <div id="manage-content-toolbar-tabs">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs" id="manage-content-toolbar-tabs-nav">
            <?php foreach ($custom_tabs as $item): ?>
                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                <a class="mw-ui-btn tip" data-tip="<?php print $title; ?>"> <span
                            class="<?php print $class; ?>"></span> <span> <?php print $title; ?> </span>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="mw-ui-box">
            <?php foreach ($custom_tabs as $item): ?>
                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                <div class="mw-ui-box-content" style="display: none;"><?php print $html; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if (!isset($edit_page_info)): ?>
    <div class="card-body pt-3 pb-0">
        <div class="toolbar row js-hide-when-no-items">
            <div class="col-sm-6 d-md-flex d-none align-items-center justify-content-center justify-content-sm-start my-1">
                <div class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="custom-control-input " id="posts-check">
                    <label class="custom-control-label" for="posts-check"><?php _e('Check all'); ?></label>
                </div>

                <div class="d-inline-block ml-3">
                    <div class="js-bulk-actions" style="display: none;">
                        <select class="selectpickeFr js-bulk-action form-control" title="<?php _e("Bulk actions"); ?>" data-style="btn-sm" data-width="auto">

                            <?php
                            if (user_can_access('module.content.edit')):
                                ?>
                                <option value="assign_selected_posts_to_category"><?php _e("Move to category"); ?></option>
                                <option value="publish_selected_posts"><?php _e("Published"); ?></option>
                                <option value="unpublish_selected_posts"><?php _e("Unpublish"); ?></option>
                            <?php endif; ?>

                            <?php
                            if (user_can_access('module.content.destroy')):
                                ?>
                                <option value="delete_selected_posts"><?php _e("Delete"); ?></option>
                            <?php endif; ?>

                        </select>
                    </div>
                </div>
            </div>
            <script>
                $('.select_posts_for_action').on('change', function () {
                    var all = document.querySelector('.select_posts_for_action:checked');
                    if (all === null) {
                        $('.js-bulk-actions').hide();
                    } else {
                        $('.js-bulk-actions').show();
                    }
                });

                $('.js-bulk-action').on('change', function () {
                    var selectedBulkAction = $('.js-bulk-action option:selected').val();
                    if (selectedBulkAction == 'assign_selected_posts_to_category') {
                        assign_selected_posts_to_category();
                    } else if (selectedBulkAction == 'publish_selected_posts') {
                        publish_selected_posts();
                    } else if (selectedBulkAction == 'unpublish_selected_posts') {
                        unpublish_selected_posts();
                    } else if (selectedBulkAction == 'delete_selected_posts') {
                        delete_selected_posts();
                    }
                });
            </script>
            <?php
            $order_by_field = '';
            $order_by_type = '';
            if (isset($params['data-order'])) {
                $explode_date_order = explode(' ', $params['data-order']);
                if (isset($explode_date_order[1])) {
                    $order_by_field = $explode_date_order[0];
                    $order_by_type = $explode_date_order[1];
                }
            }
            ?>

            <div class="js-table-sorting col-sm-6 text-end text-right my-1 d-flex justify-content-center justify-content-sm-end align-items-center">


                <span class="d-md-block d-none"><?php _e("Limit"); ?>:</span>

                <div class="d-inline-block mx-1">
                   <select class="form-control form-control-sm" onclick="postsLimit({id:'pages_edit_container_content_list', el:this});">
                       <option value="10"   <?php if(isset($params['limit']) && $params['limit']==10): ?>selected="selected"<?php endif; ?>>10</option>
                       <option value="25"   <?php if(isset($params['limit']) && $params['limit']==25): ?>selected="selected"<?php endif; ?>>25</option>
                       <option value="50"   <?php if(isset($params['limit']) && $params['limit']==50): ?>selected="selected"<?php endif; ?>>50</option>
                       <option value="100"   <?php if(isset($params['limit']) && $params['limit']==100): ?>selected="selected"<?php endif; ?>>100</option>
                       <option value="200"   <?php if(isset($params['limit']) && $params['limit']==200): ?>selected="selected"<?php endif; ?>>200</option>
                       <option value="300"   <?php if(isset($params['limit']) && $params['limit']==300): ?>selected="selected"<?php endif; ?>>300</option>
                   </select>

                </div>

                <span class="d-md-block d-none"><?php _e("Sort By"); ?>:</span>

                <div class="d-inline-block mx-1">
                    <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-state="<?php if ($order_by_field == 'created_at'): ?><?php echo $order_by_type; ?><?php endif; ?>" data-sort-type="created_at" onclick="postsSort({id:'pages_edit_container_content_list', el:this});">
                        <?php _e("Date"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                    </button>
                </div>

                <div class="d-inline-block">
                    <button type="button" class="js-sort-btn btn btn-outline-secondary btn-sm icon-right" data-state="<?php if ($order_by_field == 'title'): ?><?php echo $order_by_type; ?><?php endif; ?>" data-sort-type="title" onclick="postsSort({id:'pages_edit_container_content_list', el:this});">
                        <?php _e("Title"); ?> <i class="mdi mdi-chevron-down text-muted"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<script>
    $(document).ready(function () {
        var el = $("#posts-check")
        el.on('change', function () {
            mw.check.toggle('#mw_admin_posts_sortable');

            var all = $('#mw_admin_posts_sortable input[type="checkbox"]');
            var checked = all.filter(':checked');
            if (checked.length && checked.length === all.length) {
                el[0].checked = true;
            }
            else {
                el[0].checked = false;
            }

            var all = document.querySelector('.select_posts_for_action:checked');
            if (all === null) {
                $('.js-bulk-actions').hide();
            } else {
                $('.js-bulk-actions').show();
            }
        });

    });
</script>

<script>
    mw.require('forms.js', true);


    $(document).ready(function () {
        var postsSelectTags = mw.select({
            element: '#posts-select-tags',
            placeholder: 'Filter by tag',
            multiple: true,
            autocomplete: true,
            size: 'small',
            tags: false,
            ajaxMode: {
                paginationParam: 'page',
                searchParam: 'keyword',
                endpoint: mw.settings.api_url + 'tagging_tag/autocomplete',
                method: 'get'
            }
        });

        $(postsSelectTags).on("change", function (event, val) {
            var parent_mod = document.getElementById('pages_edit_container_content_list');
            parent_mod.setAttribute('tags', '');
            if (val.length > 0) {

                var tagSeperated = '';
                for (i = 0; i < val.length; i++) {
                    tagSeperated += val[i].title + ',';
                }

                parent_mod.setAttribute('tags', tagSeperated);
            }
            mw.reload_module(parent_mod);
        });
    });

    postsLimit = function (obj) {

        mw.spinner({
            element: document.querySelector('.toolbar'), decorate: true, size: 26
        }).show();

        var parent_mod = document.getElementById('pages_edit_container');

        var tosend = {};
        tosend.limit = $(obj.el).find(':selected').val();

        if (parent_mod !== undefined) {

            parent_mod.setAttribute('data-limit', tosend.limit);

            mw.reload_module(parent_mod, function (){
                mw.spinner({
                    element: document.querySelector('.toolbar'), decorate: true, size: 26
                }).remove();
            });
        }

    };

    postsSort = function (obj) {
        mw.spinner({
            element: document.querySelector('.toolbar'), decorate: true, size: 26
        }).show();
        var group = mw.tools.firstParentWithClass(obj.el, 'js-table-sorting');
        var parent_mod = document.getElementById('pages_edit_container_content_list');


        var others = group.querySelectorAll('.js-sort-btn'), i = 0, len = others.length;
        for (; i < len; i++) {
            var curr = others[i];
            if (curr !== obj.el) {
                $(curr).removeClass('ASC DESC active');
            }
        }
        obj.el.attributes['data-state'] === undefined ? obj.el.setAttribute('data-state', 0) : '';
        var state = obj.el.attributes['data-state'].nodeValue;

        var jQueryEl = $(obj.el);

        var tosend = {}
        tosend.type = obj.el.attributes['data-sort-type'].nodeValue;
        if (state === '0') {
            tosend.state = 'ASC';
//            obj.el.className = 'js-sort-btn btn btn-outline-primary btn-sm icon-right ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }
        else if (state === 'ASC') {
            tosend.state = 'DESC';
//            obj.el.className = 'js-sort-btn btn btn-outline-primary btn-sm icon-right DESC';
            obj.el.setAttribute('data-state', 'DESC');

            jQueryEl.find('i').removeClass('mdi-chevron-up');
            jQueryEl.find('i').addClass('mdi-chevron-down');
        }
        else if (state === 'DESC') {
            tosend.state = 'ASC';
//            obj.el.className = 'js-sort-btn btn btn-outline-primary btn-sm icon-right ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }
        else {
            tosend.state = 'ASC';
//            obj.el.className = 'js-sort-btn btn btn-outline-primary btn-sm icon-right ASC';
            obj.el.setAttribute('data-state', 'ASC');

            jQueryEl.find('i').removeClass('mdi-chevron-down');
            jQueryEl.find('i').addClass('mdi-chevron-up');
        }

        if (parent_mod !== undefined) {
            parent_mod.setAttribute('data-order', tosend.type + ' ' + tosend.state);
            mw.reload_module(parent_mod, function (){
                mw.spinner({
                    element: document.querySelector('.toolbar'), decorate: true, size: 26
                }).remove();
            });
        }
    }

</script>
