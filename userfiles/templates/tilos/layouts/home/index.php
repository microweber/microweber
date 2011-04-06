<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
               <? include TEMPLATE_DIR. "header.php"; ?>
               
                     <script type="text/javascript" src="<? print TEMPLATE_URL ?>flash/js/swfobject.js"></script>
      <script type="text/javascript" src="<? print TEMPLATE_URL ?>flash/js/CU3ER.js"></script>
      <script type="text/javascript">
  // add your FlashVars
  var vars = { xml_location : '<? print TEMPLATE_URL ?>flash/CU3ER-config.php?site=<? print base64_encode(site_url()); ?>' };
  // add Flash embedding parameters, etc. wmode, bgcolor...
  var params = { scale:'exactfit' };
  // Flash object attributes id and name
  var attributes = { id:'rotator', name:'rotator' };
  // dynamic embed of Flash, set the location of expressInstall if needed
	swfobject.embedSWF('<? print TEMPLATE_URL ?>flash/CU3ER.swf', "rotator", 952, 420, "10.0.0", "<? print TEMPLATE_URL ?>flash/js/expressinstall.swf", vars, params, attributes );
  // initialize CU3ER class containing Javascript controls and events for CU3ER
  var CU3ER_object = new CU3ER("CU3ER");
</script>
      <div id="rotator_holder">
        <div id="rotator"> </div>
      </div>
      <div class="in_featured"> 
      
      
      <a class="product product_active" href="#"> <span class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/_demo_featured.jpg)">&nbsp;</span> <strong>3MM TITANIUM HYDRO and ZIP BOOT</strong> <span class="best_seller">&nbsp;</span> </a>
      
      
        <div class="in_new">
        <editable rel="page" field="content_body">
          <h1>Whats New?</h1>
          <h2>New website design</h2>
          
          
          <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged </p>
          
          
          
          
          <a href="#" class="btn right">Read more</a>   </editable> </div>
        <div class="home_dealer">
        <editable rel="page" field="custom_field_txt1">
        
          <h1 style="font-size: 16px;">Dealer Locator</h1>
          <img src="<? print TEMPLATE_URL ?>img/hmap.png" alt=""  /> <br />
          <a href="#" class="btn right">Find a dealer</a>
          
          </editable> 
           </div>
      </div>
      
      
 


               

               <? include   TEMPLATE_DIR.  "footer.php"; ?>
