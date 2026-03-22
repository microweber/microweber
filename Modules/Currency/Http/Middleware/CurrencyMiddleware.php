<?php

namespace Modules\Currency\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Currency\Services\CurrencyManager;

class CurrencyMiddleware
{
    /**
     * @var CurrencyManager
     */
    protected CurrencyManager $currencyManager;

    /**
     * Constructor.
     *
     * @param CurrencyManager $currencyManager
     */
    public function __construct(CurrencyManager $currencyManager)
    {
        $this->currencyManager = $currencyManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for currency parameter in URL
        if ($request->has('currency')) {
            $currencyCode = $request->input('currency');
            if ($this->currencyManager->setCurrency($currencyCode)) {
                // Redirect to remove the currency parameter from URL
                return redirect($request->url());
            }
        }

        // Auto-detect currency if multi-currency is enabled and no currency is set
        if ($this->currencyManager->isMultiCurrencyEnabled()) {
            $currentSession = session('selected_currency');
            if (!$currentSession) {
                $detected = $this->currencyManager->autoDetectCurrency();
                if ($detected) {
                    $this->currencyManager->setCurrency($detected);
                }
            }
        }

        // Share current currency with views
        view()->share('currentCurrency', $this->currencyManager->getCurrentCurrency());
        view()->share('currentCurrencyCode', $this->currencyManager->getCurrentCurrencyCode());

        return $next($request);
    }
}
