<?php

namespace MicroweberPackages\Role\Filament\Resources;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Role\Filament\Resources\RoleResource\Pages;
use MicroweberPackages\Role\Models\Role;
use MicroweberPackages\Role\Models\Permission;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shield-check';

    protected static string | \UnitEnum | null $navigationGroup = 'Users';

    protected static ?int $navigationSort = 99;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Grid::make(['default' => 1, 'lg' => 2])
                    ->schema([
                        Section::make('Role Information')
                            ->description('Basic role details')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('e.g., Content Manager')
                                    ->helperText('A unique name for this role'),

                                Textarea::make('description')
                                    ->rows(3)
                                    ->placeholder('Describe the purpose and permissions of this role...')
                                    ->helperText('Optional description to help identify this role'),

                                Grid::make(2)
                                    ->schema([
                                        ColorPicker::make('color')
                                            ->label('Role Color')
                                            ->helperText('Visual identifier for this role')
                                            ->default('#3b82f6'),

                                        TextInput::make('sort_order')
                                            ->label('Sort Order')
                                            ->numeric()
                                            ->default(0)
                                            ->helperText('Lower numbers appear first'),
                                    ]),

                                Toggle::make('is_system')
                                    ->label('System Role')
                                    ->helperText('System roles cannot be deleted')
                                    ->default(false)
                                    ->disabled(fn (?Model $record) => $record?->is_system ?? false),
                            ])
                            ->columnSpan(['lg' => 1]),

                        Section::make('Permissions')
                            ->description('Assign permissions to this role')
                            ->schema([
                                CheckboxList::make('permissions')
                                    ->label('')
                                    ->relationship('permissions', 'name')
                                    ->options(function () {
                                        return Permission::orderBy('name')->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->bulkToggleable()
                                    ->columns(2)
                                    ->descriptions(function () {
                                        return Permission::orderBy('name')
                                            ->get()
                                            ->mapWithKeys(function ($permission) {
                                                $description = self::getPermissionDescription($permission->name);
                                                return [$permission->id => $description];
                                            })
                                            ->toArray();
                                    }),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ColorColumn::make('color')
                    ->label('')
                    ->width('40px'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('font-bold')
                    ->icon(fn (Role $record) => $record->is_system ? 'heroicon-o-lock-closed' : null)
                    ->iconColor('warning'),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50)
                    ->toggleable()
                    ->placeholder('No description'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Users')
                    ->counts('users')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->counts('permissions')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_system')
                    ->label('System')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-user')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_system')
                    ->label('System Role')
                    ->trueLabel('System roles only')
                    ->falseLabel('Custom roles only')
                    ->default(false),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this role? Users with this role will lose access.')
                    ->disabled(fn (Role $record) => $record->is_system),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalDescription('Are you sure you want to delete these roles?')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if (!$record->is_system) {
                                    $record->delete();
                                }
                            }
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RoleUsersRelationManager::class could be added here
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    /**
     * Get permission description for display
     */
    protected static function getPermissionDescription(string $permissionName): string
    {
        $descriptions = [
            'view users' => 'View user accounts',
            'create users' => 'Create new user accounts',
            'edit users' => 'Edit existing user accounts',
            'delete users' => 'Delete user accounts',
            'view roles' => 'View roles and permissions',
            'create roles' => 'Create new roles',
            'edit roles' => 'Edit existing roles',
            'delete roles' => 'Delete roles',
            'view content' => 'View pages and posts',
            'create content' => 'Create pages and posts',
            'edit content' => 'Edit pages and posts',
            'delete content' => 'Delete pages and posts',
            'view products' => 'View products',
            'create products' => 'Create products',
            'edit products' => 'Edit products',
            'delete products' => 'Delete products',
            'view orders' => 'View orders',
            'manage orders' => 'Manage order status and details',
            'view settings' => 'View system settings',
            'edit settings' => 'Modify system settings',
            'view media' => 'Access media library',
            'upload media' => 'Upload files to media library',
            'delete media' => 'Delete files from media library',
        ];

        return $descriptions[$permissionName] ?? 'Permission: ' . $permissionName;
    }

    /**
     * Get the attributes that should be searchable globally.
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    /**
     * Get the title for the global search result.
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    /**
     * Get the details for the global search result.
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Users' => $record->users_count ?? 0,
            'Permissions' => $record->permissions_count ?? 0,
            'Type' => $record->is_system ? 'System' : 'Custom',
        ];
    }

    /**
     * Get the actions for the global search result.
     */
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Action::make('edit')
                ->url(static::getUrl('edit', ['record' => $record])),
        ];
    }
}
