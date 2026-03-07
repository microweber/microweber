<?php

namespace Coolsam\Modules;

abstract class TableWidget extends \Filament\Widgets\TableWidget
{
    use \Coolsam\Modules\Traits\CanAccessTrait;

    public static function canView(): bool
    {
        return self::canAccess();
    }
}
