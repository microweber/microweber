<?php

$styles_attr = '';
$height = '';

if(isset($params['height'])){
    $height = trim($params['height']);
}
if(!$height ){
    $height = '20px';
}
$optionHeight = get_option('height', $params['id']);
if($optionHeight){
    $height = $optionHeight;
}

$styles = [];
if($height){
    $styles[] = 'height: '.$height.'';
}
if(!empty($styles)) {
    $styles_attr = 'style="' . implode(';', $styles) . '"';
}



?><div class="mw-le-spacer noelement nodrop no-settings inaccessibleModule" data-for-module-id="<?php print $params['id'] ?>" contenteditable="false" <?php print $styles_attr ?> id="spacer-item-<?php print $params['id'] ?>"></div>
