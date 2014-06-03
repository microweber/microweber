<?php if(!is_admin()){

    return;

}
/*
$var3 = api('mw/utils/Backup/get_bakup_location');
 d($var3);

$backs = new \admin\backup\api();
$backs = $backs->get_bakup_location();
d($backs);




 $var3 = api('mw/utils/Backup/get_bakup_location');
 d($var3);

 $var3 = api('mw/utils/Backup/get_bakup_location');
 d($var3);

 $var3 = api('mw/utils/Backup/get_bakup_location');
 d($var3);*/

 ?>

<div id="backups_list" >
  <h2><?php _e("Available Backups"); ?></h2>
  <table   cellspacing="0" cellpadding="0" class="mw-ui-table">
    <thead>
      <tr>
        <th><?php _e("Filename"); ?> </th>
        <th><?php _e("Date"); ?></th>
        <th><?php _e("Time"); ?></th>
        <th><?php _e("Size"); ?></th>
        <th><?php _e("Download"); ?></th>
        <th><?php _e("Restore"); ?></th>
        <th><?php _e("Delete"); ?></th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td><?php _e("Filename"); ?></td>
        <td><?php _e("Date"); ?></td>
        <td><?php _e("Time"); ?></td>
        <td><?php _e("Size"); ?></td>
        <td><?php _e("Download"); ?></td>
        <td><?php _e("Restore"); ?></td>
        <td><?php _e("Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php $backups = mw('Utils/Backup')->get();
		  if(isarr($backups )): ?>
      <?php
	  $i = 1;
	   foreach($backups  as $item): ?>
      <tr class="mw_admin_backup_item_<?php print $i ?>">
        <td><?php print $item['filename']  ?></td>
          <td><span class="mw-date"><?php print $item['date']  ?></span></td>
          <td><span class="mw-date"><?php print $item['time']  ?></span></td>
          <td><span class="mw-date"><?php print file_size_nice( $item['size'])  ?></span></td>

          <td class="mw-backup-download"><a class="show-on-hover mw-icon-download" target="_blank" title="<?php _e("Download"); ?>" href="<?php print api_url('Utils/Backup/download'); ?>?file=<?php print $item['filename']  ?>"></a></td>
          <td class="mw-backup-restore"><a  title="<?php _e("Restore"); ?>" class="show-on-hover mw-icon-reload" href="javascript:mw.admin_backup.restore('<?php print $item['filename']  ?>')"></a></td>
          <td class="mw-backup-delete"><span title="<?php _e("Delete"); ?>" class="mw-icon-bin show-on-hover" onclick="mw.admin_backup.remove('<?php print $item['filename']  ?>', '.mw_admin_backup_item_<?php print $i ?>');"></span></td>
      </tr>
      <?php $i++; endforeach ; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
