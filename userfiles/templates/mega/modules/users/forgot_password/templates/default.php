<?php

/*

type: layout

name: Default

description: Default forgot pass template

*/

 ?>


<div class="box-head">
  <h2>
    <a href="javascript:mw.load_module('users/register', '#<?php print $params['id'] ?>', false, {template:'default'});" class="blue"><?php _e("New Registration"); ?></a>
    or
    <a href="javascript:mw.load_module('users/login', '#<?php print $params['id'] ?>', false, {template: 'mega'});" class="blue"><?php _e("Login"); ?></a></h2>
</div>
<hr>
<div id="form-holder{rand}">
<h4><?php _e("Enter your username or email"); ?></h4>
  <form id="user_forgot_password_form{rand}" method="post" class="clearfix">

    <div class="control-group">
      <div class="controls">
        <input type="text" class="box" autofocus=""  name="username" placeholder="<?php _e("Email or Username"); ?>">
      </div>
    </div>


    <div class="control-group">
      <div class="controls">
        <div class="box" >
        <div class="box-content" >

            <img class="mw-captcha-img" src="<?php print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
          <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="invisible-field" name="captcha">
        </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-large pull-right"><?php print $form_btn_title ?></button>
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>



<script> mw.$('[autofocus]').focus();</script>