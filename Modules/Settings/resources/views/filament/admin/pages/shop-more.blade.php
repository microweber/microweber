<x-filament-panels::page>

    <div class="mw-settings-hub">
        @foreach($groups as $group)
            @continue(empty($group['items']))
            <div class="mw-settings-hub-group">
                <h2 class="mw-settings-hub-group-title">{{ $group['title'] }}</h2>
                <div class="mw-settings-hub-card">
                    <div class="mw-settings-hub-grid">
                        @foreach($group['items'] as $card)
                            <a href="{{ $card['url'] }}" class="mw-settings-hub-item" aria-label="{{ trim($card['title']) }}">
                                <div class="mw-settings-hub-item-icon">
                                    @if(!empty($card['icon']))
                                        <?php try { ?>
                                            @svg($card['icon'], "h-6 w-6 text-black/90 dark:text-white/90")
                                        <?php } catch(\Exception $e) { ?>
                                            @svg('mw-general', "h-6 w-6 text-black/90 dark:text-white/90")
                                        <?php } ?>
                                    @endif
                                </div>
                                <div class="mw-settings-hub-item-content">
                                    <h3 class="mw-settings-hub-item-title">{{ $card['title'] }}</h3>
                                    @if(!empty($card['description']))
                                        <p class="mw-settings-hub-item-description">{{ $card['description'] }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</x-filament-panels::page>
