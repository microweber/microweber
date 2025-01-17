<?php

/*

type: layout

name: Skin-17

description: Skin-17

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
    .mw-pictures-17-text {
        position: absolute;
        left: 30px;
        bottom: 30px;
        transition: .5s ease-in-out;
        cursor: pointer;

        .mw-pictures-17-title {
            font-size: 22px;
            line-height: 1.5;
            color: #fff;
            transform: translateY(25px);
            transition: .5s ease-in-out;
            margin-bottom: 0;
        }

        .mw-pictures-17-description {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: .5s ease-in-out;
            color: #fff;

        }
    }

    .mw-pictures-17-wrapper:hover {
        .mw-pictures-17-text {
            .mw-pictures-17-title {
                transform: translateY(0);
            }

            .mw-pictures-17-description {
                opacity: 1;
                transform: translateY(0);

            }
        }

    }


</style>

<?php if (is_array($data)): ?>
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center" id="gallery-<?php print $rand; ?>">
        <?php if (sizeof($data) > 1) { ?>
            <?php $count = -1; foreach ($data as $item): $count++; ?>

                <?php
                $itemTitle = false;
                $itemDescription = false;
                $itemLink = false;
                $itemAltText = 'Open';
                if (isset($item['image_options']) and is_array($item['image_options'])) {
                    if (isset($item['image_options']['title'])) {
                        $itemTitle = $item['image_options']['title'];
                    }
                    if (isset($item['image_options']['caption'])) {
                        $itemDescription = $item['image_options']['caption'];
                    }
                    if (isset($item['image_options']['link'])) {
                        $itemLink = $item['image_options']['link'];
                    }
                    if (isset($item['image_options']['alt-text'])) {
                        $itemAltText = $item['image_options']['alt-text'];
                    }
                }
                ?>
                <div class="col-sm-6 col-md-4 p-0 mw-pictures-17-wrapper position-relative">
                    <a
                        data-index="<?php print $count; ?>"
                        href="<?php print $item['filename']; ?>">

                        <img   style="object-fit: cover; max-height: 500px; width: 100%; height: 100%;" src="<?php print $item['filename']; ?>" alt=""/>

                       <div class="mw-pictures-17-text">
                           <?php print $itemTitle ? '<h5 class="mw-pictures-17-title">'.$itemTitle.'</h5>' : '';  ?>
                           <p class="mw-pictures-17-description"><?php print $itemDescription; ?></p>
                       </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php } ?>
    </div>


<?php endif; ?>
