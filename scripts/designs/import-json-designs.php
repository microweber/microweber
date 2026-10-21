<?php
/**
 * Import external JSON/CSS "Bootstrap designs" as Microweber pages, each under
 * a thin ExtraTemplates/<Name> child of the Base template.
 *
 * These designs are authored as data (content.json) + a stylesheet + images and
 * rendered by a shared PHP renderer (index.php?template=<slug>). This importer
 * runs that renderer per design, stores the exact rendered body in the page's
 * `content` field, and publishes the design's CSS (plus a shared "design kit"
 * extracted from the renderer) and images under public/templates/<slug>/. The
 * page uses the Base `standalone` layout (no MW header/footer) so the design
 * owns the whole viewport. See .autodev/json-templates-strategy.md.
 *
 * Why it works end-to-end:
 *  - Base's master loads public/templates/<active_site_template>/css/base.css,
 *    keyed per-page off the page's own active_site_template (so many designs
 *    coexist on one site).
 *  - mw-body.css uses :where(.mw-main) for its token-driven typography, so each
 *    design's own stylesheet (a later <link>) wins the cascade by load order.
 *  - base.css maps --mw-*-font-family to the design's font so MW's own heading
 *    rules render the design typeface.
 *
 * Run inside booted Laravel:
 *   SAMPLES_DIR=/path/to/samples [ONLY="alfa vacay"] \
 *     php artisan tinker --execute="require 'scripts/designs/import-json-designs.php';"
 */
use Modules\Content\Models\Content;

$REF   = getenv('SAMPLES_DIR') ?: '/tmp/bt';
$ONLY  = array_filter(explode(' ', trim((string) getenv('ONLY'))));
$RENDER_ONE = __DIR__ . '/render-one-design.php';
$ICONS = 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css';

if (!is_file($REF . '/index.php')) { echo "ERROR: no renderer at {$REF}/index.php (set SAMPLES_DIR)\n"; return; }

// Extract the renderer's shared base CSS (shared_css()) — the foundation every
// design layers on (section-head centering, grids, .btnx, form grids, mobile
// collapse). Without it, imported pages lose cross-design layout.
$SHARED = '';
$rendererSrc = (string) file_get_contents($REF . '/index.php');
if (preg_match('/function shared_css.*?return\s*.<style>(.*?)<\/style>/s', $rendererSrc, $m)) {
    $SHARED = $m[1];
}

$slugs = array_map('basename', glob($REF . '/template/*', GLOB_ONLYDIR));
sort($slugs);
if ($ONLY) { $slugs = array_values(array_intersect($slugs, $ONLY)); }

$done = []; $failed = [];
foreach ($slugs as $slug) {
    try {
        $name  = ucfirst($slug);
        $lname = strtolower($name);

        // 1. Render full HTML via the sample renderer (isolated process).
        $html = shell_exec('SAMPLES_DIR=' . escapeshellarg($REF) . ' php ' . escapeshellarg($RENDER_ONE) . ' ' . escapeshellarg($slug) . ' 2>/dev/null');
        if (!$html || strlen($html) < 500) { throw new \RuntimeException('render empty'); }

        // 2. Extract <title>, Google-font href, and the <body> inner HTML.
        $title = preg_match('/<title>(.*?)<\/title>/s', $html, $m) ? trim(html_entity_decode($m[1], ENT_QUOTES)) : $name;
        $fontHref = '';
        if (preg_match('/<link href="(https:\/\/fonts\.googleapis\.com[^"]*)"/', $html, $m)) {
            $fontHref = html_entity_decode($m[1], ENT_QUOTES);
        }
        if (!preg_match('/<body[^>]*>(.*)<\/body>/s', $html, $m)) { throw new \RuntimeException('no body'); }
        $body = $m[1];
        $body = preg_replace('/<a class="skip-link".*?<\/a>/s', '', $body);  // MW has its own skip link
        $body = preg_replace('/<script\b.*?<\/script>/is', '', $body);       // drop the design's page JS
        $body = str_replace('template/' . $slug . '/', '/templates/' . $lname . '/', $body); // images → public path
        $body = trim($body);

        // 3. Per-design style.css + its primary font.
        $styleCss = (string) @file_get_contents($REF . '/template/' . $slug . '/css/style.css');
        $font = '';
        if (preg_match('/body\s*\{[^}]*font-family:\s*([^;}]+)/i', $styleCss, $m)) {
            $font = trim($m[1]);
        } elseif ($fontHref && preg_match('/family=([^:&]+)/', $fontHref, $m)) {
            $font = str_replace('+', ' ', urldecode($m[1])) . ', sans-serif';
        } else {
            $font = 'system-ui, sans-serif';
        }
        // @import rules must lead the file — hoist any from the design CSS.
        $hoisted = [];
        $styleCss = preg_replace_callback('/@import[^;]+;/i', function ($mm) use (&$hoisted) {
            $hoisted[] = trim($mm[0]); return '';
        }, $styleCss);

        // 4. Assemble base.css: imports → token map → shared kit → design CSS.
        $imports = ($fontHref ? "@import url('" . $fontHref . "');\n" : '')
                 . "@import url('" . $ICONS . "');\n";
        foreach ($hoisted as $h) { $imports .= $h . "\n"; }
        $baseCss = $imports
            . "/* Microweber design-token map (headings pick up the design font). */\n"
            . ":root { --mw-body-font-family: {$font}; --mw-heading-font-family: {$font}; }\n"
            . "\n/* ===== Shared design kit (section-head centering, grids, .btnx, forms) ===== */\n"
            . $SHARED
            . "\n/* ===== Per-design style ===== */\n"
            . $styleCss . "\n";

        // 5. ExtraTemplates/<Name> thin child scaffold.
        $tplDir = base_path('ExtraTemplates/' . $name);
        @mkdir($tplDir . '/config', 0775, true);
        @mkdir($tplDir . '/resources/assets/css', 0775, true);
        @mkdir($tplDir . '/resources/views', 0775, true);
        file_put_contents($tplDir . '/config/config.php',
            "<?php\n\nreturn [\n    'name' => '{$name}',\n    // Thin child of Base: inherits Base's master layout + design system.\n    // Identity = baked resources/assets/css/base.css (published to\n    // public/templates/{$lname}/css/base.css) + the imported JSON design page.\n    'parent_template' => 'Base',\n];\n");
        file_put_contents($tplDir . '/module.json',
            json_encode(['name' => $name, 'alias' => $lname, 'description' => $name . ' (imported design)', 'keywords' => [], 'priority' => 0, 'providers' => [], 'files' => []], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
        file_put_contents($tplDir . '/composer.json',
            json_encode(['name' => 'microweber-template/' . $lname, 'description' => $name . ' imported design template', 'type' => 'microweber-template', 'require' => new stdClass()], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
        // No per-template standalone.blade.php — the page's layout_file
        // 'standalone.blade.php' resolves to Base's single shared copy via the
        // parent_template chain (resolveParentTemplateViewFile), so the layout
        // lives in exactly one place (Templates/Base/resources/views/standalone.blade.php).
        file_put_contents($tplDir . '/resources/assets/css/base.css', $baseCss);

        // 6. Publish CSS + images.
        $pubDir = public_path('templates/' . $lname);
        @mkdir($pubDir . '/css', 0775, true);
        file_put_contents($pubDir . '/css/base.css', $baseCss);
        $srcImg = $REF . '/template/' . $slug . '/images';
        if (is_dir($srcImg)) {
            @mkdir($pubDir . '/images', 0775, true);
            foreach (glob($srcImg . '/*') as $img) { copy($img, $pubDir . '/images/' . basename($img)); }
        }

        // 7. Upsert the MW page (content lives in the `content` field).
        $page = Content::where('url', $slug)->first() ?: new Content();
        $page->title = $title !== '' ? $title : $name;
        $page->url = $slug;
        $page->content_type = 'page';
        $page->subtype = 'static';
        $page->is_active = 1;
        $page->is_deleted = 0;
        $page->active_site_template = $name;
        $page->layout_file = 'standalone.blade.php';
        $page->content = $body;
        $page->save();

        // 8. Ship a content.json seed in the template dir so the page's default
        //    content can be (re)installed from the admin "Apply template" flow or
        //    `php artisan content-json:import`. We store the pre-rendered body as
        //    the authoritative `html` (re-apply reproduces this exact page) AND keep
        //    the source `sections` + `data` so the design stays authorable/flexible
        //    (MW's SectionRenderer can render the sections if `html` is ever dropped).
        $source = json_decode((string) @file_get_contents($REF . '/template/' . $slug . '/content.json'), true) ?: [];
        $sections = $source['sections'] ?? [];
        $contentJson = [
            'template' => $name,
            'data' => $source,
            'pages' => [[
                'url' => $slug,
                'title' => $title !== '' ? $title : $name,
                'is_home' => true,
                'content_type' => 'page',
                'subtype' => 'static',
                'layout_file' => 'standalone.blade.php',
                'active_site_template' => $name,
                'html' => $body,
                'sections' => $sections,
            ]],
        ];
        file_put_contents($tplDir . '/content.json', json_encode($contentJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $done[] = "{$slug} -> page#{$page->id} ({$name}), css=" . strlen($baseCss) . "B, body=" . strlen($body) . "B, content.json=" . (count($sections)) . " sections";
    } catch (\Throwable $e) {
        $failed[] = "{$slug}: " . $e->getMessage();
    }
}

echo "=== IMPORTED (" . count($done) . ") ===\n" . implode("\n", $done) . "\n";
if ($failed) { echo "=== FAILED (" . count($failed) . ") ===\n" . implode("\n", $failed) . "\n"; }
