<?php

namespace MicroweberPackages\Notification\Mail;

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

    private function getSubjectFromTemplate($templateContent)
    {
        $subject = '';
        $lines = explode("\n", $templateContent);
        foreach ($lines as $line) {
            if (str_contains($line, 'subject:')) {
                $subject = str_replace('subject:', '', $line);
                $subject = trim($subject);
                break;
            }
        }
        return $subject;
    }

    public function getMailTemplateFiles()
    {
        $templateFiles = [];
        $paths = self::$mailTempalatesPaths;
        foreach ($paths as $path) {
            $files = scandir($path);
            foreach ($files as $file) {
                if (str_contains($file, "blade.php")) {


                    $templateType = str_replace('.blade.php', false, $file);
                    $templateName = str_replace('_', ' ', $templateType);
                    $templateName = ucfirst($templateName);

                    $templateContent = file_get_contents($path . $file);
                    $templateSubject = $this->getSubjectFromTemplate($templateContent);

                    $templateFiles[] = [
                        'type' => $templateType,
                        'name' => $templateName,
                        'file' => $file,
                        'path' => $path . $file,
                        'subject' => $templateSubject
                    ];
                }
            }
        }

        return $templateFiles;
    }

}
