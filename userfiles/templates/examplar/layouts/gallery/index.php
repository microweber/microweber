<?php

/*

type: layout

name: galeria layout

description: galeria site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div id="content">
  <div class="shadow">
    <div class="shadow-content box inner_top"> <img src="<? print TEMPLATE_URL ?>img/ekip.jpg" alt="" /> </div>
  </div>
  <!-- /#shadow -->
  <div id="main">
    <h2 class="title">Галерия</h2>
    <div id="List" class="gallery">
      <ul>
        <? $pics = get_media(PAGE_ID, $for = 'post', $media_type = false, $queue_id = false, $collection = false);   ?>
        <? foreach($pics['pictures'] as $pic): ?>
        <li>
          <div class="shadow img">
            <div class="shadow-content">
              <div style="background-image: url('<? print get_media_thumbnail($pic['id'], $size_width = 287, $size_height = false); ?>')"> <a rel="slide" href="<? print get_media_thumbnail($pic['id'], $size_width = 'original', $size_height = false); ?>">&nbsp;</a> </div>
            </div>
          </div>
        </li>
        <? endforeach; ?>
      </ul>
    </div>
    <!-- /#List -->
  </div>
  <!-- /#main -->
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
