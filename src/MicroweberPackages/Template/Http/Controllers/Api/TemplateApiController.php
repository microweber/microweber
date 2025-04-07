<?php

namespace MicroweberPackages\Template\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Backup\SessionStepper;
use MicroweberPackages\Template\TemplateInstaller;
use MicroweberPackages\Utils\Zip\Unzip;

class TemplateApiController
{
    /**
     * Change website template
     * @param Request $request
      */
    public function change(Request $request)
    {

        $template = $request->get('template', false);
        $importType = $request->get('import_type', 'default');

        $filePath = templates_dir() . $template . DS . 'mw_default_content.zip';


        $importLog = [];

        if ($importType == 'default') {

            $importLog['done'] = true;

            save_option('current_template', $template,'template');

        } else if ($importType == 'only_media' || $importType == 'full' || $importType == 'delete') {
            if (!is_file($filePath)) {
                return ['error'=>'Template dosen\'t have default content file.'];
            }
            $sessionId = SessionStepper::generateSessionId(1);

            $installTemplate = new TemplateInstaller();
            $installTemplate->setSessionId($sessionId);
            $installTemplate->setFile($filePath);
            $installTemplate->setBatchRestoring(false);

            // Delete old content and import fresh content
            if ($importType == 'delete') {
                $installTemplate->setOvewriteById(true);
                $installTemplate->setToDeleteOldContent(true);
                $installTemplate->setToDeleteOldCssFiles(true);
            }

            // Restore all and dont delete old content
            if ($importType == 'full') {
                $installTemplate->setOvewriteById(true);
            }

            // Dont write on database, import only media
            if ($importType == 'only_media') {
                $installTemplate->setWriteOnDatabase(false);
            }



            $importLog['done'] = true;
            $importLog['log'] = $installTemplate->start();

            // But save current template option
            save_option('current_template', $template,'template');
        }
        clearcache();

        return $importLog;
    }

    /**
     * Upload new template
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

        $templatesPath = $this->_getTemplatesPath();

        // Make cache dir
        $cacheTemplateDir = $templatesPath . md5(time()). '/';
        mkdir($cacheTemplateDir);

        if (!copy($checkFile, $cacheTemplateDir . 'uploaded-file.zip')) {

            // Remove cached dir
            rmdir_recursive($cacheTemplateDir, false);

            return array(
                'error' => 'Error moving uploaded file!'
            );
        }

        // Unzip uploaded files
        $unzip = new Unzip();
        $unzip->extract($cacheTemplateDir . 'uploaded-file.zip', $cacheTemplateDir);

        // Check config file
        if (!is_file($cacheTemplateDir . "config.php") || !is_file($cacheTemplateDir . "composer.json")) {

            // Remove cached dir
            rmdir_recursive($cacheTemplateDir, false);

            return array(
                'error' => "config.php or composer.json is not found in template."
            );
        }

        // include($cacheTemplateDir . 'config.php');
        $composerThemeJson = json_decode(file_get_contents($cacheTemplateDir . "composer.json"), true);

        if (!isset($composerThemeJson['target-dir'])) {

            // Remove cached dir
            rmdir_recursive($cacheTemplateDir, false);

            return array(
                'error' => "Target dir not found in composer.json."
            );
        }

        // Remove uploaded file
        @unlink($cacheTemplateDir . 'uploaded-file.zip');

        if ($overwrite) {
            // Delete old folder
            rmdir_recursive($templatesPath .'/'. $composerThemeJson['target-dir'], false);
        }

        // Rename cache folder name to theme name
        $renameFolder = @rename($cacheTemplateDir, $templatesPath .'/'. $composerThemeJson['target-dir']);

        if (!$renameFolder) {

            // Remove cached dir
            rmdir_recursive($cacheTemplateDir, false);

            return array(
                'success' => "Template already exists!"
            );
        }

        return array(
            'success' => "Template was uploaded success!"
        );
    }

    /**
     * Get template folder
     * @return string
     */
    protected function _getTemplatesPath() {

        $templatesPath = base_path() . 'Templates';
        $templatesPath = normalize_path($templatesPath);

        return $templatesPath;
    }

}
