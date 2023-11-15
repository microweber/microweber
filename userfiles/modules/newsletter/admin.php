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
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <style>
            .table td{
                vertical-align: middle;
            }
        </style>
        <?php
        $mod_action = '';
        $load_mod_action = false;
        if ((url_param('mod_action') != false)) {
            $mod_action = url_param('mod_action');
        }
        ?>

        <?php if (isset($params['backend'])): ?>
            <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
                <a class="btn btn-outline-secondary justify-content-center active" data-bs-toggle="tab" href="#subscribers"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> <?php _e('Subscribers'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#list"><i class="mdi mdi-clipboard-text-outline mr-1"></i> <?php _e('Lists'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#campaigns"><i class="mdi mdi-email-check-outline mr-1"></i> <?php _e('Campaigns'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#templates"><i class="mdi mdi-view-dashboard-outline mr-1"></i> <?php _e('Templates'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#sender_accounts"><i class="mdi mdi-book-account-outline mr-1"></i> <?php _e('Sending accounts'); ?></a>
                <a class="btn btn-outline-secondary justify-content-center" data-bs-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php _e('Settings'); ?></a>
            </nav>

            <div class="tab-content py-3">
                <div class="tab-pane fade show active" id="subscribers">
                    <module type="newsletter/subscribers"/>
                </div>

                <div class="tab-pane fade" id="list">
                    <module type="newsletter/lists"/>
                </div>

                <div class="tab-pane fade" id="campaigns">
                    <module type="newsletter/campaigns"/>
                </div>

                <div class="tab-pane fade" id="templates">
                    <module type="newsletter/templates"/>
                </div>

                <div class="tab-pane fade" id="sender_accounts">
                    <module type="newsletter/sender_accounts"/>
                </div>

                <div class="tab-pane fade" id="settings">
                    <module type="newsletter/privacy_settings" for_module_id="<?php print $params['id'] ?>" data-no-hr="true"/>


                    <module type="newsletter/settings" for_module_id="<?php print $params['id'] ?>"/>
                </div>
            </div>
        <?php else: ?>
            <div class="mw-live-edit-settings">
                <div class="text-center"><a href="<?php echo admin_url(); ?>view:modules/load_module:newsletter" class="mw-ui-btn mw-ui-btn-info"  target="_blank">Go to Newsletter in Admin panel</a></div>

                <div class="mw-ui-box-content">
                    <module type="admin/modules/templates"/>
                    <hr class="thin"/>
                    <module type="newsletter/privacy_settings" for_module_id="<?php print $params['id'] ?>"/>
                    <hr class="thin"/>
                    <module type="newsletter/settings" for_module_id="<?php print $params['id'] ?>"/>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php _e('Subscribe to list'); ?>:</label>
                    <?php
                    $list_id = get_option('list_id', $params['id']);
                    $list_params = array();
                    $list_params['no_limit'] = true;
                    $list_params['order_by'] = "created_at desc";
                    $lists = newsletter_get_lists($list_params);
                    ?>
                    <select name="list_id" class="mw_option_field selectpicker" data-width="100%" name="list_id" value="<?php print $list_id; ?>">
                        <option value="0">Default</option>
                        <?php if (!empty($lists)): ?>
                            <?php foreach ($lists as $list) : ?>
                                <option value="<?php echo $list['id']; ?>" <?php if ($list_id == $list['id']): ?>  selected="selected"    <?php endif; ?> ><?php echo $list['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
