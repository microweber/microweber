<?php
must_have_access();
if (!isset($notif_params)) {
    $notif_params = $params;
}
if (isset($notif_params['id'])) {
    unset($notif_params['id']);
}
if (isset($notif_params['module'])) {
    unset($notif_params['module']);
}

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

dump($data);
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

        mw.tools.confirm('<?php _e('Are you sure you want to delete'); ?> ' + selectedNotificationIds.length + ' <?php _e('notifications'); ?>?', function () {
            $.post("<?php print api_link('notifications_manager/delete_selected'); ?>?ids=" + selectedNotificationIds, function () {
                mw.reload_module('admin/notifications');
            });
        });
    }

    mw.notif_read_selected = function () {
        var selectedNotificationIds = mw.notif_get_selected();

        mw.tools.confirm('<?php _e('Are you sure you want to read'); ?> ' + selectedNotificationIds.length + ' <?php _e('notifications'); ?>?', function () {
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

        mw.tools.confirm('<?php _e('Are you sure you want to unread'); ?> ' + selectedNotificationIds.length + ' <?php _e('notifications'); ?>?', function () {
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
            mw.dialog({
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

        $('.js-check-all').on('change', function () {
            if ($(this).is(':checked')) {
                $('.js-all-notifications .js-checked-checkbox').each(function () {
                    $(this).prop('checked', true);
                });
            } else {
                $('.js-all-notifications .js-checked-checkbox').each(function () {
                    $(this).prop('checked', false);
                });
            }
        });

        $('.js-check-all, .js-checked-checkbox').on('change', function () {
            var hasChecked = false;

            $('.js-all-notifications .js-checked-checkbox').each(function () {
                if ($(this).is(':checked')) {
                    hasChecked = true;
                }
            });

            if (hasChecked) {
                $('.js-show-options').show();
            } else {
                $('.js-show-options').hide();
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

<div class="card style-1 mb-3">
    <div class="card-header">
        <h5><i class="mdi mdi-bell text-primary mr-3"></i> <strong>Notifications</strong></h5>
    </div>

    <div class="card-body" id="<?php print $wrapper_id ?>">
        <?php if (is_array($data)): ?>
            <?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
            <?php $periods_printed = array(); ?>
            <div class="toolbar row mb-3">
                <div class="col-12 text-center text-sm-left">
                    <h5><strong>All Activities</strong></h5>
                    <p>List of all notifications of your website. <a href="javascript:;" onClick="mw.load_module('admin/notifications/system_log','#admin_notifications');" class="d-block d-sm-block float-sm-right"><?php _e("Show system log"); ?></a></p>
                </div>
                <div class="col-12 d-sm-flex align-items-center justify-content-between">
                    <div class="text-center text-md-left my-2">
                        <div class="custom-control custom-checkbox d-inline-block my-2 mr-3">
                            <input type="checkbox" class="custom-control-input check-all js-check-all" id="check-all"/>
                            <label class="custom-control-label" for="check-all">Select all</label>
                        </div>

                        <div class="d-inline-flex">
                            <div class="js-show-options" style="display: none;">
                                <a href="javascript:mw.notif_read_selected();" class="btn btn-outline-success btn-sm mr-1 mr-lg-2 btn-lg-only-icon  notif-read-selected"><i class="mdi mdi-email-open"></i> <span class="d-none d-xl-block"><?php _e("Mark as read"); ?></span></a>
                                <a href="javascript:mw.notif_reset_selected();" class="btn btn-outline-warning btn-sm mr-1 mr-lg-2 btn-lg-only-icon notif-unread-selected"><i class="mdi mdi-email"></i> <span class="d-none d-xl-block"><?php _e("Mark as unread"); ?></span></a>
                                <a href="javascript:mw.notif_delete_selected();" class="btn btn-outline-danger btn-sm mr-1 mr-lg-2 btn-lg-only-icon notif-delete-selected"><i class="mdi mdi-delete"></i> <span class="d-none d-xl-block"><?php _e("Delete selected"); ?></span></a>


                                <div>
                                    <?php if ($is_quick == true): ?>
                                        <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a> /
                                        <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a> /
                                        <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="float-sm-right text-center text-md-right my-2">
                        <span class="d-inline d-sm-none d-md-inline">Show notifications</span>

                        <script>
                            $(function () {
                                $('.js-show-notif').on('change', function () {
                                    var url = $(this).val();
                                    if (url) {
                                        window.location = url;
                                    }
                                    return false;
                                });
                            });
                        </script>

                        <select class="selectpicker js-show-notif" data-style="btn-sm" data-width="auto">
                            <option value="?">All notifications</option>
                            <option value="?filter_by=comments" <?php if (isset($filter_by) AND $filter_by == 'comments'): ?>selected<?php endif; ?>>Comments</option>
                            <option value="?filter_by=orders" <?php if (isset($filter_by) AND $filter_by == 'orders'): ?>selected<?php endif; ?>>Orders</option>
                            <option value="?filter_by=form_entries" <?php if (isset($filter_by) AND $filter_by == 'form_entries'): ?>selected<?php endif; ?>>Form Entries</option>
                            <option value="?filter_by=new_user_registrations" <?php if (isset($filter_by) AND $filter_by == 'new_user_registrations'): ?>selected<?php endif; ?>>New User Registrations</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="timeline js-all-notifications">
                <div class="row timeline-event">
                    <div class="col pr-0 timeline-line datetime-indicator">
                        <button type="button" class="btn btn-primary btn-rounded btn-sm">Past hour</button>
                    </div>
                </div>

                <?php foreach ($data as $item): ?>

                    <div class="row timeline-event mw-ui-admin-notif-item-<?php print $item['id'] ?>">
                        <?php
                        $load_module_modal_on_click = 'class="mw-load-module-modal-link" onClick="load_module_modal(' . "'" . module_name_encode($item['module']) . "'" . ', ' . $item['id'] . ')";';

                        $mod_info = false;
                        if (isset($item['module']) and $item['module'] != '') {
                            $mod_info = module_info($item['module']);
                        }
                        ?>

                        <div class="col pr-0 timeline-line">
                            <div class="custom-control custom-checkbox d-inline-block">
                                <input type="checkbox" class="custom-control-input js-checked-checkbox" id="notif-<?php echo $item['id']; ?>" value="<?php echo $item['id']; ?>" name="checked[<?php echo $item['id']; ?>]">
                                <label class="custom-control-label" for="notif-<?php echo $item['id']; ?>"></label>
                            </div>

                            <?php if (isset($item['rel_type']) AND $item['rel_type'] == 'cart_orders' AND isset($item['module']) AND $item['module'] == 'shop'): ?>
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-shopping"></i></button>
                            <?php elseif (isset($item['rel_type']) AND $item['rel_type'] == 'forms_data' AND isset($item['module']) AND $item['module'] == 'contact_form'): ?>
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-email-check"></i></button>
                            <?php elseif (isset($item['rel_type']) AND $item['rel_type'] == 'content' AND isset($item['module']) AND $item['module'] == 'comments'): ?>
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-comment-account"></i></button>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-primary btn-rounded btn-icon"><i class="mdi mdi-bell"></i></button>
                            <?php endif; ?>
                        </div>

                        <div class="col">
                            <?php if (isset($item['rel_type']) AND $item['rel_type'] == 'cart_orders' AND isset($item['module']) AND $item['module'] == 'shop'): ?>
                                <?php include('notif_order.php'); ?>
                            <?php elseif (isset($item['rel_type']) AND $item['rel_type'] == 'forms_data' AND isset($item['module']) AND $item['module'] == 'contact_form'): ?>
                                <?php include('notif_form_entry.php'); ?>
                            <?php elseif (isset($item['rel_type']) AND $item['rel_type'] == 'content' AND isset($item['module']) AND $item['module'] == 'comments'): ?>
                                <?php include('notif_comment.php'); ?>
                            <?php else: ?>



                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>


            <?php if ($is_quick == false): ?>
                <div class="mw-ui-link-nav" style="padding: 20px 0;">
                    <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a>
                    <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a>
                    <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
                </div>
            <?php endif; ?>

        <?php else : ?>
            <?php if ($filter_by): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="no-items-box no-notifications" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_notifications.svg');">
                            <h4><?php _e("No notifications for this filter"); ?></h4>
                            <p>Here you will be able to see notifications <br/> about orders, comments, email and others.</p>
                            <br/>
                            <a href="?" class="btn btn-outline-primary btn-sm icon-left"><i class="mdi mdi-arrow-left"></i> <span class="">Back</span></a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($is_quick == false): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="no-items-box no-notifications" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_notifications.svg');">
                                <h4>You don't have any notifications</h4>
                                <p>Here you will be able to see notifications <br/> about orders, comments, email and others.</p>
                                <br/>
                                <a href="<?php print admin_url() ?>view:dashboard" class="btn btn-outline-primary btn-sm icon-left"><i class="mdi mdi-arrow-left"></i> <span class="">Back</span></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
