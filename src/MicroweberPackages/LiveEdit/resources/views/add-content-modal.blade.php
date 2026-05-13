{{-- AI-308 / AI-309 (task-2026-05-13-5f1937) — Add Content picker UX.
     AI-308: search/filter input at top filters cards by title +
             description (Alpine x-model + x-show; case-insensitive
             substring match). Empty-state message appears when no
             card matches the query so the modal never shows blank.
     AI-309: the "Add to this page" meta-instruction card was removed
             from the actions array in AdminLiveEditPage::addContentAction
             — see commit notes in that method. This template only renders
             whatever the action provides, so nothing here needs to know
             about the removed card. --}}
<div class="mw-add-content-modal-root mb-6 p-2 flex flex-col gap-4"
     x-data="{ q: '' }">

    <label class="mw-add-content-modal-search relative block">
        <span class="sr-only">Search content types</span>
        <input
            type="search"
            x-model="q"
            placeholder="Search content types…"
            aria-label="Search content types"
            autocomplete="off"
            class="mw-add-content-modal-search-input w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 text-base focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
        >
    </label>

    @foreach($actions as $action)

        {{-- task-2026-05-05-66e507 (QW1) — drunk-designer external
             audit flagged role=button divs as the most important a11y
             fix on this surface. Switched to a real <button> element
             so keyboard activation, focus rings, and form-control
             semantics work natively without manual wiring. The
             previous onkeydown shim is no longer needed —
             <button>'s default behaviour fires click on Enter and
             on Space-keyup, exactly per ARIA APG. --}}
        @php
            $mwAddContentHaystack = strtolower($action['title'] . ' ' . $action['description']);
        @endphp
        <button
            type="button"
            wire:click="replaceMountedAction('{{ $action['action'] }}')"
            aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
            data-mw-add-content-card
            data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"
            x-show="q === '' || @js($mwAddContentHaystack).includes(q.toLowerCase())"
            class="mw-add-content-modal-action-wrapper cursor-pointer flex gap-6 p-5 group transition duration-150 hover:bg-blue-500/10 dark:hover:bg-white/5 rounded-lg w-full border border-gray-100 dark:border-gray-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 text-start bg-transparent">
            <div class="flex items-center justify-center w-20 h-20 bg-blue-500/5 transition duration-150 group-hover:bg-blue-500/10 dark:group-hover:bg-white/10 rounded-lg p-4">
                @svg($action['icon'], "h-10 w-10 text-black/80 dark:text-white")
            </div>
            <div class="flex flex-col gap-2 w-full">
                <div class="font-bold">
                    {{ $action['title'] }}
                </div>
                <div class="text-sm">
                    {{ $action['description'] }}
                </div>
            </div>
        </button>

    @endforeach

    @php
        $mwAddContentHaystacks = collect($actions)
            ->map(fn ($a) => strtolower($a['title'] . ' ' . $a['description']))
            ->values()
            ->all();
    @endphp
    {{-- AI-307 post-fix polish (task-2026-05-13-5d3a06): the empty-state
         element used to render `No content types match "<span x-text=q>".`
         and relied on `x-cloak` to suppress the brief pre-Alpine paint.
         No project stylesheet defines the `[x-cloak] { display: none }`
         rule, so the message visibly flashed with literal empty quotes
         (`No content types match "".`) on every modal open. Replaced
         the query-echoing copy with a static, query-free message + an
         inline `style="display: none"` so the element is hidden until
         Alpine flips it via x-show. This eliminates the empty-quote
         flash AND drops the dependency on a global x-cloak rule. --}}
    <div class="mw-add-content-modal-empty text-center text-sm text-gray-500 dark:text-gray-400 py-6"
         x-show="q !== '' && @js($mwAddContentHaystacks).every(h => !h.includes(q.toLowerCase()))"
         style="display: none;">
        No content types found.
    </div>
</div>
