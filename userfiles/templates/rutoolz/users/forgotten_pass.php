<? //var_dump($form_values); ?>


<div style="float: right;width: 330px;">
  <? require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>
</div>

<div id="home-title" style="padding-bottom: 20px;">
    <h2>Forgotten Password</h2>
</div>






<div class="main border-top" style="padding-top: 15px">

<form action="<? print site_url('users/user_action:forgotten_pass'); ?>" method="post" id="logIn">
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
               <span class="linput">
                    <input class="type-text" name="email" type="text" style="width: 338px" />
               </span>
               <span class="btn submit" style="margin:4px 24px 0 0;">Send</span>
           </div>
        <div class="c" style="padding-bottom: 7px;">&nbsp;</div>


</form>
</div>

<div class="sidebar border-top" style="padding-top: 15px">

    <img src="<? print TEMPLATE_URL; ?>img/login_add.jpg" alt="" />

</div>



