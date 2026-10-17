<x-filament-panels::page>

    <div class="mw-settings-search-wrap">
        <span class="mw-settings-search-icon">
            @svg('heroicon-o-magnifying-glass', 'w-5 h-5')
        </span>
        <input
            type="search"
            class="mw-settings-search"
            placeholder="{{ __('Search settings…') }}"
            aria-label="{{ __('Search settings') }}"
            autocomplete="off"
            spellcheck="false"
        >
    </div>

    <div class="mw-settings-hub">
        @foreach($settingsGroups as $settingsTitle => $settings)
            <div class="mw-settings-hub-group" data-mw-settings-group>
                <h2 class="mw-settings-hub-group-title">{{ $settingsTitle }}</h2>
                <div class="mw-settings-hub-card">
                    <div class="mw-settings-hub-grid">
                        @foreach($settings as $setting)

                            <a href="{{ $setting['url'] }}"
                               class="mw-settings-hub-item"
                               data-mw-settings-item
                               data-search="{{ \Illuminate\Support\Str::lower(trim(($setting['title'] ?? '') . ' ' . ($setting['description'] ?? ''))) }}"
                               aria-label="{{ trim($setting['title']) }}">
                                <div class="mw-settings-hub-item-icon">
                                    @if(isset($setting['icon']) && $setting['icon'] != '')
                                        <?php try { ?>
                                            @svg($setting['icon'], "h-6 w-6 text-black/90 dark:text-white/90")
                                        <?php } catch(\Exception $e) { ?>
                                            @svg('mw-general', "h-6 w-6 text-black/90 dark:text-white/90")
                                        <?php } ?>
                                    @endif
                                </div>
                                <div class="mw-settings-hub-item-content">
                                    <h3 class="mw-settings-hub-item-title">{{ $setting['title'] }}</h3>
                                    @if(!empty($setting['description']))
                                        <p class="mw-settings-hub-item-description">{{ $setting['description'] }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mw-settings-no-results" data-mw-settings-no-results hidden>
            {{ __('No settings match your search.') }}
        </div>
    </div>

    @script
    <script>
        (function () {
            const root = document.querySelector('.mw-settings-hub');
            const input = document.querySelector('.mw-settings-search');
            if (!root || !input || input.dataset.mwBound === '1') {
                return;
            }
            input.dataset.mwBound = '1';

            const items = Array.from(root.querySelectorAll('[data-mw-settings-item]'));
            const groups = Array.from(root.querySelectorAll('[data-mw-settings-group]'));
            const noResults = root.querySelector('[data-mw-settings-no-results]');

            function apply() {
                const q = input.value.trim().toLowerCase();
                let anyVisible = false;

                items.forEach(function (item) {
                    const match = !q || (item.getAttribute('data-search') || '').indexOf(q) !== -1;
                    item.classList.toggle('mw-settings-hidden', !match);
                    if (match) { anyVisible = true; }
                });

                groups.forEach(function (group) {
                    const hasVisible = group.querySelector('[data-mw-settings-item]:not(.mw-settings-hidden)') !== null;
                    group.classList.toggle('mw-settings-hidden', !hasVisible);
                });

                if (noResults) { noResults.hidden = anyVisible; }
            }

            input.addEventListener('input', apply);
            // Allow Escape to clear quickly.
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { input.value = ''; apply(); }
            });
        })();
    </script>
    @endscript

</x-filament-panels::page>
