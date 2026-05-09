class MicroweberBaseClass {
    #events = {};

    /*
     * AI-106 / TICKET-AZ (cycle-119 2026-05-09): event-replay buffer.
     *
     * Microweber's event bus had no replay — handlers attached AFTER
     * an event fired never received it. Documented as a recurring
     * pain point in the `mw-app-event-bus-no-replay` skill: any
     * Vue component / IIFE / boot-time listener attached late
     * silently misses critical boot events (`onLiveEditReady`,
     * `onAdmin`, etc.).
     *
     * The fix is a "last-emitted payload" buffer per event name.
     * Every `dispatch(e, payload)` updates the buffer. A handler
     * subscribed via `on(e, fn, { replay: true })` synchronously
     * receives the last payload (if any) before being added to the
     * regular subscriber list — so it sees both the historical
     * fire AND every future fire.
     *
     * Backwards-compatible: `on(e, fn)` (no options object) keeps
     * the original no-replay behaviour for callers that explicitly
     * want to ignore prior fires.
     */
    #lastPayload = {};

    on(e, f, options) {
        this.#events[e] ? this.#events[e].push(f) : (this.#events[e] = [f]);

        // Replay last-emitted payload synchronously. Only fires if
        // the event has been dispatched at least once before this
        // subscription.
        if (options && options.replay === true && Object.prototype.hasOwnProperty.call(this.#lastPayload, e)) {
            try {
                f.call(this, this.#lastPayload[e]);
            } catch (err) {
                // Replay must never break the chain; log + swallow.
                if (typeof console !== 'undefined' && console.error) {
                    console.error('[mw.app] replay handler for "' + e + '" raised:', err);
                }
            }
        }

        return this;
    };
    off(e, f) {
        if(!this.#events[e]) {
            return this;
        }
        if(typeof f === 'function') {
            const index = this.#events[e].indexOf(f);
            if(index === -1) {
                return this;
            }
            this.#events[e].splice(index, 1);
        } else {
            this.#events[e] = [];
        }
        return this;
    };

    dispatch (e, f, f2) {
        // AI-106 / TICKET-AZ: capture the last-emitted payload so
        // late `on(e, fn, { replay: true })` subscribers can catch
        // up. Storing the raw payload (no clone) so reference-typed
        // payloads stay live for the replay handler.
        this.#lastPayload[e] = f;

        this.#events[e] ? this.#events[e].forEach(function (c) {
            c.call(this, f);
        }) : '';
        return this;
    };

    emit (e, f) {
        return this.dispatch(e, f)
    };

    /*
     * AI-106 / TICKET-AZ: explicit reset for the replay buffer.
     * Tests that mount a fresh component lifecycle can call this to
     * avoid prior-test state leaking into a replay subscription.
     */
    clearReplayBuffer (e) {
        if (typeof e === 'string') {
            delete this.#lastPayload[e];
        } else {
            this.#lastPayload = {};
        }
        return this;
    };

    /*
     * AI-106 / TICKET-AZ: introspection — has this event been
     * dispatched at least once? Useful for callers that want to
     * skip the "no replay" warning when they know the event has
     * already fired.
     */
    hasReplayPayload (e) {
        return Object.prototype.hasOwnProperty.call(this.#lastPayload, e);
    };

}

export default MicroweberBaseClass;
