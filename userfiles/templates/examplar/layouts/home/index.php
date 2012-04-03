<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="shadow">
  <div class="shadow-content box">
    <div id="HeadRotator">
      <div class="slider">
        <div class="frames">
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slide.jpg" alt="" /> </div>
          <div class="frame"> <img src="<? print TEMPLATE_URL ?>img/slideshow/banner_2.jpg" alt="" /> </div>
          <div class="frame">  <img src="<? print TEMPLATE_URL ?>img/slideshow/banner_3.jpg" alt="" /></div>
          <div class="frame">  <img src="<? print TEMPLATE_URL ?>img/slideshow/banner_4.jpg" alt="" /> </div>
        </div>
      </div>
      <div id="SliderControlls"></div>
    </div>
  </div>
  <div id="banner_home_ribon"></div>
</div>
<!-- /#shadow -->
<br />

<div id="Scroll">
  <!--<span class="slide_left"></span> <span class="slide_right"></span>-->
  <div id="ScrollContainer">
    <div id="ScrollHolder" class="slide_engine">
      <div class="shadow">
        <div class="shadow-content box"><a href="<? print page_link(3397); ?>"><h2 class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/home_sub_banner_1.png)"><span class="hid">Practice Management And Development</span></h2></a>
          <div class="scroll_info">
            <p>Exemplar Health Resources sets the standard as the only company dedicated to the entire process of RCM, business development, referral network, marketing, and overall profitability for medical practices. <br />
 <br />Benchmarking the practices processes and baselines, we are able to set adequate goals with the understanding of the practice's intended medical services and market.</p>
        <!--    <h3 class="green">We are taking care of</h3>-->
            <br />
             <ul>
             <li><a href="<? print page_link(3397); ?>">Operations</a></li>
<li><a href="<? print page_link(3397); ?>">Marketing</a></li>
<li><a href="<? print page_link(3397); ?>">Accounting</a></li>
<li><a href="<? print page_link(3397); ?>">Referral Development</a></li> 
             </ul>
            
            
            
            <!--<a href="<? print page_link(3397); ?>" class="more greenbtn right">Learn More</a>--> </div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <a href="<? print page_link(3421); ?>"><h2 class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/home_sub_banner_2.png)"><span class="hid">Billing And Financial Services</span></h2></a>
          <div class="scroll_info">
            <p>Exemplar Health Resources develops solutions for each practice customized to their specific needs. <br /><br /> In order to help the physician to be patient-focused, our certified professional coders take on the responsibility of insurance filing and deliver optimal capital results with back-end financial management. </p>
<!--            <h3 class="blue">We are taking care of</h3>
-->          





<br /><ul>
             <li><a href="<? print page_link(3421); ?>">Revenue Cycle Management (RCM)</a></li>
<li><a href="<? print page_link(3421); ?>">Medical Coding</a></li>
<li><a href="<? print page_link(3421); ?>">Accounts Payable & Bookkeeping</a></li>
<li><a href="<? print page_link(3421); ?>">Chart Auditing</a></li>
<li><a href="<? print page_link(3421); ?>">Patient Accounts</a></li>
             </ul>

          <!--  <a href="<? print page_link(3421); ?>" class="more bluebtn right">Learn More</a> --></div>
        </div>
      </div>
      <div class="shadow">
        <div class="shadow-content box"> <a href="<? print page_link(3422); ?>"><h2 class="img" style="background-image: url(<? print TEMPLATE_URL ?>img/home_sub_banner_3.png)"><span class="hid">Information Technology</span></h2></a>
          <div class="scroll_info">
            <p>Exemplar Health Resources gives each practice an I.T. department without the costly investment by implementing software solutions, network management, data recovery, and staff training.</p>
<!--            <h3 class="purple">We are taking care of</h3>
-->            

<br />  


   <ul>
             <li><a href="<? print page_link(3422); ?>">Network Management</a></li>
<li><a href="<? print page_link(3422); ?>">Support Center</a></li>
<li><a href="<? print page_link(3422); ?>">Data, Voice, Internet</a></li>
<li><a href="<? print page_link(3422); ?>">Disaster Recovery & BackUp</a></li>
             </ul>


            <!--<a href="<? print page_link(3425); ?>" class="more purplebtn right">Learn More</a> --></div>
        </div>
      </div>
       
    </div>
    <!-- /#ScrollHolder -->
  </div>
  <!-- /#ScrollContainer -->
</div>
<!-- /#Scroll -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
