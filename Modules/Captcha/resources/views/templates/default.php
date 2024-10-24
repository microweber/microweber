<?php
/*

type: layout

name: Default

description: Default comments template

*/


?>

<?php
$callback = false;
if (isset($params['data-callback'])) {
    $callback = $params['data-callback'];
}
?>
<?php if($callback): ?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#captcha-<?php print $params['id'] ?>-input').on('input paste keyup blur', function () {
            var val = $(this).val();
            if (val.length > 0) {
                <?php print $callback; ?>(val);
            }
        })
    });
</script>
<?php endif; ?>



<div class="mw-ui-row">
    <div class="mw-captcha" style="max-width: 400px; margin: 15px;">
        <div class="form-group">
            <div class="captcha-holder">
                <div class="mw-ui-col" style="width: 100px;">
                    <a href="javascript:;" class="tip" data-tip="Refresh captcha" data-tipposition="top-center">
                        <img onclick="mw.tools.refresh_image(this);" onerror="this.onerror=null;mw.tools.refresh_image(this);" class="mw-captcha-img" id="captcha-<?php print $form_id; ?>" src="<?php print api_link('captcha') ?>?w=100&h=60&uid=<?php print uniqid($form_id) ?>&rand=<?php print rand(1, 10000) ?>&id=<?php print $params['id'] ?>"/>
                    </a>
                </div>
                <div class="mw-ui-col">
                    <input name="captcha" id="captcha-<?php print $params['id'] ?>-input" type="text" required class="mw-captcha-input form-control" placeholder="<?php _e("Security code"); ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>
