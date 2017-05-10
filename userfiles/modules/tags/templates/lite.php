<?php

/*

type: layout

name: Lite

description: Lite

*/
?>

<style>
    .tag a {
        background-color: #f5f5f5;
        border: 1px solid #f5f5f5;
        color: #656565;
        font-size: 14px;
        padding: 10px 20px;
        text-decoration: none;
        float: left;
        display: block;
        margin-bottom: 10px;
        margin-right: 10px;
    }

    .tag a:hover {
        background-color: #fff;
        border: 1px solid #3b3b3b;
        color: #656565;
    }
</style>
<div class="tag">
    <?php foreach ($content_tags as $item): ?>
        <a href="<?php print $tags_url_base ?>/tags:<?php print url_title($item) ?>" class="tag__link"><?php print $item ?></a>
    <?php endforeach; ?>
</div>