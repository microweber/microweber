<?php

namespace Modules\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * task-2026-05-17-66a21a / AI-849 — frontend /shop route handler.
 *
 * Stage-3 propagation-without-renderer-update closure, 4th instance in the
 * silent-stub family (sibling lineage AI-755 → AI-795 → AI-837 → this).
 * FIRST instance on the primary commerce surface — every prior instance
 * was on an auxiliary route. P1 severity per designer because /shop is
 * the canonical Google Shopping / social product card landing.
 *
 * Pre-fix: `/shop`, `/shop/products`, `/shop/category`, `/shop/featured`,
 * `/shop/categories` all had no registered route. They fell through to
 * the FrontendController catch-all which detected "shop" as an installed
 * module name, set $page['content'] = '<module type="shop" />' and
 * $page['layout_file'] = 'clean.php', then rendered Templates/Bootstrap
 * clean.blade.php which carries hardcoded fixture markup ("Describe your
 * company", "Call to action", "The Feature Title", "Your Story Should
 * Evolve Over Time", "Pictures In The Sky", "$79 iWork '08", etc.) at
 * HTTP 200 OK with no noindex header. Designer's Round 16 silent-stub
 * probe surfaced 5 fixture-text instances per subroute × 5 subroutes =
 * 25 visible stub-leak instances on the primary commerce nav target.
 * SEO indexing risk + commerce conversion impact + bounce-rate spike.
 *
 * Fix shape per the AI-795 standing chrome-application checklist for
 * URL fall-through paths (AI-849 Slice A — minimum-viable chrome to
 * close the stub-renderer defect; AI-849b follow-up: wire ShopComponent
 * Livewire product grid for real product listings inside the chrome):
 *   (1) extends active template master with Bootstrap fallback per the
 *       AI-757 pattern;
 *   (2) semantic chrome container (.mw-frontend-shop);
 *   (3) recovery / empty-state context (cart link + return home + future
 *       hook-point for the ShopComponent grid via <module type="shop" />);
 *   (4) correct HTTP response code (200 OK — /shop is a legitimate
 *       commerce surface, not an error) + noindex headers ONLY when no
 *       products exist (commerce surfaces with real catalogue MUST be
 *       indexable for SEO);
 *   (5) pinned by Shop66a21aAI849ShopStubElimContractTest.
 *
 * Subroute coverage: the controller's index() action handles /shop AND
 * /shop/{path} via the wildcard route at Modules/Shop/routes/web.php.
 * The optional {path} captures /products, /category, /featured,
 * /categories, and any future subpath that needs the same chrome.
 *
 * Why one route handler for the whole subtree: per designer's dispatch,
 * all 5 subroutes resolve to the SAME renderer pre-fix (FrontendController
 * catch-all → clean.blade.php). The fix replaces ONE fallthrough with ONE
 * dedicated chrome surface; per-subroute differentiation (real category
 * filters / featured-only filter / etc.) is AI-849b follow-up scope.
 */
class ShopController extends Controller
{
    public function index(Request $request, ?string $path = null)
    {
        $extendsView = $this->resolveExtendsView();
        $hasProducts = $this->hasAnyProduct();
        $subroute = is_string($path) && $path !== '' ? trim($path, '/') : null;

        $headers = [
            'X-Fallback-Message' => 'shop-landing',
        ];

        // Empty catalogue = noindex. Real catalogue = indexable per
        // commerce-SEO intent. Designer's spec: "noindex when empty".
        if (! $hasProducts) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }

        return response()
            ->view('frontend.shop.index', [
                'extendsView' => $extendsView,
                'hasProducts' => $hasProducts,
                'subroute' => $subroute,
            ])
            ->withHeaders($headers);
    }

    /**
     * AI-757 active-template-master with Bootstrap fallback. Mirrors
     * the pattern used by AI-794 auth layout + AI-795 frontend 404
     * renderer + AI-837 search results renderer. If the active template
     * ships its own layouts.master view, extend that; otherwise fall back
     * to the Bootstrap shipped template.
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

    /**
     * Lightweight catalogue presence check. Returns true if the content
     * table has at least one product row (any subtype, any state). Used
     * to gate the noindex header — commerce surfaces with real products
     * should be indexable, empty ones should not.
     *
     * Wrapped in try/catch so missing migrations / first-boot install
     * states default to "no products" rather than crashing the route.
     */
    protected function hasAnyProduct(): bool
    {
        try {
            return \Illuminate\Support\Facades\DB::table('content')
                ->where('content_type', 'product')
                ->where('is_deleted', 0)
                ->exists();
        } catch (\Throwable $e) {
            return false;
        }
    }
}
