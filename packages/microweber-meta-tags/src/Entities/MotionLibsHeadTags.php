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
            . '<script id="mw-motion-kit">' . $this->kitJs() . '</script>';
    }

    /**
     * The "motion kit": a tiny, tested, data-attribute-driven motion engine that
     * ships on every page. The agents add attributes/classes and get correct,
     * reduced-motion-safe motion WITHOUT hand-writing GSAP:
     *   [data-mw-reveal]            fade + rise as it scrolls into view (GSAP
     *                               ScrollTrigger; CSS fallback). Options:
     *                               data-mw-reveal-y="48", data-mw-reveal-stagger
     *                               (stagger the element's direct children).
     *   [data-mw-parallax="0.3"]    ONE rAF loop drifts the element by scroll +
     *                               cursor (depth 0..1). Decorative layers.
     *   [data-mw-tilt]              subtle cursor tilt (same rAF loop).
     * Registers ScrollTrigger, re-scans on DOM changes (Live-Edit inserts), and
     * degrades to a plain fade when gsap is absent or reduced-motion is on.
     */
    private function kitJs(): string
    {
        return <<<'JS'
(function(){
  "use strict";
  try{ if(window.gsap&&window.ScrollTrigger&&gsap.registerPlugin){gsap.registerPlugin(ScrollTrigger);} }catch(e){}
  var reduce=false; try{ reduce=window.matchMedia&&window.matchMedia("(prefers-reduced-motion: reduce)").matches; }catch(e){}
  var gsapOk=function(){ return typeof window.gsap!=="undefined"; };
  var stOk=function(){ return typeof window.ScrollTrigger!=="undefined"; };
  function inView(el){ try{ var r=el.getBoundingClientRect(); return r.top < (window.innerHeight||document.documentElement.clientHeight||800)*0.92 && r.bottom > 0; }catch(e){ return true; } }
  // Reveals use IntersectionObserver (rock-solid for one-shot enter, works with
  // dynamic inserts + programmatic scroll); GSAP only does the tween. ScrollTrigger
  // stays loaded for the agent's own scrub/timeline animations via apply_js.
  var _io=null;
  function io(){ if(_io) return _io; try{ _io=new IntersectionObserver(function(es){ es.forEach(function(e){ if(e.isIntersecting){ _io.unobserve(e.target); if(e.target.__mwRun){ e.target.__mwRun(); } } }); },{threshold:0.12}); }catch(e){ _io=null; } return _io; }
  function reveals(root){
    var els=(root||document).querySelectorAll("[data-mw-reveal], .mw-reveal");
    Array.prototype.forEach.call(els,function(el){
      if(el.__mwRev) return; el.__mwRev=1;
      var y=parseInt(el.getAttribute("data-mw-reveal-y")||"32",10);
      var stag=el.hasAttribute("data-mw-reveal-stagger");
      var kids=stag?el.children:null, done=false;
      if(reduce||!gsapOk()){ el.style.opacity="1"; el.style.transform="none"; if(kids){Array.prototype.forEach.call(kids,function(k){k.style.opacity="1";k.style.transform="none";});} return; }
      gsap.set(el,{opacity:0,y:stag?0:y}); if(kids){gsap.set(kids,{opacity:0,y:y});}
      var run=function(){ if(done)return; done=true;
        gsap.to(el,{opacity:1,y:0,duration:0.7,ease:"power3.out"});
        if(kids){ gsap.to(kids,{opacity:1,y:0,duration:0.7,ease:"power3.out",stagger:0.08}); }
      };
      el.__mwRun=run;
      // Above-the-fold / already-in-view: reveal now. Below the fold: reveal when
      // it scrolls into view via IntersectionObserver.
      if(inView(el)){ run(); } else { var o=io(); if(o){ o.observe(el); } else { run(); } }
    });
  }
  var pxEls=[], tiltEls=[], mouse={x:0,y:0}, cur={x:0,y:0}, running=false;
  function collect(root){
    Array.prototype.forEach.call((root||document).querySelectorAll("[data-mw-parallax]"),function(el){ if(!el.__mwPx){el.__mwPx=1;pxEls.push(el);} });
    Array.prototype.forEach.call((root||document).querySelectorAll("[data-mw-tilt]"),function(el){ if(!el.__mwTilt){el.__mwTilt=1;tiltEls.push(el);} });
  }
  function loop(){
    cur.x+=(mouse.x-cur.x)*0.06; cur.y+=(mouse.y-cur.y)*0.06;
    var sy=window.pageYOffset||document.documentElement.scrollTop||0;
    var i,el,d;
    for(i=0;i<pxEls.length;i++){ el=pxEls[i]; d=parseFloat(el.getAttribute("data-mw-parallax"))||0.2; el.style.transform="translate3d("+(cur.x*d*40).toFixed(2)+"px,"+((cur.y*d*40)+(sy*d*-0.12)).toFixed(2)+"px,0)"; }
    for(i=0;i<tiltEls.length;i++){ el=tiltEls[i]; d=parseFloat(el.getAttribute("data-mw-tilt"))||1; el.style.transform="perspective(800px) rotateY("+(cur.x*4*d).toFixed(2)+"deg) rotateX("+(-cur.y*4*d).toFixed(2)+"deg)"; }
    requestAnimationFrame(loop);
  }
  function startRaf(){
    if(running||reduce||(!pxEls.length&&!tiltEls.length)) return; running=true;
    window.addEventListener("mousemove",function(e){ mouse.x=(e.clientX/window.innerWidth-0.5)*2; mouse.y=(e.clientY/window.innerHeight-0.5)*2; },{passive:true});
    requestAnimationFrame(loop);
  }
  function scan(root){ try{ reveals(root); collect(root); startRaf(); }catch(e){} }
  function boot(){
    scan(document);
    try{ var mo=new MutationObserver(function(m){ var a=false; m.forEach(function(x){ if(x.addedNodes&&x.addedNodes.length)a=true; }); if(a)scan(document); }); mo.observe(document.body||document.documentElement,{childList:true,subtree:true}); }catch(e){}
  }
  if(document.readyState==="loading"){ window.addEventListener("DOMContentLoaded",boot); } else { boot(); }
  window.mwMotionKit={ scan:scan };
})();
JS;
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
