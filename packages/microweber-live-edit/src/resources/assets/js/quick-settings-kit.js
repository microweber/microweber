// task-2026-09-14-qskit — Shared Live-Edit "quick settings" framework.
//
// The designer rebuilt every module's settings as a compact 320px side panel
// (see "Module quick settings — 45 panels"). Rather than hand-coding each one
// (as Modules/Btn does in 380 lines), this kit renders a panel from a small
// DECLARATIVE config and wires each control to the SAME module option the
// module's real settings form writes — so the template already consumes it
// (the failure mode that silently broke the Btn panel: writing a key the
// template never reads).
//
// Register configs with mw.quickSettingsKit.register({ type, ...config }).
// Each becomes a single module-handle entry that opens the floating panel.
//
// Control vocabulary (matches the design):
//   segmented  — a row of equal cells; use when options are few.
//   toggle     — a quiet on/off row.
//   swatches   — curated colour circles + a "custom" chip (shared MW picker).
//   select     — a native dropdown; use when options are many.
//   text       — a text / number field (optional suffix + inputType).
//   link       — a URL field + "Page" button (defers to full settings picker).
//   advanced   — a row that opens the module's full settings dialog.
(function () {
    if (window.mw && window.mw.quickSettingsKit) { return; }
    var mw = window.mw = window.mw || {};
    mw.quickSettings = mw.quickSettings || {};

    function lang(s) { try { return mw.lang ? mw.lang(s) : s; } catch (e) { return s; } }

    // 6-dot grip icon for the drag handle.
    var dragDots = '<svg viewBox="0 0 16 16" width="13" height="13" fill="currentColor" aria-hidden="true"><circle cx="6" cy="4" r="1.25"/><circle cx="10" cy="4" r="1.25"/><circle cx="6" cy="8" r="1.25"/><circle cx="10" cy="8" r="1.25"/><circle cx="6" cy="12" r="1.25"/><circle cx="10" cy="12" r="1.25"/></svg>';

    // ── top-window plumbing (the panel mounts above the canvas iframe) ──────
    function topDoc() {
        try { if (window.top && window.top.document) { return window.top.document; } } catch (e) {}
        return document;
    }
    function frameOffset() {
        try {
            var fr = mw.top().app.canvas.getFrame();
            var r = fr.getBoundingClientRect();
            return { x: r.left, y: r.top };
        } catch (e) { return { x: 0, y: 0 }; }
    }
    // The canvas is a separate iframe document — clicks there don't reach the
    // top-window click listener, so outside-click-close needs to listen on it too.
    function canvasDoc() {
        try {
            var fr = mw.top().app.canvas.getFrame();
            return fr.contentDocument || (fr.contentWindow && fr.contentWindow.document) || null;
        } catch (e) { return null; }
    }

    // Real module icon from the modules service Live Edit already loads
    // (mw.app.modules — Modules.list() runs on boot, data is cached; NO new
    // request). Returns processed icon HTML (<img> data-URI SVG or inline SVG),
    // or null so the caller can fall back to the 2-letter badge.
    function moduleIcon(type) {
        if (!type) { return null; }
        try {
            var svc = (mw.top && mw.top().app && mw.top().app.modules) ? mw.top().app.modules
                : (mw.app && mw.app.modules ? mw.app.modules : null);
            if (svc && typeof svc.getModuleIcon === 'function' && svc.modulesListData) {
                var html = svc.getModuleIcon(type);
                if (html && typeof html === 'string') { return html; }
            }
        } catch (e) {}
        return null;
    }

    // ── module option round-trip (identical contract to the Btn panel) ──────
    function readOptions(el) {
        try {
            var d = mw.top().app.modules.getModuleInlineViewData(el.getAttribute('id'));
            return (d && d.options) || {};
        } catch (e) { return {}; }
    }
    function saveOption(el, key, value, cb) {
        var moduleId = el.getAttribute('id');
        var moduleType = el.getAttribute('data-type') || el.getAttribute('type');
        mw.options.saveOption({
            option_group: moduleId,
            option_key: key,
            option_value: value,
            module: moduleType
        }, function () {
            mw.app.editor.dispatch('onModuleSettingsChanged', { 'moduleId': moduleId });
            if (typeof cb === 'function') { cb(); }
        });
    }

    // ── authed JSON request (mirrors mw-ai.js: CSRF meta + same-origin) ─────
    function qsHttp(method, path, body) {
        var base = (mw.settings && mw.settings.site_url) ? mw.settings.site_url : '/';
        var headers = { 'Accept': 'application/json' };
        if (body) { headers['Content-Type'] = 'application/json'; }
        try {
            var meta = topDoc().querySelector('meta[name="csrf-token"]')
                || document.querySelector('meta[name="csrf-token"]');
            if (meta) { headers['X-CSRF-TOKEN'] = meta.getAttribute('content'); }
        } catch (e) {}
        return fetch(base + path, {
            method: method,
            headers: headers,
            credentials: 'same-origin',
            body: body ? JSON.stringify(body) : undefined
        }).then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); });
    }

    // ── curated colour palette (site palette → recommended swatches) ────────
    function contrast(hex) {
        try {
            var c = String(hex).trim().replace('#', '');
            if (c.length === 3) { c = c[0] + c[0] + c[1] + c[1] + c[2] + c[2]; }
            var r = parseInt(c.substr(0, 2), 16), g = parseInt(c.substr(2, 2), 16), b = parseInt(c.substr(4, 2), 16);
            return ((0.299 * r + 0.587 * g + 0.114 * b) / 255) > 0.62 ? '#182433' : '#ffffff';
        } catch (e) { return '#ffffff'; }
    }
    var FALLBACK_COLORS = ['#0d6efd', '#2fb344', '#dc2626', '#f59e0b', '#7c3aed', '#182433', '#ffffff'];
    function recommendedColors() {
        try {
            var mgr = mw.top().app.templateSettings && mw.top().app.templateSettings.colorPaletteManager;
            if (mgr && mgr.getColors) {
                var seen = {}, out = [];
                (mgr.getColors() || []).forEach(function (c) {
                    if (c && typeof c === 'string' && /^#([0-9a-fA-F]{3,8})$/.test(c)) {
                        var low = c.toLowerCase();
                        if (!seen[low]) { seen[low] = 1; out.push(c); }
                    }
                });
                if (out.length) { return out.slice(0, 8); }
            }
        } catch (e) {}
        return FALLBACK_COLORS;
    }

    // ── one-time CSS ────────────────────────────────────────────────────────
    function injectCss() {
        var doc = topDoc();
        if (doc.getElementById('mw-qs-panel-css')) { return; }
        var s = doc.createElement('style');
        s.id = 'mw-qs-panel-css';
        s.textContent = [
            '.mw-qs-panel{position:fixed;z-index:100061;width:300px;max-width:calc(100vw - 16px);max-height:calc(100vh - 24px);overflow:auto;',
            'background:#fff;color:#182433;border-radius:14px;padding:14px;',
            'box-shadow:0 10px 34px rgba(24,36,51,.16),0 2px 8px rgba(24,36,51,.08);',
            'font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;font-size:13px;}',
            'html.dark .mw-qs-panel{background:#1b1e22;color:#e8eaed;box-shadow:0 12px 40px rgba(0,0,0,.6);}',
            '.mw-qs-panel__head{display:flex;align-items:center;gap:8px;margin-bottom:12px;}',
            '.mw-qs-panel__badge{width:28px;height:28px;border-radius:8px;background:#182433;color:#fff;font-weight:600;font-size:11px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;letter-spacing:.02em;}',
            'html.dark .mw-qs-panel__badge{background:#e8eaed;color:#182433;}',
            // real module icon: light surface + ink glyph (the icon SVGs use
            // currentColor / are dark, so an ink-on-light chip reads correctly).
            '.mw-qs-panel__badge--icon{background:#F4F4F2;color:#182433;padding:4px;}',
            '.mw-qs-panel__badge--icon img,.mw-qs-panel__badge--icon svg{width:20px;height:20px;display:block;object-fit:contain;}',
            'html.dark .mw-qs-panel__badge--icon{background:#2a2e34;color:#e8eaed;}',
            'html.dark .mw-qs-panel__badge--icon img{filter:invert(1) brightness(1.6);}',
            '.mw-qs-panel__title{font-weight:600;font-size:14px;flex:1 1 auto;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}',
            '.mw-qs-panel__head-actions{display:flex;gap:2px;flex:0 0 auto;}',
            '.mw-qs-panel__ico{width:30px;height:30px;border:0;border-radius:7px;background:transparent;color:inherit;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}',
            '.mw-qs-panel__ico:hover{background:#18243310;}',
            'html.dark .mw-qs-panel__ico:hover{background:#ffffff16;}',
            '.mw-qs-panel__ico.is-danger:hover{background:rgba(220,38,38,.35);}',
            '.mw-qs-panel__ico svg{width:16px;height:16px;}',
            '.mw-qs-tabs{display:flex;gap:4px;background:#18243308;border-radius:9px;padding:3px;margin-bottom:12px;}',
            '.mw-qs-tab{flex:1 1 0;min-height:32px;border:0;border-radius:7px;background:transparent;color:#77776f;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;}',
            '.mw-qs-tab.active{background:#fff;color:#182433;box-shadow:0 1px 3px rgba(24,36,51,.12);}',
            'html.dark .mw-qs-tab{color:#9aa3af;}html.dark .mw-qs-tab.active{background:#2a2e34;color:#e8eaed;}',
            '.mw-qs-section{margin-bottom:12px;}.mw-qs-section:last-child{margin-bottom:0;}',
            '.mw-qs-label{font-size:11px;font-weight:600;letter-spacing:.02em;color:#8a94a3;margin-bottom:6px;}',
            '.mw-qs-seg{display:flex;gap:6px;}.mw-qs-seg .mw-qs-cell{flex:1 1 0;}',
            '.mw-qs-cell{min-height:36px;padding:7px 10px;border:1px solid #18243318;border-radius:9px;background:#18243305;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;display:inline-flex;align-items:center;justify-content:center;transition:background-color .15s,border-color .15s,color .15s;}',
            '.mw-qs-cell:hover{background:#1824330d;border-color:#18243230;}',
            '.mw-qs-cell.active{border-color:#182433;box-shadow:inset 0 0 0 1px #182433;color:#182433;}',
            'html.dark .mw-qs-cell{background:#ffffff08;border-color:#ffffff1f;}html.dark .mw-qs-cell:hover{background:#ffffff14;}',
            'html.dark .mw-qs-cell.active{border-color:#e8eaed;box-shadow:inset 0 0 0 1px #e8eaed;color:#e8eaed;}',
            '.mw-qs-swatches{display:flex;flex-wrap:wrap;gap:8px;}',
            '.mw-qs-sw{width:26px;height:26px;border-radius:50%;border:1px solid rgba(0,0,0,.12);cursor:pointer;padding:0;position:relative;transition:transform .1s;}',
            '.mw-qs-sw:hover{transform:scale(1.08);}',
            '.mw-qs-sw.active{box-shadow:0 0 0 2px #fff,0 0 0 4px #182433;}',
            'html.dark .mw-qs-sw.active{box-shadow:0 0 0 2px #1b1e22,0 0 0 4px #e8eaed;}',
            'html.dark .mw-qs-sw{border-color:rgba(255,255,255,.18);}',
            '.mw-qs-sw--custom{display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#8a94a3;border:1px dashed #18243340;font-size:16px;line-height:1;}',
            '.mw-qs-sw--custom:hover{color:#182433;border-color:#18243366;transform:none;}',
            'html.dark .mw-qs-sw--custom{background:#22262c;color:#9aa3af;border-color:#ffffff33;}',
            'html.dark .mw-qs-sw--custom:hover{color:#e8eaed;border-color:#ffffff66;transform:none;}',
            '.mw-qs-row{display:flex;align-items:center;justify-content:space-between;gap:10px;min-height:36px;}',
            '.mw-qs-row__txt{min-width:0;}.mw-qs-row__txt small{display:block;color:#8a94a3;font-size:10.5px;margin-top:1px;}',
            '.mw-qs-input,.mw-qs-select{width:100%;min-width:0;border:1px solid #18243326;border-radius:9px;padding:8px 10px;font:inherit;font-size:12.5px;background:#fff;color:#182433;outline:none;}',
            '.mw-qs-input:focus,.mw-qs-select:focus{border-color:#182433;box-shadow:0 0 0 3px #1824331f;}',
            'html.dark .mw-qs-input,html.dark .mw-qs-select{background:#22262c;color:#e8eaed;border-color:#ffffff26;}',
            'html.dark .mw-qs-input:focus,html.dark .mw-qs-select:focus{border-color:#e8eaed;box-shadow:0 0 0 3px rgba(232,234,237,.22);}',
            '.mw-qs-input::placeholder,.mw-qs-select::placeholder{color:#8a94a3;opacity:1;}',
            // custom ink caret on selects (native OS arrow is low-contrast on the dark fill)
            '.mw-qs-select,select.mw-qs-input{-webkit-appearance:none;-moz-appearance:none;appearance:none;padding-right:28px;background-image:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'none\' stroke=\'%23182433\' stroke-width=\'2\'><path d=\'M4 6l4 4 4-4\'/></svg>");background-repeat:no-repeat;background-position:right 10px center;}',
            'html.dark .mw-qs-select,html.dark select.mw-qs-input{background-image:url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'16\' height=\'16\' fill=\'none\' stroke=\'%23e8eaed\' stroke-width=\'2\'><path d=\'M4 6l4 4 4-4\'/></svg>");}',
            '.mw-qs-field{display:flex;gap:6px;align-items:center;}',
            '.mw-qs-field .mw-qs-input,.mw-qs-field .mw-qs-select{flex:1 1 auto;}',
            '.mw-qs-suffix{flex:0 0 auto;color:#6b7280;font-size:11.5px;}',
            // unit suffix (px, %) sits INSIDE the input on the right, not as loose
            // text hanging off the field edge — so the input keeps full width and a
            // single clean right edge.
            '.mw-qs-field--unit{position:relative;}',
            '.mw-qs-field--unit .mw-qs-input{padding-right:30px;}',
            '.mw-qs-field--unit .mw-qs-suffix{position:absolute;right:11px;top:50%;transform:translateY(-50%);pointer-events:none;}',
            '.mw-qs-pick{flex:0 0 auto;padding:8px 12px;border:1px solid #18243318;border-radius:9px;background:#18243308;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;}',
            '.mw-qs-pick:hover{background:#1824330d;}',
            'html.dark .mw-qs-pick{background:#ffffff0d;border-color:#ffffff1f;}',
            'html.dark .mw-qs-pick:hover{background:#ffffff16;}',
            // quiet toggle
            '.mw-qs-toggle{position:relative;width:38px;height:22px;border-radius:22px;border:0;background:#c9ccd2;cursor:pointer;flex:0 0 auto;transition:background .15s;padding:0;}',
            '.mw-qs-toggle::after{content:"";position:absolute;top:2px;left:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:transform .15s;box-shadow:0 1px 2px rgba(0,0,0,.25);}',
            '.mw-qs-toggle:hover{background:#b4b8bf;}',
            '.mw-qs-toggle.on{background:#182433;}.mw-qs-toggle.on::after{transform:translateX(16px);}',
            '.mw-qs-toggle.on:hover{background:#0f1722;}',
            'html.dark .mw-qs-toggle{background:#4b4f57;}html.dark .mw-qs-toggle.on{background:#e8eaed;}html.dark .mw-qs-toggle.on::after{background:#182433;}',
            'html.dark .mw-qs-toggle:hover{background:#565a63;}html.dark .mw-qs-toggle.on:hover{background:#ffffff;}',
            // advanced row
            '.mw-qs-advanced{display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;border:0;border-top:1px solid #18243314;background:transparent;color:inherit;cursor:pointer;font:inherit;padding:12px 0 2px;margin-top:4px;text-align:left;}',
            '.mw-qs-advanced small{display:block;color:#8a94a3;font-size:10.5px;margin-top:1px;font-weight:400;}',
            '.mw-qs-advanced__t{font-size:12.5px;font-weight:600;}',
            'html.dark .mw-qs-advanced{border-color:#ffffff14;}',
            // inline item list
            '.mw-qs-items{display:flex;flex-direction:column;gap:6px;}',
            '.mw-qs-item{border:1px solid #18243318;border-radius:9px;background:#18243305;overflow:hidden;}',
            'html.dark .mw-qs-item{border-color:#ffffff1f;background:#ffffff08;}',
            '.mw-qs-item__head{display:flex;align-items:center;gap:6px;padding:7px 8px;}',
            '.mw-qs-item__toggle{flex:1 1 auto;min-width:0;display:flex;align-items:center;gap:6px;border:0;background:transparent;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;text-align:left;padding:0;}',
            '.mw-qs-item__toggle span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}',
            '.mw-qs-item__caret{flex:0 0 auto;transition:transform .15s;color:#8a94a3;}',
            '.mw-qs-item.open .mw-qs-item__caret{transform:rotate(90deg);}',
            '.mw-qs-item__actions{flex:0 0 auto;display:flex;gap:1px;}',
            '.mw-qs-item__act{width:24px;height:24px;border:0;border-radius:6px;background:transparent;color:#8a94a3;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-size:13px;line-height:1;}',
            '.mw-qs-item__act:hover{background:#18243312;color:#182433;}',
            'html.dark .mw-qs-item__act:hover{background:#ffffff16;color:#e8eaed;}',
            '.mw-qs-item__act.is-danger:hover{background:rgba(220,38,38,.28);color:#dc2626;}',
            '.mw-qs-item__act[disabled]{opacity:.3;cursor:default;background:transparent;}',
            '.mw-qs-item__body{padding:0 8px 8px;display:flex;flex-direction:column;gap:8px;}',
            '.mw-qs-item__body textarea.mw-qs-input{min-height:56px;resize:vertical;}',
            '.mw-qs-item__field{display:flex;flex-direction:column;gap:3px;}',
            '.mw-qs-item__flabel{font-size:10.5px;font-weight:600;letter-spacing:.02em;color:#8a94a3;}',
            '.mw-qs-items__empty{color:#8a94a3;font-size:12px;padding:6px 2px;}',
            '.mw-qs-mi-badge{font-size:10px;font-weight:600;letter-spacing:.02em;color:#77776f;background:#18243310;border-radius:6px;padding:2px 7px;align-self:center;white-space:nowrap;}',
            'html.dark .mw-qs-mi-badge{color:#c3c8d0;background:#ffffff14;}',
            // Cap the menu item list so a long menu scrolls INSIDE the panel
            // instead of stretching it to the full viewport height. The list is
            // a flex column, so its rows must NOT flex-shrink (they would squash
            // to a sliver under the capped height instead of overflowing).
            '.mw-qs-menuitems{max-height:300px;overflow-y:auto;overflow-x:hidden;margin:0 -2px;padding:2px;}',
            '.mw-qs-menuitems .mw-qs-item{flex:0 0 auto;}',
            '.mw-qs-items__add--top{margin:0 0 8px;}',
            // drag handle + drop hints
            '.mw-qs-item__drag{flex:0 0 auto;display:inline-flex;align-items:center;justify-content:center;width:18px;color:#b3b9c2;cursor:grab;user-select:none;}',
            '.mw-qs-item__drag:active{cursor:grabbing;}',
            'html.dark .mw-qs-item__drag{color:#6b7280;}',
            '.mw-qs-item--dragging{opacity:.45;}',
            '.mw-qs-item--over-top{box-shadow:inset 0 2px 0 0 #182433;}',
            '.mw-qs-item--over-bot{box-shadow:inset 0 -2px 0 0 #182433;}',
            'html.dark .mw-qs-item--over-top{box-shadow:inset 0 2px 0 0 #e8eaed;}',
            'html.dark .mw-qs-item--over-bot{box-shadow:inset 0 -2px 0 0 #e8eaed;}',
            // add-item picker popup
            '.mw-qs-add-pop{position:fixed;z-index:100062;background:#fff;color:#182433;border-radius:14px;box-shadow:0 12px 40px rgba(24,36,51,.28);padding:12px;}',
            'html.dark .mw-qs-add-pop{background:#1b1e22;color:#e8eaed;box-shadow:0 12px 40px rgba(0,0,0,.6);}',
            '.mw-qs-add-pop__head{display:flex;align-items:center;justify-content:space-between;font-weight:600;font-size:13px;margin-bottom:10px;}',
            '.mw-qs-add-pop__link{width:100%;margin-bottom:8px;}',
            '.mw-qs-add-pop__search{margin-bottom:8px;}',
            '.mw-qs-add-pop__list{max-height:320px;overflow-y:auto;overflow-x:hidden;display:flex;flex-direction:column;gap:4px;padding:2px;}',
            '.mw-qs-pick-row{flex:0 0 auto;display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;text-align:left;border:1px solid #18243314;border-radius:8px;background:#18243305;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;padding:8px 10px;}',
            '.mw-qs-pick-row:hover{background:#1824330d;border-color:#18243230;}',
            '.mw-qs-pick-row__t{min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}',
            'html.dark .mw-qs-pick-row{background:#ffffff08;border-color:#ffffff1f;}',
            'html.dark .mw-qs-pick-row:hover{background:#ffffff14;}',
            // nested (submenu) rows: indented via inline margin + a hierarchy accent
            '.mw-qs-item--nested{border-left:2px solid #18243326;}',
            'html.dark .mw-qs-item--nested{border-left-color:#ffffff2e;}',
            '.mw-qs-items__add{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:8px;}',
            '.mw-qs-add{border:1px dashed #18243340;border-radius:9px;background:transparent;color:#182433;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;padding:8px 10px;flex:1 1 auto;}',
            '.mw-qs-add:hover{border-color:#182433;background:#18243308;}',
            'html.dark .mw-qs-add{color:#e8eaed;border-color:#ffffff33;}',
            'html.dark .mw-qs-add:hover{border-color:#e8eaed;background:#ffffff0d;}',
            '.mw-qs-ai{flex:0 0 auto;border:0;border-radius:9px;background:#18243310;color:#182433;cursor:pointer;font:inherit;font-size:12px;font-weight:500;padding:8px 10px;display:inline-flex;align-items:center;gap:4px;}',
            '.mw-qs-ai:hover{background:#18243320;}',
            'html.dark .mw-qs-ai{background:#ffffff14;color:#e8eaed;}',
            'html.dark .mw-qs-ai:hover{background:#ffffff22;}',
            // single-image picker (logo / favicon style)
            '.mw-qs-imgpick{display:flex;align-items:center;gap:10px;}',
            '.mw-qs-imgpick__thumb{flex:none;width:48px;height:48px;border-radius:8px;background-color:#18243308;border:1px solid #18243318;background-size:cover;background-position:center;background-repeat:no-repeat;display:flex;align-items:center;justify-content:center;color:#18243366;}',
            'html.dark .mw-qs-imgpick__thumb{background-color:#ffffff08;border-color:#ffffff1f;color:#ffffff66;}',
            '.mw-qs-imgpick.has-img .mw-qs-imgpick__ph{display:none;}',
            '.mw-qs-imgpick__act{display:flex;gap:6px;flex-wrap:wrap;}',
            '.mw-qs-btn2{height:30px;padding:0 12px;border-radius:8px;border:1px solid transparent;background:#182433;color:#fff;font:inherit;font-size:12px;font-weight:500;cursor:pointer;display:inline-flex;align-items:center;}',
            '.mw-qs-btn2:hover{opacity:.9;}',
            '.mw-qs-btn2--ghost{background:#fff;color:#182433;border-color:#18243322;}',
            '.mw-qs-btn2--ghost:hover{background:#18243308;opacity:1;}',
            'html.dark .mw-qs-btn2{background:#e8eaed;color:#182433;}',
            'html.dark .mw-qs-btn2--ghost{background:transparent;color:#e8eaed;border-color:#ffffff1f;}',
            '.mw-qs-imgpick:not(.has-img) [data-act="remove"]{display:none;}',
            '.mw-qs-btn2:focus-visible{outline:2px solid #182433;outline-offset:2px;}',
            'html.dark .mw-qs-btn2:focus-visible{outline-color:#e8eaed;}',
            // image gallery
            '.mw-qs-images{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;}',
            '.mw-qs-image{position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid #18243318;background:#18243308;}',
            'html.dark .mw-qs-image{border-color:#ffffff1f;background:#ffffff08;}',
            '.mw-qs-image img{width:100%;height:100%;object-fit:cover;display:block;}',
            '.mw-qs-image[draggable="true"]{cursor:grab;}',
            '.mw-qs-image--dragging{opacity:.4;}',
            '.mw-qs-image--over{box-shadow:0 0 0 2px #182433;}',
            'html.dark .mw-qs-image--over{box-shadow:0 0 0 2px #e8eaed;}',
            '.mw-qs-image__del{position:absolute;top:3px;right:3px;width:20px;height:20px;border:0;border-radius:50%;background:rgba(24,36,51,.72);color:#fff;cursor:pointer;font-size:11px;line-height:1;display:inline-flex;align-items:center;justify-content:center;opacity:0;transition:opacity .12s;}',
            '.mw-qs-image:hover .mw-qs-image__del{opacity:1;}',
            '.mw-qs-image__del:hover{background:#dc2626;}',
            '.mw-qs-image__nav{position:absolute;left:0;right:0;bottom:0;display:flex;justify-content:space-between;opacity:0;transition:opacity .12s;}',
            '.mw-qs-image:hover .mw-qs-image__nav{opacity:1;}',
            '.mw-qs-image__nav button{width:22px;height:22px;border:0;background:rgba(24,36,51,.6);color:#fff;cursor:pointer;font-size:13px;line-height:1;}',
            '.mw-qs-image__nav button:hover{background:rgba(24,36,51,.85);}',
            '.mw-qs-image__nav button[disabled]{opacity:.25;cursor:default;}',
            // imagelist empty/loading message spans the full grid, not a 1/3 sliver
            '.mw-qs-images .mw-qs-items__empty{grid-column:1/-1;}',
            // reveal image controls for keyboard focus + touch (not only pointer hover)
            '.mw-qs-image:focus-within .mw-qs-image__del,.mw-qs-image:focus-within .mw-qs-image__nav{opacity:1;}',
            '@media (hover:none){.mw-qs-image__del,.mw-qs-image__nav{opacity:1;}}',
            // larger tap targets on isolated controls (no ::before insets on tight clusters)
            '.mw-qs-panel__ico{width:34px;height:34px;}',
            '.mw-qs-item__act{width:28px;height:28px;}',
            '.mw-qs-image__del{width:22px;height:22px;}',
            '.mw-qs-image__del::before{content:"";position:absolute;inset:-8px;}',
            '.mw-qs-image__nav button{height:26px;min-width:26px;}',
            // one coherent ink focus-visible ring across every custom button control
            '.mw-qs-cell:focus-visible,.mw-qs-tab:focus-visible,.mw-qs-sw:focus-visible,.mw-qs-toggle:focus-visible,.mw-qs-panel__ico:focus-visible,.mw-qs-item__act:focus-visible,.mw-qs-item__toggle:focus-visible,.mw-qs-advanced:focus-visible,.mw-qs-pick:focus-visible,.mw-qs-add:focus-visible,.mw-qs-ai:focus-visible,.mw-qs-image__del:focus-visible,.mw-qs-image__nav button:focus-visible{outline:2px solid #182433;outline-offset:2px;}',
            'html.dark .mw-qs-cell:focus-visible,html.dark .mw-qs-tab:focus-visible,html.dark .mw-qs-sw:focus-visible,html.dark .mw-qs-toggle:focus-visible,html.dark .mw-qs-panel__ico:focus-visible,html.dark .mw-qs-item__act:focus-visible,html.dark .mw-qs-item__toggle:focus-visible,html.dark .mw-qs-advanced:focus-visible,html.dark .mw-qs-pick:focus-visible,html.dark .mw-qs-add:focus-visible,html.dark .mw-qs-ai:focus-visible,html.dark .mw-qs-image__del:focus-visible,html.dark .mw-qs-image__nav button:focus-visible{outline-color:#e8eaed;}',
            '.mw-qs-image__del:focus-visible,.mw-qs-image__nav button:focus-visible{opacity:1;outline:2px solid #fff;outline-offset:-2px;}'
        ].join('');
        doc.head.appendChild(s);
    }

    // ── control renderers ───────────────────────────────────────────────────
    function esc(v) { return String(v == null ? '' : v).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;'); }
    function eq(a, b) { return String(a == null ? '' : a) === String(b == null ? '' : b); }

    function renderControl(c, opts) {
        var cur = opts[c.key];
        if (typeof cur === 'undefined' && typeof c.def !== 'undefined') { cur = c.def; }
        var label = c.label ? '<div class="mw-qs-label">' + esc(lang(c.label)) + '</div>' : '';

        if (c.type === 'segmented') {
            var cells = (c.options || []).map(function (o) {
                var on = eq(o.value, cur);
                return '<button type="button" class="mw-qs-cell' + (on ? ' active' : '') + '"'
                    + ' role="radio" aria-checked="' + (on ? 'true' : 'false') + '"'
                    + ' data-ctl="segmented" data-key="' + esc(c.key) + '" data-val="' + esc(o.value) + '">' + esc(lang(o.label)) + '</button>';
            }).join('');
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-seg" role="radiogroup"'
                + (c.label ? ' aria-label="' + esc(lang(c.label)) + '"' : '') + '>' + cells + '</div></div>';
        }
        if (c.type === 'swatches') {
            var low = String(cur || '').toLowerCase();
            var matched = false;
            var sw = recommendedColors().map(function (col) {
                var isOn = col.toLowerCase() === low;
                if (isOn) { matched = true; }
                return '<button type="button" class="mw-qs-sw' + (isOn ? ' active' : '') + '"'
                    + ' data-ctl="swatch" data-key="' + esc(c.key) + '" data-col="' + esc(col) + '" aria-label="' + esc(col) + '"'
                    + ' style="background:' + esc(col) + '"></button>';
            }).join('');
            // Custom chip: when the saved colour isn't a recommended swatch, show
            // it AS the custom chip (filled + active) so the applied colour is visible.
            var customUnmatched = cur && !matched;
            sw += '<button type="button" class="mw-qs-sw mw-qs-sw--custom' + (customUnmatched ? ' active' : '') + '"'
                + ' data-ctl="swatch-custom" data-key="' + esc(c.key) + '" title="' + esc(lang('Custom')) + '"'
                + (customUnmatched ? ' style="background:' + esc(cur) + '"' : '') + '>' + (customUnmatched ? '' : '+') + '</button>';
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-swatches">' + sw + '</div></div>';
        }
        if (c.type === 'select') {
            var os = (c.options || []).map(function (o) {
                return '<option value="' + esc(o.value) + '"' + (eq(o.value, cur) ? ' selected' : '') + '>' + esc(lang(o.label)) + '</option>';
            }).join('');
            return '<div class="mw-qs-section">' + label + '<select class="mw-qs-select" data-ctl="select" data-key="' + esc(c.key) + '" aria-label="' + esc(lang(c.label || c.key)) + '">' + os + '</select></div>';
        }
        if (c.type === 'text') {
            var suffix = c.suffix ? '<span class="mw-qs-suffix">' + esc(lang(c.suffix)) + '</span>' : '';
            // enableKey: a companion boolean option written alongside this field
            // (1 when non-empty, 0 when cleared) — e.g. SocialLinks' <net>_enabled.
            var enableAttr = c.enableKey ? ' data-enable-key="' + esc(c.enableKey) + '"' : '';
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-field' + (c.suffix ? ' mw-qs-field--unit' : '') + '">'
                + '<input type="' + (c.inputType || 'text') + '" class="mw-qs-input" data-ctl="text" data-key="' + esc(c.key) + '"' + enableAttr
                + ' aria-label="' + esc(lang(c.label || c.placeholder || c.key)) + '"'
                + ' placeholder="' + esc(lang(c.placeholder || '')) + '" value="' + esc(cur) + '">' + suffix + '</div></div>';
        }
        if (c.type === 'toggle') {
            var on = String(cur) === String(typeof c.onValue !== 'undefined' ? c.onValue : '1')
                || cur === true || String(cur) === 'true' || String(cur) === '1';
            var hint = c.hint ? '<small>' + esc(lang(c.hint)) + '</small>' : '';
            return '<div class="mw-qs-section"><div class="mw-qs-row"><div class="mw-qs-row__txt">' + esc(lang(c.label)) + hint + '</div>'
                + '<button type="button" class="mw-qs-toggle' + (on ? ' on' : '') + '" data-ctl="toggle" data-key="' + esc(c.key) + '"'
                + ' role="switch" aria-checked="' + (on ? 'true' : 'false') + '" aria-label="' + esc(lang(c.label)) + '"'
                + ' data-on="' + esc(typeof c.onValue !== 'undefined' ? c.onValue : '1') + '" data-off="' + esc(typeof c.offValue !== 'undefined' ? c.offValue : '') + '"></button></div></div>';
        }
        if (c.type === 'link') {
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-field">'
                + '<input type="text" class="mw-qs-input" data-ctl="text" data-key="' + esc(c.key) + '" aria-label="' + esc(lang(c.label || 'Link')) + '" placeholder="' + esc(lang('Paste a URL')) + '" value="' + esc(cur) + '">'
                + '<button type="button" class="mw-qs-pick" data-ctl="open-settings">' + esc(lang('Page')) + '</button></div></div>';
        }
        if (c.type === 'image') {
            // Single image option (e.g. Logo's logoimage). Thumb + Choose/Remove;
            // wire() opens the shared media picker and writes opts[key] = url.
            var iurl = cur ? String(cur) : '';
            var thumbStyle = iurl ? ' style="background-image:url(' + esc(iurl) + ')"' : '';
            return '<div class="mw-qs-section">' + label
                + '<div class="mw-qs-imgpick' + (iurl ? ' has-img' : '') + '" data-ctl="imagepick" data-key="' + esc(c.key) + '">'
                + '<span class="mw-qs-imgpick__thumb"' + thumbStyle + '>'
                + '<svg class="mw-qs-imgpick__ph" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>'
                + '</span>'
                + '<div class="mw-qs-imgpick__act">'
                + '<button type="button" class="mw-qs-btn2" data-act="pick">' + esc(lang(c.pickLabel || 'Choose image')) + '</button>'
                + '<button type="button" class="mw-qs-btn2 mw-qs-btn2--ghost" data-act="remove">' + esc(lang('Remove')) + '</button>'
                + '</div></div></div>';
        }
        if (c.type === 'itemlist') {
            // Async: rendered empty here; wire() fetches + fills it. Endpoint +
            // field defs are stashed on the container as JSON.
            var cfg = JSON.stringify({ endpoint: c.endpoint, fields: c.fields || [{ key: 'title' }], ai: !!c.ai });
            return '<div class="mw-qs-section">' + label
                + '<div class="mw-qs-items" data-ctl="itemlist" data-cfg="' + esc(cfg) + '"><div class="mw-qs-items__empty">' + esc(lang('Loading…')) + '</div></div>'
                + '<div class="mw-qs-items__add">'
                + '<button type="button" class="mw-qs-add" data-ctl="itemlist-add">+ ' + esc(lang(c.addLabel || 'Add item')) + '</button>'
                + (c.ai ? '<button type="button" class="mw-qs-ai" data-ctl="itemlist-ai" title="' + esc(lang('Create with AI')) + '">✦ ' + esc(lang('AI')) + '</button>' : '')
                + '</div></div>';
        }
        if (c.type === 'imagelist') {
            // Async image gallery: wire() fetches + fills; add opens the shared
            // media picker (mw.filePickerDialog).
            var icfg = JSON.stringify({ endpoint: c.endpoint });
            return '<div class="mw-qs-section">' + label
                + '<div class="mw-qs-images" data-ctl="imagelist" data-cfg="' + esc(icfg) + '"><div class="mw-qs-items__empty">' + esc(lang('Loading…')) + '</div></div>'
                + '<div class="mw-qs-items__add"><button type="button" class="mw-qs-add" data-ctl="imagelist-add">+ ' + esc(lang(c.addLabel || 'Add image')) + '</button></div>'
                + '</div>';
        }
        if (c.type === 'menuselect') {
            // Which menu the module shows. Options fetched in wire() (api/menu/list);
            // change writes the module's menu_name option + reloads the item list.
            return '<div class="mw-qs-section">' + label
                + '<select class="mw-qs-select" data-ctl="menuselect" data-key="' + esc(c.key || 'menu_name') + '" aria-label="' + esc(lang(c.label || 'Menu')) + '">'
                + '<option>' + esc(lang('Loading…')) + '</option></select></div>';
        }
        if (c.type === 'menuitems') {
            // The selected menu's links — expandable rows with a Page/Link/Category
            // badge, inline label/url edit, drag-reorder, delete. The Add button
            // sits ON TOP and opens a picker popup (wired in wire()).
            return '<div class="mw-qs-section">' + label
                + '<div class="mw-qs-items__add mw-qs-items__add--top"><button type="button" class="mw-qs-add" data-ctl="menuitems-add">+ ' + esc(lang(c.addLabel || 'Add menu item')) + '</button></div>'
                + '<div class="mw-qs-items mw-qs-menuitems" data-ctl="menuitems"><div class="mw-qs-items__empty">' + esc(lang('Loading…')) + '</div></div></div>';
        }
        if (c.type === 'advanced') {
            return '<button type="button" class="mw-qs-advanced" data-ctl="open-settings"><span class="mw-qs-advanced__t">' + esc(lang(c.label || 'Advanced'))
                + (c.hint ? '<small>' + esc(lang(c.hint)) + '</small>' : '') + '</span>'
                + '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></button>';
        }
        return '';
    }

    // ── cross-panel coordination ────────────────────────────────────────────
    // Opening any quick panel must close every other one — including panels from
    // OTHER implementations (e.g. the Btn module's own .mw-btn-panel). Decoupled
    // via a top-window event: on open a panel announces itself; every panel but
    // the announcer closes. Panels from other bundles just listen for the same
    // event + emit it on their own open.
    // The real top WINDOW (mw.top() returns the top mw-namespace object, which
    // has no addEventListener) — use the top document's defaultView.
    function qsWin() { try { return topDoc().defaultView || window; } catch (e) { return window; } }
    function announceOpen(source) {
        try { qsWin().dispatchEvent(new CustomEvent('mwQuickSettingsWillOpen', { detail: { source: source } })); } catch (e) {}
    }

    // Can this module be duplicated? Reuse the element handle's own eligibility
    // rule (shouldShowCloneButtonInMoreButton) — true only when the module (or an
    // ancestor) is `cloneable`, i.e. it sits inside an editable field. Modules
    // dropped straight into a layout (not in an edit field) aren't cloneable, so
    // the panel's Duplicate button must hide for them.
    function canClone(el) {
        try {
            var v = mw.top().app.liveEdit.elementHandleContent.elementHandleButtonsVisibility;
            if (v && typeof v.shouldShowCloneButtonInMoreButton === 'function') {
                return !!v.shouldShowCloneButtonInMoreButton(el);
            }
        } catch (e) {}
        // Fallback: `cloneable` on the element or any ancestor.
        try {
            var n = el;
            while (n && n.classList) {
                if (n.classList.contains('cloneable')) { return true; }
                n = n.parentElement;
            }
        } catch (e) {}
        return false;
    }

    // ── panel lifecycle ─────────────────────────────────────────────────────
    var _el = null, _docClick = null, _docKey = null, _closeOnOutside = true;
    function close() {
        if (_el) { _el.style.display = 'none'; }
        // Remove any open add-item picker popup (lives outside _el).
        try { var p = topDoc().querySelector('.mw-qs-add-pop'); if (p) { p.remove(); } } catch (e) {}
    }

    function sectionsHtml(sections, opts) {
        return (sections || []).map(function (c) { return renderControl(c, opts); }).join('');
    }

    // Make the panel draggable by its header (grabbing the badge/title area, not
    // the duplicate/settings/close buttons). Re-bound on every open since the
    // header markup is rebuilt each time.
    function makeDraggable() {
        var head = _el && _el.querySelector('.mw-qs-panel__head');
        if (!head) { return; }
        head.style.cursor = 'move';
        head.addEventListener('mousedown', function (e) {
            if (e.button !== 0) { return; }
            // Don't start a drag from the header action buttons.
            if (e.target.closest && e.target.closest('.mw-qs-panel__ico')) { return; }
            var doc = topDoc(), win = doc.defaultView || window;
            var r = _el.getBoundingClientRect();
            var startX = e.clientX, startY = e.clientY, startLeft = r.left, startTop = r.top;
            e.preventDefault();
            var prevUserSelect = doc.body.style.userSelect;
            doc.body.style.userSelect = 'none';
            var move = function (ev) {
                var m = 4, pw = _el.offsetWidth, ph = _el.offsetHeight;
                var nl = startLeft + (ev.clientX - startX);
                var nt = startTop + (ev.clientY - startY);
                nl = Math.min(Math.max(m, nl), Math.max(m, win.innerWidth - pw - m));
                nt = Math.min(Math.max(m, nt), Math.max(m, win.innerHeight - ph - m));
                _el.style.left = Math.round(nl) + 'px';
                _el.style.top = Math.round(nt) + 'px';
            };
            var up = function () {
                doc.removeEventListener('mousemove', move, true);
                doc.removeEventListener('mouseup', up, true);
                doc.body.style.userSelect = prevUserSelect;
            };
            doc.addEventListener('mousemove', move, true);
            doc.addEventListener('mouseup', up, true);
        });
    }

    // Fetch a module's saved options as a key=>value map — the fallback for
    // layout-embedded modules (logo/menu/spacer) that emit NO inline settings
    // <script>, so readOptions() can't see their values and every field would
    // otherwise open blank even after a save.
    function fetchModuleOptions(el) {
        try {
            return qsHttp('GET', 'api/live-edit/module-options?id=' + encodeURIComponent(el.getAttribute('id') || ''))
                .then(function (r) { return (r && r.options) || {}; })
                .catch(function () { return {}; });
        } catch (e) { return Promise.resolve({}); }
    }

    function open(el, config) {
        injectCss();
        // Close any other quick panel (incl. other implementations) before this
        // one shows. The kit reuses a single _el, so it never needs to close a
        // prior KIT panel — but this closes e.g. the Btn module's own panel.
        announceOpen('kit');
        var opts = readOptions(el);
        // Content modules ship the inline settings <script> (readOptions has
        // data → render immediately). Layout modules don't, so fetch their saved
        // options first, then render — a small delay beats blank fields.
        if (opts && Object.keys(opts).length) {
            renderPanel(el, config, opts);
        } else {
            fetchModuleOptions(el).then(function (srv) { renderPanel(el, config, srv || {}); });
        }
    }

    function renderPanel(el, config, opts) {
        var doc = topDoc();

        var dupIco = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h9a2 2 0 0 1 2 2v9h-2V5H9V3zM5 7h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm0 2v10h9V9H5z"/></svg>';
        var setIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';

        // Prefer the real module icon (from the modules service Live Edit
        // already loaded); fall back to the 2-letter badge only if unavailable.
        var iconHtml = moduleIcon(el.getAttribute('data-type') || el.getAttribute('type') || config.type);
        var badge = iconHtml
            ? '<span class="mw-qs-panel__badge mw-qs-panel__badge--icon">' + iconHtml + '</span>'
            : (config.badge ? '<span class="mw-qs-panel__badge">' + esc(config.badge) + '</span>' : '');

        // Only offer Duplicate when the module actually can be cloned (it lives
        // inside an editable field) — mirrors the element handle's own rule.
        var dupBtn = canClone(el)
            ? '<button type="button" class="mw-qs-panel__ico" data-ctl="duplicate" title="' + esc(lang('Duplicate')) + '">' + dupIco + '</button>'
            : '';

        var head = '<div class="mw-qs-panel__head">'
            + badge
            + '<span class="mw-qs-panel__title">' + esc(lang(config.title || 'Settings')) + '</span>'
            + '<div class="mw-qs-panel__head-actions">'
            + dupBtn
            + '<button type="button" class="mw-qs-panel__ico" data-ctl="open-settings" title="' + esc(lang('Settings')) + '">' + setIco + '</button>'
            + '<button type="button" class="mw-qs-panel__ico" data-ctl="close" title="' + esc(lang('Close')) + '">✕</button>'
            + '</div></div>';

        var body;
        if (config.tabs && config.tabs.length) {
            var tid = 'mwqs-' + (el.getAttribute('id') || 'x');
            // Which tab opens active: the first whose activeWhen(opts) is true
            // (e.g. Logo's Image tab when a logoimage is set), else the first.
            var activeIdx = 0;
            for (var ti = 0; ti < config.tabs.length; ti++) {
                var aw = config.tabs[ti].activeWhen;
                if (typeof aw === 'function' && aw(opts)) { activeIdx = ti; break; }
            }
            var tabsNav = '<div class="mw-qs-tabs" role="tablist">' + config.tabs.map(function (t, i) {
                var on = (i === activeIdx);
                return '<button type="button" class="mw-qs-tab' + (on ? ' active' : '') + '" data-tab="' + i + '"'
                    + ' role="tab" id="' + tid + '-t' + i + '" aria-controls="' + tid + '-p' + i + '" aria-selected="' + (on ? 'true' : 'false') + '">'
                    + esc(lang(t.name)) + '</button>';
            }).join('') + '</div>';
            var panes = config.tabs.map(function (t, i) {
                return '<div class="mw-qs-pane" data-pane="' + i + '" role="tabpanel" id="' + tid + '-p' + i + '" aria-labelledby="' + tid + '-t' + i + '"'
                    + (i === activeIdx ? '' : ' style="display:none"') + '>' + sectionsHtml(t.sections, opts) + '</div>';
            }).join('');
            body = tabsNav + panes;
            // Shared sections (e.g. Advanced) render below the tabs, always visible.
            if (config.sections && config.sections.length) { body += sectionsHtml(config.sections, opts); }
        } else {
            body = sectionsHtml(config.sections, opts);
        }

        if (!_el) { _el = doc.createElement('div'); _el.className = 'mw-qs-panel'; doc.body.appendChild(_el); }
        _el.innerHTML = head + body;
        _el.style.display = 'block';

        // Position near the module's TOP-LEFT — i.e. next to the handle toolbar,
        // which always sits at the module's top edge. Anchoring to the top (not
        // below the whole module) keeps the panel by the toolbar for a tall
        // module like Video instead of dropping it near the module's bottom.
        // Then HARD-CLAMP fully into the viewport so it can never open off-screen
        // (a scrolled canvas / the canvas-frame offset would otherwise push it
        // out). The panel CSS caps max-height + scrolls internally, so a panel
        // taller than the viewport pins to the top margin.
        var r = el.getBoundingClientRect(), off = frameOffset(), win = doc.defaultView || window;
        var margin = 8;
        var winW = win.innerWidth, winH = win.innerHeight;
        var pw = _el.offsetWidth || 300, ph = _el.offsetHeight || 380;
        var anchorLeft = r.left + off.x;
        var top = r.top + off.y;
        var left = Math.min(Math.max(anchorLeft, margin), Math.max(margin, winW - pw - margin));
        top = Math.min(Math.max(top, margin), Math.max(margin, winH - ph - margin));
        _el.style.left = Math.round(left) + 'px';
        _el.style.top = Math.round(top) + 'px';

        wire(el, config);
        makeDraggable();

        // Close-on-outside-click, gated by a per-module flag so a module can
        // opt out (config.closeOnOutsideClick === false). The listener is bound
        // once on BOTH the top window and the canvas iframe (clicks on the page
        // content live in a different document and otherwise never reach it).
        _closeOnOutside = (config.closeOnOutsideClick !== false);
        if (!_docClick) {
            _docClick = function (ev) {
                if (!_el || _el.style.display !== 'block') { return; }
                if (!_closeOnOutside) { return; }
                var t = ev.target;
                if (_el.contains(t)) { return; }
                // Interacting with a picker / dialog / dropdown the panel spawned
                // (colour picker, icon picker, media picker, module settings) must
                // not close it.
                if (t && t.closest && t.closest('.mw-qs-panel, .mw-color-picker, .mw-dropdown, .modal, .fi-modal, .mw-dialog, .mw-filepicker')) { return; }
                close();
            };
            // Escape closes the panel (keyboard dismissal), bound on both docs.
            _docKey = function (ev) {
                if (!_el || _el.style.display !== 'block') { return; }
                if (ev.key === 'Escape' || ev.key === 'Esc') { ev.stopPropagation(); close(); }
            };
            setTimeout(function () {
                doc.addEventListener('click', _docClick, true);
                doc.addEventListener('keydown', _docKey, true);
                var cd = canvasDoc();
                if (cd && cd !== doc) {
                    try { cd.addEventListener('click', _docClick, true); } catch (e) {}
                    try { cd.addEventListener('keydown', _docKey, true); } catch (e) {}
                }
            }, 0);
        }
    }

    function wire(el, config) {
        // tabs
        _el.querySelectorAll('.mw-qs-tab').forEach(function (t) {
            t.addEventListener('click', function () {
                var i = t.dataset.tab;
                _el.querySelectorAll('.mw-qs-tab').forEach(function (x) {
                    var on = x === t;
                    x.classList.toggle('active', on);
                    x.setAttribute('aria-selected', on ? 'true' : 'false');
                });
                _el.querySelectorAll('.mw-qs-pane').forEach(function (p) { p.style.display = (p.dataset.pane === i) ? '' : 'none'; });
            });
        });

        var setActive = function (key, val) {
            _el.querySelectorAll('[data-ctl="segmented"][data-key="' + key + '"]').forEach(function (b) {
                var on = eq(b.dataset.val, val);
                b.classList.toggle('active', on);
                b.setAttribute('aria-checked', on ? 'true' : 'false');
            });
        };

        _el.querySelectorAll('[data-ctl="segmented"]').forEach(function (b) {
            b.addEventListener('click', function () { saveOption(el, b.dataset.key, b.dataset.val); setActive(b.dataset.key, b.dataset.val); });
        });
        _el.querySelectorAll('[data-ctl="select"]').forEach(function (s) {
            s.addEventListener('change', function () { saveOption(el, s.dataset.key, s.value); });
        });
        _el.querySelectorAll('[data-ctl="text"]').forEach(function (inp) {
            var save = function () {
                saveOption(el, inp.dataset.key, inp.value);
                if (inp.dataset.enableKey) {
                    saveOption(el, inp.dataset.enableKey, inp.value.trim() ? '1' : '0');
                }
            };
            inp.addEventListener('change', save);
            inp.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); save(); } });
        });
        _el.querySelectorAll('[data-ctl="toggle"]').forEach(function (tg) {
            var flip = function () {
                var on = !tg.classList.contains('on');
                tg.classList.toggle('on', on);
                tg.setAttribute('aria-checked', on ? 'true' : 'false');
                saveOption(el, tg.dataset.key, on ? tg.dataset.on : tg.dataset.off);
            };
            tg.addEventListener('click', flip);
            // Clicking the label row also flips the switch (larger target).
            var row = tg.closest('.mw-qs-row');
            var txt = row && row.querySelector('.mw-qs-row__txt');
            if (txt) { txt.style.cursor = 'pointer'; txt.addEventListener('click', flip); }
        });
        _el.querySelectorAll('[data-ctl="swatch"]').forEach(function (sw) {
            sw.addEventListener('click', function () {
                var scope = sw.closest('.mw-qs-swatches') || _el;
                scope.querySelectorAll('.mw-qs-sw').forEach(function (x) { x.classList.remove('active'); });
                sw.classList.add('active');
                // Reset the custom chip back to the "+" affordance.
                var cust = scope.querySelector('.mw-qs-sw--custom');
                if (cust) { cust.style.background = ''; cust.textContent = '+'; }
                saveOption(el, sw.dataset.key, sw.dataset.col);
            });
        });
        _el.querySelectorAll('[data-ctl="swatch-custom"]').forEach(function (b) {
            b.addEventListener('click', function () {
                var picker = (mw.top().app && mw.top().app.colorPicker) || (mw.app && mw.app.colorPicker) || null;
                var current = readOptions(el)[b.dataset.key] || '#182433';
                if (picker && picker.openColorPicker) {
                    picker.openColorPicker(current, function (color) {
                        if (!color) { return; }
                        var scope = b.closest('.mw-qs-swatches') || _el;
                        scope.querySelectorAll('.mw-qs-sw').forEach(function (x) { x.classList.remove('active'); });
                        // Show the chosen colour ON the custom chip so it's visible.
                        b.classList.add('active');
                        b.style.background = color;
                        b.textContent = '';
                        saveOption(el, b.dataset.key, color);
                    }, b);
                }
            });
        });
        // ── single image picker (opens shared media picker, writes opts[key]) ──
        _el.querySelectorAll('[data-ctl="imagepick"]').forEach(function (box) {
            var key = box.dataset.key;
            var thumb = box.querySelector('.mw-qs-imgpick__thumb');
            var pickBtn = box.querySelector('[data-act="pick"]');
            var rmBtn = box.querySelector('[data-act="remove"]');
            var apply = function (url) {
                if (url) { box.classList.add('has-img'); if (thumb) { thumb.style.backgroundImage = 'url(' + url + ')'; } }
                else { box.classList.remove('has-img'); if (thumb) { thumb.style.backgroundImage = ''; } }
                saveOption(el, key, url || '');
            };
            if (pickBtn) {
                pickBtn.addEventListener('click', function () {
                    try {
                        mw.filePickerDialog({ pickerOptions: { type: 'images' } }, function (url) {
                            var u = Array.isArray(url) ? url[0] : url;
                            if (u) { apply(String(u)); }
                        });
                    } catch (e) {}
                });
            }
            if (rmBtn) { rmBtn.addEventListener('click', function () { apply(''); }); }
        });
        _el.querySelectorAll('[data-ctl="duplicate"]').forEach(function (b) {
            b.addEventListener('click', function () {
                try {
                    // Duplicate THIS module (cloneElement copies the element itself;
                    // cloneElementFirstClonableParent would copy the whole column).
                    mw.top().app.liveEdit.elementHandleContent.elementActions.cloneElement(el);
                    // cloneElement only registers sync/undo — NOT the changed state,
                    // so the toolbar Save never activated and the leave-guard never
                    // prompted. registerChangedState resolves the edit field, marks
                    // it changed and sets askUserToStay.
                    mw.top().app.registerChangedState(el, true);
                } catch (e) {}
                close();
            });
        });
        _el.querySelectorAll('[data-ctl="open-settings"]').forEach(function (b) {
            b.addEventListener('click', function () {
                try { mw.top().app.editor.dispatch('onModuleSettingsRequest', el); } catch (e) {}
                close();
            });
        });
        _el.querySelectorAll('[data-ctl="close"]').forEach(function (b) {
            b.addEventListener('click', function () { close(); });
        });

        // ── inline item list (DB-backed via the module's CRUD API) ──────────
        _el.querySelectorAll('[data-ctl="itemlist"]').forEach(function (container) {
            var cfg = {};
            try { cfg = JSON.parse(container.dataset.cfg || '{}'); } catch (e) {}
            var endpoint = cfg.endpoint;
            var fields = (cfg.fields && cfg.fields.length) ? cfg.fields : [{ key: 'title' }];
            var relId = el.getAttribute('id');
            var items = [];
            var reload = function () { try { mw.app.editor.dispatch('onModuleSettingsChanged', { moduleId: relId }); } catch (e) {} };
            var titleText = function (it) {
                return String(it[fields[0].key] || '').replace(/<[^>]*>/g, '').trim().slice(0, 60) || lang('Untitled');
            };

            var render = function () {
                if (!items.length) {
                    container.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('No items yet')) + '</div>';
                    return;
                }
                container.innerHTML = items.map(function (it, i) {
                    var body = fields.map(function (f) {
                        var v = esc(it[f.key]);
                        var ph = esc(lang(f.placeholder || f.label || f.key));
                        var ctl;
                        if (f.multiline || f.type === 'textarea') {
                            ctl = '<textarea class="mw-qs-input" data-field="' + esc(f.key) + '" placeholder="' + ph + '">' + v + '</textarea>';
                        } else if (f.type === 'select') {
                            var opts = (f.options || []).map(function (o) {
                                return '<option value="' + esc(o.value) + '"' + (String(o.value) === String(it[f.key]) ? ' selected' : '') + '>' + esc(lang(o.label)) + '</option>';
                            }).join('');
                            ctl = '<select class="mw-qs-input" data-field="' + esc(f.key) + '">' + opts + '</select>';
                        } else {
                            ctl = '<input type="' + (f.type === 'number' ? 'number' : 'text') + '" class="mw-qs-input" data-field="' + esc(f.key) + '" placeholder="' + ph + '" value="' + v + '">';
                        }
                        var flabel = f.label ? '<div class="mw-qs-item__flabel">' + esc(lang(f.label)) + '</div>' : '';
                        return '<div class="mw-qs-item__field">' + flabel + ctl + '</div>';
                    }).join('');
                    return '<div class="mw-qs-item" data-id="' + esc(it.id) + '">'
                        + '<div class="mw-qs-item__head">'
                        + '<button type="button" class="mw-qs-item__toggle"><span class="mw-qs-item__caret">▸</span><span>' + esc(titleText(it)) + '</span></button>'
                        + '<div class="mw-qs-item__actions">'
                        + '<button type="button" class="mw-qs-item__act" data-act="up" title="' + esc(lang('Move up')) + '"' + (i === 0 ? ' disabled' : '') + '>↑</button>'
                        + '<button type="button" class="mw-qs-item__act" data-act="down" title="' + esc(lang('Move down')) + '"' + (i === items.length - 1 ? ' disabled' : '') + '>↓</button>'
                        + '<button type="button" class="mw-qs-item__act is-danger" data-act="del" title="' + esc(lang('Remove')) + '">✕</button>'
                        + '</div></div>'
                        + '<div class="mw-qs-item__body" style="display:none">' + body + '</div>'
                        + '</div>';
                }).join('');
                bind();
            };

            var move = function (id, dir) {
                var ids = items.map(function (x) { return String(x.id); });
                var idx = ids.indexOf(String(id));
                var to = idx + dir;
                if (idx < 0 || to < 0 || to >= ids.length) { return; }
                var moved = items.splice(idx, 1)[0];
                items.splice(to, 0, moved);
                render();
                qsHttp('POST', 'api/' + endpoint + '/reorder', { rel_id: relId, ids: items.map(function (x) { return x.id; }) }).then(reload);
            };

            var bind = function () {
                container.querySelectorAll('.mw-qs-item').forEach(function (row) {
                    var id = row.dataset.id;
                    row.querySelector('.mw-qs-item__toggle').addEventListener('click', function () {
                        var open = !row.classList.contains('open');
                        container.querySelectorAll('.mw-qs-item').forEach(function (r) {
                            r.classList.remove('open'); r.querySelector('.mw-qs-item__body').style.display = 'none';
                        });
                        if (open) { row.classList.add('open'); row.querySelector('.mw-qs-item__body').style.display = ''; }
                    });
                    row.querySelectorAll('[data-field]').forEach(function (inp) {
                        inp.addEventListener('change', function () {
                            // rel_id is redundant for DB-keyed controllers (global
                            // id) but REQUIRED for JSON-option ones where the id is
                            // an array index scoped to the module.
                            var payload = { rel_id: relId }; payload[inp.dataset.field] = inp.value;
                            qsHttp('POST', 'api/' + endpoint + '/' + id, payload).then(function () {
                                var found = items.filter(function (x) { return String(x.id) === String(id); })[0];
                                if (found) { found[inp.dataset.field] = inp.value; }
                                if (inp.dataset.field === fields[0].key) {
                                    var lbl = row.querySelector('.mw-qs-item__toggle span:last-child');
                                    if (lbl) { lbl.textContent = titleText(found || {}); }
                                }
                                reload();
                            });
                        });
                    });
                    row.querySelector('[data-act="del"]').addEventListener('click', function () {
                        qsHttp('DELETE', 'api/' + endpoint + '/' + id + '?rel_id=' + encodeURIComponent(relId)).then(function () { load(); reload(); });
                    });
                    row.querySelector('[data-act="up"]').addEventListener('click', function () { move(id, -1); });
                    row.querySelector('[data-act="down"]').addEventListener('click', function () { move(id, 1); });
                });
            };

            var load = function () {
                qsHttp('GET', 'api/' + endpoint + '?rel_id=' + encodeURIComponent(relId)).then(function (res) {
                    items = (res && res.items) || [];
                    render();
                }).catch(function () {
                    container.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('Could not load items')) + '</div>';
                });
            };

            var section = container.closest('.mw-qs-section') || container.parentElement;
            var addBtn = section ? section.querySelector('[data-ctl="itemlist-add"]') : null;
            if (addBtn) {
                addBtn.addEventListener('click', function () {
                    var payload = { rel_id: relId };
                    qsHttp('POST', 'api/' + endpoint, payload).then(function () { load(); reload(); });
                });
            }
            var aiBtn = section ? section.querySelector('[data-ctl="itemlist-ai"]') : null;
            if (aiBtn) {
                // Full "Create with AI" lives in the module settings editor.
                aiBtn.addEventListener('click', function () {
                    try { mw.top().app.editor.dispatch('onModuleSettingsRequest', el); } catch (e) {}
                    close();
                });
            }

            load();
        });

        // ── inline image gallery (Media-backed via the module's CRUD API) ────
        _el.querySelectorAll('[data-ctl="imagelist"]').forEach(function (container) {
            var cfg = {};
            try { cfg = JSON.parse(container.dataset.cfg || '{}'); } catch (e) {}
            var endpoint = cfg.endpoint;
            var moduleId = el.getAttribute('id');
            // task-2026-09-23 — a Pictures module with rel="content" renders the
            // CURRENT CONTENT's media (the product/page gallery), not its own
            // module media. Point the CRUD at that store (rel_type=content,
            // rel_id=content id) so edits actually show; otherwise they save to
            // rel_type='module'/<module id> and the gallery never updates.
            var relId = moduleId;
            var relType = 'module';
            var moduleRel = (el.getAttribute('rel') || el.getAttribute('data-rel') || '').toLowerCase();
            if (moduleRel === 'content') {
                relType = 'content';
                var cid = el.getAttribute('rel-id') || el.getAttribute('data-rel-id') || '';
                if (!cid) { try { cid = mw.top().app.canvas.getLiveEditData().content.id; } catch (e) {} }
                if (cid) { relId = String(cid); }
            }
            var relQuery = 'rel_id=' + encodeURIComponent(relId) + '&rel_type=' + encodeURIComponent(relType);
            var images = [];
            // dispatch on the MODULE id so the module re-renders in place.
            var reload = function () { try { mw.app.editor.dispatch('onModuleSettingsChanged', { moduleId: moduleId }); } catch (e) {} };

            var render = function () {
                if (!images.length) {
                    container.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('No images yet')) + '</div>';
                    return;
                }
                container.innerHTML = images.map(function (it, i) {
                    return '<div class="mw-qs-image" data-id="' + esc(it.id) + '"' + (images.length > 1 ? ' draggable="true"' : '') + '>'
                        + '<img src="' + esc(it.url) + '" alt="" loading="lazy" draggable="false">'
                        + '<button type="button" class="mw-qs-image__del" data-act="del" title="' + esc(lang('Remove')) + '">✕</button>'
                        + (images.length > 1 ? '<div class="mw-qs-image__nav">'
                            + '<button type="button" data-act="left" title="' + esc(lang('Move left')) + '"' + (i === 0 ? ' disabled' : '') + '>‹</button>'
                            + '<button type="button" data-act="right" title="' + esc(lang('Move right')) + '"' + (i === images.length - 1 ? ' disabled' : '') + '>›</button></div>' : '')
                        + '</div>';
                }).join('');
                bind();
            };

            var persistOrder = function () {
                qsHttp('POST', 'api/' + endpoint + '/reorder', { rel_id: relId, rel_type: relType, ids: images.map(function (x) { return x.id; }) }).then(reload);
            };
            var move = function (id, dir) {
                var ids = images.map(function (x) { return String(x.id); });
                var idx = ids.indexOf(String(id));
                var to = idx + dir;
                if (idx < 0 || to < 0 || to >= ids.length) { return; }
                var moved = images.splice(idx, 1)[0];
                images.splice(to, 0, moved);
                render();
                persistOrder();
            };
            var dropMove = function (dragId, targetId, before) {
                if (dragId == null || String(dragId) === String(targetId)) { return; }
                var moved = images.filter(function (x) { return String(x.id) === String(dragId); })[0];
                if (!moved) { return; }
                images = images.filter(function (x) { return String(x.id) !== String(dragId); });
                var tIdx = images.map(function (x) { return String(x.id); }).indexOf(String(targetId));
                if (tIdx < 0) { tIdx = images.length - 1; }
                images.splice(before ? tIdx : tIdx + 1, 0, moved);
                render();
                persistOrder();
            };
            var _dragId = null;

            var bind = function () {
                container.querySelectorAll('.mw-qs-image').forEach(function (row) {
                    var id = row.dataset.id;
                    row.querySelector('[data-act="del"]').addEventListener('click', function () {
                        qsHttp('DELETE', 'api/' + endpoint + '/' + id + '?' + relQuery).then(function () { load(); reload(); });
                    });
                    var l = row.querySelector('[data-act="left"]'), rt = row.querySelector('[data-act="right"]');
                    if (l) { l.addEventListener('click', function () { move(id, -1); }); }
                    if (rt) { rt.addEventListener('click', function () { move(id, 1); }); }
                    // Drag-to-reorder (the arrows stay as a fallback).
                    row.addEventListener('dragstart', function (e) {
                        _dragId = id; row.classList.add('mw-qs-image--dragging');
                        try { e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', id); } catch (_) {}
                    });
                    row.addEventListener('dragend', function () {
                        _dragId = null;
                        container.querySelectorAll('.mw-qs-image').forEach(function (r) { r.classList.remove('mw-qs-image--dragging', 'mw-qs-image--over'); });
                    });
                    row.addEventListener('dragover', function (e) {
                        if (_dragId == null) { return; }
                        e.preventDefault();
                        try { e.dataTransfer.dropEffect = 'move'; } catch (_) {}
                        if (String(_dragId) !== String(id)) { row.classList.add('mw-qs-image--over'); }
                    });
                    row.addEventListener('dragleave', function () { row.classList.remove('mw-qs-image--over'); });
                    row.addEventListener('drop', function (e) {
                        if (_dragId == null) { return; }
                        e.preventDefault();
                        var rect = row.getBoundingClientRect();
                        var before = (e.clientX - rect.left) < rect.width / 2;
                        var dId = _dragId; _dragId = null;
                        container.querySelectorAll('.mw-qs-image').forEach(function (r) { r.classList.remove('mw-qs-image--dragging', 'mw-qs-image--over'); });
                        dropMove(dId, id, before);
                    });
                });
            };

            var load = function () {
                qsHttp('GET', 'api/' + endpoint + '?' + relQuery).then(function (res) {
                    images = (res && res.items) || [];
                    render();
                }).catch(function () {
                    container.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('Could not load images')) + '</div>';
                });
            };

            var section = container.closest('.mw-qs-section');
            var addBtn = section ? section.querySelector('[data-ctl="imagelist-add"]') : null;
            if (addBtn) {
                addBtn.addEventListener('click', function () {
                    // Shared media picker → create a Media row with the chosen file.
                    try {
                        mw.filePickerDialog({ pickerOptions: { type: 'images' } }, function (url) {
                            if (!url) { return; }
                            qsHttp('POST', 'api/' + endpoint, { rel_id: relId, rel_type: relType, filename: String(url) }).then(function () { load(); reload(); });
                        });
                    } catch (e) {}
                });
            }

            load();
        });

        // ── menu editor (menu selector + its links) ─────────────────────────
        // Menu items belong to a MENU container (parent_id), not the module
        // rel_id, and use the dedicated api/menu/* endpoints — so this is a
        // bespoke pair (menuselect + menuitems) coordinated by the resolved
        // current menu id.
        (function wireMenu() {
            var selectEl = _el.querySelector('[data-ctl="menuselect"]');
            var itemsBox = _el.querySelector('[data-ctl="menuitems"]');
            var addBtn = _el.querySelector('[data-ctl="menuitems-add"]');
            if (!selectEl && !itemsBox) { return; }
            var opts = readOptions(el);
            var currentName = opts.menu_name || el.getAttribute('data-name') || el.getAttribute('data-menu_name') || 'header_menu';
            var menus = [], currentMenuId = null, _items = [], _dragId = null, _addPop = null, _pickables = [];
            var reloadCanvas = function () { try { mw.app.editor.dispatch('onModuleSettingsChanged', { moduleId: el.getAttribute('id') }); } catch (e) {} };

            var renderItems = function () {
                if (!itemsBox) { return; }
                if (!_items.length) { itemsBox.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('No items yet')) + '</div>'; return; }
                itemsBox.innerHTML = _items.map(function (it) {
                    var urlField = (it.type === 'Link') ? '<div class="mw-qs-item__field"><div class="mw-qs-item__flabel">' + esc(lang('URL')) + '</div>'
                        + '<input type="text" class="mw-qs-input" data-field="url" value="' + esc(it.url) + '"></div>' : '';
                    var body = '<div class="mw-qs-item__field"><div class="mw-qs-item__flabel">' + esc(lang('Label')) + '</div>'
                        + '<input type="text" class="mw-qs-input" data-field="title" value="' + esc(it.label) + '"></div>' + urlField;
                    var indent = it.depth ? ' style="margin-left:' + (it.depth * 14) + 'px"' : '';
                    return '<div class="mw-qs-item' + (it.depth ? ' mw-qs-item--nested' : '') + '" data-id="' + esc(it.id) + '"' + indent + '>'
                        + '<div class="mw-qs-item__head">'
                        + '<span class="mw-qs-item__drag" draggable="true" title="' + esc(lang('Drag to reorder')) + '" aria-label="' + esc(lang('Drag to reorder')) + '">' + dragDots + '</span>'
                        + '<button type="button" class="mw-qs-item__toggle"><span class="mw-qs-item__caret">▸</span><span>' + esc(it.label) + '</span></button>'
                        + '<div class="mw-qs-item__actions">'
                        + '<span class="mw-qs-mi-badge">' + esc(lang(it.type)) + '</span>'
                        + '<button type="button" class="mw-qs-item__act is-danger" data-act="del" title="' + esc(lang('Remove')) + '">✕</button>'
                        + '</div></div>'
                        + '<div class="mw-qs-item__body" style="display:none">' + body + '</div></div>';
                }).join('');
                bindItems();
            };

            var clearDropHints = function () {
                itemsBox.querySelectorAll('.mw-qs-item').forEach(function (r) { r.classList.remove('mw-qs-item--dragging', 'mw-qs-item--over-top', 'mw-qs-item--over-bot'); });
            };
            var dropReorder = function (dragId, targetId, before) {
                if (dragId == null || String(dragId) === String(targetId)) { return; }
                var moved = _items.filter(function (x) { return String(x.id) === String(dragId); })[0];
                if (!moved) { return; }
                _items = _items.filter(function (x) { return String(x.id) !== String(dragId); });
                var tIdx = _items.map(function (x) { return String(x.id); }).indexOf(String(targetId));
                if (tIdx < 0) { tIdx = _items.length - 1; }
                _items.splice(before ? tIdx : tIdx + 1, 0, moved);
                renderItems();
                qsHttp('POST', 'api/menu/item/reorder', { ids: _items.map(function (x) { return x.id; }) }).then(reloadCanvas);
            };

            var bindItems = function () {
                itemsBox.querySelectorAll('.mw-qs-item').forEach(function (row) {
                    var id = row.dataset.id;
                    row.querySelector('.mw-qs-item__toggle').addEventListener('click', function () {
                        var open = !row.classList.contains('open');
                        itemsBox.querySelectorAll('.mw-qs-item').forEach(function (r) { r.classList.remove('open'); r.querySelector('.mw-qs-item__body').style.display = 'none'; });
                        if (open) { row.classList.add('open'); row.querySelector('.mw-qs-item__body').style.display = ''; }
                    });
                    // Drag-to-reorder via the left handle.
                    var handle = row.querySelector('.mw-qs-item__drag');
                    handle.addEventListener('dragstart', function (e) {
                        _dragId = id; row.classList.add('mw-qs-item--dragging');
                        try { e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', id); } catch (_) {}
                    });
                    handle.addEventListener('dragend', function () { _dragId = null; clearDropHints(); });
                    row.addEventListener('dragover', function (e) {
                        if (_dragId == null) { return; }
                        e.preventDefault();
                        try { e.dataTransfer.dropEffect = 'move'; } catch (_) {}
                        var rect = row.getBoundingClientRect(); var before = (e.clientY - rect.top) < rect.height / 2;
                        row.classList.toggle('mw-qs-item--over-top', before);
                        row.classList.toggle('mw-qs-item--over-bot', !before);
                    });
                    row.addEventListener('dragleave', function () { row.classList.remove('mw-qs-item--over-top', 'mw-qs-item--over-bot'); });
                    row.addEventListener('drop', function (e) {
                        if (_dragId == null) { return; }
                        e.preventDefault();
                        var rect = row.getBoundingClientRect(); var before = (e.clientY - rect.top) < rect.height / 2;
                        var dId = _dragId; _dragId = null; clearDropHints();
                        dropReorder(dId, id, before);
                    });
                    row.querySelectorAll('[data-field]').forEach(function (inp) {
                        inp.addEventListener('change', function () {
                            var payload = { menu_id: currentMenuId, id: id }; payload[inp.dataset.field] = inp.value;
                            qsHttp('POST', 'api/menu/item/save', payload).then(function () {
                                if (inp.dataset.field === 'title') {
                                    var lbl = row.querySelector('.mw-qs-item__toggle span:last-child');
                                    if (lbl) { lbl.textContent = inp.value; }
                                    var found = _items.filter(function (x) { return String(x.id) === String(id); })[0];
                                    if (found) { found.label = inp.value; }
                                }
                                reloadCanvas();
                            });
                        });
                    });
                    row.querySelector('[data-act="del"]').addEventListener('click', function () {
                        qsHttp('POST', 'api/menu/item/delete/' + id).then(function () { loadItems(); reloadCanvas(); });
                    });
                });
            };

            var loadItems = function () {
                if (!itemsBox) { return; }
                if (!currentMenuId) { itemsBox.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('No menu selected')) + '</div>'; return; }
                qsHttp('GET', 'api/menu/items?menu_id=' + currentMenuId).then(function (res) { _items = (res && res.items) || []; renderItems(); })
                    .catch(function () { itemsBox.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('Could not load items')) + '</div>'; });
            };

            // ── "Add menu item" popup: pick a page/category from the site tree,
            //    or add a custom link. Adds via api/menu/item/save then reloads.
            var closeAddPop = function () {
                // Remove by selector (not just the _addPop ref) so a leftover popup
                // from an earlier open can never linger as a duplicate.
                try { topDoc().querySelectorAll('.mw-qs-add-pop').forEach(function (x) { x.remove(); }); } catch (e) {}
                _addPop = null;
                _closeOnOutside = (config.closeOnOutsideClick !== false);
            };
            var openAddPop = function () {
                if (!currentMenuId) { return; }
                closeAddPop();
                _closeOnOutside = false; // keep the panel open while the popup is up
                var doc = topDoc();
                var pop = doc.createElement('div');
                pop.className = 'mw-qs-add-pop';
                pop.innerHTML = '<div class="mw-qs-add-pop__head"><span>' + esc(lang('Add menu item')) + '</span>'
                    + '<button type="button" class="mw-qs-panel__ico" data-x title="' + esc(lang('Close')) + '">✕</button></div>'
                    + '<button type="button" class="mw-qs-add mw-qs-add-pop__link" data-link>+ ' + esc(lang('Custom link')) + '</button>'
                    + '<input type="text" class="mw-qs-input mw-qs-add-pop__search" data-search placeholder="' + esc(lang('Search pages, posts, products…')) + '">'
                    + '<div class="mw-qs-add-pop__list" data-list><div class="mw-qs-items__empty">' + esc(lang('Loading…')) + '</div></div>';
                doc.body.appendChild(pop);
                _addPop = pop;
                var win = doc.defaultView || window, pr = _el.getBoundingClientRect();
                var pw = 300, ph = Math.min(430, win.innerHeight - 24);
                pop.style.width = pw + 'px';
                pop.style.left = Math.round(Math.min(Math.max(8, pr.left), win.innerWidth - pw - 8)) + 'px';
                pop.style.top = Math.round(Math.min(Math.max(8, pr.top), win.innerHeight - ph - 8)) + 'px';
                var listEl = pop.querySelector('[data-list]');
                var searchEl = pop.querySelector('[data-search]');

                var addItem = function (payload) {
                    var done = function () { closeAddPop(); loadItems(); reloadCanvas(); };
                    qsHttp('POST', 'api/menu/item/save', Object.assign({ menu_id: currentMenuId }, payload)).then(done, done);
                };
                var renderPick = function (q) {
                    var ql = (q || '').toLowerCase();
                    var list;
                    if (ql) {
                        list = _pickables.filter(function (x) { return String(x.title).toLowerCase().indexOf(ql) >= 0; }).slice(0, 200);
                    } else {
                        // Balanced default so every type is visible without searching
                        // (a site with 200+ pages would otherwise bury posts/products).
                        var per = { Page: 0, Post: 0, Product: 0, Category: 0 }, cap = 25;
                        list = [];
                        _pickables.forEach(function (x) { if (per[x.type] !== undefined && per[x.type] < cap) { per[x.type]++; list.push(x); } });
                    }
                    if (!list.length) { listEl.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('No matches')) + '</div>'; return; }
                    listEl.innerHTML = list.slice(0, 200).map(function (x) {
                        return '<button type="button" class="mw-qs-pick-row" data-kind="' + esc(x.kind) + '" data-id="' + esc(x.id) + '">'
                            + '<span class="mw-qs-pick-row__t">' + esc(x.title) + '</span>'
                            + '<span class="mw-qs-mi-badge">' + esc(lang(x.type)) + '</span></button>';
                    }).join('');
                    listEl.querySelectorAll('.mw-qs-pick-row').forEach(function (b) {
                        b.addEventListener('click', function () {
                            addItem(b.dataset.kind === 'category' ? { categories_id: b.dataset.id } : { content_id: b.dataset.id });
                        });
                    });
                };

                pop.querySelector('[data-x]').addEventListener('click', closeAddPop);
                pop.querySelector('[data-link]').addEventListener('click', function () { addItem({ title: lang('New link'), url: '#' }); });
                searchEl.addEventListener('input', function () { renderPick(searchEl.value); });

                // Pickable targets: pages/posts/products (content) + categories.
                if (_pickables.length) { renderPick(''); }
                else {
                    Promise.all([
                        qsHttp('GET', 'api/content?limit=500&is_active=1').catch(function () { return {}; }),
                        qsHttp('GET', 'api/module/categories?limit=500').catch(function () { return {}; })
                    ]).then(function (r) {
                        var carr = (r[0] && (r[0].data || r[0].items)) || (Array.isArray(r[0]) ? r[0] : []);
                        var content = carr.filter(function (x) { return x && x.title && ['page', 'post', 'product'].indexOf(x.content_type) >= 0; })
                            .map(function (x) { return { kind: 'content', id: x.id, title: x.title, type: x.content_type === 'post' ? 'Post' : (x.content_type === 'product' ? 'Product' : 'Page') }; });
                        var catarr = (r[1] && (r[1].data || r[1].items)) || (Array.isArray(r[1]) ? r[1] : []);
                        var categories = catarr.filter(function (x) { return x && x.title; })
                            .map(function (x) { return { kind: 'category', id: x.id, title: x.title, type: 'Category' }; });
                        var ord = { Page: 0, Post: 1, Product: 2, Category: 3 };
                        _pickables = content.concat(categories).sort(function (a, b) { return (ord[a.type] - ord[b.type]) || String(a.title).localeCompare(String(b.title)); });
                        if (_addPop === pop) { renderPick(''); }
                    });
                }
            };
            if (addBtn) { addBtn.addEventListener('click', openAddPop); }
            if (selectEl) {
                selectEl.addEventListener('change', function () {
                    saveOption(el, selectEl.dataset.key || 'menu_name', selectEl.value);
                    var cur = menus.filter(function (m) { return String(m.title) === String(selectEl.value); })[0];
                    currentMenuId = cur ? cur.id : null;
                    loadItems();
                });
            }

            qsHttp('GET', 'api/menu/list').then(function (res) {
                menus = (res && res.items) || [];
                if (selectEl) {
                    selectEl.innerHTML = menus.map(function (m) {
                        return '<option value="' + esc(m.title) + '"' + (String(m.title) === String(currentName) ? ' selected' : '') + '>' + esc(m.label || m.title) + '</option>';
                    }).join('') || ('<option>' + esc(lang('No menus')) + '</option>');
                }
                var cur = menus.filter(function (m) { return String(m.title) === String(currentName); })[0] || menus[0];
                currentMenuId = cur ? cur.id : null;
                loadItems();
            }).catch(function () {
                if (itemsBox) { itemsBox.innerHTML = '<div class="mw-qs-items__empty">' + esc(lang('Could not load menus')) + '</div>'; }
            });
        })();
    }

    // When any OTHER quick panel announces it's opening, close this kit panel.
    try {
        qsWin().addEventListener('mwQuickSettingsWillOpen', function (e) {
            if (e && e.detail && e.detail.source === 'kit') { return; }
            close();
        });
    } catch (e) {}

    // ── public API ──────────────────────────────────────────────────────────
    mw.quickSettingsKit = {
        open: open,
        closeAll: function () { announceOpen('external'); close(); },
        recommendedColors: recommendedColors,
        register: function (config) {
            if (!config || !config.type) { return; }
            mw.quickSettings[config.type] = [{
                title: config.title || config.type,
                icon: config.icon || '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M4 12h4M16 12h4M12 4v4M12 16v4"/></svg>',
                action: function (el) { open(el, config); }
            }];
        }
    };
})();
