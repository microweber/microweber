<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use Modules\Slider\Models\Slider;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Ai\Facades\AiImages;
use NeuronAI\Chat\Messages\UserMessage;
use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
use Filament\Schemas\Components\Grid;
use MicroweberPackages\Multilanguage\Forms\Actions\TranslateFieldAction;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class SliderTableList extends LiveEditModuleTable implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string|null $rel_id = null;
    public string|null $rel_type = null;
    public string|null $module_id = null;

    public function editFormArray()
    {
        return [
            Hidden::make('multilanguage')
                ->visible(MultilanguageHelpers::multilanguageIsEnabled()),

            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            Hidden::make('position')
                ->default(0),
            MwFileUpload::make('media')
                ->label('Image')
                ->helperText('Upload image for this slide.'),
            TextInput::make('name')
                ->label('Slide Title')
                ->helperText('Enter a title for this slide.')
                ->hintAction(
                    TranslateFieldAction::make('name')->label('')
                ),
            Textarea::make('description')
                ->label('Slide Description')
                ->helperText('Provide a description for this slide.')
                ->hintAction(
                    TranslateFieldAction::make('description')->label('')
                ),
            ToggleButtons::make('settings.alignItems')
                ->helperText('Choose the alignment of the slide.')
                ->live()
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->inline()
                ->icons([
                    'left' => 'heroicon-o-bars-3-bottom-left',
                    'center' => 'heroicon-o-bars-3',
                    'right' => 'heroicon-o-bars-3-bottom-right',
                ]),

            TextInput::make('button_text')
                ->label('Button Text')
                ->helperText('Enter text for the button')
                ->hintAction(
                    TranslateFieldAction::make('button_text')->label('')
                ),
            MwLinkPicker::make('link')
                ->label('Button URL')
                ->setSimpleMode()
                ->live()
                ->url()
                ->helperText('Enter a URL for the button'),


            Section::make('Styling')
                ->collapsible()
                ->collapsed()
                ->schema([


                    Grid::make()
                        ->columns(2)
                        ->schema([


                            MwColorPicker::make('settings.buttonBackgroundColor')
                                ->label('Button Background Color')
                                ->rgba()
                            ,
                            MwColorPicker::make('settings.buttonBackgroundHoverColor')
                                ->label('Button Background Hover Color')
                                ->rgba()
                            ,
                            MwColorPicker::make('settings.buttonBorderColor')
                                ->label('Button Border Color')
                                ->rgba()
                            ,

                            MwColorPicker::make('settings.buttonTextColor')
                                ->label('Button Text Color')
                                ->rgba()
                            ,


                            MwColorPicker::make('settings.buttonTextHoverColor')
                                ->label('Button Text Hover Color')
                                ->rgba(),


                            MwColorPicker::make('settings.imageBackgroundColor')
                                ->label('Image Background Color')
                                ->rgba(),

                        ]),


                    Grid::make()
                        ->columns(2)
                        ->schema([
                            MwInputSliderGroup::make()
                                ->live()
                                ->columns(10)
                                ->label('Button Font Size')
                                ->sliders([
                                    MwInputSlider::make('settings.buttonFontSize')
                                        ->label('Button Font Size')
                                        ->step(1),
                                ])
                                ->enableTooltips()
                                ->range([
                                    "min" => 8,
                                    "max" => 72
                                ]),
                            TextInput::make('settings.buttonFontSize')
                                ->label('Button Font Size')
                                ->numeric()
                                ->columns(2)
                                ->live()
                                ->step(1),

                        ]),

                    Grid::make()
                        ->columns(3)
                        ->schema([
                            MwColorPicker::make('settings.titleColor')
                                ->label('Title Color')
                                ->live()
                                ->rgba(),

                            MwInputSliderGroup::make()
                                ->live()
                                ->label('Title Font Size')
                                ->sliders([
                                    MwInputSlider::make('settings.titleFontSize')
                                        ->label('Title Font Size'),
                                ])
                                ->enableTooltips()
                                ->range([
                                    "min" => 8,
                                    "max" => 72
                                ]),

                            TextInput::make('settings.titleFontSize')
                                ->label('Set Title Font Size')
                                ->numeric()
                                ->live(),
                        ]),

                    Grid::make(3)
                        ->columns(3)
                        ->schema([

                            MwColorPicker::make('settings.descriptionColor')
                                ->label('Description Color')
                                ->live()
                                ->rgba(),


                            MwInputSliderGroup::make()
                                ->live()
                                ->label('Description Font Size')
                                ->sliders([
                                    MwInputSlider::make('settings.descriptionFontSize')
                                        ->label('Description Font Size'),
                                ])
                                ->enableTooltips()
                                ->range([
                                    "min" => 8,
                                    "max" => 72
                                ]),
                            TextInput::make('settings.descriptionFontSize')
                                ->label('Set Description Font Size')
                                ->numeric()
                                ->live(),

                        ]),


                    TextInput::make('settings.imageBackgroundOpacity')
                        ->label('Image Background Opacity')
                        ->numeric()
                        ->step(0.01)
                        ->live()
                    ,


                    Select::make('settings.imageBackgroundFilter')
                        ->label('Image Background Filter')
                        ->live()
                        ->options([
                            'none' => 'None',
                            'blur' => 'Blur',
                            'mediumBlur' => 'Medium Blur',
                            'maxBlur' => 'Max Blur',
                            'grayscale' => 'Grayscale',
                            'hue-rotate' => 'Hue Rotate',
                            'invert' => 'Invert',
                            'sepia' => 'Sepia',
                        ])
                        ->default('none'),

                ])


        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Slider::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')->columns([
                ImageColumn::make('media')
                    ->label('Image')
                    ->action(EditAction::make('edit'))
                    ->circular(),
                TextColumn::make('name')
                    ->label('Title')
                    ->action(EditAction::make('edit'))
                    ->searchable(),


            ])->filters([
                // ...
            ])
            ->headerActions([
                CreateAction::make('createSlideWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createSlideWithAiSubject')
                            ->label('Subject')
                            ->required()
                            ->helperText('Describe the topic or theme for which you need slides generated'),

                        TextInput::make('createSlideWithAiContentNumber')
                            ->numeric()
                            ->default(3)
                            ->label('Number of slides')
                            ->required(),

                        Toggle::make('createSlideWithAiContentImages')
                            ->visible(app()->has('ai.images'))
                            ->label('Also create images')
                            ->default(true)
                            ->onColor('success')
                            ->inline(),
                    ])
                    ->action(function (array $data) {
                        $prompt = "Create compelling slide content for: " . $data['createSlideWithAiSubject'];

                        $numberOfSlides = $data['createSlideWithAiContentNumber'] ?? 3;
                        $createImages = $data['createSlideWithAiContentImages'] ?? false;

                        $class = new class {
                            public string $name;
                            public string $description;
                            public string $button_text;
                            public string $link;
                        };

                        /*
                         * @var \Modules\Ai\Agents\BaseAgent $agent
                         */
                        $agent = app('ai.agents')->agent('base');

                        for ($i = 0; $i < $numberOfSlides; $i++) {
                            $resp = $agent->structured(
                                new UserMessage($prompt),
                                $class::class
                            );
                            $resp = json_decode(json_encode($resp), true);

                            if ($resp) {
                                $slide = new Slider();
                                $slide->name = $resp['name'] ?? 'Slide Title';
                                $slide->description = $resp['description'] ?? 'Slide description content.';
                                $slide->button_text = $resp['button_text'] ?? 'Learn More';
                                $slide->link = $resp['link'] ?? '#';
                                $slide->rel_id = $this->rel_id;
                                $slide->rel_type = $this->rel_type;
                                $slide->position = $i;
                                $slide->save();

                                if ($createImages) {
                                    $messagesForImages = [];
                                    $imagePrompt = 'Create a beautiful, professional slide image for: ' . $resp['name'] . '. ' . $resp['description'];
                                    $messagesForImages[] = ['role' => 'user', 'content' => $imagePrompt];

                                    try {
                                        $response = AiImages::generateImage($messagesForImages);
                                        if ($response && isset($response['url']) && $response['url']) {
                                            $slide->media = $response['url'];
                                            $slide->save();
                                        }
                                    } catch (\Exception $e) {
                                        // Log error but continue with slide creation
                                    }
                                }
                            }
                        }

                        $this->resetTable();
                    }), CreateAction::make('create')
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form($this->editFormArray()),
                DeleteAction::make('delete'),
                Action::make('copy')
                    ->label('Copy')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (Slider $record) {
                        $newSlider = $record->replicate();
                        $newSlider->push();

                        $this->resetTable();
                    }),
            ])
            ->reorderable('position')
            ->bulkActions([

                DeleteBulkAction::make()

            ]);
    }

    public function render()
    {
        return view('modules.slider::slider-table-list');
    }
}
