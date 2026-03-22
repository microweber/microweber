<?php

namespace Modules\Currency\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CurrencyChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The new currency code.
     *
     * @var string
     */
    public string $newCurrency;

    /**
     * The previous currency code.
     *
     * @var string
     */
    public string $oldCurrency;

    /**
     * Create a new event instance.
     *
     * @param string $newCurrency
     * @param string $oldCurrency
     */
    public function __construct(string $newCurrency, string $oldCurrency)
    {
        $this->newCurrency = $newCurrency;
        $this->oldCurrency = $oldCurrency;
    }
}
