<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;
use MicroweberPackages\Template\Export\TemplateExporter;
use MicroweberPackages\Template\Export\TemplateImporter;
use MicroweberPackages\Template\Http\Controllers\Api\TemplateApiController;

class AdminTemplatePage extends Page
{

    protected static bool $shouldRegisterNavigation = false;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Template';


    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';


    protected static string $description = 'Configure your template settings';

    public $selectedTemplate = '';
    public $importType = '';
    public $layout_file = 'index.blade.php';
    public $data=[];

    public function getDescription(): string
    {
        return static::$description;
    }


    public function mount(): void
    {
        $defaultTemplate = app()->template_manager->get_config();
        if ($defaultTemplate and isset($defaultTemplate['dir_name'])) {
            $this->selectedTemplate = $defaultTemplate['dir_name'];

        }
    }

    public function form(Schema $schema): Schema
    {


        //    $defaultTemplate = app()->template_manager->get_config();
        //  dd($defaultTemplate,$allTemplates);

//        $layout_options = array();
//
//
//        $layout_options['site_template'] = $data['active_site_template'];
//        $layout_options['no_cache'] = true;
//        $layout_options['no_folder_sort'] = true;
//
//        $layouts = app()->layouts_manager->get_all($layout_options);
        return $schema
            ->schema([

                Section::make('Website template')
                    ->icon('heroicon-m-paint-brush')
                    ->view('mw-filament::sections.section')
                    ->description('The website template is the design of your website. You can choose from a variety of templates.')
                    ->schema([
                        Actions::make([
Action::make('applyTemplate')
                        ->icon('heroicon-o-check-circle')
                                ->visible(function (Get $get) {
                                    $defaultTemplate = template_name();
                                    $selectedTemplate = $get('selectedTemplate');

                                    if ($selectedTemplate == $defaultTemplate) {
                                        return false;
                                    }

                                    return $selectedTemplate;
                                })
                                ->form([
                                    Select::make('importType')
                                        ->label('Install template content')
                                        ->live()
                                        ->options([
                                            'default' => 'Apply template and use current content',
                                            'full' => 'Install content and media files',
                                            'only_media' => 'Install only media files',
                                            'delete' => 'Replace all content and media files',
                                        ])
                                        ->default('default')
                                        ->required(),
                                ])
                                ->requiresConfirmation()
                                ->modalDescription('Are you sure want to change the template of your website?')

                                ->modalIcon('mw-template')
                                ->modalHeading('Apply template')
                                ->action(function (array $data, Get $get) {
                                    $selectedTemplate =  $data['selectedTemplate'] ?? $get('selectedTemplate');
                                    $importType = $data['importType'] ?? $get('importType');
                                    $request = request();

                                    $request->merge([
                                        'template' => $selectedTemplate,
                                        'import_type' => $importType,
                                    ]);

                                    $controller = new TemplateApiController();

                                    $controller = $controller->change($request);

                                    if(isset($controller['error'])) {
                                        Notification::make('template_error')
                                            ->title('Error: ' . $controller['error'])
                                            ->danger()
                                            ->send();
                                        return;
                                    }

                                    if(isset($controller['done'])) {

                                        Notification::make('template_done')
                                            ->title('Template is changed')
                                            ->success()
                                            ->send();
                                    }

//
//                                    $saveOption = [];
//                                    $saveOption['option_value'] = $selectedTemplate;
//                                    $saveOption['option_key'] = 'current_template';
//                                    $saveOption['option_group'] = 'template';
//                                    save_option($saveOption);

                                    $notificationId = 'settings_updated' . crc32(date('i') . $selectedTemplate);

                                    Notification::make($notificationId)
                                        ->title('Template is changed')
                                        ->success()
                                        ->send();

                                }),

                        ])->fullWidth(),
                        // Select::make('options.template.current_template')
//                      s

                        MwSelectTemplateForPage::make(
                            'selectedTemplate',
                            'layout_file')
                            ->columnSpanFull(),


//                        TextInput::make('options.template.current_template')
//                            ->label('Website Template')
//                            ->helperText('Select your website template')
//                            ->live()




                    ]),

                Section::make('Reusable templates')
                    ->icon('heroicon-m-arrow-path-rounded-square')
                    ->view('mw-filament::sections.section')
                    ->description('Export the current site as a reusable template, or upload a template others exported. Exported/uploaded templates contain only content and assets — never PHP.')
                    ->schema([
                        Actions::make([
                            $this->exportAsTemplateAction(),
                            $this->uploadTemplateAction(),
                        ])->fullWidth(),
                    ]),


            ]);
    }

    /**
     * Export the current Live-Edit site as a reusable, uploadable child template
     * (design + content + fonts frozen to JSON + assets; no PHP) and stream the ZIP.
     */
    protected function exportAsTemplateAction(): Action
    {
        return Action::make('exportAsTemplate')
            ->label('Export as template')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->modalSubmitActionLabel('Export & download')
            ->modalIcon('heroicon-o-arrow-down-tray')
            ->modalHeading('Export this site as a template')
            ->modalDescription('Packages the current site (design, pages, menus, fonts) into an uploadable template ZIP. The ZIP contains only content and assets — no PHP — so it is safe to share and re-upload.')
            ->form([
                TextInput::make('name')
                    ->label('Template name')
                    ->required()
                    ->default(template_name() . ' Copy')
                    ->helperText('A new template folder is created with this name.'),
                Select::make('parent')
                    ->label('Parent template')
                    ->options($this->templateOptions())
                    ->placeholder('Same as current template (recommended)')
                    ->helperText('The exported template inherits its layout/views from this parent.'),
                Toggle::make('fullSite')
                    ->label('Include the whole site')
                    ->helperText("On: export every page. Off: only the current template's pages.")
                    ->default(false),
                TextInput::make('author')
                    ->label('Author')
                    ->placeholder('Microweber'),
            ])
            ->action(function (array $data) {
                try {
                    $info = (new TemplateExporter())
                        ->setName((string) $data['name'])
                        ->setParent($data['parent'] ?? null)
                        ->setFullSite((bool) ($data['fullSite'] ?? false))
                        ->setAuthor($data['author'] ?? null)
                        ->exportToZip();
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title('Export failed')
                        ->body($e->getMessage())
                        ->send();
                    return null;
                }

                $zip = (string) $info['zip'];
                $downloadName = $info['name'] . '.zip';

                return response()->streamDownload(function () use ($zip) {
                    if (is_file($zip)) {
                        readfile($zip);
                        @unlink($zip);
                    }
                }, $downloadName, ['Content-Type' => 'application/zip']);
            });
    }

    /**
     * Upload a template ZIP produced by "Export as template". The importer refuses
     * any archive containing PHP/executable files or unsafe paths (see
     * {@see TemplateImporter}), so an upload can only add data + assets.
     */
    protected function uploadTemplateAction(): Action
    {
        return Action::make('uploadTemplate')
            ->label('Upload template')
            ->icon('heroicon-o-arrow-up-tray')
            ->color('gray')
            ->modalHeading('Upload a template')
            ->modalDescription('Upload a template ZIP (data + assets only). PHP files and unsafe paths are rejected.')
            ->modalSubmitActionLabel('Upload')
            ->form([
                // Store on the LOCAL disk (not the media disk, which may be S3) so the
                // importer gets a real local path to read.
                FileUpload::make('templateZip')
                    ->label('Template ZIP')
                    ->disk('local')
                    ->directory('template-imports')
                    ->visibility('private')
                    ->preserveFilenames(false)
                    ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed', 'application/octet-stream', 'multipart/x-zip'])
                    ->helperText('A template ZIP exported from this or another Microweber site.')
                    ->required(),
            ])
            ->action(function (array $data) {
                $stored = $data['templateZip'] ?? null;
                if (is_array($stored)) {
                    $stored = reset($stored);
                }
                if (!$stored || !Storage::disk('local')->exists($stored)) {
                    Notification::make()->danger()->title('Uploaded file not found')->send();
                    return;
                }
                $path = Storage::disk('local')->path($stored);

                try {
                    $info = (new TemplateImporter())->importZip($path);
                } catch (\Throwable $e) {
                    Notification::make()
                        ->danger()
                        ->title('Upload rejected')
                        ->body($e->getMessage())
                        ->send();
                    return;
                } finally {
                    // The upload was only a transport; drop it either way.
                    Storage::disk('local')->delete($stored);
                }

                // The new template dir is auto-discovered on the next scan.
                if (function_exists('clearcache')) {
                    try { clearcache(); } catch (\Throwable) {}
                }

                Notification::make()
                    ->success()
                    ->title('Template uploaded')
                    ->body("\"{$info['name']}\" (child of {$info['parent_template']}) installed — {$info['files']} file(s). Select it above to apply it.")
                    ->send();
            });
    }

    /** Template names available as an export parent (core + extra templates). */
    protected function templateOptions(): array
    {
        $dirs = array_merge(
            glob(base_path('Templates/*'), GLOB_ONLYDIR) ?: [],
            glob(base_path('ExtraTemplates/*'), GLOB_ONLYDIR) ?: []
        );
        $names = [];
        foreach ($dirs as $d) {
            $name = basename($d);
            $names[$name] = $name;
        }
        ksort($names);
        return $names;
    }
}
