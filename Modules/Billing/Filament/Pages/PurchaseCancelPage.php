<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class PurchaseCancelPage extends Page
{
    protected static string $view = 'modules.billing::filament.pages.purchase-cancel';

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationLabel = 'Purchase Cancelled';

    protected static ?string $title = 'Purchase Cancelled';

    protected static bool $shouldRegisterNavigation = true;

    public function getBreadcrumb(): string
    {
        return '';
    }

    public function getTitle(): string
    {
        return '';
    }
}
