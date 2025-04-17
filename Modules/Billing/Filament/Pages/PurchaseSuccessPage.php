<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class PurchaseSuccessPage extends Page
{
    protected static string $view = 'modules.billing::filament.pages.purchase-success';

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Purchase Success';

    protected static ?string $title = 'Purchase Successful';

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
