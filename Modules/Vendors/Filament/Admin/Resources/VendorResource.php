<?php

namespace Modules\Vendors\Filament\Admin\Resources;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Product\Models\Product;
use Modules\Vendors\Filament\Admin\Resources\VendorResource\Pages;
use Modules\Vendors\Models\Vendor;

/**
 * Admin screen to review, approve and manage vendor storefronts.
 *
 * A user becomes a vendor by being given the `vendor` role (Users → Roles);
 * their vendor profile row is created the first time they open their
 * profile panel. This resource is where staff curate those storefronts —
 * approve/suspend them and edit the public identity.
 */
class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    // Reachable from the Shop → "More" hub, not the main sidebar (keeps Shop short).
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 8;
    protected static ?string $navigationLabel = 'Vendors';
    protected static ?string $modelLabel = 'vendor';
    protected static ?string $pluralModelLabel = 'vendors';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Section::make('Storefront')->columns(2)->schema([
                TextInput::make('shop_name')
                    ->label(__('Shop name'))
                    ->required()
                    ->maxLength(191),
                TextInput::make('slug')
                    ->label(__('URL slug'))
                    ->helperText(__('Public address: /vendor/{slug}. Leave blank to auto-generate.'))
                    ->maxLength(191),
                Textarea::make('description')
                    ->label(__('About the shop'))
                    ->columnSpanFull()
                    ->rows(4),
                TextInput::make('logo')->label(__('Logo URL'))->url(),
                TextInput::make('banner')->label(__('Banner URL'))->url(),
                TextInput::make('contact_email')->label(__('Contact email'))->email(),
                TextInput::make('website')->label(__('Website'))->url(),
            ]),

            Section::make('Moderation')->columns(2)->schema([
                Toggle::make('is_approved')
                    ->label(__('Approved (storefront is public)'))
                    ->default(true),
                Toggle::make('notify_on_order')
                    ->label(__('Email this vendor on new orders'))
                    ->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shop_name')
                    ->label(__('Shop'))
                    ->searchable()
                    ->sortable()
                    ->description(fn (Vendor $r) => '/vendor/' . $r->slug),

                TextColumn::make('user.email')
                    ->label(__('Owner'))
                    ->searchable(),

                TextColumn::make('products_count')
                    ->label(__('Products'))
                    ->state(fn (Vendor $r) => Product::query()
                        ->filter(['author' => $r->user_id])
                        ->where('is_deleted', 0)
                        ->count()),

                IconColumn::make('is_approved')
                    ->label(__('Approved'))
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label(__('Joined'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('visit')
                    ->label(__('View shop'))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Vendor $r) => url('vendor/' . $r->slug), shouldOpenInNewTab: true)
                    ->color('gray'),

                \Filament\Actions\Action::make('toggle_approval')
                    ->label(fn (Vendor $r) => $r->is_approved ? __('Suspend') : __('Approve'))
                    ->icon(fn (Vendor $r) => $r->is_approved ? 'heroicon-o-pause-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Vendor $r) => $r->is_approved ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->action(fn (Vendor $r) => $r->forceFill(['is_approved' => ! $r->is_approved])->save()),

                \Filament\Actions\EditAction::make()->button(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
