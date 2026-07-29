<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

/**
 * @property \Filament\Schemas\Schema $form
 */
class TemplateFontsSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 57;

    protected static ?string $title = 'Font Settings';

    protected static ?string $slug = 'template-fonts-settings';

    protected string $view = 'template-fonts::filament.pages.template-fonts-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $manager = app(TemplateFontsManager::class);
        $config = $manager->getConfig();

        $useProxy = (bool) ($config['use_google_fonts_proxy'] ?? false);
        if (function_exists('get_option')) {
            $opt = get_option('use_google_fonts_proxy', 'template');
            if ($opt !== null && $opt !== false && $opt !== '') {
                $useProxy = (int) $opt === 1;
            }
        }

        $domain = $config['google_fonts_domain'] ?? 'fonts.googleapis.com';
        $this->form->fill([
            'use_google_fonts_proxy' => $useProxy,
            'download_google_fonts_locally' => (bool) ($config['download_google_fonts_locally'] ?? true),
            'google_fonts_domain' => is_string($domain) ? $domain : 'fonts.googleapis.com',
            'enabled_count' => $this->countEnabled(),
        ]);
    }

    protected function countEnabled(): int
    {
        try {
            return (int) TemplateFont::query()->enabled()->count();
        } catch (\Throwable) {
            return count(app(TemplateFontsManager::class)->getEnabledFonts());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Google Fonts')
                    ->description('Configure the Google Fonts provider and local download behaviour.')
                    ->schema([
                        Toggle::make('use_google_fonts_proxy')
                            ->label('Use Microweber Google Fonts proxy')
                            ->helperText('Routes font CSS through google-fonts.microweberapi.com when enabled.'),

                        Toggle::make('download_google_fonts_locally')
                            ->label('Download Google Fonts locally')
                            ->helperText('Cache font files under the fonts storage path when enabling a font.'),

                        TextInput::make('google_fonts_domain')
                            ->label('Google Fonts domain')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('enabled_count')
                            ->label('Enabled fonts')
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * @return array<int, mixed>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make('Google Fonts')->schema([
                Toggle::make('use_google_fonts_proxy')->label('Use Microweber Google Fonts proxy'),
                Toggle::make('download_google_fonts_locally')->label('Download Google Fonts locally'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();
        $manager = app(TemplateFontsManager::class);

        $useProxy = !empty($formData['use_google_fonts_proxy']);
        $downloadLocal = !empty($formData['download_google_fonts_locally']);

        $manager->setConfigValue('use_google_fonts_proxy', $useProxy);
        $manager->setConfigValue('download_google_fonts_locally', $downloadLocal);

        if (function_exists('save_option')) {
            save_option('use_google_fonts_proxy', $useProxy ? '1' : '0', 'template');
        }

        config([
            'template-fonts.use_google_fonts_proxy' => $useProxy,
            'template-fonts.download_google_fonts_locally' => $downloadLocal,
        ]);

        app()->forgetInstance(TemplateFontsManager::class);

        Notification::make()
            ->title('Font settings saved.')
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin') && is_admin()) {
            return true;
        }

        try {
            $user = auth()->user();
            if ($user !== null && isset($user->is_admin) && (int) $user->is_admin === 1) {
                return true;
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
