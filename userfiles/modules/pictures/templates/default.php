<?php

/*

type: layout

name: Default

description: Pictures List

*/

  ?>
<? if(isarr($data )): ?>

<?php  $rand = uniqid(); ?>

<script>mw.require("tools.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>js/api.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true); </script>
<script>

$(document).ready(function(){

    mw.popupZoom("#mw-gallery-<?php print $rand; ?> .thumbnail");



});

</script>
<div class="mw-module-images">
<div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-<?php print $rand; ?>">
  <? foreach($data  as $item): ?>
  <div class="mw-pictures-item mw-pictures-item-<? print $item['id']; ?>">
    <a class="thumbnail">
        <img src="<? print $item['filename']; ?>" />
    </a>
  </div>
  <? endforeach ; ?>
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
