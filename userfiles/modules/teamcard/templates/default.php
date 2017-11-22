<div class="team-card-holder">
    <style scoped="scoped">
        .team-card-item {
            width: 33.333%;
            margin: 0;
            display: block;
            background-size: cover;
            float: left;
            overflow: hidden;
            text-align: center;
            margin-bottom: 20px;
        }

        .team-card-item-image {
            display: block;
            height: 120px;
            width: 120px;
            margin: auto;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 120px;
        }

        .team-card-item-name {
            display: block;
            font-size: 21px;
            text-align: center;
            padding: 10px 15px;
        }

    </style>
    <?php
    $count = 0;
    if (isset($data)) {
        foreach ($data as $slide) {
            $count++;
            ?>
            <div class="team-card-item">
                <span class="team-card-item-image" style="background-image: url('<?php print thumbnail($slide['file'], 200); ?>');"></span>
                <span class="team-card-item-name"><?php print array_get($slide, 'name'); ?></span>
                <span class="team-card-item-position"> <?php print array_get($slide, 'role'); ?></span>
                <span class="team-card-item-bio"> <?php print array_get($slide, 'bio'); ?></span>
            </div>
        <?php }
    } ?>
</div>
 
