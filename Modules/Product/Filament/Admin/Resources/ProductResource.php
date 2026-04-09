<?php

namespace Modules\Product\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Modules\Content\Filament\Admin\ContentResource;
use Modules\Product\Filament\Exports\ProductExporter;
use Modules\Product\Filament\Imports\ProductImporter;
use Modules\Product\Models\Product;

class ProductResource extends ContentResource
{

    protected static ?string $model = Product::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Shop';

    protected static bool $shouldRegisterNavigation = true;
    protected static ?int $navigationSort = 1;


    protected static string $contentType = 'product';
    protected static string $subType = 'product';

    public static function getPages(): array
    {
        return [
            'index' => ProductResource\Pages\ListProducts::route('/'),
            'create' => ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->headerActions([
                Tables\Actions\ActionGroup::make([
                \MicroweberPackages\Filament\Tables\Actions\ImportAction::make('importProducts')
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->importer(ProductImporter::class)
                    ->chunkSize(50),
                Tables\Actions\ExportAction::make()
                    ->exporter(ProductExporter::class)
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->form(function (Tables\Actions\ExportAction $action): array {
                        $exportColumns = ProductExporter::getColumns();
                        $schemaSchema = [];
                        foreach ($exportColumns as $column) {
                            $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                ->label($column->getLabel())
                                ->default(true);
                        }
                        $schemaSchema[] = \Filament\Forms\Components\Checkbox::make('export_multiple')
                            ->label('Export to multiple files (ZIP)');
                        return $schemaSchema;
                    })
                    ->action(function (array $data, Table $table) {
                        $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                        $exportMultiple = $data['export_multiple'] ?? false;
                        $url = route('filament.admin.product.export', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                        return redirect()->to($url);
                    }),
                ])->icon('heroicon-o-cog-6-tooth')->tooltip('Settings')->color('gray')->button()->label(''),
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ProductExporter::class)
                        ->form(function (Tables\Actions\BulkAction $action): array {
                            $exportColumns = ProductExporter::getColumns();
                            $schemaSchema = [];
                            foreach ($exportColumns as $column) {
                                $schemaSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            $schemaSchema[] = \Filament\Forms\Components\Checkbox::make('export_multiple')
                                ->label('Export to multiple files (ZIP)')
                                ->default(false);
                            return $schemaSchema;
                        })
                        ->action(function (array $data, Tables\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = implode(',', $action->getRecords()->pluck('id')->toArray());
                            $exportMultiple = $data['export_multiple'] ?? false;
                            $route = route('filament.admin.product.export', ['columns' => $selectedColumns, 'selected_ids' => $selectedRecordIds, 'export_multiple' => $exportMultiple]);
                            return redirect()->to($route);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
