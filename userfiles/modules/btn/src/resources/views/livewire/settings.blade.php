<div>

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


            <x-microweber-module-btn::btn-text :settings="$settings"/>

            <x-microweber-module-btn::btn-url-picker :url="$url" :settings="$settings"/>


        </div>


        <div class="tab-pane fade" wire:ignore.self id="design">

            <div class="mt-4">
                <div>
                    <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId"
                                                                           :moduleType="$moduleType"/>
                </div>
                <div>
                    <x-microweber-module-btn::btn-align :settings="$settings"/>

                    <x-microweber-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>
                </div>
            </div>
        </div>
    </div>


</div>

