<?php if(!is_admin()){error("must be admin");}; ?>

<h3><?php _e("Restore Log"); ?></h3>
<table cellpadding="0" cellspacing="0">
  <td><textarea rows="100" cols="100"><?php api('mw/utils/Backup/restore'); ?>
</textarea></td>
</table>
<br />
