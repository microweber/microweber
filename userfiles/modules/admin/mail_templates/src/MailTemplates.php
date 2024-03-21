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

    public function getMailTemplatePath()
    {
        return self::$mailTempalatesPaths;
    }

}
