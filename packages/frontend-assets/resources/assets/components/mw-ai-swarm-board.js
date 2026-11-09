/**
 * MwAiSwarmBoard — a self-contained, no-framework "swarm build board".
 *
 * Renders the live state of a multi-agent site build: a coordinator (PLANNER)
 * decomposes the brief into ordered PAGE-lanes and locks the global design
 * system first; then a swarm of named workers each own one page and report
 * progress as their sections land. This component only VISUALISES that stream —
 * it owns no network and no canvas; a router drives it from the SSE contract.
 *
 * Two synchronised views, echoing the reference "self-building design tool" UX:
 *   1. a per-lane progress list — one titled card per worker, each a named,
 *      colour-coded cursor + progress bar + a live "building <section>" status;
 *   2. a build-as-you-go section canvas — skeleton placeholders per lane that
 *      fill in as each lane reports `built`/`total`, with the owning agent's
 *      named cursor hovering the section currently under construction.
 *
 * Header: "N agents · M tasks in flight". On completion the board collapses to
 * "This site was built by N agents."
 *
 * Each lane is labelled by its ROLE — the page it builds (Home, Products,
 * Navigation …) — with a token colour cycled deterministically by lane order.
 * agent_id is threaded end to end: the router addresses every update by the
 * agent_id handed out in the plan.
 *
 * ── Window API (what the router / integrator calls) ────────────────────────
 *   window.mwAiSwarmBoard = {
 *     mount(container),                 // attach the board into a DOM node
 *     setPlan(lanes),                   // lanes: [{agent_id,title,url,order,summary}]
 *                                       //   (also accepts the full plan object
 *                                       //    {lanes:[…], design:{tokens:{…}}})
 *     updateLane(agent_id, {            // one `lane` SSE frame
 *       status:"started"|"building"|"done"|"error",
 *       built:int, total:int, title?, url?
 *     }),
 *     tool(agent_id, edit),             // one `tool` SSE frame; edit = {tool, args}
 *                                       //   agent_id OMITTED (null/undefined) ⇒
 *                                       //   legacy single-agent, routed to the
 *                                       //   active/first lane (back-compat).
 *     finish(summary),                  // one `done` SSE frame:
 *                                       //   {lanes:[{agent_id,status,built,url}]}
 *     destroy()                         // tear down DOM + injected style
 *   };
 *
 * Pure vanilla JS + one scoped <style>. No external dependencies. Colours are
 * tokens; light + dark both supported (admin: html.dark / body.fi-panel-admin
 * dark). prefers-reduced-motion disables all shimmer/cursor motion.
 */

/* Deterministic lane identities: name + token colour, assigned by lane order.
   Colours are picked to stay distinguishable and legible on both light and dark
   surfaces (WCAG-ish contrast against the card background). */
const SWARM_AGENTS = [
    { name: "Ada",    color: "#0d6efd" }, // brand blue
    { name: "Grace",  color: "#12b886" }, // teal
    { name: "Alan",   color: "#7048e8" }, // violet
    { name: "Edsger", color: "#f76707" }, // orange
    { name: "Donald", color: "#e64980" }, // pink
    { name: "Linus",  color: "#2f9e44" }, // green
    { name: "Guido",  color: "#1098ad" }, // cyan
    { name: "Bjarne", color: "#f08c00" }, // amber
    { name: "Dennis", color: "#4263eb" }, // indigo
    { name: "Ken",    color: "#ae3ec9" }, // magenta
    { name: "Tim",    color: "#0ca678" }, // emerald
    { name: "Barbara",color: "#fa5252" }, // red
];

const SWARM_CSS = `
.mw-ai-swarm{
    /* tokens — overridden in dark below; never read raw var() for Filament
       primaries, but this board paints its own neutral palette so it stands
       alone on any admin/public surface. */
    --mw-sw-ink:#182433;
    --mw-sw-muted:#5a6b7b;
    --mw-sw-bg:#ffffff;
    --mw-sw-card:#f6f8fb;
    --mw-sw-card-brd:#18243314;
    --mw-sw-track:#1824330f;
    --mw-sw-skel:#18243312;
    --mw-sw-skel-brd:#1824331a;
    --mw-sw-accent:#0d6efd;
    --mw-sw-ok:#2f9e44;
    --mw-sw-err:#e03131;
    color:var(--mw-sw-ink);
    font:13px/1.45 ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;
    display:flex; flex-direction:column; gap:14px; width:100%; box-sizing:border-box;
}
.mw-ai-swarm *,.mw-ai-swarm *::before,.mw-ai-swarm *::after{ box-sizing:border-box; }

html.dark .mw-ai-swarm,
body.fi-panel-admin.dark .mw-ai-swarm,
.dark .mw-ai-swarm{
    --mw-sw-ink:#e8eaed;
    --mw-sw-muted:#9aa7b4;
    --mw-sw-bg:#1c2027;
    --mw-sw-card:#23272f;
    --mw-sw-card-brd:#ffffff14;
    --mw-sw-track:#ffffff14;
    --mw-sw-skel:#ffffff10;
    --mw-sw-skel-brd:#ffffff1a;
    --mw-sw-accent:#4d9bff;
}

/* ── header ─────────────────────────────────────────────────────────────── */
.mw-ai-swarm-head{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.mw-ai-swarm-title{ font-weight:650; font-size:14px; letter-spacing:-.01em; margin:0; }
.mw-ai-swarm-count{ color:var(--mw-sw-muted); font-size:12px; font-variant-numeric:tabular-nums; }
.mw-ai-swarm-count b{ color:var(--mw-sw-ink); font-weight:600; }
.mw-ai-swarm-design{
    margin-left:auto; display:inline-flex; align-items:center; gap:6px;
    font-size:11px; font-weight:600; color:var(--mw-sw-ok);
    padding:3px 9px; border-radius:999px; background:color-mix(in srgb,var(--mw-sw-ok) 12%,transparent);
    opacity:0; transform:translateY(-2px); transition:opacity .25s ease,transform .25s ease;
}
.mw-ai-swarm-design.is-locked{ opacity:1; transform:none; }
.mw-ai-swarm-design .dot{ width:7px; height:7px; border-radius:50%; background:var(--mw-sw-ok); }

/* ── avatar cluster ─────────────────────────────────────────────────────── */
.mw-ai-swarm-cluster{ display:flex; align-items:center; }
.mw-ai-swarm-cluster .ava{
    width:24px; height:24px; border-radius:50%; margin-left:-7px;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:10px; font-weight:700; color:#fff; border:2px solid var(--mw-sw-bg);
    box-shadow:0 1px 2px #18243326;
}
.mw-ai-swarm-cluster .ava:first-child{ margin-left:0; }

/* ── lane cards ─────────────────────────────────────────────────────────── */
.mw-ai-swarm-lanes{ display:grid; gap:10px; }
.mw-ai-swarm-lane{
    position:relative; background:var(--mw-sw-card); border:1px solid var(--mw-sw-card-brd);
    border-radius:12px; padding:11px 12px; overflow:hidden;
    --lane:var(--mw-sw-accent);
}
.mw-ai-swarm-lane::before{
    content:""; position:absolute; left:0; top:0; bottom:0; width:3px;
    background:var(--lane); opacity:.9;
}
.mw-ai-swarm-lane-top{ display:flex; align-items:center; gap:8px; cursor:pointer; user-select:none; }
.mw-ai-swarm-lane-top:focus-visible{ outline:2px solid var(--lane); outline-offset:2px; border-radius:6px; }
.mw-ai-swarm-chevron{ flex:0 0 auto; width:14px; height:14px; margin-left:2px; color:var(--mw-sw-muted); transition:transform .2s ease; }
.mw-ai-swarm-chevron svg{ width:14px; height:14px; display:block; }
/* Collapsed by default — only the top row + thin progress bar show; the live
   status line and the section skeletons (the space hogs) are hidden until the
   lane is expanded by click/Enter. */
.mw-ai-swarm-lane.is-collapsed .mw-ai-swarm-chevron{ transform:rotate(-90deg); }
.mw-ai-swarm-lane.is-collapsed .mw-ai-swarm-bar{ margin:9px 0 2px; }
.mw-ai-swarm-lane.is-collapsed .mw-ai-swarm-status,
.mw-ai-swarm-lane.is-collapsed .mw-ai-swarm-skels{ display:none; }
.mw-ai-swarm-cursor{
    position:relative; display:inline-flex; align-items:center; gap:5px;
    padding:2px 8px 2px 4px; border-radius:999px; flex:0 0 auto;
    background:color-mix(in srgb,var(--lane) 14%,transparent);
    color:var(--lane); font-weight:650; font-size:11px; white-space:nowrap;
}
.mw-ai-swarm-cursor svg{ width:13px; height:13px; display:block; }
.mw-ai-swarm-lane-title{
    font-weight:600; font-size:12.5px; color:var(--mw-sw-ink);
    overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:0;
}
.mw-ai-swarm-lane-url{ color:var(--mw-sw-muted); font-weight:400; font-size:11px; }
.mw-ai-swarm-badge{
    margin-left:auto; flex:0 0 auto; font-size:10.5px; font-weight:600; font-variant-numeric:tabular-nums;
    color:var(--mw-sw-muted); display:inline-flex; align-items:center; gap:5px;
}
.mw-ai-swarm-badge .pct{ color:var(--lane); }
.mw-ai-swarm-lane[data-status="done"] .mw-ai-swarm-badge{ color:var(--mw-sw-ok); }
.mw-ai-swarm-lane[data-status="error"] .mw-ai-swarm-badge{ color:var(--mw-sw-err); }

/* progress bar */
.mw-ai-swarm-bar{ height:6px; border-radius:999px; background:var(--mw-sw-track); margin:9px 0 7px; overflow:hidden; }
.mw-ai-swarm-bar>i{
    display:block; height:100%; width:0%; border-radius:999px; background:var(--lane);
    transition:width .4s cubic-bezier(0.22,1,0.36,1);
}
.mw-ai-swarm-lane[data-status="done"] .mw-ai-swarm-bar>i{ background:var(--mw-sw-ok); }
.mw-ai-swarm-lane[data-status="error"] .mw-ai-swarm-bar>i{ background:var(--mw-sw-err); }

/* live status line */
.mw-ai-swarm-status{ display:flex; align-items:center; gap:6px; color:var(--mw-sw-muted); font-size:11.5px; min-height:16px; }
.mw-ai-swarm-status .spin{
    width:9px; height:9px; border-radius:50%; flex:0 0 auto;
    border:1.5px solid color-mix(in srgb,var(--lane) 35%,transparent); border-top-color:var(--lane);
    animation:mw-sw-spin .7s linear infinite;
}
.mw-ai-swarm-lane[data-status="done"] .mw-ai-swarm-status{ color:var(--mw-sw-ok); }
.mw-ai-swarm-lane[data-status="done"] .mw-ai-swarm-status .spin,
.mw-ai-swarm-lane[data-status="error"] .mw-ai-swarm-status .spin{ display:none; }
.mw-ai-swarm-lane[data-status="error"] .mw-ai-swarm-status{ color:var(--mw-sw-err); }

/* ── build-as-you-go section skeletons ─────────────────────────────────── */
.mw-ai-swarm-skels{ display:flex; flex-wrap:wrap; gap:5px; margin-top:9px; }
.mw-ai-swarm-skel{
    position:relative; height:16px; flex:1 1 42px; min-width:28px; max-width:72px;
    border-radius:5px; background:var(--mw-sw-skel); border:1px solid var(--mw-sw-skel-brd);
    overflow:hidden; transition:background .35s ease;
}
.mw-ai-swarm-skel.is-filled{ background:color-mix(in srgb,var(--lane) 26%,transparent); border-color:transparent; }
.mw-ai-swarm-skel.is-active{ background:color-mix(in srgb,var(--lane) 16%,transparent); border-color:color-mix(in srgb,var(--lane) 45%,transparent); }
.mw-ai-swarm-skel.is-active::after{
    content:""; position:absolute; inset:0; transform:translateX(-100%);
    background:linear-gradient(90deg,transparent,color-mix(in srgb,var(--lane) 38%,transparent),transparent);
    animation:mw-sw-shimmer 1.15s ease-in-out infinite;
}
/* the owning agent's cursor hovering the section under construction */
.mw-ai-swarm-skel.is-active .mw-ai-swarm-ptr{
    position:absolute; right:-2px; bottom:-3px; width:12px; height:12px; color:var(--lane);
    filter:drop-shadow(0 1px 1px #18243340);
    animation:mw-sw-bob 1.15s ease-in-out infinite;
}

/* ── done state ─────────────────────────────────────────────────────────── */
.mw-ai-swarm.is-finished .mw-ai-swarm-lanes{ opacity:.85; }
.mw-ai-swarm-done{
    display:none; align-items:center; gap:10px; padding:12px 14px;
    background:color-mix(in srgb,var(--mw-sw-ok) 10%,var(--mw-sw-card));
    border:1px solid color-mix(in srgb,var(--mw-sw-ok) 24%,transparent); border-radius:12px;
}
.mw-ai-swarm.is-finished .mw-ai-swarm-done{ display:flex; }
.mw-ai-swarm-done .tick{
    width:26px; height:26px; flex:0 0 auto; border-radius:50%; background:var(--mw-sw-ok); color:#fff;
    display:inline-flex; align-items:center; justify-content:center; font-size:15px; line-height:1;
}
.mw-ai-swarm-done-text b{ color:var(--mw-sw-ink); }
.mw-ai-swarm-done-text{ color:var(--mw-sw-muted); font-size:12.5px; }

.mw-ai-swarm-sr{ position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); white-space:nowrap; border:0; }

@keyframes mw-sw-spin{ to{ transform:rotate(360deg); } }
@keyframes mw-sw-shimmer{ to{ transform:translateX(100%); } }
@keyframes mw-sw-bob{ 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-2px); } }

@media (prefers-reduced-motion: reduce){
    .mw-ai-swarm *{ animation:none !important; transition:none !important; }
    .mw-ai-swarm-skel.is-active::after{ display:none; }
}
`;

const CURSOR_SVG =
    '<svg viewBox="0 0 16 16" aria-hidden="true" focusable="false">' +
    '<path fill="currentColor" d="M2 1.4 13.2 7.1a.7.7 0 0 1-.1 1.28l-4.33 1.3-1.72 4.2a.7.7 0 0 1-1.3-.06L2 1.4Z"/>' +
    "</svg>";

const CHEVRON_SVG =
    '<svg viewBox="0 0 16 16" aria-hidden="true" focusable="false">' +
    '<path d="M4 6l4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>' +
    "</svg>";

function escapeHtml(s) {
    return String(s == null ? "" : s)
        .replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;").replace(/'/g, "&#39;");
}

class MwAiSwarmBoard {
    constructor() {
        this.root = null;      // the .mw-ai-swarm element
        this.styleEl = null;   // injected <style>
        this.container = null; // host node
        this.lanes = [];       // ordered lane view-models
        this.byId = {};        // agent_id -> lane view-model
        this.designLocked = false;
        this.finished = false;
        // Refs into the DOM:
        this._el = {};
    }

    _lang(s) {
        try { return (typeof window !== "undefined" && window.mw && mw.lang) ? mw.lang(s) : s; } catch (e) { return s; }
    }

    _ensureStyle() {
        if (this.styleEl || typeof document === "undefined") { return; }
        this.styleEl = document.createElement("style");
        this.styleEl.setAttribute("data-mw-ai-swarm-board", "");
        this.styleEl.textContent = SWARM_CSS;
        document.head.appendChild(this.styleEl);
    }

    /* Human label for a streamed tool edit → the "building <section>" status. */
    _toolLabel(edit) {
        const t = edit && edit.tool;
        const a = (edit && edit.args) || {};
        const L = (s) => this._lang(s);
        if (!t) { return L("Working…"); }
        if (t === "set_css_var") { return L("Locking the design system"); }
        if (t === "apply_css") { return L("Applying styles"); }
        if (t === "add_section") { return L("Building a section") + (a.name || a.type ? " · " + (a.name || a.type) : ""); }
        if (t === "insert_module") { return L("Inserting module") + (a.type ? " · " + a.type : ""); }
        if (t === "set_module_option") { return L("Configuring module") + (a.key ? " · " + a.key : ""); }
        if (t === "create_content") { return L("Creating page") + (a.title ? " · " + a.title : ""); }
        if (t === "create_post") { return L("Creating post") + (a.title ? " · " + a.title : ""); }
        if (t === "set_custom_field") { return L("Setting field") + (a.field ? " · " + a.field : ""); }
        if (t === "add_menu_item") { return L("Adding menu link") + (a.title ? " · " + a.title : ""); }
        if (t === "set_text") { return L("Writing copy") + (a.selector ? " · " + a.selector : ""); }
        if (t === "set_image") { return L("Placing image"); }
        if (t === "generate_image") { return L("Generating image"); }
        if (t === "navigate_to_page") { return L("Opening page") + (a.url ? " · " + a.url : ""); }
        if (t === "save_page") { return L("Saving the page"); }
        return String(t).replace(/_/g, " ");
    }

    /* ── public: mount ──────────────────────────────────────────────────── */
    mount(container) {
        if (typeof document === "undefined") { return this; }
        this._ensureStyle();
        this.container = (typeof container === "string") ? document.querySelector(container) : container;
        if (!this.container) { return this; }

        if (this.root && this.root.parentNode) { this.root.parentNode.removeChild(this.root); }

        const r = document.createElement("div");
        r.className = "mw-ai-swarm";
        r.setAttribute("role", "region");
        r.setAttribute("aria-label", this._lang("AI swarm build progress"));
        r.innerHTML =
            '<div class="mw-ai-swarm-head">' +
            '  <h3 class="mw-ai-swarm-title"></h3>' +
            '  <span class="mw-ai-swarm-cluster" aria-hidden="true"></span>' +
            '  <span class="mw-ai-swarm-count" aria-live="polite"></span>' +
            '  <span class="mw-ai-swarm-design"><span class="dot"></span>' + escapeHtml(this._lang("Design system locked")) + "</span>" +
            "</div>" +
            '<div class="mw-ai-swarm-lanes"></div>' +
            '<div class="mw-ai-swarm-done">' +
            '  <span class="tick" aria-hidden="true">✓</span>' +
            '  <span class="mw-ai-swarm-done-text"></span>' +
            "</div>" +
            '<span class="mw-ai-swarm-sr" aria-live="polite"></span>';

        this.container.appendChild(r);
        this.root = r;
        this._el = {
            title:   r.querySelector(".mw-ai-swarm-title"),
            cluster: r.querySelector(".mw-ai-swarm-cluster"),
            count:   r.querySelector(".mw-ai-swarm-count"),
            design:  r.querySelector(".mw-ai-swarm-design"),
            lanes:   r.querySelector(".mw-ai-swarm-lanes"),
            done:    r.querySelector(".mw-ai-swarm-done-text"),
            sr:      r.querySelector(".mw-ai-swarm-sr"),
        };
        this._el.title.textContent = this._lang("Building your site");
        this._renderHeaderCounts();
        return this;
    }

    /* ── public: setPlan ────────────────────────────────────────────────── */
    setPlan(plan) {
        if (!this.root) { return this; }
        // Accept either the lanes array or the full plan object {lanes, design}.
        let lanes = plan, design = null;
        if (plan && !Array.isArray(plan) && typeof plan === "object") {
            lanes = plan.lanes || [];
            design = plan.design || null;
        }
        lanes = Array.isArray(lanes) ? lanes.slice() : [];
        lanes.sort((a, b) => (Number(a && a.order) || 0) - (Number(b && b.order) || 0));

        this.finished = false;
        this.root.classList.remove("is-finished");
        this.lanes = [];
        this.byId = {};
        this._el.lanes.innerHTML = "";
        this._el.cluster.innerHTML = "";

        lanes.forEach((planLane, i) => {
            const ident = SWARM_AGENTS[i % SWARM_AGENTS.length];
            const agentId = (planLane && (planLane.agent_id != null)) ? String(planLane.agent_id) : ("lane-" + i);
            const title = (planLane && planLane.title) || (this._lang("Page") + " " + (i + 1));
            const vm = {
                agentId: agentId,
                // Name the lane by its ROLE — the page it builds — not a generic
                // persona (Ada / Grace / …). The colour still cycles per lane for
                // visual variety. (The nav subagent names itself "Navigation".)
                name: title,
                color: ident.color,
                index: i,
                title: title,
                url: (planLane && planLane.url) || "",
                summary: (planLane && planLane.summary) || "",
                status: "queued",
                built: 0,
                total: 0,
                el: null,
            };
            this.lanes.push(vm);
            this.byId[agentId] = vm;
            this._el.lanes.appendChild(this._buildLaneEl(vm));
            this._el.cluster.appendChild(this._buildAvatar(vm));
        });

        if (design || (plan && plan.design)) { this._setDesignLocked(true); }
        this._renderHeaderCounts();
        this._announce(this._lang("Plan ready") + " · " + this.lanes.length + " " + this._lang("pages"));
        return this;
    }

    _buildAvatar(vm) {
        const a = document.createElement("span");
        a.className = "ava";
        a.style.background = vm.color;
        a.textContent = (vm.title || vm.name || "?").charAt(0).toUpperCase();
        a.title = vm.title + (vm.url ? " · " + vm.url : "");
        return a;
    }

    _buildLaneEl(vm) {
        const lane = document.createElement("div");
        // Collapsed by default so a 5-lane swarm doesn't fill the whole chat —
        // only the top row + thin bar show; click/Enter the row to reveal the
        // live status + section skeletons.
        lane.className = "mw-ai-swarm-lane is-collapsed";
        lane.style.setProperty("--lane", vm.color);
        lane.setAttribute("data-status", "queued");
        lane.setAttribute("data-agent", vm.agentId);
        lane.setAttribute("aria-label", vm.name + " — " + vm.title);
        lane.innerHTML =
            '<div class="mw-ai-swarm-lane-top" role="button" tabindex="0" aria-expanded="false">' +
            // Cursor icon only — the lane is named by its role in the title span
            // below, so the old persona chip text would just duplicate it.
            '  <span class="mw-ai-swarm-cursor">' + CURSOR_SVG + "</span>" +
            '  <span class="mw-ai-swarm-lane-title">' + escapeHtml(vm.title) +
                 (vm.url ? ' <span class="mw-ai-swarm-lane-url">' + escapeHtml(vm.url) + "</span>" : "") +
            "</span>" +
            '  <span class="mw-ai-swarm-badge"><span class="pct">0%</span></span>' +
            '  <span class="mw-ai-swarm-chevron">' + CHEVRON_SVG + "</span>" +
            "</div>" +
            '<div class="mw-ai-swarm-bar"><i></i></div>' +
            '<div class="mw-ai-swarm-status"><span class="spin"></span><span class="txt">' +
                 escapeHtml(this._lang("Queued")) + "</span></div>" +
            '<div class="mw-ai-swarm-skels"></div>';
        vm.el = {
            root:  lane,
            top:   lane.querySelector(".mw-ai-swarm-lane-top"),
            bar:   lane.querySelector(".mw-ai-swarm-bar>i"),
            badge: lane.querySelector(".mw-ai-swarm-badge .pct"),
            status:lane.querySelector(".mw-ai-swarm-status .txt"),
            skels: lane.querySelector(".mw-ai-swarm-skels"),
        };

        // Expand / collapse this lane (per-lane, independent).
        const toggle = () => {
            const collapsed = lane.classList.toggle("is-collapsed");
            vm.el.top.setAttribute("aria-expanded", collapsed ? "false" : "true");
        };
        vm.el.top.addEventListener("click", toggle);
        vm.el.top.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " " || e.key === "Spacebar") { e.preventDefault(); toggle(); }
        });
        // progressbar a11y
        const bar = lane.querySelector(".mw-ai-swarm-bar");
        bar.setAttribute("role", "progressbar");
        bar.setAttribute("aria-valuemin", "0");
        bar.setAttribute("aria-valuemax", "100");
        bar.setAttribute("aria-valuenow", "0");
        bar.setAttribute("aria-label", vm.name + " " + this._lang("progress"));
        vm.el.barWrap = bar;
        return lane;
    }

    /* Render / reconcile the row of section skeletons for a lane. */
    _renderSkels(vm) {
        const host = vm.el && vm.el.skels;
        if (!host) { return; }
        const total = Math.max(0, Number(vm.total) || 0);
        const built = Math.max(0, Math.min(total, Number(vm.built) || 0));
        // (Re)build the right number of blocks.
        if (host.children.length !== total) {
            host.innerHTML = "";
            for (let i = 0; i < total; i++) {
                const s = document.createElement("span");
                s.className = "mw-ai-swarm-skel";
                host.appendChild(s);
            }
        }
        const building = vm.status === "building" || vm.status === "started";
        Array.prototype.forEach.call(host.children, (s, i) => {
            s.classList.remove("is-filled", "is-active");
            const ptr = s.querySelector(".mw-ai-swarm-ptr");
            if (ptr) { ptr.remove(); }
            if (i < built) {
                s.classList.add("is-filled");
            } else if (i === built && building && !this.finished) {
                s.classList.add("is-active");
                const p = document.createElement("span");
                p.className = "mw-ai-swarm-ptr";
                p.innerHTML = CURSOR_SVG;
                p.setAttribute("aria-hidden", "true");
                s.appendChild(p);
            }
        });
    }

    /* ── public: updateLane ─────────────────────────────────────────────── */
    // Lazily add a lane card for a synthetic agent (e.g. the "nav" navigation
    // subagent) that wasn't part of the planner's page lanes, so it still shows
    // on the board when its first frame arrives.
    _lazyLane(agentId, data) {
        if (this.byId[agentId]) { return this.byId[agentId]; }
        const i = this.lanes.length;
        const ident = SWARM_AGENTS[i % SWARM_AGENTS.length];
        const vm = {
            agentId: agentId, name: (data && data.title) ? data.title : ident.name, color: ident.color,
            index: i, title: (data && data.title) ? data.title : agentId, url: (data && data.url) || "",
            summary: "", status: "queued", built: 0, total: 0, el: null,
        };
        this.lanes.push(vm);
        this.byId[agentId] = vm;
        this._el.lanes.appendChild(this._buildLaneEl(vm));
        this._el.cluster.appendChild(this._buildAvatar(vm));
        this._renderHeaderCounts();
        return vm;
    }

    updateLane(agentId, data) {
        let vm = this._resolveLane(agentId);
        if (!vm && agentId != null && !this.finished) { vm = this._lazyLane(String(agentId), data || {}); }
        if (!vm || !vm.el) { return this; }
        data = data || {};
        if (data.status) { vm.status = String(data.status); }
        if (data.title) { vm.title = String(data.title); }
        if (data.url != null) { vm.url = String(data.url); }
        if (typeof data.total !== "undefined" && data.total !== null) { vm.total = Math.max(0, Number(data.total) || 0); }
        if (typeof data.built !== "undefined" && data.built !== null) { vm.built = Math.max(0, Number(data.built) || 0); }
        if (vm.total) { vm.built = Math.min(vm.built, vm.total); }

        vm.el.root.setAttribute("data-status", vm.status);
        const pct = vm.status === "done" ? 100 : (vm.total ? Math.round((vm.built / vm.total) * 100) : (vm.status === "building" || vm.status === "started" ? 8 : 0));
        vm.el.bar.style.width = pct + "%";
        vm.el.badge.textContent = pct + "%";
        if (vm.el.barWrap) { vm.el.barWrap.setAttribute("aria-valuenow", String(pct)); }

        let statusText;
        if (vm.status === "done") { statusText = this._lang("Done") + (vm.total ? " · " + vm.built + "/" + vm.total + " " + this._lang("sections") : ""); }
        else if (vm.status === "error") { statusText = this._lang("Failed — needs attention"); }
        else if (vm.status === "started") { statusText = this._lang("Starting…"); }
        else { statusText = this._lang("Building") + (vm.total ? " · " + vm.built + "/" + vm.total : "") + "…"; }
        // Keep a tool-driven status if one is already showing for an in-flight lane.
        if (!(vm._toolStatus && (vm.status === "building" || vm.status === "started"))) {
            vm.el.status.textContent = statusText;
        }

        this._renderSkels(vm);
        this._renderHeaderCounts();
        if (vm.status === "done") { this._announce(vm.name + ": " + vm.title + " " + this._lang("done")); }
        else if (vm.status === "error") { this._announce(vm.name + ": " + this._lang("failed")); }
        return this;
    }

    /* ── public: tool ───────────────────────────────────────────────────── */
    tool(agentId, edit) {
        const vm = this._resolveLane(agentId);
        if (!vm || !vm.el) { return this; }
        // A design-system lock (set_css_var / global apply_css) from the planner
        // flips the header chip the first time we see it.
        const t = edit && edit.tool;
        if (t === "set_css_var" || (t === "apply_css" && edit.args && edit.args.global)) {
            this._setDesignLocked(true);
        }
        const label = this._toolLabel(edit);
        vm._toolStatus = label;
        if (vm.status === "queued") { vm.status = "building"; vm.el.root.setAttribute("data-status", "building"); }
        if (vm.status === "building" || vm.status === "started") {
            vm.el.status.textContent = label + "…";
            this._renderSkels(vm);
        }
        return this;
    }

    /* ── public: finish ─────────────────────────────────────────────────── */
    finish(summary) {
        if (!this.root) { return this; }
        // Reconcile final lane states from the done frame, if provided.
        const doneLanes = (summary && (Array.isArray(summary) ? summary : summary.lanes)) || [];
        doneLanes.forEach((d) => {
            if (!d) { return; }
            this.updateLane(d.agent_id, {
                status: d.status || "done",
                built: d.built,
                url: d.url,
            });
        });
        // Any lane not explicitly errored is considered complete.
        this.lanes.forEach((vm) => {
            if (vm.status !== "error" && vm.status !== "done") {
                this.updateLane(vm.agentId, { status: "done", built: vm.total || vm.built });
            }
        });

        this.finished = true;
        this.root.classList.add("is-finished");
        // Clear in-flight affordances.
        this.lanes.forEach((vm) => { vm._toolStatus = null; this._renderSkels(vm); });

        const n = this.lanes.length;
        const errs = this.lanes.filter((v) => v.status === "error").length;
        let msg = this._lang("This site was built by") + " <b>" + n + "</b> " +
                  (n === 1 ? this._lang("agent") : this._lang("agents")) + ".";
        if (errs) { msg += " <b>" + errs + "</b> " + this._lang("need attention") + "."; }
        this._el.done.innerHTML = msg;
        this._renderHeaderCounts();
        this._announce(this._lang("Build complete") + " · " + n + " " + this._lang("agents"));
        return this;
    }

    /* ── public: destroy ────────────────────────────────────────────────── */
    destroy() {
        if (this.root && this.root.parentNode) { this.root.parentNode.removeChild(this.root); }
        if (this.styleEl && this.styleEl.parentNode) { this.styleEl.parentNode.removeChild(this.styleEl); }
        this.root = null; this.styleEl = null; this.container = null;
        this.lanes = []; this.byId = {}; this._el = {};
        this.designLocked = false; this.finished = false;
        return this;
    }

    /* ── internals ──────────────────────────────────────────────────────── */
    _resolveLane(agentId) {
        // Legacy single-agent frames omit agent_id → route to the active lane,
        // else the first in-flight lane, else the first lane.
        if (agentId != null && this.byId[String(agentId)]) { return this.byId[String(agentId)]; }
        if (agentId != null) { return null; }
        const inflight = this.lanes.filter((v) => v.status === "building" || v.status === "started");
        return inflight[inflight.length - 1] || this.lanes[0] || null;
    }

    _setDesignLocked(on) {
        this.designLocked = !!on;
        if (this._el.design) { this._el.design.classList.toggle("is-locked", this.designLocked); }
    }

    _renderHeaderCounts() {
        if (!this._el.count) { return; }
        const n = this.lanes.length;
        const inflight = this.lanes.filter((v) => v.status === "building" || v.status === "started").length;
        const done = this.lanes.filter((v) => v.status === "done").length;
        if (this.finished) {
            this._el.count.innerHTML = "<b>" + done + "</b> / " + n + " " + escapeHtml(this._lang("pages built"));
        } else {
            this._el.count.innerHTML =
                "<b>" + n + "</b> " + escapeHtml(n === 1 ? this._lang("agent") : this._lang("agents")) +
                " · <b>" + inflight + "</b> " + escapeHtml(this._lang("tasks in flight"));
        }
    }

    _announce(msg) {
        if (this._el.sr) { this._el.sr.textContent = msg; }
    }
}

// Singleton across bundles (the admin panel injects this as a standalone IIFE
// while the Live-Edit app imports it as a module; both share one window). Reuse
// any instance already registered so the router always drives the same board.
let mwAiSwarmBoard;
if (typeof window !== "undefined" && window.mwAiSwarmBoard && typeof window.mwAiSwarmBoard.mount === "function") {
    mwAiSwarmBoard = window.mwAiSwarmBoard;
} else {
    const instance = new MwAiSwarmBoard();
    // Expose a plain, bound API object (the router calls bare methods).
    mwAiSwarmBoard = {
        _instance: instance,
        mount:      (container) => instance.mount(container),
        setPlan:    (lanes) => instance.setPlan(lanes),
        updateLane: (agentId, data) => instance.updateLane(agentId, data),
        tool:       (agentId, edit) => instance.tool(agentId, edit),
        finish:     (summary) => instance.finish(summary),
        destroy:    () => instance.destroy(),
    };
    if (typeof window !== "undefined") { window.mwAiSwarmBoard = mwAiSwarmBoard; }
}

export { MwAiSwarmBoard };
export default mwAiSwarmBoard;
