<?php

declare(strict_types=1);

namespace MicroweberPackages\Template\Export;

use Illuminate\Support\Facades\Storage;

/**
 * Makes an exported template's INLINE media portable across sites.
 *
 * Content built in Live Edit references media by absolute, site-local URLs —
 * either template design assets (`/templates/<tpl>/images/foo.jpg`) or uploaded
 * user media (`/storage/userfiles/…/media/…`, which may live on S3). Those URLs only
 * resolve on the site that produced them, so a template uploaded to a DIFFERENT site
 * would render with broken images.
 *
 * Run during export, this bundler rewrites every such URL it can resolve to a
 * readable file into a portable placeholder — `{{TPL_MEDIA}}/<name>` — and records
 * the file for copying into the template's `resources/assets/media/`. On install,
 * {@see \MicroweberPackages\Template\ContentJson\ContentJsonInstaller} materialises
 * that folder into the target site's userfiles (S3-safe) and swaps the placeholder
 * for the resolved URL, so the content renders with its images on any site/disk.
 *
 * SCOPE: inline media in the content HTML (`src`/`href`/`url()`/`poster`/`data-*`).
 * Gallery images (the `media` table) and background images (module options) are
 * NOT in the HTML and are handled separately by the exporter.
 *
 * URLs that are NOT this site's media (third-party/CDN, data: URIs, module tags)
 * are left untouched.
 */
class TemplateMediaBundler
{
    /** Placeholder the installer resolves to the target site's media base URL. */
    public const PLACEHOLDER = '{{TPL_MEDIA}}';

    /** Media file extensions we bundle (lowercased, no dot). */
    private const MEDIA_EXT = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'avif', 'svg', 'ico', 'bmp', 'mp4', 'webm', 'ogg', 'mp3', 'wav', 'pdf'];

    /** Never bundle a file this large (bytes) — guards a runaway export. */
    private int $maxFileBytes = 25 * 1024 * 1024;

    /** url (as written in content) => bundled file name under resources/assets/media. */
    private array $urlToName = [];

    /** bundled file name => source descriptor: ['local', absPath] | ['disk', diskKey]. */
    private array $nameToSource = [];

    private int $bundled = 0;
    private int $skipped = 0;

    /** Lazily-resolved S3/userfiles bucket base URL (probe-derived), or '' when local. */
    private ?string $remoteBase = null;

    public function setMaxFileBytes(int $bytes): self
    {
        $this->maxFileBytes = max(1, $bytes);
        return $this;
    }

    /**
     * Rewrite media URLs in the content.json payload and record the files to copy.
     * Mutates and returns the payload (pages/posts/products `html` fields).
     *
     * @param array<string,mixed> $content
     * @return array<string,mixed>
     */
    public function process(array $content): array
    {
        foreach (['pages', 'posts', 'products'] as $bucket) {
            if (empty($content[$bucket]) || !is_array($content[$bucket])) {
                continue;
            }
            foreach ($content[$bucket] as &$item) {
                if (!is_array($item)) {
                    continue;
                }
                if (isset($item['html']) && is_string($item['html'])) {
                    $item['html'] = $this->rewriteHtml($item['html']);
                }
                // Attached gallery (Pictures module) image URLs.
                if (isset($item['gallery']) && is_array($item['gallery'])) {
                    foreach ($item['gallery'] as &$g) {
                        if (is_array($g) && isset($g['filename']) && is_string($g['filename'])) {
                            $rewritten = $this->bundleUrl($g['filename']);
                            if ($rewritten !== null) {
                                $g['filename'] = $rewritten;
                            }
                        }
                    }
                    unset($g);
                }
            }
            unset($item);
        }
        return $content;
    }

    /**
     * Copy every bundled file into <templateRoot>/resources/assets/media/.
     * Call after process(). Returns the number of files written.
     */
    public function writeInto(string $templateRoot): int
    {
        if ($this->nameToSource === []) {
            return 0;
        }
        $dir = rtrim($templateRoot, '/\\') . '/resources/assets/media';
        @mkdir($dir, 0755, true);
        $written = 0;
        foreach ($this->nameToSource as $name => $source) {
            $bytes = $this->readSource($source);
            if ($bytes === null) {
                continue;
            }
            if (@file_put_contents($dir . '/' . $name, $bytes) !== false) {
                $written++;
            }
        }
        return $written;
    }

    /** @return array{bundled:int, skipped:int, files:int} */
    public function stats(): array
    {
        return ['bundled' => $this->bundled, 'skipped' => $this->skipped, 'files' => count($this->nameToSource)];
    }

    // ---- internals ---------------------------------------------------------

    /** Find media URLs in attribute/url() positions and rewrite the resolvable ones. */
    private function rewriteHtml(string $html): string
    {
        // Matches src="…", href="…", poster="…", data-*="…", and url(…) whose value
        // ends in a media extension. 'pre' = the leading attr/url( context (kept
        // verbatim); 'url' = the URL value to maybe rewrite.
        $pattern = '#(?P<pre>(?:src|href|data-src|data-image|data-bg|data-background-image|poster)\s*=\s*["\']|url\(\s*["\']?)(?P<url>[^"\')\s]+?\.(?:' . implode('|', self::MEDIA_EXT) . '))#i';

        return (string) preg_replace_callback($pattern, function (array $m): string {
            $replacement = $this->bundleUrl($m['url']);
            return $m['pre'] . ($replacement ?? $m['url']);
        }, $html);
    }

    /**
     * Resolve a URL to a readable source; if it's this site's media, record it for
     * copying and return the portable placeholder URL. Returns null to leave the
     * original URL unchanged (not ours / unreadable).
     */
    private function bundleUrl(string $url): ?string
    {
        $url = html_entity_decode($url, ENT_QUOTES);
        if ($url === '' || str_starts_with($url, 'data:') || str_starts_with($url, '{{')) {
            return null;
        }
        if (isset($this->urlToName[$url])) {
            return self::PLACEHOLDER . '/' . $this->urlToName[$url];
        }

        $source = $this->resolveSource($url);
        if ($source === null) {
            $this->skipped++;
            return null;
        }

        $name = $this->bundledName($url, $source);
        $this->urlToName[$url] = $name;
        $this->nameToSource[$name] = $source;
        $this->bundled++;

        return self::PLACEHOLDER . '/' . $name;
    }

    /**
     * Resolve a site-local media URL to a source descriptor:
     *   ['local', absPath]  for a readable file under public/ (design assets, local userfiles)
     *   ['disk',  diskKey]  for a userfiles object on a remote disk (S3)
     * Returns null for off-site/third-party/unreadable URLs.
     *
     * @return array{0:string,1:string}|null
     */
    private function resolveSource(string $url): ?array
    {
        // 1) Remote (S3) userfiles: the URL starts with the bucket base URL.
        $remote = $this->remoteBase();
        if ($remote !== '' && str_starts_with($url, $remote)) {
            $key = ltrim((string) strtok(substr($url, strlen($remote)), '?'), '/');
            if ($key !== '' && $this->matchesMediaExt($key) && $this->diskHas($key)) {
                return ['disk', $key];
            }
            return null;
        }

        // 2) Local: map to a path under public/.
        $path = $this->toSiteRelativePath($url);
        if ($path === null) {
            return null;
        }
        $path = '/' . ltrim($path, '/');
        if (!preg_match('#^/(templates|userfiles|storage|media)/#i', $path)) {
            return null;
        }
        $abs = $this->publicPath(ltrim($path, '/'));
        if ($abs !== null && is_file($abs) && filesize($abs) <= $this->maxFileBytes) {
            return ['local', $abs];
        }
        return null;
    }

    /** Strip this site's scheme/host; return a root-relative path, or null if off-site. */
    private function toSiteRelativePath(string $url): ?string
    {
        if (preg_match('#^(https?:)?//#i', $url)) {
            $site = function_exists('site_url') ? (string) site_url() : '';
            $host = $site !== '' ? (parse_url($site, PHP_URL_HOST) ?: '') : '';
            $urlHost = parse_url($url, PHP_URL_HOST) ?: '';
            if ($host === '' || strcasecmp($host, $urlHost) !== 0) {
                return null; // different host and not the (already-checked) remote bucket
            }
            return (string) (parse_url($url, PHP_URL_PATH) ?: '');
        }
        return $url; // already root-relative
    }

    /** The remote userfiles bucket base URL (probe-derived), or '' when local. */
    private function remoteBase(): string
    {
        if ($this->remoteBase !== null) {
            return $this->remoteBase;
        }
        $this->remoteBase = '';
        try {
            if (function_exists('userfiles_disk_is_remote') && userfiles_disk_is_remote()
                && function_exists('userfiles_disk')) {
                $probe = Storage::disk(userfiles_disk())->url('__MW_TPL_MEDIA_PROBE__');
                $pos = strpos($probe, '__MW_TPL_MEDIA_PROBE__');
                if ($pos !== false) {
                    $this->remoteBase = substr($probe, 0, $pos);
                }
            }
        } catch (\Throwable) {
            $this->remoteBase = '';
        }
        return $this->remoteBase;
    }

    private function diskHas(string $key): bool
    {
        try {
            return Storage::disk(userfiles_disk())->exists($key);
        } catch (\Throwable) {
            return false;
        }
    }

    private function matchesMediaExt(string $pathOrKey): bool
    {
        $ext = strtolower(pathinfo(parse_url($pathOrKey, PHP_URL_PATH) ?: $pathOrKey, PATHINFO_EXTENSION));
        return in_array($ext, self::MEDIA_EXT, true);
    }

    private function publicPath(string $rel): ?string
    {
        return function_exists('public_path') ? public_path($rel) : null;
    }

    /** @param array{0:string,1:string} $source */
    private function readSource(array $source): ?string
    {
        [$kind, $ref] = $source;
        try {
            if ($kind === 'local') {
                $bytes = @file_get_contents($ref);
                return $bytes === false ? null : $bytes;
            }
            if ($kind === 'disk') {
                $disk = Storage::disk(userfiles_disk());
                if (!$disk->exists($ref)) {
                    return null;
                }
                $bytes = $disk->get($ref);
                if ($bytes !== null && strlen($bytes) <= $this->maxFileBytes) {
                    return $bytes;
                }
            }
        } catch (\Throwable) {
            return null;
        }
        return null;
    }

    /** A stable, collision-free, extension-preserving file name for the bundle. */
    private function bundledName(string $url, array $source): string
    {
        $urlPath = parse_url($url, PHP_URL_PATH) ?: $url;
        $ext = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
        if ($ext === '' || !in_array($ext, self::MEDIA_EXT, true)) {
            $ext = strtolower(pathinfo($source[1], PATHINFO_EXTENSION)) ?: 'bin';
        }
        $base = preg_replace('/[^A-Za-z0-9._-]+/', '-', (string) pathinfo($urlPath, PATHINFO_FILENAME));
        $base = trim((string) $base, '-.') ?: 'media';
        // Short hash of the full URL keeps distinct sources distinct (two different
        // "hero.jpg" under different folders won't collide).
        return $base . '-' . substr(sha1($url), 0, 8) . '.' . $ext;
    }
}
