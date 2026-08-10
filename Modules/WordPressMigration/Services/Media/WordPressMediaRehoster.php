<?php

namespace Modules\WordPressMigration\Services\Media;

use MicroweberPackages\Http\Facades\Http as MicroweberHttp;
use MicroweberPackages\Url\Facades\UrlManager;


/**
 * Content-hash deduplicating rehoster scoped to a single WordPress
 * migration job.
 *
 * Given a remote asset URL, this class:
 *   1. Downloads the bytes into a temp file
 *   2. Hashes the content (sha256)
 *   3. If the hash was seen earlier in this job, reuses the existing
 *      file + media row (no second write, no second DB insert)
 *   4. Otherwise moves the temp file into
 *      `userfiles/media/imported/wordpress/<job-id>/<hash>.<ext>`
 *      and records a `media` row
 *
 * The hash-based dedupe matters because WordPress themes routinely
 * point at the same image from multiple posts (hero, archive, OG
 * tag) — without it a 50-post import would re-download and re-store
 * the same hero 50 times.
 *
 * Why a dedicated class when {@see MicroweberMediaRehoster} already
 * exists: the legacy class dedupes only by target path (same URL →
 * same path), not by content. Two URLs that return the same bytes
 * (CDN variants, `?v=` cache busters) would still double-store. The
 * spec calls for content-hash dedupe, so this is a clean seam.
 *
 * Storage layout
 * --------------
 * `<storageRoot>/imported/wordpress/<job-id>/<hash12>.<ext>`
 *
 * - `<storageRoot>` defaults to `media_base_path()`; tests override
 * - `<job-id>` is required at construction time so the class can't
 *   accidentally scatter files into the wrong job's tree
 * - `<hash12>` is the first 12 hex chars of sha256 — long enough
 *   that collisions in a single job are astronomically unlikely, short
 *   enough to keep paths readable when debugging
 * - `<ext>` comes from the URL path; no mime-sniff fallback is done
 *   here (the separate {@see MediaRehoster} impl will grow that; the
 *   line 1303 test will drive it)
 *
 * Assets this class WILL rehost (returns a receipt):
 *   - http(s) URLs whose path ends in a whitelisted image/doc ext
 *   - protocol-relative URLs (`//host/file.jpg`) — promoted using
 *     `$context['scheme']` (defaults to `https`)
 *
 * Assets this class WON'T rehost (returns null):
 *   - non-http(s) schemes (mailto:, tel:, javascript:, data:)
 *   - paths without a usable extension
 *   - URLs whose download fails or returns zero bytes
 *
 * Redirects
 * ---------
 * The injected downloader is expected to follow HTTP redirects
 * transparently. The default downloader (`MicroweberHttp::url($url)->
 * download($target)`) uses Microweber's HTTP client, which does
 * follow redirects. Two URLs redirecting to the same final
 * resource are still downloaded twice (once per unique request URL)
 * but content-hash dedupe catches the duplicate bytes and
 * collapses them into a single on-disk file + media row — exactly
 * the behavior the spec asks for.
 */
final class WordPressMediaRehoster implements MediaRehoster
{
    private const ASSET_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif',
        'mp4', 'webm', 'mov', 'mp3', 'wav', 'ogg',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip',
    ];

    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];
    private const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov'];
    private const AUDIO_EXTENSIONS = ['mp3', 'wav', 'ogg'];

    /**
     * In-process cache: hash → RehostReceipt. Keyed by full sha256
     * so two callers that agree on content always land on the same
     * row, even if they came in through different URLs.
     *
     * @var array<string, RehostReceipt>
     */
    private array $byHash = [];

    /**
     * Secondary cache: URL → RehostReceipt. Avoids re-downloading
     * when the same URL appears twice in a single job (common with
     * related-post widgets that reuse hero images).
     *
     * @var array<string, RehostReceipt>
     */
    private array $byUrl = [];

    /** @var callable(string, string): bool */
    private $downloader;

    /** @var callable(array<string, mixed>): int|null */
    private $saver;

    private string $storageRoot;

    /**
     * True when the caller explicitly passed a storage root (tests,
     * custom deployments). Used to pick the right public-URL path:
     * stock deployments go through `app()->url_manager`; overrides get
     * the conventional `/userfiles/media/...` fallback.
     */
    private bool $storageRootIsCustom;

    /**
     * @param int|string $jobId
     *   Scopes the on-disk location so one job's files never land in
     *   another's directory. Accepts int (`wp_migration_jobs.id`) or
     *   string (synthetic key for ad-hoc imports).
     *
     * @param callable(string $url, string $targetPath): bool|null $downloader
     *   Override for the bytes-fetcher. Defaults to
     *   `MicroweberHttp::url($url)->download($target)`. Tests pass a
     *   closure that writes deterministic bytes without the network.
     *
     * @param callable(array<string, mixed>): int|null|null $saver
     *   Override for the media-row writer. Defaults to `save_media()`.
     *   MUST return the inserted row's id (or null on failure).
     *
     * @param string|null $storageRoot
     *   Override for the filesystem root. Defaults to
     *   `media_base_path()`. Tests point this at a tmp dir.
     */
    public function __construct(
        private readonly int|string $jobId,
        ?callable $downloader = null,
        ?callable $saver = null,
        ?string $storageRoot = null,
    ) {
        $this->downloader = $downloader ?? fn (string $url, string $target): bool
            => (bool) MicroweberHttp::url($url)->download($target);

        $this->saver = $saver ?? fn (array $data): ?int => self::saveMediaRow($data);

        if ($storageRoot !== null) {
            $this->storageRoot = rtrim($storageRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            $this->storageRootIsCustom = true;
        } else {
            $this->storageRoot = media_base_path();
            $this->storageRootIsCustom = false;
        }
    }

    /**
     * Download, hash, dedupe, store — return structured receipt.
     *
     * @param array<string, mixed> $context
     */
    public function fetch(string $url, array $context = []): ?RehostReceipt
    {
        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }
        if (isset($this->byUrl[$trimmed])) {
            return $this->byUrl[$trimmed];
        }

        // Protocol-relative URLs (`//host/file.jpg`) get promoted
        // using the origin scheme carried on $context. The default
        // is https — safe for any modern WP export and matches the
        // rewriter's own default. We cache both the original and
        // the promoted key below so the second caller hitting the
        // same `//host/...` URL short-circuits without a second
        // parse.
        $resolved = $trimmed;
        if (str_starts_with($resolved, '//')) {
            $scheme = $this->normalizeScheme($context['scheme'] ?? null);
            $resolved = $scheme . ':' . $resolved;
        }

        $parsed = parse_url($resolved);
        if (!is_array($parsed) || !isset($parsed['scheme'], $parsed['host'])) {
            return null;
        }
        $scheme = strtolower($parsed['scheme']);
        if ($scheme !== 'http' && $scheme !== 'https') {
            return null;
        }

        $path = $parsed['path'] ?? '';
        $urlExt = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // URL-derived extension is the fast path. We accept it if
        // the path ends in a recognised image/doc ext. Otherwise we
        // still attempt a download and sniff the mime type of the
        // bytes — WP sites increasingly serve extensionless asset
        // URLs (CDN rewrites, signed URLs), and rejecting them on
        // path shape alone loses real images.
        $hasUrlExt = $urlExt !== '' && in_array($urlExt, self::ASSET_EXTENSIONS, true);

        $tmp = $this->makeTempPath($hasUrlExt ? $urlExt : 'bin');
        if (!$this->download($resolved, $tmp)) {
            @unlink($tmp);
            return null;
        }

        // If the URL gave us no usable extension, sniff it from the
        // downloaded bytes. Anything we can't classify gets dropped
        // here (not an asset we'd want to rehost).
        $ext = $hasUrlExt ? $urlExt : $this->sniffExtension($tmp);
        if ($ext === null) {
            @unlink($tmp);
            return null;
        }

        $hash = hash_file('sha256', $tmp);
        if ($hash === false) {
            @unlink($tmp);
            return null;
        }

        if (isset($this->byHash[$hash])) {
            // Same bytes we've already stored in this job — drop the
            // duplicate copy, reuse the earlier receipt. This is the
            // content-hash dedupe the spec calls for.
            @unlink($tmp);
            $existing = $this->byHash[$hash];
            $this->byUrl[$trimmed] = $existing;
            $this->byUrl[$resolved] = $existing;
            return $existing;
        }

        $finalPath = $this->finalPathFor($hash, $ext);
        if (!$this->moveIntoPlace($tmp, $finalPath)) {
            @unlink($tmp);
            return null;
        }

        $publicUrl = $this->publicUrlFor($finalPath);
        if ($publicUrl === null) {
            return null;
        }

        $mediaId = ($this->saver)([
            'rel_type' => (string)($context['rel_type'] ?? 'Modules\\Content\\Models\\Content'),
            'rel_id' => (string)($context['rel_id'] ?? '0'),
            'media_type' => $this->guessMediaType($ext),
            'filename' => $publicUrl,
            'title' => basename($path),
        ]);

        if (!is_int($mediaId) || $mediaId <= 0) {
            return null;
        }

        $receipt = new RehostReceipt($mediaId, $publicUrl);
        $this->byHash[$hash] = $receipt;
        $this->byUrl[$trimmed] = $receipt;
        $this->byUrl[$resolved] = $receipt;
        return $receipt;
    }

    private function normalizeScheme(mixed $scheme): string
    {
        if (!is_string($scheme) || $scheme === '') {
            return 'https';
        }
        $lower = strtolower($scheme);
        return in_array($lower, ['http', 'https'], true) ? $lower : 'https';
    }

    /**
     * Adapter that keeps this class substitutable for the existing
     * {@see MediaRehoster} interface: returns just the URL part of
     * the receipt, which is all the HTML rewriter needs.
     */
    public function rehost(string $url, array $context = []): ?string
    {
        return $this->fetch($url, $context)?->url;
    }

    private function download(string $url, string $target): bool
    {
        try {
            $ok = ($this->downloader)($url, $target);
        } catch (\Throwable) {
            return false;
        }
        if (!$ok || !is_file($target) || filesize($target) === 0) {
            return false;
        }
        return true;
    }

    private function makeTempPath(string $ext): string
    {
        $tmpDir = sys_get_temp_dir();
        $base = 'wp-rehost-' . bin2hex(random_bytes(8));
        return $tmpDir . DIRECTORY_SEPARATOR . $base . '.' . $ext;
    }

    private function finalPathFor(string $hash, string $ext): string
    {
        $jobDir = $this->storageRoot
            . 'imported' . DIRECTORY_SEPARATOR
            . 'wordpress' . DIRECTORY_SEPARATOR
            . $this->sanitizeJobId() . DIRECTORY_SEPARATOR;

        if (!is_dir($jobDir)) {
            @mkdir($jobDir, 0775, true);
        }

        return $jobDir . substr($hash, 0, 12) . '.' . $ext;
    }

    private function sanitizeJobId(): string
    {
        $raw = (string)$this->jobId;
        $safe = preg_replace('/[^A-Za-z0-9_-]+/', '_', $raw);
        return $safe === '' ? 'default' : $safe;
    }

    private function moveIntoPlace(string $tmp, string $final): bool
    {
        if (is_file($final) && filesize($final) > 0) {
            // Same hash already on disk — reuse. The @unlink is done
            // by the caller on the "existing hash" branch.
            @unlink($tmp);
            return true;
        }
        return @rename($tmp, $final);
    }

    private function publicUrlFor(string $absolutePath): ?string
    {
        // With the real media root, mw()'s URL manager knows how to
        // map the absolute path back to a public URL. When tests
        // (or custom deployments) override the root, fall back to a
        // conventional `/userfiles/media/...` path derived from the
        // absolute location — mw() would point at the wrong docroot.
        if (!$this->storageRootIsCustom && function_exists('mw')) {
            $url = UrlManager::link_to_file($absolutePath);
            return is_string($url) && $url !== '' ? $url : null;
        }

        $rel = substr($absolutePath, strlen($this->storageRoot));
        return '/userfiles/media/' . str_replace(DIRECTORY_SEPARATOR, '/', $rel);
    }

    /**
     * Best-effort extension sniff for URLs where the path didn't
     * carry a usable extension. We peek at the first few bytes of
     * the downloaded file and map recognised image/audio/video
     * magic numbers to extensions; anything unknown returns null,
     * which the caller treats as "don't rehost this".
     *
     * Why not `mime_content_type()` / `finfo`: both are available
     * on most PHP builds but their exact mapping varies by system
     * (`image/svg` vs `image/svg+xml`, PDF magic length, etc.)
     * and the fixture test wants deterministic behaviour. Sniffing
     * by the handful of signatures we actually care about is
     * simpler and faster.
     */
    private function sniffExtension(string $path): ?string
    {
        $fh = @fopen($path, 'rb');
        if ($fh === false) {
            return null;
        }
        $head = fread($fh, 16) ?: '';
        fclose($fh);
        if ($head === '') {
            return null;
        }

        // Binary signatures
        if (str_starts_with($head, "\xFF\xD8\xFF"))             return 'jpg';
        if (str_starts_with($head, "\x89PNG\r\n\x1A\n"))        return 'png';
        if (str_starts_with($head, 'GIF87a') || str_starts_with($head, 'GIF89a')) return 'gif';
        if (str_starts_with($head, '%PDF'))                     return 'pdf';
        if (str_starts_with($head, "ID3") || (isset($head[0], $head[1]) && $head[0] === "\xFF" && (ord($head[1]) & 0xE0) === 0xE0)) return 'mp3';

        // RIFF-wrapped families (WebP, WAV) share a 4-byte magic;
        // disambiguate with the trailing 4-byte form code.
        if (str_starts_with($head, 'RIFF') && strlen($head) >= 12) {
            $form = substr($head, 8, 4);
            if ($form === 'WEBP') return 'webp';
            if ($form === 'WAVE') return 'wav';
        }

        // Textual SVG sniff — first non-whitespace bytes are '<'.
        $ltrim = ltrim($head);
        if (stripos($ltrim, '<?xml') === 0 || stripos($ltrim, '<svg') === 0) {
            return 'svg';
        }

        return null;
    }

    private function guessMediaType(string $ext): string
    {
        if (in_array($ext, self::IMAGE_EXTENSIONS, true)) {
            return 'picture';
        }
        if (in_array($ext, self::VIDEO_EXTENSIONS, true)) {
            return 'video';
        }
        if (in_array($ext, self::AUDIO_EXTENSIONS, true)) {
            return 'audio';
        }
        return 'file';
    }

    private static function saveMediaRow(array $data): ?int
    {
        $result = save_media($data);
        if (is_int($result)) {
            return $result;
        }
        if (is_array($result) && isset($result['id'])) {
            return (int)$result['id'];
        }
        if (is_numeric($result)) {
            return (int)$result;
        }
        return null;
    }
}
