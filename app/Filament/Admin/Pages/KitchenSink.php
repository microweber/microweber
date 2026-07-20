<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action as FilamentAction;
use Filament\Schemas\Components\Actions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Enums\IconSize;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Forms\Components\Concerns\MwInputSliderBehaviour;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;

class KitchenSink extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.admin.pages.kitchen-sink';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';
    protected static ?int $navigationSort = 99;

    // task-2026-05-23-cc3b22 / AI-1053 — description for settings hub card.
    public static string $description = 'UI component showcase and developer tools for testing Filament form components.';

    public int $star = 0;
    public int $resetStars = 0;

    public $image = '';

    public $mw_color_picker = '#133be8';
    public $mw_link_picker = '';
    public $mw_icon_picker = 'mw-micon-Address-Book';

    // MwInputSlider fields
    public $min = 0;
    public $max = 100;
    // RadioDeck
    public $name = '';
    // Radio
    public $status = '';
    // CheckboxList
    public $technologies = [];
    // Image tab
    public $backgroundColor = '#000000';
    public $text = '';
    public $hsl_color = '';
    public $rgb_color = '';
    public $rgba_color = '';
    // Inputs tab
    public $email = '';
    public $password = '';
    public $bio = '';
    public $rich_bio = '';
    public $markdown_bio = '';
    public $card_input = '';
    public $grid_input_1 = '';
    public $grid_input_2 = '';
    public $placeholder = '';
    public $repeater = [];
    public $time_picker = '';
    public $toggle_button = '';
    public $card_input2 = '';
    public $section_input = '';
    // Selects & Dropdowns tab
    public $role = '';
    public $tags = [];
    public $actions = '';
    // Toggles & Checkboxes tab
    public $agree_terms = false;
    public $preferences = [];
    public $is_active = false;
    // Media tab
    public $profile_picture = null;
    public $custom_file = null;
    // Advanced tab
    public $favorite_color = '';
    public $custom_color_picker = '';
    public $icon_picker = '';
    public $link_picker = '';
    public $tags_input = [];
    public $key_value = [];
    // Dates & Times tab
    public $birthdate = '';
    public $event_date = '';
    // Wizard tab
    public $step1_input = '';
    public $step2_input = '';



    public function getIcons(): array
    {
        return \MicroweberPackages\SvgIcons\SvgIconsServiceProvider::availableIcons();
    }

    public function form(Schema $schema): Schema
    {


        return $schema
            ->schema([

                MwInputSliderGroup::make()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        dump($state);
                    })
                    ->sliders([
                        MwInputSlider::make('min')
                    ])
                    ->label('Limit'),

                 MwInputSliderGroup::make()
                    ->sliders([
                        MwInputSlider::make('min'),
                        MwInputSlider::make('max')
                    ])
                    ->label('Limit'),

                MwInputSliderGroup::make()
                    ->sliders([
                    MwInputSlider::make('min'),
                    MwInputSlider::make('max')
                ])
                ->connect([
                        false,
                        true,
                        false
                ])->label('Limit'),

                MwInputSliderGroup::make()
                    ->sliders([
                        MwInputSlider::make('min'),
                         MwInputSlider::make('max')
                    ])
                    ->connect([
                            false,
                            true,
                            false
                        ])
                    ->max(100)
                    ->min(0)
                    ->label('Limit'),


                MwInputSliderGroup::make()
                    ->sliders([
                        MwInputSlider::make('min'),
                        MwInputSlider::make('max')->default(50),
                    ])
                    ->connect([
                        true,
                        false,
                        true
                    ]) // array length must be sliders length + 1
                    ->range([
                        "min" => 30,
                        "max" => 100
                    ])
                    ->step(10)
                    ->behaviour([
                        MwInputSliderBehaviour::DRAG,
                        MwInputSliderBehaviour::TAP
                    ])
                    ->enableTooltips()
                    ->label("Limit"),


                RadioDeck::make('name')
                    ->options([
                        'ios' => 'iOS',
                        'android' => 'Android',
                        'web' => 'Web',
                        'windows' => 'Windows',
                        'mac' => 'Mac',
                        'linux' => 'Linux',
                    ])
                    ->descriptions([
                        'ios' => 'iOS Mobile App',
                        'android' => 'Android Mobile App',
                        'web' => 'Web App',
                        'windows' => 'Windows Desktop App',
                        'mac' => 'Mac Desktop App',
                        'linux' => 'Linux Desktop App',
                    ])
                    ->icons([
                        'ios' => 'heroicon-m-device-phone-mobile',
                        'android' => 'heroicon-m-device-phone-mobile',
                        'web' => 'heroicon-m-globe-alt',
                        'windows' => 'heroicon-m-computer-desktop',
                        'mac' => 'heroicon-m-computer-desktop',
                        'linux' => 'heroicon-m-computer-desktop',
                    ])
                    ->required()
                    ->iconSize(IconSize::Large) // Small | Medium | Large | (string - sm | md | lg)
//                    ->iconSizes([ // Customize the values for each icon size
//                                  'sm' => 'h-12 w-12',
//                                  'md' => 'h-14 w-14',
//                                  'lg' => 'h-16 w-16',
//                    ])
//                    ->iconPosition(IconPosition::Before) // Before | After | (string - before | after)
//                    ->alignment(Alignment::Start) // Start | Center | End | (string - start | center | end)
//                    ->gap('gap-5') // Gap between Icon and Description (Any TailwindCSS gap-* utility)
//                    ->padding('px-4 px-6') // Padding around the deck (Any TailwindCSS padding utility)
//                    ->direction('column') // Column | Row (Allows to place the Icon on top)
                    ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                                             'class' => 'rounded-xl'
                    ])
                    ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                                               'class' => 'text-3xl leading-none w-full flex flex-col items-center justify-center p-4'
                    ])
                    ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                                                    'class' => 'text-sm font-light text-center'
                    ])
//                    ->color('primary') // supports all color custom or not
//                    ->multiple() // Select multiple card (it will also returns an array of selected card values)
                    ->columns(3),


                // LaraZeus Accordion disabled - incompatible with Filament v5
                // \LaraZeus\Accordion\Forms\Accordions::make('OptionsOriginal') ...,
                // \LaraZeus\Accordion\Forms\Accordions::make('Options') ...,

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

                    FilamentAction::make('star')
                        ->icon('heroicon-m-star')
                        ->requiresConfirmation()
                        ->tooltip('New Badge Tooltip')
                        ->action(function () {
                            Notification::make()
                                ->title('Saved successfully')
                                ->success()
                                ->send();
                        }),
                    FilamentAction::make('resetStars')
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
                    ->schema([
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
                Tabs::make('Components')
                    ->schema([
                        // Text Inputs Tab
                        Tabs\Tab::make('Inputs')
                            ->schema([
                                TextInput::make('name')->label('Name')->required(),
                                TextInput::make('email')->label('Email')->email()->required(),
                                TextInput::make('password')->label('Password')->password(),
                                Textarea::make('bio')->label('Bio'),
                                RichEditor::make('rich_bio')->label('Rich Bio'),
                                MarkdownEditor::make('markdown_bio')->label('Markdown Bio'),

                                // Section Component (replaces removed Card)
                                \Filament\Schemas\Components\Section::make('Card Input')
                                    ->schema([
                                        TextInput::make('card_input')->label('Card Input'),
                                    ]),

                                // Grid Component
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('grid_input_1')->label('Grid Input 1'),
                                        TextInput::make('grid_input_2')->label('Grid Input 2'),
                                    ]),

                                // Placeholder Component
                                Placeholder::make('placeholder')
                                    ->label('Placeholder')
                                    ->content('This is a placeholder content'),

                                // Repeater Component
                                Repeater::make('repeater')
                                    ->schema([
                                        TextInput::make('repeater_input')->label('Repeater Input'),
                                    ]),



                                // Time Picker Component
                                TimePicker::make('time_picker')
                                    ->label('Time Picker'),

                                // Toggle Button Component
                                ToggleButtons::make('toggle_button')
                                    ->label('Toggle Button'),
                                // Section Component (replaces removed Card)
                                \Filament\Schemas\Components\Section::make('Card Input 2')
                                    ->schema([
                                        TextInput::make('card_input2')->label('Card Input'),
                                    ]),

                                // Grid Component
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('grid_input_1')->label('Grid Input 1'),
                                        TextInput::make('grid_input_2')->label('Grid Input 2'),
                                    ]),

                                // Placeholder Component
                                Placeholder::make('placeholder')
                                    ->label('Placeholder')
                                    ->content('This is a placeholder content'),

                                // Repeater Component
                                Repeater::make('repeater')
                                    ->schema([
                                        TextInput::make('repeater_input')->label('Repeater Input'),
                                    ]),

                                // Section Component
                                \Filament\Schemas\Components\Section::make('Section')
                                    ->schema([
                                        TextInput::make('section_input')->label('Section Input'),
                                    ]),



                                // Time Picker Component
                                TimePicker::make('time_picker')
                                    ->label('Time Picker'),


                            ]),

                        // Selects and Dropdowns Tab
                        Tabs\Tab::make('Selects & Dropdowns')
                            ->schema([
                                Select::make('role')->label('Role')->options([
                                    'admin' => 'Administrator',
                                    'editor' => 'Editor',
                                    'viewer' => 'Viewer',
                                ]),
                                Select::make('tags')
                                    ->label('Tags')
                                    ->multiple()
                                    ->options([
                                        'php' => 'PHP',
                                        'javascript' => 'JavaScript',
                                        'css' => 'CSS',
                                        'html' => 'HTML',
                                    ]),
                                Select::make('actions')
                                    ->label('Actions')
                                    ->options([
                                        'save' => 'Save',
                                        'update' => 'Update',
                                        'delete' => 'Delete',
                                    ]),
                            ]),

                        // Toggles and Checkboxes Tab
                        Tabs\Tab::make('Toggles & Checkboxes')
                            ->schema([
                                Checkbox::make('agree_terms')
                                    ->label('Agree to terms'),
                                CheckboxList::make('preferences')
                                    ->label('Preferences')
                                    ->options([
                                        'newsletter' => 'Subscribe to newsletter',
                                        'updates' => 'Receive updates',
                                        'offers' => 'Receive offers',
                                    ]),
                                Toggle::make('is_active')->label('Is Active?'),
                            ]),

                        // File Uploads and Media Tab
                        Tabs\Tab::make('Media')
                            ->schema([
                                FileUpload::make('profile_picture')->label('Profile Picture'),
                                MwFileUpload::make('custom_file')->label('Custom File Upload'),
                            ]),

                        // Advanced Inputs Tab
                        Tabs\Tab::make('Advanced')
                            ->schema([
                                ColorPicker::make('favorite_color')->label('Favorite Color'),
                                MwColorPicker::make('custom_color_picker')->label('Custom Color Picker'),
                                MwIconPicker::make('icon_picker')->label('Icon Picker'),
                                MwLinkPicker::make('link_picker')->label('Link Picker'),
                                TagsInput::make('tags_input')->label('Tags Input'),
                                KeyValue::make('key_value')->label('Key/Value Pairs'),
                            ]),

                        // Date Pickers Tab
                        Tabs\Tab::make('Dates & Times')
                            ->schema([
                                DatePicker::make('birthdate')->label('Birth Date'),
                                DatePicker::make('event_date')
                                    ->label('Event Date')
                                    ->displayFormat('F j, Y'),
                            ]),

                        // Wizard for Step-by-Step Input
                        Tabs\Tab::make('Wizard')
                            ->schema([
                                Wizard::make()
                                    ->steps([
                                        Wizard\Step::make('Step 1')
                                            ->schema([
                                                TextInput::make('step1_input')->label('Input for Step 1'),
                                            ]),
                                        Wizard\Step::make('Step 2')
                                            ->schema([
                                                TextInput::make('step2_input')->label('Input for Step 2'),
                                            ]),
                                    ]),
                            ]),
                    ]),

            ]);
    }
}
