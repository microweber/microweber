<?php
// d($config['license']);
if (isset($config['license']['product_activate_link'])) :
    ?>
<div class="mw-module-needs-activation" contenteditable="false">
        <a  href="<?php print $config['license']['product_activate_link'] ?>" target="_blank"><b><?php _e('You must activate this module'); ?></b></a><p><?php print $config['license']['product_activate_link'] ?></p>
    </div>
<?php endif; ?>

