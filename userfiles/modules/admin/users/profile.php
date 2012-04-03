
<fieldset class="inputs">
  <legend><span>Form settings</span></legend>
  <ol>
    <li class="string input optional stringish">
      <label class="label">Form title</label>
      <input  name='form_title' class="mw_option_field" option_group="<? print $params['module_id'] ?>"   refresh_modules="users/profile" value="<?php  print option_get('form_title', $params['module_id']); ?>"  />
    </li>
    <li class="string input optional stringish">
      <label class="label">Form CSS class</label>
      <input  name='form_class' class="mw_option_field" option_group="<? print $params['module_id'] ?>"   refresh_modules="users/profile" value="<?php  print option_get('form_class', $params['module_id']); ?>"  />
    </li>
  </ol>
</fieldset>

<? print PAGE_ID ?>
          <microweber module="admin/content/custom_fields" page_id="<? print PAGE_ID ?>" />


