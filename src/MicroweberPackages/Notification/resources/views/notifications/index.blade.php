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
        $.post("<?php echo route('admin.notification.read') ?>", {ids:$item_id}, function () {

            $('.mw-ui-admin-notif-item-' + $item_id).find('.card').removeClass('card-success');

        });
    }

    mw.notif_item_reset = function ($item_id) {
        $.post("<?php echo route('admin.notification.reset') ?>", {ids:$item_id}, function () {
            $('.mw-ui-admin-notif-item-' + $item_id).find('.card').addClass('card-success').removeClass('bg-silver');
        });
    }

    mw.notif_item_delete = function ($item_id) {
        mw.tools.confirm(mw.msg.del, function () {
            $.post("<?php echo route('admin.notification.delete') ?>", {ids:$item_id}, function () {
                if ($item_id == 'all') {
                    window.location.reload();
                }
                $('.mw-ui-admin-notif-item-' + $item_id).find('.card').addClass('card-danger');
                $('.mw-ui-admin-notif-item-' + $item_id).effect( "blind", "slow" );
                setTimeout(function () {
                    $('.mw-ui-admin-notif-item-' + $item_id).remove();
                },300);
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
            $.post("<?php echo route('admin.notification.delete') ?>", {ids: selectedNotificationIds}, function () {

                $.each(selectedNotificationIds, function (k,notifid) {
                    $('.mw-ui-admin-notif-item-' + notifid).find('.card').addClass('card-danger');
                    $('.mw-ui-admin-notif-item-' + notifid).effect( "blind", "slow" );
                    setTimeout(function () {
                        $('.mw-ui-admin-notif-item-' + notifid).remove();
                    },300);

                });

            });
        });
    }

    mw.notif_read_selected = function () {
        var selectedNotificationIds = mw.notif_get_selected();

        mw.tools.confirm('<?php _e('Are you sure you want to read'); ?> ' + selectedNotificationIds.length + ' <?php _e('notifications'); ?>?', function () {
            $.post("<?php echo route('admin.notification.read') ?>", {ids: selectedNotificationIds}, function () {

                $.each(selectedNotificationIds, function (k,notifid) {
                    $('.mw-ui-admin-notif-item-' + notifid).find('.card').removeClass('card-success');
                });

            });
        });
    }

    mw.notif_reset_all = function () {
        $.post("<?php echo route('admin.notification.reset') ?>", {ids: 'all'}, function () {
            window.location.href = window.location.href;
        });
    }

    mw.notif_reset_selected = function () {
        var selectedNotificationIds = mw.notif_get_selected();

        mw.tools.confirm('<?php _e('Are you sure you want to unread'); ?> ' + selectedNotificationIds.length + ' <?php _e('notifications'); ?>?', function () {
            $.post("<?php echo route('admin.notification.reset') ?>", {ids: selectedNotificationIds}, function () {

                $.each(selectedNotificationIds, function (k,notifid) {
                    $('.mw-ui-admin-notif-item-' + notifid).find('.card').addClass('card-success').removeClass('bg-silver');
                });

            });
        });
    }

    mw.notif_mark_all_as_read = function () {
        $.post("<?php echo route('admin.notification.read') ?>", {ids: 'all'}, function () {
            window.location.href = window.location.href;
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
        <h5><i class="mdi mdi-bell text-primary mr-3"></i> <strong><?php _e("Notifications"); ?></strong></h5>
    </div>

    <div class="card-body" id="">

        <?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
        <?php $periods_printed = array(); ?>
        <div class="toolbar row mb-3">
            <div class="col-12 text-center text-sm-left">
                <h5 class="mb-0"><strong><?php _e("All Activities"); ?></strong></h5>
                <small class="text-muted"><?php _e("List of all notifications of your website."); ?>{{-- <a href="javascript:;"
                                                                 onClick="mw.load_module('admin/notifications/system_log','#admin_notifications');"
                                                                 class="d-block d-sm-block float-sm-right"><?php _e("Show system log"); ?></a>--}}
                </small>
            </div>
            <div class="col-12 d-sm-flex align-items-center justify-content-between">
                <div class="text-center text-md-left my-2">
                    <div class="custom-control custom-checkbox d-inline-block my-2 mr-3">
                        <input type="checkbox" class="custom-control-input check-all js-check-all" id="check-all"/>
                        <label class="custom-control-label" for="check-all"><?php _e("Check all"); ?></label>
                    </div>

                    <div class="d-inline-flex">
                        <div class="js-show-options" style="display: none;">
                            <a href="javascript:mw.notif_read_selected();"
                               class="btn btn-outline-success btn-sm mr-1 mr-lg-2 btn-lg-only-icon  notif-read-selected"><i
                                        class="mdi mdi-email-open"></i> <span
                                        class="d-none d-xl-block"><?php _e("Mark as read"); ?></span></a>
                            <a href="javascript:mw.notif_reset_selected();"
                               class="btn btn-outline-warning btn-sm mr-1 mr-lg-2 btn-lg-only-icon notif-unread-selected"><i
                                        class="mdi mdi-email"></i> <span
                                        class="d-none d-xl-block"><?php _e("Mark as unread"); ?></span></a>
                            <a href="javascript:mw.notif_delete_selected();"
                               class="btn btn-outline-danger btn-sm mr-1 mr-lg-2 btn-lg-only-icon notif-delete-selected"><i
                                        class="mdi mdi-delete"></i> <span
                                        class="d-none d-xl-block"><?php _e("Delete selected"); ?></span></a>

                        </div>
                        <div>
                            <?php if ($is_quick == true): ?>
                            <a href="javascript:mw.notif_mark_all_as_read();"
                               class="mw-ui-link"><?php _e("Read all"); ?></a> /
                            <a href="javascript:mw.notif_reset_all();"
                               class="mw-ui-link"><?php _e("Unread all"); ?></a> /
                            <a href="javascript:mw.notif_item_delete('all');"
                               class="mw-ui-link"><?php _e("Delete all"); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="float-sm-right text-center text-md-right my-2">
                    <span class="d-inline d-sm-none d-md-inline"> <?php _e("Show notifications"); ?></span>

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

                        <option value="?"><?php _e("All notifications"); ?></option>

                        <option value="?type=Comment"<?php if ($type == 'Comment'): ?> selected="selected" <?php endif; ?>> <?php _e("Comments"); ?></option>
                        <option value="?type=Order"<?php if ($type == 'Order'): ?> selected="selected" <?php endif; ?>> <?php _e("Orders"); ?></option>
                        <option value="?type=Product"<?php if ($type == 'Product'): ?> selected="selected" <?php endif; ?>> <?php _e("Products"); ?></option>
                        <option value="?type=NewFormEntry"<?php if ($type == 'NewFormEntry'): ?> selected="selected" <?php endif; ?>> <?php _e("Contact forms"); ?></option>
                        <option value="?type=NewRegistration"<?php if ($type == 'NewRegistration'): ?> selected="selected" <?php endif; ?>> <?php _e("New Registrations"); ?></option>
                        <option value="?type=Customer"<?php if ($type == 'Customer'): ?> selected="selected" <?php endif; ?>> <?php _e("Customers"); ?></option>

                    </select>
                </div>
            </div>
        </div>


        <?php if (!empty($notifications)): ?>
        <div class="timeline js-all-notifications">
            <div class="row timeline-event">
                <div class="col pr-0 timeline-line datetime-indicator">
                    <button type="button" class="btn btn-primary btn-rounded btn-sm"><?php _e("Past hour"); ?></button>
                </div>
            </div>

            <style>
                .timeline-event .row.align-items-center {
                    margin-top: -10px;
                }

                .timeline-event .row.align-items-center .item-image {
                    max-width: 55px;
                }
                .timeline-line-last {
                    width: 0px;
                    height: 0px;
                }
            </style>

            <?php
            $countIterations = 0;
            $allNotificationsCount = count($notifications);
            foreach ($notifications as $notification):
            $countIterations++;

            $notificationMessage = trim($notification['message']);
            if (empty($notificationMessage)) {
                continue;
            }
            ?>

            <div class="row timeline-event mw-ui-admin-notif-item-{{$notification['id']}}" onclick="mw.notif_item_read('{{$notification['id']}}')">
                <div class="col pr-0 timeline-line <?php if ($countIterations == $allNotificationsCount): ?>timeline-line-last<?php endif; ?>">
                    <div class="custom-control custom-checkbox d-inline-block">
                        <input type="checkbox" class="custom-control-input js-checked-checkbox"
                               id="notif-{{$notification['id']}}" value="{{$notification['id']}}"
                               name="checked[{{$notification['id']}}]">
                        <label class="custom-control-label" for="notif-{{$notification['id']}}"></label>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-rounded btn-icon">
                        {!! $notification['icon'] !!}
                    </button>
                </div>
                <div class="col">
                    <?php echo $notificationMessage; ?>
                </div>
            </div>

            <?php endforeach; ?>


            <div class="text-center mt-3">
            {{ $notifications_model->withQueryString()->links() }}
            </div>

        </div>


        <?php if ($is_quick == false): ?>
        <div class="mw-ui-link-nav" style="padding: 20px 0;">
            <a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link"><?php _e("Read all"); ?></a>
            <a href="javascript:mw.notif_reset_all();" class="mw-ui-link"><?php _e("Unread all"); ?></a>
            <a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link"><?php _e("Delete all"); ?></a>
        </div>
        <?php endif; ?>

        <?php else : ?>
        <?php if ($type): ?>
        <div class="row">
            <div class="col-12">
                <div class="no-items-box no-notifications"
                     style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_notifications.svg');">
                    <h4><?php _e("No notifications for this filter"); ?></h4>
                    <p><?php _e("Here you will be able to see notifications"); ?> <br/> <?php _e("about orders, comments, email and others."); ?></p>
                    <br/>
                    <a href="?" class="btn btn-outline-primary btn-sm icon-left"><i class="mdi mdi-arrow-left"></i>
                        <span class=""><?php _e("Back"); ?></span></a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <?php if ($is_quick == false): ?>
        <div class="row">
            <div class="col-12">
                <div class="no-items-box no-notifications"
                     style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_notifications.svg');">
                    <h4><?php _e("You don't have any notifications"); ?></h4>
                    <p><?php _e("Here you will be able to see notifications"); ?> <br/><?php _e("about orders, comments, email and others."); ?> </p>
                    <br/>
                    <a href="<?php print admin_url() ?>view:dashboard" class="btn btn-outline-primary btn-sm icon-left"><i
                                class="mdi mdi-arrow-left"></i> <span class=""><?php _e("Back"); ?></span></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</div>
