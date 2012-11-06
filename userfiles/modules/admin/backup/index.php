<? if(!is_admin()){error("must be admin");}; ?>
<? if(isset($_GET['do'])): ?>
<? include($_GET['do'].'.php'); ?>
<? else :?>
<? include('manage.php'); ?>
<? endif ;?>