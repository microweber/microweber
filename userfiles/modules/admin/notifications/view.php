<?php 
only_admin_access();

$notification_id = (int) $params['notification_id'];
$notification_module = $params['notification_module'];
?>

<?php
if ($notification_module === 'contact_form') :
?>
<module type="contact_form/view" notification_id="<?php echo $notification_id; ?>" />
<?php else: ?>

<?php endif; ?>