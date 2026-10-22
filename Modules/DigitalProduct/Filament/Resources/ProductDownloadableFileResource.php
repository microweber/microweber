<?php

namespace Modules\DigitalProduct\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource\Pages;
use Modules\DigitalProduct\Models\ProductDownloadableFile;
use Modules\DigitalProduct\Services\DigitalProductService;
use Modules\Product\Models\Product;

class ProductDownloadableFileResource extends Resource
{
    protected static ?string $model = ProductDownloadableFile::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static string | \UnitEnum | null $navigationGroup = 'Shop';
    // Reachable from the Shop → "More" hub, not the main sidebar (keeps Shop short).
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationLabel = 'Downloadable files';
    protected static ?string $modelLabel = 'downloadable file';
    protected static ?string $pluralModelLabel = 'downloadable files';

    public static function form(Schema $schema): Schema
    {
        $disk = (new DigitalProductService())->defaultDisk();

        return $schema->schema([
            Section::make('Downloadable file')
                ->description('Attach a file to a product. Buyers get a secure, tokenized download link after they pay.')
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search): array => Product::query()
                            ->where('content_type', 'product')
                            ->where('title', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('title', 'id')
                            ->all())
                        ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->title),

                    FileUpload::make('file_path')
                        ->label('File')
                        ->required()
                        ->disk($disk)
                        ->directory('digital-products')
                        ->visibility('private')
                        ->storeFileNamesIn('original_name')
                        ->helperText('Stored privately — never served by a public URL.'),

                    TextInput::make('original_name')
                        ->label('Download file name')
                        ->helperText('The filename buyers receive. Defaults to the uploaded name.')
                        ->maxLength(255),

                    TextInput::make('download_limit')
                        ->label('Download limit')
                        ->numeric()
                        ->minValue(1)
                        ->helperText('Max downloads per purchase. Leave blank for unlimited.'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('original_name')
                    ->label('File')
                    ->searchable(),
                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn ($state) => $state ? number_format($state / 1024, 1) . ' KB' : '—'),
                TextColumn::make('download_limit')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state) => $state ?: 'Unlimited'),
                TextColumn::make('downloads_count')
                    ->label('Grants')
                    ->counts('downloads')
                    ->badge()
                    ->tooltip('Number of purchase grants issued for this file'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductDownloadableFiles::route('/'),
            'create' => Pages\CreateProductDownloadableFile::route('/create'),
            'edit' => Pages\EditProductDownloadableFile::route('/{record}/edit'),
        ];
    }
}
