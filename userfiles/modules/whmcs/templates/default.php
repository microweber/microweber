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


            $(mwd.getElementById('format_main')).bind("change", function () {
                mw.$("#domain-search-field").trigger("change");
            });

            mw.$("#domain-search-field").bind("keydown keyup change", function (e) {

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

                                var tld = $(mwd.getElementById('format_main')).getDropdownValue();

                                mw.$("#domain-search-ajax-results").attr("class", "loading");
                                mw.$("#user_registration_submit").attr("disabled", "true");
                                $("#mw-domain-val").val('');

                                mw.whm.domain_check(val + tld, false, function (data) {
                                    mw.$("#user_registration_submit").removeAttr("disabled");
                                    if (data != null) {
                                        if (data.status == "available") {
                                            $("#mw-domain-val").val(val + tld);
                                            mw.$("#domain-search-ajax-results").attr("class", "yes");
                                        }
                                        else if (data.status == "unavailable") {
                                            mw.$("#domain-search-ajax-results").attr("class", "no");

                                        }
                                        else if (typeof data.status == "undefined") {
                                            mw.$("#domain-search-ajax-results").attr("class", "no");
                                        }

                                    }
                                    else {
                                        mw.$("#domain-search-ajax-results").attr("class", "");
                                    }
                                    issearching = null;

                                });

                            }, 400);
                        }
                        else {
                            mw.$("#domain-search-ajax-results").removeClass("active");
                            issearching = null;
                        }
                    }
                }

            });

        });


    </script>

<div class="box box-gray block" id="domain-search">
  <div class="box-content">
    <div class="row row-fluid">
      <div class="span9 col-md-9 col-sm-9">
        <div class="box block domain-search-form">
          <div class="box-content">
            <form action="#" method="post">
              <input type="text" placeholder="Type domain name" tabindex="1"
                                               class="pull-left invisible-field" autocomplete="off" autofocus=""
                                               id="domain-search-field"/>
              <div data-value=".microweber.com" id="format_main"
                                                 class="mw_dropdown mw_dropdown_type_mw pull-left" tabindex="2"> <span class="mw_dropdown_val_holder"> <span class="mw-dropdown-arrow"></span> <span class="mw_dropdown_val">.microweber.com <small>Free</small></span> </span>
                <div class="mw_dropdown_fields">
                  <ul>
                    <li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small> </a></li>
                    <li value=".com"><a href="javascript:;">.com - <small>$20</small> </a></li>
                    <li value=".net"><a href="javascript:;">.net - <small>$30</small> </a></li>
                    <li value=".org"><a href="javascript:;">.org - <small>$40</small> </a></li>
                    <li value=".org"><a href="javascript:;">.org - <small>987987987987987</small> </a></li>
                  </ul>
                </div>
              </div>
            </form>
          </div>
          <div id="domain-search-ajax-results"></div>
        </div>
      </div>
      <div class="span3 col-sm-3 col-md-3"><a href="javascript:;" class="start inline-block">Get Started Free </a></div>
    </div>
  </div>
  <i class="box-arr-topleft"><i></i></i> </div>
<script type="text/javascript">

                mw.require('forms.js', true);


                $(document).ready(function () {


                    mw.$('#user_registration_form').submit(function () {


                        mw.form.post(mw.$('#user_registration_form'), '<?php print site_url('api') ?>/user_register', function () {


                            mw.response('#form-holder', this);
							 
								mw.reload_module('#mw_client_sites')
							 



                        });

                        return false;


                    });

                });


            </script>
<div id="form-holder">
  <form id="user_registration_form" method="post" class="clearfix">
           <?php print csrf_form(); ?>

    <div class="control-group form-group">
      <div class="controls">
        <input type="text" class="large-field" id="mw-domain-val" name="whm_order_domain">
      </div>
    </div>
    <?php $hosting = whm_get_products(); ?>
    <?php if (!empty($hosting)): ?>
    <?php foreach ($hosting as $item): ?>
    <?php if (isset($item['name']) and isset($item['id'])): ?>
    <div class="control-group form-group">
      <div class="controls">
        <label>
          <input type="radio" name="product_id" value="<?php print $item['id'] ?>">
          <?php print $item['name'] ?> </label>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
    
    <?php $remote_client_id = mw()->user_manager->session_get('whm_user_id');

    if ($remote_client_id == false): ?>

    
    <div class="control-group form-group">
      <div class="controls">
        <input type="text" class="large-field" name="email" placeholder="<?php _e("Email"); ?>">
      </div>
    </div>
    <div class="control-group form-group">
      <div class="controls">
        <input type="password" class="large-field" name="password" placeholder="<?php _e("Password"); ?>">
      </div>
    </div>

     <?php else: ?>
     Welcome, <?php  print user_name(); ?>
     <?php endif; ?>
    
    
    
     <div class="control-group form-group">
      <div class="controls">
        <div class="input-prepend" style="width: 100%;"><span style="width: 100px;background: white"
                                                                                  class="add-on"> <img
                                        class="mw-captcha-img" src="<?php print site_url('api/captcha') ?>"
                                        onclick="mw.tools.refresh_image(this);"/> </span>
          <input type="text" placeholder="<?php _e("Enter the text"); ?>" class="mw-captcha-input"
                                       name="captcha">
        </div>
      </div>
    </div>
    
    
    
    <button id="user_registration_submit" type="submit" class="btn btn-default btn-large pull-right">Get started </button>
    
    
    
    
    <div style="clear: both"></div>
  </form>
  <div class="alert" style="margin: 0;display: none;"></div>
  
     <module type="whmcs" template="client_products" id="mw_client_sites" />
</div>

<script>
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
</script> 
