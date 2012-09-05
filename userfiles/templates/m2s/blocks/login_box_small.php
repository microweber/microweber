<? $form_id = "login_form_".md5(url()).rand(); ?>
<? $user = user_id();

 
?>

<div class="login_engine">
  <? if($user > 0): ?>
  
 <div class="box3_logged">
      <div class="box3_tit">Welcome</div>
      
      
          <microweber module="content/menu"  name="main_menu"  />

      <!--
                  <a href="<? print site_url('shopping-center') ?>" class="about_link">Shopping centre</a> 


            <a href="<? print site_url('money-2-study') ?>" class="about_link">Money 2 Study</a> 
 
      <a href="<? print site_url('forum') ?>" class="about_link">Talk in the forums</a> 
      
            <a href="<? print site_url('dashboard') ?>" class="about_link">Dashboard</a> 

      <a href="<? print site_url('agony-corner') ?>" class="about_link">Agony Corner</a> 
      
       <a href="#"   class="about_link" onclick="mw.users.LogOut()">Log Out</a>
       -->
       
       </div>
      
      
      
      
 
  
  
 
 
  <? else:  ?>
  <script type="text/javascript">
 


function mw_process_login(resp){
 
	if(resp.error != undefined){
	 alert(resp.error.login_error);
		}

		if(resp.success != undefined){
	 window.location.href = '<? print site_url('dashboard') ?>'
		//window.location.reload();
		}


}


 


</script>
  <? /*
<a href="<? print site_url('fb_login'); ?>">Login with Facebook</a>
*/ ?>
   <form   method="post" id="<? print $form_id ?>" class="logn_small_boxc"  >
 
    <div class="loginbox">
      <div class="login_tit">PLEASE LOG IN</div>
     
      <input type="text" class="login_box" name="username" />
      <input   class="login_box" name="password" type="password"  style="margin-top:11px;"/>
      <div class="rememberbox">
       <!-- <input type="radio" class="radio_but"/>-->
        <div class="remember_text"><a href="<? print site_url('register'); ?>/view:forgot-pass" class="orange">Forgot your Password?</a> </div>
      </div>
       <a href="<? print site_url('register'); ?>" id="new_reg" style="float:left">Register</a>
      <div class="signin_basdasdut" style="float:right; margin-right:45px;;" >
      <a href="javascript:mw.users.login('#<? print $form_id ?>', mw_process_login);">
        <img src="<? print TEMPLATE_URL ?>images/signin_but.png" alt="sing in" /></a></div>
    </div>
  </form>
 
  <? endif;  ?>
</div>
