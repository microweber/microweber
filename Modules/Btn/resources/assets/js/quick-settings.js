// task-2026-09-07-btnpanel — Button module Live-Edit quick-settings.
//
// A single cohesive settings PANEL (Type / Color / Size / Width / Align / Link),
// matching the reference button popover, opened from the module handle. Each
// control persists a module option via mw.options.saveOption and refreshes the
// button on the canvas — the same round-trip the module settings use.

function btnSaveOption(el, key, value, cb) {
    var moduleId = el.getAttribute('id');
    var moduleType = el.getAttribute('data-type') || el.getAttribute('type');
    mw.options.saveOption({
        option_group: moduleId,
        option_key: key,
        option_value: value,
        module: moduleType,
    }, function () {
        mw.app.editor.dispatch('onModuleSettingsChanged', { 'moduleId': moduleId });
        if (typeof cb === 'function') { cb(); }
    });
}

// Save several options in one go, reloading the module only ONCE at the end.
// Used when a single choice (e.g. Type = Outline) must reconcile multiple options
// (style + background/border/text colour) atomically.
function btnSaveOptions(el, obj, cb) {
    var moduleId = el.getAttribute('id');
    var moduleType = el.getAttribute('data-type') || el.getAttribute('type');
    var keys = Object.keys(obj);
    var i = 0;
    (function next() {
        if (i >= keys.length) {
            mw.app.editor.dispatch('onModuleSettingsChanged', { 'moduleId': moduleId });
            if (typeof cb === 'function') { cb(); }
            return;
        }
        var k = keys[i++];
        mw.options.saveOption({ option_group: moduleId, option_key: k, option_value: obj[k], module: moduleType }, next);
    })();
}

function btnReadOptions(el) {
    try {
        var d = mw.top().app.modules.getModuleInlineViewData(el.getAttribute('id'));
        return (d && d.options) || {};
    } catch (e) { return {}; }
}

// ── option tables ──────────────────────────────────────────────────────────
// Type → option `style`; the btn template renders it as a class ($style).
// task-2026-09-14-btn-settings — was saving `button_style`/`button_size`, which
// the template ($style/$size) never read, so Type/Size silently did nothing.
var BTN_TYPES = [
    { label: 'Solid', value: 'btn-primary' },
    { label: 'Outline', value: 'btn-outline-primary' },
    { label: 'Soft', value: 'btn-light' },
];
// [background, contrasting text] — fallback palette if the site palette service
// is unavailable.
var BTN_COLORS = [
    ['#0d6efd', '#ffffff'], ['#2fb344', '#ffffff'], ['#dc2626', '#ffffff'],
    ['#f59e0b', '#182433'], ['#7c3aed', '#ffffff'], ['#182433', '#ffffff'], ['#ffffff', '#182433'],
];

// task-2026-09-14-btn-settings — pick a legible text colour for a given bg.
function btnContrast(hex) {
    try {
        var c = String(hex).trim().replace('#', '');
        if (c.length === 3) { c = c[0] + c[0] + c[1] + c[1] + c[2] + c[2]; }
        var r = parseInt(c.substr(0, 2), 16), g = parseInt(c.substr(2, 2), 16), b = parseInt(c.substr(4, 2), 16);
        var lum = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return lum > 0.62 ? '#182433' : '#ffffff';
    } catch (e) { return '#ffffff'; }
}

// Recommended colours from the SAME site palette service the MW colour picker
// uses (as the ESE / other pickers do). Falls back to BTN_COLORS.
function btnRecommendedColors() {
    try {
        var mgr = mw.top().app.templateSettings && mw.top().app.templateSettings.colorPaletteManager;
        if (mgr && mgr.getColors) {
            var colors = (mgr.getColors() || []).filter(function (c) {
                return c && typeof c === 'string' && /^#([0-9a-fA-F]{3,8})$/.test(c);
            });
            var seen = {}, out = [];
            colors.forEach(function (c) {
                var low = c.toLowerCase();
                if (!seen[low]) { seen[low] = 1; out.push([c, btnContrast(c)]); }
            });
            if (out.length) { return out.slice(0, 8); }
        }
    } catch (e) { /* fall through */ }
    return BTN_COLORS;
}
// Size → button_size (Bootstrap btn-sm / default / btn-lg).
var BTN_SIZES = [
    { label: 'S', value: 'btn-sm' },
    { label: 'M', value: '' },
    { label: 'L', value: 'btn-lg' },
];

function btnTopDoc() {
    try { if (window.top && window.top.document) { return window.top.document; } } catch (e) {}
    return document;
}

// Canvas iframe document — clicks there don't reach the top-window listener,
// so outside-click-close must listen on it too (matches the kit).
function btnCanvasDoc() {
    try {
        var fr = mw.top().app.canvas.getFrame();
        return fr.contentDocument || (fr.contentWindow && fr.contentWindow.document) || null;
    } catch (e) { return null; }
}

// Real module icon from the cached modules service (no new request), matching
// the kit's header. Returns HTML or null so the caller can fall back.
function btnModuleIcon() {
    try {
        var svc = (mw.top && mw.top().app && mw.top().app.modules) ? mw.top().app.modules
            : (mw.app && mw.app.modules ? mw.app.modules : null);
        if (svc && typeof svc.getModuleIcon === 'function' && svc.modulesListData) {
            var html = svc.getModuleIcon('btn');
            if (html && typeof html === 'string') { return html; }
        }
    } catch (e) {}
    return null;
}

// The button module lives in the canvas iframe; the panel mounts in the TOP
// window, so translate the button's viewport rect into top-window coords.
function btnFrameOffset() {
    try {
        var fr = mw.top().app.canvas.getFrame();
        var r = fr.getBoundingClientRect();
        return { x: r.left, y: r.top };
    } catch (e) { return { x: 0, y: 0 }; }
}

function btnPanelInjectCss() {
    var doc = btnTopDoc();
    if (doc.getElementById('mw-btn-panel-css')) { return; }
    var s = doc.createElement('style');
    s.id = 'mw-btn-panel-css';
    s.textContent = [
        '.mw-btn-panel{position:fixed;z-index:100061;width:300px;max-width:calc(100vw - 16px);max-height:calc(100vh - 24px);overflow:auto;',
        'background:#fff;color:#182433;border-radius:14px;padding:12px;',
        'box-shadow:0 10px 34px rgba(24,36,51,.16),0 2px 8px rgba(24,36,51,.08);',
        'font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;font-size:13px;}',
        'html.dark .mw-btn-panel{background:#1b1e22;color:#e8eaed;box-shadow:0 12px 40px rgba(0,0,0,.6);}',
        '.mw-btn-panel__head{display:flex;align-items:center;gap:8px;margin-bottom:12px;}',
        '.mw-btn-panel__badge{width:28px;height:28px;border-radius:8px;background:#F4F4F2;color:#182433;padding:4px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;}',
        '.mw-btn-panel__badge svg{width:20px;height:20px;display:block;}',
        'html.dark .mw-btn-panel__badge{background:#2a2e34;color:#e8eaed;}',
        '.mw-btn-panel__title{font-weight:600;font-size:14px;flex:1 1 auto;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}',
        '.mw-btn-panel__head-actions{display:flex;gap:2px;flex:0 0 auto;}',
        '.mw-btn-panel__ico{width:34px;height:34px;border:0;border-radius:7px;background:transparent;color:inherit;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}',
        '.mw-btn-panel__ico:hover{background:#18243310;}',
        'html.dark .mw-btn-panel__ico:hover{background:#ffffff16;}',
        '.mw-btn-panel__ico.is-danger:hover{background:rgba(220,38,38,.35);}',
        '.mw-btn-panel__ico svg{width:16px;height:16px;}',
        '.mw-btn-panel__section{margin-bottom:9px;}',
        '.mw-btn-panel__section:last-child{margin-bottom:0;}',
        // task-2026-09-24 — compact: pair short sections side-by-side in a row.
        '.mw-btn-panel__row{display:flex;gap:10px;margin-bottom:9px;}',
        '.mw-btn-panel__row>.mw-btn-panel__section{flex:1 1 0;min-width:0;margin-bottom:0;}',
        '.mw-btn-panel__label{font-size:10.5px;font-weight:600;letter-spacing:.02em;color:#8a94a3;margin-bottom:4px;}',
        '.mw-btn-panel__seg{display:flex;gap:5px;}',
        '.mw-btn-panel__seg--eq .mw-btn-panel__cell{flex:1 1 0;}',
        '.mw-btn-panel__cell{flex:0 0 auto;min-height:30px;padding:5px 8px;border:1px solid #18243318;border-radius:8px;background:#18243305;color:inherit;cursor:pointer;font:inherit;font-size:12px;font-weight:500;display:inline-flex;align-items:center;justify-content:center;transition:background-color .15s,border-color .15s,color .15s;}',
        // icon-only cells (e.g. Align) — square-ish, centered glyph.
        '.mw-btn-panel__cell--icon{padding:5px 4px;}',
        '.mw-btn-panel__cell--icon svg{width:16px;height:16px;display:block;}',
        '.mw-btn-panel__cell:hover{background:#1824330d;border-color:#18243230;}',
        '.mw-btn-panel__cell.active{border-color:#182433;box-shadow:inset 0 0 0 1px #182433;color:#182433;}',
        'html.dark .mw-btn-panel__cell{background:#ffffff08;border-color:#ffffff1f;}',
        'html.dark .mw-btn-panel__cell:hover{background:#ffffff14;}',
        'html.dark .mw-btn-panel__cell.active{border-color:#e8eaed;box-shadow:inset 0 0 0 1px #e8eaed;color:#e8eaed;}',
        '.mw-btn-panel__swatches{display:flex;flex-wrap:wrap;gap:6px;}',
        '.mw-btn-panel__sw{width:22px;height:22px;border-radius:50%;border:1px solid rgba(0,0,0,.12);cursor:pointer;padding:0;position:relative;transition:transform .1s;}',
        '.mw-btn-panel__sw:hover{transform:scale(1.08);}',
        '.mw-btn-panel__sw.active{box-shadow:0 0 0 2px #fff,0 0 0 4px #182433;}',
        'html.dark .mw-btn-panel__sw.active{box-shadow:0 0 0 2px #1b1e22,0 0 0 4px #e8eaed;}',
        'html.dark .mw-btn-panel__sw{border-color:rgba(255,255,255,.18);}',
        '.mw-btn-panel__sw--custom{display:inline-flex;align-items:center;justify-content:center;background:#fff;color:#8a94a3;border:1px dashed #18243340;font-size:16px;font-weight:400;line-height:1;}',
        '.mw-btn-panel__sw--custom:hover{color:#182433;border-color:#18243366;transform:none;}',
        'html.dark .mw-btn-panel__sw--custom{background:#22262c;color:#9aa3af;border-color:#ffffff33;}',
        'html.dark .mw-btn-panel__sw--custom:hover{color:#e8eaed;border-color:#ffffff66;}',
        '.mw-btn-panel__link{display:flex;gap:6px;}',
        '.mw-btn-panel__input{flex:1 1 auto;min-width:0;border:1px solid #18243326;border-radius:9px;padding:8px 10px;font:inherit;font-size:12.5px;background:#fff;color:#182433;outline:none;}',
        '.mw-btn-panel__input:focus{border-color:#182433;box-shadow:0 0 0 3px #1824331f;}',
        'html.dark .mw-btn-panel__input{background:#22262c;color:#e8eaed;border-color:#ffffff26;}',
        'html.dark .mw-btn-panel__input:focus{border-color:#e8eaed;box-shadow:0 0 0 3px rgba(232,234,237,.22);}',
        '.mw-btn-panel__input::placeholder{color:#8a94a3;opacity:1;}',
        '.mw-btn-panel__pick{flex:0 0 auto;padding:8px 12px;border:1px solid #18243318;border-radius:9px;background:#18243308;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;}',
        '.mw-btn-panel__pick:hover{background:#1824330d;}',
        '.mw-btn-panel__icoprev{display:inline-flex;align-items:center;}',
        '.mw-btn-panel__icoprev i,.mw-btn-panel__icoprev svg{width:16px;height:16px;font-size:16px;line-height:1;}',
        'html.dark .mw-btn-panel__pick{background:#ffffff0d;border-color:#ffffff1f;}',
        'html.dark .mw-btn-panel__pick:hover{background:#ffffff16;}',
        '.mw-btn-panel__cell:focus-visible,.mw-btn-panel__sw:focus-visible,.mw-btn-panel__pick:focus-visible,.mw-btn-panel__ico:focus-visible{outline:2px solid #182433;outline-offset:2px;}',
        'html.dark .mw-btn-panel__cell:focus-visible,html.dark .mw-btn-panel__sw:focus-visible,html.dark .mw-btn-panel__pick:focus-visible,html.dark .mw-btn-panel__ico:focus-visible{outline-color:#e8eaed;}'
    ].join('');
    doc.head.appendChild(s);
}

var _btnPanelEl = null;
var _btnPanelDocClick = null;
var _btnPanelDocKey = null;

function btnClosePanel() {
    if (_btnPanelEl) { _btnPanelEl.style.display = 'none'; }
}

// task-2026-09-15-qs — cross-panel coordination: opening any quick panel closes
// every other one (incl. the shared kit's .mw-qs-panel). Decoupled via a
// top-window event; the kit emits + listens for the same event.
function btnQsWin() { try { return btnTopDoc().defaultView || window; } catch (e) { return window; } }
function btnAnnounceOpen() {
    try { btnQsWin().dispatchEvent(new CustomEvent('mwQuickSettingsWillOpen', { detail: { source: 'btn' } })); } catch (e) {}
}
(function () {
    try {
        btnQsWin().addEventListener('mwQuickSettingsWillOpen', function (e) {
            if (e && e.detail && e.detail.source === 'btn') { return; }
            btnClosePanel();
        });
    } catch (e) {}
})();

function btnPanelSeg(label, group, items, current) {
    var cells = items.map(function (it) {
        var active = (String(it.value) === String(current)) ? ' active' : '';
        // task-2026-09-24 — an item may carry an `icon` (SVG) instead of text
        // (e.g. Align). Keep the label as title/aria for a11y.
        var iconCls = it.icon ? ' mw-btn-panel__cell--icon' : '';
        var attrs = it.icon ? (' title="' + it.label + '" aria-label="' + it.label + '"') : '';
        var content = it.icon ? it.icon : it.label;
        return '<button type="button" class="mw-btn-panel__cell' + active + iconCls + '" data-group="' + group + '" data-val="' + it.value + '"' + attrs + '>' + content + '</button>';
    }).join('');
    return '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + label + '</div>'
        + '<div class="mw-btn-panel__seg mw-btn-panel__seg--eq">' + cells + '</div></div>';
}

// task-2026-09-24 — pair short sections side-by-side to keep the panel compact.
function btnPanelRow() {
    return '<div class="mw-btn-panel__row">' + Array.prototype.slice.call(arguments).join('') + '</div>';
}

// task-2026-09-14-btn-settings — reusable colour-swatch section (recommended
// palette + Custom). `target` is the option key it writes ('backgroundColor' or
// 'color'). Background also sets a contrasting text colour as a smart default.
function btnSwatchSection(label, target, current) {
    var cur = (current || '').toLowerCase();
    var matched = false;
    var sw = btnRecommendedColors().map(function (c) {
        var isOn = c[0].toLowerCase() === cur;
        if (isOn) { matched = true; }
        return '<button type="button" class="mw-btn-panel__sw' + (isOn ? ' active' : '') + '" data-target="' + target + '" data-bg="' + c[0] + '" data-fg="' + c[1] + '" aria-label="' + c[0] + '" style="background:' + c[0] + '"></button>';
    }).join('');
    // Custom chip carries the applied colour when it isn't a recommended swatch.
    var customUnmatched = current && !matched;
    sw += '<button type="button" class="mw-btn-panel__sw mw-btn-panel__sw--custom' + (customUnmatched ? ' active' : '') + '" data-act="custom-color" data-target="' + target + '" title="' + mw.lang('Custom') + '"'
        + (customUnmatched ? ' style="background:' + current + '"' : '') + '>' + (customUnmatched ? '' : '+') + '</button>';
    return '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + label + '</div><div class="mw-btn-panel__swatches">' + sw + '</div></div>';
}

function openBtnPanel(el) {
    btnPanelInjectCss();
    btnAnnounceOpen();
    var doc = btnTopDoc();
    var opts = btnReadOptions(el);
    var curType = opts.style || '';
    var curSize = (typeof opts.size !== 'undefined') ? opts.size : '';
    var curBg = (opts.backgroundColor || '').toLowerCase();
    var curColor = (opts.color || '').toLowerCase();
    var curWidth = /\bw-100\b/.test(opts.class || '') ? 'w-100' : '';
    var curAlign = opts.align || 'left';
    var curUrl = opts.url || '';
    var curIcon = opts.icon || '';
    var curIconPos = opts.iconPosition || 'left';

    var iconSection = '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + mw.lang('Icon') + '</div>'
        + '<div class="mw-btn-panel__link">'
        + '  <button type="button" class="mw-btn-panel__pick" data-act="pick-icon" style="flex:1 1 auto;">'
        + (curIcon ? ('<span class="mw-btn-panel__icoprev">' + curIcon + '</span> ' + mw.lang('Change')) : ('+ ' + mw.lang('Add icon'))) + '</button>'
        + (curIcon ? '<button type="button" class="mw-btn-panel__pick" data-act="remove-icon" title="' + mw.lang('Remove') + '">✕</button>' : '')
        + '</div>'
        + (curIcon ? btnPanelSeg(mw.lang('Icon position'), 'iconPosition', [{ label: mw.lang('Left'), value: 'left' }, { label: mw.lang('Right'), value: 'right' }], curIconPos) : '')
        + '</div>';

    var dupIco = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h9a2 2 0 0 1 2 2v9h-2V5H9V3zM5 7h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm0 2v10h9V9H5z"/></svg>';
    var setIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>';
    // Real module icon (falls back to a button glyph) — matches the kit header.
    var badgeIco = btnModuleIcon() || '<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h14M4 11h9M4 15h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="16" cy="11" r="2.4" fill="currentColor"/></svg>';

    // task-2026-09-24 — align icons (left / center / right) for a compact,
    // icon-based Align control instead of three text buttons.
    var alignLeftIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h10M4 18h13"/></svg>';
    var alignCenterIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M7 12h10M5 18h14"/></svg>';
    var alignRightIco = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M10 12h10M7 18h13"/></svg>';

    var html = ''
        + '<div class="mw-btn-panel__head">'
        + '  <span class="mw-btn-panel__badge">' + badgeIco + '</span>'
        + '  <span class="mw-btn-panel__title">' + mw.lang('Button') + '</span>'
        + '  <div class="mw-btn-panel__head-actions">'
        + '    <button type="button" class="mw-btn-panel__ico" data-act="duplicate" title="' + mw.lang('Duplicate') + '">' + dupIco + '</button>'
        + '    <button type="button" class="mw-btn-panel__ico" data-act="pick-page" title="' + mw.lang('Settings') + '">' + setIco + '</button>'
        + '    <button type="button" class="mw-btn-panel__ico" data-act="close" title="' + mw.lang('Close') + '">✕</button>'
        + '  </div>'
        + '</div>'
        + btnPanelSeg(mw.lang('Type'), 'style', BTN_TYPES, curType)
        + btnPanelRow(
            btnSwatchSection(mw.lang('Color'), 'backgroundColor', curBg),
            btnSwatchSection(mw.lang('Text color'), 'color', curColor)
          )
        + btnPanelRow(
            btnPanelSeg(mw.lang('Size'), 'size', BTN_SIZES, curSize),
            btnPanelSeg(mw.lang('Width'), 'width', [{ label: mw.lang('Fit'), value: '' }, { label: mw.lang('Fill'), value: 'w-100' }], curWidth)
          )
        + btnPanelSeg(mw.lang('Align'), 'align', [
            { label: mw.lang('Left'), value: 'left', icon: alignLeftIco },
            { label: mw.lang('Center'), value: 'center', icon: alignCenterIco },
            { label: mw.lang('Right'), value: 'right', icon: alignRightIco }
          ], curAlign)
        + iconSection
        + '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + mw.lang('Link') + '</div>'
        + '  <div class="mw-btn-panel__link">'
        + '    <input type="text" class="mw-btn-panel__input" data-link placeholder="' + mw.lang('Paste a URL') + '" value="' + String(curUrl).replace(/"/g, '&quot;') + '">'
        + '    <button type="button" class="mw-btn-panel__pick" data-act="pick-page">' + mw.lang('Page') + '</button>'
        + '  </div>'
        + '</div>';

    if (!_btnPanelEl) {
        _btnPanelEl = doc.createElement('div');
        _btnPanelEl.className = 'mw-btn-panel';
        doc.body.appendChild(_btnPanelEl);
    }
    _btnPanelEl.innerHTML = html;
    _btnPanelEl.style.display = 'block';

    // Position near the module's TOP-LEFT (by the handle toolbar), then clamp
    // fully into the viewport — matches the shared kit.
    var r = el.getBoundingClientRect();
    var off = btnFrameOffset();
    var win = doc.defaultView || window;
    var margin = 8;
    var winW = win.innerWidth, winH = win.innerHeight;
    var pw = _btnPanelEl.offsetWidth || 300, ph = _btnPanelEl.offsetHeight || 360;
    var left = Math.min(Math.max(r.left + off.x, margin), Math.max(margin, winW - pw - margin));
    var top = Math.min(Math.max(r.top + off.y, margin), Math.max(margin, winH - ph - margin));
    _btnPanelEl.style.left = Math.round(left) + 'px';
    _btnPanelEl.style.top = Math.round(top) + 'px';

    // Draggable by the header (matches the shared kit). Grabbing the header
    // background/title moves the panel; the action icons keep working.
    (function () {
        var head = _btnPanelEl.querySelector('.mw-btn-panel__head');
        if (!head) { return; }
        head.style.cursor = 'move';
        head.addEventListener('mousedown', function (e) {
            if (e.button !== 0) { return; }
            if (e.target.closest && e.target.closest('.mw-btn-panel__ico')) { return; }
            var d = btnTopDoc(), w = d.defaultView || window;
            var rr = _btnPanelEl.getBoundingClientRect();
            var sx = e.clientX, sy = e.clientY, sl = rr.left, st = rr.top;
            e.preventDefault();
            var prevSel = d.body.style.userSelect;
            d.body.style.userSelect = 'none';
            var mv = function (ev) {
                var m = 4, pw2 = _btnPanelEl.offsetWidth, ph2 = _btnPanelEl.offsetHeight;
                var nl = Math.min(Math.max(m, sl + (ev.clientX - sx)), Math.max(m, w.innerWidth - pw2 - m));
                var nt = Math.min(Math.max(m, st + (ev.clientY - sy)), Math.max(m, w.innerHeight - ph2 - m));
                _btnPanelEl.style.left = Math.round(nl) + 'px';
                _btnPanelEl.style.top = Math.round(nt) + 'px';
            };
            var up = function () {
                d.removeEventListener('mousemove', mv, true);
                d.removeEventListener('mouseup', up, true);
                d.body.style.userSelect = prevSel;
            };
            d.addEventListener('mousemove', mv, true);
            d.addEventListener('mouseup', up, true);
        });
    })();

    // ── wiring ──
    var setActive = function (group, val) {
        _btnPanelEl.querySelectorAll('[data-group="' + group + '"]').forEach(function (b) {
            b.classList.toggle('active', String(b.dataset.val) === String(val));
        });
    };

    _btnPanelEl.querySelectorAll('.mw-btn-panel__cell').forEach(function (b) {
        b.addEventListener('click', function () {
            var group = b.dataset.group, val = b.dataset.val;
            if (group === 'width') {
                // width rides the extra `class` option (w-100 = full width).
                var cls = (btnReadOptions(el).class || '').replace(/\bw-100\b/g, '').trim();
                if (val === 'w-100') { cls = (cls + ' w-100').trim(); }
                btnSaveOption(el, 'class', cls);
            } else if (group === 'align') {
                btnSaveOption(el, 'align', val);
            } else if (group === 'style') {
                // Type must RECONCILE the colours, otherwise a previously chosen
                // fill colour (custom-css emits background-color:…!important) keeps
                // covering the outline, so "Outline" appeared to do nothing.
                var o = btnReadOptions(el);
                var accent = o.backgroundColor || o.borderColor || o.color || '#0d6efd';
                if (val === 'btn-outline-primary') {
                    // transparent fill, accent border + text.
                    btnSaveOptions(el, { style: val, backgroundColor: '', borderColor: accent, color: accent });
                } else if (val === 'btn-primary') {
                    // solid fill in the accent, contrasting text, no border override.
                    btnSaveOptions(el, { style: val, backgroundColor: accent, color: btnContrast(accent), borderColor: '' });
                } else {
                    // soft / light — clear inline overrides so the class styling shows.
                    btnSaveOptions(el, { style: val, backgroundColor: '', borderColor: '', color: '' });
                }
            } else {
                btnSaveOption(el, group, val);
            }
            setActive(group, val);
        });
    });

    _btnPanelEl.querySelectorAll('.mw-btn-panel__sw').forEach(function (sw) {
        sw.addEventListener('click', function () {
            if (!sw.dataset.bg) { return; } // the "Custom" swatch is handled below
            var target = sw.dataset.target || 'backgroundColor';
            var scope = sw.closest('.mw-btn-panel__swatches') || _btnPanelEl;
            scope.querySelectorAll('.mw-btn-panel__sw').forEach(function (x) { x.classList.remove('active'); });
            sw.classList.add('active');
            var cust = scope.querySelector('.mw-btn-panel__sw--custom');
            if (cust) { cust.style.background = ''; cust.textContent = '+'; }
            if (target === 'backgroundColor') {
                // On an OUTLINE button the "Color" is the border+text accent, not a
                // fill — otherwise the fill covers the outline. Keep bg transparent.
                var curStyle = (btnReadOptions(el).style || '');
                if (curStyle === 'btn-outline-primary') {
                    btnSaveOptions(el, { backgroundColor: '', borderColor: sw.dataset.bg, color: sw.dataset.bg });
                } else {
                    // background sets a contrasting text colour as a smart default;
                    // the Text-colour section can override it.
                    btnSaveOptions(el, { backgroundColor: sw.dataset.bg, color: sw.dataset.fg });
                }
            } else {
                btnSaveOption(el, target, sw.dataset.bg);
            }
        });
    });

    var linkInput = _btnPanelEl.querySelector('[data-link]');
    if (linkInput) {
        var saveUrl = function () { btnSaveOption(el, 'url', linkInput.value.trim()); };
        linkInput.addEventListener('change', saveUrl);
        linkInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); saveUrl(); } });
    }

    _btnPanelEl.querySelectorAll('[data-act]').forEach(function (b) {
        b.addEventListener('click', function () {
            var act = b.dataset.act;
            if (act === 'close') { btnClosePanel(); return; }
            if (act === 'duplicate') {
                try { mw.top().app.liveEdit.elementHandleContent.elementActions.cloneElement(el); } catch (e) {}
                btnClosePanel(); return;
            }
            if (act === 'delete') {
                try { mw.top().app.liveEdit.elementHandleContent.elementActions.deleteElement(el); } catch (e) {}
                btnClosePanel(); return;
            }
            if (act === 'pick-page') {
                // Full link/page picker lives in the module settings.
                try { mw.top().app.editor.dispatch('onModuleSettingsRequest', el); } catch (e) {}
                btnClosePanel(); return;
            }
            if (act === 'custom-color') {
                // Open the shared MW colour picker with the site's recommended
                // colours (same service the ESE / other pickers use).
                var tgt = b.dataset.target || 'backgroundColor';
                var picker = (mw.top().app && mw.top().app.colorPicker)
                    || (mw.app && mw.app.colorPicker) || null;
                var current = (btnReadOptions(el)[tgt]) || '#182433';
                if (picker && picker.openColorPicker) {
                    picker.openColorPicker(current, function (color) {
                        if (!color) { return; }
                        var scope = b.closest('.mw-btn-panel__swatches') || _btnPanelEl;
                        scope.querySelectorAll('.mw-btn-panel__sw').forEach(function (x) { x.classList.remove('active'); });
                        // Show the chosen colour ON the custom chip.
                        b.classList.add('active'); b.style.background = color; b.textContent = '';
                        if (tgt === 'backgroundColor') {
                            btnSaveOption(el, 'backgroundColor', color);
                            btnSaveOption(el, 'color', btnContrast(color));
                        } else {
                            btnSaveOption(el, tgt, color);
                        }
                    }, b);
                }
                return;
            }
            if (act === 'pick-icon') {
                // Shared MW icon picker; save the chosen <i> markup to options.icon.
                try {
                    var ipSvc = (mw.top().app && mw.top().app.get) ? mw.top().app.get('iconPicker')
                        : (mw.app && mw.app.get ? mw.app.get('iconPicker') : null);
                    if (ipSvc && ipSvc.pickIcon) {
                        var holder = btnTopDoc().createElement('i');
                        var picked = ipSvc.pickIcon(holder);
                        // The icon picker dialog opens BELOW this panel (z-index
                        // 100061), so it was hidden behind it. Raise the picker (and
                        // any dialog holder/overlay) above the panel once it renders.
                        (function raiseIconPicker() {
                            var td = btnTopDoc();
                            var bump = function (node) {
                                if (!node || !node.style) { return; }
                                node.style.setProperty('z-index', '100200', 'important');
                            };
                            try {
                                if (picked && picked.picker && typeof picked.picker.dialog === 'function') {
                                    var dlg = picked.picker.dialog();
                                    bump(dlg && (dlg.get ? dlg.get(0) : (dlg.nodeType ? dlg : dlg[0])));
                                }
                            } catch (e) {}
                            var scan = function () {
                                try {
                                    td.querySelectorAll('.mw-dialog-holder, .mw-dialog, .mw-ui-modal, [class*="icon-selector"], [class*="icon-picker"]').forEach(function (n) {
                                        var z = parseInt((td.defaultView.getComputedStyle(n).zIndex) || '0', 10);
                                        if (isNaN(z) || z < 100062) { bump(n); }
                                    });
                                } catch (e) {}
                            };
                            scan(); setTimeout(scan, 60); setTimeout(scan, 200);
                        })();
                        picked.promise().then(function (data) {
                            try { data.render(); } catch (e) {}
                            var iconHtml = (picked.target && picked.target.outerHTML) || holder.outerHTML;
                            btnSaveOption(el, 'icon', iconHtml, function () { openBtnPanel(el); });
                        });
                    }
                } catch (e) {}
                return;
            }
            if (act === 'remove-icon') {
                btnSaveOption(el, 'icon', '', function () { openBtnPanel(el); });
                return;
            }
        });
    });

    // Outside-click / Escape close — bound once on BOTH the top window and the
    // canvas iframe (matches the kit). Clicks inside a spawned picker/dialog the
    // panel opened must NOT dismiss it.
    if (!_btnPanelDocClick) {
        _btnPanelDocClick = function (ev) {
            if (!_btnPanelEl || _btnPanelEl.style.display !== 'block') { return; }
            var t = ev.target;
            if (_btnPanelEl.contains(t)) { return; }
            if (t && t.closest && t.closest('.mw-btn-panel, .mw-color-picker, .mw-dropdown, .modal, .fi-modal, .mw-dialog, .mw-filepicker')) { return; }
            btnClosePanel();
        };
        _btnPanelDocKey = function (ev) {
            if (!_btnPanelEl || _btnPanelEl.style.display !== 'block') { return; }
            if (ev.key === 'Escape' || ev.key === 'Esc') { ev.stopPropagation(); btnClosePanel(); }
        };
        setTimeout(function () {
            doc.addEventListener('click', _btnPanelDocClick, true);
            doc.addEventListener('keydown', _btnPanelDocKey, true);
            var cd = btnCanvasDoc();
            if (cd && cd !== doc) {
                try { cd.addEventListener('click', _btnPanelDocClick, true); } catch (e) {}
                try { cd.addEventListener('keydown', _btnPanelDocKey, true); } catch (e) {}
            }
        }, 0);
    }
}

// A single module-handle entry: the cohesive Button settings panel.
mw.quickSettings.btn = [
    {
        title: 'Button',
        icon: '<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 7h14M4 11h9M4 15h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="16" cy="11" r="2.4" fill="currentColor"/></svg>',
        action: function (el) { openBtnPanel(el); },
    },
];
