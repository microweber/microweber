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
                <form wire:submit="saveContent">
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


                         $customFieldParams = [
                            'relId' => $relId,
                            'relType' => morph_name($this->getModel()),
                        ];

                        if($relId == 0){
                            if (isset($this->data['session_id']) and $this->data['session_id']) {
                                $customFieldParams['sessionId'] = $this->data['session_id'];
                            }
                        }





                    @endphp

                    @livewire('admin-list-custom-fields',$customFieldParams)
                </x-filament::section>
            </div>


            @if(isset($this->seoForm) and $this->seoForm)
                <div x-show="activeTab === 'seo'">
                    <form wire:submit="saveContent">
                        {{$this->seoForm}}
                    </form>
                </div>
            @endif



            @if(isset($this->advancedSettingsForm) and $this->advancedSettingsForm)
                <div x-show="activeTab === 'advanced'">

                    <form wire:submit="saveContent">
                        {{$this->advancedSettingsForm}}
                    </form>


                </div>
            @endif
        </div>


        <x-filament-actions::modals/>
    </div>
</div>
