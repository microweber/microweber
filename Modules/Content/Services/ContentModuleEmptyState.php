<?php

declare(strict_types=1);

namespace Modules\Content\Services;

/**
 * task-2026-06-07-pmprod
 *
 * Resolves the admin / Live-Edit empty-state view-model for a content
 * LIST module (posts, pages, products, or generic content).
 *
 * This logic used to be copy-pasted as a ~50-line `@php` block inside six
 * Content list templates (default, skin-1, masonry, search, sidebar,
 * dictionary). Each copy drifted independently — some had a product
 * branch, some didn't; some had the re-scope secondary link, most didn't;
 * the product type never resolved at all because ProductsModule::$module
 * is the path-namespaced 'shop/products', not 'products'. Centralising it
 * here (logic in PHP, markup in one shared partial) is the single source
 * of truth the templates call via:
 *
 *     @include('modules.content::partials.module-empty-state', ['params' => $params])
 *
 * Lineage: AI-780 (module-type-aware empty state) → AI-801 (infer type
 * from $params['type'] when content_type is absent) → AI-753 (post copy +
 * re-scope secondary link) → this extraction + product parity.
 */
class ContentModuleEmptyState
{
    /**
     * Map a raw module `type` attribute (as the parser populates it from
     * `<module type="...">`, see ParserLoadModuleTrait) to a content-type
     * slug. ProductsModule registers as 'shop/products', so both the bare
     * 'products' and the namespaced 'shop/products' map to 'product'.
     *
     * @var array<string, string>
     */
    private const TYPE_MAP = [
        'posts'         => 'post',
        'pages'         => 'page',
        'products'      => 'product',
        'shop/products' => 'product',
    ];

    /**
     * @param array<string, mixed> $params the module $params bag (carries
     *                                      content_type, type, id)
     * @return array{
     *     type: ?string,
     *     title: string,
     *     body: string,
     *     ctaLabel: string,
     *     ctaHref: string,
     *     showSecondary: bool,
     *     secondaryLabel: ?string,
     *     secondaryAria: ?string
     * }
     */
    public static function resolve(array $params): array
    {
        $type = self::resolveType($params);
        $copy = self::copyFor($type);

        $showSecondary = in_array($type, ['post', 'product'], true) && is_live_edit();

        return [
            'type'           => $type,
            'title'          => $copy['title'],
            'body'           => $copy['body'],
            'ctaLabel'       => $copy['ctaLabel'],
            'ctaHref'        => $copy['ctaHref'],
            'showSecondary'  => $showSecondary,
            'secondaryLabel' => $showSecondary ? self::secondaryLabelFor($type) : null,
            'secondaryAria'  => $showSecondary ? self::secondaryAriaFor($type) : null,
        ];
    }

    /**
     * Prefer the explicit content_type; otherwise infer from the raw
     * module type attribute. Returns null for unknown types so the caller
     * renders the generic content fallback.
     *
     * @param array<string, mixed> $params
     */
    public static function resolveType(array $params): ?string
    {
        $type = $params['content_type'] ?? null;

        if (! $type) {
            $type = self::TYPE_MAP[$params['type'] ?? ''] ?? null;
        }

        return $type !== null ? (string) $type : null;
    }

    /**
     * @return array{title: string, body: string, ctaLabel: string, ctaHref: string}
     */
    private static function copyFor(?string $type): array
    {
        // Microweber's _e($str, true) is used (not Laravel __()) for every
        // user-visible string: Laravel's __() returns an EMPTY string for any
        // key ending in '.', because it treats the trailing dot as a
        // translation-namespace separator (the AI-796 trailing-period footgun).
        // Several bodies below end in '.', so __() would silently blank them.
        switch ($type) {
            case 'post':
                return [
                    'title'    => _e('No posts yet', true),
                    'body'     => _e('Articles, news, and updates you publish appear here.', true),
                    'ctaLabel' => _e('Write your first post →', true),
                    'ctaHref'  => route('filament.admin.resources.posts.create'),
                ];
            case 'page':
                return [
                    'title'    => _e('No pages yet', true),
                    'body'     => _e('Add your first page to fill this module.', true),
                    'ctaLabel' => _e('+ Add page', true),
                    'ctaHref'  => route('filament.admin.resources.pages.create'),
                ];
            case 'product':
                return [
                    'title'    => _e('No products yet', true),
                    'body'     => _e('Products you add to your store appear here.', true),
                    'ctaLabel' => _e('Add your first product →', true),
                    'ctaHref'  => route('filament.admin.resources.products.create'),
                ];
            default:
                return [
                    'title'    => _e('No content yet', true),
                    'body'     => _e('Add your first item to fill this module.', true),
                    'ctaLabel' => _e('+ Add content', true),
                    'ctaHref'  => route('filament.admin.resources.contents.create'),
                ];
        }
    }

    public static function secondaryLabelFor(?string $type): string
    {
        return $type === 'product'
            ? _e('Or change which products this module shows →', true)
            : _e('Or change which posts this module shows →', true);
    }

    public static function secondaryAriaFor(?string $type): string
    {
        return $type === 'product'
            ? _e('Change which products this module shows', true)
            : _e('Change which posts this module shows', true);
    }
}
