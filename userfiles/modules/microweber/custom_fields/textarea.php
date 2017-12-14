<?php
if(!isset($data['input_class'])){
    $data['input_class'] = '';
}


?><div class="mw-custom-field-group">
  <label class="mw-custom-field-label">
    <?php if(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php elseif(isset($data['name']) == true and $data['name'] != ''): ?>
    <?php print $data['name'] ?>
    <?php else : ?>
    <?php endif; ?>
  </label>
  <?php if(isset($data['help']) == true and $data['help'] != ''): ?>
  <br />
  <small  class="mw-custom-field-help"><?php print $data['help'] ?></small>
  <?php endif; ?>
  <div class="mw-custom-field-form-controls">
    <textarea  <?php if (trim($data['custom_field_required']) == 'y'): ?> required="true"  <?php endif; ?> <?php if (isset($data['input_class'])): ?> class="<?php print $data['input_class'] ?>"  <?php endif; ?>   data-custom-field-id="<?php print $data["id"]; ?>"  name="<?php print $data["name"]; ?>" ><?php print $data["value"]; ?></textarea>
  </div>
</div>
