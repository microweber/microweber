<?php

namespace Modules\Testimonials\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Component;


use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\Ai\Facades\AiImages;
use Modules\Testimonials\Models\Testimonial;
use NeuronAI\Chat\Messages\UserMessage;

class TestimonialsTableList extends LiveEditModuleTable
{

    public function editFormArray()
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required(),
            Textarea::make('content')
                ->label('Content')
                ->required(),
            MwFileUpload::make('client_image')
                ->label('Client Image'),
            TextInput::make('client_company')
                ->label('Client Company'),
            TextInput::make('client_role')
                ->label('Client Role'),
            TextInput::make('client_website')
                ->label('Client Website'),
            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    public function table(Table $table): Table
    {

        $query = Testimonial::query()
            ->where('rel_id', $this->rel_id)
            ->where('rel_type', $this->rel_type);

        // Check if there are testimonials for this module and if not, add default ones
        $testimonialsCount = $query->count();
//        if ($testimonialsCount == 0) {
//            $defaultContent = file_get_contents(module_path('testimonials') . '/default_content.json');
//            $defaultContent = json_decode($defaultContent, true);
//            if (isset($defaultContent['testimonials'])) {
//                foreach ($defaultContent['testimonials'] as $testimonial) {
//                    $newTestimonial = new Testimonial();
//                    $newTestimonial->fill($testimonial);
//                    $newTestimonial->rel_id = $this->rel_id;
//                    $newTestimonial->rel_type = $this->rel_type;
//                    $newTestimonial->save();
//                }
//            }
//        }

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->selectable()
            ->columns([

                ImageColumn::make('client_image')
                    ->label('Picture')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('rel_id')
                    ->label('rel_id')
                    ->hidden(),

                TextColumn::make('rel_type')->hidden()
            ])
            ->filters([

            ])
            ->headerActions([
                CreateAction::make('createTestimonialWithAi')
                    ->visible(app()->has('ai'))
                    ->createAnother(false)
                    ->label('Create with AI')
                    ->form([
                        Textarea::make('createTestimonialWithAiSubject')
                            ->label('Subject')
                            ->required(),

                        TextInput::make('createTestimonialWithAiContentNumber')
                            ->numeric()
                            ->default(1)
                            ->label('Number of testimonials')
                            ->required(),

                        Toggle::make('createTestimonialWithAiContentImages')
                            ->visible(app()->has('ai.images'))
                            ->label('Also create images')
                            ->default(false)
                            ->onColor('success')
                            ->inline()
                        ,

                    ])
                    ->action(function (array $data) {

                        $prompt = "Write testimonials for a client with the following details: " . $data['createTestimonialWithAiSubject'];

                        $numberOfTesimonials = $data['createTestimonialWithAiContentNumber'] ?? 1;
                        $createImages = $data['createTestimonialWithAiContentImages'] ?? false;

                        $class = new class {
                            public string $name;
                            public string $content;
                            public string $client_company;
                            public string $client_role;
                        };

                        /*
                         *  @var \Modules\Ai\Agents\BaseAgent $agent ;
                         */
                        $agent = app('ai.agents')->agent('base');


                        for ($i = 0; $i < $numberOfTesimonials; $i++) {

                            $resp = $agent->structured(
                                new UserMessage($prompt)
                                , $class::class
                            );
                            $resp = json_decode(json_encode($resp), true);

                            if ($resp) {
                                $testimonial = new Testimonial();
                                $testimonial->name = $resp['name'] ?? 'John Doe';
                                $testimonial->content = $resp['content'] ?? 'This is a testimonial content.';
                                $testimonial->client_company = $resp['client_company'] ?? 'Company Name';
                                $testimonial->client_role = $resp['client_role'] ?? 'Client Role';
                                $testimonial->rel_id = $this->rel_id;
                                $testimonial->rel_type = $this->rel_type;
                                $testimonial->save();
                            }


                            if ($createImages) {

                                $messagesForImages = [];
                                $messagesForImages[] = ['role' => 'user', 'content' => 'Create an image for the testimonial: ' . $resp['content']];

                                $response = AiImages::generateImage($messagesForImages);

                                if ($response and isset($response['url']) and $response['url']) {
                                    $testimonial->client_image = $response['url'];
                                    $testimonial->save();
                                }

                            }


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
                DeleteAction::make('delete')
            ])
            ->reorderable('position')
            ->bulkActions([
                DeleteBulkAction::make()
            ]);
    }


}
