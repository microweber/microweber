<?php

namespace Modules\Faq\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Faq\Models\Faq;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('question')
                    ->required()
                    ->label('Question'),
                Textarea::make('answer')
                    ->required()
                    ->label('Answer'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question')
                    ->label('Question'),
                TextColumn::make('answer')
                    ->label('Answer')
                    ->limit(50),
            ]);
    }
}
