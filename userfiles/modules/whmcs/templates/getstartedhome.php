<?php

/*

  type: layout

  name: getstartedhome

  description: getstartedhome template for WHMCS




*/

?>
<script type="text/javascript" src="<?php print $config['url_to_module']; ?>lite_and_getstarted.js"></script>
<script>
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
</script> 

<div id="site-reg-get-started">
	<div id="site-reg-form-holder">
		<form id="user_registration_form" method="post" class="clearfix">
         <?php print csrf_form(); ?>

        <div class="mw-ui-row">
            <div class="mw-ui-col">
              <div class="rfield" id="select_domain_field_dropdown" style="disp">
					<input type="text" placeholder="Site Name" tabindex="1" class="pull-left invisible-field" autocomplete="off" autofocus="" name="domain" id="domain-search-field"/>
                    <div data-value=".microweber.com" id="domain_selector" class="mw_dropdown mw_dropdown_type_domain mw_dd_blue" tabindex="-1"> <span class="mw_dropdown_val_holder"> <span class="mw-dropdown-arrow"></span> <span class="mw_dropdown_val">.microweber.com - <small>Free</small></span></span>
						<div class="mw_dropdown_fields">
							<ul>
								<li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small> </a></li>
								<li value=".com"><a href="javascript:;">.com - <small>$19</small> </a></li>
								<li value=".net"><a href="javascript:;">.net - <small>$19</small> </a></li>
								<li value=".org"><a href="javascript:;">.org - <small>$19</small> </a></li>
							</ul>
						</div>
					</div>
				<div id="domain-search-ajax-results"><i></i></div>

                <div class="isdomainavailable domainnotavailable">This domain is already registered.</div>
                <div class="isdomainavailable domainavailable">Yeah, this domain is available.</div>

			</div>

            </div>
              <div class="mw-ui-col">
                  <div style="padding-left:12px;"><input type="submit" value="Create a site" class="kbtn kbtn-blue right" /></div>
              </div>

        </div>








			<?php $hosting = whm_get_hosting_products(); ?>
			<?php if (!empty($hosting)): ?>
			<?php foreach ($hosting as $item): ?>
			<?php if (isset($item['name']) and isset($item['id'])): ?>
			<input
                          type="radio"
                          style="display: none"
                          tabindex="-1"
                          name="product_id"
                          <?php if(isset($_REQUEST['plan']) and $_REQUEST['plan'] == $item['id']) : ?>  checked="checked" <?php endif; ?>
                          value="<?php print $item['id'] ?>"
                          id="radio_item_<?php print $item['id']; ?>"
                          data-name="<?php print $item['name']; ?>" />
			<?php if(isset($_REQUEST['plan'])and $_REQUEST['plan'] == $item['id']) { ?>
			<script>
             $(document).ready(function(){
               mw.$("#plan_chooser").setDropdownValue(<?php print $item['id']; ?>);
             });

             </script>
			<?php } ?>
			<?php endif; ?>
			<?php endforeach; ?>
			<?php endif; ?>

			<input type="hidden"  id="mw-domain-val" name="whm_order_domain">
			<?php $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false): ?>
			<input type="email" name="email" id="the_email" required placeholder="Email Address" class="rfield" tabindex="2" autofocus="true">
               <div class="relative" id="the_pass_holder">
				<input type="text" name="password" id="the_pass" required tabindex="3" class="rfield" placeholder="<?php _e("Password"); ?>">


                <i class="the-eye the-eye-open" id="set_the_pass" title="Show Password"></i>
</div>

			<?php else: ?>
		   

			<?php endif; ?>
			<?php   mw()->user_manager->session_set('captcha', 'getstarted');   ?>
			<input type="hidden" value="getstarted"  name="captcha">





           <?php
     $cur_user = user_id();
     if($cur_user == false){


?>

            <a href="<?php print site_url(); ?>register" class="btn btn-link">I want to register just a username </a>
 <?php } ?>

		</form>
		<div class="alert" style="margin: 0;display: none;"></div>
	</div>
	<script type="text/javascript">
  mw.require('forms.js', true);
</script> 
	<script type="text/javascript">

    $(document).ready(function () {



 
        mw.$("#user_registration_submit").click(function(){

            if(!$(this).hasClass("disabled")){
               mw.$('#user_registration_form').trigger("submit")
            }

        });


        var q = mwd.getElementById('domain-search-field'),
            w = mwd.getElementById('the_email'),
            e = mwd.getElementById('the_pass');


       if(q !== null && typeof q.validity !== 'undefined'){
         if(q!==null && w!==null && e!==null){
              $(mwd.body).bind("keyup mousemove", function(){
                  if(q.validity.valid && w.validity.valid && e.validity.valid){
                    mw.$("#user_registration_submit").removeClass("disabled");
                  }
                  else{
                     mw.$("#user_registration_submit").addClass("disabled");
                  }
              });
         }

       }

    });
</script>
	<style>

#plan_chooser{
  margin-left: 20px;
}

#plan_chooser .mw_dropdown_val{
  width: 80px;
  font-weight:bold;
  font-size: 14px;
  color:  #ED5F19
}

#the_plan_title{
  margin-top: 2px;
}

#plan_chooser{

}

#site-reg-get-started #user_registration_form{
  max-width: 800px;
  margin: auto
}

#site-reg-get-started #the_email{
  margin-left: 0;
}
#site-reg-get-started #the_email, #site-reg-get-started #the_pass_holder{
  width: 48%;
}

 #site-reg-get-started #the_pass{
   width: 100%;
   float: right;
   margin: 0;
 }


#site-reg-get-started #select_domain_field_dropdown{
  display:block;
  max-width: 800px;
  margin-bottom: 20px;
}

#site-reg-get-started #the_pass_holder{
  float:right;
  margin-right: 0;
}


#ijwhf{
    position: relative;
    top: 14px;
}

</style>
</div>



