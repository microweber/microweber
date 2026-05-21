@php

/*

type: layout

name: Default

description: Default - 3 Columns

*/

/* task-2026-05-17-aeb113 / AI-806 + AI-807 + AI-808 — full Page template
   rewrite (designer-authorised bundle per Round 13.2 dispatch).

   PRE-FIX (the old template was a near-verbatim copy of an old Products
   template):
     1. mw.require('shop.js') loaded unconditionally on Pages lists.
     2. data-mw-cart-add-and-checkout button rendered Pages as addable
        to cart when $item['prices'] happened to be an array.
     3. <span class="price"> + hidden form inputs (name="price",
        name="content_id") were emitted per page.
     4. .module-products-template-columns-3 CSS hook (cascades from
        .module-products-* rules into Page lists).
     5. itemtype dynamically resolved to http://schema.org/Product via
        getSchemaOrgItemTypeTag() because PageModule does not inject
        content_type='page' into options at runtime — Google was
        indexing CMS pages as Products. Hard-coded to WebPage now.
     6. Legacy raw php-print of $item fields (AI-807 XSS) -- six
        unescaped fields per the designer enumeration: title (3x:
        alt/title/h3), link (2x: href attrs), created_at, description,
        id (in data-attr), add_cart_text (option-controllable). A page
        title like "><img src=x onerror=alert(1)> injected raw HTML
        into the public list. Now: every value rendered via Blade {{ }}
        which auto-escapes via htmlspecialchars.
     7. No empty-state -- AI-808.

   SHIPPED HERE:
     - Visual shape mirrors the Posts canonical template
       (Modules/Content/resources/views/templates/default.blade.php).
     - itemtype hard-coded to https://schema.org/WebPage so this never
       drifts back to Product regardless of options carried in $params.
     - .module-pages-* CSS class prefix (no .module-products-* cascade).
     - All cart / price / shop.js code paths DROPPED entirely (not just
       gated) -- Pages never have prices, so the gated dead code was
       both confusing + a SEO leak vector.
     - Every dynamic value rendered through Blade {{ }} (auto-escaped
        via htmlspecialchars). Closes AI-807 XSS family.
     - AI-780a/AI-801 empty-state with explicit content_type='page'
       label set (no inference needed -- this template is by definition
       rendering Pages). Closes AI-808.
     - Pagination footer preserved.
     - Audit-grep marker comment on every section.

   References: AI-803 lineage for the parser-gating pattern (specific-
   scope rules MUST live at the scope they target); AI-780a/AI-801 for
   the empty-state template; AI-807/AI-808 bundled per designer email
   2026-05-17T10:10:17Z. */

@endphp

<style>
    #pages-{{ $params['id'] }} .thumbnail-image-holder {
        position: relative;
        overflow: hidden;
        height: 300px;
    }

    #pages-{{ $params['id'] }} .thumbnail-image-holder .thumbnail {
        object-fit: cover;
        width: 100%;
        height: 100%;
        min-width: calc(100% + 2px);
        min-height: 100%;
    }

    #pages-{{ $params['id'] }} .mw-pages-title a {
        color: var(--mw-heading-color);
        text-decoration: none;
    }

    #pages-{{ $params['id'] }} .page-bottom-holder {
        padding: 25px;
        padding-top: 20px;
    }

    #pages-{{ $params['id'] }} .module-pages-template-columns-3 .page-holder {
        background: var(--background);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        margin: 20px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: left;
    }

    #pages-{{ $params['id'] }} .page-list-image {
        width: 240px;
    }
</style>

@php
    $columns = get_option('columns', $params['id']);
    if (! $columns) {
        $columns = $params['data-columns'] ?? 'col-md-6 col-lg-4';
    }
    $columns_xl = get_option('columns-xl', $params['id']);
    $columns_md = get_option('columns-md', $params['id']);
    if ($columns_xl) { $columns .= ' ' . $columns_xl; }
    if ($columns_md) { $columns .= ' ' . $columns_md; }

    $count = 0;
@endphp

<div class="row module-pages-template-columns-3" id="pages-{{ $params['id'] }}">
    <div class="col-lg-12 mx-auto">
        <div class="row">
            @if (empty($data))
                {{-- task-2026-05-17-aeb113 / AI-808 -- empty-state mirror
                     of the AI-780a/AI-801 Posts pattern. Page-specific:
                     no need to infer content_type from $params['type']
                     because this template is by definition rendering
                     Pages. Wrapped in is_admin() so it only surfaces to
                     editors (AI-104 lineage). --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-ai780-content-type="page">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pages yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first page to fill this module.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ route('filament.admin.resources.pages.create') }}" aria-label="{{ __('+ Add page') }}">{{ __('+ Add page') }}</a>
                    </div>
                @endif
            @else
                @foreach ($data as $key => $item)
                    @php $count++; @endphp
                    {{-- task-2026-05-17-aeb113 / AI-806 -- itemtype hard-
                         coded to schema.org/WebPage. Pre-fix this used
                         $schema_org_item_type_tag which defaulted to
                         schema.org/Product unless options['content_type']
                         was 'page' -- PageModule never injected that
                         option, so Google indexed pages as Products. --}}
                    <div class="{{ $columns }}" data-aos="fade-up" data-aos-delay="{{ min($key, 8) * 100 }}" itemscope itemtype="https://schema.org/WebPage">
                        <div class="page-holder">
                            <a href="{{ $item['link'] }}" itemprop="url">
                                <div class="thumbnail-holder">
                                    @if (! isset($show_fields) || $show_fields == false || in_array('thumbnail', $show_fields))
                                        <div class="thumbnail-image-holder">
                                            {!! responsive_thumbnail($item['image'], 535, 285, [
                                                'class' => 'thumbnail img-fluid',
                                            ]) !!}
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="page-bottom-holder">
                                @if (! isset($show_fields) || $show_fields == false || in_array('title', $show_fields))
                                    {{-- task-2026-05-17-aeb113 / AI-807 --
                                         all dynamic values rendered via
                                         Blade {{ }} -- auto-escaped via
                                         htmlspecialchars. Closes the
                                         legacy raw-php-print-of-item
                                         XSS surface family. --}}
                                    <h3 itemprop="name" class="mw-pages-title m-0"><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></h3>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('created_at', $show_fields))
                                    <small class="text-muted" itemprop="dateCreated">{{ date_system_format($item['created_at']) }}</small>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('description', $show_fields))
                                    <p itemprop="description" class="mt-3">{{ \Illuminate\Support\Str::limit(strip_tags((string) ($item['description'] ?? '')), 150) }}</p>
                                @endif

                                @if (! isset($show_fields) || $show_fields == false || in_array('read_more', $show_fields))
                                    <a href="{{ $item['link'] }}" class="button-8 m-t-20">
                                        <span>{{ $read_more_text ?? __('Read more') }}</span>
                                    </a>
                                @endif

                                {{-- task-2026-05-17-aeb113 / AI-806 --
                                     ALL price/cart/shop.js code paths
                                     dropped entirely. Pages never have
                                     prices; the gated dead code was a
                                     copy-paste leak from an old Products
                                     template. Designer-authorised. --}}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@if (isset($pages_count) && $pages_count > 1 && isset($paging_param))
    @php $current_page = $current_page ?? 1; @endphp
    {!! paging("num={$pages_count}&paging_param={$paging_param}&current_page={$current_page}") !!}
@endif
