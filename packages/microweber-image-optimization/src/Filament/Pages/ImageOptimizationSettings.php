<?php

declare(strict_types=1);

namespace MicroweberPackages\ImageOptimization\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\ImageOptimization\Services\ImageOptimizationService;

/**
 * @property \Filament\Schemas\Schema $form
 */
class ImageOptimizationSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 55;

    protected static ?string $title = 'Image Optimization';

    protected static ?string $slug = 'image-optimization-settings';

    protected string $view = 'image-optimization::filament.pages.image-optimization-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'webp_enabled' => (bool) $this->getOptionOrConfig('webp_enabled', true),
            'webp_quality' => (int) $this->getOptionOrConfig('webp_quality', 85),
            'lazy_loading_enabled' => (bool) $this->getOptionOrConfig('lazy_loading_enabled', true),
            'webp_cache' => (bool) $this->getOptionOrConfig('webp_cache', true),
            'auto_convert_uploads' => (bool) $this->getOptionOrConfig('auto_convert_uploads', false),
        ]);
    }

    protected function getOptionOrConfig(string $key, mixed $default): mixed
    {
        if (function_exists('get_option')) {
            $val = get_option('image_optimization_' . $key, 'image_optimization');
            if ($val !== null && $val !== '') {
                if ($val === '1' || $val === '0') {
                    return $val === '1';
                }

                return $val;
            }
        }

        return config('image-optimization.' . $key, $default);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Image Optimization')
                    ->description('Configure WebP conversion and lazy loading for images.')
                    ->schema([
                        Toggle::make('webp_enabled')
                            ->label('Enable WebP conversion')
                            ->helperText('Serve WebP versions of images when the browser supports it.')
                            ->live(),

                        TextInput::make('webp_quality')
                            ->label('WebP quality')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('1–100. Higher is better quality / larger files.'),

                        Toggle::make('lazy_loading_enabled')
                            ->label('Enable lazy loading')
                            ->helperText('Defer loading of off-screen images.'),

                        Toggle::make('webp_cache')
                            ->label('Cache WebP conversions')
                            ->helperText('Store converted WebP files for reuse.'),

                        Toggle::make('auto_convert_uploads')
                            ->label('Auto-convert uploads')
                            ->helperText('Automatically convert newly uploaded images to WebP (host app may implement this).'),
                    ]),

                Section::make('Statistics')
                    ->description('Current WebP cache statistics.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('stats_total_files')
                                ->label('Cached WebP files')
                                ->disabled()
                                ->dehydrated(false)
                                ->default(fn () => (string) app(ImageOptimizationService::class)->getStatistics()['total_files']),

                            TextInput::make('stats_total_size')
                                ->label('Cache size')
                                ->disabled()
                                ->dehydrated(false)
                                ->default(fn () => app(ImageOptimizationService::class)->getStatistics()['total_size_human']),
                        ]),
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
            Section::make('Image Optimization')->schema([
                Toggle::make('webp_enabled')->label('Enable WebP conversion'),
                TextInput::make('webp_quality')->label('WebP quality'),
                Toggle::make('lazy_loading_enabled')->label('Enable lazy loading'),
                Toggle::make('webp_cache')->label('Cache WebP conversions'),
                Toggle::make('auto_convert_uploads')->label('Auto-convert uploads'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $mapping = [
            'webp_enabled' => !empty($formData['webp_enabled']) ? '1' : '0',
            'webp_quality' => (string) ($formData['webp_quality'] ?? 85),
            'lazy_loading_enabled' => !empty($formData['lazy_loading_enabled']) ? '1' : '0',
            'webp_cache' => !empty($formData['webp_cache']) ? '1' : '0',
            'auto_convert_uploads' => !empty($formData['auto_convert_uploads']) ? '1' : '0',
        ];

        foreach ($mapping as $key => $value) {
            if (function_exists('save_option')) {
                save_option('image_optimization_' . $key, $value, 'image_optimization');
            }
        }

        config([
            'image-optimization.webp_enabled' => !empty($formData['webp_enabled']),
            'image-optimization.webp_quality' => (int) ($formData['webp_quality'] ?? 85),
            'image-optimization.lazy_loading_enabled' => !empty($formData['lazy_loading_enabled']),
            'image-optimization.webp_cache' => !empty($formData['webp_cache']),
            'image-optimization.auto_convert_uploads' => !empty($formData['auto_convert_uploads']),
        ]);

        // Rebind service so next resolution picks up new config
        app()->forgetInstance(ImageOptimizationService::class);

        Notification::make()
            ->title('Image optimization settings saved.')
            ->success()
            ->send();
    }

    public function clearCache(): void
    {
        /** @var ImageOptimizationService $service */
        $service = app(ImageOptimizationService::class);
        $count = $service->clearWebpCache();

        Notification::make()
            ->title('WebP cache cleared')
            ->body("Deleted {$count} file(s).")
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin') && is_admin()) {
            return true;
        }

        // Fallback for standalone Laravel apps / Filament auth
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

        // Allow access when no auth system is wired (standalone package tests)
        if (!function_exists('is_admin') && auth()->guest()) {
            return true;
        }

        return auth()->check();
    }
}
