<? if(is_admin() == false) { error("Must be admin"); }

$uid = 0;
$rand = uniqid();

$user_params = array();
$user_params['id'] = 0;
if(isset($params['edit-user'])){
$user_params['id'] =intval($params['edit-user']);
}
 
 
 $user_params['limit'] = 1;
$data = get_users($user_params);
if(isset($data[0]) == false){
$data = array();	
$data['id'] = 0;
$data['username'] = '';
$data['password'] = '';
$data['email'] = '';
$data['first_name'] = '';
$data['last_name'] = '';
$data['is_active'] = 'y';
$data['is_admin'] = 'n';
} else {
$data = $data[0];	
}
 
 ?>
<? if(isarr($data )): ?>

<script  type="text/javascript">
mw.require('forms.js');
</script>


<script  type="text/javascript">
_mw_admin_save_user_form<?  print $data['id']; ?> = function(){
  mw.url.windowDeleteHashParam('edit-user');

 mw.form.post(mw.$('#users_edit_<? print $rand ?>') , '<? print site_url('api/save_user') ?>', function(){
	 

	// mw.reload_module('[data-type="categories"]');
	  mw.reload_module('[data-type="users/manage"]');
	 });


 
 
}
</script>
<div class="<? print $config['module_class'] ?> user-id-<?  print $data['id']; ?>" id="users_edit_<? print $rand ?>">
 
 
 
 
 <input type="hidden" class="mw-ui-field" name="id" value="<?  print $data['id']; ?>">
  <table   border="0">
    <tr>
      <th scope="row">Username</th>
      <td><input type="text" class="mw-ui-field" name="username" value="<?  print $data['username']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">password</th>
      <td><input type="text" class="mw-ui-field" name="password" value="<?  print $data['password']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">email</th>
      <td><input type="text" class="mw-ui-field" name="email" value="<?  print $data['email']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">first_name</th>
      <td><input type="text" class="mw-ui-field" name="first_name" value="<?  print $data['first_name']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">last_name</th>
      <td><input type="text" class="mw-ui-field" name="last_name" value="<?  print $data['last_name']; ?>"></td>
    </tr>
    <tr>
      <th scope="row">is_active</th>
      <td><div class="mw-ui-select">
          <select name="is_active">
            <option value="y" <? if($data['is_active'] == 'y'): ?> selected <? endif; ?>>Yes</option>
            <option value="n" <? if($data['is_active'] == 'n'): ?> selected <? endif; ?>>No</option>
          </select>
        </div></td>
    </tr>
    <tr>
      <th scope="row">is_admin</th>
      <td><div class="mw-ui-select">
          <select name="is_admin">
            <option value="y" <? if($data['is_admin'] == 'y'): ?> selected <? endif; ?>>Yes</option>
            <option value="n" <? if($data['is_admin'] == 'n'): ?> selected <? endif; ?>>No</option>
          </select>
        </div></td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
  </table>
  <button class="mw-ui-btn" onclick="_mw_admin_save_user_form<?  print $data['id']; ?>()"><?php _e("Save"); ?></button>
</div>
<? endif; ?>
