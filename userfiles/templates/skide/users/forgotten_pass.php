<?php //var_dump($form_values); ?>


<?php /*
<div style="float: right;width: 330px;">
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_people_online.php') ?>
</div>
*/ ?>




 <div class="wrap">
    <div id="main_content">






   <h2>Forgotten Password</h2>
    <br />
 <script>
 $(document).ready(function(){
 $("#logIn").validate();
 });

 </script>

<form action="<?php print site_url('users/user_action:forgotten_pass'); ?>" method="post" id="logIn" class="form">

<div class="bluebox">
<div class="blueboxcontent">


<?php
	if($error):
?>

<script>
    $(document).ready(function(){
        mw.box.alert("<h3 class='red'><?php print $error; ?></h3>");
    });
    </script>
<?php endif;?>
<?php
	if($ok):
?>

<script>
    $(document).ready(function(){
        mw.box.alert("<h3 class='green'><?php print $ok; ?></h3>");
    });
    </script>
<?php endif;?>
       <div class="login-item" style="width:450px">
               <label>Please, enter your email and we will send you your password: <strong>*</strong></label>
               <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
               <span class="linput">
                    <input class="required-email" name="email" type="text" style="width: 338px" />
               </span>
               <input type="submit" class="xhidden" />
               <a href="#" class="btn submit" style="margin:4px 34px 0 0;">Send</a>
           </div>

</div>
</div>
        <div class="c" style="padding-bottom: 7px;">&nbsp;</div>




</form>

      </div>
 </div>



