<?php
only_admin_access();
if (!isset($notif_params)) {
    $notif_params = $params;
}
if (isset($notif_params['id'])) {
    unset($notif_params['id']);
}
if (isset($notif_params['module'])) {
    unset($notif_params['module']);
}

/*if(isset($params['is_read'])){
	$notif_params["is_read"] = $params['is_read'];
}
if(isset($params['limit'])){
	$notif_params["is_read"] = $params['is_read'];
}*/


if (!isset($notif_params['limit'])) {
    $notif_params["limit"] = 500;
}

$notif_params["no_cache"] = true;
$notif_params["order_by"] = 'created_at desc';
$notif_params["order_by"] = 'is_read desc, created_at desc';

$data = mw()->notifications_manager->get($notif_params);
$wrapper_id = "admin_notifications";
if (isset($notif_params['wrapper-id'])) {
    $wrapper_id = $notif_params['wrapper-id'];
}
$is_quick = false;

if (isset($notif_params['quick'])) {
    $is_quick = $notif_params['quick'];
}

?>
<style>
.mw-load-module-modal-link {
	cursor:pointer;
}
</style>
<script type="text/javascript">

    mw.notif_item_read = function ($item_id) {
        $.post("<?php print api_link('notifications_manager/read'); ?>?id=" + $item_id, function () {
			
        });

    }
    mw.notif_item_delete = function ($item_id) {
        mw.tools.confirm(mw.msg.del, function () {
            $.post("<?php print api_link('notifications_manager/delete'); ?>?id=" + $item_id, function () {
                //mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();
                mw.reload_module('admin/notifications');
                //mw.reload_module('#<?php print $params['id'] ?>');

            });
        });

    }

    mw.notif_get_selected = function() {

    	var selectedNotificationIds = [];
    	
    	$(':checkbox').each(function() {
    		if ($(this).prop('checked')) {
    			selectedNotificationIds.push($(this).val());
    		}
    	});

    	if(selectedNotificationIds.length < 1) {
    		mw.notification.error('<?php echo _e('First select notifications.'); ?>');
    		return;
    	}

    	return selectedNotificationIds;
    }

    mw.notif_delete_selected = function () {

    	var selectedNotificationIds = mw.notif_get_selected();
        
        mw.tools.confirm('<?php echo _e('Are you sure you want to delete');?> ' +selectedNotificationIds.length+ ' <?php echo _e(' notifications'); ?>?', function () {
            $.post("<?php print api_link('notifications_manager/delete_selected'); ?>?ids=" + selectedNotificationIds, function () {
                mw.reload_module('admin/notifications');
            });
        });

    }

    mw.notif_read_selected = function () {

    	var selectedNotificationIds = mw.notif_get_selected();
        
        mw.tools.confirm('<?php echo _e('Are you sure you want to read');?> ' +selectedNotificationIds.length+ ' <?php echo _e(' notifications'); ?>?', function () {
            $.post("<?php print api_link('notifications_manager/read_selected'); ?>?ids=" + selectedNotificationIds, function () {
                mw.reload_module('admin/notifications');
            });
        });

    }

    mw.notif_reset_all = function () {
        $.post("<?php print api_link('notifications_manager/reset'); ?>", function () {
            mw.reload_module('admin/notifications');
            //	mw.reload_module('#<?php print $params['id'] ?>');

        });
    }


    mw.notif_reset_selected = function () {

    	var selectedNotificationIds = mw.notif_get_selected();
        
        mw.tools.confirm('<?php echo _e('Are you sure you want to unread');?> ' +selectedNotificationIds.length+ ' <?php echo _e(' notifications'); ?>?', function () {
            $.post("<?php print api_link('notifications_manager/reset_selected'); ?>?ids=" + selectedNotificationIds, function () {
                mw.reload_module('admin/notifications');
            });
        });

    }


    mw.notif_mark_all_as_read = function () {
        $.post("<?php print api_link('notifications_manager/mark_all_as_read'); ?>", function () {
            mw.reload_module('admin/notifications');
            //	mw.reload_module('#<?php print $params['id'] ?>');

        });
    }

	mw.notif_select_all = function () {
        $(':checkbox').each(function() {
        	$(this).prop('checked', true);
        });
        $('.notif-select-all').attr('href', 'javascript:mw.notif_unselect_all();');
        $('.notif-select-all').html('<?php _e("Unselect all"); ?>');
	}

	mw.notif_unselect_all = function () {
				
        $(':checkbox').each(function() {
        	$(this).prop('checked', false);
        });
        $('.notif-select-all').attr('href', 'javascript:mw.notif_select_all();');
        $('.notif-select-all').html('<?php _e("Select all"); ?>');
	}
	
    function load_module_modal(module_name, notification_id) {

    	mw.notif_item_read(notification_id);

    	mw.reload_module('admin/notifications');
        
 		if (module_name == 'contact_form') {
 	    	 mw.modal({
 	             content: '<div id="mw_admin_preview_module_content"></div>',
 	             title: 'Preview Notification',
 	             id: 'mw_admin_preview_module_modal'
 	         });
 	         
 	    	 var params = {}
 	         params.notification_id = notification_id;
 	         params.notification_module = module_name;
 	         
 	         mw.load_module('admin/notifications/view', '#mw_admin_preview_module_content', null, params);
 	         	
 		} else {
 			var redirectModuleUrl = '<?php echo admin_url(); ?>view:modules/load_module:' + module_name + '/mw_notif:' + notification_id;
			window.location.href = redirectModuleUrl;
			return;
 		}
 		
    }
    
    mw.notif_unselect_all(); 
</script>
<?php if (is_array($data)): ?>
<?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
<?php $periods_printed = array(); ?>
<?php
/*		foreach($periods as $period){
            if(!in_array($period ,$periods_printed )){
                $time1 = strtotime($item['created_at']);


                $time2 = strtotime($period);

                if($time1 < $time2){
                 print 	$period;
                 $periods_printed[] = $period;
                }

            }

        }*/


?>
<div class="admin-side-content">
    <div class="mw-admin-notifications-holder mw-ui-box mw-ui-box-content" id="<?php print $wrapper_id ?>">
    
        <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic">

                <?php if ($is_quick == false): ?>
                    <colgroup>
                    	<col width="40">
                        <col width="40">
                        <col width="200">
                        <col width="auto">
                        <col width="120">
                        <col width="40">
                    </colgroup>
                    <thead>
                    <tr valign="middle">
                        <th colspan="6" valign="middle">
                            <div class="pull-left">
                                <h2 style="margin-top: 0;"><span class="mw-icon-notification"></span>
                                    <?php _e("Your Notifications"); ?>
                                </h2>
                            </div>
                            <?php if ($is_quick == false): ?>
                                <div class="pull-right">
                                
                                <a href="javascript:mw.notif_delete_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-important notif-delete-selected">
                                <?php _e("Delete"); ?>
                                </a> 
                                
                                 <a href="javascript:mw.notif_read_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-notification notif-read-selected">
                                <?php _e("Mark as read"); ?>
                                </a>
                                
                                 <a href="javascript:mw.notif_reset_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-warn notif-unread-selected">
                                <?php _e("Mark as unread"); ?>
                                </a>
                                
                                
                                 <a href="javascript:mw.notif_select_all();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-info notif-select-all">
                                <?php _e("Select all"); ?>
                                </a>
                                
                                <b style="font-size:19px;">|</b> 
                                
                                	<?php if ($is_quick == false): ?>
							                <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a> /
							                <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a> / 
							                <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
							        <?php endif; ?>
                                
                                </div>
                            <?php endif; ?>
                        </th>
                    </tr>
                    </thead>
                <?php endif; ?>

                <tbody>
                <?php foreach ($data as $item): ?> 
                	
	                <?php 
	                $load_module_modal_on_click = 'class="mw-load-module-modal-link" onClick="load_module_modal('."'". module_name_encode($item['module']) . "'" . ', ' . $item['id'] . ')";';
	                ?>
					
                    <tr class="mw-ui-admin-notif-item-<?php print $item['id'] ?> <?php if (isset($item['is_read']) && $item['is_read'] == '0'): ?>mw-notification-new<?php endif; ?>" onclick="mw.notif_item_read('<?php print $item['id'] ?>');">
                        <?php
                        $mod_info = false;
                        if (isset($item['module']) and $item['module'] != '') {
                            $mod_info = module_info($item['module']);
                        }

                        //$view_more_link = 
                        ?>
                        <td>
							<label class="mw-ui-check">
							   <input type="checkbox" value="<?php echo $item['id']; ?>" name="checked[<?php echo $item['id']; ?>]">
							   <span></span>
							</label>
						</td>
                        
                        <td><?php if ($mod_info != false and isset($mod_info['name'])): ?>
                                <img src=" <?php print $mod_info['icon'] ?>" style="width: 18px; height: 18px;"/>
                            <?php endif; ?></td>
                        <td><?php if ($mod_info != false and isset($mod_info['name'])): ?>
                        		
                                <a class="mw-ui-link" <?php echo $load_module_modal_on_click; ?> title="<?php print $mod_info['name'] ?>"> <?php print $item['title'] ?></a>
                            <?php elseif (isset($item['rel_type']) and $item['rel_type'] == 'content'): ?>
                                <a class="mw-ui-link" href="<?php print admin_url() ?>view:content#action=editpage:<?php print ($item['rel_id']) ?>"> <?php print $item['title'] ?></a>
                            <?php else : ?>
                                <?php print $item['title'] ?>
                            <?php endif; ?>


                            <div class="notification_info">
                                <time title="<?php print mw('format')->date($item['created_at']); ?>"><?php print mw('format')->ago($item['created_at'], 1); ?></time>
                            </div>

                        </td>

                        <?php if ($is_quick == false): ?>
                            <td style="max-width: 60%;">
                                <div class="notification_info">
                                  <a <?php echo $load_module_modal_on_click; ?> >
                                        <?php if (isset($item['content']) and $item['content'] != ''): ?>
                                            <?php print strip_tags(html_entity_decode($item['content'])); ?>
                                        <?php else : ?>
                                        <?php endif; ?>
                                  </a>
                                </div>
                            </td>
                        <?php endif; ?>

                        <td>
						
                            <?php
                            if (isset($item['module']) and $item['module'] == 'comments'): ?>
                           <?php

                                /*
                                <div class="mw-dropdown mw-dropdown-default">
                                <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-dropdown-val"><i class="mai-idea"></i> Published</span>
                                <div class="mw-dropdown-content" style="display: none;">
                                    <ul>
                                        <li value="1">Published</li>
                                        <li value="2">Unpublished</li>
                                        <li value="3">Span</li>
                                        <li value="3">Delete</li>
                                    </ul>
                                </div>
                            </div>   */
                                ?>
                            <?php endif; ?>

                        </td>

                        <?php if ($is_quick == false): ?>
                            <td><a href="javascript:mw.notif_item_delete('<?php print $item['id'] ?>');" class="show-on-hover mw-icon-close"></a></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        
        <?php if ($is_quick == false): ?>

            <div class="mw-ui-link-nav" style="padding: 20px 0;">
                <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a>
                <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a>
                <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
            </div>
            <a class="mw-ui-btn right" href="javascript:mw.load_module('admin/notifications/system_log','#admin_notifications')">
                <?php _e("Show system log"); ?>
            </a>
        <?php endif; ?>
        
        
    </div>
    <?php else : ?>
        <?php if ($is_quick == false): ?>
            <div class="mw-ui-box" style="width: 500px;text-align: center;margin: 60px auto;">
                <div class="mw-ui-box-header">
                    <h2>
                        <?php _e("No new notifications available"); ?>
                        !</h2>
                </div>
                <div class="mw-ui-box-content">
                    <p>
                        <?php _e("Choose your Action"); ?>
                    </p>
                    <br>
                    <p><a href="<?php print admin_url() ?>view:dashboard" class="mw-ui-btn mw-ui-btn-blue" style="margin-right: 12px;">
                            <?php _e("Back to Dashboard"); ?>
                        </a> <a href="<?php print admin_url() ?>view:content" class="mw-ui-btn mw-ui-btn-green">
                            <?php _e("Manage your Content"); ?>
                        </a></p>
                    <br>
                    <?php //print notif('No new notifications available!'); ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>