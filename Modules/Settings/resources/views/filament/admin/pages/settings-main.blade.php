<x-filament-panels::page>

    <div class="mw-settings-hub">
        @foreach($settingsGroups as $settingsTitle => $settings)
            <div class="mw-settings-hub-group">
                <h2 class="mw-settings-hub-group-title">{{ $settingsTitle }}</h2>
                <div class="mw-settings-hub-card">
                    <div class="mw-settings-hub-grid">
                        @foreach($settings as $setting)
                            <a href="{{ $setting['url'] }}" class="mw-settings-hub-item">
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
    </div>

</x-filament-panels::page>
