<?php

/*

type: layout

name: Bootstrap Carousel

description: Bootstrap Carousel

*/

  ?>
<?php if(is_array($data )): ?>

<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true); </script>

<?php $rand = 'item_carousel_'.$params['id']; $id = 'carousel_'.$params['id']; ?>
    <div class="mw-module-images">
    <div id="<?php print $id; ?>" class="carousel slide mw-image-carousel">
      <ol class="carousel-indicators">
        <?php $count = -1; foreach($data  as $item): ?>
        <li data-target="#<?php print $id; ?>" data-slide-to="<?php print $count++; ?>" class="<?php if($count==0){ print 'active';} ?>"></li>
        <?php endforeach; ?>
      </ol>
    <!-- Carousel items -->
      <div class="carousel-inner">
        <?php $count = -1; foreach($data  as $item): ?>
         <?php $count++; ?>
          <div class="<?php if($count==0){ print 'active ';} ?>item">
            <img src="<?php print thumbnail($item['filename'], 1900, 1000); ?>"  />
            <?php if(isset($item['title']) and $item['title'] !=''){ ?>
            <div class="carousel-caption">
                <p><?php print $item['title']; ?></p>
            </div>
            <?php } ?>

          </div>
        <?php endforeach ; ?>
      </div>
    <!-- Carousel nav -->
      <a class="carousel-control left" href="#<?php print $id; ?>" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="carousel-control right" href="#<?php print $id; ?>" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
    </div>

<?php else : ?>

 <?php endif; ?>
