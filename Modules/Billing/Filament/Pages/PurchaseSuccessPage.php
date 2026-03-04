<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class PurchaseSuccessPage extends Page
{
    protected string $view = 'modules.billing::filament.pages.purchase-success';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-check-circle';

    protected static string | null $navigationLabel = 'Purchase Success';

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
