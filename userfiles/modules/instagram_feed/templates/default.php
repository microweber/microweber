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
        padding: 12px;
    }
</style>

<?php if (!$profile_data['is_private']): ?>
    <?php if ($photos): ?>
        <div class="mw-instagram-feeds">
            <?php foreach ($photos as $photo): ?>
                <div class="feed-photo">
                    <img src="<?php print $photo['thumbnail_src']; ?>" alt="<?php print $photo['module_caption']; ?>"/>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php else: ?>
    <p>This profile is private!</p>
<?php endif; ?>
