<?php
only_admin_access();
?>


<?php

$order_notif_html = false;
$new_orders_count = false;

$font_size = 11;


$shop_disabled = get_option('shop_disabled', 'website') == 'y';

if (!$shop_disabled) {
    $new_orders_count = mw()->order_manager->get_count_of_new_orders();
    $font_size = 11;

    if($new_orders_count  and $new_orders_count > 100){
        $new_orders_count = '99+';
        $font_size = 8;
    }

    if ($new_orders_count) {
        $order_notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: '.$font_size.'px; width: 20px; height:20px;">' . $new_orders_count . '</span>';
    }
}

$comments_notif_html = false;
$new_comments_count = Auth::user()->unreadNotifications()->where('type', 'like', '%Comment%')->count('id');
if ($new_comments_count) {
    $font_size = 11;

    if($new_comments_count > 100){
        $new_comments_count = '99+';
        $font_size = 8;
    }

    $comments_notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: '.$font_size.'px; width: 20px; height:20px;">' . $new_comments_count . '</span>';
}

$notif_html = '';
$notif_count = Auth::user()->unreadNotifications()->count('id');
if ($notif_count > 0) {
    $font_size = 11;
    if($notif_count > 100){
        $notif_count = '99+';
        $font_size = 8;
    }

    $notif_html = '<span class="badge badge-success badge-pill mr-1 lh-0 d-inline-flex justify-content-center align-items-center" style="font-size: '.$font_size.'px; width: 20px; height:20px;">' . $notif_count . '</span>';
}
?>



<ul class="nav">

    <?php if ($new_orders_count > 0): ?>
        <li class="mx-2">
            <a href="<?php echo route('admin.order.index'); ?>" class="btn btn-link btn-rounded icon-left text-dark px-0">
                <?php print $order_notif_html; ?>
                <i class="mdi mdi-shopping text-muted m-0"></i>
                <span class="d-none d-xl-block mw-colorscheme-text-white">
                                    <?php if ($new_orders_count == 1): ?>
                                        <?php _e("New order"); ?>
                                    <?php elseif ($new_orders_count > 1): ?>
                                        <?php _e("New orders"); ?>
                                    <?php endif; ?>
                                </span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($new_comments_count > 0): ?>
        <li class="mx-2">
            <a href="<?php print admin_url(); ?>view:modules/load_module:comments" class="btn btn-link btn-rounded icon-left text-dark px-0">
                <?php print $comments_notif_html; ?>&nbsp;
                <i class="mdi mdi-comment-account text-muted m-0"></i>
                <span class="d-none d-xl-block mw-colorscheme-text-white">
                                <?php if ($new_comments_count == 1): ?>
                                    <?php _e("New comment"); ?>
                                <?php elseif ($new_comments_count > 1): ?>
                                    <?php _e("New comments"); ?>
                                <?php else: ?>
                                    <?php _e("Comments"); ?>
                                <?php endif; ?>
                            </span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($notif_count > 0): ?>
        <li class="mx-2 ">
            <a href="<?php echo route('admin.notification.index'); ?>" class="btn btn-link btn-rounded icon-left text-dark px-0">
                <?php print $notif_html; ?>
                <i class="mdi mdi-newspaper-variant-multiple text-muted m-0"></i>


                <span class="notif-label d-none d-xl-block">
                                <?php if ($notif_count == 1): ?>
                                    <?php _e("New notification"); ?>
                                <?php elseif ($notif_count > 1): ?>
                                    <?php _e("New notifications"); ?>
                                <?php else: ?>
                                    <?php _e("Notifications"); ?>
                                <?php endif; ?>
                            </span>
            </a>
        </li>
    <?php endif; ?>

</ul>
