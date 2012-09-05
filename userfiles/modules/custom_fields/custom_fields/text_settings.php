<? include('settings_header.php'); ?>
    <div class="control-group">
      <label class="control-label" for="custom_field_value<? print $rand ?>">Value</label>
      <div class="controls">
        <input type="text" class="input-xlarge" name="custom_field_value"  value="<? print ($data['custom_field_value']) ?>" id="custom_field_value<? print $rand ?>">
      </div>
    </div>
    <? include('settings_footer.php'); ?>
