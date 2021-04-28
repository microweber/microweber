<?php

/*

type: layout

name: Default

description: Default

*/
?>

<style>
    .mw-active-tag {
        background-color: blue;
    }
</style>

<?php $current_tag = url_param('tags'); ?>

<?php foreach ($content_tags_data as $tag_item): ?>
    <div class="btn-group tag tag-xs m-1">
        <a href="<?php print $tags_url_base ?><?php print $current_tag == $tag_item['tag_slug'] ? '' : '/tags:'. $tag_item['tag_slug'] ?>">
            <span class="btn btn-primary btn-sm icon-left no-hover <?php print $current_tag == $tag_item['tag_slug'] ? 'mw-active-tag' : '' ?>"><i class="mdi mdi-tag"></i> <?php print $tag_item['tag_name']; ?></span>
        </a>
    </div>
<?php endforeach; ?>

