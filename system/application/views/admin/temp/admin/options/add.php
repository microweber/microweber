<form action="<?php print site_url('admin/options/index') ; ?>" method="post" id="optionsForm_0" enctype="multipart/form-data" class="optionsForm">
<h2>Add new option</h2>
<table border="0">
  <tr>
    <th scope="row">option_key</th>
    <td><input name="option_key" type="text" /></td>
  </tr>
  <tr>
    <th scope="row">option_value</th>
    <td><textarea name="option_value" cols="" rows=""></textarea></td>
  </tr>
  <tr> 
    <th scope="row">option_value 2</th>
    <td><textarea name="option_value2" cols="" rows=""></textarea></td>
  </tr>
  <tr>
    <th scope="row">option_group</th>
    <td><input name="option_group" type="text" /></td>
  </tr>
  <tr>
    <th scope="row">save</th>
    <td><input name="save" value="save" type="submit" /></td>
  </tr>
</table>
</form>
