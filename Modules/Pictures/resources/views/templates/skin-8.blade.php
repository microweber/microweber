<?php

/*

type: layout

name: Skin-8

description: Skin-8

*/

?>
<?php $rand = uniqid(); ?>


<script>



    var gallery<?php $rand ?> = function (id) {
        var el = mwd.getElementById(id);
        if(el && !el.__gallery) {
            el.__gallery = [];
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
        gallery<?php $rand ?>('gallery-<?php print $rand; ?>');
    });
    $(document).ready(function () {
        gallery<?php $rand ?>('gallery-<?php print $rand; ?>');
    });
</script>

<?php if (is_array($data)): ?>



    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center" id="gallery-<?php print $rand; ?>">
        <?php if (sizeof($data) > 1) { ?>
            <?php $count = -1; foreach ($data as $item): $count++; ?>
                <div class="col-sm-6 col-md-4 col-lg-3 pb-3 px-2">
                    <a
                        data-index="<?php print $count; ?>"
                        href="<?php print thumbnail($item['filename'], 1080, 1080); ?>">
                        <div class="img-as-background   mh-400">
                            <img   src="<?php print thumbnail($item['filename'], 1080, 1080, true); ?>"/>
                        </div>
                    </a>


                </div>
            <?php endforeach; ?>
        <?php } ?>
    </div>


<?php endif; ?>


