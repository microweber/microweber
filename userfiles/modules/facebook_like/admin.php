<?php only_admin_access(); ?>
<?php $layout=get_option('layout', $params['id']); ?>
<?php $url_to_like=get_option('url', $params['id']); ?>
<?php $url_to_like=get_option('url', $params['id']); ?>
<?php $color=get_option('color', $params['id']); ?>
<?php $show_faces=get_option('show_faces', $params['id']); ?>


<div class="module-live-edit-settings">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label"> <?php _e("URL to like"); ?> </label>
    <input
      name="url"
      class="mw-ui-field w100 mw_option_field"
      type="text"
      placeholder="<?php _e("Current URL or type your own"); ?>"
      value="<?php print $url_to_like; ?>" />
  </div>

<div class="mw-ui-field-holder"><div class="mw-ui-row-nodrop">
    <div class="mw-ui-col">  <div class="mw-ui-col-container">
    <label class="mw-ui-label"> <?php _e("Layout"); ?> </label>
      <select name="layout"  class="mw-ui-field mw_option_field w100">
        <option value="standard" <?php if ($layout == false or $layout == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Standard"); ?></option>
        <option value="box_count" <?php if ($layout == 'box_count'): ?> selected="selected" <?php endif ?>><?php _e("Box count"); ?></option>
        <option value="button_count" <?php if ($layout == 'button_count'): ?> selected="selected" <?php endif ?>><?php _e("Button count"); ?></option>
        <option value="button" <?php if ($layout == 'button'): ?> selected="selected" <?php endif ?>><?php _e("Button"); ?></option>
      </select>
  </div></div>
    <div class="mw-ui-col">
      <div class="mw-ui-col-container">
    <label class="mw-ui-label"> <?php _e("Color cheme"); ?> </label>
    <select name="color"  class="mw-ui-field mw_option_field w100">
        <option value="light" <?php if ($color == false or $color == 'standard'): ?> selected="selected" <?php endif ?>><?php _e("Light"); ?></option>
        <option value="dark" <?php if ($color == 'dark'): ?> selected="selected" <?php endif ?>><?php _e("Dark"); ?></option>
    </select>
  </div>
    </div>
    <div class="mw-ui-col">
       <div class="mw-ui-col-container">
    <label class="mw-ui-label"> <?php _e("Show faces"); ?> </label>
    <select name="show_faces"  class="mw-ui-field mw_option_field w100">
      <option value="y" <?php if ($show_faces == false or $show_faces == 'y'): ?> selected="selected" <?php endif ?>><?php _e("Yes"); ?></option>
      <option value="n" <?php if ($show_faces == 'n'): ?> selected="selected" <?php endif ?>><?php _e("No"); ?></option>
    </select>
  </div>
    </div>
</div></div>




  
  
  
</div>
