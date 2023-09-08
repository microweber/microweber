<?php

$styles_attr = '';
$height = '';

if(isset($params['height'])){
    $height = $params['height'];
}

$styles = [];
if($height){
    $styles[] = 'height: '.$height.'';
}
if(!empty($styles)) {
    $styles_attr = 'style="' . implode(';', $styles) . '"';
}
?>
<div class="mw-le-spacer noelement nodrop no-settings inaccessibleModule" contenteditable="false" <?php print $styles_attr ?> id="spacer-item-<?php print $params['id'] ?>"></div>
