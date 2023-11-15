<div>
    <div x-data="{
    showEditTab: 'content'
    }">

        <div class="d-flex justify-content-between align-items-center collapseNav-initialized form-control-live-edit-label-wrapper">
            <div class="d-flex flex-wrap gap-md-4 gap-3">
                <a href="#" x-on:click="showEditTab = 'content'" :class="{ 'active': showEditTab == 'content' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs active">
                    Content
                </a>
                <a href="#" x-on:click="showEditTab = 'settings'" :class="{ 'active': showEditTab == 'settings' }"
                   class="btn btn-link text-decoration-none mw-admin-action-links mw-adm-liveedit-tabs">
                    Settings
                </a>
            </div>
        </div>

        <div x-show="showEditTab=='content'">


            <?php
            $selected_category = get_option('fromcategory', $moduleId);
            $selected_page = get_option('frompage', $moduleId);
            $show_category_header = get_option('show_category_header', $moduleId);
            $show_only_for_parent = get_option('single-only', $moduleId);
            $show_subcats = get_option('show-subcats', $moduleId);
            $hide_pages = get_option('hide-pages', $moduleId);
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

                            let parentPageContent = pages.join(',');
                            let parentCatContent = cats.join(',');

                            mw.options.saveOption({
                                option_group: '<?php print $moduleId; ?>',
                                module: '<?php print $moduleType; ?>',
                                option_key: 'fromcategory',
                                option_value: parentCatContent
                            });

                            mw.options.saveOption({
                                option_group: '<?php print $moduleId; ?>',
                                module: '<?php print $moduleType; ?>',
                                option_key: 'frompage',
                                option_value: parentPageContent,
                            });

                        })
                    });
                });
            </script>

                <div class="p-2">

                    <div class="module-live-edit-settings module-categories-image-settings">

                        <label class="mw-ui-label"><?php _e('Select parent category'); ?></label>
                        <div class="mw-ui-field-holder">

                            <input type="text" style="min-width: 200px"  class="mw-ui-searchfield" placeholder="Search categories" id="tree-search" />
                        </div>

                        <div id="category-parent-selector" dir="ltr"></div>

                    </div>
                </div>

                <div class="mt-1">
                    <label class="live-edit-label">{{__('Show only parent category')}} </label>
                    <livewire:microweber-option::toggle optionKey="single-only" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div class="mt-1">
                    <label class="live-edit-label">{{__('Show sub categories')}} </label>
                    <livewire:microweber-option::toggle optionKey="show-subcats" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div class="mt-1">
                    <label class="live-edit-label">{{__('Hide pages')}} </label>
                    <livewire:microweber-option::toggle optionKey="hide-pages" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

                <div class="mt-1">
                    <label class="live-edit-label">{{__('Show only products in stock')}} </label>
                    <livewire:microweber-option::toggle optionKey="filter-only-in-stock" :optionGroup="$moduleId" :module="$moduleType"  />
                </div>

        </div>
        <div x-show="showEditTab=='settings'">
            This is the settings tab
        </div>

    </div>

</div>
