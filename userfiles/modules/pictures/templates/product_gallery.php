<?php

/*

type: layout

name: Product Gallery

description: Product Gallery

*/

  ?>




 <?php if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>



<div class="autoscale mw-rotator mw-rotator-template-inner" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <?php foreach($data  as $item): ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<?php print $item['id']; ?>">


         
    <span class=" mw-slider-zoomholder">
            <img class="mw-slider-zoomimg-base" src="<?php print thumbnail($item['filename'], 600); ?>" alt="" />
            <img src="<?php print thumbnail($item['filename'], 1200); ?>" class="mw-slider-zoomimg" alt="" />
        </span>



    </div>
    <?php endforeach ; ?>
  </div>
</div>


<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
    mw.require("<?php print $config['url_to_module']; ?>js/api.js", true);
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
<?php print mw_notif_live_edit("Please click on settings to upload your pictures."); ?>
<?php endif; ?>