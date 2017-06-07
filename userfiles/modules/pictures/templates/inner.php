<?php

/*

type: layout

name: Inner

description: Inner Slider

*/

?>

<?php if (is_array($data)): ?>
    <?php $id = "slider-" . uniqid(); ?>
    <div class=" mw-rotator mw-rotator-template-inner" id="<?php print $id; ?>">
        <div class=" mw-gallery-holder">
            <?php foreach ($data as $item): ?>
                <div class="mw-gallery-item mw-gallery-item-<?php print $item['id']; ?>">
                    <img class="autoscale-x" src="<?php print thumbnail($item['filename'], 1400); ?>" alt=""/>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script type="text/javascript">
        mw.moduleCSS("<?php print $config['url_to_module']; ?>css/style.css");
        mw.require("<?php print $config['url_to_module']; ?>js/api.js", true);
        $(document).ready(function () {
            var el = mwd.getElementById('<?php print $id; ?>');
            var module = mw.tools.firstParentWithClass(el, 'module');
        });

    </script>
    <script type="text/javascript">
        Rotator = null;
        $(document).ready(function () {
            if ($('#<?php print $id; ?>').find('.mw-gallery-item').length > 1) {
                Rotator = mw.rotator('#<?php print $id; ?>');
                if (!Rotator) return false;
                Rotator.options({
                    paging: true,
                    pagingMode: "thumbnails",
                    next: true,
                    prev: true,
                    reflection: false
                });
            }
        });
    </script>
<?php else : ?>
<?php endif; ?>