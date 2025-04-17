<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class SubscriptionSuccessPage extends Page
{
    protected static string $view = 'modules.billing::filament.pages.subscription-success';

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Subscription Success';

    protected static ?string $title = 'Subscription Successful';

    protected static bool $shouldRegisterNavigation = false;


    public function getBreadcrumb(): string
    {
        return '';
    }
}
