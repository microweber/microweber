<div>
    <div x-data="{ activeTab: 'products' }">

        <x-filament::tabs>
            <x-filament::tabs.item
                alpine-active="activeTab === 'products'"
                x-on:click="activeTab = 'products'"
            >
                Products
            </x-filament::tabs.item>
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

        <div class="mt-4">
            <div x-show="activeTab === 'products'">
                {{$this->table}}
            </div>

            <div x-show="activeTab === 'settings'">
                <div>
                    {{$this->form}}
                </div>
            </div>

            <div x-show="activeTab === 'design'">
                <div>
                    Design
                </div>
            </div>
        </div>

    </div>
</div>
