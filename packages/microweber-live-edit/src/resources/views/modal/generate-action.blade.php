<div>

    <div x-data="{ activeTab: 'details' }">

        <div class="flex gap-4 items-center">
            <div>
                <x-filament::tabs>
                    <x-filament::tabs.item
                        alpine-active="activeTab === 'details'"
                        x-on:click="activeTab = 'details'"
                    >
                        Details
                    </x-filament::tabs.item>

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'custom_fields'"
                        x-on:click="activeTab = 'custom_fields'"
                    >
                        Custom Fields
                    </x-filament::tabs.item
                    >

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'seo'"
                        x-on:click="activeTab = 'seo'"
                    >
                        SEO
                    </x-filament::tabs.item
                    >

                    <x-filament::tabs.item
                        alpine-active="activeTab === 'advanced'"
                        x-on:click="activeTab = 'advanced'"
                    >
                        Advanced
                    </x-filament::tabs.item>
                </x-filament::tabs>
            </div>

        </div>


        <div class="py-6">
            <div x-show="activeTab === 'details'">
              bbbb
            </div>

            <div x-show="activeTab === 'custom_fields'">
              bbb
            </div>


            @if(isset($this->seoForm) and $this->seoForm)
                <div x-show="activeTab === 'seo'">
                   aaa
                </div>
            @endif



            @if(isset($this->advancedSettingsForm) and $this->advancedSettingsForm)
                <div x-show="activeTab === 'advanced'">


                    aaa
                </div>
            @endif
        </div>


    </div>
</div>
