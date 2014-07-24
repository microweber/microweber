<?php

/*

type: layout

name: Masonry

description: Masonry

*/

?>
<?php if (is_array($data)): ?>
    <?php $rand = uniqid(); ?>
    <script>mw.require("tools.js", true);</script>
    <script>mw.require("<?php print $config['url_to_module']; ?>js/masonry.pkgd.min.js", true); </script>
    <script>mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css"); </script>
    <script>
        mw._masons = mw._masons || [];
        $(document).ready(function () {
            var m = mw.$('#mw-gallery-<?php print $rand; ?>');
            m.masonry({
                "itemSelector": '.masonry-item',
                "gutter": 5
            });
            mw._masons.push(m);
            if (typeof mw._masons_binded === 'undefined') {
                mw._masons_binded = true;
                setInterval(function () {
                    var l = mw._masons.length, i = 0;
                    for (; i < l; i++) {
                        var _m = mw._masons[i];
                        if (mw.$(".masonry-item", _m[0]).length > 0) {
                            _m.masonry({
                                "itemSelector": '.masonry-item',
                                "gutter": 5
                            });
                        }
                    }
                }, 500);
            }

        });
    </script>
    <div class="mw-images-template-masonry" id="mw-gallery-<?php print $rand; ?>"
         style="position: relative;width: 100%;">
        <?php $count = -1; foreach ($data as $item): ?>
            <?php $count++; ?>
            <div class="masonry-item"
                 onclick="mw.gallery(gallery<?php print $rand; ?>, <?php print $count; ?>)">
                <img src="<?php print thumbnail($item['filename'], 300); ?>" width="100%"/>
                <?php if ($item['title'] != '') { ?>
                    <div class="masonry-item-description"><?php print $item['title']; ?></div>
                <?php } ?>
            </div>
        <?php endforeach;  ?>
    </div>
    <script>gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print $item['filename']; ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];</script>
<?php else : ?>
<?php endif; ?>