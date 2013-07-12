<?php include "head.php"; ?>

<body>

<div id="header">
    <div class="container">
        <div id="get-started-header">
            <a href="javascript:;" id="logo" class="logomark" title="Microweber">Microweber</a>
            <span>Make Web. Easy and Fun!</span>
        </div>
    </div> <!-- /#header > .container -->
</div><!-- /# header -->
<div id="content" class="get-started-content">
    <div class="container narrow">
        <div style="padding: 30px 0 40px;">
            <img src="<?php print TEMPLATE_URL; ?>site/free-arr.png" alt="" class="pull-right" style="margin: 37px 0 0 16px;" />
            <img src="<?php print TEMPLATE_URL; ?>site/free.png" alt="" class="pull-right" />
           <h4 class="nomargin" style="padding-top: 27px;">Great! Few steps to create your website.</h4>
           <h6 class="nomargin">Fill the fild below, define your site name and get started right now</h6>
        </div>
       <form action="javascript:;" method="post" id="get-started">
        <input type="email" required placeholder="Email Address" class="box" tabindex="1" autofocus="true" />
        <input type="password" required placeholder="Password" class="box" tabindex="2" />
        <div class="box">
          <div class="box-content">
              <input type="text" tabindex="3" required class="invisible-field pull-left" id="domain-search-field" placeholder="Site Name" />
              <div data-value=".microweber.com" id="choose_domain" class="mw_dropdown mw_dropdown_type_mw pull-left" tabindex="4">
                  <span class="mw_dropdown_val_holder">
                      <span class="dd_rte_arr"></span>
                      <span class="mw_dropdown_val">.microweber.com - <small>Free</small></span>
                  </span>
                  <div class="mw_dropdown_fields">
                    <ul>
                      <li value=".microweber.com"><a href="javascript:;">.microweber.com -  <small>Free</small></a></li>
                      <li value=".com"><a href="javascript:;">.com - <small>$20</small></a></li>
                      <li value=".net"><a href="javascript:;">.net - <small>$30</small></a></li>
                      <li value=".org"><a href="javascript:;">.org - <small>$40</small></a></li>
                      <li value=".me"><a href="javascript:;">.me - <small>$50</small></a></li>
                    </ul>
                  </div>
              </div>
          </div>
        </div>

       <script type="text/javascript">

        issearching = null;

        $(document).ready(function () {
            $(mwd.getElementById('format_main')).bind("change", function () {
                mw.$("#domain-search-field").trigger("change");
            });
            $(mwd.getElementById('domain-search-field').parentNode).bind("mouseup", function(e){

               if($(e.target).hasClass('box-content')){
                 mwd.getElementById('domain-search-field').focus()
               }
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
       </form>
       <div class="vpad">
           <h4>Thinking for upgrades?</h4>
           <p>With the free plan you have a limited web space on our servers. If you have a business or will upload more content, now is the time to upgrade your plan. It is cheap and you save money!</p>
       </div>
       <div class="box mw-features">
            <table cellpadding="0" cellspacing="0" class="table table-bordered">
              <col width="285">
              <col width="160">
              <col width="160">
              <tr>
                <td>Free Website, Blog or Online Shop </td>
                <td><i class="yes"></i></td>
                <td><i class="yes"></i></td>
              </tr>
              <tr>
                <td>Custom Domain Name </td>
                <td><i class="yes"></i></td>
                <td><i class="no"></i></td>
              </tr>
              <tr>
                <td>Custom Design & Resources</td>
                <td><i class="yes"></i></td>
                <td><i class="no"></i></td>
              </tr>
              <tr>
                <td>10 GB Space </td>
                <td><i class="yes"></i></td>
                <td><i class="no"></i></td>
              </tr>
              <tr>
                <td>No Ads in your site</td>
                <td><i class="yes"></i></td>
                <td><i class="no"></i></td>
              </tr>
              <tr>
                <td>Premium Customer Support</td>
                <td><i class="yes"></i></td>
                <td><i class="no"></i></td>
              </tr>
              <tr>
                <td>Manage Content by Drag & Drop</td>
                <td><i class="YES">YES</i></td>
                <td><i class="YES">YES</i></td>
              </tr>
            </table>
            <table width="100%">
                <col width="445">
                <col width="160">
                <tr>
                    <td><a href="javascript:;"><img src="<?php print TEMPLATE_URL; ?>grow.jpg" alt="" /></a></td>
                    <td>
                         <p style="margin:-10px 0 30px;"><strong>Free</strong></p>
                        <a href="javascript:;" class="fbtn fitem-orange fbtn-medium">Start Free</a>
                    </td>
                </tr>
            </table>
       </div>
    </div>
</div><!-- /# content -->
</body>
</html>