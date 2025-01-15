<?php

namespace Modules\MailTemplate\Services;

use Modules\MailTemplate\Models\MailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class MailTemplateService
{
    public function getTemplateByType(string $type): ?MailTemplate
    {
        return MailTemplate::where('type', $type)
            ->where('is_active', true)
            ->first();
    }

    public function parseTemplate(MailTemplate $template, array $variables = []): string
    {
        $message = $template->message;

        foreach ($variables as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }

    public function send(MailTemplate $template, string $to, array $variables = [], array $attachments = []): void
    {
        $parsedMessage = $this->parseTemplate($template, $variables);

        Mail::send([], [], function (Message $message) use ($template, $to, $parsedMessage, $attachments) {
            $message->to($to)
                ->subject($template->subject)
                ->from($template->from_email, $template->from_name);

            if ($template->copy_to) {
                $message->cc($template->copy_to);
            }

            foreach ($attachments as $attachment) {
                if (isset($attachment['path'])) {
                    $message->attach($attachment['path'], $attachment['options'] ?? []);
                }
            }

            $message->setBody($parsedMessage, 'text/html');
        });
    }

    public function getAvailableVariables(string $type): array
    {
        return config('modules.mail_template.variables.' . $type, []);
    }

    public function getTemplateTypes(): array
    {
        return config('modules.mail_template.template_types', []);
    }

    public function getDefaultFromName(): string
    {
        return config('modules.mail_template.defaults.from_name');
    }

    public function getDefaultFromEmail(): string
    {
        return config('modules.mail_template.defaults.from_email');
    }
}
