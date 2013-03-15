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


	 $.get("<?php print site_url('api/delete_notification'); ?>/"+$item_id, function(){
		 	mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();

	  });
}
</script>
<? if(isarr($data )): ?>

<div class="mw-admin-notifications-holder">
  <table cellspacing="0" cellpadding="0" class="mw-ui-admin-table">

    <tbody>
      <? foreach($data  as $item): ?>




      <tr class="mw-ui-admin-notif-item-<? print $item['id'] ?> <? if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?><? endif; ?>">

        <?
	 $mod_info = false;
	 if(isset($item['module']) and $item['module'] != ''){
		 $mod_info = module_info($item['module']);
		}
?>
      <td>
        <? if($mod_info != false and isset($mod_info['name'])): ?>
            <a href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_notif:<? print $item['id'] ?>">
        <?php endif; ?>
            <img src=" <?   print thumbnail($mod_info['icon'], 16,16) ?>" />
          <? if($mod_info != false and isset($mod_info['name'])): ?>
        </a>
          <?php endif; ?>
        </td>

       <td>



          <? if($mod_info != false and isset($mod_info['name'])): ?>

          <a class="mw-ui-link" href="<? print admin_url() ?>view:modules/load_module:<? print module_name_encode($item['module']) ?>/mw_notif:<? print $item['id'] ?>" title="<? print $mod_info['name'] ?>">



          <span class="ellipsis"><? print $item['title'] ?></span>

          <? else : ?>
          <span >
          <? print $item['title'] ?></span>
          <? endif; ?>
          <div class="mw_clear"></div>
          <time title="<? print mw_date($item['created_on']); ?>"><? print ago($item['created_on'],1); ?></time>
          <? if($mod_info != false and isset($mod_info['name'])): ?>
           </a>

          <? endif; ?>

          </td>




      </tr>
      <? endforeach ; ?>
    </tbody>
  </table>
</div>
<? endif; ?>
