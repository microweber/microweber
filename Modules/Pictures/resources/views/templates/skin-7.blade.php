<?php

/*

type: layout

name: Pictures skin 7 - Justify

description: Pictures Skin 7 - Justify

*/

?>

<?php $rand = uniqid(); ?>




<script>
    mw.require('{{ template_url() }}js/justified/justifiedGallery.js');
    mw.require('{{ template_url() }}js/justified/justifiedGallery.min.css');
</script>
<script>



    var masonry = function (id) {
        var el = mwd.getElementById(id);
        if(el && !el.__gallery) {
            el.__gallery = [];
            var aa = $(el).justifiedGallery({
                sizeRangeSuffixes:
                    {
                        'lt100': '',
                        'lt240': '',
                        'lt320': '',
                        'lt500': '',
                        'lt640': '',
                        'lt1024': ''
                    },
                rowHeight: 350,
                lastRow: 'justify',
                margins: 7
            });
            Array.from(el.querySelectorAll('a')).forEach(function (link){
                el.__gallery.push({
                    url: link.href
                })
                link.addEventListener('click', function (e){
                    e.preventDefault()
                    mw.gallery(el.__gallery, Number(this.dataset.index || 0));
                })
            })
        }
    }

    $(window).on('load', function () {
        masonry('gallery-<?php print $rand; ?>');
    });
    $(document).ready(function () {
        masonry('gallery-<?php print $rand; ?>');
    });
</script>

<?php

if (is_array($data)): ?>

    <div class="module-posts-template-justified module-posts-template-justifiedfull" id="gallery-<?php print $rand; ?>">
        <?php if (sizeof($data) > 1) { ?>
            <?php $count = -1; foreach ($data as $item): $count++; ?>
                <a
                    data-index="<?php print $count; ?>"
                    href="<?php print thumbnail($item['filename'], 1080, 1080); ?>">
                    <img   src="<?php print thumbnail($item['filename'], 600, 600); ?>" alt=""/>
                </a>
            <?php endforeach; ?>
        <?php } ?>
    </div>

<?php endif; ?>


