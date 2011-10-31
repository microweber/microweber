<?php

/*

type: layout

name: ekip layout

description: ekip site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="shadow">
  <div class="shadow-content box inner_top"> <img src="<? print TEMPLATE_URL ?>img/ekip.jpg" alt="" /> </div>
</div>
<!-- /#shadow -->
<div id="main">
  <h2 class="title">Екип</h2>
  <div id="List">
    <ul>
      <? $pics = get_media(PAGE_ID, $for = 'post', $media_type = false, $queue_id = false, $collection = false); 
 	  ?>
      <? foreach($pics['pictures'] as $pic): ?>
      <li>
        <div class="shadow img">
          <div class="shadow-content">
            <div style="background-image: url('<? print get_media_thumbnail($pic['id'], $size_width = 287, $size_height = false); ?>')">&nbsp;</div>
          </div>
        </div>
        <h3><? print ($pic['media_name']); ?></h3>
        <p><? print ($pic['media_description']); ?></p>
      </li>
      <? endforeach; ?>
      <!--
          
          
          
          
          
          
          
          
          
          
          
          
          
          
          
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>
            <li>
              <div class="shadow img">
                <div class="shadow-content">
                  <div style="background-image: url(<? print TEMPLATE_URL ?>img/d.ekip.jpg)">&nbsp;</div>
                </div>
              </div>
              <h3>Доктор Митова</h3>
              <p>Д-р Юлиана Митова е специалист по дентална медицина с опит от над 20 години. Всеки клиент може да се чувства сигурен в нейният професионализъм.</p>
            </li>-->
    </ul>
  </div>
  <!-- /#List -->
</div>
<!-- /#main -->
<? include   TEMPLATE_DIR.  "footer.php"; ?>
