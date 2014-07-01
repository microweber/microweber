<?php only_admin_access(); ?>
<?php $show_login_link=get_option('show-login-link', $params['id']); ?>
<div class="module-live-edit-settings">
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Form title"); ?>
    </label>
    <input
      name="form-title"
      class="mw-ui-field mw_option_field"
      type="text"
      value="<?php print get_option('form-title', $params['id']); ?>" />
  </div>
  <div class="mw-ui-field-holder">
    <label class="mw-ui-label">
      <?php _e("Show"); ?>  <?php _e("Login link"); ?>
    </label>

      <select name="show-login-link"  class="mw-ui-field mw_option_field">
        <option value="y" <?php if ($show_login_link != 'n'): ?> selected="selected" <?php endif ?>>
        <?php _e("Yes"); ?>
        </option>
        <option value="n" <?php if ($show_login_link == 'n'): ?> selected="selected" <?php endif ?>>
        <?php _e("No"); ?>
        </option>
      </select>

  </div>
</div>
