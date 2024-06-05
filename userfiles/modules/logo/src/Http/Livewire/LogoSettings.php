<?php

namespace MicroweberPackages\Modules\Logo\Http\Livewire;

use App\Filament\Admin\Pages\Abstract\LiveEditSettingsPageDefault;
use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Product\Models\Product;

class LogoSettings extends LiveEditSettingsPageDefault implements HasTable
{

    use InteractsWithTable;

    public function getOptionGroups(): array
    {
        return [
            'logo'
        ];
    }

    public function getOptionModule(): string
    {
        return 'logo';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query())
            ->columns([
                ImageUrlColumn::make('media_url')
                    ->imageUrl(function (Product $product) {
                        return $product->mediaUrl();
                    }),

                TextColumn::make('title')
                    ->searchable()
                    ->columnSpanFull()
                    ->weight(FontWeight::Bold),


                TextColumn::make('price_display')
                    ->columnSpanFull(),

                SelectColumn::make('is_active')
                    ->options([
                        1 => 'Published',
                        0 => 'Unpublished',
                    ]),


                TextColumn::make('created_at')
                    ->searchable()
                    ->columnSpanFull(),

            ])
            ->paginationPageOptions([
                1,
                3,
                5
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                    ])
            ])
            ->bulkActions([
                // ...
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Options')
                    ->tabs([
                        Tabs\Tab::make('Image')
                            ->schema([
                                FileUpload::make('options.logo.attachment')->live(),
                                TextInput::make('options.logo.title')->live()
                            ]),
                        Tabs\Tab::make('Text')
                            ->schema([
                                TextInput::make('options.logo.text')
                                    ->label('Logo Text')
                                    ->helperText('This logo text will appear when image not applied')
                                    ->live(),
                                ColorPicker::make('options.logo.text_color')
                                    ->live()
                                    ->rgba()
                            ]),
                    ]),

            ]);
    }

}
