<?php

/*

type: layout

name: Media layout

description: Media site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $view = url_param('view'); ?>
<?  if($view == 'cart'):  ?>
<?  elseif($view == 'checkout'):  ?>

<div id="main">
  <? include  "checkout.php"; ?>
</div>
<? else: ?>
<? include TEMPLATE_DIR."sidebar.php"; ?>
<div id="main">
  <h2 class="title">Media</h2>
  <br />
  <p>&nbsp;&nbsp;See our Media galleries, you can send us your videos and photo, and we can  publish them on this page!</p>
  <br />
  <br />
  <br />
  <h3 class="title">video gallery</h3>
  <div class="gbox">
    <div class="gbox_top">
      <div class="gbox_top">&nbsp;</div>
    </div>
    <div class="gbox_content">
      <div class="photoslider" id="photoslider">
        <div class="photoslider_holder">
         <mw module="media/videos" content_id="<? print $page['id']?>">
          
        </div>
        <span class="slide_left photo_slide_left"></span> <span class="slide_right photo_slide_right"></span> </div>
    </div>
    <div class="gbox_bot">
      <div class="gbox_bot">&nbsp;</div>
    </div>
  </div>
  <br />
  <br />
  <br />
  <h3 class="title">Picture gallery</h3>
  <div class="gbox">
    <div class="gbox_top">
      <div class="gbox_top">&nbsp;</div>
    </div>
    <div class="gbox_content">
    
    
      <div class="photoslider" id="pic_gal">
        <div class="photoslider_holder">
          
          <mw module="media/gallery" content_id="<? print $page['id']?>">
          
 
          
          
          
          
        </div>
        <span class="slide_left photo_slide_left"></span> <span class="slide_right photo_slide_right"></span> </div>
    </div>
    <div class="gbox_bot">
      <div class="gbox_bot">&nbsp;</div>
    </div>
  </div>
</div>
<? endif; ?>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
