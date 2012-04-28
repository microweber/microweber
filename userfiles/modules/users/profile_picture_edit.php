

 <?  if($user_id == false){

	$user_id = user_id();
}




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" style="background:none">

<head>
<style type="text/css">

body {
    color: #333;
    font: 12px Verdana,Arial,sans-serif;
	background-color:#FFF;
}
a{
  color:#036;
   font: 11px Verdana,Arial,sans-serif;
  text-decoration:none;
}


</style>
<script type="text/javascript">
            eventlistener='';
            imgurl="<?php print TEMPLATE_URL; ?>static/img/";
            template_url="<?php print TEMPLATE_URL; ?>";
            site_url="<?php print site_url(); ?>";



</script>.


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>


<script type="text/javascript" src="<?php print site_url('api/js'); ?>"></script>
 
<?php echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->'; ?>

 
 
</head>

<body>
<? //print $user_id; ?>
<form action="<? print site_url('api/user/save') ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<? print $user_id; ?>" />
<input type="hidden" name="redirect_to" value="<? print url() ?>" />


 <div name="user_image" id="">
              <?php 
			   // $CI = get_instance ();
			  $thumb = get_instance()->users_model->getUserThumbnail( $user_id, 100); ?>
              
         
              <?php if($thumb != ''): ?>
              <img id='user_image' class="the-user-pic" style="float: left;margin: 0 10px 0 0"  src="<?php print $thumb; ?>" />
              <input class="cinput input_Up" id="profile_picture" name="picture_0" style="height:auto" type="file"  onChange="document.forms[0].submit()" />

              <br />

              <? else: ?>
              Upload a picture<br />
              <input class="cinput input_Up" id="profile_picture" name="picture_0" style="height:auto" type="file" />
              <?php endif; ?>

              <a href="#" class="submit btn left" style="margin-top: 10px;">Upload picture</a>
              <input name="upload picture" class="xhidden" style="position: absolute;left: -9999px;top:-9999px;" type="submit" value="upload picture" />
            
               <?php if($thumb != ''): ?>
              <a id='user_image_href' href="javascript:userPictureDelete('<?php echo $user_id ?>')">Delete photo</a>
              <? endif; ?>
            </div>

</form>


</body>

</html>