<?php if (!isset($data)) {
    $data = $params;
}


$custom_tabs = mw()->modules->ui('content.edit.tabs');

?>
<script>
    mw.lib.require('colorpicker');
</script>
<div id="settings-tabs">

    <div>
        <div class="card style-1 mb-3 categories" style="">
        <div class="card-body pt-3">
            <?php if (isset($data['url']) and $data['id'] > 0): ?>
                <script>
                    $(document).ready(function () {
                        $('.go-live-edit-href-set').attr('href', '<?php print content_link($data['id']); ?>');
                    });
                </script>
            <?php endif; ?>

            <?php if ($data['content_type'] == 'page') : ?>
                <div class="mw-admin-edit-page-primary-settings parent-selector ">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("Select parent page"); ?></label>
                        <div class="quick-parent-selector">
                            <module
                                type="content/views/selector"
                                no-parent-title="<?php _e('No parent page'); ?>"
                                field-name="parent_id_selector"
                                change-field="parent"
                                selected-id="<?php print $data['parent']; ?>"
                                remove_ids="<?php print $data['id']; ?>"
                                recommended-id="<?php print $recommended_parent; ?>"/>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Select Category'); ?></label>
                </div>
            <?php endif; ?>

            <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                <div class="mw-admin-edit-page-primary-settings content-category-selector">
                    <div class="mw-ui-field-holder">
                        <script>


                            $(document).ready(function(){
                                $('#mw-post-added-<?php print $rand; ?>').on('mousedown touchstart', function(e){
                                    if(e.target.nodeName === 'DIV') {
                                        setTimeout(function () {
                                            $('.mw-ui-invisible-field', e.target).focus()
                                        },78)
                                    }
                                });

                                var all = [{type: 'page', id: <?php print $data['parent']; ?>}];

                                var cats = [<?php print $categories_active_ids; ?>];

                                $.each(cats, function () {
                                    all.push({
                                        type:'category',
                                        id: this
                                    })
                                });
                                if(typeof(mw.adminPagesTree) != 'undefined') {
                                    mw.adminPagesTree.select(all);
                                }
                            });
                        </script>

                        <div class="mw-tag-selector" id="mw-post-added-<?php print $rand; ?>">
                            <div class="post-category-tags" ></div>
                        </div>
                        <div class="mw-ui-category-selector mw-ui-category-selector-abs mw-tree mw-tree-selector"
                             id="mw-category-selector-<?php print $rand; ?>">

                            <?php if ($data['content_type'] != 'page' and $data['subtype'] != 'category'): ?>
                                <script>
                                    $(document).ready(function () {
                                        $.get("<?php print api_url('content/get_admin_js_tree_json'); ?>", function (tdata) {

                                            var selectedPages = [ <?php print $data['parent']; ?>];
                                            var selectedCategories = [ <?php print $categories_active_ids; ?>];

                                            window.categorySelector = new mw.treeTags({
                                                data: tdata,
                                                selectable: true,
                                                multiPageSelect: false,
                                                tagsHolder: '.post-category-tags',
                                                treeHolder: '#quick-parent-selector-tree',
                                                color: 'primary',
                                                size: 'sm',
                                                outline: true,
                                                saveState:false
                                            });

                                            $(categorySelector.tree).on('ready', function () {
                                                if (window.pagesTree && pagesTree.selectedData.length) {
                                                    $.each(pagesTree.selectedData, function () {
                                                        categorySelector.tree.select(this)
                                                    })
                                                }
                                                else {
                                                    $.each(selectedPages, function () {
                                                        categorySelector.tree.select(this, 'page')
                                                    });
                                                    $.each(selectedCategories, function () {
                                                        categorySelector.tree.select(this, 'category')
                                                    });
                                                }


                                                var atcmplt = $("<input type='text' class='mw-ui-invisible-field'>");

                                                $(".post-category-tags").after(atcmplt);

                                                atcmplt.on('input', function(){
                                                    var val = this.value.toLowerCase().trim();
                                                    if(!val){
                                                        categorySelector.tree.showAll();
                                                    }
                                                    else{
                                                        categorySelector.tree.options.data.forEach(function(item){

                                                            if(item.title.toLowerCase().indexOf(val) === -1){
                                                                categorySelector.tree.hide(item);
                                                            }
                                                            else{
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

                                        });
                                    })
                                </script>


                                <div id="quick-parent-selector-tree">

                                </div>
                                <!-- <module
                                        type="categories/selector"
                                        for="content"
                                        active_ids="<?php print $data['parent']; ?>"
                                        subtype="<?php print $data['subtype']; ?>"
                                        categories_active_ids="<?php print $categories_active_ids; ?>"
                                        for-id="<?php print $data['id']; ?>"/> -->

                            <?php include(__DIR__ . '/edit_default_scripts_two.php'); ?>
                                <div id="category-tree-not-found-message">
                                    <h3><?php _e("Category"); ?> "<span id="category-not-found-name"></span>" <?php _e("not found"); ?>.</h3>
                                    <br>
                                    <span class="mw-ui-btn mw-ui-btn-invert" onclick="CreateCategoryForPost(3)"><em class="mw-icon-plus"></em><?php _e("Create it"); ?></span>
                                </div>
                                <div id="parent-category-selector-block">
                                    <h3>
                                        <?php _e("Select parent"); ?>
                                    </h3>

                                    <div id="parent-category-selector-holder"></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        </div>

        <div class="card style-1 mb-3 images" >
            <div id="edit-post-gallery-main" class="card-body pt-3" type="pictures/admin" for="content" for-id="<?php print $data['id']; ?>"></div>
        </div>

        <?php if ($data['content_type'] == 'page'): ?>
            <div class="mw-ui-box-content mw-settings-tabs-content menus" >
                <?php event_trigger('mw_edit_page_admin_menus', $data); ?>
                <?php if (isset($data['add_to_menu'])): ?>
                    <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>" add_to_menu="<?php print $data['add_to_menu']; ?>"/>
                <?php else: ?>
                    <module type="menu" view="edit_page_menus" content_id="<?php print $data['id']; ?>"/>
                <?php endif; ?>

                <?php event_trigger('mw_admin_edit_page_after_menus', $data); ?>
                <?php event_trigger('mw_admin_edit_page_tab_2', $data); ?>
            </div>
        <?php endif; ?>


        <div class="card style-1 mb-3 fields">
            <div class="card-body pt-3">
                <module type="custom_fields/admin"
                    <?php if (trim($data['content_type']) == 'product'): ?> default-fields="price" <?php endif; ?>
                        content-id="<?php print $data['id'] ?>" suggest-from-related="true" list-preview="true" id="fields_for_post_<?php print $data['id']; ?>"/>
                <?php event_trigger('mw_admin_edit_page_tab_3', $data); ?>
            </div>
        </div>

        <?php if (trim($data['content_type']) == 'product'): ?>
            <div class="card style-1 mb-3">
                <div class="card-body pt-3">
                    <?php event_trigger('mw_edit_product_admin', $data); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card style-1 mb-3 advanced">
            <div class="card-body pt-3">

                <?php event_trigger('mw_admin_edit_page_tab_4', $data); ?>
                <module type="content/views/advanced_settings" content-id="<?php print $data['id']; ?>" content-type="<?php print $data['content_type']; ?>" subtype="<?php print $data['subtype']; ?>"/>
            </div>
        </div>

        <?php if ($data['content_type'] == 'old_page'): ?>
            <?php
            $no_content_type_setup_from_layout = false;
            if ($data['content_type'] != 'page' and $data['content_type'] != 'post' and $data['content_type'] != 'product') {
                $no_content_type_setup_from_layout = true;
            } else if (isset($data['subtype']) and $data['subtype'] != 'static' and $data['subtype'] != 'dynamic' and $data['subtype'] != 'post' and $data['subtype'] != 'product') {
                $no_content_type_setup_from_layout = true;
            }
            if ($no_content_type_setup_from_layout != false) {
                $no_content_type_setup_from_layout = ' no_content_type_setup="true" ';
            }
            ?>

        <div class="card style-1 mb-3 old-page">
            <div class="card-body pt-3">
                <div type="content/views/layout_selector" id="mw-quick-add-choose-layout" template-selector-position="bottom" content-id="<?php print $data['id']; ?>" inherit_from="<?php print $data['parent']; ?>" <?php print $no_content_type_setup_from_layout ?> ></div>
            </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($custom_tabs)): ?>
            <?php foreach ($custom_tabs as $item): ?>
                <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                <div class="card style-1 mb-3 custom-tabs"><div class="card-body pt-3"><?php print $html; ?></div></div>
            <?php endforeach; ?>
        <?php endif; ?>
        <?php event_trigger('content/views/advanced_settings', $data); ?>
    </div>
</div>

<script>
$(document).ready(function () {
	pick1 = mw.colorPicker({
		element: '.mw-ui-color-picker',
		position: 'bottom-left',
		onchange: function (color) {
			//
		}
	});
});
</script>
