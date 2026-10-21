<?php

namespace Modules\Vendors\Filament\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Models\Media;
use Modules\Product\Models\Product;
use Modules\Vendors\Services\VendorManager;

/**
 * Vendor self-service: upload and manage the products this vendor owns.
 *
 * Ownership is `content.created_by = auth id` — the same FilterByAuthor
 * convention the public vendor page and admin use. The table is scoped to
 * the signed-in vendor, create stamps created_by, and an uploaded image is
 * attached as a Media row so it renders in the shop grid.
 *
 * Lives in the `profile` panel; only visible to users with the vendor role.
 */
class MyVendorProducts extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';
    protected string $view = 'modules.vendors::filament.pages.my-vendor-products';
    protected static ?int $navigationSort = 11;

    public function mount(): void
    {
        // Marking a user a vendor is enough — provision their profile lazily.
        app(VendorManager::class)->profileFor(Auth::user());
    }

    public function getTitle(): string
    {
        return __('My Products');
    }

    public static function getNavigationLabel(): string
    {
        return __('My Products');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Vendor');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('My Products'))
            ->query(
                Product::query()
                    ->filter(['author' => (int) Auth::id()])
                    ->where('is_deleted', 0)
            )
            ->columns([
                ImageColumn::make('mediaUrl')
                    ->label(__('Image'))
                    ->state(fn (Product $r) => $r->mediaUrl())
                    ->square(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->state(fn (Product $r) => currency_format($r->price)),
                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(__('Added'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Add product'))
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->form($this->productForm())
                    ->using(fn (array $data) => $this->createProduct($data)),
            ])
            ->actions([
                EditAction::make()
                    ->form($this->productForm())
                    ->fillForm(fn (Product $r) => [
                        'title' => $r->title,
                        'description' => $r->description,
                        'price' => $r->price,
                        'is_active' => (bool) $r->is_active,
                    ])
                    ->using(fn (Product $r, array $data) => $this->updateProduct($r, $data)),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading(__('No products yet'))
            ->emptyStateDescription(__('Add your first product to start selling.'))
            ->emptyStateIcon('heroicon-o-shopping-bag');
    }

    /** Shared create/edit form schema. */
    protected function productForm(): array
    {
        return [
            TextInput::make('title')
                ->label(__('Title'))
                ->required()
                ->maxLength(191),
            Textarea::make('description')
                ->label(__('Description'))
                ->rows(4),
            TextInput::make('price')
                ->label(__('Price'))
                ->numeric()
                ->minValue(0)
                ->prefix(fn () => currency_symbol() ?: '$'),
            FileUpload::make('image')
                ->label(__('Image'))
                ->image()
                ->disk('public')
                ->directory('vendor-products')
                ->visibility('public')
                ->helperText(__('Leave empty when editing to keep the current image.')),
            Toggle::make('is_active')
                ->label(__('Visible in shop'))
                ->default(true),
        ];
    }

    protected function createProduct(array $data): Product
    {
        $product = new Product();
        $product->title = (string) $data['title'];
        $product->description = (string) ($data['description'] ?? '');
        $product->content = (string) ($data['description'] ?? '');
        $product->content_type = 'product';
        $product->subtype = 'product';
        $product->created_by = (int) Auth::id();
        $product->is_active = !empty($data['is_active']) ? 1 : 0;
        $product->is_deleted = 0;
        $product->is_shop = 1;
        if (isset($data['price']) && $data['price'] !== '' && $data['price'] !== null) {
            $product->price = $data['price'];
        }
        $product->save();

        $this->attachImage($product, $data['image'] ?? null);

        return $product;
    }

    protected function updateProduct(Product $product, array $data): Product
    {
        // Only the owning vendor may edit — defence in depth on top of the
        // author-scoped table query.
        if ((int) $product->created_by !== (int) Auth::id()) {
            abort(403);
        }

        $product->title = (string) $data['title'];
        $product->description = (string) ($data['description'] ?? '');
        $product->content = (string) ($data['description'] ?? '');
        $product->is_active = !empty($data['is_active']) ? 1 : 0;
        if (isset($data['price']) && $data['price'] !== '' && $data['price'] !== null) {
            $product->price = $data['price'];
        }
        $product->save();

        // Only replace the image when a new one was uploaded.
        if (!empty($data['image'])) {
            $this->attachImage($product, $data['image']);
        }

        return $product;
    }

    /**
     * Attach (or replace) the product's primary image as a Media row so it
     * renders via $product->mediaUrl() in the shop grid.
     */
    protected function attachImage(Product $product, $path): void
    {
        if (empty($path)) {
            return;
        }

        $url = $this->normalizeUpload($path);
        if (! $url) {
            return;
        }

        $media = Media::query()
            ->where('rel_id', $product->id)
            ->where('rel_type', $product->getMorphClass())
            ->orderBy('position')
            ->first();

        if (! $media) {
            $media = new Media();
            $media->rel_id = $product->id;
            $media->rel_type = $product->getMorphClass();
            $media->media_type = 'image';
            $media->position = 1;
        }

        $media->filename = $url;
        $media->save();
    }

    protected function normalizeUpload($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (is_string($value) && preg_match('~^https?://~i', $value)) {
            return $value;
        }
        try {
            return Storage::disk('public')->url($value);
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check();
    }
}
