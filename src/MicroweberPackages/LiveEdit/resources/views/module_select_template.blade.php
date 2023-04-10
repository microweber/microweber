<div class="mw-module-select-template">

    @php
        $currentSkin = $settings['template'];
        $currentSkinName = str_replace('.php', '', $currentSkin);
        $componentName = 'live-edit::'.$moduleType.'-template-'.$currentSkinName;

        $hasSkinSettingsComponent= livewire_component_exists($componentName) === true;


    @endphp
   <div>
       select template

       <select wire:model.debounce.100ms="settings.template">
           <option value="default">
               <?php _e("Default"); ?>
           </option>

           <?php foreach ($moduleTemplates as $item): ?>
               <?php if ((strtolower($item['name']) != 'default')): ?>
           <option value="<?php print $item['layout_file'] ?>"
                   title="Template: <?php print str_replace('.php', '', $item['layout_file']); ?>"> <?php print $item['name'] ?> </option>
           <?php endif; ?>
           <?php endforeach; ?>
       </select>

   </div>

    <div class="mw-module-skin-setting-holder">


        @if($currentSkin and $hasSkinSettingsComponent)
            <div class="mw-module-skin-setting-inner">
                <div>
                    <div>
                    @livewire($componentName, ['moduleId' => $moduleId, 'moduleType' => $moduleType], key($componentName.'-'.$moduleId))
                    </div>
                    <script>
                        window.livewire.rescan();
                    </script>
                </div>
            </div>

        @endif
    </div>

</div>
