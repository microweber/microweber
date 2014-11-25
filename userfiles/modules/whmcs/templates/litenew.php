<?php

/*

  type: layout

  name: Lite NEW

  description: Lite NEW template for WHMCS
  



*/

?>
<?php  $cur_user = user_id();  ?>




<script type="text/javascript" src="<?php print $config['url_to_module']; ?>lite_and_getstarted.js"></script>
<script type="text/javascript">

 mw.require('tools.js');
 mw.require('forms.js', true);

</script>

	<div id="site-reg-HOME" class="litenew">
	<div id="site-reg-form-holder">
		<form id="user_registration_form" method="post" class="clearfix<?php  if($cur_user != false){ print ' domain_form_logged'; } ?>">
			<div class="rfield rfield<?php  if($cur_user != 99){ print ' select_domain_logged'; } ?>" id="select_domain_field_dropdown">

					<div class="mw-ui-row-nodrop">
    <div class="mw-ui-col"><input type="text" placeholder="Site Name" tabindex="1" class="pull-left invisible-field" autocomplete="off" id="domain-search-field"/></div>
    <div class="mw-ui-col" style="width: 260px;">
        <div data-value=".microweber.com" id="domain_selector" class="mw-dropdown mw_dropdown_type_domain" tabindex="-1">
                            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-info mw-dropdown-val mw-dropdown-button">.microweber.com - <small>Free</small></span>
						<div class="mw-dropdown-content">
							<ul>
								<li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small> </a></li>
								<li value=".com"><a href="javascript:;">.com - <small>$19</small> </a></li>
								<li value=".net"><a href="javascript:;">.net - <small>$19</small> </a></li>
								<li value=".org"><a href="javascript:;">.org - <small>$19</small> </a></li>
                                <li><a href="https://members.microweber.com/cart.php?a=add&pid=10&mwdomain=own"><small>I will use my own domain</small></a></li>
							</ul>
						</div>
					</div>
    </div>
</div>


				<div id="domain-search-ajax-results"><i></i></div>
                <div class="isdomainavailable domainnotavailable">This domain is already registered.</div>
                <div class="isdomainavailable domainavailable">Yeah, this domain is available.</div>
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
			<?php $remote_client_id = mw()->user_manager->session_get('whm_user_id'); ?>

			<?php   mw()->user_manager->session_set('captcha', 'getstarted');   ?>
			<input type="hidden" value="getstarted"  name="captcha">

		   <span class="dlholder right" id="blueBtnLoadingLoginSubmit">	<input type="submit" value="Create a site" class="kbtn kbtn-blue" />    </span>



  <?php  if (user_id() == false or $remote_client_id == false): ?>
        <div class="c"></div>
            <div id="hempas">
                <div class="mw-ui-row">
                    <div class="mw-ui-col" style="width: 50%">
                         <input type="email" name="email" id="the_email" required placeholder="Email Address" class="rfield" tabindex="2" >
                    </div>
                     <div class="mw-ui-col" style="width: 50%">
                        <div class="relative inline-block" id="pass-holder">
            				<input type="text" name="password" id="the_pass" required tabindex="3" class="rfield" placeholder="<?php _e("Password"); ?>">
                            <i class="the-eye the-eye-open" id="set_the_pass" title="Show Password"></i>
                        </div>
                     </div>
                </div>



            </div>
			<?php else: ?>

			<?php endif; ?>


		</form>
		<div class="alert" style="margin: 0;display: none;"></div>
	</div>
	</div>

    

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



</style>


<script>
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
</script> 
