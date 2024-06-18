<?php

namespace App\Filament\Admin\Resources\ProductResource\RelationManagers;

use Akaunting\Money\Currency;
use App\Filament\Resources\Shop\OrderResource;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use MicroweberPackages\CustomField\Enums\CustomFieldTypes;
use MicroweberPackages\CustomField\Models\CustomField;

class CustomFieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'customField';

    protected static ?string $title = 'Custom Fields';

    protected static ?string $recordTitleAttribute = 'reference';

    public function form(Form $form): Form
    {
        $editForm = [];
        $editForm[] = TextInput::make('name')
            ->label('Name')
            ->placeholder('Name')
            ->required();

        return $form->schema($editForm);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->label('Name'),
                IconColumn::make('type')
                    ->icon(function (CustomField $customField) {
                        $icon = CustomFieldTypes::from($customField->type);
                        return $icon->getIcons();
                    }),
                TextColumn::make('value')
                    ->state(function (CustomField $customField) {
                        if ($customField->type == 'radio'
                            || $customField->type == 'dropdown'
                            || $customField->type == 'checkbox') {
                            if ($customField->fieldValue) {
                                if (!empty($customField->fieldValue)) {
                                    $values = [];
                                    foreach ($customField->fieldValue as $value) {
                                        $values[] = $value->value;
                                    }
                                    return implode(', ', $values);
                                }
                            }
                        } else if ($customField->fieldValueSingle) {
                            return $customField->fieldValueSingle->value;
                        }
                    })->label('Value')

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
