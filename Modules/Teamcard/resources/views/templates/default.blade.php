<?php
/*

type: layout

name: Default

description: Default

*/
?>


<style>
    .team-card-item-image {
        padding-top: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }


</style>

<div class="team-card-holder d-flex flex-wrap">
    <?php
    $count = 0;

    if (isset($data) AND $data) {
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="team-card-item col-md-6 col-12 mb-3 overflow-hidden text-start my-5 d-flex flex-wrap">
                <div class="col-md-6 pe-2">
                    <?php if ($slide['file']) { ?>
                        <div class="team-card-item-image rounded-circle" style="background-image: url('<?php print thumbnail($slide['file'], 800); ?>');"></div>

                    <?php } else { ?>
                        <div class="rounded-circle">

                            <img  width="300" height="300" src="<?php print modules_url() ?>teamcard/templates/default-image.svg"/>
                        </div>

                    <?php } ?>
                </div>

                <div class="col-md-6 ps-2">

                    <h3 class="team-card-item-name"><?php print array_get($slide, 'name'); ?></h3>

                    <p class="team-card-item-position"><?php print array_get($slide, 'role'); ?></p>

                    <a class="d-block mb-3" href="<?php print $slide['website']; ?>" target="_blank"> <?php print array_get($slide, 'website'); ?></a>

                    <p class="team-card-item-bio italic">
                        <?php print array_get($slide, 'bio'); ?>
                    </p>

                </div>
            </div>
        <?php }
    } ?>
</div>

