<? if(!is_admin()){error("must be admin");}; ?>
<?
/*
$var3 = api('admin/backup/api/get_bakup_location');
 d($var3);
 
 $var3 = api('admin/backup/api/get_bakup_location');
 d($var3);
 
 $var3 = api('admin/backup/api/get_bakup_location');
 d($var3);
 
 $var3 = api('admin/backup/api/get_bakup_location');
 d($var3);*/

 ?>
<div id="backups_list" >
  <h2>Available Database Backups</h2>
  <table   cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
    <thead>
      <tr>
        <th>Filename </th>
        <th>Date</th>
        <th>Time</th>
        <th>Download</th>
        <th>Restore</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <td>Filename</td>
        <td>Date</td>
        <td>Time</td>
        <td>Download</td>
        <td>Restore</td>
        <td>Delete</td>
      </tr>
    </tfoot>
    <tbody>
      <? $backups = api('admin/backup/api/get');
		  if(isarr($backups )): ?>
      <? foreach($backups  as $item): ?>
      <tr>
        <td><? print $item['filename']  ?></td>
        <td><? print $item['date']  ?></td>
        <td><? print $item['time']  ?></td>
        <td><a class="mw-ui-admin-table-show-on-hover mw-ui-btn" href="<? print $config['url']; ?>?backup_action=dl&file=<? print $item['filename']  ?>">Download</a></td>
        <td><a class="mw-ui-admin-table-show-on-hover mw-ui-btn" href="<? print $config['url']; ?>?backup_action=restore&file=<? print $item['filename']  ?>">Restore</a></td>
        <td><a class="mw-ui-admin-table-show-on-hover mw-ui-btn" href="<? print $config['url']; ?>?backup_action=delete&file=<? print $item['filename']  ?>">Delete</a></td>
      </tr>
      <? endforeach ; ?>
      <? endif; ?>
    </tbody>
  </table>
</div>
