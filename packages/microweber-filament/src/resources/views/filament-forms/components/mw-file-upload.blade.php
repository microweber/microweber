{{--
    Global image uploader (single). Redesigned to the 6-state spec:
      1 EMPTY · 2 DRAGGING OVER · 3 UPLOADING · 4 FILLED · 5 ERROR · 6 COMPACT.

    Real HTML5 drag-and-drop + inline upload to api/media/upload (base64 path, so
    SVG/WEBP are accepted) with a live XHR progress bar + Cancel, typed validation
    errors, and a "Media library" button that opens the MW file picker. State is a
    single URL string entangled with Livewire (unchanged shape — every consumer,
    Logo/Image/Favicon, keeps working).

    Accessibility (carries the AI-1203 fixes): every icon-only control has an
    aria-label + title; decorative SVGs are aria-hidden + focusable=false.
--}}
@php
    $id = $getId();
    $statePath = $getStatePath();
    $exts = $getAcceptExtensions();
    $maxMb = $getMaxSizeMb();
    $compact = $isCompact();
    $accept = implode(',', array_map(fn ($e) => '.' . ltrim($e, '.'), $exts));
    $typesDisplay = strtoupper(implode(', ', array_slice($exts, 0, 3)));

    // Noun for the empty-state copy + header — "image" only when every accepted
    // extension is an image type, otherwise the generic "file" (audio, PDF, …).
    $fileNoun = $getFileNoun();
    $nounHeader = ucfirst($fileNoun);
    $nounArticle = $fileNoun === 'image' ? 'an' : 'a';
    $dropTitle = __('Drop ' . $nounArticle . ' ' . $fileNoun . ' here');
    $addTitle = __('Add ' . $nounArticle . ' ' . $fileNoun);

    $lblRemove = __('Remove');
    $lblEdit = __('Edit');
    $lblReplace = __('Replace');
    $lblChange = __('Change');
    $lblCancel = __('Cancel');

    // Config passed to the Alpine factory as base64 JSON — pure [A-Za-z0-9+/=], so
    // it is safe inside the double-quoted x-data attribute (no quote/apostrophe
    // from translations can terminate the attribute).
    $uploaderConfig = base64_encode(json_encode([
        'exts' => array_values($exts),
        'maxBytes' => $maxMb * 1024 * 1024,
        'compact' => (bool) $compact,
        'typeErr' => __("That file type isn't supported. Use ") . $typesDisplay . '.',
        'sizeErr' => __('File is too large. Max ') . $maxMb . ' MB.',
        'uploadErr' => __('Upload failed. Please try again.'),
    ]));
@endphp

{{-- The factory + layout CSS are wrapped in Livewire's @assets so they load once
     at page level and — crucially — still execute when this field arrives inside a
     Livewire/Filament modal via AJAX morph (morphdom does NOT run injected <script>
     tags, so a bare inline <script> left window.mwFileUploader undefined in Live
     Edit's module-settings modal, and x-data init silently failed → blank body). --}}
@assets
<script>
    window.mwFileUploader = window.mwFileUploader || function (stateProxy, cfg) {
        return {
            state: stateProxy,          // single URL string ('' = empty)
            ui: 'idle',                 // idle | dragging | uploading | error
            progress: 0,
            fileName: '',
            fileMeta: '',
            errorName: '',
            errorMsg: '',
            thumb: '',
            _xhr: null,
            cfg: cfg,

            init() {
                this.syncFromState();
                this.$watch('state', () => { if (this.ui !== 'uploading') { this.syncFromState(); } });
            },
            get isFilled() { return this.ui === 'idle' && !!this.state; },
            get isEmpty() { return (this.ui === 'idle' || this.ui === 'dragging') && !this.state; },

            syncFromState() {
                if (this.state && typeof this.state === 'string') {
                    this.thumb = this.state;
                    this.fileName = this.state.split('?')[0].split('/').pop();
                    this.loadMeta(this.state);
                } else {
                    this.thumb = ''; this.fileName = ''; this.fileMeta = '';
                }
            },
            loadMeta(url, sizeBytes) {
                var self = this;
                var img = new Image();
                img.onload = function () {
                    var dims = img.naturalWidth + '×' + img.naturalHeight;
                    var ext = (self.fileName.split('.').pop() || '').toUpperCase();
                    self.fileMeta = dims + ' · ' + ext + (sizeBytes ? (' · ' + self.humanSize(sizeBytes)) : '');
                };
                img.onerror = function () { self.fileMeta = ''; };
                img.src = url;
            },
            humanSize(b) {
                if (!b) return '';
                if (b < 1024) return b + ' B';
                if (b < 1048576) return Math.round(b / 1024) + ' KB';
                return (Math.round(b / 104857.6) / 10) + ' MB';
            },
            validExt(name) {
                var ext = (name.split('.').pop() || '').toLowerCase();
                return this.cfg.exts.indexOf(ext) !== -1;
            },

            openLibrary() {
                var self = this;
                try {
                    mw.filePickerDialog({ pickerOptions: { multiple: false, type: 'image' } }, function (url) {
                        var u = Array.isArray(url) ? url[0] : url;
                        if (typeof u === 'string' && u !== '') { self.ui = 'idle'; self.state = u; }
                    });
                } catch (e) {}
            },
            openUpload() { this.$refs.fileInput && this.$refs.fileInput.click(); },
            onPick(ev) {
                var f = ev.target.files && ev.target.files[0];
                if (f) { this.handleFile(f); }
                ev.target.value = '';
            },

            onDragOver(ev) { ev.preventDefault(); if (this.ui === 'idle') { this.ui = 'dragging'; } },
            onDragLeave(ev) { if (this.ui === 'dragging') { this.ui = 'idle'; } },
            onDrop(ev) {
                ev.preventDefault();
                if (this.ui === 'dragging') { this.ui = 'idle'; }
                var f = ev.dataTransfer && ev.dataTransfer.files && ev.dataTransfer.files[0];
                if (f) { this.handleFile(f); }
            },

            handleFile(f) {
                if (!this.validExt(f.name)) {
                    return this.fail(f.name, this.cfg.typeErr);
                }
                if (f.size > this.cfg.maxBytes) {
                    return this.fail(f.name, this.cfg.sizeErr);
                }
                this.upload(f);
            },
            upload(f) {
                var self = this;
                this.ui = 'uploading'; this.progress = 0; this.fileName = f.name; this.thumb = '';
                try { this.thumb = URL.createObjectURL(f); } catch (e) {}
                var reader = new FileReader();
                reader.onload = function (e) {
                    var xhr = new XMLHttpRequest();
                    self._xhr = xhr;
                    xhr.open('POST', mw.settings.api_url + 'media/upload');
                    var csrf = document.querySelector('meta[name="csrf-token"]');
                    if (csrf) { xhr.setRequestHeader('X-CSRF-TOKEN', csrf.getAttribute('content')); }
                    xhr.upload.onprogress = function (ev) {
                        if (ev.lengthComputable) { self.progress = Math.round((ev.loaded / ev.total) * 100); }
                    };
                    xhr.onload = function () {
                        self._xhr = null;
                        var res = null;
                        try { res = JSON.parse(xhr.responseText); } catch (err) {}
                        if (res && res.src) {
                            self.ui = 'idle';
                            self.state = res.src;              // entangles to Livewire
                            self.loadMeta(res.src, f.size);
                        } else {
                            self.fail(f.name, self.cfg.uploadErr);
                        }
                    };
                    xhr.onerror = function () { self._xhr = null; self.fail(f.name, self.cfg.uploadErr); };
                    var fd = new FormData();
                    fd.append('file', e.target.result);        // base64 data URL
                    fd.append('name', f.name);
                    xhr.send(fd);
                };
                reader.onerror = function () { self.fail(f.name, self.cfg.uploadErr); };
                reader.readAsDataURL(f);
            },
            cancelUpload() {
                if (this._xhr) { try { this._xhr.abort(); } catch (e) {} this._xhr = null; }
                this.ui = 'idle'; this.progress = 0;
                this.syncFromState();
            },
            fail(name, msg) { this.ui = 'error'; this.errorName = name; this.errorMsg = msg; this.progress = 0; },
            tryAnother() { this.ui = 'idle'; this.errorName = ''; this.errorMsg = ''; this.openUpload(); },
            clear() { this.ui = 'idle'; this.state = ''; this.syncFromState(); },
        };
    };
</script>

{{-- Layout-critical CSS shipped inline: the Filament theme's Tailwind build purges
     utilities that aren't already referenced elsewhere (flex-wrap, basis-*,
     whitespace-nowrap), so relying on them for structure silently no-ops inside a
     narrow grid column. These own-namespace rules guarantee the empty row wraps
     and the label never breaks word-by-word regardless of what got purged. --}}
<style>
    /* Structural spacing is owned here, not via Tailwind spacing utilities
       (padding and gap): the Filament theme build purges the ones not
       referenced elsewhere, so the card rendered with 0 padding inside the
       Live Edit modal. */
    .mw-uploader{padding:1rem;}
    .mw-uploader.mw-uploader--compact{padding:.75rem;}
    .mw-uploader .mw-up-head{display:flex;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.625rem;}
    .mw-uploader.mw-uploader--compact .mw-up-head{margin-bottom:.5rem;}
    .mw-uploader .mw-up-row{display:flex;flex-wrap:wrap;align-items:center;gap:.75rem;padding:1rem;}
    .mw-uploader.mw-uploader--compact .mw-up-row{padding:.625rem;}
    .mw-uploader .mw-up-info{display:flex;align-items:center;gap:.75rem;flex:1 1 10rem;min-width:0;}
    .mw-uploader .mw-up-title{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .mw-uploader .mw-up-actions{flex:none;display:flex;align-items:center;gap:.5rem;}
    /* filled / uploading / error single rows */
    .mw-uploader .mw-up-state{display:flex;align-items:center;gap:.75rem;}
    .mw-uploader .mw-up-error{padding:.75rem;}
    .mw-uploader .mw-up-drag{padding:2rem 1rem;}
    .mw-uploader.mw-uploader--compact .mw-up-drag{padding:1.25rem 1rem;}
    /* Buttons: sizing and colours owned here too — the Tailwind padding, height
       and text-size utilities are among the purged ones, so the controls
       otherwise had no padding or height and collapsed onto each other. */
    .mw-uploader .mw-up-btn{display:inline-flex;align-items:center;justify-content:center;height:2.25rem;padding:0 .75rem;border-radius:.5rem;border:1px solid transparent;background:transparent;font-size:.875rem;font-weight:500;line-height:1;white-space:nowrap;cursor:pointer;transition:background-color .12s,opacity .12s;}
    .mw-uploader.mw-uploader--compact .mw-up-btn{height:2rem;padding:0 .625rem;font-size:.8125rem;}
    .mw-uploader .mw-up-btn--ghost{border-color:#e5e7eb;background:#fff;color:#374151;}
    .mw-uploader .mw-up-btn--ghost:hover{background:#f9fafb;}
    .mw-uploader .mw-up-btn--primary{background:#111827;color:#fff;}
    .mw-uploader .mw-up-btn--primary:hover{opacity:.9;}
    .mw-uploader .mw-up-btn--icon{width:2rem;height:2rem;padding:0;color:#9ca3af;}
    .mw-uploader .mw-up-btn--icon:hover{background:#f9fafb;color:#374151;}
    .dark .mw-uploader .mw-up-btn--ghost{border-color:rgba(255,255,255,.15);background:rgba(255,255,255,.05);color:#e5e7eb;}
    .dark .mw-uploader .mw-up-btn--ghost:hover{background:rgba(255,255,255,.1);}
    .dark .mw-uploader .mw-up-btn--primary{background:#fff;color:#111827;}
    .dark .mw-uploader .mw-up-btn--icon:hover{background:rgba(255,255,255,.1);color:#fff;}
</style>
@endassets

<div>
    {{-- The card is self-contained (its header carries the field label), matching
         the mockup — so no separate outer field <label> here, which would print
         the label a second time above the card. --}}
    <div
        x-data="mwFileUploader($wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}, JSON.parse(atob('{{ $uploaderConfig }}')))"
        x-on:dragover="onDragOver($event)"
        x-on:dragleave="onDragLeave($event)"
        x-on:drop="onDrop($event)"
        class="mw-uploader rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5"
        :class="{ 'mw-uploader--compact': cfg.compact }"
    >
        <input type="file" x-ref="fileInput" class="hidden" accept="{{ $accept }}" x-on:change="onPick($event)" />

        {{-- Header: field label + type/size hint (the card's only label) --}}
        <div class="mw-up-head">
            <span class="text-[13px] font-medium text-gray-700 dark:text-gray-200">{{ $getLabel() ?: __($nounHeader) }}</span>
            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $typesDisplay }} &middot; {{ __('up to') }} {{ $maxMb }} MB</span>
        </div>

        {{-- 1 · EMPTY / 2 · DRAGGING.
             Layout uses flex-wrap (not a viewport sm: breakpoint) so it degrades
             gracefully inside a narrow grid column: the buttons drop below the
             label instead of crushing the text to one-word-per-line. --}}
        <div x-show="isEmpty" x-cloak
             class="rounded-lg border border-dashed transition-colors"
             :class="ui === 'dragging' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-500/10' : 'border-gray-300 dark:border-white/15'">
            {{-- dragging label --}}
            <div x-show="ui === 'dragging'" class="mw-up-drag flex flex-col items-center justify-center gap-1 text-indigo-600 dark:text-indigo-300">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M12 5v14M6 13l6 6 6-6"/></svg>
                <span class="text-sm font-medium">{{ __('Release to upload') }}</span>
            </div>
            {{-- idle empty --}}
            <div x-show="ui !== 'dragging'" class="mw-up-row">
                <div class="mw-up-info">
                    <span class="flex-none rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-400" :class="cfg.compact ? 'w-8 h-8' : 'w-9 h-9'">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </span>
                    <div class="min-w-0">
                        <div class="mw-up-title text-sm font-semibold text-gray-800 dark:text-gray-100" x-text="cfg.compact ? '{{ $addTitle }}' : '{{ $dropTitle }}'"></div>
                        <div x-show="!cfg.compact" class="text-xs text-gray-400">{{ __('or pick one below') }}</div>
                    </div>
                </div>
                <div class="mw-up-actions">
                    <button type="button" x-on:click="openLibrary()" class="mw-up-btn mw-up-btn--ghost"
                        x-text="cfg.compact ? '{{ __('Choose') }}' : '{{ __('Media library') }}'"></button>
                    <button type="button" x-on:click="openUpload()" class="mw-up-btn mw-up-btn--primary">{{ __('Upload') }}</button>
                </div>
            </div>
        </div>

        {{-- 3 · UPLOADING --}}
        <div x-show="ui === 'uploading'" x-cloak class="mw-up-state flex items-center gap-3">
            <span class="flex-none w-16 h-14 rounded-md bg-gray-100 dark:bg-white/10 bg-center bg-cover" :style="thumb ? ('background-image:url(' + thumb + ')') : ''"></span>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <span class="text-sm text-gray-800 dark:text-gray-100 truncate" x-text="fileName"></span>
                    <span class="text-xs text-gray-400 flex-none" x-text="progress + '%'"></span>
                </div>
                <div class="mt-1.5 h-1.5 rounded-full bg-gray-200 dark:bg-white/10 overflow-hidden">
                    <div class="h-full rounded-full bg-indigo-600 transition-[width] duration-150" :style="'width:' + progress + '%'"></div>
                </div>
            </div>
            <button type="button" x-on:click="cancelUpload()" class="flex-none text-sm text-gray-500 hover:text-gray-800 dark:hover:text-white">{{ $lblCancel }}</button>
        </div>

        {{-- 4 · FILLED --}}
        <div x-show="isFilled" x-cloak class="mw-up-state flex items-center gap-3">
            <span class="flex-none w-16 h-14 rounded-md bg-gray-100 dark:bg-white/10 bg-center bg-cover border border-gray-100 dark:border-white/10" :style="thumb ? ('background-image:url(' + thumb + ')') : ''"></span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate" x-text="fileName"></div>
                <div class="text-xs text-gray-400" x-text="fileMeta"></div>
            </div>
            <div class="mw-up-actions">
                <button type="button" x-show="!cfg.compact" x-on:click="openLibrary()" class="mw-up-btn mw-up-btn--ghost">{{ $lblEdit }}</button>
                <button type="button" x-on:click="openUpload()" class="mw-up-btn mw-up-btn--ghost" x-text="cfg.compact ? '{{ __('Change') }}' : '{{ $lblReplace }}'"></button>
                <button type="button" x-show="!cfg.compact" x-on:click="clear()" class="mw-up-btn mw-up-btn--icon" aria-label="{{ $lblRemove }}" title="{{ $lblRemove }}">
                    <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"/></svg>
                </button>
            </div>
        </div>

        {{-- 5 · ERROR --}}
        <div x-show="ui === 'error'" x-cloak class="mw-up-state mw-up-error flex items-center gap-3 rounded-lg border border-red-200 dark:border-red-500/30 bg-red-50/60 dark:bg-red-500/10">
            <span class="flex-none w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-300 flex items-center justify-center font-bold" aria-hidden="true">!</span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate"><span x-text="errorName"></span> {{ __("couldn't be uploaded") }}</div>
                <div class="text-xs text-red-600 dark:text-red-300" x-text="errorMsg"></div>
            </div>
            <button type="button" x-on:click="tryAnother()" class="mw-up-btn mw-up-btn--ghost flex-none">{{ __('Try another') }}</button>
        </div>
    </div>
</div>
