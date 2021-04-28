<?php

api_expose_admin('whitelabel/whmcs_status', function() {

    try {
        $checkConnection = \Whmcs::GetProducts();
    } catch (\Exception $e) {
        return ['error'=> $e->getMessage()];
    }

    if (empty($checkConnection)) {
        return ['error'=>'Something went wrong. Can\'t connect to the WHMCS.'];
    }

    if (isset($checkConnection['result']) && $checkConnection['result'] == 'error') {
        return ['error'=>$checkConnection['message']];
    }

    return ['success'=>'Connection with WHMCS is successfully.'];

});

