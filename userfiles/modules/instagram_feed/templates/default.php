<?php

/*

type: layout

name: Default

description: Default Instagram Feed

*/

?>
<style>
    .mw-instagram-feeds {
        margin: 0 -6px;
    }

    .mw-instagram-feeds .feed-photo {
        float: left;
        width: 33.3%;
        padding: 6px;
    }
</style>

<?php if (!$profile_data['is_private']): ?>
    <?php if ($photos): ?>
        <div class="mw-instagram-feeds">
            <?php foreach ($photos as $photo): ?>
                <div class="feed-photo">
                    <a href="<?php print $photo['thumbnail_src']; ?>" data-fancybox target="_blank">
                        <img src="<?php print $photo['thumbnail_src']; ?>" alt="<?php print $photo['module_caption']; ?>"/>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <p>This profile is private!</p>
<?php endif; ?>
