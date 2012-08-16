<?php include (ADMIN_VIEWS_PATH . 'header.php'); ?>
<? if(is_admin() == false): ?>
<module type="login" />
<? else: ?>
<module type="pages_menu" append_to_link="/editmode:y" />
<? endif; ?>
<?php	include (ADMIN_VIEWS_PATH . 'footer.php');
?>