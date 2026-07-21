<?php

namespace MicroweberPackages\CdnSync\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\CdnSync\Services\CdnSyncService;

/**
 * @property \Filament\Schemas\Schema $form
 */
class CdnSyncSettings extends Page
{

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cloud';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 50;

    protected static ?string $title = 'CDN Sync Settings';

    protected static ?string $slug = 'cdn-sync-settings';

    protected string $view = 'cdn-sync::filament.pages.cdn-sync-settings';

    // Form state
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'cdn_sync_enabled' => (bool) $this->getOptionOrConfig('enabled', false),
            'cdn_sync_key' => (string) $this->getOptionOrConfig('key', ''),
            'cdn_sync_secret' => (string) $this->getOptionOrConfig('secret', ''),
            'cdn_sync_region' => (string) ($this->getOptionOrConfig('region', 'us-east-1') ?: 'us-east-1'),
            'cdn_sync_bucket' => (string) $this->getOptionOrConfig('bucket', ''),
            'cdn_sync_endpoint' => (string) $this->getOptionOrConfig('endpoint', ''),
            'cdn_sync_url' => (string) $this->getOptionOrConfig('url', ''),
            'cdn_sync_use_path_style' => (bool) $this->getOptionOrConfig('use_path_style_endpoint', false),
            'cdn_sync_cdn_url' => (string) $this->getOptionOrConfig('cdn_url', ''),
            'cdn_sync_path_prefix' => (string) ($this->getOptionOrConfig('path_prefix', 'cdn-sync') ?: 'cdn-sync'),
        ]);
    }

    protected function getOptionOrConfig(string $key, mixed $default): mixed
    {
        if (function_exists('get_option')) {
            $val = get_option('cdn_sync_' . $key, 'cdn_sync');
            if (!empty($val)) {
                return $val;
            }
        }

        return config('cdn-sync.' . $key, $default);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('CDN Sync Configuration')
                    ->description('Configure your S3-compatible storage for CDN file synchronization.')
                    ->schema([
                        Toggle::make('cdn_sync_enabled')
                            ->label('Enable CDN Sync')
                            ->helperText('Master switch to enable or disable CDN syncing globally.')
                            ->live(),

                        Grid::make(2)->schema([
                            TextInput::make('cdn_sync_key')
                                ->label('Access Key')
                                ->password()
                                ->revealable()
                                ->placeholder('Your S3/Minio access key'),

                            TextInput::make('cdn_sync_secret')
                                ->label('Secret Key')
                                ->password()
                                ->revealable()
                                ->placeholder('Your S3/Minio secret key'),
                        ]),

                        Grid::make(2)->schema([
                            TextInput::make('cdn_sync_bucket')
                                ->label('Bucket')
                                ->placeholder('my-cdn-bucket'),

                            TextInput::make('cdn_sync_region')
                                ->label('Region')
                                ->placeholder('us-east-1'),
                        ]),

                        TextInput::make('cdn_sync_endpoint')
                            ->label('Custom Endpoint (for Minio/DigitalOcean/etc.)')
                            ->placeholder('http://minio:9000')
                            ->helperText('Leave empty for standard AWS S3.'),

                        Grid::make(2)->schema([
                            TextInput::make('cdn_sync_cdn_url')
                                ->label('CDN Base URL (optional)')
                                ->placeholder('https://cdn.example.com')
                                ->helperText('CloudFront or custom CDN URL. If empty, S3 URL is used.'),

                            TextInput::make('cdn_sync_path_prefix')
                                ->label('Path Prefix')
                                ->placeholder('cdn-sync'),
                        ]),

                        Toggle::make('cdn_sync_use_path_style')
                            ->label('Use Path-Style Endpoint')
                            ->helperText('Enable for Minio and some S3-compatible providers.'),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Non-Filament accessor for tests that don't boot the full Filament lifecycle.
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('CDN Sync Configuration')->schema([
                Toggle::make('cdn_sync_enabled')->label('Enable CDN Sync'),
                TextInput::make('cdn_sync_key')->label('Access Key'),
                TextInput::make('cdn_sync_secret')->label('Secret Key'),
                TextInput::make('cdn_sync_bucket')->label('Bucket'),
                TextInput::make('cdn_sync_region')->label('Region'),
                TextInput::make('cdn_sync_endpoint')->label('Custom Endpoint'),
                TextInput::make('cdn_sync_cdn_url')->label('CDN Base URL'),
                TextInput::make('cdn_sync_path_prefix')->label('Path Prefix'),
                Toggle::make('cdn_sync_use_path_style')->label('Use Path-Style Endpoint'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $mapping = [
            'enabled' => !empty($formData['cdn_sync_enabled']) ? '1' : '0',
            'key' => $formData['cdn_sync_key'] ?? '',
            'secret' => $formData['cdn_sync_secret'] ?? '',
            'region' => $formData['cdn_sync_region'] ?? 'us-east-1',
            'bucket' => $formData['cdn_sync_bucket'] ?? '',
            'endpoint' => $formData['cdn_sync_endpoint'] ?? '',
            'url' => $formData['cdn_sync_url'] ?? '',
            'use_path_style_endpoint' => !empty($formData['cdn_sync_use_path_style']) ? '1' : '0',
            'cdn_url' => $formData['cdn_sync_cdn_url'] ?? '',
            'path_prefix' => $formData['cdn_sync_path_prefix'] ?? 'cdn-sync',
        ];

        foreach ($mapping as $key => $value) {
            if (function_exists('save_option')) {
                save_option('cdn_sync_' . $key, $value, 'cdn_sync');
            } else {
                config(['cdn-sync.' . $key => $value]);
            }
        }

        // Update runtime config so isConfigured() works immediately
        config([
            'cdn-sync.enabled' => !empty($formData['cdn_sync_enabled']),
            'cdn-sync.key' => $formData['cdn_sync_key'] ?? '',
            'cdn-sync.secret' => $formData['cdn_sync_secret'] ?? '',
            'cdn-sync.region' => $formData['cdn_sync_region'] ?? 'us-east-1',
            'cdn-sync.bucket' => $formData['cdn_sync_bucket'] ?? '',
            'cdn-sync.endpoint' => $formData['cdn_sync_endpoint'] ?? '',
            'cdn-sync.url' => $formData['cdn_sync_url'] ?? '',
            'cdn-sync.use_path_style_endpoint' => !empty($formData['cdn_sync_use_path_style']),
            'cdn-sync.cdn_url' => $formData['cdn_sync_cdn_url'] ?? '',
            'cdn-sync.path_prefix' => $formData['cdn_sync_path_prefix'] ?? 'cdn-sync',
        ]);

        Notification::make()
            ->title('CDN Sync settings saved.')
            ->success()
            ->send();
    }

    public function testConnection(): void
    {
        $formData = $this->form->getState();

        // Temporarily apply current form values to config
        config([
            'cdn-sync.enabled' => true,
            'cdn-sync.key' => $formData['cdn_sync_key'] ?? '',
            'cdn-sync.secret' => $formData['cdn_sync_secret'] ?? '',
            'cdn-sync.region' => $formData['cdn_sync_region'] ?? 'us-east-1',
            'cdn-sync.bucket' => $formData['cdn_sync_bucket'] ?? '',
            'cdn-sync.endpoint' => $formData['cdn_sync_endpoint'] ?? '',
            'cdn-sync.use_path_style_endpoint' => !empty($formData['cdn_sync_use_path_style']),
        ]);

        /** @var CdnSyncService $service */
        $service = app('cdn_sync');
        $result = $service->testConnection();

        if ($result['success']) {
            Notification::make()
                ->title('Connection Successful')
                ->body($result['message'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Connection Failed')
                ->body($result['message'])
                ->danger()
                ->send();
        }
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin')) {
            return is_admin();
        }

        return true;
    }
}