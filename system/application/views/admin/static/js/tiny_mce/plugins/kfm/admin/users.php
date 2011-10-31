<?php
require_once('initialise.php');
$kfm->requireAdmin(true);
//if($kfm->user_status!=1)die ('error("No authorization aquired")');
require_once('functions.php');
$users=db_fetch_all('SELECT * FROM '.KFM_DB_PREFIX.'users WHERE id>1');
if(!is_array($users))die ('error retrieving user list');
$lhtml='
<div class="ui-widget ui-widget-content" style="padding:10px;">
<table id="kfm_admin_users_table">
<thead>
	<tr>
		<th class="ui-widget-header">username</th>
		<th class="ui-widget-header">type</th>
		<th></th>
    <th></th>
	</tr>
</thead>
<tbody>
';
foreach($users as $user){
	$lhtml.=user_row($user['id'],$user['username'],$user['status']);
}
$lhtml.="</tbody>\n</table>\n</div>\n";
//if(count($users)==0)$lhtml='<span class="message">No users found</span>';

?>
<script type="text/javascript">
/* User functions */
  var np_uid = -1;
$(function(){
  var un = $('#newuser_username');
  var pw = $('#newuser_password');
  var nu_errors = $("#newuser_errors");
  var nu_allFields = $([]).add(un).add(pw);

  $('#newuser_blueprint').dialog({
    autoOpen:false,
    modal: true,
    buttons: {"Create":function(){
      var bValid = true;
      e_msg = '';
      if(un.val() == ''){
        bValid = false;
        e_msg += 'Username cannot be empty<br/>';
        un.addClass('ui-state-error');
      }
      // Password conditions??? (pw.val())
      if(bValid){
				$.post('user_new.php',{username:un.val(),password:pw.val()},function(res){eval(res);});
        $(this).dialog('close');
      }else{
        nu_errors.html(e_msg).slideDown();
      }
    }},
    close: function(){
      nu_allFields.val('').removeClass('ui-state-error');
      nu_errors.html('').hide();
    }
  });

  // Now resetting password code
  var np_pass = $('#reset_pw_newpass');
  var np_confirm = $('#reset_pw_newpass_confirm');
  var np_errors = $('#reset_pw_errors');
  var np_allFields = $([]).add(np_pass).add(np_confirm); 
  $('#reset_pw_blueprint').dialog({
    autoOpen:false,
    modal: true,
    buttons:{
      Assign: function(){
        bValid = true;
        e_msg = '';
        if(np_pass.val() != np_confirm.val()){
          bValid = false;
          e_msg += 'Passwords are not equal<br/>';
          np_confirm.addClass('ui-state-error');
        }
        if(bValid){
				  $.post('password_change.php',{uid:np_uid,npw:np_pass.val(),npw2:np_confirm.val(),reset:true},function(res){eval(res);});
          $(this).dialog('close');
        }else{
          np_errors.html(e_msg).slideDown();
        }
      },
      Cancel: function(){
        $(this).dialog('close');
      }
    },
    close: function(){
      np_allFields.val('').removeClass('ui-state-error');
      np_errors.html('').hide();
    }
   });
});
function new_user(){
  $('#newuser_blueprint').dialog('open');
}
function delete_user(uid, username){
  $('<div title="Delete user?">Do you want to delete user: '+username+'?</div>').dialog({
    modal: true,
    buttons:{
      'Delete': function(){
        $.post('user_delete.php',{uid:uid},function(res){eval(res);});
        $(this).dialog('close');
      },
      Cancel: function(){
        $(this).dialog('close');
      }
    }
  });
}
var testerbj = null;
function edit_user_settings(uid, username){
  $.post('settings.php',{uid:uid, ismodal:1}, function(data){
     $('<div title="Settings for '+username+'">'+data+'</div>').dialog({
        modal:true,
        width:900,
        height: 0.9*$(window).height(),
        close: function(event, ui){
          $(this).parents('.ui-dialog').empty();
        }
     });
  });
}
function user_status_change(uid, status){
	$.post('user_status_change.php',{uid:uid,status:status},function(res){eval(res);});
}
function password_reset(uid, username){
  np_uid = uid;
  $('#np_username').html(username);
  $('#reset_pw_blueprint').dialog('open');
}
</script>
<div id="newuser_blueprint" title="New user">
<div id="newuser_errors" class="ui-state-highlight" style="display:none;"></div>
Username: <input type="text" id="newuser_username"><br/>
Password: <input type="password" id="newuser_password"><br/>
</div>
<div id="reset_pw_blueprint" title="Reset password">
<div>Reset password for user: <span id="np_username"></span></div>
<div id="reset_pw_errors" class="ui-state-highlight" style="display:none;"></div>
<label for="reset_pw_newpass">New password</label>
<input type="password" name="reset_pw_newpass" id="reset_pw_newpass" />
<br/>
<label for="reset_pw_newpass_confirm">Retype</label>
<input type="password" name="reset_pw_newpass_confirm" id="reset_pw_newpass_confirm" />
</div>
<?php echo $lhtml;?>
<br />
<span class="ui-state-default button" onclick="new_user()">New user</span>
<br />
