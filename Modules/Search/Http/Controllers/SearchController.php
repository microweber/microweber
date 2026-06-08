<?php

namespace Modules\Search\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Content\Models\Content;

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

        // task-2026-06-08-srchwire / AI-837b — wire the actual search.
        // Pre-fix the controller passed only $searchQuery and the view
        // hardcoded "No matching pages or products were found.", so /search
        // ALWAYS reported zero results even when matching content existed
        // (AI-837 Slice A shipped the chrome; this closes the deferred
        // live-results follow-up). Empty query → empty collection, so the
        // view renders the "type a term" prompt instead of a results list.
        $results = $query === '' ? new Collection() : $this->search($query);

        return response()
            ->view('frontend.search.results', [
                'extendsView' => $this->resolveExtendsView(),
                'searchQuery' => $query,
                'searchResults' => $results,
            ])
            ->withHeaders([
                'X-Robots-Tag' => 'noindex, nofollow',
                'X-Fallback-Message' => 'search-results',
            ]);
    }

    /**
     * Search active, non-deleted pages / posts / products by title,
     * content, body and description. Title matches rank first. Returns a
     * lightweight view-model collection (id, title, type, link, excerpt)
     * so the Blade view stays presentation-only.
     *
     * The keyword is bound as a parameter (no SQL injection); wildcard
     * characters in the term simply broaden the LIKE match.
     *
     * @return Collection<int, array{id:int, title:string, type:string, link:string, excerpt:string}>
     */
    protected function search(string $query): Collection
    {
        $like = '%' . $query . '%';

        return Content::query()
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->whereNull('is_deleted')->orWhere('is_deleted', 0);
            })
            ->whereIn('content_type', ['page', 'post', 'product'])
            ->where(function ($q) use ($like) {
                $q->where('title', 'like', $like)
                    ->orWhere('content', 'like', $like)
                    ->orWhere('content_body', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderByRaw('CASE WHEN title LIKE ? THEN 0 ELSE 1 END', [$like])
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get(['id', 'title', 'content_type', 'description'])
            ->map(function ($content) {
                $link = function_exists('content_link') ? (string) content_link($content->id) : (string) $content->id;
                if ($link !== '' && ! preg_match('#^https?://#i', $link)) {
                    $link = function_exists('site_url') ? (string) site_url($link) : '/' . ltrim($link, '/');
                }

                return [
                    'id' => (int) $content->id,
                    'title' => (string) $content->title,
                    'type' => (string) $content->content_type,
                    'link' => $link,
                    'excerpt' => Str::limit(trim(strip_tags((string) $content->description)), 160),
                ];
            });
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
