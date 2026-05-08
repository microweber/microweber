<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-80 / AI-61 / TICKET-EE — Product detail hero image fallback
 * regression coverage.
 *
 * Pins:
 *   - Modules/Pictures/.../shop-inner-templates.blade.php carries an
 *     `@else` branch under the `@if(isset($data[0]['filename']))`
 *     gate so empty-product-image rendering shows a placeholder
 *     instead of a broken/empty column layout.
 *   - The fallback uses inline-SVG (not asset()) to avoid 404 risk.
 *   - The fallback `<figure>` keeps the same id as the real hero
 *     image so the JS gallery binding doesn't NPE when content has
 *     no images.
 *   - Accessibility: role="img" + aria-label + visually-hidden
 *     <figcaption> so SR users hear "No product image available".
 *
 * Style after the cycle-52..79 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class ProductHeroImageFallbackContractTest extends TestCase
{
    private string $skinSrc;

    protected function setUp(): void
    {
        parent::setUp();
        $this->skinSrc = file_get_contents(base_path(
            'Modules/Pictures/resources/views/templates/shop-inner-templates.blade.php'
        ));
    }

    #[Test]
    public function else_branch_under_data_filename_gate_is_present(): void
    {
        // The hero image gate is `@if(isset($data[0]['filename']))`;
        // before cycle-80 there was no @else branch — empty-image
        // case rendered nothing and broke the layout.
        $this->assertMatchesRegularExpression(
            "/@if\\(isset\\(\\\$data\\[0\\]\\['filename'\\]\\)\\)[\\s\\S]*?@else[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: must have an @else branch under the data[0][filename] gate'
        );
    }

    #[Test]
    public function fallback_uses_inline_svg_not_asset_url(): void
    {
        // Inline SVG avoids a 404 risk if the placeholder asset isn't
        // published. The cycle-80 fallback embeds the SVG directly.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?<svg\\b[\\s\\S]*?<\\/svg>[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must contain an inline <svg> (not an asset() <img>) to avoid 404 risk'
        );
        // Negative: the @else block does NOT call asset() for the
        // image source — it carries an inline SVG instead.
        $this->assertDoesNotMatchRegularExpression(
            "/@else[\\s\\S]*?asset\\(['\"]modules\\/pictures\\/img[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must NOT depend on asset() for the placeholder image (avoids 404 if not published)'
        );
    }

    #[Test]
    public function fallback_carries_same_pictureelementid_as_real_hero(): void
    {
        // The JS gallery binding (line 109+) does
        // `document.getElementById($pictureElementId)`. If the
        // placeholder doesn't carry the same id, the binding
        // attempts to addEventListener on null when the content has
        // no images.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?id=\"\\{\\{\\s*\\\$pictureElementId\\s*\\}\\}\"[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry id="{{ $pictureElementId }}" so the JS gallery binding does not NPE'
        );
    }

    #[Test]
    public function fallback_is_accessible_via_role_img_and_aria_label(): void
    {
        // The placeholder is a meaningful image (telling the user
        // "no image available") — must be exposed as such to AT.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?role=\"img\"[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry role="img" on the placeholder figure'
        );
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?aria-label=\"\\{\\{\\s*__\\(['\"]No product image available['\"]\\)\\s*\\}\\}\"[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry aria-label="No product image available" (translated)'
        );
        // Inner <svg> is aria-hidden so SR doesn't double-announce
        // alongside the figure-level aria-label.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?<svg[^>]*aria-hidden=\"true\"[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch inner <svg> must carry aria-hidden="true"'
        );
        // Visually-hidden <figcaption> is a belt-and-braces label —
        // some legacy SR engines miss role=img+aria-label combos.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?<figcaption[^>]*visually-hidden[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry a <figcaption class="visually-hidden"> with the same label'
        );
    }

    #[Test]
    public function fallback_carries_data_attribute_for_styling_hooks(): void
    {
        // CSS hook for theme overrides without depending on the
        // semantic id (which varies per content_id).
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?data-mw-no-product-image[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry data-mw-no-product-image attribute for CSS hooks'
        );
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?mw-shop-inner-big-image-placeholder[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must carry .mw-shop-inner-big-image-placeholder class hook'
        );
    }

    #[Test]
    public function fallback_preserves_aspect_ratio_so_layout_does_not_jump(): void
    {
        // The real hero is 1080x1080. The placeholder must have the
        // same aspect-ratio so when the admin adds a real product
        // image, the layout doesn't reflow / jump.
        $this->assertMatchesRegularExpression(
            "/@else[\\s\\S]*?aspect-ratio:\\s*1\\s*\\/\\s*1[\\s\\S]*?@endif/s",
            $this->skinSrc,
            'shop-inner-templates: @else branch must declare aspect-ratio: 1/1 on the placeholder so layout does not reflow when an admin adds an image'
        );
    }
}
