<?php
if (function_exists('get_whitelabel_whmcs_settings')) {

    $whitelabelSettings = get_whitelabel_whmcs_settings();

    if (isset($whitelabelSettings['whmcs_url']) && !empty($whitelabelSettings['whmcs_url'])) {

        event_bind('mw.admin.sidebar.li.last', function () use ($whitelabelSettings) {
            echo '<li class="nav-item dropdown" style="margin-top:15px;">
                    <a class="nav-link" href="' . $whitelabelSettings['whmcs_url'] . '/clientarea.php?action=services" target="_blank">
                        <i class="mdi mdi-account-box-multiple"></i> ' . _e('Members area', true) . '</a>
                </li>';
        });

    }
}
