<?php only_admin_access(); ?>
<script type="text/javascript">
    mw.require('wysiwyg.js');
</script>
<script type="text/javascript">
   mw.require(mw.settings.libs_url + 'fontIconPicker' + '/jquery.fonticonpicker.min.js');
   mw.require(mw.settings.libs_url + 'fontIconPicker' + '/css/jquery.fonticonpicker.min.css');
   mw.require(mw.settings.libs_url + 'fontIconPicker' + '/themes/grey-theme/jquery.fonticonpicker.grey.min.css');
</script>
<!-- SELECT element -->
<select id="myselect" name="myselect" class="myselect">
    <option value="">No icon</option>
    <option>icon-user</option>
    <option>icon-search</option>
    <option>icon-right-dir</option>
    <option>icon-star</option>
    <option>icon-cancel</option>
    <option>icon-help-circled</option>
    <option>icon-info-circled</option>
    <option>icon-eye</option>
    <option>icon-tag</option>
    <option>icon-bookmark</option>
    <option>icon-heart</option>
    <option>icon-thumbs-down-alt</option>
    <option>icon-upload-cloud</option>
    <option>icon-phone-squared</option>
    <option>icon-cog</option>
    <option>icon-wrench</option>
    <option>icon-volume-down</option>
    <option>icon-down-dir</option>
    <option>icon-up-dir</option>
    <option>icon-left-dir</option>
    <option>icon-thumbs-up-alt</option>
</select>
<!-- JavaScript -->
<script type="text/javascript">
    // Make sure to fire only when the DOM is ready
    $(document).ready(function($) {
        $('#myselect').fontIconPicker(); // Load with default options
    });
</script>
 