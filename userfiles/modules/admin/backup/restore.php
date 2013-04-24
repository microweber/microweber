<? if(!is_admin()){error("must be admin");}; ?>

<h3>Restore Log</h3>
<table cellpadding="0" cellspacing="0">
  <td><textarea rows="100" cols="100"><? api('mw/utils/Backup/restore'); ?>
</textarea></td>
</table>
<br />
