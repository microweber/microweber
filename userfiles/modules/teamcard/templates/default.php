<style>
    .team-card-item-image {
        height: 185px;
        width: 185px;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    .team-card-item-bio {
        line-height: normal;
        font-size: 14px;
        font-weight: 300;
        text-overflow: ellipsis;
        word-wrap: break-word;
    }

    .team-card-item-name {
        font-weight: 600;
    }

</style>

<div class="team-card-holder d-flex flex-wrap">
    <?php
    $count = 0;

    if (isset($data) AND $data) {
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="team-card-item col-md-6 col-12 mb-3 overflow-hidden text-start px-md-4 my-5 d-flex flex-wrap">
               <div class="col-md-6">
                   <?php if ($slide['file']) { ?>
                       <div class="team-card-item-image m-auto rounded-circle" style="background-image: url('<?php print thumbnail($slide['file'], 200); ?>');"></div>

                   <?php } else { ?>
                       <div class="m-auto rounded-circle">

                           <img  width="185" height="185" src="<?php print modules_url() ?>teamcard/templates/default-image.svg"/>
                       </div>

                   <?php } ?>
               </div>

                <div class="col-md-6">
                    <div class="team-card-item-name py-4 fs-4" ><?php print array_get($slide, 'name'); ?></div>
                    <div class="team-card-item-position pb-3"> <?php print array_get($slide, 'role'); ?></div>
                    <a href="<?php print $slide['website']; ?>" target="_blank"> <?php print array_get($slide, 'website'); ?></a>
                    <div class="team-card-item-bio pt-3 italic"> <?php print array_get($slide, 'bio'); ?></div>
                </div>
            </div>
        <?php }
    } ?>
</div>

