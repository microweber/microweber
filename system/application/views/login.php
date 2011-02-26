<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<link rel="stylesheet" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>css/style.css" type="text/css" media="screen"  />
<script type="text/javascript" src="<?php print site_url('api/js')  ?>"></script>

<style type="text/css">
#submit{
  position: relative;
  width:88px;
  height:55px;
  float: right;
}
#submit input{
  display: block;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
  opacity:0;
  filter:alpha(opacity=0);
  width:88px;
  height:55px;
  cursor: pointer;
  border: none
}


</style>

<title>Login to Microweber</title>


</head>

<body id="login">

<div class="main-content">



<div class="login">

<!--<h1>Control panel login</h1>-->
<div class="box radius">





<form name="loginform" id="loginform" action="<?php print site_url ('login'); ?>" method="post" class="form col" >

    <h2>Login to Microweber</h2>
    <br />

    <div><label class="llabel">Email or Username:</label>
    <div class="field2">
     <input name="username" type="text" id="username" tabindex="1" value="" size="40" />
    </div>
    </div>




    <div>
        <label class="llabel">Password:</label>
      <div class="field2">
        <input  name="password" type="password" id="password" tabindex="2" value="" size="40" />
      </div>
    </div>



   <div id="submit">
     <input type="submit"  name="submit" id="submit" value="Login" tabindex="3" />

     <a href="#" class="btn" id="login_submit">Login</a>
   </div>





</form>
</div>

</div>

</div>

</body>

</html>