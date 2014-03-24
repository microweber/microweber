<?php only_admin_access(); ?>
<?php $layout=get_option('layout', $params['id']); ?>
<?php $url_to_like=get_option('url', $params['id']); ?>
<?php $url_to_like=get_option('url', $params['id']); ?>
<?php $color=get_option('color', $params['id']); ?>
<?php $show_faces=get_option('show_faces', $params['id']); ?>


<div class="module-live-edit">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"> URL to like </label>
    <input
      name="url"
      class="mw-ui-field mw-ui-field-full mw_option_field"
      type="text"
      placeholder="Current URL or type your own"
      value="<?php print $url_to_like; ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"> Layout </label>
    <div class="mw-ui-select" style="width: 290px;">
      <select name="layout"  class="mw_option_field">
        <option value="standard" <?php if ($layout == false or $layout == 'standard'): ?> selected="selected" <?php endif ?>>Standard</option>
        <option value="box_count" <?php if ($layout == 'box_count'): ?> selected="selected" <?php endif ?>>Box count</option>
        <option value="button_count" <?php if ($layout == 'button_count'): ?> selected="selected" <?php endif ?>>Button count</option>
        <option value="button" <?php if ($layout == 'button'): ?> selected="selected" <?php endif ?>>Button</option>
      </select>
    </div>
  </div>
  
  
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"> Color cheme </label>
    <div class="mw-ui-select" style="width: 290px;">
      <select name="color"  class="mw_option_field">
        <option value="light" <?php if ($color == false or $color == 'standard'): ?> selected="selected" <?php endif ?>>Light</option>
        <option value="dark" <?php if ($color == 'dark'): ?> selected="selected" <?php endif ?>>Dark</option>
       
      </select>
    </div>
  </div>
  
    <div class="mw-ui-field-holder">
    <label class="mw-ui-label"> Show faces </label>
    <div class="mw-ui-select" style="width: 290px;">
      <select name="show_faces"  class="mw_option_field">
        <option value="y" <?php if ($show_faces == false or $show_faces == 'y'): ?> selected="selected" <?php endif ?>>Yes</option>
        <option value="n" <?php if ($show_faces == 'n'): ?> selected="selected" <?php endif ?>>No</option>

      </select>
    </div>
  </div>
  
  
  
</div>
