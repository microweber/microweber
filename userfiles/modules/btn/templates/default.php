<?php

/*

type: layout

name: Default

description: Default

*/
?><script>
    mw.require('tools.js', true);
    mw.require('ui.css', true);
</script>

<a id="<?php print $btn_id ?>" href="<?php print $url; ?>" <?php if ($blank) {
    print ' target="_blank" ';
} ?> class="btn <?php print $style . ' ' . $size; ?>">

    <?php print $text; ?>
</a>