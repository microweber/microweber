<?php

namespace ScwCookie;

use Illuminate\Support\Facades\Cookie;

class ScwCookie
{
    public $mod_id		  = '';
    public $config        = [];
    private $decisionMade = false;
    private $choices      = [];

    public function __construct($config,$mod_id)
    {
        $this->config = $config;
        $this->mod_id = $mod_id;
        //$this->config = parse_ini_file("config.ini", true);
        $this->decisionMade = self::getCookie('scwCookieHidden') == 'true';
        $this->choices      = $this->getChoices();
    }

    public function getChoices()
    {
        if (self::getCookie('scwCookie') !== false) {
            $cookie = self::getCookie('scwCookie');
            $cookie = self::decrypt($cookie);
            return $cookie;
        }

        $return = [];
        foreach ($this->enabledCookies() as $name => $label) {
            $return[$name] = $this->config['unsetDefault'];
        }
        return $return;
    }

    public static function encrypt($value)
    {
        $value  = json_encode($value);
        $return = base64_encode($value);
        return $return;
    }

    public static function decrypt($value)
    {
        $value  = base64_decode($value);
        $value  = str_replace('\"', '"', $value);
        $return = json_decode($value, true);
        return $return;
    }

    public function isAllowed($name)
    {
        $choices = $this->getChoices();
        return isset($choices[$name]) && $choices[$name] == 'allowed';
    }

    public function isEnabled($name)
    {
        $check = $this->config[$name];
        return is_array($check) && isset($check['enabled']) && $check['enabled'] == 'true';
    }

    public function getConfig($name, $attribute)
    {
        return isset($this->config[$name]) && isset($this->config[$name][$attribute])
        ? $this->config[$name][$attribute]
        : false;
    }

    public function output()
    {
        echo $this->getOutput();
    }

    public function getOutput()
    {
        Cookie::queue('google-analytics-allow', 0, 360);
        Cookie::queue('facebook-pixel-allow', 0, 360);

        $return = [];

        // Get popup output
        $add_html = '<script>var modId = \'' . $this->mod_id . '\';</script>';
        //$return[] = $this->getOutputHTML('popup',$add_html);

        // Module template
        $module_template = get_option('template', $this->mod_id);
        if ($module_template == false and isset($params['template'])) {
            $module_template = $params['template'];
        }

        if ($module_template != false) {
            $template_file = module_templates('cookie_notice', $module_template);
        } else {
            $template_file = module_templates('cookie_notice', 'default');
        }

        ob_start();
        include $template_file;
        $return[] = trim(ob_get_clean());

        // Get embed codes
        foreach ($this->config as $configKey => $configValue) {
            if (!is_array($configValue) || !$configValue['enabled'] || !$this->isAllowed($configKey)) {
                continue;
            }

            if ($configKey == 'Google_Analytics') {
                Cookie::queue('google-analytics-allow', 1, 360);
                continue;
            }

            if ($configKey == 'Facebook_Pixel') {
                Cookie::queue('facebook-pixel-allow', 1, 360);
                continue;
            }

            $return[] = $this->getOutputHTML('/cookies/'.$configKey.'/output');
        }

        return implode("\n", $return);
    }

    public function getOutputHTML($filename,$add_html='')
    {
        if (!file_exists(__DIR__.'/output/'.$filename.'.php')) {
            return false;
        }

        ob_start();
        if(isset($add_html)) print $add_html;
        include __DIR__.'/output/'.$filename.'.php';
        return trim(ob_get_clean());
    }

    public function enabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {
            if (!$this->isEnabled($name)) {
                continue;
            }
            $return[$name] = $value['label'];
		}

        return $return;
    }

    public function disabledCookies()
    {
        $return = [];
        foreach ($this->config as $name => $value) {
            if (!$this->isEnabled($name) || !is_array($value) || $this->isAllowed($name)) {
                continue;
            }
            $return[$name] = $value['label'];
        }
        return $return;
    }

    public static function setCookie(
        $name,
        $value,
        $lifetime = 30,
        $lifetimePeriod = 'days',
        $domain = '/',
        $secure = false
    ) {
        // Validate parameters
        self::validateSetCookieParams($name, $value, $lifetime, $domain, $secure);

        // Calculate expiry
        $expiry = strtotime('+'.$lifetime.' '.$lifetimePeriod);

        // Set cookie
        return setcookie($name, $value, $expiry, $domain, $secure);
    }

    public static function validateSetCookieParams($name, $value, $lifetime, $domain, $secure)
    {
        // Types of parameters to check
        $paramTypes = [
            // Type => Array of variables
            'string' => [$name, $value, $domain],
            'int'    => [$lifetime],
            'bool'   => [$secure],
        ];

        // Validate basic parameters
        $validParams = self::basicValidationChecks($paramTypes);

        // Ensure parameters are still valid
        if (!$validParams) {
            // Failed parameter check
            header('HTTP/1.0 403 Forbidden');
            throw new \Exception("Incorrect parameter passed to Cookie::set");
        }

        return true;
    }

    public static function basicValidationChecks($paramTypes)
    {
        foreach ($paramTypes as $type => $variables) {
            $functionName = 'is_'.$type;
            foreach ($variables as $variable) {
                if (!$functionName($variable)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function clearCookieGroup($groupName)
    {
        if (!file_exists(__DIR__.'/output/cookies/'.$groupName.'/cookies.php')) {
            return false;
        }
        $clearCookies = include __DIR__.'/output/cookies/'.$groupName.'/cookies.php';

        $defaults = [
            'path'   => '/',
            'domain' => $_SERVER['HTTP_HOST'],
        ];

        if (isset($clearCookies['defaults'])) {
            $defaults = array_merge($defaults, $clearCookies['defaults']);
            unset($clearCookies['defaults']);
        }

        $return = [];

        foreach ($clearCookies as $cookie) {
            $cookie['path'] = isset($cookie['path']) ? $cookie['path'] : $defaults['path'];
            $cookie['domain'] = isset($cookie['domain']) ? $cookie['domain'] : $defaults['domain'];
            self::destroyCookie($cookie['name'], $cookie['path'], $cookie['domain']);
            $return[] = $cookie;
        }

        return $return;
    }

    public static function getCookie($name)
    {
        // If cookie exists - return it, otherwise return false
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
    }

    public static function destroyCookie($name, $path = '', $domain = '')
    {
        // Set cookie expiration to 1 hour ago
        return setcookie($name, '', time() - 3600, $path, $domain);
    }
}
