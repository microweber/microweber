<?php
$selected_category = get_option('fromcategory', $params['id']);
$selected_page = get_option('frompage', $params['id']);
$show_category_header = get_option('show_category_header', $params['id']);
$show_only_for_parent = get_option('single-only', $params['id']);
$show_subcats = get_option('show-subcats', $params['id']);
$hide_pages = get_option('hide-pages', $params['id']);
$my_tree_id = ''
?>
<style  scoped="scoped">
    #parentcat .depth-1 {
        padding-left: 10px;
    }
    #parentcat .depth-2 {
        padding-left: 20px;
    }
    #parentcat .depth-3 {
        padding-left: 30px;
    }
    #parentcat .depth-4 {
        padding-left: 40px;
    }
</style>

<script type="text/javascript">
    mw.require('tree.js')
</script>

<style>
    .module-categories-image-settings .level-1:not(.has-children):not(.type-category) {
        display: none;
    }
</style>

<script type="text/javascript">
    var selectedData = [];

    <?php if($selected_category){ ?>
    <?php $selected_category_explode = explode(',', $selected_category); ?>
    <?php foreach ($selected_category_explode as $sel){ ?>
    selectedData.push({
        id: <?php print $sel; ?>,
        type: 'category'
    })
    <?php } ?>
    <?php } ?>

    <?php if($selected_page){ ?>
    <?php $selected_page_explode = explode(',', $selected_page); ?>
    <?php foreach ($selected_page_explode as $sel){ ?>
    selectedData.push({
        id: <?php print $sel; ?>,
        type: 'page'
    })
    <?php } ?>
    <?php } ?>

    $(document).ready(function () {
        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (data) {

            if (!Array.isArray(data)) {
                var data = [];
            }

            data.unshift({
                id: 0,
                type: 'category',
                title: 'None',
                "parent_id": 0,
                "parent_type": "category"
            });

            var categoryParentSelector = new mw.tree({
                element: "#category-parent-selector",
                selectable: true,
                selectedData: selectedData,
                data: data,
                searchInput: '#tree-search'
            })

            $(categoryParentSelector).on("selectionChange", function (e, selected) {

                var pages = [];
                var cats = [];

                $.each(selected, function (key, value) {
                    var parent = value;
                    if (parent.type) {
                        if (parent.type == 'page') {
                            pages.push(parent.id)
                        }
                        if (parent.type == 'category') {
                            cats.push(parent.id)
                        }
                    }
                });

                $('#parentpage').val(pages.join(',')).trigger('change');
                $('#parentcat').val(cats.join(',')).trigger('change');

            })
        });
    });
</script>

<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content card">

            <div class="module-live-edit-settings module-categories-image-settings">
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>


                <input type="hidden" name="fromcategory" id="parentcat" value="<?php print $selected_category; ?>"
                       class="mw_option_field"/>
                <input type="hidden" name="frompage" id="parentpage" value="<?php print $selected_page; ?>"
                       class="mw_option_field"/>

                <label class="mw-ui-label"><?php _e('Select parent category'); ?></label>
                <div class="mw-ui-field-holder">

                    <input type="text" style="min-width: 200px"  class="mw-ui-searchfield" placeholder="Search categories" id="tree-search" />
                </div>


                <div id="category-parent-selector" dir="ltr"></div>

                <br>
                <hr>
                <div class="form-group">
                    <div class="checkbox">
                        <label class="mw-ui-check">
                            <input type="checkbox" class="mw_option_field" name="single-only"
                                   value="single-only" <?php if ($show_only_for_parent == '1') {
                                echo 'checked';
                            } ?> /> <span></span><span><?php _e("Show only parent category"); ?></span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label class="mw-ui-check">
                            <input type="checkbox" class="mw_option_field" name="show-subcats"
                                   value="1" <?php if ($show_subcats == '1') {
                                echo 'checked';
                            } ?> /> <span></span><span><?php _e("Show sub categories"); ?></span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label class="mw-ui-check">
                            <input type="checkbox" class="mw_option_field" name="hide-pages"
                                   value="1" <?php if ($hide_pages == '1') {
                                echo 'checked';
                            } ?> /> <span></span><span><?php _e("Hide pages"); ?></span>
                        </label>
                    </div>
                </div>

                  <div class="form-group">
                    <div class="checkbox">
                        <label class="mw-ui-check">
                            <input type="checkbox" class="mw_option_field" name="filter-only-in-stock"
                                   value="1" <?php if (get_option('filter-only-in-stock', $params['id']) == '1') {
                                echo 'checked';
                            } ?> /> <span></span><span><?php _e("Show only products in stock"); ?></span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content card">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>
