<?php

namespace Modules\Payment\Drivers;

use Modules\Payment\Models\PaymentProvider;

abstract class AbstractPaymentMethod
{
    public string $provider = '';
    public PaymentProvider $model; // must be set by the driver with setModel

    public function title(): string
    {
        return 'PaymentMethod';
    }


    public function getModel(): PaymentProvider|null
    {
        return $this->model ?? null;
    }

    public function setModel(PaymentProvider $model)
    {
        return $this->model = $model;
    }


    public function process($data = [])
    {

        return [];
    }

    public function getSettingsForm(): array
    {
        return [];
    }

    public function getForm(): array
    {
        return [];
    }

    public function verifyPayment(array $data): array
    {
        return [];
    }

    /**
     * The ISO-4217 currency codes this provider can charge in. An empty array
     * means "no restriction" (the provider accepts any currency — e.g. Stripe
     * supports 135+). Drivers with a real restriction override this.
     *
     * @return array<int, string>
     */
    public function supportedCurrencies(): array
    {
        return [];
    }

    /**
     * Whether this provider can charge in the given currency. True when the
     * provider declares no restriction, or the code is in its supported list.
     */
    public function supportsCurrency(string $currencyCode): bool
    {
        $supported = $this->supportedCurrencies();
        if (empty($supported)) {
            return true;
        }

        return in_array(strtoupper($currencyCode), array_map('strtoupper', $supported), true);
    }


}
