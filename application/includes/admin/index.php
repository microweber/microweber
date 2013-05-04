<?php if(is_admin() == false): ?>
<?php error('Not logged as admin'); ?>
<?php endif; ?>
<?php include('dashboard.php'); ?>
