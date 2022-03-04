<?php

namespace MicroweberPackages\Package;

use Composer\Semver\Comparator;

class MicroweberComposerPackage
{

    public static $allPackages = [];
    public static $localPackages = [];


    public static function loadLocalData()
    {
        self::$allPackages = [];
        self::$localPackages = mw()->update->collect_local_data();

        foreach(self::$localPackages['modules'] as $package) {
            self::$allPackages[] = $package;
        }
        foreach(self::$localPackages['templates'] as $package) {
            self::$allPackages[] = $package;
        }
    }

    public static function format($version)
    {
        if (empty(self::$allPackages)) {
            self::loadLocalData();
        }

        $version['release_date'] = date('Y-m-d H:i:s');
        $version['latest_version'] = $version;

        if ($version['type'] == 'library' || $version['type'] == 'composer-plugin' || $version['type'] == 'application') {
            return;
        }

        $currentInstall = false;
        foreach(self::$allPackages as $module) {

            if (isset($version['target-dir']) && $module['dir_name'] == $version['target-dir']) {

                $currentInstall = [];
                $currentInstall['composer_type'] = $version['type'];
                $currentInstall['local_type'] = $version['type'];
                $currentInstall['module'] = $module['name'];
                $currentInstall['module_details'] = $module;

                $version['has_update'] = false;

                $v1 = trim($version['latest_version']['version']);
                $v2 = trim($module['version']);

                if ($v1 != $v2) {
                    if (Comparator::greaterThan($v1, $v2)) {
                        $version['has_update'] = true;
                    }
                }

                $version['is_symlink'] = false;
                if (isset($module['is_symlink']) && $module['is_symlink']) {
                    $version['has_update'] = false;
                    $version['is_symlink'] = true;
                }

                break;
            }
        }

        $version['current_install'] = $currentInstall;

        return $version;
    }

}
