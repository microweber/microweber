<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Pages\Page;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;

class AdminTemplatePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-template';

    protected static string $view = 'filament.admin.pages.settings-template';

    protected static ?string $title = 'Template';

    protected static string $description = 'Configure your template settings';
    public array $optionGroups = [
        'template'
    ];
    public string $moduleNameForOption = '';

    public function form(Form $form): Form
    {


        $defaultTemplate = app()->template->get_config();
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

                        Select::make('options.template.current_template')
                            ->label('Website Template')
                            ->helperText('Select your website template')
                            //->live()
                            ->options(function () {
                                $allTemplates = site_templates();
                                $options = [];

                                foreach ($allTemplates as $template) {
                                    $options[$template['dir_name']] = $template['name'];
                                }
                                return $options;
                            }),

//                        TextInput::make('options.template.current_template')
//                            ->label('Website Template')
//                            ->helperText('Select your website template')
//                            ->live()


                    ]),


            ]);
    }
}
