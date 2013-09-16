<?php

/*

type: layout

name: Masonry

description: Masonry

*/

  ?>
<?php if(is_array($data )): ?>

<?php  $rand = uniqid(); ?>

<script>mw.require("tools.js", true); </script>

<script>mw.require("<?php print $config['url_to_module']; ?>js/masonry.pkgd.min.js", true); </script>
<script>mw.require("<?php print $config['url_to_module']; ?>css/style.css", true); </script>
<script>

    $(document).ready(function(){

        var m = mw.$('#mw-gallery-<?php print $rand; ?>');
        m.masonry({
          "itemSelector": '.masonry-item',
          "gutter":5
        });

        mw.onLive(function(){
           setInterval(function(){
             m.masonry({
              "itemSelector": '.masonry-item',
              "gutter":5
            });
           }, 500);
        });

    });
</script>

<div class="mw-images-template-masonry" id="mw-gallery-<?php print $rand; ?>" style="position: relative;width: 100%;" >

  <?php foreach($data  as $item): ?>

    <div class="masonry-item" style="width: 19%;margin-bottom: 5px;box-shadow: 0 2px 2px;">
        <img src="<?php print thumbnail( $item['filename'], 300); ?>" width="100%" />
    </div>

  <?php endforeach;  ?>
</div>

<?php else : ?>
<?php endif; ?>