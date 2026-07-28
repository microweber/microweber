<?php

declare(strict_types=1);

namespace MicroweberPackages\Minifier\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\Minifier\Services\MinifierService;

/**
 * @property \Filament\Schemas\Schema $form
 */
class MinifierSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scissors';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 56;

    protected static ?string $title = 'Asset Minifier';

    protected static ?string $slug = 'minifier-settings';

    protected string $view = 'minifier::filament.pages.minifier-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'enabled' => (bool) $this->getOptionOrConfig('enabled', true),
            'minify_js' => (bool) $this->getOptionOrConfig('minify_js', true),
            'minify_css' => (bool) $this->getOptionOrConfig('minify_css', true),
            'js_flagged_comments' => (bool) $this->getOptionOrConfig('js_flagged_comments', false),
        ]);
    }

    protected function getOptionOrConfig(string $key, mixed $default): mixed
    {
        if (function_exists('get_option')) {
            $val = get_option('minifier_' . $key, 'minifier');
            if ($val !== null && $val !== '') {
                if ($val === '1' || $val === '0') {
                    return $val === '1';
                }

                return $val;
            }
        }

        $configKey = match ($key) {
            'js_flagged_comments' => 'minifier.js.flaggedComments',
            default => 'minifier.' . $key,
        };

        return config($configKey, $default);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Asset Minifier')
                    ->description('Configure JavaScript and CSS minification for frontend assets.')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Enable minifier')
                            ->helperText('Master switch for JS/CSS minification.')
                            ->live(),

                        Toggle::make('minify_js')
                            ->label('Minify JavaScript')
                            ->helperText('Strip comments and unnecessary whitespace from JS.'),

                        Toggle::make('minify_css')
                            ->label('Minify CSS')
                            ->helperText('Strip comments and collapse whitespace in CSS.'),

                        Toggle::make('js_flagged_comments')
                            ->label('Preserve /*! license comments */')
                            ->helperText('Keep flagged JS license comments when minifying.'),
                    ]),

                Section::make('Statistics')
                    ->description('Current minifier engine status.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('stats_js_engine')
                                ->label('JS engine')
                                ->disabled()
                                ->dehydrated(false)
                                ->default(function (): string {
                                    $stats = app(MinifierService::class)->getStatistics();
                                    $engine = $stats['engine'] ?? [];
                                    if (is_array($engine) && isset($engine['js']) && is_string($engine['js'])) {
                                        return $engine['js'];
                                    }

                                    return 'JsMinifier';
                                }),

                            TextInput::make('stats_css_engine')
                                ->label('CSS engine')
                                ->disabled()
                                ->dehydrated(false)
                                ->default(function (): string {
                                    $stats = app(MinifierService::class)->getStatistics();
                                    $engine = $stats['engine'] ?? [];
                                    if (is_array($engine) && isset($engine['css']) && is_string($engine['css'])) {
                                        return $engine['css'];
                                    }

                                    return 'CssMinifier';
                                }),
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
            Section::make('Asset Minifier')->schema([
                Toggle::make('enabled')->label('Enable minifier'),
                Toggle::make('minify_js')->label('Minify JavaScript'),
                Toggle::make('minify_css')->label('Minify CSS'),
                Toggle::make('js_flagged_comments')->label('Preserve flagged comments'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();

        $mapping = [
            'enabled' => !empty($formData['enabled']) ? '1' : '0',
            'minify_js' => !empty($formData['minify_js']) ? '1' : '0',
            'minify_css' => !empty($formData['minify_css']) ? '1' : '0',
            'js_flagged_comments' => !empty($formData['js_flagged_comments']) ? '1' : '0',
        ];

        foreach ($mapping as $key => $value) {
            if (function_exists('save_option')) {
                save_option('minifier_' . $key, $value, 'minifier');
            }
        }

        config([
            'minifier.enabled' => !empty($formData['enabled']),
            'minifier.minify_js' => !empty($formData['minify_js']),
            'minifier.minify_css' => !empty($formData['minify_css']),
            'minifier.js.flaggedComments' => !empty($formData['js_flagged_comments']),
        ]);

        app()->forgetInstance(MinifierService::class);

        Notification::make()
            ->title('Minifier settings saved.')
            ->success()
            ->send();
    }

    public function runSelfTest(): void
    {
        /** @var MinifierService $service */
        $service = app(MinifierService::class);
        $result = $service->selfTest();

        $jsOk = !empty($result['js']['ok']);
        $cssOk = !empty($result['css']['ok']);

        Notification::make()
            ->title($jsOk && $cssOk ? 'Self-test passed' : 'Self-test completed with issues')
            ->body(sprintf(
                'JS: %d→%d bytes; CSS: %d→%d bytes',
                $result['js']['original_len'],
                $result['js']['minified_len'],
                $result['css']['original_len'],
                $result['css']['minified_len'],
            ))
            ->{$jsOk && $cssOk ? 'success' : 'warning'}()
            ->send();
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
