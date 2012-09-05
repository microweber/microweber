<?php

/*

type: layout

name: layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
$user_id = user_id(); ?>
<? if( $user_id == 0): ?>
<? include TEMPLATE_DIR. "must_reg_generic.php"; ?>
<? else: ?>
<? 



$user_data = get_user();

 

 $v = url_param('view');
  $vv =  $v ;
 if(  $v == false){
	 $v1 = "main.php";
 } else {
	  $v1 = $v .".php";
 }



?>

<div class="page_tit"><span class="welcome">Welcome</span> Josh Mayfid </div>
<div class="body_part_inner">
  <div class="profile_tit">Manage your profile</div>
  <div class="profile_nav">
    <ul>
      <? if($user_data['role'] == 'company'): ?>
      <li <? if($vv == 'my-posts'): ?> class="current" <? endif; ?> > <a <? if($vv == 'my-posts'): ?> class="current" <? endif; ?>  href="<? print page_url(); ?>/view:my-posts"  >My job ads</a></li>
      <? endif ?>
      <li <? if($vv == 'my-profile'): ?> class="current" <? endif; ?> > <a <? if($vv == 'my-profile'): ?> class="current" <? endif; ?>  href="<? print page_url(); ?>/view:my-profile"  >Account settings</a></li>
      <li  <? if($vv == ''): ?> class="current" <? endif ?> > <a  <? if($vv == ''): ?> class="current" <? endif ?>  href="<? print page_url() ?>">Dashboard</a></li>
    </ul>
  </div>
  <div class="profile_nav_bot"></div>
  <div class="profile_blk">
    <? $dashboard_user = user_id_from_url();


 
 $v1 = TEMPLATE_DIR. "layouts".DS."dashboard".DS.$v1;
// p( $v1);
 include( $v1);
?>
    <? endif; ?>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
