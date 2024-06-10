<?php

namespace MicroweberPackages\Modules\Testimonials\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\Modules\Testimonials\Models\Testimonial;

class TestimonialsModuleSettings extends LiveEditModuleSettings implements HasTable
{
    use InteractsWithTable;

    public string $module = 'testimonials';

    protected static string $view = 'microweber-module-testimonials::livewire.module-settings';

    public function table(Table $table): Table
    {

        $editForm = [
           // FileUpload::make('client_picture'),
            TextInput::make('name'),
            Textarea::make('content'),
            TextInput::make('client_company'),
            TextInput::make('client_role'),
            TextInput::make('client_website'),
        ];

        return $table
            ->query(Testimonial::query())
            ->columns([
            TextColumn::make('name'),
        ])->headerActions([
                CreateAction::make()
                    ->slideOver()
                    ->form($editForm)
                    ->createAnother(false)
                    ->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    })
        ])->actions([
            EditAction::make()
                ->slideOver()
                ->hiddenLabel(true)
                ->form($editForm)
                ->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                }),

                DeleteAction::make()
                    ->hiddenLabel(true)
                    ->after(function () {
                        $this->dispatch('mw-option-saved',
                            optionGroup: $this->optionGroup
                        );
                    }),
            ]);

    }
}
