<?php

use Modules\MailTemplate\Services\MailTemplateService;
use Modules\MailTemplate\Models\MailTemplate;

if (!function_exists('mail_template_service')) {
    function mail_template_service(): MailTemplateService
    {
        return app(MailTemplateService::class);
    }
}

if (!function_exists('get_mail_template_types')) {
    function get_mail_template_types(): array
    {
        return config('modules.mailtemplate.template_types', []);
    }
}

if (!function_exists('get_mail_template_fields')) {
    function get_mail_template_fields($type = ''): array
    {
        if ($type == 'new_order' || $type == 'order_change_status' || $type == 'receive_payment') {
            return [
                ['value' => '{{id}}', 'label' => 'Order Id'],
                ['value' => '{{date}}', 'label' => 'Date'],
                ['value' => '{{cart_items}}', 'label' => 'Cart items'],
                ['value' => '{{amount}}', 'label' => 'Amount'],
                ['value' => '{{order_status}}', 'label' => 'Order Status'],
                ['value' => '{{currency}}', 'label' => 'Currency'],
                ['value' => '{{first_name}}', 'label' => 'First Name'],
                ['value' => '{{last_name}}', 'label' => 'Last Name'],
                ['value' => '{{email}}', 'label' => 'Email'],
                ['value' => '{{country}}', 'label' => 'Country'],
                ['value' => '{{city}}', 'label' => 'City'],
                ['value' => '{{state}}', 'label' => 'State'],
                ['value' => '{{zip}}', 'label' => 'Zip'],
                ['value' => '{{address}}', 'label' => 'Address'],
                ['value' => '{{phone}}', 'label' => 'Phone'],
                ['value' => '{{transaction_id}}', 'label' => 'Transaction Id'],
                ['value' => '{{order_id}}', 'label' => 'Order Id']
            ];
        }

        if ($type == 'new_comment_reply') {
            return [
                ['value' => '{{comment_author}}', 'label' => 'Comment Author'],
                ['value' => '{{comment_reply_author}}', 'label' => 'Comment Reply Author'],
                ['value' => '{{post_url}}', 'label' => 'Post Url']
            ];
        }

        if ($type == 'new_user_registration') {
            return [
                ['value' => '{{id}}', 'label' => 'User Id'],
                ['value' => '{{username}}', 'label' => 'Username'],
                ['value' => '{{email}}', 'label' => 'Email'],
                ['value' => '{{first_name}}', 'label' => 'First Name'],
                ['value' => '{{last_name}}', 'label' => 'Last Name'],
                ['value' => '{{created_at}}', 'label' => 'Date of registration'],
                ['value' => '{{verify_email_link}}', 'label' => 'Verify email link']
            ];
        }

        if ($type == 'forgot_password') {
            return [
                ['value' => '{{id}}', 'label' => 'User Id'],
                ['value' => '{{username}}', 'label' => 'Username'],
                ['value' => '{{email}}', 'label' => 'Email'],
                ['value' => '{{first_name}}', 'label' => 'First Name'],
                ['value' => '{{last_name}}', 'label' => 'Last Name'],
                ['value' => '{{created_at}}', 'label' => 'Date of registration'],
                ['value' => '{{reset_password_link}}', 'label' => 'Reset password link'],
                ['value' => '{{ip}}', 'label' => 'IP address']
            ];
        }

        return [];
    }
}

if (!function_exists('get_mail_templates')) {
    function get_mail_templates($params = []): array
    {
        $showTemplates = [];
        $defaultTemplates = get_default_mail_templates();

        $getTemplates = MailTemplate::all();
        if ($getTemplates->count() > 0) {
            $templates = $getTemplates->toArray();
            foreach ($templates as $template) {
                $showTemplates[] = $template;
            }
        }

        if (empty($showTemplates)) {
            $showTemplates = $defaultTemplates;
        } else {
            foreach ($defaultTemplates as $defaultTemplate) {
                $appendThisDefaultTemplate = true;
                foreach ($showTemplates as $template) {
                    if ($template['type'] == $defaultTemplate['type']) {
                        $appendThisDefaultTemplate = false;
                    }
                }
                if ($appendThisDefaultTemplate) {
                    $showTemplates[] = $defaultTemplate;
                }
            }
        }

        return $showTemplates;
    }
}

if (!function_exists('get_default_mail_templates')) {
    function get_default_mail_templates(): array
    {
        $templates = [];
        $types = get_mail_template_types();

        foreach ($types as $type => $name) {
            $templates[] = [
                'id' => $type,
                'type' => $type,
                'name' => $name,
                'subject' => $name,
                'from_name' => get_default_mail_from_name(),
                'from_email' => get_default_mail_from_email(),
                'copy_to' => '',
                'message' => '',
                'is_default' => true,
                'is_active' => 1
            ];
        }

        return $templates;
    }
}

if (!function_exists('get_mail_template_by_id')) {
    function get_mail_template_by_id($id, $type = false)
    {
        $templates = get_mail_templates();
        $templates = array_merge($templates, get_default_mail_templates());

        foreach ($templates as $template) {
            if ($template['id'] == $id) {
                return $template;
            }
        }

        return get_default_mail_template_by_type($type);
    }
}

if (!function_exists('get_default_mail_template_by_type')) {
    function get_default_mail_template_by_type($type = '')
    {
        foreach (get_default_mail_templates() as $template) {
            if ($template['type'] == $type) {
                return $template;
            }
        }
        return null;
    }
}

if (!function_exists('get_mail_templates_by_type')) {
    function get_mail_templates_by_type($type = ''): array
    {
        $templates = [];

        foreach (get_mail_templates() as $template) {
            if ($template['type'] == $type) {
                if (isset($template['is_default'])) {
                    $template['name'] = $template['name'] . ' (default)';
                }
                $templates[] = $template;
            }
        }

        return $templates;
    }
}

if (!function_exists('save_mail_template')) {
    function save_mail_template($data)
    {
        $template = isset($data['id']) ? MailTemplate::find($data['id']) : new MailTemplate();

        if (!$template) {
            $template = new MailTemplate();
        }

        $template->type = $data['type'];
        $template->name = $data['name'] ?? '';
        $template->subject = $data['subject'] ?? '';
        $template->from_name = $data['from_name'] ?? '';
        $template->from_email = $data['from_email'] ?? '';
        $template->copy_to = $data['copy_to'] ?? '';
        $template->is_active = $data['is_active'] ?? 1;
        $template->message = $data['message'] ?? '';

        $template->save();

        if (isset($data['append_files']) && !empty($data['append_files'])) {
            $option = [
                'option_value' => $data['append_files'],
                'option_key' => 'append_files',
                'option_group' => 'mail_template_id_' . $template->id
            ];

            mw()->option_manager->save($option);
        }

        return $template->id;
    }
}

if (!function_exists('delete_mail_template')) {
    function delete_mail_template($params)
    {
        if (!is_admin()) {
            return;
        }

        if (isset($params['id'])) {
            return MailTemplate::find($params['id'])?->delete();
        }
    }
}

