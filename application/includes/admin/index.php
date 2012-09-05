<? if(is_admin() == false): ?>
<? error('Not logged as admin'); ?>
<? endif; ?>
<module type="pages_menu" append_to_link="/editmode:y" />
