<?php

declare(strict_types=1);

namespace MicroweberPackages\DbExport\Facades;

use Illuminate\Support\Facades\Facade;
use MicroweberPackages\DbExport\DbExportManager;

/**
 * @method static array copy(string $from, string $to, array $tables = [], ?callable $onTable = null)
 * @method static void exportToJson(string $path, ?string $connection = null, array $tables = [], ?callable $onTable = null)
 * @method static void importFromJson(string $path, ?string $connection = null, ?callable $onTable = null)
 * @method static array getTableContent(string $table, ?string $connection = null)
 * @method static static setChunkSize(int $size)
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