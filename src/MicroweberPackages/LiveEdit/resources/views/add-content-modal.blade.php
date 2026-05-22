{{-- AI-308 / AI-309 (task-2026-05-13-5f1937) — Add Content picker UX.
     AI-308: search/filter input at top filters cards by title +
             description (Alpine x-model + x-show; case-insensitive
             substring match). Empty-state message appears when no
             card matches the query so the modal never shows blank.
     AI-309: the "Add to this page" meta-instruction card was removed
             from the actions array in AdminLiveEditPage::addContentAction
             — see commit notes in that method. This template only renders
             whatever the action provides, so nothing here needs to know
             about the removed card.

     Free-form UX improvement (task-2026-05-13-bf1966):
       - Search input auto-focuses on modal open so users can start
         typing immediately. Saves a tap on every modal open.
       - Pressing Enter in the search input activates the first visible
         card (command-palette pattern). If exactly one card matches,
         the user can type → Enter to pick without ever leaving the
         keyboard.
       - Inline clear button (×) when the search has text. Cheaper than
         select-all + delete. Hidden when the input is empty.
       - ArrowDown from the search input moves focus to the first
         visible card; ArrowUp from the search input moves focus to the
         last visible card. From a card, ArrowDown/ArrowUp cycle through
         the visible cards. Standard command-palette navigation.
       - Refs (`x-ref="search"` + per-card refs via index) so the
         keyboard handlers don't need to re-query the DOM.

     Free-form UX improvement (task-2026-05-14-dac0b8):
       - Clear button floor lifted from 28x28 (w-7 h-7) to 44x44
         (w-11 h-11) to match the project-wide WCAG 2.5.5 touch-target
         standard enforced by AI-516..AI-522. Visual × glyph stays the
         same size; only the tap-area grows. Mobile users can now hit
         the clear button reliably on first try.
       - Escape on the search input: when `q` is non-empty, Escape
         clears the search and refocuses the input — natural
         command-palette ergonomic. When `q` is empty, Escape lets
         Filament's modal-level Escape handler close the picker
         (per AI-240 mw-modal focus contract). Implemented via
         `event.stopPropagation()` only when we have text to clear.
       - `aria-live="polite"` result-count announcement (visually
         hidden via .sr-only): assistive tech now hears "3 results" /
         "no results" / "all 6 options visible" as the user types,
         without any visual change for sighted users. Updates on
         `q` change via Alpine x-text. --}}
<div class="mw-add-content-modal-root mb-6 p-2 flex flex-col gap-4"
     {{-- AI-790 (task-2026-05-17-255d24) — Stage-5 template-attribute
          escape leak. The previous inline `x-data="{ ... }"` block
          carried embedded `//` line comments with literal `"`
          characters (e.g. `// primary "Add a block" card`). The HTML
          attribute parser terminates the attribute at the first
          embedded `"`, dumping the remainder of the JS as visible
          text content in the modal. Canonical fix per designer's
          dispatch: extract the entire state object to a named
          Alpine.data() registration (see <script> at the bottom of
          this file). The Blade attribute now carries only a bare
          identifier — no quoting issues possible. --}}
     x-data="addContentModal"
     x-init="$nextTick(() => $refs.search && $refs.search.focus())">

    {{-- NOVICE #14 (task-2026-05-13-899d57) — the previous copy
         "Search content types…" was jargon. A first-time site owner does
         not know "content type" is a thing. Replaced with a plain-English
         prompt that mirrors the question the user actually has in their
         head ("what do I want to add?") and seeds them with three
         concrete examples so they don't have to guess at the vocabulary. --}}
    {{-- task-2026-05-16-de4ce4 / AI-694 — search promotion. Per spec §2 + §4
         + §7 Slice 4: input becomes the primary affordance — 44px tall
         (WCAG 2.5.5 touch-target floor + visual prominence), auto-focused
         on modal open (already done via x-init below), `⌘K` shortcut chip
         on the right edge as a visual affordance hint (global hotkey
         routing intentionally deferred — designer note: "visual hint only
         at this slice"). Keyboard contract: ENTER → activateFirstVisible
         (zero-match → primary fallback); ↑/↓ enter the card grid;
         ESC clears `q` or escalates to modal close (Filament/AI-240).
         New `←/→` handlers added on the cards (below) as aliases for
         prev/next. The pe-16 padding-right reserves space for the
         optional ⌘K chip + clear button. --}}
    <label class="mw-add-content-modal-search relative block">
        <span class="sr-only">What do you want to add?</span>
        <input
            type="search"
            x-ref="search"
            x-model="q"
            placeholder="What do you want to add? (page, post, image…)"
            aria-label="What do you want to add?"
            autocomplete="off"
            x-on:keydown.enter.prevent="activateFirstVisibleCard()"
            x-on:keydown.arrow-down.prevent="focusFirstVisibleCard()"
            x-on:keydown.arrow-up.prevent="focusLastVisibleCard()"
            x-on:keydown.escape="if (q !== '') { q = ''; $refs.search.focus(); $event.stopPropagation(); }"
            class="mw-add-content-modal-search-input w-full min-h-[44px] rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 pe-16 text-base focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
        >
        {{-- task-2026-05-16-de4ce4 / AI-694 — ⌘K shortcut affordance chip.
             Visual hint only at this slice; global ⌘K hotkey routing is
             intentionally deferred (designer dispatch note). Hidden while
             the input has text so the clear button has room. Hidden on
             coarse pointers (touch devices) where the cmd-K convention
             is meaningless. --}}
        <kbd x-show="q === ''"
             aria-hidden="true"
             class="mw-add-content-modal-search-shortcut hidden sm:inline-flex items-center justify-center absolute end-3 top-1/2 -translate-y-1/2 px-1.5 py-0.5 text-xs font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">⌘K</kbd>
        {{-- Inline clear button — visible only while the input has text.
             Aria-labelled for assistive tech; click resets the model + re-
             focuses the input so users can keep typing without breaking
             flow. --}}
        <button
            type="button"
            x-show="q !== ''"
            x-on:click="q = ''; $refs.search.focus()"
            aria-label="Clear search"
            class="mw-add-content-modal-search-clear absolute end-1 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-11 h-11 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/60 dark:hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
            style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </label>

    {{-- task-2026-05-16-de4ce4 / AI-694 — zero-match ENTER hint.
         When the query has no matches anywhere, surface a small hint
         that ENTER will still add a block. This is the visible
         counterpart to the activateFirstVisibleCard() primary
         fallback above. style="display: none;" is the inline
         default-hidden state (per LESSONS — there is no global
         [x-cloak] rule). --}}
    <div x-show="q !== '' && visibleCount() === 0"
         class="mw-add-content-modal-zero-match-hint text-xs text-gray-500 dark:text-gray-400 px-1"
         style="display: none;">
        Press <kbd class="inline-flex items-center px-1 py-0.5 text-[10px] font-medium bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">Enter</kbd> to add a block to this page.
    </div>

    {{-- task-2026-05-14-dac0b8 — assistive-tech result-count announcement.
         Visually hidden via .sr-only; updated by Alpine on every `q`
         change via x-text + resultAnnouncement(). aria-live="polite" so
         screen-readers announce the count after the user pauses typing,
         not on every keystroke. Empty string suppresses announcements
         when q is empty (no result-count to report). --}}
    <div class="sr-only"
         aria-live="polite"
         aria-atomic="true"
         x-text="resultAnnouncement()"></div>

    {{-- task-2026-05-16-968a71 / AI-692 / §A2 + §A3: TWO-GROUP layout.
         Replaces the prior single 2-column grid that lumped all 6
         cards together. Spec §2 key-move 2 — "two action classes —
         separate them visually." Primary group (Add a block) renders
         as a single full-width card under "On this page". Secondary
         group (Page/Post/Product/Image/Category) renders as a 3-col
         (desktop) / 2-col (mobile) grid under "New content".
         Synonyms map lifted outside both loops since it's static
         per the project — not per-iteration data.

         task-2026-05-16-cdeefd / AI-691 changes preserved through
         the refactor: card body description is rendered as the
         native `title=` attribute (browser tooltip on hover) plus
         the aria-label (accessibility tree) — never as visible
         body text inside the card. Section headers introduced by
         AI-692 do NOT re-introduce the visible body text. --}}
    @php
        // NOVICE #14 (task-2026-05-13-899d57) — synonyms map. Adding
        // a new card? Append to this map keyed on the action name.
        $mwAddContentSynonyms = [
            'addPageAction'           => 'about services contact landing static homepage',
            'addPostAction'           => 'article news update story news blog entry',
            'addCategoryAction'       => 'folder group section tag taxonomy',
            'addProductAction'        => 'shop item store buy sell merchandise sku',
            'addImageAction'          => 'photo picture banner graphic logo upload media gallery',
            'addToCurrentPageAction'  => 'block layout text image button heading paragraph row column section insert',
        ];
        $mwAddContentPrimary   = array_values(array_filter($actions, fn ($a) => ($a['group'] ?? 'secondary') === 'primary'));
        $mwAddContentSecondary = array_values(array_filter($actions, fn ($a) => ($a['group'] ?? 'secondary') === 'secondary'));
    @endphp

    {{-- ON THIS PAGE — primary group (single full-width card) --}}
    <section class="mw-add-content-group mw-add-content-group--primary"
             x-show="hasVisibleCardsInGroup('primary')"
             aria-labelledby="mw-add-content-group-primary-heading">
        <h3 id="mw-add-content-group-primary-heading"
            class="mw-add-content-group__header text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2 px-1">
            On this page
        </h3>
        <div class="mw-add-content-group__items flex flex-col gap-3">
            @foreach($mwAddContentPrimary as $action)
                @php
                    $mwAddContentHaystack = strtolower(
                        $action['title']
                        . ' ' . $action['description']
                        . ' ' . ($mwAddContentSynonyms[$action['action']] ?? '')
                    );
                    $mwAddContentJsDispatch = $action['js_dispatch'] ?? null;
                @endphp
                {{-- task-2026-05-16-c3d0ed / AI-693 — monochrome icon at rest; the
                     `mw-add-content-icon` wrapper picks up the accent contract
                     (--ese-accent-soft bg + --ese-accent foreground) on hover/
                     focus via CSS in `live-edit-module-settings.blade.php`.
                     Previously each action had per-type tinting (blue/indigo/
                     emerald/violet/rose/amber); that match map is removed so
                     the six cards share a single monochrome line-set per
                     designer spec §2 + AI-693 description. --}}
                <button
                    type="button"
                    @if ($mwAddContentJsDispatch)
                        x-on:click="window.dispatchEvent(new CustomEvent(@js($mwAddContentJsDispatch))); try { $wire.unmountAction(); } catch (e) {}"
                    @else
                        wire:click="replaceMountedAction('{{ $action['action'] }}')"
                    @endif
                    aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
                    title="{{ $action['description'] }}"
                    data-mw-add-content-card
                    data-mw-add-content-group="primary"
                    data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"
                    {{-- task-2026-05-16-de4ce4 / AI-694 — `visibility: hidden`
                         path (via .mw-add-content-card--hidden) replaces
                         x-show display:none so filtered cards keep their
                         grid cell (no reflow on type). --}}
                    :class="{ 'mw-add-content-card--hidden': q !== '' && !@js($mwAddContentHaystack).includes(q.toLowerCase()) }"
                    x-on:keydown.arrow-down.prevent="focusNextCard($el)"
                    x-on:keydown.arrow-up.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-left.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-right.prevent="focusNextCard($el)"
                    {{-- task-2026-05-21-30040a / AI-870 Fix 3: min-h
                         increased (63→72px), title text-base (was text-sm),
                         chevron affordance added.
                         task-2026-05-22-fa8e70: description moved back to tooltip-only
                         (title + aria-label) per AI-691 spec — visible description
                         below primary card title created visual imbalance vs
                         secondary cards (icon + title only). --}}
                    class="mw-add-content-modal-action-wrapper cursor-pointer flex flex-row items-center gap-3 p-4 min-h-[72px] group transition duration-150 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg w-full border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 text-start bg-transparent">
                    <div class="mw-add-content-icon flex items-center justify-center w-12 h-12 bg-gray-500/10 transition duration-150 rounded-lg shrink-0">
                        @svg($action['icon'], 'h-6 w-6 transition duration-150 text-gray-600 dark:text-gray-400')
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="font-semibold text-base leading-tight">
                            {{ $action['title'] }}
                        </div>
                    </div>
                    @svg('heroicon-o-chevron-right', 'ml-auto shrink-0 h-4 w-4 text-gray-400 dark:text-gray-500')
                </button>
            @endforeach
        </div>
    </section>

    {{-- NEW CONTENT — secondary group (3-col desktop, 2-col mobile) --}}
    <section class="mw-add-content-group mw-add-content-group--secondary mt-4"
             x-show="hasVisibleCardsInGroup('secondary')"
             aria-labelledby="mw-add-content-group-secondary-heading">
        <h3 id="mw-add-content-group-secondary-heading"
            class="mw-add-content-group__header text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2 px-1">
            New content
        </h3>
        {{-- task-2026-05-21-30040a / AI-870: modal is always ≥618px;
             the 2-col fallback caused a Z-flow reflow artefact when
             transitioning from 2→3 columns on viewport resize. Always
             use 3 columns. --}}
        <div class="mw-add-content-group__items grid grid-cols-3 gap-3">
            @foreach($mwAddContentSecondary as $action)
                @php
                    $mwAddContentHaystack = strtolower(
                        $action['title']
                        . ' ' . $action['description']
                        . ' ' . ($mwAddContentSynonyms[$action['action']] ?? '')
                    );
                    $mwAddContentJsDispatch = $action['js_dispatch'] ?? null;
                @endphp
                {{-- task-2026-05-16-c3d0ed / AI-693 — monochrome icon at rest;
                     accent contract (`--ese-accent-soft` bg + `--ese-accent`
                     foreground) applies on hover/focus via CSS in
                     `live-edit-module-settings.blade.php`. The previous
                     task-2026-05-15-0282f5 per-action `match` map (indigo /
                     emerald / violet / rose / amber tints) is removed so the
                     six cards share a single monochrome line-set per designer
                     spec §2 + AI-693 description. --}}
                <button
                    type="button"
                    @if ($mwAddContentJsDispatch)
                        x-on:click="window.dispatchEvent(new CustomEvent(@js($mwAddContentJsDispatch))); try { $wire.unmountAction(); } catch (e) {}"
                    @else
                        wire:click="replaceMountedAction('{{ $action['action'] }}')"
                    @endif
                    aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
                    title="{{ $action['description'] }}"
                    data-mw-add-content-card
                    data-mw-add-content-group="secondary"
                    data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"
                    {{-- task-2026-05-16-de4ce4 / AI-694 — visibility:hidden
                         path (see primary card for full rationale). --}}
                    :class="{ 'mw-add-content-card--hidden': q !== '' && !@js($mwAddContentHaystack).includes(q.toLowerCase()) }"
                    x-on:keydown.arrow-down.prevent="focusNextCard($el)"
                    x-on:keydown.arrow-up.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-left.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-right.prevent="focusNextCard($el)"
                    {{-- task-2026-05-22-9d5e21: padding p-4→p-3 (tighter cards);
                         dark:bg-white/[0.04] replaces bg-transparent so cards are
                         visible against the dark modal background; dark:border-white/10
                         replaces dark:border-gray-700 for better dark-mode separation;
                         icon 48px→40px proportionally fits the compact p-3 card. --}}
                    class="mw-add-content-modal-action-wrapper cursor-pointer flex flex-col gap-2 p-3 group transition duration-150 bg-transparent dark:bg-white/[0.04] hover:bg-gray-50 dark:hover:bg-white/[0.07] rounded-lg w-full border border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 text-start">
                    <div class="mw-add-content-icon flex items-center justify-center w-10 h-10 bg-gray-500/10 dark:bg-white/[0.08] transition duration-150 rounded-lg shrink-0">
                        @svg($action['icon'], 'h-5 w-5 transition duration-150 text-gray-600 dark:text-gray-400')
                    </div>
                    <div class="font-semibold text-sm leading-tight">
                        {{ $action['title'] }}
                    </div>
                </button>
            @endforeach
        </div>
    </section>

    @php
        // NOVICE #14 — the empty-state check must use the SAME haystack
        // (including synonyms) that the per-card x-show uses, otherwise
        // typing "photo" filters every card to hidden AND ALSO shows the
        // "no content types found" message — confusing twice.
        $mwAddContentHaystacks = collect($actions)
            ->map(fn ($a) => strtolower(
                $a['title']
                . ' ' . $a['description']
                . ' ' . ($mwAddContentSynonyms[$a['action']] ?? '')
            ))
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
    {{-- Empty state — outside both groups so it spans the modal
         width when no card in EITHER group matches. The .sm:col-span-2
         utility from the prior single-grid layout is no longer
         needed since this div is no longer inside a grid. --}}
    <div class="mw-add-content-modal-empty text-center text-sm text-gray-500 dark:text-gray-400 py-6"
         x-show="q !== '' && @js($mwAddContentHaystacks).every(h => !h.includes(q.toLowerCase()))"
         style="display: none;">
        No content types found.
    </div>
</div>

{{-- task-2026-05-18-76a360 — `addContentModal` Alpine.data() factory
     registration was MOVED from this Blade view to
     `src/MicroweberPackages/Filament/resources/views/filament/components/layout/live-edit.blade.php`
     @push('scripts') block. Embedded <script> tags inside Filament/
     Livewire-morph-inserted modal HTML do NOT execute in the browser,
     so the in-modal registration (per the original AI-790 / task-255d24
     lift) silently failed at runtime — cards rendered but stayed
     invisible because Alpine could not resolve `x-data="addContentModal"`.
     The factory MUST be registered before the modal mounts; the parent
     admin-frame layout is the canonical home. The Blade `x-data` binding
     below stays — Stage-5 template-attribute-escape-leak guard still in
     force (bare identifier, no quoting issues). --}}

