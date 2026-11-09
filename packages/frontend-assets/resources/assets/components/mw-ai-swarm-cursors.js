/**
 * mw-ai-swarm-cursors — live per-agent cursors on the Live-Edit canvas.
 *
 * During a swarm build the board (mw-ai-swarm-board) shows each lane's progress,
 * but nothing showed WHERE each agent was working on the page. This draws one
 * labelled cursor per lane, in the lane's colour, floating over the canvas and
 * hopping to the element each tool frame touches — so the canvas visibly comes
 * alive with "agents" editing it.
 *
 * It is a pure overlay: a fixed layer in the TOP (Live-Edit) window, over the
 * canvas iframe, with pointer-events:none. It resolves an edit's target element
 * the same way the tools do (args.selector / args.target / id) and maps the
 * element's rect — which is relative to the iframe viewport — into top-window
 * coordinates via the iframe's own rect. Everything is best-effort and wrapped
 * in try/catch: if there is no canvas (e.g. the admin-panel chat) or an element
 * can't be found, the cursor simply idles or is skipped — it must NEVER break a
 * build. Honours prefers-reduced-motion.
 *
 * Singleton across bundles on window.mwAiSwarmCursors, mirroring the board.
 */

const CURSOR_SVG =
    '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">' +
    '<path d="M5 3l14 7-6 2-2 6-6-15z" fill="currentColor" stroke="#fff" stroke-width="1.2" stroke-linejoin="round"/>' +
    '</svg>';

class MwAiSwarmCursors {
    constructor() {
        this.layer = null;
        this.cursors = {};   // agentId -> { el, label, color, title, x, y, done }
        this.idx = 0;
        this.reduce = false;
        try {
            this.reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
        } catch (e) { /* no-op */ }
    }

    // ── canvas plumbing ───────────────────────────────────────────────────────

    _topDoc() {
        try { return (window.mw && mw.top) ? mw.top().doc : document; } catch (e) { return document; }
    }

    _canvasIframe() {
        try {
            const holder = this._topDoc().getElementById("live-edit-frame-holder");
            return holder ? holder.querySelector("iframe") : null;
        } catch (e) { return null; }
    }

    _canvasDoc() {
        try {
            if (window.mw && mw.top && mw.top().app && mw.top().app.canvas) {
                return mw.top().app.canvas.getDocument();
            }
        } catch (e) { /* fall through */ }
        const f = this._canvasIframe();
        try { return f ? (f.contentDocument || f.contentWindow.document) : null; } catch (e) { return null; }
    }

    _hasCanvas() {
        return !!(this._canvasIframe() && this._canvasDoc());
    }

    _ensureLayer() {
        if (this.layer && this.layer.isConnected) { return this.layer; }
        const doc = this._topDoc();
        if (doc.getElementById("mw-ai-swarm-cursor-layer")) {
            this.layer = doc.getElementById("mw-ai-swarm-cursor-layer");
            return this.layer;
        }
        if (!doc.getElementById("mw-ai-swarm-cursor-styles")) {
            const s = doc.createElement("style");
            s.id = "mw-ai-swarm-cursor-styles";
            s.textContent = `
#mw-ai-swarm-cursor-layer{ position:fixed; inset:0; z-index:2147483100; pointer-events:none; overflow:hidden; }
.mw-ai-swarm-cur{ position:absolute; top:0; left:0; transform:translate(-9999px,-9999px); will-change:transform; transition:transform .5s cubic-bezier(.22,1,.36,1), opacity .3s ease; opacity:0; }
.mw-ai-swarm-cur.is-live{ opacity:1; }
.mw-ai-swarm-cur-ptr{ color:var(--c,#0d6efd); filter:drop-shadow(0 2px 4px rgba(0,0,0,.3)); display:block; }
.mw-ai-swarm-cur-tag{ position:absolute; left:16px; top:16px; white-space:nowrap; font:600 11px/1.4 system-ui,-apple-system,sans-serif; color:#fff; background:var(--c,#0d6efd); padding:2px 8px; border-radius:7px; box-shadow:0 2px 8px rgba(0,0,0,.22); }
.mw-ai-swarm-cur.is-click .mw-ai-swarm-cur-ptr{ animation:mwSwarmCurClick .35s ease; }
@keyframes mwSwarmCurClick{ 0%{ transform:scale(1);} 40%{ transform:scale(.72);} 100%{ transform:scale(1);} }
@media (prefers-reduced-motion: reduce){ .mw-ai-swarm-cur{ transition:opacity .2s ease; } .mw-ai-swarm-cur.is-click .mw-ai-swarm-cur-ptr{ animation:none; } }
`;
            (doc.head || doc.documentElement).appendChild(s);
        }
        const layer = doc.createElement("div");
        layer.id = "mw-ai-swarm-cursor-layer";
        doc.body.appendChild(layer);
        this.layer = layer;
        return layer;
    }

    // ── public API ────────────────────────────────────────────────────────────

    /** Create a cursor per planned lane. Safe to call without a canvas (no-op). */
    start(plan) {
        try {
            if (!this._hasCanvas()) { return this; }
            this.destroy();
            const layer = this._ensureLayer();
            let lanes = (plan && (plan.lanes || (Array.isArray(plan) ? plan : []))) || [];
            const iframe = this._canvasIframe();
            const r = iframe ? iframe.getBoundingClientRect() : { left: 0, top: 0, width: 800, height: 600 };
            lanes.forEach((lane, i) => {
                const agentId = (lane && lane.agent_id != null) ? String(lane.agent_id) : ("lane-" + i);
                const title = (lane && lane.title) || ("Agent " + (i + 1));
                this._make(agentId, title, i, lanes.length, r);
            });
        } catch (e) { /* never break the build */ }
        return this;
    }

    _make(agentId, title, i, total, r) {
        const doc = this._topDoc();
        const color = this._color(i);
        const el = doc.createElement("div");
        el.className = "mw-ai-swarm-cur";
        el.style.setProperty("--c", color);
        el.innerHTML = '<span class="mw-ai-swarm-cur-ptr">' + CURSOR_SVG + "</span>" +
            '<span class="mw-ai-swarm-cur-tag"></span>';
        el.querySelector(".mw-ai-swarm-cur-tag").textContent = title;
        this.layer.appendChild(el);
        // Park cursors spread across the top of the canvas.
        const span = Math.max(1, total);
        const x = r.left + (r.width * (i + 0.5)) / span;
        const y = r.top + 48;
        const vm = { el, color, title, x, y, done: false };
        this.cursors[agentId] = vm;
        this._place(vm, x, y);
        // Fade in on the next frame.
        try { requestAnimationFrame(() => el.classList.add("is-live")); } catch (e) { el.classList.add("is-live"); }
    }

    /** A tool frame fired for this agent — hop the cursor to the edited element. */
    tool(agentId, edit) {
        try {
            const vm = this.cursors[agentId] || this._lazy(agentId, edit);
            if (!vm || vm.done) { return; }
            const target = this._resolveTarget(edit);
            if (target) {
                const p = this._pointFor(target);
                if (p) { this._place(vm, p.x, p.y); }
            }
            // Click pulse regardless (shows activity even for off-canvas work).
            vm.el.classList.remove("is-click");
            // Force reflow so the animation restarts.
            void vm.el.offsetWidth;
            vm.el.classList.add("is-click");
        } catch (e) { /* no-op */ }
    }

    /** Lane finished — fade its cursor out. */
    finishLane(agentId) {
        try {
            const vm = this.cursors[agentId];
            if (!vm) { return; }
            vm.done = true;
            vm.el.classList.remove("is-live");
            setTimeout(() => { try { vm.el.remove(); } catch (e) {} }, 400);
        } catch (e) { /* no-op */ }
    }

    /** Whole build finished — fade everything out. */
    finishAll() {
        try {
            Object.keys(this.cursors).forEach((id) => this.finishLane(id));
            setTimeout(() => this.destroy(), 600);
        } catch (e) { /* no-op */ }
    }

    destroy() {
        try {
            Object.values(this.cursors).forEach((vm) => { try { vm.el.remove(); } catch (e) {} });
        } catch (e) { /* no-op */ }
        this.cursors = {};
        if (this.layer) { this.layer.innerHTML = ""; }
    }

    // ── internals ─────────────────────────────────────────────────────────────

    _lazy(agentId, edit) {
        // A frame arrived for an agent we have no cursor for (e.g. the nav
        // subagent): make one on the fly if a canvas exists.
        if (!this._hasCanvas()) { return null; }
        try {
            this._ensureLayer();
            const iframe = this._canvasIframe();
            const r = iframe ? iframe.getBoundingClientRect() : { left: 0, top: 0, width: 800, height: 600 };
            const i = Object.keys(this.cursors).length;
            const title = (edit && edit.args && (edit.args.title || edit.args.url)) || agentId;
            this._make(String(agentId), String(title), i, i + 1, r);
            return this.cursors[String(agentId)];
        } catch (e) { return null; }
    }

    _color(i) {
        const palette = ["#0d6efd", "#12b886", "#7048e8", "#f76707", "#e64980",
            "#2f9e44", "#1098ad", "#f08c00", "#4263eb", "#ae3ec9", "#0ca678", "#fa5252"];
        return palette[i % palette.length];
    }

    _place(vm, x, y) {
        vm.x = x; vm.y = y;
        vm.el.style.transform = "translate(" + Math.round(x) + "px," + Math.round(y) + "px)";
    }

    /** Resolve the canvas element an edit targets (best-effort). */
    _resolveTarget(edit) {
        const doc = this._canvasDoc();
        if (!doc || !edit) { return null; }
        const a = edit.args || {};
        // Selector-based tools (apply_css, set_text, set_image, set_link, …).
        if (a.selector && typeof a.selector === "string") {
            try { const el = doc.querySelector(a.selector); if (el) { return el; } } catch (e) { /* bad selector */ }
        }
        // Id/target based tools (set_module_option, delete_element, …).
        const id = a.target || a.id || a.element_id || a.module_id;
        if (id) {
            try { const el = doc.getElementById(String(id).replace(/^#/, "")); if (el) { return el; } } catch (e) { /* no-op */ }
        }
        // add_section / add_module: aim at the last element in the content region.
        if (edit.tool === "add_section" || edit.tool === "insert_module" || edit.tool === "insert_layout") {
            try {
                const region = doc.querySelector(".edit, .mw-content-region, [field=content], body");
                const kids = region ? region.querySelectorAll(".element, .module, section") : null;
                if (kids && kids.length) { return kids[kids.length - 1]; }
            } catch (e) { /* no-op */ }
        }
        return null;
    }

    /** Map a canvas element's rect into top-window coordinates, clamped to the iframe. */
    _pointFor(el) {
        try {
            const iframe = this._canvasIframe();
            if (!iframe) { return null; }
            const ir = iframe.getBoundingClientRect();
            const er = el.getBoundingClientRect();
            // Aim a little inside the element's top-left (where a cursor would click).
            let x = ir.left + er.left + Math.min(Math.max(er.width * 0.4, 12), 60);
            let y = ir.top + er.top + Math.min(Math.max(er.height * 0.4, 10), 40);
            // Keep the cursor within the visible canvas region.
            x = Math.min(Math.max(x, ir.left + 8), ir.right - 8);
            y = Math.min(Math.max(y, ir.top + 8), ir.bottom - 8);
            return { x, y };
        } catch (e) { return null; }
    }
}

// Singleton across bundles (same pattern as mw-ai-swarm-board).
let mwAiSwarmCursors;
if (typeof window !== "undefined" && window.mwAiSwarmCursors && typeof window.mwAiSwarmCursors.start === "function") {
    mwAiSwarmCursors = window.mwAiSwarmCursors;
} else {
    const instance = new MwAiSwarmCursors();
    mwAiSwarmCursors = {
        _instance: instance,
        start:      (plan) => instance.start(plan),
        tool:       (agentId, edit) => instance.tool(agentId, edit),
        finishLane: (agentId) => instance.finishLane(agentId),
        finishAll:  () => instance.finishAll(),
        destroy:    () => instance.destroy(),
    };
    if (typeof window !== "undefined") { window.mwAiSwarmCursors = mwAiSwarmCursors; }
}

export { MwAiSwarmCursors };
export default mwAiSwarmCursors;
