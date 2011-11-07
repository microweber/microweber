<div id="subheader">
  <ul>
    <li><a class="ovalbutton<?php if($plugin_action == 'index') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>list"><img src="<?php print_the_static_files_url() ; ?>/icons/rss.png" alt=" " border="0">Manage RSS Feeds</a></li>
    <li><a class="ovalbutton<?php if($plugin_action == 'add') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>edit/id:0"><img src="<?php print_the_static_files_url() ; ?>/icons/rss_add.png" alt=" " border="0">Add new feed</a></li>
    <li><a class="ovalbutton<?php if($plugin_action == 'process_feeds_now') : ?> active<?php endif; ?>" href="<?php print THIS_PLUGIN_URL ?>process_feeds_now"><img src="<?php print_the_static_files_url() ; ?>/icons/rss_go.png" alt=" " border="0">Process feeds now</a></li>
  </ul>
</div>


