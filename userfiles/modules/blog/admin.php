<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>
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
                                ->get();
                    ?>

                    <div class="form-group col-12">
                        <label class="control-label d-block"><?php echo _e("Display content from", true); ?></label>
                        <select name="content_from_id" option-group="<?php echo $params['id'];?>" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <?php
                            foreach ($pages as $page):
                            ?>
                            <option <?php if (get_option('content_from_id', $params['id']) == $page->id): ?>selected="selected"<?php endif; ?> value="<?php echo $page->id; ?>"><?php echo $page->title;?></option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-4">
                        <label class="control-label d-block"><?php echo _e("Pagination", true); ?></label>
                        <div class="custom-control custom-switch pl-0">
                            <label class="d-inline-block mr-5" for="pagination_the_results"><?php _e('No'); ?></label>
                            <input class="mw_option_field custom-control-input" id="pagination_the_results" type="checkbox"
                                   autocomplete="off" name="pagination_the_results" <?php if (get_option('pagination_the_results', $params['id']) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $params['id'];?>" data-value-checked="1" data-value-unchecked="0">
                            <label class="custom-control-label" for="pagination_the_results"><?php _e('Yes'); ?></label>
                        </div>
                    </div>


                    <div class="form-group col-4">
                        <label class="control-label d-block"><?php echo _e("Limit", true); ?></label>
                        <div class="custom-control custom-switch pl-0">
                            <label class="d-inline-block mr-5" for="limit_the_results"><?php _e('No'); ?></label>
                            <input class="mw_option_field custom-control-input" id="limit_the_results" type="checkbox"
                                   autocomplete="off" name="limit_the_results" <?php if (get_option('limit_the_results', $params['id']) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $params['id'];?>" data-value-checked="1" data-value-unchecked="0">
                            <label class="custom-control-label" for="limit_the_results"><?php _e('Yes'); ?></label>
                        </div>
                    </div>

                    <div class="form-group col-4">
                        <label class="control-label d-block"><?php echo _e("Sort", true); ?></label>
                        <div class="custom-control custom-switch pl-0">
                            <label class="d-inline-block mr-5" for="sort_the_results"><?php _e('No'); ?></label>
                            <input class="mw_option_field custom-control-input" id="sort_the_results" type="checkbox"
                                   autocomplete="off" name="sort_the_results" <?php if (get_option('sort_the_results', $params['id']) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $params['id'];?>" data-value-checked="1" data-value-unchecked="0">
                            <label class="custom-control-label" for="sort_the_results"><?php _e('Yes'); ?></label>
                        </div>
                    </div>

                    <div class="form-group col-12">
                        <label class="control-label d-block"><?php echo _e("Filtering the results", true); ?></label>
                        <div class="custom-control custom-switch pl-0">
                            <label class="d-inline-block mr-5" for="filtering_the_results"><?php _e('No'); ?></label>
                            <input class="mw_option_field custom-control-input" id="filtering_the_results" type="checkbox"
                                   autocomplete="off" name="filtering_the_results" <?php if (get_option('filtering_the_results', $params['id']) == '1'): ?>checked<?php endif; ?> option-group="<?php echo $params['id'];?>" data-value-checked="1" data-value-unchecked="0">
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

                        });
                    </script>

                    <div class="col-12 js-blog-filtering-the-results" <?php if (get_option('filtering_the_results', $params['id']) != '1'): ?>style="display:none"<?php endif; ?>>

                        <div class="card">
                            <div class="card-body">
                                <strong>Filter by</strong>

                                <div class="custom-control custom-checkbox mt-3">
                                    <input type="checkbox" <?php if ('1'== get_option('filtering_by_tags', $params['id'])): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_tags" value="1" id="filter_by_tags">
                                    <label class="custom-control-label" for="filter_by_tags"><?php _e("Tags"); ?></label>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" <?php if ('1' == get_option('filtering_by_categories', $params['id'])): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_categories" value="1" id="filter_by_categories">
                                    <label class="custom-control-label" for="filter_by_categories"><?php _e("Categories"); ?></label>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" <?php if ('1' == get_option('filtering_by_custom_fields', $params['id'])): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_custom_fields" value="1" id="filter_by_custom_fields">
                                    <label class="custom-control-label" for="filter_by_custom_fields"><?php _e("Custom Fields"); ?></label>
                                </div>

                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" <?php if ('1' == get_option('filtering_by_template_fields', $params['id'])): ?>checked="checked"<?php endif; ?> class="mw_option_field custom-control-input" name="filtering_by_template_fields" value="1" id="filter_by_template_fields">
                                    <label class="custom-control-label" for="filter_by_template_fields"><?php _e("Template fields"); ?></label>
                                </div>

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
