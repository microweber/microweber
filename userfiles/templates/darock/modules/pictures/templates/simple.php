<?php

/*

type: layout

name: Simple

description: Simple Pictures List Template

*/

?>
<?php if (is_array($data)): ?>

    <?php $rand = uniqid(); ?>

    <script>mw.require("tools.js", true); </script>
    <div class="mw-module-images<?php if ($no_img) { ?> no-image<?php } ?>">
        <div class="mw-pictures-list mw-images-template-default-grid" id="mw-gallery-<?php print $rand; ?>">
            <?php $count = -1; foreach ($data as $item): ?>
                <?php $count++; ?>
                <div class="mw-pictures-item-simple mw-pictures-item-<?php print $item['id']; ?>">
                    <div class="mw-pictures-item-thumbnail" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>)">
                        <img src="<?php print $item['filename']; ?>"/>
                    </div>
                    <div class="mw-pictures-item-simple-description"><?php print $item['title']; ?></div>
                </div>
            <?php endforeach;  ?>
            <script>gallery<?php print $rand; ?> = [
                        <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
                    <?php endforeach;  ?>
                ];</script>
        </div>
    </div>
<?php else : ?>
<?php endif; ?>