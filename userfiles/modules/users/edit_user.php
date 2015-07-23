<?php if(is_admin() == false) { mw_error("Must be admin"); }

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
  $data['is_active'] = 1;
  $data['is_admin'] = 0;
  $data['basic_mode'] = 0;
  $data['thumbnail'] = '';
  $data['profile_url'] = '';
} else {
$data = $data[0];	
}

 
 
 ?>
<?php if(is_array($data )): ?>
<?php event_trigger('mw.admin.user.edit',$data); ?>
<?php $custom_ui = mw()->modules->ui('mw.admin.user.edit'); ?>
<?php $custom_user_fields = mw()->modules->ui('mw.admin.user.edit.fields');

 
 ?>

 
<script  type="text/javascript">

DeleteUserAdmin<?php  print $data['id']; ?> = function($user_id){
	
	 
	var r = confirm("Are you sure you want to delete this user?");
	if (r == true) {
		 $.post( "<?php print api_url('delete_user') ?>", { id: $user_id })
		  .done(function( data ) {
			    mw.reload_module('[data-type="users/manage"]', function(){
					mw.hash('#sortby=created_at desc');
					mw.notification.success('User deleted');
					 
				  
				 });
		  });
	} 
}

LoginAsUserFromAdmin<?php  print $data['id']; ?> = function($user_id){
	
	 
	var r = confirm("Are you sure you want to login as this user?");
	if (r == true) {
		 $.post( "<?php print api_url('user_make_logged') ?>", { id: $user_id })
		  .done(function( data ) {
			  
			  window.location.reload() 
			
			   
			   
			   
		  });
	} 
}

SaveAdminUserForm<?php  print $data['id']; ?> = function(){
    if(mwd.getElementById("reset_password").value == ''){
        mwd.getElementById("reset_password").disabled = true;
    }
    mw.tools.loading('.mw-module-admin-wrap');
    mw.form.post(mw.$('#users_edit_{rand}') , '<?php print api_link('save_user') ?>', function(){
      UserId = this;
      mw.tools.loading('.mw-module-admin-wrap', false);
	  mw.reload_module('[data-type="users/manage"]', function(){
	    mw.hash('#sortby=created_at desc');
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



$(document).ready(function(){

    mw.$("#change_avatar").append(uploader);

    $(uploader).bind("FileUploaded", function(a,b){
          mw.$("#avatar_holder")
            .css("backgroundImage", 'url(' + b.src + ')')
            .find(".mw-icon-user")
            .remove();
          mw.$("#user_thumbnail").val(b.src);
    });

    mw.$("#avatar_holder .mw-icon-close").click(function(){
      if(mw.$("#avatar_holder .mw-icon-user").length === 0){
          mw.$('#avatar_holder')
            .css('backgroundImage', 'none')
            .prepend('<span class="mw-icon-user"></span>');
          mw.$("#user_thumbnail").val("");
      }

    });


 /*   mw.$("#profile_url_field").bind('keyup paste', function(){
        if(this.value.length > 15){
            mw.$("#google-verify-button").visibilityDefault()
        }
    });
    if(mw.$("#profile_url_field").val().length > 15){
           mw.$("#google-verify-button").visibilityDefault()
    }*/

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
<?php if (!empty($custom_ui)): ?>
<script>
$(document).ready(function(){
	mw.tabs({
	nav:'.mw-admin-user-tab',
	tabs:'.mw-admin-user-tab-content'
	});
});
</script>

<div class="mw-ui-btn-nav mw-ui-btn-nav-tabs pull-right"> <a class="mw-ui-btn mw-ui-btn-outline active mw-admin-user-tab" href="javascript:;">Profile</a>
  <?php foreach ($custom_ui as $item): ?>
  <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
  <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
  <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
  <a class="mw-ui-btn mw-ui-btn-outline mw-admin-user-tab" href="javascript:;"><?php print $title; ?></a>
  <?php endforeach; ?>
</div>
<?php endif; ?>
<div class="mw-ui-box <?php print $config['module_class'] ?> user-id-<?php  print $data['id']; ?>" id="users_edit_{rand}">
  <div class="mw-ui-box-header" style="margin-bottom: 0;"> <span class="ico iusers"></span>
    <?php if($data['id'] != 0): ?>
    <span>
    <?php _e("Edit user"); ?>
    &laquo;
    <?php  print $data['username']; ?>
    &raquo;</span>
    <?php else: ?>
    <span>
    <?php _e("Add new user"); ?>
    </span>
    <?php endif; ?>
  </div>
  <input type="hidden" class="mw-ui-field" name="id" value="<?php  print $data['id']; ?>">
  <div>
    <table border="0" cellpadding="0" cellspacing="0" class="mw-ui-table mw-ui-table-basic mw-admin-user-tab-content" width="100%">
      <col width="150px" />
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Avatar"); ?>
          </label></td>
        <td><?php if($data['thumbnail'] == ''){    ?>
          <div id="avatar_holder"><span class="mw-icon-user"></span></div>
          <span class='mw-ui-link' id="change_avatar">
          <?php _e("Add Image"); ?>
          </span>
          <?php   } else {   ?>
          <div id="avatar_holder" style="background-image: url(<?php print $data['thumbnail']; ?>)"><span class="mw-icon-close"></span></div>
          <span class='mw-ui-link' id="change_avatar">
          <?php _e("Change Image"); ?>
          </span>
          <?php } ?>
          <input type="hidden" class="mw-ui-field" name="thumbnail" id="user_thumbnail" value="<?php  print $data['thumbnail']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Username"); ?>
          </label></td>
        <td><input type="text" class="mw-ui-field" name="username" value="<?php  print $data['username']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Password"); ?>
          </label></td>
        <td><span class="mw-ui-link" onclick="reset_password();$(this).hide()">
          <?php _e("Change Password"); ?>
          </span>
          <input type="password" disabled="disabled" name="password" class="mw-ui-field semi_hidden" id="reset_password" /></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Email"); ?>
          </label></td>
        <td><input type="text" class="mw-ui-field" name="email" value="<?php  print $data['email']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("First Name"); ?>
          </label></td>
        <td><input type="text" class="mw-ui-field" name="first_name" value="<?php  print $data['first_name']; ?>"></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Last Name"); ?>
          </label></td>
        <td><input type="text" class="mw-ui-field" name="last_name" value="<?php  print $data['last_name']; ?>"></td>
      </tr>
      <?php if (is_admin()){ ?>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Is Active"); ?>
          </label></td>
        <td><div class="mw-ui-inline-list">
            <label class="mw-ui-check">
              <input type="radio" value="1" name="is_active" <?php if($data['is_active'] == 1): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("Yes"); ?>
              </span> </label>
            <label class="mw-ui-check">
              <input type="radio" value="0" name="is_active" <?php if($data['is_active'] == 0): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("No"); ?>
              </span> </label>
          </div></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Is Admin"); ?>
            ?</label></td>
        <td><div class="mw-ui-inline-list">
            <label class="mw-ui-check">
              <input type="radio" value="1" name="is_admin" <?php if($data['is_admin'] == 1): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("Yes"); ?>
              </span> </label>
            <label class="mw-ui-check">
              <input type="radio" value="0" name="is_admin" <?php if($data['is_admin'] == 0): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("No"); ?>
              </span> </label>
          </div></td>
      </tr>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Basic mode"); ?>
          </label></td>
        <td><div class="mw-ui-inline-list">
            <label class="mw-ui-check">
              <input type="radio" value="1" name="basic_mode" <?php if($data['basic_mode'] == 1): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("Yes"); ?>
              </span> </label>
            <label class="mw-ui-check">
              <input type="radio" value="0" name="basic_mode" <?php if($data['basic_mode'] == 0): ?> checked="checked" <?php endif; ?>>
              <span></span> <span>
              <?php _e("No"); ?>
              </span> </label>
          </div></td>
      </tr>
      <?php }  ?>
      <tr>
        <td><label class="mw-ui-label">
            <?php _e("Api key"); ?>
          </label></td>
        <td><input type="text" class="mw-ui-field" name="api_key" value="<?php  print $data['api_key']; ?>"></td>
      </tr>
       
       <?php if (!empty($custom_user_fields)): ?>
    <?php foreach ($custom_user_fields as $item): ?>
    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
    
   
    <tr class="<?php print $class; ?>">
        <td><label class="mw-ui-label">
           <?php print ($title); ?>
          </label></td>
        <td><?php print $html; ?></td>
      </tr>
    
    
 
    <?php endforeach; ?>
    <?php endif; ?>
       
       
       
       
      <tr class="no-hover">
        <td><?php if($data['id'] != false and $data['id'] != user_id()): ?>
    
    <a onclick="LoginAsUserFromAdmin<?php  print $data['id']; ?>('<?php  print $data['id']; ?>')"  class="mw-ui-btn mw-ui-btn-small pull-left">Login as User</a>
   
           <a onclick="DeleteUserAdmin<?php  print $data['id']; ?>('<?php  print $data['id']; ?>')"  class="mw-ui-btn mw-ui-btn-small pull-left">Delete user</a>
          
          <?php endif; ?></td>
        <td><span class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert pull-right" onclick="SaveAdminUserForm<?php  print $data['id']; ?>()">
          <?php _e("Save"); ?>
          </span> <a class="mw-ui-btn mw-ui-btn-medium pull-right" href="#sortby=created_at desc">
          <?php _e("Cancel"); ?>
          </a></td>
      </tr>
    </table>
    <?php if (!empty($custom_ui)): ?>
    <?php foreach ($custom_ui as $item): ?>
    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
    <div style="display:none;" class="mw-ui-box-content mw-admin-user-tab-content  <?php print $class; ?>"
                 title="<?php print addslashes($title); ?>"><?php print $html; ?></div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
