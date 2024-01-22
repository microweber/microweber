<?php

/*

type: layout

name: Default

description: Default Picture List

*/

?>
<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>
    <script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/clean.css"); </script>
    <div class="mw-module-images<?php if ($no_img) { ?> no-image<?php } ?>">
        <div class="mw-pictures-clean" id="mw-gallery-<?php print $rand; ?>">
            <?php $count = -1; foreach ($data as $item): ?>
                <?php $count++; ?>

                <div class="mw-pictures-clean-item mw-pictures-clean-item-<?php print $item['id']; ?>">
                    <a href="<?php print ($item['filename']); ?>"
                       onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>);return false;">
                        <img src="<?php print thumbnail($item['filename'], 600); ?>"/>
                    </a>
                </div>
            <?php endforeach;  ?>
            <script><?php _ejs("gallery"); ?><?php print $rand; ?> = [
                        <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
                    <?php endforeach;  ?>
                ];</script>
        </div>
    </div>
<?php else : ?>
<?php endif; ?>
