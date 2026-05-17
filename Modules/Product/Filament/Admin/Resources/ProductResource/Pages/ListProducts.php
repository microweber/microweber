<?php

namespace Modules\Product\Filament\Admin\Resources\ProductResource\Pages;

use Filament\Tables\Table;
use Modules\Content\Filament\Admin\ContentResource\Pages\ListContents;
use Modules\Content\Models\Content;
use Modules\Product\Filament\Admin\Resources\ProductResource;


class ListProducts extends ListContents
{
    protected static string $resource = ProductResource::class;

    /**
     * task-2026-05-17-378d85 / AI-782 surface 2 — hide the paginator
     * chrome when the product count is ≤ 10. Mirrors the AI-736
     * (task-0b912e) Pages-list pattern. Parent ContentResource::table()
     * declares ->paginated([10, 25, 50, 100, 250, 'all']) which renders
     * the "Per page" dropdown even when only 1 product exists — visual
     * noise on starter / demo stores.
     *
     * Count is computed once per render against the Content table
     * (Product extends Content; content_type = 'product') — cheap on
     * /admin/products because the rowset is bounded for stores in the
     * 1-50 product range. For larger catalogs the paginator returns.
     */
    public function table(Table $table): Table
    {
        $table = parent::table($table);

        $productCount = Content::where('content_type', 'product')
            ->whereNull('deleted_at')
            ->where(function ($q) {
                $q->where('is_deleted', 0)->orWhereNull('is_deleted');
            })
            ->count();

        if ($productCount <= 10) {
            $table->paginated(false);
        }

        return $table;
    }
}
