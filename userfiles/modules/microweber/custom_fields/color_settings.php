<?php include('settings_header.php'); ?>

<script type="text/javascript">

   mw.lib.require('colorpicker');

    color = mw.colorPicker({
        element: '#value<?php print $rand; ?>',
        position: 'bottom-left',
        onchange: function (color) {
            $('#value<?php print $rand; ?>').val(color).css('background', color);
            $('#value<?php print $rand; ?>').trigger('change');
        }
    });

</script>

<div class="custom-field-settings-values">
  <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Color"); ?></label>
   <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the color');?></small>

   <input type="text" class="form-control" name="value" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
   <?php print $savebtn; ?>
</div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
