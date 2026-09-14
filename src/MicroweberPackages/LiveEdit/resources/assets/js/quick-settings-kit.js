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
            '.mw-qs-panel__title{font-weight:600;font-size:14px;flex:1 1 auto;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}',
            '.mw-qs-panel__head-actions{display:flex;gap:2px;flex:0 0 auto;}',
            '.mw-qs-panel__ico{width:30px;height:30px;border:0;border-radius:7px;background:transparent;color:inherit;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}',
            '.mw-qs-panel__ico:hover{background:#18243310;}',
            'html.dark .mw-qs-panel__ico:hover{background:#ffffff16;}',
            '.mw-qs-panel__ico.is-danger:hover{background:rgba(220,57,57,.35);}',
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
            '.mw-qs-sw--custom{display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#8a94a3;border:1px dashed #18243340;font-size:16px;line-height:1;}',
            '.mw-qs-sw--custom:hover{color:#182433;border-color:#18243366;transform:none;}',
            'html.dark .mw-qs-sw--custom{background:#22262c;color:#9aa3af;border-color:#ffffff33;}',
            '.mw-qs-row{display:flex;align-items:center;justify-content:space-between;gap:10px;min-height:36px;}',
            '.mw-qs-row__txt{min-width:0;}.mw-qs-row__txt small{display:block;color:#8a94a3;font-size:10.5px;margin-top:1px;}',
            '.mw-qs-input,.mw-qs-select{width:100%;border:1px solid #18243326;border-radius:9px;padding:8px 10px;font:inherit;font-size:12.5px;background:#fff;color:#182433;outline:none;}',
            '.mw-qs-input:focus,.mw-qs-select:focus{border-color:#182433;box-shadow:0 0 0 3px #1824331f;}',
            'html.dark .mw-qs-input,html.dark .mw-qs-select{background:#22262c;color:#e8eaed;border-color:#ffffff26;}',
            '.mw-qs-field{display:flex;gap:6px;align-items:center;}',
            '.mw-qs-suffix{flex:0 0 auto;color:#8a94a3;font-size:11.5px;}',
            '.mw-qs-pick{flex:0 0 auto;padding:8px 12px;border:1px solid #18243318;border-radius:9px;background:#18243308;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;}',
            '.mw-qs-pick:hover{background:#1824330d;}',
            'html.dark .mw-qs-pick{background:#ffffff0d;border-color:#ffffff1f;}',
            // quiet toggle
            '.mw-qs-toggle{position:relative;width:38px;height:22px;border-radius:22px;border:0;background:#c9ccd2;cursor:pointer;flex:0 0 auto;transition:background .15s;padding:0;}',
            '.mw-qs-toggle::after{content:"";position:absolute;top:2px;left:2px;width:18px;height:18px;border-radius:50%;background:#fff;transition:transform .15s;box-shadow:0 1px 2px rgba(0,0,0,.25);}',
            '.mw-qs-toggle.on{background:#182433;}.mw-qs-toggle.on::after{transform:translateX(16px);}',
            'html.dark .mw-qs-toggle{background:#4b4f57;}html.dark .mw-qs-toggle.on{background:#e8eaed;}html.dark .mw-qs-toggle.on::after{background:#182433;}',
            // advanced row
            '.mw-qs-advanced{display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;border:0;border-top:1px solid #18243314;background:transparent;color:inherit;cursor:pointer;font:inherit;padding:12px 0 2px;margin-top:4px;text-align:left;}',
            '.mw-qs-advanced small{display:block;color:#8a94a3;font-size:10.5px;margin-top:1px;font-weight:400;}',
            '.mw-qs-advanced__t{font-size:12.5px;font-weight:600;}',
            'html.dark .mw-qs-advanced{border-color:#ffffff14;}'
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
                return '<button type="button" class="mw-qs-cell' + (eq(o.value, cur) ? ' active' : '') + '"'
                    + ' data-ctl="segmented" data-key="' + esc(c.key) + '" data-val="' + esc(o.value) + '">' + esc(lang(o.label)) + '</button>';
            }).join('');
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-seg">' + cells + '</div></div>';
        }
        if (c.type === 'swatches') {
            var low = String(cur || '').toLowerCase();
            var sw = recommendedColors().map(function (col) {
                return '<button type="button" class="mw-qs-sw' + (col.toLowerCase() === low ? ' active' : '') + '"'
                    + ' data-ctl="swatch" data-key="' + esc(c.key) + '" data-col="' + esc(col) + '" style="background:' + esc(col) + '"></button>';
            }).join('');
            sw += '<button type="button" class="mw-qs-sw mw-qs-sw--custom" data-ctl="swatch-custom" data-key="' + esc(c.key) + '" title="' + esc(lang('Custom')) + '">+</button>';
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-swatches">' + sw + '</div></div>';
        }
        if (c.type === 'select') {
            var os = (c.options || []).map(function (o) {
                return '<option value="' + esc(o.value) + '"' + (eq(o.value, cur) ? ' selected' : '') + '>' + esc(lang(o.label)) + '</option>';
            }).join('');
            return '<div class="mw-qs-section">' + label + '<select class="mw-qs-select" data-ctl="select" data-key="' + esc(c.key) + '">' + os + '</select></div>';
        }
        if (c.type === 'text') {
            var suffix = c.suffix ? '<span class="mw-qs-suffix">' + esc(lang(c.suffix)) + '</span>' : '';
            // enableKey: a companion boolean option written alongside this field
            // (1 when non-empty, 0 when cleared) — e.g. SocialLinks' <net>_enabled.
            var enableAttr = c.enableKey ? ' data-enable-key="' + esc(c.enableKey) + '"' : '';
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-field">'
                + '<input type="' + (c.inputType || 'text') + '" class="mw-qs-input" data-ctl="text" data-key="' + esc(c.key) + '"' + enableAttr
                + ' placeholder="' + esc(lang(c.placeholder || '')) + '" value="' + esc(cur) + '">' + suffix + '</div></div>';
        }
        if (c.type === 'toggle') {
            var on = String(cur) === String(typeof c.onValue !== 'undefined' ? c.onValue : '1')
                || cur === true || String(cur) === 'true' || String(cur) === '1';
            var hint = c.hint ? '<small>' + esc(lang(c.hint)) + '</small>' : '';
            return '<div class="mw-qs-section"><div class="mw-qs-row"><div class="mw-qs-row__txt">' + esc(lang(c.label)) + hint + '</div>'
                + '<button type="button" class="mw-qs-toggle' + (on ? ' on' : '') + '" data-ctl="toggle" data-key="' + esc(c.key) + '"'
                + ' data-on="' + esc(typeof c.onValue !== 'undefined' ? c.onValue : '1') + '" data-off="' + esc(typeof c.offValue !== 'undefined' ? c.offValue : '') + '"></button></div></div>';
        }
        if (c.type === 'link') {
            return '<div class="mw-qs-section">' + label + '<div class="mw-qs-field">'
                + '<input type="text" class="mw-qs-input" data-ctl="text" data-key="' + esc(c.key) + '" placeholder="' + esc(lang('Paste a URL')) + '" value="' + esc(cur) + '">'
                + '<button type="button" class="mw-qs-pick" data-ctl="open-settings">' + esc(lang('Page')) + '</button></div></div>';
        }
        if (c.type === 'advanced') {
            return '<button type="button" class="mw-qs-advanced" data-ctl="open-settings"><span class="mw-qs-advanced__t">' + esc(lang(c.label || 'Advanced'))
                + (c.hint ? '<small>' + esc(lang(c.hint)) + '</small>' : '') + '</span>'
                + '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg></button>';
        }
        return '';
    }

    // ── panel lifecycle ─────────────────────────────────────────────────────
    var _el = null, _docClick = null;
    function close() { if (_el) { _el.style.display = 'none'; } }

    function sectionsHtml(sections, opts) {
        return (sections || []).map(function (c) { return renderControl(c, opts); }).join('');
    }

    function open(el, config) {
        injectCss();
        var doc = topDoc();
        var opts = readOptions(el);

        var dupIco = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h9a2 2 0 0 1 2 2v9h-2V5H9V3zM5 7h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm0 2v10h9V9H5z"/></svg>';
        var setIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';

        var head = '<div class="mw-qs-panel__head">'
            + (config.badge ? '<span class="mw-qs-panel__badge">' + esc(config.badge) + '</span>' : '')
            + '<span class="mw-qs-panel__title">' + esc(lang(config.title || 'Settings')) + '</span>'
            + '<div class="mw-qs-panel__head-actions">'
            + '<button type="button" class="mw-qs-panel__ico" data-ctl="duplicate" title="' + esc(lang('Duplicate')) + '">' + dupIco + '</button>'
            + '<button type="button" class="mw-qs-panel__ico" data-ctl="open-settings" title="' + esc(lang('Settings')) + '">' + setIco + '</button>'
            + '<button type="button" class="mw-qs-panel__ico" data-ctl="close" title="' + esc(lang('Close')) + '">✕</button>'
            + '</div></div>';

        var body;
        if (config.tabs && config.tabs.length) {
            var tabsNav = '<div class="mw-qs-tabs">' + config.tabs.map(function (t, i) {
                return '<button type="button" class="mw-qs-tab' + (i === 0 ? ' active' : '') + '" data-tab="' + i + '">' + esc(lang(t.name)) + '</button>';
            }).join('') + '</div>';
            var panes = config.tabs.map(function (t, i) {
                return '<div class="mw-qs-pane" data-pane="' + i + '"' + (i === 0 ? '' : ' style="display:none"') + '>' + sectionsHtml(t.sections, opts) + '</div>';
            }).join('');
            body = tabsNav + panes;
        } else {
            body = sectionsHtml(config.sections, opts);
        }

        if (!_el) { _el = doc.createElement('div'); _el.className = 'mw-qs-panel'; doc.body.appendChild(_el); }
        _el.innerHTML = head + body;
        _el.style.display = 'block';

        // position near the module
        var r = el.getBoundingClientRect(), off = frameOffset(), win = doc.defaultView || window;
        var pw = _el.offsetWidth || 300;
        var left = Math.min(r.left + off.x, win.innerWidth - pw - 10);
        var top = r.bottom + off.y + 8;
        if (top + (_el.offsetHeight || 380) > win.innerHeight - 8) {
            top = Math.max(8, r.top + off.y - (_el.offsetHeight || 380) - 8);
        }
        _el.style.left = Math.round(Math.max(8, left)) + 'px';
        _el.style.top = Math.round(Math.max(8, top)) + 'px';

        wire(el, config);

        if (!_docClick) {
            _docClick = function (ev) {
                if (!_el || _el.style.display !== 'block') { return; }
                if (_el.contains(ev.target)) { return; }
                // colour-picker / icon-picker popups live outside the panel
                if (ev.target.closest && ev.target.closest('.mw-color-picker, .mw-dropdown, .modal, .fi-modal')) { return; }
                close();
            };
            setTimeout(function () { doc.addEventListener('click', _docClick, true); }, 0);
        }
    }

    function wire(el, config) {
        // tabs
        _el.querySelectorAll('.mw-qs-tab').forEach(function (t) {
            t.addEventListener('click', function () {
                var i = t.dataset.tab;
                _el.querySelectorAll('.mw-qs-tab').forEach(function (x) { x.classList.toggle('active', x === t); });
                _el.querySelectorAll('.mw-qs-pane').forEach(function (p) { p.style.display = (p.dataset.pane === i) ? '' : 'none'; });
            });
        });

        var setActive = function (key, val) {
            _el.querySelectorAll('[data-ctl="segmented"][data-key="' + key + '"]').forEach(function (b) {
                b.classList.toggle('active', eq(b.dataset.val, val));
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
            tg.addEventListener('click', function () {
                var on = !tg.classList.contains('on');
                tg.classList.toggle('on', on);
                saveOption(el, tg.dataset.key, on ? tg.dataset.on : tg.dataset.off);
            });
        });
        _el.querySelectorAll('[data-ctl="swatch"]').forEach(function (sw) {
            sw.addEventListener('click', function () {
                var scope = sw.closest('.mw-qs-swatches') || _el;
                scope.querySelectorAll('.mw-qs-sw').forEach(function (x) { x.classList.remove('active'); });
                sw.classList.add('active');
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
                        saveOption(el, b.dataset.key, color);
                    }, b);
                }
            });
        });
        _el.querySelectorAll('[data-ctl="duplicate"]').forEach(function (b) {
            b.addEventListener('click', function () {
                try { mw.top().app.liveEdit.elementHandleContent.elementActions.cloneElement(el); } catch (e) {}
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
    }

    // ── public API ──────────────────────────────────────────────────────────
    mw.quickSettingsKit = {
        open: open,
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
