<?php

declare(strict_types=1);

namespace Modules\Backup\Console\Commands;

use Illuminate\Console\Command;
use Modules\Category\Models\Category;
use Modules\Content\Models\Content;
use Modules\Media\Models\Media;
use Modules\Product\Models\Product;

/**
 * Cycle-159 (2026-05-10) — Shop demo seeder for mobile-audit testing.
 *
 * Creates a "Demo Shop" category, 6 demo products with picsum.photos
 * placeholder images + realistic titles + prices + descriptions, and
 * ensures a Page exists at slug `shop` with `<module type="shop"/>`
 * embedded so `/shop` renders the populated product grid.
 *
 * PM blocker on AI-171 (Big2 Ecommerce layout audit) — agent-test
 * needs a populated shop surface to verify mobile layout at 390x844.
 *
 * Idempotent: re-runs delete prior demo products (matched by
 * `subtype_value='demo-shop-seed'` marker) and recreate them. The
 * category is reused if present, recreated if not. The /shop Page is
 * upserted by slug.
 *
 * Usage:
 *   php artisan mw:shop-demo-seed
 *   php artisan mw:shop-demo-seed --count=12
 *   php artisan mw:shop-demo-seed --slug=shop
 *   php artisan mw:shop-demo-seed --no-images   # skip picsum image attach
 */
class ShopDemoSeedCommand extends Command
{
    protected $signature = 'mw:shop-demo-seed
        {--count=6 : Number of products to create (default 6)}
        {--slug=shop : Shop page slug}
        {--category=demo-shop : Category slug}
        {--no-images : Skip picsum.photos image attach}
        {--with-cart=2 : Seed N items into the cart (CLI session — see reminder in command output)}';

    protected $description = 'Create a populated demo shop (category + N products + /shop page) for mobile-audit testing of the Big2 Ecommerce layouts.';

    /** Marker stored on `subtype_value` so re-runs can clean up prior demo products. */
    private const SUBTYPE_VALUE_MARKER = 'demo-shop-seed';

    /**
     * Realistic-looking demo product titles + descriptions + prices.
     * The catalog is small enough to keep page weight reasonable for
     * mobile audits but varied enough to exercise different layouts.
     */
    private const CATALOG = [
        ['title' => 'Mountain Trail Backpack',     'description' => 'Lightweight 35L backpack with hydration sleeve, padded straps, and weatherproof zippers. Built for day hikes and weekend trails.',                'price' => 89.50],
        ['title' => 'Wireless Studio Headphones',  'description' => 'Over-ear active-noise-cancelling headphones with 40 hours of battery life and a foldable travel-friendly design.',                                'price' => 199.00],
        ['title' => 'Stainless Pour-Over Kettle',  'description' => 'Gooseneck stainless kettle with built-in thermometer for precision pour-over coffee. Stovetop-safe and easy to clean.',                            'price' => 49.99],
        ['title' => 'Linen Throw Blanket',         'description' => 'Soft pre-washed European linen blanket in a warm neutral. Lightweight enough for summer, cosy enough for winter evenings.',                       'price' => 79.00],
        ['title' => 'Brass Desk Lamp',             'description' => 'Adjustable brass-finish architect-style desk lamp with warm-white LED bulb. Articulating arm and weighted base for stability.',                  'price' => 134.00],
        ['title' => 'Walnut Cutting Board',        'description' => 'Edge-grain solid walnut cutting board with juice groove and recessed handles. Hand-finished with food-safe mineral oil.',                       'price' => 64.50],
        ['title' => 'Indoor Garden Planter',       'description' => 'Self-watering ceramic planter with built-in reservoir and drainage. Perfect for herbs, succulents, or small leafy greens.',                       'price' => 38.00],
        ['title' => 'Mineral Sunscreen SPF 50',    'description' => 'Reef-safe mineral sunscreen with non-nano zinc oxide. Tinted for a natural finish, water-resistant up to 80 minutes.',                            'price' => 24.00],
        ['title' => 'Cotton Crewneck Sweatshirt',  'description' => 'Heavyweight 100% cotton brushed-fleece crewneck with reinforced cuffs and ribbed hem. Pre-shrunk, sized true.',                                   'price' => 72.00],
        ['title' => 'Espresso Tamper 58mm',        'description' => 'Stainless flat-base 58mm tamper with walnut handle. Calibrated weight for consistent extraction. Fits all standard 58mm portafilters.',          'price' => 45.00],
        ['title' => 'Geometric Wall Mirror',       'description' => 'Hexagonal frame wall mirror with brushed brass finish. Hangs vertically or horizontally. 60cm point-to-point.',                                   'price' => 119.00],
        ['title' => 'Travel Toiletry Bag',         'description' => 'Compact roll-up toiletry bag with hanging hook and four mesh compartments. Water-resistant interior, recycled exterior shell.',                  'price' => 56.00],
    ];

    public function handle(): int
    {
        $count = max(1, (int) $this->option('count'));
        $slug = (string) $this->option('slug');
        $categorySlug = (string) $this->option('category');
        $skipImages = (bool) $this->option('no-images');

        // 1. Cycle-165 / wave3-f (2026-05-10): build a slug → id map so
        //    re-runs upsert in place instead of delete-then-create. The
        //    cycle-159 behaviour deleted prior demo products on every
        //    run + assigned new auto-increment ids, which (a) made the
        //    product ids drift (confusing for testers + breaks any
        //    persisted cart with the old ids) and (b) lost any
        //    runtime state on the products. Now: re-runs reuse the
        //    same product ids.
        $existingBySlug = Content::query()
            ->where('content_type', 'product')
            ->where('subtype_value', self::SUBTYPE_VALUE_MARKER)
            ->pluck('id', 'url')
            ->toArray();

        // 2. Category — fetch or create.
        $category = Category::query()->where('url', $categorySlug)->first();
        if (!$category) {
            $category = new Category();
            $category->title = 'Demo Shop';
            $category->url = $categorySlug;
            $category->rel_type = 'content';
            $category->rel_id = 0;
            $category->data_type = 'category';
            $category->is_active = 1;
            $category->is_deleted = 0;
            $category->is_hidden = 0;
            $category->parent_id = 0;
            $category->description = 'Auto-generated demo category — for mobile-audit testing of the shop layouts.';
            $category->save();
            $this->info("Created category: id={$category->id} url=/{$category->url}");
        } else {
            $this->line("Reusing existing category: id={$category->id} url=/{$category->url}");
        }

        // 3. Create N products from the catalog.
        $catalog = array_slice(self::CATALOG, 0, $count);
        if (count($catalog) < $count) {
            $this->warn("Requested count={$count} exceeds catalog size " . count(self::CATALOG)
                . '; capping to catalog size.');
        }

        $createdIds = [];
        foreach ($catalog as $i => $entry) {
            // Stable per-catalog slug so re-runs land on the same URL
            // and the same DB row. Renamed from `$slug` (cycle-165
            // first pass) → `$productSlug` because `$slug` is the
            // OUTER variable holding the SHOP page slug from --slug;
            // the original cycle-165 introduced a name collision that
            // made the post-loop /shop upsert lookup fail and create a
            // new /shop page on every run.
            $productSlug = 'demo-' . str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) . '-' . $this->slugify($entry['title']);
            $existingId = $existingBySlug[$productSlug] ?? null;

            // Cycle-165: upsert. If a prior demo product exists at this
            // slug, fetch + update in place (preserves the auto-
            // increment id so any persisted cart doesn't break). Else
            // create new.
            $product = $existingId !== null
                ? Product::query()->find($existingId)
                : new Product();
            // ::find() returns null if the row exists but is filtered
            // out by ProductScope — guard.
            if ($product === null) {
                $product = new Product();
            }
            $product->title = $entry['title'];
            $product->content_type = 'product';
            $product->subtype = 'product';
            $product->subtype_value = self::SUBTYPE_VALUE_MARKER;
            $product->is_active = 1;
            $product->is_deleted = 0;
            $product->is_home = 0;
            $product->is_shop = 0;
            $product->parent = 0;
            $product->content_body = $entry['description'];
            $product->description = $entry['description'];
            $product->url = $productSlug;
            $product->category_ids = [$category->id];
            $product->setCustomField([
                'type' => 'price',
                'name' => 'Price',
                'value' => (float) $entry['price'],
            ]);
            $product->save();
            $product->setContentData([
                'qty' => '10',
                'sku' => 'DEMO-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
            ]);
            $product->save();

            // Attach a picsum.photos placeholder image. Use the product
            // id as the seed so each product gets a stable but distinct
            // image across re-runs.
            // Cycle-165 / wave3-f: upsert by (rel_type, rel_id,
            // filename) so re-runs don't accumulate duplicate Media
            // rows. firstOrCreate keys on those three fields; if
            // already present, no-op.
            if (!$skipImages) {
                $imageUrl = sprintf('https://picsum.photos/seed/mw-shop-demo-%d/600/400', $product->id);
                Media::query()->firstOrCreate(
                    [
                        'rel_type' => $product->getMorphClass(),
                        'rel_id' => $product->id,
                        'filename' => $imageUrl,
                    ],
                    [
                        'media_type' => 'picture',
                        'position' => 1,
                        'session_id' => '',
                    ]
                );
            }

            $createdIds[] = $product->id;
            $this->line(sprintf('  - id=%d  $%6.2f  /%s', $product->id, $entry['price'], $product->url));
        }

        // 4. Upsert /shop Page.
        $shop = Content::query()->where('url', $slug)->where('content_type', 'page')->first();
        $shopContent = '<div class="mw-demo-shop-wrap"><module type="shop" /></div>';
        if (!$shop) {
            $shop = new Content();
            $shop->title = 'Shop';
            $shop->url = $slug;
            $shop->content_type = 'page';
            $shop->subtype = 'static';
            $shop->is_active = 1;
            $shop->is_deleted = 0;
            $shop->is_home = 0;
            $shop->is_shop = 1;
            $shop->parent = 0;
            $shop->content = $shopContent;
            $shop->description = 'Auto-generated shop page — populated via mw:shop-demo-seed.';
            $shop->save();
            $this->info("Created /shop page: id={$shop->id}");
        } else {
            // Only force is_shop + content. Leave other fields alone in
            // case a maintainer customised the page.
            $shop->is_shop = 1;
            $shop->is_active = 1;
            $shop->content = $shopContent;
            $shop->save();
            $this->line("Updated /shop page: id={$shop->id}");
        }

        // 5. Upsert /cart Page (cycle-161 — agent-test mobile audit
        //    follow-up). Without a Page at /cart the URL fell through
        //    to the homepage and the cart-icon-counter looked broken
        //    (cart had items but the page showed "Site Logo" content).
        //    Microweber's canonical cart-view path IS the checkout
        //    page: `mw.cart.add_and_show_modal()` opens a modal whose
        //    Checkout button hits `/api/shop/redirect_to_checkout`
        //    which routes to `/checkout/checkout` (rendered by the
        //    Checkout module's layout.blade.php — that view embeds
        //    `<module type="shop/cart" template="checkout_v2_sidebar"/>`
        //    inline so the user sees both cart items + checkout form
        //    on one mobile screen).
        //    For the /cart standalone surface we render shop/cart with
        //    the checkout_v2 template which IS shipped (verified in
        //    Modules/Checkout/.../index.blade.php) plus a "Proceed to
        //    Checkout" CTA so users can continue from a deep link.
        $cart = Content::query()->where('url', 'cart')->where('content_type', 'page')->first();
        $cartContent = '<div class="mw-demo-cart-wrap container py-4">'
            . '<h2 class="mb-3">Your Cart</h2>'
            . '<module type="shop/cart" template="checkout_v2" class="no-settings" data-checkout-link-enabled="y" />'
            . '<div class="text-center mt-4">'
            . '<a href="/checkout?nocache=1" class="btn btn-primary mw-add-to-cart-btn" style="min-width:44px;min-height:44px;display:inline-flex;align-items:center;justify-content:center;padding:8px 20px;">Proceed to Checkout</a>'
            . '</div>'
            . '</div>';
        if (!$cart) {
            $cart = new Content();
            $cart->title = 'Cart';
            $cart->url = 'cart';
            $cart->content_type = 'page';
            $cart->subtype = 'static';
            $cart->is_active = 1;
            $cart->is_deleted = 0;
            $cart->is_home = 0;
            $cart->parent = 0;
            $cart->content = $cartContent;
            $cart->description = 'Auto-generated cart page — populated via mw:shop-demo-seed.';
            $cart->save();
            $this->info("Created /cart page: id={$cart->id}");
        } else {
            $cart->is_active = 1;
            $cart->content = $cartContent;
            $cart->save();
            $this->line("Updated /cart page: id={$cart->id}");
        }

        $publicUrl = $this->resolvePublicUrl($shop);
        $cartUrl = function_exists('site_url')
            ? rtrim((string) site_url(), '/') . '/cart'
            : url('/cart');

        // Optional cart seed. NOTE: cart state is bound to the current
        // session — the CLI session is NOT the browser session. This
        // is mainly useful for unit tests / CI; for browser audits
        // agent-test should use the Playwright snippet printed below.
        $cartCount = (int) $this->option('with-cart');
        $cartSeeded = 0;
        if ($cartCount > 0 && !empty($createdIds) && function_exists('app')) {
            $shopManager = app()->shop_manager ?? null;
            if ($shopManager !== null && method_exists($shopManager, 'add_to_cart')) {
                foreach (array_slice($createdIds, 0, $cartCount) as $productId) {
                    try {
                        $shopManager->add_to_cart(['product_id' => $productId, 'qty' => 1]);
                        $cartSeeded++;
                    } catch (\Throwable $e) {
                        $this->warn("  cart seed failed for product {$productId}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->newLine();
        $this->info('Shop demo seed complete.');
        $this->table(
            ['Field', 'Value'],
            [
                ['products', (string) count($createdIds)],
                ['category', "/{$category->url}"],
                ['shop page', $publicUrl],
                ['cart page', (string) $cartUrl],
                ['cart seeded', "{$cartSeeded} item(s) into CLI session"],
            ]
        );
        $this->newLine();
        $this->line('Verification URLs (add ?nocache=1 in fresh browser):');
        $this->line('  ' . $publicUrl . '?nocache=1');
        $this->line('  ' . $cartUrl . '?nocache=1');
        $this->newLine();
        if ($cartSeeded > 0) {
            $this->warn('NOTE: cart items were added to the CLI session, NOT the browser session.');
            $this->line('To populate /cart for a Playwright/browser audit, fire add-to-cart');
            $this->line('via JS in the same browser context that visits /cart, e.g.:');
            $this->newLine();
            $firstId = $createdIds[0] ?? 0;
            $this->line('  // in Playwright after page.goto(/shop)');
            $this->line('  await page.evaluate((id) => mw.app.cart.addToCart({ product_id: id, qty: 1 }), ' . $firstId . ');');
            $this->line('  await page.goto("/cart?nocache=1");');
            $this->newLine();
            $this->line('Or POST to /api/cart/add with product_id + qty in the same session.');
        }

        return self::SUCCESS;
    }

    private function slugify(string $title): string
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($title));
        return trim((string) $slug, '-');
    }

    private function resolvePublicUrl(Content $page): string
    {
        if (function_exists('content_link')) {
            try {
                $link = content_link((int) $page->id);
                if (is_string($link) && $link !== '' && preg_match('#^https?://#i', $link)) {
                    return $link;
                }
            } catch (\Throwable) {
                // fall through
            }
        }
        $base = function_exists('site_url') ? rtrim((string) site_url(), '/') : rtrim((string) url('/'), '/');
        return $base . '/' . ltrim((string) $page->url, '/');
    }
}
