<?php
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
$notif_params["order_by"] = 'is_read desc, created_on desc';
$data = \mw\Notifications::get($notif_params);


?>
<script  type="text/javascript">
mw.notif_item_delete = function($item_id){


	 $.get("<?php print site_url('api/mw/Notifications/delete'); ?>/"+$item_id, function(){
		 	mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();

	  });
}
</script>
<?php if(isarr($data )): ?>

<div class="mw-admin-notifications-holder">
  <table cellspacing="0" cellpadding="0" class="mw-ui-admin-table">

    <tbody>
      <?php foreach($data  as $item): ?>




      <tr class="mw-ui-admin-notif-item-<?php print $item['id'] ?> <?php if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?><?php endif; ?>">

        <?php
	 $mod_info = false;
	 if(isset($item['module']) and $item['module'] != ''){
		 $mod_info = module_info($item['module']);
		}
?>
      <td>
        <?php if($mod_info != false and isset($mod_info['name'])): ?>
            <a href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php print $item['id'] ?>">
        <?php endif; ?>
            <img src=" <?php   print thumbnail($mod_info['icon'], 16,16) ?>" />
          <?php if($mod_info != false and isset($mod_info['name'])): ?>
        </a>
          <?php endif; ?>
        </td>

       <td>



          <?php if($mod_info != false and isset($mod_info['name'])): ?>

          <a class="mw-ui-link" href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php print $item['id'] ?>" title="<?php print $mod_info['name'] ?>">



          <span class="ellipsis"><?php print $item['title'] ?></span>

          <?php else : ?>
          <span >
          <?php print $item['title'] ?></span>
          <?php endif; ?>
          <div class="mw_clear"></div>
          <time title="<?php print mw_date($item['created_on']); ?>"><?php print ago($item['created_on'],1); ?></time>
          <?php if($mod_info != false and isset($mod_info['name'])): ?>
           </a>

          <?php endif; ?>

          </td>




      </tr>
      <?php endforeach ; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
