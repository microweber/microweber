<?php

/*

type: layout

name: Users layout

description: Users layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $view = url_param('view'); ?>

<? include TEMPLATE_DIR. "sidebar.php"; ?>
<div id="main">
  <?  if(user_id()  != false):  ?>
  <table width="100%" border="0" cellspacing="1" cellpadding="1">
    <tr>
      <td><h2>Profile</h2></td>
      <td><a href="#" onclick="mw.users.LogOut()">Logout</a></td>
    </tr>
    <tr>
      <td colspan="2"><?  if($view == 'forgot_password'):  ?>
        <? // include  "forgot_password.php"; ?>
        <?  elseif($view == 'post'):  ?>
        <? //include  "post.php"; ?>
        <? else: ?>
        <mw module="users/profile" />
        <? endif; ?></td>
    </tr>
  </table>
  <? else: ?>
  <mw module="users/login" />
  <? endif; ?>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
