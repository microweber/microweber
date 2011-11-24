<script type="text/javascript">
function quickSearchBox(){
mw.box.element({element:'#quick_search_box', width:660,height:125});
mw.box.overlay("#000");
$("input[name='modal-search-content']").check();

}
</script>

<div id="quick_search_box" style="display:none">
  <?php $temp = $this->core_model->urlConstruct(site_url('browse/view:list'),false );
$kw = $this->core_model->getParamFromURL ( 'keyword' );
if(!$kw){
	$kw = 'Search';
}
?>
  <div id="modal-search-content" class="quickForm">
    <h2 class="title">Seach for content</h2>
    <div class="searchformwrap">
      <form class="tsearch" method="post" action="<?php print $temp  ?>">
        <input name="search_by_keyword_auto_append_params" type="hidden" value="0" />
        <input type="text" class="type-text" name="search_by_keyword" value="<?php print $kw ?>" onfocus="this.value=='Search'?this.value='':''" onblur="this.value==''?this.value='Search':''" />
        <input value="" type="submit" class="type-submit"  />
      </form>
    </div>
  </div>
  <div id="modal-search-users" class="quickForm" style="display: none">
    <h2 class="title">Seach for users</h2>
    <div class="searchformwrap">
      <form action="<?php print site_url('/userbase/users_do_search') ; ?>" method="post" enctype="multipart/form-data" class="xform">
        <div class="tsearch">
          <input value="<?php print $search_by_keyword?>" name="search_by_keyword" type="text" class="type-text" onfocus="this.value=='Search'?this.value='':''" onblur="this.value==''?this.value='Search':''"  />
          <input type="submit" value="" class="type-submit" />
        </div>
      </form>
    </div>
  </div>
  <div id="quick_search_selector">
    <input checked="checked" type="checkbox" name="modal-search-content" />
    <label>Seach for content</label>
    <input type="checkbox" name="modal-search-users" />
    <label>Seach for users</label>
  </div>
</div>
<?php if(strval($user_session['is_logged'] ) == 'yes'):  ?>
<?php $user_data = $this->users_model->getUserById ( $user_session ['user_id'] );   ?>
<?php //   var_dump($user_data); ?>
<div id="user-top">
  <div id="user-top-1">
    <a class="logout" href="<?php print site_url('users/user_action:exit'); ?>">Logout</a>
    <a title="Profile settings" class="title-tip top-settings right" href="<?php print site_url('users/user_action:profile'); ?>"></a>

    Hello, <span id="user-name-top">
    <?php $user_data['first_name']? print $user_data['first_name'] : print $user_data['username'] ;  ?>
    </span>
    <?php $msg_count = $this->users_model->messagesGetUnreadCountForUser($user_session ['user_id']);  ?>
    <?php if($msg_count == 0): ?>
    <a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>" class="inbox title-tip" title="No new messages"><?php print  $msg_count ; ?></a>
    <?php else : ?>
    <a href="<?php print site_url('dashboard/action:messages/show_inbox:1'); ?>" class="inbox hasInbox title-tip" title="You have <?php print  $msg_count ; ?> new messages"><?php print  $msg_count ; ?></a>
    <?php endif;  ?>
    <?php $msg_count = $this->users_model->notificationsGetUnreadCountForUser($user_session ['user_id']);  ?>
    <?php if($msg_count == 0): ?>
    <a href="<?php print site_url('dashboard/action:notifications/unreaded:1'); ?>" class="inbox_notification title-tip" title="No new notifications"><?php print  $msg_count ; ?></a>
    <?php else : ?>
    <a href="<?php print site_url('dashboard/action:notifications'); ?>" class="inbox_notification hasInbox title-tip" title="<?php print  $msg_count ; ?> new notifications"><?php print  $msg_count ; ?></a>
    <?php endif;  ?>
    <br />
  </div>
  <div id="user-top-2">
    <?php $thumb = $this->users_model->getUserThumbnail( $user_data['id'], 32); ?>
    <?php if($thumb != ''): ?>
    <!--     <a href="<?php print site_url('users/user_action:profile'); ?>" class="img"> <span style="background-image:url(<?php print $thumb; ?>)"></span> </a>--> 
    
    <a href="<?php print site_url('dashboard'); ?>" class="img title-tip" title="See the activity of your friends"> <img src="<?php print $thumb; ?>" class="user-image-triger"   height="32" /> 
    <!-- <span style="background-image:url(<?php print $thumb; ?>)"></span>--> 
    
    </a>
    <?php endif; ?>
    <ul id="user-top-nav">
      <li><a title="See the activity of your friends" href="<?php print site_url('dashboard'); ?>" class="first title-tip<?php if(getCurentURL() == site_url('dashboard') ) :  ?> active<?php endif; ?>" >Dashboard</a></li>
      
      <!--          <li> <a href="<?php print site_url('users/user_action:posts'); ?>">All my posts</a> </li>
          <li> <a href="<?php print site_url('users/user_action:post'); ?>">Add post</a> </li>-->
      <li><a title="Preview your profile" class="title-tip<?php if(getCurentURL() == site_url('users/user_action:profile') ) :  ?> active<?php endif; ?>" href="<?php print site_url('userbase/action:profile/username:').$user_data['username']; ?>">Profile</a></li>
      <li><a title="Publish new content" class="Publish title-tip<?php if(getCurentURL() == site_url('users/user_action:post') ) :  ?> active<?php endif; ?>" href="<?php print site_url('users/user_action:post'); ?>">Publish</a></li>
      <li><a title="Search" class="title-tip" href="javascript:quickSearchBox();">Search</a></li>
    </ul>
  </div>
</div>
<?php //var_dump($shipping_service = $this->session->userdata ( 'user' )) ; ?>
<?php else :  ?>
<div id="login-or-register"> <a href="<?php print site_url('users/user_action:register'); ?>" id="top-join-free">Join Free</a> <span>OR</span> <a href="#" id="top-login">Login</a> </div>
<form id="header-login" action="<?php print site_url('users/user_action:login'); ?>" method="post">
  <label>Username</label>
  <span class="linput">
  <input name="username" style="width: 110px;" type="text" value="<?php print $form_values['username'];  ?>" />
  </span>
  <label>Password</label>
  <span class="linput">
  <input name="password" style="width: 110px;" type="password" value="<?php print $form_values['password'];  ?>" />
  </span>
  <input type="submit" class="submit-field" value="" />
  <span id="or-register">or <a href="<?php print site_url('users/user_action:register'); ?>">Register</a></span>
</form>
<!-- <a href="<?php print site_url('users/user_action:login'); ?>" class="<?php if($user_action == 'login') : ?> active<?php endif; ?>">Login</a>
          <a href="<?php print site_url('users/user_action:register'); ?>" class="<?php if($user_action == 'register') : ?> active<?php endif; ?>">Register</a>-->
<?php endif; ?>
