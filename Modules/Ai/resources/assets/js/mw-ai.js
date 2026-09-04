function MwAi() {
    return {
        async init() {
            //todo

        },

        generateImage(messages, options = {}) {
            return new Promise((resolve, reject) => {
                let data = {
                    messages: messages,
                    options: options
                };

                let ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/generateImage',
                    type: 'POST',
                    data: data,
                    dataType: "json",
                };

                const csrf = $('meta[name="csrf-token"]')

                if (csrf.length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": csrf.attr("content")
                    };
                }

                $.post(ajaxSettings)
                    .then(function(res) {
                        if (res.success) {
                            resolve(res);
                        } else {
                            reject('Image generation failed');
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {
                        reject(errorThrown || 'AI Image Generation Error');
                    });
            });
        },

        async sendToChat(messages, options = {}) {
            return new Promise((resolve, reject) => {
                let  data = {
                    messages: messages,
                    options: options
                };

                let  ajaxSettings = {
                    url: mw.settings.site_url + 'api/ai/chat',
                    type: 'POST',
                    data: data,
                    dataType: "json",
               };

               const csrf = $('meta[name="csrf-token"]')

                if ( csrf.length) {
                    ajaxSettings.headers = {
                        "X-CSRF-TOKEN": csrf.attr("content")
                    };
                }

                $.post(ajaxSettings)
                    .then(function(res) {
                        if (res.success && res.data) {
                            resolve(res);
                        } else {
                            reject(res);
                        }
                    })
                    .fail(function(jqXHR, textStatus, errorThrown) {

                        mw.notification.error('Error')
                        reject(errorThrown);


                    });
            })
        },

        // ------------------------------------------------------------------
        // Live-Edit AI: frontend tools + streaming transport
        //
        // The Live-Edit tools are FRONTEND tools: the backend agent (NeuronAI +
        // Kimi) only decides which to call; the actual work runs here, on the
        // live canvas, in real time. The server streams each tool call as a
        // Server-Sent Event ({tool, args}); applyEdit() dispatches it to the
        // matching frontendTools implementation below. Every edit goes through
        // the normal Live-Edit primitives so the existing SAVE button persists
        // it (CSS via cssEditor, content via registerChangedState).
        // ------------------------------------------------------------------

        // Return the live canvas document (the page the user is editing).
        canvasDocument() {
            try {
                if (mw.top().app && mw.top().app.canvas && mw.top().app.canvas.getDocument) {
                    return mw.top().app.canvas.getDocument();
                }
            } catch (e) {}
            // Fallback: if mw-ai runs inside the canvas frame itself.
            return document;
        },

        // Trigger the normal Live-Edit SAVE (content + custom CSS). Used by the
        // save_page tool and by the conversation's auto-save after each AI turn.
        saveCanvas() {
            let done = false;
            try {
                if (mw.liveEditSaveService && typeof mw.liveEditSaveService.save === 'function') {
                    mw.liveEditSaveService.save();
                    done = true;
                }
            } catch (e) {}
            if (!done) {
                try {
                    const btn = mw.top().doc.querySelector('#save-button');
                    if (btn) { btn.click(); done = true; }
                } catch (e) {}
            }
            try { if (mw.top().app.cssEditor) { mw.top().app.cssEditor.publishIfChanged(); } } catch (e) {}
            return done;
        },

        // Reload one (or all) module(s) on the canvas so a server-side edit
        // (module option, custom field) shows without a full page refresh. Uses
        // the canvas frame's mw.reload_module. Pass a module id/element, a CSS
        // selector, or nothing (reload every .module on the page).
        reloadCanvasModule(target) {
            try {
                const doc = this.canvasDocument();
                const win = doc.defaultView || (mw.top().app.canvas.getWindow && mw.top().app.canvas.getWindow());
                const reload = win && win.mw && win.mw.reload_module;
                if (!reload) { return false; }

                let els = [];
                if (!target) {
                    els = Array.from(doc.querySelectorAll('.module[data-type], .module[type]'));
                } else if (typeof target === 'string') {
                    const byId = doc.getElementById(target);
                    els = byId ? [byId] : Array.from(doc.querySelectorAll(target));
                } else if (target && target.nodeType) {
                    els = [target];
                }
                els.forEach(function (el) { try { reload.call(win.mw, el); } catch (e) {} });
                return els.length > 0;
            } catch (e) {
                return false;
            }
        },

        // The id of the content/page currently open in Live Edit (0 if unknown).
        canvasContentId() {
            try {
                const data = mw.top().app.canvas.getLiveEditData();
                if (data && data.content && data.content.id) {
                    return parseInt(data.content.id, 10) || 0;
                }
            } catch (e) {}
            return 0;
        },

        // A cleaned snapshot of the canvas markup to send to the model as
        // context, so it writes selectors that actually exist on the page.
        collectCanvas(maxLen = 12000) {
            let html = '';
            try {
                const doc = this.canvasDocument();
                html = (doc && doc.body) ? doc.body.innerHTML : '';
            } catch (e) {
                html = '';
            }
            if (!html) {
                return '';
            }
            html = html
                .replace(/<script\b[^>]*>[\s\S]*?<\/script>/gi, '')
                .replace(/<style\b[^>]*>[\s\S]*?<\/style>/gi, '')
                .replace(/<svg\b[^>]*>[\s\S]*?<\/svg>/gi, '')
                .replace(/<!--[\s\S]*?-->/g, '')
                .replace(/\s+/g, ' ')
                .trim();
            if (html.length > maxLen) {
                html = html.slice(0, maxLen) + ' …';
            }
            return html;
        },

        // Find the page's main editable content region — where built sections
        // must live so the Live-Edit SAVE persists them. Prefer the biggest
        // .edit[rel][field] content area; fall back to the largest layout, then
        // the canvas body.
        contentRegion() {
            const doc = this.canvasDocument();
            const regions = Array.from(doc.querySelectorAll('.edit[rel][field]'))
                .filter((el) => !el.classList.contains('module'));
            if (regions.length) {
                // Prefer a content field; otherwise the tallest region.
                const byField = regions.filter((el) => (el.getAttribute('field') || '').toLowerCase().indexOf('content') !== -1);
                const pool = byField.length ? byField : regions;
                pool.sort((a, b) => b.getBoundingClientRect().height - a.getBoundingClientRect().height);
                return pool[0];
            }
            const layout = doc.querySelector('.module-layouts, .edit');
            return layout || doc.body;
        },

        // Collect the editable fields/regions + module instances on the canvas so
        // the backend get_edit_fields tool can tell the model where it can write
        // text or drop a module (instead of guessing selectors).
        collectEditFields() {
            const out = [];
            try {
                const doc = this.canvasDocument();
                doc.querySelectorAll('.edit[rel][field]').forEach((el) => {
                    if (el.classList.contains('module')) { return; }
                    out.push({
                        kind: 'region',
                        field: el.getAttribute('field') || '',
                        rel: el.getAttribute('rel') || '',
                        tag: el.tagName.toLowerCase(),
                        id: el.id || '',
                    });
                });
                doc.querySelectorAll('.module[type], .module[data-type], [id^="module-"]').forEach((el) => {
                    out.push({
                        kind: 'module',
                        id: el.id || '',
                        type: el.getAttribute('type') || el.getAttribute('data-type') || '',
                    });
                });
            } catch (e) {}
            return out.slice(0, 200);
        },

        // Escape a plain string for safe insertion into HTML text.
        escapeHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
        },

        // Sanitise AI-authored section HTML before it touches the canvas: no
        // scripts, styles, iframes, Microweber <module> tags, or inline event
        // handlers / javascript: URLs. Returns a safe HTML string.
        sanitizeSectionHtml(html) {
            const doc = this.canvasDocument();
            const tmp = doc.createElement('div');
            tmp.innerHTML = String(html || '');
            tmp.querySelectorAll('script,style,iframe,object,embed,link,module').forEach((n) => n.remove());
            tmp.querySelectorAll('*').forEach((el) => {
                Array.from(el.attributes).forEach((attr) => {
                    const name = attr.name.toLowerCase();
                    const val = String(attr.value || '');
                    if (name.indexOf('on') === 0) { el.removeAttribute(attr.name); return; }
                    if ((name === 'href' || name === 'src') && /^\s*javascript:/i.test(val)) {
                        el.removeAttribute(attr.name);
                    }
                });
            });
            return tmp.innerHTML;
        },

        // Add !important to every declaration in a flat CSS string so AI styles
        // win the cascade. Nested at-rule blocks (@media etc.) contain braces and
        // are left untouched by the rule regex — acceptable, they are rare.
        forceImportant(css) {
            return String(css).replace(/([^{}]+)\{([^{}]*)\}/g, function (m, sel, body) {
                const decls = body.split(';').map(function (d) {
                    d = d.trim();
                    if (!d) { return ''; }
                    if (/!important\s*$/i.test(d)) { return d; }
                    return d + ' !important';
                }).filter(Boolean).join('; ');
                return sel.trim() + ' { ' + decls + ' }';
            });
        },

        // Inject AI CSS into a single global <style> kept LAST in the canvas head
        // (so it wins by source order). Accumulates across the session.
        injectGlobalCss(css) {
            const doc = this.canvasDocument();
            let el = doc.getElementById('mw-ai-global-css');
            if (!el) {
                el = doc.createElement('style');
                el.id = 'mw-ai-global-css';
            }
            el.appendChild(doc.createTextNode('\n' + css));
            doc.head.appendChild(el); // move/keep last
        },

        // Persist the accumulated global AI CSS to the template's custom CSS file
        // so the design is global and survives SAVE. Reads the current file,
        // replaces any previous AI block (sentinel-marked) and appends the fresh
        // one after the template's own rules. Debounced + best-effort.
        persistGlobalCss() {
            const self = this;
            clearTimeout(self._persistCssTimer);
            self._persistCssTimer = setTimeout(function () { self._doPersistGlobalCss(); }, 800);
        },

        async _doPersistGlobalCss() {
            try {
                const doc = this.canvasDocument();
                const el = doc.getElementById('mw-ai-global-css');
                if (!el || !el.textContent.trim()) { return; }

                const editor = mw.top().app && mw.top().app.cssEditor;
                const settings = (editor && editor.settings) ? editor.settings : {};
                const saveUrl = settings.saveUrl || (mw.settings.api_url + 'current_template_save_custom_css');
                const cssUrl = settings.cssUrl;

                let base = '';
                if (cssUrl) {
                    try { base = await fetch(cssUrl, { cache: 'no-store' }).then(function (r) { return r.text(); }); } catch (e) {}
                }
                // The AI block is CUMULATIVE across pages. On the first persist of
                // this page load, seed the (freshly-created) style element with the
                // block already saved by earlier pages/turns, so styling this page
                // ADDS to the site design instead of replacing it. After seeding,
                // the element is the single source of truth and we just re-save it.
                const prev = (base.match(/\/\* MW-AI-CSS-START \*\/([\s\S]*?)\/\* MW-AI-CSS-END \*\//) || [null, ''])[1].trim();
                if (el.dataset.seeded !== '1') {
                    if (prev) { el.textContent = prev + '\n' + el.textContent; }
                    el.dataset.seeded = '1';
                }
                const aiCss = el.textContent.trim();
                if (!aiCss) { return; }

                base = base.replace(/\/\* MW-AI-CSS-START \*\/[\s\S]*?\/\* MW-AI-CSS-END \*\//g, '').trim();
                const combined = base + '\n\n/* MW-AI-CSS-START */\n' + aiCss + '\n/* MW-AI-CSS-END */\n';

                const data = { css_file_content: combined };
                try {
                    const led = mw.top().app.canvas.getLiveEditData();
                    if (led && led.template_name) { data.active_site_template = led.template_name; }
                    if (led && led.content) { data.content_id = led.content.id; }
                } catch (e) {}

                const ajax = { url: saveUrl, type: 'POST', data: data };
                const csrf = $('meta[name="csrf-token"]');
                if (csrf.length) { ajax.headers = { 'X-CSRF-TOKEN': csrf.attr('content') }; }
                await $.ajax(ajax);
            } catch (e) {}
        },

        // Very small flat-CSS parser: "sel { a:1; b:2 } sel2 { c:3 }" ->
        // [{selector, props:{a:'1', b:'2'}}, ...]. Rules containing nested "{"
        // (e.g. @media) are returned raw so applyCss can inject them verbatim.
        parseCss(css) {
            const rules = [];
            const raw = [];
            const re = /([^{}]+)\{([^{}]*)\}/g;
            let m;
            let matchedTo = 0;
            while ((m = re.exec(css)) !== null) {
                const selector = m[1].trim();
                const body = m[2].trim();
                if (!selector) { continue; }
                if (/@/.test(selector) || selector.indexOf('{') !== -1) {
                    raw.push(m[0]);
                    matchedTo = re.lastIndex;
                    continue;
                }
                const props = {};
                body.split(';').forEach(function(decl) {
                    const idx = decl.indexOf(':');
                    if (idx > 0) {
                        const prop = decl.slice(0, idx).trim();
                        const val = decl.slice(idx + 1).trim();
                        if (prop && val) { props[prop] = val; }
                    }
                });
                if (Object.keys(props).length) {
                    rules.push({ selector: selector, props: props });
                }
                matchedTo = re.lastIndex;
            }
            // Anything the rule regex could not structure (at-rules etc.) keeps
            // its visual effect via a raw injected block.
            if (matchedTo < css.length && /@/.test(css.slice(matchedTo))) {
                raw.push(css.slice(matchedTo));
            }
            return { rules: rules, raw: raw };
        },

        // The frontend tool implementations. Keyed by the backend tool name.
        frontendTools: {
            apply_css: function(args, api) {
                const css = (args && args.css) ? String(args.css) : '';
                if (!css.trim()) { return { ok: false, message: 'empty css' }; }

                // Full-scope design: write to the GLOBAL Live-Edit custom CSS, not
                // the per-region temp style. We (1) inject it into a single global
                // <style> appended LAST in the canvas <head> — so at equal
                // specificity it beats the template's own rules (e.g. the shipped
                // `.btn{…!important}`) by source order — and (2) persist it to the
                // template's custom CSS file so it is global and survives SAVE.
                const withImportant = api.forceImportant(css);
                api.injectGlobalCss(withImportant);
                api.persistGlobalCss();
                return { ok: true, message: 'applied global css' };
            },

            set_text: function(args, api) {
                const selector = (args && args.selector) ? String(args.selector) : '';
                const text = (args && typeof args.text !== 'undefined') ? String(args.text) : '';
                if (!selector) { return { ok: false, message: 'no selector' }; }
                const doc = api.canvasDocument();
                const el = doc.querySelector(selector);
                if (!el) { return { ok: false, message: 'no element for ' + selector }; }
                el.textContent = text;
                try { mw.top().app.registerChangedState(el); } catch (e) {}
                return { ok: true, message: 'text updated' };
            },

            set_image: function(args, api) {
                const selector = (args && args.selector) ? String(args.selector) : '';
                const url = (args && args.url) ? String(args.url) : '';
                if (!selector || !url) { return { ok: false, message: 'selector+url required' }; }
                const doc = api.canvasDocument();
                let el = doc.querySelector(selector);
                if (!el) { return { ok: false, message: 'no element for ' + selector }; }
                if (el.tagName !== 'IMG') {
                    const inner = el.querySelector('img');
                    if (inner) { el = inner; }
                }
                if (el.tagName === 'IMG') {
                    el.src = url;
                    el.removeAttribute('srcset');
                } else {
                    el.style.backgroundImage = 'url("' + url + '")';
                }
                try { mw.top().app.registerChangedState(el); } catch (e) {}
                return { ok: true, message: 'image updated' };
            },

            add_section: function(args, api) {
                const html = (args && args.html) ? String(args.html) : '';
                if (!html.trim()) { return { ok: false, message: 'no html' }; }
                const doc = api.canvasDocument();
                const region = api.contentRegion();
                if (!region) { return { ok: false, message: 'no editable content region on this page' }; }

                // Dedup guard: small local models sometimes call add_section twice
                // for the same section. Skip if a section with the same text is
                // already on the page so duplicates never reach the canvas.
                const norm = function (s) { return String(s || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim().toLowerCase().slice(0, 240); };
                const incomingText = norm(html);
                if (incomingText.length > 20) {
                    const dup = Array.from(region.querySelectorAll('.mw-ai-built-section'))
                        .some(function (s) { return norm(s.textContent) === incomingText; });
                    if (dup) { return { ok: true, message: 'duplicate section skipped' }; }
                }

                // Build the node. If the AI already provided a single block-level
                // root (e.g. <section class="hero">), use it directly so its own
                // class is the section handle — avoids <section><section> nesting
                // and keeps the AI's selectors matching the top-level element.
                const tmp = doc.createElement('div');
                tmp.innerHTML = api.sanitizeSectionHtml(html);
                const roots = Array.from(tmp.children);
                const blockTags = ['SECTION', 'DIV', 'HEADER', 'FOOTER', 'ARTICLE', 'ASIDE', 'MAIN', 'NAV'];
                let section;
                if (roots.length === 1 && blockTags.indexOf(roots[0].tagName) !== -1) {
                    section = roots[0];
                } else {
                    section = doc.createElement('section');
                    while (tmp.firstChild) { section.appendChild(tmp.firstChild); }
                }
                section.classList.add('mw-ai-built-section');
                section.id = 'mw-ai-sec-' + Math.floor(Math.random() * 1e9).toString(36);

                const position = (args && args.position === 'prepend') ? 'prepend' : 'append';
                if (position === 'prepend' && region.firstChild) {
                    region.insertBefore(section, region.firstChild);
                } else {
                    region.appendChild(section);
                }

                try { mw.top().app.registerChangedState(section); } catch (e) {}

                // Atomic build+style: if the model passed css with the section,
                // apply it now (via the same !important path) so the section looks
                // right even if the model never makes a separate apply_css call.
                let styled = '';
                if (args && args.css && String(args.css).trim()) {
                    const r = api.frontendTools.apply_css({ css: String(args.css) }, api);
                    if (r && r.ok) { styled = ' + styled'; }
                }
                return { ok: true, message: 'section added (' + section.children.length + ' blocks)' + styled };
            },

            insert_module: function(args, api) {
                const type = (args && args.type) ? String(args.type).trim() : '';
                if (!type) { return { ok: false, message: 'no module type' }; }
                const region = api.contentRegion();
                if (!region) { return { ok: false, message: 'no editable content region' }; }
                const position = (args && args.position === 'top') ? 'top' : 'bottom';
                try {
                    const app = mw.top().app;
                    const edit = mw.top().tools.firstParentOrCurrentWithClass(region, 'edit') || region;
                    app.registerChangedState(edit, true);
                    // insertModule may be async; apply optimistically and finalise on resolve.
                    const p = app.editor.insertModule(type, {}, position, region);
                    Promise.resolve(p).then(function (itm) {
                        try {
                            if (itm) {
                                if (!itm.id) { itm.id = 'mw-ai-mod-' + Math.floor(Math.random() * 1e9).toString(36); }
                                mw.top().app._mwAiLastModule = { id: itm.id, type: type };
                            }
                            app.registerChangedState(edit, true);
                        } catch (e) {}
                    });
                    return { ok: true, message: 'inserting module "' + type + '"' };
                } catch (e) {
                    return { ok: false, message: String(e && e.message || e) };
                }
            },

            // Insert a ready-made TEMPLATE layout using the same editor call the
            // "Insert layout" modal uses (ListLayouts.vue -> insertLayout):
            // mw.app.editor.insertLayout({template}, location, target). The layout
            // list is NOT hardcoded — the model discovers it via get_layouts.
            insert_layout: function(args, api) {
                const template = (args && args.template) ? String(args.template).trim() : '';
                if (!template) { return { ok: false, message: 'no layout template (call get_layouts first)' }; }
                const region = api.contentRegion();
                if (!region) { return { ok: false, message: 'no editable content region' }; }
                try {
                    const app = mw.top().app;
                    const edit = mw.top().tools.firstParentOrCurrentWithClass(region, 'edit') || region;
                    app.registerChangedState(edit, true);
                    const location = (args && args.position === 'top') ? 'top' : 'bottom';
                    // target: the current layout container if one is known, else the
                    // content region — the same target the modal passes.
                    const target = (app.liveEdit && app.liveEdit.layoutHandle && app.liveEdit.layoutHandle.getTarget())
                        || edit;
                    const p = app.editor.insertLayout({ template: template }, location, target);
                    Promise.resolve(p).then(function () {
                        try { app.registerChangedState(edit, true); } catch (e) {}
                    });
                    return { ok: true, message: 'inserting layout "' + template + '"' };
                } catch (e) {
                    return { ok: false, message: String(e && e.message || e) };
                }
            },

            // Server-side read tools. Nothing for the canvas to apply — the
            // returned JSON is shown as a chip / used by the model.
            get_module_settings: function() { return { ok: true, message: 'module settings read' }; },
            get_modules: function() { return { ok: true, message: 'modules listed' }; },
            get_layouts: function() { return { ok: true, message: 'layouts listed' }; },
            get_dom: function() { return { ok: true, message: 'dom read' }; },
            get_edit_fields: function() { return { ok: true, message: 'edit fields read' }; },

            // Server-side: add_form_field writes a CustomField definition on the
            // module. Reload the affected module on the canvas so the new field
            // renders in the form without a full page refresh.
            add_form_field: function(args, api) {
                const id = (args && args.module_id) ? String(args.module_id) : '';
                setTimeout(function () {
                    try {
                        api.reloadCanvasModule(id
                            ? ('#' + id + ', [id="' + id + '"]')
                            : '.module[data-type="contact_form"]');
                    } catch (e) {}
                }, 300);
                return { ok: true, message: 'form field added (reloading form)' };
            },

            set_module_option: function(args, api) {
                const key = (args && args.key) ? String(args.key) : '';
                const value = (args && typeof args.value !== 'undefined') ? args.value : '';
                if (!key) { return { ok: false, message: 'no option key' }; }
                const last = mw.top().app._mwAiLastModule || {};
                const group = (args && args.module_id) ? String(args.module_id) : (last.id || '');
                if (!group) { return { ok: false, message: 'no module to configure (insert one first or pass module_id)' }; }
                try {
                    const opt = { group: group, key: key, value: value };
                    if (last.type) { opt.module = last.type; }
                    mw.top().options.saveOption(opt);
                    // Reload the module on the canvas so the new setting shows.
                    setTimeout(function () { try { api.reloadCanvasModule(group); } catch (e) {} }, 250);
                    return { ok: true, message: 'set ' + key + ' on module ' + group + ' (reloading)' };
                } catch (e) {
                    return { ok: false, message: String(e && e.message || e) };
                }
            },

            // set_custom_field runs server-side (writes content-data). Reload the
            // product/shop modules on the canvas so the new value shows.
            set_custom_field: function(args, api) {
                setTimeout(function () {
                    try {
                        api.reloadCanvasModule('.module[data-type="shop"], .module[data-type="products"], .module[data-type="shop/products"]');
                    } catch (e) {}
                }, 300);
                return { ok: true, message: 'custom field saved (reloading modules)' };
            },

            save_page: function(args, api) {
                const ok = api.saveCanvas();
                return { ok: ok, message: ok ? 'saved' : 'save unavailable' };
            },

            navigate_to_page: function(args, api) {
                const raw = (args && args.url) ? String(args.url).trim() : '';
                if (!raw) { return { ok: false, message: 'no url' }; }
                let full = raw;
                if (!/^https?:/i.test(raw)) {
                    const base = String(mw.settings.site_url || '').replace(/\/+$/, '');
                    full = (raw === '/' || raw === '') ? base + '/' : base + '/' + raw.replace(/^\/+/, '');
                }
                try {
                    // Save before leaving so nothing is lost, then load the page.
                    try { api.saveCanvas(); } catch (e) {}
                    mw.top().app.canvas.setUrl(full);
                    return { ok: true, message: 'navigating to ' + raw };
                } catch (e) {
                    return { ok: false, message: String(e && e.message || e) };
                }
            },

            // These run server-side (create_content/create_post/add_menu_item do
            // real DB work; generate_image needs a provider). Nothing for the
            // canvas to apply — the SSE frame is just shown as a chip.
            create_content: function() { return { ok: true, message: 'page created' }; },
            create_post: function() { return { ok: true, message: 'post created' }; },
            add_menu_item: function() { return { ok: true, message: 'menu updated' }; },
            get_menu: function() { return { ok: true, message: 'menu read' }; },
            edit_menu_item: function() { return { ok: true, message: 'menu item edited' }; },
            generate_image: function() {
                return { ok: true, message: 'image generated (model will place it)' };
            },

            get_page_context: function() {
                return { ok: true, message: 'context read' };
            }
        },

        // Apply one streamed tool call to the live canvas.
        applyEdit(edit) {
            if (!edit || !edit.tool) { return { ok: false, message: 'no tool' }; }
            const impl = this.frontendTools[edit.tool];
            if (!impl) { return { ok: false, message: 'unknown tool ' + edit.tool }; }
            try {
                return impl(edit.args || {}, this);
            } catch (e) {
                return { ok: false, message: String(e && e.message || e) };
            }
        },

        // Streaming Live-Edit chat. Opens the SSE endpoint, applies each tool
        // call to the canvas as it arrives, and calls the provided handlers.
        //
        //   handlers = {
        //     onStart(data), onTool(edit, result), onError(msg), onDone(data)
        //   }
        async agentChatStream(message, options = {}, handlers = {}) {
            const self = this;
            const body = {
                message: message,
                agent_type: options.agent_type || 'liveedit',
                content_id: (typeof options.content_id !== 'undefined')
                    ? options.content_id : self.canvasContentId(),
                canvas_html: (typeof options.canvas_html !== 'undefined')
                    ? options.canvas_html : self.collectCanvas()
            };
            if (options.chat_id) { body.chat_id = options.chat_id; }
            if (options.chat_title) { body.chat_title = options.chat_title; }
            // Surface the last module inserted this session so the backend can tell
            // the model which module id to target with get_module_settings /
            // set_module_option / add_form_field (the SSE stream is one-way).
            try {
                const lm = mw.top().app._mwAiLastModule;
                if (lm && lm.id) { body.last_module = { id: lm.id, type: lm.type || '' }; }
            } catch (e) {}
            // The editable regions/modules on the canvas, so the get_edit_fields
            // tool can tell the model exactly where it can act.
            try {
                const ef = self.collectEditFields();
                if (ef && ef.length) { body.edit_fields = ef; }
            } catch (e) {}
            // Optional canvas screenshot so the backend vision model can describe
            // the current design to the (text-only) editing model.
            if (options.screenshot) { body.screenshot = options.screenshot; }
            // Optional reference images (pasted/attached design screenshots) the
            // user wants the AI to recreate. The backend reads them with the
            // vision model into a build spec for the editing model.
            if (options.reference_images && options.reference_images.length) {
                body.reference_images = options.reference_images;
            }

            const headers = { 'Content-Type': 'application/json', 'Accept': 'text/event-stream' };
            const csrf = $('meta[name="csrf-token"]');
            if (csrf.length) { headers['X-CSRF-TOKEN'] = csrf.attr('content'); }

            const res = await fetch(mw.settings.site_url + 'api/ai/agent-chat-stream', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(body),
                credentials: 'same-origin'
            });

            if (!res.ok || !res.body) {
                const msg = 'AI stream failed (' + res.status + ')';
                if (handlers.onError) { handlers.onError(msg); }
                throw new Error(msg);
            }

            const reader = res.body.getReader();
            const decoder = new TextDecoder();
            let buffer = '';
            let done = null;

            const dispatch = function(eventName, dataStr) {
                let data = {};
                try { data = dataStr ? JSON.parse(dataStr) : {}; } catch (e) { data = { raw: dataStr }; }
                if (eventName === 'start') {
                    if (handlers.onStart) { handlers.onStart(data); }
                } else if (eventName === 'vision') {
                    if (handlers.onVision) { handlers.onVision(data); }
                } else if (eventName === 'reference') {
                    if (handlers.onReference) { handlers.onReference(data); }
                } else if (eventName === 'tool') {
                    const result = self.applyEdit(data);
                    if (handlers.onTool) { handlers.onTool(data, result); }
                } else if (eventName === 'error') {
                    if (handlers.onError) { handlers.onError(data.message || 'error'); }
                } else if (eventName === 'done') {
                    done = data;
                    if (handlers.onDone) { handlers.onDone(data); }
                }
            };

            // Parse the SSE stream frame by frame ("event:"/"data:" blocks).
            for (;;) {
                const chunk = await reader.read();
                if (chunk.done) { break; }
                buffer += decoder.decode(chunk.value, { stream: true });

                let sep;
                while ((sep = buffer.indexOf('\n\n')) !== -1) {
                    const frame = buffer.slice(0, sep);
                    buffer = buffer.slice(sep + 2);
                    let eventName = 'message';
                    const dataLines = [];
                    frame.split('\n').forEach(function(line) {
                        if (line.indexOf('event:') === 0) {
                            eventName = line.slice(6).trim();
                        } else if (line.indexOf('data:') === 0) {
                            dataLines.push(line.slice(5).trim());
                        }
                    });
                    dispatch(eventName, dataLines.join('\n'));
                }
            }

            return done || { response: '', edits: [] };
        }
    }
}
