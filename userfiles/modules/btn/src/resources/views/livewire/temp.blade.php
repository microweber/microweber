<div xmlns:x-microweber-ui="http://www.w3.org/1999/html">

    <ul class="nav nav-tabs">
        <li class="nav-item" wire:ignore>
            <a href="#text" class="nav-link active" data-bs-toggle="tab">Text</a>
        </li>

        <li class="nav-item" wire:ignore>
            <a href="#design" class="nav-link" data-bs-toggle="tab">Design</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" wire:ignore.self id="text">

            <x-microweber-ui::tabs>
                <x-microweber-ui::tab-item>


                </x-microweber-ui::tab-item>
            </x-microweber-ui::tabs>






            <x-microweber-ui::label  content="pick url" />

            <livewire:microweber-module-option::text optionKey="button_text"  :optionGroup="$moduleId" :module="$moduleType" />
            <x-microweber-ui::row>
                <x-microweber-ui::col>




                    <x-microweber-ui::form-help  content="pick url for button" />


                    <livewire:microweber-module-option::url-picker optionName="button_url"  :optionGroup="$moduleId" :module="$moduleType" />
                    <livewire:microweber-module-option::text optionKey="button_text"  :optionGroup="$moduleId" :module="$moduleType" />


                    <livewire:microweber-module-option::icon-picker optionName="button_url"  :optionGroup="$moduleId" :module="$moduleType" />




                </x-microweber-ui::col>
            </x-microweber-ui::row>









            <x-microweber-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>
            <x-microweber-ui::text wire:model="settings.title" :value="$settings['title']"/>
            <x-microweber-ui::url-picker wire:model="settings.url" :value="$settings['url']"/>


            <x-microweber-module-option::url-picker wire:model="settings.url"  :optionGroup="$moduleId" :module="$moduleType" />
            <x-microweber-module-option::icon-picker wire:model="settings.url"  :optionGroup="$moduleId" :module="$moduleType" />
            <x-microweber-module-option::text wire:model="settings.title"  :optionGroup="$moduleId" :module="$moduleType" />
            <x-microweber-module-option::select wire:model="settings.map_type"   :optionGroup="$moduleId" :module="$moduleType" />


            <x-microweber-module-option::checkbox wire:model="settings.url_blank"  :optionGroup="$moduleId" :module="$moduleType" />



            <livewire:microweber-module-btn::settings-form field="backgroudColor" :optionGroup="$moduleId" :module="$moduleType" />
            <livewire:microweber-module-btn::settings-form field="url" :optionGroup="$moduleId" :module="$moduleType" />
            <livewire:microweber-module-btn::settings-form field="title"   :optionGroup="$moduleId" :module="$moduleType" />
            <livewire:microweber-module-btn::settings-form field="padding"   :optionGroup="$moduleId" :module="$moduleType" />
            <livewire:microweber-module-btn::settings-form field="margin"   :optionGroup="$moduleId" :module="$moduleType" />

        </div>


        <div class="tab-pane fade" wire:ignore.self id="design">

            <div class="mt-4">
                <div>

                    <livewire:microweber-live-edit::module-select-template :optionGroup="$moduleId"
                                                                           :module="$moduleType"/>
                </div>


                <div>
                    <livewire:microweber-module-btn::settings-form-design :optionGroup="$moduleId" :module="$moduleType" />
                </div>

                <div>
                    <x-microweber-module-btn::btn-align :settings="$settings"/>

                    <x-microweber-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>
                </div>
            </div>
        </div>
    </div>


</div>

