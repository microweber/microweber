<?php

$background_color = '';

if(isset($params['data-background-color'])){
    $background_color = $params['data-background-color'];
}
$style_attr = '';

if($background_color != ''){
    $style_attr = ' style="background-color: '.$background_color.'" ';
}

?>
<div class="mw-layout-background-block edit no-settings inaccessibleModule"  field="layout-content-skin-63-<?php print $params['id'] ?>--background" rel="module">
    <div class="mw-layout-background-node" <?php print $style_attr; ?>>

    </div>
    <div class="mw-layout-background-overlay">

    </div>
</div>
