<script  type="text/javascript">
  /* $(document).ready(function () {
		
 $(window).bind('customFieldSaved', function(){

	 
			if(window.parent != undefined && window.parent.mw != undefined){
				 window.parent.mw.reload_module('custom_fields');
			 }
		});
});
*/
 


</script>
<style>
#form_email_options input[type='text'], #form_email_options textarea {
	width: 90%;
	width: calc(100% - 22px);
	width: -webkit-calc(100% - 22px);
}
.mw_tabs_layout_simple .mw_simple_tabs_nav {
	padding-left: 12px;
}
.mw_tabs_layout_simple .mw_simple_tabs_nav li {
	margin:0;
}
</style>

<div class="mw_simple_tabs mw_tabs_layout_simple">   
  <ul class="mw_simple_tabs_nav">
    <li><a href="javascript:;" class="active">Form Fields</a></li>
    <li><a href="javascript:;">Country selector</a></li>
    <li><a href="javascript:;">Options</a></li>
  </ul>
  <div class="tab">
    <label class="mw-ui-label"><small>Form Fields</small></label>
    <module type="custom_fields"  view="admin" data-for="module" data-id="<? print $params['id'] ?>" default-fields="address,phone" />
  </div>
  <div class="tab">
    <? include_once($config['path_to_module'].'admin_backend.php'); ?>

  </div>
  <div class="tab">
    <div class="vSpace"></div>
    <hr>
    <div id="form_email_options">
      <label class="mw-ui-label" style="padding-bottom: 0;"><span class="ico ismall_warn"></span><small>Type your e-mail where you will receive the email from this form</small></label>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Email To</label>
        <input placeholder="Your Email" type="text" />
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">BCC Email To</label>
        <input placeholder="Your Email" type="text" />
      </div>
      <div class="mw-ui-field-holder">
        <label class="mw-ui-label">Autorespond Message</label>
        <textarea></textarea>
        <label class="mw-ui-label"><span class="ico ismall_warn"></span><small>Autorespond e-mail back to the user with the text you write </small></label>
      </div>
    </div>
    <hr>
    <label class="mw-ui-check">
      <input
            type="checkbox"
            name="disable_captcha"
            value="y"
            class="mw_option_field"
            <? if(get_option('disable_captcha', $params['id']) =='y'): ?>   checked="checked"  <? endif; ?>
      />
      <span></span> <span>Disable Code Verification ex.:</span> </label>
    <img src="<?php print INCLUDES_URL; ?>img/code_verification_example.jpg" class="relative" style="top: 7px;left:10px;" alt="" />
    <div class="vSpace"></div>
    <hr>
    <div class="vSpace"></div>
    <button class="mw-ui-btn mw-ui-btn-blue right" style="width: 90px;">Save</button>
    <div class="vSpace"></div>
  </div>
</div>
