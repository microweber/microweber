<?php

/*

type: layout

name: in-block

description: in-block

*/
?>
<div class="mw-layout-background-block" style="z-index: -1000;">
    <div class="mw-layout-background-node" <?php print $video_attr_parent; ?>  <?php print $style_attr; ?>>
        <?php print $video_html; ?>
    </div>
    <div class="mw-layout-background-overlay" <?php print $style_attr_overlay; ?>>

    </div>
</div>
