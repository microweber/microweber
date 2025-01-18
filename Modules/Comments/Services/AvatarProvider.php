<?php

namespace Modules\Comments\Services;

class AvatarProvider
{
    protected $config;

    public function __construct()
    {
        $this->config = config('modules.comments.avatar_provider');
    }

    public function getAvatarUrl($comment)
    {
        // Try UI Avatar first
        if (isset($this->config['ui-avatar'])) {
            $config = $this->config['ui-avatar'];
            $name = urlencode($comment->comment_name);
            $bgColor = $config['dynamic_bg_color'] ? $this->generateDynamicColor($name) : $config['bg_color'];
            
            return $config['url'] . "?name={$name}&color={$config['text_color']}&background={$bgColor}";
        }
        
        // Fallback to Gravatar if UI Avatar not configured
        if (isset($this->config['gravatar'])) {
            $config = $this->config['gravatar'];
            $email = $comment->comment_email;
            $hash = md5(strtolower(trim($email)));
            
            return $config['url'] . $hash;
        }

        // Default fallback avatar
        return 'https://www.gravatar.com/avatar/default?d=mp';
    }

    protected function generateDynamicColor($name)
    {
        $config = $this->config['ui-avatar'];
        $hash = md5($name);
        
        // Convert hash to HSL values within configured ranges
        $h = hexdec(substr($hash, 0, 2)) % ($config['hRange'][1] - $config['hRange'][0]) + $config['hRange'][0];
        $s = hexdec(substr($hash, 2, 2)) % ($config['sRange'][1] - $config['sRange'][0]) + $config['sRange'][0];
        $l = hexdec(substr($hash, 4, 2)) % ($config['lRange'][1] - $config['lRange'][0]) + $config['lRange'][0];
        
        // Convert HSL to hex
        return $this->hslToHex($h, $s, $l);
    }

    protected function hslToHex($h, $s, $l)
    {
        $h /= 360;
        $s /= 100;
        $l /= 100;

        $r = $g = $b = $l;

        if ($s != 0) {
            $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
            $p = 2 * $l - $q;

            $r = $this->hueToRgb($p, $q, $h + 1/3);
            $g = $this->hueToRgb($p, $q, $h);
            $b = $this->hueToRgb($p, $q, $h - 1/3);
        }

        return sprintf("%02x%02x%02x", $r * 255, $g * 255, $b * 255);
    }

    protected function hueToRgb($p, $q, $t)
    {
        if ($t < 0) $t += 1;
        if ($t > 1) $t -= 1;
        if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
        if ($t < 1/2) return $q;
        if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
        return $p;
    }
}
