<? if(!is_admin()){error("must be admin");}; ?>
 <? 
$backups = api('mw/utils/Backup/create');
 
 ?>