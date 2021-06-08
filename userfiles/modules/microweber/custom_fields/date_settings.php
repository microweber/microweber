<?php include('settings_header.php'); ?>

<script type="text/javascript">

    mw.lib.require("air_datepicker");


    if ($.fn.datepicker) {
        $.fn.datepicker.language['en'] = {
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysShort: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            today: 'Today',
            clear: 'Clear',
            dateFormat: 'yyyy-mm-dd',
            firstDay: 0
        };
    }

    $('#value<?php print $rand; ?>').datepicker({
        timepicker: false,
        range: false,
        multipleDates: false,
        dateFormat: 'yyyy-mm-dd',
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

<?php include('placeholder_settings.php'); ?>
<?php include('settings_footer.php'); ?>
