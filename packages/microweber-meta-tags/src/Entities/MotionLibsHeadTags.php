<?php

namespace MicroweberPackages\MetaTags\Entities;

use Butschster\Head\Contracts\MetaTags\Entities\TagInterface;
use Butschster\Head\MetaTags\Meta;

/**
 * Loads the GreenSock (GSAP) motion library + ScrollTrigger on the public
 * frontend of every template, so templates AND the AI Live-Edit / swarm agents
 * can drive rich "motion design" (GSAP timelines + ScrollTrigger for entrances
 * and scroll-driven reveals, a raw requestAnimationFrame loop for continuous
 * physics/displacement — the motionin.design pattern).
 *
 * Self-hosted from the frontend-assets-libs package as a SINGLE combined file:
 * mw-motion.js = gsap.min.js + ScrollTrigger.min.js + the data-attribute motion
 * kit (previously an inline <script>). Served from the project's own origin (no
 * cdnjs), so it is cacheable, CSP-friendly and offline-safe. Loaded with `defer`
 * so it never blocks render; because the kit is concatenated AFTER gsap +
 * ScrollTrigger it runs with both defined, registers the plugin, and boots on
 * DOMContentLoaded. The agents detect gsap as a loaded library (collectLibraries
 * → the "[Loaded JS libraries…]" prompt hint) and use it only when present.
 *
 * The bundle is built by
 * packages/frontend-assets-libs/resources/dist/gsap/build-mw-motion.mjs and
 * published to public/vendor/microweber-packages/frontend-assets-libs/gsap/.
 *
 * Opt out with the `load_motion_libs` website option set to a falsey string
 * (n / no / 0 / false / off).
 */
class MotionLibsHeadTags implements TagInterface, \Stringable
{
    /** Pinned GSAP version vendored into the bundle (see build-mw-motion.mjs). */
    public const GSAP_VERSION = '3.13.0';

    /** Published path of the single self-hosted bundle, relative to the web root. */
    private const BUNDLE = 'vendor/microweber-packages/frontend-assets-libs/gsap/mw-motion.js';

    public function toHtml(): string
    {
        if (!$this->enabled()) {
            return '';
        }

        return '<script defer src="' . $this->bundleUrl() . '" data-mw-motion-lib="gsap"></script>';
    }

    /**
     * The self-hosted bundle URL with a filemtime cache-buster so a rebuilt
     * bundle is picked up without a hard flush (un-versioned static JS otherwise
     * session-caches). Falls back to the pinned version when the file can't be
     * stat'd (e.g. assets served from a CDN/remote disk).
     */
    private function bundleUrl(): string
    {
        $base = function_exists('public_asset') ? public_asset() : (function_exists('site_url') ? site_url() : '/');
        $url = rtrim($base, '/') . '/' . self::BUNDLE;

        $version = self::GSAP_VERSION;
        try {
            if (function_exists('public_path')) {
                $file = public_path(self::BUNDLE);
                if (is_file($file)) {
                    $version = (string) filemtime($file);
                }
            }
        } catch (\Throwable $e) {
            // keep the pinned version as the cache key
        }

        return $url . '?v=' . $version;
    }

    private function enabled(): bool
    {
        try {
            if (!function_exists('get_option')) {
                return true;
            }
            $opt = get_option('load_motion_libs', 'website');
            if ($opt === false || $opt === null || $opt === '') {
                return true; // default ON
            }

            return !in_array(strtolower((string) $opt), ['n', 'no', '0', 'false', 'off'], true);
        } catch (\Throwable $e) {
            return true;
        }
    }

    public function getPlacement(): string
    {
        // Load at body-end (footer), not in <head>: the libs are deferred either
        // way, but keeping them out of the head avoids any head-render cost and
        // matches where the other frontend runtime scripts load. The inline kit
        // guards every GSAP call with gsapOk() at runtime and re-scans on
        // mutation, so loading after the DOM is fine.
        return Meta::PLACEMENT_FOOTER;
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    public function toArray(): array
    {
        return [
            'type' => 'motion_libs_head_tags',
        ];
    }
}
