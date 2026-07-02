<?php

namespace Modules\Pictures\Filament;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Ai\Facades\AiImages;
use Modules\Media\Models\Media;
use NeuronAI\Chat\Messages\UserMessage;
use NeuronAI\StructuredOutput\SchemaProperty;

class PicturesTableList extends LiveEditModuleTable
{
    public ?string $rel_id = null;
    public ?string $rel_type = null;
    public ?string $module_id = null;

    public function editFormArray()
    {
        return [
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
            MwMediaBrowser::make('filename')
                ->label('Image')
                ->required(),
            TextInput::make('title')
                ->label('Title'),
            Textarea::make('description')
                ->label('Description')
                ->rows(3),
        ];
    }

    public function table(Table $table): Table
    {
        $query = Media::query()
            ->where('rel_id', $this->rel_id)
            ->where('rel_type', $this->rel_type);

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->selectable()
            ->columns([


                ImageColumn::make('filename')
                    ->label('Image')
                    ->action(EditAction::make('edit'))
                    ->square()
                    ->size(60),
                TextColumn::make('title')
                    ->action(EditAction::make('edit'))
                    ->label('Title')
                    ->searchable()
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->sortable(),
                TextColumn::make('description')
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('rel_id')
                    ->label('rel_id')
                    ->hidden(),
                TextColumn::make('rel_type')->hidden()
            ])
            ->filters([

            ])
            ->headerActions([
                Action::make('upload-images')
                    ->label('Upload Images')
                    ->icon('heroicon-o-photo')
                    ->color('success')
                    ->form([
                        MwMediaBrowser::make('selected_images')
                            ->label('Select Images')

                    ])
                    ->action(function (array $data) {
                        if (isset($data['selected_images']) && !empty($data['selected_images'])) {
                            $uploadedCount = 0;
                            $selectedImages = is_array($data['selected_images']) ? $data['selected_images'] : [$data['selected_images']];

                            foreach ($selectedImages as $imageUrl) {
                                if (!empty(trim($imageUrl))) {
                                    $media = new Media();
                                    $media->filename = $imageUrl;
                                    $media->title = pathinfo($imageUrl, PATHINFO_FILENAME);
                                    $media->description = '';
                                    $media->rel_id = $this->rel_id;
                                    $media->rel_type = $this->rel_type;
                                    $media->position = Media::where('rel_type', $this->rel_type)
                                            ->where('rel_id', $this->rel_id)
                                            ->max('position') + 1;
                                    $media->save();

                                    $uploadedCount++;
                                }
                            }

                            if ($uploadedCount > 0) {
                                \Filament\Notifications\Notification::make()
                                    ->title('Images Added Successfully!')
                                    ->body("Added {$uploadedCount} image(s)")
                                    ->success()
                                    ->send();

                                $this->resetTable();
                            }
                        }
                    })
                    ->modalHeading('Select Images')
                    ->modalDescription('Choose images from the media library or upload new ones.'),

                CreateAction::make('createPictureWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createPictureWithAiSubject')
                            ->label('Image Prompt')
                            ->placeholder('E.g., A beautiful sunset over mountains, Modern office interior, Product photography')
                            ->helperText('Describe the images you want to generate')
                            ->required(),

                        TextInput::make('createPictureWithAiContentNumber')
                            ->numeric()
                            ->default(1)
                            ->label('Number of images')
                            ->minValue(1)
                            ->maxValue(10)
                            ->required(),

                        Toggle::make('createPictureWithAiContentImages')
                            ->visible(app()->has('ai.images'))
                            ->label('High Quality')
                            ->helperText('Generate higher quality images (takes longer)')
                            ->default(false)
                            ->onColor('success')
                            ->inline(),
                    ])
                    ->action(function (array $data) {
                        $prompt = "Generate images based on: " . $data['createPictureWithAiSubject'];
                        $numberOfImages = $data['createPictureWithAiContentNumber'] ?? 1;
                        $highQuality = $data['createPictureWithAiContentImages'] ?? false;

                        $generatedCount = 0;
                        $errorCount = 0;

                        for ($i = 0; $i < $numberOfImages; $i++) {
                            try {
                                $messagesForImages = [];
                                $messagesForImages[] = [
                                    'role' => 'user',
                                    'content' => $prompt . ($numberOfImages > 1 ? " (variation " . ($i + 1) . ")" : "")
                                ];

                                $options = [];
                                if ($highQuality) {
                                    $options['quality'] = 'high';
                                    $options['size'] = '1024x1024';
                                } else {
                                    $options['size'] = '512x512';
                                }

                                $response = AiImages::generateImage($messagesForImages, $options);

                                if ($response && isset($response['url']) && $response['url']) {
                                    $media = new Media();
                                    $media->filename = $response['url'];
                                    $media->title = 'AI Generated: ' . substr($data['createPictureWithAiSubject'], 0, 50) . '...';
                                    $media->description = 'Generated with AI prompt: ' . $data['createPictureWithAiSubject'];
                                    $media->rel_id = $this->rel_id;
                                    $media->rel_type = $this->rel_type;
                                    $media->position = Media::where('rel_type', $this->rel_type)
                                            ->where('rel_id', $this->rel_id)
                                            ->max('position') + 1;
                                    $media->save();

                                    $generatedCount++;
                                }
                            } catch (\Exception $e) {
                                $errorCount++;
                                \Log::error('AI Image Generation Error: ' . $e->getMessage());
                            }
                        }

                        // Show notification
                        if ($generatedCount > 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Images Generated Successfully!')
                                ->body("Generated {$generatedCount} image(s)" . ($errorCount > 0 ? " ({$errorCount} failed)" : ""))
                                ->success()
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Generation Failed')
                                ->body('Could not generate any images. Please try again.')
                                ->danger()
                                ->send();
                        }

                        $this->resetTable();
                    }),

                CreateAction::make('create')
                    ->slideOver()
                    ->form($this->editFormArray())
            ])
            ->actions([
                EditAction::make('edit')
                    ->slideOver()
                    ->form($this->editFormArray()),

        Action::make('copy')
            ->label('Copy')
            ->icon('heroicon-o-document-duplicate')
            ->action(function (Media $record) {
                        $newMedia = $record->replicate();
                        $newMedia->push();

                        $this->resetTable();
                    }),
                DeleteAction::make('delete')
            ])
            ->reorderable('position')
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('modules.pictures::pictures-table-list');
    }
}
