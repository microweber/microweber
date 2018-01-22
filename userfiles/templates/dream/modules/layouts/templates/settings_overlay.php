<?php
$overlay = get_option('overlay', $params['id']);
if ($overlay === null OR $overlay === false OR $overlay == '') {
    $overlay = '';
}
?>

<div class="overlay" style="margin-top:15px;">
    <label class="mw-ui-label"><?php _lang("Overlay", "templates/dream"); ?></label>
    <input type="text" name="overlay" placeholder="from 0 to 10" value="<?php print $overlay; ?>" class="mw-ui-field mw_option_field"/>
</div>