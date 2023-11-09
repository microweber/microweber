<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php $current_tag = url_param('tags'); ?>

<?php foreach ($content_tags_data as $tag_item): ?>
    <div class="btn-group tag tag-xs m-1">
        <a href="<?php print $tags_url_base ?><?php print $current_tag == $tag_item['tag_slug'] ? '' : 'tags:'. $tag_item['tag_slug'] ?>" class="btn rounded-pill btn-sm icon-left no-hover px-3 <?php print $current_tag == $tag_item['tag_slug'] ? 'btn-primary' : 'btn-outline-primary' ?>">
           <?php print $tag_item['tag_name']; ?>
        </a>
    </div>
<?php endforeach; ?>

