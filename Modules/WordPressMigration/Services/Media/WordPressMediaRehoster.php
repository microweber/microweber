<?php

namespace Modules\WordPressMigration\Services\Media;

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
 *
 * Assets this class WON'T rehost (returns null):
 *   - non-http(s) schemes (mailto:, tel:, javascript:, data:)
 *   - protocol-relative URLs (`//host/file.jpg`) — caller must resolve
 *   - paths without a usable extension
 *   - URLs whose download fails or returns zero bytes
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
     * stock deployments go through `mw()->url_manager`; overrides get
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
     *   `mw()->http->url($url)->download($target)`. Tests pass a
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
            => (bool) mw()->http->url($url)->download($target);

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

        $parsed = parse_url($trimmed);
        if (!is_array($parsed) || !isset($parsed['scheme'], $parsed['host'])) {
            return null;
        }
        $scheme = strtolower($parsed['scheme']);
        if ($scheme !== 'http' && $scheme !== 'https') {
            return null;
        }

        $path = $parsed['path'] ?? '';
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === '' || !in_array($ext, self::ASSET_EXTENSIONS, true)) {
            return null;
        }

        $tmp = $this->makeTempPath($ext);
        if (!$this->download($trimmed, $tmp)) {
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
        return $receipt;
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
            $url = mw()->url_manager->link_to_file($absolutePath);
            return is_string($url) && $url !== '' ? $url : null;
        }

        $rel = substr($absolutePath, strlen($this->storageRoot));
        return '/userfiles/media/' . str_replace(DIRECTORY_SEPARATOR, '/', $rel);
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
