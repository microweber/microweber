<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Cycle-98 / AI-88..93 / TASK-059 — Big2 template a11y sweep
 * regression coverage (bundled across 6 issues, BIG2-A through
 * BIG2-F).
 *
 * Pins:
 *   - BIG2-A: every `<img>` in
 *     Templates/Big2/resources/views/modules/layouts/templates/**
 *     carries an `alt=` attribute (decorative ones use empty alt).
 *   - BIG2-B: product.blade.php prev/next thumbnails carry
 *     `alt="{{ $prev['title'] }}"` / `alt="{{ $next['title'] }}"`
 *     plus loading/decoding hints.
 *   - BIG2-C: post.blade.php featured image is a real `<img>` via
 *     responsive_thumbnail() (was a `<div style="background-image">` —
 *     invisible to SR, CSP-violating inline style).
 *   - BIG2-D: post.blade.php title is `<h1>`, not `<h2>`.
 *   - BIG2-E: design/skin-18 brand-logo strip carries descriptive
 *     `alt="<Brand> logo"` for each of 5 logos.
 *   - BIG2-F: product.blade.php availability icons carry
 *     `aria-hidden="true"` (decorative — colour conveys state;
 *     the text "In Stock" / "Out of Stock" is the info).
 *
 * Style after the cycle-52..97 contract tests (file-system reads only,
 * no DB touch). Per project memory `feedback_testing`: contract tests
 * never mount Filament resources or hit MySQL.
 */
class Big2A11ySweepContractTest extends TestCase
{
    private const POST     = 'Templates/Big2/resources/views/post.blade.php';
    private const PRODUCT  = 'Templates/Big2/resources/views/product.blade.php';
    private const SKIN_18  = 'Templates/Big2/resources/views/modules/layouts/templates/design/skin-18.blade.php';
    private const LAYOUTS  = 'Templates/Big2/resources/views/modules/layouts/templates';

    private function read(string $rel): string
    {
        $path = base_path($rel);
        $this->assertFileExists($path, "Expected: {$rel}");
        return file_get_contents($path);
    }

    /**
     * Big2 is a separate composer package (microweber-templates/big2)
     * checked out as a nested git repo. Its directory is gitignored
     * in the main microweber repo (.gitignore line 94: `Templates/Big2/`),
     * so fresh main-repo checkouts and CI environments without an
     * explicit Big2 install will skip these tests cleanly. When Big2
     * IS present locally, the contract pins the cycle-98 deliverable.
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (!is_dir(base_path('Templates/Big2/resources/views'))) {
            $this->markTestSkipped('Templates/Big2/ not present (separate package — install via composer to run this test)');
        }
    }

    #[Test]
    public function big2_a_every_img_carries_an_alt_attribute(): void
    {
        // Walk every Blade under modules/layouts/templates and check
        // that every <img tag (whether single-line or multi-line)
        // contains alt= somewhere in its body.
        $dir = base_path(self::LAYOUTS);
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $offenders = [];
        foreach ($rii as $file) {
            if (!$file->isFile() || !str_ends_with($file->getPathname(), '.blade.php')) {
                continue;
            }
            $src = file_get_contents($file->getPathname());
            // Match <img ... > including multi-line.
            if (preg_match_all('/<img\\b[\\s\\S]*?>/', $src, $matches)) {
                foreach ($matches[0] as $tag) {
                    if (!preg_match('/\\balt\\s*=/', $tag)) {
                        $offenders[] = sprintf(
                            '%s: %s',
                            str_replace(base_path() . '/', '', $file->getPathname()),
                            substr(preg_replace('/\\s+/', ' ', $tag), 0, 120)
                        );
                    }
                }
            }
        }
        $this->assertEmpty(
            $offenders,
            'BIG2-A: every <img> in Big2 layouts must carry an alt= attribute. Offenders:' . "\n" . implode("\n", $offenders)
        );
    }

    #[Test]
    public function big2_b_product_prev_next_thumbnails_have_descriptive_alt(): void
    {
        $src = $this->read(self::PRODUCT);

        $this->assertMatchesRegularExpression(
            "/<img\\s[^>]*src=\"\\{\\{ get_picture\\(\\\$prev\\['id'\\]\\) \\}\\}\"\\s+alt=\"\\{\\{ \\\$prev\\['title'\\] \\?\\? '' \\}\\}\"/",
            $src,
            'BIG2-B: prev thumbnail must carry alt={{ $prev[title] ?? "" }}'
        );
        $this->assertMatchesRegularExpression(
            "/<img\\s[^>]*src=\"\\{\\{ get_picture\\(\\\$next\\['id'\\]\\) \\}\\}\"\\s+alt=\"\\{\\{ \\\$next\\['title'\\] \\?\\? '' \\}\\}\"/",
            $src,
            'BIG2-B: next thumbnail must carry alt={{ $next[title] ?? "" }}'
        );
        // Both must declare loading=lazy + decoding=async.
        $this->assertMatchesRegularExpression(
            '/get_picture\\(\\$prev[\\s\\S]{0,400}loading="lazy"[\\s\\S]{0,200}decoding="async"/',
            $src,
            'BIG2-B: prev thumbnail must declare loading="lazy" + decoding="async"'
        );
        $this->assertMatchesRegularExpression(
            '/get_picture\\(\\$next[\\s\\S]{0,400}loading="lazy"[\\s\\S]{0,200}decoding="async"/',
            $src,
            'BIG2-B: next thumbnail must declare loading="lazy" + decoding="async"'
        );
    }

    #[Test]
    public function big2_c_post_featured_image_is_real_img_not_background_div(): void
    {
        $src = $this->read(self::POST);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        // Negative: pre-fix `<div ... style="background-image: url(...)">` shape must be gone.
        $this->assertDoesNotMatchRegularExpression(
            '/<div[^>]*style="background-image:\\s*url\\(/',
            $stripped,
            'BIG2-C: post featured image must NOT be a `<div style="background-image">` (CSP-violation + invisible to SR)'
        );

        // Positive: responsive_thumbnail() call wraps the picture
        // with class img-fluid + alt = post title.
        $this->assertMatchesRegularExpression(
            "/responsive_thumbnail\\(\\\$picture,\\s*\\d+,\\s*\\d+,\\s*\\[\\s*'class'\\s*=>\\s*'img-fluid w-100',\\s*'alt'\\s*=>\\s*\\\$post\\['title'\\] \\?\\? ''/",
            $src,
            'BIG2-C: post featured image must use responsive_thumbnail() with class+alt args'
        );
    }

    #[Test]
    public function big2_d_post_title_is_h1_not_h2(): void
    {
        $src = $this->read(self::POST);
        $stripped = preg_replace('/\\{\\{--[\\s\\S]*?--\\}\\}/', '', $src);

        $this->assertMatchesRegularExpression(
            '/<h1\\s[^>]*>\\{\\{ \\$post\\[\'title\'\\] \\}\\}<\\/h1>/',
            $stripped,
            'BIG2-D: post title must be `<h1>{{ $post[\'title\'] }}</h1>` (single-document outline)'
        );

        // Negative: pre-fix `<h2 ... >{{ $post['title'] }}</h2>` must be gone.
        $this->assertDoesNotMatchRegularExpression(
            '/<h2\\s[^>]*>\\{\\{ \\$post\\[\'title\'\\] \\}\\}<\\/h2>/',
            $stripped,
            'BIG2-D: post title must NOT be wrapped in <h2> anymore'
        );
    }

    #[Test]
    public function big2_e_skin_18_brand_logos_carry_descriptive_alt(): void
    {
        $src = $this->read(self::SKIN_18);

        foreach (['Amazon', 'Facebook', 'Google', 'LinkedIn', 'Philips'] as $brand) {
            $this->assertMatchesRegularExpression(
                "/<img\\s[^>]*src=\"[^\"]+logo_" . strtolower($brand) . "\\.png[^\"]*\"[^>]*\\salt=\"{$brand} logo\"/",
                $src,
                "BIG2-E: {$brand} logo must carry alt=\"{$brand} logo\" (meaningful — surrounding text mentions \"top-tier brands\")"
            );
        }
    }

    #[Test]
    public function big2_f_product_availability_icons_carry_aria_hidden(): void
    {
        $src = $this->read(self::PRODUCT);

        // Both circle icons (in-stock + out-of-stock branches) must
        // carry aria-hidden="true".
        $count = preg_match_all(
            '/<i class="fa fa-circle"\\s+aria-hidden="true"/',
            $src
        );
        $this->assertGreaterThanOrEqual(
            2,
            $count,
            'BIG2-F: both availability circle icons (in-stock + out-of-stock) must carry aria-hidden="true"'
        );
    }
}
