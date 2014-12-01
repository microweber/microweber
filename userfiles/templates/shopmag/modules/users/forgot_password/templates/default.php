<script>mw.moduleCSS("<?php print MW_MODULES_URL; ?>users/users_modules.css")</script>




  <?php if(!isset($show_login_link) or $show_login_link != 'n'): ?>

  <?php endif; ?>
  <div id="form-holder{rand}">
    <h2 class="box-head">
      <?php if(!isset($form_title) or $form_title == false): ?>
      <?php _e("Enter your username or email"); ?>
      <?php else: ?>
      <?php  print $form_title; ?>
      <?php endif; ?>
    </h2>
    <form id="user_forgot_password_form{rand}" method="post" class="clearfix">
        <div class="control-group form-group">

          <input type="text" class="mw-ui-field  mw-ui-field-big w100"  name="username" placeholder="<?php _e("Email or Username"); ?>">
        </div>

      <div class="mw-ui-row vertical-middle captcha-row pull-left">
          <div class="mw-ui-col">
            <div class="mw-captcha-image-holder"> <img class="mw-captcha-img" src="<?php print api_link('captcha') ?>" onclick="mw.tools.refresh_image(this);" /> </div>
          </div>
          <div class="mw-ui-col">
            <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-ui-field mw-captcha-input" name="captcha">
          </div>
          <div class="mw-ui-col"><button type="submit" class="mw-ui-btn mw-ui-btn-invert pull-right"><?php _e('Reset'); ?></button></div>
        </div>

    </form>
    <div class="alert" style="margin: 0;display: none;"></div>
  </div>