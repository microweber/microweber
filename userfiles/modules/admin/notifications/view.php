<?php 
only_admin_access();

$notification_id = (int) $params['notification_id'];
$notification_module = (int) $params['notification_module'];
?>

<?php 
if ($notification_module == 'contact_form') :
?>
<module type
<?php endif; ?>