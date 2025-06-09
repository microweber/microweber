<?php

namespace Modules\Slider\Filament;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Slider\Models\Slider;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Ai\Facades\AiImages;
use NeuronAI\Chat\Messages\UserMessage;

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
                ->helperText('Enter a title for this slide.'),
            Textarea::make('description')
                ->label('Slide Description')
                ->helperText('Provide a description for this slide.'),
            Select::make('settings.alignItems')
                ->label('Align Items')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->default('center'),

            TextInput::make('button_text')
                ->label('Button Text')
                ->helperText('Enter text for the button'),
            TextInput::make('link')
                ->label('Button URL')
                ->url()
                ->helperText('Enter a URL for the button'),
            ColorPicker::make('settings.buttonBackgroundColor')
                ->label('Button Background Color')
                ->visible(fn($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonBackgroundHoverColor')
                ->label('Button Background Hover Color')
                ->visible(fn($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonBorderColor')
                ->label('Button Border Color')
                ->visible(fn($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonTextColor')
                ->label('Button Text Color')
                ->visible(fn($get) => $get('settings.showButton')),
            ColorPicker::make('settings.buttonTextHoverColor')
                ->label('Button Text Hover Color')
                ->visible(fn($get) => $get('settings.showButton')),
            TextInput::make('settings.buttonFontSize')
                ->label('Button Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(16)
                ->visible(fn($get) => $get('settings.showButton')),
            ColorPicker::make('settings.titleColor')
                ->label('Title Color'),
            TextInput::make('settings.titleFontSize')
                ->label('Title Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(24),
            ColorPicker::make('settings.descriptionColor')
                ->label('Description Color'),
            TextInput::make('settings.descriptionFontSize')
                ->label('Description Font Size')
                ->suffix('px')
                ->numeric()
                ->minValue(8)
                ->maxValue(64)
                ->default(16),
            ColorPicker::make('settings.imageBackgroundColor')
                ->label('Image Background Color'),
            TextInput::make('settings.imageBackgroundOpacity')
                ->label('Image Background Opacity')
                ->numeric()
                ->minValue(0)
                ->maxValue(1)
                ->step(0.1)
                ->default(1),
            Select::make('settings.imageBackgroundFilter')
                ->label('Image Background Filter')
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
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Slider::query()->where('rel_id', $this->rel_id)->where('rel_type', $this->rel_type))
            ->defaultSort('position', 'asc')            ->columns([
                ImageColumn::make('media')
                    ->label('Image')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                TextColumn::make('button_text')
                    ->label('Button')
                    ->limit(20),
            ])            ->filters([
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
                                        \Log::error('Failed to generate image for slide: ' . $e->getMessage());
                                    }
                                }
                            }
                        }

                        $this->resetTable();
                    }),                CreateAction::make('create')
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form($this->editFormArray()),
                DeleteAction::make('delete')
            ])
            ->reorderable('position')
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('modules.slider::slider-table-list');
    }
}
