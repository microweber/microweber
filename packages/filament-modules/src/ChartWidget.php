<?php

namespace Coolsam\Modules;

abstract class ChartWidget extends \Filament\Widgets\ChartWidget
{
    use \Coolsam\Modules\Traits\CanAccessTrait;

    public static function canView(): bool
    {
        return self::canAccess();
    }
}
