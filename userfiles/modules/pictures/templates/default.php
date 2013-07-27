<?php

/*

type: layout

name: Default

description: Pictures List

*/

  ?>
<?php if(is_array($data )): ?>

<?php  $rand = uniqid(); ?>

<script>mw.require("tools.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>js/api.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true); </script>
<script>
    $(document).ready(function(){
        mw.popupZoom("#mw-gallery-<?php print $rand; ?> .thumbnail");
    });
</script>
<div class="mw-module-images<?php if($no_img){ ?> no-image<?php } ?>">
<div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-<?php print $rand; ?>">
  <?php foreach($data  as $item): ?>
  <div class="mw-pictures-item mw-pictures-item-<?php print $item['id']; ?>">
    <div class="thumbnail">
        <span class="valign">
          <span class="valign-cell">
              <img src="<?php print thumbnail( $item['filename'], 700); ?>" />
          </span>
        </span>
    </div>
  </div>
  <?php endforeach;  ?>
</div>
</div>
<?php else : ?>
<?php endif; ?>