<?php

/*

type: layout

name: layout

description: layout









*/


$dash_user = $to_user = $dashboard_user = user_id_from_url();
?>
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
$user_data = get_user($dash_user);

 $v = url_param('view');
  $vv =  $v ;
 if(  $v == false){
	 $v1 = "main.php";
 } else {
	  $v1 = $v .".php";
 }

?>

<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left"> <? print user_name($dash_user); ?>
      <div class="howit_works_left_img"> <br />
        <? $u_pic = get_picture($dash_user, $for = 'user'); 
	
	?>
        <? if($u_pic == false){
	$u_pic = TEMPLATE_URL . "images/mystery-man.jpg";
	$no = true;
	} else {
		
	$u_pic = get_media_thumbnail($u_pic ['id'], $size_width = 210, $size_height = false);	
	$no = false;
	
	}
	 
	?>
        <!-- <img src="<? print TEMPLATE_URL ?>images/How_it_work_left_img.jpg" />-->
        <img src="<? print $u_pic ?>" /> </div>
      <div class="dash-sidebar-nav-container">
        <div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">Contact</div>
          <div class="dash-sidebar-nav"> <a href="javascript:mw.users.UserMessage.compose(<?php echo $user['id']; ?>);" class="send_msg"><span   title="Send new message to <?php echo $user['first_name']; ?>" >Send a Message</span></a> </div>
          
          
          <div class="dash-sidebar-nav"> <a  id="follow_btn_<?php echo $to_user?>" href="javascript:mw.users.FollowingSystem.follow(<?php echo $to_user?>, 0, '.inner_user_<?php echo $to_user?>');" class="add_request" title="Add as friend <?php print get_instance()->users_model->getPrintableName($to_user, 'first'); ?>"><span>Add as friend</span></a></div>
        </div>
      </div>
    </div>
    <div class="inner_rt">
      <microweber module="users/profile_view" user_id="<? print $dashboard_user ?>"></microweber>
    </div>
  </div>
</div>
<? endif; ?>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
