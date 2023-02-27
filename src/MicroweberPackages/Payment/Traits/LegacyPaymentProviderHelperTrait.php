<?php

namespace MicroweberPackages\Payment\Traits;


trait LegacyPaymentProviderHelperTrait
{

    public function enable(): bool
    {
        $saveOption = [];
        $saveOption['option_key'] = 'payment_gw_' . $this->module;
        $saveOption['option_value'] = '1';
        $saveOption['option_group'] = 'payments';
        save_option($saveOption);
        return true;
    }

    public function disable(): bool
    {
        $saveOption = [];
        $saveOption['option_key'] = 'payment_gw_' . $this->module;
        $saveOption['option_value'] = '0';
        $saveOption['option_group'] = 'payments';
        save_option($saveOption);
        return true;
    }


    public function isEnabled(): bool
    {
        $status = get_option('payment_gw_' . $this->module, 'payments') == '1' ? true : false;

        return $status;
    }


    public function process($place_order): array
    {
        $processFile = normalize_path(modules_path() . $this->module . DS . 'process.php', false);

        if (is_file($processFile)) {
            include $processFile;
        }
        return $place_order;
    }

}
