<?php
include_once(__DIR__ . "/whmcs.class.php");

api_expose_admin('whitelabel/whmcs_status', function () {

    $settings = get_whitelabel_whmcs_settings();
    $whmcs_is_not_set_up = false;
    if (!isset($settings['whmcs_url']) or (isset($settings['whmcs_url']) and !$settings['whmcs_url'])) {
        $whmcs_is_not_set_up = true;
    }
    if (!isset($settings['whmcs_auth_type']) or (isset($settings['whmcs_auth_type']) and !$settings['whmcs_auth_type'])) {
        $whmcs_is_not_set_up = true;
    }

    if ($whmcs_is_not_set_up) {
        return ['warning' => 'WHMCS connection is not set'];
    }


    try {
        $whmcs = new WHMCS();
        $whmcs->setUrl($settings['whmcs_url'] . '/includes/api.php');

        if ($settings['whmcs_auth_type'] == 'password') {
            $whmcs->setUsername($settings['whmcs_username']);
            $whmcs->setPassword($settings['whmcs_password']);
        } else {
            $whmcs->setIdentifier($settings['whmcs_api_identifier']);
            $whmcs->setSecret($settings['whmcs_api_secret']);
        }

        $status = $whmcs->getProducts();
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }

    if (empty($status)) {
        return ['error' => 'Something went wrong. Can\'t connect to the WHMCS.'];
    }

    if (isset($status['result']) && $status['result'] == 'error') {
        return ['error' => $status['message']];
    }

    return ['success' => 'Connection with WHMCS is successfully.'];

});

if (!function_exists('get_whitelabel_whmcs_settings')) {

    function get_whitelabel_whmcs_settings()
    {
        $whmcs_url = false;
        $whmcs_auth_type = false;
        $whmcs_api_identifier = false;
        $whmcs_api_secret = false;
        $whmcs_username = false;
        $whmcs_password = false;

        if(!function_exists('get_white_label_config')){
            return [];
        }

        $settings = get_white_label_config();
        if (isset($settings['whmcs_url'])) {
            $whmcs_url = $settings['whmcs_url'];
        }
        if (isset($settings['whmcs_auth_type'])) {
            $whmcs_auth_type = $settings['whmcs_auth_type'];
        }
        if (isset($settings['whmcs_api_identifier'])) {
            $whmcs_api_identifier = $settings['whmcs_api_identifier'];
        }
        if (isset($settings['whmcs_api_secret'])) {
            $whmcs_api_secret = $settings['whmcs_api_secret'];
        }
        if (isset($settings['whmcs_username'])) {
            $whmcs_username = $settings['whmcs_username'];
        }
        if (isset($settings['whmcs_password'])) {
            $whmcs_password = $settings['whmcs_password'];
        }

        return [
            'whmcs_url' => $whmcs_url,
            'whmcs_auth_type' => $whmcs_auth_type,
            'whmcs_api_identifier' => $whmcs_api_identifier,
            'whmcs_api_secret' => $whmcs_api_secret,
            'whmcs_username' => $whmcs_username,
            'whmcs_password' => $whmcs_password
        ];
    }

    $whitelabelSettings = get_whitelabel_whmcs_settings();

    if (isset($whitelabelSettings['whmcs_url']) && !empty($whitelabelSettings['whmcs_url'])) {

        event_bind('mw.admin.sidebar.li.last', function () use ($whitelabelSettings) {
            echo '<li class="nav-item dropdown" style="margin-top:15px;">
                    <a class="nav-link" href="' . $whitelabelSettings['whmcs_url'] . 'clientarea.php?action=services" target="_blank">
                        <i class="mdi mdi-account-box-multiple"></i> ' . _e('Members area', true) . '</a>
                </li>';
        });

    }
}
