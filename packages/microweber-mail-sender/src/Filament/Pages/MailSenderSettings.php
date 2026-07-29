<?php

declare(strict_types=1);

namespace MicroweberPackages\MailSender\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\MailSender\Services\MailConfigApplier;
use MicroweberPackages\MailSender\Services\MailSenderService;

/**
 * @property \Filament\Schemas\Schema $form
 */
class MailSenderSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 55;

    protected static ?string $title = 'Mail Sender';

    protected static ?string $slug = 'mail-sender-settings';

    protected string $view = 'mail-sender::filament.pages.mail-sender-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'enabled' => (bool) $this->getOptionOrConfig('enabled', true),
            'transport' => $this->asString($this->getOptionOrConfig('transport', 'smtp'), 'smtp'),
            'from_address' => $this->asString($this->getOptionOrConfig('from_address', '')),
            'from_name' => $this->asString($this->getOptionOrConfig('from_name', '')),
            'smtp_host' => $this->asString($this->getOptionOrConfig('smtp_host', '')),
            'smtp_port' => $this->asString($this->getOptionOrConfig('smtp_port', '587'), '587'),
            'smtp_username' => $this->asString($this->getOptionOrConfig('smtp_username', '')),
            'smtp_password' => $this->asString($this->getOptionOrConfig('smtp_password', '')),
            'smtp_encryption' => $this->asString($this->getOptionOrConfig('smtp_encryption', 'tls'), 'tls'),
            'hostname' => $this->asString($this->getOptionOrConfig('hostname', '')),
        ]);
    }

    protected function getOptionOrConfig(string $key, mixed $default): mixed
    {
        if (function_exists('get_option')) {
            $val = get_option('mail_sender_' . $key, 'mail_sender');
            if ($val !== null && $val !== false && $val !== '') {
                if ($val === '1' || $val === '0') {
                    return $val === '1';
                }

                return $val;
            }
        }

        $configKey = match ($key) {
            'from_address' => 'mail-sender.from.address',
            'from_name' => 'mail-sender.from.name',
            'smtp_host' => 'mail-sender.smtp.host',
            'smtp_port' => 'mail-sender.smtp.port',
            'smtp_username' => 'mail-sender.smtp.username',
            'smtp_password' => 'mail-sender.smtp.password',
            'smtp_encryption' => 'mail-sender.smtp.encryption',
            default => 'mail-sender.' . $key,
        };

        return config($configKey, $default);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Mail Sender')
                    ->description('Configure outbound email transport. Settings are applied on boot (and re-applied on save).')
                    ->schema([
                        Select::make('enabled')
                            ->label('Enabled')
                            ->options([
                                1 => 'Yes',
                                0 => 'No',
                            ])
                            ->default(1),

                        Select::make('transport')
                            ->label('Transport')
                            ->options([
                                'smtp' => 'SMTP',
                                'php' => 'PHP mail()',
                                'gmail' => 'Gmail',
                                'cpanel' => 'cPanel',
                                'plesk' => 'Plesk',
                                'config' => 'Use Laravel config/mail.php',
                                'log' => 'Log (dev)',
                                'array' => 'Array (testing)',
                            ])
                            ->required(),

                        TextInput::make('from_address')
                            ->label('From address')
                            ->email()
                            ->placeholder('noreply@example.com'),

                        TextInput::make('from_name')
                            ->label('From name')
                            ->placeholder('Example Site'),

                        TextInput::make('smtp_host')
                            ->label('SMTP host')
                            ->placeholder('smtp.example.com'),

                        TextInput::make('smtp_port')
                            ->label('SMTP port')
                            ->numeric()
                            ->placeholder('587'),

                        TextInput::make('smtp_username')
                            ->label('SMTP username')
                            ->autocomplete(false),

                        TextInput::make('smtp_password')
                            ->label('SMTP password')
                            ->password()
                            ->autocomplete(false),

                        Select::make('smtp_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                                '' => 'None',
                            ]),

                        TextInput::make('hostname')
                            ->label('Hostname (subject prefix)')
                            ->helperText('When enabled on a message, subjects become "[hostname] …".'),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Non-Filament accessor for unit tests that skip the full Filament lifecycle.
     *
     * @return array<int, mixed>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('Mail Sender')->schema([
                Select::make('transport')->label('Transport'),
                TextInput::make('from_address')->label('From address'),
                TextInput::make('from_name')->label('From name'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $mapping = [
            'enabled' => !empty($formData['enabled']) ? '1' : '0',
            'transport' => $this->asString($formData['transport'] ?? 'smtp', 'smtp'),
            'from_address' => $this->asString($formData['from_address'] ?? ''),
            'from_name' => $this->asString($formData['from_name'] ?? ''),
            'smtp_host' => $this->asString($formData['smtp_host'] ?? ''),
            'smtp_port' => $this->asString($formData['smtp_port'] ?? '587', '587'),
            'smtp_username' => $this->asString($formData['smtp_username'] ?? ''),
            'smtp_password' => $this->asString($formData['smtp_password'] ?? ''),
            'smtp_encryption' => $this->asString($formData['smtp_encryption'] ?? 'tls', 'tls'),
            'hostname' => $this->asString($formData['hostname'] ?? ''),
        ];

        foreach ($mapping as $key => $value) {
            if (function_exists('save_option')) {
                save_option('mail_sender_' . $key, $value, 'mail_sender');
            }
        }

        // Also mirror into the legacy CMS email option group so existing
        // get_email_from() helpers keep working.
        if (function_exists('save_option')) {
            save_option('email_from', $mapping['from_address'], 'email');
            save_option('email_from_name', $mapping['from_name'], 'email');
            save_option('email_transport', $mapping['transport'], 'email');
            save_option('smtp_host', $mapping['smtp_host'], 'email');
            save_option('smtp_port', $mapping['smtp_port'], 'email');
            save_option('smtp_username', $mapping['smtp_username'], 'email');
            save_option('smtp_password', $mapping['smtp_password'], 'email');
            save_option('smtp_auth', $mapping['smtp_encryption'], 'email');
        }

        config([
            'mail-sender.enabled' => $mapping['enabled'] === '1',
            'mail-sender.transport' => $mapping['transport'],
            'mail-sender.from.address' => $mapping['from_address'] !== '' ? $mapping['from_address'] : null,
            'mail-sender.from.name' => $mapping['from_name'] !== '' ? $mapping['from_name'] : null,
            'mail-sender.smtp.host' => $mapping['smtp_host'],
            'mail-sender.smtp.port' => (int) $mapping['smtp_port'],
            'mail-sender.smtp.username' => $mapping['smtp_username'] !== '' ? $mapping['smtp_username'] : null,
            'mail-sender.smtp.password' => $mapping['smtp_password'] !== '' ? $mapping['smtp_password'] : null,
            'mail-sender.smtp.encryption' => $mapping['smtp_encryption'] !== '' ? $mapping['smtp_encryption'] : null,
            'mail-sender.hostname' => $mapping['hostname'] !== '' ? $mapping['hostname'] : null,
        ]);

        app(MailConfigApplier::class)->apply();

        Notification::make()
            ->title('Mail sender settings saved.')
            ->success()
            ->send();
    }

    public function runSelfTest(): void
    {
        /** @var MailSenderService $service */
        $service = app(MailSenderService::class);
        $result = $service->selfTest();

        Notification::make()
            ->title(!empty($result['ok']) ? 'Self-test passed' : 'Self-test completed with issues')
            ->body(!empty($result['errors']) ? implode('; ', $result['errors']) : 'Configuration looks valid.')
            ->{!empty($result['ok']) ? 'success' : 'warning'}()
            ->send();
    }


    private function asString(mixed $value, string $default = ''): string
    {
        if (is_string($value)) {
            return $value;
        }
        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return $default;
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin') && is_admin()) {
            return true;
        }

        try {
            $user = auth()->user();
            if ($user !== null) {
                /** @var object $user */
                if (isset($user->is_admin) && (int) $user->is_admin === 1) {
                    return true;
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        if (!function_exists('is_admin') && auth()->guest()) {
            return true;
        }

        return auth()->check();
    }
}
