<?
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
$data = get_log($log_params);
 

?>
<script  type="text/javascript">

log_del_conf = 0;
mw.log_item_delete = function($item_id){
	
	
	if(log_del_conf < 3){
	
     mw.tools.confirm(mw.msg.del, function(){
		 log_del_conf++;
    	  $.get("<?php print site_url('api/delete_log_entry'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-log-item-'+$item_id).fadeOut();
    	  });
     });
	 
	} else {
		 log_del_conf++;
    	  $.get("<?php print site_url('api/delete_log_entry'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-log-item-'+$item_id).fadeOut();
    	  });
	}

}

mw.syslog_log_reset_all = function(){
	 mw.tools.confirm("All the log entries will be deleted!! Are you sure?", function(){
	 $.get("<?php print site_url('api/system_log_reset'); ?>", function(){
		 	mw.reload_module('<? print $config['module'] ?>');
	  });
	 });
}


</script>
<? if(isarr($data )): ?>

<div class="mw-admin-system_log-holder" id="admin_system_log">
  <table cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
    <colgroup>
    <col width="150">
    <col width="auto">
     <col width="40">
    </colgroup>
    <thead>
      <tr valign="middle">
        <th valign="middle" colspan="3"><h2><span class="ico ilogication"></span>Your system log for the last 30 days</h2> 
<a href="javascript:mw.syslog_log_reset_all();" class="mw-ui-link">Clean up system log</a>
        </th>
      </tr>
    </thead>
    <tbody>
      <? foreach($data  as $item): ?>
      <tr class="mw-ui-admin-log-item-<? print $item['id'] ?> <? if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?>mw-success<? endif; ?>">
        <?
  	    $mod_info = false;
  	    if(isset($item['module']) and $item['module'] != ''){
  		    $mod_info = module_info($item['module']);
  	    }
       ?>
        <td> 
          <time class="mw-date" title="<? print mw_date($item['created_on']); ?> (<? print ($item['created_on']); ?>)"><? print ago($item['created_on'],1); ?></time> <br> 
          
           
           <? if($mod_info != false and isset($mod_info['name'])): ?> 
          <img src=" <?   print thumbnail($mod_info['icon'], 16,16) ?>" />
          <? elseif(isset($item['rel']) and trim($item['rel']) != '') : ?>
          <? endif; ?>
          <? if(isset($item['rel']) and trim($item['rel']) != '') : ?>
               <span><? print $item['rel'] ?></span> <br> 
            <? else : ?>
            <? endif; ?>
            <? if(isset($item['user_ip']) and $item['user_ip'] != ''): ?>
             
<small><? print $item['user_ip'] ?></small>
              <? endif; ?>
          
          </td>
        <td style="max-width: 60%;"><? if($mod_info != false and isset($mod_info['name'])): ?>
          <a class="mw-ui-link" href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_notif:log_<? print $item['id'] ?>" title="<? print $mod_info['name'] ?>"> <? print $item['title'] ?></a>
          <? else : ?>
          <? print $item['title'] ?>
          <? endif; ?>
          
          <div class="logication_info">
            <? if(isset($item['content']) and $item['content'] != ''): ?>
            <? if($mod_info != false and isset($mod_info['name'])): ?><a href="<? if($mod_info != false and isset($mod_info['name'])): ?><? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_log:<?  print  $item['id'] ?><? endif; ?>" class="ellipsis"><? endif; ?>
			
			
			<? print html_entity_decode($item['content']); ?>
             <? if($mod_info != false and isset($mod_info['name'])): ?>
            </a> 
            <? endif; ?>
            
            
            
            
            <? elseif(isset($item['rddddel']) and trim($item['rddddel']) != '') : ?>
            <span class="left"><? print $item['rddddel'] ?></span>
            <? else : ?>
            <? endif; ?>
            <? if(isset($item['description']) and $item['description'] != ''): ?>
              <br>
<small><? print html_entity_decode( $item['description']) ?></small>
              <? endif; ?>
          </div>
          
          </td>
  
        <td><a href="javascript:mw.log_item_delete('<? print $item['id'] ?>');" class="mw-ui-admin-table-show-on-hover mw-ui-btnclose"></a></td>
      </tr>
      <? endforeach ; ?>
    </tbody>
  </table>
</div>
<? else: ?>
<? print mw_notif("Your system log is empty") ?>
<? endif; ?>
 