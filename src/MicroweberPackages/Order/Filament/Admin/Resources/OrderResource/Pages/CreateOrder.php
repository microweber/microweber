<?php

namespace MicroweberPackages\Order\Filament\Admin\Resources\OrderResource\Pages;

use MicroweberPackages\Order\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
