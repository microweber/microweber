<?php

namespace Modules\MailTemplate\Services;

use Modules\MailTemplate\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use Modules\MailTemplate\Mail\TemplateBasedMail;
use Illuminate\Support\Facades\File;

class MailTemplateService
{
    private array $mailTemplatePaths = [];

    public function __construct()
    {
        $defaultMailTemplatesPath = normalize_path(dirname(__DIR__) . '/resources/views/emails');

        $this->registerMailTemplatePath($defaultMailTemplatesPath);
    }

    /**
     * Register a new path to look for mail templates
     */
    public function registerMailTemplatePath(string $path): bool
    {
        if (!is_dir($path)) {
            return false;
        }
        $this->mailTemplatePaths[] = $path;
        return true;
    }

    /**
     * Get all available mail template files
     */
    public function getMailTemplateFiles(): array
    {
        $templates = [];

        foreach ($this->mailTemplatePaths as $path) {
            if (!File::exists($path)) {
                continue;
            }

            $files = File::files($path);

            foreach ($files as $file) {
                if ($file->getExtension() === 'php' || str_contains($file->getFilename(), 'blade.php')) {
                    $name = $file->getBasename('.' . $file->getExtension());
                    $type = $this->getTemplateType($name);

                    $templateContent = File::get($file->getPathname());
                    $subject = $this->getSubjectFromTemplate($templateContent);

                    $templates[] = [
                        'file' => $name,
                        'type' => $type,
                        'name' => $this->formatTemplateName($name),
                        'subject' => $subject,
                        'path' => $file->getPathname()
                    ];
                }
            }
        }

        return $templates;
    }

    /**
     * Get template content by name
     */
    public function getTemplateContent(string $name): ?string
    {
        foreach ($this->mailTemplatePaths as $path) {
            $fullPath = rtrim($path, '/') . '/' . $name;

            if (File::exists($fullPath . '.blade.php')) {
                return File::get($fullPath . '.blade.php');
            }

            if (File::exists($fullPath . '.php')) {
                return File::get($fullPath . '.php');
            }
        }

        return null;
    }

    /**
     * Get a mail template by type from the database
     */
    public function getTemplateByType(string $type): ?MailTemplate
    {
        return MailTemplate::where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get a mail template by ID
     */
    public function getTemplateById(int $id): ?MailTemplate
    {
        return MailTemplate::find($id);
    }

    /**
     * Parse a template with variables
     */
    public function parseTemplate(MailTemplate $template, array $variables = []): string
    {
        $message = $template->message;

        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Create a mailable instance from a template
     */
    public function createMailable(MailTemplate $template, array $variables = [], array $attachments = []): TemplateBasedMail
    {
        $parsedMessage = $this->parseTemplate($template, $variables);
        return new TemplateBasedMail($template, $parsedMessage, $attachments);
    }

    /**
     * Send an email using a template
     */
    public function send(MailTemplate $template, string $to, array $variables = [], array $attachments = []): void
    {
        $mailable = $this->createMailable($template, $variables, $attachments);
        Mail::to($to)->send($mailable);
    }

    /**
     * Get available variables for a template type
     */
    public function getAvailableVariables(string $type): array
    {
        return config('modules.mail_template.variables.' . $type, []);
    }

    /**
     * Get all template types
     */
    public function getTemplateTypes(): array
    {
        return config('modules.mail_template.template_types', []);
    }

    /**
     * Get default from name
     */
    public function getDefaultFromName(): string
    {
        return config('modules.mail_template.defaults.from_name');
    }

    /**
     * Get default from email
     */
    public function getDefaultFromEmail(): string
    {
        return config('modules.mail_template.defaults.from_email');
    }

    /**
     * Extract subject from template content
     */
    private function getSubjectFromTemplate(string $templateContent): string
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

    /**
     * Convert filename to template type
     */
    private function getTemplateType(string $filename): string
    {
        $type = str_replace(['_notification', '_email'], '', $filename);
        $type = str_replace(['.blade', '.php'], '', $type);
        return $type;
    }

    /**
     * Format template name for display
     */
    private function formatTemplateName(string $name): string
    {
        $name = str_replace(['_notification', '_email', '.blade', '.php'], '', $name);
        $name = str_replace('_', ' ', $name);
        return ucwords($name);
    }
}
