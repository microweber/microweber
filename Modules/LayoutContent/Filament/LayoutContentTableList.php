<?php

namespace Modules\LayoutContent\Filament;

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
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\LiveEdit\Filament\Admin\Tables\LiveEditModuleTable;
use Modules\LayoutContent\Models\LayoutContentItem;
use Modules\Testimonials\Models\Testimonial;

class LayoutContentTableList extends LiveEditModuleTable
{
    public function editFormArray()
    {
        return [
            TextInput::make('title')
                ->label('Title')
                ->required(),

            Textarea::make('description')
                ->label('Description')
                ->rows(3),

            MwFileUpload::make('image')
                ->label('Image'),

            TextInput::make('image_alt_text')
                ->label('Image Alt Text'),

            TextInput::make('button_text')
                ->label('Button Text'),

            MwLinkPicker::make('button_link')
                ->label('Button Link')
                ->url(),

            Hidden::make('rel_id')
                ->default($this->rel_id),
            Hidden::make('rel_type')
                ->default($this->rel_type),
        ];
    }

    public function table(Table $table): Table
    {

        $query = LayoutContentItem::query()
            ->where('rel_id', $this->rel_id)
            ->where('rel_type', $this->rel_type);

        // Check if there are testimonials for this module and if not, add default ones
        $layoutContentItemCount = $query->count();
        if ($layoutContentItemCount == 0) {
            $defaultSettings = file_get_contents(module_path('layout_content') . '/default_settings.json');
            $defaultSettings = json_decode($defaultSettings, true);
            if (isset($defaultSettings['contents'])) {
                foreach ($defaultSettings['contents'] as $content) {
                    $newTestimonial = new LayoutContentItem();
                    $newTestimonial->fill($content);
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
                TextColumn::make('title')
                    ->label('Title'),
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
