
<div class="page_tit"><? print $post['content_title']; ?></div>
<?php $c = 0; ?>
<div class="body_part_inner">
  <div class="companies_inner_logo"><img src="<? print TEMPLATE_URL ?>images/bayler_logo.jpg" alt="bayler" /></div>
  <div class="applytothejob_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/applytothejob_but.jpg" alt="apply to this job" border="0" /></a></div>
  <div class="jobseaker_tit">Job Ad &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Information</div>
  <div class="jobseakers_info_container">
    <table width="98%" border="0" align="left" cellpadding="5" cellspacing="0" >
      <tr>
        <td width="204" ></td>
        <td width="500" class="lt_line">&nbsp;</td>
      </tr>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" >Job title</td>
        <td class="lt_line"><? print $post['content_title']; ?></td>
      </tr>
      <? if($post['custom_fields']['location']): ?>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" >Location</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['location']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['location']) ; ?>
      <? endif; ?>
      <? if($post['custom_fields']['speciality']): ?>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" >Speciality</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['speciality']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['speciality']) ; ?>
      <? endif; ?>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" >Posted Date</td>
        <td class="lt_line"><? print $post['created_on'] ?></td>
      </tr>
      <tr>
        <td ></td>
        <td class="lt_line">&nbsp;</td>
      </tr>
      <? if($post['custom_fields']['sallary-range']): ?>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" >Salary Range</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['sallary-range']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['sallary-range']) ; ?>
      <? endif; ?>
      <? if($post['custom_fields']): ?>
      <? foreach($post['custom_fields'] as $cfk => $cfv): ?>
      <tr>
        <td bgcolor="<? print($c++%2==1) ? '#F0F0F0' : '#FFFFF' ?>" ><? print ucwords(str_replace('-', ' ', $cfk));  ; ?></td>
        <td class="lt_line"><? print($cfv);  ; ?></td>
      </tr>
      <?   endforeach;  ?>
      <? endif; ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </div>
  <div class="jobseaker_tit" style="margin-top:0px; padding-top:3px; border:none;">The Company &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Information</div>
  <? $user = get_user($post['created_by']); 
  
 // P($user);
  
  ?>
  <div class="jobseakers_info_container">
    <table width="98%" border="0" align="left" cellpadding="5" cellspacing="0" >
      <tr>
        <td width="204" ></td>
        <td width="500" class="lt_line">&nbsp;</td>
      </tr>
      <tr>
        <td >Published by</td>
        <td class="lt_line"><? print ucwords(user_name($post['created_by'])) ?></td>
      </tr>
      <tr>
        <td class="left_space">&nbsp;</td>
        <td class="lt_line">&nbsp;</td>
      </tr>
      <? if($user['custom_fields']['country']): ?>
      <tr>
        <td>Country</td>
        <td class="lt_line"><strong><? print $user['custom_fields']['country']; ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['country']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['state']): ?>
      <tr>
        <td>State</td>
        <td class="lt_line"><strong><? print $user['custom_fields']['state']; ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['state']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['city']): ?>
      <tr>
        <td>City</td>
        <td class="lt_line"><strong><? print $user['custom_fields']['city']; ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['city']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['address']): ?>
      <tr>
        <td>Address</td>
        <td class="lt_line"><strong><? print nl2br($user['custom_fields']['address']); ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['address']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['zip']): ?>
      <tr>
        <td>Zip</td>
        <td class="lt_line"><strong><? print $user['custom_fields']['zip']; ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['zip']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['phone']): ?>
      <tr>
        <td>Phone</td>
        <td class="lt_line"><strong><? print $user['custom_fields']['phone']; ?></strong></td>
      </tr>
      <? unset($user['custom_fields']['phone']) ; ?>
      <? endif; ?>
      <? if($user['custom_fields']['about']): ?>
      <tr>
        <td >About</td>
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
        <td >Website</td>
        <td  valign="top" class="lt_line"><a href="<? print prep_url($user['custom_fields']['website']); ?>" target="_blank"><? print ($user['custom_fields']['website']); ?></a></td>
      </tr>
      <? endif; ?>
      <tr>
        <td >E-mail Contacts </td>
        <td class="lt_line"><a href="mailto:<? print $user['email'];  ?>"><? print $user['email'];  ?></a></td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td class="lt_line">&nbsp;</td>
      </tr>
    </table>
  </div>
  <div class="jobseaker_tit" style="margin-top:0px; border:none;">Send your CV to apply for this Job</div>
  <div style="height:71px; float:left; width:100%"></div>
  <div class="send_cv_but"><img src="<? print TEMPLATE_URL ?>images/send_cv_but_16.jpg" /></div>
  <div class="sendcv_arr"><img src="<? print TEMPLATE_URL ?>images/sendcv_arr.jpg" /></div>
  <div class="sendcv_box">
    <div class="sendcv_box_top"></div>
    <div class="sendcv_box_mid">
      <div class="sendcv_browse">
        <input type="file" style="" />
      </div>
      <textarea name="" cols="" rows="" class="sendcv_msg"></textarea>
      <div class="sendcv_send_but">
        <input type="image" src="<? print TEMPLATE_URL ?>images/sendcv_send_but.jpg" />
      </div>
    </div>
    <div class="sendcv_box_bot"></div>
  </div>
  <div class="sendcv_dn_arr"></div>
  <div class="sendcv_error">Error Message: Please fill all fields</div>
  <div class="sendcv_success">Your CV was sent successfuly to Employer</div>
</div>
