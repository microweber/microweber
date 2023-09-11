<?php

$background_color = '';

if(isset($params['data-background-color'])){
    $background_color = $params['data-background-color'];
}
$style_attr = '';

if($background_color != ''){
    $style_attr = ' style="background-color: '.$background_color.'" ';
}
$video_url = '';
if(isset($params['data-video-url'])){
    $background_color = $params['data-video-url'];
}

$video_html  = '';
if($video_url) {
    $video_html = '<video src="' . $video_url . '" autoplay muted></video>';
}
// bgNode = bg.querySelector('.mw-layout-background-node')
// bgOverlay = bg.querySelector('.mw-layout-background-overlay')
?>
<div class="mw-layout-background-block edit no-settings inaccessibleModule"  field="layout-content-skin-63-<?php print $params['id'] ?>--background" rel="module">
    <div class="mw-layout-background-node" <?php print $style_attr; ?>>
        <?php print $video_html; ?>
    </div>
    <div class="mw-layout-background-overlay">

    </div>
</div>
