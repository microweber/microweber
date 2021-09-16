<?php

event_bind('mw.admin.dashboard.content.before', function ($params = false) {

    if (isset($_GET['install_done']) and mw()->ui->disable_marketplace != true) {
        print '<p style="font-size: 24px; color: #555555;"> ' . _e("Welcome to Microweber", true) . ' '. MW_VERSION . '</p>';
        print '<p style="font-size: 13px; color: #999999; line-height: 1.6; margin-bottom: 5px;"> ' . _e("Use Microweber to build your website, online shop or blog.") . ' </p>';
        print '<p style="font-size: 13px; color: #999999; line-height: 1.6; margin-bottom:30px;">' . _e("Create and edit content, sell online, manage orders and clients.") . ' </p>';
    }
});
