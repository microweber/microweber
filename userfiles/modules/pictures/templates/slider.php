<?php

/*

type: layout

name: Slider

description: Pictures slider

*/

  ?>


 <? if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>
<div class="autoscale mw-rotator" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <? foreach($data  as $item): ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<? print $item['id']; ?>" style="background-image:url(<? print $item['filename']; ?>);"></div>
    <? endforeach ; ?>
  </div>
</div>


<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
    mw.require("<?php print $config['url_to_module']; ?>js/api.js", true);
</script>


<script type="text/javascript">
  Rotator = null;
  $(document).ready(function(){
      Rotator = mw.rotator('#<?php print $id; ?>');
      if (!Rotator) return false;
      Rotator.controlls({
          paging:true,
          next:true,
          prev:true
      });
      Rotator.autoRotate(3000);
  });
</script>
 

<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>