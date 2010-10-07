<?php //var_dump($form_values); ?>


<?php /*
<div style="float: right;width: 330px;">
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>
</div>
*/ ?>








<div class="main border-top" style="padding-top: 15px;height: 390px;">

<div style="margin-bottom: 12px;" id="top-inner-title" class="border-bottom">

   <h2>Forgotten Password</h2>

</div>

<form action="<?php print site_url('users/user_action:forgotten_pass'); ?>" method="post" id="logIn">
<?php
	if($error):
?>
<div style="color:red"><?php print $error;?></div>
<?php endif;?>
<?php 
	if($ok):
?>
<div style="color:blue"><?php print $ok;?></div>
<?php endif;?>
       <div class="login-item" style="width:450px">
               <label>Please, enter your email and we will send you your password: <strong>*</strong></label>
               <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
               <span class="linput">
                    <input class="type-text" name="email" type="text" style="width: 338px" />
               </span>
               <span class="btn submit" style="margin:4px 34px 0 0;">Send</span>
           </div>
        <div class="c" style="padding-bottom: 7px;">&nbsp;</div>


</form>
</div>

<div class="sidebar border-top" style="padding-top: 15px">

    <img src="<?php print TEMPLATE_URL; ?>img/login_add.jpg" alt="" />

</div>



