<?php

/*

type: layout

name: Bootstrap Carousel

description: Bootstrap Carousel 

*/

  ?>
<? if(isarr($data )): ?>

<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true); </script>

<?php $rand = uniqid(); $id = 'carousel_'.$rand; ?>
    <div class="mw-module-images">
    <div id="<?php print $id; ?>" class="carousel slide mw-image-carousel">
      <ol class="carousel-indicators">
        <? $count = -1; foreach($data  as $item): ?>
        <li data-target="#<?php print $id; ?>" data-slide-to="<?php print $count++; ?>" class="<?php if($count==0){ print 'active';} ?>"></li>
        <?php endforeach; ?>
      </ol>
    <!-- Carousel items -->
      <div class="carousel-inner">
        <? $count = -1; foreach($data  as $item): ?>
         <?php $count++; ?>
          <div class="<?php if($count==0){ print 'active ';} ?>item">
            <img src="<? print $item['filename']; ?>"  />
            <?php if(isset($item['title']) and $item['title'] !=''){ ?>
            <div class="carousel-caption">
                <p><?php print $item['title']; ?></p>
            </div>
            <?php } ?>

          </div>
        <? endforeach ; ?>
      </div>
    <!-- Carousel nav -->
      <a class="carousel-control left" href="#<?php print $id; ?>" data-slide="prev">&lsaquo;</a>
      <a class="carousel-control right" href="#<?php print $id; ?>" data-slide="next">&rsaquo;</a>
    </div>
    </div>

<? else : ?>
<div class="mw-notification mw-success">
    <div>
      <span class="ico ioptions"></span>
      <span>Please click on settings to upload your pictures.</span>
    </div>
  </div>
 <? endif; ?>
