<?php

namespace Microweber\Install;

class WebserverInstaller
{
    public function run()
    {
        $prefix = 'setup';
        $prefixLenght = strlen($prefix);
        $supported = get_class_methods(get_class());
        $match = array();
        foreach ($supported as $s) {
            if (false !== strpos($s, $prefix)) {
                $match[] = substr($s, $prefixLenght);
            }
        }
        $serverName = $this->detectServer($match);
        $serverName = $prefix.$serverName;

        if (method_exists($this, $serverName)) {
            return $this->$serverName();
        } else {
            return $this->setupApache();
        }

        return false;
    }

    private function storeConfig($file, $data)
    {
        $file = public_path().'/'.$file;
        $data = trim($data);

        return \File::put($file, $data);
    }

    private function detectServer($match)
    {
        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $server = $_SERVER['SERVER_SOFTWARE'];
            if (stristr($server, 'Apache')) {
                return 'Apache';
            } elseif (stristr($server, 'Ngix')) {
                return 'Ngix';
            }
        }
    }

    public function setupApache()
    {
        $forbidDirs = ['app', 'bootstrap', 'config', 'database', 'resources', 'src', 'storage', 'tests', 'vendor'];

        if (function_exists('base_path')) {
            $rwBase = base_path();
        } elseif (isset($_SERVER['SCRIPT_NAME'])) {
            $rwBase = dirname($_SERVER['SCRIPT_NAME']);
        } elseif (isset($_SERVER['PHP_SELF'])) {
            $rwBase = dirname($_SERVER['PHP_SELF']);
        }

        $data = '
        <IfModule mod_rewrite.c>
            <IfModule mod_negotiation.c>
                Options -MultiViews
            </IfModule>
            RewriteEngine On
                # RewriteRule ^(.*)/$ '.$rwBase.'/$1 [R=301,L]
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule ^(.*)$ index.php [NC,L]
        </IfModule>
        ';

        foreach ($forbidDirs as $dir) {
            $root = base_path().DS;
            if (is_dir($root.$dir)) {
                $writable_path = $dir.DS.'.htaccess';
                if (!is_file($writable_path) and is_writable($writable_path)) {
                    $this->storeConfig($writable_path, 'Deny from all');
                }
            }
        }

        $is_htaccess = public_path().DS.'.htaccess';
        if (!is_file($is_htaccess) and is_writable($is_htaccess)) {
            return $this->storeConfig('.htaccess', $data);
        }
    }
}
