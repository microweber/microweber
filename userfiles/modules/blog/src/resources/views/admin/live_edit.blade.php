<script type="text/javascript">
    mw.top().dialog.get().resize(1000);
</script>
<div class="card style-1 mb-3 card-in-live-edit">
    <div class="card-header">
        <?php $module_info = module_info('blog/admin'); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php _e($module_info['name']); ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">

            <div class="tab-pane fade show active" id="settings">
                <div class="module-live-edit-settings module-blog-settings">
                    <div class="form-row row">
                        <?php
                        $pages = \MicroweberPackages\Content\Content::where('content_type', 'page')
                            ->where('subtype','dynamic')
                           // ->where('is_shop', 0)
                            ->get();
                        ?>

                        <div class="form-group col-12">
                            <label class="control-label d-block"><?php echo _e("Display content from", true); ?></label>
                            <select name="content_from_id" option-group="<?php echo $moduleId;?>" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                                <option value=""><?php echo _e("Select", true); ?></option>
                                <?php
                                foreach ($pages as $page):
                                    ?>
                                    <option <?php if (get_option('content_from_id', $moduleId) == $page->id): ?>selected="selected"<?php endif; ?> value="<?php echo $page->id; ?>"><?php echo $page->title;?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <label class="control-label d-block"><?php echo _e("Allow pagination", true); ?></label>
                            <span class="help-block"><?php echo _e("Allow limitation of posts on page", true); ?></span>
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="pagination_the_results"><?php _e('No'); ?></label>
                                <input class="mw_option_field custom-control-input" id="pagination_the_results" type="checkbox"
                                       autocomplete="off" name="pagination_the_results" <?php if (get_option('pagination_the_results', $moduleId) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="1" data-value-unchecked="0">
                                <label class="custom-control-label" for="pagination_the_results"><?php _e('Yes'); ?></label>
                            </div>
                        </div>


                        <div class="form-group col-4">
                            <label class="control-label d-block"><?php echo _e("Allow limit", true); ?></label>
                            <span class="help-block"><?php echo _e("Allow limitation of posts on page", true); ?></span>
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="limit_the_results"><?php _e('No'); ?></label>
                                <input class="mw_option_field custom-control-input" id="limit_the_results" type="checkbox"
                                       autocomplete="off" name="limit_the_results" <?php if (get_option('limit_the_results', $moduleId) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="1" data-value-unchecked="0">
                                <label class="custom-control-label" for="limit_the_results"><?php _e('Yes'); ?></label>
                            </div>
                        </div>

                        <div class="form-group col-4">
                            <label class="control-label d-block"><?php echo _e("Allow sorting", true); ?></label>
                            <span class="help-block"><?php echo _e("Allow limitation of posts on page", true); ?></span>
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="sort_the_results"><?php _e('No'); ?></label>
                                <input class="mw_option_field custom-control-input" id="sort_the_results" type="checkbox"
                                       autocomplete="off" name="sort_the_results" <?php if (get_option('sort_the_results', $moduleId) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="1" data-value-unchecked="0">
                                <label class="custom-control-label" for="sort_the_results"><?php _e('Yes'); ?></label>
                            </div>
                        </div>

                        <div class="form-group col-12">
                            <label class="control-label d-block"><?php echo _e("Filtering the results", true); ?></label>
                            <div class="custom-control custom-switch pl-0">
                                <label class="d-inline-block mr-5" for="filtering_the_results"><?php _e('No'); ?></label>
                                <input class="mw_option_field custom-control-input" id="filtering_the_results" type="checkbox"
                                       autocomplete="off" name="filtering_the_results" <?php if (get_option('filtering_the_results', $moduleId) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $moduleId;?>" data-value-checked="1" data-value-unchecked="0">
                                <label class="custom-control-label" for="filtering_the_results"><?php _e('Yes'); ?></label>
                            </div>
                        </div>

                        <script type="text/javascript">
                            $(document).ready(function () {
                                $('#filtering_the_results').change(function() {
                                    if ($(this).prop('checked')) {
                                        $('.js-blog-filtering-the-results').fadeIn();
                                    } else {
                                        $('.js-blog-filtering-the-results').fadeOut();
                                    }
                                });

                                $('#filter_by_custom_fields').change(function() {
                                    if ($(this).prop('checked')) {
                                        $('.js-filterting-custom-fields-settings').fadeIn();
                                    } else {
                                        $('.js-filterting-custom-fields-settings').fadeOut();
                                    }
                                });

                            });
                        </script>

                        <div class="col-12 js-blog-filtering-the-results" <?php if (get_option('filtering_the_results', $moduleId) != '1'): ?>style="display:none"<?php endif; ?>>

                            <div class="card">
                                <div class="card-body">
                                    <strong>Filter by</strong>

                                    <div class="custom-control custom-checkbox mt-3">
                                        <input type="checkbox" <?php if ('1'== get_option('filtering_by_tags', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_tags" value="1" id="filter_by_tags">
                                        <label class="custom-control-label" for="filter_by_tags"><?php _e("Tags"); ?></label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" <?php if ('1' == get_option('filtering_by_categories', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_categories" value="1" id="filter_by_categories">
                                        <label class="custom-control-label" for="filter_by_categories"><?php _e("Categories"); ?></label>
                                    </div>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" <?php if ('1' == get_option('filtering_by_custom_fields', $moduleId)): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_custom_fields" value="1" id="filter_by_custom_fields">
                                        <label class="custom-control-label" for="filter_by_custom_fields"><?php _e("Custom Fields"); ?></label>
                                    </div>

                                    <div class="js-filterting-custom-fields-settings">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td style="width:40px"></td>
                                                <td></td>
                                                <td><?php _e("Control"); ?></td>
                                                <td><?php _e("Enable"); ?></td>
                                            </tr>
                                            </thead>

                                        @foreach($customFieldNames as $customFieldKey=>$customFieldName)
                                            <tr class="js-filter-custom-field-holder vertical-align-middle show-on-hover-root" data-field-custom-field-key="{{$customFieldKey}}">
                                                <td>
                                                    <i data-title="<?php _e("Reorder filters"); ?>" data-toggle="tooltip" class="js-filter-custom-field-handle-field mdi mdi-cursor-move mdi-18px text-muted show-on-hover" style="cursor: pointer;"></i>
                                                </td>
                                                <td>
                                                    {{ucfirst($customFieldName)}}
                                                </td>

                                                <td>
                                                    @php
                                                        $customFieldControlTypeOptionName = 'filtering_by_custom_fields_control_type_' . $customFieldKey;
                                                    @endphp
                                                    <select class="mw_option_field form-control" name="{{$customFieldControlTypeOptionName}}">
                                                        <option value="" disabled="disabled"><?php _e("Select control type"); ?></option>
                                                        <option value="checkbox" <?php if ('checkbox' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Checkbox"); ?></option>
                                                        <option value="radio" <?php if ('radio' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Radio button"); ?></option>
                                                        <option value="select" <?php if ('select' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Selectbox"); ?></option>
                                                        <option value="slider" <?php if ('slider' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Slider"); ?></option>
                                                        <option value="price_range" <?php if ('price_range' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Price Range"); ?></option>
                                                        <option value="square_checkbox" <?php if ('square_checkbox' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Square checkbox"); ?></option>
                                                        <option value="color" <?php if ('color' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Color"); ?></option>
                                                        <option value="date" <?php if ('date' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Date"); ?></option>
                                                        <option value="date_range" <?php if ('date_range' == get_option($customFieldControlTypeOptionName, $moduleId)): ?>selected="selected"<?php endif; ?>><?php _e("Date Range"); ?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @php
                                                        $customFieldOptionName = 'filtering_by_custom_fields_' . $customFieldKey;
                                                    @endphp
                                                    <div class="custom-control custom-switch pl-0">
                                                        <label class="d-inline-block mr-5" for="{{$customFieldOptionName}}"></label>
                                                        <input type="checkbox" <?php if ('1' == get_option($customFieldOptionName, $moduleId)): ?>checked="checked"<?php endif; ?> name="{{$customFieldOptionName}}" data-value-checked="1" data-value-unchecked="0" id="{{$customFieldOptionName}}" class="mw_option_field custom-control-input">
                                                        <label class="custom-control-label" for="{{$customFieldOptionName}}"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </table>

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

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>

    </div>
</div>
