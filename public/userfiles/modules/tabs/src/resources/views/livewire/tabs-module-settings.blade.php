<div>
    <div x-data="{ activeTab: 'settings' }">
        <div class="mb-4">
            <x-filament::tabs>

                <x-filament::tabs.item
                    alpine-active="activeTab === 'settings'"
                    x-on:click="activeTab = 'settings'"
                >
                    Settings
                </x-filament::tabs.item>

                <x-filament::tabs.item
                    alpine-active="activeTab === 'design'"
                    x-on:click="activeTab = 'design'"
                >
                    Design
                </x-filament::tabs.item>

            </x-filament::tabs>
        </div>

        <div x-show="activeTab=='settings'">
            {{ $this->table }}
        </div>
        <div x-show="activeTab=='design'">

             {{ $this->skinsForm }}


        </div>

    </div>
</div>
