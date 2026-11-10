/**
 * MwAiSwarm — client-side swarm coordinator for multi-agent Live-Edit builds.
 *
 * The single-agent chat (MwAi().agentChatStream) streams one agent's tool calls
 * over Server-Sent Events and applies each to the live canvas. A SWARM run is
 * the same transport with a coordinator on top: a server-side PLANNER decomposes
 * the brief into ordered PAGE-lanes (and locks the global design system first),
 * then each worker lane streams its own tool calls tagged with an `agent_id`.
 *
 * This coordinator opens the swarm SSE stream (api/ai/agent-swarm-stream) and
 * routes every lane-tagged frame to the RIGHT lane:
 *
 *   - Each lane keeps its OWN MwAi() instance (its own per-turn state —
 *     _turnDeletes / _moduleReloadQueue), keyed by agent_id, so concurrently
 *     building lanes never share per-turn counters/queues.
 *   - Tool frames are applied through the lane's MwAi().applyEdit() verbatim —
 *     NO tool logic is duplicated here; all editing primitives live in mw-ai.js.
 *   - A single saveCanvasAsync() fires once, after ALL lanes report done — never
 *     per lane. Module reloads are flushed per lane (each owns its own queue)
 *     right before that one save.
 *
 * Back-compat: a frame with no `agent_id` (a legacy single-agent tool frame)
 * routes to a default lane, so this coordinator can drive an old stream too.
 *
 * Exposed as window.mw.aiSwarm. Requires window.MwAi (modules/ai/js/mw-ai.js).
 *
 * LOCKED SSE CONTRACTS (must match SseToolEmitter + the backend swarm):
 *   event: plan  -> {lanes:[{agent_id,title,url,order,summary}], design:{tokens:{...}}}
 *   event: tool  -> {agent_id, tool, args}   (agent_id omitted => legacy/default lane)
 *   event: lane  -> {agent_id, status:"started"|"building"|"done"|"error", built, total, title?, url?}
 *   event: done  -> {lanes:[{agent_id,status,built,url}]}
 * Each frame is "event: <name>\n" + "data: <json>\n\n", flushed immediately.
 */

const DEFAULT_LANE = "__default__";

// Resolve the global MwAi factory the same robust way the toolbar/ESE do: the
// module bundle and the Live-Edit page may each hold a different window ref.
function resolveMwAiFactory() {
    try {
        if (typeof window !== "undefined" && typeof window.MwAi === "function") {
            return window.MwAi;
        }
    } catch (e) {}
    try {
        if (typeof MwAi === "function") { return MwAi; } // eslint-disable-line no-undef
    } catch (e) {}
    try {
        const top = window.mw && mw.top && mw.top();
        if (top && top.win && typeof top.win.MwAi === "function") { return top.win.MwAi; }
    } catch (e) {}
    return null;
}

class MwAiSwarm {
    constructor() {
        // agent_id -> { api: MwAi(), meta: {...last lane frame} }
        this._lanes = {};
        this._running = false;
        // One coordinator-scoped MwAi instance for the single whole-run save
        // (saveCanvasAsync acts on the open canvas globally, not on per-turn state).
        this._coordApi = null;
    }

    _factory() {
        const f = resolveMwAiFactory();
        if (!f) { throw new Error("window.MwAi is not available (modules/ai/js/mw-ai.js not loaded)"); }
        return f;
    }

    // Get (or lazily create) the MwAi instance that owns one lane's per-turn
    // state. A missing agent_id collapses to a single default lane (back-compat).
    _lane(agentId) {
        const key = (agentId === 0 || agentId) ? String(agentId) : DEFAULT_LANE;
        if (!this._lanes[key]) {
            this._lanes[key] = { api: this._factory()(), meta: {} };
        }
        return this._lanes[key];
    }

    _coord() {
        if (!this._coordApi) { this._coordApi = this._factory()(); }
        return this._coordApi;
    }

    _reset() {
        this._lanes = {};
        this._coordApi = null;
    }

    // JSON + CSRF headers for an SSE POST (shared by every swarm request).
    _headers(accept) {
        const headers = { "Content-Type": "application/json", "Accept": accept || "text/event-stream" };
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]');
            if (csrf && csrf.getAttribute("content")) { headers["X-CSRF-TOKEN"] = csrf.getAttribute("content"); }
        } catch (e) {}
        return headers;
    }

    _siteUrl() {
        return (window.mw && mw.settings && mw.settings.site_url) ? mw.settings.site_url : "";
    }

    /**
     * Open the swarm SSE stream and route frames to lanes.
     *
     * PHASE 2: this prefers TRUE PARALLEL lanes — it plans once
     * (api/ai/agent-swarm-plan) then fans the lanes out as CONCURRENT requests
     * (api/ai/agent-swarm-lane-stream), one php process each, so lanes build at
     * the same time. It falls back to the sequential combined stream ONLY when
     * planning itself fails, so lanes can never double-build.
     *
     * @param {Object}   opts
     * @param {string}   opts.message        The user's build brief.
     * @param {Object}   [opts.contextBundle] Extra request body (page/canvas/design
     *                                        context). Spread into the POST body.
     * @param {Function} [opts.onPlan]  (plan)            — plan frame payload.
     * @param {Function} [opts.onLane]  (agentId, status, built, total, meta)
     * @param {Function} [opts.onTool]  (agentId, edit)   — edit = {agent_id,tool,args,result}
     * @param {Function} [opts.onDone]  (summary)         — done frame payload.
     * @param {Function} [opts.onError] (message)         — optional error handler.
     * @returns {Promise<Object>} the `done` summary ({lanes:[...]}), or {lanes:[]}.
     */
    async start(opts = {}) {
        let plan;
        try {
            plan = await this._plan(opts);
        } catch (e) {
            // Planning failed before any lane ran → safe to fall back to the
            // sequential combined stream (no lane has started building).
            return this._startSequential(opts);
        }
        return this._fanOut(plan, opts);
    }

    /**
     * PHASE 2 — plan once (JSON). Returns {plan_id, lanes, design, coordinator}.
     * Throws on any failure so start() can fall back to the sequential stream.
     */
    async _plan(opts = {}) {
        const body = Object.assign({ message: opts.message }, opts.contextBundle || {});
        const res = await fetch(this._siteUrl() + "api/ai/agent-swarm-plan", {
            method: "POST",
            headers: this._headers("application/json"),
            body: JSON.stringify(body),
            credentials: "same-origin",
        });
        if (!res.ok) { throw new Error("swarm plan failed (" + res.status + ")"); }
        const data = await res.json();
        if (!data || !data.plan_id || !Array.isArray(data.lanes) || data.lanes.length === 0) {
            throw new Error("swarm plan returned no lanes");
        }
        return data;
    }

    /**
     * PHASE 2 — apply the coordinator design once, then fan the lanes out as
     * CONCURRENT lane streams; after they all close, flush each lane's reloads
     * and fire ONE save. Summary is aggregated from each lane's last frame.
     */
    async _fanOut(plan, opts = {}) {
        const self = this;
        const onPlan = opts.onPlan;
        const onLane = opts.onLane;
        const onTool = opts.onTool;
        const onDone = opts.onDone;
        const onError = opts.onError;

        this._reset();
        this._running = true;

        try {
            if (onPlan) { onPlan({ lanes: plan.lanes, design: plan.design }); }

            // Coordinator locks the global design system FIRST (set_css_var +
            // one apply_css), exactly as the sequential stream does — but here the
            // frontend plays the two frames from the plan response up front.
            (plan.coordinator || []).forEach(function (frame) {
                const edit = Object.assign({ agent_id: "coordinator" }, frame);
                let result;
                try { result = self._lane("coordinator").api.applyEdit(edit); } catch (e) { result = { ok: false, message: String(e && e.message || e) }; }
                if (onTool) { onTool("coordinator", Object.assign({}, edit, { result: result })); }
            });

            const siteUrl = this._siteUrl();
            const headers = this._headers("text/event-stream");

            // One SSE request per lane, all opened concurrently. Each lane runs in
            // its own php process with its own canvas context — no shared state.
            await Promise.all((plan.lanes || []).map(function (laneMeta) {
                const body = Object.assign(
                    { plan_id: plan.plan_id, agent_id: laneMeta.agent_id },
                    opts.contextBundle || {}
                );
                return self._laneRequest(siteUrl, headers, body, { onLane: onLane, onTool: onTool, onError: onError });
            }));

            // NAVIGATION subagent — after every page lane closes, wire the built
            // pages into the header menu (one deterministic server call). Shown on
            // the board as a "Navigation" lane.
            try {
                if (onLane) { onLane("nav", "started", 0, 1, { title: "Navigation", url: "", agent_id: "nav" }); }
                const navRes = await fetch(siteUrl + "api/ai/agent-swarm-nav", {
                    method: "POST",
                    headers: self._headers("application/json"),
                    // Send the pages directly (title+url) so the nav does not depend
                    // on the server-side plan cache, which page creation can evict.
                    body: JSON.stringify({
                        plan_id: plan.plan_id,
                        lanes: (plan.lanes || []).map(function (l) { return { title: l.title, url: l.url, order: l.order }; }),
                        content_id: (opts.contextBundle && opts.contextBundle.content_id) || undefined,
                    }),
                    credentials: "same-origin",
                });
                let navData = {};
                try { navData = await navRes.json(); } catch (e) {}
                const nav = (navData && navData.nav) || {};
                if (onLane) { onLane("nav", nav.status || "done", nav.built || 0, 1, { title: "Navigation", url: "", agent_id: "nav" }); }
            } catch (e) { if (onLane) { onLane("nav", "error", 0, 1, { title: "Navigation", url: "", agent_id: "nav" }); } }

            // QA REVIEWERS — two dedicated agents run AFTER the pages + nav: one
            // hunts colours that blend (white-on-white text, an icon the same
            // colour as its bar, a button label lost on its button); the other
            // hunts spacing mismatches (cramped logo, uneven card gaps,
            // inconsistent section padding). Each reads the LIVE open page and
            // fixes its own defect class in place, so they run against the final
            // built page — and concurrently with each other (contrast only touches
            // colour, spacing only touches spacing, so they never collide).
            await Promise.all([
                self._qaRequest(siteUrl, headers, Object.assign({ focus: "contrast" }, opts.contextBundle || {}),
                    { title: "Contrast QA", agent_id: "qa-contrast" }, { onLane: onLane, onTool: onTool, onError: onError }),
                self._qaRequest(siteUrl, headers, Object.assign({ focus: "spacing" }, opts.contextBundle || {}),
                    { title: "Spacing QA", agent_id: "qa-spacing" }, { onLane: onLane, onTool: onTool, onError: onError }),
            ]);

            // Every lane closed → flush per-lane module-reload queues and fire the
            // single whole-run save (never per lane). Build the done summary from
            // each lane's last frame.
            const laneSummary = [];
            Object.keys(self._lanes).forEach(function (k) {
                if (k === "coordinator" || k === DEFAULT_LANE) { return; }
                try { self._lanes[k].api.flushModuleReloads(); } catch (e) {}
                const m = self._lanes[k].meta || {};
                laneSummary.push({
                    agent_id: (typeof m.agent_id !== "undefined") ? m.agent_id : k,
                    status: m.status || "done",
                    built: m.built || 0,
                    url: m.url,
                });
            });
            try { self._coord().saveCanvasAsync(); } catch (e) {}

            const summary = { lanes: laneSummary };
            if (onDone) { onDone(summary); }
            return summary;
        } finally {
            this._running = false;
        }
    }

    /** Open ONE lane's SSE stream and route its frames (no plan/done frames). */
    async _laneRequest(siteUrl, headers, body, cbs) {
        const self = this;
        let res;
        try {
            res = await fetch(siteUrl + "api/ai/agent-swarm-lane-stream", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(body),
                credentials: "same-origin",
            });
        } catch (e) {
            if (cbs.onError) { cbs.onError("lane " + body.agent_id + " failed (" + String(e && e.message || e) + ")"); }
            return;
        }
        if (!res.ok || !res.body) {
            if (cbs.onError) { cbs.onError("lane " + body.agent_id + " failed (" + res.status + ")"); }
            return;
        }
        await this._pumpStream(res, self._laneDispatch(cbs));
    }

    /**
     * Open ONE QA reviewer's SSE stream (focus = contrast | spacing) and route
     * its frames. Same transport/dispatch as a page lane, but every frame already
     * carries the reviewer's agent_id (qa-contrast / qa-spacing) from the backend
     * emitter, so the board shows each reviewer as its own agent. A QA failure is
     * non-fatal — it must never abort the save after a successful build.
     */
    async _qaRequest(siteUrl, headers, body, meta, cbs) {
        const self = this;
        // Show the reviewer on the board immediately (started), so it appears even
        // if its stream is slow to open.
        if (cbs.onLane) { cbs.onLane(meta.agent_id, "started", 0, 1, { title: meta.title, url: "", agent_id: meta.agent_id }); }
        let res;
        try {
            res = await fetch(siteUrl + "api/ai/agent-swarm-qa-stream", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(body),
                credentials: "same-origin",
            });
        } catch (e) {
            if (cbs.onLane) { cbs.onLane(meta.agent_id, "error", 0, 1, { title: meta.title, url: "", agent_id: meta.agent_id }); }
            return;
        }
        if (!res.ok || !res.body) {
            if (cbs.onLane) { cbs.onLane(meta.agent_id, "error", 0, 1, { title: meta.title, url: "", agent_id: meta.agent_id }); }
            return;
        }
        // Default any un-tagged frame to this reviewer's agent_id (defensive).
        await this._pumpStream(res, self._laneDispatch(cbs, meta.agent_id));
    }

    /** Dispatch for a single-lane stream: tool / lane / error frames only. */
    _laneDispatch(cbs, fallbackAgentId) {
        const self = this;
        return function (eventName, dataStr) {
            let data = {};
            try { data = dataStr ? JSON.parse(dataStr) : {}; } catch (e) { data = { raw: dataStr }; }
            // A single-lane stream (page lane or QA reviewer) knows its own
            // agent_id, so an un-tagged frame falls back to it rather than the
            // default lane — keeps every frame addressed to the right board card.
            const hasId = (data.agent_id === 0 || !!data.agent_id);
            if (!hasId && fallbackAgentId) { data.agent_id = fallbackAgentId; }

            if (eventName === "tool") {
                const lane = self._lane(data.agent_id);
                let result;
                try { result = lane.api.applyEdit(data); } catch (e) { result = { ok: false, message: String(e && e.message || e) }; }
                if (cbs.onTool) {
                    cbs.onTool((data.agent_id === 0 || data.agent_id) ? data.agent_id : null, Object.assign({}, data, { result: result }));
                }
            } else if (eventName === "lane") {
                const lane = self._lane(data.agent_id);
                lane.meta = data;
                if (cbs.onLane) {
                    cbs.onLane(
                        (data.agent_id === 0 || data.agent_id) ? data.agent_id : null,
                        data.status,
                        (typeof data.built !== "undefined") ? data.built : null,
                        (typeof data.total !== "undefined") ? data.total : null,
                        data
                    );
                }
            } else if (eventName === "error") {
                if (cbs.onError) { cbs.onError(data.message || "error"); }
            }
        };
    }

    /** Shared SSE frame parser: read a response body, dispatch each frame. */
    async _pumpStream(res, dispatch) {
        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let buffer = "";
        for (;;) {
            const chunk = await reader.read();
            if (chunk.done) { break; }
            buffer += decoder.decode(chunk.value, { stream: true });

            let sep;
            while ((sep = buffer.indexOf("\n\n")) !== -1) {
                const frame = buffer.slice(0, sep);
                buffer = buffer.slice(sep + 2);
                let eventName = "message";
                const dataLines = [];
                frame.split("\n").forEach(function (line) {
                    if (line.indexOf("event:") === 0) {
                        eventName = line.slice(6).trim();
                    } else if (line.indexOf("data:") === 0) {
                        dataLines.push(line.slice(5).trim());
                    }
                });
                dispatch(eventName, dataLines.join("\n"));
            }
        }
    }

    /**
     * SEQUENTIAL combined stream (back-compat / fallback): one request streams
     * every lane in order. Used when planning for the parallel path fails.
     */
    async _startSequential(opts = {}) {
        const self = this;
        const message = opts.message;
        const contextBundle = opts.contextBundle || {};
        const onPlan = opts.onPlan;
        const onLane = opts.onLane;
        const onTool = opts.onTool;
        const onDone = opts.onDone;
        const onError = opts.onError;

        this._reset();
        this._running = true;

        const siteUrl = (window.mw && mw.settings && mw.settings.site_url) ? mw.settings.site_url : "";
        const body = Object.assign({ message: message }, contextBundle);

        const headers = { "Content-Type": "application/json", "Accept": "text/event-stream" };
        try {
            const csrf = document.querySelector('meta[name="csrf-token"]');
            if (csrf && csrf.getAttribute("content")) { headers["X-CSRF-TOKEN"] = csrf.getAttribute("content"); }
        } catch (e) {}

        let res;
        try {
            res = await fetch(siteUrl + "api/ai/agent-swarm-stream", {
                method: "POST",
                headers: headers,
                body: JSON.stringify(body),
                credentials: "same-origin",
            });
        } catch (e) {
            this._running = false;
            const msg = "AI swarm stream failed (" + String(e && e.message || e) + ")";
            if (onError) { onError(msg); }
            throw new Error(msg);
        }

        if (!res.ok || !res.body) {
            this._running = false;
            const msg = "AI swarm stream failed (" + res.status + ")";
            if (onError) { onError(msg); }
            throw new Error(msg);
        }

        const reader = res.body.getReader();
        const decoder = new TextDecoder();
        let buffer = "";
        let summary = null;

        // Route one decoded SSE frame. Mirrors MwAi().agentChatStream's dispatch,
        // extended with the swarm's per-lane fan-out.
        const dispatch = function (eventName, dataStr) {
            let data = {};
            try { data = dataStr ? JSON.parse(dataStr) : {}; } catch (e) { data = { raw: dataStr }; }

            if (eventName === "plan") {
                if (onPlan) { onPlan(data); }
            } else if (eventName === "tool") {
                // Route the tool call to the owning lane's MwAi instance so each
                // lane applies against its own per-turn state. applyEdit reads
                // only .tool / .args, so the extra agent_id key is harmless.
                const lane = self._lane(data.agent_id);
                let result;
                try { result = lane.api.applyEdit(data); } catch (e) { result = { ok: false, message: String(e && e.message || e) }; }
                if (onTool) {
                    const edit = Object.assign({}, data, { result: result });
                    onTool((data.agent_id === 0 || data.agent_id) ? data.agent_id : null, edit);
                }
            } else if (eventName === "lane") {
                // Track the latest lane frame on its MwAi instance's bucket.
                const lane = self._lane(data.agent_id);
                lane.meta = data;
                if (onLane) {
                    onLane(
                        (data.agent_id === 0 || data.agent_id) ? data.agent_id : null,
                        data.status,
                        (typeof data.built !== "undefined") ? data.built : null,
                        (typeof data.total !== "undefined") ? data.total : null,
                        data
                    );
                }
            } else if (eventName === "error") {
                if (onError) { onError(data.message || "error"); }
            } else if (eventName === "done") {
                summary = data;
                // Flush each lane's own module-reload queue (menus, products,
                // forms, posts…). Per-lane, because queues are not shared.
                try {
                    Object.keys(self._lanes).forEach(function (k) {
                        try { self._lanes[k].api.flushModuleReloads(); } catch (e) {}
                    });
                } catch (e) {}
                // ONE save for the WHOLE run — never per lane. Fire-and-forget;
                // it round-trips in the background and clears the dirty/askusertostay
                // flags so the next navigation doesn't trip "Leave site?".
                try { self._coord().saveCanvasAsync(); } catch (e) {}
                if (onDone) { onDone(data); }
            }
        };

        // Parse the SSE stream frame by frame ("event:"/"data:" blocks), exactly
        // as MwAi().agentChatStream does.
        try {
            for (;;) {
                const chunk = await reader.read();
                if (chunk.done) { break; }
                buffer += decoder.decode(chunk.value, { stream: true });

                let sep;
                while ((sep = buffer.indexOf("\n\n")) !== -1) {
                    const frame = buffer.slice(0, sep);
                    buffer = buffer.slice(sep + 2);
                    let eventName = "message";
                    const dataLines = [];
                    frame.split("\n").forEach(function (line) {
                        if (line.indexOf("event:") === 0) {
                            eventName = line.slice(6).trim();
                        } else if (line.indexOf("data:") === 0) {
                            dataLines.push(line.slice(5).trim());
                        }
                    });
                    dispatch(eventName, dataLines.join("\n"));
                }
            }
        } finally {
            this._running = false;
        }

        return summary || { lanes: [] };
    }

    isRunning() {
        return this._running;
    }
}

// Singleton across bundles (same reasoning as mw.aiChat): the admin panel may
// inject this as a standalone IIFE while the Live-Edit app imports it as a
// module — both run in the same window on the Live-Edit page. Reuse any instance
// already registered so a run's lane map isn't shadowed by a second coordinator.
let mwAiSwarm;
if (typeof window !== "undefined" && window.mw && window.mw.aiSwarm && typeof window.mw.aiSwarm.start === "function") {
    mwAiSwarm = window.mw.aiSwarm;
} else {
    mwAiSwarm = new MwAiSwarm();
    if (typeof window !== "undefined") {
        window.mw = window.mw || {};
        window.mw.aiSwarm = mwAiSwarm;
    }
}

export { MwAiSwarm };
export default mwAiSwarm;
