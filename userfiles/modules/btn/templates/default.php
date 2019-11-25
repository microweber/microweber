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

<?php if($action == 'submit'): ?>
<button type="submit" id="<?php print $btn_id ?>" class="btn <?php print $style . ' ' . $size; ?>" <?php print $attributes ?>>
    <?php print $text; ?>
</button>
<?php else: ?>
<a id="<?php print $btn_id ?>" href="<?php print $url; ?>" <?php if ($blank) { print ' target="_blank" '; } ?> class="btn <?php print $style . ' ' . $size; ?>" <?php print $attributes ?>>
    <?php print $text; ?>
</a>
<?php endif; ?>