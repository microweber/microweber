<table  border="0" cellspacing="5" cellpadding="5" class="plugins_subnav">
  <tr>
    <td>
    <a class="ovalbutton<?php if($plugin_action == 'index') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>list"><span><img src="<?php print_the_static_files_url() ; ?>/icons/rss.png" alt=" " border="0">Manage RSS Feeds</span></a>
    </td>
    <td>
    <a class="ovalbutton<?php if($plugin_action == 'add') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>edit/id:0"><span><img src="<?php print_the_static_files_url() ; ?>/icons/rss_add.png" alt=" " border="0">Add new feed</span></a>
    </td>
    
    
     <td>
    <a class="ovalbutton<?php if($plugin_action == 'process_feeds_now') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>process_feeds_now"><span><img src="<?php print_the_static_files_url() ; ?>/icons/rss_go.png" alt=" " border="0">Process feeds now</span></a>
    </td>
    
  </tr>
</table>


