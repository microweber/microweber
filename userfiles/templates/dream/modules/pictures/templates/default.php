<?php

/*

type: layout

name: Default

description: Default

*/

?>
<?php if (is_array($data)): ?>

    <?php $rand = uniqid(); ?>

    <div class="text-center">
        <div id="mw-gallery-<?php print $rand; ?>">
            <div class="slider" data-paging="false">
                <ul class="slides">
                    <?php $count = -1;
                    foreach ($data as $item): ?>
                        <?php $count++; ?>
                        <li><a href="javascript:;" onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;" class="pictures picture-<?php print $item['id']; ?>"><img
                                    class="blog-post__hero box-shadow" alt="" src="<?php print thumbnail($item['filename'], 1200, 600, true); ?>"/></a></li>
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
        $(document).ready(function () {
            mr.sliders.documentReady($)
        })
    </script>
<?php endif; ?>