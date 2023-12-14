<div>

    @php

    $showSkinsDropdown = true;


        $currentSkin = 'default';
        if(isset($settings['template'])){
            $currentSkin = $settings['template'];
        }

        $currentSkinName = str_replace('.php', '', $currentSkin);
        $componentNameForModuleSkin = 'microweber-module-'.$moduleType.'::template-settings-'.$currentSkinName;

        $moduleSkinSettingsRegisteredAlias =  \MicroweberPackages\Module\Facades\ModuleAdmin::getSkinSettings($moduleType, $currentSkinName);
        if($moduleSkinSettingsRegisteredAlias){
            $componentNameForModuleSkin = $moduleSkinSettingsRegisteredAlias;
        }

        $hasSkinSettingsComponent= livewire_component_exists($componentNameForModuleSkin) === true;

        $moduleTypeForComponent = str_replace('/', '.', $moduleType);
        $moduleTypeForComponent = str_replace('_', '-', $moduleType);


        if(count($moduleTemplates) == 1 && !$hasSkinSettingsComponent){
            $showSkinsDropdown = false;
        }

        if($currentSkin and $hasSkinSettingsComponent){
            $showSkinsDropdown = true;
        }

    @endphp


    <div class="mw-module-select-template"
         @if(!$showSkinsDropdown) style="display:none" @endif >

        <div class="mt-4 mb-3">
            <label class="live-edit-label"><?php _ejs("Skin"); ?></label>
            @php
                $selectModernOptions = [];
                if ($disableScreenshots) {
                    $selectModernOptions['default'] = '<div>Default Skin</div>';
                } else {
                    $selectModernOptions['default'] = '<div style="width:100%;height:100px;background:#fff;display:flex;justify-content:center;align-items:center;">Default Skin</div>';
                }
                foreach ($moduleTemplates as $item) {
                    $optionHtml = '<div  style="width:100%;">';

                    if (isset($item['screenshot']) && ($disableScreenshots == false)) {
                        $optionHtml .=  '<div><h4>'.$item['name'].'</h4></div>';
                        $optionHtml .= '<div style="width:100%; height:100px; background-image: url(' . $item['screenshot'] . '); background-size: contain; background-position: top center; background-repeat: no-repeat;"></div>';
                    } else {
                         $optionHtml .=  '<div>'.$item['name'].'</div>';
                    }

                    $optionHtml .= '</div>';

                    $selectModernOptions[$item['layout_file']] = $optionHtml;
                }
            @endphp
            <x-microweber-ui::select-modern wire:model="settings.template" :options="$selectModernOptions" />
        </div>


        <div class="mw-module-skin-setting-holder">


            @if($currentSkin and $hasSkinSettingsComponent)
                <div class="mw-module-skin-setting-inner">
                    <div>
                        <div>

                                <?php
                                $moduleTypeForComponent = str_replace('/', '-', $moduleType);
                                $hasError = false;
                                $output = false;

                                try {
                                    $output = \Livewire\Livewire::mount($componentNameForModuleSkin, [
                                        'moduleId' => $moduleId,
                                        'moduleType' => $moduleType,
                                    ])->html();

                                } catch (\Livewire\Exceptions\ComponentNotFoundException $e) {
                                    $hasError = true;
                                    $output = $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
                                } catch (\Exception $e) {
                                    $hasError = true;
                                    $output = $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine();
                                }

                                if ($hasError) {
                                    print '<div class="alert alert-danger" role="alert">';
                                    print $output;
                                    print '</div>';
                                } else {
                                    print $output;
                                }


                                ?>


                        </div>
                        <script>
                            //    window.livewire.rescan();
                        </script>
                    </div>
                </div>

            @endif
        </div>

    </div>
</div>
