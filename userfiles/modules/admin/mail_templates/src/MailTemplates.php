<?php

namespace MicroweberPackages\Modules\MailTemplates;

class MailTemplates
{
    private static $mailTempalatesPaths = [];

    public function __construct()
    {
        $defaultMailTemplatesPath = normalize_path(dirname(MW_PATH) . '/View/emails');
        $this->registerMailTemplatePath($defaultMailTemplatesPath);
    }

    public function registerMailTemplatePath($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        self::$mailTempalatesPaths[] = $path;
    }

    public function getMailTemplateFiles()
    {
        $templateFiles = [];
        $paths = self::$mailTempalatesPaths;
        foreach ($paths as $path) {
            $files = scandir($path);
            foreach ($files as $file) {
                if (str_contains($file, "blade.php")) {

                    $template_type = str_replace('.blade.php', false, $file);
                    $template_name = str_replace('_', ' ', $template_type);
                    $template_name = ucfirst($template_name);

                    $templateFiles[] = [
                        'type' => $template_type,
                        'name' => $template_name,
                        'file' => $file,
                        'path' => $path
                    ];
                }
            }
        }

        return $templateFiles;
    }

}
