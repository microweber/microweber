<?php
/*
 *
 * USER FIELDS
 *
 */
include('skins_variables.php');

$engine_by_module_template = $module_template;
$engine_by_module_template = explode('-', $engine_by_module_template);
$engine_by_module_template = $engine_by_module_template[0];
$engine = get_module_option('engine', $params['id']);
if ($engine) {
    $engine = $engine;
} elseif (isset($params['engine'])) {
    $engine = $params['engine'];
} elseif (isset($engine_by_module_template)) {
    $engine = $engine_by_module_template;
} else {
    $engine = 'bxslider';
}

$slides_xs = get_module_option('slides-xs', $params['id']);
if ($slides_xs) {
    $slides_xs = $slides_xs;
} elseif (isset($params['data-slides-xs'])) {
    $slides_xs = $params['data-slides-xs'];
} else {
    $slides_xs = 1;
}

$slides_sm = get_module_option('slides-sm', $params['id']);
if ($slides_sm) {
    $slides_sm = $slides_sm;
} elseif (isset($params['data-slides-sm'])) {
    $slides_sm = $params['data-slides-sm'];
} else {
    $slides_sm = 2;
}

$slides_md = get_module_option('slides-md', $params['id']);
if ($slides_md) {
    $slides_md = $slides_md;
} elseif (isset($params['data-slides-md'])) {
    $slides_md = $params['data-slides-md'];
} else {
    $slides_md = 3;
}

$slides_lg = get_module_option('slides-lg', $params['id']);
if ($slides_lg) {
    $slides_lg = $slides_lg;
} elseif (isset($params['data-slides-lg'])) {
    $slides_lg = $params['data-slides-lg'];
} else {
    $slides_lg = 4;
}

$slides_xl = get_module_option('slides-xl', $params['id']);
if ($slides_xl) {
    $slides_xl = $slides_xl;
} elseif (isset($params['data-slides-xl'])) {
    $slides_xl = $params['data-slides-xl'];
} else {
    $slides_xl = 4;
}

$thumb_quality_x = 8000;
$thumb_quality_x = $thumb_quality_x / $slides_xl;

$thumb_quality_y = 8000;
$thumb_quality_y = $thumb_quality_y / $slides_xl;

//bxSlider & Slick
$pager = get_module_option('pager', $params['id']);
if ($pager) {
    $pager = $pager;
} elseif (isset($params['pager'])) {
    $pager = $params['pager'];
} else {
    $pager = 'true';
}


$controls = get_module_option('controls', $params['id']);
if ($controls) {
    $controls = $controls;
} elseif (isset($params['controls'])) {
    $controls = $params['controls'];
} else {
    $controls = 'true';
}

$loop = get_module_option('loop', $params['id']);
if ($loop) {
    $loop = $loop;
} elseif (isset($params['loop'])) {
    $loop = $params['loop'];
} else {
    $loop = 'true';
}

$adaptiveHeight = get_module_option('adaptive_height', $params['id']);
if ($adaptiveHeight) {
    $adaptiveHeight = $adaptiveHeight;
} elseif (isset($params['adaptive_height'])) {
    $adaptiveHeight = $params['adaptive_height'];
} else {
    $adaptiveHeight = 'true';
}

$speed = get_module_option('speed', $params['id']);
if ($speed) {
    $speed = $speed;
} elseif (isset($params['speed'])) {
    $speed = $params['speed'];
} else {
    $speed = '1000';
}

$autoplay = get_module_option('autoplay', $params['id']);

if ($autoplay) {
    $autoplay = $autoplay;
} elseif (isset($params['autoplay'])) {
    $autoplay = $params['autoplay'];
} else {
    $autoplay = 'true';
}

$autoplaySpeed = get_module_option('autoplay_speed', $params['id']);
if ($autoplaySpeed) {
    $autoplaySpeed = $autoplaySpeed;
} elseif (isset($params['autoplay_speed'])) {
    $autoplaySpeed = $params['autoplay_speed'];
} else {
    $autoplaySpeed = '4000';
}

$pauseOnHover = get_module_option('pauseOnHover', $params['id']);
if ($pauseOnHover) {
    $pauseOnHover = $pauseOnHover;
} elseif (isset($params['pauseOnHover'])) {
    $pauseOnHover = $params['pauseOnHover'];
} else {
    $pauseOnHover = true;
}

$pauseOnHover = get_module_option('pause_on_hover', $params['id']);
if ($pauseOnHover) {
    $pauseOnHover = $pauseOnHover;
} elseif (isset($params['pause_on_hover'])) {
    $pauseOnHover = $params['pause_on_hover'];
} else {
    $pauseOnHover = 'true';
}

//bxSlider
$mode = get_module_option('mode', $params['id']);
if ($mode) {
    $mode = $mode;
} elseif (isset($params['mode'])) {
    $mode = $params['mode'];
} else {
    $mode = 'horizontal';
}

$hideControlOnEnd = get_module_option('hideControlOnEnd', $params['id']);
if ($hideControlOnEnd) {
    $hideControlOnEnd = $hideControlOnEnd;
} else if (isset($params['hideControlOnEnd'])) {
    $hideControlOnEnd = $params['hideControlOnEnd'];
} else {
    $hideControlOnEnd = true;
}

//Slick
$responsive = get_module_option('responsive', $params['id']);
if ($responsive) {
    $responsive = $responsive;
} elseif (isset($params['responsive'])) {
    $responsive = $params['responsive'];
} else {
    $responsive = 'true';
}

$slidesPerRow = get_module_option('slides_per_row', $params['id']);
if ($slidesPerRow) {
    $slidesPerRow = $slidesPerRow;
} elseif (isset($params['slides_per_row'])) {
    $slidesPerRow = $params['slides_per_row'];
} else {
    $slidesPerRow = 1;
}

$centerMode = get_module_option('center_mode', $params['id']);
if ($centerMode) {
    $centerMode = $centerMode;
} elseif (isset($params['center_mode'])) {
    $centerMode = $params['center_mode'];
} else {
    $centerMode = 'true';
}
$centerPadding = get_module_option('center_padding', $params['id']);
if ($centerPadding) {
    $centerPadding = $centerPadding;
} elseif (isset($params['center_padding'])) {
    $centerPadding = $params['center_padding'];
} else {
    $centerPadding = '50px';
}

$draggable = get_module_option('draggable', $params['id']);
if ($draggable) {
    $draggable = $draggable;
} elseif (isset($params['draggable'])) {
    $draggable = $params['draggable'];
} else {
    $draggable = 'true';
}

$fade = get_module_option('fade', $params['id']);
if ($fade) {
    $fade = $fade;
} elseif (isset($params['fade'])) {
    $fade = $params['fade'];
} else {
    $fade = 'false';
}

$focusOnSelect = get_module_option('focus_on_select', $params['id']);
if ($focusOnSelect) {
    $focusOnSelect = $focusOnSelect;
} elseif (isset($params['focus_on_select'])) {
    $focusOnSelect = $params['focus_on_select'];
} else {
    $focusOnSelect = 'true';
}

$touchEnabled = get_module_option('touch_enabled', $params['id']);
if ($touchEnabled) {
    $touchEnabled = $touchEnabled;
} elseif (isset($params['touch_enabled'])) {
    $touchEnabled = $params['touch_enabled'];
} else {
    $touchEnabled = false;
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
