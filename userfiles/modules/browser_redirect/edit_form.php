<?php
only_admin_access();
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */

$redirect_from_url = '';
$redirect_to_url = '';
$redirect_code = '';
$redirect_enabled = '';
$redirect_browsers = array();
?>
<script type="text/javascript">
    $(function() {

        $(".js-browser-redirect-form").submit(function(e) {
            e.preventDefault();
            $.post("<?php echo api_url('browser_redirect_save');?>", $(this).serialize(), function(data){
               if (data.error) {
                    mw.notification.error(data.error);
               }
               if (data.success) {
                   if (typeof(window.parent.editBrowserRedirectModal) != 'undefined') {
                       mw.notification.success(data.success);
                       window.parent.editBrowserRedirectModal.remove();
                   }
               }
            });
        });
    });
</script>

<form method="post" class="js-browser-redirect-form">
<div class="mw-ui-box mw-ui-box-content" data-view="">
    <div class="mw-flex-row">
        <div class="mw-flex-col-xs-12">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Redirect From URL Address</label>
                <div class="mw-ui-btn-nav">
                    <a href="javascript:;" class="mw-ui-btn"><?php echo url('');?>/</a>
                    <input type="text" name="redirect_from_url" class="mw-ui-field" value="<?php echo $redirect_from_url;?>">
                </div>
            </div>
        </div>
        <div class="mw-flex-col-xs-12">
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Redirect To URL Address</label>
                <input type="text" value="<?php echo $redirect_to_url;?> " name="redirect_to_url" class="mw-ui-field mw-full-width" placeholder="<?php echo url('not-supported-browser'); ?>">
            </div>
        </div>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Error code</label>
        <ul class="mw-ui-inline-list">

            <li>
                <label class="mw-ui-check">
                    <input type="radio" value="200" <?php if ($redirect_code=='200'):?>checked="checked"<?php endif; ?> name="error_code">
                    <span></span><span>200 OK</span>
                </label>
            </li>
            <li>
                <label class="mw-ui-check">
                    <input type="radio" value="301" <?php if ($redirect_code=='301'):?>checked="checked"<?php endif; ?> name="error_code" checked="checked">
                    <span></span><span>301 Moved Permanently</span>
                </label>
            </li>
        </ul>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Redirect Browsers</label>
        <div class="mw-ui-box mw-ui-box-content">
            <ul class="mw-ui-inline-list">
                <?php foreach (get_browsers_options() as $key=>$value): ?>
                    <li>
                        <label class="mw-ui-check" style="width:156px;margin-top: 5px;">
                            <input type="checkbox" option-group="redirect" name="redirect_browsers" value="<?php echo $key; ?>" <?php if(array_key_exists($key, $redirect_browsers)): ?>checked=""<?php endif;?>>
                            <span></span><span><?php echo $value; ?></span>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Redirect Enabled</label>
        <label class="mw-ui-check">
            <input type="checkbox" value="y" name="active" <?php if ($redirect_enabled): ?>checked="checked"<?php endif; ?>>
            <span></span><span>Yes</span>
        </label>
    </div>

    <div class="text-left">
        <button type="submit" class="mw-ui-btn" style="width: 100%;">Save</button>
    </div>
</div>
</form>