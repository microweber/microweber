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
         keyboard handlers don't need to re-query the DOM. --}}
<div class="mw-add-content-modal-root mb-6 p-2 flex flex-col gap-4"
     x-data="{
        q: '',
        visibleCards() {
            return Array.from($root.querySelectorAll('[data-mw-add-content-card]'))
                .filter(el => window.getComputedStyle(el).display !== 'none');
        },
        focusFirstVisibleCard() {
            const cards = this.visibleCards();
            if (cards.length) cards[0].focus();
        },
        focusLastVisibleCard() {
            const cards = this.visibleCards();
            if (cards.length) cards[cards.length - 1].focus();
        },
        focusNextCard(current) {
            const cards = this.visibleCards();
            const i = cards.indexOf(current);
            if (i === -1 || i === cards.length - 1) {
                $refs.search.focus();
            } else {
                cards[i + 1].focus();
            }
        },
        focusPrevCard(current) {
            const cards = this.visibleCards();
            const i = cards.indexOf(current);
            if (i === -1 || i === 0) {
                $refs.search.focus();
            } else {
                cards[i - 1].focus();
            }
        },
        activateFirstVisibleCard() {
            const cards = this.visibleCards();
            if (cards.length === 1) {
                cards[0].click();
            } else if (cards.length > 1) {
                cards[0].focus();
            }
        },
     }"
     x-init="$nextTick(() => $refs.search && $refs.search.focus())">

    {{-- NOVICE #14 (task-2026-05-13-899d57) — the previous copy
         "Search content types…" was jargon. A first-time site owner does
         not know "content type" is a thing. Replaced with a plain-English
         prompt that mirrors the question the user actually has in their
         head ("what do I want to add?") and seeds them with three
         concrete examples so they don't have to guess at the vocabulary. --}}
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
            class="mw-add-content-modal-search-input w-full rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 pe-10 text-base focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
        >
        {{-- Inline clear button — visible only while the input has text.
             Aria-labelled for assistive tech; click resets the model + re-
             focuses the input so users can keep typing without breaking
             flow. --}}
        <button
            type="button"
            x-show="q !== ''"
            x-on:click="q = ''; $refs.search.focus()"
            aria-label="Clear search"
            class="mw-add-content-modal-search-clear absolute end-3 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-7 h-7 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/60 dark:hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
            style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </label>

    @foreach($actions as $action)

        {{-- task-2026-05-05-66e507 (QW1) — drunk-designer external
             audit flagged role=button divs as the most important a11y
             fix on this surface. Switched to a real <button> element
             so keyboard activation, focus rings, and form-control
             semantics work natively without manual wiring. The
             previous onkeydown shim is no longer needed —
             <button>'s default behaviour fires click on Enter and
             on Space-keyup, exactly per ARIA APG.

             NOVICE #14 (task-2026-05-13-899d57) — extended the per-card
             search haystack with hand-curated synonyms keyed on the
             action name. The novice persona reported typing "photo"
             and "article" and "banner" only to see "no content types
             found" — because the search was exact-substring against
             the literal titles + descriptions. The synonym map below
             folds in the words a first-time user actually uses, so the
             search returns the right card on the first try. Adding a
             new card? Add its synonyms to $mwAddContentSynonyms below. --}}
        @php
            $mwAddContentSynonyms = [
                'addPageAction'     => 'about services contact landing static homepage',
                'addPostAction'     => 'article news update story news blog entry',
                'addCategoryAction' => 'folder group section tag taxonomy',
                'addProductAction'  => 'shop item store buy sell merchandise sku',
                'addImageAction'    => 'photo picture banner graphic logo upload media gallery',
            ];
            $mwAddContentHaystack = strtolower(
                $action['title']
                . ' ' . $action['description']
                . ' ' . ($mwAddContentSynonyms[$action['action']] ?? '')
            );
        @endphp
        <button
            type="button"
            wire:click="replaceMountedAction('{{ $action['action'] }}')"
            aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
            data-mw-add-content-card
            data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"
            x-show="q === '' || @js($mwAddContentHaystack).includes(q.toLowerCase())"
            x-on:keydown.arrow-down.prevent="focusNextCard($el)"
            x-on:keydown.arrow-up.prevent="focusPrevCard($el)"
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
    <div class="mw-add-content-modal-empty text-center text-sm text-gray-500 dark:text-gray-400 py-6"
         x-show="q !== '' && @js($mwAddContentHaystacks).every(h => !h.includes(q.toLowerCase()))"
         style="display: none;">
        No content types found.
    </div>
</div>
