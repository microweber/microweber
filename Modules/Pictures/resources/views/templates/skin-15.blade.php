<?php

/*

type: layout

name: Skin-15

description: Skin-15

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

<style>
    #gallery-<?php print $rand; ?> .background-image-holder {
        min-height: 500px;
        display: block;

    }

    #gallery-<?php print $rand; ?> .selector:nth-child(odd) .background-image-holder {
        min-height: 400px;
        display: block;
    }



</style>

<?php if (is_array($data)): ?>



    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center align-items-center" id="gallery-<?php print $rand; ?>">
        <?php if (sizeof($data) > 1) { ?>
            <?php $count = -1; foreach ($data as $item): $count++; ?>
                <div class="selector col-sm-6 col-lg-4 p-3">
                    <a class="background-image-holder" style="background-image: url(<?php print thumbnail($item['filename'], 1080, 1080, true); ?>)"
                        data-index="<?php print $count; ?>"
                        href="<?php print thumbnail($item['filename'], 1080, 1080); ?>">

                    </a>

                </div>
            <?php endforeach; ?>
        <?php } ?>
    </div>


<?php endif; ?>


