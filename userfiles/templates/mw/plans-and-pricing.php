<?php

/*

  type: layout
  content_type: static
  name: Login
  description: Login layout

*/

?>

<?php include THIS_TEMPLATE_DIR. "header.php"; ?>

<div id="content" style="padding-top: 120px;">
  <div class="container">



        <div class="plan-tabs" id="plans-and-pricing-tabs">

            <a href="javascript:;" class="pbtn active">Personal</a>
            <a href="javascript:;" class="pbtn">Business</a>

        </div>

        <script type="text/javascript">
            mw.ui.btn.radionav(mwd.getElementById('plans-and-pricing-tabs'), '.pbtn');
        </script>

       <div id="pricing-tabs-content">
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
                          <input type="text" placeholder="Type domain name" tabindex="1" class="pull-left invisible-field" autocomplete="off " autofocus="" />

                          <div title="" id="format_main" class="mw_dropdown mw_dropdown_type_mw pull-left" tabindex="2" >
                              <span class="mw_dropdown_val_holder">
                                  <span class="dd_rte_arr"></span>
                                  <span class="mw_dropdown_val">.microweber.com <small>Free</small></span>
                              </span>
                            <div class="mw_dropdown_fields">
                              <ul>
                                <li value="subdomain"><a href="javascript:;">.microweber.com - <small>Free</small></a></li>
                                <li value="com"><a href="javascript:;">.com - <small>$20</small></a></li>
                                <li value="net"><a href="javascript:;">.net - <small>$30</small></a></li>
                                <li value="org"><a href="javascript:;">.org - <small>$40</small></a></li>
                              </ul>
                            </div>
                          </div>
                      </form>
                    </div>
                  </div>
                  </div>

                  <div class="span3"><a href="javascript:;" class="start inline-block">Get Started Free </a></div>


              </div>

        </div>
        <i class="box-arr-topleft"><i></i></i>
       </div>

       <div class="vspace"></div>

       <h2 class="text-center blue">You may want to know?</h2>

  </div>
</div>

<?php include THIS_TEMPLATE_DIR. "footer.php"; ?>