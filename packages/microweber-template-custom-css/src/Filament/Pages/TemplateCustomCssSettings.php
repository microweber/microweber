<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Filament\Pages;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException;
use MicroweberPackages\TemplateCustomCss\Services\TemplateCustomCssManager;

/**
 * Filament admin page for editing live-edit and user custom CSS.
 *
 * @property \Filament\Schemas\Schema $form
 */
class TemplateCustomCssSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 58;

    protected static ?string $title = 'Template Custom CSS';

    protected static ?string $slug = 'template-custom-css-settings';

    protected string $view = 'template-custom-css::filament.pages.template-custom-css-settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $manager = app(TemplateCustomCssManager::class);
        $template = $this->resolveTemplate();

        $this->form->fill([
            'template' => $template,
            'live_edit_css' => $manager->liveEdit()->getContent($template),
            'custom_css' => $manager->customCss()->getCustomCssContent(),
            'validate_on_save' => (bool) ($manager->getConfig()['validate_on_save'] ?? true),
            'live_edit_url' => $manager->liveEdit()->getLiveEditCssUrl($template),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Live Edit CSS')
                    ->description('Per-template styles applied by the live editor (live_edit.css).')
                    ->schema([
                        Select::make('template')
                            ->label('Template')
                            ->options(fn () => $this->templateOptions())
                            ->live()
                            ->afterStateUpdated(function (?string $state): void {
                                if ($state === null || $state === '') {
                                    return;
                                }
                                $manager = app(TemplateCustomCssManager::class);
                                $this->data['live_edit_css'] = $manager->liveEdit()->getContent($state);
                                $this->data['live_edit_url'] = $manager->liveEdit()->getLiveEditCssUrl($state);
                            }),

                        Textarea::make('live_edit_css')
                            ->label('Live edit CSS')
                            ->rows(16)
                            ->extraInputAttributes(['class' => 'font-mono text-sm']),
                    ]),

                Section::make('User Custom CSS')
                    ->description('Site-wide custom CSS stored in options (custom_css).')
                    ->schema([
                        Textarea::make('custom_css')
                            ->label('Custom CSS')
                            ->rows(12)
                            ->extraInputAttributes(['class' => 'font-mono text-sm']),

                        Toggle::make('validate_on_save')
                            ->label('Validate CSS before save')
                            ->helperText('Reject broken CSS using the Sabberworm CSS parser.'),
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
            Section::make('Live Edit CSS')->schema([
                Textarea::make('live_edit_css')->label('Live edit CSS')->rows(16),
            ]),
            Section::make('User Custom CSS')->schema([
                Textarea::make('custom_css')->label('Custom CSS')->rows(12),
                Toggle::make('validate_on_save')->label('Validate CSS before save'),
            ]),
        ];
    }

    public function save(): void
    {
        $formData = $this->form->getState();
        $manager = app(TemplateCustomCssManager::class);

        $validate = !empty($formData['validate_on_save']);
        $manager->setConfigValue('validate_on_save', $validate);

        $template = is_string($formData['template'] ?? null) ? $formData['template'] : $this->resolveTemplate();
        $liveEditCss = is_string($formData['live_edit_css'] ?? null) ? $formData['live_edit_css'] : '';
        $customCss = is_string($formData['custom_css'] ?? null) ? $formData['custom_css'] : '';

        try {
            $manager->liveEdit()->saveLiveEditCssContent($liveEditCss, $template);
            $manager->customCss()->saveCustomCss($customCss);
        } catch (InvalidCssException $e) {
            Notification::make()
                ->title('Invalid CSS')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('CSS saved')
            ->success()
            ->send();

        $this->data['live_edit_url'] = $manager->liveEdit()->getLiveEditCssUrl($template);
    }

    protected function resolveTemplate(): string
    {
        if (function_exists('get_option')) {
            $opt = get_option('current_template', 'template');
            if (is_string($opt) && $opt !== '' && $opt !== 'default') {
                return $opt;
            }
        }
        if (function_exists('template_name')) {
            $name = template_name();
            if (is_string($name) && $name !== '') {
                return $name;
            }
        }

        $cfg = config('template-custom-css.default_template');

        return is_string($cfg) && $cfg !== '' ? $cfg : 'default';
    }

    /**
     * @return array<string, string>
     */
    protected function templateOptions(): array
    {
        $current = $this->resolveTemplate();
        $options = [$current => $current];

        try {
            if (function_exists('app') && app()->bound('template_manager')) {
                $tm = app('template_manager');
                if (is_object($tm) && method_exists($tm, 'get_templates')) {
                    $templates = $tm->get_templates();
                    if (is_array($templates)) {
                        foreach ($templates as $tpl) {
                            if (is_array($tpl) && isset($tpl['dir_name']) && is_string($tpl['dir_name'])) {
                                $options[$tpl['dir_name']] = $tpl['dir_name'];
                            } elseif (is_string($tpl)) {
                                $options[$tpl] = $tpl;
                            }
                        }
                    }
                }
            }
        } catch (\Throwable) {
            // ignore
        }

        return $options;
    }
}
