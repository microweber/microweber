<?php

namespace Modules\Testimonials\Filament;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Modules\Testimonials\Models\Testimonial;

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
        if ($testimonialsCount == 0) {
            $defaultContent = file_get_contents(module_path('testimonials') . '/default_content.json');
            $defaultContent = json_decode($defaultContent, true);
            if (isset($defaultContent['testimonials'])) {
                foreach ($defaultContent['testimonials'] as $testimonial) {
                    $newTestimonial = new Testimonial();
                    $newTestimonial->fill($testimonial);
                    $newTestimonial->rel_id = $this->rel_id;
                    $newTestimonial->rel_type = $this->rel_type;
                    $newTestimonial->save();
                }
            }
        }

        return $table
            ->query($query)
            ->defaultSort('position', 'asc')
            ->columns([
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
                // BulkActionGroup::make([ DeleteBulkAction::make() ])
            ]);
    }


}
