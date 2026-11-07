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
 * Served from cdnjs at a pinned version, loaded with `defer` so it never blocks
 * render; ScrollTrigger is registered on DOMContentLoaded (after both deferred
 * scripts have run), before any page/agent JS fires. The agents detect these as
 * loaded libraries (collectLibraries → the "[Loaded JS libraries…]" prompt hint)
 * and use them only when present.
 *
 * Opt out with the `load_motion_libs` website option set to a falsey string
 * (n / no / 0 / false / off).
 */
class MotionLibsHeadTags implements TagInterface, \Stringable
{
    private const GSAP = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js';
    private const SCROLLTRIGGER = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js';

    public function toHtml(): string
    {
        if (!$this->enabled()) {
            return '';
        }

        return '<script defer src="' . self::GSAP . '" crossorigin="anonymous" referrerpolicy="no-referrer" data-mw-motion-lib="gsap"></script>'
            . '<script defer src="' . self::SCROLLTRIGGER . '" crossorigin="anonymous" referrerpolicy="no-referrer" data-mw-motion-lib="scrolltrigger"></script>'
            . '<script>window.addEventListener("DOMContentLoaded",function(){try{if(window.gsap&&window.ScrollTrigger){gsap.registerPlugin(ScrollTrigger);}}catch(e){}});</script>';
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
        return Meta::PLACEMENT_HEAD;
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
