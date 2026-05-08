<?php

declare(strict_types=1);

namespace Tests\Feature;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Modules\Category\Helpers\KnpCustomListRenderer;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-72 / AI-65 / TICKET-SS — category active-state aria-current
 * regression coverage.
 *
 * Pins:
 *   - KnpCustomListRenderer::renderLinkElement emits
 *     aria-current="page" on the <a> when the menu item is current.
 *   - KnpCustomListRenderer::renderSpanElement emits the same on
 *     the fallback <span> shape (when `currentAsLink` is false).
 *   - The active-state CSS class still wins via the existing
 *     `currentClass` option — aria-current is the semantic
 *     COMPLEMENT, not a replacement.
 *
 * Style after the cycle-52..71 contract tests (file-system reads only,
 * plus a tightly-scoped renderer instantiation that does NOT touch
 * MySQL or boot Filament). Per project memory `feedback_testing`:
 * contract tests never mount Filament resources or hit MySQL.
 */
class CategoryAriaCurrentContractTest extends TestCase
{
    private string $rendererSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rendererSrc = file_get_contents(base_path(
            'Modules/Category/Helpers/KnpCustomListRenderer.php'
        ));
    }

    #[Test]
    public function renderer_source_emits_aria_current_on_current_link(): void
    {
        $this->assertStringContainsString(
            "\$linkAttributes['aria-current'] = 'page'",
            $this->rendererSrc,
            'renderLinkElement must set aria-current="page" on the <a> when item->isCurrent()'
        );
        // The check must be GUARDED on isCurrent() — we don't want
        // every link to claim aria-current=page.
        $this->assertStringContainsString(
            "\$item->isCurrent() && empty(\$linkAttributes['aria-current'])",
            $this->rendererSrc,
            'renderLinkElement: aria-current assignment must be guarded on $item->isCurrent() && empty($linkAttributes[aria-current])'
        );
    }

    #[Test]
    public function renderer_source_emits_aria_current_on_current_span(): void
    {
        $this->assertStringContainsString(
            "\$labelAttributes['aria-current'] = 'page'",
            $this->rendererSrc,
            'renderSpanElement must set aria-current="page" on the <span> fallback (currentAsLink=false case)'
        );
        $this->assertStringContainsString(
            "\$item->isCurrent() && empty(\$labelAttributes['aria-current'])",
            $this->rendererSrc,
            'renderSpanElement: aria-current assignment must be guarded on $item->isCurrent() && empty($labelAttributes[aria-current])'
        );
    }

    #[Test]
    public function rendered_link_for_current_item_carries_aria_current_page(): void
    {
        // Build a tiny tree by hand using Knp\Menu and pass it through
        // the actual production renderer. This is the strongest pin
        // (functional, not just file-text) AND it stays DB-free.
        $factory = new MenuFactory();
        $root = $factory->createItem('root');
        $cat1 = $root->addChild('cat-1', ['uri' => '/cat-1']);
        $cat2 = $root->addChild('cat-2', ['uri' => '/cat-2']);

        // Mark cat-2 as the active page.
        $cat2->setCurrent(true);

        // No voter needed — Knp's Matcher::isCurrent() returns the
        // explicit setCurrent() value when it's not null, before
        // consulting voters. setCurrent(true) above is sufficient.
        $matcher = new Matcher();

        $renderer = new KnpCustomListRenderer($matcher);
        $html = $renderer->render($root);

        // The active <a> must carry aria-current="page".
        $this->assertMatchesRegularExpression(
            '/<a [^>]*href="\\/cat-2"[^>]*aria-current="page"|<a [^>]*aria-current="page"[^>]*href="\\/cat-2"/',
            $html,
            'cat-2 (current) <a> must carry aria-current="page" — got HTML: ' . $html
        );

        // The non-active link must NOT carry aria-current.
        $this->assertDoesNotMatchRegularExpression(
            '/<a [^>]*href="\\/cat-1"[^>]*aria-current=/',
            $html,
            'cat-1 (non-current) <a> must NOT carry aria-current'
        );
    }

    #[Test]
    public function active_class_still_applies_alongside_aria_current(): void
    {
        // The visual active-state class is independent of aria-current —
        // both must coexist (CSS targets the class, AT targets the
        // attribute). Pin that the existing currentClass mechanism
        // is unchanged.
        $factory = new MenuFactory();
        $root = $factory->createItem('root');
        $cat = $root->addChild('cat-active', ['uri' => '/cat-active']);
        $cat->setCurrent(true);

        $matcher = new Matcher();

        $renderer = new KnpCustomListRenderer($matcher, [
            // The cycle-72 change must not break the explicit
            // currentClass override.
            'currentClass' => 'mw-category-active',
        ]);
        $html = $renderer->render($root);

        $this->assertStringContainsString(
            'mw-category-active',
            $html,
            'currentClass=mw-category-active must still apply on the active <li>'
        );
        $this->assertStringContainsString(
            'aria-current="page"',
            $html,
            'aria-current=page must apply alongside the visual active class'
        );
    }
}
