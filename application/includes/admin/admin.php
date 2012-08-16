<?php include (ADMIN_VIEWS_PATH . 'header.php'); ?>
<? if(is_admin() == false): ?>
<module type="login" />
<? else: ?>
<module type="pages_menu" append_to_link="/editmode:y" />


<br />
<br />
<br />
<br />

<a href="<? print site_url('api/set_language/en') ?>">en</a>
<a href="<? print site_url('api/set_language/bg') ?>">bg</a>
<? endif; ?>

<?php	include (ADMIN_VIEWS_PATH . 'footer.php'); ?>