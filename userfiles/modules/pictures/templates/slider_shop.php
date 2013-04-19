<?php

/*

type: layout

name: Product Slider

description: Product Slider

*/

  ?>




 <? if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>

<div class="well mw-module-images">
<div class="autoscale mw-rotator mw-rotator-template-shop" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <? foreach($data  as $item): ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<? print $item['id']; ?>">

        <span class=" mw-slider-zoomholder">
            <img class="mw-slider-zoomimg-base" src="<? print $item['filename']; ?>" alt="" />
            <img src="<? print $item['filename']; ?>" class="mw-slider-zoomimg" alt="" />
        </span>

    </div>
    <? endforeach ; ?>
  </div>
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
            prev:true
        });
        Rotator.autoRotate(5000);
      }
      mw.$('#<?php print $id; ?> span.mw-slider-zoomholder').each(function(){
            mw.productZoom(this);
      });
  });

</script>
 

<? else : ?>
<? print mw_notif("Please click on settings to upload your pictures."); ?>
<? endif; ?>