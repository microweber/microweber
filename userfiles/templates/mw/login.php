<?php

/*

  type: layout
  content_type: static
  name: Login
  description: Login layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "head.php"; ?>
<script>mwd.body.className+=' blue';</script>
<div id="content">
  <div class="container">

    <div class="box" id="login-form">
      <div class="box-content">



        <form action="#" method="post" onsubmit="$('.alert').css('opacity',1);return false;">
            <div id="login-form-status" class="boxtable">
                <div class="boxcell"><div class="alert alert-info">Please, enter your username and password to login</div></div>
            </div>
            <div class="liunput">
                <input type="text" autofocus="" placeholder="Username" />
                <i class="box-arr-leftcenter"><i></i></i>
            </div>
            <div class="liunput" style="margin-bottom:25px;">
                <input type="text" placeholder="Password"  />
                <i class="box-arr-leftcenter"><i></i></i>
            </div>
            <input type="submit" value="Login" class="semi_hidden" />
            <span class="loginbtn action-submit">Login<i class="box-arr-top-left"><i></i></i></span>
            <a href="javascript:;" class="btn btn-link forgot-password-link">Forgot Password?</a>
        </form>



      </div>
      <i class="loginico"></i>
      <i class="box-arr-topcenter"><i></i></i>
      <i class="wlogo"></i>
    </div>
  </div>
</div>

<?php include THIS_TEMPLATE_DIR. "foot.php"; ?>