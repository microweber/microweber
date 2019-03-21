<?php
/*
 * 
 * USER FIELDS
 * 
 */

//$sliderEngine = explode('-', $module_template_clean)[0];

$engine = get_option('engine', $params['id']);
if ($engine) {
    $engine = $engine;
} elseif (isset($params['engine'])) {
    $engine = $params['engine'];
} else {
    $engine = 'bxslider';
}


//bxSlider & Slick
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

$responsive = get_option('responsive', $params['id']);
if ($responsive) {
    $responsive = $responsive;
} elseif (isset($params['responsive'])) {
    $responsive = $params['responsive'];
} else {
    $responsive = 'true';
}

$autoplay = get_option('autoplay', $params['id']);
if ($autoplay) {
    $autoplay = $autoplay;
} elseif (isset($params['autoplay'])) {
    $autoplay = $params['autoplay'];
} else {
    $autoplay = 'true';
}

$slidesPerRow = get_option('slides_per_row', $params['id']);
if ($slidesPerRow) {
    $slidesPerRow = $slidesPerRow;
} elseif (isset($params['slides_per_row'])) {
    $slidesPerRow = $params['slides_per_row'];
} else {
    $slidesPerRow = 1;
}

$slidesToShow = get_option('slides_to_show', $params['id']);
if ($slidesToShow) {
    $slidesToShow = $slidesToShow;
} elseif (isset($params['slides_to_show'])) {
    $slidesToShow = $params['slides_to_show'];
} else {
    $slidesToShow = 1;
}

$slidesToScroll = get_option('slides_to_scroll', $params['id']);
if ($slidesToScroll) {
    $slidesToScroll = $slidesToScroll;
} elseif (isset($params['slides_to_scroll'])) {
    $slidesToScroll = $params['slides_to_scroll'];
} else {
    $slidesToScroll = 1;
}

$centerMode = get_option('center_mode', $params['id']);
if ($centerMode) {
    $centerMode = $centerMode;
} elseif (isset($params['center_mode'])) {
    $centerMode = $params['center_mode'];
} else {
    $centerMode = 'true';
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
    $fade = 'true';
}

$focusOnSelect = get_option('focus_on_select', $params['id']);
if ($focusOnSelect) {
    $focusOnSelect = $focusOnSelect;
} elseif (isset($params['focus_on_select'])) {
    $focusOnSelect = $params['focus_on_select'];
} else {
    $focusOnSelect = 'true';
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