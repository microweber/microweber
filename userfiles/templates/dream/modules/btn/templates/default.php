<?php

/*

type: layout

name: Default

description: Default

*/
?>

<script>
    mw.require('tools.js', true);
    mw.require('ui.css', true);
</script>

<a class="btn btn--primary <?php print $style . ' ' . $size; ?>" id="<?php print $btn_id ?>" href="<?php print $url; ?>" <?php if ($blank) { print ' target="_blank" '; } ?>>
    <span class="btn__text">
        <?php print $text; ?>
    </span>
</a>