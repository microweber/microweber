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

$slides_xs = get_option('slides-xs', $params['id']);
if ($slides_xs === null OR $slides_xs === false OR $slides_xs == '') {
    $slides_xs = '1';
}

$slides_sm = get_option('slides-sm', $params['id']);
if ($slides_sm === null OR $slides_sm === false OR $slides_sm == '') {
    $slides_sm = '2';
}

$slides_md = get_option('slides-md', $params['id']);
if ($slides_md === null OR $slides_md === false OR $slides_md == '') {
    $slides_md = '3';
}

$slides_lg = get_option('slides-lg', $params['id']);
if ($slides_lg === null OR $slides_lg === false OR $slides_lg == '') {
    $slides_lg = '4';
}

$slides_xl = get_option('slides-xl', $params['id']);
if ($slides_xl === null OR $slides_xl === false OR $slides_xl == '') {
    $slides_xl = '4';
}

$thumb_quality_x = '1920';
$thumb_quality_x = $thumb_quality_x / $slides_xl;

$thumb_quality_y = '1920';
$thumb_quality_y = $thumb_quality_y / $slides_xl;

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