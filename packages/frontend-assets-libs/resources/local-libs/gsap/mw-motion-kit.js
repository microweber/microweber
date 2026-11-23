/*!
 * Microweber Motion Kit
 * A tiny, tested, data-attribute-driven motion engine that ships on every page.
 * Agents (AI Live-Edit / swarm) add attributes/classes and get correct,
 * reduced-motion-safe motion WITHOUT hand-writing GSAP:
 *   [data-mw-reveal]            fade + rise as it scrolls into view (GSAP tween;
 *                               CSS fallback). Options: data-mw-reveal-y="48",
 *                               data-mw-reveal-stagger (stagger direct children).
 *   [data-mw-parallax="0.3"]    ONE rAF loop drifts the element by scroll +
 *                               cursor (depth 0..1). Decorative layers.
 *   [data-mw-tilt]              subtle cursor tilt (same rAF loop).
 * Registers ScrollTrigger, re-scans on DOM changes (Live-Edit inserts), and
 * degrades to a plain fade when gsap is absent or reduced-motion is on.
 *
 * This is the single source of truth for the kit. The served bundle
 * (mw-motion.js) is gsap.min.js + ScrollTrigger.min.js + this file, concatenated
 * by build-mw-motion.mjs. Edit here, then run that script to rebuild the bundle.
 */
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
