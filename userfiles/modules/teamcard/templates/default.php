<style>
    .team-card-item-image {
        height: 120px;
        width: 120px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .team-card-item-bio {
        line-height: normal;
    }
</style>

<div class="team-card-holder">
    <?php
    $count = 0;

    if (isset($data) AND $data) {
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="team-card-item col-lg-4 col-md-6 col-12 d-block text-center mb-3 overflow-hidden float-start px-md-4 my-5">
                <?php if ($slide['file']) { ?>
                    <div class="team-card-item-image m-auto rounded-circle" style="background-image: url('<?php print thumbnail($slide['file'], 200); ?>');"></div>

                <?php } else { ?>
                <div class="m-auto rounded-circle">

                        <img  width="120" height="120" src="<?php print modules_url() ?>teamcard/templates/default-image.svg"/>
                </div>
                <?php } ?>


                <div class="team-card-item-name p-3"><?php print array_get($slide, 'name'); ?></div>
                <div class="team-card-item-position"> <?php print array_get($slide, 'role'); ?></div>
                <div class="team-card-item-website p-3"> <?php print array_get($slide, 'website'); ?></div>
                <div class="team-card-item-bio"> <?php print array_get($slide, 'bio'); ?></div>
            </div>
        <?php }
    } ?>
</div>

