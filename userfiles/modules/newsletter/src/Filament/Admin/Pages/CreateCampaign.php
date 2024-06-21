<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class CreateCampaign extends Page
{
    protected static ?string $slug = 'newsletter/create-campaign';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.create-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $template = '';
    public $recipientsFrom = '';

    public function form(Form $form): Form
    {

        $countSubscribers = NewsletterSubscriber::count();

        return $form->schema([

            Wizard::make([
//                Wizard\Step::make('Email')
//                    ->icon('heroicon-o-paint-brush')
//                    ->schema([
//                        SelectTemplate::make('template'),
//                    ]),
                Wizard\Step::make('Recipients')
                    ->icon('heroicon-o-users')
                    ->schema([

                        RadioDeck::make('recipientsFrom')
                            ->columns(3)
                            ->icons([
                                'all_subscribers' => 'heroicon-o-users',
                                'specific_lists' => 'heroicon-o-list-bullet',
                                'import_new_list' => 'heroicon-o-arrow-up-tray',
                            ])
                            ->color('primary')
                            ->live()
                            ->options([
                                'all_subscribers' => 'All subscribers' . ' (' . $countSubscribers . ')',
                                'specific_lists' => 'Specific lists',
                                'import_new_list' => 'Import new list',
                            ]),

                        Radio::make('list_id')
                            ->label('Select list')
                            ->hidden(function (Get $get) {
                                if ($get('recipientsFrom') == 'specific_lists') {
                                    return false;
                                }
                                return true;
                            })
                            ->options(NewsletterList::all()->pluck('name', 'id')),

                        Group::make([
                            TextInput::make('list_name')
                                ->label('List name'),
                            MwFileUpload::make('upload_list_file')
                                ->label('Upload list file'),
                        ])->hidden(function (Get $get) {
                            if ($get('recipientsFrom') == 'import_new_list') {
                                return false;
                            }
                            return true;
                        }),


                    ]),
                Wizard\Step::make('Delivery')
                    ->icon('heroicon-o-paper-airplane')
                    ->schema([
                        // ...
                    ]),
            ])

        ]);
    }

}
