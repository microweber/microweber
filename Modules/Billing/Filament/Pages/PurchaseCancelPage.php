<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class PurchaseCancelPage extends Page
{
    protected string $view = 'modules.billing::filament.pages.purchase-cancel';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-x-circle';

    protected static string | null $navigationLabel = 'Purchase Cancelled';

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
