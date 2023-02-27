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
        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">

            <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
        </nav>

        <?php $pages = get_content('content_type=page&limit=1000'); ?>
        <?php $posts_parent_page = get_option('data-parent', $params['id']); ?>
        <?php $posts_maxdepth = get_option('maxdepth', $params['id']); ?>
        <?php $include_categories = get_option('include_categories', $params['id']); ?>
        <?php $include_parent = get_option('include_parent', $params['id']); ?>

        <div class="tab-content py-3">

            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-pages-settings">

                    <div class="form-group">
                        <label class="control-label"><?php _e("Pages & Sub-Pages From"); ?></label>
                        <select name="data-parent" id="mw_change_pages_parent_root" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option valie="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
                            <?php
                            $pt_opts = array();
                            $pt_opts['link'] = "{empty}{title}";
                            $pt_opts['list_tag'] = " ";
                            $pt_opts['list_item_tag'] = "option";
                            $pt_opts['active_ids'] = $posts_parent_page;
                            $pt_opts['active_code_tag'] = '   selected="selected"  ';

                            pages_tree($pt_opts);
                            ?>
                            <?php if (defined('PAGE_ID')): ?>
                                <option value="<?php print PAGE_ID; ?>">[use current page]</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Show Categories from page"); ?></label>

                        <select name="include_categories" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="y" <?php if ('y' == $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("Yes"); ?></option>
                            <option value="n" <?php if ('y' != $include_categories): ?>   selected="selected"  <?php endif; ?> ><?php _e("No"); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Include Parent"); ?></label>

                        <select name="include_parent" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="y" <?php if ('y' == $include_parent): ?>   selected="selected"  <?php endif; ?> ><?php _e("Yes"); ?></option>
                            <option value="n" <?php if ('y' != $include_parent): ?>   selected="selected"  <?php endif; ?> ><?php _e("No"); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e("Max depth"); ?></label>

                        <select name="maxdepth" class="mw_option_field selectpicker" data-width="100%" data-size="5" data-live-search="true">
                            <option value="none" selected>
                                <?php _e("Default"); ?>
                            </option>
                            <?php for ($i = 1; $i < 10; $i++): ?>
                                <option value="<?php print $i ?>" <?php if (($i == $posts_maxdepth)): ?>   selected="selected"  <?php endif; ?>> <?php print $i ?></option>
                            <?php endfor; ?>
                        </select>
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
