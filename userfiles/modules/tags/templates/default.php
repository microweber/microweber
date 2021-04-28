<?php

/*

type: layout

name: Default

description: Default

*/
?>

<?php include modules_path() . 'editor' . DS . 'template_settings' . DS . 'generated_vars.php'; ?>

<style>
    .mw-tags-button.btn-outline-primary {
        background-color: <?php echo $less_primary; ?>;
        border: 1px solid <?php echo $less_primary; ?>;
        color: <?php echo $less_textLight; ?>;
    }

    .mw-active-tag-default {
        background-color: <?php echo $less_dark; ?>!important;
        border: 1px solid <?php echo $less_dark; ?>!important;
        color: <?php echo $less_textLight; ?>!important;
    }
</style>

<?php $current_tag = url_param('tags'); ?>

<?php foreach ($content_tags_data as $tag_item): ?>
    <div class="btn-group tag tag-xs m-1">
        <a href="<?php print $tags_url_base ?><?php print $current_tag == $tag_item['tag_slug'] ? '' : '/tags:'. $tag_item['tag_slug'] ?>">
            <span class="btn mw-tags-button btn-outline-primary rounded-pill btn-sm icon-left no-hover px-3 <?php print $current_tag == $tag_item['tag_slug'] ? 'mw-active-tag-default' : '' ?>"> <?php print $tag_item['tag_name']; ?></span>
        </a>
    </div>
<?php endforeach; ?>

