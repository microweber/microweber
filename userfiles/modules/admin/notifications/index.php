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
     mw.tools.confirm(mw.msg.del, function(){
    	  $.get("<?php print site_url('api/mw/Notifications/delete'); ?>/"+$item_id, function(){
    		 	mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();
    	  });
     });

}

mw.notif_reset_all = function(){
	 $.get("<?php print site_url('api/Notifications/reset'); ?>", function(){
		 	mw.reload_module('admin/notifications');
	  });
}


</script>
<?php if(isarr($data )): ?>
<?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
<?php $periods_printed = array(); ?>
<?php
	/*		foreach($periods as $period){
				if(!in_array($period ,$periods_printed )){
					$time1 = strtotime($item['created_on']);


					$time2 = strtotime($period);

					if($time1 < $time2){
					 print 	$period;
					 $periods_printed[] = $period;
					}

				}

			}*/



			  ?>

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
        <th colspan="4"><h2><span class="ico inotification"></span><?php _e("Your Notifications"); ?></h2></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data  as $item): ?>
      <tr class="mw-ui-admin-notif-item-<?php print $item['id'] ?> <?php if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?>mw-success<?php endif; ?>">
        <?php
  	    $mod_info = false;
  	    if(isset($item['module']) and $item['module'] != ''){
  		    $mod_info = module_info($item['module']);
  	    }
        ?>
        <td><?php if($mod_info != false and isset($mod_info['name'])): ?>
          <img src=" <?php   print thumbnail($mod_info['icon'], 16,16) ?>" />
          <?php endif; ?></td>
        <td><?php if($mod_info != false and isset($mod_info['name'])): ?>
          <a class="mw-ui-link" href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php print $item['id'] ?>" title="<?php print $mod_info['name'] ?>"> <?php print $item['title'] ?></a>
          <?php else : ?>
          <?php print $item['title'] ?>
          <?php endif; ?>
          <time title="<?php print mw_date($item['created_on']); ?>"><?php print ago($item['created_on'],1); ?></time></td>
        <td style="max-width: 60%;"><div class="notification_info"><a href="<?php if($mod_info != false and isset($mod_info['name'])): ?><?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php  print  $item['id'] ?><?php endif; ?>" class="ellipsis">
            <?php if(isset($item['content']) and $item['content'] != ''): ?>
            <?php print $item['content']; ?>
            <?php else : ?>
            <?php endif; ?>
            </a></div></td>
        <td><a href="javascript:mw.notif_item_delete('<?php print $item['id'] ?>');" class="mw-ui-admin-table-show-on-hover mw-ui-btnclose"></a></td>
      </tr>
      <?php endforeach ; ?>
    </tbody>
  </table>
  <div class="vSpace"></div>
  <a class="mw-ui-btn right" href="javascript:mw.load_module('admin/notifications/system_log','#admin_notifications')"><?php _e("Show system log"); ?></a>
</div>

<?php else : ?>



<div class="mw-o-box" style="width: 500px;text-align: center;margin: 60px auto;">
<div class="mw-o-box-header"><h2><?php _e("No new notifications available"); ?>!</h2></div>
<div class="mw-o-box-content">
    <p><?php _e("Choose your Action"); ?></p>
    <br>
   <p>
    <a href="<?php print admin_url() ?>view:dashboard" class="mw-ui-btn mw-ui-btn-blue" style="margin-right: 12px;"><?php _e("Back to Dashboard"); ?></a>
    <a href="<?php print admin_url() ?>view:content" class="mw-ui-btn mw-ui-btn-green"><?php _e("Manage your Content"); ?></a>
   </p>
    <br>
   <?php //print mw_notif('No new notifications available!'); ?>

</div>
</div>

<?php endif; ?>
