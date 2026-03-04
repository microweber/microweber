<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class SubscriptionSuccessPage extends Page
{
    protected string $view = 'modules.billing::filament.pages.subscription-success';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-check-circle';

    protected static string | null $navigationLabel = 'Subscription Success';

    protected static ?string $title = 'Subscription Successful';

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
