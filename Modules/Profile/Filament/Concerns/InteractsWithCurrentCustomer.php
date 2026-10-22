<?php

namespace Modules\Profile\Filament\Concerns;

use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;
use Modules\Order\Models\Order;

/**
 * Resolves the signed-in user to their Customer and order ids — the common
 * linkage the buyer-facing profile pages (orders, invoices, payments) share.
 * Mirrors OrderHistory's resolution: by Customer.user_id, falling back to email.
 */
trait InteractsWithCurrentCustomer
{
    protected function currentCustomerId(): ?int
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        $customer = Customer::where('user_id', $user->id)->first();
        if ($customer) {
            return (int) $customer->id;
        }

        $byEmail = Customer::where('email', $user->email)->value('id');
        return $byEmail ? (int) $byEmail : null;
    }

    /** @return array<int,int> the current user's order ids */
    protected function currentCustomerOrderIds(): array
    {
        $customerId = $this->currentCustomerId();
        if (! $customerId) {
            return [];
        }

        return Order::query()
            ->where('customer_id', $customerId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}
