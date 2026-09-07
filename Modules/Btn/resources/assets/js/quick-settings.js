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

function btnReadOptions(el) {
    try {
        var d = mw.top().app.modules.getModuleInlineViewData(el.getAttribute('id'));
        return (d && d.options) || {};
    } catch (e) { return {}; }
}

// ── option tables ──────────────────────────────────────────────────────────
// Type → button_style; the btn template applies it directly as a class.
var BTN_TYPES = [
    { label: 'Solid', value: 'btn-primary' },
    { label: 'Outline', value: 'btn-outline-primary' },
    { label: 'Soft', value: 'btn-light' },
];
// [background, contrasting text].
var BTN_COLORS = [
    ['#0d6efd', '#ffffff'], ['#2fb344', '#ffffff'], ['#dc2626', '#ffffff'],
    ['#f59e0b', '#182433'], ['#7c3aed', '#ffffff'], ['#182433', '#ffffff'], ['#ffffff', '#182433'],
];
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
        '.mw-btn-panel{position:fixed;z-index:100061;width:270px;max-width:calc(100vw - 16px);',
        'background:#fff;color:#182433;border-radius:14px;padding:14px;',
        'box-shadow:0 10px 34px rgba(24,36,51,.16),0 2px 8px rgba(24,36,51,.08);',
        'font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;font-size:13px;}',
        'html.dark .mw-btn-panel{background:#1b1e22;color:#e8eaed;box-shadow:0 12px 40px rgba(0,0,0,.6);}',
        '.mw-btn-panel__head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;}',
        '.mw-btn-panel__title{font-weight:600;font-size:14px;}',
        '.mw-btn-panel__head-actions{display:flex;gap:2px;}',
        '.mw-btn-panel__ico{width:30px;height:30px;border:0;border-radius:7px;background:transparent;color:inherit;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;}',
        '.mw-btn-panel__ico:hover{background:#18243310;}',
        'html.dark .mw-btn-panel__ico:hover{background:#ffffff16;}',
        '.mw-btn-panel__ico.is-danger:hover{background:rgba(220,57,57,.35);}',
        '.mw-btn-panel__ico svg{width:16px;height:16px;}',
        '.mw-btn-panel__section{margin-bottom:12px;}',
        '.mw-btn-panel__section:last-child{margin-bottom:0;}',
        '.mw-btn-panel__label{font-size:11px;font-weight:600;letter-spacing:.02em;color:#8a94a3;margin-bottom:6px;}',
        '.mw-btn-panel__seg{display:flex;gap:6px;}',
        '.mw-btn-panel__seg--eq .mw-btn-panel__cell{flex:1 1 0;}',
        '.mw-btn-panel__cell{flex:0 0 auto;min-height:36px;padding:7px 10px;border:1px solid #18243318;border-radius:9px;background:#18243305;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;display:inline-flex;align-items:center;justify-content:center;transition:background-color .15s,border-color .15s,color .15s;}',
        '.mw-btn-panel__cell:hover{background:#1824330d;border-color:#18243230;}',
        '.mw-btn-panel__cell.active{border-color:#0d6efd;box-shadow:inset 0 0 0 1px #0d6efd;color:#0d6efd;}',
        'html.dark .mw-btn-panel__cell{background:#ffffff08;border-color:#ffffff1f;}',
        'html.dark .mw-btn-panel__cell:hover{background:#ffffff14;}',
        'html.dark .mw-btn-panel__cell.active{border-color:#4c9dff;box-shadow:inset 0 0 0 1px #4c9dff;color:#8cc0ff;}',
        '.mw-btn-panel__swatches{display:flex;flex-wrap:wrap;gap:8px;}',
        '.mw-btn-panel__sw{width:26px;height:26px;border-radius:50%;border:1px solid rgba(0,0,0,.12);cursor:pointer;padding:0;position:relative;transition:transform .1s;}',
        '.mw-btn-panel__sw:hover{transform:scale(1.08);}',
        '.mw-btn-panel__sw.active{box-shadow:0 0 0 2px #fff,0 0 0 4px #0d6efd;}',
        'html.dark .mw-btn-panel__sw.active{box-shadow:0 0 0 2px #1b1e22,0 0 0 4px #4c9dff;}',
        '.mw-btn-panel__link{display:flex;gap:6px;}',
        '.mw-btn-panel__input{flex:1 1 auto;min-width:0;border:1px solid #18243326;border-radius:9px;padding:8px 10px;font:inherit;font-size:12.5px;background:#fff;color:#182433;outline:none;}',
        '.mw-btn-panel__input:focus{border-color:#0d6efd;box-shadow:0 0 0 3px #0d6efd1f;}',
        'html.dark .mw-btn-panel__input{background:#22262c;color:#e8eaed;border-color:#ffffff26;}',
        '.mw-btn-panel__pick{flex:0 0 auto;padding:8px 12px;border:1px solid #18243318;border-radius:9px;background:#18243308;color:inherit;cursor:pointer;font:inherit;font-size:12.5px;font-weight:500;}',
        '.mw-btn-panel__pick:hover{background:#1824330d;}',
        'html.dark .mw-btn-panel__pick{background:#ffffff0d;border-color:#ffffff1f;}'
    ].join('');
    doc.head.appendChild(s);
}

var _btnPanelEl = null;
var _btnPanelDocClick = null;

function btnClosePanel() {
    if (_btnPanelEl) { _btnPanelEl.style.display = 'none'; }
}

function btnPanelSeg(label, group, items, current) {
    var cells = items.map(function (it) {
        var active = (String(it.value) === String(current)) ? ' active' : '';
        return '<button type="button" class="mw-btn-panel__cell' + active + '" data-group="' + group + '" data-val="' + it.value + '">' + it.label + '</button>';
    }).join('');
    return '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + label + '</div>'
        + '<div class="mw-btn-panel__seg mw-btn-panel__seg--eq">' + cells + '</div></div>';
}

function openBtnPanel(el) {
    btnPanelInjectCss();
    var doc = btnTopDoc();
    var opts = btnReadOptions(el);
    var curType = opts.button_style || '';
    var curSize = (typeof opts.button_size !== 'undefined') ? opts.button_size : '';
    var curBg = (opts.backgroundColor || '').toLowerCase();
    var curWidth = /\bw-100\b/.test(opts.class || '') ? 'w-100' : '';
    var curAlign = opts.align || 'left';
    var curUrl = opts.url || '';

    var swatches = BTN_COLORS.map(function (c) {
        var active = (c[0].toLowerCase() === curBg) ? ' active' : '';
        return '<button type="button" class="mw-btn-panel__sw' + active + '" data-bg="' + c[0] + '" data-fg="' + c[1] + '" style="background:' + c[0] + '"></button>';
    }).join('');

    var dupIco = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h9a2 2 0 0 1 2 2v9h-2V5H9V3zM5 7h9a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2zm0 2v10h9V9H5z"/></svg>';
    var delIco = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M9 3h6l1 2h4v2H4V5h4l1-2zM6 9h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 9z"/></svg>';

    var html = ''
        + '<div class="mw-btn-panel__head">'
        + '  <span class="mw-btn-panel__title">' + mw.lang('Button') + '</span>'
        + '  <div class="mw-btn-panel__head-actions">'
        + '    <button type="button" class="mw-btn-panel__ico" data-act="duplicate" title="' + mw.lang('Duplicate') + '">' + dupIco + '</button>'
        + '    <button type="button" class="mw-btn-panel__ico is-danger" data-act="delete" title="' + mw.lang('Delete') + '">' + delIco + '</button>'
        + '    <button type="button" class="mw-btn-panel__ico" data-act="close" title="' + mw.lang('Close') + '">✕</button>'
        + '  </div>'
        + '</div>'
        + btnPanelSeg(mw.lang('Type'), 'button_style', BTN_TYPES, curType)
        + '<div class="mw-btn-panel__section"><div class="mw-btn-panel__label">' + mw.lang('Color') + '</div><div class="mw-btn-panel__swatches">' + swatches + '</div></div>'
        + btnPanelSeg(mw.lang('Size'), 'button_size', BTN_SIZES, curSize)
        + btnPanelSeg(mw.lang('Width'), 'width', [{ label: mw.lang('Fit'), value: '' }, { label: mw.lang('Fill'), value: 'w-100' }], curWidth)
        + btnPanelSeg(mw.lang('Align'), 'align', [{ label: mw.lang('Left'), value: 'left' }, { label: mw.lang('Center'), value: 'center' }, { label: mw.lang('Right'), value: 'right' }], curAlign)
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

    // Position under/near the button.
    var r = el.getBoundingClientRect();
    var off = btnFrameOffset();
    var win = doc.defaultView || window;
    var pw = _btnPanelEl.offsetWidth || 270;
    var left = Math.min(r.left + off.x, win.innerWidth - pw - 10);
    var top = r.bottom + off.y + 8;
    if (top + (_btnPanelEl.offsetHeight || 360) > win.innerHeight - 8) {
        top = Math.max(8, r.top + off.y - (_btnPanelEl.offsetHeight || 360) - 8);
    }
    _btnPanelEl.style.left = Math.round(Math.max(8, left)) + 'px';
    _btnPanelEl.style.top = Math.round(Math.max(8, top)) + 'px';

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
            } else {
                btnSaveOption(el, group, val);
            }
            setActive(group, val);
        });
    });

    _btnPanelEl.querySelectorAll('.mw-btn-panel__sw').forEach(function (sw) {
        sw.addEventListener('click', function () {
            _btnPanelEl.querySelectorAll('.mw-btn-panel__sw').forEach(function (x) { x.classList.remove('active'); });
            sw.classList.add('active');
            btnSaveOption(el, 'backgroundColor', sw.dataset.bg);
            btnSaveOption(el, 'color', sw.dataset.fg);
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
        });
    });

    // Outside-click closes (bound once on the top document).
    if (!_btnPanelDocClick) {
        _btnPanelDocClick = function (ev) {
            if (!_btnPanelEl || _btnPanelEl.style.display !== 'block') { return; }
            if (_btnPanelEl.contains(ev.target)) { return; }
            btnClosePanel();
        };
        setTimeout(function () { doc.addEventListener('click', _btnPanelDocClick, true); }, 0);
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
