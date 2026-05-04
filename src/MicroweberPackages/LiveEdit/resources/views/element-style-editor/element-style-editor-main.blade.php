
@include('admin::layouts.partials.loads-user-custom-fonts')


<script>
    // The Microweber `mw.require` / `mw.top` helpers are defined inside
    // `admin.js`, which is loaded as `type="module"` and therefore has
    // implicit `defer` semantics — its body runs AFTER the page parser
    // hits these inline scripts. Calling `mw.require()` / `mw.top()`
    // synchronously here used to fail with `mw.require is not a
    // function` and `mw.top is not a function`, aborting the whole
    // setup so the Vue style-editor app mounted with `mw.top().app` ===
    // undefined and emitted "Cannot read properties of undefined" in
    // its `mounted()` hook. Same pattern as the `waitForCodeMirror`
    // poll in `render-css-editor.blade.php` (task-2026-04-29-8db524).
    // task-2026-05-04-3337c0.
    (function () {
        var attempts = 0;
        var maxAttempts = 200; // 200 × 50ms = 10s

        function mwReady() {
            return typeof window.mw !== 'undefined'
                && typeof window.mw.require === 'function'
                && typeof window.mw.top === 'function'
                && window.mw.top()
                && window.mw.top().app;
        }

        function init() {
            mw.lib.require('jseldom');
            mw.require('domtree.js');

            window.selectNodeInElementStyleEditorApp = function (ActiveNode) {
                if (ActiveNode) {
                    mw.top().app.dispatch('mw.elementStyleEditor.selectNode', ActiveNode);
                }
            };

            var ActiveSelector = false;
            var ActiveNode = false;
            var OverlayNode = false;
            mw.app = mw.top().app;
            var targetWindow = mw.top().app.canvas.getWindow();
            var targetMw = targetWindow ? targetWindow.mw : null;

            var specialCases = function (property, value) {
                if (!property) return;
                if (property.includes('col-')) {
                    scColumns(property, value);
                    return true;
                } else if (OverlayNode && property === 'overlay-color') {
                    OverlayNode.style.backgroundColor = value;
                    mw.top().app.registerChange(OverlayNode);
                    return true;
                } else if (OverlayNode && property === 'overlay-blend-mode') {
                    OverlayNode.style.mixBlendMode = value;
                    mw.top().app.registerChange(OverlayNode);
                    return true;
                }
            };

            $(document).ready(function () {
                ActiveNode = mw.top().app.liveEdit.getSelectedNode();
                if (ActiveNode) {
                    window.selectNodeInElementStyleEditorApp(ActiveNode);
                }

                window.document.addEventListener('refreshSelectedElement', function (e) {
                    ActiveNode = mw.top().app.liveEdit.getSelectedNode();
                    if (ActiveNode) {
                        window.selectNodeInElementStyleEditorApp(ActiveNode);
                    }
                });

                mw.top().app.on('cssEditorSelectElementBySelector', function (selector) {
                    var canvasDocument = mw.top().app.canvas.getDocument();

                    if (selector) {
                        ActiveNode = canvasDocument.querySelector(selector);
                        if (!ActiveNode) {
                            var newEl = $.jseldom(selector);

                            var holder = canvasDocument.querySelector('#mw-non-existing-temp-element-holder');
                            if (!holder) {
                                holder = canvasDocument.createElement('div');
                                holder.id = 'mw-non-existing-temp-element-holder';
                                holder.style.display = 'none';
                                canvasDocument.body.append(holder);
                            }
                            if (newEl) {
                                holder = canvasDocument.getElementById('mw-non-existing-temp-element-holder');
                                holder.append(newEl[0]);
                            }
                            ActiveNode = canvasDocument.querySelector(selector);
                        }
                        ActiveSelector = selector;
                        window.selectNodeInElementStyleEditorApp(ActiveNode);
                    }
                });
            });

            mw.top().app.on('mw.elementStyleEditor.addClassToNode', function (data) {
                if (data.node) {
                    data.node.classList.add(data.class);
                    mw.top().app.registerChange(data.node);
                }
            });

            mw.top().app.on('mw.elementStyleEditor.removeClassFromNode', function (data) {
                if (data.node) {
                    data.node.classList.remove(data.class);
                    mw.top().app.registerChange(data.node);
                }
            });

            mw.top().app.on('mw.elementStyleEditor.applyCssPropertyToNode', function (data) {
                output(data.prop, data.val, data.node);
            });

            // Now that mw.top().app is confirmed ready, load the Vue
            // app bundle. The bundle's `mounted()` hook calls
            // mw.top().app.on(...) and mw.top().app.canvas.getWindow()
            // synchronously and would otherwise crash with "Cannot
            // read properties of undefined" if it raced ahead of mw.
            // Idempotent — only loads once per page lifetime.
            if (window.__mwElementStyleEditorAppUrl && !window.__mwElementStyleEditorAppLoaded) {
                window.__mwElementStyleEditorAppLoaded = true;
                var s = document.createElement('script');
                s.src = window.__mwElementStyleEditorAppUrl;
                s.type = 'module';
                document.body.appendChild(s);
            }

            var output = function (property, value, ActiveNode) {
                if (ActiveNode) {
                    if (!specialCases(property, value)) {
                        let prop = property.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                        if (ActiveSelector) {
                            mw.top().app.cssEditor.setPropertyForSelector(ActiveSelector, prop, value);
                        }
                        if (ActiveNode.style && ActiveNode.style[prop]) {
                            ActiveNode.style[prop] = '';
                        }
                        mw.top().app.cssEditor.temp(ActiveNode, prop, value);
                    }
                    mw.top().app.registerChange(ActiveNode);

                    if (mw.top().app.liveEdit) {
                        mw.top().app.liveEdit.handles.get('interactionHandle').hide();
                        mw.top().app.liveEdit.handles.reposition();
                    }
                }
            };
        }

        function tick() {
            if (mwReady()) {
                init();
            } else if (++attempts < maxAttempts) {
                setTimeout(tick, 50);
            } else {
                console.error('mw.require/mw.top failed to load within 10s — element style editor will not initialise.');
            }
        }
        tick();
    })();
</script>

<style>
    body {
        background: #fff !important;
    }
</style>


<div>
    <div id="mw-element-style-editor-app">
        Loading ...
    </div>
    <div id="mw-element-style-editor-app-scripts">

        {{-- Vue bundle URL captured for the wait-for-mw loader above
             — stylesheet ships immediately, but the JS module is
             injected ONLY after `mw.top().app` is ready, otherwise the
             Vue `mounted()` hook fires `mw.top().app.on()` against an
             undefined `app` and emits the cascade reported in
             task-2026-05-04-3337c0. --}}
        <link rel="stylesheet" href="{{ asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.css') }}">
        <script>
            window.__mwElementStyleEditorAppUrl = @json(asset('vendor/microweber-packages/frontend-assets/build/element-style-editor-app.js'));
        </script>


    </div>

</div>

