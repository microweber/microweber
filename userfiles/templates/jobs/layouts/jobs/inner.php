
<div class="page_tit"><? print $post['title']; ?></div>
<?php $c = 0; ?>
<div class="body_part_inner">
  <div class="companies_inner_logo"><img src="<? print user_picture($post['created_by'], 200); ?>" alt="<? print addslashes($post['title']); ?>" /></div>
  <div class="applytothejob_but"><a href="#"><img src="<? print TEMPLATE_URL ?>images/applytothejob_but.jpg" alt="apply to this job" border="0" /></a></div>
  <div class="jobseaker_tit">Job Ad &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Information</div>
  <div class="jobseakers_info_container">
    <table width="98%" border="0" align="left" cellpadding="5" cellspacing="0" >
      <tr>
        <td width="204" ></td>
        <td width="500" class="lt_line">&nbsp;</td>
      </tr>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Job title</td>
        <td class="lt_line"><? print $post['title']; ?></td>
      </tr>
      <? if($post['custom_fields']['location']): ?>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Location</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['location']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['location']) ; ?>
      <? endif; ?>
      <? if($post['custom_fields']['speciality']): ?>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Speciality</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['speciality']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['speciality']) ; ?>
      <? endif; ?>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Posted Date</td>
        <td class="lt_line"><? print $post['created_on'] ?></td>
      </tr>
      <tr>
        <td ></td>
        <td class="lt_line">&nbsp;</td>
      </tr>
      <? if($post['custom_fields']['sallary-range']): ?>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" >Salary Range</td>
        <td class="lt_line"><strong><? print $post['custom_fields']['sallary-range']; ?></strong></td>
      </tr>
      <? unset($post['custom_fields']['sallary-range']) ; ?>
      <? endif; ?>
      <? if($post['custom_fields']): ?>
      <? foreach($post['custom_fields'] as $cfk => $cfv): ?>
      <tr>
        <td class="<? print($c++%2==1) ? 'zebra1' : 'zebra2' ?>" ><? print ucwords(str_replace('-', ' ', $cfk));  ; ?></td>
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
  <? include (TEMPLATE_DIR. "layouts".DS."companies".DS."profile_box.php"); ?>
  <? include (TEMPLATE_DIR. "layouts".DS."companies".DS."send_cv_box.php"); ?>
</div>
