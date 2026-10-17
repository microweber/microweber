
<div class="mw-add-content-modal-root mb-1 p-2 flex flex-col gap-3"

     x-data="addContentModal"
     x-init="$nextTick(() => {
         $refs.search && $refs.search.focus();

         var dlg = $el.closest('[role=dialog]');
         if (dlg) {
             var lbl = dlg.getAttribute('aria-labelledby');
             var h = dlg.querySelector('.fi-modal-heading');
             if (lbl && h && !h.id) h.id = lbl;
         }
     })">

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

            class="mw-add-content-modal-search-input w-full min-h-[44px] rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-200 px-4 py-3 pe-16 text-base focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#182433]"
        >

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
            class="mw-add-content-modal-search-clear absolute end-1 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-11 h-11 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-200/60 dark:hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#182433]"
            style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </label>

    <div x-show="q !== '' && visibleCount() === 0"
         class="mw-add-content-modal-zero-match-hint text-xs text-gray-500 dark:text-gray-400 px-1"
         style="display: none;">
        Press <kbd class="inline-flex items-center px-1 py-0.5 text-[10px] font-medium bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">Enter</kbd> to add a block to this page.
    </div>

    <div class="sr-only"
         aria-live="polite"
         aria-atomic="true"
         x-text="resultAnnouncement()"></div>

    @php
        // a new card? Append to this map keyed on the action name.
        $mwAddContentSynonyms = [
            'addPageAction'           => 'about services contact landing static homepage',
            'addPostAction'           => 'article news update story news blog entry',
            'addCategoryAction'       => 'folder group section tag taxonomy',
            'addProductAction'        => 'shop item store buy sell merchandise sku',
            'addImageAction'          => 'photo picture banner graphic logo upload media gallery',
            'addToCurrentPageAction'  => 'block layout text image button heading paragraph row column section insert',
            'addLayoutAction'         => 'section block template row column hero grid footer layout structure',
        ];
        $mwAddContentPrimary   = array_values(array_filter($actions, fn ($a) => ($a['group'] ?? 'secondary') === 'primary'));
        $mwAddContentSecondary = array_values(array_filter($actions, fn ($a) => ($a['group'] ?? 'secondary') === 'secondary'));
    @endphp

    {{-- ON THIS PAGE — primary group (single full-width card) --}}
    <section class="mw-add-content-group mw-add-content-group--primary"
             x-show="hasVisibleCardsInGroup('primary')"
             aria-labelledby="mw-add-content-group-primary-heading">
        <h3 id="mw-add-content-group-primary-heading"
            class="mw-add-content-group__header text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 px-1">
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

                <button
                    type="button"
                    @if ($mwAddContentJsDispatch)
                        x-on:click="window.dispatchEvent(new CustomEvent(@js($mwAddContentJsDispatch))); try { $wire.unmountAction(); } catch (e) {}"
                    @else

                        x-on:click="window.dispatchEvent(new CustomEvent('liveEditOpenCreateContent', { detail: { action: '{{ $action['action'] }}' } }))"
                    @endif
                    aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
                    title="{{ $action['description'] }}"
                    data-mw-add-content-card
                    data-mw-add-content-group="primary"
                    data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"

                    :class="{ 'mw-add-content-card--hidden': q !== '' && !@js($mwAddContentHaystack).includes(q.toLowerCase()) }"
                    x-on:keydown.arrow-down.prevent="focusNextCard($el)"
                    x-on:keydown.arrow-up.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-left.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-right.prevent="focusNextCard($el)"

                    class="mw-add-content-modal-action-wrapper cursor-pointer flex flex-row items-center gap-3 p-3 min-h-[56px] group transition duration-150 hover:bg-gray-50 dark:hover:bg-white/5 rounded-lg w-full border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#182433] text-start bg-transparent">
                    <div class="mw-add-content-icon flex items-center justify-center w-12 h-12 transition duration-150 rounded-full shrink-0">
                        @svg($action['icon'], 'h-6 w-6 transition duration-150 text-white')
                    </div>
                    <div class="flex flex-col min-w-0">

                        <div class="font-semibold text-base leading-tight text-gray-900 dark:text-gray-100">
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
            class="mw-add-content-group__header text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 px-1">
            New content
        </h3>

        <p class="text-xs text-gray-600 dark:text-gray-300 mb-3 px-1">Create something new for your website</p>

        <div class="mw-add-content-group__items grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach($mwAddContentSecondary as $action)
                @php
                    $mwAddContentHaystack = strtolower(
                        $action['title']
                        . ' ' . $action['description']
                        . ' ' . ($mwAddContentSynonyms[$action['action']] ?? '')
                    );
                    $mwAddContentJsDispatch = $action['js_dispatch'] ?? null;
                @endphp

                <button
                    type="button"
                    @if ($mwAddContentJsDispatch)
                        x-on:click="window.dispatchEvent(new CustomEvent(@js($mwAddContentJsDispatch))); try { $wire.unmountAction(); } catch (e) {}"
                    @else

                        x-on:click="window.dispatchEvent(new CustomEvent('liveEditOpenCreateContent', { detail: { action: '{{ $action['action'] }}' } }))"
                    @endif
                    aria-label="{{ $action['title'] }}: {{ $action['description'] }}"
                    title="{{ $action['description'] }}"
                    data-mw-add-content-card
                    data-mw-add-content-group="secondary"
                    data-mw-add-content-haystack="{{ $mwAddContentHaystack }}"

                    :class="{ 'mw-add-content-card--hidden': q !== '' && !@js($mwAddContentHaystack).includes(q.toLowerCase()) }"
                    x-on:keydown.arrow-down.prevent="focusNextCard($el)"
                    x-on:keydown.arrow-up.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-left.prevent="focusPrevCard($el)"
                    x-on:keydown.arrow-right.prevent="focusNextCard($el)"

                    class="mw-add-content-modal-action-wrapper cursor-pointer flex flex-col items-center text-center gap-2 p-3 group transition duration-150 bg-transparent dark:bg-white/[0.04] hover:bg-gray-50 dark:hover:bg-white/[0.07] rounded-lg w-full border border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#182433]">
                    <div class="mw-add-content-icon flex items-center justify-center w-12 h-12 transition duration-150 rounded-full shrink-0" style="background-color: {{ $action['tint'] ?? '#f3f4f6' }}">

                        @svg($action['icon'], 'h-6 w-6 transition duration-150 text-gray-700 dark:text-gray-300')
                    </div>
                    <div class="flex flex-col items-center gap-0.5">

                        <div class="font-semibold text-sm leading-tight text-gray-900 dark:text-gray-100">
                            {{ $action['title'] }}
                        </div>
                        @if (!empty($action['sublabel']))

                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $action['sublabel'] }}</div>
                        @endif
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

