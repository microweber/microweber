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
    .mw-uploader .mw-up-row{display:flex;flex-wrap:wrap;align-items:center;gap:.75rem;}
    .mw-uploader .mw-up-info{display:flex;align-items:center;gap:.75rem;flex:1 1 10rem;min-width:0;}
    .mw-uploader .mw-up-title{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .mw-uploader .mw-up-actions{flex:none;display:flex;align-items:center;gap:.5rem;}
</style>

<div>
    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-1.5">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">{{ $getLabel() }}</span>
    </label>

    <div
        x-data="mwFileUploader($wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }}, JSON.parse(atob('{{ $uploaderConfig }}')))"
        x-on:dragover="onDragOver($event)"
        x-on:dragleave="onDragLeave($event)"
        x-on:drop="onDrop($event)"
        class="mw-uploader rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-white/5 p-3 sm:p-4"
        :class="{ 'mw-uploader--compact': cfg.compact }"
    >
        <input type="file" x-ref="fileInput" class="hidden" accept="{{ $accept }}" x-on:change="onPick($event)" />

        {{-- Header: type/size hint (hidden in compact) --}}
        <div x-show="!cfg.compact" class="flex items-center justify-between mb-2">
            <span class="text-[13px] font-medium text-gray-700 dark:text-gray-200">{{ $getLabel() ?: __('Image') }}</span>
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
            <div x-show="ui === 'dragging'" class="flex flex-col items-center justify-center gap-1 text-indigo-600 dark:text-indigo-300" :class="cfg.compact ? 'py-5' : 'py-8'">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false"><path d="M12 5v14M6 13l6 6 6-6"/></svg>
                <span class="text-sm font-medium">{{ __('Release to upload') }}</span>
            </div>
            {{-- idle empty --}}
            <div x-show="ui !== 'dragging'" class="mw-up-row" :class="cfg.compact ? 'p-2.5' : 'p-4'">
                <div class="mw-up-info">
                    <span class="flex-none rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-400" :class="cfg.compact ? 'w-8 h-8' : 'w-9 h-9'">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </span>
                    <div class="min-w-0">
                        <div class="mw-up-title text-sm font-semibold text-gray-800 dark:text-gray-100" x-text="cfg.compact ? '{{ __('Add an image') }}' : '{{ __('Drop an image here') }}'"></div>
                        <div x-show="!cfg.compact" class="text-xs text-gray-400">{{ __('or pick one below') }}</div>
                    </div>
                </div>
                <div class="mw-up-actions">
                    <button type="button" x-on:click="openLibrary()"
                        class="rounded-lg border border-gray-200 dark:border-white/15 bg-white dark:bg-white/5 font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/10"
                        :class="cfg.compact ? 'px-2.5 h-8 text-xs' : 'px-3 h-9 text-sm'"
                        x-text="cfg.compact ? '{{ __('Choose') }}' : '{{ __('Media library') }}'"></button>
                    <button type="button" x-on:click="openUpload()"
                        class="rounded-lg bg-gray-900 dark:bg-white font-medium text-white dark:text-gray-900 hover:opacity-90"
                        :class="cfg.compact ? 'px-2.5 h-8 text-xs' : 'px-3 h-9 text-sm'">{{ __('Upload') }}</button>
                </div>
            </div>
        </div>

        {{-- 3 · UPLOADING --}}
        <div x-show="ui === 'uploading'" x-cloak class="flex items-center gap-3">
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
        <div x-show="isFilled" x-cloak class="flex items-center gap-3">
            <span class="flex-none w-16 h-14 rounded-md bg-gray-100 dark:bg-white/10 bg-center bg-cover border border-gray-100 dark:border-white/10" :style="thumb ? ('background-image:url(' + thumb + ')') : ''"></span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate" x-text="fileName"></div>
                <div class="text-xs text-gray-400" x-text="fileMeta"></div>
            </div>
            <div class="flex-none flex items-center gap-1.5">
                <button type="button" x-show="!cfg.compact" x-on:click="openLibrary()" class="px-2.5 h-8 rounded-lg border border-gray-200 dark:border-white/15 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/10">{{ $lblEdit }}</button>
                <button type="button" x-on:click="openUpload()" class="px-2.5 h-8 rounded-lg border border-gray-200 dark:border-white/15 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/10" x-text="cfg.compact ? '{{ __('Change') }}' : '{{ $lblReplace }}'"></button>
                <button type="button" x-show="!cfg.compact" x-on:click="clear()" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-white/10" aria-label="{{ $lblRemove }}" title="{{ $lblRemove }}">
                    <svg viewBox="0 0 20 20" width="16" height="16" fill="currentColor" aria-hidden="true" focusable="false"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.293 5.293a1 1 0 011.414 0L10 8.586l3.293-3.293a1 1 0 111.414 1.414L11.414 10l3.293 3.293a1 1 0 01-1.414 1.414L10 11.414l-3.293 3.293a1 1 0 01-1.414-1.414L8.586 10 5.293 6.707a1 1 0 010-1.414z"/></svg>
                </button>
            </div>
        </div>

        {{-- 5 · ERROR --}}
        <div x-show="ui === 'error'" x-cloak class="flex items-center gap-3 rounded-lg border border-red-200 dark:border-red-500/30 bg-red-50/60 dark:bg-red-500/10 p-3">
            <span class="flex-none w-7 h-7 rounded-full bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-300 flex items-center justify-center font-bold" aria-hidden="true">!</span>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate"><span x-text="errorName"></span> {{ __("couldn't be uploaded") }}</div>
                <div class="text-xs text-red-600 dark:text-red-300" x-text="errorMsg"></div>
            </div>
            <button type="button" x-on:click="tryAnother()" class="flex-none px-3 h-8 rounded-lg border border-gray-200 dark:border-white/15 bg-white dark:bg-white/5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/10">{{ __('Try another') }}</button>
        </div>
    </div>
</div>
