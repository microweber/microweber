<?php

/*

  type: layout

  name: Default

  description: Default template for WHMCS
  



*/

?>
<script type="text/javascript">

        issearching = null;


        $(document).ready(function () {
            PTABS = mw.$("#plans-and-pricing-tabs");
            $(window).bind("scroll resize", function () {

                $(this).scrollTop() > 102 ? PTABS.addClass("fixed12") : PTABS.removeClass("fixed12");

            });


            $(mwd.getElementById('choose_domain')).bind("change", function () {
                mw.$("#domain-search-field").trigger("change");
            });

            mw.$("#domain-search-field").bind("keydown keyup change", function (e) {

                if(this.value == ''){
                  mw.$("#domain-search-ajax-results").attr("class", "");
                  mw.$("#domain-search-ajax-results i").attr("class", "");
                 issearching = null;
                }
                else{
                var w = e.keyCode;

                if (w === 32) {
                    return false;
                }

                if (e.type == 'keyup' || e.type == 'change') {
                    if (w != 32 && !e.ctrlKey) {
                        var val = this.value;
                        var val = val.replace(/[`~!@#$%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                        var val = val.replace(/-+$|(-)+/g, '-');
                        if (val.indexOf("-") == 0) {
                            var val = val.substring(1);
                        }
                        if (val != '') {
                            this.value = val;

                            if (typeof issearching === 'number') {
                                clearTimeout(issearching);
                            }
                            issearching = setTimeout(function () {

                                var tld = $(mwd.getElementById('choose_domain')).getDropdownValue();

                                mw.$("#domain-search-ajax-results").attr("class", "loading");
                                mw.$("#domain-search-ajax-results i").attr("class", "icon-cog icon-spin");

                                $("#mw-domain-val").val('');

                                mw.whm.domain_check(val + tld, false, function (data) {

                                    if (data != null) {
                                        if (data.status == "available") {
                                            $("#mw-domain-val").val(val + tld);
                                            mw.$("#domain-search-ajax-results").attr("class", "yes");
                                            mw.$("#domain-search-ajax-results i").attr("class", "icon-check-sign");
                                        }
                                        else if (data.status == "unavailable") {
                                            mw.$("#domain-search-ajax-results").attr("class", "no");
                                            mw.$("#domain-search-ajax-results i").attr("class", "icon-ban-circle");

                                        }
                                        else if (typeof data.status == "undefined") {
                                            mw.$("#domain-search-ajax-results").attr("class", "no");
                                            mw.$("#domain-search-ajax-results i").attr("class", "icon-ban-circle");
                                        }

                                    }
                                    else {
                                       mw.$("#domain-search-ajax-results").attr("class", "");
                                       mw.$("#domain-search-ajax-results i").attr("class", "");
                                    }
                                    issearching = null;

                                });



                                if(mw.$("#domain-search-field").val() == ''){
                  mw.$("#domain-search-ajax-results").attr("class", "");
                  mw.$("#domain-search-ajax-results i").attr("class", "");
                 issearching = null;
                }


                            }, 400);
                        }
                        else {
                            mw.$("#domain-search-ajax-results").removeClass("active");
                            issearching = null;

                        }
                    }
                }
                }

            });



            mw.$("#plan_chooser").bind("change", function(){

               var val = $(this).getDropdownValue();
               mw.$("#radio_item_"+val)[0].checked = true;
               //mw.$("#the_plan_title strong").html(mw.$("#radio_item_"+val).dataset('name'));

            });

            mw.$(".domain-search-form").bind("click", function(e){
                if(mw.tools.hasClass(e.target, 'box-content') || mw.tools.hasClass(e.target, 'box') ){
                   mw.$("#domain-search-field").focus()
                }
            })


        });




    </script>

<div id="get-started">
	<div id="form-holder">
		<form id="user_registration_form" method="post" class="clearfix">
         <?php print csrf_form(); ?>



        <div class="box block domain-search-form">
		<div class="box-content">
			<input type="text" required placeholder="Choose Site Name" tabindex="1" class="pull-left invisible-field" autocomplete="off" autofocus="" id="domain-search-field"/>
			<div data-value=".microweber.com" id="choose_domain" class="mw_dropdown mw_dropdown_type_mw pull-left" tabindex="-1"> <span class="mw_dropdown_val_holder"> <span class="mw-dropdown-arrow"></span> <span class="mw_dropdown_val">.microweber.com - <small>Free</small></span></span>
				<div class="mw_dropdown_fields">
					<ul>
						<li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small> </a></li>
						<li value=".com"><a href="javascript:;">.com - <small>$19</small> </a></li>
						<li value=".net"><a href="javascript:;">.net - <small>$19</small> </a></li>
						<li value=".org"><a href="javascript:;">.org - <small>$19</small> </a></li>
					</ul>
				</div>
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
               mw.$("#plan_chooser").setDropdownValue(<?php print $item['id']; ?>)
             })

             </script>
             <?php } ?>
			<?php endif; ?>
			<?php endforeach; ?>

			<?php endif; ?>









            <div style="height: 6px;overflow: hidden;clear: both;position: relative"></div>

			<div class="control-group form-group">
				<div class="controls">
					<input type="hidden"  id="mw-domain-val" name="whm_order_domain">
				</div>
			</div>

		
			
			
			<?php $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false): ?>
			<input type="email" name="email" id="the_email" required placeholder="Email Address" class="box" tabindex="2" autofocus="true">

			<div class="relative">


                <input type="text" name="password" id="the_pass" required tabindex="3" class="box" placeholder="<?php _e("Password"); ?>">

                <span class="btn btn-default btn-small" id="set_the_pass"><i class="icon-eye-open"></i>  <span>Show Password</span></span>

            </div>

			<?php else: ?>
			Welcome,
			<?php  print user_name(); ?>!
			<br />
<small>Not you? <a href="<?php print site_url(); ?>api/logout">Logout</a></small>
			
			
			
			<?php endif; ?>
			<?php   mw()->user_manager->session_set('captcha', 'getstarted');   ?>
			<input type="hidden" value="getstarted"  name="captcha">
			<div style="clear: both"></div>
			


			


	
			

			
		</form>
		<div class="alert" style="margin: 0;display: none;"></div>
	</div>

	
	
	

	
	
	
	<script type="text/javascript">
  mw.require('forms.js', true);
</script> 
	<script type="text/javascript">

    $(document).ready(function () {

        var p = mw.$("#the_pass");
        var pk = mw.$("#set_the_pass");

        p.attr("type", "password");

        pk.click(function(){
          if( p.attr("type") == "password"  ){
              p.attr("type", "text");
              $('span', pk).html("Hide Password");
              $('i', pk).attr("class", 'icon-eye-close');
              p.focus()
          }
          else{
             p.attr("type", "password");
             $('span', pk).html("Show Password");
             $('i', pk).attr("class", 'icon-eye-open');
             p.focus()
          }
        });



        mw.$('#user_registration_form').bind("submit",function () {
            mw.utils.stateloading(true, "Creating your website, plase wait ...");

            mw.form.post(mw.$('#user_registration_form'), '<?php print site_url('api') ?>/user_register', function () {
                mw.response('#form-holder', this);


                setTimeout(function(){
                   mw.utils.stateloading(true, "Still Loading Please Wait... ");
                }, 1000);

			  setTimeout(function () {
				// window.location.href = "<?php print site_url('profile') ?>";





                mw.utils.stateloading(true, "Creating your website. Please Wait... 30");




var count=30;

function atimer(){
  count=count-1;
  mw.utils.stateloading(true, "Creating your website. Please Wait... "+count);
  if (count <= 0){
     clearInterval(counter);
     window.location.href = "<?php  $goto = "clientarea.php?action=products"; 	print api_url('panel_user_link') ?>?goto=<?php print urlencode($goto) ?>";
     return;
  }
}
var counter=setInterval(atimer, 1000);













				  },1000);
				  
				  

				/*			 
			 $.get('<?php print site_url('api/whm_get_user_last_unpaid_invoice') ?>', function(data) {
				 
				 
				   mw.utils.stateloading(false);
				 
				 
				 if(data.masspaylink != undefined){
				 	window.location.href = data.masspaylink;

				 } else if(data.paylink != undefined){
				 	window.location.href = data.paylink;

				 } else {
				 setTimeout(function () {
				 window.location.href = "<?php print site_url('profile') ?>";
				 
				  },3000);
				 }
      
 
   			}, 'json');
			 */
			 
				
				
               // window.location.href = mw.$("#mw-domain-val").val() + "/admin";
               /* mw.reload_module('#mw_client_sites', function(){

                    mw.utils.stateloading(false);
                });*/
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


       if(typeof q.validity !== 'undefined'){
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

</style>
</div>

<script>
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
</script> 
