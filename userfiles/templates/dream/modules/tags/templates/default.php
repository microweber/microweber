<?php

/*

type: layout

name: Default

description: Default

*/
?>

<ul class="tag-cloud">
    <?php foreach ($content_tags as $item): ?>
        <li>
            <a href="<?php print $tags_url_base ?>/tags:<?php print url_title($item) ?>" class="btn btn--sm btn--square">
                <span class="btn__text"><?php print $item ?></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>