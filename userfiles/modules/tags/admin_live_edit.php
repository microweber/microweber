<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-root-page-id', $params['id']); ?>
<?php
if (isset($params['for-current-content-id'])) {
    $params['for-content-id'] = content_id();
}

if (isset($params['for-content-id'])) {
    return print '<module type="content/edit" content-id="' . intval($params['for-content-id']) . '" />';
}
?>
<script>mw.lib.require('font_awesome5')</script>
<script type="text/javascript">
    function editTagsShowManageWindow(module_id) {
        var opts = {};
        opts.width = '900';
        opts.height = '800';

        opts.liveedit = true;
        opts.mode = 'modal';

        var additional_params = {};
        additional_params.manage_tags = 'yes';

        return window.mw.parent().tools.open_global_module_settings_modal('tags/admin_backend', module_id, opts, additional_params);


    }
</script>

<nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
    <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
    <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php _e('Templates'); ?></a>
</nav>

<div class="tab-content py-3">
    <div class="tab-pane fade show active" id="settings">
        <!-- Settings Content -->
        <div class="module-live-edit-settings module-categories-settings">
            <div class="form-group">
                <label class="control-label"><?php _e("Show Tags from"); ?></label>
                <input type="hidden" id="mw_page_id_front" value="<?php print PAGE_ID ?>"/>

                <div class="">
                    <select name="data-root-page-id" class="mw_option_field selectpicker" data-width="100%" data-also-reload="<?php print  $config['the_module'] ?>">
                        <option value="0" <?php if ((0 == intval($posts_parent_page))): ?>   selected="selected"  <?php endif; ?> title="<?php _e("Default"); ?>"><?php _e("Default"); ?></option>
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
            </div>

            <div class="text-end text-right">
                <a href="javascript:;" onClick="editTagsShowManageWindow('<?php print $params['id'] ?>',{mode:'modal', liveedit:false});" class="btn btn-primary btn-sm"><?php _e('Manage tags') ?></a>
            </div>
        </div>
        <!-- Settings Content - End -->
    </div>

    <div class="tab-pane fade" id="templates">
        <module type="admin/modules/templates"/>
    </div>
</div>


