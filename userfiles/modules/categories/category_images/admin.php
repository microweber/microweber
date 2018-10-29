<?php
$selected_category = get_option('fromcategory', $params['id']);
$show_category_header = get_option('show_category_header', $params['id']);
?>

<style type="text/css" scoped="scoped">
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

<div class="mw-accordion">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-settings"></i> Settings
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-categories-image-settings">
                <input type="hidden" name="settings" id="settingsfield" value="" class="mw_option_field"/>

                <div calss="mw-ui-field-holder">
                    <?php $trees = get_categories('limit=1000&parent_id=0&rel=content'); ?>
                    <label class="mw-ui-label"><?php _e('Select parent category'); ?></label>
                    <select name="fromcategory" class="mw_option_field mw-ui-field mw-full-width" id="parentcat">
                        <option <?php if ((0 == intval($selected_category))): ?>  selected="selected"  <?php endif; ?>><?php _e("None"); ?></option>
                        <option <?php if (('current' == $selected_category)): ?>  selected="selected"  <?php endif; ?> value="current"><?php _e("Current"); ?></option>
                        <?php
                        if ($trees != false and is_array($trees) and !empty($trees)) {
                            foreach ($trees as $cat) {
                                $cat_params = $params;
                                $pt_opts = array();
                                $pt_opts['link'] = "{title}";
                                $pt_opts['list_tag'] = " ";
                                $pt_opts['list_item_tag'] = "option";
                                $pt_opts['parent'] = $cat['id'];
                                $pt_opts['include_first'] = 1;
                                $pt_opts['active_ids'] = $selected_category;
                                $pt_opts['active_code_tag'] = '   selected="selected"  ';
                                category_tree($pt_opts);
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>
</div>