<?php

namespace Modules\Vendors\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Models\Product;
use Modules\Vendors\Models\Vendor;

/**
 * Public vendor storefronts.
 *
 * Follows the AI-795 / AI-849 chrome-application checklist for frontend
 * fall-through surfaces: extend the active template master (Bootstrap
 * fallback), wrap a semantic container, give a recovery CTA, and emit
 * correct status + robots headers per state (404 for a missing/unapproved
 * vendor, noindex when a shop has no products).
 */
class VendorController extends Controller
{
    /** Directory of approved vendors. */
    public function index(Request $request)
    {
        $extendsView = $this->resolveExtendsView();

        $vendors = Vendor::query()
            ->where('is_approved', true)
            ->orderBy('shop_name')
            ->get();

        $headers = ['X-Fallback-Message' => 'vendors-directory'];
        if ($vendors->isEmpty()) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        return response()
            ->view('modules.vendors::frontend.vendors.index', [
                'extendsView' => $extendsView,
                'vendors' => $vendors,
            ])
            ->withHeaders($headers);
    }

    /** A single vendor's public shop page. */
    public function show(Request $request, string $slug)
    {
        $extendsView = $this->resolveExtendsView();

        $vendor = Vendor::query()
            ->where('slug', $slug)
            ->where('is_approved', true)
            ->first();

        if (! $vendor) {
            // Unknown or not-yet-approved vendor: a real 404 with chrome,
            // not a silent catch-all stub.
            return response()
                ->view('modules.vendors::frontend.vendors.missing', [
                    'extendsView' => $extendsView,
                ], 404)
                ->withHeaders([
                    'X-Robots-Tag' => 'noindex, nofollow',
                    'X-Fallback-Message' => 'vendor-not-found',
                ]);
        }

        $products = Product::query()
            ->filter(['author' => $vendor->user_id])
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = ['X-Fallback-Message' => 'vendor-shop'];
        if ($products->isEmpty()) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        return response()
            ->view('modules.vendors::frontend.vendors.show', [
                'extendsView' => $extendsView,
                'vendor' => $vendor,
                'products' => $products,
            ])
            ->withHeaders($headers);
    }

    /**
     * AI-757 active-template-master with Bootstrap fallback. If the active
     * template ships its own layouts.master view, extend that; otherwise
     * fall back to the Bootstrap shipped template.
     */
    protected function resolveExtendsView(): string
    {
        $activeTemplate = (string) (get_option('current_template', 'template') ?? '');
        if ($activeTemplate !== '') {
            $candidate = 'templates.' . strtolower($activeTemplate) . '::layouts.master';
            if (view()->exists($candidate)) {
                return $candidate;
            }
        }
        return 'templates.bootstrap::layouts.master';
    }
}
