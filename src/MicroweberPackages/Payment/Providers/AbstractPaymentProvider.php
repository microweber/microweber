<?php


namespace MicroweberPackages\Payment\Providers;


abstract class AbstractPaymentProvider
{
    public $name = 'Payment provider';
    public function getCredentials(): array
    {
        return [];
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function enable(): bool
    {
        return true;
    }

    public function disable(): bool
    {
        return true;
    }

    public function name(): string
    {
        return $this->name;
    }


    public function validate($data = []): array
    {
        return [];
    }

    public function process($data): array
    {
        return [];
    }


}
