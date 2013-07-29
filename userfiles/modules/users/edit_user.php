<?php if(is_admin() == false) { mw_mw_error("Must be admin"); }

$uid = 0;
//$rand = uniqid();

$user_params = array();
$user_params['id'] = 0;
if(isset($params['edit-user'])){
$user_params['id'] =intval($params['edit-user']);
}

 if($user_params['id'] >  0){
	  $user_params['limit'] = 1;
	  $data = get_users($user_params);

 } else {
	$data = FALSE; 
 }
if(isset($data[0]) == false){
  $data = array();
  $data['id'] = 0;
  $data['username'] = '';
  $data['password'] = '';
  $data['email'] = '';
  $data['first_name'] = '';
  $data['last_name'] = '';
  $data['api_key'] = '';
  $data['is_active'] = 'y';
  $data['is_admin'] = 'n';
  $data['basic_mode'] = 'n';
  $data['thumbnail'] = '';
} else {
$data = $data[0];	
}
 
 ?>
<?php if(is_array($data )): ?>
<script  type="text/javascript">
mw.require('forms.js');
mw.require('files.js');
</script>
<script  type="text/javascript">
_mw_admin_save_user_form<?php  print $data['id']; ?> = function(){

    if(mwd.getElementById("reset_password").value == ''){
        mwd.getElementById("reset_password").disabled = true;
    }

 mw.form.post(mw.$('#users_edit_{rand}') , '<?php print mw_site_url('api/save_user') ?>', function(){
	 
      UserId = this;
	  // mw.reload_module('[data-type="categories"]');
	  mw.reload_module('[data-type="users/manage"]', function(){

	    mw.url.windowDeleteHashParam('edit-user');

        mw.notification.success('<?php _e("All changes saved"); ?>');
        setTimeout(function(){
            mw.tools.highlight(mwd.getElementById('mw-admin-user-'+UserId));
        }, 300);
	  });
	 });


 
 
}


uploader = mw.files.uploader({
  filetypes:"images"
});


Pixum =  "<?php print pixum(67,67); ?>";

$(document).ready(function(){

    mw.$("#change_avatar").append(uploader);

    $(uploader).bind("FileUploaded", function(a,b){
          mw.$("#avatar_image").attr("src", b.src);
          mw.$("#user_thumbnail").val(b.src);
    });

    mw.$("#avatar_holder .mw-close").click(function(){
      mw.$("#avatar_image").attr("src", Pixum);
      mw.$("#user_thumbnail").val("");
    });

});

reset_password = function(y){
    var y = y || false;
    var field = mw.$("#reset_password");
    if(field.hasClass("semi_hidden") && !y){
        field.removeClass("semi_hidden");
        field[0].disabled = false;
        field.focus();
    }
    else{
        field.addClass("semi_hidden");
        field[0].disabled = true;
    }

}

</script>
<style>
#change_avatar {
	position: relative;
	overflow: hidden;
	display: inline-block;
	float: left;
	white-space: nowrap;
	margin-top: 15px;
}
#avatar_holder {
	width: 67px;
	max-height: 67px;
	float: left;
	margin-right: 12px;
	position: relative;
	border: 1px solid #ccc;
}
#avatar_holder img {
	max-width: 67px;
	max-height: 67px;
}
#avatar_holder .mw-close {
	position: absolute;
	z-index: 1;
	top: 3px;
	right: 3px;
	visibility: hidden;
}
#avatar_holder:hover .mw-close {
	visibility: visible;
}
#reset_password{
  margin-left: 12px;
}

</style>

<div class="mw-o-box <?php print $config['module_class'] ?> user-id-<?php  print $data['id']; ?>" id="users_edit_{rand}">
  <div class="mw-o-box-header" style="margin-bottom: 0;"> <span class="ico iusers"></span>
    <?php if($data['id'] != 0): ?>
    <span>
    <?php _e("Edit user"); ?>
    &laquo;
    <?php  print $data['username']; ?>
    &raquo;</span>
    <?php else: ?>
    <span><?php _e("Add new user"); ?></span>
    <?php endif; ?>
  </div>
  <input type="hidden" class="mw-ui-field" name="id" value="<?php  print $data['id']; ?>">
  <div>
    <table border="0" cellpadding="0" cellspacing="0" class="mw-ui-admin-table mw-edit-user-table" width="100%">
      <col width="150px" />
      <tr>
        <td><label class="mw-ui-label"><?php _e("Avatar"); ?></label></td>
        <td><?php if($data['thumbnail'] == ''){    ?>
          <div id="avatar_holder"><img src="<?php print pixum(67,67); ?>" id="avatar_image" alt=""  /><span class="mw-close"></span></div>
          <span class='mw-ui-link' id="change_avatar">
          <?php _e("Add Image"); ?>
          </span>
          <?php   } else {   ?>
          <div id="avatar_holder"><img src="<?php print $data['thumbnail']; ?>" id="avatar_image" alt="" /><span class="mw-close"></span></div>
          <span class='mw-ui-link' id="change_avatar">
          <?php _e("Change Image"); ?>
          </span>
          <?php } ?>
          <input type="hidden" class="mw-ui-field" name="thumbnail" id="user_thumbnail" value="<?php  print $data['thumbnail']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("Username"); ?></label></td>
        <td><input type="text" class="mw-ui-field" name="username" value="<?php  print $data['username']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label" style="padding-bottom: 0;"><?php _e("Password"); ?></label></td>
        <td>
            <span class="mw-ui-link" onclick="reset_password();"><?php _e("Change Password"); ?></span>

            <input type="password" disabled="disabled" name="password" class="mw-ui-field semi_hidden" id="reset_password" />
      </td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("Email"); ?></label></td>
        <td><input type="text" class="mw-ui-field" name="email" value="<?php  print $data['email']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("First Name"); ?></label></td>
        <td><input type="text" class="mw-ui-field" name="first_name" value="<?php  print $data['first_name']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("Last Name"); ?></label></td>
        <td><input type="text" class="mw-ui-field" name="last_name" value="<?php  print $data['last_name']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("Is Active"); ?>?</label></td>
        <td><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable<?php if($data['is_active'] == 'n'): ?> mw-switcher-off<?php endif; ?>"> <span class="mw-switch-handle"></span>
            <label><?php _e("Yes"); ?>
              <input type="radio" value="y" name="is_active" <?php if($data['is_active'] == 'y'): ?> checked="checked" <?php endif; ?>>
            </label>
            <label><?php _e("No"); ?>
              <input type="radio" value="n" name="is_active" <?php if($data['is_active'] == 'n'): ?> checked="checked" <?php endif; ?>>
            </label>
          </div></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label"><?php _e("Is Admin"); ?>?</label></td>
        <td><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable<?php if($data['is_admin'] == 'n'): ?> mw-switcher-off<?php endif; ?>"> <span class="mw-switch-handle"></span>
            <label><?php _e("Yes"); ?>
              <input type="radio" value="y" name="is_admin" <?php if($data['is_admin'] == 'y'): ?> checked="checked" <?php endif; ?>>
            </label>
            <label><?php _e("No"); ?>
              <input type="radio" value="n" name="is_admin" <?php if($data['is_admin'] == 'n'): ?> checked="checked" <?php endif; ?>>
            </label>
          </div></td>
      </tr>
      
      <tr>
        <td><label class="mw-ui-label"><?php _e("Basic mode"); ?></label></td>
        <td><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable<?php if($data['basic_mode'] == 'n'): ?> mw-switcher-off<?php endif; ?>"> <span class="mw-switch-handle"></span>
            <label><?php _e("Yes"); ?>
              <input type="radio" value="y" name="basic_mode" <?php if($data['basic_mode'] == 'y'): ?> checked="checked" <?php endif; ?>>
            </label>
            <label><?php _e("No"); ?>
              <input type="radio" value="n" name="basic_mode" <?php if($data['basic_mode'] == 'n'): ?> checked="checked" <?php endif; ?>>
            </label>
          </div></td>
      </tr>
      
      
      <tr>
        <td><label class="mw-ui-label"><?php _e("Api key"); ?></label></td>
        <td><input type="text" class="mw-ui-field" name="api_key" value="<?php  print $data['api_key']; ?>"></td>
      </tr>
      <tr class="no-hover">
        <td>&nbsp;</td>
        <td><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green right" onclick="_mw_admin_save_user_form<?php  print $data['id']; ?>()">
          <?php _e("Save"); ?>
          </span> <span class="mw-ui-btn mw-ui-btn-medium right" style="margin-right: 10px;" onclick="mw.url.windowDeleteHashParam('edit-user');">
          <?php _e("Cancel"); ?>
          </span></td>
      </tr>
    </table>
  </div>
</div>
<?php endif; ?>
