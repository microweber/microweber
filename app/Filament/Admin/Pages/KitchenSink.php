<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\Actions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;

class KitchenSink extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.kitchen-sink';

    protected static ?string $navigationGroup = 'Other';
    protected static ?int $navigationSort = 99;

    public int $star = 0;
    public int $resetStars = 0;

    public $image = '';

    public $mw_color_picker = '#133be8';
    public $mw_link_picker = '';
    public $mw_icon_picker = 'mw-micon-Address-Book';



    public function getIcons()
    {
        $iconsPath = '/src/MicroweberPackages/Admin/resources/mw-svg';
        $icons = scandir(base_path($iconsPath));
        $allIcons = [];
        foreach ($icons as $icon) {
            if (strpos($icon, '.svg') !== false) {
                $icon = str_replace('.svg', '', $icon);
                $allIcons[] = $icon;
            }
        }

        return $allIcons;
    }

    public function form(Form $form): Form
    {


        return $form
            ->schema([

                \LaraZeus\Accordion\Forms\Accordions::make('OptionsOriginal')
                    ->activeAccordion(0)
                    ->accordions([
                        \LaraZeus\Accordion\Forms\Accordion::make('main-data')
                            ->columns()
                            ->label('User Details')
                            ->icon('heroicon-o-user')
                            ->badge('New Badge')
                            ->badgeColor('info')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                        \LaraZeus\Accordion\Forms\Accordion::make('settings')
                            ->columns()
                            ->label('Settings')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                        \LaraZeus\Accordion\Forms\Accordion::make('next')
                            ->columns()
                            ->label('Whats next?')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                    ]),

                \LaraZeus\Accordion\Forms\Accordions::make('Options')
                    ->slideOverRight()
                    ->activeAccordion(0)
                    ->accordions([
                        \LaraZeus\Accordion\Forms\Accordion::make('main-data')
                            ->columns()
                            ->label('User Details')
                            ->icon('heroicon-o-user')
                            ->badge('New Badge')
                            ->badgeColor('info')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                        \LaraZeus\Accordion\Forms\Accordion::make('settings')
                            ->columns()
                            ->label('Settings')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                        \LaraZeus\Accordion\Forms\Accordion::make('next')
                            ->columns()
                            ->label('Whats next?')
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('email')->required(),
                            ]),
                    ]),

                MwColorPicker::make('mw_color_picker')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        dump($state);
                    })->columnSpanFull(),

                MwIconPicker::make('mw_icon_picker')
                    ->live()
                    ->addIconSet('iconsMindLine')
                    ->addIconSet('iconsMindSolid')
                    ->addIconSet('fontAwesome')
                    ->addIconSet('materialDesignIcons')
                    ->afterStateUpdated(function ($state) {
                        dump($state);
                    })->columnSpanFull(),

                MwLinkPicker::make('mw_link_picker')
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        dump($state);
                    })->columnSpanFull(),

                ColorPicker::make('mw_color_picker')
                    ->live()
                    ->default('#133be8')
                    ->afterStateUpdated(function ($state) {
                        dump($state);
                    })->columnSpanFull(),

                Actions::make([

                    Actions\Action::make('star')
                        ->icon('heroicon-m-star')
                        ->requiresConfirmation()
                        ->action(function () {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }),
                    Actions\Action::make('resetStars')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function () {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }),


                ]),
                Radio::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                        'published' => 'Published'
                    ])
                    ->descriptions([
                        'draft' => 'Is not visible.',
                        'scheduled' => 'Will be visible.',
                        'published' => 'Is visible.'
                    ]),
                CheckboxList::make('technologies')
                    ->options([
                        'tailwind' => 'Tailwind CSS',
                        'alpine' => 'Alpine.js',
                        'laravel' => 'Laravel',
                        'livewire' => 'Laravel Livewire',
                    ])
                    ->disableOptionWhen(fn (string $value): bool => $value === 'livewire'),

                Tabs::make('Test')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([

                                MwFileUpload::make('image')
                                    ->label('Image'),

                                TextInput::make('backgroundColor')
                                    ->type('color')
                                    ->label('Background Color'),
                                TextInput::make('text')
                                    ->inputMode('decimal')
                                    ->label('Text'),

                                ColorPicker::make('hsl_color')
                                    ->hsl(),

                                ColorPicker::make('rgb_color')
                                    ->rgb(),

                                ColorPicker::make('rgba_color')
                                    ->rgba(),


                            ]),

                    ]),

            ]);
    }
}
