<?php

/*

type: layout

name: Skin-1

description: Skin-1

*/
?>

<?php $current_tag = url_param('tags'); ?>

<?php foreach ($content_tags_data as $tag_item): ?>
    <div class="btn-group tag tag-xs m-1">
        <a href="<?php print $tags_url_base ?><?php print $current_tag == $tag_item['tag_slug'] ? '' : 'tags:'. $tag_item['tag_slug'] ?>">
            <button class="btn btn-link px-0 <?php print $current_tag == $tag_item['tag_slug'] ? 'mw-active-tag-skin-1' : '' ?>"> #<?php print $tag_item['tag_name']; ?></button>
        </a>
    </div>
<?php endforeach; ?>
