<?php

/*

type: layout

name: Product Gallery

description: Product Gallery

*/

  ?>




 <?php if(is_array($data )): ?>

 <?php $id = "slider-".uniqid(); ?>

  <?php  $rand = uniqid(); ?>

<div class="autoscale mw-rotator mw-rotator-template-inner" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <?php $count = -1; foreach($data  as $item): ?>
    <?php $count++;  ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<?php print $item['id']; ?>">
        <span class=" mw-slider-zoomholder">
            <img class="mw-slider-zoomimg-base" src="<?php print thumbnail($item['filename'], 600); ?>" alt="" />
            <img src="<?php print thumbnail($item['filename'], 1200); ?>" class="mw-slider-zoomimg" alt=""  onclick="mw.tools.gallery.init(gallery<?php print $rand; ?>, <?php print $count; ?>)" />
        </span>
        <?php if($item['title'] != ''){ ?><i class="mw-rotator-description"><i class="mw-rotator-description-content"><?php print $item['title']; ?></i></i><?php } ?>
    </div>
    <?php endforeach ; ?>
  </div>
</div>
<script>gallery<?php print $rand; ?> = [<?php foreach($data  as $item): ?>{image:"<?php print thumbnail( $item['filename'], 1000); ?>",description:"<?php print $item['title']; ?>"},<?php endforeach;  ?>];</script>

<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
    mw.require("<?php print $config['url_to_module']; ?>js/api.js", true);
    mw.require("tools.js", true);
</script>

<script type="text/javascript">

  Rotator = null;
  $(document).ready(function(){
    if($('#<?php print $id; ?>').find('.mw-gallery-item').length>1){
        Rotator = mw.rotator('#<?php print $id; ?>');
        if (!Rotator) return false;
        Rotator.options({
            paging:true,
            pagingMode:"thumbnails",
            next:true,
            prev:true,
            reflection:false
        });
    }

    mw.$('#<?php print $id; ?> span.mw-slider-zoomholder').each(function(){
            mw.productZoom(this);
        });

  });

</script>


<?php else : ?>
<?php endif; ?>