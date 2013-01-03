<? include('settings_header.php'); ?>
    <div class="mw-custom-field-group">

      <div class="mw-custom-field-form-controls">


        <select onchange="mw.custom_fields.save('custom_fields_edit<? print $rand ?>');" class="mw-ui-field" name="custom_field_value">
            <option <?php if (($data['custom_field_value']) =='hr'): ?> selected="selected" <?php endif; ?> value="hr">Horizontal Rule</option>
            <option <?php if (($data['custom_field_value']) =='space'): ?> selected="selected" <?php endif; ?>  value="space">Space</option>
        </select>


      </div>
    </div>
<? include('settings_footer.php'); ?>
