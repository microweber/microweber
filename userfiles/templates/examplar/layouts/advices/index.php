<?php

/*

type: layout

name: advices layout

description: advices site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>


 
      <div class="shadow">
        <div class="shadow-content box inner_top"> <img src="<? print TEMPLATE_URL ?>img/syveti.jpg" /> </div>
      </div>
      <!-- /#shadow -->
      <div id="main">
        <div id="sidebar">
          <div class="shadow" id="sidenav">
            <div class="shadow-content box">
              <h2 id="sidetitle">Нашите съвети</h2>
              
              <microweber module="content/pages_tree" />
              
           <!--   <ul>
                <li><a href="#">Как да се грижим зазъбите си?</a></li>
                <li><a href="#">Симптоми за здрави зъби</a></li>
                <li><a href="#">Профелактични прегледи</a></li>
                <li><a href="#">Знаете ли че?</a></li>
                <li><a href="#">Дентална медицина и консумативи</a></li>
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
            
            <? print $page['content_body']; ?>
            
          
          </div>
        </div>
        <!-- /#sidecontent -->
      </div>
      <!-- /#main -->
 

<? include   TEMPLATE_DIR.  "footer.php"; ?>
