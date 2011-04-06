
               <? include "header.php"; ?>

<script type="text/javascript" src="flash/js/swfobject.js"></script>
<script type="text/javascript" src="flash/js/CU3ER.js"></script>
<script type="text/javascript">
  // add your FlashVars
  var vars = { xml_location : 'flash/CU3ER-config.php?site=<? print base64_encode(site_url()); ?>' };
  // add Flash embedding parameters, etc. wmode, bgcolor...
  var params = { scale:'exactfit' };
  // Flash object attributes id and name
  var attributes = { id:'rotator', name:'rotator' };
  // dynamic embed of Flash, set the location of expressInstall if needed
	swfobject.embedSWF('flash/CU3ER.swf', "rotator", 952, 420, "10.0.0", "flash/js/expressinstall.swf", vars, params, attributes );
  // initialize CU3ER class containing Javascript controls and events for CU3ER
  var CU3ER_object = new CU3ER("CU3ER");
</script>


               <div id="rotator_holder">
                 <div id="rotator">



                 </div>
               </div>

               <div class="in_featured">

                    <a class="product product_active" href="#">
                        <span class="img" style="background-image: url(img/_demo_featured.jpg)">&nbsp;</span>

                        <strong>3MM TITANIUM HYDRO and ZIP BOOT</strong>
                        <span class="best_seller">&nbsp;</span>
                    </a>


                    <div class="in_new">
                      <h1>Whats New?</h1>
                      <h2>New website design</h2>
                      <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                        when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                        It has survived not only five centuries, but also the leap into electronic typesetting,
                        remaining essentially unchanged
                      </p>
                      <a href="#" class="btn right">Read more</a>
                    </div>

                    <div class="home_dealer">
                        <h1 style="font-size: 16px;">Dealer Locator</h1>
                        <img src="img/hmap.png" alt=""  />
                        <br />
                        <a href="#" class="btn right">Find a dealer</a>
                    </div>



               </div>

               <? include "footer.php"; ?>
