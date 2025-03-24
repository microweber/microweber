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

if (!function_exists('save_mail_template')) {
    function save_mail_template($data)
    {

        $findMailTemplate = null;
        if (isset($data['id'])) {
            $findMailTemplate = MailTemplate::where('id', $data['id'])->first();
        }
        if ($findMailTemplate == null) {
            $findMailTemplate = new MailTemplate();
        }

        $findMailTemplate->type = $data['type'];
        $findMailTemplate->name = $data['type'];
        $findMailTemplate->subject = \Illuminate\Support\Str::headline($data['type']);

        if (isset($data['name'])) {
            $findMailTemplate->name = $data['name'];
        }

        if (isset($data['subject'])) {
            $findMailTemplate->subject = $data['subject'];
        }

        if (isset($data['from_name'])) {
            $findMailTemplate->from_name = $data['from_name'];
        }

        if (isset($data['from_email'])) {
            $findMailTemplate->from_email = $data['from_email'];
        }

        if (isset($data['copy_to'])) {
            $findMailTemplate->copy_to = $data['copy_to'];
        }

        if (isset($data['is_active'])) {
            $findMailTemplate->is_active = $data['is_active'];
        }

        $findMailTemplate->message = $data['message'];
        // $findMailTemplate->custom = $data['custom'];
        // $findMailTemplate->plain_text = $data['plain_text'];

        if (isset($data['multilanguage'])) {
            $findMailTemplate->multilanguage = $data['multilanguage'];
        }

        $findMailTemplate->save();

        if (isset($data['append_files']) && !empty($data['append_files'])) {

            $option = array();
            $option['option_value'] = $data['append_files'];
            $option['option_key'] = 'append_files';
            $option['option_group'] = 'mail_template_id_' . $findMailTemplate->id;

            mw()->option_manager->save($option);

        }

        return $findMailTemplate->id;
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
if (!function_exists('delete_mail_template_by_id')) {
    function delete_mail_template_by_id($params)
    {
        if (is_numeric($params)) {
            $params= ['id'=>$params];
        }

        if (isset($params['id'])) {
            $table = "mail_templates";
            $id = $params['id'];
            return db_delete($table, $id);
        }
    }
}
