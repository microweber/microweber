<?php

/*

type: layout

name: Shop Inner 1 - Gallery

description: Skin 2

*/

?>
    <script src="<?php print template_url(); ?>assets/js/scripts.js"></script>
<?php if (is_array($data)): ?>

    <?php $rand = uniqid(); ?>

    <div class="text-center">
        <div class="slider" data-paging="false" data-arrows="true">
            <div id="mw-gallery-<?php print $rand; ?>">
                <ul class="slides">
                    <?php $count = -1;
                    foreach ($data as $item): ?>
                        <?php $count++; ?>
                        <li><a href="javascript:;" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;" class="pictures picture-<?php print $item['id']; ?>"><img
                                        alt=""
                                        src="<?php print thumbnail($item['filename'], 1600, 695, true); ?>"/></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>
<?php endif; ?>