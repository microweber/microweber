<?php
/*
 *
 * USER FIELDS
 *
 */

$slides_xs = get_option('slides-xs', $params['id']);
if (($slides_xs === null or $slides_xs === false or $slides_xs == '') and isset($params['slides-xs'])) {
    $slides_xs = $params['slides-xs'];
} elseif ($slides_xs === null or $slides_xs === false or $slides_xs == '') {
    $slides_xs = '1';
}

$slides_sm = get_option('slides-sm', $params['id']);
if (($slides_sm === null or $slides_sm === false or $slides_sm == '') and isset($params['slides-sm'])) {
    $slides_sm = $params['slides-sm'];
} elseif ($slides_sm === null or $slides_sm === false or $slides_sm == '') {
    $slides_sm = '1';
}

$slides_md = get_option('slides-md', $params['id']);
if (($slides_md === null or $slides_md === false or $slides_md == '') and isset($params['slides-md'])) {
    $slides_md = $params['slides-md'];
} elseif ($slides_md === null or $slides_md === false or $slides_md == '') {
    $slides_md = '2';
}

$slides_lg = get_option('slides-lg', $params['id']);
if (($slides_lg === null or $slides_lg === false or $slides_lg == '') and isset($params['slides-lg'])) {
    $slides_lg = $params['slides-lg'];
} elseif ($slides_lg === null or $slides_lg === false or $slides_lg == '') {
    $slides_lg = '2';
}

$slides_xl = get_option('slides-lg', $params['id']);
if (($slides_xl === null or $slides_xl === false or $slides_xl == '') and isset($params['slides-lg'])) {
    $slides_xl = $params['slides-lg'];
} elseif ($slides_xl === null or $slides_xl === false or $slides_xl == '') {
    $slides_xl = '2';
}

$thumb_quality = 1920 / $slides_xl;

$pager = get_option('pager', $params['id']);
if ($pager) {
    $pager = $pager;
} elseif (isset($params['pager'])) {
    $pager = $params['pager'];
} else {
    $pager = 'true';
}


$controls = get_option('controls', $params['id']);
if ($controls) {
    $controls = $controls;
} elseif (isset($params['controls'])) {
    $controls = $params['controls'];
} else {
    $controls = 'true';
}

$loop = get_option('loop', $params['id']);
if ($loop) {
    $loop = $loop;
} elseif (isset($params['loop'])) {
    $loop = $params['loop'];
} else {
    $loop = 'true';
}

$adaptiveHeight = get_option('adaptive_height', $params['id']);
if ($adaptiveHeight) {
    $adaptiveHeight = $adaptiveHeight;
} elseif (isset($params['adaptive_height'])) {
    $adaptiveHeight = $params['adaptive_height'];
} else {
    $adaptiveHeight = 'true';
}

if (isset($params['speed'])) {
    $speed = $params['speed'];
} else {
    $speed = '5000';
}

//bxSlider

if (isset($params['mode'])) {
    $mode = $params['mode'];
} else {
    $mode = 'horizontal';
}

if (isset($params['hideControlOnEnd'])) {
    $hideControlOnEnd = $params['hideControlOnEnd'];
} else {
    $hideControlOnEnd = true;
}

//Slick
$pauseOnHover = get_option('pause_on_hover', $params['id']);
if ($pauseOnHover) {
    $pauseOnHover = $pauseOnHover;
} elseif (isset($params['pause_on_hover'])) {
    $pauseOnHover = $params['pause_on_hover'];
} else {
    $pauseOnHover = 'true';
}

$autoplay = get_option('autoplay', $params['id']);
if ($autoplay) {
    $autoplay = $autoplay;
} elseif (isset($params['autoplay'])) {
    $autoplay = $params['autoplay'];
} else {
    $autoplay = 'true';
}

$draggable = get_option('draggable', $params['id']);
if ($draggable) {
    $draggable = $draggable;
} elseif (isset($params['draggable'])) {
    $draggable = $params['draggable'];
} else {
    $draggable = 'true';
}

$fade = get_option('fade', $params['id']);
if ($fade) {
    $fade = $fade;
} elseif (isset($params['fade'])) {
    $fade = $params['fade'];
} else {
    $fade = 'false';
}

$focusOnSelect = get_option('focus_on_select', $params['id']);
if ($focusOnSelect) {
    $focusOnSelect = $focusOnSelect;
} elseif (isset($params['focus_on_select'])) {
    $focusOnSelect = $params['focus_on_select'];
} else {
    $focusOnSelect = 'true';
}

$centerPadding = get_option('center_padding', $params['id']);
if ($centerPadding) {
    $centerPadding = $centerPadding;
} elseif (isset($params['center_padding'])) {
    $centerPadding = $params['center_padding'];
} else {
    $centerPadding = '0px';
}


/*
 *
 * DEVEOPER FIELDS
 *
 */

if (isset($params['prev_text'])) {
    $prevText = $params['prev_text'];
} else {
    $prevText = 'Prev';
}

if (isset($params['next_text'])) {
    $nextText = $params['next_text'];
} else {
    $nextText = 'Next';
}

if (isset($params['prev_selector'])) {
    $prevSelector = $params['prev_selector'];
} else {
    $prevSelector = null;
}

if (isset($params['next_selector'])) {
    $nextSelector = $params['next_selector'];
} else {
    $nextSelector = null;
}

if (isset($params['pager_custom'])) {
    $pagerCustom = $params['pager_custom'];
} else {
    $pagerCustom = '';
}
?>

<script>mw.lib.require('slick');</script>
<script>
    $(document).ready(function () {
        var config = {
            dots: <?php print $pager; ?>,
            arrows: <?php print $controls; ?>,
            infinite: <?php print $loop; ?>,
            adaptiveHeight: <?php print $adaptiveHeight; ?>,
            autoplaySpeed: '<?php print $speed; ?>',
            speed: '500',
            pauseOnHover: <?php print $pauseOnHover; ?>,
            autoplay: <?php print $autoplay; ?>,
            slidesToShow: <?php print $slides_xl; ?>,
            slidesToScroll: <?php print $slides_xl; ?>,
            draggable: <?php print $draggable; ?>,
            fade: <?php print $fade; ?>,
            focusOnSelect: <?php print $focusOnSelect; ?>,
            centerPadding: '<?php print $centerPadding; ?>',
            responsive: [
                {
                    breakpoint: 1199,
                    settings: {
                        slidesToShow: <?php print $slides_lg; ?>,
                        slidesToScroll: <?php print $slides_lg; ?>
                    }
                }, {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: <?php print $slides_md; ?>,
                        slidesToScroll: <?php print $slides_md; ?>
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: <?php print $slides_sm; ?>,
                        slidesToScroll: <?php print $slides_sm; ?>
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: <?php print $slides_xs; ?>,
                        slidesToScroll: <?php print $slides_xs; ?>
                    }
                }
            ]
        };

        $('.slickslider', '#<?php print $params['id']; ?>').slick(config);
    });
</script>
