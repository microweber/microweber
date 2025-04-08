<?php

namespace Modules\Billing\Services;

use Laravel\Cashier\Cashier;
use Stripe\StripeClient;
use Exception;

class StripeService
{
    /**
     * @var StripeClient
     */
    protected $stripe;

    public function __construct()
    {


        $this->stripe = Cashier::stripe();
    }

    /**
     * Fetch all Stripe products.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getProducts(array $params = [])
    {
        return $this->stripe->products->all($params);
    }

    /**
     * Fetch all Stripe prices.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getPrices(array $params = [])
    {
        return $this->stripe->prices->all($params);
    }

    /**
     * Fetch all Stripe invoices.
     *
     * @param array $params Optional parameters for the API call
     * @return \Stripe\Collection
     */
    public function getInvoices(array $params = [])
    {
        return $this->stripe->invoices->all($params);
    }

    /**
     * Test if the Stripe API connection is working.
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $account = $this->stripe->accounts->retrieve();
            return isset($account->id);
        } catch (Exception $e) {
            return false;
        }
    }
}
