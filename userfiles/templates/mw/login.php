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
<div id="content" style="padding-top: 120px;">
  <div class="container">
    <div class="row">
        <div class="span7">
            <div class="box" id="login-form">
              <div class="box-content">
                <form action="#" method="post" onsubmit="$('.alert').css('opacity',1);return false;">
                    <div id="login-form-status" class="boxtable">
                        <div class="boxcell"><div class="alert alert-info">Please, enter your username and password to login</div></div>
                    </div>
                    <div class="liunput">
                        <input type="text" autofocus="" placeholder="Username" tabindex="1" />
                        <i class="box-arr-leftcenter"><i></i></i>
                    </div>
                    <div class="liunput" style="margin-bottom:25px;">
                        <input type="password" placeholder="Password" tabindex="2"  />
                        <i class="box-arr-leftcenter"><i></i></i>
                    </div>
                    <input type="submit" value="Login" class="semi_hidden" />
                    <span class="loginbtn action-submit" tabindex="3">Login<i class="box-arr-topleft"><i></i></i></span>
                    <div class="forgot-password-holder"><a href="javascript:;" class="btn btn-link forgot-password-link">Forgot Password?</a></div>
                </form>
              </div>
              <i class="loginico"></i>
              <i class="box-arr-topcenter"><i></i></i>
              <i class="wlogo"></i>
            </div>
        </div>
        <div class="span3" id="login-registration-bar">

            <h3>New Registration?</h3>

            <div class="box blue-box">
                <div class="box-content">
                    <h4>It is Free</h4>
                    <a href="javascript:;" class="btn btn1">Yes, Please</a>
                </div>
                <i class="box-arr-topcenter"><i></i></i>
            </div>

        </div>
    </div>




  </div>
</div>

<?php include THIS_TEMPLATE_DIR. "foot.php"; ?>