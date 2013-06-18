<?php

/*

  type: layout
  content_type: static
  name: Login
  description: Login layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content" style="padding-top: 0px;">
      <div id="plans-and-pricing-tabs-holder">
        <div class="plan-tabs" id="plans-and-pricing-tabs">

                <a href="#personal" class="pbtn active">Personal</a>
                <a href="#business" class="pbtn">Business</a>

            </div>
        </div>


        <script type="text/javascript">
            mw.ui.btn.radionav(mwd.getElementById('plans-and-pricing-tabs'), '.pbtn');


            $(window).load(function(){
                var hash = window.location.hash;
                if(hash == '#business'){

                }
                else {

                }

            });


            issearching = null;


            $(document).ready(function(){


             mw.$(".pbtn").mouseup(function(){
               if(!$(this).hasClass("active")){
                     var i = mw.tools.index(this);
                     mw.$(".ptab.active").removeClass("active");
                     mw.$('.ptab').eq(i).addClass("active");
               }
             });



                PTABS = mw.$("#plans-and-pricing-tabs");
                $(window).bind("scroll resize", function(){

                    $(this).scrollTop() > 102 ? PTABS.addClass("fixed12") : PTABS.removeClass("fixed12");

                });


                $(mwd.getElementById('format_main')).bind("change", function(){
                    mw.$("#domain-search-field").trigger("change");
                });

                mw.$("#domain-search-field").bind("keydown keyup change", function(e){

                    var w = e.keyCode;

                    if(w===32){ return false; }

                    if(e.type == 'keyup' || e.type == 'change'){
                          if(w!=32 && !e.ctrlKey){
                               var val = this.value;
                               var val = val.replace(/[`~!@#$№§%^&*()_|+\=?;:'",.<>\{\}\[\]\\\/]/gi, '');
                               var val = val.replace(/-+$|(-)+/g, '-');
                               if(val.indexOf("-")==0){
                                 var val = val.substring(1);
                               }


                               if(val != ''){
                                   this.value = val;

                                   if(typeof issearching  === 'number'){
                                     clearTimeout(issearching);
                                   }
                                     issearching = setTimeout(function(){

                                         var tld = $(mwd.getElementById('format_main')).getDropdownValue();

                                         mw.$("#domain-search-ajax-results").attr("class", "loading");

                                         var val = mw.$("#domain-search-field").val();
                                         if(val.substring(val.length - 1) == '-'){
                                           var val = val.substring(0, val.length - 1);
                                           mw.$("#domain-search-field").val(val);
                                         }

                                         mw.whm.domain_check(val+tld, false, function(data){
                                             if(data != null){
                                                if(data.status == "available"){
                                                    mw.$("#domain-search-ajax-results").attr("class", "yes");
                                                }
                                                else if(data.status == "unavailable"){
                                                    mw.$("#domain-search-ajax-results").attr("class", "no");

                                                }
                                                else if(typeof data.status == "undefined"){
                                                    mw.$("#domain-search-ajax-results").attr("class", "no");
                                                }

                                             }
                                             else{
                                                 mw.$("#domain-search-ajax-results").attr("class", "");
                                             }
                                             issearching = null;

                                         });

                                     }, 400);
                               }
                               else{
                                    mw.$("#domain-search-ajax-results").removeClass("active");
                                    issearching = null;
                               }
                          }
                    }

                });

            });





        </script>


  <div class="container">





       <div id="pricing-tabs-content" class="ptab active">
            <img
                  class="plan-image"
                  src="{TEMPLATE_URL}img/personal-plans.jpg"
                  alt="Personal plans - Get everything in one place" />

            <div class="vspace"></div>
            <div class="row">
            <div class="span5">
            <div class="features-list">
                <h2 class="blue">With personal plans you get</h2>
                <ul class="plan-features">
                    <li class="plan-features-media"><a href="javascript:;"><i></i>Unlimited Videos, Audios and many more ...</a></li>
                    <li class="plan-features-images"><a href="javascript:;"><i></i>Add Images & Galleries many as you want</a></li>
                    <li class="plan-features-website"><a href="javascript:;"><i></i>You have own Website, Blog & Online Shop</a></li>
                    <li class="plan-features-mobile"><a href="javascript:;"><i></i>100% Mobile Website, Blog & Online Shop</a></li>
                    <li class="plan-features-domain"><a href="javascript:;"><i></i>Custom Domain & Support</a></li>
                </ul>
            </div>
            </div>
            <div class="span7">
           <div class="personal-plans">

               <div class="plan-micro">
                   <h2 class="blue plan-title"><small>$</small> 8 <small>/ MO</small></h2>
                   <div class="box box-blue">
                       <div class="box-content">
                          <h3>Micro</h3>
                          <ul class="plan-features-list">
                            <li>200 GB Bandwidth</li>
                            <li>500 MB Storage</li>
                          </ul>
                          <a href="javascript:;" class="start start-medium">Get it</a>
                       </div>
                       <i class="box-arr-topleft"><i></i></i>
                   </div>
               </div>
               <div class="plan-weber">
                    <h2 class="blue plan-title"><small>$</small> 19 <small>/ MO</small></h2>
                    <div class="box box-blue-border">
                       <div class="box-content">
                        <h3>Weber</h3>
                        <h4 class="hnd orange">Bestseller</h4>
                        <ul class="plan-features-list">
                          <li>1 TB Bandwidth</li>
                          <li>500 MB Storage</li>
                        </ul>
                       <a href="javascript:;" class="start start-medium">Get it</a>
                       </div>
                       <i class="box-arr-topcenter"><i></i></i>
                    </div>
               </div>
               <div class="plan-business">
                   <h2 class="blue plan-title"><small>$</small> 25 <small>/ MO</small></h2>
                   <div class="box box-blue">
                     <div class="box-content">
                      <h3>Business</h3>
                       <ul class="plan-features-list">
                        <li>200 GB Bandwidth</li>
                        <li>500 MB Storage</li>
                      </ul>
                      <a href="javascript:;" class="start start-medium">Get it</a>
                     </div>
                     <i class="box-arr-topright"><i></i></i>
                 </div>
               </div>
            </div>
            </div>
           </div>
       </div>

       <div id="ptab-business" class="ptab">


       PRO


       </div>



       <div id="starttitle" class="row">

           <div class="span3"><h2 class="orange">Want to try it first?</h2></div>

           <div class="span6"><p id="starttitle-info">Sure, choose your domain and get started right now!</p></div>

           <div class="span3"><img src="{TEMPLATE_URL}img/getit.png" alt="" /></div>

       </div>

       <div class="box box-gray block" id="domain-search">
        <div class="box-content">

              <div class="row-fluid">




                  <div class="span9">
                  <div class="box block domain-search-form">
                    <div class="box-content">
                      <form action="#" method="post">
                          <input type="text" placeholder="Type domain name" tabindex="1" class="pull-left invisible-field" autocomplete="off" autofocus="" id="domain-search-field" />

                          <div data-value=".microweber.com" id="format_main" class="mw_dropdown mw_dropdown_type_mw pull-left" tabindex="2" >
                              <span class="mw_dropdown_val_holder">
                                  <span class="dd_rte_arr"></span>
                                  <span class="mw_dropdown_val">.microweber.com <small>Free</small></span>
                              </span>
                            <div class="mw_dropdown_fields">
                              <ul>
                                <li value=".microweber.com"><a href="javascript:;">.microweber.com - <small>Free</small></a></li>
                                <li value=".com"><a href="javascript:;">.com - <small>$20</small></a></li>
                                <li value=".net"><a href="javascript:;">.net - <small>$30</small></a></li>
                                <li value=".org"><a href="javascript:;">.org - <small>$40</small></a></li>
                              </ul>
                            </div>
                          </div>
                      </form>
                    </div>

                    <div id="domain-search-ajax-results"></div>


                  </div>
                  </div>

                  <div class="span3"><a tabindex="3" href="javascript:;" class="start inline-block">Get Started Free </a></div>


              </div>

        </div>
        <i class="box-arr-topleft"><i></i></i>
       </div>

       <div class="vspace"></div>


       <div class="also-faq">
       <h2 class="text-center blue">You may want to know?</h2>
       <div class="row">
           <div class="span6">
            <h3>HOW LONG ARE YOUR CONTRACTS?</h3>
            <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
           <div class="span6">
             <h3>WHAT KIND OF WEBSITES I CAN MAKE?</h3>
             <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
       </div>
       <div class="row">
           <div class="span6">
            <h3>CAN I DOWNLOAD MICROWEBER?</h3>
            <p>Sure. MW is a fully-managed web service. We do not have plans to make a downloadable version. Squarespace does provide many standard methods for exporting your data.</p>
           </div>
           <div class="span6">
             <h3>DO I HAVE A ONLINE SHOP INCLUDED?</h3>
             <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
       </div>
       <div class="row">
           <div class="span6">
            <h3>DO YOU OFFER EMAIL ACCOUNTS?</h3>
            <p>While Squarespace does not provide email accounts, you can easily link your domain to Google Apps and get email.</p>
           </div>
           <div class="span6">
             <h3>Which payment methods can i accept?</h3>
             <p>Sure. MW is a fully-managed web service. We do not have plans to make a downloadable version. Squarespace does provide many standard methods for exporting your data. </p>
           </div>
       </div>
       <div class="row">
           <div class="span6">
            <h3>DO I NEED ANOTHER WEB HOST?</h3>
            <p>No. All Squarespace plans include our fully-managed cloud hosting, ensuring your website remains available at all times.</p>
           </div>
           <div class="span6">
             <h3>CAN I GET MOBILE VERSION OF MY SITE?</h3>
             <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
       </div>
       <div class="row">
           <div class="span6">
            <h3>DO I HAVE SUPPORT?</h3>
            <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
           <div class="span6">
             <h3>Do you offer a free PLAN?</h3>
             <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
       </div>
       <div class="row">
           <div class="span6">
            <h3>Can I use my own domain name?</h3>
            <p>Our plans are month-to-month, yearly, or two years. We make it simple to start — and stop — your service at any time.</p>
           </div>
           <div class="span6">
             <h3>DO I NEED ANOTHER WEB HOST?</h3>
             <p>No. All Squarespace plans include our fully-managed cloud hosting, ensuring your website remains available at all times.</p>
           </div>
       </div>

      </div>

  </div>
</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>