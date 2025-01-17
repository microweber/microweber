<?php

/*

type: layout

name: skin 14 - ocean

description: Skin 14 - ocean

*/

?>


<style>
    .ocean-14 {

        .shop-inner-big-image {
            img {
                display: flex;
                margin: 0 auto;
                width: auto;
                max-width: 50%;
            }
        }
    }
</style>

<?php

$pictureElementId = 'module-image-' . $params['id'];

if (is_array($data)): ?>
   <div class="new-skin-shop">


        <div class="shop-inner-gallery ocean-14 row">

            <div class="shop-inner-big-image position-relative ps-lg-0">

                <img src="<?php print $data[0]['filename'] ?>" id="<?php print $pictureElementId; ?>" />
            </div>

            <?php if (sizeof($data) > 1) { ?>
                <div class="shop-inner-gallery-thumbnails mt-4 d-flex">
                    <?php $count = -1; foreach ($data as $item): $count++; ?>
                        <a class="mx-0"
                           href="<?php print thumbnail($item['filename'], 1080, 1080); ?>"
                           onclick="setProductImage('<?php print $pictureElementId; ?>', '<?php print thumbnail($item['filename'], 1920, 1920); ?>', <?php print $count; ?>);return false;"
                           style="background-image: url('<?php print thumbnail ($item['filename'], 800, 800); ?>');">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php } ?>
        </div>
   </div>


    <script>

        var setProductImage = function (id, url, index) {
            var el = document.getElementById(id);
            el.dataset.index = index;
            var parent = el.parentElement;
            mw.spinner(({element: parent, size: 60, decorate: true})).show();
            mw.element({
                tag: 'img',
                props: {
                    src: url,
                    onload: function (){
                        el.src = url;
                        mw.spinner(({element: parent})).hide();
                    }
                }
            })
        }

        var gallery = <?php print json_encode($data); ?>;

        document.getElementById('<?php print $pictureElementId; ?>').addEventListener('click', function(){
            mw.gallery(gallery, Number(this.dataset.index || 0));
        });
    </script>

<?php endif; ?>
