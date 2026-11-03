<?php

namespace Coolsam\Modules;

abstract class StatsOverviewWidget extends \Filament\Widgets\StatsOverviewWidget
{
    use \Coolsam\Modules\Traits\CanAccessTrait;

    public static function canView(): bool
    {
        return self::canAccess();
    }
}
