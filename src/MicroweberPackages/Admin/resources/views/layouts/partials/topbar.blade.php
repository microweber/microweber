
<?php
$past_page = site_url();

$last_page_front = session_get('last_content_id');
if ($last_page_front == false) {
    if (isset($_COOKIE['last_page'])) {
        $last_page_front = $_COOKIE['last_page'];
    }
}

if ($last_page_front != false) {
    $cont_by_url = app()->content_manager->get_by_id($last_page_front, true);
    if (isset($cont_by_url) and $cont_by_url == false) {
        $past_page = app()->content_manager->get("order_by=updated_at desc&limit=1");
        if (isset($past_page[0])) {
            $past_page = app()->content_manager->link($past_page[0]['id']);
        }
    } else {
        $past_page = app()->content_manager->link($last_page_front);
    }
} else {
    $past_page = app()->content_manager->get("order_by=updated_at desc&limit=1");
    if (isset($past_page[0])) {
        $past_page = app()->content_manager->link($past_page[0]['id']);

    } else {
        $past_page = site_url();
    }
}
if (!$past_page) {
    $past_page = site_url();
}
?>

<header class="position-sticky sticky-top admin-navigation-colorscheme">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-1">

            <ul class="nav" id="admin-header-logo-nav">
                <li id="admin-logo-nav-toggle">
                        <span class="js-toggle-mobile-nav">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                </li>
                <li id="admin-logo">
                    <?php
                    if (mw()->ui->admin_logo != false):
                        $logo = mw()->ui->admin_logo;
                    elseif (mw()->ui->admin_logo_login() != false):
                        $logo = mw()->ui->admin_logo_login();
                    else:
                        $logo = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
                    endif;
                    ?>
                    <a href="<?php print admin_url(); ?>">
                         <img alt="" src="<?php print $logo; ?>">
                    </a>
                </li>

                <?php
                if (user_can_access('module.content.edit')):
                    ?>
                <li class="mx-1">
                    <button type="button" class="btn btn-outline-success btn-rounded btn-sm-only-icon "
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-plus"></i>
                        <span class="d-none d-md-block"><?php _e("Add New"); ?></span>
                    </button>
                    <div class="dropdown-menu ">
                            <?php $custom_view = url_param('view'); ?>
                            <?php $custom_action = url_param('action'); ?>
                            <?php event_trigger('content.create.menu'); ?>
                            <?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>
                            <?php if (!empty($create_content_menu)): ?>
                            <?php foreach ($create_content_menu as $type => $item): ?>
                            <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
                            <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
                            <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
                            <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
                            <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
                            <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>
                            <?php
                            $base_url = route('admin.content.create');
                            if (Route::has('admin.' . $item['content_type'] . '.create')) {
                                $base_url = route('admin.' . $item['content_type'] . '.create');
                            }
                            ?>
                        <a class="dropdown-item" href="<?php print $base_url; ?>"><span
                                class="<?php print $class; ?>"></span> <?php print $title; ?></a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </li>
                <?php endif; ?>

            </ul>

            <div class="mw-lazy-load-module module" id="admin-header-notification"
                 type="admin/header_notifications"></div>


            <?php


            event_trigger('mw.admin.header.toolbar'); ?>

            <ul class="nav">
                <?php if (user_can_access('module.content.edit')): ?>
                <li class="mx-1">
                    <a href="<?php print $past_page ?>?editmode=n"
                       class="btn btn-outline-success btn-rounded btn-sm-only-icon go-live-edit-href-set go-live-edit-href-set-view">
                        <i class="mdi mdi-earth"></i><span class="d-none d-md-block ml-1"><?php _e("Website"); ?></span>
                    </a>
                </li>
                <li class="mx-1">
                    <a href="<?php print $past_page ?>?editmode=y"
                       class="btn btn-primary btn-rounded btn-sm-only-icon go-live-edit-href-set">
                        <i class="mdi mdi-eye-outline"></i><span
                            class="d-none d-md-block ml-1"><?php _e("Live Edit"); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php event_trigger('mw.admin.header.toolbar.ul'); ?>
            </ul>
        </div>
    </div>
</header>
