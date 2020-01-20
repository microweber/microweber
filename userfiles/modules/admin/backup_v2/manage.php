<?php if(!is_admin()){error("must be admin");}; ?>
 <?php $here = $config['url_to_module']; ?>
 
<p><?php _e("Microweber supports importing content from"); ?>
    <a href="<?php print $here; ?>samples/sample.csv" class="mw-ui-link">csv</a>,
    <a href="<?php print $here; ?>samples/sample.json" class="mw-ui-link">json</a>,
    <a href="<?php print $here; ?>samples/sample.xlsx" class="mw-ui-link">xls</a>,
    <a href="<?php print $here; ?>samples/other_cms.zip" class="mw-ui-link"><?php _e('other files'); ?></a>.
</p>
<div id="backups_list" >
  <h2><?php _e("Avaiable import files"); ?></h2>
  <table   cellspacing="0" cellpadding="0" class="mw-ui-table">
    <thead>
      <tr>
        <th style="width:300px;"><?php _e("Filename"); ?> </th>
        <th><?php _e("Date"); ?></th>
        <th><?php _e("Time"); ?></th>
        <th><?php _e("Size"); ?></th>
        <th><?php _e("Download"); ?></th>
        <th><?php _e("Import"); ?></th>
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
        <td><?php _e("Import"); ?></td>
        <td><?php _e("Delete"); ?></td>
      </tr>
    </tfoot>
    <tbody>
      <?php 
      $backups = mw('Microweber\Utils\BackupV2')->get();
		  if(isarr($backups )):
		?>
      <?php
	  $i = 1;
	   foreach($backups  as $item): ?>
      <tr class="mw_admin_import_item_<?php print $i ?>">
        <td><?php print $item['filename']  ?></td>
          <td><span class="mw-date"><?php print $item['date']  ?></span></td>
          <td><span class="mw-date"><?php print $item['time']  ?></span></td>
          <td><span class="mw-date"><?php print file_size_nice( $item['size'])  ?></span></td>
          <td><a class="show-on-hover mw-ui-btn mw-ui-btn-blue" target="_blank" href="<?php print api_url('Microweber/Utils/BackupV2/download'); ?>?file=<?php print $item['filename']  ?>"><?php _e("Download"); ?></a></td>
        <td>
      
        <a class="show-on-hover mw-ui-btn mw-ui-btn-green" href="javascript:mw.backup_import.import('<?php print $item['filename']  ?>')"><?php _e("Import"); ?></a>
        
        </td>
        <td><a class="show-on-hover mw-ui-btn mw-ui-btn-red" href="javascript:mw.backup_import.remove('<?php print $item['filename']  ?>', '.mw_admin_import_item_<?php print $i ?>')"><?php _e("Delete"); ?></a></td>
      </tr>
      <?php $i++; endforeach ; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
