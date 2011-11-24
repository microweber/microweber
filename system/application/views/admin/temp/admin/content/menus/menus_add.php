<form action="<?php print site_url('admin/menus')  ?>" method="post" enctype="multipart/form-data">
  <fieldset>
  <legend>
  <h1>Add/Edit menu</h1>
  </legend>
  <label>Menu unique id:
  <input name="menu_unique_id" type="text" value="<?php print $form_values['menu_unique_id']; ?>">
  </label>
  <label>Menu title:
  <input name="item_title" type="text" value="<?php print $form_values['item_title']; ?>">
  </label>
  <label>is_active:
  <select name="is_active">
    <option  <?php if($form_values['is_active'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">yes</option>
    <option  <?php if($form_values['is_active'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">no</option>
  </select>
  </label>
  <label>Title:
  <input name="menu_title" type="text" value="<?php print $form_values['menu_title']; ?>">
  </label>
  <label>Description:
  <input name="menu_description" type="text" value="<?php print $form_values['menu_description']; ?>">
  </label>
  <input name="Save" value="Save" type="submit">
  <input name="id" type="hidden" value="<?php print $form_values['id']; ?>">
  </fieldset>
</form>