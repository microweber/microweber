<script type="text/javascript">
function change_pw(){
	opw=$('#password_old').val();
	npw=$('#password_new').val();
	npw2=$('#password_new2').val();
  /*
	if(npw!=npw2){
		$.prompt('The new passwords are not the same');
		return;
	}*/
	$.post('password_change.php',{opw:opw,npw:npw,npw2:npw2},function(res){eval(res);});
}
</script>
<div id="password_div" class='ui-widget-content'>
<label for="password_old">Old password</label><input type="password" name="password_old" id="password_old" class="npw_field"/><br/>
<label for="password_new">New password</label><input type="password" name="password_new" id="password_new" class="npw_field"/><br/>
<label for="password_new2">New password again</label><input type="password" name="password_new2" id="password_new2" class="npw_field"/><br/>
<span class="ui-state-default button" onclick="change_pw()">Change</span>
</div
