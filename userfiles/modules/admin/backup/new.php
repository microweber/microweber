<?php if(!has_access()){error("must be admin");}; ?>
 <?php 
$backups = api('mw/utils/Backup/create');
 
 ?>