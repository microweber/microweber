<?php include('settings_header.php'); ?>

    <script type="text/javascript">

        mw.lib.require("air_datepicker");

        $('#value<?php print $rand; ?>').datepicker({
            timepicker: true,
            onlyTimepicker: true,
            onSelect: function (fd, d, picker) {
                $('#value<?php print $rand; ?>').trigger('change');
            }
        });

    </script>

    <div class="custom-field-settings-values">
        <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
        <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>

        <input type="text" class="form-control" name="value"  value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
        <?php print $savebtn; ?>
    </div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
