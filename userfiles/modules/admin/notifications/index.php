<?
only_admin_access();
$notif_params = $params;

if(isset($notif_params['id'])){
	unset($notif_params['id']);
}
if(isset($notif_params['module'])){
	unset($notif_params['module']);
}

/*if(isset($params['is_read'])){
	$notif_params["is_read"] = $params['is_read'];
}
if(isset($params['limit'])){
	$notif_params["is_read"] = $params['is_read'];
}*/
$notif_params["order_by"] = 'created_on desc';
$data = get_notifications($notif_params);


?>
<script  type="text/javascript">


mw.notif_item_delete = function($item_id){
     mw.tools.confirm(mw.msg.del, function(){
    	  $.get("<?php print site_url('api/delete_notification'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();
    	  });
     });

}

mw.notif_reset_all = function(){
	 $.get("<?php print site_url('api/notifications_reset'); ?>", function(){
		 	mw.reload_module('admin/notifications');
	  });
}


</script>
<? if(isarr($data )): ?>

<div class="mw-admin-notifications-holder" id="admin_notifications">

  <table cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
    <colgroup>
        <col width="40">
        <col width="200">
        <col width="auto">
        <col width="40">
    </colgroup>
    <thead>
        <tr>
            <th colspan="4"><h2><span class="ico inotification"></span>Your Notifications</h2></th>
        </tr>
    </thead>
    <tbody>
      <? foreach($data  as $item): ?>
      <tr class="mw-ui-admin-notif-item-<? print $item['id'] ?> <? if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?>mw-success<? endif; ?>">

       <?
  	    $mod_info = false;
  	    if(isset($item['module']) and $item['module'] != ''){
  		    $mod_info = module_info($item['module']);
  	    }
       ?>

      <td>
            <? if($mod_info != false and isset($mod_info['name'])): ?>
                <img src=" <?   print thumbnail($mod_info['icon'], 16,16) ?>" />
            <?php endif; ?>
      </td>


        <td>
          <? if($mod_info != false and isset($mod_info['name'])): ?>

          <a class="mw-ui-link" href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_notif:<? print $item['id'] ?>" title="<? print $mod_info['name'] ?>">
            <? print $item['title'] ?></a>

          <? else : ?>
          <? print $item['title'] ?>
          <? endif; ?>

          <time title="<? print mw_date($item['created_on']); ?>"><? print ago($item['created_on'],1); ?></time>

          </td>





        <td style="max-width: 60%;">
            <div class="notification_info"><a href="<? if($mod_info != false and isset($mod_info['name'])): ?><? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_notif:<?  print  $item['id'] ?><? endif; ?>" class="ellipsis">
          <? if(isset($item['content']) and $item['content'] != ''): ?>
          <? print $item['content']; ?>
          <? else : ?>
          <? endif; ?>

          </a></div></td>
        <td><a href="javascript:mw.notif_item_delete('<? print $item['id'] ?>');" class="mw-ui-admin-table-show-on-hover mw-ui-btnclose"></a></td>
      </tr>
      <? endforeach ; ?>
    </tbody>
  </table>
</div>
<? endif; ?><br />

<a href="javascript:mw.notif_reset_all();" class="mw-ui-btn"><strong>[test] Reset  notifications</strong></a>