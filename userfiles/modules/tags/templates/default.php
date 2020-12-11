<?php

/*

type: layout

name: Default

description: Default

*/
?>

<div class="tag">
    <?php foreach ($content_tags_data as $tag_item): ?>
        <a href="<?php print $tags_url_base ?>/tags:<?php print $tag_item['tag_slug']; ?>" class="tag__link">
            <span class="label label-warning label-md">
                <?php print $tag_item['tag_name']; ?> (<?php print $tag_item['count']; ?>)
            </span>
        </a>
    <?php endforeach; ?>
</div>