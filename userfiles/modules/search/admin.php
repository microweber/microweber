<?php $pages = get_content('content_type=page&subtype=dynamic&limit=1000'); ?>
<?php $posts_parent_page = get_option('data-content-id', $params['id']); ?>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-search-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Search in page"); ?></label>

                    <select name="data-content-id" class="mw-ui-field mw-full-width mw_option_field">
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
            <!-- Settings Content - End -->
        </div>
    </div>

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