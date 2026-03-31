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
    public function getTemplateById($id): ?MailTemplate
    {
        if(!$id){
            return null;
        }
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

    /**
     * Get the full mail template form schema for use in other modules
     */
    public function getTemplateFormSchema(): array
    {
        return [
            \Filament\Schemas\Components\Section::make('Template Details')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Template Name')
                        ->helperText('Enter a descriptive name for this email template')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\Select::make('type')
                        ->options($this->getTemplateTypes())
                        ->required()
                        ->live()
                        ->helperText('Select the type of email template')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\TextInput::make('from_name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('From Name')
                        ->default($this->getDefaultFromName())
                        ->helperText('The sender name that will appear in the email')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\TextInput::make('from_email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->placeholder('From Email')
                        ->default($this->getDefaultFromEmail())
                        ->helperText('The sender email address')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\TextInput::make('copy_to')
                        ->email()
                        ->maxLength(255)
                        ->placeholder('Copy To Email (Optional)')
                        ->helperText('Optional: Send a copy of each email to this address')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\TextInput::make('subject')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Email Subject')
                        ->helperText('You can use variables like {order_id}, {first_name}, etc.')
                        ->columnSpanFull(),
                ]),

            \Filament\Schemas\Components\Section::make('Template Content')
                ->schema([
                    \Filament\Forms\Components\RichEditor::make('message')
                        ->required()
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'link',
                            'orderedList',
                            'bulletList',
                            'undo',
                            'redo',
                        ])
                        ->placeholder('Email Content')
                        ->helperText('Use HTML formatting and variables like {order_id}, {first_name}, {cart_items}, etc.')
                        ->columnSpanFull(),

                    \Filament\Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->helperText('Enable or disable this template')
                        ->columnSpanFull(),
               ]),

            \Filament\Schemas\Components\Section::make('Available Variables')
                ->schema([
                    \Filament\Forms\Components\Placeholder::make('variables')
                        ->content(function ($get) {
                            $type = $get('type');
                            if (!$type) {
                                return new \Illuminate\Support\HtmlString('<p class="text-gray-500">Select a template type to see available variables.</p>');
                            }

                            $variables = $this->getAvailableVariables($type);
                            
                            // Default order variables if none configured
                            if (empty($variables) && in_array($type, ['new_order', 'order_paid', 'order_shipped', 'order_delivered'])) {
                                $variables = [
                                    '{order_id}' => 'Order ID',
                                    '{first_name}' => 'Customer first name',
                                    '{last_name}' => 'Customer last name',
                                    '{email}' => 'Customer email',
                                    '{phone}' => 'Customer phone',
                                    '{address}' => 'Customer address',
                                    '{city}' => 'Customer city',
                                    '{state}' => 'Customer state',
                                    '{country}' => 'Customer country',
                                    '{zip}' => 'Customer zip code',
                                    '{order_amount}' => 'Total order amount',
                                    '{cart_items}' => 'Order items table',
                                ];
                            }

                            $content = '<div class="rounded-lg border border-blue-200 bg-blue-50 p-4"><h4 class="font-medium mb-2">Available Variables</h4><div class="space-y-1 text-sm">';
                            foreach ($variables as $var => $desc) {
                                $content .= "<div><code class='bg-blue-100 px-2 py-1 rounded text-blue-800'>{$var}</code> - {$desc}</div>";
                            }
                            $content .= '</div></div>';

                            return new \Illuminate\Support\HtmlString($content);
                        })
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),
        ];
    }
}
