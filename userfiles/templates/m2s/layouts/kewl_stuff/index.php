<?php

/*

type: layout

name:  layout

description: layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="inner_container">
  <div class="inner_container_top"></div>
  <div class="inner_container_mid">
    <div class="inner_left">
      <div class="howit_works_left_img"><img src="<? print TEMPLATE_URL ?>images/kewl_left_img.jpg" width="210" height="162" /></div>
      <ul>
        <li><a href="agony_center.html" id="current">Agony Center</a></li>
        <li><a href="shopping_center.html" id="left_link">Shopping Center</a></li>
        <li><a href="content.html" id="left_link">Content</a></li>
        <li><a href="mentoring.html" id="left_link">Mentoring</a></li>
      </ul>
      <div class="sponsored_tit">&nbsp;</div>
      <div class="sponsor_logo"><img src="<? print TEMPLATE_URL ?>images/sponsor_logo.jpg" alt="sponsor" /></div>
    </div>
    <div class="inner_rt">
      <div class="agony_blk">
        <div class="agony_icon"><img src="<? print TEMPLATE_URL ?>images/agony_iocn_1.jpg" /></div>
        <div class="agony_rt">
          <div class="page_tit"> Agony Center - Ask in the forum </div>
          <div class="agony_text"> Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the. </div>
          <a href="#" id="agony_but"> Join the forum here</a> </div>
      </div>
      <div class="agony_blk">
        <div class="agony_icon"><img src="<? print TEMPLATE_URL ?>images/agony_iocn_1.jpg" /></div>
        <div class="agony_rt">
          <div class="page_tit"> Shopping Center</div>
          <div class="agony_text">Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the. <br />
          </div>
          <a href="#" id="agony_but"> Join to shppping center </a> </div>
      </div>
      <div class="agony_blk">
        <div class="agony_icon"><img src="<? print TEMPLATE_URL ?>images/agony_iocn_3.jpg" width="97" height="123" /></div>
        <div class="agony_rt">
          <div class="page_tit"> Mentoring</div>
          <div class="agony_text"> Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the. </div>
          <a href="#" id="agony_but">Go to Mentoring </a> </div>
      </div>
      <div class="agony_blk">Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the. <br />
        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of &quot;de Finibus Bonorum et Malorum&quot; (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, &quot;Lorem ipsum dolor sit amet..&quot;, comes from a line in section 1.10.32.</p>
      </div>
      <br />
      <p> <a href="agony_center.html"><strong>Agony Center </strong></a>&nbsp;&nbsp;&nbsp;<a href="shopping_center.html">Shopping Center</a>&nbsp;&nbsp;&nbsp;<a href="content.html">Content</a>&nbsp;&nbsp;&nbsp;<a href="mentoring.html">Mentoring</a></p>
      <br />
    </div>
  </div>
  <div class="inner_container_bot">
      <div class="fb-like" data-href="<? print url() ?>" data-send="true" data-width="450" data-show-faces="true" data-font="tahoma"></div>

    </div>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
