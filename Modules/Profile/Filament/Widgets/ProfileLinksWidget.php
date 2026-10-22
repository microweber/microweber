<?php

namespace Modules\Profile\Filament\Widgets;

use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Modules\Profile\Filament\Pages\EditProfile;
use Modules\Profile\Filament\Pages\MyCompany;
use Modules\Profile\Filament\Pages\MyInvoices;
use Modules\Profile\Filament\Pages\MyPayments;
use Modules\Profile\Filament\Pages\OrderHistory;
use Modules\Profile\Filament\Pages\SavedAddresses;

/**
 * Fills the otherwise-empty profile dashboard with a card hub linking to every
 * profile section. Cards mirror the sidebar exactly: each candidate page is
 * only shown when its own shouldRegisterNavigation() allows it (so the
 * per-user conditional Invoices/Payments/Company stay in sync), and reuses the
 * page's own nav label + icon. Styled with the shared .mw-settings-hub classes
 * so it matches the admin "Shop → More" hub.
 */
class ProfileLinksWidget extends Widget
{
    protected string $view = 'modules.profile::filament.widgets.profile-links';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -3;

    protected function getViewData(): array
    {
        $candidates = [
            __('Profile') => [
                EditProfile::class => __('Update your name, photo, email and password.'),
            ],
            __('Orders & Billing') => [
                OrderHistory::class => __('Review your past orders and their details.'),
                \Modules\DigitalProduct\Filament\Pages\MyDownloads::class => __('Download the digital products you have bought.'),
                SavedAddresses::class => __('Manage your shipping and billing addresses.'),
                MyInvoices::class => __('View and download your invoices.'),
                MyPayments::class => __('See your payment history.'),
                MyCompany::class => __('Edit your company billing details.'),
            ],
        ];

        $groups = [];

        foreach ($candidates as $groupTitle => $pages) {
            $items = [];

            foreach ($pages as $class => $description) {
                try {
                    if (! class_exists($class) || ! $class::shouldRegisterNavigation()) {
                        continue;
                    }

                    $icon = $class::getNavigationIcon();

                    $items[] = [
                        'title' => $class::getNavigationLabel(),
                        'description' => $description,
                        'icon' => is_string($icon) ? $icon : null,
                        'url' => $class::getUrl(),
                    ];
                } catch (\Throwable $e) {
                    // A page that can't resolve a URL in this panel is skipped.
                }
            }

            if (! empty($items)) {
                $groups[] = ['title' => $groupTitle, 'items' => $items];
            }
        }

        // Vendor shortcut — only for users who hold the vendor role.
        try {
            $vendorManager = \Modules\Vendors\Services\VendorManager::class;
            if (class_exists($vendorManager) && app($vendorManager)->isVendor(Auth::user())) {
                $groups[] = [
                    'title' => __('Vendor'),
                    'items' => [[
                        'title' => __('Vendor dashboard'),
                        'description' => __('Manage your shop, products and orders.'),
                        'icon' => 'heroicon-o-building-storefront',
                        'url' => Filament::getPanel('vendor')->getUrl(),
                    ]],
                ];
            }
        } catch (\Throwable $e) {
            // Vendors module absent or panel unavailable — no vendor card.
        }

        return ['groups' => $groups];
    }
}
