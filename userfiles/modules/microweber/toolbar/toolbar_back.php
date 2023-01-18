


<style>
    #mw-site-preview-navigation{
        position: fixed;
        top: 0;
        transform: translateX(-50%);
        left: 50%;
        z-index: 999;
    }
</style>

<script>

    mw.require('mai.css')
    mw.require('components.css')

</script>

<div class="mw-ui-btn-nav" id="mw-site-preview-navigation">


    <a href="<?php echo route('admin.content.index'); ?>" id="back_to_admin" class="mw-ui-btn mw-ui-btn-medium"> <span class="d-none d-sm-block mdi mdi-arrow-left"></span> <?php _e("Admin"); ?></a>



    <a href="<?php
  if(defined('CONTENT_ID') and CONTENT_ID != 0){
	  $u  = mw()->content_manager->link(CONTENT_ID);
  } else {
	  $u  =mw()->url_manager->current(1,1);
  }
 print $u ?>?editmode=y"  class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><span class="d-none d-sm-block mdi mdi-eye-outline"></span> &nbsp;<?php _e("Live Edit"); ?></a>
</div>

