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

        <div id="login-form-status"></div>

        <form action="#">


            <div class="liunput">

                <input type="text" autofocus="" placeholder="Username" />
                <i class="box-arr-leftcenter">&#9664;</i>

            </div>
            <div class="liunput">

                <input type="text" placeholder="Password"  />
                <i class="box-arr-leftcenter">&#9664;</i>

            </div>


            <input type="submit" value="Login" />



            <a href="javascript:;" class="forgot-password-link">Forgot Password?</a>



        </form>



      </div>
      <i class="loginico"></i>
      <i class="box-arr-topcenter">&#9650;</i>
      <i class="wlogo"></i>
    </div>
  </div>
</div>

<?php include THIS_TEMPLATE_DIR. "foot.php"; ?>