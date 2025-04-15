<?php

namespace Modules\Customer\Filament;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Customer\Filament\CustomerResource\Pages\ManageCustomers;
use Modules\Customer\Models\Customer;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('first_name')

                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')

                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')

                    ->maxLength(255),
                Forms\Components\TextInput::make('email')

                    ->email()
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'username')
                    ->preload()
                    ->reactive()
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('currency_id')
                    ->label('Currency')
                    ->options(collect(\Modules\Currency\Models\Currency::all())->pluck('name', 'id'))
                    ->searchable()
                    ->default('USD')

                    ->required(),
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->relationship('company', 'name')
                    ->searchable()
                    ->reactive()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('company_number')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('vat_number')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->maxLength(255)
                            ->unique(),

                        Forms\Components\TextInput::make('phone')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('city')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('zip')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('country')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('website')
                            ->maxLength(255),

                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Create company')
                            ->modalSubmitActionLabel('Create company')
                            ->modalWidth('lg');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->emptyState(function (Table $table) {
                $modelName = static::$model;
                return view('modules.content::filament.admin.empty-state', ['modelName' => $modelName]);

            })

            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('first_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('last_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('phone')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\BooleanColumn::make('active')->sortable(),
                Tables\Columns\TextColumn::make('user.username')->sortable(),
                Tables\Columns\TextColumn::make('currency.name')->sortable(),
                Tables\Columns\TextColumn::make('company.name')->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->query(fn(Builder $query): Builder => $query->where('active', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCustomers::route('/'),
        ];
    }

    /**
     * Get the attributes that should be searchable globally.
     *
     * @return array
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'first_name', 'last_name', 'email', 'phone', 'company.name'];
    }

    /**
     * Get the title for the global search result.
     *
     * @param Model $record
     * @return string
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    /**
     * Get the details for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
            'Phone' => $record->phone,
            'Company' => $record->company?->name,
            'Status' => $record->active ? 'Active' : 'Inactive',
        ];
    }

    /**
     * Get the actions for the global search result.
     *
     * @param Model $record
     * @return array
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('index', ['record' => $record->id, 'activeTab' => 'edit'])),
            Action::make('view')
                ->url(static::getUrl('index', ['record' => $record->id])),
        ];
    }
}
