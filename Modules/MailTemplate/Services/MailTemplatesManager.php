<?php

namespace Modules\MailTemplate\Services;

use Illuminate\Support\Facades\File;

class MailTemplatesManager
{
    protected $emailsPath;

    public function __construct()
    {
        $this->emailsPath = dirname(base_path()) . '/View/emails';
    }

    public function getMailTemplateFiles(): array
    {
        $templates = [];
        
        if (!File::exists($this->emailsPath)) {
            return $templates;
        }

        $files = File::files($this->emailsPath);
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'php' || $file->getExtension() === 'blade.php') {
                $name = $file->getBasename('.' . $file->getExtension());
                $type = $this->getTemplateType($name);
                
                $templates[] = [
                    'file' => $name,
                    'type' => $type,
                    'name' => $this->formatTemplateName($name),
                    'subject' => $this->formatTemplateName($name),
                    'path' => $file->getPathname()
                ];
            }
        }

        return $templates;
    }

    protected function getTemplateType(string $filename): string
    {
        // Convert filename to type
        // e.g., 'new_order_notification.blade.php' becomes 'new_order'
        $type = str_replace(['_notification', '_email'], '', $filename);
        $type = str_replace(['.blade', '.php'], '', $type);
        
        return $type;
    }

    protected function formatTemplateName(string $name): string
    {
        // Convert filename to readable name
        // e.g., 'new_order_notification' becomes 'New Order Notification'
        $name = str_replace(['_notification', '_email', '.blade', '.php'], '', $name);
        $name = str_replace('_', ' ', $name);
        
        return ucwords($name);
    }

    public function getTemplateContent(string $name): ?string
    {
        $path = $this->emailsPath . '/' . $name;
        
        if (File::exists($path . '.blade.php')) {
            return File::get($path . '.blade.php');
        }
        
        if (File::exists($path . '.php')) {
            return File::get($path . '.php');
        }
        
        return null;
    }
}
