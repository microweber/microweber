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
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">

        <?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
        <?php $posts_parent_page = get_option('data-content-id', $params['id']); ?>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-search-settings">
                    <div class="form-group">
                        <label class="control-label"><?php _e("Search in page"); ?></label>

                        <div>
                            <select name="data-content-id" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                                <option value="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?> title="<?php _e("None"); ?>"><?php _e("All pages"); ?></option>
                                <?php
                                $pt_opts = array();
                                $pt_opts['link'] = "{empty}{title}";
                                $pt_opts['list_tag'] = " ";
                                $pt_opts['list_item_tag'] = "option";
                                $pt_opts['active_ids'] = $posts_parent_page;
                                $pt_opts['active_code_tag'] = '   selected="selected"  ';
                                pages_tree($pt_opts);
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Settings Content - End -->
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
