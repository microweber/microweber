<?php

namespace MicroweberPackages\Module\Http\Controllers\Api;

use Illuminate\Http\Request;
use MicroweberPackages\Utils\Zip\Unzip;

class ModuleApiController
{
    /**
     * Upload new module
     * @param Request $request
     * @return string[]
     */
    public function upload(Request $request)
    {
        $query = $request->all();

        $overwrite = false;
        if (isset($query['overwrite']) && $query['overwrite'] == 1) {
            $overwrite = true;
        }

        if (! isset($query['src'])) {
            return array(
                'error' => 'You have not provided src to the file.'
            );
        }

        $checkFile = url2dir(trim($query['src']));
        if (!is_file($checkFile)) {
            return array(
                'error' => 'Uploaded file is not found!'
            );
        }

        $modulesPath = $this->_getModulesPath();

        // Make cache dir
        $cacheModuleDir = $modulesPath . md5(time()). '/';
        mkdir($cacheModuleDir);

        if (!copy($checkFile, $cacheModuleDir . 'uploaded-file.zip')) {

            // Remove cached dir
            rmdir_recursive($cacheModuleDir, false);

            return array(
                'error' => 'Error moving uploaded file!'
            );
        }

        // Unzip uploaded files
        $unzip = new Unzip();
        $unzip->extract($cacheModuleDir . 'uploaded-file.zip', $cacheModuleDir);

        // Check config file
        if (!is_file($cacheModuleDir . "config.php") || !is_file($cacheModuleDir . "composer.json")) {

            // Remove cached dir
            rmdir_recursive($cacheModuleDir, false);

            return array(
                'error' => "config.php or composer.json is not found in module."
            );
        }

        // include($cacheModuleDir . 'config.php');
        $composerModuleJson = json_decode(file_get_contents($cacheModuleDir . "composer.json"), true);

        if (!isset($composerModuleJson['target-dir'])) {

            // Remove cached dir
            rmdir_recursive($cacheModuleDir, false);

            return array(
                'error' => "Target dir not found in composer.json."
            );
        }

        // Remove uploaded file
        @unlink($cacheModuleDir . 'uploaded-file.zip');

        if ($overwrite) {
            // Delete old folder
            rmdir_recursive($modulesPath .'/'. $composerModuleJson['target-dir'], false);
        }

        // Rename cache folder name to theme name
        $renameFolder = @rename($cacheModuleDir, $modulesPath .'/'. $composerModuleJson['target-dir']);

        if (!$renameFolder) {

            // Remove cached dir
            rmdir_recursive($cacheModuleDir, false);

            return array(
                'success' => "Module allready exists!"
            );
        }

        return array(
            'success' => "Module was uploaded success!"
        );
    }

    /**
     * Get template folder
     * @return string
     */
    protected function _getModulesPath() {

        $modulesPath = userfiles_path() . 'modules';
        $modulesPath = normalize_path($modulesPath);

        return $modulesPath;
    }
}
