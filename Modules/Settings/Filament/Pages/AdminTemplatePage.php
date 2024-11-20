<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;

class AdminTemplatePage extends Page
{

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'mw-template';

    protected static string $view = 'filament.admin.pages.settings-template';

    protected static ?string $title = 'Template';

    protected static string $description = 'Configure your template settings';

    public $selectedTemplate = '';
    public $layout_file = 'index.php';
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

    public function form(Form $form): Form
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
//        $layouts = mw()->layouts_manager->get_all($layout_options);
        return $form
            ->schema([

                Section::make('Website template')
                    ->view('filament-forms::sections.section')
                    ->description('The website template is the design of your website. You can choose from a variety of templates.')
                    ->schema([
                        Actions::make([
                            Action::make('applyTemplate')
                                ->icon('mw-save')
                                ->visible(function (Get $get) {
                                    $defaultTemplate = template_name();
                                    $selectedTemplate = $get('selectedTemplate');

                                    if ($selectedTemplate == $defaultTemplate) {
                                        return false;
                                    }

                                    return $selectedTemplate;
                                })
                                ->requiresConfirmation()
                                ->action(function (Get $get) {
                                    $selectedTemplate = $get('selectedTemplate');


                                    $saveOption = [];
                                    $saveOption['option_value'] = $selectedTemplate;
                                    $saveOption['option_key'] = 'current_template';
                                    $saveOption['option_group'] = 'template';
                                    save_option($saveOption);

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


            ]);
    }
}
