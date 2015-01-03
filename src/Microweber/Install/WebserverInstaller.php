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
        $serverName = $prefix . $serverName;

        if (method_exists($this, $serverName)) {

            return $this->$serverName();
        } else {
            return $this->setupApache();
        }
        return false;
    }

    private function storeConfig($file, $data)
    {
        $file = public_path() . '/' . $file;
        $data = trim($data);
        return \File::put($file, $data);
    }

    private function detectServer($match)
    {
        ob_start();
        phpinfo(1);
        $srv = ob_get_clean();
        $str = 'Server API';
        $srv = substr($srv, strpos($srv, $str) + strlen($str));
        $srv = substr($srv, 0, strpos($srv, "\n"));
        $srv = trim(strip_tags($srv));
        foreach ($match as $m) {
            if (strpos($srv, $m) !== false) {
                return $m;
            }
        }
        return false;
    }

    public function setupApache()
    {
        $forbidDirs = ['app', 'bootstrap', 'config', 'database', 'resources', 'src', 'storage', 'tests', 'vendor'];


        if (function_exists('base_path')) {
            $rwBase = base_path();
        } else if (isset($_SERVER['SCRIPT_NAME'])) {
            $rwBase = dirname($_SERVER['SCRIPT_NAME']);
        } else if (isset($_SERVER['PHP_SELF'])) {
            $rwBase = dirname($_SERVER['PHP_SELF']);
        }

      

        $data = '
        <IfModule mod_rewrite.c>
            <IfModule mod_negotiation.c>
                Options -MultiViews
            </IfModule>
            RewriteEngine On
                # RewriteRule ^(.*)/$ ' . $rwBase . '/$1 [R=301,L]
                RewriteCond %{REQUEST_FILENAME} !-f
                RewriteCond %{REQUEST_FILENAME} !-d
                RewriteRule ^(.*)$ index.php [NC,L]
        </IfModule>
        ';

        foreach ($forbidDirs as $dir) {

            $this->storeConfig($dir . '/.htaccess', 'Deny from all');
        }
        return $this->storeConfig('.htaccess', $data);
    }
}
