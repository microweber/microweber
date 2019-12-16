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

/* if(isset($params['is_read'])){
  $notif_params["is_read"] = $params['is_read'];
  }
  if(isset($params['limit'])){
  $notif_params["is_read"] = $params['is_read'];
  } */


if (!isset($notif_params['limit'])) {
    $notif_params["limit"] = 500;
}

$notif_params["no_cache"] = true;
$notif_params["order_by"] = 'created_at desc';
$notif_params["order_by"] = 'is_read desc, created_at desc';

$filter_by = url_param('filter_by', true);
if ($filter_by) {
    if ($filter_by == 'comments') {
        $notif_params["module"] = 'comments';
    }

    if ($filter_by == 'orders') {
        $notif_params["module"] = 'shop';
    }

    if ($filter_by == 'form_entries') {
        $notif_params["module"] = 'contact_form';
    }

    if ($filter_by == 'new_user_registrations') {
        $notif_params["module"] = 'users';
    }
}

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
        cursor: pointer;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        mw.dropdown();
    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('.check-all').on('click', function () {
            if ($(this).is(':checked')) {
                $('.all-notifications input[type="checkbox"]').each(function () {
                    $(this).prop('checked', true);
                });
            } else {
                $('.all-notifications input[type="checkbox"]').each(function () {
                    $(this).prop('checked', false);
                });
            }
        });
    });

    mw.notif_item_read = function ($item_id) {
        $.post("<?php print api_link('notifications_manager/read'); ?>?id=" + $item_id, function () {
            mw.reload_module('admin/notifications');
        });
    }

    mw.notif_item_reset = function ($item_id) {
        $.post("<?php print api_link('notifications_manager/reset_selected'); ?>?ids=" + $item_id, function () {
            mw.reload_module('admin/notifications');
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

    mw.notif_get_selected = function () {

        var selectedNotificationIds = [];

        $(':checkbox').each(function () {
            if ($(this).prop('checked')) {
                selectedNotificationIds.push($(this).val());
            }
        });

        if (selectedNotificationIds.length < 1) {
            mw.notification.error('<?php _ejs('First select notifications.'); ?>');
            return [];
        }

        return selectedNotificationIds;
    }

    mw.notif_delete_selected = function () {

        var selectedNotificationIds = mw.notif_get_selected();

        mw.tools.confirm('<?php _e('Are you sure you want to delete'); ?> ' + selectedNotificationIds.length + ' <?php _e(' notifications'); ?>?', function () {
            $.post("<?php print api_link('notifications_manager/delete_selected'); ?>?ids=" + selectedNotificationIds, function () {
                mw.reload_module('admin/notifications');
            });
        });

    }

    mw.notif_read_selected = function () {

        var selectedNotificationIds = mw.notif_get_selected();

        mw.tools.confirm('<?php _e('Are you sure you want to read'); ?> ' + selectedNotificationIds.length + ' <?php _e(' notifications'); ?>?', function () {
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

        mw.tools.confirm('<?php _e('Are you sure you want to unread'); ?> ' + selectedNotificationIds.length + ' <?php _e(' notifications'); ?>?', function () {
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
        $(':checkbox').each(function () {
            $(this).prop('checked', true);
        });
        $('.notif-select-all').attr('href', 'javascript:mw.notif_unselect_all();');
        $('.notif-select-all').html('<?php _e("Unselect all"); ?>');
    }

    mw.notif_unselect_all = function () {

        $(':checkbox').each(function () {
            $(this).prop('checked', false);
        });
        $('.notif-select-all').attr('href', 'javascript:mw.notif_select_all();');
        $('.notif-select-all').html('<?php _e("Select all"); ?>');
    }

    function load_module_modal(module_name, notification_id) {

        mw.notif_item_read(notification_id);

        if (module_name == 'contact_form' || module_name == 'comments') {
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


<script type="text/javascript">
    toggle_show_less = function (el) {
        var el = $(el);
        el.prev().toggleClass('semi_hidden');
        var html = el.html();
        el.html(el.dataset("later"));
        el.dataset("later", html);
    }

    $(document).ready(function () {
        $('.js-toggle-full').on('click', function () {
            $(this).parent().toggleClass('more');
            $(this).toggleClass('showed');
        });
    });

    $(document).ready(function () {
        $('.all-notifications input[type="checkbox"], input.check-all').on('click', function () {

            var hasChecked = false;

            $('.all-notifications input[type="checkbox"]').each(function () {
                if ($(this).is(':checked')) {
                    hasChecked = true;
                }
            });

            if (hasChecked) {
                $('.js-show-options').removeClass('hidden');
            } else {
                $('.js-show-options').addClass('hidden');
            }
        });
    });
</script>
<style>
    .js-limited {
        min-width: 250px;
        max-width: 500px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .js-limited.more {
        white-space: inherit;
    }

    .js-toggle-full {
        margin-top: 10px;
    }

    .js-toggle-full span:last-child {
        display: none;
    }

    .js-toggle-full.showed span:first-child {
        display: none;
    }

    .js-toggle-full.showed span:last-child {
        display: block;
    }

    .entry-col {
        min-width: 150px;
    }
</style>

<?php if (is_array($data)): ?>
<?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
<?php $periods_printed = array(); ?>
<?php
/* 		foreach($periods as $period){
  if(!in_array($period ,$periods_printed )){
  $time1 = strtotime($item['created_at']);


  $time2 = strtotime($period);

  if($time1 < $time2){
  print 	$period;
  $periods_printed[] = $period;
  }

  }

  } */
?>
<div class="admin-side-content">
    <h2 style="margin-top: 0; margin-bottom: 20px;"><span class="mw-icon-notification"></span>
        <?php _e("Your Notifications"); ?>
    </h2>

    <div class="mw-admin-notifications-holder mw-ui-box mw-ui-box-content" id="<?php print $wrapper_id ?>">


        <div class="table-responsive">
            <table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic">

                <?php if ($is_quick == false): ?>
                    <thead>
                    <tr class="mw-ui-admin-notif-item">
                        <td style="width: 40px; border-top: 0;">
                            <label class="mw-ui-check">
                                <input type="checkbox" class="check-all">
                                <span></span>
                            </label>
                        </td>

                        <td style="border-top: 0;">
                            <div class="mw-flex-row">
                                <div class="mw-flex-col-xs-6">
                                    <div class="box" style="align-items: center; align-content: center; height: 100%; display: flex;">
                                        <div>
                                            <span>Show notifications</span>
                                            <div class="mw-dropdown mw-dropdown-default  m-l-10">
                                                        <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium  mw-dropdown-val">
                                                            <?php
                                                            if ($filter_by) {
                                                                echo 'Filter by';
                                                                $filter_by_text = str_replace('_', ' ', $filter_by);
                                                                echo ': ' . $filter_by_text;
                                                            } else {
                                                                echo 'All notifications';
                                                            }
                                                            ?>
                                                        </span>
                                                <div class="mw-dropdown-content" style="display: none;">
                                                    <ul>
                                                        <li style="padding: 0px;"><a style="border:0px;" href="?">All notifications</a></li>
                                                        <li style="padding: 0px;"><a style="border:0px;" href="?filter_by=comments">Comments</a></li>
                                                        <li style="padding: 0px;"><a style="border:0px;" href="?filter_by=orders">Orders</a></li>
                                                        <li style="padding: 0px;"><a style="border:0px;" href="?filter_by=form_entries"">Form Entries</a></li>
                                                        <li style="padding: 0px;"><a style="border:0px;" href="?filter_by=new_user_registrations"">New User Registrations</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mw-flex-col-xs-6">
                                    <div class="box js-show-options hidden" style="align-items: center; align-content: center; height: 100%; display: flex;">
                                        <div>
                                            <a href="javascript:mw.notif_read_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-notification notif-read-selected" style="margin: 0 5px;">
                                                <?php _e("Mark as Read"); ?>
                                            </a>

                                            <a href="javascript:mw.notif_reset_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-warn notif-unread-selected" style="margin: 0 5px;">
                                                <?php _e("Mark as Unread"); ?>
                                            </a>

                                            <a href="javascript:mw.notif_delete_selected();" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-medium mw-ui-btn-important notif-delete-selected" style="margin: 0 5px;">
                                                <?php _e("Delete Selected"); ?>
                                            </a>

                                            <?php if ($is_quick == true): ?>
                                                <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a> /
                                                <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a> /
                                                <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </td>

                        <td style="width: 210px; text-align: right; border-top:0;">
                            <a class="mw-ui-btn right mw-ui-btn-medium" onClick="mw.load_module('admin/notifications/system_log','#admin_notifications');">
                                <?php _e("Show system log"); ?>
                            </a>
                        </td>
                    </tr>


                    </thead>
                <?php endif; ?>

                <tbody class="all-notifications">
                <?php foreach ($data as $item): ?>

                    <?php
                    $load_module_modal_on_click = 'class="mw-load-module-modal-link" onClick="load_module_modal(' . "'" . module_name_encode($item['module']) . "'" . ', ' . $item['id'] . ')";';
                    ?>

                    <tr class="mw-ui-admin-notif-item-<?php print $item['id'] ?> <?php if (isset($item['is_read']) && $item['is_read'] == '0'): ?>mw-notification-new<?php endif; ?>">
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

                        <td>
                            <div class="mw-flex-row">

                                <?php if ($mod_info != false and isset($mod_info['name'])): ?>
                                    <div class="mw-flex-col-xs-1">
                                        <div class="box" style="align-items: center; align-content: center; height: 100%; display: flex; text-align: center;">
                                            <img src=" <?php print $mod_info['icon'] ?>" style="width: 20px; height: 20px;"/>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mw-flex-col-xs-4">
                                    <div class="box" style="align-items: center; align-content: center; height: 100%; display: flex;">
                                        <div>
                                            <?php if ($mod_info != false and isset($mod_info['name'])): ?>
                                                <a class="mw-ui-link" <?php echo $load_module_modal_on_click; ?> title="<?php print $mod_info['name'] ?>"> <?php print $item['title'] ?></a>
                                            <?php elseif (isset($item['rel_type']) and $item['rel_type'] == 'content'): ?>
                                                <a class="mw-ui-link" href="<?php print admin_url() ?>view:content#action=editpage:<?php print ($item['rel_id']) ?>"> <?php print $item['title'] ?></a>
                                            <?php else : ?>
                                                <?php print $item['title'] ?>
                                            <?php endif; ?>

                                            <div class="notification_info">
                                                <time title="<?php print mw('format')->date($item['created_at']); ?>"><?php print mw('format')->ago($item['created_at'], 1); ?></time>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="mw-flex-col-xs-7">
                                    <div class="box">
                                        <?php if ($is_quick == false): ?>
                                            <div class="notification_info">

                                                <?php if (isset($item['content']) and $item['content'] != ''): ?>
                                                    <?php if (mb_strlen($item['content']) > 200): ?>
                                                        <div class="js-limited">
                                                            <?php print strip_tags(html_entity_decode($item['content'])); ?>
                                                            <!--<br/>-->
                                                            <!--<button class="js-toggle-full mw-ui-btn mw-ui-btn-info mw-ui-btn-small mw-ui-btn-outline"><span>show more</span><span>show less</span></button>-->
                                                        </div>
                                                    <?php else: ?>
                                                        <?php print strip_tags(html_entity_decode($item['content'])); ?>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                            <?php if (isset($item['module']) and $item['module'] == 'comments'): ?>
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
                                  </div> */
                                ?>
                            <?php endif; ?>
                        </td>

                        <?php if ($is_quick == false): ?>
                            <td class="text-right">
                                <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-small mw-ui-btn-outline"><span <?php echo $load_module_modal_on_click; ?>>View More</span></button>
                                <a href="javascript:mw.notif_item_delete('<?php print $item['id'] ?>');" class="mw-ui-btn mw-ui-btn-important mw-ui-btn-small mw-ui-btn-outline">Delete</a>
                            </td>
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
        <?php endif; ?>


    </div>
    <?php else : ?>
        <?php if ($filter_by): ?>

            <div class="mw-ui-box" style="width: 500px;text-align: center;margin: 60px auto;">
                <div class="mw-ui-box-header">
                    <h2>
                        <?php _e("No notifications for this filter"); ?>
                        !</h2>
                </div>
                <div class="mw-ui-box-content">
                    <p>
                        <?php _e("Choose your Action"); ?>
                    </p>
                    <br>
                    <p><a href="?" class="mw-ui-btn mw-ui-btn-blue" style="margin-right: 12px;">
                            <?php _e("Back to Notifications"); ?>
                        </a>
                    </p>
                    <br>
                    <?php //print notif('No new notifications available!');  ?>
                </div>
            </div>

        <?php else: ?>
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
    <?php endif; ?>
</div>