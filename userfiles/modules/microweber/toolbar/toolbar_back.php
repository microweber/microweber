


 

<link href="<?php   print( mw_includes_url());  ?>css/components.css" rel="stylesheet" type="text/css" />

  <a href="<?php
  if(defined('CONTENT_ID') and CONTENT_ID != 0){
	  $u  = mw()->content_manager->link(CONTENT_ID);
  } else {
	  $u  =mw('url')->current(1,1);
  }
 print $u ?>?editmode:y"  class="mw-ui-btn mw-ui-btn-invert" id="mw_toolbar_back_to_live_edit"><span class="mw-icon-live"></span><?php _e("Back to Live Edit"); ?></a>

