<?php

namespace Modules\WordPressMigration\Services\Media;

/**
 * Concrete {@see MediaRehoster} that downloads remote assets via
 * Microweber's HTTP client and records them on the `media` table
 * through `save_media()`.
 *
 * This is the production-path rehoster. The unit tests use a
 * hand-rolled fake instead of this class because the class's
 * value-add is orchestrating three runtime globals
 * (`app()->http`, `save_media()`, `media_base_path()`), so mocking
 * them out inside the test leaves nothing worth testing here.
 * Behavioral coverage lives in the integration test that wires
 * the rehoster through {@see \Modules\WordPressMigration\Services\WordPressContentMapper}.
 *
 * Storage layout
 * --------------
 * Files land under:
 *   `userfiles/media/downloaded/<rel_type>/external/<host>/<safe-basename>`
 *
 * which is the same path convention `MediaManager::save()` uses
 * when its `allow_remote_download` branch fires, so an operator
 * inspecting `userfiles/media/` can't tell WP-imported files
 * apart from files the regular media picker downloaded.
 *
 * Assets this class WILL rehost (returns a rewritten URL):
 *   - http(s) URLs whose path ends in a whitelisted image/doc ext
 *
 * Assets this class WON'T rehost (returns null, URL left alone):
 *   - non-http(s) schemes (mailto:, tel:, javascript:, data:)
 *   - paths without a usable extension (category archives,
 *     in-site links, API endpoints)
 *   - URLs whose download fails for any reason
 */
class MicroweberMediaRehoster implements MediaRehoster
{
    private const ASSET_EXTENSIONS = [
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif',
        'mp4', 'webm', 'mov', 'mp3', 'wav', 'ogg',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip',
    ];

    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];
    private const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov'];
    private const AUDIO_EXTENSIONS = ['mp3', 'wav', 'ogg'];

    /** @var callable(string $url, string $targetPath): bool */
    private $downloader;

    /**
     * @param callable(string $url, string $targetPath): bool|null $downloader
     *   Override for the bytes-fetcher. Defaults to
     *   `app()->http->url($url)->download($target)`. Tests pass a
     *   closure that copies a fixture file so the production path
     *   is exercised without hitting the network.
     */
    public function __construct(?callable $downloader = null)
    {
        $this->downloader = $downloader ?? fn (string $url, string $target): bool
            => (bool) app()->http->url($url)->download($target);
    }

    public function rehost(string $url, array $context = []): ?string
    {
        $parsed = parse_url($url);
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

        $hostDir = str_replace(['www.', '.'], ['', '-'], strtolower((string)$parsed['host']));
        $relType = isset($context['rel_type']) ? (string)$context['rel_type'] : 'Modules\\Content\\Models\\Content';
        $relTypeSafe = sanitize_path($relType);
        $relId = isset($context['rel_id']) ? (string)$context['rel_id'] : '0';

        $safeBase = preg_replace('/[^\w\._-]+/', '_', basename($path)) ?: 'file';
        $dir = media_base_path() . 'downloaded' . DS . $relTypeSafe . DS . 'external' . DS . $hostDir . DS;
        if (!is_dir($dir)) {
            mkdir_recursive($dir);
        }
        $target = $dir . $safeBase;

        if (!is_file($target) || filesize($target) === 0) {
            try {
                $ok = ($this->downloader)($url, $target);
            } catch (\Throwable) {
                return null;
            }
            if (!$ok || !is_file($target) || filesize($target) === 0) {
                @unlink($target);
                return null;
            }
        }

        $publicUrl = app()->url_manager->link_to_file($target);
        if (!is_string($publicUrl) || $publicUrl === '') {
            return null;
        }

        save_media([
            'rel_type' => $relType,
            'rel_id' => $relId,
            'media_type' => $this->guessMediaType($ext),
            'filename' => $publicUrl,
            'title' => basename($path),
        ]);

        return $publicUrl;
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
}
