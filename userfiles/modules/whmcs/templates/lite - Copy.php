<?php

/*

  type: layout

  name: Lite

  description: Lite template for WHMCS
  



*/

?>
<script type="text/javascript" src="<?php print $config['url_to_module']; ?>lite_and_getstarted.js"></script>


	<div id="site-reg-HOME">
	<div id="site-reg-form-holder">
		<form id="user_registration_form" method="post" class="clearfix">
			<div class="rfield rfield" id="select_domain_field_dropdown">
					<input type="text" placeholder="Site Name" tabindex="1" class="pull-left invisible-field" autocomplete="off" autofocus="" id="domain-search-field"/>
                    <div data-value=".microweber.com" id="domain_selector" class="mw-dropdown mw-dropdown_type_domain mw_dd_blue" tabindex="-1"> <span class="mw-dropdown-value"> <span class="mw-dropdown-arrow"></span> <span class="mw-dropdown-val">.microweber.com - <small>Free</small></span></span>
						<div class="mw-dropdown-content">
							<ul>
								<li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small> </a></li>
								<li value=".com"><a href="javascript:;">.com - <small>$19</small> </a></li>
								<li value=".net"><a href="javascript:;">.net - <small>$19</small> </a></li>
								<li value=".org"><a href="javascript:;">.org - <small>$19</small> </a></li>
							</ul>
						</div>
					</div>
				<div id="domain-search-ajax-results"><i></i></div>
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
        	<div class="relative inline-block">
				<input type="text" name="password" id="the_pass" required tabindex="3" class="rfield" placeholder="<?php _e("Password"); ?>">


                <i class="the-eye the-eye-open" id="set_the_pass" title="Show Password"></i>

            </div>
			<?php else: ?>
			Welcome,
			<?php  print user_name(); ?>
			! <br />
			<small>Not you? <a href="<?php print site_url(); ?>api/logout">Logout</a></small>
			<?php endif; ?>
			<?php   mw()->user_manager->session_set('captcha', 'getstarted');   ?>
			<input type="hidden" value="getstarted"  name="captcha">

			<input type="submit" value="Create site" class="kbtn kbtn-blue" />
		</form>
		<div class="alert" style="margin: 0;display: none;"></div>
	</div>
	</div>
	
    
    
    
    
    
    
    <script type="text/javascript">
	   mw.require('tools.js');
  mw.require('forms.js', true);
 
</script> 
    
    
    
    
	<script type="text/javascript">

    $(document).ready(function () {





        mw.$('#user_registration_form').bind("submit",function () {
           // mw.utils.stateloading(true, "Creating your website, plase wait ...");

            mw.form.post(mw.$('#user_registration_form'), '<?php print site_url('api') ?>/user_register', function () {
                mw.response('#site-reg-form-holder', this);
					var redir_base = "<?php print api_url('panel_user_link') ?>"
					var redir = redir_base + "?goto=<?php print urlencode('clientarea.php?action=products') ?>";
					
					if(this.invoiceid != undefined){
						
					var intVal = parseInt(this.invoiceid);
						if(intVal > 0){
							
							var redir = redir_base + "?goto=<?php print urlencode('creditcard.php?invoiceid=') ?>"+intVal;

							 
						}
					 
					}
					
					
					
					
					
					
					
					
					window.location.href = redir;

              

		 
			 
            });
            return false;
        });

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

#plan_chooser .mw-dropdown-val{
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
