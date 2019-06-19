<?php only_admin_access() ?>

<?php







$check = __mw_check_core_system_update();
 if($check and is_array($check)){
    ?>

    <a class="active" href="<?php print admin_url(); ?>view:packages/?only_updates=true">
        <span class="mai-market"><sup class="mw-notification-count"><?php print count($check); ?></sup></span> <strong>
             <?php _e("Updates"); ?>
        </strong>
    </a>

<?php
}


