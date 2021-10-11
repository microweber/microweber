<?php include('settings_header.php'); ?>

<script type="text/javascript">

    mw.lib.require("air_datepicker");

    $('#value<?php print $rand; ?>').datepicker({
        language: 'en',
        timepicker: false,
        range: false,
        multipleDates: false,
        dateFormat: '<?php echo $settings['date_format']; ?>',
        multipleDatesSeparator: " - ",
        onSelect: function (fd, d, picker) {
            $('#value<?php print $rand; ?>').trigger('change');
        }
    });

</script>

<div class="custom-field-settings-values">
  <label class="control-label" for="value<?php print $rand; ?>"><?php _e("Value"); ?></label>
   <small class="text-muted d-block mb-2"><?php _e('This attribute specifies the value of description');?></small>

   <input type="text" class="form-control" name="value" autocomplete="off" value="<?php print ($data['value']) ?>" id="value<?php print $rand; ?>">
   <?php print $savebtn; ?>
</div>

<br />


<div class="form-group mb-4">
    <label class="control-label"><?php _e("Date Format"); ?></label>
    <small class="text-muted d-block mb-2"><?php _e("Date format of the calendar picker"); ?></small>
    <?php $date_formats = mw()->format->available_date_formats(); ?>
    <?php $curent_val = $settings['date_format']; ?>
    <select name="options[date_format]"  class="selectpicker mw_option_field" data-width="100%" data-size="7">
        <?php if (is_array($date_formats)): ?>
            <?php foreach ($date_formats as $item): ?>
                <option value="<?php print $item['js'] ?>" <?php if ($curent_val == $item['js']): ?> selected="selected" <?php endif; ?>><?php print date($item['php'], time()) ?> - (<?php print $item['js'] ?>)</option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
