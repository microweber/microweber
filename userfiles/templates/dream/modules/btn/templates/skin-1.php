<?php

/*

type: layout

name: Skin 1

description: Skin 1

*/
?>

<a class="btn" id="<?php print $btn_id ?>" href="<?php print $url; ?>" <?php if ($blank) {
    print ' target="_blank" ';
} ?>>
    <span class="btn__text"><?php print $text; ?></span>
</a>