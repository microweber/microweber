<script type="text/javascript">
            $(document).ready(function(){
               $("#ctandc").attr("checked", "");
               $(".sidebar").height($("#content").height());

               $("#logIn").validate();

            });


         </script>
<? //var_dump($form_values); ?>



    <? //require (ACTIVE_TEMPLATE_DIR.'users/right_sidebar_new.php') ?>


<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Register</h1>
  </div>

  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a>

    <!--END HELP-->
  </div>
  <div class="clr"></div>
  <!--END SUBNAV-->
</div>
<div class="pad2">&nbsp;</div>

<div class="main border-top" style="padding-top: 15px">

      <? if($user_registration_done != true) : ?>

<div class="box-holder">
    <div class="box-top"></div>
    <div class="box-inside">
    <form action="<? print site_url('users/user_action:register'); ?>" method="post" id="logIn" class="validate xform">

    <? /*
    <div id="register-msg">
      <h2>Registering you get:</h2>

      <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sollicitudin, dui et malesuada </p>

          <p>Consequat, sapien nisi molestie nunc, at egestas dui nibh et turpis. Duis nec urna dolor, ac aliquam felis. Sed vel sodales odio.</p>

          <p>Ut porta tortor sit amet leo egestas et sagittis tortor vestibulum. Fusce ligula tortor, fermentum at tempus vitae, varius non justo. Donec ut commodo mauris. Suspendisse eget urna ligula. Sed tempor lobortis nisl, a aliquam ligula sollicitudin eget. Nunc accumsan urna ac lectus sodales commodo. Aliquam erat volutpat.</p>

   </div>
    */ ?>



             <? if(!empty($user_register_errors)) : ?>
      <ol class="submit-error-info">
        <? foreach($user_register_errors as $k => $v) :  ?>
        <li><? print $v ?></li>
        <? endforeach; ?>
      </ol>
      <? endif ?>
        <div class="login-item" style="margin-right: 90px;">
          <label>Username: <strong>*</strong></label>
          <span class="field"><input maxlength="13" class="required" name="username" type="text" value="<? print $form_values['username'];  ?>">
          </span>
        </div>
        <div class="login-item">
          <label>Email: <strong>*</strong></label>
          <span class="field"><input class="required-email" name="email" type="text" value="<? print $form_values['email'];  ?>">
          </span>
        </div>
        <div class="login-item" style="margin-right: 90px;">
          <label>Password: <strong>*</strong></label>
          <span class="field"><input class="required" name="password" type="password" value="<? print $form_values['password'];  ?>">
          </span>
        </div>
        <div class="login-item">
          <label>Retype Password: <strong>*</strong></label>

          <span class="field"><input class="required" name="password2" type="password" value="<? print $form_values['password2'];  ?>">
          </span>
        </div>

        <div class="t_and_c">
          <input type="checkbox" class="required" name="tandc" id="ctandc" style="float: left;margin:2px 8px 0 0;" />
          <label class="cetify">
          I certify that I have read and agree to the "RUToolz" <a href="#">Terms of Service</a> <br />and "RUToolz" <a href="#">Privacy Policy</a>.</label>

        </div>
         <div class="c" >&nbsp;</div>
        <a href="javascript:;" class="submit btn" style="margin-top: 20px;">Register</a>
        <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
        <? else: ?>
        <h1 id="success" class="tvtitle tvorange">Registration Successful</h1>
        <p><strong>Thank you for registering.</strong> </p>
        <p> <a style="font:bold 12px Arial;color:#555" href="<? print site_url('users/user_action:login'); ?>">To log in you have to enter your username and password.</a></p>
        <?  endif ; ?>
      </form>


     <div class="sidebar border-top" style="padding-top: 15px">

</div>




    </div>
    <div class="box-bottom"></div>

</div>




</div>




