<script type="text/javascript">
    $(document).ready(function() {

        $.post("/configure-whmcs-connection-status")
            .done(function(data) {
                if (data.success) {
                    $('.js-connection-status').html('<div class="alert alert-success"><i class="fa fa-check"></i> '+data.success+'</div>');
                }
                if (data.error) {
                    $('.js-connection-status').html('<div class="alert alert-danger"><i class="fa fa-times"></i> '+data.error+'</div>');
                }
            });

        $('.js-whmcs-auth-type').change(function() {
            var authType = $(this).val();
            toggleAuthType(authType);
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
</script>

<div class="js-connection-status"></div>

<?php
$whmcsUrl = false;
$whmcsAuthType = false;
$whmcsApiIdentifier = false;
$whmcsApiSecret = false;
$whmcsUsername = false;
$whmcsPassword = false;

$settings = get_white_label_config();
if (isset($settings['whmcs_url'])) {
    $whmcsUrl = $settings['whmcs_url'];
}
if (isset($settings['whmcs_auth_type'])) {
    $whmcsAuthType = $settings['whmcs_auth_type'];
}
if (isset($settings['whmcs_api_identifier'])) {
    $whmcsApiIdentifier = $settings['whmcs_api_identifier'];
}
if (isset($settings['whmcs_api_secret'])) {
    $whmcsApiSecret = $settings['whmcs_api_secret'];
}
if (isset($settings['whmcs_username'])) {
    $whmcsUsername = $settings['whmcs_username'];
}
if (isset($settings['whmcs_password'])) {
    $whmcsPassword = $settings['whmcs_password'];
}
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
                <input id="whmcs_url" name="whmcs_url" value="<?php echo $whmcsUrl; ?>" option-group="whitelabel" placeholder="https://whmcs.yourwebsite.com" type="text" class="form-control">
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="whmcs_auth_type" class="col-12 col-form-label">WHMSC Auth Type</label>
        <div class="col-12">
            <select id="whmcs_auth_type" name="whmcs_auth_type" option-group="whitelabel" class="custom-select js-whmcs-auth-type" aria-describedby="selectHelpBlock">
                <option value="api" <?php if ($whmcsAuthType=='api'):?>selected="selected"<?php endif; ?>>API</option>
                <option value="password" <?php if ($whmcsAuthType=='password'):?>selected="selected"<?php endif; ?>>Username & Password</option>
            </select>
            <span id="selectHelpBlock" class="form-text text-muted">This is how we will make connection with your WHMCS</span>
        </div>
    </div>
    <div class="js-authbox-api" style="display:none">
        <div class="form-group row">
            <label for="api_identifier" class="col-12 col-form-label">WHMCS Api Identifier</label>
            <div class="col-12">
                <input id="api_identifier" value="<?php echo $whmcsApiIdentifier; ?>" option-group="whitelabel" name="whmcs_api_identifier" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="api_secret" class="col-12 col-form-label">WHMCS Api Secret</label>
            <div class="col-12">
                <input id="api_secret" value="<?php echo $whmcsApiSecret; ?>" option-group="whitelabel" name="whmcs_api_secret" type="text" class="form-control">
            </div>
        </div>
    </div>
    <div class="js-authbox-username-password" style="display:none">
        <div class="form-group row">
            <label for="username" class="col-12 col-form-label">WHMSCS Username</label>
            <div class="col-12">
                <input id="username" value="<?php echo $whmcsUsername; ?>" option-group="whitelabel" name="whmcs_username" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-12 col-form-label">WHMSCS Password</label>
            <div class="col-12">
                <input id="password" value="<?php echo $whmcsPassword; ?>" option-group="whitelabel" name="whmcs_password" type="text" class="form-control">
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
