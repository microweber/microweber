<?php include ACTIVE_TEMPLATE_DIR. "header.php" ?>
<div class="pad2"></div>

<div id="RU-subnav">
  <div class="pad3"></div>
  <div id="RU-nav">
    <h1>Dashboard</h1>
  </div>

  <!--HELP-->
  <div id="RU-help"> <a href="#" title="Help" class="help"></a>
    <div>
      <div>
        <div id="tab1"></div>
      </div>
      <div id="preloader"></div>
    </div>

    <!--END HELP-->
  </div>
  <div class="clr"></div>
  <!--END SUBNAV-->
</div>


<div id="RU-content" class="text-pages">
	<div class="preloader">
    	<div class="preload"><img src="<? print TEMPLATE_URL; ?>images/loader.gif" alt="Preloader" /></div>
    </div>
    

	<!--BOX WELCOME-->
	<div class="box-holder">    
    	<div class="box-top"></div>
    	<div class="box-inside">
        
        
        
         
          <?php include ACTIVE_TEMPLATE_DIR.  "page_menu.php" ?>      
          
          
          
          <h1>Welcome to RU Toolz</h1>

<!--box content-->
<div class="box-cont"> 
  <!--left column-->
  <div class="cola"> <a  
			 href="<? print TEMPLATE_URL; ?>videos/flowplayer/hello.flv"  
			 style="display:block;width:450px;height:270px;"  
			 id="player"> <img src="<? print TEMPLATE_URL; ?>images/video_bg.gif" alt="Search engine friendly content" /> </a> 
    
    <!--end left column--> 
  </div>
  <!--right column-->
  <div class="colb">
    <h4>Other Videos</h4>
    <div class="separator"> <a href="#" title="#"> <img src="<? print TEMPLATE_URL; ?>images/video_tut.gif" alt="#"/> </a>
      <h5><a href="#" title="#"> Learn how to buy additional services </a></h5>
      <div class="clr"></div>
    </div>
    <div class="separator"> <a href="#" title="#"> <img src="<? print TEMPLATE_URL; ?>images/video_tut.gif" alt="#"/> </a>
      <h5><a href="#" title="#"> Learn how to buy additional services </a></h5>
      <div class="clr"></div>
    </div>
    <div class="separator"> <a href="<? print $link = $this->content_model->getContentURLById(490) ?>" title="#" class="button"><span>See all videos</span></a>
      <div class="clr"></div>
    </div>
    
    <!--end right column--> 
  </div>
  <div class="clr"></div>
  <!--end box content--> 
</div>

<!--box content-->
<div class="box-cont"> 
  <!--left column-->
  <div class="cola">
    <h2>Here goes the text</h2>
    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin laoreet, leo non commodo semper, orci urna lacinia arcu, et egestas tellus justo at purus. Curabitur accumsan faucibus urna, sollicitudin consequat ante fermentum sit amet. Curabitur nunc mi, congue eget lobortis ut, egestas eget velit. </p>
    <p> Vivamus dictum porta ante sed auctor. Integer adipiscing viverra euismod. Mauris eros augue, consectetur quis laoreet sed, porttitor in justo. Cras lorem massa, fermentum condimentum cursus non, pretium vitae nisi. </p>
    <p> <a href="<? print $link = $this->content_model->getContentURLById(491) ?>" title="#" class="button" style="float:left;"><span>Read more</span></a> </p>
    <div class="clr"></div>
    <br />
    <!--end left column--> 
  </div>
  <!--right column-->
  <div class="colb">
    <h4>Your latest activity</h4>
    <div class="separator"> <a href="#" title="#" class="activity"> <span>04.05.2010 / 12:45 /</span> Campain editor </a>
      <div class="clr"></div>
    </div>
    <div class="separator"> <a href="#" title="#" class="activity"> <span>08.03.2010 / 11:25 /</span> Affiliates Link Manager </a>
      <div class="clr"></div>
    </div>
    <div class="separator"> <a href="#" title="#" class="activity"> <span>02.01.2010 / 16:05 /</span> Page creator </a>
      <div class="clr"></div>
    </div>
    <br />
    <div class="clr"></div>
    <!--end right column--> 
  </div>
  <div class="clr"></div>
  <!--end box content--> 
</div>


        
        <div class="clr"></div>
        </div>
        <div class="box-bottom"></div>
        <!--END BOX WELCOME-->
    </div>

	
  
<div class="pad2"></div>
 <!--END CONTENT-->
</div> 


<?php include ACTIVE_TEMPLATE_DIR. "footer.php" ?>








 

