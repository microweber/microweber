<script type="text/javascript">
    mw.top().dialog.get().resize(1000);
</script>
<div class="card style-1 mb-3 card-in-live-edit">
    <div class="card-header">
        <?php $module_info = module_info('shop/admin'); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#products"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Products'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">

            <div class="tab-pane fade show active" id="products">
                <div class="text-end text-right">
                   <a href="<?php print route('admin.product.create'); ?>" target="_blank" class="btn btn-success btn-sm" ><i class="mdi mdi-shopping"></i> <?php _e("New Product"); ?></a>
                </div>
                <module content_type="product" type="content/manager" no_page_edit="true" id="mw_posts_manage_live_edit" no_toolbar="true"/>
            </div>

            <div class="tab-pane fade" id="settings">
                <div class="module-live-edit-settings module-blog-settings">
                    @if($pages->count() == 0)
                        <div class="alert alert-warning">
                            {{_e('You don\'t have any shop pages.')}}
                            <br />
                            {{_e('Please, create a shop page from admin panel to continue.')}}
                        </div>

                        @else
                    <div class="form-row row">

                        <div class="form-group col-12">
                            <label class="control-label d-block"><?php echo _e("Display products from", true); ?></label>
                            <select name="content_from_id" option-group="<?php echo $moduleId;?>" class="mw_option_field form-control js-filtering-from-content-id" data-width="100%" data-size="5" data-live-search="true">
                                <option value=""><?php echo _e("Select page", true); ?></option>
                                <?php
                                foreach ($pages as $page):
                                ?>
                                <option <?php if (get_option('content_from_id', $moduleId) == $page->id): ?>selected="selected"<?php endif; ?> value="<?php echo $page->id; ?>"><?php echo $page->title;?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('.js-filtering-from-content-id').change(function () {
                                    showFilteringSettingsHolder();
                                });
                                showFilteringSettingsHolder();
                            });
                            function showFilteringSettingsHolder()
                            {
                                if ($('.js-filtering-from-content-id').val() == '') {
                                    $('.js-filtering-settings-holder').slideUp();
                                } else {
                                    $('.js-filtering-settings-holder').slideDown();
                                }
                            }
                        </script>
                        <div class="form-row row js-filtering-settings-holder" style="display: none;">

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('.js-filtering-from-content-id').change(function () {
                                        loadFilteringCustomFieldsTable();
                                    });
                                    <?php if (get_option('filtering_by_custom_fields', $moduleId)): ?>
                                    loadFilteringCustomFieldsTable();
                                    <?php endif; ?>
                                });
                                function loadFilteringCustomFieldsTable() {
                                    var contentFromId = $('.js-filtering-from-content-id').val();
                                    var moduleId = '{{$moduleId}}';
                                    $.post("{{$getCustomFieldsTableRoute}}", {
                                        contentFromId: contentFromId,
                                        moduleId: moduleId
                                    }, function(data, status){
                                        $('.js-filtering-custom-fields-table').html(data);
                                    });
                                }
                            </script>

                            <div class="form-group col-4">
                                <label class="control-label d-block"><?php echo _e("Enable pagination", true); ?></label>
                                <span class="text-muted"><?php echo _e("Show pagination on the page", true); ?></span>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="disable_pagination"><?php _e('No'); ?></label>
                                    <input class="mw_option_field custom-control-input" id="disable_pagination" type="checkbox"
                                           autocomplete="off" name="disable_pagination" <?php if (!get_option('disable_pagination', $moduleId)): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="0" data-value-unchecked="1">
                                    <label class="custom-control-label" for="disable_pagination"><?php _e('Yes'); ?></label>
                                </div>
                            </div>


                            <div class="form-group col-4 js-filtering-allow-limit">
                                <label class="control-label d-block"><?php echo _e("Enable limit", true); ?></label>
                                <span class="text-muted"><?php echo _e("User can define number of products per page", true); ?></span>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="disable_limit"><?php _e('No'); ?></label>
                                    <input class="mw_option_field custom-control-input" id="disable_limit" type="checkbox"
                                           autocomplete="off" name="disable_limit" <?php if (!get_option('disable_limit', $moduleId)): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="0" data-value-unchecked="1">
                                    <label class="custom-control-label" for="disable_limit"><?php _e('Yes'); ?></label>
                                </div>
                            </div>

                            <div class="form-group col-4 js-filtering-products-per-page">
                                <label class="control-label d-block"><?php echo _e("Products per page", true); ?></label>
                                <span class="text-muted"><?php echo _e("Number of products per page", true); ?></span>
                                <input type="text" name="items_per_page" value="<?php echo get_option('items_per_page', $moduleId) ?>" class="mw_option_field form-control" option-group="<?php echo $moduleId;?>" />
                            </div>

                            <div class="form-group col-4 js-filtering-allow-sort">
                                <label class="control-label d-block"><?php echo _e("Enable product sort", true); ?></label>
                                <span class="text-muted"><?php echo _e("Sort products by criteria", true); ?></span>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="disable_sort"><?php _e('No'); ?></label>
                                    <input class="mw_option_field custom-control-input" id="disable_sort" type="checkbox"
                                           autocomplete="off" name="disable_sort" <?php if (!get_option('disable_sort', $moduleId)): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="0" data-value-unchecked="1">
                                    <label class="custom-control-label" for="disable_sort"><?php _e('Yes'); ?></label>
                                </div>
                            </div>

                            <div class="form-group col-4">
                                <label class="control-label d-block"><?php echo _e("Filtering the results", true); ?></label>
                                <span class="text-muted"><?php echo _e("Show filters on the shop page", true); ?></span>
                                <div class="custom-control custom-switch pl-0">
                                    <label class="d-inline-block mr-5" for="disable_filter"><?php _e('No'); ?></label>
                                    <input class="mw_option_field custom-control-input" id="disable_filter" type="checkbox"
                                           autocomplete="off" name="disable_filter" <?php if (!get_option('disable_filter', $moduleId)): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="0" data-value-unchecked="1">
                                    <label class="custom-control-label" for="disable_filter"><?php _e('Yes'); ?></label>
                                </div>
                            </div>

                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $('#disable_filter').change(function() {
                                        if ($(this).prop('checked')) {
                                            $('.js-blog-filtering-the-results').fadeIn();
                                        } else {
                                            $('.js-blog-filtering-the-results').fadeOut();
                                        }
                                    });

                                    $('#filter_by_custom_fields').change(function() {
                                        if ($(this).prop('checked')) {
                                            loadFilteringCustomFieldsTable();
                                            $('.js-filterting-custom-fields-settings').fadeIn();
                                        } else {
                                            $('.js-filterting-custom-fields-settings').fadeOut();
                                        }
                                    });

                                });
                            </script>

                            <div class="col-12 js-blog-filtering-the-results" <?php if (get_option('disable_filter', $moduleId)): ?>style="display:none"<?php endif; ?>>

                                <div class="card">
                                    <div class="card-body">
                                        <strong><?php _e("Filtering the results"); ?></strong>


                                        <script>
                                            mw.manage_shop_filters_sort = function () {
                                                if (!mw.$("#js-shop-filters-items").hasClass("ui-sortable")) {
                                                    mw.$("#js-shop-filters-items").sortable({
                                                        items: 'div.list-group-item',
                                                        axis: 'y',
                                                        handle: '.list-group-item-handle-sort',
                                                        update: function () {
                                                            var filters = [];
                                                            $(document).find('.js-shop-filter-item-sort').each(function () {
                                                                filters.push(this.attributes['data-filter-name'].nodeValue);
                                                            });
                                                            var data = {
                                                                option_group: '{{$moduleId}}',
                                                                option_key: 'filters-sort',
                                                                option_value: JSON.stringify(filters)
                                                            }
                                                            mw.options.saveOption(data, function () {
                                                                // Saved
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
                                            }
                                            $(document).ready(function () {
                                                mw.manage_shop_filters_sort();
                                            });
                                        </script>

                                        <div class="list-group mt-3" id="js-shop-filters-items">
                                            <div data-filter-name="search" class="js-shop-filter-item-sort d-flex align-items-center align-content-center list-group-item list-group-item-action" aria-current="true">
                                                <div class="mr-3 mt-1">
                                                    <i class="list-group-item-handle-sort mdi mdi-cursor-move mdi-18px text-muted"></i>
                                                </div>
                                                <div class="custom-control custom-checkbox mt-3 js-disable-search">
                                                    <input type="checkbox" <?php if (!get_option('disable_search', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" data-value-checked="0" data-value-unchecked="1" name="disable_search" value="1" id="disable_search">
                                                    <label class="custom-control-label" for="disable_search"><?php _e("Search"); ?></label>
                                                </div>
                                            </div>
                                            <div data-filter-name="tags" class="js-shop-filter-item-sort d-flex align-items-center align-content-center list-group-item list-group-item-action" aria-current="true">
                                                <div class="mr-3 mt-1">
                                                <i class="list-group-item-handle-sort mdi mdi-cursor-move mdi-18px text-muted"></i>
                                                </div>
                                                <div class="custom-control custom-checkbox js-filtering-by-tags">
                                                    <input type="checkbox" <?php if ('1'== get_option('filtering_by_tags', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_tags" value="1" id="filter_by_tags">
                                                    <label class="custom-control-label" for="filter_by_tags"><?php _e("Tags"); ?></label>
                                                </div>
                                            </div>
                                            <div data-filter-name="categories" class="js-shop-filter-item-sort d-flex align-items-center align-content-center list-group-item list-group-item-action" aria-current="true">
                                                <div class="mr-3 mt-1">
                                                <i class="list-group-item-handle-sort mdi mdi-cursor-move mdi-18px text-muted"></i>
                                                </div>
                                                <div class="custom-control custom-checkbox js-filtering-by-categories">
                                                    <input type="checkbox" <?php if ('1' == get_option('filtering_by_categories', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_categories" value="1" id="filter_by_categories">
                                                    <label class="custom-control-label" for="filter_by_categories"><?php _e("Categories"); ?></label>
                                                </div>
                                            </div>
                                            <div data-filter-name="custom_fields" class="js-shop-filter-item-sort d-flex align-items-center align-content-center list-group-item list-group-item-action" aria-current="true">
                                                <div class="mr-3 mt-1">
                                                <i class="list-group-item-handle-sort mdi mdi-cursor-move mdi-18px text-muted"></i>
                                                </div>
                                                <div class="custom-control custom-checkbox js-filtering-by-custom-fields">
                                                    <input type="checkbox" <?php if (get_option('filtering_by_custom_fields', $moduleId)=='1'): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_custom_fields" value="1" id="filter_by_custom_fields">
                                                    <label class="custom-control-label" for="filter_by_custom_fields"><?php _e("Custom Fields"); ?></label>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="js-filterting-custom-fields-settings mt-4" <?php if (!get_option('filtering_by_custom_fields', $moduleId)): ?>style="display:none"<?php endif; ?>>
                                            <div class="card">
                                                <div class="card-body">

                                            <div class="form-group">
                                                <label class="control-label d-block"><?php echo _e("Apply filter with", true); ?></label>
                                                <select name="filtering_when" option-group="<?php echo $moduleId;?>" class="mw_option_field form-control">
                                                    <option <?php if (get_option('filtering_when', $moduleId) == 'automatically'): ?> selected="selected" <?php endif; ?> value="automatically">{{_e("Automatically")}}</option>
                                                    <option <?php if (get_option('filtering_when', $moduleId) == 'apply_button'): ?> selected="selected" <?php endif; ?> value="apply_button">{{_e("Apply Button")}}</option>
                                                </select>
                                            </div>

                                            <div class="js-filtering-custom-fields-table"></div>


                                            <input type="hidden" name="filtering_by_custom_fields_order" value="" class="mw_option_field js-filtering-custom-fields-ordering">

                                            <script type="text/javascript">
                                                function encodeObjectToUrl(object)
                                                {
                                                    var parameters = [];
                                                    for (var property in object) {
                                                        if (object.hasOwnProperty(property)) {
                                                            parameters.push(encodeURI(property + '=' + object[property]));
                                                        }
                                                    }

                                                    return parameters.join('&');
                                                }
                                                $(document).ready(function () {
                                                    mw.$(".js-filterting-custom-fields-settings").sortable({
                                                        items: '.js-filter-custom-field-holder',
                                                        //helper:"clone",
                                                        axis: 'y',
                                                        cancel: ".country-id-0",
                                                        handle: '.js-filter-custom-field-handle-field',
                                                        update: function () {
                                                            var obj = {order: []}
                                                            $(this).find('.js-filter-custom-field-holder').each(function () {
                                                                var id = this.attributes['data-field-custom-field-key'].nodeValue;
                                                                obj.order.push(id);
                                                            });
                                                            $('.js-filtering-custom-fields-ordering').val(encodeObjectToUrl(obj.order));
                                                            $('.js-filtering-custom-fields-ordering').trigger('change');
                                                        },
                                                        start: function (a, ui) {
                                                            $(this).height($(this).outerHeight());
                                                            $(ui.placeholder).height($(ui.item).outerHeight())
                                                            $(ui.placeholder).width($(ui.item).outerWidth())
                                                        },
                                                        stop: function () {
                                                            mw.$(".js-filterting-custom-fields-settings").height("auto");
                                                        },
                                                        scroll: false,
                                                        placeholder: "custom-field-main-table-placeholder"
                                                    });
                                                });
                                            </script>


                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" <?php if ('1' == get_option('filtering_show_picked_first', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_show_picked_first" value="1" id="filtering_show_picked_first">
                                                <label class="custom-control-label" for="filtering_show_picked_first"><?php _e("Show picked filters first"); ?></label>
                                            </div>
                                        </div>

                                     {{--   <div class="custom-control custom-checkbox">
                                            <input type="checkbox" <?php if ('1' == get_option('filtering_by_template_fields', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_template_fields" value="1" id="filter_by_template_fields">
                                            <label class="custom-control-label" for="filter_by_template_fields"><?php _e("Template fields"); ?></label>
                                        </div>--}}

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates" parent-module="shop" parent-module-id="{{$moduleId}}" />
            </div>
        </div>

    </div>
</div>
