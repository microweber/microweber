<?php include('settings_header.php'); ?>

<?php
$curr_symbol = mw()->shop_manager->currency_symbol();
?>
<?php
if ($data['value'] == '') {
    $data['value'] = 0;
}
?>
    <div class="mw-ui-field-holder">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value in "); ?>
            <b><?php print $curr_symbol; ?> </b></label>
        <small class="text-muted d-block mb-2"><?php _e('Your price'); ?></small>

        <input type="text"
               class="form-control"
               name="value"
               value="<?php print ($data['value']) ?>"/>
    </div>


<?php
event_trigger('mw.admin.custom_fields.price_settings', $data);
?>


<?php include('settings_footer.php'); ?>