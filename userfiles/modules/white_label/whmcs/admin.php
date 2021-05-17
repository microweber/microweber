<?php only_admin_access() ?>

<script type="text/javascript">
    $(document).ready(function() {

        whmcsTestWhiteLabelConnfigStaus()


        mw.on('whiteLabelModuleSettingsUpdated', function (target){
            whmcsTestWhiteLabelConnfigStaus()
        });



        $('.js-whmcs-auth-type').change(function() {
            var authType = $(this).val();
            toggleAuthType(authType);
            whmcsTestWhiteLabelConnfigStaus()
        });
        toggleAuthType('api');
    });
    function toggleAuthType(type) {
        if (type == 'api') {
            $('.js-authbox-api').slideDown();
            $('.js-authbox-username-password').hide();
        } else {
            $('.js-authbox-api').hide();
            $('.js-authbox-username-password').slideDown();
        }
    }


    function whmcsTestWhiteLabelConnfigStaus() {
        $.post("<?php echo api_url('whitelabel/whmcs_status'); ?>")
            .done(function(data) {
                if (data.success) {
                    $('.js-connection-status').html('<div class="alert alert-success"><i class="fa fa-check"></i> '+data.success+'</div>');
                }
                if (data.error) {
                    $('.js-connection-status').html('<div class="alert alert-danger"><i class="fa fa-times"></i> '+data.error+'</div>');
                }
                if (data.warning) {
                    $('.js-connection-status').html('<div class="alert alert-warning"><i class="fa fa-wave-square"></i> '+data.warning+'</div>');
                }
            });
    }
</script>

<div class="js-connection-status"></div>

<?php
$whmcsSettings = get_whitelabel_whmcs_settings();
?>

<div class="row justify-content-center align-items-center">
    <div class="col-md-6 text-center">
        <img src="<?php print module_url(); ?>whmcslogo.png" style="border:0px" />
    </div>
<div class="col-md-6">
<div class="form-group row">
        <label for="whmcs_url" class="col-12 col-form-label">WHMCS URL</label>
        <div class="col-12">
            <div class="input-group">
                <input id="whmcs_url" name="whmcs_url" value="<?php echo $whmcsSettings['whmcs_url']; ?>" option-group="whitelabel" placeholder="https://whmcs.yourwebsite.com" type="text" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="whmcs_auth_type" class="col-12 col-form-label">WHMSC Auth Type</label>
        <div class="col-12">
            <select id="whmcs_auth_type" name="whmcs_auth_type" option-group="whitelabel" class="custom-select js-whmcs-auth-type" aria-describedby="selectHelpBlock">
                <option value="api" <?php if ($whmcsSettings['whmcs_auth_type']=='api'):?>selected="selected"<?php endif; ?>>API</option>
                <option value="password" <?php if ($whmcsSettings['whmcs_auth_type']=='password'):?>selected="selected"<?php endif; ?>>Username & Password</option>
            </select>
            <span id="selectHelpBlock" class="form-text text-muted">This is how we will make connection with your WHMCS</span>
        </div>
    </div>
    <div class="js-authbox-api" style="display:none">
        <div class="form-group row">
            <label for="api_identifier" class="col-12 col-form-label">WHMCS Api Identifier</label>
            <div class="col-12">
                <input id="api_identifier" value="<?php echo $whmcsSettings['whmcs_api_identifier']; ?>" option-group="whitelabel" name="whmcs_api_identifier" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="api_secret" class="col-12 col-form-label">WHMCS Api Secret</label>
            <div class="col-12">
                <input id="api_secret" value="<?php echo $whmcsSettings['whmcs_api_secret']; ?>" option-group="whitelabel" name="whmcs_api_secret" type="text" class="form-control">
            </div>
        </div>
    </div>
    <div class="js-authbox-username-password" style="display:none">
        <div class="form-group row">
            <label for="username" class="col-12 col-form-label">WHMSCS Username</label>
            <div class="col-12">
                <input id="username" value="<?php echo $whmcsSettings['whmcs_username']; ?>" option-group="whitelabel" name="whmcs_username" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-12 col-form-label">WHMSCS Password</label>
            <div class="col-12">
                <input id="password" value="<?php echo $whmcsSettings['whmcs_password']; ?>" option-group="whitelabel" name="whmcs_password" type="text" class="form-control">
            </div>
        </div>
    </div>
<!--
    <div class="form-group row">
        <div class="offset-4 col-12">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Settings</button>
        </div>
    </div>
-->
</div>
</div>
