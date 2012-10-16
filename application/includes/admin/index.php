<? if(is_admin() == false): ?>
<? error('Not logged as admin'); ?>
<? endif; ?>
<? include('dashboard.php'); ?>
