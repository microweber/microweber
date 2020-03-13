<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-root-page-id', $params['id']); ?>
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
    function editTagsShowManageWindow(module_id) {
        var opts = {};
        opts.width = '800';
        opts.height =  '600';

        opts.liveedit = true;
        opts.mode = 'modal';

        var additional_params = {};
        additional_params.manage_tags = 'yes';

        return window.parent.mw.tools.open_global_module_settings_modal('tags/admin_backend', module_id, opts,additional_params);
    }
</script>

<div class="mw-accordion-item-block   mw-live-edit-module-manage-and-list-top">
    <a href="javascript:void();" onClick="editTagsShowManageWindow('<?php print $params['id'] ?>',{mode:'modal', liveedit:false});"
       class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded"><span class="fas fa-list"></span>
        &nbsp; <?php print _e('Manage tags') ?></a>


</div>
<div class="mw-modules-tabs">


    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-categories-settings">

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Show Tags From"); ?></label>
                    <input type="hidden" id="mw_page_id_front" value="<?php print PAGE_ID ?>"/>

                    <select name="data-root-page-id" class="mw-ui-field mw_option_field mw-full-width" data-also-reload="<?php print  $config['the_module'] ?>">
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
            <!-- Settings Content - End -->
        </div>
    </div>

    <?php

    /*  <div class="mw-accordion-item">
          <div class="mw-ui-box-header mw-accordion-title">
              <div class="header-holder" id="mw-live-edit-cats-tab" onclick="mw.live_edit_load_cats_list()">
                  <i class="mw-icon-navicon-round"></i> <?php print _e('List of Categories'); ?>
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
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>