<?php if(!has_access()){error("must be admin");}; ?>

 <?php api('mw/utils/Backup/download'); ?>