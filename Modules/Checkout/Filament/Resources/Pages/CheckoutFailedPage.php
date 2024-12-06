<?php

namespace Modules\Checkout\Filament\Resources\Pages;

use Filament\Resources\Pages\Page;
use Modules\Checkout\Filament\Resources\CheckoutResource;

class CheckoutFailedPage extends Page
{
    protected static string $resource = CheckoutResource::class;

    protected static string $view = 'modules.checkout::filament.pages.checkout-failed';
    public function getBreadcrumb(): string
    {
        return '';
    }
    public function getTitle(): string
    {
        return 'Failed';
    }
    public function mount(): void
    {
        // Keep checkout session data for retry
    }
}
