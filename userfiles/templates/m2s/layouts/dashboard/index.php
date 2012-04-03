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

<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left">
      
       <div class="dash-name-title"> 
       <? print user_name(); ?></div>
      <div class="howit_works_left_img">
   
      <br />

          <? $u_pic = get_picture($user, $for = 'user'); 
	
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
     
      <img src="<? print $u_pic ?>" />
     
     </div>
      <div class="dash-sidebar-nav-container">
        <div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">Profile</div>
          <div class="dash-sidebar-nav">
            <a href="<? print page_url() ?>" <? if($vv == ''): ?> class="current" <? endif ?> >Dashboard</a>
            
          </div>
             <div class="dash-sidebar-nav">
             <a href="<? print page_url(); ?>/view:my-profile" <? if($vv == 'my-profile'): ?> class="current" <? endif; ?> >My profile</a>
 
            
          </div>
        </div>
        
      
        
        
        <div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">My content</div>
          
          
           <?  if(stristr($user_data['email'], 'money2study.com')): ?>
              <div class="dash-sidebar-nav">
          <a href="<? print page_url(); ?>/view:my-posts-news" <? if($vv == 'my-posts-news'): ?> class="current" <? endif; ?>  >My news</a>
          </div>
           
           
           <? endif; ?>
          
          
          
          
          <div class="dash-sidebar-nav">
          <a href="<? print page_url(); ?>/view:my-posts-forum" <? if($vv == 'my-posts-forum'): ?> class="current" <? endif; ?>  >My forum posts</a>
          </div>
            <div class="dash-sidebar-nav">
      <a href="<? print page_url(); ?>/view:my-posts-agony" <? if($vv == 'my-posts-agony'): ?> class="current" <? endif; ?>  >My agony posts</a>
          </div>
            <div class="dash-sidebar-nav">
          <a href="<? print page_url(); ?>/view:my-mentors" <? if($vv == 'my-mentors'): ?> class="current" <? endif; ?>  >Talk with mentor</a>
          </div>
        </div>
        
        
          
        <div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">Friends</div>
          <div class="dash-sidebar-nav">
           <a href="<? print page_url(); ?>/view:my-friends" <? if($vv == 'my-friends'): ?> class="current" <? endif; ?> >My friends</a>
          </div>
            <div class="dash-sidebar-nav">
          <a href="<? print page_url(); ?>/view:find-friends" <? if($vv == 'find-friends'): ?> class="current" <? endif; ?> >Find friends</a>
          </div>
            <div class="dash-sidebar-nav">
           <a href="<? print page_url(); ?>/view:friend-requests" <? if($vv == 'friend-requests'): ?> class="current" <? endif; ?> >Friend requests</a>
          </div>
           <? $new_msgs = get_unread_messages(); ?>
           <div class="dash-sidebar-nav <? if($new_msgs != 0): ?>highlight<? endif; ?>">
           <a href="<? print page_url(); ?>/view:my-messages" <? if($vv == 'my-messages'): ?> class="current" <? endif; ?> >Messages  <? if($new_msgs != 0): ?>
      (<? print $new_msgs; ?> new)
      <? endif; ?></a>
          </div>
        </div>
        
        
        
        <!--<div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">Friends</div>
          <ul class="dash-sidebar-nav">
            <li><a href="<? print page_url(); ?>/view:my-friends" <? if($vv == 'my-friends'): ?> class="current" <? endif; ?> >My friends</a></li>
            <li><a href="<? print page_url(); ?>/view:find-friends" <? if($vv == 'find-friends'): ?> class="current" <? endif; ?> >Find friends</a></li>
            <li><a href="<? print page_url(); ?>/view:friend-requests" <? if($vv == 'friend-requests'): ?> class="current" <? endif; ?> >Friend requests</a></li>
          </ul>
        </div>
        <div class="dash-sidebar-nav-item">
          <div class="dash-sidebar-title">Need help</div>
          <ul class="dash-sidebar-nav">
            <li><a href="<? print page_url(); ?>/view:my-posts-forum" <? if($vv == 'my-posts-forum'): ?> class="current" <? endif; ?>  >Ask in forum</a></li>
            <li><a href="<? print page_url(); ?>/view:my-posts-forum" <? if($vv == 'my-posts-forum'): ?> class="current" <? endif; ?>  >Agony center</a></li>
            <li><a href="<? print page_url(); ?>/view:my-posts-forum" <? if($vv == 'my-posts-forum'): ?> class="current" <? endif; ?>  >Talk with mentor</a></li>
          </ul>
        </div>
              <div class="dash-sidebar-nav-item">
        <div class="sponsored_tit">Money2study.com is sponsored by:</div>
        <div class="sponsor_logo"><img src="<? print TEMPLATE_URL ?>images/sponsor_logo.jpg" alt="sponsor" /></div>
        </div>-->
      </div>
    
    </div>
    <div class="inner_rt">
      <!--      <div class="page_logo"><img src="<? print TEMPLATE_URL ?>images/page_logo.jpg" alt="money study" /></div>
-->
      <? $dashboard_user = user_id_from_url();


 
 $v1 = TEMPLATE_DIR. "layouts".DS."dashboard".DS.$v1;
// p( $v1);
 include( $v1);
?>
    </div>
  </div>
</div>





<? endif; ?>








<? include   TEMPLATE_DIR.  "footer.php"; ?>
