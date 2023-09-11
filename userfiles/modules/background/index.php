<?php
$style_attributes = [];
$background_color = '';

if(isset($params['data-background-color'])){
    $background_color = $params['data-background-color'];
}
$background_image = '';

$background_image_option = get_option('background_image', $params['id']);

if(isset($params['data-background-image'])){
    $background_image = $params['data-background-image'];
}
if($background_image_option){
    $background_image = $background_image_option;
}

$background_image_attr_style  = '';
if($background_image){
    $style_attributes[] = 'background-image: url('.$background_image.')';
 }

$style_attr = '';

if($background_color != ''){
    $style_attributes[] = 'background-color: '.$background_color;
}
$video_url = '';
if(isset($params['data-video-url'])){
    $background_color = $params['data-video-url'];
}
$background_video_option = get_option('background_video', $params['id']);
if($background_video_option){
    $video_url = $background_video_option;
}



$video_html  = '';
$video_attr_parent  = '';
if($video_url) {
    $video_html = '<video src="' . $video_url . '" autoplay muted></video>';
    $video_attr_parent  = ' mwvideo ';
}
if($style_attributes) {
    $style_attr_items = implode('; ', $style_attributes);
    $style_attr = 'style="' . $style_attr_items . '"';
}
// bgNode = bg.querySelector('.mw-layout-background-node')
// bgOverlay = bg.querySelector('.mw-layout-background-overlay')
?>
<div class="mw-layout-background-block xxxedit no-settings inaccessibleModule"  field="layout-content-skin-63-<?php print $params['id'] ?>--background" rel="module">
    <div class="mw-layout-background-node" <?php print $video_attr_parent; ?>  <?php print $style_attr; ?>>
        <?php print $video_html; ?>
    </div>
    <div class="mw-layout-background-overlay">

    </div>
</div>
