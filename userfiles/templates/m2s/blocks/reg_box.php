<script>
     
     
    function register(){
    $user = jQuery( "#new_user_username" ).val();
    $pass = jQuery( "#new_user_password" ).val();
	
	    $email = jQuery( "#new_user_email" ).val();

	
    $captcha = jQuery( "#new_user_captcha" ).val();
    jQuery.post("<? print site_url('api/user/register') ?>", { username: $user ,email: $email , password: $pass , captcha: $captcha},
    function(resp) {
    if(resp.error != undefined){
    alert("Registration failed! Please check the catcha field.");
    }
    if(resp.success != undefined){
    // window.location.href = '<? print site_url('dashboard') ?>'
    alert("Registration completed. The page will reload now.");
    window.location.reload();
    }
    }, 'json');
    }
     
     
      $(document).ready(function(){
	 $(':input[title]').each(function() {
  var $this = $(this);
  if($this.val() === '') {
    $this.val($this.attr('title'));
  }
  $this.focus(function() {
    if($this.val() === $this.attr('title')) {
      $this.val('');
    }
  });
  $this.blur(function() {
    if($this.val() === '') {
      $this.val($this.attr('title'));
    }
  });
});
	 
	 
	 
      
    });
     
     
     
    </script>

 
 
  
  <div class="reg_form_box">
      <label for="asdasd">Enter the text below</label>

    <input type="text" class="reg_text_Box" id="new_user_username"  title="Username" />
        <label for="asdasd">Enter the text below</label>

    <input type="text" class="reg_text_Box" title="Choose password" id="new_user_password"  />
        <label for="asdasd">Enter the text below</label>

    <input type="text" class="reg_text_Box" title="Your E-mail" id="new_user_email"  />
    <!--	<div class="checkbox"><input type="checkbox" /></div>-->
    <img src="<? print site_url('captcha')?>" id="captcha" /><br/>
    <a href="javascript:document.getElementById('captcha').src='<? print site_url('captcha')?>/'+Math.random(); void(0)"
    id="change-image">Not readable? Change text.</a> <br />
    <label for="new_user_captcha">Enter the text below</label>
    <input type="text" class="reg_text_Box" value="" id="new_user_captcha"  />
    <div class="i_agree">I agree with <span class="giving_orange">the terms</span></div>
    <div class="register_but">
      <input type="image" src="<? print TEMPLATE_URL ?>images/register_but.jpg" onclick="register();" />
    </div>
 
</div>
 