<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class SubscriptionCancelPage extends Page
{
    protected string $view = 'modules.billing::filament.pages.subscription-cancel';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-x-circle';

    protected static string | null $navigationLabel = 'Subscription Cancelled';

    protected static ?string $title = 'Subscription Cancelled';

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
