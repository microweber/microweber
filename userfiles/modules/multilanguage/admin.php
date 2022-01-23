<?php
if (isset($_GET['dusk']) && $_GET['dusk'] == 1):
    include 'dusk.php';
endif;
?>

<?php if (!isset($params['live_edit'])): ?>
    <?php include($config['path_to_module'] . 'admin_backend.php'); ?>
<?php else: ?>
    <?php include($config['path_to_module'] . 'admin_live_edit.php'); ?>
<?php endif; ?>

