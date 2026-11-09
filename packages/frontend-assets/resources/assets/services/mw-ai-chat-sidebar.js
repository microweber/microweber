/**
 * MwAiChatSidebar — a global, surface-agnostic AI chat sidebar service.
 *
 * Decouples the AI conversation panel (MwAiConversation) from the Live-Edit
 * canvas so ANY surface — the Live-Edit toolbar, the Filament admin panel, a
 * module settings screen — can open the same chat by emitting an event:
 *
 *   window.dispatchEvent(new CustomEvent('mw:ai-chat:open', { detail: {
 *       agentType: 'liveedit' | 'general' | 'content' | 'shop' | ...,
 *       contentId: <int>,            // optional page/content context
 *       title:     'AI assistant',   // optional header label
 *       canvas:    true|false,       // whether canvas-coupled ops apply (default: agentType==='liveedit')
 *   }}));
 *   window.dispatchEvent(new CustomEvent('mw:ai-chat:close'));
 *   window.dispatchEvent(new CustomEvent('mw:ai-chat:toggle', { detail: {...} }));
 *
 * It mounts one right-docked drawer lazily, (re)creates the conversation for the
 * requested agent/context, and emits 'mw:ai-chat:opened' / 'mw:ai-chat:closed'
 * back out. Exposed as window.mw.aiChat for direct calls too.
 */

import { MwAiConversation } from "../components/mw-ai-conversation.js";
// Swarm mode: the client lane-router (window.mw.aiSwarm) + the build board
// (window.mwAiSwarmBoard). Imported here so both the admin sidebar bundle and the
// Live-Edit app get them wherever the AI box mounts.
import "./mw-ai-swarm.js";
import "../components/mw-ai-swarm-board.js";
// Live per-agent cursors on the canvas (window.mwAiSwarmCursors).
import "../components/mw-ai-swarm-cursors.js";

const SIDEBAR_CSS = `
:root{ --mw-ai-sidebar-width:420px; }
.mw-ai-chat-sidebar{
    position:fixed; top:0; right:0; height:100vh; width:var(--mw-ai-sidebar-width); max-width:100vw;
    background:#fff; color:#182433; z-index:2147483000;
    box-shadow:-8px 0 40px rgba(24,36,51,.14);
    transform:translateX(100%); transition:transform .28s cubic-bezier(0.22,1,0.36,1);
    display:flex; flex-direction:column; border-left:1px solid #18243314;
}
html.dark .mw-ai-chat-sidebar{ background:#1c2027; color:#e8eaed; border-left-color:#ffffff14; }
.mw-ai-chat-sidebar.mw-ai-chat-open{ transform:translateX(0); }
.mw-ai-chat-sidebar-head{
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 14px; border-bottom:1px solid #18243312; flex:0 0 auto;
}
html.dark .mw-ai-chat-sidebar-head{ border-bottom-color:#ffffff12; }
.mw-ai-chat-sidebar-title{ font-weight:600; font-size:13px; letter-spacing:-.01em; flex:0 0 auto; }
.mw-ai-chat-sidebar-head-spacer{ flex:1 1 auto; }

/* Header "mode" dropdown — AI Chat (primary) vs Quick edit (the classic field
   editor). Only shown in a Live-Edit/canvas context; hidden on admin surfaces. */
.mw-ai-chat-sidebar-modes{ position:relative; flex:0 0 auto; }
.mw-ai-chat-sidebar.is-admin .mw-ai-chat-sidebar-modes{ display:none; }
.mw-ai-chat-sidebar-modes-toggle{
    display:inline-flex; align-items:center; gap:6px; height:32px; padding:0 10px;
    border:1px solid #18243320; border-radius:8px; background:transparent; color:inherit;
    font:inherit; font-size:12px; font-weight:600; cursor:pointer;
    transition:background-color .15s ease,border-color .15s ease;
}
.mw-ai-chat-sidebar-modes-toggle:hover{ background:#18243310; }
html.dark .mw-ai-chat-sidebar-modes-toggle{ border-color:#ffffff22; }
html.dark .mw-ai-chat-sidebar-modes-toggle:hover{ background:#ffffff14; }
.mw-ai-chat-sidebar-modes-toggle svg{ width:12px; height:12px; opacity:.7; transition:transform .18s ease; }
.mw-ai-chat-sidebar-modes[data-open="1"] .mw-ai-chat-sidebar-modes-toggle svg{ transform:rotate(180deg); }
.mw-ai-chat-sidebar-modes-menu{
    position:absolute; right:0; top:calc(100% + 6px); min-width:168px; z-index:5; padding:6px;
    background:#fff; color:#182433; border:1px solid #18243316; border-radius:10px;
    box-shadow:0 12px 32px rgba(24,36,51,.18); display:none;
}
html.dark .mw-ai-chat-sidebar-modes-menu{ background:#23272f; color:#e8eaed; border-color:#ffffff1a; }
.mw-ai-chat-sidebar-modes[data-open="1"] .mw-ai-chat-sidebar-modes-menu{ display:block; }
.mw-ai-chat-sidebar-modes-item{
    display:flex; align-items:center; gap:8px; width:100%; min-height:38px; padding:8px 10px;
    border:none; border-radius:7px; background:transparent; color:inherit; font:inherit; font-size:13px;
    text-align:left; cursor:pointer; transition:background-color .12s ease;
}
.mw-ai-chat-sidebar-modes-item:hover{ background:#18243310; }
html.dark .mw-ai-chat-sidebar-modes-item:hover{ background:#ffffff12; }
.mw-ai-chat-sidebar-modes-item[aria-current="true"]{ color:rgb(var(--primary-600, 13 110 253)); font-weight:600; }
.mw-ai-chat-sidebar-modes-item .tick{ margin-left:auto; opacity:0; }
.mw-ai-chat-sidebar-modes-item[aria-current="true"] .tick{ opacity:1; }
.mw-ai-chat-sidebar-close{
    width:34px; height:34px; min-width:34px; border:none; border-radius:9px; cursor:pointer;
    background:transparent; color:inherit; font-size:18px; line-height:1;
    display:inline-flex; align-items:center; justify-content:center;
    transition:background-color .15s ease;
}
.mw-ai-chat-sidebar-close:hover{ background:#18243310; }
html.dark .mw-ai-chat-sidebar-close:hover{ background:#ffffff14; }
.mw-ai-chat-sidebar-body{ flex:1 1 auto; min-height:0; padding:12px 14px; display:flex; }
.mw-ai-chat-sidebar-body > .mw-ai-conv{ flex:1 1 auto; }

/* Backdrop: NO dimming/blocking on desktop — the sidebar docks beside a
   resized canvas, so the site stays fully visible, scrollable and editable in
   real time. A click-catcher backdrop only appears on narrow viewports where
   the drawer goes full-width. */
.mw-ai-chat-backdrop{
    position:fixed; inset:0; background:rgba(24,36,51,.28); z-index:2147482999;
    opacity:0; pointer-events:none; transition:opacity .2s ease;
}
@media (max-width:640px){
    .mw-ai-chat-sidebar{ width:100vw; }
    .mw-ai-chat-backdrop.mw-ai-chat-open{ opacity:1; pointer-events:auto; }
}

/* Push & resize the host surface so the drawer reflows the layout instead of
   overlaying it (desktop only). */
@media (min-width:641px){
    /* LIVE EDIT: the drawer docks INSIDE the canvas region — exactly like the
       in-canvas template-settings panel. The page (and its toolbar: ADD / SAVE /
       PREVIEW) stays FULL WIDTH so the toolbar is never squashed and SAVE is
       never hidden; the drawer starts BELOW the toolbar (--mw-ai-le-top, measured
       on open) and only the canvas holder shrinks to the drawer's left. */
    html.mw-ai-chat-sidebar-visible.mw-ai-chat-liveedit .mw-ai-chat-sidebar{
        top:var(--mw-ai-le-top, 60px) !important;
        height:calc(100vh - var(--mw-ai-le-top, 60px)) !important;
        /* Dock BELOW the toolbar's stacking context (the toolbar bar is a
           z-index:999 stacking context, so its own dropdowns — resolution,
           language, ⋮ overflow, page chip — are capped at that level). The
           modal z-index:2147483000 above would otherwise clip those menus
           behind the docked chat. Here the drawer lives in its own right-hand
           column below the toolbar (top:60px), so a lower z still sits above the
           canvas (z:auto) while letting the toolbar menus open over it. Only the
           desktop LIVE-EDIT dock is lowered; the narrow-viewport modal + admin
           push mode keep the high z-index from the base rule above. */
        z-index:990 !important;
    }
    html.mw-ai-chat-sidebar-visible.mw-ai-chat-liveedit #live-edit-frame-holder{
        width:auto !important;
        right:var(--mw-ai-sidebar-width) !important;
        transition:right .28s cubic-bezier(0.22,1,0.36,1);
    }
    /* ADMIN (non-Live-Edit Filament surfaces: dashboard, settings, …): the drawer
       is full height and the whole body is pushed so nothing hides under it. */
    html.mw-ai-chat-sidebar-visible:not(.mw-ai-chat-liveedit) .fi-layout{
        padding-inline-end:var(--mw-ai-sidebar-width);
        transition:padding .28s cubic-bezier(0.22,1,0.36,1);
    }
}
@media (prefers-reduced-motion: reduce){
    .mw-ai-chat-sidebar{ transition:none; }
    #live-edit-frame-holder, .fi-layout{ transition:none !important; }
}
`;

class MwAiChatSidebar {
    constructor() {
        this.drawer = null;
        this.backdrop = null;
        this.body = null;
        this.titleEl = null;
        this.conversation = null;
        this.isOpen = false;
        this.current = {}; // the detail of the last open (agentType, contentId, canvas)
        this._wireEvents();
    }

    _wireEvents() {
        if (typeof window === "undefined") {
            return;
        }
        window.addEventListener("mw:ai-chat:open", (e) => this.open(e && e.detail ? e.detail : {}));
        window.addEventListener("mw:ai-chat:close", () => this.close());
        window.addEventListener("mw:ai-chat:toggle", (e) => this.toggle(e && e.detail ? e.detail : {}));
        // Esc closes the sidebar (when it's the top-most overlay).
        window.addEventListener("keydown", (e) => {
            if (this.isOpen && e.key === "Escape") {
                this.close();
            }
        });
    }

    _lang(s) {
        try { return (window.mw && mw.lang) ? mw.lang(s) : s; } catch (e) { return s; }
    }

    _ensureDrawer() {
        if (this.drawer) {
            return;
        }
        const style = document.createElement("style");
        style.setAttribute("data-mw-ai-chat-sidebar", "");
        style.textContent = SIDEBAR_CSS;
        document.head.appendChild(style);

        this.backdrop = document.createElement("div");
        this.backdrop.className = "mw-ai-chat-backdrop";
        this.backdrop.addEventListener("click", () => this.close());

        this.drawer = document.createElement("div");
        this.drawer.className = "mw-ai-chat-sidebar";
        this.drawer.setAttribute("role", "complementary");
        this.drawer.setAttribute("aria-label", this._lang("AI assistant"));
        this.drawer.innerHTML =
            '<div class="mw-ai-chat-sidebar-head">' +
            '  <div class="mw-ai-chat-sidebar-title"></div>' +
            '  <div class="mw-ai-chat-sidebar-head-spacer"></div>' +
            '  <div class="mw-ai-chat-sidebar-modes">' +
            '    <button type="button" class="mw-ai-chat-sidebar-modes-toggle" aria-haspopup="true" aria-expanded="false">' +
            '      <span class="mw-ai-chat-sidebar-modes-label">' + this._lang("AI Chat") + "</span>" +
            '      <svg viewBox="0 0 16 16" aria-hidden="true"><path d="M4 6l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>' +
            "    </button>" +
            '    <div class="mw-ai-chat-sidebar-modes-menu" role="menu">' +
            '      <button type="button" class="mw-ai-chat-sidebar-modes-item" role="menuitem" data-mode="chat" aria-current="true">' + this._lang("AI Chat") + '<span class="tick" aria-hidden="true">✓</span></button>' +
            '      <button type="button" class="mw-ai-chat-sidebar-modes-item" role="menuitem" data-mode="fields">' + this._lang("Quick edit") + '<span class="tick" aria-hidden="true">✓</span></button>' +
            "    </div>" +
            "  </div>" +
            '  <button type="button" class="mw-ai-chat-sidebar-close" aria-label="' + this._lang("Close") + '">✕</button>' +
            "</div>" +
            '<div class="mw-ai-chat-sidebar-body"></div>';

        this.titleEl = this.drawer.querySelector(".mw-ai-chat-sidebar-title");
        this.body = this.drawer.querySelector(".mw-ai-chat-sidebar-body");
        this.drawer.querySelector(".mw-ai-chat-sidebar-close").addEventListener("click", () => this.close());

        // Header mode dropdown: AI Chat (primary) vs Quick edit (classic field
        // editor). Opening Quick edit closes the chat and opens the Live-Edit
        // field panel; the dropdown is hidden on admin (non-canvas) surfaces.
        this.modes = this.drawer.querySelector(".mw-ai-chat-sidebar-modes");
        const modesToggle = this.drawer.querySelector(".mw-ai-chat-sidebar-modes-toggle");
        if (this.modes && modesToggle) {
            const closeMenu = () => { this.modes.removeAttribute("data-open"); modesToggle.setAttribute("aria-expanded", "false"); };
            modesToggle.addEventListener("click", (e) => {
                e.stopPropagation();
                if (this.modes.getAttribute("data-open") === "1") { closeMenu(); }
                else { this.modes.setAttribute("data-open", "1"); modesToggle.setAttribute("aria-expanded", "true"); }
            });
            this.modes.querySelectorAll(".mw-ai-chat-sidebar-modes-item").forEach((item) => {
                item.addEventListener("click", (e) => {
                    e.stopPropagation();
                    closeMenu();
                    if (item.dataset.mode === "fields") { this._openQuickEdit(); }
                });
            });
            document.addEventListener("click", (e) => { if (this.modes && !this.modes.contains(e.target)) { closeMenu(); } });
        }

        document.body.appendChild(this.backdrop);
        document.body.appendChild(this.drawer);
    }

    _needsNewConversation(opts) {
        if (!this.conversation) {
            return true;
        }
        // Re-create when the agent or the page context changes.
        return this.current.agentType !== (opts.agentType || this.current.agentType)
            || (typeof opts.contentId !== "undefined" && this.current.contentId !== opts.contentId);
    }

    _mountConversation(opts) {
        if (!this._needsNewConversation(opts)) {
            return;
        }
        if (this.conversation && this.conversation.root && this.conversation.root.parentNode) {
            this.conversation.root.parentNode.removeChild(this.conversation.root);
        }
        const agentType = opts.agentType || "general";
        this.conversation = new MwAiConversation({
            contentId: opts.contentId || 0,
            agentType: agentType,
            // Canvas-coupled behaviour (apply edits to the live canvas, screenshot,
            // save-canvas, self-verify loop) only in a Live-Edit context.
            canvas: typeof opts.canvas === "boolean" ? opts.canvas : (agentType === "liveedit"),
        });
        this.body.appendChild(this.conversation.root);
    }

    // Switch to the classic field editor ("Quick edit"): close the chat and open
    // the Live-Edit quick-edit panel. No-op on surfaces without liveEditWidgets.
    _openQuickEdit() {
        try {
            var le = null;
            try { le = window.mw && mw.top && mw.top().app && mw.top().app.liveEditWidgets; } catch (e) {}
            this.close();
            if (le && typeof le.openQuickEditComponent === "function") { le.openQuickEditComponent(); }
        } catch (e) {}
    }

    open(opts = {}) {
        this._ensureDrawer();
        this._mountConversation(opts);
        this.current = Object.assign({}, this.current, opts);
        if (this.titleEl) {
            this.titleEl.textContent = opts.title || this._lang("AI assistant");
        }
        // The "Quick edit" mode only exists on the Live-Edit canvas; hide the
        // mode dropdown on admin (non-canvas) surfaces.
        try {
            var agentType = opts.agentType || this.current.agentType || "general";
            var isCanvas = (typeof opts.canvas === "boolean") ? opts.canvas : (agentType === "liveedit");
            this.drawer.classList.toggle("is-admin", !isCanvas);
        } catch (e) {}
        // Backdrop only on narrow viewports; on wide screens the sidebar docks.
        this.backdrop.classList.add("mw-ai-chat-open");
        this.drawer.classList.add("mw-ai-chat-open");
        document.documentElement.classList.add("mw-ai-chat-sidebar-visible");
        // On the Live-Edit surface, dock the drawer BELOW the page toolbar and
        // inside the canvas region (so ADD/SAVE/PREVIEW stay full-width); on admin
        // it stays full-height and pushes the whole body. Detect by the Live-Edit
        // page + canvas holder, and measure the toolbar height (holder top).
        try {
            var leHolder = document.getElementById("live-edit-frame-holder");
            var lePage = document.querySelector(".mw-live-edit-page");
            if (leHolder && lePage) {
                var top = Math.max(0, Math.round(leHolder.getBoundingClientRect().top));
                document.documentElement.style.setProperty("--mw-ai-le-top", top + "px");
                document.documentElement.classList.add("mw-ai-chat-liveedit");
            } else {
                document.documentElement.classList.remove("mw-ai-chat-liveedit");
            }
        } catch (e) {}
        this.isOpen = true;
        try { this.conversation && this.conversation.input && this.conversation.input.focus(); } catch (e) {}
        try { window.dispatchEvent(new CustomEvent("mw:ai-chat:opened", { detail: this.current })); } catch (e) {}
    }

    close() {
        if (this.drawer) {
            this.drawer.classList.remove("mw-ai-chat-open");
        }
        if (this.backdrop) {
            this.backdrop.classList.remove("mw-ai-chat-open");
        }
        document.documentElement.classList.remove("mw-ai-chat-sidebar-visible");
        document.documentElement.classList.remove("mw-ai-chat-liveedit");
        this.isOpen = false;
        try { window.dispatchEvent(new CustomEvent("mw:ai-chat:closed")); } catch (e) {}
    }

    toggle(opts = {}) {
        if (this.isOpen) {
            this.close();
        } else {
            this.open(opts);
        }
    }
}

// Singleton across bundles: the admin panel injects this service as a standalone
// IIFE while the Live-Edit app imports it as a module — in the Live-Edit page
// BOTH run in the same window. Reuse any instance already registered so we don't
// mount two drawers / double-wire the open events.
let mwAiChatSidebar;
if (typeof window !== "undefined" && window.mw && window.mw.aiChat && typeof window.mw.aiChat.open === "function") {
    mwAiChatSidebar = window.mw.aiChat;
} else {
    mwAiChatSidebar = new MwAiChatSidebar();
    if (typeof window !== "undefined") {
        window.mw = window.mw || {};
        window.mw.aiChat = mwAiChatSidebar;
    }
}

export { MwAiChatSidebar };
export default mwAiChatSidebar;
