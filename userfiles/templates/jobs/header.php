<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">
<link href="<? print TEMPLATE_URL ?>css/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<? print TEMPLATE_URL ?>css/styles.css" rel="stylesheet" type="text/css" />
<link href="<? print TEMPLATE_URL ?>css/ooyes.framework.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>/layouts/dashboard/dashboard.css" />
<!--  button scroller files -->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/l10n.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_003.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery_002.js"></script>-->
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery-ui-1.8.18.custom/js/jquery-1.7.1.min"></script>
-->
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/jquery-ui-1.8.18.custom/js/jquery-ui-1.8.18.custom.min.js"></script>
<link rel="stylesheet" href="<? print TEMPLATE_URL ?>js/jquery-ui-1.8.18.custom/css/custom-theme/jquery-ui-1.8.18.custom.css" type="text/css" media="all" />
<!--  button scroller files -->
<script type="text/javascript">
var $ = jQuery.noConflict();
 
</script>
<!--<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/field_label/src/jquery.infieldlabel.js"></script>
-->
<script type="text/javascript" src="<? print site_url('api/js'); ?>"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>css/bootstrap/js/bootstrap.js"></script>
<link href="<? print TEMPLATE_URL ?>css/mw.modules.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main" align="center">
<div class="container">
<div class="header">
  <div class="logo"> <a href="<? print site_url() ?>"> <img src="<? print TEMPLATE_URL ?>images/logo.jpg" alt="logo" /> </a> </div>
  <? $user = user_id(); ?>
  <? if($user > 0): ?>
  <? $u_pic = get_picture($user, $for = 'user'); 
	
	?>
  <? if($u_pic == false){
	$u_pic = TEMPLATE_URL . "images/mystery-man.jpg";
	
	} else {
		
	$u_pic = get_media_thumbnail($u_pic ['id'], $size_width = 32, $size_height = false);	
	
	//p($u_pic_1);
	}?>
  <div class="header_inner_right">
    <div class="job_company_logo">
      <div class="top_jobseeker_name">Josh Mayfid</div>
      <div class="top_jobseeker_icon"><img src="<? print TEMPLATE_URL ?>images/top_jobseeker_icon.jpg" alt="jon seeker" />
        <table border="0" cellpadding="0" cellspacing="0" width="180">
          <tr valign="middle">
            <td><a  style="float:left; margin-right:5px;" href="<? print site_url('dashboard'); ?>"> <img src="<? print $u_pic;  ?>" height="25" /></a></td>
            <td><a class=""  href="<? print site_url('dashboard'); ?>">Dashboard</a> |</td>
            <td><? $new_msgs = get_unread_messages(); ?>
              <? 
	  $msg_class = 'msg-ico-no-new';
	  if($new_msgs > 0): ?>
              <?  $msg_class = 'msg-ico-new'; ?>
              <? endif; ?>
              <a class="<? print $msg_class; ?>" href="<? print site_url('dashboard/view:my-messages'); ?>" title="<? print $new_msgs; ?> messages"></a> |</td>
            <td><a class=""  href="#" onclick="mw.users.LogOut()">Exit</a></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="logout_but"><img src="<? print TEMPLATE_URL ?>images/logoout_but.jpg" alt="logout" /></div>
  </div>
  <? else:  ?>
  <div class="jobsonline">570 JOBS ONLINE</div>
  <div class="caption"><span class="blue"><? print users_count() ?> members</span> of our community <small style="float:right"> <a class="blue" href="<? print page_link_to_layout('register'); ?>">Register</a> or <a class="blue" href="<? print page_link_to_layout('register'); ?>/view:login">Login</a></small> </div>
  <? endif;  ?>
</div>
<div class="nav">
  <microweber module="content/menu"  name="main_menu"  />
  <!--<ul>
				<li ><a href="index.html" class="current">Home</a></li>
				<li ><a href="search_jobs.html" >Search Jobs</a></li>
				<li ><a href="companies.html" >Companies</a></li>
				<li ><a href="job_seakers.html" >Job Seekers</a></li>
				<li ><a href="news.html" >News</a></li>
				<li ><a href="about.html" >About</a></li>
				<li ><a href="contact.html" >Contact</a></li>
			</ul>-->
</div>
