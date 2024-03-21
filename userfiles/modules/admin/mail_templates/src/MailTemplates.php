<?php
namespace MicroweberPackages\Modules\MailTemplates;

class MailTemplates
{
    private $mailTempalatesPaths = [];

    public function __construct()
    {
        $defaultMailTemplatesPath = normalize_path(dirname(MW_PATH) . '/View/emails');
        $this->mailTempalatesPaths = $defaultMailTemplatesPath;
    }

    public function registerMailTemplatePath($path)
    {
        if (!is_dir($path)) {
            return false;
        }
        $this->mailTempalatesPaths[] = $path;
    }

    public function getMailTemplatePath()
    {
        return $this->mailTempalatesPaths;
    }

}
