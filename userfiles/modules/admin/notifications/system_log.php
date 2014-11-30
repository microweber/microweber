<?php
only_admin_access();
$log_params = $params;

if(isset($log_params['id'])){
	unset($log_params['id']);
}
if(isset($log_params['module'])){
	unset($log_params['module']);
}

/*if(isset($params['is_read'])){
	$log_params["is_read"] = $params['is_read'];
}
if(isset($params['limit'])){
	$log_params["is_read"] = $params['is_read'];
}*/
$log_params["is_system"] = 'n';
$log_params["order_by"] = 'created_on desc';
$data = mw()->log_manager->get($log_params);
 

?>
<script  type="text/javascript">

log_del_conf = 0;
mw.log_item_delete = function($item_id){
	
	
	if(log_del_conf < 3){
	
     mw.tools.confirm(mw.msg.del, function(){
		 log_del_conf++;
    	  $.get("<?php print api_link('delete_log_entry'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-log-item-'+$item_id).fadeOut();
    	  });
     });
	 
	} else {
		 log_del_conf++;
    	  $.get("<?php print api_link('delete_log_entry'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-log-item-'+$item_id).fadeOut();
    	  });
	}

}

mw.syslog_log_reset_all = function(){
	 mw.tools.confirm("All the log entries will be deleted!! Are you sure?", function(){
	 $.get("<?php print api_link('system_log_reset'); ?>", function(){
		 	mw.reload_module('<?php print $config['module'] ?>');
	  });
	 });
}


</script>
<?php if(is_array($data )): ?>

<div class="mw-admin-system_log-holder" id="admin_system_log">
  <table cellspacing="0" cellpadding="0" class="mw-ui-table">
    <colgroup>
    <col width="150">
    <col width="auto">
     <col width="40">
    </colgroup>
    <thead>
      <tr valign="middle">
        <th valign="middle" colspan="3"><h2><span class="ico ilogication"></span><?php _e("Your system log for the last 30 days"); ?></h2>
<a href="javascript:mw.syslog_log_reset_all();" class="mw-ui-link"><?php _e("Clean up system log"); ?></a>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data  as $item): ?>
      <tr class="mw-ui-admin-log-item-<?php print $item['id'] ?> <?php if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?>mw-success<?php endif; ?>">
        <?php
  	    $mod_info = false;
  	    if(isset($item['module']) and $item['module'] != ''){
  		    $mod_info = module_info($item['module']);
  	    }
       ?>
        <td> 
          <time class="mw-date" title="<?php print mw('format')->date($item['created_on']); ?> (<?php print ($item['created_on']); ?>)"><?php print mw('format')->ago($item['created_on'],1); ?></time> <br> 
          
           
           <?php if($mod_info != false and isset($mod_info['name'])): ?> 
          <img src=" <?php   print thumbnail($mod_info['icon'], 16,16) ?>" />
          <?php elseif(isset($item['rel']) and trim($item['rel']) != '') : ?>
          <?php endif; ?>
          <?php if(isset($item['rel']) and trim($item['rel']) != '') : ?>
               <span><?php print $item['rel'] ?></span> <br> 
            <?php else : ?>
            <?php endif; ?>
            <?php if(isset($item['user_ip']) and $item['user_ip'] != ''): ?>
             
<small><?php print $item['user_ip'] ?></small>
              <?php endif; ?>
          
          </td>
        <td style="max-width: 60%;"><?php if($mod_info != false and isset($mod_info['name'])): ?>
          <a class="mw-ui-link" href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:log_<?php print $item['id'] ?>" title="<?php print $mod_info['name'] ?>"> <?php print $item['title'] ?></a>
          <?php else : ?>
          <?php print $item['title'] ?>
          <?php endif; ?>
          
          <div class="logication_info">
            <?php if(isset($item['content']) and $item['content'] != ''): ?>
            <?php if($mod_info != false and isset($mod_info['name'])): ?><a href="<?php if($mod_info != false and isset($mod_info['name'])): ?><?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_log:<?php  print  $item['id'] ?><?php endif; ?>" class="ellipsis"><?php endif; ?>

			
			<?php print html_entity_decode($item['content']); ?>
             <?php if($mod_info != false and isset($mod_info['name'])): ?>
            </a> 
            <?php endif; ?>
            
            
            
            
            <?php elseif(isset($item['rddddel']) and trim($item['rddddel']) != '') : ?>
            <span class="left"><?php print $item['rddddel'] ?></span>
            <?php else : ?>
            <?php endif; ?>
            <?php if(isset($item['description']) and $item['description'] != ''): ?>
              <br>
<small><?php print html_entity_decode( $item['description']) ?></small>
              <?php endif; ?>
          </div>
          
          </td>
  
        <td><a href="javascript:mw.log_item_delete('<?php print $item['id'] ?>');" class="show-on-hover mw-icon-close"></a></td>
      </tr>
      <?php endforeach ; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<?php print notif("Your system log is empty") ?>
<?php endif; ?>
 