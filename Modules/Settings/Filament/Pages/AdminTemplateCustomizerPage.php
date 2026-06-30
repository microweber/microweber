<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;

use function get_options;
use function save_option;

class AdminTemplateCustomizerPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $title = 'Template Customization';
    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';
    protected static ?string $slug = 'template-customization';
    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'modules.settings::filament.admin.pages.template-customizer';

    public $selectedTemplate = '';
    public $previewUrl = '';
    public $customizationData = [];
    public $activeTab = 'appearance';

    // Form field properties (required for Livewire entangle)
    public $primary_color = '#007bff';
    public $secondary_color = '#6c757d';
    public $background_color = '#ffffff';
    public $text_color = '#333333';
    public $heading_font = 'Arial';
    public $body_font = 'Arial';
    public $base_font_size = 16;
    public $font_weight = '400';
    public $container_width = 'container';
    public $section_padding = '60px';
    public $show_header = true;
    public $show_footer = true;
    public $logo = null;
    public $favicon = null;
    public $layout_file = '';

    public function mount(): void
    {
        $defaultTemplate = app()->template_manager->get_config();
        if ($defaultTemplate && isset($defaultTemplate['dir_name'])) {
            $this->selectedTemplate = $defaultTemplate['dir_name'];
        }

        // AI-1068 / task-2026-06-04-le1068 — seed the Layout dropdown with
        // the active template's first available layout instead of leaving
        // $layout_file = '' (which renders as the "Select an option" null
        // placeholder and blocks the live preview until the user picks a
        // layout). Mirrors the component-level ->default() in
        // MwSelectTemplateForPage so the page-bound property carries a real
        // value on first mount. The ticket accepts "the first available
        // option" as the default.
        if ($this->layout_file === '' && $this->selectedTemplate) {
            $layouts = app()->layouts_manager->get_all([
                'site_template' => $this->selectedTemplate,
                'no_cache' => true,
                'no_folder_sort' => true,
            ]);
            if (isset($layouts[0]['layout_file'])) {
                $this->layout_file = $layouts[0]['layout_file'];
            }
        }

        // Load existing customization options
        $this->loadCustomizationData();

        // Generate initial preview URL
        $this->updatePreviewUrl();
    }

    public function getTitle(): string
    {
        return 'Template Customization';
    }

    public function getDescription(): string
    {
        return 'Customize your website template appearance, colors, fonts, and settings with live preview.';
    }

    protected function loadCustomizationData(): void
    {
        $templateName = $this->selectedTemplate;
        if (!$templateName) {
            return;
        }

        $optionGroup = 'mw-template-' . $templateName;
        $settings = get_options(['option_group' => $optionGroup]);

        if ($settings && is_array($settings)) {
            foreach ($settings as $setting) {
                if (isset($setting['option_key']) && isset($setting['option_value'])) {
                    $this->customizationData[$setting['option_key']] = $setting['option_value'];
                }
            }
        }

        // Load style-specific options
        $styleOptionGroup = 'mw-template-' . $templateName . '-settings';
        $styleSettings = get_options(['option_group' => $styleOptionGroup]);
        if ($styleSettings && is_array($styleSettings)) {
            foreach ($styleSettings as $setting) {
                if (isset($setting['option_key']) && isset($setting['option_value'])) {
                    $this->customizationData[$setting['option_key']] = $setting['option_value'];
                }
            }
        }
    }

    public function updatePreviewUrl(): void
    {
        if (!$this->selectedTemplate) {
            $this->previewUrl = '';
            return;
        }

        // Generate preview URL with current template
        $previewParams = [
            'preview_template' => $this->selectedTemplate,
            'preview_layout' => 'clean.blade.php',
            'no_cache' => 1,
        ];

        $this->previewUrl = site_url() . '?' . http_build_query($previewParams);
    }

    public function updatedSelectedTemplate(): void
    {
        $this->loadCustomizationData();
        $this->updatePreviewUrl();
        $this->dispatch('templateChanged', template: $this->selectedTemplate, previewUrl: $this->previewUrl);
    }

    public function saveCustomization(string $key, $value): void
    {
        if (!$this->selectedTemplate) {
            return;
        }

        $optionGroup = 'mw-template-' . $this->selectedTemplate;

        $saveOption = [
            'option_key' => $key,
            'option_value' => $value,
            'option_group' => $optionGroup,
        ];

        save_option($saveOption);

        // Clear template cache
        Cache::forget('template_settings_' . $this->selectedTemplate);

        $this->customizationData[$key] = $value;

        // Refresh preview
        $this->updatePreviewUrl();
        $this->dispatch('refreshPreview');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Template Selection')
                    ->description('Select the template you want to customize')
                    ->icon('heroicon-o-rectangle-stack')
                    ->collapsible()
                    ->schema([
                        MwSelectTemplateForPage::make('selectedTemplate', 'layout_file')
                            ->columnSpanFull(),
                    ]),

                Section::make('Appearance Settings')
                    ->description('Customize the visual appearance of your template')
                    ->icon('heroicon-o-paint-brush')
                    ->visible(fn () => $this->selectedTemplate !== '')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ColorPicker::make('primary_color')
                                    ->label('Primary Color')
                                    ->default('#007bff')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('primary_color', $state);
                                    }),

                                ColorPicker::make('secondary_color')
                                    ->label('Secondary Color')
                                    ->default('#6c757d')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('secondary_color', $state);
                                    }),

                                ColorPicker::make('background_color')
                                    ->label('Background Color')
                                    ->default('#ffffff')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('background_color', $state);
                                    }),

                                ColorPicker::make('text_color')
                                    ->label('Text Color')
                                    ->default('#333333')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('text_color', $state);
                                    }),
                            ]),
                    ]),

                Section::make('Typography')
                    ->description('Customize fonts and text styling')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn () => $this->selectedTemplate !== '')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('heading_font')
                                    ->label('Heading Font')
                                    ->options([
                                        'Arial' => 'Arial',
                                        'Helvetica' => 'Helvetica',
                                        'Georgia' => 'Georgia',
                                        'Times New Roman' => 'Times New Roman',
                                        'Verdana' => 'Verdana',
                                        'Open Sans' => 'Open Sans',
                                        'Roboto' => 'Roboto',
                                        'Lato' => 'Lato',
                                        'Montserrat' => 'Montserrat',
                                    ])
                                    ->default('Arial')
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('heading_font', $state);
                                    }),

                                Select::make('body_font')
                                    ->label('Body Font')
                                    ->options([
                                        'Arial' => 'Arial',
                                        'Helvetica' => 'Helvetica',
                                        'Georgia' => 'Georgia',
                                        'Times New Roman' => 'Times New Roman',
                                        'Verdana' => 'Verdana',
                                        'Open Sans' => 'Open Sans',
                                        'Roboto' => 'Roboto',
                                        'Lato' => 'Lato',
                                        'Montserrat' => 'Montserrat',
                                    ])
                                    ->default('Arial')
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('body_font', $state);
                                    }),

                                TextInput::make('base_font_size')
                                    ->label('Base Font Size (px)')
                                    ->numeric()
                                    ->default(16)
                                    ->minValue(10)
                                    ->maxValue(24)
                                    ->suffix('px')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('base_font_size', $state);
                                    }),

                                Select::make('font_weight')
                                    ->label('Default Font Weight')
                                    ->options([
                                        '300' => 'Light (300)',
                                        '400' => 'Regular (400)',
                                        '500' => 'Medium (500)',
                                        '600' => 'Semi-Bold (600)',
                                        '700' => 'Bold (700)',
                                    ])
                                    ->default('400')
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('font_weight', $state);
                                    }),
                            ]),
                    ]),

                Section::make('Layout Settings')
                    ->description('Customize layout and spacing options')
                    ->icon('heroicon-o-view-columns')
                    ->visible(fn () => $this->selectedTemplate !== '')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('container_width')
                                    ->label('Container Width')
                                    ->options([
                                        'container-fluid' => 'Full Width',
                                        'container' => 'Standard (1140px)',
                                        'container-sm' => 'Small (960px)',
                                        'container-lg' => 'Large (1320px)',
                                    ])
                                    ->default('container')
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('container_width', $state);
                                    }),

                                TextInput::make('section_padding')
                                    ->label('Section Padding')
                                    ->default('60px')
                                    ->placeholder('e.g., 60px, 4rem')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('section_padding', $state);
                                    }),

                                Toggle::make('show_header')
                                    ->label('Show Header')
                                    ->default(true)
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('show_header', $state ? '1' : '0');
                                    }),

                                Toggle::make('show_footer')
                                    ->label('Show Footer')
                                    ->default(true)
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        $this->saveCustomization('show_footer', $state ? '1' : '0');
                                    }),
                            ]),
                    ]),

                Section::make('Branding')
                    ->description('Upload your logo and favicon')
                    ->icon('heroicon-o-photo')
                    ->visible(fn () => $this->selectedTemplate !== '')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('logo')
                                    ->label('Logo')
                                    ->image()
                                    ->directory('template-logos')
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        if ($state) {
                                            $this->saveCustomization('logo', $state);
                                        }
                                    }),

                                FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->directory('template-favicons')
                                    ->maxSize(512)
                                    ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml'])
                                    ->live()
                                    ->afterStateUpdated(function ($state) {
                                        if ($state) {
                                            $this->saveCustomization('favicon', $state);
                                        }
                                    }),
                            ]),
                    ]),

                // task-2026-05-27-6e9cf4 / AI-1174: size(Large) intent for 44px
                // task-2026-05-30-tplcust: Size::Large rendered at 42px on mobile
                // (probed 390x844). Tailwind JIT does not pick up new utility
                // classes added via extraAttributes here, so inline style is
                // the guaranteed cascade-winner for the WCAG 2.5.5 floor.
                // The !important flag is mandatory because Filament v5 ships
                // its own !important min-height on .fi-btn.fi-size-lg /
                // .fi-size-sm — bare inline style loses the cascade.
                Actions::make([
                    Action::make('resetToDefaults')
                        ->label('Reset to Defaults')
                        ->icon('heroicon-o-arrow-path')
                        ->color('danger')
                        ->size(\Filament\Support\Enums\Size::Large)
                        ->extraAttributes(['style' => 'min-height:44px !important;'])
                        ->requiresConfirmation()
                        ->modalHeading('Reset Template Settings')
                        ->modalDescription('Are you sure you want to reset all template settings to their default values? This action cannot be undone.')
                        ->action(function () {
                            $this->resetTemplateSettings();
                        }),

                    Action::make('exportSettings')
                        ->label('Export Settings')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('gray')
                        ->size(\Filament\Support\Enums\Size::Large)
                        ->extraAttributes(['style' => 'min-height:44px !important;'])
                        ->action(function () {
                            $this->exportSettings();
                        }),

                    Action::make('importSettings')
                        ->label('Import Settings')
                        ->icon('heroicon-o-arrow-up-tray')
                        ->color('gray')
                        ->size(\Filament\Support\Enums\Size::Large)
                        ->extraAttributes(['style' => 'min-height:44px !important;'])
                        ->form([
                            FileUpload::make('settings_file')
                                ->label('Settings JSON File')
                                ->acceptedFileTypes(['application/json'])
                                ->required(),
                        ])
                        ->action(function (array $data) {
                            $this->importSettings($data['settings_file'] ?? null);
                        }),
                ])->fullWidth(),
            ]);
    }

    public function resetTemplateSettings(): void
    {
        if (!$this->selectedTemplate) {
            return;
        }

        $optionGroup = 'mw-template-' . $this->selectedTemplate;
        $styleOptionGroup = 'mw-template-' . $this->selectedTemplate . '-settings';

        // Delete all template options.
        // AI-108 / TICKET-BG (cycle-133): routed through the Option Eloquent
        // model so OptionWasDeleted fires for each row -> cache invalidation
        // listeners (TemplateClearCachedCssListener) run automatically.
        \MicroweberPackages\Option\Models\Option::query()
            ->where('option_group', $optionGroup)
            ->orWhere('option_group', $styleOptionGroup)
            ->get()
            ->each(fn ($option) => $option->delete());

        // Clear cache
        Cache::forget('template_settings_' . $this->selectedTemplate);
        Cache::forget('options');

        // Reload data
        $this->customizationData = [];
        $this->loadCustomizationData();
        $this->updatePreviewUrl();

        Notification::make()
            ->title('Settings Reset')
            ->body('All template settings have been reset to defaults.')
            ->success()
            ->send();

        $this->dispatch('refreshPreview');
    }

    protected function exportSettings(): void
    {
        if (!$this->selectedTemplate) {
            return;
        }

        $exportData = [
            'template' => $this->selectedTemplate,
            'exported_at' => now()->toIso8601String(),
            'settings' => $this->customizationData,
        ];

        $filename = 'template-settings-' . $this->selectedTemplate . '-' . now()->format('Y-m-d-His') . '.json';

        // Store in storage
        $path = storage_path('app/exports/' . $filename);
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, json_encode($exportData, JSON_PRETTY_PRINT));

        Notification::make()
            ->title('Settings Exported')
            ->body('Template settings have been exported to ' . $filename)
            ->success()
            ->send();
    }

    protected function importSettings(?string $filePath): void
    {
        if (!$filePath || !$this->selectedTemplate) {
            return;
        }

        $fullPath = storage_path('app/' . $filePath);
        if (!file_exists($fullPath)) {
            Notification::make()
                ->title('Import Failed')
                ->body('Settings file not found.')
                ->danger()
                ->send();
            return;
        }

        $content = file_get_contents($fullPath);
        $data = json_decode($content, true);

        if (!$data || !isset($data['settings']) || !is_array($data['settings'])) {
            Notification::make()
                ->title('Import Failed')
                ->body('Invalid settings file format.')
                ->danger()
                ->send();
            return;
        }

        // Import settings
        $optionGroup = 'mw-template-' . $this->selectedTemplate;
        foreach ($data['settings'] as $key => $value) {
            $saveOption = [
                'option_key' => $key,
                'option_value' => $value,
                'option_group' => $optionGroup,
            ];
            save_option($saveOption);
        }

        // Clear cache and reload
        Cache::forget('template_settings_' . $this->selectedTemplate);
        $this->loadCustomizationData();
        $this->updatePreviewUrl();

        Notification::make()
            ->title('Settings Imported')
            ->body('Template settings have been imported successfully.')
            ->success()
            ->send();

        $this->dispatch('refreshPreview');
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('viewSite')
                ->label('View Site')
                ->icon('heroicon-o-eye')
                ->url(site_url(), true),
        ];
    }
}
