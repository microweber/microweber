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

    /**
     * Open the swarm SSE stream and route frames to lanes.
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
