<?php if(!is_admin()){error("must be admin");}; ?>

 <?php api('mw/utils/Backup/download'); ?>