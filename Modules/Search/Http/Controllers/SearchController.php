<?php

namespace Modules\Search\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * task-2026-05-17-3e91f4 / AI-837 — frontend /search route handler.
 *
 * Stage-3 propagation-without-renderer-update closure (sibling lineage:
 * AI-735 admin route propagation, AI-793 admin 404 chrome, AI-795
 * frontend 404 chrome, AI-735b explicit follow-up flagged in
 * src/MicroweberPackages/Frontend/routes/web.php lines 36-39).
 *
 * Pre-fix: /search?q=hello had no registered route. It fell through to
 * the FrontendController catch-all which detected "search" as an
 * installed module name, set $page['content'] = '<module type="search"
 * />' and $page['layout_file'] = 'clean.php', then rendered
 * Templates/Bootstrap/resources/views/clean.blade.php which carries
 * hardcoded "My title / My text content" static markup at HTTP 200
 * with no noindex header. Designer's Round 13 audit caught this as a
 * High-priority SEO indexing risk + impression-failure on a public
 * search surface.
 *
 * Fix shape per the AI-795 standing chrome-application checklist for
 * URL fall-through paths: (1) extends active template master with
 * Bootstrap fallback per AI-757; (2) semantic chrome container
 * (.mw-frontend-search-results); (3) recovery / empty-state context;
 * (4) correct HTTP response code (200 OK — search is a legitimate
 * surface, not an error) + noindex headers (search-result URLs MUST
 * NOT be indexed); (5) pinned by contract test.
 */
class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Accept both `q` (designer-named convention) and `keyword`
        // (back-compat with the existing SearchComponent Livewire
        // mount() at Modules/Search/Livewire/SearchComponent.php:36).
        $query = trim((string) ($request->query('q', '')
            ?: $request->query('keyword', '')));

        return response()
            ->view('frontend.search.results', [
                'extendsView' => $this->resolveExtendsView(),
                'searchQuery' => $query,
            ])
            ->withHeaders([
                'X-Robots-Tag' => 'noindex, nofollow',
                'X-Fallback-Message' => 'search-results',
            ]);
    }

    /**
     * AI-757 active-template-master with Bootstrap fallback. Mirrors
     * the pattern used by AI-794 auth layout + AI-795 frontend 404
     * renderer. If the active template ships its own layouts.master
     * view, extend that; otherwise fall back to the Bootstrap shipped
     * template.
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
