


<style>
    #mw-site-preview-navigation{
        position: fixed;
        top: 0;
        transform: translateX(-50%);
        left: 50%;
        z-index: 999;
        background-color: #eeefef !important;
        color: #0a0a0a  !important;
    }
    #mw-site-preview-navigation, #mw-site-preview-navigation a {
        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
        color: #0a0a0a  !important;
    }
</style>

<script>

    mw.require('mai.css')
    mw.require('components.css')

</script>

<div class="mw-ui-btn-nav" id="mw-site-preview-navigation">


    <a href="<?php echo route('admin.content.index'); ?>" id="mw_back_to_admin" class="mw-ui-btn">  <?php _e("Admin"); ?></a>



    <a id="mw_back_to_live_edit" href="<?php
  if(defined('CONTENT_ID') and CONTENT_ID != 0){
	  $u  = app()->content_manager->link(CONTENT_ID);
  } else {
	  $u  =mw()->url_manager->current(1,1);
  }
 print $u ?>?editmode=y"  class="mw-ui-btn" >  &nbsp;<?php _e("Live Edit"); ?></a>
</div>

