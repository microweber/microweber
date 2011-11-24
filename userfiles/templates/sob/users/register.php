<script type="text/javascript">
            $(document).ready(function(){
               $("#ctandc").attr("checked", "");
               $(".sidebar").height($("#content").height())
            });


         </script>
<?php //var_dump($form_values); ?>



    <?php //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar_new.php') ?>





<div class="main">

<div class="border-bottom" id="top-inner-title" style="margin-bottom: 12px;">

   <h2>Register</h2>

</div>
      <?php if($user_registration_done != true) : ?>

      <form action="<?php print site_url('users/user_action:register'); ?>" method="post" id="logIn" class="validate xform">

             <?php if(!empty($user_register_errors)) : ?>
      <ol class="submit-error-info">
        <?php foreach($user_register_errors as $k => $v) :  ?>
        <li><?php print $v ?></li>
        <?php endforeach; ?>
      </ol>
      <?php endif ?>
        <div class="login-item" style="margin-right: 90px;">
          <label>Username: <strong>*</strong></label>
          <span class="linput"><input tabindex="1" maxlength="13" class="required" name="username" type="text" value="<?php print $form_values['username'];  ?>">
          </span>
        </div>
        <div class="login-item">
          <label>Email: <strong>*</strong></label>
          <span class="linput"><input tabindex="2" class="required-email" name="email" type="text" value="<?php print $form_values['email'];  ?>">
          </span>
        </div>
        <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
        <div class="login-item" style="margin-right: 90px;">
          <label>Password: <strong>*</strong></label>
          <span class="linput"><input tabindex="3" class="required" name="password" type="password" value="<?php print $form_values['password'];  ?>">
          </span>
        </div>
        <div class="login-item">
          <label>Retype Password: <strong>*</strong></label>
          <span class="linput"><input tabindex="4" class="required" name="password2" type="password" value="<?php print $form_values['password2'];  ?>">
          </span>
        </div>
        <div class="c" style="padding-bottom: 25px;">&nbsp;</div>
        <div class="t_and_c">
          <input type="checkbox" class="required" name="tandc" id="ctandc" style="float: left;margin:2px 8px 0 0;" />
          <label class="cetify">
          I certify that I have read and agree to the "SOB" <a href="<?php print $this->content_model->getContentURLByIdAndCache(121); ?>">Terms and conditions</a> <br />and "SOB" <a href="<?php print $this->content_model->getContentURLByIdAndCache(122); ?>">Privacy Policy</a>.</label>

        </div>
        <input type="submit" value="" class="hidden" />
        <a href="javascript:;" tabindex="5" class="submit btn">Register</a>
        <?php else: ?>
        <h1 id="success" class="content-title">Registration Successful</h1>
        <div class="pad">
            <p><strong>Thank you for registering.</strong> </p>
             <br />
            <p> <a style="font:bold 12px Arial;color:#555;border-bottom: 1px dotted #555;" href="<?php print site_url('users/user_action:login'); ?>">To log in you have to enter your username and password.</a></p>
        </div>

        <?php endif ; ?>
      </form>


</div>

<div class="sidebar">
    <div id="register-msg">
      <h2>Registering you get:</h2>

      <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sollicitudin, dui et malesuada </p>

          <p>Consequat, sapien nisi molestie nunc, at egestas dui nibh et turpis. Duis nec urna dolor, ac aliquam felis. Sed vel sodales odio.</p>

          <p>Ut porta tortor sit amet leo egestas et sagittis tortor vestibulum. Fusce ligula tortor, fermentum at tempus vitae, varius non justo. Donec ut commodo mauris. Suspendisse eget urna ligula. Sed tempor lobortis nisl, a aliquam ligula sollicitudin eget. Nunc accumsan urna ac lectus sodales commodo. Aliquam erat volutpat.</p>

   </div>
</div>


