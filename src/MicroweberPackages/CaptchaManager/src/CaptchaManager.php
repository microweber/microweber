<?php

namespace MicroweberPackages\CaptchaManager;

use MicroweberPackages\CaptchaManager\Adapters\GoogleRecaptchaV2;
use MicroweberPackages\CaptchaManager\Adapters\GoogleRecaptchaV3;
use MicroweberPackages\CaptchaManager\Adapters\MicroweberCaptcha;

/**
 * Cache class.
 *
 * These functions will allow you to save and get data from the MW cache system
 *
 * @category Cache
 * @desc     These functions will allow you to save and get data from the MW cache system
 */
class CaptchaManager
{
    /**
     * An instance of the Microweber Application class.
     *
     * @var
     */
    public $app;
    /**
     * An instance of the cache adapter to use.
     *
     * @var
     */
    public $adapter;

    public function __construct($app = null)
    {

            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = app();
            }


        $captcha_provider = get_option('provider', 'captcha');
        if ($captcha_provider == 'google_recaptcha_v2') {
            $this->adapter = new GoogleRecaptchaV2();
        } else if ($captcha_provider == 'google_recaptcha_v3') {
            $this->adapter = new GoogleRecaptchaV3();
        } else {
            $this->adapter = new MicroweberCaptcha($app);
        }
    }

    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        return $this->adapter->validate($key, $captcha_id, $unset_if_found);
    }

    public function render($params = array())
    {
        return $this->adapter->render($params);
    }

}
