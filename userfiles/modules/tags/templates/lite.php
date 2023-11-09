<?php

/*

type: layout

name: Lite

description: Lite

*/
?>

<?php $current_tag = url_param('tags'); ?>

<?php foreach ($content_tags_data as $tag_item): ?>
    <div class="btn-group tag tag-xs m-1">
        <a href="<?php print $tags_url_base ?><?php print $current_tag == $tag_item['tag_slug'] ? '' : 'tags:'. $tag_item['tag_slug'] ?>" class="btn btn-sm mw-tags-btn-light px-3 <?php print $current_tag == $tag_item['tag_slug'] ? 'btn-outline' :  'btn-outline-light'?>">
            <?php print $tag_item['tag_name']; ?>
        </a>
    </div>
<?php endforeach; ?>


