<?php

/*

type: layout

name: Skin-1

description: Skin-1

*/
?>







<div class="shop-tags">
    <?php foreach ($content_tags_data as $tag_item): ?>
        <a href="<?php print $tags_url_base ?>/tags:<?php print $tag_item['tag_slug']; ?>">


            #<?php print $tag_item['tag_name']; ?>

        </a>
    <?php endforeach; ?>
</div>




