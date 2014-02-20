<?php

/*

type: layout

name: Default

description: Default Picture List

*/

  ?>
<?php if(is_array($data )): ?>

<?php  $rand = uniqid(); ?>

<script>mw.require("tools.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>css/clean.css", true); </script>
<div class="mw-module-images<?php if($no_img){ ?> no-image<?php } ?>">
<div class="mw-pictures-clean" id="mw-gallery-<?php print $rand; ?>">
  <?php $count = -1; foreach($data  as $item): ?>
  <?php $count++; ?>
  <div class="mw-pictures-clean-item mw-pictures-clean-item-<?php print $item['id']; ?>">
    <a href="<?php print thumbnail( $item['filename'], 1000); ?>" onclick="mw.tools.gallery.init(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
        <img src="<?php print thumbnail( $item['filename'], 300); ?>" />
    </a>
  </div>
  <?php endforeach;  ?>
  <script>gallery<?php print $rand; ?> = [<?php foreach($data  as $item): ?>{image:"<?php print thumbnail( $item['filename'], 1000); ?>",description:"<?php print $item['title']; ?>"},<?php endforeach;  ?>];</script>
</div>
</div>
<?php else : ?>
<?php endif; ?>