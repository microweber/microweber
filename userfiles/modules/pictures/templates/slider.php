<?php

/*

type: layout

name: Slider

description: Pictures slider

*/

  ?>


 <? if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>
 <div class="well mw-module-images">
<div class="autoscale mw-rotator mw-rotator-template-default" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <? foreach($data  as $item): ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<? print $item['id']; ?>">

        <img src="<? print $item['filename']; ?>" alt="" />

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
          next:true,
          prev:true
      });
      Rotator.autoRotate(3000);
    }
  });
</script>
 

<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>