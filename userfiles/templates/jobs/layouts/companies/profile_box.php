<? $c=0; ?>
<div class="jobseakers_info_container">
  <table width="98%" border="0" align="left" cellpadding="5" cellspacing="0" >
    <tr>
      <td width="204" ></td>
      <td width="500" class="lt_line">&nbsp;</td>
    </tr>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >User</td>
      <td class="lt_line"><? print ucwords(user_name($user['id'])) ?></td>
    </tr>
    <tr>
      <td  class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >E-mail Contacts </td>
      <td class="lt_line"><a href="mailto:<? print $user['email'];  ?>"><? print $user['email'];  ?></a></td>
    </tr>
    <tr>
      <td class="left_space">&nbsp;</td>
      <td class="lt_line">&nbsp;</td>
    </tr>
    <? if($user['custom_fields']['country']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Country</td>
      <td class="lt_line"><strong><? print $user['custom_fields']['country']; ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['country']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['state']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">State</td>
      <td class="lt_line"><strong><? print $user['custom_fields']['state']; ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['state']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['city']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">City</td>
      <td class="lt_line"><strong><? print $user['custom_fields']['city']; ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['city']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['address']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">Address</td>
      <td class="lt_line"><strong><? print nl2br($user['custom_fields']['address']); ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['address']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['zip']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">Zip</td>
      <td class="lt_line"><strong><? print $user['custom_fields']['zip']; ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['zip']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['phone']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">Phone</td>
      <td class="lt_line"><strong><? print $user['custom_fields']['phone']; ?></strong></td>
    </tr>
    <? unset($user['custom_fields']['phone']) ; ?>
    <? endif; ?>
    <? if($user['custom_fields']['about']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>">About</td>
      <td rowspan="2" valign="top" class="lt_line"><? print nl2br($user['custom_fields']['about']); ?></td>
    </tr>
    <? endif; ?>
    <tr>
      <td ><br /></td>
    </tr>
    <tr>
      <td ></td>
      <td class="lt_line">&nbsp;</td>
    </tr>
    <? if($user['custom_fields']['website']): ?>
    <tr>
      <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Website</td>
      <td  valign="top" class="lt_line"><a href="<? print prep_url($user['custom_fields']['website']); ?>" target="_blank"><? print ($user['custom_fields']['website']); ?></a></td>
    </tr>
    <? endif; ?>
    <tr>
      <td >&nbsp;</td>
      <td class="lt_line">&nbsp;</td>
    </tr>
  </table>
</div>
