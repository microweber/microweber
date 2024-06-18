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
            <div>
                <x-filament::button color="success" size="lg" wire:click="saveAllForms">
                   SAVE
                </x-filament::button>
            </div>
        </div>


        <div class="py-6">
            <div x-show="activeTab === 'details'">
                <form wire:submit="save">
                    {{$this->seoForm}}
                    {{$this->form}}
                </form>
            </div>

            <div x-show="activeTab === 'custom_fields'">
                <x-filament::section>
                    @php
                        $relId = 0;
                        if (isset($this->data['id'])) {
                            $relId = $this->data['id'];
                        }
                    @endphp

                    @livewire('admin-list-custom-fields', [
                        'relId' => $relId,
                        'relType' => morph_name($this->getModel()),
                    ])
                </x-filament::section>
            </div>

            <div x-show="activeTab === 'seo'">
                <form wire:submit="save">
                    {{$this->seoForm}}
                </form>
            </div>

            <div x-show="activeTab === 'advanced'">
                Advanced
            </div>

        </div>


        <x-filament-actions::modals/>
    </div>
</div>
