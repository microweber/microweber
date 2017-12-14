<?php

/*

type: layout

name: bootstrap

description: Default

*/


 if($style == '' or $style == 'btn-default'){
     $style = 'btn--primary';
 }
?>
<script>
    mw.require('tools.js', true);
</script>

<a id="<?php print $btn_id ?>" href="<?php print $url; ?>" <?php if ($blank) {
    print ' target="_blank" ';
} ?> class="btn <?php print $style . ' ' . $size; ?>">
 <span class="btn__text">
        <?php print $text; ?>
    </span>
</a>