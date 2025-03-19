<?php

use Modules\MailTemplate\Models\MailTemplate;
use Modules\MailTemplate\Services\MailTemplateService;

if (!function_exists('mail_template_service')) {
    /**
     * Get the mail template service instance
     */
    function mail_template_service(): MailTemplateService
    {
        return app(MailTemplateService::class);
    }
}

if (!function_exists('get_mail_template_by_type')) {
    /**
     * Get a mail template by type
     */
    function get_mail_template_by_type(string $type): ?MailTemplate
    {
        return mail_template_service()->getTemplateByType($type);
    }
}

if (!function_exists('send_mail_template')) {
    /**
     * Send an email using a template
     */
    function send_mail_template(string $type, string $to, array $variables = [], array $attachments = []): void
    {
        $service = mail_template_service();
        $template = $service->getTemplateByType($type);

        if (!$template) {
            throw new \RuntimeException("Mail template of type '{$type}' not found");
        }

        $service->send($template, $to, $variables, $attachments);
    }
}

if (!function_exists('get_mail_template_variables')) {
    /**
     * Get available variables for a template type
     */
    function get_mail_template_variables(string $type): array
    {
        return mail_template_service()->getAvailableVariables($type);
    }
}

if (!function_exists('get_mail_template_types')) {
    /**
     * Get all template types
     */
    function get_mail_template_types(): array
    {
        return mail_template_service()->getTemplateTypes();
    }
}

if (!function_exists('get_mail_template_files')) {
    /**
     * Get all mail template files
     */
    function get_mail_template_files(): array
    {
        return mail_template_service()->getMailTemplateFiles();
    }
}

if (!function_exists('get_mail_template_content')) {
    /**
     * Get template content by name
     */
    function get_mail_template_content(string $name): ?string
    {
        return mail_template_service()->getTemplateContent($name);
    }
}

if (!function_exists('get_mail_template_by_id')) {
    /**
     * Get a mail template by ID
     *
     * @param $id Template ID
     * @return \Modules\MailTemplate\Models\MailTemplate|null
     */
    function get_mail_template_by_id($id): ?MailTemplate
    {
        return mail_template_service()->getTemplateById($id);
    }
}
