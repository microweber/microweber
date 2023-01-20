<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-content-id', $params['id']); ?>
<?php
if (isset($params['for-current-content-id'])) {
    $params['for-content-id'] = CONTENT_ID;
}

if (isset($params['for-content-id'])) {
    return print '<module type="content/edit" content-id="' . intval($params['for-content-id']) . '" />';
}
?>
<script>mw.lib.require('font_awesome5')</script>
<script type="text/javascript">
    mw.live_edit_load_cats_list = function () {
        mw.$('.mw-module-category-manager').hide();
        mw.$("#mw-live-edit-cats-tab").removeClass('active');

        var cont_id = mw.$("#mw_set_categories_tree_root_page").val();

        mw.$("#mw_add_cat_live_edit").attr("page-id", cont_id);
        mw.load_module('categories/manage_sidebar', '#mw_add_cat_live_edit', function () {

        });
        CatTabs.set(3);
    }


    mw.load_quick_cat_edit = function ($id) {

        if ($id == undefined) {
            mw.$("#mw_select_cat_to_edit_dd").val();
        }
        mw.$("#mw_add_cat_live_edit").attr("data-category-id", $id);

        var cont_id = mw.$("#mw_set_categories_tree_root_page").val();
        if (cont_id == 0) {
            var cont_id = mw.$("#mw_page_id_front").val();

        }

        mw.$("#mw_add_cat_live_edit").attr("page-id", cont_id);

        mw.load_module('categories/edit_category', '#mw_add_cat_live_edit', function () {
            $(document.body).removeClass("loading");
        });
    }

    $(mwd).ready(function () {
        CatTabs = mw.tabs({
            nav: '.mw-ui-btn-nav-tabs a',
            tabs: '.tab',
            onclick: function () {
                mw.$('.mw-module-category-manager').show();
            }
        });
    });


    $(document).ready(function () {
        mw.$("#mw_set_categories_tree_root_page").change(function () {
            var val = this.value;
            mw.$('#mw_add_cat_live_edit').attr('page-id', val);
            mw.reload_module('#mw_add_cat_live_edit')
        });
    });

    function editCategoriesShowManageWindow(module_id) {

        var opts = {};
        opts.width = '800';
        opts.height =  '600';

        opts.liveedit = true;
        opts.mode = 'modal';

        var additional_params = {};
        additional_params.manage_categories = 'yes';

        return window.mw.parent().tools.open_global_module_settings_modal('categories/admin_backend_modal', module_id, opts,additional_params);

    }


</script>

<div class="mw-accordion-item-block   mw-live-edit-module-manage-and-list-top">
    <a  href="javascript:;" onClick="editCategoriesShowManageWindow('<?php print $params['id'] ?>',{mode:'modal', liveedit:false});"
       class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded" style="margin-bottom: 10px;"><span class="fa fa-list"></span>
        &nbsp; <?php _e('Manage categories') ?></a>



</div>
<div class="mw-modules-tabs">


    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-categories-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Show Categories From"); ?></label>
                    <input type="hidden" id="mw_page_id_front" value="<?php print PAGE_ID ?>"/>

                    <select name="data-content-id" id="mw_set_categories_tree_root_page"
                            class="mw-ui-field mw_option_field mw-full-width"
                            data-also-reload="<?php print  $config['the_module'] ?>">
                        <option value="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>
                                title="<?php _e("None"); ?>"><?php _e("None"); ?></option>
                        <?php
                        $pt_opts = array();
                        $pt_opts['link'] = "{title}";
                        $pt_opts['list_tag'] = " ";
                        $pt_opts['list_item_tag'] = "option";
                        $pt_opts['active_ids'] = $posts_parent_page;
                        //$pt_opts['include_categories'] = true;
                        $pt_opts['active_code_tag'] = '   selected="selected"  ';
                        pages_tree($pt_opts);
                        ?>
                    </select>
                </div>

                <?php if ($posts_parent_page != false and intval($posts_parent_page) > 0): ?>
                    <?php $category_id = get_option('data-category-id', $params['id']); ?>
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Show only from category"); ?></label>
                        <select name="data-category-id" id="selcted_categogy_for_parent_category"
                                class="mw-ui-field mw_option_field mw-full-width"
                                data-also-reload="<?php print  $config['the_module'] ?>">
                            <option value='' <?php if ((0 == intval($category_id))): ?>   selected="selected"  <?php endif; ?>><?php _e("Select a category"); ?></option>
                            <?php
                            $pt_opts = array();
                            $pt_opts['link'] = "{title}";
                            $pt_opts['list_tag'] = " ";
                            $pt_opts['list_item_tag'] = "option";
                            $pt_opts['active_ids'] = $category_id;
                            $pt_opts['active_code_tag'] = '   selected="selected"  ';
                            $pt_opts['rel_type'] = 'content';
                            $pt_opts['rel_id'] = $posts_parent_page;
                            category_tree($pt_opts);
                            ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php $selected_max_depth = get_option('data-max-depth', $params['id']); ?>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Max depth"); ?></label>
                    <select name="data-max-depth" class="mw-ui-field mw_option_field mw-full-width"
                            data-also-reload="<?php print  $config['the_module'] ?>">
                        <option value='0' <?php if (0 == intval($selected_max_depth)): ?>   selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value='<?php print $i; ?>' <?php if (($i == intval($selected_max_depth))): ?>   selected="selected"  <?php endif; ?>><?php print $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>


                <?php $only_products_in_stock = get_option('only-products-in-stock', $params['id']); ?>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Show only categories with products in-stock"); ?></label>
                    <select name="only-products-in-stock" class="mw-ui-field mw_option_field mw-full-width"
                            data-also-reload="<?php print  $config['the_module'] ?>">

                        <option value="0">No</option>
                        <option value="1" <?php if ($only_products_in_stock == 1): ?> selected="selected" <?php endif; ?>>Yes</option>

                    </select>
                </div>

            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <?php

    /*  <div class="mw-accordion-item">
          <div class="mw-ui-box-header mw-accordion-title">
              <div class="header-holder" id="mw-live-edit-cats-tab" onclick="mw.live_edit_load_cats_list()">
                  <i class="mw-icon-navicon-round"></i> <?php _e('List of Categories'); ?>
              </div>
          </div>
          <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
              <div id="mw_add_cat_live_edit"></div>
          </div>
      </div>*/

    ?>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
