<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        $('<?php echo '#' . $params['id']; ?> .timeline').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: false
        });

        $('<?php echo '#' . $params['id']; ?> .timeline .slickNext').on('click', function () {
            $('<?php echo '#' . $params['id']; ?> .timeline').slick('slickNext');
        });

        $('<?php echo '#' . $params['id']; ?> .timeline .slickPrev').on('click', function () {
            $('<?php echo '#' . $params['id']; ?> .timeline').slick('slickPrev');
        });
    });
</script>

<div class="timeline row">
    <?php if (is_array($data) AND $data): ?>
        <?php foreach ($data as $key => $event): ?>
            <div class="event col-md-3 <?php if ($key == 0): ?>active<?php endif; ?>">
                <i class="<?php print $event['icon']; ?>"></i>
                <span class="date"><?php print $event['date']; ?></span>
                <span class="desc"><?php print $event['description']; ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="event col-md-3 active">
            <i class="icon-large-road"></i>
            <span class="date">20 JUNI 2019</span>
            <span class="desc">Startdaag</span>
        </div>

        <div class="event col-md-3">
            <i class="icon-large-bike"></i>
            <span class="date">20 Juni 2019</span>
            <span class="desc">Startdaag</span>
        </div>

        <div class="event col-md-3">
            <i class="icon-large-bike"></i>
            <span class="date">20 Juni 2019</span>
            <span class="desc">Startdaag</span>
        </div>

        <div class="event col-md-3">
            <i class="icon-large-bike"></i>
            <span class="date">20 Juni 2019</span>
            <span class="desc">Startdaag</span>
        </div>
    <?php endif; ?>
</div>

<div class="row">
    <div class="col-xs-12">
        <button type="button" class="slickPrev btn btn-primary"><i class="fas fa-arrow-left"></i></button>
        <button type="button" class="slickNext btn btn-primary"><i class="fas fa-arrow-right"></i></button>
    </div>
</div>