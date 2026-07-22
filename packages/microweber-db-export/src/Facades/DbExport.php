<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\DbExport\DbExportManager;
use MicroweberPackages\DbExport\ExportFilter;

/**
 * @method static array<string, int> copy(string $from, string $to, list<string> $tables = [], ?callable $onTable = null)
 * @method static void exportToJson(string $path, ?string $connection = null, list<string> $tables = [], ?callable $onTable = null)
 * @method static void importFromJson(string $path, ?string $connection = null, ?callable $onTable = null)
 * @method static array<int, array<string, mixed>> getTableContent(string $table, ?string $connection = null, list<int> $ids = [])
 * @method static static setChunkSize(int $size)
 * @method static ExportFilter filter()
 * @method static static setFilter(ExportFilter $filter)
 *
 * @see DbExportManager
 */
class DbExport extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DbExportManager::class;
    }
}