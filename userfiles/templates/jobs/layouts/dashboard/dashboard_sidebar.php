<? $dashboard_user = user_id_from_url(); ?>
<? if($dashboard_user == false){ $dashboard_user = $user_id; } ?>

<div id="user_sidebar">
  <div id="user_image"><img src="<?php print $this->users_model->getUserThumbnail($dashboard_user, $size = 250); ?>" alt="<?php print addslashes($this->users_model->getPrintableName($dashboard_user)); ?>" />
    <? if($dashboard_user != $user_id) : ?>
    <a href="<?php print site_url('userbase/action:profile/username:'.user_name($dashboard_user, 'username')) ?>">View profile</a>
    <?php  else : ?>
    <a href="<?php print (site_url('dashboard/action:profile')); ?>">Change profile Image</a>
    <? endif; ?>
  </div>
  <h3 id="user_image_name"> <?php print $this->users_model->getPrintableName($dashboard_user); ?></h3>
  <ul class="user_side_nav">
    <? if($dashboard_user != $user_id) : ?>
    <li id="user_side_nav_pics"><a <? if(url_param('action') == 'my-pictures' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:my-pictures'); ?>/user_id:<? print $dashboard_user ?>">See Pictures</a></li>
    <li id="user_side_nav_videos"><a <? if(url_param('action') == 'my-videos' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:my-videos'); ?>/user_id:<? print $dashboard_user ?>">See Videos</a></li>
    <li id="user_side_nav_questions"><a <? if(url_param('action') == 'my-questions' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:my-questions'); ?>/user_id:<? print $dashboard_user ?>">See questions</a></li>
    <li id="user_side_nav_toys"><a <? if(url_param('action') == 'toys' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:toys'); ?>/user_id:<? print $dashboard_user ?>">Toys for sale</a></li>
    <li id="user_side_nav_toys"><a <? if(url_param('action') == 'profile-view' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:profile-view'); ?>/user_id:<? print $dashboard_user ?>">Contact info</a></li>
    <?php  else : ?>
    <li><a id="preview-profile" href="<?php print site_url('dashboard') ?>">My dashboard</a></li>
    <li id="user_side_nav_msg"><a <? if(url_param('user_action') == 'my-messages' ) : ?> class="active" <? endif; ?> href="<?php print (site_url('dashboard/user_action:my-messages')); ?>">Messages</a>
    <li  <? if(url_param('action') == 'message_compose' ) : ?> class="active" <? endif; ?>    ><a href="#" onclick="mw.users.UserMessage.compose();" class="send_a_message">Send a message</a></li>
    <!--    <? if((url_param('user_action') == 'messages' ) or (url_param('action') == 'message_compose' ) ) : ?>

      <ul>

        <li  <? if(url_param('action') == 'message_compose' ) : ?> class="active" <? endif; ?>    ><a href="#" onclick="mw.users.UserMessage.compose();" class="send_a_message">Send a message</a></li>

        



        

        <li  <? if(url_param('show') == 'unread' ) : ?> class="active" <? endif; ?>   ><a href="<?php print site_url('dashboard/user_action:messages/show:unread'); ?>">Unread messages</a></li>

        <li  <? if(url_param('show') == 'read' ) : ?> class="active" <? endif; ?>    ><a href="<?php print site_url('dashboard/user_action:messages/show:read'); ?>">Read messages</a></li>

        <li  <? if(url_param('show') == 'sent' ) : ?> class="active" <? endif; ?>    ><a href="<?php print site_url('dashboard/user_action:messages/show:sent'); ?>">Sent messages</a></li>

      </ul>

      <? endif; ?>-->
    </li>
    <li id="user_side_nav_pics"><a <? if(url_param('action') == 'livechat' ) : ?> class="active" <? endif; ?> <a href="http://skidekids.com/chat" onClick="return (window.open(this.href, 'popup', 'height=425,width=705,top='+ ((screen.height - 425) / 2) +',left='+ ((screen.width - 705) / 2) +',scrollbars=yes,resizable,status=yes') == null);">Live Chat (New!)</a></li>	
    <li id="user_side_nav_pics"><a <? if(url_param('action') == 'my-pictures' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:my-pictures'); ?>">My Pictures</a></li>
    <li id="user_side_nav_videos"><a <? if(url_param('action') == 'my-videos' ) : ?> class="active" <? endif; ?> href="<?php print site_url('dashboard/action:my-videos'); ?>">My Videos</a></li>
    <li id="user_side_nav_friends"><a <? if(url_param('action')=='find-friends'): ?>class="active"<? endif; ?> href="<?php print site_url('dashboard/action:find-friends'); ?>">Find New Friends</a></li>
    <li id="user_side_nav_my_friends"><a <? if(url_param('action')=='my-friends'): ?>class="active"<? endif; ?> href="<?php print site_url('dashboard/action:my-friends'); ?>">My Friends</a></li>
    <li id="user_side_nav_edit_profile"><a <? if(url_param('action')=='profile'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:profile')); ?>">Edit my profile</a></li>
    <li id="user_side_nav_questions"><a <? if(url_param('action')=='my-questions'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:my-questions')); ?>">My questions</a></li>
    <? $this_user = get_user($user_id); ?>
    <? if(intval($this_user['parent_id']) == 0): ?>
    <li id="user_side_nav_toys"><a <? if(url_param('action')=='toys'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:toys')); ?>">My Toys</a></li>
    <li id="user_side_nav_kid"><a <? if(url_param('action')=='add-kid'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:add-kid')); ?>">Add kid</a></li>
    <li id="user_side_nav_manage_kid"><a <? if(url_param('action')=='manage-kid'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:manage-kid')); ?>">Manage kids</a></li>
    <? endif ?>
    <li id="user_side_nav_payment"><a <? if(url_param('action')=='payment-history'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:payment-history')); ?>">Payment history</a></li>
    <li id="user_side_nav_extend"><a <? if(url_param('action')=='extend-account'): ?>class="active"<? endif; ?> href="<?php print (site_url('dashboard/action:extend-account')); ?>/ns:true">Extend account</a></li>
    <? endif; ?>
  </ul>
  <? if($dashboard_user == $user_id) : ?>
  <?php $more =  $this->core_model->getCustomFields('table_users',$dashboard_user);



$user_info = $more;

?>
  <? $usr_data = get_user($dashboard_user); ?>
  <?  $expires = $usr_data['expires_on'];

  $expires = strtotime($expires);

   $expires2 = strtotime('now');

   

  if($expires < $expires2)  :?>
  <h3 class="user_sidebar_title">Account status</h3>
  <div class="richtext"> <br />
    <span class="red">Your account is expired</span><br />
    <br />
    <a href="<? print site_url('dashboard/action:extend-account/ns:true') ?>" class="mw_btn_x_orange left"><span>Enable Account</span></a> <br />
    <br />
  </div>
  <? else : ?>
  <?  $expires = $usr_data['expires_on'];

  $expires = strtotime($expires);

   $expires2 = strtotime('+15 days');

   

  if($expires < $expires2)  :?>
  <h3 class="user_sidebar_title">Account status</h3>
  <div class="richtext"> <br />
    <span class="red">Your account will expire on <? print ($usr_data['expires_on']) ?></span><br />
    <br />
    <a href="<? print site_url('dashboard/action:extend-account/ns:true') ?>" class="mw_btn_x_orange left"><span>Extend Account</span></a> <br />
    <br />
  </div>
  <? endif; ?>
  <? endif; ?>
  <? endif; ?>
  <h3 class="user_sidebar_title">Information about Me</h3>
  <div class="richtext">
    <p> Name<br />
      <strong><? print user_name($dashboard_user); ?></strong> </p>
    <? if($user_info['bday'] and $user_info['bmonth'] and $user_info['byear']): ?>
    <p> Date of birthday<br />
      <strong><? print $user_info['bday'] ?>/<? print $user_info['bmonth'] ?>/<? print $user_info['byear'] ?></strong> </p>
    <? endif; ?>
    <? if($user_info['country']): ?>
    <p> Country<br />
      <strong><? print $user_info['country'] ?></strong> </p>
    <? endif; ?>
    <? if($user_info['city']): ?>
    <p> City<br />
      <strong><? print $user_info['city'] ?></strong> </p>
    <? endif; ?>
  </div>
  <? if($user_info['about']): ?>
  <h3 class="user_sidebar_title">About me</h3>
  <div class="richtext">
    <p><? print $user_info['about'] ?></p>
  </div>
  <? endif; ?>
  <h3 class="user_sidebar_title">My Friends</h3>
  <div class="friends_side_box">
    <div class="c">&nbsp;</div>
    <microweber module="users/friends" user_id="<? print $dashboard_user ?>" limit="4"></microweber>
    <a href="<?php print site_url('dashboard/action:my-friends'); ?>" class="mw_blue_link"><? print friends_count($dashboard_user); ?> friends</a> <a href="<?php print site_url('dashboard/action:my-friends'); ?>" class="mw_btn_s right"><span>See All</span></a>
    </ul>
  </div>
  
  
  
  
  
  <? include(TEMPLATE_DIR.'banner_dashboard_left_sidebar.php')	; ?> 
</div>
<!-- /.dashboard-sidebar -->
<?php dbg(__FILE__, 1); ?>
