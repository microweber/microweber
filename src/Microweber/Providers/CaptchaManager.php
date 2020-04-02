<?php

namespace Microweber\Providers;

use Microweber\Utils\Adapters\Captcha\GoogleRecaptcha;
use Microweber\Utils\Adapters\Captcha\MicroweberCaptcha;

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
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }

        $this->adapter = new MicroweberCaptcha($app);
    }

    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        if (!empty($captcha_id)) {
            $this->_checkProvider($captcha_id);
        }

        return $this->adapter->validate($key, $captcha_id, $unset_if_found);
    }

    public function render($params = array())
    {
        if (isset($params['id'])) {
            $this->_checkProvider($params['id']);
        }

        return $this->adapter->render($params);
    }

    private function _checkProvider($captcha_id) {
        $captcha_provider = get_option('captcha_provider', $captcha_id);
        if ($captcha_provider == 'google_recaptcha') {
            $this->adapter = new GoogleRecaptcha();
        }
    }
}
