<?php

/*

type: layout

name: Inner

description: Inner Slider

*/

  ?>




 <? if(isarr($data )): ?>

 <?php $id = "slider-".uniqid(); ?>



<div class="autoscale mw-rotator mw-rotator-template-inner" id="<?php print $id; ?>">
  <div class="autoscale mw-gallery-holder">
    <? foreach($data  as $item): ?>
    <div class="autoscale mw-gallery-item mw-gallery-item-<? print $item['id']; ?>">


            <img  class="autoscale-x" src="<? print $item['filename']; ?>" alt="" />




    </div>
    <? endforeach ; ?>
  </div>
</div>


<script type="text/javascript">
    mw.require("<?php print $config['url_to_module']; ?>css/style.css", true);
    mw.require("<?php print $config['url_to_module']; ?>js/api.js", true);

    $(document).ready(function(){
      var el = mwd.getElementById('<?php print $id; ?>');
      var module = mw.tools.firstParentWithClass(el, 'module');
      module.style.paddingBottom = '130px';
    });

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
            reflection:true
        });
    }
  });

</script>
 

<? else : ?>
Please click on settings to upload your pictures.
<? endif; ?>