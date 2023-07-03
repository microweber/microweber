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


            <livewire:microweber-module-option::text optionName="text"  :moduleId="$moduleId" :moduleType="$moduleType"  />
            <livewire:microweber-module-option::text optionName="url"  :moduleId="$moduleId" :moduleType="$moduleType"    />
            <livewire:microweber-module-option::text optionName="popupcontent"  :moduleId="$moduleId" :moduleType="$moduleType"    />


            <?php
            /*
             *    <livewire:microweber-module-option::text optionName="url"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />
            <livewire:microweber-module-option::text optionName="button_action"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />
            <livewire:microweber-module-option::text optionName="button_onclick"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />
            <livewire:microweber-module-option::text optionName="popupcontent"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />
            <livewire:microweber-module-option::text optionName="url_blank"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />
            <livewire:microweber-module-option::text optionName="icon"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />

             * */
            ?>





  <?php

  /*



            <livewire:microweber-module-option::text optionName="url"  moduleId="{{$moduleId}}" moduleType="{{$moduleType}}" translatable="true"  />
            <livewire:microweber-module-option::text optionName="button_text"  :moduleId="$moduleId" :moduleType="$moduleType" translatable="true"  />

   <livewire:microweber-module-btn::settings-form :moduleId="$moduleId" :moduleType="$moduleType" /> */
  ?>

        </div>


        <div class="tab-pane fade" wire:ignore.self id="design">

            <div class="mt-4">
                <div>

                    <livewire:microweber-live-edit::module-select-template :moduleId="$moduleId"
                                                                           :moduleType="$moduleType"/>
                </div>


                <div>
                    <livewire:microweber-module-btn::settings-form-design :moduleId="$moduleId" :moduleType="$moduleType" />
                </div>

                <div>
                    <x-microweber-module-btn::btn-align :settings="$settings"/>

                    <x-microweber-ui::icon-picker wire:model="settings.icon" :value="$settings['icon']"/>
                </div>
            </div>
        </div>
    </div>


</div>

