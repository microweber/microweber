<?php

/*

type: layout

name: knigi layout

description: knigi site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print TEMPLATE_URL ?>img/uslugi.jpg" /> </div>
</div>
<!-- /#shadow -->
<div id="main">
  <div id="sidebar">
    <div class="shadow">
      <div class="shadow-content box">
        <iframe src="http://player.vimeo.com/video/22439234" width="314" height="220" frameborder="0" scrolling="no"></iframe>
      </div>
    </div>
    <div class="c" style="padding-bottom: 20px;">&nbsp;</div>
    <div class="shadow" id="sidenav">
      <div class="shadow-content box">
        <h2 id="sidetitle">Нашите услуги</h2>
        <mw module="content/pages_tree" />
        <!--  <ul>
                          <li><a href="#">Профилактика</a></li>
                          <li><a href="#">КОНСЕРВАТИВНА дентална медицина</a></li>
                          <li><a href="#">Възстановителна дентална медицина</a></li>
                          <li><a href="#">Възстановяване с имплантати</a></li>
                          <li><a href="#">Дентална хирургия</a></li>
                          <li><a href="#">Детска стоматология</a></li>
                          <li><a href="#">Ортодонтия</a></li>
                          <li><a href="#">Козметична дентална медицина</a></li>
                        </ul>-->
      </div>
    </div>
    <a  href="https://www.facebook.com/pages/Дентален-Център-3М/241127939263153" target="_blank" >
    <img src="<? print TEMPLATE_URL ?>img/sidefb.jpg" alt="" border="0" />
    </a>
    
     </div>
  <!-- /#sidebar -->
  <div id="sidecontent">
    <div class="richtext">
         <h2><? print $page['content_title']; ?></h2>
            <!--<img src="<? print TEMPLATE_URL ?>img/img.jpg" alt="" />-->
            
            <? print $page['content_body']; ?> </div>
  </div>
  <!-- /#sidecontent -->
</div>
<!-- /#main -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
