

<?php

    foreach (mw()->module_manager->get_modules('all=1') as $module):


        ?>


   <module type="<?php echo $module['module'] ?>" />

<?php endforeach; ?>
