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
<script type="text/javascript"> 
 

$(document).ready(function(){
	 refresh_user_picture_info();
	 //jcrop_init();
});

 

    function refresh_user_picture_info(callback){


		$.ajax({
		  url: '<?php print site_url('api/media/user_get_picture_info') ?>/rand:'+Math.random(),
		  dataType: 'json',
		 // data: myData,
		  success: function(data) {
    cropimg = new Image();
    cropimg.className = 'abshidden';
    cropimg.onload = function(){  //pod ie dava greshni razmeri
  			$('#the-user-pic-for-crop').attr('src',  data.urls.original+'?rand'+Math.random());

  			$('.user-image-triger').attr('src',  data.urls.original+'?rand'+Math.random());

  			$('#user_picture_media_id').val(data.id);

  			$('#user_image').attr('src', data.urls.original+'?rand'+Math.random() );
            if(typeof callback =='function'){
                   callback.call(this);
            }

    }
    cropimg.src = data.urls.original;
 		  }
		});




	}





	function change_pass_show(){

	$('#change_pass_holder input').val("");
	$('#change_pass_holder').fadeIn();


$('.change_pass_holder input').val("");
	$('.change_pass_holder').fadeIn();
	}

	function save_user(){


//var data = $('#edit-profile-form').dataCollect();
var data =  $('#edit-profile-form').serialize(); // serialize the form's data
$.post("<? print site_url('api/user/save') ?>", data, function(resp){

var resp_msg = '';

if(isobj(resp.error) != false){
jQuery.each(resp.error, function(i, val) {

	  resp_msg = resp_msg + '<br />' + val;
    });

mw.box.alert(resp_msg);
	}



if(isobj(resp.success) != false){

  $("#user_save_success").fadeIn();
   $("#edit-profile-form").fadeOut();
 
}
 

}, "json");





	}



    </script>
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
      <div class="top_jobseeker_name"><? print user_name() ?></div>
      <div class="top_jobseeker_icon"><a href="<? print site_url('dashboard'); ?>"><img src="<? print $u_pic ?>" alt="<? print addslashes(user_name() ); ?>" /></a>
        <table border="0" cellpadding="0" cellspacing="0"  >
          <tr valign="middle">
           <td><a class=""  href="<? print site_url('dashboard'); ?>">Dashboard</a> |</td>
           
            <td><a class=""  href="<? print site_url('dashboard/view:my-profile'); ?>">Profile</a></td>
          </tr>
        </table>
      </div>
    </div> 
    <div class="logout_but"><a href="javascript:mw.users.LogOut()"><img src="<? print TEMPLATE_URL ?>images/logoout_but.jpg" alt="logout" /></a></div>
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
