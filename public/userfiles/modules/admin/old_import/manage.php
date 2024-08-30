<?php if(!is_admin()){error("must be admin");}; ?>
 <?php $here = $config['url_to_module']; ?>

<p> <?php _e("Supported formats"); ?>
    <a href="<?php print $here; ?>samples/sample.csv" class="mw-ui-link">csv</a>,
    <a href="<?php print $here; ?>samples/sample.json" class="mw-ui-link">json</a>,
    <a href="<?php print $here; ?>samples/sample.xlsx" class="mw-ui-link">xls</a>,
    <a href="<?php print $here; ?>samples/other_cms.zip" class="mw-ui-link"><?php _e('other files'); ?></a>.
</p>
<div id="backups_list" >

    <div class="alert alert-danger">This module is depricated.</div>

  <h2><?php _e("Avaiable import files"); ?></h2>
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
      <?php
      $backups = false;// mw('Microweber\Utils\Import')->get();
      if(is_array($backups )): ?>
      <?php
	  $i = 1;
	   foreach($backups  as $item): ?>
      <tr class="mw_admin_import_item_<?php print $i ?>">
        <td><?php print $item['filename']  ?></td>
          <td><span class="mw-date"><?php print $item['date']  ?></span></td>
          <td><span class="mw-date"><?php print $item['time']  ?></span></td>
          <td><span class="mw-date"><?php print file_size_nice( $item['size'])  ?></span></td>
          <td><a class="show-on-hover mw-ui-btn mw-ui-btn-blue" target="_blank" href="<?php print api_url('Microweber/Utils/Import/download'); ?>?file=<?php print $item['filename']  ?>"><?php _e("Download"); ?></a></td>
        <td>
        <!--<a class="show-on-hover mw-ui-btn mw-ui-btn-green" href="javascript:mw.admin_import.restore('<?php print $item['filename']  ?>')"><?php _e("Restore"); ?></a>-->

        <a class="show-on-hover mw-ui-btn mw-ui-btn-green" href="javascript:mw.confirm_import_file('<?php print $item['filename']  ?>')"><?php _e("Restore"); ?></a>


        </td>
        <td><a class="show-on-hover mw-ui-btn mw-ui-btn-red" href="javascript:mw.admin_import.remove('<?php print $item['filename']  ?>', '.mw_admin_import_item_<?php print $i ?>')"><?php _e("Delete"); ?></a></td>
      </tr>
      <?php $i++; endforeach ; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
