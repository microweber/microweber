<?php

namespace MicroweberPackages\Utils\Captcha;

use MicroweberPackages\Utils\Captcha\Adapters\GoogleRecaptchaV2;
use MicroweberPackages\Utils\Captcha\Adapters\GoogleRecaptchaV3;
use MicroweberPackages\Utils\Captcha\Adapters\MicroweberCaptcha;

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
            $this->app = mw();
        }

        $captcha_provider = get_option('provider', 'captcha');
        $recaptcha_v3_secret_key = get_option('recaptcha_v3_secret_key', 'captcha');
        $recaptcha_v2_secret_key = get_option('recaptcha_v2_secret_key', 'captcha');


        if ($recaptcha_v2_secret_key and $captcha_provider == 'google_recaptcha_v2') {
            $this->adapter = new GoogleRecaptchaV2();
        } else if ($recaptcha_v3_secret_key and $captcha_provider == 'google_recaptcha_v3') {
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

    public function reset($captcha_id = null)
    {
        if (method_exists($this->adapter, 'reset')) {
            return $this->adapter->reset($captcha_id);
        }
    }


    public function setAdapter($adapter)
    {
        return $this->adapter = $adapter;
    }

}
