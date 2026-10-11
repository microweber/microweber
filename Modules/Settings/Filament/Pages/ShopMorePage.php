<?php

namespace Modules\Settings\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page;

/**
 * "More" — a small hub under the Shop navigation group that gathers the
 * secondary shop areas (Subscriptions, Payments, Customers, Invoices) as cards
 * so the main Shop sidebar stays short. URLs are resolved from each resource /
 * panel rather than hardcoded.
 */
class ShopMorePage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-ellipsis-horizontal-circle';

    protected string $view = 'modules.settings::filament.admin.pages.shop-more';

    protected static string | \UnitEnum | null $navigationGroup = 'Shop';

    protected static ?string $title = 'Shop Advanced';

    protected static ?string $navigationLabel = 'More';

    protected static ?int $navigationSort = 20;

    protected static ?string $slug = 'shop-more';

    public function getViewData(): array
    {
        $cards = [];

        // Subscriptions live in the separate admin-billing panel.
        try {
            $cards[] = [
                'title' => 'Subscriptions',
                'description' => 'Manage recurring billing plans and customer subscriptions.',
                'icon' => 'heroicon-o-credit-card',
                'url' => Filament::getPanel('admin-billing')->getUrl(),
            ];
        } catch (\Throwable $e) {
            // Billing panel unavailable — skip the Subscriptions card.
        }

        if (class_exists(\Modules\Payment\Filament\Admin\Resources\PaymentResource::class)) {
            $cards[] = [
                'title' => 'Payments',
                'description' => 'View and record payments and transactions.',
                'icon' => 'heroicon-o-banknotes',
                'url' => \Modules\Payment\Filament\Admin\Resources\PaymentResource::getUrl('index'),
            ];
        }

        if (class_exists(\Modules\Customer\Filament\CustomerResource::class)) {
            $cards[] = [
                'title' => 'Customers',
                'description' => 'Manage your customers and their details.',
                'icon' => 'heroicon-o-user-group',
                'url' => \Modules\Customer\Filament\CustomerResource::getUrl('index'),
            ];
        }

        if (class_exists(\Modules\Invoice\Filament\Resources\InvoiceResource::class)) {
            $cards[] = [
                'title' => 'Invoices',
                'description' => 'Create and manage customer invoices.',
                'icon' => 'heroicon-o-document-currency-dollar',
                'url' => \Modules\Invoice\Filament\Resources\InvoiceResource::getUrl('index'),
            ];
        }

        return [
            'cards' => $cards,
        ];
    }
}
