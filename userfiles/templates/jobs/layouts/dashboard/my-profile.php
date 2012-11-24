<?  if($user_id == false){

	$user_id = user_id();
}


?>
<? $form_values = get_user($user_id); ?>
<?php dbg(__FILE__); ?>
<?php $more = get_instance()->core_model->getCustomFields('table_users', $form_values['id']);

$form_values['custom_fields'] = $more;
?>

<div class="edit-profile-form-holder">
  <?php $compete_profile = get_instance()->core_model->getParamFromURL ( 'compete_profile' ); ?>
  <?php if($user_edit_done == true) : ?>
  <? /*
  <h1 class="saved">Changes are saved</h1>
 */ ?>
  <?php endif;  ?>
  <?php if($compete_profile == 'yes') : ?>
  <h1 id="profile-please-complete">Please complete your profile</h1>
  <?php endif;  ?>
  <?php if(!empty($user_edit_errors)) : ?>
  <!--<ul class="error">
      <?php foreach($user_edit_errors as $k => $v) :  ?>
      <li><?php print $v ?></li>
      <?php endforeach; ?>
    </ul>--> 
  <script type="text/javascript">
        var errors = '<ul class="error"><?php foreach($user_edit_errors as $k => $v) :  ?><li><?php print $v ?></li><?php endforeach; ?></ul>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });
        });

    </script>
  <?php endif ?>
  <?php if($user_edit_done == true) : ?>
  <script type="text/javascript">
        var errors = ' <h2>Profile updated!</h2>';

        $(document).ready(function(){
            $(window).load(function(){
              mw.box.alert(errors);
            });


            //$("#edit-profile-form").validate();

        });



    </script>
  <?php endif ?>
  <!-- <form action="<?php print site_url('users/user_action:profile'); ?>" method="post" enctype="multipart/form-data" id="profileForm" class="form validate">-->
  <form action="#" method="post" enctype="multipart/form-data" id="edit-profile-form" class="form validate edit-profile-form">
    <input type="hidden" name="id" value="<? print $form_values['id']; ?>" />
    <!-- <span id="first-fields-error">You must fill out your: username, first name, last name and email.</span>-->
    <div class="role1"> 1. <span class="role">Account type</span>
      <div class="control-group">
        <label class="control-label"></label>
        <div class="controls">
          <label class="radio">
            <input type="radio" name="role" id="optionsRadios1" value="job_seeker" <? if($form_values['role'] == strtolower('job_seeker')): ?> checked="checked" <? endif; ?> >
            Job seeker - you can search for jobs and send your CV to employers </label>
          <label class="radio">
            <input type="radio" name="role" id="optionsRadios2" value="company" <? if($form_values['role'] == strtolower('company')): ?> checked="checked" <? endif; ?> >
            Company - publish job ads and receive CVs of job seekers </label>
        </div>
      </div>
      <br />
    </div>
    <div class="role1 item"> 2. <span class="role">Speciality</span>
      <? $roles = option_get('user_roles');
   $roles = csv2array($roles);
   //p($roles);
     ?>
      <select class="mw-input" name="custom_field_speciality">
        <? foreach($roles as $role) : ?>
        <option <? if($form_values['custom_fields']['speciality'] == strtolower($role)): ?> selected="selected" <? endif; ?> value="<? print strtolower($role); ?>"><? print $role; ?></option>
        <? endforeach ?>
      </select>
    </div>
    <div class="role1 item"> 3. <span class="role">Edit your profile</span> </div>
    <div class="stabs" style="padding-top: 15px;">
      <?
		   $iframe_module_params = array();
		   $iframe_module_params['module'] = 'users/profile_picture_edit';
		     $iframe_module_params['user_id'] = $user_id;
		   $iframe_module_params = base64_encode(serialize($iframe_module_params));



		   ?>
      <div class="stab"> 
        <script>
	  $(document).ready(function(){


 
	});
 
	</script>
        <div id="tabs">
          <div id="tabs-1">
            <h3>Account info</h3>
            <div class="item">
              <label>Picture:</label>
              <div class="field user_imae_field">
                <iframe height="100" width="450"  frameborder="0" scrolling="no" src="<? print site_url('api/module/iframe:'. $iframe_module_params) ?>"></iframe>
              </div>
            </div>
            <div class="item">
              <label>Username: *</label>
              <span class="field">
              <input disabled="disabled"   id="profile-username" class="required"  type="text" value="<?php print $form_values['username'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Email: *</label>
              <span class="field">
              <input name="email" id="profile-email"    type="text" value="<?php print $form_values['email'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Website:</label>
              <span class="field">
              <input  name="custom_field_website" type="text" value="<?php print $form_values['custom_fields']['website'];  ?>" />
              </span> </div>
            <div class="item">
              <label>First name: *</label>
              <span class="field">
              <input name="first_name" id="profile-firstname" type="text"  value="<?php print $form_values['first_name'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Last name: *</label>
              <span class="field">
              <input name="last_name" id="profile-lastname" type="text" value="<?php print $form_values['last_name'];  ?>" />
              </span> </div>
          </div>
          <div id="tabs-2">
            <h3>Contact information</h3>
            <div class="item">
              <label>Country:</label>
              <span class="field">
              <input  name="custom_field_country" type="text" value="<?php print $form_values['custom_fields']['country'];  ?>" />
              </span> </div>
            <div class="item">
              <label>State:</label>
              <span class="field">
              <input  name="custom_field_state" type="text" value="<?php print $form_values['custom_fields']['state'];  ?>" />
              </span> </div>
            <div class="item">
              <label>City:</label>
              <span class="field">
              <input  name="custom_field_city" type="text" value="<?php print $form_values['custom_fields']['city'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Address:</label>
              <span class="field">
              <input  name="custom_field_address" type="text" value="<?php print $form_values['custom_fields']['address'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Zip:</label>
              <span class="field">
              <input  name="custom_field_zip" type="text" value="<?php print $form_values['custom_fields']['zip'];  ?>" />
              </span> </div>
            <div class="item">
              <label>Phone:</label>
              <span class="field">
              <input  name="custom_field_phone" type="text" value="<?php print $form_values['custom_fields']['phone'];  ?>" />
              </span> </div>
          </div>
          <div id="tabs-3">
            <h3>Personal information</h3>
            <? /*
          <div class="item">
          <label>Your paypal address:</label>
          <span class="linput">
          <input  name="custom_field_paypal" type="text" value="<?php print $form_values['custom_fields']['paypal'];  ?>" />
          </span> </div>
          */ ?>
            <div class="item">
              <label>Birth day:</label>
              <span class="field">
              <? /*
<input  name="custom_field_bday" type="text" value="<?php print $form_values['custom_fields']['bday'];  ?>" />
*/ ?>
              <select  name="custom_field_bday" value="<?php print $form_values['custom_fields']['bday'];  ?>" >
                <option value="">Day&nbsp;</option>
                <? for ($i = 1; $i <= 31; $i++) :?>
                <option <? if($form_values['custom_fields']['bday'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
                <? endfor; ?>
              </select>
              </span> </div>
            <div class="item">
              <label>Birth month:</label>
              <span class="field">
              <select   name="custom_field_bmonth" value="<?php print $form_values['custom_fields']['bmonth'];  ?>" >
                <option value="">Month&nbsp;</option>
                <? for ($i = 1; $i <= 12; $i++) :?>
                <option  <? if($form_values['custom_fields']['bmonth'] == $i): ?> selected="selected" <? endif; ?>   value="<? print $i ?>"><? print  date("F", mktime(0, 0, 0, $i, 1, 2000)); ?>&nbsp;</option>
                <? endfor; ?>
              </select>
              </span> </div>
            <div class="item">
              <label>Birth year:</label>
              <span class="field">
              <select  name="custom_field_byear" value="<?php print $form_values['custom_fields']['byear'];  ?>" >
                <option value="">Year&nbsp;</option>
                <? for ($i = date("Y"); $i >= 1950; $i--) :?>
                <option <? if($form_values['custom_fields']['byear'] == $i): ?> selected="selected" <? endif; ?> value="<? print $i ?>"><? print $i ?>&nbsp;</option>
                <? endfor; ?>
              </select>
              </span> </div>
            <div class="item">
              <label>About me text:</label>
              <span class="area">
              <textarea   name="custom_field_about" type="text" ><?php print $form_values['custom_fields']['about'];  ?></textarea>
              </span> </div>
            <? // p($form_values); ?>
            <!--   <h4>Your account will expire on <? print $form_values['expires_on'] ?></h4>-->
            <?php /*
        <input name="test" type="button" onClick="refresh_user_picture_info()" value="test" />
        <input name="test2" type="button" onClick="open_crop_user_picture_info()" value="test2" />
*/ ?>
          </div>
        </div>
        <div class="item">

         <a href="javascript:if($('#edit-profile-form')){save_user()};" ><img src="<? print TEMPLATE_URL ?>images/pwd_change_save_but_45.jpg" /></a>  </div>
         <div class="c" style="padding-bottom: 12px;">&nbsp;</div>
      </div>
    </div>
    <div class="item">
      <div class="role1">3. <span class="role">Password</span> </div>
      <br />
      <a href='javascript:change_pass_show();' class="blue">change password</a>
      <div class="change_pass_holder field" style="display:none">
        <table width="100%" border="0">
          <tr>
            <td><label>Enter new password:</label>
              <span class="field">
              <input class="required-equal" equalto="pass" type="password" name="password"   value="<?php print $form_values['password'];  ?>" />
              </span></td>
          </tr>
          <tr>
            <td><label>Repeat new password:</label>
              <span class="field">
              <input class="required-equal" equalto="pass" type="password" value="<?php print $form_values['password'];  ?>"  />
              </span></td>
          </tr>
        </table>
        <div class="item" style="margin:15px;"> <span class="field"> <a href="javascript:if($('#edit-profile-form')){save_user()};" ><img src="<? print TEMPLATE_URL ?>images/pwd_change_save_but_45.jpg" /></a> </span> </div>
      </div>
    </div>
    <!--<a href="javascript:;" class="btn submit">SAVE</a>-->
  </form>
  <div id="user_save_success" style="display:none;">
    <h2>Profile saved.</h2>
  </div>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar.php') ?>
</div>
<?php dbg(__FILE__, 1); ?>
