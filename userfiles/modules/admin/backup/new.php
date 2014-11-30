<?php if(!is_admin()){error("must be admin");}; ?>
 <?php 
$backups = api('mw/utils/Backup/create');
 
 ?>