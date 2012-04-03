

  <?  if($user_id == false){

	$user_id = user_id();
}


?>
  <? $form_values = get_user($user_id); 
  
 // p($form_values);
  ?>

  
<?
//print $form_values["custom_fields"]["last_name"];

?>
<div style="height: 10px;">&nbsp;</div>
<h2><? print $form_values["first_name"] ?>&nbsp;<? print $form_values["last_name"] ?>  </h2>

<div style="height: 15px;">&nbsp;</div>


<table class="userinfo" cellpadding="0" cellspacing="0">
<tr>
    <td class="inf">Username</td>
    <td><? print $form_values["username"] ?> </td>
</tr>
<tr>
    <td class="inf">Email</td>
    <td><? print $form_values["email"] ?></td>
</tr>
<tr>
    <td class="inf">Country</td>
    <td><? print $form_values["custom_fields"]["country"];     ?>  </td>
</tr>
<tr>
    <td class="inf">City</td>
    <td><? print $form_values["custom_fields"]["city"];     ?>  </td>
</tr>
<tr>
    <td class="inf">About me</td>
    <td><? print $form_values["custom_fields"]["about"];     ?>  </td>
    </tr>
</table>





