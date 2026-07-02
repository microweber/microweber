<?php

namespace Modules\Product\Filament\Admin;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Support\Enums\Width as MaxWidth;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use Modules\Product\Models\ProductVariantAttribute;
use Modules\Product\Models\ProductVariantCombination;
use Modules\Product\Services\ProductVariantService;

class ProductVariantManager extends AdminComponent implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithActions;

    public int $productId = 0;
    public array $selectedAttributeIds = [];

    public function mount(): void
    {
        parent::mount();

        $this->loadSelectedAttributes();
    }

    protected function loadSelectedAttributes(): void
    {
        if ($this->productId <= 0) {
            return;
        }

        $combinations = ProductVariantCombination::where('product_id', $this->productId)
            ->with('attributeValues.attribute')
            ->get();

        $attributeIds = $combinations
            ->flatMap(fn($c) => $c->attributeValues->pluck('attribute_id'))
            ->unique()
            ->values()
            ->toArray();

        $this->selectedAttributeIds = $attributeIds;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariantCombination::query()
                    ->where('product_id', $this->productId)
                    ->with('attributeValues.attribute')
            )
            ->columns($this->getTableColumns())
            ->actions([
                EditAction::make()
                    ->modalWidth(MaxWidth::Large)
                    ->form($this->getCombinationFormSchema()),
                DeleteAction::make()
                    ->after(function () {
                        $this->resetTable();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No variants yet')
            ->emptyStateDescription('Select attributes above and generate variant combinations.')
            ->defaultSort('id', 'asc')
            ->paginated(false);
    }

    protected function getTableColumns(): array
    {
        $columns = [
            TextColumn::make('variant_label')
                ->label('Variant')
                ->state(function (ProductVariantCombination $record): string {
                    return $record->attributeValues
                        ->map(fn($v) => $v->attribute->name . ': ' . $v->value)
                        ->join(' / ');
                })
                ->weight('bold')
                ->wrap(),

            TextColumn::make('sku')
                ->label('SKU')
                ->placeholder('—'),

            TextColumn::make('price')
                ->label('Price')
                ->money(currency_code() ?: 'USD')
                ->placeholder('Default'),

            TextColumn::make('quantity')
                ->label('Stock')
                ->formatStateUsing(function (ProductVariantCombination $record): string {
                    if (!$record->track_quantity || $record->quantity_type === 'unlimited') {
                        return '∞';
                    }
                    return (string) $record->quantity;
                }),

            IconColumn::make('is_default')
                ->label('Default')
                ->boolean(),

            IconColumn::make('is_active')
                ->label('Active')
                ->boolean(),
        ];

        return $columns;
    }

    protected function getCombinationFormSchema(): array
    {
        return [
            Section::make('Pricing')
                ->schema([
                    TextInput::make('price')
                        ->numeric()
                        ->rules(['nullable', 'regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        ->helperText('Leave empty to use the product default price.'),

                    TextInput::make('compare_price')
                        ->label('Compare at Price')
                        ->numeric()
                        ->rules(['nullable', 'regex:/^\d{1,6}(\.\d{0,2})?$/']),

                    TextInput::make('cost_price')
                        ->label('Cost Price')
                        ->numeric()
                        ->rules(['nullable', 'regex:/^\d{1,6}(\.\d{0,2})?$/']),
                ])
                ->columns(3),

            Section::make('Inventory')
                ->schema([
                    TextInput::make('sku')
                        ->label('SKU')
                        ->maxLength(255),

                    TextInput::make('barcode')
                        ->maxLength(255),

                    Toggle::make('track_quantity')
                        ->label('Track Quantity')
                        ->live()
                        ->default(true),

                    Group::make([
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(0),

                        TextInput::make('low_stock_threshold')
                            ->numeric()
                            ->label('Low Stock Threshold')
                            ->helperText('Alert when stock falls below this.')
                            ->default(10),

                        Toggle::make('allow_backorders')
                            ->label('Allow Backorders')
                            ->default(false),
                    ])->visible(fn($get) => $get('track_quantity')),
                ])
                ->columns(2),

            Section::make('Shipping')
                ->schema([
                    TextInput::make('weight')
                        ->numeric()
                        ->rules(['nullable', 'regex:/^\d{1,6}(\.\d{0,2})?$/'])
                        ->suffix('kg'),
                ]),

            Section::make('Status')
                ->schema([
                    Toggle::make('is_default')
                        ->label('Default Variant')
                        ->helperText('The default variant is pre-selected on the product page.'),

                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->columns(2),
        ];
    }

    public function getAvailableAttributes(): array
    {
        return ProductVariantAttribute::where('is_active', true)
            ->orderBy('position')
            ->with('activeValues')
            ->get()
            ->mapWithKeys(fn($attr) => [
                $attr->id => $attr->name . ' (' . $attr->activeValues->count() . ' values)',
            ])
            ->toArray();
    }

    public function generateCombinations(): void
    {
        if ($this->productId <= 0) {
            return;
        }

        if (empty($this->selectedAttributeIds)) {
            return;
        }

        $service = app(ProductVariantService::class);
        $product = \Modules\Content\Models\Content::find($this->productId);

        if (!$product) {
            return;
        }

        $service->generateVariantCombinations($product, $this->selectedAttributeIds);

        $this->resetTable();
    }

    public function removeOrphanedCombinations(): void
    {
        if ($this->productId <= 0) {
            return;
        }

        $combinations = ProductVariantCombination::where('product_id', $this->productId)
            ->with('attributeValues')
            ->get();

        foreach ($combinations as $combination) {
            $attrIds = $combination->attributeValues->pluck('attribute_id')->unique()->toArray();
            $allSelected = !array_diff($attrIds, $this->selectedAttributeIds);

            if (!$allSelected) {
                $combination->attributeValues()->detach();
                $combination->delete();
            }
        }
    }

    public function updatedSelectedAttributeIds(): void
    {
        $this->removeOrphanedCombinations();
    }

    public function render(): View
    {
        return view('modules.product::filament.admin.product-variant-manager');
    }
}
