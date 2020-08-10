<?php only_admin_access(); ?>

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
        <?php
        $username = get_option('username', $params['id']);
        if (!isset($username) or $username == false or $username == '') {
            $username = 'bummer.frenchie.wild';
        }

        $number_of_items = get_option('number_of_items', $params['id']);
        if (!isset($number_of_items) or $number_of_items == false or $number_of_items == '') {
            $number_of_items = 3;
        }

        $instagram_api_client = get_option('instagram_api_client', $params['id']);
        $instagram_api_secret = get_option('instagram_api_secret', $params['id']);
        $instagram_api_access_token = get_option('instagram_api_access_token', $params['id']);
        ?>

        <script type="text/javascript">mw.require('options.js');</script>

        <script type="text/javascript">
            $(window).ready(function () {
                mw.options.form('.module-instagram-feed-settings', function () {
                    if (mw.notification != undefined) {
                        mw.notification.success('Instagram feed settings saved');
                    }
                    mw.reload_module_parent('instagram_feed'); //reload the module in live edit
                });
            });
        </script>


        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#settings"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Settings'); ?></a>
            <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">


            <div class="tab-pane fade show active" id="settings">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-instagram-feed-settings">

                    <div class="form-group">
                        <label class="control-label"><?php _e('Instagram Username'); ?></label>
                        <input type="text" class="mw_option_field form-control" placeholder="Example: microweber" name="username" value="<?php print $username; ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Number of items'); ?></label>
                        <input type="number" class="mw_option_field form-control" name="number_of_items" value="<?php print $number_of_items; ?>">
                    </div>

                    <hr class="thin"/>

                    <h5 class="font-weight-bold mb-3">Api Settings</h5>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Instagram Api Client'); ?></label>
                        <input type="text" class="mw_option_field form-control" name="instagram_api_client" value="<?php print $instagram_api_client; ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Instagram Api Secret'); ?></label>
                        <input type="text" class="mw_option_field form-control" name="instagram_api_secret" value="<?php print $instagram_api_secret; ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label"><?php _e('Instagram Api Access Token'); ?></label>
                        <input type="text" class="mw_option_field form-control" name="instagram_api_access_token" value="<?php print $instagram_api_access_token; ?>">
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