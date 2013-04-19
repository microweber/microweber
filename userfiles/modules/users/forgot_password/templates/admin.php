
<div class="box-head">
  <h2>Enter your username or email</h2>
</div>
<div id="form-holder{rand}">

  <form id="user_forgot_password_form{rand}" method="post" class="clearfix">
    <div class="mw-ui-field-holder">

        <input type="text" class="mw-ui-field"  name="username" placeholder="Enter Email or Username">

    </div>

    <div class="mw-ui-field-holder">

          <div class="mw-ui-field mw-ico-field" style="width: 312px;">
              <img class="mw-captcha-img" src="<? print site_url('api/captcha') ?>" onclick="mw.tools.refresh_image(this);" />
              <input type="text" placeholder="Enter the text" class="mw-ui-invisible-field" name="captcha">
          </div>
    </div>
    <div class="mw-ui-field-holder">
        <button type="submit" class="mw-ui-btn right"><? print $form_btn_title ?></button>
    </div>

    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
</div>