<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Pages\Page;

class SubscriptionCancelPage extends Page
{
    protected static string $view = 'modules.billing::filament.pages.subscription-cancel';

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';

    protected static ?string $navigationLabel = 'Subscription Cancelled';

    protected static ?string $title = 'Subscription Cancelled';

    public function getBreadcrumb(): string
    {
        return '';
    }
}
