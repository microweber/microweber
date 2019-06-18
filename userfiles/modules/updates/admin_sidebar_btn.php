<?php only_admin_access() ?>
<?php


$package_name='microweber/update';
$package_name='microweber';

//if(isset($_GET['only_updates']) and $_GET['only_updates']){
//    $package_name=false;
//
//}

$check = __mw_get_packages_that_has_updates($package_name);
 var_dump($check);
if($check and is_array($check)){
    ?>

    <a href="<?php print admin_url(); ?>view:packages/?only_updates=true">
        <span class="mai-market"><sup class="mw-notification-count"><?php print count($check); ?></sup></span> <strong>
             <?php _e("Updates"); ?>
        </strong>
    </a>

<?php
}


