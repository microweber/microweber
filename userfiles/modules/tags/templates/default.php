<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="tag">
    <?php foreach ($content_tags as $item): ?>
        <a href="<?php print $tags_url_base ?>/tags:<?php print url_title($item) ?>" class="tag__link"><span class="label label-warning label-md"><?php print $item ?></span></a>
    <?php endforeach; ?>
</div>