<?php
$style_attributes = [];
$style_attributes_overlay = [];
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
$background_color_option = get_option('background_color', $params['id']);
if($background_color_option){
    $background_color = $background_color_option;
}

if($background_color != ''){
    $style_attributes_overlay[] = 'background-color: '.$background_color;
}
$video_url = '';
if(isset($params['data-background-video'])){
    $background_color = $params['data-background-video'];
}
$background_video_option = get_option('background_video', $params['id']);
if($background_video_option){
    $video_url = $background_video_option;
}



$video_html  = '';
$video_attr_parent  = '';
if($video_url) {
    $video_html = '<video src="' . $video_url . '" autoplay muted loop playsinline></video>';
    $video_attr_parent  = ' data-mwvideo="'. $video_url.'" ';
}
if($style_attributes) {
    $style_attr_items = implode('; ', $style_attributes);
    $style_attr = 'style="' . $style_attr_items . '"';
}
$style_attr_overlay = '';
if($style_attributes_overlay) {
    $style_attributes_overlay_items = implode('; ', $style_attributes_overlay);
    $style_attr_overlay = 'style="' . $style_attributes_overlay_items . '"';
}

?>
<div class="mw-layout-background-block xxxedit no-settings inaccessibleModule"  field="layout-content-skin-63-<?php print $params['id'] ?>--background" rel="module">
    <div class="mw-layout-background-node" <?php print $video_attr_parent; ?>  <?php print $style_attr; ?>>
        <?php print $video_html; ?>
    </div>
    <div class="mw-layout-background-overlay" <?php print $style_attr_overlay; ?>>

    </div>
</div>
